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
 * This file: AbuseIPDB module (last modified: 2023.08.27).
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
    if (empty($this->BlockInfo['IPAddr']) || $this->Configuration['abuseipdb']['lookup_strategy'] === 0) {
        return;
    }

    /**
     * We can't perform lookups without an API key, so we should check for
     * that, too.
     */
    if ($this->Configuration['abuseipdb']['api_key'] === '') {
        return;
    }

    /**
     * Normalised, lower-cased request URI; Used to determine whether the
     * module needs to do anything for the request.
     */
    $LCURI = preg_replace('/\s/', '', strtolower($this->BlockInfo['rURI']));

    /**
     * If the request isn't attempting to access a sensitive page (login,
     * registration page, etc), exit.
     */
    if ($this->Configuration['abuseipdb']['lookup_strategy'] !== 1 && !$this->isSensitive($LCURI)) {
        return;
    }

    /** Check whether the lookup limit has been exceeded. */
    if (!isset($this->CIDRAM['AbuseIPDB-429'])) {
        $this->CIDRAM['AbuseIPDB-429'] = $this->Cache->getEntry('AbuseIPDB-429') ? true : false;
    }

    /**
     * Only execute if not already blocked for some other reason, if the IP is
     * valid, if not from a private or reserved range, and if the lookup limit
     * hasn't already been exceeded (reduces superfluous lookups).
     */
    if (
        $this->CIDRAM['AbuseIPDB-429'] ||
        !$this->honourLookup() ||
        filter_var($this->BlockInfo['IPAddr'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false
    ) {
        return;
    }

    /** Marks for use with reCAPTCHA and hCAPTCHA. */
    $EnableCaptcha = ['recaptcha' => ['enabled' => true], 'hcaptcha' => ['enabled' => true]];

    /** Executed if there aren't any cache entries corresponding to the IP of the request. */
    if (
        !isset($this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']]) ||
        $this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']] === false
    ) {
        $this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']] = $this->Cache->getEntry('AbuseIPDB-' . $this->BlockInfo['IPAddr']);
        if ($this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']] === false) {
            /** Perform AbuseIPDB lookup. */
            $Lookup = $this->Request->request(
                'https://api.abuseipdb.com/api/v2/check?ipAddress=' . urlencode($this->BlockInfo['IPAddr']) . '&maxAgeInDays=' . $this->Configuration['abuseipdb']['max_age_in_days'],
                [],
                $this->Configuration['abuseipdb']['timeout_limit'],
                ['Key: ' . $this->Configuration['abuseipdb']['api_key'], 'Accept: application/json']
            );

            if ($this->Request->MostRecentStatusCode === 429) {
                /** Lookup limit has been exceeded. */
                $this->Cache->setEntry('AbuseIPDB-429', true, $this->Configuration['abuseipdb']['timeout_rl']->getAsSeconds());
                $this->CIDRAM['AbuseIPDB-429'] = true;
                return;
            }

            /** Validate or substitute. */
            $Lookup = strpos($Lookup, '"abuseConfidenceScore":') !== false ? (json_decode($Lookup, true) ?: []) : [];

            /** Generate local AbuseIPDB cache entry. */
            $this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']] = $Lookup['data'];

            /** Ensure confidence score. */
            if (!isset($this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']]['abuseConfidenceScore'])) {
                $this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']]['abuseConfidenceScore'] = 0;
            }

            /** Ensure total reports. */
            if (!isset($this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']]['totalReports'])) {
                $this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']]['totalReports'] = 0;
            }

            /** Check whether whitelisted. */
            $this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']]['isWhitelisted'] = !empty($Lookup['data']['isWhitelisted']);

            /** Update cache. */
            $this->Cache->setEntry(
                'AbuseIPDB-' . $this->BlockInfo['IPAddr'],
                $this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']],
                $this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']]['abuseConfidenceScore'] < 1 ? 3600 : 604800
            );
        }
    }

    /** Guard. */
    if (
        !isset($this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']]) ||
        !is_array($this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']])
    ) {
        return;
    }

    /** Block the request if the IP is listed by AbuseIPDB. */
    $this->trigger(
        (
            !(
                (isset($this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']]['usageType']) && $this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']]['usageType'] === 'Search Engine Spider') ||
                $this->hasProfile(['Search Engine', 'Search Engine Spider']) ||
                (isset($this->BlockInfo['Verified']) && $this->BlockInfo['Verified'] !== '')
            ) &&
            $this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']]['isWhitelisted'] === false &&
            $this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']]['abuseConfidenceScore'] >= $this->Configuration['abuseipdb']['minimum_confidence_score'] &&
            $this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']]['totalReports'] >= $this->Configuration['abuseipdb']['minimum_total_reports']
        ),
        'AbuseIPDB Lookup',
        $this->L10N->getString('ReasonMessage_Generic') . '<br />' . sprintf($this->L10N->getString('request_removal'), 'https://www.abuseipdb.com/check/' . $this->BlockInfo['IPAddr']),
        $this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']]['abuseConfidenceScore'] <= $this->Configuration['abuseipdb']['max_cs_for_captcha'] ? $EnableCaptcha : []
    );

    /** Build profiles. */
    if (
        $this->Configuration['abuseipdb']['build_profiles_from_usage_type'] &&
        !empty($this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']]['usageType'])
    ) {
        $this->addProfileEntry($this->CIDRAM['AbuseIPDB-' . $this->BlockInfo['IPAddr']]['usageType']);
    }
};

/** Add AbuseIPDB report handler. */
if ($this->Configuration['abuseipdb']['report_back'] && $this->Configuration['abuseipdb']['api_key'] !== '') {
    $this->Reporter->addHandler(function ($Report) {
        if ($this->Configuration['abuseipdb']['report_back'] === 2 && $this->BlockInfo['SignatureCount'] < 1) {
            return;
        }
        if (!isset($this->CIDRAM['AbuseIPDB-Recently Reported-' . $Report['IP']])) {
            $this->CIDRAM['AbuseIPDB-Recently Reported-' . $Report['IP']] = $this->Cache->getEntry('AbuseIPDB-Recently Reported-' . $Report['IP']);
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
        $Queue = true;
        if ($this->CIDRAM['AbuseIPDB-Recently Reported-' . $Report['IP']] === false) {
            $Status = $this->Request->request('https://api.abuseipdb.com/api/v2/report', [
                'ip' => $Report['IP'],
                'categories' => $Categories,
                'comment' => $Report['Comments']
            ], $this->Configuration['abuseipdb']['timeout_limit'], [
                'Key: ' . $this->Configuration['abuseipdb']['api_key'],
                'Accept: application/json'
            ]);
            $this->Cache->setEntry('AbuseIPDB-Recently Reported-' . $Report['IP'], true, 900);
            $this->CIDRAM['AbuseIPDB-Recently Reported-' . $Report['IP']] = true;
            if (strpos($Status, '"ipAddress":"' . $Report['IP'] . '"') !== false && strpos($Status, '"errors":') === false) {
                if (!isset($this->CIDRAM['Report OK'])) {
                    $this->CIDRAM['Report OK'] = 0;
                }
                $this->CIDRAM['Report OK']++;
                $Queue = false;
            } else {
                if (!isset($this->CIDRAM['Report Failed'])) {
                    $this->CIDRAM['Report Failed'] = 0;
                }
                $this->CIDRAM['Report Failed']++;
            }
        }
        if ($Queue) {
            if (!isset($this->CIDRAM['AbuseIPDB-Report Queue'])) {
                $this->CIDRAM['AbuseIPDB-Report Queue'] = $this->Cache->getEntry('AbuseIPDB-Report Queue');
            }
            if (!is_string($this->CIDRAM['AbuseIPDB-Report Queue'])) {
                $this->CIDRAM['AbuseIPDB-Report Queue'] = '';
            }
            if (substr_count($this->CIDRAM['AbuseIPDB-Report Queue'], '|' . $Report['IP'] . '|') < 10) {
                $this->CIDRAM['AbuseIPDB-Report Queue'] .= $this->Now . '|' . $Report['IP'] . '|' . $Categories . '|' . $Report['Comments'] . '||';
            }
        }
    });

    /** Bulk reporting. */
    $this->Events->addHandler('reporterFinished', function (): bool {
        if (!isset($this->CIDRAM['AbuseIPDB-Report Queue'])) {
            $this->CIDRAM['AbuseIPDB-Report Queue'] = $this->Cache->getEntry('AbuseIPDB-Report Queue');
        }
        if ($this->CIDRAM['AbuseIPDB-Report Queue'] === false) {
            unset($this->CIDRAM['AbuseIPDB-Report Queue']);
            return false;
        }
        $First = true;
        $Keep = '';
        $Try = [];
        $Count = 0;
        foreach (explode('||', $this->CIDRAM['AbuseIPDB-Report Queue']) as $Line) {
            if ($Line === '') {
                continue;
            }
            $Entry = explode('|', $Line, 4);
            if (count($Entry) !== 4) {
                continue;
            }
            $Ago = $this->Now - $Entry[0];
            if ($First) {
                if ($Ago < 21600) {
                    $Keep = $this->CIDRAM['AbuseIPDB-Report Queue'];
                    break;
                }
                $First = false;
            }
            if (!isset($this->CIDRAM['AbuseIPDB-Recently Reported-' . $Entry[1]])) {
                $this->CIDRAM['AbuseIPDB-Recently Reported-' . $Entry[1]] = $this->Cache->getEntry('AbuseIPDB-Recently Reported-' . $Entry[1]);
            }
            if ($Ago < 901 || $this->CIDRAM['AbuseIPDB-Recently Reported-' . $Entry[1]] !== false) {
                $Keep .= $Line . '||';
                continue;
            }
            if ($Ago > 259200) {
                continue;
            }
            $Count++;
            if ($Count > 9999) {
                $Keep .= $Line . '||';
                continue;
            }
            $Try[] = $Entry;
            $this->Cache->setEntry('AbuseIPDB-Recently Reported-' . $Entry[1], true, 900);
            $this->CIDRAM['AbuseIPDB-Recently Reported-' . $Entry[1]] = true;
        }
        if ($Keep !== $this->CIDRAM['AbuseIPDB-Report Queue']) {
            $this->CIDRAM['AbuseIPDB-Report Queue'] = $Keep;
        }
        unset($Ago, $Keep, $First);
        $Count = count($Try);
        if ($Count === 0) {
            return false;
        }
        $OK = false;
        $TryBulk = false;
        if ($Count > 4 && class_exists('\CURLStringFile')) {
            if (!isset($this->CIDRAM['AbuseIPDB-Daily Bulk Quota'])) {
                $this->CIDRAM['AbuseIPDB-Daily Bulk Quota'] = $this->Cache->getEntry('AbuseIPDB-Daily Bulk Quota');
            }
            if ($this->CIDRAM['AbuseIPDB-Daily Bulk Quota'] < 3) {
                $TryBulk = true;
            }
        }
        if ($TryBulk) {
            $this->Cache->incEntry('AbuseIPDB-Daily Bulk Quota', 1, 86400);
            $Bulk = "IP,Categories,ReportDate,Comment\n";
            foreach ($Try as $Entry) {
                $Bulk .= $Entry[1] . ',"' . $Entry[2] . '",' . $this->timeFormat($Entry[0], '{yyyy}-{mm}-{dd}T{hh}:{ii}:{ss}{t:z}') . ',"' . $Entry[3] . "\"\n";
            }
            $Bulk = new \CURLStringFile($Bulk, 'report.csv', 'text/csv');
            $Status = $this->Request->request('https://api.abuseipdb.com/api/v2/bulk-report', ['csv' => $Bulk], $this->Configuration['abuseipdb']['timeout_limit'], [
                'Key: ' . $this->Configuration['abuseipdb']['api_key'],
                'Accept: application/json'
            ]);
            if (preg_match('~"savedReports":(\d+)~', $Status, $Success) && isset($Success[1])) {
                if ($Success[1] > 0) {
                    $OK = true;
                }
                if (!isset($this->CIDRAM['Report OK'])) {
                    $this->CIDRAM['Report OK'] = 0;
                }
                $this->CIDRAM['Report OK'] += $Success[1];
            }
            if (($Failure = substr_count($Status, '"error":')) > 0) {
                if (!isset($this->CIDRAM['Report Failed'])) {
                    $this->CIDRAM['Report Failed'] = 0;
                }
                $this->CIDRAM['Report Failed'] += $Failure;
            }
            if ($this->Events->assigned('writeToReportLog')) {
                $ToLog = $this->L10N->getString('response_bulk_reporting');
                $this->Events->fireEvent('writeToReportLog', $ToLog, $ToLog);
            }
            return $OK;
        }
        foreach ($Try as $Entry) {
            $Status = $this->Request->request('https://api.abuseipdb.com/api/v2/report', [
                'ip' => $Entry[1],
                'categories' => $Entry[2],
                'comment' => $Entry[3]
            ], $this->Configuration['abuseipdb']['timeout_limit'], [
                'Key: ' . $this->Configuration['abuseipdb']['api_key'],
                'Accept: application/json'
            ]);
            if (strpos($Status, '"ipAddress":"' . $Report['IP'] . '"') !== false && strpos($Status, '"errors":') === false) {
                if (!isset($this->CIDRAM['Report OK'])) {
                    $this->CIDRAM['Report OK'] = 0;
                }
                $this->CIDRAM['Report OK']++;
                $OK = true;
                continue;
            }
            if (!isset($this->CIDRAM['Report Failed'])) {
                $this->CIDRAM['Report Failed'] = 0;
            }
            $this->CIDRAM['Report Failed']++;
        }
        if ($this->Events->assigned('writeToReportLog')) {
            if ($Count > 1) {
                $ToLog = $this->L10N->getString('response_bulk_reporting');
                $this->Events->fireEvent('writeToReportLog', $ToLog, $ToLog);
            } else {
                $this->Events->fireEvent('writeToReportLog', $Entry[3], $Entry[1]);
            }
        }
        return $OK;
    });

    /** Cache the report queue. */
    $this->Events->addHandler('final', function (): bool {
        if (!isset($this->CIDRAM['AbuseIPDB-Report Queue'])) {
            return false;
        }
        $this->Cache->setEntry('AbuseIPDB-Report Queue', $this->CIDRAM['AbuseIPDB-Report Queue'], 259200);
        unset($this->CIDRAM['AbuseIPDB-Report Queue']);
        return true;
    });
}

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
