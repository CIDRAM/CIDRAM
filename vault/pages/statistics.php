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
 * This file: The statistics page (last modified: 2023.12.13).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'statistics' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.Statistics'), $this->L10N->getString('tip.Statistics'), false);

if (isset($this->Stages['Statistics:Enable'])) {
    /** Statistics have been counted since... */
    if (($Since = $this->Cache->getEntry('Statistics-Since')) === false) {
        $Since = $this->Now;
        $this->Cache->setEntry('Statistics-Since', $Since, 0);
    }
    $this->FE['Other-Since'] = $this->timeFormat($Since, $this->Configuration['general']['time_format']) . ' (' . $this->relativeTime($Since) . ')';
    unset($Since);
} else {
    $this->FE['Other-Since'] = '-';

    /** Display how to enable statistics if currently disabled. */
    $this->FE['state_msg'] .= '<span class="txtRd">' . $this->L10N->getString('tip.Statistics tracking is currently disabled') . '</span><br />';
}

/** Generate confirm button. */
$this->FE['Confirm-ClearAll'] = $this->generateConfirmation($this->L10N->getString('field.Clear all'), 'statForm');

/** Clear statistics. */
if (!empty($_POST['ClearStats']) && $this->Cache->deleteAllEntriesWhere('~^Statistics-~')) {
    $this->FE['state_msg'] .= $this->L10N->getString('response.Statistics cleared') . '<br />';
}

/** Fetch and process various statistics. */
foreach ([
    ['Blocked-IPv4', 'Blocked-Total'],
    ['Blocked-IPv6', 'Blocked-Total'],
    ['Blocked-Other', 'Blocked-Total'],
    ['Banned-IPv4', 'Banned-Total'],
    ['Banned-IPv6', 'Banned-Total'],
    ['Passed-IPv4', 'Passed-Total'],
    ['Passed-IPv6', 'Passed-Total'],
    ['Passed-Other', 'Passed-Total'],
    ['CAPTCHAs-Failed', 'CAPTCHAs-Total'],
    ['CAPTCHAs-Passed', 'CAPTCHAs-Total'],
    ['Reported-IPv4-OK', 'Reported-Total'],
    ['Reported-IPv4-Failed', 'Reported-Total'],
    ['Reported-IPv6-OK', 'Reported-Total'],
    ['Reported-IPv6-Failed', 'Reported-Total']
] as $TheseStats) {
    if (!isset($this->FE[$TheseStats[1]])) {
        $this->FE[$TheseStats[1]] = 0;
    }
    $Try = $this->Cache->getEntry('Statistics-' . $TheseStats[0]);
    if (!is_int($Try) || $Try < 1) {
        $Try = (int)$Try;
    }
    $this->FE[$TheseStats[1]] += $Try;
    $this->FE[$TheseStats[0]] = $this->NumberFormatter->format($Try);
    if (!isset($this->Stages['Statistics:Enable'], $this->StatisticsTracked[$TheseStats[0]])) {
        $this->FE[$TheseStats[0]] .= ' – ' . $this->L10N->getString('field.Not tracking');
    }
}

/** Attempt to parse the auxiliary rules file. */
if (!isset($this->CIDRAM['AuxData'])) {
    $this->CIDRAM['AuxData'] = [];
    $this->YAML->process($this->readFile($this->Vault . 'auxiliary.yml'), $this->CIDRAM['AuxData']);
}

$AuxRulesTracked = [];

/** Determine whether statistics for any auxiliary rules are being tracked. */
foreach ($this->CIDRAM['AuxData'] as $AuxRuleName => $AuxRuleData) {
    if (empty($AuxRuleData['Track this rule'])) {
        continue;
    }
    $AuxRulesTracked[] = $AuxRuleName;
}

/** Process auxiliary rules statistics. */
if (count($AuxRulesTracked) === 0) {
    $this->FE['AuxStats'] = '';
    $this->FE['AuxStatsForClipboard'] = '';
} else {
    $AuxRulesTotal = 0;
    $this->FE['AuxStats'] = sprintf(
        "\n      <tr><td class=\"center h4f\" colspan=\"2\"><div class=\"s\">%s</div></td></tr>",
        $this->L10N->getString('label.aux_triggered')
    );
    $this->FE['AuxStatsForClipboard'] = $this->L10N->getString('label.aux_triggered') . '\n';
    $MostRecent = $this->L10N->getString('label.Most recent') . $this->L10N->getString('pair_separator');
    foreach ($AuxRulesTracked as $AuxRuleName) {
        $Try = $this->Cache->getEntry('Statistics-Aux-' . $AuxRuleName);
        if (!is_int($Try) || $Try < 1) {
            $Try = (int)$Try;
        }
        $AuxRulesTotal += $Try;
        $Date = $this->Cache->getEntry('Statistics-Aux-' . $AuxRuleName . '-Most-Recent');
        if (!is_int($Date) || $Date < 1) {
            $Date = (int)$Date;
        }
        $Date = $MostRecent . $this->timeFormat($Date, $this->Configuration['general']['time_format']) . ' (' . $this->relativeTime($Date) . ')';
        $this->FE['AuxStats'] .= sprintf(
            "\n      <tr>\n        <td class=\"h3\"><div class=\"s\">%s</div></td>\n        <td class=\"h3f\"><div class=\"s canBreak\">%s%s</div></td>\n      </tr>",
            str_replace(['<', '>'], ['&lt;', '&gt;'], $AuxRuleName),
            $this->NumberFormatter->format($Try),
            $Try === 0 ? '' : '<br />' . $Date
        );
        $this->FE['AuxStatsForClipboard'] .= $AuxRuleName . ' – ' . $this->NumberFormatter->format($Try);
        if ($Try !== 0) {
            $this->FE['AuxStatsForClipboard'] .= ' – ' . $Date;
        }
        $this->FE['AuxStatsForClipboard'] .= '\n';
    }
    $AuxRulesTotal = $this->NumberFormatter->format($AuxRulesTotal);
    $this->FE['AuxStats'] .= sprintf(
        "\n      <tr>\n        <td class=\"h3\"><div class=\"s\">%s</div></td>\n        <td class=\"h3f\"><div class=\"s\">%s</div></td>\n      </tr>",
        $this->L10N->getString('label.Total'),
        $AuxRulesTotal
    );
    $this->FE['AuxStatsForClipboard'] = $this->escapeJsInHTML($this->FE['AuxStatsForClipboard']) . '\n';
}
unset($AuxRulesTotal, $AuxRuleData, $AuxRuleName, $AuxRulesTracked);

/** Fetch and process totals. */
foreach (['Blocked-Total', 'Banned-Total', 'Passed-Total', 'CAPTCHAs-Total', 'Reported-Total'] as $TheseStats) {
    $this->FE[$TheseStats] = $this->NumberFormatter->format($this->FE[$TheseStats]);
}

/** Active signature files. */
foreach ([
    ['ipv4', 'Other-ActiveIPv4', 'ClassActiveIPv4'],
    ['ipv6', 'Other-ActiveIPv6', 'ClassActiveIPv6'],
    ['modules', 'Other-ActiveModules', 'ClassActiveModules'],
    ['imports', 'Other-ActiveImports', 'ClassActiveImports'],
    ['events', 'Other-ActiveEvents', 'ClassActiveEvents']
] as $TheseStats) {
    if (empty($this->Configuration['components'][$TheseStats[0]])) {
        $this->FE[$TheseStats[1]] = $this->NumberFormatter->format(0);
        $this->FE[$TheseStats[2]] = 'txtRd';
    } else {
        $this->FE[$TheseStats[1]] = 0;
        $Path = $this->pathFromComponentType($TheseStats[0]);
        foreach (explode("\n", $this->Configuration['components'][$TheseStats[0]]) as $StatWorking) {
            if (strlen($StatWorking) && is_readable($Path . $StatWorking)) {
                $this->FE[$TheseStats[1]]++;
            }
        }
        $this->FE[$TheseStats[1]] = $this->NumberFormatter->format($this->FE[$TheseStats[1]]);
        $this->FE[$TheseStats[2]] = $this->FE[$TheseStats[1]] ? 'txtGn' : 'txtRd';
    }
}

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_statistics.html')), true);

/** Send output. */
echo $this->sendOutput();

/** Cleanup. */
unset($Path, $StatWorking, $Try, $TheseStats);
