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
 * This file: BGPView module (last modified: 2023.03.28).
 *
 * False positive risk (an approximate, rough estimate only): « [x]Low [ ]Medium [ ]High »
 */

/** Safety. */
if (!isset($this->CIDRAM['ModuleResCache'])) {
    $this->CIDRAM['ModuleResCache'] = [];
}

/** Initialise BGPView module information. */
$this->CIDRAM['BGPConfig'] = [
    'blocked_asns' => array_flip(preg_split('~[\s,]~', $this->Configuration['bgpview']['blocked_asns'], -1, PREG_SPLIT_NO_EMPTY)),
    'whitelisted_asns' => array_flip(preg_split('~[\s,]~', $this->Configuration['bgpview']['whitelisted_asns'], -1, PREG_SPLIT_NO_EMPTY)),
    'blocked_ccs' => array_flip(preg_split('~[\s,]~', $this->Configuration['bgpview']['blocked_ccs'], -1, PREG_SPLIT_NO_EMPTY)),
    'whitelisted_ccs' => array_flip(preg_split('~[\s,]~', $this->Configuration['bgpview']['whitelisted_ccs'], -1, PREG_SPLIT_NO_EMPTY))
];

/** Defining as closure for later recall (no params; no return value). */
$this->CIDRAM['ModuleResCache'][$Module] = function () {
    /** Guard. */
    if (empty($this->BlockInfo['IPAddr'])) {
        return;
    }

    $InCache = false;

    /** Check whether the lookup limit has been exceeded. */
    if (!isset($this->CIDRAM['BGPView-429'])) {
        $this->CIDRAM['BGPView-429'] = $this->Cache->getEntry('BGPView-429') ? true : false;
    }

    /**
     * Only execute if the IP is valid, if not from a private or reserved
     * range, and if the lookup limit hasn't already been exceeded (reduces
     * superfluous lookups).
     */
    if (
        $this->CIDRAM['BGPView-429'] ||
        filter_var($this->BlockInfo['IPAddr'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false
    ) {
        return;
    }

    /** Expand factors for this origin. */
    $Expanded = [$this->expandIpv4($this->BlockInfo['IPAddr']), $this->expandIpv6($this->BlockInfo['IPAddr'])];
    $ToCheck = [];
    if (is_array($Expanded[0]) && count($Expanded[0]) === 32) {
        $ToCheck[] = array_slice($Expanded[0], 23);
    }
    if (is_array($Expanded[1]) && count($Expanded[1]) === 128) {
        $ToCheck[] = array_slice($Expanded[1], 47);
    }

    /** Check whether we've already performed a lookup for this origin. */
    foreach ($ToCheck as $Factors) {
        foreach ($Factors as $Factor) {
            if (!isset($this->CIDRAM['BGPView-' . $Factor])) {
                $this->CIDRAM['BGPView-' . $Factor] = $this->Cache->getEntry('BGPView-' . $Factor);
            }
            if ($this->CIDRAM['BGPView-' . $Factor] === false) {
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

        if ($this->Request->MostRecentStatusCode !== 200) {
            /** Lookup limit has been exceeded. */
            $this->Cache->setEntry('BGPView-429', true, $this->Configuration['bgpview']['timeout_rl']->getAsSeconds());
            $this->CIDRAM['BGPView-429'] = true;
            return;
        }

        $Lookup = (
            substr($Lookup, 0, 63) === '{"status":"ok","status_message":"Query was successful","data":{' &&
            substr($Lookup, -2) === '}}'
        ) ? json_decode($Lookup, true) : false;
        $Low = strpos($this->BlockInfo['IPAddr'], ':') !== false ? 128 : 32;
        $this->Cache->setEntry('BGPView-' . $this->BlockInfo['IPAddr'] . '/' . $Low, ['ASN' => 0, 'CC' => 'XX'], 604800);

        /** Lookup failed. */
        if (!is_array($Lookup) || !isset($Lookup['data'])) {
            return;
        }

        $TryForRir = (
            isset($Lookup['data']['rir_allocation']) &&
            is_array($Lookup['data']['rir_allocation']) &&
            isset($Lookup['data']['rir_allocation']['prefix'])
        ) ? $Lookup['data']['rir_allocation']['prefix'] : '';
        if (isset($Lookup['data']['prefixes']) && is_array($Lookup['data']['prefixes'])) {
            foreach ($Lookup['data']['prefixes'] as $Prefix) {
                $Factor = $Prefix['prefix'] ?? '';
                $ASN = $Prefix['asn']['asn'] ?? '';
                $CC = $Prefix['asn']['country_code'] ?? 'XX';
                if ($Factor && $ASN) {
                    if ($TryForRir === $Factor) {
                        $CC = $Lookup['data']['rir_allocation']['country_code'] ?? $CC;
                        $TryForRir = '';
                    }
                    $this->CIDRAM['BGPView-' . $Factor] = ['ASN' => $ASN, 'CC' => $CC];
                    $this->Cache->setEntry('BGPView-' . $Factor, ['ASN' => $ASN, 'CC' => $CC], 604800);
                }
            }
        }
        if ($TryForRir !== '') {
            $this->CIDRAM['BGPView-' . $TryForRir] = ['CC' => $Lookup['data']['rir_allocation']['country_code'] ?? 'XX'];
            $this->Cache->setEntry('BGPView-' . $TryForRir, $this->CIDRAM['BGPView-' . $TryForRir], 604800);
        }
    }

    /** Process lookup results for this origin and act as per configured. */
    foreach ($Expanded as $Factors) {
        if (!is_array($Factors)) {
            continue;
        }
        foreach ($Factors as $Factor) {
            if (!isset($this->CIDRAM['BGPView-' . $Factor])) {
                $this->CIDRAM['BGPView-' . $Factor] = $this->Cache->getEntry('BGPView-' . $Factor);
            }
            if ($this->CIDRAM['BGPView-' . $Factor] === false) {
                continue;
            }

            /** Act based on ASN. */
            if (!empty($this->CIDRAM['BGPView-' . $Factor]['ASN'])) {
                /** Populate ASN lookup information. */
                if ($this->CIDRAM['BGPView-' . $Factor]['ASN'] > 0) {
                    $this->BlockInfo['ASNLookup'] = $this->CIDRAM['BGPView-' . $Factor]['ASN'];
                }

                /** Origin is whitelisted. */
                if (isset($this->CIDRAM['BGPConfig']['whitelisted_asns'][$this->CIDRAM['BGPView-' . $Factor]['ASN']])) {
                    $this->ZeroOutBlockInfo(true);
                    break 2;
                }

                /** Origin is blocked. */
                if (isset($this->CIDRAM['BGPConfig']['blocked_asns'][$this->CIDRAM['BGPView-' . $Factor]['ASN']])) {
                    $this->BlockInfo['ReasonMessage'] = $this->L10N->getString('ReasonMessage_Generic');
                    if (!empty($this->BlockInfo['WhyReason'])) {
                        $this->BlockInfo['WhyReason'] .= ', ';
                    }
                    $this->BlockInfo['WhyReason'] .= sprintf(
                        '%s (BGPView, "%d")',
                        $this->L10N->getString('Short_Generic'),
                        $this->CIDRAM['BGPView-' . $Factor]['ASN']
                    );
                    if (!empty($this->BlockInfo['Signatures'])) {
                        $this->BlockInfo['Signatures'] .= ', ';
                    }
                    $this->BlockInfo['Signatures'] .= $Factor;
                    $this->BlockInfo['SignatureCount']++;
                }
            }

            /** Act based on CC. */
            if (!empty($this->CIDRAM['BGPView-' . $Factor]['CC']) && empty($CCDone)) {
                /** Populate country code lookup information. */
                if ($this->CIDRAM['BGPView-' . $Factor]['CC'] !== 'XX') {
                    $this->BlockInfo['CCLookup'] = $this->CIDRAM['BGPView-' . $Factor]['CC'];
                    $CCDone = true;
                }

                /** Origin is whitelisted. */
                if (isset($this->CIDRAM['BGPConfig']['whitelisted_ccs'][$this->CIDRAM['BGPView-' . $Factor]['CC']])) {
                    $this->ZeroOutBlockInfo(true);
                    break 2;
                }

                /** Origin is blocked. */
                if (isset($this->CIDRAM['BGPConfig']['blocked_ccs'][$this->CIDRAM['BGPView-' . $Factor]['CC']])) {
                    $this->BlockInfo['ReasonMessage'] = sprintf(
                        $this->L10N->getString('why_no_access_allowed_from'),
                        $this->CIDRAM['BGPView-' . $Factor]['CC']
                    );
                    if (!empty($this->BlockInfo['WhyReason'])) {
                        $this->BlockInfo['WhyReason'] .= ', ';
                    }
                    $this->BlockInfo['WhyReason'] .= sprintf('CC (BGPView, "%s")', $this->CIDRAM['BGPView-' . $Factor]['CC']);
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
