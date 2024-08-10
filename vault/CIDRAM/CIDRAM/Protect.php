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
 * This file: Protect traits (last modified: 2024.08.10).
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
        $this->CIDRAM['L10N-Lang-Attache'] = $this->L10NAccepted === $this->ClientL10NAccepted ? '' : sprintf(
            ' lang="%s" dir="%s"',
            $this->ClientL10NAccepted,
            $this->ClientL10N->Directionality
        );

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

        /** Initialise verification adjustments. */
        $this->VAdjust = array_flip(explode("\n", $this->Configuration['verification']['adjust']));

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
            'Protocol' => $_SERVER['SERVER_PROTOCOL'] ?? '',
            'SEC_CH_UA_PLATFORM' => $_SERVER['HTTP_SEC_CH_UA_PLATFORM'] ?? '',
            'SEC_CH_UA_MOBILE' => $_SERVER['HTTP_SEC_CH_UA_MOBILE'] ?? '',
            'SEC_CH_UA' => $_SERVER['HTTP_SEC_CH_UA'] ?? '',
            'Inspection' => '',
            'ClientL10NAccepted' => $this->ClientL10NAccepted,
            'xmlLang' => $this->L10NAccepted
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
        if (isset($_SERVER['SERVER_PORT']) && is_scalar($_SERVER['SERVER_PORT'])) {
            $Try = (int)$_SERVER['SERVER_PORT'];
            $Try = ($Try > 0 && (
                ($this->BlockInfo['rURI'] === 'http://' && $Try !== 80) ||
                ($this->BlockInfo['rURI'] === 'https://' && $Try !== 443)
            )) ? ':' . $Try : '';
        }
        $this->BlockInfo['rURI'] .= $this->CIDRAM['HTTP_HOST'] ?: 'Unknown.Host';
        $this->BlockInfo['rURI'] .= $Try;
        $this->BlockInfo['rURI'] .= $_SERVER['REQUEST_URI'] ?? '/';

        /** Initialise page output and block event log fields. */
        $this->CIDRAM['FieldTemplates'] = $this->Configuration['template_data'] + [
            'CustomHeader' => $this->Configuration['template_data']['custom_header'],
            'CustomFooter' => $this->Configuration['template_data']['custom_footer'],
            'Logs' => '',
            'Output' => [],
            'captcha_api_include' => '',
            'captcha_div_include' => '',
        ];

        /** Instantiate report orchestrator (used by some modules). */
        $this->Reporter = new Reporter($this->Events);

        /** Ban check (will split to its own stage for v4; e.g., 'BanCheck'; keeping as 'Tracking' for now to prevent BC breaks between minor/patch releases). */
        if (isset($this->Stages['Tracking:Enable'])) {
            $this->Stage = 'Tracking';
            $DoBan = false;
            if ($AtRunTimeInfractions >= $this->Configuration['signatures']['infraction_limit']) {
                $DoBan = true;
            } elseif ($this->BlockInfo['IPAddr'] !== $this->BlockInfo['IPAddrResolved']) {
                $Try = $this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddrResolved']] ?? $this->Cache->getEntry('Tracking-' . $this->BlockInfo['IPAddrResolved']);
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
            unset($DoBan);
            $this->Stage = '';
        }

        if (isset($this->Stages['Tests:Enable'])) {
            $this->Stage = 'Tests';
            $Before = $this->BlockInfo['SignatureCount'];

            /** Execute signature files tests. */
            try {
                $this->CIDRAM['TestResults'] = $this->runTests($this->BlockInfo['IPAddr'], true);
            } catch (\Exception $e) {
                $this->Events->fireEvent('final');
                die($e->getMessage());
            }

            /** Execute for resolved IP address if necessary. */
            if ($this->BlockInfo['IPAddrResolved'] && $this->CIDRAM['TestResults'] && empty($this->CIDRAM['Whitelisted'])) {
                try {
                    $this->CIDRAM['TestResults'] = $this->runTests($this->BlockInfo['IPAddrResolved'], true);
                } catch (\Exception $e) {
                    $this->Events->fireEvent('final');
                    die($e->getMessage());
                }
            }

            /** If all tests fail, report an invalid IP address. */
            if (!$this->CIDRAM['TestResults']) {
                $this->BlockInfo['ReasonMessage'] = $this->L10N->getString('ReasonMessage_BadIP');
                $this->BlockInfo['WhyReason'] = $this->L10N->getString('Short_BadIP');
                $this->BlockInfo['SignatureCount']++;
                $this->addProfileEntry('BadIP');
                if (isset($this->Shorthand['BadIP:Suppress'])) {
                    $this->CIDRAM['Suppress output template'] = true;
                }
            }

            if (isset($this->Stages['Tests:Tracking']) && $this->BlockInfo['SignatureCount'] !== $Before) {
                $this->BlockInfo['Infractions'] += $this->BlockInfo['SignatureCount'] - $Before;
            }
            $this->Stage = '';
        }

        /** Perform forced hostname lookup if this has been enabled. */
        if ($this->Configuration['general']['force_hostname_lookup']) {
            $this->CIDRAM['Hostname'] = $this->dnsReverse($this->BlockInfo['IPAddrResolved'] ?: $this->BlockInfo['IPAddr']);
        }

        /** Execute modules, if any have been enabled. */
        if (empty($this->CIDRAM['Whitelisted']) && $this->Configuration['components']['modules'] !== '' && isset($this->Stages['Modules:Enable'])) {
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
                } elseif (!$this->isReserved($Module) && is_readable($this->ModulesPath . $Module)) {
                    require $this->ModulesPath . $Module;
                }
                if (isset($this->Stages['Modules:Tracking']) && $this->BlockInfo['SignatureCount'] !== $Before) {
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
            $this->Stage = 'Aux';
            $Before = $this->BlockInfo['SignatureCount'];
            $this->aux();
            if (isset($this->Stages['Aux:Tracking']) && $this->BlockInfo['SignatureCount'] !== $Before) {
                $this->BlockInfo['Infractions'] += $this->BlockInfo['SignatureCount'] - $Before;
            }
        }
        unset($Before);

        /** Process tracking information for the inbound IP. */
        if (!empty($this->CIDRAM['TestResults']) && (
            (isset($this->CIDRAM['Trackable']) && $this->CIDRAM['Trackable'] === true) ||
            (
                isset($this->Stages['Tracking:Enable']) &&
                $this->BlockInfo['Infractions'] > 0 &&
                $this->BlockInfo['SignatureCount'] > 0 &&
                (!isset($this->CIDRAM['Trackable']) || $this->CIDRAM['Trackable'] !== false)
            )
        )) {
            $this->Stage = 'Tracking';

            /** Set tracking expiry. */
            $TrackTime = $this->Configuration['Options']['TrackTime'] ?? $this->Configuration['signatures']['default_tracktime']->getAsSeconds();

            /** Number of infractions to append. */
            $TrackCount = $this->BlockInfo['Infractions'] - $AtRunTimeInfractions;

            /** Minimum expiry time for updating the IP tracking cache entry. */
            $MinimumTime = $this->Cache->getEntry('Tracking-' . $this->BlockInfo['IPAddr'] . '-MinimumTime') ?: 0;

            /** Tracking options override. */
            if (!empty($this->CIDRAM['Tracking options override'])) {
                if ($this->CIDRAM['Tracking options override'] === 'extended') {
                    $TrackTime = floor($this->Configuration['signatures']['default_tracktime']->getAsSeconds() * 52.1428571428571);
                    $TrackCount *= 1000;
                    if ($this->CIDRAM['Banned'] && $TrackCount >= 2000) {
                        $TrackCount -= 1000;
                    }
                } elseif ($this->CIDRAM['Tracking options override'] === 'default') {
                    $TrackTime = $this->Configuration['signatures']['default_tracktime']->getAsSeconds();
                    $TrackCount = 1;
                }
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
            $this->Cache->deleteEntry('Tracking-' . $this->BlockInfo['IPAddr'] . '-MinimumTime');
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
                        $this->CIDRAM['RL_Expired'] = $this->Now - $this->Configuration['rate_limiting']['allowance_period']->getAsSeconds();
                        $this->CIDRAM['RL_Oldest'] = unpack('l*', substr($this->CIDRAM['RL_Data'], 0, 4));
                        if ($this->CIDRAM['RL_Oldest'][1] < $this->CIDRAM['RL_Expired']) {
                            $this->rateLimitClean();
                        }
                        $this->CIDRAM['RL_Usage'] = $this->rateGetUsage();
                        if ($this->trigger((
                            ($RLMaxBandwidth > 0 && $this->CIDRAM['RL_Usage']['Bytes'] >= $RLMaxBandwidth) ||
                            ($this->Configuration['rate_limiting']['max_requests'] > 0 && $this->CIDRAM['RL_Usage']['Requests'] >= $this->Configuration['rate_limiting']['max_requests'])
                        ), $this->L10N->getString('Short_RL'))) {
                            $this->enactOptions('', ['ForciblyDisableReCAPTCHA' => true, 'ForciblyDisableHCAPTCHA' => true]);
                            $this->CIDRAM['RL_Status'] = $this->getStatusHTTP(429);
                            $this->Events->fireEvent('rateLimited');
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
                        if (ob_get_level() > 0) {
                            ob_end_flush();
                        }
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
                $this->Configuration['recaptcha']['sitekey'] !== '' &&
                $this->Configuration['recaptcha']['secret'] !== '' &&
                class_exists('\CIDRAM\CIDRAM\ReCaptcha') &&
                empty($this->CIDRAM['Banned']) &&
                $this->BlockInfo['SignatureCount'] <= $this->Configuration['recaptcha']['signature_limit'] &&
                empty($this->Configuration['recaptcha']['forcibly_disabled']) &&
                (
                    $this->Configuration['recaptcha']['usemode'] === 1 ||
                    $this->Configuration['recaptcha']['usemode'] === 3 ||
                    (
                        (
                            $this->Configuration['recaptcha']['usemode'] === 2 ||
                            $this->Configuration['recaptcha']['usemode'] === 5
                        ) && !empty($this->Configuration['recaptcha']['enabled'])
                    )
                ) &&
                (!$this->hasProfile('Blocked Negative') || !isset($this->VAdjust['Negatives:ReCaptcha'])) &&
                (!$this->hasProfile('Blocked Non-Verified') || !isset($this->VAdjust['NonVerified:ReCaptcha']))
            ) {
                /** Execute the ReCaptcha class. */
                $CaptchaDone = new ReCaptcha($this);
            } elseif (
                $this->Configuration['hcaptcha']['sitekey'] !== '' &&
                $this->Configuration['hcaptcha']['secret'] !== '' &&
                class_exists('\CIDRAM\CIDRAM\HCaptcha') &&
                empty($this->CIDRAM['Banned']) &&
                $this->BlockInfo['SignatureCount'] <= $this->Configuration['hcaptcha']['signature_limit'] &&
                empty($this->Configuration['hcaptcha']['forcibly_disabled']) &&
                (
                    $this->Configuration['hcaptcha']['usemode'] === 1 ||
                    $this->Configuration['hcaptcha']['usemode'] === 3 ||
                    (
                        (
                            $this->Configuration['hcaptcha']['usemode'] === 2 ||
                            $this->Configuration['hcaptcha']['usemode'] === 5
                        ) && !empty($this->Configuration['hcaptcha']['enabled'])
                    )
                ) &&
                (!$this->hasProfile('Blocked Negative') || !isset($this->VAdjust['Negatives:HCaptcha'])) &&
                (!$this->hasProfile('Blocked Non-Verified') || !isset($this->VAdjust['NonVerified:HCaptcha']))
            ) {
                /** Execute the HCaptcha class. */
                $CaptchaDone = new HCaptcha($this);
            }
        }

        /** Identify proxy connections (conjunctive reporting element). */
        if (
            strpos($this->BlockInfo['WhyReason'], $this->L10N->getString('Short_Proxy')) !== false ||
            $this->hasProfile(['Open Proxy', 'Proxy', 'Tor endpoints here'])
        ) {
            $this->Reporter->report([9], [], $this->BlockInfo['IPAddr']);
        }

        /** Identify VPN connections (conjunctive reporting element). */
        if ($this->hasProfile(['VPN IP', 'VPNs here'])) {
            $this->Reporter->report([13], [], $this->BlockInfo['IPAddr']);
        }

        /** Process all reports (if any exist, and if not whitelisted), and then destroy the reporter. */
        if (empty($this->CIDRAM['Whitelisted']) && isset($this->Stages['Reporting:Enable'])) {
            $this->Stage = 'Reporting';
            $this->Reporter->process();
            $this->Events->fireEvent('reporterFinished');
            if (isset($this->CIDRAM['LastTestIP'])) {
                if ($this->CIDRAM['LastTestIP'] === 4) {
                    if (isset($this->CIDRAM['Report OK']) && $this->CIDRAM['Report OK'] > 0 && isset($this->StatisticsTracked['Reported-IPv4-OK'])) {
                        $this->Cache->incEntry('Statistics-Reported-IPv4-OK', $this->CIDRAM['Report OK']);
                    }
                    if (isset($this->CIDRAM['Report Failed']) && $this->CIDRAM['Report Failed'] > 0 && isset($this->StatisticsTracked['Reported-IPv4-Failed'])) {
                        $this->Cache->incEntry('Statistics-Reported-IPv4-Failed', $this->CIDRAM['Report Failed']);
                    }
                } elseif ($this->CIDRAM['LastTestIP'] === 6) {
                    if (isset($this->CIDRAM['Report OK']) && $this->CIDRAM['Report OK'] > 0 && isset($this->StatisticsTracked['Reported-IPv6-OK'])) {
                        $this->Cache->incEntry('Statistics-Reported-IPv6-OK', $this->CIDRAM['Report OK']);
                    }
                    if (isset($this->CIDRAM['Report Failed']) && $this->CIDRAM['Report Failed'] > 0 && isset($this->StatisticsTracked['Reported-IPv6-Failed'])) {
                        $this->Cache->incEntry('Statistics-Reported-IPv6-Failed', $this->CIDRAM['Report Failed']);
                    }
                }
            }
        }

        /** Cleanup. */
        $this->Reporter = null;

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
        if (isset($this->Stages['Webhooks:Enable']) && (count($this->Webhooks) || !empty($this->Configuration['Webhook']['URL']))) {
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
            foreach ($this->Webhooks as $Webhook) {
                /** Safety. */
                if (!is_string($Webhook)) {
                    continue;
                }

                /** Parse any relevant block information to our webhooks. */
                $Webhook = $this->parseVars($this->CIDRAM['ParsedToWebhook'], $Webhook);

                /** Perform request. */
                $Webhook = $this->Request->request($Webhook, $this->CIDRAM['WebhookParams'], $this->CIDRAM['WebhookTimeout']);
            }

            /** Cleanup. */
            unset(
                $Webhook,
                $this->CIDRAM['WebhookParams'],
                $this->CIDRAM['WebhookTimeout'],
                $this->CIDRAM['WebhookVar'],
                $this->CIDRAM['ParsedToWebhook'],
                $this->Configuration['Webhook']
            );
        }

        /** Process email trigger notification queue (will be assigned its own stage configuration for v4; delaying to prevent BC breaks between minor/patch releases). */
        if (isset($this->CIDRAM['Trigger notifications']) && $this->Events->assigned('sendEmail')) {
            $this->Stage = 'TriggerNotifications';
            $Recipient = [
                'Name' => trim($this->Configuration['general']['email_notification_name']),
                'Address' => trim($this->Configuration['general']['email_notification_address'])
            ];
            if ($Recipient['Name'] !== '' && $Recipient['Address'] !== '') {
                $ParsedToEmail = [
                    $this->L10N->getString('field.ID') => $this->BlockInfo['ID'],
                    $this->L10N->getString('field.DateTime') => $this->BlockInfo['DateTime'],
                    $this->L10N->getString('field.IP address') => $this->BlockInfo['IPAddr'],
                    $this->L10N->getString('field.User agent') => $this->BlockInfo['UA'],
                    $this->L10N->getString('field.Reconstructed URI') => $this->BlockInfo['rURI'],
                    $this->L10N->getString('field.Why blocked') => $this->BlockInfo['WhyReason'],
                    $this->L10N->getString('field.Why blocked (detailed)') => $this->BlockInfo['ReasonMessage'],
                    $this->L10N->getString('field.Signatures count') => $this->NumberFormatter->format($this->BlockInfo['SignatureCount'])
                ];
                $BlockInfoForEmailBody = '';
                $this->CIDRAM['Fields'] = array_flip(explode("\n", $this->Configuration['general']['fields']));

                foreach ($ParsedToEmail as $FieldName => &$FieldData) {
                    /** Prevent dangerous HTML in outbound email. */
                    $FieldData = str_replace(
                        ['<', '>', "\r", "\n"],
                        ['&lt;', '&gt;', '&#13;', '&#10;'],
                        $FieldData
                    );

                    if ($FieldData === '') {
                        $FieldData = '-';
                    }
                    $BlockInfoForEmailBody .= $FieldName . ($this->L10N->getString('pair_separator') ?: ': ') . $FieldData . "<br />\n";
                }
                unset($FieldData, $FieldName, $ParsedToEmail);

                /** Prepare message body. */
                $Body = sprintf(
                    $this->L10N->getString('Trigger notification.Template'),
                    $Recipient['Name'],
                    '"' . implode('"<br />"', $this->CIDRAM['Trigger notifications']) . '"<br /><br />' . $BlockInfoForEmailBody
                );

                /** Prepare event data. */
                $EventData = [[$Recipient], $this->L10N->getString('Trigger notification.Subject'), $Body, strip_tags($Body), ''];

                /** Send the email. */
                $this->Events->fireEvent('sendEmail', '', ...$EventData);
                unset($EventData);
            }
            unset($EventData, $Body, $BlockInfoForEmailBody, $Recipient);
        }

        /** Clearing because intermediary. */
        $this->Stage = '';

        /** A fix for correctly displaying LTR/RTL text. */
        if ($this->ClientL10N->Directionality !== 'rtl') {
            $this->L10N->Data['Text Direction'] = 'ltr';
            $this->CIDRAM['FieldTemplates']['textBlockAlign'] = 'text-align:left;';
            $this->CIDRAM['FieldTemplates']['textBlockFloat'] = '';
            $this->CIDRAM['FieldTemplates']['FE_Align'] = 'left';
            $this->CIDRAM['FieldTemplates']['FE_Align_Reverse'] = 'right';
        } else {
            $this->L10N->Data['Text Direction'] = 'rtl';
            $this->CIDRAM['FieldTemplates']['textBlockAlign'] = 'text-align:right;';
            $this->CIDRAM['FieldTemplates']['textBlockFloat'] = 'float:right;';
            $this->CIDRAM['FieldTemplates']['FE_Align'] = 'right';
            $this->CIDRAM['FieldTemplates']['FE_Align_Reverse'] = 'left';
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
                if (!isset($this->CIDRAM['Fields'])) {
                    $this->CIDRAM['Fields'] = array_flip(explode("\n", $this->Configuration['general']['fields']));
                }

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
                $this->addField('ID', 'field.ID', $this->BlockInfo['ID']);
                $this->addField('ScriptIdent', 'field.Script version', $this->BlockInfo['ScriptIdent']);
                $this->addField('DateTime', 'field.DateTime', $this->BlockInfo['DateTime']);
                $this->addField('IPAddr', 'field.IP address', $this->BlockInfo['IPAddr']);
                $this->addField('IPAddrResolved', 'field.IP address (resolved)', $this->BlockInfo['IPAddrResolved']);
                $this->addField('Query', 'field.Query', $this->BlockInfo['Query'], true);
                $this->addField('Referrer', 'field.Referrer', $this->BlockInfo['Referrer'], true);
                $this->addField('UA', 'field.User agent', $this->BlockInfo['UA'], true);
                $this->addField('UALC', 'field.User agent (lower-case)', $this->BlockInfo['UALC'], true);
                $this->addField('SignatureCount', 'field.Signatures count', $this->NumberFormatter->format($this->BlockInfo['SignatureCount']));
                $this->addField('Signatures', 'field.Signatures reference', $this->BlockInfo['Signatures']);
                $this->addField('WhyReason', 'field.Why blocked', $this->BlockInfo['WhyReason'] . '!');
                $this->addField('ReasonMessage', 'field.Why blocked (detailed)', $this->BlockInfo['ReasonMessage'], false, false);
                $this->addField('rURI', 'field.Reconstructed URI', $this->BlockInfo['rURI'], true);
                $this->addField('Infractions', 'field.Infractions', $this->NumberFormatter->format($this->BlockInfo['Infractions']));
                $this->addField('ASNLookup', 'field.ASN lookup', $this->BlockInfo['ASNLookup'], true);
                $this->addField('CCLookup', 'field.Country code lookup', $this->BlockInfo['CCLookup'], true);
                $this->addField('Verified', 'field.Verified identity', $this->BlockInfo['Verified']);
                $this->addField('Expired', 'state_expired', $this->BlockInfo['Expired']);
                $this->addField('Ignored', 'state_ignored', $this->BlockInfo['Ignored']);
                $this->addField('Request_Method', 'field.Request method', $this->BlockInfo['Request_Method'], true);
                $this->addField('Protocol', 'field.Protocol', $this->BlockInfo['Protocol'], true);
                $this->addField('SEC_CH_UA_PLATFORM', 'SEC_CH_UA_PLATFORM', $this->BlockInfo['SEC_CH_UA_PLATFORM'], true);
                $this->addField('SEC_CH_UA_MOBILE', 'SEC_CH_UA_MOBILE', $this->BlockInfo['SEC_CH_UA_MOBILE'], true);
                $this->addField('SEC_CH_UA', 'SEC_CH_UA', $this->BlockInfo['SEC_CH_UA'], true);
                $this->addField('Hostname', 'field.Hostname', $this->BlockInfo['Hostname'], true);
                $this->addField('CAPTCHA', 'field.CAPTCHA state', $this->BlockInfo['CAPTCHA']);
                $this->addField('Inspection', 'field.Conditions inspection', $this->BlockInfo['Inspection'], false, false);
                $this->addField('ClientL10NAccepted', 'field.Language resolution', $this->ClientL10NAccepted, false, false);
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
                    $this->CIDRAM['FieldTemplates']['css_url'] === '' &&
                    !file_exists($this->AssetsPath . $this->CIDRAM['template_file'])
                ) {
                    $this->CIDRAM['template_file'] = 'core/template_default.html';
                }

                /** Prepare to process "more info" entries, if any exist. */
                if (!empty($this->Configuration['More Info']) && !empty($this->BlockInfo['ReasonMessage'])) {
                    $this->BlockInfo['ReasonMessage'] .= sprintf(
                        '<br /><br /><span%s>%s</span>',
                        $this->CIDRAM['L10N-Lang-Attache'],
                        $this->ClientL10N->getString('label.For more information')
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
                            $this->ClientL10N->getString('label.Generated by %s'),
                            '<div id="ScriptIdent" dir="ltr">' . $this->ScriptIdent . '</div>'
                        ) : '',
                        'Title' => $this->ClientL10N->getString($this->Configuration['template_data']['block_event_title']) ?: $this->Configuration['template_data']['block_event_title']
                    ]
                );
                if (!isset($this->CIDRAM['Fields']['ReasonMessage:ShowInPageOutput'])) {
                    $this->CIDRAM['Parsables']['ReasonMessage'] = '';
                }

                /** Pull relevant client-specified L10N data first. */
                if (!empty($this->CIDRAM['L10N-Lang-Attache'])) {
                    $this->CIDRAM['Parsables']['denied'] = $this->ClientL10N->getString('denied');
                }

                /** Append default L10N data. */
                $this->CIDRAM['Parsables'] += $this->L10N->Data;

                if ($this->Configuration['general']['silent_mode'] === '') {
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
                        header('Retry-After: ' . floor($this->Configuration['rate_limiting']['allowance_period']->getAsSeconds()));
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
                        $HTML = '[CIDRAM] ' . $this->ClientL10N->getString('denied');
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
                                    )) . '"><strong>' . $this->ClientL10N->getString('click_here') . '</strong></a>';
                                $this->BlockInfo['EmailAddr'] = "\n  <p class=\"detected\"" . $this->CIDRAM['L10N-Lang-Attache'] . '>' . $this->parseVars([
                                    'ClickHereLink' => $this->BlockInfo['EmailAddr']
                                ], $this->ClientL10N->getString('Support_Email')) . '</p>';
                            } elseif ($this->Configuration['general']['emailaddr_display_style'] === 'noclick') {
                                $this->BlockInfo['EmailAddr'] = "\n  <p class=\"detected\" dir=\"ltr\">" . $this->parseVars([
                                    'EmailAddr' => str_replace(
                                        '@',
                                        '<img src="data:image/gif;base64,R0lGODdhCQAKAIABAAAAAP///ywAAAAACQAKAAACE4yPAcsG+ZR7kcp6pWY4Hb54SAEAOw==" alt="@" />',
                                        '<strong>' . $this->Configuration['general']['emailaddr'] . '</strong>'
                                    )
                                ], $this->ClientL10N->getString('Support_Email_2')) . '</p>';
                            }
                        }

                        /** Include privacy policy. */
                        $this->CIDRAM['Parsables']['pp'] = empty($this->Configuration['legal']['privacy_policy']) ? '' : sprintf(
                            '<br /><a href="%s"%s>%s</a>',
                            $this->Configuration['legal']['privacy_policy'],
                            $this->CIDRAM['L10N-Lang-Attache'],
                            $this->ClientL10N->getString('PrivacyPolicy')
                        );

                        /** Generate HTML output. */
                        $HTML = $this->parseVars(
                            ['EmailAddr' => $this->BlockInfo['EmailAddr']],
                            $this->parseVars($this->CIDRAM['Parsables'], $this->readFile($this->AssetsPath . $this->CIDRAM['template_file']))
                        );
                    }
                } else {
                    $this->CIDRAM['errCode'] = (
                        $this->Configuration['general']['silent_mode_response_header_code'] > 300 &&
                        $this->Configuration['general']['silent_mode_response_header_code'] < 309
                    ) ? $this->Configuration['general']['silent_mode_response_header_code'] : 301;
                    $this->CIDRAM['Status'] = $this->getStatusHTTP($this->CIDRAM['errCode']);
                    header('HTTP/1.0 ' . $this->CIDRAM['errCode'] . ' ' . $this->CIDRAM['Status']);
                    header('HTTP/1.1 ' . $this->CIDRAM['errCode'] . ' ' . $this->CIDRAM['Status']);
                    header('Status: ' . $this->CIDRAM['errCode'] . ' ' . $this->CIDRAM['Status']);
                    header('Location: ' . $this->Configuration['general']['silent_mode']);
                    $HTML = '';
                }
            }

            /** Write to logs. */
            if (isset($this->Stages['WriteLogs:Enable'])) {
                $this->Stage = 'WriteLogs';
                if ($this->Configuration['logging']['log_banned_ips'] || empty($this->CIDRAM['Banned'])) {
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
            if (!empty($this->CIDRAM['Aux Redirect']) && !empty($this->CIDRAM['Aux Status Code']) && $this->CIDRAM['Aux Status Code'] > 300 && $this->CIDRAM['Aux Status Code'] < 309) {
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
                    $this->Configuration['recaptcha']['sitekey'] !== '' &&
                    $this->Configuration['recaptcha']['secret'] !== '' &&
                    class_exists('\CIDRAM\CIDRAM\ReCaptcha') &&
                    (
                        ($this->Configuration['recaptcha']['usemode'] >= 3 && $this->Configuration['recaptcha']['usemode'] <= 5) ||
                        ($this->Configuration['recaptcha']['usemode'] === 6 && (
                            isset($this->BlockInfo['rURI']) &&
                            $this->isSensitive(preg_replace('/\s/', '', strtolower($this->BlockInfo['rURI'])))
                        ))
                    )
                ) {
                    /** Execute the ReCaptcha class. */
                    $CaptchaDone = new ReCaptcha($this);

                    $this->CIDRAM['StatusCodeForNonBlocked'] = $this->Configuration['recaptcha']['nonblocked_status_code'];
                } elseif (
                    $this->Configuration['hcaptcha']['sitekey'] !== '' &&
                    $this->Configuration['hcaptcha']['secret'] !== '' &&
                    class_exists('\CIDRAM\CIDRAM\HCaptcha') &&
                    (
                        ($this->Configuration['hcaptcha']['usemode'] >= 3 && $this->Configuration['hcaptcha']['usemode'] <= 5) ||
                        ($this->Configuration['hcaptcha']['usemode'] === 6 && (
                            isset($this->BlockInfo['rURI']) &&
                            $this->isSensitive(preg_replace('/\s/', '', strtolower($this->BlockInfo['rURI'])))
                        ))
                    )
                ) {
                    /** Execute the HCaptcha class. */
                    $CaptchaDone = new HCaptcha($this);

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
                        $this->ClientL10N->getString('label.Generated by %s'),
                        '<div id="ScriptIdent" dir="ltr">' . $this->ScriptIdent . '</div>'
                    ) : '';
                    $this->CIDRAM['Parsables']['Title'] = $this->ClientL10N->getString($this->Configuration['template_data']['captcha_title']) ?: $this->Configuration['template_data']['captcha_title'];

                    /** Pull relevant client-specified L10N data first. */
                    if (!empty($this->CIDRAM['L10N-Lang-Attache'])) {
                        $this->CIDRAM['Parsables']['captcha_message_automated_traffic'] = $this->ClientL10N->getString('captcha_message_automated_traffic');
                    }

                    /** Append default L10N data. */
                    $this->CIDRAM['Parsables'] += $this->L10N->Data;

                    /** The CAPTCHA template file to use. */
                    $this->CIDRAM['CaptchaTemplateFile'] = 'captcha_' . $this->CIDRAM['FieldTemplates']['theme'] . '.html';

                    /** Fallback for themes without CAPTCHA template files. */
                    if (
                        $this->CIDRAM['FieldTemplates']['theme'] !== 'default' &&
                        !file_exists($this->AssetsPath . 'core/' . $this->CIDRAM['CaptchaTemplateFile'])
                    ) {
                        $this->CIDRAM['CaptchaTemplateFile'] = 'captcha_default.html';
                    }

                    /** Include privacy policy. */
                    $this->CIDRAM['Parsables']['pp'] = empty($this->Configuration['legal']['privacy_policy']) ? '' : sprintf(
                        '<br /><a href="%s"%s>%s</a>',
                        $this->Configuration['legal']['privacy_policy'],
                        $this->CIDRAM['L10N-Lang-Attache'],
                        $this->ClientL10N->getString('PrivacyPolicy')
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
                        $this->readFile($this->AssetsPath . 'core/' . $this->CIDRAM['CaptchaTemplateFile'])
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
                $this->ClientL10N->getString($FieldName) ?: $this->L10N->getString($FieldName) ?: $FieldName,
                $this->ClientL10N->getString('pair_separator') ?: $this->L10N->getString('pair_separator') ?: ': ',
                $Prepared
            );
        }
    }
}
