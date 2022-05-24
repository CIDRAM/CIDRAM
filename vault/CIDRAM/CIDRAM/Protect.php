<?php
/**
 * This file is an optional extension of the CIDRAM package.
 * Homepage: https://cidram.github.io/
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: Protect traits (last modified: 2022.05.24).
 */

namespace CIDRAM\CIDRAM;

trait Protect
{
    /**
     * Called via the main Core instance in order to begin the execution chain.
     *
     * @return void
     */
    public function protect()
    {
        /** Initialise stages. */
        $this->Stages = array_flip(explode("\n", $this->Configuration['general']['stages']));

        /** Usable by events to determine which part of the output generator we're at. */
        $this->Stage = '';

        /** Initialise cache. */
        $this->initialiseCache();

        /** Initialise an error handler. */
        $this->initialiseErrorHandler();

        /** Initialise statistics tracked. */
        $this->StatisticsTracked = array_flip(explode("\n", $this->Configuration['general']['statistics']));

        /** Reset bypass flags. */
        $this->resetBypassFlags();

        /** To be populated by webhooks. */
        $this->Webhooks = [];

        /** Reset request profiling. */
        $this->Profiles = [];

        /** Initialise statistics if necessary. */
        if (isset($this->Stages['Statistics:Enable'])) {
            $this->initialiseCacheSection('Statistics');
            if (!isset($this->Statistics['Other-Since'])) {
                $this->Statistics = [
                    'Other-Since' => $this->Now,
                    'Blocked-IPv4' => 0,
                    'Blocked-IPv6' => 0,
                    'Blocked-Other' => 0,
                    'Banned-IPv4' => 0,
                    'Banned-IPv6' => 0,
                    'Passed-IPv4' => 0,
                    'Passed-IPv6' => 0,
                    'Passed-Other' => 0,
                    'CAPTCHAs-Failed' => 0,
                    'CAPTCHAs-Passed' => 0
                ];
                $this->CIDRAM['Statistics-Modified'] = true;
            }
        }

        /** Prepare variables for block information (used if we kill the request). */
        $this->BlockInfo = [
            'ID' => $this->generateId(),
            'ScriptIdent' => $this->ScriptIdent,
            'DateTime' => $this->timeFormat($this->Now, $this->Configuration['general']['time_format']),
            'IPAddr' => $this->ipAddr,
            'IPAddrResolved' => $this->resolve6to4($this->ipAddr),
            'favicon' => $this->CIDRAM['favicon'],
            'favicon_extension' => $this->CIDRAM['favicon_extension'],
            'Query' => $this->CIDRAM['Query'],
            'Referrer' => empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'],
            'UA' => empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'],
            'SignatureCount' => 0,
            'Signatures' => '',
            'WhyReason' => '',
            'ReasonMessage' => '',
            'Infractions' => $this->CIDRAM['Tracking'][$this->BlockInfo['IPAddr']]['Count'] ?? 0,
            'ASNLookup' => 0,
            'CCLookup' => 'XX',
            'Verified' => '',
            'Expired' => '',
            'Ignored' => '',
            'Request_Method' => $_SERVER['REQUEST_METHOD'] ?? '',
            'xmlLang' => $this->Configuration['general']['lang']
        ];
        $this->BlockInfo['UALC'] = strtolower($this->BlockInfo['UA']);
        $this->BlockInfo['rURI'] = (
            (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') ||
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        ) ? 'https://' : 'http://';
        $this->BlockInfo['rURI'] .= $this->CIDRAM['HTTP_HOST'] ?: 'Unknown.Host';
        $this->BlockInfo['rURI'] .= $_SERVER['REQUEST_URI'] ?? '/';

        /** Initialise page output and block event logfile fields. */
        $this->CIDRAM['FieldTemplates'] = $this->Configuration['template_data'] + [
            'Logs' => '',
            'Output' => [],
            'captcha_api_include' => '',
            'captcha_div_include' => '',
        ];

        /** The normal blocking procedures should occur. */
        if (isset($this->Stages['Tests:Enable'])) {
            $this->Stage = 'Tests';

            /** Run all IPv4/IPv6 tests. */
            try {
                $this->CIDRAM['TestResults'] = $this->runTests($this->BlockInfo['IPAddr'], true);
            } catch (\Exception $e) {
                $this->Events->fireEvent('final');
                die($e->getMessage());
            }

            /** Run all IPv4/IPv6 tests for resolved IP address if necessary. */
            if ($this->BlockInfo['IPAddrResolved'] && $this->CIDRAM['TestResults'] && empty($this->CIDRAM['Whitelisted'])) {
                try {
                    $this->CIDRAM['TestResults'] = $this->runTests($this->BlockInfo['IPAddrResolved'], true);
                } catch (\Exception $e) {
                    $this->Events->fireEvent('final');
                    die($e->getMessage());
                }
            }

            /**
             * If all tests fail, report an invalid IP address.
             */
            if (!$this->CIDRAM['TestResults']) {
                $this->BlockInfo['ReasonMessage'] = $this->L10N->getString('ReasonMessage_BadIP');
                $this->BlockInfo['WhyReason'] = $this->L10N->getString('Short_BadIP');
                $this->BlockInfo['SignatureCount']++;
            }

            /**
             * Check whether we're tracking the IP due to previous instances of bad
             * behaviour.
             */
            elseif (isset($this->Stages['Tracking:Enable'])) {
                $this->Stage = 'Tracking';
                if (($this->BlockInfo['IPAddr'] && isset(
                    $this->CIDRAM['Tracking'][$this->BlockInfo['IPAddr']],
                    $this->CIDRAM['Tracking'][$this->BlockInfo['IPAddr']]['Count']
                ) && (
                    $this->CIDRAM['Tracking'][$this->BlockInfo['IPAddr']]['Count'] >= $this->Configuration['signatures']['infraction_limit']
                )) || ($this->BlockInfo['IPAddrResolved'] && isset(
                    $this->CIDRAM['Tracking'][$this->BlockInfo['IPAddrResolved']],
                    $this->CIDRAM['Tracking'][$this->BlockInfo['IPAddrResolved']]['Count']
                ) && (
                    $this->CIDRAM['Tracking'][$this->BlockInfo['IPAddrResolved']]['Count'] >= $this->Configuration['signatures']['infraction_limit']
                ))) {
                    $this->CIDRAM['Banned'] = true;
                    $this->BlockInfo['ReasonMessage'] = $this->L10N->getString('ReasonMessage_Banned');
                    $this->BlockInfo['WhyReason'] = $this->L10N->getString('Short_Banned');
                    $this->BlockInfo['SignatureCount']++;
                }
            }
            $this->Stage = '';
        }

        if (isset($this->Stages['Tests:Tracking']) && $this->BlockInfo['SignatureCount'] > 0) {
            $this->BlockInfo['Infractions'] += $this->BlockInfo['SignatureCount'];
        }

        /** Perform forced hostname lookup if this has been enabled. */
        if ($this->Configuration['general']['force_hostname_lookup']) {
            $this->CIDRAM['Hostname'] = dnsReverse($this->BlockInfo['IPAddrResolved'] ?: $this->BlockInfo['IPAddr']);
        }

        /** Executed only if maintenance mode is disabled. */
        if (empty($this->CIDRAM['Whitelisted'])) {
            /** Instantiate report orchestrator (used by some modules). */
            $this->Reporter = new \CIDRAM\CIDRAM\Reporter();

            /** Identify proxy connections (conjunctive reporting element). */
            if (strpos($this->BlockInfo['WhyReason'], $this->L10N->getString('Short_Proxy')) !== false) {
                $this->Reporter->report([9, 13], [], $this->BlockInfo['IPAddr']);
            }

            /** Execute modules, if any have been enabled. */
            if (empty($this->CIDRAM['Whitelisted']) && $this->Configuration['components']['modules'] && isset($this->Stages['Modules:Enable'])) {
                $this->Stage = 'Modules';
                if (!isset($this->CIDRAM['ModuleResCache'])) {
                    $this->CIDRAM['ModuleResCache'] = [];
                }
                $Modules = explode("\n", $this->Configuration['components']['modules']);
                if (!$this->Configuration['signatures']['tracking_override']) {
                    $this->CIDRAM['Restore tracking options override'] = $this->CIDRAM['Tracking options override'] ?? '';
                }

                /**
                 * Doing this with array_walk instead of foreach to ensure that modules
                 * have their own scope and that superfluous data isn't preserved.
                 */
                array_walk($Modules, function ($Module): void {
                    if (!empty($this->CIDRAM['Whitelisted'])) {
                        return;
                    }
                    $Module = (strpos($Module, ':') === false) ? $Module : substr($Module, strpos($Module, ':') + 1);
                    $Before = $this->BlockInfo['SignatureCount'];
                    if (isset($this->CIDRAM['ModuleResCache'][$Module]) && is_object($this->CIDRAM['ModuleResCache'][$Module])) {
                        $this->CIDRAM['ModuleResCache'][$Module]();
                    } elseif (!file_exists($this->ModulesPath . $Module) || !is_readable($this->ModulesPath . $Module)) {
                        return;
                    } else {
                        require $this->ModulesPath . $Module;
                    }
                    if (isset($this->Stages['Modules:Tracking']) && $this->BlockInfo['SignatureCount'] > $Before) {
                        $this->BlockInfo['Infractions'] += $this->BlockInfo['SignatureCount'] - $Before;
                    }
                });

                if (
                    !$this->Configuration['signatures']['tracking_override'] &&
                    !empty($this->CIDRAM['Tracking options override']) &&
                    isset($this->CIDRAM['Restore tracking options override'])
                ) {
                    $this->CIDRAM['Tracking options override'] = $this->CIDRAM['Restore tracking options override'];
                    unset($this->CIDRAM['Restore tracking options override']);
                }
                unset($Modules);
            }

            /** Execute search engine verification. */
            if (empty($this->CIDRAM['Whitelisted']) && isset($this->Stages['searchEngineVerification:Enable'])) {
                $this->Stage = 'searchEngineVerification';
                $this->searchEngineVerification();
            }

            /** Execute social media verification. */
            if (empty($this->CIDRAM['Whitelisted']) && isset($this->Stages['socialMediaVerification:Enable'])) {
                $this->Stage = 'socialMediaVerification';
                $this->socialMediaVerification();
            }

            /** Execute other verification. */
            if (empty($this->CIDRAM['Whitelisted']) && isset($this->Stages['otherVerification:Enable'])) {
                $this->Stage = 'otherVerification';
                $this->otherVerification();
            }

            /** Process auxiliary rules, if any exist. */
            if (empty($this->CIDRAM['Whitelisted']) && isset($this->Stages['Aux:Enable'])) {
                $Before = $this->BlockInfo['SignatureCount'];
                $this->Stage = 'Aux';
                $this->aux();
                if (isset($this->Stages['Aux:Tracking']) && $this->BlockInfo['SignatureCount'] > $Before) {
                    $this->BlockInfo['Infractions'] += $this->BlockInfo['SignatureCount'] - $Before;
                }
                unset($Before);
            }

            /** Process all reports (if any exist, and if not whitelisted), and then destroy the reporter. */
            if (empty($this->CIDRAM['Whitelisted']) && isset($this->Stages['Reporting:Enable'])) {
                $this->Stage = 'Reporting';
                $this->Reporter->process();
            }

            /** Cleanup. */
            $this->Reporter = null;
        }

        /** Process tracking information for the inbound IP. */
        if (!empty($this->CIDRAM['TestResults']) && $this->BlockInfo['Infractions'] > 0 && isset($this->Stages['Tracking:Enable'])) {
            $this->Stage = 'Tracking';

            /** Set tracking expiry. */
            $this->CIDRAM['TrackTime'] = $this->Now + ($this->Configuration['Options']['TrackTime'] ?? $this->Configuration['signatures']['default_tracktime']);

            /** Number of infractions to append. */
            if (
                isset($this->CIDRAM['Tracking'][$this->BlockInfo['IPAddr']]['Count']) &&
                $this->CIDRAM['Tracking'][$this->BlockInfo['IPAddr']]['Count'] > $this->BlockInfo['Infractions']
            ) {
                $this->CIDRAM['TrackCount'] = $this->CIDRAM['Tracking'][$this->BlockInfo['IPAddr']]['Count'] - $this->BlockInfo['Infractions'];
            } else {
                $this->CIDRAM['TrackCount'] = $this->BlockInfo['Infractions'];
            }

            /** Tracking options override. */
            if (!empty($this->CIDRAM['Tracking options override'])) {
                if ($this->CIDRAM['Tracking options override'] === 'extended') {
                    $this->CIDRAM['TrackTime'] = $this->Now + floor($this->Configuration['signatures']['default_tracktime'] * 52.1428571428571);
                    $this->CIDRAM['TrackCount'] *= 1000;
                } elseif ($this->CIDRAM['Tracking options override'] === 'default') {
                    $this->CIDRAM['TrackTime'] = $this->Now + $this->Configuration['signatures']['default_tracktime'];
                    $this->CIDRAM['TrackCount'] = 1;
                }
            }

            if (isset(
                $this->CIDRAM['Tracking'][$this->BlockInfo['IPAddr']]['Count'],
                $this->CIDRAM['Tracking'][$this->BlockInfo['IPAddr']]['Time']
            )) {
                $this->CIDRAM['Tracking'][$this->BlockInfo['IPAddr']]['Count'] += $this->CIDRAM['TrackCount'];
                if ($this->CIDRAM['TrackTime'] > $this->CIDRAM['Tracking'][$this->BlockInfo['IPAddr']]['Time']) {
                    $this->CIDRAM['Tracking'][$this->BlockInfo['IPAddr']]['Time'] = $this->CIDRAM['TrackTime'];
                }
            } else {
                $this->CIDRAM['Tracking'][$this->BlockInfo['IPAddr']] = [
                    'Count' => $this->CIDRAM['TrackCount'],
                    'Time' => $this->CIDRAM['TrackTime']
                ];
            }
            $this->CIDRAM['Tracking-Modified'] = true;

            if ($this->CIDRAM['Tracking'][$this->BlockInfo['IPAddr']]['Count'] >= $this->Configuration['signatures']['infraction_limit']) {
                $this->CIDRAM['Banned'] = true;
            }

            /** Cleanup. */
            unset($this->CIDRAM['TrackCount'], $this->CIDRAM['TrackTime']);
        }

        /**
         * Process rate limiting, if it's active. This feature exists for those that
         * need it, but I really don't recommend using this feature if at all possible.
         */
        if (isset($this->Stages['RL:Enable'])) {
            $this->Stage = 'RL';

            /** Maximum bandwidth for rate limiting. */
            $RLMaxBandwidth = $this->readBytes($this->Configuration['rate_limiting']['max_bandwidth']);

            if (isset($this->Stages['RL:Enable']) && (
                $this->Configuration['rate_limiting']['max_requests'] > 0 ||
                $RLMaxBandwidth > 0
            ) && empty($this->CIDRAM['Whitelisted']) && isset($this->CIDRAM['Factors']) && (!$this->Configuration['rate_limiting']['exceptions'] || (
                !($this->BlockInfo['Verified'] && preg_match('~(?:^|\n)Verified(?:\n|$)~', $this->Configuration['rate_limiting']['exceptions'])) &&
                !(!empty($this->CIDRAM['Whitelisted']) && preg_match('~(?:^|\n)Whitelisted(?:\n|$)~', $this->Configuration['rate_limiting']['exceptions']))
            ))) {
                if (
                    $this->CIDRAM['LastTestIP'] === 4 &&
                    $this->Configuration['rate_limiting']['precision_ipv4'] > 0 &&
                    $this->Configuration['rate_limiting']['precision_ipv4'] < 33 &&
                    isset($this->CIDRAM['Factors'][$this->Configuration['rate_limiting']['precision_ipv4'] - 1])
                ) {
                    $this->CIDRAM['RL_Capture'] = $this->CIDRAM['Factors'][$this->Configuration['rate_limiting']['precision_ipv4'] - 1];
                } elseif (
                    $this->CIDRAM['LastTestIP'] === 6 &&
                    $this->Configuration['rate_limiting']['precision_ipv6'] > 0 &&
                    $this->Configuration['rate_limiting']['precision_ipv6'] < 129 &&
                    isset($this->CIDRAM['Factors'][$this->Configuration['rate_limiting']['precision_ipv6'] - 1])
                ) {
                    $this->CIDRAM['RL_Capture'] = $this->CIDRAM['Factors'][$this->Configuration['rate_limiting']['precision_ipv6'] - 1];
                }
                if (!empty($this->CIDRAM['RL_Capture'])) {
                    $this->CIDRAM['RL_Capture'] = pack('l*', strlen($this->CIDRAM['RL_Capture'])) . $this->CIDRAM['RL_Capture'];
                    $this->rateLimitFetch();
                    if (strlen($this->CIDRAM['RL_Data']) > 4) {
                        $this->CIDRAM['RL_Expired'] = $this->Now - ($this->Configuration['rate_limiting']['allowance_period'] * 3600);
                        $this->CIDRAM['RL_Oldest'] = unpack('l*', substr($this->CIDRAM['RL_Data'], 0, 4));
                        if ($this->CIDRAM['RL_Oldest'][1] < $this->CIDRAM['RL_Expired']) {
                            $this->rateLimitClean();
                        }
                        $this->CIDRAM['RL_Usage'] = $this->rateGetUsage();
                        if ($this->trigger((
                            ($RLMaxBandwidth > 0 && $this->CIDRAM['RL_Usage']['Bytes'] >= $RLMaxBandwidth) ||
                            ($this->Configuration['rate_limiting']['max_requests'] > 0 && $this->CIDRAM['RL_Usage']['Requests'] >= $this->Configuration['rate_limiting']['max_requests'])
                        ), $this->L10N->getString('Short_RL'))) {
                            $this->Configuration['recaptcha']['usemode'] = 0;
                            $this->Configuration['recaptcha']['enabled'] = false;
                            $this->Configuration['hcaptcha']['usemode'] = 0;
                            $this->Configuration['hcaptcha']['enabled'] = false;
                            $this->CIDRAM['RL_Status'] = $this->getStatusHTTP(429);
                        }
                        unset($this->CIDRAM['RL_Usage'], $this->CIDRAM['RL_Oldest'], $this->CIDRAM['RL_Expired']);
                    }
                    $this->CIDRAM['RL_Size'] = 0;
                    ob_start(function ($In) {
                        $this->CIDRAM['RL_Size'] += strlen($In);
                        return $In;
                    }, 1);
                    register_shutdown_function(function () {
                        $this->rateLimitWriteEvent($this->CIDRAM['RL_Capture'], $this->CIDRAM['RL_Size']);
                        $this->destroyCacheObject();
                        ob_end_flush();
                    });
                }
            }
        }

        /** This code block only executed if signatures were triggered. */
        if (isset($this->Stages['CAPTCHA:Enable']) && $this->BlockInfo['SignatureCount'] > 0) {
            $this->Stage = 'CAPTCHA';

            if (
                !empty($this->Configuration['recaptcha']['sitekey']) &&
                !empty($this->Configuration['recaptcha']['secret']) &&
                empty($this->CIDRAM['Banned']) &&
                $this->BlockInfo['SignatureCount'] <= $this->Configuration['recaptcha']['signature_limit'] &&
                (
                    $this->Configuration['recaptcha']['usemode'] === 1 ||
                    $this->Configuration['recaptcha']['usemode'] === 3 ||
                    (
                        (
                            $this->Configuration['recaptcha']['usemode'] === 2 ||
                            $this->Configuration['recaptcha']['usemode'] === 5
                        ) && !empty($this->Configuration['recaptcha']['enabled'])
                    )
                )
            ) {
                /** Execute the ReCaptcha class. */
                $CaptchaDone = new ReCaptcha($this);
            } elseif (
                !empty($this->Configuration['hcaptcha']['sitekey']) &&
                !empty($this->Configuration['hcaptcha']['secret']) &&
                empty($this->CIDRAM['Banned']) &&
                $this->BlockInfo['SignatureCount'] <= $this->Configuration['hcaptcha']['signature_limit'] &&
                (
                    $this->Configuration['hcaptcha']['usemode'] === 1 ||
                    $this->Configuration['hcaptcha']['usemode'] === 3 ||
                    (
                        (
                            $this->Configuration['hcaptcha']['usemode'] === 2 ||
                            $this->Configuration['hcaptcha']['usemode'] === 5
                        ) && !empty($this->Configuration['hcaptcha']['enabled'])
                    )
                )
            ) {
                /** Execute the HCaptcha class. */
                $CaptchaDone = new HCaptcha($this);
            }
        }

        /** Update statistics if necessary. */
        if (isset($this->Stages['Statistics:Enable'])) {
            $this->Stage = 'Statistics';
            if ($this->BlockInfo['SignatureCount'] > 0) {
                if (!empty($this->CIDRAM['Banned'])) {
                    if ($this->BlockInfo['IPAddrResolved']) {
                        if (isset($this->StatisticsTracked['Banned-IPv4'])) {
                            $this->Statistics['Banned-IPv4']++;
                            $this->CIDRAM['Statistics-Modified'] = true;
                        }
                        if (isset($this->StatisticsTracked['Banned-IPv6'])) {
                            $this->Statistics['Banned-IPv6']++;
                            $this->CIDRAM['Statistics-Modified'] = true;
                        }
                    } elseif ($this->CIDRAM['LastTestIP'] === 4) {
                        if (isset($this->StatisticsTracked['Banned-IPv4'])) {
                            $this->Statistics['Banned-IPv4']++;
                            $this->CIDRAM['Statistics-Modified'] = true;
                        }
                    } elseif ($this->CIDRAM['LastTestIP'] === 6) {
                        if (isset($this->StatisticsTracked['Banned-IPv6'])) {
                            $this->Statistics['Banned-IPv6']++;
                            $this->CIDRAM['Statistics-Modified'] = true;
                        }
                    }
                } elseif ($this->BlockInfo['IPAddrResolved']) {
                    if (isset($this->StatisticsTracked['Blocked-IPv4'])) {
                        $this->Statistics['Blocked-IPv4']++;
                        $this->CIDRAM['Statistics-Modified'] = true;
                    }
                    if (isset($this->StatisticsTracked['Blocked-IPv6'])) {
                        $this->Statistics['Blocked-IPv6']++;
                        $this->CIDRAM['Statistics-Modified'] = true;
                    }
                } elseif ($this->CIDRAM['LastTestIP'] === 4) {
                    if (isset($this->StatisticsTracked['Blocked-IPv4'])) {
                        $this->Statistics['Blocked-IPv4']++;
                        $this->CIDRAM['Statistics-Modified'] = true;
                    }
                } elseif ($this->CIDRAM['LastTestIP'] === 6) {
                    if (isset($this->StatisticsTracked['Blocked-IPv6'])) {
                        $this->Statistics['Blocked-IPv6']++;
                        $this->CIDRAM['Statistics-Modified'] = true;
                    }
                } else {
                    if (isset($this->StatisticsTracked['Blocked-Other'])) {
                        $this->Statistics['Blocked-Other']++;
                        $this->CIDRAM['Statistics-Modified'] = true;
                    }
                }
            } else {
                if ($this->CIDRAM['LastTestIP'] === 4) {
                    if (isset($this->StatisticsTracked['Passed-IPv4'])) {
                        $this->Statistics['Passed-IPv4']++;
                        $this->CIDRAM['Statistics-Modified'] = true;
                    }
                } elseif ($this->CIDRAM['LastTestIP'] === 6) {
                    if (isset($this->StatisticsTracked['Passed-IPv6'])) {
                        $this->Statistics['Passed-IPv6']++;
                        $this->CIDRAM['Statistics-Modified'] = true;
                    }
                } else {
                    if (isset($this->StatisticsTracked['Passed-Other'])) {
                        $this->Statistics['Passed-Other']++;
                        $this->CIDRAM['Statistics-Modified'] = true;
                    }
                }
            }
        }

        /** Destroy cache object and some related values. */
        if (empty($this->CIDRAM['RL_Capture'])) {
            $this->destroyCacheObject();
        }

        /** Process webhooks. */
        if (isset($this->Stages['Webhooks:Enable']) && !empty($this->Webhooks) || !empty($this->Configuration['Webhook']['URL'])) {
            $this->Stage = 'Webhooks';

            /** Safety. */
            if (!isset($this->Configuration['Webhook'])) {
                $this->Configuration['Webhook'] = [];
            }

            /** Merge webhooks defined by signature files with webhooks defined by auxiliary rules. */
            if (!empty($this->Configuration['Webhook']['URL'])) {
                $this->arrayify($this->Configuration['Webhook']['URL']);
                $this->Webhooks = array_merge($this->Webhooks, $this->Configuration['Webhook']['URL']);
            }

            /** Block information copied here to be further processed for sending with the request. */
            $this->CIDRAM['ParsedToWebhook'] = $this->BlockInfo;

            /** Some further processing. */
            foreach ($this->CIDRAM['ParsedToWebhook'] as &$this->CIDRAM['WebhookVar']) {
                $this->CIDRAM['WebhookVar'] = urlencode($this->CIDRAM['WebhookVar']);
            }

            /** Set timeout. */
            $this->CIDRAM['WebhookTimeout'] = $this->Configuration['Webhook']['Timeout'] ?? $this->Request->DefaultTimeout;

            /** Process any special parameters. */
            if (empty($this->Configuration['Webhook']['Params'])) {
                $this->CIDRAM['WebhookParams'] = [];
            } else {
                $this->CIDRAM['WebhookParams'] = $this->Configuration['Webhook']['Params'];
                $this->arrayify($this->CIDRAM['WebhookParams']);

                /** Parse any relevant block information to our webhook params. */
                $this->CIDRAM['WebhookParams'] = $this->parseVars(
                    $this->CIDRAM['ParsedToWebhook'],
                    $this->Configuration['Webhook']['Params']
                );
            }

            /** Merge with block information. */
            $this->CIDRAM['WebhookParams'] = array_merge($this->BlockInfo, $this->CIDRAM['WebhookParams']);

            /** Remove useless parameters. */
            unset($this->CIDRAM['WebhookParams']['favicon'], $this->CIDRAM['WebhookParams']['favicon_extension']);

            /** Iterate through each webhook. */
            foreach ($this->Webhooks as $this->CIDRAM['Webhook']) {
                /** Safety. */
                if (!is_string($this->CIDRAM['Webhook'])) {
                    continue;
                }

                /** Parse any relevant block information to our webhooks. */
                $this->CIDRAM['Webhook'] = $this->parseVars($this->CIDRAM['ParsedToWebhook'], $this->CIDRAM['Webhook']);

                /** Perform request. */
                $this->CIDRAM['Webhook'] = $this->Request->request(
                    $this->CIDRAM['Webhook'],
                    $this->CIDRAM['WebhookParams'],
                    $this->CIDRAM['WebhookTimeout']
                );
            }

            /** Cleanup. */
            unset(
                $this->CIDRAM['Webhook'],
                $this->CIDRAM['WebhookParams'],
                $this->CIDRAM['WebhookTimeout'],
                $this->CIDRAM['WebhookVar'],
                $this->CIDRAM['ParsedToWebhook'],
                $this->Configuration['Webhook']
            );
        }

        /** Clearing because intermediary. */
        $this->Stage = '';

        /** A fix for correctly displaying LTR/RTL text. */
        if ($this->L10N->getString('Text Direction') !== 'rtl') {
            $this->L10N->Data['Text Direction'] = 'ltr';
            $this->CIDRAM['FieldTemplates']['textBlockAlign'] = 'text-align:left;';
            $this->CIDRAM['FieldTemplates']['textBlockFloat'] = '';
        } else {
            $this->CIDRAM['FieldTemplates']['textBlockAlign'] = 'text-align:right;';
            $this->CIDRAM['FieldTemplates']['textBlockFloat'] = 'float:right;';
        }

        /** If any signatures were triggered, log it, generate output, then die. */
        if ($this->BlockInfo['SignatureCount'] > 0) {
            if (isset($this->Stages['PrepareFields:Enable'])) {
                $this->Stage = 'PrepareFields';

                /** Set CAPTCHA status. */
                if (empty($this->BlockInfo['CAPTCHA'])) {
                    $this->BlockInfo['CAPTCHA'] = $this->L10N->getString('state_disabled');
                }

                /** IP address pseudonymisation. */
                if ($this->Configuration['legal']['pseudonymise_ip_addresses'] && $this->CIDRAM['TestResults']) {
                    $this->BlockInfo['IPAddr'] = $this->pseudonymiseIp($this->BlockInfo['IPAddr']);
                    if ($this->BlockInfo['IPAddrResolved']) {
                        $this->BlockInfo['IPAddrResolved'] = $this->pseudonymiseIp($this->BlockInfo['IPAddrResolved']);
                    }
                }

                /** Initialise fields. */
                $this->CIDRAM['Fields'] = array_flip(explode("\n", $this->Configuration['general']['fields']));

                $this->BlockInfo['Infractions'] = 0;
                if (isset($this->CIDRAM['Tracking'][$this->BlockInfo['IPAddr']]['Count'])) {
                    $this->BlockInfo['Infractions'] += $this->CIDRAM['Tracking'][$this->BlockInfo['IPAddr']]['Count'];
                }
                if (!empty($this->CIDRAM['Hostname']) && $this->CIDRAM['Hostname'] !== $this->BlockInfo['IPAddr']) {
                    $this->BlockInfo['Hostname'] = $this->CIDRAM['Hostname'];
                } else {
                    $this->BlockInfo['Hostname'] = '-';
                }

                /** Build fields. */
                $this->addField('ID', 'field_id', $this->BlockInfo['ID']);
                $this->addField('ScriptIdent', 'field_scriptversion', $this->BlockInfo['ScriptIdent']);
                $this->addField('DateTime', 'field_datetime', $this->BlockInfo['DateTime']);
                $this->addField('IPAddr', 'field_ipaddr', $this->BlockInfo['IPAddr']);
                $this->addField('IPAddrResolved', 'field_ipaddr_resolved', $this->BlockInfo['IPAddrResolved']);
                $this->addField('Query', 'field_query', $this->BlockInfo['Query'], true);
                $this->addField('Referrer', 'field_referrer', $this->BlockInfo['Referrer'], true);
                $this->addField('UA', 'field_ua', $this->BlockInfo['UA'], true);
                $this->addField('UALC', 'field_ualc', $this->BlockInfo['UALC'], true);
                $this->addField('SignatureCount', 'field_sigcount', $this->NumberFormatter->format($this->BlockInfo['SignatureCount']));
                $this->addField('Signatures', 'field_sigref', $this->BlockInfo['Signatures']);
                $this->addField('WhyReason', 'field_whyreason', $this->BlockInfo['WhyReason'] . '!');
                $this->addField('ReasonMessage', 'field_reasonmessage', $this->BlockInfo['ReasonMessage'], false, false);
                $this->addField('rURI', 'field_rURI', $this->BlockInfo['rURI'], true);
                $this->addField('Infractions', 'field_infractions', $this->NumberFormatter->format($this->BlockInfo['Infractions']));
                $this->addField('ASNLookup', 'field_asnlookup', $this->BlockInfo['ASNLookup'], true);
                $this->addField('CCLookup', 'field_cclookup', $this->BlockInfo['CCLookup'], true);
                $this->addField('Verified', 'field_verified', $this->BlockInfo['Verified']);
                $this->addField('Expired', 'state_expired', $this->BlockInfo['Expired']);
                $this->addField('Ignored', 'state_ignored', $this->BlockInfo['Ignored']);
                $this->addField('Request_Method', 'field_request_method', $this->BlockInfo['Request_Method'], true);
                $this->addField('Hostname', 'field_hostname', $this->BlockInfo['Hostname'], true);
                $this->addField('CAPTCHA', 'field_captcha', $this->BlockInfo['CAPTCHA']);
            }

            if (isset($this->Stages['Output:Enable'])) {
                $this->Stage = 'Output';

                /** Finalise fields. */
                $this->CIDRAM['FieldTemplates']['Output'] = implode("\n        ", $this->CIDRAM['FieldTemplates']['Output']);

                /** Determine which template file to use, if this hasn't already been determined. */
                if (!isset($this->CIDRAM['template_file'])) {
                    $this->CIDRAM['template_file'] = (
                        !$this->CIDRAM['FieldTemplates']['css_url']
                    ) ? 'assets/core/template_' . $this->CIDRAM['FieldTemplates']['theme'] . '.html' : 'assets/core/template_custom.html';
                }

                /** Fallback for themes without default template files. */
                if (
                    $this->CIDRAM['FieldTemplates']['theme'] !== 'default' &&
                    !$this->CIDRAM['FieldTemplates']['css_url'] &&
                    !file_exists($this->Vault . $this->CIDRAM['template_file'])
                ) {
                    $this->CIDRAM['template_file'] = 'template_default.html';
                }

                /** Prepare to process "more info" entries, if any exist. */
                if (!empty($this->Configuration['More Info']) && !empty($this->BlockInfo['ReasonMessage'])) {
                    $this->BlockInfo['ReasonMessage'] .= sprintf(
                        '<br /><br /><span%s>%s</span>',
                        $this->CIDRAM['L10N-Lang-Attache'],
                        $this->CIDRAM['Client-L10N']->getString('MoreInfo')
                    );
                    $this->arrayify($this->Configuration['More Info']);

                    /** Process entries. */
                    foreach ($this->Configuration['More Info'] as $this->CIDRAM['Info Name'] => $this->CIDRAM['Info Link']) {
                        $this->BlockInfo['ReasonMessage'] .= !empty($this->CIDRAM['Info Name']) && is_string($this->CIDRAM['Info Name']) ? (
                            sprintf('<br /><a href="%1$s">%2$s</a>', $this->CIDRAM['Info Link'], $this->CIDRAM['Info Name'])
                        ) : sprintf('<br /><a href="%1$s">%1$s</a>', $this->CIDRAM['Info Link']);
                    }

                    /** Cleanup. */
                    unset($this->CIDRAM['Info Link'], $this->CIDRAM['Info Name'], $this->Configuration['More Info']);
                }

                /** Parsed to the template file. */
                $this->CIDRAM['Parsables'] = array_merge(
                    $this->CIDRAM['FieldTemplates'],
                    $this->CIDRAM['FieldTemplates'],
                    $this->BlockInfo,
                    [
                        'L10N-Lang-Attache' => $this->CIDRAM['L10N-Lang-Attache'],
                        'GeneratedBy' => isset($this->CIDRAM['Fields']['ScriptIdent:ShowInPageOutput']) ? sprintf(
                            $this->CIDRAM['Client-L10N']->getString('generated_by'),
                            '<div id="ScriptIdent" dir="ltr">' . $this->ScriptIdent . '</div>'
                        ) : '',
                        'Title' => $this->CIDRAM['Client-L10N']->getString($this->Configuration['template_data']['block_event_title']) ?: $this->Configuration['template_data']['block_event_title']
                    ]
                );
                if (!isset($this->CIDRAM['Fields']['ReasonMessage:ShowInPageOutput'])) {
                    $this->CIDRAM['Parsables']['ReasonMessage'] = '';
                }

                /** Pull relevant client-specified L10N data first. */
                if (!empty($this->CIDRAM['L10N-Lang-Attache'])) {
                    foreach (['denied', 'captcha_cookie_warning', 'captcha_message', 'captcha_message_invisible', 'label_submit'] as $PullThis) {
                        if (isset($this->CIDRAM['Client-L10N']->Data[$PullThis])) {
                            $this->CIDRAM['Parsables'][$PullThis] = $this->CIDRAM['Client-L10N']->Data[$PullThis];
                        }
                    }
                    unset($PullThis);
                }

                /** Append default L10N data. */
                $this->CIDRAM['Parsables'] += $this->L10N->Data;

                if (!empty($this->CIDRAM['Banned']) && $this->Configuration['general']['ban_override'] > 400 && (
                    $this->CIDRAM['ThisStatusHTTP'] = $this->getStatusHTTP($this->Configuration['general']['ban_override'])
                )) {
                    $this->CIDRAM['errCode'] = $this->Configuration['general']['ban_override'];
                    header('HTTP/1.0 ' . $this->CIDRAM['errCode'] . ' ' . $this->CIDRAM['ThisStatusHTTP']);
                    header('HTTP/1.1 ' . $this->CIDRAM['errCode'] . ' ' . $this->CIDRAM['ThisStatusHTTP']);
                    header('Status: ' . $this->CIDRAM['errCode'] . ' ' . $this->CIDRAM['ThisStatusHTTP']);
                    $HTML = '';
                } elseif (!empty($this->CIDRAM['RL_Status']) && ($this->BlockInfo['SignatureCount'] * 1) === 1) {
                    header('HTTP/1.0 429 ' . $this->CIDRAM['RL_Status']);
                    header('HTTP/1.1 429 ' . $this->CIDRAM['RL_Status']);
                    header('Status: 429 ' . $this->CIDRAM['RL_Status']);
                    header('Retry-After: ' . floor($this->Configuration['rate_limiting']['allowance_period'] * 3600));
                    $HTML = '';
                } elseif (!$this->Configuration['general']['silent_mode']) {
                    /** Fetch appropriate status code based on either "http_response_header_code" or "Aux Status Code". */
                    if ((
                        !empty($this->CIDRAM['Aux Status Code']) &&
                        ($this->CIDRAM['errCode'] = $this->CIDRAM['Aux Status Code']) > 400 &&
                        $this->CIDRAM['ThisStatusHTTP'] = $this->getStatusHTTP($this->CIDRAM['errCode'])
                    ) || (
                        ($this->CIDRAM['errCode'] = $this->Configuration['general']['http_response_header_code']) > 400 &&
                        $this->CIDRAM['ThisStatusHTTP'] = $this->getStatusHTTP($this->CIDRAM['errCode'])
                    )) {
                        header('HTTP/1.0 ' . $this->CIDRAM['errCode'] . ' ' . $this->CIDRAM['ThisStatusHTTP']);
                        header('HTTP/1.1 ' . $this->CIDRAM['errCode'] . ' ' . $this->CIDRAM['ThisStatusHTTP']);
                        header('Status: ' . $this->CIDRAM['errCode'] . ' ' . $this->CIDRAM['ThisStatusHTTP']);
                    } else {
                        $this->CIDRAM['errCode'] = 200;
                    }

                    if (!empty($this->CIDRAM['Suppress output template'])) {
                        $HTML = '';
                    } elseif (!$this->CIDRAM['template_file'] || !file_exists($this->Vault . $this->CIDRAM['template_file'])) {
                        header('Content-Type: text/plain');
                        $HTML = '[CIDRAM] ' . $this->CIDRAM['Client-L10N']->getString('denied');
                    } else {
                        $this->BlockInfo['EmailAddr'] = '';

                        /** Generate email support ticket link. */
                        if ($this->Configuration['general']['emailaddr']) {
                            if ($this->Configuration['general']['emailaddr_display_style'] === 'default') {
                                $this->BlockInfo['EmailAddr'] =
                                    '<a href="mailto:' . $this->Configuration['general']['emailaddr'] .
                                    '?subject=CIDRAM%20Event&body=' . urlencode($this->parseVars(
                                        $this->CIDRAM['Parsables'],
                                        $this->CIDRAM['FieldTemplates']['Logs'] . "\n"
                                    )) . '"><strong>' . $this->CIDRAM['Client-L10N']->getString('click_here') . '</strong></a>';
                                $this->BlockInfo['EmailAddr'] = "\n  <p class=\"detected\"" . $this->CIDRAM['L10N-Lang-Attache'] . '>' . $this->parseVars([
                                    'ClickHereLink' => $this->BlockInfo['EmailAddr']
                                ], $this->CIDRAM['Client-L10N']->getString('Support_Email')) . '</p>';
                            } elseif ($this->Configuration['general']['emailaddr_display_style'] === 'noclick') {
                                $this->BlockInfo['EmailAddr'] = "\n  <p class=\"detected\">" . $this->parseVars([
                                    'EmailAddr' => str_replace(
                                        '@',
                                        '<img src="data:image/gif;base64,R0lGODdhCQAKAIABAAAAAP///ywAAAAACQAKAAACE4yPAcsG+ZR7kcp6pWY4Hb54SAEAOw==" alt="@" />',
                                        '<strong>' . $this->Configuration['general']['emailaddr'] . '</strong>'
                                    )
                                ], $this->CIDRAM['Client-L10N']->getString('Support_Email_2')) . '</p>';
                            }
                        }

                        /** Include privacy policy. */
                        $this->CIDRAM['Parsables']['pp'] = empty($this->Configuration['legal']['privacy_policy']) ? '' : sprintf(
                            '<br /><a href="%s"%s>%s</a>',
                            $this->Configuration['legal']['privacy_policy'],
                            $this->CIDRAM['L10N-Lang-Attache'],
                            $this->CIDRAM['Client-L10N']->getString('PrivacyPolicy')
                        );

                        /** Generate HTML output. */
                        $HTML = $this->parseVars([
                            'EmailAddr' => $this->BlockInfo['EmailAddr']
                        ], $this->parseVars($this->CIDRAM['Parsables'], $this->readFile(
                            $this->Vault . $this->CIDRAM['template_file']
                        )));
                    }
                } else {
                    $this->CIDRAM['errCode'] = 301;
                    $this->CIDRAM['Status'] = $this->getStatusHTTP(301);
                    header('HTTP/1.0 301 ' . $this->CIDRAM['Status']);
                    header('HTTP/1.1 301 ' . $this->CIDRAM['Status']);
                    header('Status: 301 ' . $this->CIDRAM['Status']);
                    header('Location: ' . $this->Configuration['general']['silent_mode']);
                    $HTML = '';
                }
            }

            if (isset($this->Stages['WriteLogs:Enable'])) {
                $this->Stage = 'WriteLogs';

                /**
                 * Skip this section if the IP has been banned and logging banned IPs has
                 * been disabled, or if the "Don't Log" flag has been set.
                 */
                if (empty($this->CIDRAM['Flag Don\'t Log']) && (
                    $this->Configuration['general']['log_banned_ips'] || empty($this->CIDRAM['Banned'])
                )) {
                    /** Write to logs. */
                    $this->Events->fireEvent('writeToLog');
                }
            }

            if (isset($this->Stages['Terminate:Enable'])) {
                $this->Stage = 'Terminate';

                /** Final event before we exit. */
                $this->Events->fireEvent('final');

                /** All necessary processing and logging has completed; Now we send HTML output and die. */
                die($HTML);
            }
        }

        /** Executed only if request redirection has been triggered by auxiliary rules. */
        if (isset($this->Stages['AuxRedirect:Enable'])) {
            $this->Stage = 'AuxRedirect';
            if (!empty($this->CIDRAM['Aux Redirect']) && !empty($this->CIDRAM['Aux Status Code']) && $this->CIDRAM['Aux Status Code'] > 300 && $this->CIDRAM['Aux Status Code'] < 400) {
                $this->CIDRAM['errCode'] = $this->CIDRAM['Aux Status Code'];
                $this->CIDRAM['Status'] = $this->getStatusHTTP($this->CIDRAM['Aux Status Code']);
                header('HTTP/1.0 ' . $this->CIDRAM['Aux Status Code'] . ' ' . $this->CIDRAM['Status']);
                header('HTTP/1.1 ' . $this->CIDRAM['Aux Status Code'] . ' ' . $this->CIDRAM['Status']);
                header('Status: ' . $this->CIDRAM['Aux Status Code'] . ' ' . $this->CIDRAM['Status']);
                header('Location: ' . $this->CIDRAM['Aux Redirect']);
                $this->Events->fireEvent('final');
                die;
            }
        }

        /** This code block executed only for non-blocked CAPTCHA configurations. */
        if (isset($this->Stages['NonBlockedCAPTCHA:Enable'])) {
            $this->Stage = 'NonBlockedCAPTCHA';
            if (empty($CaptchaDone) && empty($this->CIDRAM['Whitelisted']) && empty($this->BlockInfo['Verified'])) {
                if (
                    !empty($this->Configuration['recaptcha']['sitekey']) &&
                    !empty($this->Configuration['recaptcha']['secret']) &&
                    class_exists('\CIDRAM\CIDRAM\ReCaptcha') &&
                    ($this->Configuration['recaptcha']['usemode'] >= 3 && $this->Configuration['recaptcha']['usemode'] <= 5)
                ) {
                    /** Execute the ReCaptcha class. */
                    $CaptchaDone = new \CIDRAM\CIDRAM\ReCaptcha($this->CIDRAM);

                    $this->CIDRAM['StatusCodeForNonBlocked'] = $this->Configuration['recaptcha']['nonblocked_status_code'];
                } elseif (
                    !empty($this->Configuration['hcaptcha']['sitekey']) &&
                    !empty($this->Configuration['hcaptcha']['secret']) &&
                    class_exists('\CIDRAM\CIDRAM\HCaptcha') &&
                    ($this->Configuration['hcaptcha']['usemode'] >= 3 && $this->Configuration['hcaptcha']['usemode'] <= 5)
                ) {
                    /** Execute the HCaptcha class. */
                    $CaptchaDone = new \CIDRAM\CIDRAM\HCaptcha($this->CIDRAM);

                    $this->CIDRAM['StatusCodeForNonBlocked'] = $this->Configuration['hcaptcha']['nonblocked_status_code'];
                }

                if (
                    !empty($CaptchaDone) &&
                    is_object($CaptchaDone) &&
                    isset($CaptchaDone->Bypass) &&
                    $CaptchaDone->Bypass === false
                ) {
                    /** Parsed to the CAPTCHA's HTML file. */
                    $this->CIDRAM['Parsables'] = array_merge($this->CIDRAM['FieldTemplates'], $this->CIDRAM['FieldTemplates'], $this->BlockInfo);
                    $this->CIDRAM['Parsables']['L10N-Lang-Attache'] = $this->CIDRAM['L10N-Lang-Attache'];
                    $this->CIDRAM['Parsables']['GeneratedBy'] = isset($this->CIDRAM['Fields']['ScriptIdent:ShowInPageOutput']) ? sprintf(
                        $this->CIDRAM['Client-L10N']->getString('generated_by'),
                        '<div id="ScriptIdent" dir="ltr">' . $this->ScriptIdent . '</div>'
                    ) : '';
                    $this->CIDRAM['Parsables']['Title'] = $this->CIDRAM['Client-L10N']->getString($this->Configuration['template_data']['captcha_title']) ?: $this->Configuration['template_data']['captcha_title'];

                    /** Pull relevant client-specified L10N data first. */
                    if (!empty($this->CIDRAM['L10N-Lang-Attache'])) {
                        foreach (['captcha_cookie_warning', 'captcha_message_automated_traffic', 'captcha_message', 'captcha_message_invisible', 'label_submit'] as $PullThis) {
                            if (isset($this->CIDRAM['Client-L10N']->Data[$PullThis])) {
                                $this->CIDRAM['Parsables'][$PullThis] = $this->CIDRAM['Client-L10N']->Data[$PullThis];
                            }
                        }
                        unset($PullThis);
                    }

                    /** Append default L10N data. */
                    $this->CIDRAM['Parsables'] += $this->L10N->Data;

                    /** The CAPTCHA template file to use. */
                    $this->CIDRAM['CaptchaTemplateFile'] = 'captcha_' . $this->CIDRAM['FieldTemplates']['theme'] . '.html';

                    /** Fallback for themes without CAPTCHA template files. */
                    if (
                        $this->CIDRAM['FieldTemplates']['theme'] !== 'default' &&
                        !file_exists($this->Vault . $this->CIDRAM['CaptchaTemplateFile'])
                    ) {
                        $this->CIDRAM['CaptchaTemplateFile'] = 'captcha_default.html';
                    }

                    /** Include privacy policy. */
                    $this->CIDRAM['Parsables']['pp'] = empty($this->Configuration['legal']['privacy_policy']) ? '' : sprintf(
                        '<br /><a href="%s"%s>%s</a>',
                        $this->Configuration['legal']['privacy_policy'],
                        $this->CIDRAM['L10N-Lang-Attache'],
                        $this->CIDRAM['Client-L10N']->getString('PrivacyPolicy')
                    );

                    /** Process non-blocked status code. */
                    if (
                        $this->CIDRAM['StatusCodeForNonBlocked'] > 400 &&
                        $this->CIDRAM['ThisStatusHTTP'] = $this->getStatusHTTP($this->CIDRAM['StatusCodeForNonBlocked'])
                    ) {
                        header('HTTP/1.0 ' . $this->CIDRAM['StatusCodeForNonBlocked'] . ' ' . $this->CIDRAM['ThisStatusHTTP']);
                        header('HTTP/1.1 ' . $this->CIDRAM['StatusCodeForNonBlocked'] . ' ' . $this->CIDRAM['ThisStatusHTTP']);
                        header('Status: ' . $this->CIDRAM['StatusCodeForNonBlocked'] . ' ' . $this->CIDRAM['ThisStatusHTTP']);
                    }

                    /** Generate HTML output. */
                    $HTML = $this->parseVars(
                        $this->CIDRAM['Parsables'],
                        $this->readFile($this->Vault . $this->CIDRAM['CaptchaTemplateFile'])
                    );

                    /** Final event before we exit. */
                    $this->Events->fireEvent('final');

                    /** All necessary processing has completed; Now we send HTML output and die. */
                    die($HTML);
                }
            }
        }

        /** Final event before we exit. */
        $this->Events->fireEvent('final');

        /** Restores default error handler. */
        $this->restoreErrorHandler();
    }
}
