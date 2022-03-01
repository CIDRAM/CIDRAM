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
 * This file: Output generator (last modified: 2022.03.01).
 */

/** Initialise cache. */
$CIDRAM['InitialiseCache']();

/** Initialise an error handler. */
$CIDRAM['InitialiseErrorHandler']();

/** Initialise stages. */
$CIDRAM['Stages'] = array_flip(explode("\n", $CIDRAM['Config']['general']['stages']));

/** Usable by events to determine which part of the output generator we're at. */
$CIDRAM['Stage'] = '';

/** Initialise statistics tracked. */
$CIDRAM['StatisticsTracked'] = array_flip(explode("\n", $CIDRAM['Config']['general']['statistics']));

/** Reset bypass flags. */
$CIDRAM['ResetBypassFlags']();

/** To be populated by webhooks. */
$CIDRAM['Webhooks'] = [];

/** Reset request profiling. */
$CIDRAM['Profile'] = [];

/** Initialise statistics if necessary. */
if (isset($CIDRAM['Stages']['Statistics:Enable'])) {
    $CIDRAM['InitialiseCacheSection']('Statistics');
    if (!isset($CIDRAM['Statistics']['Other-Since'])) {
        $CIDRAM['Statistics'] = [
            'Other-Since' => $CIDRAM['Now'],
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
        $CIDRAM['Statistics-Modified'] = true;
    }
}

/**
 * Determine whether the front-end is being accessed, and whether the normal
 * blocking procedures should occur for this request.
 */
$CIDRAM['Protect'] = (
    $CIDRAM['Config']['general']['protect_frontend'] ||
    $CIDRAM['Config']['general']['disable_frontend'] ||
    !$CIDRAM['Direct']
);

/** Prepare variables for block information (used if we kill the request). */
$CIDRAM['BlockInfo'] = [
    'DateTime' => $CIDRAM['TimeFormat']($CIDRAM['Now'], $CIDRAM['Config']['general']['time_format']),
    'ID' => $CIDRAM['GenerateID'](),
    'IPAddr' => $CIDRAM['IPAddr'],
    'IPAddrResolved' => $CIDRAM['Resolve6to4']($CIDRAM['IPAddr']),
    'ScriptIdent' => $CIDRAM['ScriptIdent'],
    'favicon' => $CIDRAM['favicon'],
    'favicon_extension' => $CIDRAM['favicon_extension'],
    'Query' => $CIDRAM['Query'],
    'Referrer' => empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'],
    'UA' => empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'],
    'ReasonMessage' => '',
    'SignatureCount' => 0,
    'Signatures' => '',
    'WhyReason' => '',
    'ASNLookup' => 0,
    'CCLookup' => 'XX',
    'Verified' => '',
    'Expired' => '',
    'Ignored' => '',
    'xmlLang' => $CIDRAM['Config']['general']['lang']
];
$CIDRAM['BlockInfo']['UALC'] = strtolower($CIDRAM['BlockInfo']['UA']);
$CIDRAM['BlockInfo']['rURI'] = (
    (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
    (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') ||
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
) ? 'https://' : 'http://';
$CIDRAM['BlockInfo']['rURI'] .= $CIDRAM['HTTP_HOST'] ?: 'Unknown.Host';
$CIDRAM['BlockInfo']['rURI'] .= $_SERVER['REQUEST_URI'] ?? '/';

/** Initialise page output and block event logfile fields. */
$CIDRAM['FieldTemplates'] = $CIDRAM['Config']['template_data'] + [
    'Logs' => '',
    'Output' => [],
    'captcha_api_include' => '',
    'captcha_div_include' => '',
];

/** The normal blocking procedures should occur. */
if ($CIDRAM['Protect'] && !$CIDRAM['Config']['general']['maintenance_mode'] && isset($CIDRAM['Stages']['Tests:Enable'])) {
    $CIDRAM['Stage'] = 'Tests';

    /** Run all IPv4/IPv6 tests. */
    try {
        $CIDRAM['TestResults'] = $CIDRAM['RunTests']($CIDRAM['BlockInfo']['IPAddr'], true);
    } catch (\Exception $e) {
        $CIDRAM['Events']->fireEvent('final');
        die($e->getMessage());
    }

    /** Run all IPv4/IPv6 tests for resolved IP address if necessary. */
    if ($CIDRAM['BlockInfo']['IPAddrResolved'] && $CIDRAM['TestResults'] && empty($CIDRAM['Whitelisted'])) {
        try {
            $CIDRAM['TestResults'] = $CIDRAM['RunTests']($CIDRAM['BlockInfo']['IPAddrResolved'], true);
        } catch (\Exception $e) {
            $CIDRAM['Events']->fireEvent('final');
            die($e->getMessage());
        }
    }

    /**
     * If all tests fail, report an invalid IP address.
     */
    if (!$CIDRAM['TestResults']) {
        $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['L10N']->getString('ReasonMessage_BadIP');
        $CIDRAM['BlockInfo']['WhyReason'] = $CIDRAM['L10N']->getString('Short_BadIP');
        $CIDRAM['BlockInfo']['SignatureCount']++;
    }

    /**
     * Check whether we're tracking the IP due to previous instances of bad
     * behaviour.
     */
    elseif (isset($CIDRAM['Stages']['Tracking:Enable'])) {
        $CIDRAM['Stage'] = 'Tracking';
        if (($CIDRAM['BlockInfo']['IPAddr'] && isset(
            $CIDRAM['Tracking'][$CIDRAM['BlockInfo']['IPAddr']],
            $CIDRAM['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Count']
        ) && (
            $CIDRAM['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Count'] >= $CIDRAM['Config']['signatures']['infraction_limit']
        )) || ($CIDRAM['BlockInfo']['IPAddrResolved'] && isset(
            $CIDRAM['Tracking'][$CIDRAM['BlockInfo']['IPAddrResolved']],
            $CIDRAM['Tracking'][$CIDRAM['BlockInfo']['IPAddrResolved']]['Count']
        ) && (
            $CIDRAM['Tracking'][$CIDRAM['BlockInfo']['IPAddrResolved']]['Count'] >= $CIDRAM['Config']['signatures']['infraction_limit']
        ))) {
            $CIDRAM['Banned'] = true;
            $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['L10N']->getString('ReasonMessage_Banned');
            $CIDRAM['BlockInfo']['WhyReason'] = $CIDRAM['L10N']->getString('Short_Banned');
            $CIDRAM['BlockInfo']['SignatureCount']++;
        }
    }
    $CIDRAM['Stage'] = '';
}

/** Define whether to track the IP of the current request. */
$CIDRAM['Trackable'] = $CIDRAM['Config']['signatures']['track_mode'];

/** Perform forced hostname lookup if this has been enabled. */
if ($CIDRAM['Config']['general']['force_hostname_lookup']) {
    $CIDRAM['Hostname'] = $CIDRAM['DNS-Reverse']($CIDRAM['BlockInfo']['IPAddrResolved'] ?: $CIDRAM['BlockInfo']['IPAddr']);
}

/** Executed only if maintenance mode is disabled. */
if ($CIDRAM['Protect'] && !$CIDRAM['Config']['general']['maintenance_mode'] && empty($CIDRAM['Whitelisted'])) {
    /** Instantiate report orchestrator (used by some modules). */
    $CIDRAM['Reporter'] = new \CIDRAM\Core\Reporter();

    /** Identify proxy connections (conjunctive reporting element). */
    if (strpos($CIDRAM['BlockInfo']['WhyReason'], $CIDRAM['L10N']->getString('Short_Proxy')) !== false) {
        $CIDRAM['Reporter']->report([9, 13], [], $CIDRAM['BlockInfo']['IPAddr']);
    }

    /** Execute modules, if any have been enabled. */
    if (empty($CIDRAM['Whitelisted']) && $CIDRAM['Config']['signatures']['modules'] && isset($CIDRAM['Stages']['Modules:Enable'])) {
        $CIDRAM['Stage'] = 'Modules';
        if (!isset($CIDRAM['ModuleResCache'])) {
            $CIDRAM['ModuleResCache'] = [];
        }
        $CIDRAM['Modules'] = explode(',', $CIDRAM['Config']['signatures']['modules']);
        if (!$CIDRAM['Config']['signatures']['tracking_override']) {
            $CIDRAM['Restore tracking options override'] = $CIDRAM['Tracking options override'] ?? '';
        }

        /**
         * Doing this with array_walk instead of foreach to ensure that modules
         * have their own scope and that superfluous data isn't preserved.
         */
        array_walk($CIDRAM['Modules'], function ($Module) use (&$CIDRAM): void {
            if (
                !empty($CIDRAM['Whitelisted']) ||
                preg_match('~^(?:classes|fe_assets)[\x2F\x5C]|\.(css|gif|html?|jpe?g|js|png|ya?ml)$~i', $Module)
            ) {
                return;
            }
            $Module = (strpos($Module, ':') === false) ? $Module : substr($Module, strpos($Module, ':') + 1);
            $Infractions = $CIDRAM['BlockInfo']['SignatureCount'];
            if (isset($CIDRAM['ModuleResCache'][$Module]) && is_object($CIDRAM['ModuleResCache'][$Module])) {
                $CIDRAM['ModuleResCache'][$Module]($Infractions);
            } elseif (!file_exists($CIDRAM['Vault'] . $Module) || !is_readable($CIDRAM['Vault'] . $Module)) {
                return;
            } else {
                require $CIDRAM['Vault'] . $Module;
            }
            $CIDRAM['Trackable'] = $CIDRAM['Trackable'] ?: ($CIDRAM['BlockInfo']['SignatureCount'] - $Infractions) > 0;
        });

        if (
            !$CIDRAM['Config']['signatures']['tracking_override'] &&
            !empty($CIDRAM['Tracking options override']) &&
            isset($CIDRAM['Restore tracking options override'])
        ) {
            $CIDRAM['Tracking options override'] = $CIDRAM['Restore tracking options override'];
            unset($CIDRAM['Restore tracking options override']);
        }
        unset($CIDRAM['Modules']);
    }

    /** Execute search engine verification. */
    if (empty($CIDRAM['Whitelisted']) && isset($CIDRAM['Stages']['SearchEngineVerification:Enable'])) {
        $CIDRAM['Stage'] = 'SearchEngineVerification';
        $CIDRAM['SearchEngineVerification']();
    }

    /** Execute social media verification. */
    if (empty($CIDRAM['Whitelisted']) && isset($CIDRAM['Stages']['SocialMediaVerification:Enable'])) {
        $CIDRAM['Stage'] = 'SocialMediaVerification';
        $CIDRAM['SocialMediaVerification']();
    }

    /** Execute other verification. */
    if (empty($CIDRAM['Whitelisted']) && isset($CIDRAM['Stages']['OtherVerification:Enable'])) {
        $CIDRAM['Stage'] = 'OtherVerification';
        $CIDRAM['OtherVerification']();
    }

    /** Process auxiliary rules, if any exist. */
    if (empty($CIDRAM['Whitelisted']) && isset($CIDRAM['Stages']['Aux:Enable'])) {
        $CIDRAM['Stage'] = 'Aux';
        $CIDRAM['Aux']();
    }

    /** Process all reports (if any exist, and if not whitelisted), and then destroy the reporter. */
    if (empty($CIDRAM['Whitelisted']) && isset($CIDRAM['Stages']['Reporting:Enable'])) {
        $CIDRAM['Stage'] = 'Reporting';
        $CIDRAM['Reporter']->process();
    }

    /** Cleanup. */
    unset($CIDRAM['Reporter']);
}

/** Process tracking information for the inbound IP. */
if (!empty($CIDRAM['TestResults']) && $CIDRAM['BlockInfo']['SignatureCount'] && $CIDRAM['Trackable'] && isset($CIDRAM['Stages']['Tracking:Enable'])) {
    $CIDRAM['Stage'] = 'Tracking';

    /** Set tracking expiry. */
    $CIDRAM['TrackTime'] = $CIDRAM['Now'] + ($CIDRAM['Config']['Options']['TrackTime'] ?? $CIDRAM['Config']['signatures']['default_tracktime']);

    /** Number of infractions to append. */
    $CIDRAM['TrackCount'] = $CIDRAM['Config']['Options']['TrackCount'] ?? 1;

    /** Tracking options override. */
    if (!empty($CIDRAM['Tracking options override'])) {
        if ($CIDRAM['Tracking options override'] === 'extended') {
            $CIDRAM['TrackTime'] = $CIDRAM['Now'] + floor($CIDRAM['Config']['signatures']['default_tracktime'] * 52.1428571428571);
            $CIDRAM['TrackCount'] = 1000;
        } elseif ($CIDRAM['Tracking options override'] === 'default') {
            $CIDRAM['TrackTime'] = $CIDRAM['Now'] + $CIDRAM['Config']['signatures']['default_tracktime'];
            $CIDRAM['TrackCount'] = 1;
        }
    }

    if (isset(
        $CIDRAM['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Count'],
        $CIDRAM['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Time']
    )) {
        $CIDRAM['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Count'] += $CIDRAM['TrackCount'];
        if ($CIDRAM['TrackTime'] > $CIDRAM['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Time']) {
            $CIDRAM['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Time'] = $CIDRAM['TrackTime'];
        }
    } else {
        $CIDRAM['Tracking'][$CIDRAM['BlockInfo']['IPAddr']] = [
            'Count' => $CIDRAM['TrackCount'],
            'Time' => $CIDRAM['TrackTime']
        ];
    }
    $CIDRAM['Tracking-Modified'] = true;

    if ($CIDRAM['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Count'] >= $CIDRAM['Config']['signatures']['infraction_limit']) {
        $CIDRAM['Banned'] = true;
    }

    /** Cleanup. */
    unset($CIDRAM['TrackCount'], $CIDRAM['TrackTime']);
}

/**
 * Process rate limiting, if it's active. This feature exists for those that
 * need it, but I really don't recommend using this feature if at all possible.
 */
if (isset($CIDRAM['Stages']['RL:Enable'])) {
    $CIDRAM['Stage'] = 'RL';

    /** Maximum bandwidth for rate limiting. */
    $CIDRAM['RL_MaxBandwidth'] = $CIDRAM['ReadBytes']($CIDRAM['Config']['rate_limiting']['max_bandwidth']);

    /** Check whether rate limiting is active. */
    $CIDRAM['RL_Active'] = isset($CIDRAM['Stages']['RL:Enable']) && (
        ($CIDRAM['Config']['rate_limiting']['max_requests'] > 0 || $CIDRAM['RL_MaxBandwidth'] > 0) &&
        ($CIDRAM['Protect'] && !$CIDRAM['Config']['general']['maintenance_mode'] && empty($CIDRAM['Whitelisted']))
    );

    if ($CIDRAM['RL_Active'] && isset($CIDRAM['Factors']) && (!$CIDRAM['Config']['rate_limiting']['exceptions'] || (
        !($CIDRAM['BlockInfo']['Verified'] && preg_match('~(?:^|\n)Verified(?:\n|$)~', $CIDRAM['Config']['rate_limiting']['exceptions'])) &&
        !(!empty($CIDRAM['Whitelisted']) && preg_match('~(?:^|\n)Whitelisted(?:\n|$)~', $CIDRAM['Config']['rate_limiting']['exceptions']))
    ))) {
        if (
            $CIDRAM['LastTestIP'] === 4 &&
            $CIDRAM['Config']['rate_limiting']['precision_ipv4'] > 0 &&
            $CIDRAM['Config']['rate_limiting']['precision_ipv4'] < 33 &&
            isset($CIDRAM['Factors'][$CIDRAM['Config']['rate_limiting']['precision_ipv4'] - 1])
        ) {
            $CIDRAM['RL_Capture'] = $CIDRAM['Factors'][$CIDRAM['Config']['rate_limiting']['precision_ipv4'] - 1];
        } elseif (
            $CIDRAM['LastTestIP'] === 6 &&
            $CIDRAM['Config']['rate_limiting']['precision_ipv6'] > 0 &&
            $CIDRAM['Config']['rate_limiting']['precision_ipv6'] < 129 &&
            isset($CIDRAM['Factors'][$CIDRAM['Config']['rate_limiting']['precision_ipv6'] - 1])
        ) {
            $CIDRAM['RL_Capture'] = $CIDRAM['Factors'][$CIDRAM['Config']['rate_limiting']['precision_ipv6'] - 1];
        }
        if (!empty($CIDRAM['RL_Capture'])) {
            $CIDRAM['RL_Capture'] = pack('l*', strlen($CIDRAM['RL_Capture'])) . $CIDRAM['RL_Capture'];
            $CIDRAM['RL_Fetch']();
            if (strlen($CIDRAM['RL_Data']) > 4) {
                $CIDRAM['RL_Expired'] = $CIDRAM['Now'] - ($CIDRAM['Config']['rate_limiting']['allowance_period'] * 3600);
                $CIDRAM['RL_Oldest'] = unpack('l*', substr($CIDRAM['RL_Data'], 0, 4));
                if ($CIDRAM['RL_Oldest'][1] < $CIDRAM['RL_Expired']) {
                    $CIDRAM['RL_Clean']();
                }
                $CIDRAM['RL_Usage'] = $CIDRAM['RL_Get_Usage']();
                if ($CIDRAM['Trigger']((
                    ($CIDRAM['RL_MaxBandwidth'] > 0 && $CIDRAM['RL_Usage']['Bytes'] >= $CIDRAM['RL_MaxBandwidth']) ||
                    ($CIDRAM['Config']['rate_limiting']['max_requests'] > 0 && $CIDRAM['RL_Usage']['Requests'] >= $CIDRAM['Config']['rate_limiting']['max_requests'])
                ), $CIDRAM['L10N']->getString('Short_RL'))) {
                    $CIDRAM['Config']['recaptcha']['usemode'] = 0;
                    $CIDRAM['Config']['recaptcha']['enabled'] = false;
                    $CIDRAM['Config']['hcaptcha']['usemode'] = 0;
                    $CIDRAM['Config']['hcaptcha']['enabled'] = false;
                    $CIDRAM['RL_Status'] = $CIDRAM['GetStatusHTTP'](429);
                }
                unset($CIDRAM['RL_Usage'], $CIDRAM['RL_Oldest'], $CIDRAM['RL_Expired']);
            }
            $CIDRAM['RL_Size'] = 0;
            ob_start(function ($In) use (&$CIDRAM) {
                $CIDRAM['RL_Size'] += strlen($In);
                return $In;
            }, 1);
            register_shutdown_function(function () use (&$CIDRAM) {
                $CIDRAM['RL_WriteEvent']($CIDRAM['RL_Capture'], $CIDRAM['RL_Size']);
                $CIDRAM['DestroyCacheObject']();
                ob_end_flush();
            });
        }
    }
}

/** This code block only executed if signatures were triggered. */
if (isset($CIDRAM['Stages']['CAPTCHA:Enable']) && $CIDRAM['BlockInfo']['SignatureCount'] > 0) {
    $CIDRAM['Stage'] = 'CAPTCHA';

    if (
        !empty($CIDRAM['Config']['recaptcha']['sitekey']) &&
        !empty($CIDRAM['Config']['recaptcha']['secret']) &&
        empty($CIDRAM['Banned']) &&
        class_exists('\CIDRAM\Core\ReCaptcha') &&
        $CIDRAM['BlockInfo']['SignatureCount'] <= $CIDRAM['Config']['recaptcha']['signature_limit'] &&
        (
            $CIDRAM['Config']['recaptcha']['usemode'] === 1 ||
            $CIDRAM['Config']['recaptcha']['usemode'] === 3 ||
            (
                (
                    $CIDRAM['Config']['recaptcha']['usemode'] === 2 ||
                    $CIDRAM['Config']['recaptcha']['usemode'] === 5
                ) && !empty($CIDRAM['Config']['recaptcha']['enabled'])
            )
        )
    ) {
        /** Execute the ReCaptcha class. */
        $CIDRAM['CaptchaDone'] = new \CIDRAM\Core\ReCaptcha($CIDRAM);
    } elseif (
        !empty($CIDRAM['Config']['hcaptcha']['sitekey']) &&
        !empty($CIDRAM['Config']['hcaptcha']['secret']) &&
        empty($CIDRAM['Banned']) &&
        class_exists('\CIDRAM\Core\HCaptcha') &&
        $CIDRAM['BlockInfo']['SignatureCount'] <= $CIDRAM['Config']['hcaptcha']['signature_limit'] &&
        (
            $CIDRAM['Config']['hcaptcha']['usemode'] === 1 ||
            $CIDRAM['Config']['hcaptcha']['usemode'] === 3 ||
            (
                (
                    $CIDRAM['Config']['hcaptcha']['usemode'] === 2 ||
                    $CIDRAM['Config']['hcaptcha']['usemode'] === 5
                ) && !empty($CIDRAM['Config']['hcaptcha']['enabled'])
            )
        )
    ) {
        /** Execute the HCaptcha class. */
        $CIDRAM['CaptchaDone'] = new \CIDRAM\Core\HCaptcha($CIDRAM);
    }
}

/** Update statistics if necessary. */
if (isset($CIDRAM['Stages']['Statistics:Enable'])) {
    $CIDRAM['Stage'] = 'Statistics';
    if ($CIDRAM['BlockInfo']['SignatureCount'] > 0) {
        if (!empty($CIDRAM['Banned'])) {
            if ($CIDRAM['BlockInfo']['IPAddrResolved']) {
                if (isset($CIDRAM['StatisticsTracked']['Banned-IPv4'])) {
                    $CIDRAM['Statistics']['Banned-IPv4']++;
                    $CIDRAM['Statistics-Modified'] = true;
                }
                if (isset($CIDRAM['StatisticsTracked']['Banned-IPv6'])) {
                    $CIDRAM['Statistics']['Banned-IPv6']++;
                    $CIDRAM['Statistics-Modified'] = true;
                }
            } elseif ($CIDRAM['LastTestIP'] === 4) {
                if (isset($CIDRAM['StatisticsTracked']['Banned-IPv4'])) {
                    $CIDRAM['Statistics']['Banned-IPv4']++;
                    $CIDRAM['Statistics-Modified'] = true;
                }
            } elseif ($CIDRAM['LastTestIP'] === 6) {
                if (isset($CIDRAM['StatisticsTracked']['Banned-IPv6'])) {
                    $CIDRAM['Statistics']['Banned-IPv6']++;
                    $CIDRAM['Statistics-Modified'] = true;
                }
            }
        } elseif ($CIDRAM['BlockInfo']['IPAddrResolved']) {
            if (isset($CIDRAM['StatisticsTracked']['Blocked-IPv4'])) {
                $CIDRAM['Statistics']['Blocked-IPv4']++;
                $CIDRAM['Statistics-Modified'] = true;
            }
            if (isset($CIDRAM['StatisticsTracked']['Blocked-IPv6'])) {
                $CIDRAM['Statistics']['Blocked-IPv6']++;
                $CIDRAM['Statistics-Modified'] = true;
            }
        } elseif ($CIDRAM['LastTestIP'] === 4) {
            if (isset($CIDRAM['StatisticsTracked']['Blocked-IPv4'])) {
                $CIDRAM['Statistics']['Blocked-IPv4']++;
                $CIDRAM['Statistics-Modified'] = true;
            }
        } elseif ($CIDRAM['LastTestIP'] === 6) {
            if (isset($CIDRAM['StatisticsTracked']['Blocked-IPv6'])) {
                $CIDRAM['Statistics']['Blocked-IPv6']++;
                $CIDRAM['Statistics-Modified'] = true;
            }
        } else {
            if (isset($CIDRAM['StatisticsTracked']['Blocked-Other'])) {
                $CIDRAM['Statistics']['Blocked-Other']++;
                $CIDRAM['Statistics-Modified'] = true;
            }
        }
    } else {
        if ($CIDRAM['LastTestIP'] === 4) {
            if (isset($CIDRAM['StatisticsTracked']['Passed-IPv4'])) {
                $CIDRAM['Statistics']['Passed-IPv4']++;
                $CIDRAM['Statistics-Modified'] = true;
            }
        } elseif ($CIDRAM['LastTestIP'] === 6) {
            if (isset($CIDRAM['StatisticsTracked']['Passed-IPv6'])) {
                $CIDRAM['Statistics']['Passed-IPv6']++;
                $CIDRAM['Statistics-Modified'] = true;
            }
        } else {
            if (isset($CIDRAM['StatisticsTracked']['Passed-Other'])) {
                $CIDRAM['Statistics']['Passed-Other']++;
                $CIDRAM['Statistics-Modified'] = true;
            }
        }
    }
}

/** Destroy cache object and some related values. */
if (empty($CIDRAM['RL_Capture'])) {
    $CIDRAM['DestroyCacheObject']();
}

/** Process webhooks. */
if (isset($CIDRAM['Stages']['Webhooks:Enable']) && !empty($CIDRAM['Webhooks']) || !empty($CIDRAM['Config']['Webhook']['URL'])) {
    $CIDRAM['Stage'] = 'Webhooks';

    /** Safety. */
    if (!isset($CIDRAM['Config']['Webhook'])) {
        $CIDRAM['Config']['Webhook'] = [];
    }

    /** Merge webhooks defined by signature files with webhooks defined by auxiliary rules. */
    if (!empty($CIDRAM['Config']['Webhook']['URL'])) {
        $CIDRAM['Arrayify']($CIDRAM['Config']['Webhook']['URL']);
        $CIDRAM['Webhooks'] = array_merge($CIDRAM['Webhooks'], $CIDRAM['Config']['Webhook']['URL']);
    }

    /** Block information copied here to be further processed for sending with the request. */
    $CIDRAM['ParsedToWebhook'] = $CIDRAM['BlockInfo'];

    /** Some further processing. */
    foreach ($CIDRAM['ParsedToWebhook'] as &$CIDRAM['WebhookVar']) {
        $CIDRAM['WebhookVar'] = urlencode($CIDRAM['WebhookVar']);
    }

    /** Set timeout. */
    $CIDRAM['WebhookTimeout'] = $CIDRAM['Config']['Webhook']['Timeout'] ?? $CIDRAM['Request']->DefaultTimeout;

    /** Process any special parameters. */
    if (empty($CIDRAM['Config']['Webhook']['Params'])) {
        $CIDRAM['WebhookParams'] = [];
    } else {
        $CIDRAM['WebhookParams'] = $CIDRAM['Config']['Webhook']['Params'];
        $CIDRAM['Arrayify']($CIDRAM['WebhookParams']);

        /** Parse any relevant block information to our webhook params. */
        $CIDRAM['WebhookParams'] = $CIDRAM['ParseVars'](
            $CIDRAM['ParsedToWebhook'],
            $CIDRAM['Config']['Webhook']['Params']
        );
    }

    /** Merge with block information. */
    $CIDRAM['WebhookParams'] = array_merge($CIDRAM['BlockInfo'], $CIDRAM['WebhookParams']);

    /** Remove useless parameters. */
    unset($CIDRAM['WebhookParams']['favicon'], $CIDRAM['WebhookParams']['favicon_extension']);

    /** Iterate through each webhook. */
    foreach ($CIDRAM['Webhooks'] as $CIDRAM['Webhook']) {
        /** Safety. */
        if (!is_string($CIDRAM['Webhook'])) {
            continue;
        }

        /** Parse any relevant block information to our webhooks. */
        $CIDRAM['Webhook'] = $CIDRAM['ParseVars']($CIDRAM['ParsedToWebhook'], $CIDRAM['Webhook']);

        /** Perform request. */
        $CIDRAM['Webhook'] = $CIDRAM['Request'](
            $CIDRAM['Webhook'],
            $CIDRAM['WebhookParams'],
            $CIDRAM['WebhookTimeout']
        );
    }

    /** Cleanup. */
    unset(
        $CIDRAM['Webhook'],
        $CIDRAM['WebhookParams'],
        $CIDRAM['WebhookTimeout'],
        $CIDRAM['WebhookVar'],
        $CIDRAM['ParsedToWebhook'],
        $CIDRAM['Webhooks'],
        $CIDRAM['Config']['Webhook']
    );
}

/** Clearing because intermediary. */
$CIDRAM['Stage'] = '';

/** A fix for correctly displaying LTR/RTL text. */
if ($CIDRAM['L10N']->getString('Text Direction') !== 'rtl') {
    $CIDRAM['L10N']->Data['Text Direction'] = 'ltr';
    $CIDRAM['FieldTemplates']['textBlockAlign'] = 'text-align:left;';
    $CIDRAM['FieldTemplates']['textBlockFloat'] = '';
} else {
    $CIDRAM['FieldTemplates']['textBlockAlign'] = 'text-align:right;';
    $CIDRAM['FieldTemplates']['textBlockFloat'] = 'float:right;';
}

/** Provided for v1-v2 template file backwards compatibility. */
if (
    isset($CIDRAM['Config']['template_data']['magnification']) &&
    !isset($CIDRAM['Config']['template_data']['Magnification'])
) {
    unset($CIDRAM['FieldTemplates']['magnification']);
    $CIDRAM['FieldTemplates']['Magnification'] = $CIDRAM['Config']['template_data']['magnification'];
}

/** If any signatures were triggered, log it, generate output, then die. */
if ($CIDRAM['BlockInfo']['SignatureCount'] > 0) {
    if (isset($CIDRAM['Stages']['PrepareFields:Enable'])) {
        $CIDRAM['Stage'] = 'PrepareFields';

        /** Set CAPTCHA status. */
        if (empty($CIDRAM['BlockInfo']['CAPTCHA'])) {
            $CIDRAM['BlockInfo']['CAPTCHA'] = $CIDRAM['L10N']->getString('state_disabled');
        }

        /** IP address pseudonymisation. */
        if ($CIDRAM['Config']['legal']['pseudonymise_ip_addresses'] && $CIDRAM['TestResults']) {
            $CIDRAM['BlockInfo']['IPAddr'] = $CIDRAM['Pseudonymise-IP']($CIDRAM['BlockInfo']['IPAddr']);
            if ($CIDRAM['BlockInfo']['IPAddrResolved']) {
                $CIDRAM['BlockInfo']['IPAddrResolved'] = $CIDRAM['Pseudonymise-IP']($CIDRAM['BlockInfo']['IPAddrResolved']);
            }
        }

        /** IP address omission. */
        if ($CIDRAM['Config']['legal']['omit_ip']) {
            if (isset($CIDRAM['BlockInfo']['IPAddr'])) {
                unset($CIDRAM['BlockInfo']['IPAddr']);
            }
            if (isset($CIDRAM['BlockInfo']['IPAddrResolved'])) {
                unset($CIDRAM['BlockInfo']['IPAddrResolved']);
            }
        }

        /** Build fields. */
        $CIDRAM['AddField'](
            $CIDRAM['L10N']->getString('field_id'),
            $CIDRAM['Client-L10N']->getString('field_id'),
            $CIDRAM['BlockInfo']['ID']
        );
        if (!$CIDRAM['Config']['general']['hide_version']) {
            $CIDRAM['AddField'](
                $CIDRAM['L10N']->getString('field_scriptversion'),
                $CIDRAM['Client-L10N']->getString('field_scriptversion'),
                $CIDRAM['BlockInfo']['ScriptIdent']
            );
        }
        $CIDRAM['AddField'](
            $CIDRAM['L10N']->getString('field_datetime'),
            $CIDRAM['Client-L10N']->getString('field_datetime'),
            $CIDRAM['BlockInfo']['DateTime']
        );
        $CIDRAM['AddField'](
            $CIDRAM['L10N']->getString('field_ipaddr'),
            $CIDRAM['Client-L10N']->getString('field_ipaddr'),
            $CIDRAM['BlockInfo']['IPAddr']
        );
        if ($CIDRAM['BlockInfo']['IPAddrResolved'] || $CIDRAM['Config']['general']['empty_fields'] === 'include') {
            if (!$CIDRAM['BlockInfo']['IPAddrResolved']) {
                $CIDRAM['BlockInfo']['IPAddrResolved'] = '-';
            }
            $CIDRAM['AddField'](
                $CIDRAM['L10N']->getString('field_ipaddr_resolved'),
                $CIDRAM['Client-L10N']->getString('field_ipaddr_resolved'),
                $CIDRAM['BlockInfo']['IPAddrResolved']
            );
        }
        if ((
            !empty($CIDRAM['Hostname']) && $CIDRAM['Hostname'] !== $CIDRAM['BlockInfo']['IPAddr']
        ) || $CIDRAM['Config']['general']['empty_fields'] === 'include') {
            $CIDRAM['BlockInfo']['Hostname'] = $CIDRAM['Hostname'] ?? '-';
            $CIDRAM['AddField'](
                $CIDRAM['L10N']->getString('field_hostname'),
                $CIDRAM['Client-L10N']->getString('field_hostname'),
                $CIDRAM['BlockInfo']['Hostname'],
                true
            );
        }
        if ($CIDRAM['BlockInfo']['Query'] || $CIDRAM['Config']['general']['empty_fields'] === 'include') {
            $CIDRAM['AddField'](
                $CIDRAM['L10N']->getString('field_query'),
                $CIDRAM['Client-L10N']->getString('field_query'),
                $CIDRAM['BlockInfo']['Query'] ?: '-',
                true
            );
        }
        if ($CIDRAM['BlockInfo']['Referrer'] || $CIDRAM['Config']['general']['empty_fields'] === 'include') {
            $CIDRAM['AddField'](
                $CIDRAM['L10N']->getString('field_referrer'),
                $CIDRAM['Client-L10N']->getString('field_referrer'),
                $CIDRAM['BlockInfo']['Referrer'] ?: '-',
                true
            );
        }
        $CIDRAM['AddField'](
            $CIDRAM['L10N']->getString('field_sigcount'),
            $CIDRAM['Client-L10N']->getString('field_sigcount'),
            $CIDRAM['BlockInfo']['SignatureCount']
        );
        $CIDRAM['AddField'](
            $CIDRAM['L10N']->getString('field_sigref'),
            $CIDRAM['Client-L10N']->getString('field_sigref'),
            $CIDRAM['BlockInfo']['Signatures']
        );
        $CIDRAM['AddField'](
            $CIDRAM['L10N']->getString('field_whyreason'),
            $CIDRAM['Client-L10N']->getString('field_whyreason'),
            $CIDRAM['BlockInfo']['WhyReason'] . '!'
        );
        if ($CIDRAM['BlockInfo']['UA'] || $CIDRAM['Config']['general']['empty_fields'] === 'include') {
            $CIDRAM['AddField'](
                $CIDRAM['L10N']->getString('field_ua'),
                $CIDRAM['Client-L10N']->getString('field_ua'),
                $CIDRAM['BlockInfo']['UA'] ?: '-',
                true
            );
        }
        $CIDRAM['AddField'](
            $CIDRAM['L10N']->getString('field_rURI'),
            $CIDRAM['Client-L10N']->getString('field_rURI'),
            $CIDRAM['BlockInfo']['rURI'],
            true
        );
        if (
            $CIDRAM['Config']['recaptcha']['usemode'] > 0 ||
            $CIDRAM['Config']['hcaptcha']['usemode'] > 0 ||
            $CIDRAM['Config']['general']['empty_fields'] === 'include'
        ) {
            if (empty($CIDRAM['BlockInfo']['CAPTCHA'])) {
                $CIDRAM['BlockInfo']['CAPTCHA'] = $CIDRAM['L10N']->getString('state_disabled');
            }
            $CIDRAM['AddField'](
                $CIDRAM['L10N']->getString('field_captcha'),
                $CIDRAM['Client-L10N']->getString('field_captcha'),
                $CIDRAM['BlockInfo']['CAPTCHA']
            );
        }
    }

    if (isset($CIDRAM['Stages']['Output:Enable'])) {
        $CIDRAM['Stage'] = 'Output';

        /** Finalise fields. */
        $CIDRAM['FieldTemplates']['Output'] = implode("\n        ", $CIDRAM['FieldTemplates']['Output']);

        /** Determine which template file to use, if this hasn't already been determined. */
        if (!isset($CIDRAM['template_file'])) {
            $CIDRAM['template_file'] = (
                !$CIDRAM['FieldTemplates']['css_url']
            ) ? 'template_' . $CIDRAM['FieldTemplates']['theme'] . '.html' : 'template_custom.html';
        }

        /** Fallback for themes without default template files. */
        if (
            $CIDRAM['FieldTemplates']['theme'] !== 'default' &&
            !$CIDRAM['FieldTemplates']['css_url'] &&
            !file_exists($CIDRAM['Vault'] . $CIDRAM['template_file'])
        ) {
            $CIDRAM['template_file'] = 'template_default.html';
        }

        /** Prepare to process "more info" entries, if any exist. */
        if (!empty($CIDRAM['Config']['More Info']) && !empty($CIDRAM['BlockInfo']['ReasonMessage'])) {
            $CIDRAM['BlockInfo']['ReasonMessage'] .= sprintf(
                '<br /><br /><span%s>%s</span>',
                $CIDRAM['L10N-Lang-Attache'],
                $CIDRAM['Client-L10N']->getString('MoreInfo')
            );
            $CIDRAM['Arrayify']($CIDRAM['Config']['More Info']);

            /** Process entries. */
            foreach ($CIDRAM['Config']['More Info'] as $CIDRAM['Info Name'] => $CIDRAM['Info Link']) {
                $CIDRAM['BlockInfo']['ReasonMessage'] .= !empty($CIDRAM['Info Name']) && is_string($CIDRAM['Info Name']) ? (
                    sprintf('<br /><a href="%1$s">%2$s</a>', $CIDRAM['Info Link'], $CIDRAM['Info Name'])
                ) : sprintf('<br /><a href="%1$s">%1$s</a>', $CIDRAM['Info Link']);
            }

            /** Cleanup. */
            unset($CIDRAM['Info Link'], $CIDRAM['Info Name'], $CIDRAM['Config']['More Info']);
        }

        /** Parsed to the template file. */
        $CIDRAM['Parsables'] = array_merge(
            $CIDRAM['FieldTemplates'],
            $CIDRAM['FieldTemplates'],
            $CIDRAM['BlockInfo'],
            [
                'L10N-Lang-Attache' => $CIDRAM['L10N-Lang-Attache'],
                'GeneratedBy' => sprintf(
                    $CIDRAM['Client-L10N']->getString('generated_by'),
                    '<div id="ScriptIdent" dir="ltr">' . $CIDRAM['ScriptIdent'] . '</div>'
                )
            ]
        );

        /** Pull relevant client-specified L10N data first. */
        if (!empty($CIDRAM['L10N-Lang-Attache'])) {
            foreach (['denied', 'captcha_cookie_warning', 'captcha_message', 'captcha_message_invisible', 'label_submit'] as $CIDRAM['PullThis']) {
                if (isset($CIDRAM['Client-L10N']->Data[$CIDRAM['PullThis']])) {
                    $CIDRAM['Parsables'][$CIDRAM['PullThis']] = $CIDRAM['Client-L10N']->Data[$CIDRAM['PullThis']];
                }
            }
            unset($CIDRAM['PullThis']);
        }

        /** Append default L10N data. */
        $CIDRAM['Parsables'] += $CIDRAM['L10N']->Data;

        if (!empty($CIDRAM['Banned']) && $CIDRAM['Config']['general']['ban_override'] > 400 && (
            $CIDRAM['ThisStatusHTTP'] = $CIDRAM['GetStatusHTTP']($CIDRAM['Config']['general']['ban_override'])
        )) {
            $CIDRAM['errCode'] = $CIDRAM['Config']['general']['ban_override'];
            header('HTTP/1.0 ' . $CIDRAM['errCode'] . ' ' . $CIDRAM['ThisStatusHTTP']);
            header('HTTP/1.1 ' . $CIDRAM['errCode'] . ' ' . $CIDRAM['ThisStatusHTTP']);
            header('Status: ' . $CIDRAM['errCode'] . ' ' . $CIDRAM['ThisStatusHTTP']);
            $CIDRAM['HTML'] = '';
        } elseif (!empty($CIDRAM['RL_Status']) && ($CIDRAM['BlockInfo']['SignatureCount'] * 1) === 1) {
            header('HTTP/1.0 429 ' . $CIDRAM['RL_Status']);
            header('HTTP/1.1 429 ' . $CIDRAM['RL_Status']);
            header('Status: 429 ' . $CIDRAM['RL_Status']);
            header('Retry-After: ' . floor($CIDRAM['Config']['rate_limiting']['allowance_period'] * 3600));
            $CIDRAM['HTML'] = '';
        } elseif (!$CIDRAM['Config']['general']['silent_mode']) {
            /** Fetch appropriate status code based on either "forbid_on_block" or "Aux Status Code". */
            if ((
                !empty($CIDRAM['Aux Status Code']) &&
                ($CIDRAM['errCode'] = $CIDRAM['Aux Status Code']) > 400 &&
                $CIDRAM['ThisStatusHTTP'] = $CIDRAM['GetStatusHTTP']($CIDRAM['errCode'])
            ) || (
                ($CIDRAM['errCode'] = $CIDRAM['Config']['general']['forbid_on_block']) > 400 &&
                $CIDRAM['ThisStatusHTTP'] = $CIDRAM['GetStatusHTTP']($CIDRAM['errCode'])
            )) {
                header('HTTP/1.0 ' . $CIDRAM['errCode'] . ' ' . $CIDRAM['ThisStatusHTTP']);
                header('HTTP/1.1 ' . $CIDRAM['errCode'] . ' ' . $CIDRAM['ThisStatusHTTP']);
                header('Status: ' . $CIDRAM['errCode'] . ' ' . $CIDRAM['ThisStatusHTTP']);
            } else {
                $CIDRAM['errCode'] = 200;
            }

            if (!empty($CIDRAM['Suppress output template'])) {
                $CIDRAM['HTML'] = '';
            } elseif (!$CIDRAM['template_file'] || !file_exists($CIDRAM['Vault'] . $CIDRAM['template_file'])) {
                header('Content-Type: text/plain');
                $CIDRAM['HTML'] = '[CIDRAM] ' . $CIDRAM['Client-L10N']->getString('denied');
            } else {
                $CIDRAM['BlockInfo']['EmailAddr'] = '';

                /** Generate email support ticket link. */
                if ($CIDRAM['Config']['general']['emailaddr']) {
                    if ($CIDRAM['Config']['general']['emailaddr_display_style'] === 'default') {
                        $CIDRAM['BlockInfo']['EmailAddr'] =
                            '<a href="mailto:' . $CIDRAM['Config']['general']['emailaddr'] .
                            '?subject=CIDRAM%20Event&body=' . urlencode($CIDRAM['ParseVars'](
                                $CIDRAM['Parsables'],
                                $CIDRAM['FieldTemplates']['Logs'] . "\n"
                            )) . '"><strong>' . $CIDRAM['Client-L10N']->getString('click_here') . '</strong></a>';
                        $CIDRAM['BlockInfo']['EmailAddr'] = "\n  <p class=\"detected\"" . $CIDRAM['L10N-Lang-Attache'] . '>' . $CIDRAM['ParseVars']([
                            'ClickHereLink' => $CIDRAM['BlockInfo']['EmailAddr']
                        ], $CIDRAM['Client-L10N']->getString('Support_Email')) . '</p>';
                    } elseif ($CIDRAM['Config']['general']['emailaddr_display_style'] === 'noclick') {
                        $CIDRAM['BlockInfo']['EmailAddr'] = "\n  <p class=\"detected\">" . $CIDRAM['ParseVars']([
                            'EmailAddr' => str_replace(
                                '@',
                                '<img src="data:image/gif;base64,R0lGODdhCQAKAIABAAAAAP///ywAAAAACQAKAAACE4yPAcsG+ZR7kcp6pWY4Hb54SAEAOw==" alt="@" />',
                                '<strong>' . $CIDRAM['Config']['general']['emailaddr'] . '</strong>'
                            )
                        ], $CIDRAM['Client-L10N']->getString('Support_Email_2')) . '</p>';
                    }
                }

                /** Include privacy policy. */
                $CIDRAM['Parsables']['pp'] = empty($CIDRAM['Config']['legal']['privacy_policy']) ? '' : sprintf(
                    '<br /><a href="%s"%s>%s</a>',
                    $CIDRAM['Config']['legal']['privacy_policy'],
                    $CIDRAM['L10N-Lang-Attache'],
                    $CIDRAM['Client-L10N']->getString('PrivacyPolicy')
                );

                /** Generate HTML output. */
                $CIDRAM['HTML'] = $CIDRAM['ParseVars']([
                    'EmailAddr' => $CIDRAM['BlockInfo']['EmailAddr']
                ], $CIDRAM['ParseVars']($CIDRAM['Parsables'], $CIDRAM['ReadFile'](
                    $CIDRAM['Vault'] . $CIDRAM['template_file']
                )));
            }
        } else {
            $CIDRAM['errCode'] = 301;
            $CIDRAM['Status'] = $CIDRAM['GetStatusHTTP'](301);
            header('HTTP/1.0 301 ' . $CIDRAM['Status']);
            header('HTTP/1.1 301 ' . $CIDRAM['Status']);
            header('Status: 301 ' . $CIDRAM['Status']);
            header('Location: ' . $CIDRAM['Config']['general']['silent_mode']);
            $CIDRAM['HTML'] = '';
        }
    }

    if (isset($CIDRAM['Stages']['WriteLogs:Enable'])) {
        $CIDRAM['Stage'] = 'WriteLogs';

        /**
         * Skip this section if the IP has been banned and logging banned IPs has
         * been disabled, or if the "Don't Log" flag has been set.
         */
        if (empty($CIDRAM['Flag Don\'t Log']) && (
            $CIDRAM['Config']['general']['log_banned_ips'] || empty($CIDRAM['Banned'])
        )) {
            /** Write to logs. */
            $CIDRAM['Events']->fireEvent('writeToLog');
        }
    }

    if (isset($CIDRAM['Stages']['Terminate:Enable'])) {
        $CIDRAM['Stage'] = 'Terminate';

        /** Final event before we exit. */
        $CIDRAM['Events']->fireEvent('final');

        /** All necessary processing and logging has completed; Now we send HTML output and die. */
        die($CIDRAM['HTML']);
    }
}

/** Executed only if request redirection has been triggered by auxiliary rules. */
if (isset($CIDRAM['Stages']['AuxRedirect:Enable'])) {
    $CIDRAM['Stage'] = 'AuxRedirect';
    if (!empty($CIDRAM['Aux Redirect']) && !empty($CIDRAM['Aux Status Code']) && $CIDRAM['Aux Status Code'] > 300 && $CIDRAM['Aux Status Code'] < 400) {
        $CIDRAM['errCode'] = $CIDRAM['Aux Status Code'];
        $CIDRAM['Status'] = $CIDRAM['GetStatusHTTP']($CIDRAM['Aux Status Code']);
        header('HTTP/1.0 ' . $CIDRAM['Aux Status Code'] . ' ' . $CIDRAM['Status']);
        header('HTTP/1.1 ' . $CIDRAM['Aux Status Code'] . ' ' . $CIDRAM['Status']);
        header('Status: ' . $CIDRAM['Aux Status Code'] . ' ' . $CIDRAM['Status']);
        header('Location: ' . $CIDRAM['Aux Redirect']);
        $CIDRAM['Events']->fireEvent('final');
        die;
    }
}

/** This code block executed only for non-blocked CAPTCHA configurations. */
if (isset($CIDRAM['Stages']['NonBlockedCAPTCHA:Enable'])) {
    $CIDRAM['Stage'] = 'NonBlockedCAPTCHA';
    if (empty($CIDRAM['CaptchaDone']) && empty($CIDRAM['Whitelisted']) && empty($CIDRAM['BlockInfo']['Verified'])) {
        if (
            !empty($CIDRAM['Config']['recaptcha']['sitekey']) &&
            !empty($CIDRAM['Config']['recaptcha']['secret']) &&
            class_exists('\CIDRAM\Core\ReCaptcha') &&
            ($CIDRAM['Config']['recaptcha']['usemode'] >= 3 && $CIDRAM['Config']['recaptcha']['usemode'] <= 5)
        ) {
            /** Execute the ReCaptcha class. */
            $CIDRAM['CaptchaDone'] = new \CIDRAM\Core\ReCaptcha($CIDRAM);

            $CIDRAM['StatusCodeForNonBlocked'] = $CIDRAM['Config']['recaptcha']['nonblocked_status_code'];
        } elseif (
            !empty($CIDRAM['Config']['hcaptcha']['sitekey']) &&
            !empty($CIDRAM['Config']['hcaptcha']['secret']) &&
            class_exists('\CIDRAM\Core\HCaptcha') &&
            ($CIDRAM['Config']['hcaptcha']['usemode'] >= 3 && $CIDRAM['Config']['hcaptcha']['usemode'] <= 5)
        ) {
            /** Execute the HCaptcha class. */
            $CIDRAM['CaptchaDone'] = new \CIDRAM\Core\HCaptcha($CIDRAM);

            $CIDRAM['StatusCodeForNonBlocked'] = $CIDRAM['Config']['hcaptcha']['nonblocked_status_code'];
        }

        if (
            !empty($CIDRAM['CaptchaDone']) &&
            is_object($CIDRAM['CaptchaDone']) &&
            isset($CIDRAM['CaptchaDone']->Bypass) &&
            $CIDRAM['CaptchaDone']->Bypass === false
        ) {
            /** Parsed to the CAPTCHA's HTML file. */
            $CIDRAM['Parsables'] = array_merge($CIDRAM['FieldTemplates'], $CIDRAM['FieldTemplates'], $CIDRAM['BlockInfo']);
            $CIDRAM['Parsables']['L10N-Lang-Attache'] = $CIDRAM['L10N-Lang-Attache'];
            $CIDRAM['Parsables']['GeneratedBy'] = sprintf(
                $CIDRAM['Client-L10N']->getString('generated_by'),
                '<div id="ScriptIdent" dir="ltr">' . $CIDRAM['ScriptIdent'] . '</div>'
            );

            /** Pull relevant client-specified L10N data first. */
            if (!empty($CIDRAM['L10N-Lang-Attache'])) {
                foreach (['captcha_cookie_warning', 'captcha_message_automated_traffic', 'captcha_message', 'captcha_message_invisible', 'label_submit'] as $CIDRAM['PullThis']) {
                    if (isset($CIDRAM['Client-L10N']->Data[$CIDRAM['PullThis']])) {
                        $CIDRAM['Parsables'][$CIDRAM['PullThis']] = $CIDRAM['Client-L10N']->Data[$CIDRAM['PullThis']];
                    }
                }
                unset($CIDRAM['PullThis']);
            }

            /** Append default L10N data. */
            $CIDRAM['Parsables'] += $CIDRAM['L10N']->Data;

            /** The CAPTCHA template file to use. */
            $CIDRAM['CaptchaTemplateFile'] = 'captcha_' . $CIDRAM['FieldTemplates']['theme'] . '.html';

            /** Fallback for themes without CAPTCHA template files. */
            if (
                $CIDRAM['FieldTemplates']['theme'] !== 'default' &&
                !file_exists($CIDRAM['Vault'] . $CIDRAM['CaptchaTemplateFile'])
            ) {
                $CIDRAM['CaptchaTemplateFile'] = 'captcha_default.html';
            }

            /** Include privacy policy. */
            $CIDRAM['Parsables']['pp'] = empty($CIDRAM['Config']['legal']['privacy_policy']) ? '' : sprintf(
                '<br /><a href="%s"%s>%s</a>',
                $CIDRAM['Config']['legal']['privacy_policy'],
                $CIDRAM['L10N-Lang-Attache'],
                $CIDRAM['Client-L10N']->getString('PrivacyPolicy')
            );

            /** Process non-blocked status code. */
            if (
                $CIDRAM['StatusCodeForNonBlocked'] > 400 &&
                $CIDRAM['ThisStatusHTTP'] = $CIDRAM['GetStatusHTTP']($CIDRAM['StatusCodeForNonBlocked'])
            ) {
                header('HTTP/1.0 ' . $CIDRAM['StatusCodeForNonBlocked'] . ' ' . $CIDRAM['ThisStatusHTTP']);
                header('HTTP/1.1 ' . $CIDRAM['StatusCodeForNonBlocked'] . ' ' . $CIDRAM['ThisStatusHTTP']);
                header('Status: ' . $CIDRAM['StatusCodeForNonBlocked'] . ' ' . $CIDRAM['ThisStatusHTTP']);
            }

            /** Generate HTML output. */
            $CIDRAM['HTML'] = $CIDRAM['ParseVars'](
                $CIDRAM['Parsables'],
                $CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['CaptchaTemplateFile'])
            );

            /** Final event before we exit. */
            $CIDRAM['Events']->fireEvent('final');

            /** All necessary processing has completed; Now we send HTML output and die. */
            die($CIDRAM['HTML']);
        }
    }
}

/** Final event before we exit. */
$CIDRAM['Events']->fireEvent('final');

/** Restores default error handler. */
$CIDRAM['RestoreErrorHandler']();
