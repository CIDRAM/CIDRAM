<?php
/**
 * This file is a part of the CIDRAM package.
 * Homepage: https://cidram.github.io/
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: Bad TLDs blocker module (last modified: 2022.05.18).
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

    /** Don't continue if compatibility indicators exist. */
    if (strpos($this->BlockInfo['Signatures'], 'compat_bunnycdn.php') !== false) {
        return;
    }

    /** Fetch hostname. */
    if (empty($this->CIDRAM['Hostname'])) {
        $this->CIDRAM['Hostname'] = dnsReverse($this->BlockInfo['IPAddr']);
    }

    /** Safety mechanism against false positives caused by failed lookups. */
    if (
        !$this->CIDRAM['Hostname'] ||
        $this->CIDRAM['Hostname'] === $this->BlockInfo['IPAddr'] ||
        preg_match('~^b\.in-addr-servers\.nstld~', $this->CIDRAM['Hostname'])
    ) {
        return;
    }

    $this->trigger(preg_match(
        '~\.(?:bid|click|club?|country|cricket|date|diet|domain|download|fai' .
        'th|gdn|gq|kim|link|men|museum|party|racing|review|science|stream|to' .
        'kyo|top|webcam|website|win|work|xyz|yokohama|zip)$~i',
        $this->CIDRAM['Hostname']
    ), 'Disreputable TLD'); // 2018.04.08

    $this->trigger(preg_match('~\.onion$~i', $this->CIDRAM['Hostname']), 'Anonymous/Unroutable TLD'); // 2017.12.28
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
