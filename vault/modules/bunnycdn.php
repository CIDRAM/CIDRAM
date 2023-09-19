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
 * This file: BunnyCDN compatibility module (last modified: 2023.09.19).
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

    /** Fetch BunnyCDN IP list. */
    if (!isset($this->CIDRAM['BunnyCDN'])) {
        $this->CIDRAM['BunnyCDN'] = $this->Cache->getEntry('BunnyCDN');
        if ($this->CIDRAM['BunnyCDN'] === false) {
            $this->CIDRAM['BunnyCDN'] = $this->Request->request('https://bunnycdn.com/api/system/edgeserverlist') ?: '';
            $this->Cache->setEntry('BunnyCDN', $this->CIDRAM['BunnyCDN'], 345600);
        }
    }

    /** Converts the raw data from the BunnyCDN API to an array. */
    $IPList = (substr($this->CIDRAM['BunnyCDN'], 0, 1) === '<') ? array_filter(
        explode('<>', preg_replace('~<[^<>]+>~', '<>', $this->CIDRAM['BunnyCDN']))
    ) : (array_filter(
        explode(',', preg_replace('~["\'\[\]]~', '', $this->CIDRAM['BunnyCDN']))
    ) ?: '');

    /** Execute configured action for positive matches against the BunnyCDN IP list. */
    if (is_array($IPList) && in_array($this->BlockInfo['IPAddr'], $IPList, true)) {
        /** Prevents search engine and social media verification. */
        $this->CIDRAM['SkipVerification'] = true;

        /** Profiling. */
        $this->addProfileEntry('Content Delivery Network');

        /** Bypass the request. */
        if ($this->Configuration['bunnycdn']['positive_action'] === 'bypass') {
            $this->bypass($this->BlockInfo['SignatureCount'] > 0, 'BunnyCDN bypass');
            return;
        }

        /** Greylist the request. */
        if ($this->Configuration['bunnycdn']['positive_action'] === 'greylist') {
            $this->ZeroOutBlockInfo();
            return;
        }

        /** Whitelist the request. */
        if ($this->Configuration['bunnycdn']['positive_action'] === 'whitelist') {
            $this->ZeroOutBlockInfo(true);
            return;
        }
    }
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
