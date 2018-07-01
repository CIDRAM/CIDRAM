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
 * This file: Output generator (last modified: 2018.07.01).
 */

/** Initialise cache. */
$CIDRAM['InitialiseCache']();

/** Reset bypass flags. */
$CIDRAM['ResetBypassFlags']();

/** Initialise statistics if necessary. */
if ($CIDRAM['Config']['general']['statistics'] && empty($CIDRAM['Cache']['Statistics'])) {
    $CIDRAM['Cache']['Statistics'] = [
        'Other-Since' => $CIDRAM['Now'],
        'Blocked-IPv4' => 0,
        'Blocked-IPv6' => 0,
        'Blocked-Other' => 0,
        'Banned-IPv4' => 0,
        'Banned-IPv6' => 0,
        'reCAPTCHA-Failed' => 0,
        'reCAPTCHA-Passed' => 0
    ];
    $CIDRAM['CacheModified'] = true;
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
    'Counter' => 0,
    'IPAddr' => $_SERVER[$CIDRAM['IPAddr']],
    'ScriptIdent' => $CIDRAM['ScriptIdent'],
    'favicon' => $CIDRAM['favicon'],
    'Query' => $CIDRAM['Query'],
    'Referrer' => empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'],
    'UA' => empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'],
    'ReasonMessage' => '',
    'SignatureCount' => 0,
    'Signatures' => '',
    'WhyReason' => '',
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

/** Resolve 6to4 IPv6 address to its IPv4 address counterpart. */
if (substr($CIDRAM['BlockInfo']['IPAddr'], 0, 5) === '2002:') {
    $CIDRAM['BlockInfo']['IPAddrResolved'] = $CIDRAM['Resolve6to4']($CIDRAM['BlockInfo']['IPAddr']);
}

/** Initialise page output and block event logfile fields. */
$CIDRAM['FieldTemplates'] = ['Logs' => '', 'Output' => []];

/** The normal blocking procedures should occur. */
if ($CIDRAM['Protect'] && !$CIDRAM['Config']['general']['maintenance_mode']) {

    /** Run all IPv4/IPv6 tests. */
    try {
        $CIDRAM['TestResults'] = $CIDRAM['RunTests']($CIDRAM['BlockInfo']['IPAddr']);
    } catch (\Exception $e) {
        die($e->getMessage());
    }

    /** Run all IPv4/IPv6 tests for resolved IP address if necessary. */
    if (!empty($CIDRAM['BlockInfo']['IPAddrResolved']) && $CIDRAM['TestResults'] && empty($CIDRAM['Whitelisted'])) {
        try {
            $CIDRAM['TestResults'] = $CIDRAM['RunTests']($CIDRAM['BlockInfo']['IPAddrResolved']);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * If all tests fail, report an invalid IP address and kill the request.
     */
    if (!$CIDRAM['TestResults']) {
        $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['lang']['ReasonMessage_BadIP'];
        $CIDRAM['BlockInfo']['WhyReason'] = $CIDRAM['lang']['Short_BadIP'];
        $CIDRAM['BlockInfo']['SignatureCount']++;
    }

    /**
     * Check whether we're tracking the IP being checked due to previous
     * instances of bad behaviour.
     */
    elseif ((
        isset($CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Count']) &&
        $CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Count'] >= $CIDRAM['Config']['signatures']['infraction_limit']
    ) || (
        isset($CIDRAM['BlockInfo']['IPAddrResolved'], $CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddrResolved']]['Count']) &&
        $CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddrResolved']]['Count'] >= $CIDRAM['Config']['signatures']['infraction_limit']
    )) {
        $CIDRAM['Banned'] = true;
        $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['lang']['ReasonMessage_Banned'];
        $CIDRAM['BlockInfo']['WhyReason'] = $CIDRAM['lang']['Short_Banned'];
        $CIDRAM['BlockInfo']['SignatureCount']++;
    }

}

/**
 * Check whether we need the salt file (the salt file isn't necessary for core
 * functionality of the script, but may be used for some optional peripheral
 * functionality, such as the reCAPTCHA feature).
 */
if (
    !empty($CIDRAM['Config']['recaptcha']['sitekey']) &&
    !empty($CIDRAM['Config']['recaptcha']['secret']) &&
    file_exists($CIDRAM['Vault'] . 'recaptcha.php') &&
    $CIDRAM['BlockInfo']['SignatureCount'] > 0 &&
    $CIDRAM['BlockInfo']['SignatureCount'] <= $CIDRAM['Config']['recaptcha']['signature_limit'] && (
        $CIDRAM['Config']['recaptcha']['usemode'] === 1 || (
            $CIDRAM['Config']['recaptcha']['usemode'] === 2 &&
            !empty($CIDRAM['Config']['recaptcha']['enabled'])
        )
    ) &&
    $CIDRAM['Config']['recaptcha']['lockuser'] === true
) {
    /**
     * Check whether the salt file exists; If it doesn't, generate a new salt
     * and create the file. If it does, fetch it and extract its content for
     * the script to use.
     */
    if (!file_exists($CIDRAM['Vault'] . 'salt.dat')) {
        $CIDRAM['Salt'] = $CIDRAM['GenerateSalt']();
        $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'salt.dat', 'w');
        fwrite($CIDRAM['Handle'], $CIDRAM['Salt']);
        fclose($CIDRAM['Handle']);
    } else {
        $CIDRAM['Salt'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'salt.dat');
    }
}

/** Define whether to track the IP of the current request. */
$CIDRAM['Trackable'] = $CIDRAM['Config']['signatures']['track_mode'];

/** Perform forced hostname lookup if this has been enabled. */
if ($CIDRAM['Config']['general']['force_hostname_lookup']) {
    if (!empty($CIDRAM['BlockInfo']['IPAddrResolved'])) {
        $CIDRAM['Hostname'] = $CIDRAM['DNS-Reverse']($CIDRAM['BlockInfo']['IPAddrResolved']);
    } else {
        $CIDRAM['Hostname'] = $CIDRAM['DNS-Reverse']($CIDRAM['BlockInfo']['IPAddr']);
    }
}

/**
 * If the request hasn't originated from a whitelisted IP/CIDR, and if any modules have been registered with the
 * configuration, load them.
 */
if (
    $CIDRAM['Protect'] &&
    !$CIDRAM['Config']['general']['maintenance_mode'] &&
    !empty($CIDRAM['Config']['signatures']['modules']) &&
    empty($CIDRAM['Whitelisted'])
) {
    $CIDRAM['Modules'] = explode(',', $CIDRAM['Config']['signatures']['modules']);
    array_walk($CIDRAM['Modules'], function ($Module) use (&$CIDRAM) {
        $Module = (strpos($Module, ':') === false) ? $Module : substr($Module, strpos($Module, ':') + 1);
        if (file_exists($CIDRAM['Vault'] . $Module) && is_readable($CIDRAM['Vault'] . $Module)) {
            $Infractions = $CIDRAM['BlockInfo']['SignatureCount'];
            require $CIDRAM['Vault'] . $Module;
            $CIDRAM['Trackable'] = $CIDRAM['Trackable'] ?: ($CIDRAM['BlockInfo']['SignatureCount'] - $Infractions) > 0;
        }
    });
    unset($CIDRAM['Modules']);
}

/**
 * Block bots masquerading as popular search engines and disable tracking for
 * real, legitimate search engines.
 */
$CIDRAM['SearchEngineVerification']();

/** Process tracking information for the inbound IP. */
if (!empty($CIDRAM['TestResults']) && $CIDRAM['BlockInfo']['SignatureCount'] && $CIDRAM['Trackable']) {
    if (!isset($CIDRAM['Cache']['Tracking'])) {
        $CIDRAM['Cache']['Tracking'] = [];
    }

    /** Set tracking expiry. */
    $CIDRAM['TrackTime'] = $CIDRAM['Now'] + (
        !empty($CIDRAM['Config']['Options']['TrackTime']) ? $CIDRAM['Config']['Options']['TrackTime'] : $CIDRAM['Config']['signatures']['default_tracktime']
    );

    /** Number of infractions to append. */
    $CIDRAM['TrackCount'] = !empty($CIDRAM['Config']['Options']['TrackCount']) ? $CIDRAM['Config']['Options']['TrackCount'] : 1;
    if (isset(
        $CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Count'],
        $CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Time']
    )) {
        $CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Count'] += $CIDRAM['TrackCount'];
        if ($CIDRAM['TrackTime'] > $CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Time']) {
            $CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Time'] = $CIDRAM['TrackTime'];
        }
    } else {
        $CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddr']] = [
            'Count' => $CIDRAM['TrackCount'],
            'Time' => $CIDRAM['TrackTime']
        ];
    }

    $CIDRAM['CacheModified'] = true;
    if ($CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Count'] >= $CIDRAM['Config']['signatures']['infraction_limit']) {
        $CIDRAM['Banned'] = true;
    }

    /** Cleanup. */
    unset($CIDRAM['TrackCount'], $CIDRAM['TrackTime']);
}

/** This code block only executed if signatures were triggered. */
if ($CIDRAM['BlockInfo']['SignatureCount'] > 0) {

    /** Define reCAPTCHA working data. */
    $CIDRAM['reCAPTCHA'] = ['Bypass' => false, 'Loggable' => false, 'Expiry' => ($CIDRAM['Config']['recaptcha']['expiry'] * 3600)];

    /** Handling reCAPTCHA here. */
    if (
        !empty($CIDRAM['Config']['recaptcha']['sitekey']) &&
        !empty($CIDRAM['Config']['recaptcha']['secret']) &&
        empty($CIDRAM['Banned']) &&
        file_exists($CIDRAM['Vault'] . 'recaptcha.php') &&
        $CIDRAM['BlockInfo']['SignatureCount'] > 0 &&
        $CIDRAM['BlockInfo']['SignatureCount'] <= $CIDRAM['Config']['recaptcha']['signature_limit'] && (
            $CIDRAM['Config']['recaptcha']['usemode'] === 1 || (
                $CIDRAM['Config']['recaptcha']['usemode'] === 2 &&
                !empty($CIDRAM['Config']['recaptcha']['enabled'])
            )
        )
    ) {
        /** Load the reCAPTCHA module. */
        require $CIDRAM['Vault'] . 'recaptcha.php';
    }

    if (empty($CIDRAM['Config']['template_data']['recaptcha_api_include'])) {
        $CIDRAM['Config']['template_data']['recaptcha_api_include'] = '';
    }
    if (empty($CIDRAM['Config']['template_data']['recaptcha_div_include'])) {
        $CIDRAM['Config']['template_data']['recaptcha_div_include'] = '';
    }

    if (empty($CIDRAM['reCAPTCHA']['Bypass']) && (
        $CIDRAM['Config']['general']['log_banned_ips'] || empty($CIDRAM['Banned'])
    )) {

        /** If logging is enabled, increment the counter. */
        if (
            $CIDRAM['Config']['general']['logfile'] ||
            $CIDRAM['Config']['general']['logfileApache'] ||
            $CIDRAM['Config']['general']['logfileSerialized']
        ) {
            $CIDRAM['Cache']['Counter']++;
            $CIDRAM['CacheModified'] = true;
        }
        /** Set block information counter to match the updated cached counter. */
        $CIDRAM['BlockInfo']['Counter'] = $CIDRAM['Cache']['Counter'];

    }

    /** Unset our reCAPTCHA working data cleanly. */
    unset($CIDRAM['reCAPTCHA']);

}

/** Update statistics if necessary. */
if ($CIDRAM['Config']['general']['statistics'] && $CIDRAM['BlockInfo']['SignatureCount'] > 0) {
    if (!empty($CIDRAM['Banned'])) {
        if (!empty($CIDRAM['BlockInfo']['IPAddrResolved'])) {
            $CIDRAM['Cache']['Statistics']['Banned-IPv4']++;
            $CIDRAM['Cache']['Statistics']['Banned-IPv6']++;
        } elseif ($CIDRAM['LastTestIP'] === 4) {
            $CIDRAM['Cache']['Statistics']['Banned-IPv4']++;
        } elseif ($CIDRAM['LastTestIP'] === 6) {
            $CIDRAM['Cache']['Statistics']['Banned-IPv6']++;
        }
    } else {
        if (!empty($CIDRAM['BlockInfo']['IPAddrResolved'])) {
            $CIDRAM['Cache']['Statistics']['Blocked-IPv4']++;
            $CIDRAM['Cache']['Statistics']['Blocked-IPv6']++;
        } elseif ($CIDRAM['LastTestIP'] === 4) {
            $CIDRAM['Cache']['Statistics']['Blocked-IPv4']++;
        } elseif ($CIDRAM['LastTestIP'] === 6) {
            $CIDRAM['Cache']['Statistics']['Blocked-IPv6']++;
        } else {
            $CIDRAM['Cache']['Statistics']['Blocked-Other']++;
        }
    }
    $CIDRAM['CacheModified'] = true;
}

/** Update the cache. */
if ($CIDRAM['CacheModified']) {
    $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'cache.dat', 'w');
    fwrite($CIDRAM['Handle'], serialize($CIDRAM['Cache']));
    fclose($CIDRAM['Handle']);
}

/** Process webhooks. */
if (!empty($CIDRAM['Config']['Webhook']['URL'])) {
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
        $CIDRAM['Config']['Webhook']['Params'] = '';
    } else {
        /** Parse any relevant block information to our webhook params. */
        $CIDRAM['Config']['Webhook']['Params'] = $CIDRAM['ParseVars'](
            $CIDRAM['ParsedToWebhook'],
            $CIDRAM['Config']['Webhook']['Params']
        );
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

    /** Set status for reCAPTCHA block information. */
    if (empty($CIDRAM['BlockInfo']['reCAPTCHA'])) {
        $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['lang']['recaptcha_disabled'];
    }

    /** IP address pseudonymisation. */
    if ($CIDRAM['Config']['legal']['pseudonymise_ip_addresses'] && $CIDRAM['TestResults']) {
        $CIDRAM['BlockInfo']['IPAddr'] = $CIDRAM['Pseudonymise-IP']($CIDRAM['BlockInfo']['IPAddr']);
        if (!empty($CIDRAM['BlockInfo']['IPAddrResolved'])) {
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
    if (!empty($CIDRAM['BlockInfo']['Counter'])) {
        $CIDRAM['AddField']($CIDRAM['lang']['field_id'], $CIDRAM['BlockInfo']['Counter']);
    }
    if (!$CIDRAM['Config']['general']['hide_version']) {
        $CIDRAM['AddField']($CIDRAM['lang']['field_scriptversion'], $CIDRAM['BlockInfo']['ScriptIdent']);
    }
    $CIDRAM['AddField']($CIDRAM['lang']['field_datetime'], $CIDRAM['BlockInfo']['DateTime']);
    $CIDRAM['AddField']($CIDRAM['lang']['field_ipaddr'], $CIDRAM['BlockInfo']['IPAddr']);
    if (!empty($CIDRAM['BlockInfo']['IPAddrResolved'])) {
        $CIDRAM['AddField']($CIDRAM['lang']['field_ipaddr_resolved'], $CIDRAM['BlockInfo']['IPAddrResolved']);
    }
    if (!empty($CIDRAM['Hostname']) && $CIDRAM['Hostname'] !== $CIDRAM['BlockInfo']['IPAddr']) {
        $CIDRAM['BlockInfo']['Hostname'] = $CIDRAM['Hostname'];
        $CIDRAM['AddField']($CIDRAM['lang']['field_hostname'], $CIDRAM['BlockInfo']['Hostname']);
    }
    if ($CIDRAM['BlockInfo']['Query']) {
        $CIDRAM['AddField']($CIDRAM['lang']['field_query'], $CIDRAM['BlockInfo']['Query']);
    }
    if ($CIDRAM['BlockInfo']['Referrer']) {
        $CIDRAM['AddField']($CIDRAM['lang']['field_referrer'], $CIDRAM['BlockInfo']['Referrer']);
    }
    $CIDRAM['AddField']($CIDRAM['lang']['field_sigcount'], $CIDRAM['BlockInfo']['SignatureCount']);
    $CIDRAM['AddField']($CIDRAM['lang']['field_sigref'], $CIDRAM['BlockInfo']['Signatures']);
    $CIDRAM['AddField']($CIDRAM['lang']['field_whyreason'], $CIDRAM['BlockInfo']['WhyReason'] . '!');
    if ($CIDRAM['BlockInfo']['UA']) {
        $CIDRAM['AddField']($CIDRAM['lang']['field_ua'], $CIDRAM['BlockInfo']['UA']);
    }
    $CIDRAM['AddField']($CIDRAM['lang']['field_rURI'], $CIDRAM['BlockInfo']['rURI']);
    if ($CIDRAM['Config']['recaptcha']['usemode']) {
        $CIDRAM['AddField']($CIDRAM['lang']['field_reCAPTCHA_state'], $CIDRAM['BlockInfo']['reCAPTCHA']);
    }

    /** Finalise fields. */
    $CIDRAM['FieldTemplates']['Output'] = implode("\n                ", $CIDRAM['FieldTemplates']['Output']);

    /**
     * Some simple sanitisation for our block information (helps to prevent
     * some obscure types of XSS attacks).
     */
    $CIDRAM['BlockInfo'] = str_replace(
        ['<', '>', "\r", "\n", "\t"],
        ['&lt;', '&gt;', '&#13;', '&#10;', '&#9;'],
        $CIDRAM['BlockInfo']
    );

    /**
     * Allows certain specific HTML tags to be included within certain specific
     * elements of our block information (requested by some users; these
     * would've previously been broken by the above anti-XSS sanitisation).
     */
    list($CIDRAM['BlockInfo']['ReasonMessage'], $CIDRAM['BlockInfo']['WhyReason']) = str_ireplace(
        ['&lt;br /&gt;', '&lt;br&gt;'],
        ['<br />', '<br />'],
        [$CIDRAM['BlockInfo']['ReasonMessage'], $CIDRAM['BlockInfo']['WhyReason']]
    );

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
    if (empty($CIDRAM['lang']['textDir']) || $CIDRAM['lang']['textDir'] !== 'rtl') {
        $CIDRAM['lang']['textDir'] = 'ltr';
        $CIDRAM['Config']['template_data']['textBlockAlign'] = 'text-align:left;';
        $CIDRAM['Config']['template_data']['textBlockFloat'] = '';
    } else {
        $CIDRAM['Config']['template_data']['textBlockAlign'] = 'text-align:right;';
        $CIDRAM['Config']['template_data']['textBlockFloat'] = 'float:right;';
    }

    /** Prepare to process "more info" entries, if any exist. */
    if (!empty($CIDRAM['Config']['More Info']) && !empty($CIDRAM['BlockInfo']['ReasonMessage'])) {
        $CIDRAM['BlockInfo']['ReasonMessage'] .= '<br /><br />' . $CIDRAM['lang']['MoreInfo'];
        $CIDRAM['Arrayify']($CIDRAM['Config']['More Info']);
        /** Process entries. */
        foreach ($CIDRAM['Config']['More Info'] as $CIDRAM['Info Name'] => $CIDRAM['Info Link']) {
            $CIDRAM['BlockInfo']['ReasonMessage'] .= (!empty($CIDRAM['Info Name']) && is_string($CIDRAM['Info Name'])) ? (
                sprintf('<br /><a href="%1$s">%2$s</a>', $CIDRAM['Info Link'], $CIDRAM['Info Name']
            )) : sprintf('<br /><a href="%1$s">%1$s</a>', $CIDRAM['Info Link']);
        }
        /** Cleanup. */
        unset($CIDRAM['Info Link'], $CIDRAM['Info Name'], $CIDRAM['Config']['More Info']);
    }

    /** Parsed to the template file upon generating HTML output. */
    $CIDRAM['Parsables'] = $CIDRAM['FieldTemplates'] + $CIDRAM['Config']['template_data'] + $CIDRAM['lang'] + $CIDRAM['BlockInfo'];

    if (!empty($CIDRAM['Banned']) && (
        $CIDRAM['ThisStatusHTTP'] = $CIDRAM['GetStatusHTTP']($CIDRAM['Config']['general']['ban_override'])
    )) {

        $CIDRAM['errCode'] = $CIDRAM['Config']['general']['ban_override'];
        header('HTTP/1.0 ' . $CIDRAM['errCode'] . ' ' . $CIDRAM['ThisStatusHTTP']);
        header('HTTP/1.1 ' . $CIDRAM['errCode'] . ' ' . $CIDRAM['ThisStatusHTTP']);
        header('Status: ' . $CIDRAM['errCode'] . ' ' . $CIDRAM['ThisStatusHTTP']);
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
            $CIDRAM['HTML'] = '[CIDRAM] ' . $CIDRAM['lang']['denied'];
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
                        )) . '"><strong>' . $CIDRAM['lang']['click_here'] . '</strong></a>';
                    $CIDRAM['BlockInfo']['EmailAddr'] = "\n    <p class=\"detected\">" . $CIDRAM['ParseVars']([
                        'ClickHereLink' => $CIDRAM['BlockInfo']['EmailAddr']
                    ], $CIDRAM['lang']['Support_Email']) . '</p>';
                } elseif ($CIDRAM['Config']['general']['emailaddr_display_style'] === 'noclick') {
                    $CIDRAM['BlockInfo']['EmailAddr'] = "\n    <p class=\"detected\">" . $CIDRAM['ParseVars']([
                        'EmailAddr' => str_replace(
                            '@',
                            '<img src="data:image/gif;base64,R0lGODdhCQAKAIABAAAAAP///ywAAAAACQAKAAACE4yPAcsG+ZR7kcp6pWY4Hb54SAEAOw==" alt="@" />',
                            '<strong>' . $CIDRAM['Config']['general']['emailaddr'] . '</strong>'
                        )
                    ], $CIDRAM['lang']['Support_Email_2']) . '</p>';
                }
            }

            /** Include privacy policy. */
            $CIDRAM['Parsables']['pp'] = empty(
                $CIDRAM['Config']['legal']['privacy_policy']
            ) ? '' : '<br /><a href="' . $CIDRAM['Config']['legal']['privacy_policy'] . '">' . $CIDRAM['lang']['PrivacyPolicy'] . '</a>';

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
                $CIDRAM['HTML'] = substr(
                    $CIDRAM['HTML'], 0, $CIDRAM['WebFontPos']['Begin']
                ) . substr(
                    $CIDRAM['HTML'], $CIDRAM['WebFontPos']['End'] + 20
                );
                unset($CIDRAM['WebFontPos']);
            }

        }

    } else {

        $CIDRAM['errCode'] = 301;
        header('HTTP/1.0 301 Moved Permanently');
        header('HTTP/1.1 301 Moved Permanently');
        header('Status: 301 Moved Permanently');
        header('Location: ' . $CIDRAM['Config']['general']['silent_mode']);
        $CIDRAM['HTML'] = '';

    }

    /**
     * Skip this section if the IP has been banned and logging banned IPs has
     * been disabled.
     */
    if ($CIDRAM['Config']['general']['log_banned_ips'] || empty($CIDRAM['Banned'])) {

        /** Determining date/time information for logfile names. */
        if (
            strpos($CIDRAM['Config']['general']['logfile'], '{') !== false ||
            strpos($CIDRAM['Config']['general']['logfileApache'], '{') !== false ||
            strpos($CIDRAM['Config']['general']['logfileSerialized'], '{') !== false
        ) {
            $CIDRAM['LogFileNames'] = [];
            list(
                $CIDRAM['LogFileNames']['logfile'],
                $CIDRAM['LogFileNames']['logfileApache'],
                $CIDRAM['LogFileNames']['logfileSerialized']
            ) = $CIDRAM['TimeFormat']($CIDRAM['Now'], [
                $CIDRAM['Config']['general']['logfile'],
                $CIDRAM['Config']['general']['logfileApache'],
                $CIDRAM['Config']['general']['logfileSerialized']
            ]);
        } else {
            $CIDRAM['LogFileNames'] = [
                'logfile' => $CIDRAM['Config']['general']['logfile'],
                'logfileApache' => $CIDRAM['Config']['general']['logfileApache'],
                'logfileSerialized' => $CIDRAM['Config']['general']['logfileSerialized']
            ];
        }

        /** Writing to the standard logfile. */
        if ($CIDRAM['Config']['general']['logfile']) {
            $CIDRAM['logfileData'] = ['d' => ((
                !file_exists($CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfile']) || (
                    $CIDRAM['Config']['general']['truncate'] > 0 &&
                    filesize($CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfile']) >= $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate'])
                )
            ) ? "\x3c\x3fphp die; \x3f\x3e\n\n" : '')];
            $CIDRAM['logfileData']['Mode'] = !empty($CIDRAM['logfileData']['d']) ? 'w' : 'a';
            $CIDRAM['logfileData']['d'] .= $CIDRAM['ParseVars'](
                $CIDRAM['Parsables'],
                $CIDRAM['FieldTemplates']['Logs'] . "\n"
            );
            if ($CIDRAM['BuildLogPath']($CIDRAM['LogFileNames']['logfile'])) {
                $CIDRAM['logfileData']['f'] = fopen(
                    $CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfile'],
                    $CIDRAM['logfileData']['Mode']
                );
                fwrite($CIDRAM['logfileData']['f'], $CIDRAM['logfileData']['d']);
                fclose($CIDRAM['logfileData']['f']);
                if ($CIDRAM['logfileData']['Mode'] === 'w') {
                    $CIDRAM['LogRotation']($CIDRAM['Config']['general']['logfile']);
                }
            }
            unset($CIDRAM['logfileData']);
        }

        /** Writing to the Apache-style logfile. */
        if ($CIDRAM['Config']['general']['logfileApache']) {
            $CIDRAM['logfileApacheData'] = ['d' => sprintf(
                "%s - - [%s] \"%s %s %s\" %s %s \"%s\" \"%s\"\n",
                $CIDRAM['BlockInfo']['IPAddr'],
                $CIDRAM['BlockInfo']['DateTime'],
                (empty($_SERVER['REQUEST_METHOD']) ? 'UNKNOWN' : $_SERVER['REQUEST_METHOD']),
                (empty($_SERVER['REQUEST_URI']) ? '/' : $_SERVER['REQUEST_URI']),
                (empty($_SERVER['SERVER_PROTOCOL']) ? 'UNKNOWN/x.x' : $_SERVER['SERVER_PROTOCOL']),
                $CIDRAM['errCode'],
                strlen($CIDRAM['HTML']),
                (empty($CIDRAM['BlockInfo']['Referrer']) ? '-' : $CIDRAM['BlockInfo']['Referrer']),
                (empty($CIDRAM['BlockInfo']['UA']) ? '-' : $CIDRAM['BlockInfo']['UA'])
            ), 'Mode' => (
                !file_exists($CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfileApache']) || (
                    $CIDRAM['Config']['general']['truncate'] > 0 &&
                    filesize($CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfileApache']) >= $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate'])
                )
            ) ? 'w' : 'a'];
            if ($CIDRAM['BuildLogPath']($CIDRAM['LogFileNames']['logfileApache'])) {
                $CIDRAM['logfileApacheData']['f'] = fopen(
                    $CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfileApache'],
                    $CIDRAM['logfileApacheData']['Mode']
                );
                fwrite($CIDRAM['logfileApacheData']['f'], $CIDRAM['logfileApacheData']['d']);
                fclose($CIDRAM['logfileApacheData']['f']);
                if ($CIDRAM['logfileApacheData']['Mode'] === 'w') {
                    $CIDRAM['LogRotation']($CIDRAM['Config']['general']['logfileApache']);
                }
            }
            unset($CIDRAM['logfileApacheData']);
        }

        /** Writing to the serialised logfile. */
        if ($CIDRAM['Config']['general']['logfileSerialized']) {
            unset($CIDRAM['BlockInfo']['EmailAddr'], $CIDRAM['BlockInfo']['UALC'], $CIDRAM['BlockInfo']['favicon']);
            /** Remove empty entries prior to serialising. */
            $CIDRAM['BlockInfo'] = array_filter($CIDRAM['BlockInfo'], function ($Value) {
                return !(is_string($Value) && empty($Value));
            });
            $CIDRAM['WriteMode'] = (
                !file_exists($CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfileSerialized']) || (
                    $CIDRAM['Config']['general']['truncate'] > 0 &&
                    filesize($CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfileSerialized']) >= $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate'])
                )
            ) ? 'w' : 'a';
            if ($CIDRAM['BuildLogPath']($CIDRAM['LogFileNames']['logfileSerialized'])) {
                $CIDRAM['logfileSerialData'] = fopen(
                    $CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfileSerialized'],
                    $CIDRAM['WriteMode']
                );
                fwrite($CIDRAM['logfileSerialData'], serialize($CIDRAM['BlockInfo']) . "\n");
                fclose($CIDRAM['logfileSerialData']);
                if ($CIDRAM['WriteMode'] === 'w') {
                    $CIDRAM['LogRotation']($CIDRAM['Config']['general']['logfileSerialized']);
                }
            }
            unset($CIDRAM['logfileSerialData'], $CIDRAM['WriteMode']);
        }

    }

    /** All necessary processing and logging has completed; Now we send html output die. */
    die($CIDRAM['HTML']);

}
