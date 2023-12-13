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
 * This file: The rate limiting page (last modified: 2023.12.13).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'rl' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.Rate Limiting'), $this->L10N->getString('tip.Rate Limiting'));

/** Maximum bandwidth for rate limiting. */
$RLMaxBandwidth = $this->readBytes($this->Configuration['rate_limiting']['max_bandwidth']);

$RLLowBandwidth = $RLMaxBandwidth > 0 ? floor($RLMaxBandwidth / 4) : 0;
$RLHighBandwidth = $RLMaxBandwidth > 0 ? ceil(($RLMaxBandwidth / 4) * 3) : 0;
$RLLowRequests = $this->Configuration['rate_limiting']['max_requests'] > 0 ? floor($this->Configuration['rate_limiting']['max_requests'] / 4) : 0;
$RLHighRequests = $this->Configuration['rate_limiting']['max_requests'] > 0 ? ceil(($this->Configuration['rate_limiting']['max_requests'] / 4) * 3) : 0;

/** For entries to appear on the page. */
$this->FE['Entries'] = '';

if (isset($this->Stages['RL:Enable']) && ($this->Configuration['rate_limiting']['max_requests'] > 0 || $RLMaxBandwidth > 0)) {
    if ($this->Cache->Using && $this->Cache->Using !== 'FF') {
        /** Get all entries for when using a non-flatfile cache strategy. */
        $Entries = $this->Cache->getAllEntriesWhere('~^rl(?:-.+)?$~');
    } else {
        /** Get all entries for when using a flatfile cache strategy. */
        $Entries = [];
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->Vault, \RecursiveDirectoryIterator::FOLLOW_SYMLINKS), \RecursiveIteratorIterator::SELF_FIRST) as $Item => $AllFiles) {
            if (preg_match('~rl\.dat$~i', $Item)) {
                $Entries['rl'] = $this->readFile($Item);
            } elseif (preg_match('~rl(?:-(.+))?\.dat$~i', $Item, $Matched)) {
                $Entries['rl-' . $Matched[1]] = $this->readFile($Item);
            }
        }
        unset($Matched, $Item, $AllFiles);
    }

    if (count($Entries) === 0) {
        /** Default message to display if there aren't any rate limiting records currently available. */
        $this->FE['Entries'] .= "\n" . sprintf('<tr><td class="center h4f" colspan="2"><div class="s">%s</div></td></tr>', $this->L10N->getString('label.No data available'));
    } else {
        /** Process all entries. */
        foreach ($Entries as $EntryName => $EntryData) {
            if ($EntryName === 'rl') {
                $this->FE['Entries'] .= "\n" . sprintf('<tr><td class="center h4f" colspan="2"><div class="s">%s</div></td></tr>', $this->L10N->getString('label.Current data'));
            } elseif (substr($EntryName, 0, 3) === 'rl-') {
                $this->FE['Entries'] .= "\n" . sprintf('<tr><td class="center h4f" colspan="2"><div class="s">%s</div></td></tr>', sprintf(
                    $this->L10N->getString('label.Current data for %s'),
                    substr($EntryName, 3)
                ));
            }
            $EntryData = $this->processRLUsage(is_array($EntryData) && isset($EntryData['Data']) ? $EntryData['Data'] : $EntryData);
            if (count($EntryData) === 0) {
                $this->FE['Entries'] .= "\n" . sprintf(
                    '<tr><td class="h3f" colspan="2"><div class="s">%s</div></td></tr>',
                    $this->L10N->getString('label.No data available')
                );
            }
            foreach ($EntryData as $Address => $EntryDetails) {
                $EntryDetails['Class'] = (
                    $EntryDetails['Bandwidth'] >= $RLMaxBandwidth ||
                    $EntryDetails['Requests'] >= $this->Configuration['rate_limiting']['max_requests']
                ) ? 'txtRd' : 's';
                if ($RLMaxBandwidth > 0) {
                    $EntryDetails['BandwidthUsed'] = $EntryDetails['Bandwidth'];
                    $EntryDetails['BandwidthAvailable'] = $RLMaxBandwidth - $EntryDetails['Bandwidth'];
                    if ($EntryDetails['BandwidthAvailable'] < 1) {
                        $EntryDetails['BandwidthAvailable'] = 0;
                    }
                    $this->formatFileSize($EntryDetails['BandwidthUsed']);
                    $this->formatFileSize($EntryDetails['BandwidthAvailable']);
                }
                $EntryDetails['Bandwidth'] = $RLMaxBandwidth > 0 ? sprintf(
                    '%s.<br /><meter min="0" max="%d" low="%d" high="%d" optimum="0" value="%d" style="width:100%%"></meter><br /><br />',
                    sprintf(
                        $this->L10N->getString('label.Bandwidth'),
                        $EntryDetails['BandwidthUsed'],
                        $EntryDetails['BandwidthAvailable']
                    ),
                    $RLMaxBandwidth,
                    $RLLowBandwidth,
                    $RLHighBandwidth,
                    $EntryDetails['Bandwidth']
                ) : '';
                $EntryDetails['RequestsAvailable'] = $this->Configuration['rate_limiting']['max_requests'] - $EntryDetails['Requests'];
                if ($EntryDetails['RequestsAvailable'] < 1) {
                    $EntryDetails['RequestsAvailable'] = 0;
                }
                $EntryDetails['Requests'] = $this->Configuration['rate_limiting']['max_requests'] > 0 ? sprintf(
                    '%s.<br /><meter min="0" max="%d" low="%d" high="%d" optimum="0" value="%d" style="width:100%%"></meter><br /><br />',
                    sprintf(
                        $this->L10N->getString('label.rl_requests'),
                        $this->NumberFormatter->format($EntryDetails['Requests']),
                        $this->NumberFormatter->format($EntryDetails['RequestsAvailable'])
                    ),
                    $this->Configuration['rate_limiting']['max_requests'],
                    $RLLowRequests,
                    $RLHighRequests,
                    $EntryDetails['Requests']
                ) : '';
                $this->FE['Entries'] .= "\n" . sprintf('<tr><td class="h3"><div class="%s">%s</div></td>', $EntryDetails['Class'], $Address);
                $this->FE['Entries'] .= "\n" . sprintf(
                    '<td class="h3f"><div class="s">%s%s%s</div></td></tr>',
                    $EntryDetails['Bandwidth'],
                    $EntryDetails['Requests'],
                    sprintf(
                        $this->L10N->getString('label.rl_when'),
                        $this->relativeTime($EntryDetails['Newest']),
                        $this->relativeTime($EntryDetails['Newest'] + $this->Configuration['rate_limiting']['allowance_period']->getAsSeconds()),
                        $this->relativeTime($EntryDetails['Oldest']),
                        $this->relativeTime($EntryDetails['Oldest'] + $this->Configuration['rate_limiting']['allowance_period']->getAsSeconds())
                    )
                );
            }
        }
        unset($EntryDetails, $Address, $EntryData, $EntryName);
    }
    unset($Entries);
} else {
    /** Display how to enable rate limiting if currently disabled. */
    $this->FE['state_msg'] .= '<span class="txtRd">' . $this->L10N->getString('tip.Rate limiting is currently disabled') . '</span><br />';
}

/** Calculate page load time (useful for debugging). */
$this->FE['ProcessTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
$this->FE['state_msg'] .= sprintf(
    $this->L10N->getPlural($this->FE['ProcessTime'], 'label.Page request completed in %s seconds'),
    '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['ProcessTime'], 3) . '</span>'
);

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_rl.html')), true);

/** Send output. */
echo $this->sendOutput();
