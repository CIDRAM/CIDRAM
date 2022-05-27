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
 * This file: BGPView module (last modified: 2022.05.27).
 *
 * False positive risk (an approximate, rough estimate only): « [x]Low [ ]Medium [ ]High »
 */

/** Safety. */
if (!isset($this->CIDRAM['ModuleResCache'])) {
    $this->CIDRAM['ModuleResCache'] = [];
}

/** Initialise BGPView module  information. */
$this->CIDRAM['BGPConfig'] = [
    'blocked_asns' => array_flip(explode("\n", $this->Configuration['bgpview']['blocked_asns'])),
    'whitelisted_asns' => array_flip(explode("\n", $this->Configuration['bgpview']['whitelisted_asns'])),
    'blocked_ccs' => array_flip(explode("\n", $this->Configuration['bgpview']['blocked_ccs'])),
    'whitelisted_ccs' => array_flip(explode("\n", $this->Configuration['bgpview']['whitelisted_ccs']))
];

/** Defining as closure for later recall (no params; no return value). */
$this->CIDRAM['ModuleResCache'][$Module] = function () {
    /** Guard. */
    if (empty($this->BlockInfo['IPAddr'])) {
        return;
    }

    /** Local BGPView cache entry expiry time (successful lookups). */
    $Expiry = $this->Now + 604800;

    /** Build local BGPView cache if it doesn't already exist. */
    $this->initialiseCacheSection('BGPView');

    $InCache = false;

    /** Expand factors for this origin. */
    $Expanded = [$this->expandIpv4($this->BlockInfo['IPAddr']), $this->expandIpv6($this->BlockInfo['IPAddr'])];

    /** Check whether we've already performed a lookup for this origin. */
    foreach ($Expanded as $Factors) {
        if (!is_array($Factors)) {
            continue;
        }
        foreach ($Factors as $Factor) {
            if (!isset($this->CIDRAM['BGPView'][$Factor])) {
                continue;
            }
            $InCache = true;
            break 2;
        }
    }

    /** Prepare to perform a new lookup if none for this origin have been cached yet. */
    if (!$InCache) {
        $Lookup = $this->Request->request(
            'https://api.bgpview.io/ip/' . $this->BlockInfo['IPAddr'],
            [],
            $this->Configuration['bgpview']['timeout_limit'] ?? 12
        );
        $Lookup = (
            substr($Lookup, 0, 63) === '{"status":"ok","status_message":"Query was successful","data":{' &&
            substr($Lookup, -2) === '}}'
        ) ? json_decode($Lookup, true) : false;
        $Low = strpos($this->BlockInfo['IPAddr'], ':') !== false ? 128 : 32;
        $this->CIDRAM['BGPView'][$this->BlockInfo['IPAddr'] . '/' . $Low] = ['ASN' => -1, 'CC' => 'XX', 'Time' => $Expiry];
        $this->CIDRAM['BGPView-Modified'] = true;
        if (is_array($Lookup) && isset($Lookup['data'])) {
            if (isset($Lookup['data']['prefixes']) && is_array($Lookup['data']['prefixes'])) {
                foreach ($Lookup['data']['prefixes'] as $Prefix) {
                    $Factor = isset($Prefix['prefix']) ? $Prefix['prefix'] : false;
                    $ASN = isset($Prefix['asn']['asn']) ? $Prefix['asn']['asn'] : false;
                    $CC = isset($Prefix['asn']['country_code']) ? $Prefix['asn']['country_code'] : 'XX';
                    if ($Factor && $ASN) {
                        $this->CIDRAM['BGPView'][$Factor] = ['ASN' => $ASN, 'CC' => $CC, 'Time' => $Expiry];
                    }
                }
            }
            if (
                isset($Lookup['data']['rir_allocation']) &&
                is_array($Lookup['data']['rir_allocation']) &&
                isset($Lookup['data']['rir_allocation']['country_code'], $Lookup['data']['rir_allocation']['prefix'])
            ) {
                $Prefix = $Lookup['data']['rir_allocation']['prefix'];
                if (isset($this->CIDRAM['BGPView'][$Prefix])) {
                    $this->CIDRAM['BGPView'][$Prefix]['CC'] = $Lookup['data']['rir_allocation']['country_code'];
                } else {
                    $this->CIDRAM['BGPView'][$Prefix] = ['CC' => $Lookup['data']['rir_allocation']['country_code']];
                }
            }
        }
    }

    /** Process lookup results for this origin and act as per configured. */
    foreach ($Expanded as $Factors) {
        if (!is_array($Factors)) {
            continue;
        }
        foreach ($Factors as $Factor) {
            if (!isset($this->CIDRAM['BGPView'][$Factor])) {
                continue;
            }

            /** Act based on ASN. */
            if (!empty($this->CIDRAM['BGPView'][$Factor]['ASN'])) {
                /** Populate ASN lookup information. */
                if ($this->CIDRAM['BGPView'][$Factor]['ASN'] > 0) {
                    $this->BlockInfo['ASNLookup'] = $this->CIDRAM['BGPView'][$Factor]['ASN'];
                }

                /** Origin is whitelisted. */
                if (preg_match('~(?:^|\n)' . preg_quote($this->CIDRAM['BGPView'][$Factor]['ASN']) . '(?:$|\n)~', $this->CIDRAM['BGPConfig']['whitelisted_asns'])) {
                    $this->ZeroOutBlockInfo(true);
                    break 2;
                }

                /** Origin is blocked. */
                if (preg_match('~(?:^|\n)' . preg_quote($this->CIDRAM['BGPView'][$Factor]['ASN']) . '(?:$|\n)~', $this->CIDRAM['BGPConfig']['blocked_asns'])) {
                    $this->BlockInfo['ReasonMessage'] = $this->L10N->getString('ReasonMessage_Generic');
                    if (!empty($this->BlockInfo['WhyReason'])) {
                        $this->BlockInfo['WhyReason'] .= ', ';
                    }
                    $this->BlockInfo['WhyReason'] .= sprintf(
                        '%s (BGPView, "%d")',
                        $this->L10N->getString('Short_Generic'),
                        $this->CIDRAM['BGPView'][$Factor]['ASN']
                    );
                    if (!empty($this->BlockInfo['Signatures'])) {
                        $this->BlockInfo['Signatures'] .= ', ';
                    }
                    $this->BlockInfo['Signatures'] .= $Factor;
                    $this->BlockInfo['SignatureCount']++;
                }
            }

            /** Act based on CC. */
            if (!empty($this->CIDRAM['BGPView'][$Factor]['CC']) && empty($CCDone)) {
                /** Populate country code lookup information. */
                if ($this->CIDRAM['BGPView'][$Factor]['CC'] !== 'XX') {
                    $this->BlockInfo['CCLookup'] = $this->CIDRAM['BGPView'][$Factor]['CC'];
                    $CCDone = true;
                }

                /** Origin is whitelisted. */
                if (preg_match('~(?:^|\n)' . preg_quote($this->CIDRAM['BGPView'][$Factor]['CC']) . '(?:$|\n)~', $this->CIDRAM['BGPConfig']['whitelisted_ccs'])) {
                    $this->ZeroOutBlockInfo(true);
                    break 2;
                }

                /** Origin is blocked. */
                if (preg_match('~(?:^|\n)' . preg_quote($this->CIDRAM['BGPView'][$Factor]['CC']) . '(?:$|\n)~', $this->CIDRAM['BGPConfig']['blocked_ccs'])) {
                    $this->BlockInfo['ReasonMessage'] = sprintf(
                        $this->L10N->getString('why_no_access_allowed_from'),
                        $this->CIDRAM['BGPView'][$Factor]['CC']
                    );
                    if (!empty($this->BlockInfo['WhyReason'])) {
                        $this->BlockInfo['WhyReason'] .= ', ';
                    }
                    $this->BlockInfo['WhyReason'] .= sprintf('CC (BGPView, "%s")', $this->CIDRAM['BGPView'][$Factor]['CC']);
                    if (!empty($this->BlockInfo['Signatures'])) {
                        $this->BlockInfo['Signatures'] .= ', ';
                    }
                    $this->BlockInfo['Signatures'] .= $Factor;
                    $this->BlockInfo['SignatureCount']++;
                }
            }
        }
    }
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
