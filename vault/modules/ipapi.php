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
 * This file: IP-API module (last modified: 2024.05.18).
 *
 * False positive risk (an approximate, rough estimate only): « [x]Low [ ]Medium [ ]High »
 */

/** Safety. */
if (!isset($this->CIDRAM['ModuleResCache'])) {
    $this->CIDRAM['ModuleResCache'] = [];
}

/** Initialise ip-api module information. */
$this->CIDRAM['IPAPIConfig'] = [
    'blocked_asns' => array_flip(preg_split('~[\s,]~', $this->Configuration['ipapi']['blocked_asns'], -1, PREG_SPLIT_NO_EMPTY)),
    'whitelisted_asns' => array_flip(preg_split('~[\s,]~', $this->Configuration['ipapi']['whitelisted_asns'], -1, PREG_SPLIT_NO_EMPTY)),
    'blocked_ccs' => array_flip(preg_split('~[\s,]~', $this->Configuration['ipapi']['blocked_ccs'], -1, PREG_SPLIT_NO_EMPTY)),
    'whitelisted_ccs' => array_flip(preg_split('~[\s,]~', $this->Configuration['ipapi']['whitelisted_ccs'], -1, PREG_SPLIT_NO_EMPTY))
];

/** Defining as closure for later recall (no params; no return value). */
$this->CIDRAM['ModuleResCache'][$Module] = function () {
    /** Guard. */
    if (empty($this->BlockInfo['IPAddr'])) {
        return;
    }

    /**
     * Depending on the configured lookup strategy, if the request isn't
     * attempting to access a sensitive page (login, registration page, etc),
     * exit.
     */
    if ($this->Configuration['ipapi']['lookup_strategy'] !== 1 && !$this->isSensitive(preg_replace('/\s/', '', strtolower($this->BlockInfo['rURI'])))) {
        return;
    }

    $InCache = false;
    $DoOpt = false;

    /** Check whether the lookup limit has been exceeded. */
    if (!isset($this->CIDRAM['IPAPI-429'])) {
        $this->CIDRAM['IPAPI-429'] = $this->Cache->getEntry('IPAPI-429') ? true : false;
    }

    /**
     * Only execute if the IP is valid, if not from a private or reserved
     * range, and if the lookup limit hasn't already been exceeded (reduces
     * superfluous lookups).
     */
    if (
        $this->CIDRAM['IPAPI-429'] ||
        filter_var($this->BlockInfo['IPAddr'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false
    ) {
        return;
    }

    /** Expand factors for this origin. */
    $Expanded = [$this->expandIpv4($this->BlockInfo['IPAddr']), $this->expandIpv6($this->BlockInfo['IPAddr'])];
    if (is_array($Expanded[0]) && count($Expanded[0]) === 32) {
        $ToCheck = $Expanded[0][23];
    } elseif (is_array($Expanded[1]) && count($Expanded[1]) === 128) {
        $ToCheck = $Expanded[1][47];
    }

    /** Check whether we've already performed a lookup for this origin. */
    if (isset($ToCheck) && !isset($this->CIDRAM['IPAPI-' . $ToCheck])) {
        $Try = $this->Cache->getEntry('IPAPI-' . $ToCheck);
        if ($Try !== false) {
            $this->CIDRAM['IPAPI-' . $ToCheck] = $Try;
            $InCache = true;
        }
    }

    /** Prepare to perform a new lookup if none for this origin have been cached yet. */
    if (!$InCache) {
        $Lookup = $this->Request->request(
            'http://ip-api.com/json/' . $this->BlockInfo['IPAddr'] . '?fields=status,countryCode,as,mobile,proxy,hosting',
            [],
            $this->Configuration['ipapi']['timeout_limit'] ?? 12
        );

        if ($this->Request->MostRecentStatusCode !== 200) {
            /** Lookup limit has been exceeded. */
            $this->Cache->setEntry('IPAPI-429', true, $this->Configuration['ipapi']['timeout_rl']->getAsSeconds());
            $this->CIDRAM['IPAPI-429'] = true;
            return;
        }

        $Lookup = (substr($Lookup, 0, 21) === '{"status":"success","' && substr($Lookup, -1) === '}') ? json_decode($Lookup, true) : false;
        $CC = 'XX';
        $ASN = 0;
        $Expiry = 3600;
        $Profiles = [];

        if (is_array($Lookup)) {
            if (isset($Lookup['countryCode'])) {
                $CC = $Lookup['countryCode'];
            }
            if (isset($Lookup['as']) && substr($Lookup['as'], 0, 2) === 'AS') {
                $ASN = substr($Lookup['as'], 2);
                if (($SPos = strpos($ASN, ' ')) !== false) {
                    $ASN = substr($ASN, 0, $SPos);
                }
                $ASN = (int)$ASN;
            }
            if (!empty($Lookup['mobile'])) {
                $Profiles[] = 'Mobile ISP';
            }
            if (!empty($Lookup['proxy'])) {
                $Profiles[] = 'Proxy';
            }
            if (!empty($Lookup['hosting'])) {
                $Profiles[] = 'Webhosting';
            }
            $Expiry = 604800;
        }

        $this->Cache->setEntry('IPAPI-' . $ToCheck, ['ASN' => $ASN, 'CC' => $CC, 'Profiles' => $Profiles], $Expiry);
        $this->CIDRAM['IPAPI-' . $ToCheck] = ['ASN' => $ASN, 'CC' => $CC, 'Profiles' => $Profiles];
        $InCache = true;
    }

    /** Act based on ASN. */
    if (!empty($this->CIDRAM['IPAPI-' . $ToCheck]['ASN'])) {
        /** Populate ASN lookup information. */
        if ($this->CIDRAM['IPAPI-' . $ToCheck]['ASN'] > 0) {
            $this->BlockInfo['ASNLookup'] = $this->CIDRAM['IPAPI-' . $ToCheck]['ASN'];
        }

        /** Origin is whitelisted. */
        if (isset($this->CIDRAM['IPAPIConfig']['whitelisted_asns'][$this->CIDRAM['IPAPI-' . $ToCheck]['ASN']])) {
            $this->ZeroOutBlockInfo(true);
            return;
        }

        /** Origin is blocked. */
        if (isset($this->CIDRAM['IPAPIConfig']['blocked_asns'][$this->CIDRAM['IPAPI-' . $ToCheck]['ASN']])) {
            $this->BlockInfo['ReasonMessage'] = $this->L10N->getString('ReasonMessage_Generic');
            if (!empty($this->BlockInfo['WhyReason'])) {
                $this->BlockInfo['WhyReason'] .= ', ';
            }
            $this->BlockInfo['WhyReason'] .= sprintf(
                '%s (IP-API, "%d")',
                $this->L10N->getString('Short_Generic'),
                $this->CIDRAM['IPAPI-' . $ToCheck]['ASN']
            );
            if (!empty($this->BlockInfo['Signatures'])) {
                $this->BlockInfo['Signatures'] .= ', ';
            }
            $this->BlockInfo['Signatures'] .= $ToCheck;
            $this->BlockInfo['SignatureCount']++;
            $DoOpt = true;
        }
    }

    /** Act based on CC. */
    if (!empty($this->CIDRAM['IPAPI-' . $ToCheck]['CC'])) {
        /** Populate country code lookup information. */
        if ($this->CIDRAM['IPAPI-' . $ToCheck]['CC'] !== 'XX') {
            $this->BlockInfo['CCLookup'] = $this->CIDRAM['IPAPI-' . $ToCheck]['CC'];
        }

        /** Origin is whitelisted. */
        if (isset($this->CIDRAM['IPAPIConfig']['whitelisted_ccs'][$this->CIDRAM['IPAPI-' . $ToCheck]['CC']])) {
            $this->ZeroOutBlockInfo(true);
            return;
        }

        /** Origin is blocked. */
        if (isset($this->CIDRAM['IPAPIConfig']['blocked_ccs'][$this->CIDRAM['IPAPI-' . $ToCheck]['CC']])) {
            $this->BlockInfo['ReasonMessage'] = sprintf(
                $this->L10N->getString('why_no_access_allowed_from'),
                $this->CIDRAM['IPAPI-' . $ToCheck]['CC']
            );
            if (!empty($this->BlockInfo['WhyReason'])) {
                $this->BlockInfo['WhyReason'] .= ', ';
            }
            $this->BlockInfo['WhyReason'] .= sprintf('CC (IP-API, "%s")', $this->CIDRAM['IPAPI-' . $ToCheck]['CC']);
            if (!empty($this->BlockInfo['Signatures'])) {
                $this->BlockInfo['Signatures'] .= ', ';
            }
            $this->BlockInfo['Signatures'] .= $ToCheck;
            $this->BlockInfo['SignatureCount']++;
            $DoOpt = true;
        }
    }

    /** Profiling. */
    if (isset($this->CIDRAM['IPAPI-' . $ToCheck]['Profiles'])) {
        foreach ($this->CIDRAM['IPAPI-' . $ToCheck]['Profiles'] as $Profile) {
            $this->addProfileEntry($Profile);
        }
    }

    /** Fetch options. */
    if ($DoOpt) {
        $Options = array_flip(explode("\n", $this->Configuration['ipapi']['options']));
        if (isset($Options['MarkForUseWithReCAPTCHA'])) {
            $this->Configuration['recaptcha']['enabled'] = true;
        }
        if (isset($Options['ForciblyDisableReCAPTCHA'])) {
            $this->Configuration['recaptcha']['usemode'] = 0;
            $this->Configuration['recaptcha']['forcibly_disabled'] = true;
        }
        if (isset($Options['MarkForUseWithHCAPTCHA'])) {
            $this->Configuration['hcaptcha']['enabled'] = true;
        }
        if (isset($Options['ForciblyDisableHCAPTCHA'])) {
            $this->Configuration['hcaptcha']['usemode'] = 0;
            $this->Configuration['hcaptcha']['forcibly_disabled'] = true;
        }
    }
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
