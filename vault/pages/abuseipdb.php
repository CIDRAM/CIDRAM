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
 * This file: Report to AbuseIPDB page (last modified: 2023.12.24).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'abuseipdb' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('label.Report to AbuseIPDB'), 'This page is still under construction and won\'t work properly yet.');

/** Append number localisation JS. */
$this->FE['JS'] .= $this->numberL10nJs() . "\n";

/** Use existing configuration value if it's been set. */
if (!isset($_POST['apikey']) && isset($this->Configuration['abuseipdb']['api_key'])) {
    $_POST['apikey'] = $this->Configuration['abuseipdb']['api_key'];
}

/** Populate inputs and textareas. */
foreach (['address', 'comment', 'apikey', 'endpoint'] as $Field) {
    $this->FE[$Field] = isset($_POST[$Field]) ? str_replace(['&', '<', '>', '"'], ['&amp;', '&lt;', '&gt;', '&quot;'], $_POST[$Field]) : '';
}

/** Populate categories. */
for ($Iterator = 1; $Iterator < 24; $Iterator++) {
    $this->FE['CatSwitch' . $Iterator] = isset($_POST['CatSwitch' . $Iterator]) && $_POST['CatSwitch' . $Iterator] === 'on' ? ' checked' : '';
}

if ($this->FE['address'] !== '') {
    /** Run tests. */
    $this->simulateBlockEvent($this->FE['address'], true, false, false, false, false, true);

    /** Auto-populate based on IP profiling. */
    if (isset($_POST['populate']) && $_POST['populate'] === 'yes') {
        $this->CIDRAM['TestMode'] = 1;
        if (!empty($this->CIDRAM['TestResults'])) {
            $this->FE['CatSwitch9'] = $this->hasProfile(['Proxy', 'Tor endpoints here', 'Open Proxy']) ? ' checked' : '';
            $this->FE['CatSwitch13'] = $this->hasProfile(['VPNs here', 'VPN IP']) ? ' checked' : '';
        }
    }

    /** Invalid IP address. */
    if ($this->CIDRAM['TestResults'] === 0) {
        $this->FE['state_msg'] = $this->L10N->getString('response.The IP address is invalid');
    }
}

/** Prepare to submit the report. */
if (!isset($_POST['populate']) && $this->FE['address'] !== '' && $this->FE['apikey'] !== '' && $this->CIDRAM['TestResults'] !== 0) {
    if ($this->FE['endpoint'] === 'report') {
        if (!isset($this->CIDRAM['AbuseIPDB-Recently Reported-' . $this->FE['address']])) {
            $this->CIDRAM['AbuseIPDB-Recently Reported-' . $this->FE['address']] = $this->Cache->getEntry('AbuseIPDB-Recently Reported-' . $this->FE['address']);
        }
        $Categories = [];
        for ($Iterator = 1; $Iterator < 24; $Iterator++) {
            if (isset($_POST['CatSwitch' . $Iterator]) && $_POST['CatSwitch' . $Iterator] === 'on') {
                $Categories[] = $Iterator;
            }
        }
        if (!count($Categories)) {
            $this->FE['state_msg'] = $this->L10N->getString('response.Please select at least one category');
        } else {
            $Categories = implode(',', $Categories);
            $Queue = true;
            $Status = $this->Request->request('https://api.abuseipdb.com/api/v2/report', [
                'ip' => $this->FE['address'],
                'categories' => $Categories,
                'comment' => $this->FE['comment']
            ], $this->Configuration['abuseipdb']['timeout_limit'], ['Key: ' . $this->FE['apikey'], 'Accept: application/json']);
            $this->Cache->setEntry('AbuseIPDB-Recently Reported-' . $this->FE['address'], true, 900);
            $this->CIDRAM['AbuseIPDB-Recently Reported-' . $this->FE['address']] = true;
            if (strpos($Status, '"ipAddress":"' . $this->FE['address'] . '"') !== false && strpos($Status, '"errors":') === false) {
                $this->FE['state_msg'] = sprintf($this->L10N->getString('response.The IP address, %s, successfully reported'), $this->FE['address']);
                $Queue = false;
                if ($this->CIDRAM['LastTestIP'] === 4) {
                    $this->Cache->incEntry('Statistics-Reported-IPv4-OK');
                } elseif ($this->CIDRAM['LastTestIP'] === 6) {
                    $this->Cache->incEntry('Statistics-Reported-IPv6-OK');
                }
            } else {
                $this->FE['state_msg'] = sprintf($this->L10N->getString('response.Failed to report the IP address, %s'), $this->FE['address']);
                if (strpos($Status, 'once in 15 minutes') !== false) {
                    $this->FE['state_msg'] .= ' ' . $this->L10N->getString('response.The same IP address can be reported only once every 15 minutes');
                } elseif (strpos($Status, 'Authentication failed') !== false) {
                    $Queue = false;
                    $this->FE['state_msg'] .= ' ' . $this->L10N->getString('response.Invalid API key') . ' ' . $this->L10N->getString('response.The report has not been enqueued');
                } else {
                    $Queue = false;
                    $this->FE['state_msg'] .= ' ' . $this->L10N->getString('response.The report has not been enqueued');
                }
                if ($this->CIDRAM['LastTestIP'] === 4) {
                    $this->Cache->incEntry('Statistics-Reported-IPv4-Failed');
                } elseif ($this->CIDRAM['LastTestIP'] === 6) {
                    $this->Cache->incEntry('Statistics-Reported-IPv6-Failed');
                }
            }
            if ($Queue) {
                if (!isset($this->CIDRAM['AbuseIPDB-Report Queue'])) {
                    $this->CIDRAM['AbuseIPDB-Report Queue'] = $this->Cache->getEntry('AbuseIPDB-Report Queue');
                }
                if (!is_string($this->CIDRAM['AbuseIPDB-Report Queue'])) {
                    $this->CIDRAM['AbuseIPDB-Report Queue'] = '';
                }
                if (substr_count($this->CIDRAM['AbuseIPDB-Report Queue'], '|' . $this->FE['address'] . '|') < 10) {
                    $this->CIDRAM['AbuseIPDB-Report Queue'] .= $this->Now . '|' . $this->FE['address'] . '|' . $Categories . '|' . $this->FE['comment'] . '||';
                }
            }
        }
    } elseif ($this->FE['endpoint'] === 'delete') {
        $Status = $this->Request->request('https://api.abuseipdb.com/api/v2/clear-address?ipAddress=' . urlencode($this->FE['address']), '', $this->Configuration['abuseipdb']['timeout_limit'], ['Key: ' . $this->FE['apikey'], 'Accept: application/json'], 0, 'DELETE');
        if (preg_match('~\{"numReportsDeleted":(\d+)\}~', $Status, $Matches)) {
            $Matches = (int)$Matches[1];
            $this->FE['state_msg'] = sprintf(
                $this->L10N->getPlural($Matches, 'response.Successfully deleted %s reports for %s'),
                '<span class="txtRd">' . $this->NumberFormatter->format($Matches) . '</span>',
                $this->FE['address']
            );
        } else {
            $this->FE['state_msg'] = sprintf($this->L10N->getString('response.Failed to delete any reports for %s'), $this->FE['address']);
        }
        $this->Cache->deleteEntry('AbuseIPDB-Recently Reported-' . $this->FE['address']);
        unset($Matches, $this->CIDRAM['AbuseIPDB-Recently Reported-' . $this->FE['address']]);
        if (!isset($this->CIDRAM['AbuseIPDB-Report Queue'])) {
            $this->CIDRAM['AbuseIPDB-Report Queue'] = $this->Cache->getEntry('AbuseIPDB-Report Queue');
        }
        if (!is_string($this->CIDRAM['AbuseIPDB-Report Queue'])) {
            $this->CIDRAM['AbuseIPDB-Report Queue'] = '';
        }
        if ($this->CIDRAM['AbuseIPDB-Report Queue'] !== '') {
            $this->CIDRAM['AbuseIPDB-Report Queue'] = preg_replace('~\d+\|' . preg_quote($this->FE['address']) . '\|[\d,]+\|.*?\|\|~', '', $this->CIDRAM['AbuseIPDB-Report Queue']);
        }
    } else {
        $this->FE['state_msg'] = $this->L10N->getString('response.Wrong endpoint');
    }
}

/** Cleanup. */
unset($Categories, $Status, $Queue, $Iterator, $Field);

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_abuseipdb.html')), true);

/** Send output. */
echo $this->sendOutput();
