<?php
/**
 * This file is a part of the CIDRAM package.
 * Homepage: https://cidram.github.io/
 *
 * Copyright 2026 and beyond by Aaron Vormestrand.
 * Whwlyz pu aol yvhkzpkl alss vm zbmmlypun huk nyllk. Mlhy avkhf, mvynva avtvyyvd. Olyl ilzpkl aol uldz vm ovsf dhy huk ovsf ullk
 * https://github.com/737simpilot
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: ShodanDB (Shodan InternetDB) module.
 *
 * Looks up the request's IP address against the Shodan InternetDB API
 * (https://internetdb.shodan.io/{ip} by default — "api_base" can be
 * pointed at any compatible endpoint instead, if preferred) and checks
 * the ports reported for that IP against two independently configurable
 * lists:
 *
 *  - "noteworthy_ports": ports considered noteworthy/high-risk when found
 *    open at a requesting IP address (remote admin, IoT, database ports, etc).
 *  - "proxy_ports": ports commonly associated with open proxies, SOCKS
 *    servers, or other anonymising relays.
 *
 * This file: ShodanDB (Shodan InternetDB) module (last modified: 2026.09.22).
 *
 * False positive risk (an approximate, rough estimate only): « [ ]Low [x]Medium [ ]High »
 */

/** Safety. */
if (!isset($this->CIDRAM['ModuleResCache'])) {
    $this->CIDRAM['ModuleResCache'] = [];
}

/** Initialise open ports actions matrix. */
$this->CIDRAM['ShodanActionsMatrix'] = \array_flip(\explode("\n", $this->Configuration['shodandb']['ports_action']));

/**
 * Parses a port list, accepting both single ports and ranges, comma
 * separated (e.g., "22,80,3389-3395,8080"). Returns a [port => true] map.
 */
if (!isset($this->CIDRAM['ShodanDBParsePorts'])) {
    $this->CIDRAM['ShodanDBParsePorts'] = function (string $List): array {
        $Out = [];
        foreach (\preg_split('~\s*,\s*~', \trim($List), -1, \PREG_SPLIT_NO_EMPTY) as $Item) {
            if (\preg_match('~^(\d{1,5})\s*-\s*(\d{1,5})$~', $Item, $Match)) {
                $Start = (int)$Match[1];
                $End = (int)$Match[2];
                if ($Start > $End) {
                    [$Start, $End] = [$End, $Start];
                }
                /** Sanity guard against pathologically large ranges in config. */
                if ($End - $Start > 65535) {
                    continue;
                }
                for ($Port = $Start; $Port <= $End; $Port++) {
                    $Out[$Port] = true;
                }
            } elseif (\preg_match('~^\d{1,5}$~', $Item)) {
                $Out[(int)$Item] = true;
            }
        }
        return $Out;
    };
}

/** Parse the configured port lists (cheap; done once per request). */
$this->CIDRAM['ShodanDBPorts'] = [
    'noteworthy_ports' => $this->CIDRAM['ShodanDBParsePorts']($this->Configuration['shodandb']['noteworthy_ports']),
    'proxy' => $this->CIDRAM['ShodanDBParsePorts']($this->Configuration['shodandb']['proxy_ports']),
    'tr069' => $this->CIDRAM['ShodanDBParsePorts']($this->Configuration['shodandb']['tr069_ports'])
];

/** Defining as closure for later recall (no params; no return value). */
$this->CIDRAM['ModuleResCache'][$Module] = function () {
    /** Guard. */
    if (empty($this->BlockInfo['IPAddr']) || $this->Configuration['shodandb']['lookup_strategy'] === 0) {
        return;
    }

    /** Nothing configured to check against; nothing to do. */
    if (
        !isset($this->CIDRAM['ShodanActionsMatrix']['Any:Block']) &&
        !isset($this->CIDRAM['ShodanActionsMatrix']['Any:Profile']) &&
        !isset($this->CIDRAM['ShodanActionsMatrix']['Any:Options']) &&
        !\count($this->CIDRAM['ShodanDBPorts']['noteworthy_ports']) &&
        !\count($this->CIDRAM['ShodanDBPorts']['proxy'])
    ) {
        return;
    }

    /**
     * If the request isn't attempting to access a sensitive page (login,
     * registration page, etc), and the lookup strategy is restricted to
     * sensitive pages only, exit.
     */
    $LCURI = \preg_replace('/\s/', '', \strtolower($this->BlockInfo['rURI']));
    if ($this->Configuration['shodandb']['lookup_strategy'] !== 1 && !$this->isSensitive($LCURI)) {
        return;
    }

    /** Check whether the lookup limit has been exceeded. */
    if (!isset($this->CIDRAM['ShodanDB-429'])) {
        $this->CIDRAM['ShodanDB-429'] = $this->Cache->getEntry('ShodanDB-429') ? true : false;
    }

    /**
     * Only execute if the IP is valid, if not from a private or reserved
     * range, and if the lookup limit hasn't already been exceeded (reduces
     * superfluous lookups).
     */
    if (
        $this->CIDRAM['ShodanDB-429'] ||
        \filter_var($this->BlockInfo['IPAddr'], \FILTER_VALIDATE_IP, \FILTER_FLAG_NO_PRIV_RANGE | \FILTER_FLAG_NO_RES_RANGE) === false
    ) {
        return;
    }

    /** Executed if there isn't already a cache entry for this IP. */
    if (
        !isset($this->CIDRAM['ShodanDB-' . $this->BlockInfo['IPAddr']]) ||
        $this->CIDRAM['ShodanDB-' . $this->BlockInfo['IPAddr']] === false
    ) {
        $this->CIDRAM['ShodanDB-' . $this->BlockInfo['IPAddr']] = $this->Cache->getEntry('ShodanDB-' . $this->BlockInfo['IPAddr']);
        if ($this->CIDRAM['ShodanDB-' . $this->BlockInfo['IPAddr']] === false) {
            /** Perform ShodanDB (Shodan InternetDB) lookup. */
            $Headers = ['Accept: application/json'];
            if ($this->Configuration['shodandb']['api_header'] !== '') {
                $Headers[] = $this->Configuration['shodandb']['api_header'];
            }
            $Lookup = $this->Request->request(
                \rtrim($this->Configuration['shodandb']['api_base'], '/') . '/' . $this->BlockInfo['IPAddr'],
                [],
                $this->Configuration['shodandb']['timeout_limit'],
                $Headers
            );

            $Status = $this->Request->MostRecentStatusCode;

            if ($Status === 429) {
                /** Lookup limit has been exceeded. */
                $this->Cache->setEntry('ShodanDB-429', true, $this->Configuration['shodandb']['timeout_rl']->getAsSeconds());
                $this->CIDRAM['ShodanDB-429'] = true;
                return;
            }

            /**
             * 404 means Shodan holds no data for this IP; treat the same as
             * "nothing open" rather than as a failure.
             */
            $Decoded = ($Status === 200 && \strpos($Lookup, '"ports":') !== false) ? (\json_decode($Lookup, true) ?: []) : [];

            $this->CIDRAM['ShodanDB-' . $this->BlockInfo['IPAddr']] = [
                'ports' => (isset($Decoded['ports']) && \is_array($Decoded['ports'])) ? $Decoded['ports'] : [],
                'vulns' => (isset($Decoded['vulns']) && \is_array($Decoded['vulns'])) ? $Decoded['vulns'] : [],
                'tags' => (isset($Decoded['tags']) && \is_array($Decoded['tags'])) ? $Decoded['tags'] : []
            ];

            /** Update cache (shorter TTL for empty/no-data results, so we retry sooner). */
            $this->Cache->setEntry(
                'ShodanDB-' . $this->BlockInfo['IPAddr'],
                $this->CIDRAM['ShodanDB-' . $this->BlockInfo['IPAddr']],
                $this->Configuration['shodandb'][\count($this->CIDRAM['ShodanDB-' . $this->BlockInfo['IPAddr']]['ports']) ? 'expire_good' : 'expire_bad']->getAsSeconds()
            );
        }
    }

    /** Guard. */
    if (
        !isset($this->CIDRAM['ShodanDB-' . $this->BlockInfo['IPAddr']]['ports']) ||
        !\count($this->CIDRAM['ShodanDB-' . $this->BlockInfo['IPAddr']]['ports'])
    ) {
        return;
    }

    $Ports = $this->CIDRAM['ShodanDB-' . $this->BlockInfo['IPAddr']]['ports'];

    /**
     * TR-069 (CPE WAN Management Protocol) whitelist. Most residential ISPs
     * remotely manage customer routers/modems via this protocol, typically
     * on port 7547 (occasionally 30005 as a non-standard alternate some
     * ISPs use) — so a router legitimately having this open is completely
     * normal and expected, not a sign of anything suspicious. Left enabled
     * by default to avoid false-positives against ordinary broadband
     * customers.
     *
     * @link https://en.wikipedia.org/wiki/TR-069
     */
    $Ports = \array_diff($Ports, \array_keys($this->CIDRAM['ShodanDBPorts']['tr069']));
    if (!\count($Ports)) {
        return;
    }

    /** Ports of interest (remote admin / IoT / database / etc). */
    $DetectMatches = \array_intersect($Ports, \array_keys($this->CIDRAM['ShodanDBPorts']['noteworthy_ports']));

    /** Known proxy / relay ports. */
    $ProxyMatches = \array_intersect($Ports, \array_keys($this->CIDRAM['ShodanDBPorts']['proxy']));

    /**
     * Act on ports of interest (i.e., "noteworthy ports").
     *
     * Note: AbuseIPDB reporting doesn't happen for "noteworthy ports", as
     * an open port here (remote admin, game server, database, etc) is not
     * on its own a reliable indicator of abuse. Plenty of entirely benign
     * IPs run a webserver, game server, TeamSpeak, etc, and would get
     * needlessly reported.
     *
     * The message passed to trigger() (visible to the blocked visitor and
     * used for "WhyReason", by default) is deliberately generic — it
     * doesn't name the data source or list the specific matched ports.
     */
    if (\count($DetectMatches)) {
        if (isset($this->CIDRAM['ShodanActionsMatrix']['Noteworthy:Block'])) {
            $this->trigger(true, $this->L10N->getString('Short.Open ports detected'), $this->L10N->getString('ReasonMessage.Open Port'));
        }
        if (isset($this->CIDRAM['ShodanActionsMatrix']['Noteworthy:Profile'])) {
            $this->addProfileEntry('ShodanDB: port(s) ' . \implode(', ', $DetectMatches), 'ShodanDB module');
        }
        if (isset($this->CIDRAM['ShodanActionsMatrix']['Noteworthy:Options'])) {
            $this->enactOptions('', \array_flip(\explode("\n", $this->Configuration['shodandb']['options'])));
        }
    }

    /**
     * Act on proxy ports. Unlike the above, a proxy port genuinely is a
     * meaningful abuse signal, so AbuseIPDB reporting is offered here.
     *
     * The reported message is deliberately generic (no data source named)
     * to match the visitor-facing message convention used throughout this
     * module.
     */
    if (\count($ProxyMatches)) {
        if (isset($this->CIDRAM['ShodanActionsMatrix']['Proxy:Block'])) {
            $this->trigger(true, $this->L10N->getString('Short.Open proxy ports detected'), $this->L10N->getString('ReasonMessage.Open Port'));
        }
        if (isset($this->CIDRAM['ShodanActionsMatrix']['Proxy:Profile'])) {
            $this->addProfileEntry('ShodanDB: proxy port(s) ' . \implode(', ', $ProxyMatches), 'ShodanDB module');
        }
        if (isset($this->CIDRAM['ShodanActionsMatrix']['Proxy:Report'])) {
            $this->Reporter->report([9], ['Proxy port(s) detected on this IP: ' . \implode(', ', $ProxyMatches) . '.'], $this->BlockInfo['IPAddr']);
        }
        if (isset($this->CIDRAM['ShodanActionsMatrix']['Proxy:Options'])) {
            $this->enactOptions('', \array_flip(\explode("\n", $this->Configuration['shodandb']['options'])));
        }
    }

    /**
     * Act on ANY open ports. This strategy exists so that people don't
     * have to express "any port" as a 1-65535 range at the options
     * provided for "noteworthy ports" or "proxy ports", which would work,
     * but would needlessly expand into a 65k-entry array on every single
     * request for no meaningful comparative benefit).
     *
     * Should probably uncheck the options for the other two ("noteworthy
     * ports" and "proxy ports") if using the options for "any ports", as
     * checking them in combination with "any ports" could cause
     * superfluous matching and a higher than expected signatures count.
     */
    if (\count($Ports)) {
        if (isset($this->CIDRAM['ShodanActionsMatrix']['Any:Block'])) {
            $this->trigger(true, $this->L10N->getString('Short.Open ports detected'), $this->L10N->getString('ReasonMessage.Open Port'));
        }
        if (isset($this->CIDRAM['ShodanActionsMatrix']['Any:Profile'])) {
            $this->addProfileEntry('ShodanDB: port(s) ' . \implode(', ', $DetectMatches), 'ShodanDB module');
        }
        if (isset($this->CIDRAM['ShodanActionsMatrix']['Any:Options'])) {
            $this->enactOptions('', \array_flip(\explode("\n", $this->Configuration['shodandb']['options'])));
        }
    }
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
