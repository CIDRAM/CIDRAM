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
 * This file: AbuseIPDB module (last modified: 2022.05.18).
 *
 * False positive risk (an approximate, rough estimate only): « [ ]Low [x]Medium [ ]High »
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

    /** Normalised, lower-cased request URI; Used to determine whether the module needs to do anything for the request. */
    $LCURI = preg_replace('/\s/', '', strtolower($this->BlockInfo['rURI']));

    /** If the request isn't attempting to access a sensitive page (login, registration page, etc), exit. */
    if (!$this->Configuration['abuseipdb']['lookup_strategy'] && !$this->isSensitive($LCURI)) {
        return;
    }

    /**
     * Only execute if not already blocked for some other reason, if the IP is
     * valid, if not from a private or reserved range, and if the lookup limit
     * hasn't already been exceeded (reduces superfluous lookups).
     */
    if (
        isset($this->CIDRAM['AbuseIPDB']['429']) ||
        !$this->honourLookup() ||
        filter_var($this->BlockInfo['IPAddr'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false
    ) {
        return;
    }

    /** Marks for use with reCAPTCHA and hCAPTCHA. */
    $EnableCaptcha = ['recaptcha' => ['enabled' => true], 'hcaptcha' => ['enabled' => true]];

    /** Local AbuseIPDB cache entry expiry time (successful lookups). */
    $Expiry = $this->Now + 604800;

    /** Local AbuseIPDB cache entry expiry time (failed lookups). */
    $ExpiryFailed = $this->Now + 3600;

    /** Build local AbuseIPDB cache if it doesn't already exist. */
    $this->initialiseCacheSection('AbuseIPDB');

    /** Executed if there aren't any cache entries corresponding to the IP of the request. */
    if (!isset($this->CIDRAM['AbuseIPDB'][$this->BlockInfo['IPAddr']])) {
        /** Perform AbuseIPDB lookup. */
        $Lookup = $this->Request->request(
            'https://api.abuseipdb.com/api/v2/check?ipAddress=' . urlencode($this->BlockInfo['IPAddr']) . '&maxAgeInDays=' . $this->Configuration['abuseipdb']['max_age_in_days'],
            [],
            $this->Configuration['abuseipdb']['timeout_limit'],
            ['Key: ' . $this->Configuration['abuseipdb']['api_key'], 'Accept: application/json']
        );

        if ($this->Request->MostRecentStatusCode === 429) {
            /** Lookup limit has been exceeded. */
            $this->CIDRAM['AbuseIPDB']['429'] = ['Time' => $Expiry];
        } else {
            /** Validate or substitute. */
            $Lookup = strpos($Lookup, '"abuseConfidenceScore":') !== false ? json_decode($Lookup, true) : [];

            /** Generate local AbuseIPDB cache entry. */
            $this->CIDRAM['AbuseIPDB'][$this->BlockInfo['IPAddr']] = empty($Lookup['data']['abuseConfidenceScore']) ? [
                'abuseConfidenceScore' => 0,
                'Time' => $ExpiryFailed
            ] : [
                'abuseConfidenceScore' => $Lookup['data']['abuseConfidenceScore'],
                'Time' => $Expiry
            ];

            /** Total reports. */
            if (empty($Lookup['data']['totalReports'])) {
                $this->CIDRAM['AbuseIPDB'][$this->BlockInfo['IPAddr']]['totalReports'] = 0;
            } else {
                $this->CIDRAM['AbuseIPDB'][$this->BlockInfo['IPAddr']]['totalReports'] = $Lookup['data']['totalReports'];
            }

            /** Usage type. */
            if (!empty($Lookup['data']['usageType'])) {
                $this->CIDRAM['AbuseIPDB'][$this->BlockInfo['IPAddr']]['usageType'] = $Lookup['data']['usageType'];
            }

            /** Check whether whitelisted. */
            $this->CIDRAM['AbuseIPDB'][$this->BlockInfo['IPAddr']]['isWhitelisted'] = !empty($Lookup['data']['isWhitelisted']);
        }

        /** Cache update flag. */
        $this->CIDRAM['AbuseIPDB-Modified'] = true;
    }

    /** Block the request if the IP is listed by AbuseIPDB. */
    $this->trigger(
        (
            !$this->CIDRAM['AbuseIPDB'][$this->BlockInfo['IPAddr']]['isWhitelisted'] &&
            $this->CIDRAM['AbuseIPDB'][$this->BlockInfo['IPAddr']]['abuseConfidenceScore'] >= $this->Configuration['abuseipdb']['minimum_confidence_score'] &&
            $this->CIDRAM['AbuseIPDB'][$this->BlockInfo['IPAddr']]['totalReports'] >= $this->Configuration['abuseipdb']['minimum_total_reports']
        ),
        'AbuseIPDB Lookup',
        $this->L10N->getString('ReasonMessage_Generic') . '<br />' . sprintf($this->L10N->getString('request_removal'), 'https://www.abuseipdb.com/check/' . $this->BlockInfo['IPAddr']),
        $this->CIDRAM['AbuseIPDB'][$this->BlockInfo['IPAddr']]['abuseConfidenceScore'] <= $this->Configuration['abuseipdb']['max_cs_for_captcha'] ? $EnableCaptcha : []
    );

    /** Build profiles. */
    if (
        $this->Configuration['abuseipdb']['build_profiles_from_usage_type'] &&
        !empty($this->CIDRAM['AbuseIPDB'][$this->BlockInfo['IPAddr']]['usageType'])
    ) {
        $this->addProfileEntry($this->CIDRAM['AbuseIPDB'][$this->BlockInfo['IPAddr']]['usageType']);
    }
};

/** Add AbuseIPDB report handler. */
if ($this->Configuration['abuseipdb']['report_back']) {
    $this->Reporter->addHandler(function ($Report) {
        $this->initialiseCacheSection('RecentlyReported');
        if (isset($this->CIDRAM['RecentlyReported'][$Report['IP']])) {
            return;
        }
        $Categories = [];
        foreach ($Report['Categories'] as $Category) {
            if ($Category > 2 && $Category < 24) {
                $Categories[] = $Category;
            }
        }
        if (!count($Categories)) {
            return;
        }
        $Categories = implode(',', $Categories);
        $Status = $this->Request->request('https://api.abuseipdb.com/api/v2/report', [
            'ip' => $Report['IP'],
            'categories' => $Categories,
            'comment' => $Report['Comments']
        ], $this->Configuration['abuseipdb']['timeout_limit'], [
            'Key: ' . $this->Configuration['abuseipdb']['api_key'],
            'Accept: application/json'
        ]);
        $this->CIDRAM['RecentlyReported'][$Report['IP']] = ['Status' => $Status, 'Time' => ($this->Now + 900)];
        $this->CIDRAM['RecentlyReported-Modified'] = true;
    });
}

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
