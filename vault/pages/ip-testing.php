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
 * This file: The IP testing page (last modified: 2024.03.18).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'ip-testing' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.IP Testing'), $this->L10N->getString('tip.IP Testing'));

/** Add flags CSS. */
if ($this->FE['Flags'] = file_exists($this->AssetsPath . 'frontend/flags.css')) {
    $this->FE['OtherHead'] .= "\n  <link rel=\"stylesheet\" type=\"text/css\" href=\"?cidram-page=flags\" />";
}

/** Template for result rows. */
$this->FE['IPTestRow'] = $this->readFile($this->getAssetPath('_ip_testing_row.html'));

/** Initialise results data. */
$this->FE['IPTestResults'] = '';

/** Switches for which stages to enable for the IP test. */
if (isset($_POST['ip-addr'])) {
    $TestsSwitch = !empty($_POST['TestsSwitch']);
    $ModuleSwitch = !empty($_POST['ModuleSwitch']);
    $SEVSwitch = !empty($_POST['SEVSwitch']);
    $SMVSwitch = !empty($_POST['SMVSwitch']);
    $OVSwitch = !empty($_POST['OVSwitch']);
    $AuxSwitch = !empty($_POST['AuxSwitch']);
} else {
    $TestsSwitch = true;
    $ModuleSwitch = false;
    $SEVSwitch = false;
    $SMVSwitch = false;
    $OVSwitch = false;
    $AuxSwitch = false;
}
$this->FE['TestsSwitch'] = $TestsSwitch ? ' checked' : '';
$this->FE['ModuleSwitch'] = $ModuleSwitch ? ' checked' : '';
$this->FE['SEVSwitch'] = $SEVSwitch ? ' checked' : '';
$this->FE['SMVSwitch'] = $SMVSwitch ? ' checked' : '';
$this->FE['OVSwitch'] = $OVSwitch ? ' checked' : '';
$this->FE['AuxSwitch'] = $AuxSwitch ? ' checked' : '';
$this->CIDRAM['isSensitive'] = !empty($_POST['SensitiveSwitch']);
$this->FE['SensitiveSwitch'] = $this->CIDRAM['isSensitive'] ? ' checked' : '';

/** Fetch and repopulate all fields. */
foreach (['ip-addr', 'ip-addr-focus', 'custom-query', 'custom-referrer', 'custom-ua', 'custom-ua-focus'] as $Field) {
    $this->FE[$Field] = isset($_POST[$Field]) ? str_replace(['&', '<', '>', '"'], ['&amp;', '&lt;', '&gt;', '&quot;'], $_POST[$Field]) : '';
}
unset($Field);

/** Determine the focus. */
if (isset($_POST['FocusSwitch']) && $_POST['FocusSwitch'] === 'UserAgent') {
    $this->FE['FocusSwitchIPAddress'] = '';
    $this->FE['FocusSwitchUserAgent'] = ' checked="true"';
    $this->FE['IPTestScripting'] = '<script type="text/javascript">hideid(\'IPAddrSwitch\');hideid(\'UASwitch\');</script>';
    $this->FE['ip-addr'] = '';
    $this->FE['custom-ua'] = '';
    $ForceUAFocus = true;
} else {
    $this->FE['FocusSwitchIPAddress'] = ' checked="true"';
    $this->FE['FocusSwitchUserAgent'] = '';
    $this->FE['IPTestScripting'] = '<script type="text/javascript">hideid(\'IPAddrFocusSwitch\');hideid(\'UAFocusSwitch\');</script>';
    $this->FE['ip-addr-focus'] = '';
    $this->FE['custom-ua-focus'] = '';
    $ForceUAFocus = false;
}

/** Set field label and ascertain the mode of testing. */
if (!$ForceUAFocus && ($this->FE['ip-addr'] !== '' || $this->FE['ip-addr-focus'] !== '' || ($this->FE['custom-ua'] === '' && $this->FE['custom-ua-focus'] === ''))) {
    $this->FE['TestItemLabel'] = $this->L10N->getString('field.IP address');
    $this->CIDRAM['TestMode'] = 1;
} else {
    $this->FE['TestItemLabel'] = $this->L10N->getString('field.User agent');
    $this->CIDRAM['TestMode'] = 2;
}
unset($ForceUAFocus);

/** Data has been submitted for testing. */
if (isset($_POST['ip-addr'])) {
    if ($this->CIDRAM['TestMode'] === 1) {
        $Working = array_unique(array_map(function ($IP) {
            return preg_replace('~[^\da-f:./]~i', '', $IP);
        }, explode("\n", str_replace("\r", '', $this->FE['ip-addr'] ?: $this->FE['ip-addr-focus']))));
    } else {
        $Working = explode("\n", str_replace("\r", '', $this->FE['custom-ua'] ?: $this->FE['custom-ua-focus']));
    }
    natsort($Working);
    $this->CIDRAM['ThisIP'] = [];

    /** Initialise stages. */
    $this->Stages = array_flip(explode("\n", $this->Configuration['general']['stages']));

    /** Initialise shorthand options. */
    $this->Shorthand = array_flip(explode("\n", $this->Configuration['signatures']['shorthand']));

    /** Iterate through the addresses given to test. */
    foreach ($Working as $this->CIDRAM['ThisIP']['IPAddress']) {
        if ($this->CIDRAM['ThisIP']['IPAddress'] === '') {
            continue;
        }
        $this->simulateBlockEvent($this->CIDRAM['ThisIP']['IPAddress'], $TestsSwitch, $ModuleSwitch, $SEVSwitch, $SMVSwitch, $OVSwitch, $AuxSwitch);
        if (
            !empty($this->CIDRAM['Caught']) ||
            ($this->CIDRAM['TestMode'] === 1 && $TestsSwitch && (empty($this->CIDRAM['LastTestIP']) || empty($this->CIDRAM['TestResults']))) ||
            !empty($this->CIDRAM['RunErrors']) ||
            !empty($this->CIDRAM['ModuleErrors']) ||
            !empty($this->CIDRAM['AuxErrors'])
        ) {
            $this->CIDRAM['ThisIP']['YesNo'] = $this->L10N->getString('field.Blocked') . $this->L10N->getString('pair_separator') . $this->L10N->getString('response.Error');
            $this->CIDRAM['ThisIP']['StatClass'] = 'txtOe';
            if (!empty($this->CIDRAM['AuxErrors'])) {
                $this->CIDRAM['AuxErrorCounts'] = [];
                foreach ($this->CIDRAM['AuxErrors'] as $this->CIDRAM['AuxError']) {
                    $this->CIDRAM['AuxError'][2] = 'auxiliary.yml';
                    if (!empty($this->CIDRAM['AuxError'][4])) {
                        $this->CIDRAM['AuxError'][2] .= ':' . $this->CIDRAM['AuxError'][4];
                    }
                    if (isset($this->CIDRAM['AuxErrorCounts'][$this->CIDRAM['AuxError'][2]])) {
                        $this->CIDRAM['AuxErrorCounts'][$this->CIDRAM['AuxError'][2]]++;
                    } else {
                        $this->CIDRAM['AuxErrorCounts'][$this->CIDRAM['AuxError'][2]] = 1;
                    }
                }
                arsort($this->CIDRAM['AuxErrorCounts']);
                foreach ($this->CIDRAM['AuxErrorCounts'] as $this->CIDRAM['AuxName'] => $this->CIDRAM['AuxError']) {
                    $this->CIDRAM['ThisIP']['YesNo'] .= sprintf(
                        ' – %s (%s)',
                        $this->CIDRAM['AuxName'],
                        $this->NumberFormatter->format($this->CIDRAM['AuxError'])
                    );
                }
                unset($this->CIDRAM['AuxName'], $this->CIDRAM['AuxError'], $this->CIDRAM['AuxErrorCounts'], $this->CIDRAM['AuxErrors']);
            }
            if (!empty($this->CIDRAM['ModuleErrors'])) {
                $this->CIDRAM['ModuleErrorCounts'] = [];
                foreach ($this->CIDRAM['ModuleErrors'] as $this->CIDRAM['ModuleError']) {
                    if (isset($this->CIDRAM['ModuleErrorCounts'][$this->CIDRAM['ModuleError'][2]])) {
                        $this->CIDRAM['ModuleErrorCounts'][$this->CIDRAM['ModuleError'][2]]++;
                    } else {
                        $this->CIDRAM['ModuleErrorCounts'][$this->CIDRAM['ModuleError'][2]] = 1;
                    }
                }
                arsort($this->CIDRAM['ModuleErrorCounts']);
                foreach ($this->CIDRAM['ModuleErrorCounts'] as $this->CIDRAM['ModuleName'] => $this->CIDRAM['ModuleError']) {
                    $this->CIDRAM['ThisIP']['YesNo'] .= sprintf(
                        ' – %s (%s)',
                        $this->CIDRAM['ModuleName'],
                        $this->NumberFormatter->format($this->CIDRAM['ModuleError'])
                    );
                }
                unset($this->CIDRAM['ModuleName'], $this->CIDRAM['ModuleError'], $this->CIDRAM['ModuleErrorCounts'], $this->CIDRAM['ModuleErrors']);
            }
            if (!empty($this->CIDRAM['RunErrors'])) {
                $this->CIDRAM['RunErrorCounts'] = [];
                foreach ($this->CIDRAM['RunErrors'] as $this->CIDRAM['RunError']) {
                    if ($this->CIDRAM['RunError'][2] === 'functions.php' && !empty($this->CIDRAM['RunError'][4])) {
                        $this->CIDRAM['RunError'][2] = $this->CIDRAM['RunError'][4];
                    }
                    if (isset($this->CIDRAM['RunErrorCounts'][$this->CIDRAM['RunError'][2]])) {
                        $this->CIDRAM['RunErrorCounts'][$this->CIDRAM['RunError'][2]]++;
                    } else {
                        $this->CIDRAM['RunErrorCounts'][$this->CIDRAM['RunError'][2]] = 1;
                    }
                }
                arsort($this->CIDRAM['RunErrorCounts']);
                foreach ($this->CIDRAM['RunErrorCounts'] as $this->CIDRAM['RunName'] => $this->CIDRAM['RunError']) {
                    $this->CIDRAM['ThisIP']['YesNo'] .= sprintf(
                        ' – %s (%s)',
                        $this->CIDRAM['RunName'],
                        $this->NumberFormatter->format($this->CIDRAM['RunError'])
                    );
                }
                unset($this->CIDRAM['RunName'], $this->CIDRAM['RunError'], $this->CIDRAM['RunErrorCounts'], $this->CIDRAM['RunErrors']);
            }
        } elseif ($this->BlockInfo['SignatureCount']) {
            $this->BlockInfo['WhyReason'] = preg_replace('~(?<=</span>\\),|]\\),)( )(?=[\dA-Za-z])~', '<br />', $this->BlockInfo['WhyReason']);
            $this->CIDRAM['ThisIP']['YesNo'] = $this->L10N->getString('field.Blocked') . $this->L10N->getString('pair_separator') . $this->L10N->getString('response._Yes') . ' – ' . $this->BlockInfo['WhyReason'];
            $this->CIDRAM['ThisIP']['StatClass'] = 'txtRd';
            if (
                $this->FE['Flags'] &&
                preg_match_all('~\[([A-Z]{2})\]~', $this->CIDRAM['ThisIP']['YesNo'], $this->CIDRAM['ThisIP']['Matches']) &&
                !empty($this->CIDRAM['ThisIP']['Matches'][1])
            ) {
                foreach ($this->CIDRAM['ThisIP']['Matches'][1] as $this->CIDRAM['ThisIP']['ThisMatch']) {
                    $this->CIDRAM['ThisIP']['YesNo'] = str_replace(
                        '[' . $this->CIDRAM['ThisIP']['ThisMatch'] . ']',
                        '<span class="flag ' . $this->CIDRAM['ThisIP']['ThisMatch'] . '"><span></span></span>',
                        $this->CIDRAM['ThisIP']['YesNo']
                    );
                }
            }
            if ($this->BlockInfo['Ignored']) {
                $this->CIDRAM['ThisIP']['YesNo'] .= sprintf(
                    ', +%s (%s)',
                    $this->L10N->getString('state_ignored'),
                    $this->BlockInfo['Ignored']
                );
            }
        } elseif ($this->BlockInfo['Ignored']) {
            $this->CIDRAM['ThisIP']['YesNo'] = $this->L10N->getString('field.Blocked') . $this->L10N->getString('pair_separator') . $this->L10N->getString('response._No') . ' (' . $this->L10N->getString('state_ignored') . ') ' . $this->BlockInfo['Ignored'];
            $this->CIDRAM['ThisIP']['StatClass'] = 'txtOe';
        } elseif (!empty($this->CIDRAM['Aux Redirect']) && !empty($this->CIDRAM['Aux Status Code'])) {
            $this->CIDRAM['ThisIP']['YesNo'] = $this->L10N->getString('field.Blocked') . $this->L10N->getString('pair_separator') . $this->L10N->getString('response._No') . ' (' . $this->L10N->getString('response.Redirected') . ')';
            $this->CIDRAM['ThisIP']['StatClass'] = 'txtOe';
        } else {
            $this->CIDRAM['ThisIP']['YesNo'] = $this->L10N->getString('field.Blocked') . $this->L10N->getString('pair_separator') . $this->L10N->getString('response._No');
            $this->CIDRAM['ThisIP']['StatClass'] = 'txtGn';
        }
        if ($this->CIDRAM['Aux Redirect'] && $this->CIDRAM['Aux Status Code']) {
            if ($this->CIDRAM['ThisIP']['StatClass'] === 'txtGn') {
                $this->CIDRAM['ThisIP']['StatClass'] = 'txtOe';
            }
            $this->CIDRAM['ThisIP']['YesNo'] .= '<br />' . $this->ltrInRtf(
                $this->L10N->getString('response.Redirected') . ' <' . $this->CIDRAM['Aux Status Code'] . '> ➡ <code>' . $this->CIDRAM['Aux Redirect'] . '</code>'
            );
        } elseif ($this->BlockInfo['SignatureCount'] && $this->Configuration['general']['silent_mode'] !== '') {
            $this->CIDRAM['ThisIP']['YesNo'] .= '<br />' . $this->ltrInRtf(
                $this->L10N->getString('response.Redirected') . ' <' . $this->Configuration['general']['silent_mode_response_header_code'] . '> ➡ <code>' . $this->Configuration['general']['silent_mode'] . '</code>'
            );
        }
        $this->CIDRAM['ThisIP']['YesNo'] .= '<br />' . $this->L10N->getString('field.Tracking') . $this->L10N->getString('pair_separator');
        if (isset($this->CIDRAM['Trackable'])) {
            if ($this->CIDRAM['Trackable']) {
                $this->CIDRAM['ThisIP']['YesNo'] .= $this->L10N->getString('response._Yes') . ' (++' . $this->L10N->getString('label.aux.Forcibly enable IP tracking') . ')';
            } else {
                $this->CIDRAM['ThisIP']['YesNo'] .= $this->L10N->getString('response._No') . ' (++' . $this->L10N->getString('label.aux.Forcibly disable IP tracking') . ')';
            }
        } else {
            $this->CIDRAM['ThisIP']['YesNo'] .= $this->L10N->getString((
                isset($this->Stages['Tracking:Enable']) &&
                $this->BlockInfo['Infractions'] > 0 &&
                $this->BlockInfo['SignatureCount'] > 0
            ) ? 'response._Yes' : 'response._No');
        }
        if ($this->CIDRAM['TestMode'] === 1) {
            $this->CIDRAM['ThisIP']['YesNo'] .= '<br />' . $this->L10N->getString('field.Banned') . $this->L10N->getString('pair_separator') . $this->L10N->getString($this->CIDRAM['Banned'] ? 'response._Yes' : 'response._No');
        }
        if (isset($this->CIDRAM['ThisStatusHTTP'])) {
            $this->CIDRAM['ThisIP']['YesNo'] .= '<br />' . $this->L10N->getString('field.Status code') . $this->L10N->getString('pair_separator') . $this->CIDRAM['ThisStatusHTTP'];
        }
        if (!empty($this->Configuration['recaptcha']['enabled'])) {
            $this->CIDRAM['ThisIP']['YesNo'] .= '<br />++' . $this->L10N->getString('label.aux.Mark for use with reCAPTCHA');
        }
        if (!empty($this->Configuration['recaptcha']['forcibly_disabled'])) {
            $this->CIDRAM['ThisIP']['YesNo'] .= '<br />++' . $this->L10N->getString('label.aux.Forcibly disable reCAPTCHA');
        }
        if (!empty($this->Configuration['hcaptcha']['enabled'])) {
            $this->CIDRAM['ThisIP']['YesNo'] .= '<br />++' . $this->L10N->getString('label.aux.Mark for use with HCaptcha');
        }
        if (!empty($this->Configuration['hcaptcha']['forcibly_disabled'])) {
            $this->CIDRAM['ThisIP']['YesNo'] .= '<br />++' . $this->L10N->getString('label.aux.Forcibly disable HCaptcha');
        }
        if (!empty($this->CIDRAM['Suppress output template'])) {
            $this->CIDRAM['ThisIP']['YesNo'] .= '<br />++' . $this->L10N->getString('label.aux.Suppress output template');
        }
        if (!empty($this->CIDRAM['Suppress logging'])) {
            $this->CIDRAM['ThisIP']['YesNo'] .= '<br />++' . $this->L10N->getString('label.aux.Suppress logging');
        }
        if (isset($this->CIDRAM['Tracking options override'])) {
            if ($this->CIDRAM['Tracking options override'] === 'extended') {
                $this->CIDRAM['ThisIP']['YesNo'] .= '<br />++' . $this->L10N->getString('label.aux.Enforce extended IP tracking options');
            } elseif ($this->CIDRAM['Tracking options override'] === 'default') {
                $this->CIDRAM['ThisIP']['YesNo'] .= '<br />++' . $this->L10N->getString('label.aux.Enforce default IP tracking options');
            }
        }
        if (is_array($this->Profiles) && count($this->Profiles)) {
            foreach ($this->Profiles as $Profile) {
                $this->CIDRAM['ThisIP']['YesNo'] .= '<br />++&lt;' . $Profile . '&gt;';
            }
        }
        $this->CIDRAM['ThisIP']['ID'] = preg_replace('~[^\dA-Za-z]~', '_', $this->CIDRAM['ThisIP']['IPAddress']);
        $this->CIDRAM['ThisIP']['IPAddressLink'] = (!empty($this->FE['CachedLogsLink']) && strpos($this->FE['CachedLogsLink'], 'logfile=') !== false) ? sprintf(
            '<a href="%s&search=%s">%s</a>',
            $this->FE['CachedLogsLink'],
            str_replace('=', '_', base64_encode($this->CIDRAM['ThisIP']['IPAddress'])),
            $this->CIDRAM['ThisIP']['IPAddress']
        ) : $this->CIDRAM['ThisIP']['IPAddress'];
        $this->FE['IPTestResults'] .= $this->parseVars($this->CIDRAM['ThisIP'], $this->FE['IPTestRow'], true);
    }
    unset($this->CIDRAM['ThisIP'], $Working, $this->CIDRAM['TestMode']);
}

/** Calculate page load time (useful for debugging). */
$this->FE['ProcessTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
$this->FE['state_msg'] .= sprintf(
    $this->L10N->getPlural($this->FE['ProcessTime'], 'label.Page request completed in %s seconds'),
    '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['ProcessTime'], 3) . '</span>'
);

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_ip_testing.html')), true);

/** Send output. */
echo $this->sendOutput();
