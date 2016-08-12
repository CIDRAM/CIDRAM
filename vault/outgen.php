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
 * This file: Output generator (last modified: 2016.08.13).
 */

$CIDRAM['CacheModified'] = false;

/** Prepare the cache. */
if (!file_exists($CIDRAM['Vault'] . 'cache.dat')) {
    $CIDRAM['handle'] = fopen($CIDRAM['Vault'] . 'cache.dat', 'w');
    $CIDRAM['Cache'] = array('Counter' => 0);
    fwrite($CIDRAM['handle'], serialize($CIDRAM['Cache']));
    fclose($CIDRAM['handle']);
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

/** Fallback for missing $_SERVER superglobal. */
if (!isset($_SERVER)) {
    $_SERVER = array();
}

/** Ensure we have a variable available for the IP address of the request. */
if (!isset($_SERVER[$CIDRAM['Config']['general']['ipaddr']])) {
    $_SERVER[$CIDRAM['Config']['general']['ipaddr']] = '';
}

/** Prepare variables for block information (used if we kill the request). */
$CIDRAM['BlockInfo'] = array(
    'DateTime' => date('r', $CIDRAM['Now']),
    'IPAddr' => $_SERVER[$CIDRAM['Config']['general']['ipaddr']],
    'ScriptIdent' => $CIDRAM['ScriptIdent'],
    'Query' => $CIDRAM['Query'],
    'Referrer' => (empty($_SERVER['HTTP_REFERER'])) ? '' : $_SERVER['HTTP_REFERER'],
    'UA' => (empty($_SERVER['HTTP_USER_AGENT'])) ? '' : $_SERVER['HTTP_USER_AGENT'],
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

/** Run all IPv4/IPv6 tests. */
try {
    $CIDRAM['TestResults'] = $CIDRAM['RunTests']($_SERVER[$CIDRAM['Config']['general']['ipaddr']]);
} catch (\Exception $e) {
    die($e->getMessage());
}

/** If all tests fail, report an invalid IP address and kill the request. */
if (!$CIDRAM['TestResults']) {
    $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['lang']['ReasonMessage_BadIP'];
    $CIDRAM['BlockInfo']['Signatures'] = '';
    $CIDRAM['BlockInfo']['WhyReason'] = $CIDRAM['lang']['Short_BadIP'];
    $CIDRAM['BlockInfo']['SignatureCount']++;
}

/** This code block only executed if signatures were triggered. */
if ($CIDRAM['BlockInfo']['SignatureCount']) {

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

    /** Handling reCAPTCHA stuff here ({@todo work-in-progress feature}). */
    if (
        !empty($CIDRAM['Config']['recaptcha']['sitekey']) &&
        !empty($CIDRAM['Config']['recaptcha']['secret']) &&
        $CIDRAM['BlockInfo']['SignatureCount'] === 1
    ) {
        // 0 = Never use. 1 = Always use. 2 = Opt in to use.
        if (
            $CIDRAM['Config']['recaptcha']['usemode'] === 1 || (
                $CIDRAM['Config']['recaptcha']['usemode'] === 2 &&
                $CIDRAM['Config']['recaptcha']['enabled'] === true
            )
        ) {
            if (!empty($_POST['g-recaptcha-response'])) {
                $CIDRAM['reCAPTCHA'] = array(
                    'Results' => $CIDRAM['Request']('https://www.google.com/recaptcha/api/siteverify', array(
                        'secret' => $CIDRAM['Config']['recaptcha']['secret'],
                        'response' => $_POST['g-recaptcha-response'],
                        'remoteip' => $_SERVER[$CIDRAM['Config']['general']['ipaddr']]
                    ))
                );
                $CIDRAM['reCAPTCHA']['Offset'] = strpos($CIDRAM['reCAPTCHA']['Results'], '"success": ');
                if ($CIDRAM['reCAPTCHA']['Offset'] !== false) {
                    $CIDRAM['reCAPTCHA']['Success'] = (
                        substr($CIDRAM['reCAPTCHA']['Results'], $CIDRAM['reCAPTCHA']['Offset'] + 11, 4) === 'true'
                    );
                } else {
                    $CIDRAM['reCAPTCHA']['Success'] = false;
                }
                /** The next line is temporary; Will replace with real code shortly. */
                die(($CIDRAM['reCAPTCHA']['Success']) ? 'Success!' : 'Failure!');
                // Refer https://developers.google.com/recaptcha/docs/verify
            }
            $CIDRAM['Config']['template_data']['recaptcha_api_include'] =
                "\n    <script src='https://www.google.com/recaptcha/api.js'></script>";
            $CIDRAM['Config']['template_data']['recaptcha_div_include'] =
                "\n    <hr />\n    <p class=\"textStrong\">{recaptcha_message}<br /><br /></p>" .
                "\n    <form method=\"POST\" action=\"\"><div class=\"g-recaptcha\" data-sitekey=\"" .
                $CIDRAM['Config']['recaptcha']['sitekey'] . "\"></div><br /><br />" .
                "<input type=\"submit\" value=\"{recaptcha_submit}\" /></form>";
        }
    }
    if (empty($CIDRAM['Config']['template_data']['recaptcha_api_include'])) {
        $CIDRAM['Config']['template_data']['recaptcha_api_include'] = '';
    }
    if (empty($CIDRAM['Config']['template_data']['recaptcha_div_include'])) {
        $CIDRAM['Config']['template_data']['recaptcha_div_include'] = '';
    }

}

/** Update the cache. */
if ($CIDRAM['CacheModified']) {
    $CIDRAM['handle'] = fopen($CIDRAM['Vault'] . 'cache.dat', 'w');
    fwrite($CIDRAM['handle'], serialize($CIDRAM['Cache']));
    fclose($CIDRAM['handle']);
}

/** If any signatures were triggered, log it, generate output, then die. */
if ($CIDRAM['BlockInfo']['SignatureCount']) {

    /**
     * Some simple sanitisation for our block information (helps to prevent
     * some obscure types of XSS attacks).
     */
    $CIDRAM['BlockInfo'] = str_replace(
        array('<', '>', "\r", "\n", "\t"),
        array('&lt;', '&gt;', '&#13;', '&#10;', '&#9;'),
        $CIDRAM['BlockInfo']
    );

    /** Determine which template file to use. */
    $CIDRAM['template_file'] =
        (!$CIDRAM['Config']['template_data']['css_url']) ? 'template.html' : 'template_custom.html';

    /** A fix for correctly displaying LTR/RTL text. */
    if (empty($CIDRAM['lang']['textDir']) || $CIDRAM['lang']['textDir'] !== 'rtl') {
        $CIDRAM['lang']['textDir'] = 'ltr';
        $CIDRAM['Config']['template_data']['textBlockAlign'] = 'text-align:left;';
        $CIDRAM['Config']['template_data']['textBlockFloat'] = '';
    } else {
        $CIDRAM['Config']['template_data']['textBlockAlign'] = 'text-align:right;';
        $CIDRAM['Config']['template_data']['textBlockFloat'] = 'float:right;';
    }

    /** Parsed to the template file upon generating HTML output. */
    $CIDRAM['Parsables'] = $CIDRAM['Config']['template_data'] + $CIDRAM['lang'] + $CIDRAM['BlockInfo'];

    if (!$CIDRAM['Config']['general']['silent_mode']) {

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

        if (!file_exists($CIDRAM['Vault'] . $CIDRAM['template_file'])) {
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
                    "\n    <p class=\"textStrong\">" . $CIDRAM['ParseVars'](array(
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

    /** Determining date/time information for logfile names. */
    if (
        substr_count($CIDRAM['Config']['general']['logfile'], '{') ||
        substr_count($CIDRAM['Config']['general']['logfileApache'], '{') ||
        substr_count($CIDRAM['Config']['general']['logfileSerialized'], '{')
    ) {
        list(
            $CIDRAM['Config']['general']['logfile'],
            $CIDRAM['Config']['general']['logfileApache'],
            $CIDRAM['Config']['general']['logfileSerialized']
        ) = $CIDRAM['Time2Logfile']($CIDRAM['Now'], array(
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
            "es}\n{field_whyreason}{WhyReason}!\n{field_ua}{UA}\n{field_rURI}{rURI}\n\n"
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
        if (isset($CIDRAM['BlockInfo']['EmailAddr'])) {
            unset($CIDRAM['BlockInfo']['EmailAddr']);
        }
        if (isset($CIDRAM['BlockInfo']['UALC'])) {
            unset($CIDRAM['BlockInfo']['UALC']);
        }
        $CIDRAM['logfileSerialData'] = fopen($CIDRAM['Vault'] . $CIDRAM['Config']['general']['logfileSerialized'], 'a');
        fwrite($CIDRAM['logfileSerialData'], serialize($CIDRAM['BlockInfo']) . "\n");
        fclose($CIDRAM['logfileSerialData']);
        unset($CIDRAM['logfileSerialData']);
    }

    /** All necessary processing and logging has completed; Now we die. */
    die($CIDRAM['html']);

}
