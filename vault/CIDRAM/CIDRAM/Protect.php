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
 * This file: Protect traits (last modified: 2022.07.14).
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
        /** Attach client-accepted L10N declaration. */
        if ($this->Configuration['general']['lang'] === $this->CIDRAM['Client-L10N-Accepted']) {
            $this->CIDRAM['L10N-Lang-Attache'] = '';
        } else {
            $this->CIDRAM['L10N-Lang-Attache'] = sprintf(
                ' lang="%s" dir="%s"',
                $this->CIDRAM['Client-L10N-Accepted'],
                $this->CIDRAM['Client-L10N']->Data['Text Direction'] ?? 'ltr'
            );
        }

        /** Initialise stages. */
        $this->Stages = array_flip(explode("\n", $this->Configuration['general']['stages']));

        /** Initialise shorthand options. */
        $this->Shorthand = array_flip(explode("\n", $this->Configuration['signatures']['shorthand']));

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
            if ($this->Cache->getEntry('Statistics-Since') === false) {
                $this->Cache->setEntry('Statistics-Since', $this->Now, 0);
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
            'Referrer' => $_SERVER['HTTP_REFERER'] ?? '',
            'UA' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'SignatureCount' => 0,
            'Signatures' => '',
            'WhyReason' => '',
            'ReasonMessage' => '',
            'ASNLookup' => 0,
            'CCLookup' => 'XX',
            'Verified' => '',
            'Expired' => '',
            'Ignored' => '',
            'Request_Method' => $_SERVER['REQUEST_METHOD'] ?? '',
            'xmlLang' => $this->Configuration['general']['lang']
        ];
        if (isset($this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']])) {
            $this->BlockInfo['Infractions'] = $this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']];
        } elseif (($Try = $this->Cache->getEntry('Tracking-' . $this->BlockInfo['IPAddr'])) === false) {
            $this->BlockInfo['Infractions'] = 0;
        } else {
            $this->BlockInfo['Infractions'] = $Try;
        }
        $AtRunTimeInfractions = $this->BlockInfo['Infractions'];
        $this->BlockInfo['UALC'] = strtolower($this->BlockInfo['UA']);
        $this->BlockInfo['rURI'] = (
            (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') ||
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        ) ? 'https://' : 'http://';
        $this->BlockInfo['rURI'] .= $this->CIDRAM['HTTP_HOST'] ?: 'Unknown.Host';
        $this->BlockInfo['rURI'] .= $_SERVER['REQUEST_URI'] ?? '/';

        /** Initialise page output and block event log fields. */
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
                $this->addProfileEntry('BadIP');
                if (isset($this->Shorthand['BadIP:Suppress'])) {
                    $this->CIDRAM['Suppress output template'] = true;
                }
            }

            /**
             * Check whether we're tracking the IP due to previous instances of bad
             * behaviour.
             */
            elseif (isset($this->Stages['Tracking:Enable'])) {
                $this->Stage = 'Tracking';
                $DoBan = false;
                $Try = $this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']] ?? $this->Cache->getEntry('Tracking-' . $this->BlockInfo['IPAddr']);
                if ($Try !== false && $Try >= $this->Configuration['signatures']['infraction_limit']) {
                    $DoBan = true;
                }
                if ($this->BlockInfo['IPAddr'] !== $this->BlockInfo['IPAddrResolved']) {
                    $Try = $this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']] ?? $this->Cache->getEntry('Tracking-' . $this->BlockInfo['IPAddr']);
                    if ($Try !== false && $Try >= $this->Configuration['signatures']['infraction_limit']) {
                        $DoBan = true;
                    }
                }
                if ($DoBan) {
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
            $this->CIDRAM['Hostname'] = $this->dnsReverse($this->BlockInfo['IPAddrResolved'] ?: $this->BlockInfo['IPAddr']);
        }

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
        if (empty($this->CIDRAM['Whitelisted']) && isset($this->Stages['SearchEngineVerification:Enable'])) {
            $this->Stage = 'SearchEngineVerification';
            $this->searchEngineVerification();
        }

        /** Execute social media verification. */
        if (empty($this->CIDRAM['Whitelisted']) && isset($this->Stages['SocialMediaVerification:Enable'])) {
            $this->Stage = 'SocialMediaVerification';
            $this->socialMediaVerification();
        }

        /** Execute other verification. */
        if (empty($this->CIDRAM['Whitelisted']) && isset($this->Stages['OtherVerification:Enable'])) {
            $this->Stage = 'OtherVerification';
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

        /** Process tracking information for the inbound IP. */
        if (!empty($this->CIDRAM['TestResults']) && (
            (isset($this->CIDRAM['Trackable']) && $this->CIDRAM['Trackable'] === true) ||
            (isset($this->Stages['Tracking:Enable']) && $this->BlockInfo['Infractions'] > 0 && (
                !isset($this->CIDRAM['Trackable']) || $this->CIDRAM['Trackable'] !== false
            ))
        )) {
            $this->Stage = 'Tracking';

            /** Set tracking expiry. */
            $TrackTime = $this->Configuration['Options']['TrackTime'] ?? $this->Configuration['signatures']['default_tracktime'];

            /** Number of infractions to append. */
            $TrackCount = $this->BlockInfo['Infractions'] - $AtRunTimeInfractions;

            /** Minimum expiry time for updating the IP tracking cache entry. */
            $MinimumTime = $this->Cache->getEntry('Tracking-' . $this->BlockInfo['IPAddr'] . '-MinimumTime') ?: 0;

            /** Tracking options override. */
            if (!empty($this->CIDRAM['Tracking options override'])) {
                if ($this->CIDRAM['Tracking options override'] === 'extended') {
                    $TrackTime = floor($this->Configuration['signatures']['default_tracktime'] * 52.1428571428571);
                    $TrackCount *= 1000;
                    if ($this->CIDRAM['Banned'] && $TrackCount >= 2000) {
                        $TrackCount -= 1000;
                        $PreventAmplification = true;
                    }
                } elseif ($this->CIDRAM['Tracking options override'] === 'default') {
                    $TrackTime = $this->Configuration['signatures']['default_tracktime'];
                    $TrackCount = 1;
                }
            }
            if (!isset($PreventAmplification) && $this->CIDRAM['Banned'] && $TrackCount >= 2) {
                $TrackCount -= 1;
            }

            if (isset($this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']])) {
                $this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']] += $TrackCount;
            } elseif (($Try = $this->Cache->getEntry('Tracking-' . $this->BlockInfo['IPAddr'])) === false) {
                $this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']] = $TrackCount;
            } else {
                $this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']] = $Try + $TrackCount;
            }

            if ($MinimumTime > $TrackTime) {
                $TrackTime = $MinimumTime;
            }

            /** Track minimum tracking time. */
            $this->Cache->setEntry('Tracking-' . $this->BlockInfo['IPAddr'] . '-MinimumTime', $TrackTime, $TrackTime);

            /** Track infractions. */
            $this->Cache->setEntry(
                'Tracking-' . $this->BlockInfo['IPAddr'],
                $this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']],
                $TrackTime
            );

            if ($this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']] >= $this->Configuration['signatures']['infraction_limit']) {
                $this->CIDRAM['Banned'] = true;
            }
        } elseif (isset($this->CIDRAM['Trackable']) && $this->CIDRAM['Trackable'] === false) {
            /** Untrack IP address. */
            $this->Cache->deleteEntry('Tracking-' . $this->BlockInfo['IPAddr']);
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
                        ob_end_flush();
                    });
                }
            }
        }

        /** This code block only executed if signatures were triggered. */
        if (
            isset($this->Stages['CAPTCHA:Enable']) &&
            $this->BlockInfo['SignatureCount'] > 0 &&
            !$this->hasProfile(['BadIP', 'Blacklisted', 'Redlisted'])
        ) {
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
                            $this->Cache->incEntry('Statistics-Banned-IPv4');
                        }
                        if (isset($this->StatisticsTracked['Banned-IPv6'])) {
                            $this->Cache->incEntry('Statistics-Banned-IPv6');
                        }
                    } elseif ($this->CIDRAM['LastTestIP'] === 4) {
                        if (isset($this->StatisticsTracked['Banned-IPv4'])) {
                            $this->Cache->incEntry('Statistics-Banned-IPv4');
                        }
                    } elseif ($this->CIDRAM['LastTestIP'] === 6) {
                        if (isset($this->StatisticsTracked['Banned-IPv6'])) {
                            $this->Cache->incEntry('Statistics-Banned-IPv6');
                        }
                    }
                } elseif ($this->BlockInfo['IPAddrResolved']) {
                    if (isset($this->StatisticsTracked['Blocked-IPv4'])) {
                        $this->Cache->incEntry('Statistics-Blocked-IPv4');
                    }
                    if (isset($this->StatisticsTracked['Blocked-IPv6'])) {
                        $this->Cache->incEntry('Statistics-Blocked-IPv6');
                    }
                } elseif ($this->CIDRAM['LastTestIP'] === 4) {
                    if (isset($this->StatisticsTracked['Blocked-IPv4'])) {
                        $this->Cache->incEntry('Statistics-Blocked-IPv4');
                    }
                } elseif ($this->CIDRAM['LastTestIP'] === 6) {
                    if (isset($this->StatisticsTracked['Blocked-IPv6'])) {
                        $this->Cache->incEntry('Statistics-Blocked-IPv6');
                    }
                } else {
                    if (isset($this->StatisticsTracked['Blocked-Other'])) {
                        $this->Cache->incEntry('Statistics-Blocked-Other');
                    }
                }
            } else {
                if ($this->BlockInfo['IPAddrResolved']) {
                    if (isset($this->StatisticsTracked['Passed-IPv4'])) {
                        $this->Cache->incEntry('Statistics-Passed-IPv4');
                    }
                    if (isset($this->StatisticsTracked['Passed-IPv6'])) {
                        $this->Cache->incEntry('Statistics-Passed-IPv6');
                    }
                } elseif ($this->CIDRAM['LastTestIP'] === 4) {
                    if (isset($this->StatisticsTracked['Passed-IPv4'])) {
                        $this->Cache->incEntry('Statistics-Passed-IPv4');
                    }
                } elseif ($this->CIDRAM['LastTestIP'] === 6) {
                    if (isset($this->StatisticsTracked['Passed-IPv6'])) {
                        $this->Cache->incEntry('Statistics-Passed-IPv6');
                    }
                } else {
                    if (isset($this->StatisticsTracked['Passed-Other'])) {
                        $this->Cache->incEntry('Statistics-Passed-Other');
                    }
                }
            }
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
                if (isset($this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']])) {
                    $this->BlockInfo['Infractions'] += $this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']];
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
                    $this->CIDRAM['template_file'] = sprintf(
                        'core/template_%s.html',
                        $this->CIDRAM['FieldTemplates']['css_url'] === '' ? $this->CIDRAM['FieldTemplates']['theme'] : 'custom'
                    );
                }

                /** Fallback for themes without default template files. */
                if (
                    $this->CIDRAM['FieldTemplates']['theme'] !== 'default' &&
                    !$this->CIDRAM['FieldTemplates']['css_url'] &&
                    !file_exists($this->AssetsPath . $this->CIDRAM['template_file'])
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

                if (!$this->Configuration['general']['silent_mode']) {
                    /** Enforce output template suppression. */
                    if (
                        (!empty($this->CIDRAM['Banned']) && isset($this->Shorthand['Banned:Suppress'])) ||
                        (!empty($this->CIDRAM['RL_Status']) && isset($this->Shorthand['RL:Suppress']))
                    ) {
                        $this->CIDRAM['Suppress output template'] = true;
                    }

                    /** Fetch appropriate HTTP status code. */
                    if (
                        !empty($this->CIDRAM['Banned']) &&
                        $this->Configuration['general']['ban_override'] > 400 &&
                        ($this->CIDRAM['ThisStatusHTTP'] = $this->getStatusHTTP($this->Configuration['general']['ban_override']))
                    ) {
                        $this->CIDRAM['errCode'] = $this->Configuration['general']['ban_override'];
                        header('HTTP/1.0 ' . $this->CIDRAM['errCode'] . ' ' . $this->CIDRAM['ThisStatusHTTP']);
                        header('HTTP/1.1 ' . $this->CIDRAM['errCode'] . ' ' . $this->CIDRAM['ThisStatusHTTP']);
                        header('Status: ' . $this->CIDRAM['errCode'] . ' ' . $this->CIDRAM['ThisStatusHTTP']);
                    } elseif (
                        !empty($this->CIDRAM['RL_Status']) &&
                        $this->BlockInfo['SignatureCount'] === 1
                    ) {
                        $this->CIDRAM['errCode'] = 429;
                        header('HTTP/1.0 429 ' . $this->CIDRAM['RL_Status']);
                        header('HTTP/1.1 429 ' . $this->CIDRAM['RL_Status']);
                        header('Status: 429 ' . $this->CIDRAM['RL_Status']);
                        header('Retry-After: ' . floor($this->Configuration['rate_limiting']['allowance_period'] * 3600));
                    } elseif ((
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
                    } elseif (!file_exists($this->AssetsPath . $this->CIDRAM['template_file'])) {
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
                                $this->BlockInfo['EmailAddr'] = "\n  <p class=\"detected\" dir=\"ltr\">" . $this->parseVars([
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
                        $HTML = $this->parseVars(
                            ['EmailAddr' => $this->BlockInfo['EmailAddr']],
                            $this->parseVars($this->CIDRAM['Parsables'], $this->readFile($this->AssetsPath . $this->CIDRAM['template_file']))
                        );
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
                    $this->Configuration['logging']['log_banned_ips'] || empty($this->CIDRAM['Banned'])
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

    /**
     * Adds a field to the page output and the block event log.
     *
     * @param string $FieldInternal The internal name for the field.
     * @param string $FieldName The name of the L10N string for the field.
     * @param string $FieldData The data for the field.
     * @param bool $Sanitise Whether the data needs to be sanitised.
     * @param bool $ShowAtLabels Whether to show the data at the output labels.
     * @return void
     */
    private function addField(string $FieldInternal, string $FieldName, string $FieldData, bool $Sanitise = false, bool $ShowAtLabels = true): void
    {
        if ($FieldData === '') {
            if (isset($this->CIDRAM['Fields'][$FieldInternal . ':OmitIfEmpty'])) {
                return;
            }
            $FieldData = '-';
        }
        $Prepared = $Sanitise ? str_replace(
            ['<', '>', "\r", "\n"],
            ['&lt;', '&gt;', '&#13;', '&#10;'],
            $FieldData
        ) : $FieldData;
        if (isset($this->CIDRAM['Fields'][$FieldInternal . ':ShowInLogs'])) {
            $Logged = $this->Configuration['logging']['log_sanitisation'] ? $Prepared : $FieldData;
            $InternalResolved = $this->L10N->getString($FieldName) ?: $FieldName;
            $InternalResolved .= $this->L10N->getString('pair_separator') ?: ': ';
            $this->CIDRAM['FieldTemplates']['Logs'] .= $InternalResolved . $Logged . "\n";
        }
        if ($ShowAtLabels && isset($this->CIDRAM['Fields'][$FieldInternal . ':ShowInPageOutput'])) {
            $this->CIDRAM['FieldTemplates']['Output'][] = sprintf(
                '<span class="textLabel"%s>%s%s</span>%s<br />',
                $this->CIDRAM['L10N-Lang-Attache'],
                $this->CIDRAM['Client-L10N']->getString($FieldName) ?: $this->L10N->getString($FieldName) ?: $FieldName,
                $this->CIDRAM['Client-L10N']->getString('pair_separator') ?: $this->L10N->getString('pair_separator') ?: ': ',
                $Prepared
            );
        }
    }
}
