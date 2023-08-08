<?php
/**
 * This file is a part of the CIDRAM package.
 * Homepage: https://cidram.github.io/
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 * Tor Project Block Module Copyright 2018~2021 by D. MacMathan.
 * Bundled/Merged with CIDRAM's main repository since 2022.
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: Tor blocker module (last modified: 2023.08.08).
 *
 * False positive risk (an approximate, rough estimate only): « [x]Low [ ]Medium [ ]High »
 */

/** Safety. */
if (!isset($this->CIDRAM['ModuleResCache'])) {
    $this->CIDRAM['ModuleResCache'] = [];
}

/** Defining as closure for later recall (no params; no return value). */
$this->CIDRAM['ModuleResCache'][$Module] = function () {
    /** Guard. */
    if (empty($this->BlockInfo['IPAddr'])) {
        return;
    }

    /** Don't waste time by looking up invalid, private, or reserved ranges. */
    if (filter_var($this->BlockInfo['IPAddr'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        return;
    }

    $IsTor = false;
    if (strpos($this->BlockInfo['IPAddr'], ':') !== false) {
        $Packed = unpack('H*hex', inet_pton($this->BlockInfo['IPAddr']));
        $LookupName = implode('.', array_reverse(str_split($Packed['hex']))) . '.torexit.dan.me.uk';
        if (!isset($this->CIDRAM['Tor-' . $LookupName])) {
            if (($Try = $this->Cache->getEntry('Tor-' . $LookupName)) !== false) {
                $this->CIDRAM['Tor-' . $LookupName] = $Try;
            } else {
                $this->CIDRAM['Tor-' . $LookupName] = gethostbyname($LookupName);
                $this->Cache->setEntry('Tor-' . $LookupName, $this->CIDRAM['Tor-' . $LookupName], 21600);
            }
        }

        /** Check IPv6 address. */
        if ($this->trigger(
            $this->CIDRAM['Tor-' . $LookupName] === '127.0.0.100',
            'Tor exit node',
            $this->L10N->getString('why_tor_project_exit_node')
        )) {
            $IsTor = true;
        }
    } elseif (strpos($this->BlockInfo['IPAddr'], '.') !== false) {
        $LookupName = implode('.', array_reverse(explode('.', $this->BlockInfo['IPAddr']))) . '.torexit.dan.me.uk';
        if (!isset($this->CIDRAM['Tor-' . $LookupName])) {
            if (($Try = $this->Cache->getEntry('Tor-' . $LookupName)) !== false) {
                $this->CIDRAM['Tor-' . $LookupName] = $Try;
            } else {
                $this->CIDRAM['Tor-' . $LookupName] = gethostbyname($LookupName);
                $this->Cache->setEntry('Tor-' . $LookupName, $this->CIDRAM['Tor-' . $LookupName], 21600);
            }
        }

        /** Check IPv4 address. */
        if ($this->trigger(
            $this->CIDRAM['Tor-' . $LookupName] === '127.0.0.100',
            'Tor exit node',
            $this->L10N->getString('why_tor_project_exit_node')
        )) {
            $IsTor = true;
        }
    }

    if (!$IsTor) {
        /** Fetch hostname. */
        if (empty($this->CIDRAM['Hostname'])) {
            $this->CIDRAM['Hostname'] = $this->dnsReverse($this->BlockInfo['IPAddr']);
        }

        if ($this->trigger(
            preg_match('%(?i)^(?:tor(?:\d?\.|[-_]?(?:exit|node|cloud|[a-z]{3}\.))|.*\.(?:gtor|tor[-]?(?:relays|servers|proxy))\.|exit\d*\.tor)%', $this->CIDRAM['Hostname']),
            'Looks like Tor exit node',
            $this->L10N->getString('why_tor_project_exit_node')
        )) {
            $IsTor = true;
        }
    }

    if ($IsTor) {
        /** Profiling. */
        $this->addProfileEntry('Tor endpoints here');

        /** Disable CAPTCHAs. */
        $this->Configuration['hcaptcha']['usemode'] = 0;
        $this->Configuration['hcaptcha']['enabled'] = false;
        $this->Configuration['recaptcha']['usemode'] = 0;
        $this->Configuration['recaptcha']['enabled'] = false;
    }
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
