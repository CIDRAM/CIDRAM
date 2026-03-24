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
 * This file: Quic cloud compatibility module (last modified: 2026.03.24).
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

    /** Fetch Quic cloud IP list. */
    if (!isset($this->CIDRAM['Quic'])) {
        $this->CIDRAM['Quic'] = $this->Cache->getEntry('Quic');
        if ($this->CIDRAM['Quic'] === false) {
            $this->CIDRAM['Quic'] = $this->Request->request('https://www.quic.cloud/ips?ln') ?: '';
            $this->Cache->setEntry('Quic', $this->CIDRAM['Quic'], 345600);
        }
    }

    /** Converts the raw data from the Quic cloud API to an array. */
    $IPList = \array_filter(\explode("\n", $this->CIDRAM['Quic']));

    /** Execute configured action for positive matches against the Quic cloud IP list. */
    if (\is_array($IPList) && \in_array($this->BlockInfo['IPAddr'], $IPList, true)) {
        /** Prevents search engine and social media verification. */
        $this->CIDRAM['SkipVerification'] = true;

        /** Profiling. */
        $this->addProfileEntry('Content Delivery Network');

        /** Bypass the request. */
        if ($this->Configuration['quic']['positive_action'] === 'bypass') {
            $this->bypass($this->BlockInfo['SignatureCount'] > 0, 'Quic cloud bypass');
            return;
        }

        /** Greylist the request. */
        if ($this->Configuration['quic']['positive_action'] === 'greylist') {
            $this->ZeroOutBlockInfo();
            return;
        }

        /** Whitelist the request. */
        if ($this->Configuration['quic']['positive_action'] === 'whitelist') {
            $this->ZeroOutBlockInfo(true);
            return;
        }
    }
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
