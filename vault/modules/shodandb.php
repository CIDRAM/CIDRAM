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
 *  - "ports_to_detect": ports considered noteworthy/high-risk when found
 *    open on a requesting IP (remote admin, IoT, database ports, etc).
 *    If "flag_any_open_port" is enabled, this list is bypassed entirely
 *    and ANY reported open port counts as a match instead — and in that
 *    case, the "proxy_ports" check below is skipped entirely too, since
 *    any proxy port would already be covered by the "any open port" match.
 *    This category never reports to AbuseIPDB (only blocks/CAPTCHAs/logs
 *    locally) — an open port alone (webserver, game server, TeamSpeak,
 *    etc) isn't a reliable abuse signal on its own.
 *  - "proxy_ports": ports commonly associated with open proxies, SOCKS
 *    servers, or other anonymising relays. Unlike the category above,
 *    this one may also report to AbuseIPDB, controlled independently via
 *    "proxy_report_abuseipdb" (on by default) — a proxy port is a much
 *    stronger abuse signal. The reported message is deliberately generic
 *    and never names the data source (Shodan/InternetDB).
 *
 * Each list has its own independent action, chosen separately:
 *  0 = Detect only (logged/profiled, request is NOT blocked)
 *  1 = Block
 *  2 = CAPTCHA challenge (marks the request eligible for whichever CAPTCHA
 *      backend(s) are selected via the "options" checkbox)
 *
 * TR-069 (CPE WAN Management Protocol) ports (see "tr069_ports", default
 * 7547 and 30005) are whitelisted by default via "tr069_whitelist" — these
 * are routinely open on ordinary residential routers due to normal ISP
 * management and would otherwise cause frequent false positives,
 * especially when "flag_any_open_port" is enabled. See:
 * https://en.wikipedia.org/wiki/TR-069
 *
 * False positive risk (an approximate, rough estimate only): « [ ]Low [x]Medium [ ]High »
 */

/** Safety. */
if (!isset($this->CIDRAM['ModuleResCache'])) {
    $this->CIDRAM['ModuleResCache'] = [];
}

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
    'detect' => $this->CIDRAM['ShodanDBParsePorts']($this->Configuration['shodandb']['ports_to_detect']),
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
        !$this->Configuration['shodandb']['flag_any_open_port'] &&
        !\count($this->CIDRAM['ShodanDBPorts']['detect']) &&
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
                $this->Configuration['shodandb']['timeout_limit'] ?? 12,
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
     * customers, particularly under "flag_any_open_port".
     */
    if ($this->Configuration['shodandb']['tr069_whitelist']) {
        $Ports = \array_diff($Ports, \array_keys($this->CIDRAM['ShodanDBPorts']['tr069']));
        if (!\count($Ports)) {
            return;
        }
    }

    /**
     * Ports of interest (remote admin / IoT / database / etc). If
     * "flag_any_open_port" is enabled, any reported open port counts as a
     * match, regardless of what's configured in "ports_to_detect" (this
     * exists so people don't have to express "any port" as a 1-65535
     * range, which works, but needlessly expands into a 65k-entry array on
     * every single request for no benefit over a plain boolean check).
     */
    $DetectMatches = $this->Configuration['shodandb']['flag_any_open_port']
        ? $Ports
        : \array_intersect($Ports, \array_keys($this->CIDRAM['ShodanDBPorts']['detect']));

    /**
     * Known proxy / relay ports. Skipped when "flag_any_open_port" is
     * enabled — in that mode, any proxy port is already covered by the
     * "any open port" match above, so checking it again here would just
     * double up (both categories firing, and potentially both marking
     * CAPTCHA options, for the very same open port).
     */
    $ProxyMatches = $this->Configuration['shodandb']['flag_any_open_port']
        ? []
        : \array_intersect($Ports, \array_keys($this->CIDRAM['ShodanDBPorts']['proxy']));

    /**
     * Act on ports of interest.
     *
     * Note: no AbuseIPDB reporting happens for this category, by design —
     * an open port here (remote admin, game server, database, etc) is not
     * on its own a reliable indicator of abuse. Plenty of entirely benign
     * IPs run a webserver, game server, TeamSpeak, etc, and would get
     * needlessly reported. This category only ever blocks/CAPTCHAs/logs
     * locally; it never calls Reporter->report(). Proxy ports (below) are
     * a stronger signal and are reported, subject to their own toggle.
     *
     * The message passed to trigger() (visible to the blocked visitor and
     * used for "WhyReason", by default) is deliberately generic — it
     * doesn't name the data source or list the specific matched ports.
     */
    if (\count($DetectMatches)) {
        $Action = $this->Configuration['shodandb']['ports_action'];
        if ($Action === 0) {
            /** Detect only: profile and log, but don't block. */
            $this->addProfileEntry('ShodanDB: port(s) ' . \implode(', ', $DetectMatches), 'ShodanDB module');
        } elseif ($this->trigger(true, 'Open Ports Detected', 'Access was denied because an open port was detected on your connection.')) {
            if ($Action === 2) {
                $this->enactOptions('', \array_flip(\explode("\n", $this->Configuration['shodandb']['options'])));
            }
        }
    }

    /**
     * Act on known proxy ports. Unlike the category above, a proxy port
     * genuinely is a meaningful abuse signal, so AbuseIPDB reporting is
     * offered here — gated by its own toggle ("proxy_report_abuseipdb",
     * on by default) in case it's not wanted.
     *
     * The reported message is deliberately generic (no data source named)
     * to match the visitor-facing message convention used throughout this
     * module.
     */
    if (\count($ProxyMatches)) {
        $Action = $this->Configuration['shodandb']['proxy_ports_action'];
        $DetailedMessage = 'Proxy port(s) detected on this IP: ' . \implode(', ', $ProxyMatches) . '.';
        if ($Action === 0) {
            /** Detect only: profile and log, but don't block. */
            $this->addProfileEntry('ShodanDB: proxy port(s) ' . \implode(', ', $ProxyMatches), 'ShodanDB module');
            if ($this->Configuration['shodandb']['proxy_report_abuseipdb']) {
                $this->Reporter->report([9], [$DetailedMessage], $this->BlockInfo['IPAddr']);
            }
        } elseif ($this->trigger(true, 'Open Proxy Ports Detected', 'Access was denied because an open port was detected on your connection.')) {
            if ($this->Configuration['shodandb']['proxy_report_abuseipdb']) {
                $this->Reporter->report([9], [$DetailedMessage], $this->BlockInfo['IPAddr']);
            }
            if ($Action === 2) {
                $this->enactOptions('', \array_flip(\explode("\n", $this->Configuration['shodandb']['options'])));
            }
        }
    }
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
