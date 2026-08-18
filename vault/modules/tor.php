<?php
/**
 * This file is a part of the CIDRAM package.
 * Homepage: https://cidram.github.io/
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 * Tor blocker module COPYRIGHT 2018~2021 by D. MacMathan.
 * Bundled/Merged with CIDRAM's main repository since 2022.
 * Rewritten in 2026 to use lists instead of DNSEL.
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: Tor blocker module (last modified: 2026.08.18).
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

    /**
     * Normalised, lower-cased request URI; Used to determine whether the
     * module needs to do anything for the request.
     */
    $LCURI = \preg_replace('/\s/', '', \strtolower($this->BlockInfo['rURI']));

    /**
     * If the request isn't attempting to access a sensitive page (login,
     * registration page, etc), exit.
     */
    if ($this->Configuration['tor']['lookup_strategy'] !== 1 && !$this->isSensitive($LCURI)) {
        return;
    }

    /**
     * Only execute if not already blocked for some other reason, if the IP is
     * valid, and not from a private or reserved range.
     */
    if (!$this->honourLookup() || \filter_var($this->BlockInfo['IPAddr'], \FILTER_VALIDATE_IP, \FILTER_FLAG_NO_PRIV_RANGE | \FILTER_FLAG_NO_RES_RANGE) === false) {
        return;
    }

    /** Fetch tor exit nodes list. */
    if (!isset($this->CIDRAM['TorExitNodes'])) {
        $this->CIDRAM['TorExitNodes'] = $this->Cache->getEntry('TorExitNodes');
        if ($this->CIDRAM['TorExitNodes'] === false) {
            $this->CIDRAM['TorExitNodes'] = $this->Request->request('https://www.dan.me.uk/torlist/?exit', [], $this->Configuration['tor']['timeout_limit']) ?: '';
            $this->Cache->setEntry('TorExitNodes', $this->CIDRAM['TorExitNodes'], 21600);
        }
    }

    /** Check whether the IP address of the request is on the list. */
    $IsTor = $this->trigger(\strpos("\n" . $this->CIDRAM['TorExitNodes'] . "\n", $this->BlockInfo['IPAddr']) !== false, 'Tor exit node', $this->L10N->getString('why_tor_project_exit_node'));

    if (!$IsTor) {
        /** Fetch hostname. */
        if (empty($this->CIDRAM['Hostname'])) {
            $this->CIDRAM['Hostname'] = $this->dnsReverse($this->BlockInfo['IPAddr']);
        }

        $IsTor = $this->trigger(
            \preg_match('%(?i)^(?:tor(?:\d?\.|[-_]?(?:exit|node|cloud|[a-z]{3}\.))|.*\.(?:gtor|tor[-]?(?:relays|servers|proxy))\.|exit\d*\.tor)%', $this->CIDRAM['Hostname']),
            'Looks like Tor exit node',
            $this->L10N->getString('why_tor_project_exit_node')
        );
    }

    if ($IsTor) {
        /** Profiling. */
        $this->addProfileEntry('Tor endpoints here', 'Tor blocker module');

        /** Fetch options. */
        $this->enactOptions('', \array_flip(\explode("\n", $this->Configuration['tor']['options'])));
    }
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
