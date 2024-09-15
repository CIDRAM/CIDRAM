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
 * This file: AbuseIPDB event handlers (last modified: 2024.09.15).
 */

/**
 * Bulk reporting.
 *
 * @return void
 */
$this->Events->addHandler('reporterFinished', function (): void {
    if (!$this->Configuration['abuseipdb']['report_back'] || $this->Configuration['abuseipdb']['api_key'] === '') {
        return;
    }
    if (!isset($this->CIDRAM['AbuseIPDB-Report Queue'])) {
        $this->CIDRAM['AbuseIPDB-Report Queue'] = $this->Cache->getEntry('AbuseIPDB-Report Queue');
    }
    if ($this->CIDRAM['AbuseIPDB-Report Queue'] === false) {
        unset($this->CIDRAM['AbuseIPDB-Report Queue']);
        return;
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
        return;
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
            $Bulk .= $Entry[1] . ',"' . $Entry[2] . '",' . date('c', $Entry[0]) . ',"' . $Entry[3] . "\"\n";
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
            $ToLog = $this->L10N->getString('response.Multiple IP addresses (bulk reporting)');
            $this->Events->fireEvent('writeToReportLog', $ToLog, $ToLog);
        }
        return;
    }
    foreach ($Try as $Entry) {
        $Status = $this->Request->request('https://api.abuseipdb.com/api/v2/report', [
            'ip' => $Entry[1],
            'categories' => $Entry[2],
            'comment' => $Entry[3],
            'timestamp' => date('c', $Entry[0])
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
            $ToLog = $this->L10N->getString('response.Multiple IP addresses (bulk reporting)');
            $this->Events->fireEvent('writeToReportLog', $ToLog, $ToLog);
        } else {
            if (substr($Entry[3], 0, 18) === 'Automated report (' && substr($Entry[3], 43, 2) === ').') {
                $Entry[3] = substr($Entry[3], 46);
            }
            $this->Events->fireEvent('writeToReportLog', $Entry[3], $Entry[1]);
        }
    }
    return;
});

/**
 * Cache the report queue.
 *
 * @return void
 */
$this->Events->addHandler('final', function (): void {
    if (!isset($this->CIDRAM['AbuseIPDB-Report Queue'])) {
        return;
    }
    $this->Cache->setEntry('AbuseIPDB-Report Queue', $this->CIDRAM['AbuseIPDB-Report Queue'], 259200);
    unset($this->CIDRAM['AbuseIPDB-Report Queue']);
});
