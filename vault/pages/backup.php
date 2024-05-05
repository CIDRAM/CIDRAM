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
 * This file: The backup page (last modified: 2024.05.04).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'backup' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.Backup'), $this->L10N->getString('tip.Backup'));

$this->FE['size_config'] = filesize($this->FE['ActiveConfigFile']) ?: 0;
$this->FE['size_aux'] = filesize($this->Vault . 'auxiliary.yml') ?: 0;
$this->FE['size_metadata'] = filesize($this->Vault . 'installed.yml') ?: 0;
$this->formatFileSize($this->FE['size_config']);
$this->formatFileSize($this->FE['size_aux']);
$this->formatFileSize($this->FE['size_metadata']);
$this->FE['size_config'] = '[<span dir="ltr" class="txtRd">' . $this->FE['ActiveConfigFile'] . '</span>] – ' . $this->FE['size_config'];
$this->FE['size_aux'] = '[<span dir="ltr" class="txtRd">' . $this->Vault . 'auxiliary.yml</span>] – ' . $this->FE['size_aux'];
$this->FE['size_metadata'] = '[<span dir="ltr" class="txtRd">' . $this->Vault . 'installed.yml</span>] – ' . $this->FE['size_metadata'];

if (isset($_POST['bckpAct'])) {
    /** Export. */
    if ($_POST['bckpAct'] === 'export') {
        $Export = ['CIDRAM Version' => $this->ScriptVersion];
        $this->initialiseErrorHandler();

        /** Export configuration. */
        if (isset($_POST['doConfig']) && $_POST['doConfig'] === 'on') {
            $Export['Configuration'] = $this->Configuration;
        }

        /** Export auxiliary rules. */
        if (isset($_POST['doAux']) && $_POST['doAux'] === 'on') {
            if (!isset($this->CIDRAM['AuxData'])) {
                $this->CIDRAM['AuxData'] = [];
                $this->YAML->process($this->readFile($this->Vault . 'auxiliary.yml'), $this->CIDRAM['AuxData']);
            }
            if (isset($_POST['xprtName'])) {
                $Export['Auxiliary Rules'] = isset($this->CIDRAM['AuxData'][$_POST['xprtName']]) ? [$_POST['xprtName'] => $this->CIDRAM['AuxData'][$_POST['xprtName']]] : [];
            } else {
                $Export['Auxiliary Rules'] = $this->CIDRAM['AuxData'];
            }
        }

        /** Export component updates metadata. */
        if (isset($_POST['doMetadata']) && $_POST['doMetadata'] === 'on') {
            $Arr = [];
            $this->readInstalledMetadata($Arr);
            $Export['Components'] = array_keys($Arr);
        }

        /** Export IP tracking data. */
        if (isset($_POST['doTracking']) && $_POST['doTracking'] === 'on') {
            $Export['IP Tracking'] = $this->Cache->getAllEntriesWhere('~^Tracking-(.+)$~', '\1');
        }

        /** Export statistics. */
        if (isset($_POST['doStatistics']) && $_POST['doStatistics'] === 'on') {
            $Export['Statistics'] = $this->Cache->getAllEntriesWhere('~^Statistics-(.+)$~', '\1');
        }

        /** Build output. */
        $Export = $this->YAML->reconstruct($Export);
        $Filename = 'CIDRAM-v' . $this->ScriptVersion . '-Exported-' . date('Y-m-d-H-i-s', $this->Now) . '.yml';
        if (isset($_POST['doCompress']) && $_POST['doCompress'] === 'on' && $Export !== '') {
            $Export = gzencode($Export);
            $Filename .= '.gz';
        }
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: Binary');
        header('Content-disposition: attachment; filename="' . $Filename . '"');
        echo $Export;
        $this->restoreErrorHandler();
        $this->Events->fireEvent('final');
        die;
    }

    /** Import. */
    if ($_POST['bckpAct'] === 'import') {
        if (
            isset($_FILES['importFile']['name'], $_FILES['importFile']['tmp_name'], $_FILES['importFile']['error']) &&
            $_FILES['importFile']['error'] === UPLOAD_ERR_OK &&
            is_uploaded_file($_FILES['importFile']['tmp_name'])
        ) {
            $this->initialiseErrorHandler();
            $Try = $this->readFile($_FILES['importFile']['tmp_name']);
            if (substr($Try, 0, 2) === "\x1F\x8B") {
                $Try = gzdecode($Try);
            }
            $Import = [];
            if (substr($Try, 0, 6) === 'CIDRAM') {
                $this->YAML->process($Try, $Import);
            }
            $Try = false;
            if (!isset($Import['CIDRAM Version'])) {
                $this->FE['state_msg'] .= $this->L10N->getString('response.Failed to import') . '<br />';
            } else {
                /** Import configuration. */
                if (isset($_POST['doConfig']) && $_POST['doConfig'] === 'on') {
                    if ($this->CIDRAM['Operation']->singleCompare($Import['CIDRAM Version'], '<1.23|>=2 <2.10|>=4')) {
                        $this->FE['state_msg'] .= sprintf(
                            $this->L10N->getString('response.Can_t import from v%s data'),
                            $Import['CIDRAM Version']
                        ) . ' ' . $this->L10N->getString('response.Failed to update configuration') . '<br />';
                    } elseif (isset($Import['Configuration']) && is_array($Import['Configuration'])) {
                        if ($this->CIDRAM['Operation']->singleCompare($Import['CIDRAM Version'], '<3')) {
                            /** Renamed configuration directives (v1->v2->v3). */
                            foreach ([
                                'general' => [
                                    ['logfileApache', 'logfile_apache'],
                                    ['logfileSerialized', 'logfile_serialized'],
                                    ['timeOffset', 'time_offset'],
                                    ['timeFormat', 'time_format'],
                                    ['FrontEndLog', 'frontend_log'],
                                    ['forbid_on_block', 'http_response_header_code']
                                ],
                                'recaptcha' => [['logfile', 'recaptcha_log']],
                                'hcaptcha' => [['logfile', 'hcaptcha_log']],
                                'template_data' => [['Magnification', 'magnification']],
                                'PHPMailer' => [
                                    ['EventLog', 'event_log'],
                                    ['SkipAuthProcess', 'skip_auth_process'],
                                    ['Enable2FA', 'enable_two_factor'],
                                    ['Host', 'host'],
                                    ['Port', 'port'],
                                    ['SMTPSecure', 'smtp_secure'],
                                    ['SMTPAuth', 'smtp_auth'],
                                    ['Username', 'username'],
                                    ['Password', 'password'],
                                    ['setFromAddress', 'set_from_address'],
                                    ['setFromName', 'set_from_name'],
                                    ['addReplyToAddress', 'add_reply_to_address'],
                                    ['addReplyToName', 'add_reply_to_name']
                                ],
                            ] as $CatKey => $Cat) {
                                foreach ($Cat as $Pair) {
                                    if (isset($Import['Configuration'][$CatKey][$Pair[0]]) && !isset($Import['Configuration'][$CatKey][$Pair[1]])) {
                                        $Import['Configuration'][$CatKey][$Pair[1]] = $Import['Configuration'][$CatKey][$Pair[0]];
                                        unset($Import['Configuration'][$CatKey][$Pair[0]]);
                                    }
                                }
                            }
                            if (isset($Import['Configuration']['PHPMailer']) && !isset($Import['Configuration']['phpmailer'])) {
                                $Import['Configuration']['phpmailer'] = $Import['Configuration']['PHPMailer'];
                                unset($Import['Configuration']['PHPMailer']);
                            }

                            /** Moved configuration directives (v2->v3). */
                            $Import['Configuration'] = array_replace_recursive($Import['Configuration'], ['logging' => [
                                'standard_log' => $Import['Configuration']['general']['logfile'] ?? $this->Configuration['logging']['standard_log'],
                                'apache_style_log' => $Import['Configuration']['general']['logfile_apache'] ?? $this->Configuration['logging']['apache_style_log'],
                                'serialised_log' => $Import['Configuration']['general']['logfile_serialized'] ?? $this->Configuration['logging']['serialised_log'],
                                'error_log' => $Import['Configuration']['general']['error_log'] ?? $this->Configuration['logging']['error_log'],
                                'truncate' => $Import['Configuration']['general']['truncate'] ?? $this->Configuration['logging']['truncate'],
                                'log_rotation_limit' => $Import['Configuration']['general']['log_rotation_limit'] ?? $this->Configuration['logging']['log_rotation_limit'],
                                'log_rotation_action' => $Import['Configuration']['general']['log_rotation_action'] ?? $this->Configuration['logging']['log_rotation_action'],
                                'log_banned_ips' => $Import['Configuration']['general']['log_banned_ips'] ?? $this->Configuration['logging']['log_banned_ips'],
                                'log_sanitisation' => $Import['Configuration']['general']['log_sanitisation'] ?? $this->Configuration['logging']['log_sanitisation']
                            ], 'frontend' => [
                                'frontend_log' => $Import['Configuration']['general']['frontend_log'] ?? $this->Configuration['frontend']['frontend_log'],
                                'signatures_update_event_log' => $Import['Configuration']['general']['signatures_update_event_log'] ?? $this->Configuration['frontend']['signatures_update_event_log'],
                                'max_login_attempts' => $Import['Configuration']['general']['max_login_attempts'] ?? $this->Configuration['frontend']['max_login_attempts'],
                                'theme' => $Import['Configuration']['template_data']['theme'] ?? $this->Configuration['frontend']['theme'],
                                'magnification' => $Import['Configuration']['template_data']['magnification'] ?? $this->Configuration['frontend']['magnification'],
                                'enable_two_factor' => $Import['Configuration']['phpmailer']['enable_two_factor'] ?? $this->Configuration['frontend']['enable_two_factor']
                            ], 'components' => [
                                'ipv4' => $Import['Configuration']['signatures']['ipv4'] ?? $this->Configuration['components']['ipv4'],
                                'ipv6' => $Import['Configuration']['signatures']['ipv6'] ?? $this->Configuration['components']['ipv6'],
                                'modules' => $Import['Configuration']['signatures']['modules'] ?? $this->Configuration['components']['modules'],
                                'imports' => $Import['Configuration']['general']['config_imports'] ?? $this->Configuration['components']['imports'],
                                'events' => $Import['Configuration']['general']['events'] ?? $this->Configuration['components']['events']
                            ]]);

                            /** Deleted configuration directives (v1->v2->v3). */
                            foreach ([
                                'general' => [
                                    'config_imports', 'disable_cli', 'disable_frontend', 'empty_fields', 'error_log', 'error_log_stages', 'events', 'frontend_log',
                                    'hide_version', 'log_banned_ips', 'log_rotation_action', 'log_rotation_limit', 'log_sanitisation', 'logfile', 'logfile_apache', 'logfile_serialized',
                                    'maintenance_mode', 'max_login_attempts', 'omit_hostname', 'omit_ip', 'omit_ua', 'other_verification', 'protect_frontend', 'protect_frontend',
                                    'search_engine_verification', 'signatures_update_event_log', 'social_media_verification', 'truncate'
                                ],
                                'phpmailer' => ['enable_two_factor'],
                                'signatures' => [
                                    'block_attacks', 'block_bogons', 'block_cloud', 'block_generic', 'block_legal', 'block_malware', 'block_proxies', 'block_spam',
                                    'config_imports', 'events', 'ipv4', 'ipv6', 'modules', 'track_mode'
                                ]
                            ] as $CatKey => $Cat) {
                                foreach ($Cat as $Pair) {
                                    if (isset($Import['Configuration'][$CatKey])) {
                                        unset($Import['Configuration'][$CatKey][$Pair]);
                                    }
                                }
                            }
                        }

                        /** Normalisation and modified configuration directives (v2->v3). */
                        foreach (['general' => ['default_dns'], 'components' => ['ipv4', 'ipv6', 'modules', 'imports', 'events'], 'frontend' => ['remotes'], 'bypasses' => ['used']] as $CatKey => $Cat) {
                            foreach ($Cat as $Pair) {
                                if (isset($Import['Configuration'][$CatKey][$Pair])) {
                                    $Import['Configuration'][$CatKey][$Pair] = preg_replace(['~(?<=^|,)[\r\t ]+|[\r\t ]+(?=,|$)~', '~,~'], ['', "\n"], $Import['Configuration'][$CatKey][$Pair]);
                                }
                            }
                        }

                        unset($Pair, $Cat, $CatKey, $Import['Configuration']['Config Defaults'], $Import['Configuration']['Provide'], $Import['Configuration']['Links']);
                        $this->Configuration = array_replace_recursive($this->Configuration, $Import['Configuration']);
                        $this->FE['state_msg'] .= $this->L10N->getString($this->updateConfiguration() ? 'response.Configuration successfully updated' : 'response.Failed to update configuration') . '<br />';
                    } else {
                        $this->FE['state_msg'] .= $this->L10N->getString('response.Failed to update configuration') . '<br />';
                    }
                }

                /** Import auxiliary rules. */
                if (isset($_POST['doAux']) && $_POST['doAux'] === 'on') {
                    if (isset($Import['Auxiliary Rules']) && is_array($Import['Auxiliary Rules'])) {
                        if (!isset($this->CIDRAM['AuxData'])) {
                            $this->CIDRAM['AuxData'] = [];
                            $this->YAML->process($this->readFile($this->Vault . 'auxiliary.yml'), $this->CIDRAM['AuxData']);
                        }
                        if ($this->CIDRAM['Operation']->singleCompare($Import['CIDRAM Version'], '<3')) {
                            $this->callableRecursive($Import['Auxiliary Rules'], function (&$Arr, $Depth) {
                                if ($Depth === 2) {
                                    if (isset($Arr['Profile']) && !isset($Arr['Profiles'])) {
                                        $Arr['Profiles'] = $Arr['Profile'];
                                        unset($Arr['Profile']);
                                    }
                                }
                                return ($Depth < 3);
                            });
                        }
                        $this->CIDRAM['AuxData'] = array_replace($this->CIDRAM['AuxData'], $Import['Auxiliary Rules']);
                        if (
                            ($NewAuxData = $this->YAML->reconstruct($this->CIDRAM['AuxData'])) !== '' &&
                            ($Handle = fopen($this->Vault . 'auxiliary.yml', 'wb')) !== false
                        ) {
                            if ((fwrite($Handle, $NewAuxData)) !== false) {
                                $this->FE['state_msg'] .= $this->L10N->getString('response.Auxiliary rules successfully updated') . '<br />';
                            } else {
                                $this->FE['state_msg'] .= $this->L10N->getString('response.Failed to update auxiliary rules') . '<br />';
                            }
                            fclose($Handle);
                        } else {
                            $this->FE['state_msg'] .= $this->L10N->getString('response.Failed to update auxiliary rules') . '<br />';
                        }
                        unset($Handle, $NewAuxData);
                    } else {
                        $this->FE['state_msg'] .= $this->L10N->getString('response.Failed to update auxiliary rules') . '<br />';
                    }
                }

                /** Import component updates metadata. */
                if (isset($_POST['doMetadata']) && $_POST['doMetadata'] === 'on') {
                    if ($this->CIDRAM['Operation']->singleCompare($Import['CIDRAM Version'], '<3')) {
                        $this->FE['state_msg'] .= sprintf(
                            $this->L10N->getString('response.Can_t import from v%s data'),
                            $Import['CIDRAM Version']
                        ) . ' ' . $this->L10N->getString('response.Failed to install') . '<br />';
                    } elseif (isset($Import['Components']) && is_array($Import['Components'])) {
                        $this->Components = ['Meta' => [], 'Installed Versions' => ['PHP' => PHP_VERSION], 'Available Versions' => []];
                        $this->fetchRemotesData();
                        $this->readInstalledMetadata($this->Components['Meta']);
                        $this->checkVersions($this->Components['Meta'], $this->Components['Installed Versions']);
                        $this->checkVersions($this->Components['RemoteMeta'], $this->Components['Available Versions']);
                        $this->calculateShared();
                        $Try = [];
                        foreach ($Import['Components'] as $Component) {
                            if (!is_string($Component)) {
                                continue;
                            }
                            if (!isset($this->Components['Available Versions'][$Component])) {
                                $this->FE['state_msg'] .= '<code>' . $Component . '</code> – ' . $this->L10N->getString('response.Not available at the upstream') . '<br />';
                            } elseif (!isset($this->Components['Installed Versions'][$Component]) || $this->CIDRAM['Operation']->singleCompare(
                                $this->Components['Installed Versions'][$Component],
                                '<' . $this->Components['Available Versions'][$Component]
                            )) {
                                $Try[] = $Component;
                            } else {
                                $this->FE['state_msg'] .= '<code>' . $Component . '</code> – ' . $this->L10N->getString('response.Already up-to-date') . '<br />';
                            }
                        }

                        /** Trigger updates handler. */
                        $this->updatesHandler('update-component', $Try);

                        /** Trigger signatures update log event. */
                        if (!empty($this->CIDRAM['SignaturesUpdateEvent'])) {
                            $this->CIDRAM['SignaturesUpdateEvent'] = sprintf(
                                $this->L10N->getString('response.Signature files have been updated (%s)'),
                                $this->timeFormat(
                                    $this->CIDRAM['SignaturesUpdateEvent'],
                                    $this->Configuration['general']['time_format']
                                )
                            );
                            $this->Events->fireEvent('writeToSignaturesUpdateEventLog', $this->CIDRAM['SignaturesUpdateEvent']);
                        }
                    } else {
                        $this->FE['state_msg'] .= $this->L10N->getString('response.Failed to install') . '<br />';
                    }
                }

                /** Import IP tracking data. */
                if (isset($_POST['doTracking']) && $_POST['doTracking'] === 'on') {
                    if ($this->CIDRAM['Operation']->singleCompare($Import['CIDRAM Version'], '<3.3')) {
                        $this->FE['state_msg'] .= sprintf(
                            $this->L10N->getString('response.Can_t import from v%s data'),
                            $Import['CIDRAM Version']
                        ) . ' ' . $this->L10N->getString('response.Failed to update tracking') . '<br />';
                    } elseif (isset($Import['IP Tracking']) && is_array($Import['IP Tracking'])) {
                        $Success = false;
                        $Response = $this->L10N->getString('response.Added %s to tracking');
                        foreach ($Import['IP Tracking'] as $Key => $Values) {
                            if (!isset($Values['Data'], $Values['Time']) || $Values['Time'] < $this->Now) {
                                continue;
                            }
                            if ($this->Cache->setEntry('Tracking-' . $Key, $Values['Data'], $Values['Time'] - $this->Now)) {
                                $Success = true;
                                if (substr($Key, -12) !== '-MinimumTime') {
                                    $this->FE['state_msg'] .= sprintf($Response, $Key) . '<br />';
                                }
                            }
                        }
                        if (!$Success) {
                            $this->FE['state_msg'] .= $this->L10N->getString('response.Failed to update tracking') . '<br />';
                        }
                    } else {
                        $this->FE['state_msg'] .= $this->L10N->getString('response.Failed to update tracking') . '<br />';
                    }
                }

                /** Import statistics. */
                if (isset($_POST['doStatistics']) && $_POST['doStatistics'] === 'on') {
                    if ($this->CIDRAM['Operation']->singleCompare($Import['CIDRAM Version'], '<3.3')) {
                        $this->FE['state_msg'] .= sprintf(
                            $this->L10N->getString('response.Can_t import from v%s data'),
                            $Import['CIDRAM Version']
                        ) . ' ' . $this->L10N->getString('response.Failed to update statistics') . '<br />';
                    } elseif (isset($Import['Statistics']) && is_array($Import['Statistics'])) {
                        $Success = false;
                        foreach ($Import['Statistics'] as $Key => $Value) {
                            if (!$this->Cache->setEntry('Statistics-' . $Key, $Value, 0)) {
                                continue;
                            }
                            $Success = true;
                        }
                        $this->FE['state_msg'] .= $this->L10N->getString($Success ? 'response.Statistics updated' : 'response.Failed to update statistics') . '<br />';
                    } else {
                        $this->FE['state_msg'] .= $this->L10N->getString('response.Failed to update statistics') . '<br />';
                    }
                }
            }
            unset($Response, $Success, $Component, $Try, $Import);
            $this->restoreErrorHandler();
        } else {
            $this->FE['state_msg'] .= $this->L10N->getString('response.Failed to upload') . '<br />';
        }
    }
}

/** Calculate page load time (useful for debugging). */
$this->FE['ProcessTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
$this->FE['state_msg'] .= sprintf(
    $this->L10N->getPlural($this->FE['ProcessTime'], 'label.Page request completed in %s seconds'),
    '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['ProcessTime'], 3) . '</span>'
);

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_backup.html')), true);

/** Send output. */
echo $this->sendOutput();
