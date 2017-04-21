<?php
/**
 * This file is a part of the CIDRAM package, and can be downloaded for free
 * from {@link https://github.com/Maikuolan/CIDRAM/ GitHub}.
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: Output generator (last modified: 2017.04.20).
 */

$CIDRAM['CacheModified'] = false;

/** Prepare the cache. */
if (!file_exists($CIDRAM['Vault'] . 'cache.dat')) {
    $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'cache.dat', 'w');
    $CIDRAM['Cache'] = array('Counter' => 0);
    fwrite($CIDRAM['Handle'], serialize($CIDRAM['Cache']));
    fclose($CIDRAM['Handle']);
    if (!file_exists($CIDRAM['Vault'] . 'cache.dat')) {
        header('Content-Type: text/plain');
        die('[CIDRAM] ' . $CIDRAM['lang']['Error_WriteCache']);
    }
} else {
    $CIDRAM['Cache'] = unserialize($CIDRAM['ReadFile']($CIDRAM['Vault'] . 'cache.dat'));
    if (!isset($CIDRAM['Cache']['Counter'])) {
        $CIDRAM['CacheModified'] = true;
        $CIDRAM['Cache']['Counter'] = 0;
    }
}

/** Clear outdated IP tracking. */
$CIDRAM['ClearFromCache']('Tracking');

/** Clear outdated DNS forward lookups. */
$CIDRAM['ClearFromCache']('DNS-Forwards');

/** Clear outdated DNS reverse lookups. */
$CIDRAM['ClearFromCache']('DNS-Reverses');

/** Fallback for missing $_SERVER superglobal. */
if (!isset($_SERVER)) {
    $_SERVER = array();
}

/** Ensure we have a variable available for the IP address of the request. */
if (!isset($_SERVER[$CIDRAM['Config']['general']['ipaddr']])) {
    $_SERVER[$CIDRAM['Config']['general']['ipaddr']] = '';
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
$CIDRAM['BlockInfo'] = array(
    'DateTime' => $CIDRAM['TimeFormat']($CIDRAM['Now'], $CIDRAM['Config']['general']['timeFormat']),
    'IPAddr' => $_SERVER[$CIDRAM['Config']['general']['ipaddr']],
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
);
$CIDRAM['BlockInfo']['UALC'] = strtolower($CIDRAM['BlockInfo']['UA']);
$CIDRAM['BlockInfo']['rURI'] = (
    (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
    (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') ||
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
) ? 'https://' : 'http://';
$CIDRAM['BlockInfo']['rURI'] .= (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : 'Unknown.Host';
$CIDRAM['BlockInfo']['rURI'] .= (!empty($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '/';

/** The normal blocking procedures should occur. */
if ($CIDRAM['Protect']) {

    /** Run all IPv4/IPv6 tests. */
    try {
        $CIDRAM['TestResults'] = $CIDRAM['RunTests']($_SERVER[$CIDRAM['Config']['general']['ipaddr']]);
    } catch (\Exception $e) {
        die($e->getMessage());
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
    elseif (
        isset($CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]) &&
        $CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Count'] >= $CIDRAM['Config']['signatures']['infraction_limit']
    ) {
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
    $CIDRAM['BlockInfo']['SignatureCount'] === 1 && (
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

/**
 * If the request hasn't originated from a whitelisted IP/CIDR, and if any modules have been registered with the
 * configuration, load them.
 */
if ($CIDRAM['Protect'] && !empty($CIDRAM['Config']['signatures']['modules']) && empty($CIDRAM['Whitelisted'])) {
    $CIDRAM['Modules'] = explode(',', $CIDRAM['Config']['signatures']['modules']);
    array_walk($CIDRAM['Modules'], function ($Module) use (&$CIDRAM) {
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
if (
    !empty($CIDRAM['TestResults']) &&
    $CIDRAM['Config']['general']['search_engine_verification'] &&
    $CIDRAM['UA-Clean'] = strtolower(urldecode($CIDRAM['BlockInfo']['UA']))
) {
    /**
     * Verify Googlebot.
     * Reference: https://support.google.com/webmasters/answer/80553?hl=en
     */
    if (empty($CIDRAM['Flag-Bypass-Googlebot-Check']) && strpos($CIDRAM['UA-Clean'], 'googlebot') !== false) {
        $CIDRAM['DNS-Reverse-Forward'](array('.googlebot.com', '.google.com'), 'Googlebot');
    }
    /**
     * Verify Bingbot.
     * Reference: http://blogs.bing.com/webmaster/2012/08/31/how-to-verify-that-bingbot-is-bingbot
     */
    if (empty($CIDRAM['Flag-Bypass-Bingbot-Check']) && (
            strpos($CIDRAM['UA-Clean'], 'bingbot') !== false || strpos($CIDRAM['UA-Clean'], 'msnbot') !== false
    )) {
        $CIDRAM['DNS-Reverse-Forward']('.search.msn.com', 'Bingbot');
    }
    /**
     * Verify Yahoo! Slurp.
     * Reference: http://www.ysearchblog.com/2007/06/05/yahoo-search-crawler-slurp-has-a-new-address-and-signature-card/
     */
    if (empty($CIDRAM['Flag-Bypass-Y!Slurp-Check']) && strpos($CIDRAM['UA-Clean'], 'slurp') !== false) {
        $CIDRAM['DNS-Reverse-Forward'](array('.crawl.yahoo.net', '.yse.yahoo.net'), 'Y!Slurp');
    }
    /**
     * Verify Baidu Spider.
     * Reference: http://help.baidu.com/question?prod_en=master&class=Baiduspider
     */
    if (empty($CIDRAM['Flag-Bypass-Baidu-Check']) && strpos($CIDRAM['UA-Clean'], 'baidu') !== false) {
        $CIDRAM['DNS-Reverse-Forward'](array('.baidu.com', '.baidu.jp'), 'Baidu', true);
    }
    /**
     * Verify YandexBot.
     * Reference: https://yandex.com/support/webmaster/robot-workings/check-yandex-robots.xml
     */
    if (empty($CIDRAM['Flag-Bypass-Yandex-Check']) && strpos($CIDRAM['UA-Clean'], 'yandex') !== false) {
        $CIDRAM['DNS-Reverse-Forward'](array('.yandex.com', '.yandex.net', '.yandex.ru'), 'YandexBot');
    }
    /**
     * Verify DuckDuckGo Bot.
     * Reference: https://duckduckgo.com/duckduckbot
     */
    if (empty($CIDRAM['Flag-Bypass-DuckDuckGo-Check']) && strpos($CIDRAM['UA-Clean'], 'duckduckbot') !== false) {
        $CIDRAM['UA-IP-Match'](array('72.94.249.34', '72.94.249.35', '72.94.249.36', '72.94.249.37', '72.94.249.38'), 'DuckDuckGo Bot');
    }
    unset($CIDRAM['UA-Clean']);
}

/** Process tracking information for the inbound IP. */
if (!empty($CIDRAM['TestResults']) && $CIDRAM['BlockInfo']['SignatureCount'] && $CIDRAM['Trackable']) {
    if (!isset($CIDRAM['Cache']['Tracking'])) {
        $CIDRAM['Cache']['Tracking'] = array();
    }
    /** Set tracking expiry. */
    $CIDRAM['TrackTime'] = $CIDRAM['Now'] + (
        !empty($CIDRAM['Config']['Options']['TrackTime']) ? $CIDRAM['Config']['Options']['TrackTime'] : $CIDRAM['Config']['signatures']['default_tracktime']
    );
    /** Number of infractions to append. */
    $CIDRAM['TrackCount'] = !empty($CIDRAM['Config']['Options']['TrackCount']) ? $CIDRAM['Config']['Options']['TrackCount'] : 1;
    if (
        isset($CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Count']) &&
        isset($CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Time'])
    ) {
        $CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Count'] += $CIDRAM['TrackCount'];
        if ($CIDRAM['TrackTime'] > $CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Time']) {
            $CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]['Time'] = $CIDRAM['TrackTime'];
        }
    } else {
        $CIDRAM['Cache']['Tracking'][$CIDRAM['BlockInfo']['IPAddr']] = array('Count' => $CIDRAM['TrackCount'], 'Time' => $CIDRAM['TrackTime']);
    }
    /** Implement double-banning (required by some specific custom modules; not a standard feature). */
    if (isset($CIDRAM['Config']['Options']['DoubleBan'])) {
        if (
            isset($CIDRAM['Cache']['Tracking'][$CIDRAM['Config']['Options']['DoubleBan']]['Count']) &&
            isset($CIDRAM['Cache']['Tracking'][$CIDRAM['Config']['Options']['DoubleBan']]['Time'])
        ) {
            $CIDRAM['Cache']['Tracking'][$CIDRAM['Config']['Options']['DoubleBan']]['Count'] += $CIDRAM['TrackCount'];
            if ($CIDRAM['TrackTime'] > $CIDRAM['Cache']['Tracking'][$CIDRAM['Config']['Options']['DoubleBan']]['Time']) {
                $CIDRAM['Cache']['Tracking'][$CIDRAM['Config']['Options']['DoubleBan']]['Time'] = $CIDRAM['TrackTime'];
            }
        } else {
            $CIDRAM['Cache']['Tracking'][$CIDRAM['Config']['Options']['DoubleBan']] = array('Count' => $CIDRAM['TrackCount'], 'Time' => $CIDRAM['TrackTime']);
        }
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
    $CIDRAM['reCAPTCHA'] = array('Bypass' => false, 'Loggable' => false, 'Expiry' => ($CIDRAM['Config']['recaptcha']['expiry'] * 3600));

    /** Handling reCAPTCHA here. */
    if (
        !empty($CIDRAM['Config']['recaptcha']['sitekey']) &&
        !empty($CIDRAM['Config']['recaptcha']['secret']) &&
        empty($CIDRAM['Banned']) &&
        file_exists($CIDRAM['Vault'] . 'recaptcha.php') &&
        $CIDRAM['BlockInfo']['SignatureCount'] === 1 && (
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

    /**
     * Some simple sanitisation for our block information (helps to prevent
     * some obscure types of XSS attacks).
     */
    $CIDRAM['BlockInfo'] = str_replace(
        array('<', '>', "\r", "\n", "\t"),
        array('&lt;', '&gt;', '&#13;', '&#10;', '&#9;'),
        $CIDRAM['BlockInfo']
    );

    /**
     * Allows certain specific HTML tags to be included within certain specific
     * elements of our block information (requested by some users; these
     * would've previously been broken by the above anti-XSS sanitisation).
     */
    list($CIDRAM['BlockInfo']['ReasonMessage'], $CIDRAM['BlockInfo']['WhyReason']) = str_ireplace(
        array('&lt;br /&gt;', '&lt;br&gt;'),
        array('<br />', '<br />'),
        array($CIDRAM['BlockInfo']['ReasonMessage'], $CIDRAM['BlockInfo']['WhyReason'])
    );

    /** Determine which template file to use, if this hasn't already been determined. */
    if (!isset($CIDRAM['template_file'])) {
        $CIDRAM['template_file'] =
            !$CIDRAM['Config']['template_data']['css_url'] ? 'template.html' : 'template_custom.html';
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

    /** Webfonts link. */
    $CIDRAM['Config']['template_data']['WebFontsLink'] = $CIDRAM['WebFontsLink'];

    /** Parsed to the template file upon generating HTML output. */
    $CIDRAM['Parsables'] = $CIDRAM['Config']['template_data'] + $CIDRAM['lang'] + $CIDRAM['BlockInfo'];

    if (!empty($CIDRAM['Banned']) && $CIDRAM['Config']['general']['ban_override'] === 403) {

        $CIDRAM['errCode'] = 403;
        header('HTTP/1.0 403 Forbidden');
        header('HTTP/1.1 403 Forbidden');
        header('Status: 403 Forbidden');
        $CIDRAM['html'] = '';

    } elseif (!empty($CIDRAM['Banned']) && $CIDRAM['Config']['general']['ban_override'] === 503) {

        $CIDRAM['errCode'] = 503;
        header('HTTP/1.0 503 Service Unavailable');
        header('HTTP/1.1 503 Service Unavailable');
        header('Status: 503 Service Unavailable');
        $CIDRAM['html'] = '';

    } elseif (!$CIDRAM['Config']['general']['silent_mode']) {

        if ($CIDRAM['Config']['general']['forbid_on_block'] === 503) {
            $CIDRAM['errCode'] = 503;
            header('HTTP/1.0 503 Service Unavailable');
            header('HTTP/1.1 503 Service Unavailable');
            header('Status: 503 Service Unavailable');
        } elseif ($CIDRAM['Config']['general']['forbid_on_block'] && $CIDRAM['Config']['general']['forbid_on_block'] !== 200) {
            $CIDRAM['errCode'] = 403;
            header('HTTP/1.0 403 Forbidden');
            header('HTTP/1.1 403 Forbidden');
            header('Status: 403 Forbidden');
        } else {
            $CIDRAM['errCode'] = 200;
        }

        if (!$CIDRAM['template_file'] || !file_exists($CIDRAM['Vault'] . $CIDRAM['template_file'])) {
            header('Content-Type: text/plain');
            $CIDRAM['html'] = '[CIDRAM] ' . $CIDRAM['lang']['denied'];
        } else {
            if (!$CIDRAM['Config']['general']['emailaddr']) {
                $CIDRAM['BlockInfo']['EmailAddr'] = '';
            } else {
                $CIDRAM['BlockInfo']['EmailAddr'] =
                    '<a href="mailto:' . $CIDRAM['Config']['general']['emailaddr'] .
                    '?subject=CIDRAM%20Event&body=' .
                    urlencode($CIDRAM['ParseVars']($CIDRAM['Parsables'],
                        "{field_id}{Counter}\n{field_scriptversion}{ScriptIdent}\n{field_datetime" .
                        "}{DateTime}\n{field_ipaddr}{IPAddr}\n{field_query}{Query}\n{field_referr" .
                        "er}{Referrer}\n{field_sigcount}{SignatureCount}\n{field_sigref}{Signatur" .
                        "es}\n{field_whyreason}{WhyReason}!\n{field_ua}{UA}\n{field_rURI}{rURI}\n\n"
                    )) . '">' . $CIDRAM['lang']['click_here'] . '</a>';
                $CIDRAM['BlockInfo']['EmailAddr'] =
                    "\n    <p class=\"detected\">" . $CIDRAM['ParseVars'](array(
                        'ClickHereLink' => $CIDRAM['BlockInfo']['EmailAddr']
                    ), $CIDRAM['lang']['Support_Email']) . '</p>';
            }
            $CIDRAM['html'] = $CIDRAM['ParseVars'](array(
                'EmailAddr' => $CIDRAM['BlockInfo']['EmailAddr']
            ), $CIDRAM['ParseVars']($CIDRAM['Parsables'], $CIDRAM['ReadFile'](
                $CIDRAM['Vault'] . $CIDRAM['template_file']
            )));
        }

    } else {

        $CIDRAM['errCode'] = 301;
        header('HTTP/1.0 301 Moved Permanently');
        header('HTTP/1.1 301 Moved Permanently');
        header('Status: 301 Moved Permanently');
        header('Location: ' . $CIDRAM['Config']['general']['silent_mode']);
        $CIDRAM['html'] = '';

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
            list(
                $CIDRAM['Config']['general']['logfile'],
                $CIDRAM['Config']['general']['logfileApache'],
                $CIDRAM['Config']['general']['logfileSerialized']
            ) = $CIDRAM['TimeFormat']($CIDRAM['Now'], array(
                $CIDRAM['Config']['general']['logfile'],
                $CIDRAM['Config']['general']['logfileApache'],
                $CIDRAM['Config']['general']['logfileSerialized']
            ));
        }

        /** Writing to the standard logfile. */
        if ($CIDRAM['Config']['general']['logfile']) {
            $CIDRAM['logfileData'] = array('d' =>
                (!file_exists($CIDRAM['Vault'] . $CIDRAM['Config']['general']['logfile'])) ?
                "\x3c\x3fphp die; \x3f\x3e\n\n" :
                ''
            );
            $CIDRAM['logfileData']['d'] .= $CIDRAM['ParseVars']($CIDRAM['Parsables'],
                "{field_id}{Counter}\n{field_scriptversion}{ScriptIdent}\n{field_datetime" .
                "}{DateTime}\n{field_ipaddr}{IPAddr}\n{field_query}{Query}\n{field_referr" .
                "er}{Referrer}\n{field_sigcount}{SignatureCount}\n{field_sigref}{Signatur" .
                "es}\n{field_whyreason}{WhyReason}!\n{field_ua}{UA}\n{field_rURI}{rURI}\n" .
                "{field_reCAPTCHA_state}{reCAPTCHA}\n\n"
            );
            $CIDRAM['logfileData']['f'] = fopen($CIDRAM['Vault'] . $CIDRAM['Config']['general']['logfile'], 'a');
            fwrite($CIDRAM['logfileData']['f'], $CIDRAM['logfileData']['d']);
            fclose($CIDRAM['logfileData']['f']);
            unset($CIDRAM['logfileData']);
        }

        /** Writing to the Apache-style logfile. */
        if ($CIDRAM['Config']['general']['logfileApache']) {
            $CIDRAM['logfileApacheData'] = array('d' => sprintf(
                "%s - - [%s] \"%s %s %s\" %s %s \"%s\" \"%s\"\n",
                $CIDRAM['BlockInfo']['IPAddr'],
                $CIDRAM['BlockInfo']['DateTime'],
                (empty($_SERVER['REQUEST_METHOD'])) ? 'UNKNOWN' : $_SERVER['REQUEST_METHOD'],
                (empty($_SERVER['REQUEST_URI'])) ? '/' : $_SERVER['REQUEST_URI'],
                (empty($_SERVER['SERVER_PROTOCOL'])) ? 'UNKNOWN/x.x' : $_SERVER['SERVER_PROTOCOL'],
                $CIDRAM['errCode'],
                strlen($CIDRAM['html']),
                (empty($CIDRAM['BlockInfo']['Referrer'])) ? '-' : $CIDRAM['BlockInfo']['Referrer'],
                (empty($CIDRAM['BlockInfo']['UA'])) ? '-' : $CIDRAM['BlockInfo']['UA']
            ));
            $CIDRAM['logfileApacheData']['f'] = fopen($CIDRAM['Vault'] . $CIDRAM['Config']['general']['logfileApache'], 'a');
            fwrite($CIDRAM['logfileApacheData']['f'], $CIDRAM['logfileApacheData']['d']);
            fclose($CIDRAM['logfileApacheData']['f']);
            unset($CIDRAM['logfileApacheData']);
        }

        /** Writing to the serialised logfile. */
        if ($CIDRAM['Config']['general']['logfileSerialized']) {
            unset($CIDRAM['BlockInfo']['EmailAddr'], $CIDRAM['BlockInfo']['UALC'], $CIDRAM['BlockInfo']['favicon']);
            $CIDRAM['logfileSerialData'] = fopen($CIDRAM['Vault'] . $CIDRAM['Config']['general']['logfileSerialized'], 'a');
            fwrite($CIDRAM['logfileSerialData'], serialize($CIDRAM['BlockInfo']) . "\n");
            fclose($CIDRAM['logfileSerialData']);
            unset($CIDRAM['logfileSerialData']);
        }

    }

    /** All necessary processing and logging has completed; Now we die. */
    die($CIDRAM['html']);

}
