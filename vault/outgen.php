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
 * This file: Output generator (last modified: 2020.04.04).
 */

/** Initialise cache. */
$CIDRAM['InitialiseCache']();

/** Initialise an error handler. */
$CIDRAM['InitialiseErrorHandler']();

/** Usable by events to determine which part of the output generator we're at. */
$CIDRAM['Stage'] = '';

/** Reset bypass flags. */
$CIDRAM['ResetBypassFlags']();

/** Initialise statistics if necessary. */
if ($CIDRAM['Config']['general']['statistics']) {
    $CIDRAM['InitialiseCacheSection']('Statistics');
    if (!isset($CIDRAM['Statistics']['Other-Since'])) {
        $CIDRAM['Statistics'] = [
            'Other-Since' => $CIDRAM['Now'],
            'Blocked-IPv4' => 0,
            'Blocked-IPv6' => 0,
            'Blocked-Other' => 0,
            'Banned-IPv4' => 0,
            'Banned-IPv6' => 0,
            'reCAPTCHA-Failed' => 0,
            'reCAPTCHA-Passed' => 0
        ];
        $CIDRAM['Statistics-Modified'] = true;
    }
}

/** Fallback for missing $_SERVER superglobal. */
if (!isset($_SERVER)) {
    $_SERVER = [];
}

/** Ensure we have a variable available for the IP address of the request. */
if (!isset($_SERVER[$CIDRAM['IPAddr']])) {
    $_SERVER[$CIDRAM['IPAddr']] = '';
}

/**
 * Determine whether the front-end is being accessed, and whether the normal
 * blocking procedures should occur for this request.
 */
$CIDRAM['Protect'] = (
    $CIDRAM['Config']['general']['protect_frontend'] ||
    $CIDRAM['Config']['general']['disable_frontend'] ||
    !$CIDRAM['Direct']()
);

/** Prepare variables for block information (used if we kill the request). */
$CIDRAM['BlockInfo'] = [
    'DateTime' => $CIDRAM['TimeFormat']($CIDRAM['Now'], $CIDRAM['Config']['general']['timeFormat']),
    'ID' => $CIDRAM['GenerateID'](),
    'IPAddr' => $_SERVER[$CIDRAM['IPAddr']],
    'IPAddrResolved' => $CIDRAM['Resolve6to4']($_SERVER[$CIDRAM['IPAddr']]),
    'ScriptIdent' => $CIDRAM['ScriptIdent'],
    'favicon' => $CIDRAM['favicon'],
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
$CIDRAM['BlockInfo']['rURI'] .= (!empty($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '/';

/** Initialise page output and block event logfile fields. */
$CIDRAM['FieldTemplates'] = ['Logs' => '', 'Output' => []];

/** Maximum bandwidth for rate limiting. */
$CIDRAM['RL_MaxBandwidth'] = $CIDRAM['ReadBytes']($CIDRAM['Config']['rate_limiting']['max_bandwidth']);

/** Check whether rate limiting is active. */
$CIDRAM['RL_Active'] = (
    ($CIDRAM['Config']['rate_limiting']['max_requests'] > 0 || $CIDRAM['RL_MaxBandwidth'] > 0) &&
    ($CIDRAM['Protect'] && !$CIDRAM['Config']['general']['maintenance_mode'] && empty($CIDRAM['Whitelisted']))
);

/** The normal blocking procedures should occur. */
if ($CIDRAM['Protect'] && !$CIDRAM['Config']['general']['maintenance_mode']) {
    $CIDRAM['Stage'] = 'Tests';

    /** Run all IPv4/IPv6 tests. */
    try {
        $CIDRAM['TestResults'] = $CIDRAM['RunTests']($CIDRAM['BlockInfo']['IPAddr'], $CIDRAM['RL_Active']);
    } catch (\Exception $e) {
        $CIDRAM['Events']->fireEvent('final');
        die($e->getMessage());
    }

    /** Run all IPv4/IPv6 tests for resolved IP address if necessary. */
    if ($CIDRAM['BlockInfo']['IPAddrResolved'] && $CIDRAM['TestResults'] && empty($CIDRAM['Whitelisted'])) {
        try {
            $CIDRAM['TestResults'] = $CIDRAM['RunTests']($CIDRAM['BlockInfo']['IPAddrResolved'], $CIDRAM['RL_Active']);
        } catch (\Exception $e) {
            $CIDRAM['Events']->fireEvent('final');
            die($e->getMessage());
        }
    }

    /**
     * If all tests fail, report an invalid IP address and kill the request.
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
    elseif (($CIDRAM['BlockInfo']['IPAddr'] && isset(
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
    if (empty($CIDRAM['Whitelisted']) && $CIDRAM['Config']['signatures']['modules']) {
        $CIDRAM['Stage'] = 'Modules';
        if (!isset($CIDRAM['ModuleResCache'])) {
            $CIDRAM['ModuleResCache'] = [];
        }
        $CIDRAM['Modules'] = explode(',', $CIDRAM['Config']['signatures']['modules']);

        /**
         * Doing this with array_walk instead of foreach to ensure that modules
         * have their own scope and that superfluous data isn't preserved.
         */
        array_walk($CIDRAM['Modules'], function ($Module) use (&$CIDRAM) {
            if (!empty($CIDRAM['Whitelisted'])) {
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

        unset($CIDRAM['Modules']);
    }

    /** Execute search engine verification. */
    if (empty($CIDRAM['Whitelisted'])) {
        $CIDRAM['Stage'] = 'SearchEngineVerification';
        $CIDRAM['SearchEngineVerification']();
    }

    /** Execute social media verification. */
    if (empty($CIDRAM['Whitelisted'])) {
        $CIDRAM['Stage'] = 'SocialMediaVerification';
        $CIDRAM['SocialMediaVerification']();
    }

    /** Process auxiliary rules, if any exist. */
    if (empty($CIDRAM['Whitelisted'])) {
        $CIDRAM['Stage'] = 'Aux';
        $CIDRAM['Aux']();
    }

    /** Process all reports (if any exist, and if not whitelisted), and then destroy the reporter. */
    if (empty($CIDRAM['Whitelisted'])) {
        $CIDRAM['Stage'] = 'Reporting';
        $CIDRAM['Reporter']->process();
    }

    /** Cleanup. */
    unset($CIDRAM['Reporter']);
}

/** Process tracking information for the inbound IP. */
if (!empty($CIDRAM['TestResults']) && $CIDRAM['BlockInfo']['SignatureCount'] && $CIDRAM['Trackable']) {
    $CIDRAM['Stage'] = 'Tracking';

    /** Set tracking expiry. */
    $CIDRAM['TrackTime'] = $CIDRAM['Now'] + (
        !empty($CIDRAM['Config']['Options']['TrackTime']) ? $CIDRAM['Config']['Options']['TrackTime'] : $CIDRAM['Config']['signatures']['default_tracktime']
    );

    /** Number of infractions to append. */
    $CIDRAM['TrackCount'] = !empty($CIDRAM['Config']['Options']['TrackCount']) ? $CIDRAM['Config']['Options']['TrackCount'] : 1;
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
if ($CIDRAM['RL_Active'] && isset($CIDRAM['Factors']) && (!$CIDRAM['Config']['rate_limiting']['exceptions'] || (
    !($CIDRAM['BlockInfo']['Verified'] && $CIDRAM['in_csv']('Verified', $CIDRAM['Config']['rate_limiting']['exceptions'])) &&
    !(!empty($CIDRAM['Whitelisted']) && $CIDRAM['in_csv']('Whitelisted', $CIDRAM['Config']['rate_limiting']['exceptions']))
))) {
    $CIDRAM['Stage'] = 'RL';
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
        $CIDRAM['RL_Data'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'rl.dat');
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
            ob_end_flush();
        });
    }
}

/** This code block only executed if signatures were triggered. */
if ($CIDRAM['BlockInfo']['SignatureCount'] > 0) {
    $CIDRAM['Stage'] = 'reCAPTCHA';

    /** Define reCAPTCHA working data. */
    $CIDRAM['reCAPTCHA'] = ['Bypass' => false, 'Loggable' => false, 'Expiry' => ($CIDRAM['Config']['recaptcha']['expiry'] * 3600)];

    /** Handling reCAPTCHA here. */
    if (
        !empty($CIDRAM['Config']['recaptcha']['sitekey']) &&
        !empty($CIDRAM['Config']['recaptcha']['secret']) &&
        empty($CIDRAM['Banned']) &&
        file_exists($CIDRAM['Vault'] . 'recaptcha.php') &&
        $CIDRAM['BlockInfo']['SignatureCount'] <= $CIDRAM['Config']['recaptcha']['signature_limit'] && (
            $CIDRAM['Config']['recaptcha']['usemode'] === 1 || (
                $CIDRAM['Config']['recaptcha']['usemode'] === 2 &&
                !empty($CIDRAM['Config']['recaptcha']['enabled'])
            )
        )
    ) {

        /**
         * Check whether the salt file exists; If it doesn't, generate a new salt
         * and create the file. If it does, fetch it and extract its content for
         * the script to use.
         */
        if (!file_exists($CIDRAM['Vault'] . 'salt.dat')) {
            $CIDRAM['Salt'] = $CIDRAM['GenerateSalt']();
            $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'salt.dat', 'wb');
            fwrite($CIDRAM['Handle'], $CIDRAM['Salt']);
            fclose($CIDRAM['Handle']);
        } else {
            $CIDRAM['Salt'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'salt.dat');
        }

        /** Load the reCAPTCHA module. */
        require $CIDRAM['Vault'] . 'recaptcha.php';
    }

    if (empty($CIDRAM['Config']['template_data']['recaptcha_api_include'])) {
        $CIDRAM['Config']['template_data']['recaptcha_api_include'] = '';
    }
    if (empty($CIDRAM['Config']['template_data']['recaptcha_div_include'])) {
        $CIDRAM['Config']['template_data']['recaptcha_div_include'] = '';
    }

    /** Unset our reCAPTCHA working data cleanly. */
    unset($CIDRAM['reCAPTCHA']);
}

/** Update statistics if necessary. */
if ($CIDRAM['Config']['general']['statistics'] && $CIDRAM['BlockInfo']['SignatureCount'] > 0) {
    $CIDRAM['Stage'] = 'Statistics';
    if (!empty($CIDRAM['Banned'])) {
        if ($CIDRAM['BlockInfo']['IPAddrResolved']) {
            $CIDRAM['Statistics']['Banned-IPv4']++;
            $CIDRAM['Statistics']['Banned-IPv6']++;
        } elseif ($CIDRAM['LastTestIP'] === 4) {
            $CIDRAM['Statistics']['Banned-IPv4']++;
        } elseif ($CIDRAM['LastTestIP'] === 6) {
            $CIDRAM['Statistics']['Banned-IPv6']++;
        }
    } else {
        if ($CIDRAM['BlockInfo']['IPAddrResolved']) {
            $CIDRAM['Statistics']['Blocked-IPv4']++;
            $CIDRAM['Statistics']['Blocked-IPv6']++;
        } elseif ($CIDRAM['LastTestIP'] === 4) {
            $CIDRAM['Statistics']['Blocked-IPv4']++;
        } elseif ($CIDRAM['LastTestIP'] === 6) {
            $CIDRAM['Statistics']['Blocked-IPv6']++;
        } else {
            $CIDRAM['Statistics']['Blocked-Other']++;
        }
    }
    $CIDRAM['Statistics-Modified'] = true;
}

/** Destroy cache object and some related values. */
$CIDRAM['DestroyCacheObject']();

/** Process webhooks. */
if (!empty($CIDRAM['Config']['Webhook']['URL'])) {
    $CIDRAM['Stage'] = 'Webhooks';
    /** Fetch data for sending with the request. */
    $CIDRAM['ParsedToWebhook'] = $CIDRAM['BlockInfo'];
    /** Prepare data for sending with the request. */
    foreach ($CIDRAM['ParsedToWebhook'] as &$CIDRAM['WebhookVar']) {
        $CIDRAM['WebhookVar'] = urlencode($CIDRAM['WebhookVar']);
    }
    unset($CIDRAM['WebhookVar']);
    /** Set timeout. */
    if (empty($CIDRAM['Config']['Webhook']['Timeout'])) {
        $CIDRAM['Config']['Webhook']['Timeout'] = $CIDRAM['Timeout'];
    }
    /** Set params. */
    if (empty($CIDRAM['Config']['Webhook']['Params'])) {
        $CIDRAM['Config']['Webhook']['Params'] = [];
    } else {
        /** Parse any relevant block information to our webhook params. */
        $CIDRAM['Config']['Webhook']['Params'] = $CIDRAM['ParseVars'](
            $CIDRAM['ParsedToWebhook'],
            $CIDRAM['Config']['Webhook']['Params']
        );
        $CIDRAM['Arrayify']($CIDRAM['Config']['Webhook']['Params']);
    }
    /** Parse any relevant block information to our webhook URL. */
    $CIDRAM['Config']['Webhook']['URL'] = $CIDRAM['ParseVars'](
        $CIDRAM['ParsedToWebhook'],
        $CIDRAM['Config']['Webhook']['URL']
    );
    /** Perform request. */
    $CIDRAM['WebhookResults'] = $CIDRAM['Request'](
        $CIDRAM['Config']['Webhook']['URL'],
        $CIDRAM['Config']['Webhook']['Params'],
        $CIDRAM['Config']['Webhook']['Timeout']
    );
    /** Cleanup. */
    unset($CIDRAM['WebhookResults'], $CIDRAM['ParsedToWebhook'], $CIDRAM['Config']['Webhook']);
}

/** If any signatures were triggered, log it, generate output, then die. */
if ($CIDRAM['BlockInfo']['SignatureCount'] > 0) {
    $CIDRAM['Stage'] = 'Output';

    /** Set status for reCAPTCHA block information. */
    if (empty($CIDRAM['BlockInfo']['reCAPTCHA'])) {
        $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['L10N']->getString('recaptcha_disabled');
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

    /** Hostname omission. */
    if ($CIDRAM['Config']['legal']['omit_hostname']) {
        if (isset($CIDRAM['BlockInfo']['Hostname'])) {
            unset($CIDRAM['BlockInfo']['Hostname']);
        }
    }

    /** User agent omission. */
    if ($CIDRAM['Config']['legal']['omit_ua']) {
        if (isset($CIDRAM['BlockInfo']['UA'])) {
            unset($CIDRAM['BlockInfo']['UA']);
        }
        if (isset($CIDRAM['BlockInfo']['UALC'])) {
            unset($CIDRAM['BlockInfo']['UALC']);
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
        $CIDRAM['BlockInfo']['Hostname'] = empty($CIDRAM['Hostname']) ? '-' : $CIDRAM['Hostname'];
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
    if ($CIDRAM['Config']['recaptcha']['usemode'] || $CIDRAM['Config']['general']['empty_fields'] === 'include') {
        if (empty($CIDRAM['BlockInfo']['reCAPTCHA'])) {
            $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['L10N']->getString('recaptcha_disabled');
        }
        $CIDRAM['AddField'](
            $CIDRAM['L10N']->getString('field_reCAPTCHA_state'),
            $CIDRAM['Client-L10N']->getString('field_reCAPTCHA_state'),
            $CIDRAM['BlockInfo']['reCAPTCHA']
        );
    }

    /** Finalise fields. */
    $CIDRAM['FieldTemplates']['Output'] = implode("\n                ", $CIDRAM['FieldTemplates']['Output']);

    /** Determine which template file to use, if this hasn't already been determined. */
    if (!isset($CIDRAM['template_file'])) {
        $CIDRAM['template_file'] = (
            !$CIDRAM['Config']['template_data']['css_url']
        ) ? 'template_' . $CIDRAM['Config']['template_data']['theme'] . '.html' : 'template_custom.html';
    }

    /** Fallback for themes without default template files. */
    if (
        $CIDRAM['Config']['template_data']['theme'] !== 'default' &&
        !$CIDRAM['Config']['template_data']['css_url'] &&
        !file_exists($CIDRAM['Vault'] . $CIDRAM['template_file'])
    ) {
        $CIDRAM['template_file'] = 'template_default.html';
    }

    /** A fix for correctly displaying LTR/RTL text. */
    if ($CIDRAM['L10N']->getString('Text Direction') !== 'rtl') {
        $CIDRAM['L10N']->Data['Text Direction'] = 'ltr';
        $CIDRAM['Config']['template_data']['textBlockAlign'] = 'text-align:left;';
        $CIDRAM['Config']['template_data']['textBlockFloat'] = '';
    } else {
        $CIDRAM['Config']['template_data']['textBlockAlign'] = 'text-align:right;';
        $CIDRAM['Config']['template_data']['textBlockFloat'] = 'float:right;';
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

    /** Parsed to the template file upon generating HTML output. */
    $CIDRAM['Parsables'] = $CIDRAM['FieldTemplates'] + $CIDRAM['Config']['template_data'] + $CIDRAM['BlockInfo'] + [
        'L10N-Lang-Attache' => $CIDRAM['L10N-Lang-Attache']
    ];

    /** Pull relevant client-specified L10N data first. */
    if (!empty($CIDRAM['L10N-Lang-Attache'])) {
        foreach (['denied', 'recaptcha_message', 'recaptcha_message_invisible', 'recaptcha_submit'] as $CIDRAM['PullThis']) {
            if (isset($CIDRAM['Client-L10N']->Data[$CIDRAM['PullThis']])) {
                $CIDRAM['Parsables'][$CIDRAM['PullThis']] = $CIDRAM['Client-L10N']->Data[$CIDRAM['PullThis']];
            }
        }
        unset($CIDRAM['PullThis']);
    }

    /** Append default L10N data. */
    $CIDRAM['Parsables'] += $CIDRAM['L10N']->Data;

    if (!empty($CIDRAM['Banned']) && $CIDRAM['Config']['general']['ban_override'] !== 200 && (
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

        if ($CIDRAM['ThisStatusHTTP'] = $CIDRAM['GetStatusHTTP']($CIDRAM['Config']['general']['forbid_on_block'])) {
            $CIDRAM['errCode'] = $CIDRAM['Config']['general']['forbid_on_block'];
            header('HTTP/1.0 ' . $CIDRAM['errCode'] . ' ' . $CIDRAM['ThisStatusHTTP']);
            header('HTTP/1.1 ' . $CIDRAM['errCode'] . ' ' . $CIDRAM['ThisStatusHTTP']);
            header('Status: ' . $CIDRAM['errCode'] . ' ' . $CIDRAM['ThisStatusHTTP']);
        } else {
            $CIDRAM['errCode'] = 200;
        }

        if (!$CIDRAM['template_file'] || !file_exists($CIDRAM['Vault'] . $CIDRAM['template_file'])) {
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
            $CIDRAM['Parsables']['pp'] = empty(
                $CIDRAM['Config']['legal']['privacy_policy']
            ) ? '' : sprintf(
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

            /** Handle webfonts. */
            if (empty($CIDRAM['Config']['general']['disable_webfonts'])) {
                $CIDRAM['HTML'] = str_replace(['<!-- WebFont Begin -->', '<!-- WebFont End -->'], '', $CIDRAM['HTML']);
            } else {
                $CIDRAM['WebFontPos'] = [
                    'Begin' => strpos($CIDRAM['HTML'], '<!-- WebFont Begin -->'),
                    'End' => strpos($CIDRAM['HTML'], '<!-- WebFont End -->')
                ];
                if ($CIDRAM['WebFontPos']['Begin'] !== false && $CIDRAM['WebFontPos']['End'] !== false) {
                    $CIDRAM['HTML'] = (
                        substr($CIDRAM['HTML'], 0, $CIDRAM['WebFontPos']['Begin']) .
                        substr($CIDRAM['HTML'], $CIDRAM['WebFontPos']['End'] + 20)
                    );
                }
                unset($CIDRAM['WebFontPos']);
            }
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

    /**
     * Skip this section if the IP has been banned and logging banned IPs has
     * been disabled.
     */
    if (empty($CIDRAM['Flag Don\'t Log']) && (
        $CIDRAM['Config']['general']['log_banned_ips'] || empty($CIDRAM['Banned'])
    )) {

        /** Applies formatting for dynamic log filenames. */
        $CIDRAM['LogFileNames'] = $CIDRAM['TimeFormat']($CIDRAM['Now'], [
            'logfile' => $CIDRAM['Config']['general']['logfile'],
            'logfileApache' => $CIDRAM['Config']['general']['logfileApache'],
            'logfileSerialized' => $CIDRAM['Config']['general']['logfileSerialized']
        ]);

        /** Write to logs. */
        $CIDRAM['Events']->fireEvent('writeToLog');
    }

    /** Final event before we exit. */
    $CIDRAM['Events']->fireEvent('final');

    /** All necessary processing and logging has completed; Now we send HTML output and die. */
    die($CIDRAM['HTML']);
}

/** Final event before we exit. */
$CIDRAM['Events']->fireEvent('final');

/** Restores default error handler. */
$CIDRAM['RestoreErrorHandler']();
