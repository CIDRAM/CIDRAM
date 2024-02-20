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
 * This file: The IP tracking page (last modified: 2024.02.20).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'ip-tracking' || $this->FE['Permissions'] !== 1) {
    die;
}

$this->FE['TrackingFilter'] = 'cidram-page=ip-tracking';
$this->FE['TrackingFilterControls'] = '';
$StateModified = false;
$this->filterSwitch(
    ['tracking-blocked-already', 'tracking-aux', 'tracking-hide-banned-blocked'],
    $_POST['FilterSelector'] ?? '',
    $StateModified,
    $this->FE['TrackingFilter'],
    $this->FE['TrackingFilterControls']
);
if ($StateModified) {
    header('Location: ?' . $this->FE['TrackingFilter']);
    die;
}
unset($StateModified);

if (!$this->FE['ASYNC']) {
    /** Page initial prepwork. */
    $this->initialPrepwork($this->L10N->getString('link.IP Tracking'), $this->L10N->getString('tip.IP Tracking'));

    /** Add flags CSS. */
    if ($this->FE['Flags'] = file_exists($this->AssetsPath . 'frontend/flags.css')) {
        $this->FE['OtherHead'] .= "\n  <link rel=\"stylesheet\" type=\"text/css\" href=\"?cidram-page=flags\" />";
    }

    /** Template for result rows. */
    $this->FE['TrackingRow'] = $this->readFile($this->getAssetPath('_ip_tracking_row.html'));

    $this->FE['ExtraSvgIcons'] = '';
    if (isset($this->CIDRAM['Extra SVG Icons']) && is_array($this->CIDRAM['Extra SVG Icons'])) {
        $Timestamp = date('Y-m-d\TH:i', $this->Now);
        foreach ($this->CIDRAM['Extra SVG Icons'] as $ExtraSvgIcon) {
            $this->FE['ExtraSvgIcons'] .= substr_count($ExtraSvgIcon, '%s') > 1 ? sprintf($ExtraSvgIcon, '{IPAddr}', $Timestamp) : sprintf($ExtraSvgIcon, '{IPAddr}');
        }
        unset($ExtraSvgIcon, $Timestamp);
    }
    $this->FE['TrackingRow'] = str_replace('{ExtraSvgIcons}', $this->FE['ExtraSvgIcons'], $this->FE['TrackingRow']);
}

/** Initialise variables. */
$this->FE['TrackingData'] = '';
$this->FE['TrackingCount'] = '';
$this->FE['NormalExpiry'] = $this->relativeTime($this->Now + $this->Configuration['signatures']['default_tracktime']->getAsSeconds());
$this->FE['ExtendedExpiry'] = $this->relativeTime($this->Now + floor($this->Configuration['signatures']['default_tracktime']->getAsSeconds() * 52.1428571428571));
$this->FE['1HourLabel'] = $this->relativeTime($this->Now + 3600);
$this->FE['1DayLabel'] = $this->relativeTime($this->Now + 86400);
$this->FE['1WeekLabel'] = $this->relativeTime($this->Now + 604800);
$this->FE['1MonthLabel'] = $this->relativeTime($this->Now + 2592000);
$this->FE['1YearLabel'] = $this->relativeTime($this->Now + 31557600);

/** Generate confirm button. */
$this->FE['Confirm-ClearAll'] = $this->generateConfirmation($this->L10N->getString('field.Clear all'), 'trackForm');

/** Clear, or remove an IP address from tracking. */
if (isset($_POST['IPAddr'])) {
    if ($_POST['IPAddr'] === '*' && $this->Cache->deleteAllEntriesWhere('~^Tracking-~')) {
        $this->FE['state_msg'] = $this->L10N->getString('response.Tracking cleared');
    } elseif ($this->Cache->deleteEntry('Tracking-' . $_POST['IPAddr'])) {
        $this->Cache->deleteEntry('Tracking-' . $_POST['IPAddr'] . '-MinimumTime');
        $this->FE['state_msg'] = sprintf($this->L10N->getString('response.Removed %s from tracking'), $_POST['IPAddr']);
    }
}

/** Add an IP address to tracking. */
if (isset($_POST['addNewAddress'], $_POST['addNewInfractions'], $_POST['addNewExpiryMenu'])) {
    if ($this->FE['state_msg']) {
        $this->FE['state_msg'] .= "<br />\n";
    }
    if ($this->expandIpv4($_POST['addNewAddress'], true) || $this->expandIpv6($_POST['addNewAddress'], true)) {
        if ($_POST['addNewExpiryMenu'] === 'Extended') {
            $TrackTime = floor($this->Configuration['signatures']['default_tracktime']->getAsSeconds() * 52.1428571428571);
        } elseif ($_POST['addNewExpiryMenu'] === 'Other' && isset($_POST['addNewExpiryOther'])) {
            $TrackTime = $_POST['addNewExpiryOther'];
        } else {
            $TrackTime = $this->Configuration['signatures']['default_tracktime']->getAsSeconds();
        }
        $this->Cache->setEntry('Tracking-' . $_POST['addNewAddress'], $_POST['addNewInfractions'], $TrackTime);
        $this->Cache->setEntry('Tracking-' . $_POST['addNewAddress'] . '-MinimumTime', $TrackTime, $TrackTime);
        $this->FE['state_msg'] .= sprintf($this->L10N->getString('response.Added %s to tracking'), $_POST['addNewAddress']);
    } else {
        $this->FE['state_msg'] .= $this->L10N->getString('Short_BadIP') . '!';
    }
}

if (!$this->FE['ASYNC']) {
    $ThisTracking = [];

    /** Initialise stages. */
    $this->Stages = array_flip(explode("\n", $this->Configuration['general']['stages']));

    /** Initialise shorthand options. */
    $this->Shorthand = array_flip(explode("\n", $this->Configuration['signatures']['shorthand']));

    /** Get all IP tracking entries. */
    $Entries = $this->Cache->getAllEntriesWhere('~^Tracking-(.+)(?<!-MinimumTime)$~', '\1', function ($A, $B): int {
        if (!is_array($A) || !isset($A['Time'])) {
            return is_array($B) && isset($B['Time']) ? -1 : 0;
        }
        if (!is_array($B) || !isset($B['Time'])) {
            return 1;
        }
        if ($A['Time'] === $B['Time']) {
            return 0;
        }
        return ($A['Time'] < $B['Time']) ? -1 : 1;
    });

    /** Count currently tracked IPs. */
    $this->FE['TrackingCount'] = count($Entries);
    $this->FE['TrackingCount'] = sprintf(
        $this->L10N->getPlural($this->FE['TrackingCount'], 'state_tracking'),
        '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['TrackingCount']) . '</span>'
    );

    /** Iterate through all addresses being currently tracked. */
    foreach ($Entries as $ThisTracking['IPAddr'] => $ThisTrackingArray) {
        /** Normalise in case of cache mechanism type which doesn't support returning expiries. */
        if (!is_array($ThisTrackingArray)) {
            $ThisTrackingArray = ['Time' => 0, 'Data' => $ThisTrackingArray];
        }

        /** Guard. */
        if (!isset($ThisTrackingArray['Time'], $ThisTrackingArray['Data']) || !is_scalar($ThisTrackingArray['Time']) || !is_scalar($ThisTrackingArray['Data'])) {
            continue;
        }

        /** Check whether normally blocked by signature files and/or auxiliary rules. */
        if ($this->FE['tracking-blocked-already'] || $this->FE['tracking-aux']) {
            $this->simulateBlockEvent($ThisTracking['IPAddr'], $this->FE['tracking-blocked-already'], false, false, false, false, $this->FE['tracking-aux']);
            $ThisTracking['Blocked'] = ($this->CIDRAM['Caught'] || $this->BlockInfo['SignatureCount']);
        } else {
            $ThisTracking['Blocked'] = false;
        }

        /** Hide banned/blocked IPs. */
        if ($this->FE['tracking-hide-banned-blocked'] && (
            $ThisTracking['Blocked'] || $ThisTrackingArray['Data'] >= $this->Configuration['signatures']['infraction_limit']
        )) {
            continue;
        }
        $ThisTracking['IPID'] = bin2hex($ThisTracking['IPAddr']);

        /** Set clearing option. */
        $ThisTracking['Options'] = sprintf(
            '<input type="button" class="auto" onclick="javascript:{window[\'IPAddr\']=\'%s\';' .
            '$(\'POST\',\'\',[\'IPAddr\'],function(){w(\'stateMsg\',\'%s\')},function(e){w(\'stateMsg\',e);' .
            'hideid(\'%s\')},function(e){w(\'stateMsg\',e)})}" value="%s" />',
            $ThisTracking['IPAddr'],
            $this->L10N->getString('label.Loading_'),
            $ThisTracking['IPID'],
            $this->L10N->getString('field.Clear')
        );

        /** When the entry expires. */
        if (!is_numeric($ThisTrackingArray['Time']) || $ThisTrackingArray['Time'] < 1) {
            $ThisTracking['Expiry'] = $this->L10N->getString('label.No data available');
        } else {
            $ThisTracking['Expiry'] = $this->timeFormat(
                $ThisTrackingArray['Time'],
                $this->Configuration['general']['time_format']
            ) . '<br />(' . $this->relativeTime($ThisTrackingArray['Time']) . ')';
        }

        if ($ThisTrackingArray['Data'] >= $this->Configuration['signatures']['infraction_limit']) {
            $ThisTracking['StatClass'] = 'txtRd';
            $ThisTracking['Status'] = $this->L10N->getString('field.Banned');
        } elseif ($ThisTrackingArray['Data'] >= ($this->Configuration['signatures']['infraction_limit'] / 2)) {
            $ThisTracking['StatClass'] = 'txtOe';
            $ThisTracking['Status'] = $this->L10N->getString('field.Tracking');
        } else {
            $ThisTracking['StatClass'] = 's';
            $ThisTracking['Status'] = $this->L10N->getString('field.Tracking');
        }
        if ($ThisTracking['Blocked']) {
            $ThisTracking['StatClass'] = 'txtRd';
            $ThisTracking['Status'] .= '/' . $this->L10N->getString('field.Blocked');
        }
        $ThisTracking['Status'] .= ' – ' . $this->NumberFormatter->format($ThisTrackingArray['Data'], 0);
        $ThisTracking['TrackingFilter'] = $this->FE['TrackingFilter'];
        $ThisTracking['IPAddrLink'] = (!empty($this->FE['CachedLogsLink']) && strpos($this->FE['CachedLogsLink'], 'logfile=') !== false) ? sprintf(
            '<a href="%s&search=%s">%s</a>',
            $this->FE['CachedLogsLink'],
            str_replace('=', '_', base64_encode($ThisTracking['IPAddr'])),
            $ThisTracking['IPAddr']
        ) : $ThisTracking['IPAddr'];
        if (
            isset($this->BlockInfo['SignatureCount'], $this->BlockInfo['WhyReason']) &&
            strlen($this->BlockInfo['WhyReason'])
        ) {
            $ThisTracking['Status'] .= '<hr /><em>' . $this->BlockInfo['WhyReason'] . '</em>';
            if (
                $this->FE['Flags'] &&
                preg_match_all('~\[([A-Z]{2})\]~', $ThisTracking['Status'], $ThisTracking['Matches']) &&
                !empty($ThisTracking['Matches'][1])
            ) {
                foreach ($ThisTracking['Matches'][1] as $ThisTracking['ThisMatch']) {
                    $ThisTracking['Status'] = str_replace(
                        '[' . $ThisTracking['ThisMatch'] . ']',
                        '<span class="flag ' . $ThisTracking['ThisMatch'] . '"><span></span></span>',
                        $ThisTracking['Status']
                    );
                }
            }
            unset($ThisTracking['Matches'], $ThisTracking['ThisMatch']);
        }
        $ThisTracking['ID'] = preg_replace('~[^\dA-Za-z]~', '_', $ThisTracking['IPAddr']);
        $this->FE['TrackingData'] .= $this->parseVars($ThisTracking, $this->FE['TrackingRow'], true);
    }
    unset($ThisTrackingArray, $ThisTracking);
}

/** Fix status display. */
if ($this->FE['state_msg']) {
    $this->FE['state_msg'] .= '<br />';
}

if ($this->FE['TrackingCount']) {
    $this->FE['TrackingCount'] .= ' ';
}

/** Calculate page load time (useful for debugging). */
$this->FE['ProcessTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
$this->FE['TrackingCount'] .= sprintf(
    $this->L10N->getPlural($this->FE['ProcessTime'], 'label.Page request completed in %s seconds'),
    '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['ProcessTime'], 3) . '</span>'
);

if ($this->FE['ASYNC']) {
    /** Send output (async). */
    echo $this->FE['state_msg'] . $this->FE['TrackingCount'];
} else {
    /** Parse output. */
    $this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_ip_tracking.html')), true);

    /** Send output. */
    echo $this->sendOutput();
}
