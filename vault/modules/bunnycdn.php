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
 * This file: BunnyCDN compatibility module (last modified: 2022.05.18).
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

    /** Instantiate API cache. */
    $this->initialiseCacheSection('API');

    /** Fetch BunnyCDN IP list. */
    if (!isset($this->CIDRAM['API']['BunnyCDN'], $this->CIDRAM['API']['BunnyCDN']['Data'])) {
        $this->CIDRAM['API']['BunnyCDN'] = [
            'Data' => $this->Request->request('https://bunnycdn.com/api/system/edgeserverlist') ?: '',
            'Time' => $this->Now + 345600
        ];
        $this->CIDRAM['API-Modified'] = true;
    }

    /** Converts the raw data from the BunnyCDN API to an array. */
    $IPList = (substr($this->CIDRAM['API']['BunnyCDN']['Data'], 0, 1) === '<') ? array_filter(
        explode('<>', preg_replace('~<[^<>]+>~', '<>', $this->CIDRAM['API']['BunnyCDN']['Data']))
    ) : (array_filter(
        explode(',', preg_replace('~["\'\[\]]~', '', $this->CIDRAM['API']['BunnyCDN']['Data']))
    ) ?: '');

    /** Execute configured action for positive matches against the BunnyCDN IP list. */
    if (is_array($IPList) && in_array($this->BlockInfo['IPAddr'], $IPList, true)) {
        /** Prevents search engine and social media verification. */
        $this->CIDRAM['SkipVerification'] = true;

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
