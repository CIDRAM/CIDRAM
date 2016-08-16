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
 * This file: Output generator (last modified: 2016.08.16).
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

/**
 * Check whether the salt file exists; If it doesn't, generate a new salt and
 * create the file (this salt isn't essential for core functionality of the
 * script, but may be used for some optional peripheral functionality, such as
 * the reCAPTCHA feature). If it does, fetch it and extract its content for the
 * script to use.
 */
if (!file_exists($CIDRAM['Vault'] . 'salt.dat')) {
    $CIDRAM['Salt'] = $CIDRAM['GenerateSalt']();
    $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'salt.dat', 'w');
    fwrite($CIDRAM['Handle'], $CIDRAM['Salt']);
    fclose($CIDRAM['Handle']);
} else {
    $CIDRAM['Salt'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'salt.dat');
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

    /** Define reCAPTCHA working data. */
    $CIDRAM['reCAPTCHA'] = array('Bypass' => false, 'Loggable' => false, 'Expiry' => ($CIDRAM['Config']['recaptcha']['expiry'] * 3600));

    /** Handling reCAPTCHA here. */
    if (
        !empty($CIDRAM['Config']['recaptcha']['sitekey']) &&
        !empty($CIDRAM['Config']['recaptcha']['secret']) &&
        $CIDRAM['BlockInfo']['SignatureCount'] === 1
    ) {

        /** Attempt to load the hash list. */
        if (file_exists($CIDRAM['Vault'] . 'hashes.dat')) {
            $CIDRAM['reCAPTCHA']['HashList'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'hashes.dat');
            $CIDRAM['reCAPTCHA']['HashListMod'] = false;
        } else {
            $CIDRAM['reCAPTCHA']['HashList'] = "HASH LIST\n---------\n";
            $CIDRAM['reCAPTCHA']['HashListMod'] = true;
        }

        /** Cycle through the hash list and remove any expired hashes. */
        if ($CIDRAM['reCAPTCHA']['HashList']) {
            $CIDRAM['reCAPTCHA']['HashPosEnd'] = 0;
            while (true) {
                $CIDRAM['reCAPTCHA']['HashPosBegin'] = $CIDRAM['reCAPTCHA']['HashPosEnd'];
                if ($CIDRAM['reCAPTCHA']['HashPosEnd'] = strpos($CIDRAM['reCAPTCHA']['HashList'], "\n", $CIDRAM['reCAPTCHA']['HashPosBegin'] + 1)) {
                    $CIDRAM['reCAPTCHA']['HashLine'] = substr(
                        $CIDRAM['reCAPTCHA']['HashList'],
                        $CIDRAM['reCAPTCHA']['HashPosBegin'],
                        $CIDRAM['reCAPTCHA']['HashPosEnd'] - $CIDRAM['reCAPTCHA']['HashPosBegin']
                    );
                    if ($CIDRAM['reCAPTCHA']['Split'] = strpos($CIDRAM['reCAPTCHA']['HashLine'], ':')) {
                        $CIDRAM['reCAPTCHA']['HashExpiry'] = (int)substr($CIDRAM['reCAPTCHA']['HashLine'], $CIDRAM['reCAPTCHA']['Split'] + 1);
                        if ($CIDRAM['reCAPTCHA']['HashExpiry'] < $CIDRAM['Now']) {
                            $CIDRAM['reCAPTCHA']['HashList'] = str_replace($CIDRAM['reCAPTCHA']['HashLine'], '', $CIDRAM['reCAPTCHA']['HashList']);
                            $CIDRAM['reCAPTCHA']['HashPosEnd'] = 0;
                            $CIDRAM['reCAPTCHA']['HashListMod'] = true;
                        }
                    }
                } else {
                    break;
                }
            }
        }

        /**
         * Determine whether the user has already entered a CAPTCHA before and
         * populate relevant variables.
         */
        if (!empty($_COOKIE['CIDRAM']) && $CIDRAM['reCAPTCHA']['Split'] = strpos($_COOKIE['CIDRAM'], '|')) {
            $CIDRAM['reCAPTCHA']['UsrHash'] = substr($_COOKIE['CIDRAM'], 0, $CIDRAM['reCAPTCHA']['Split']);
            if (strpos($CIDRAM['reCAPTCHA']['HashList'], "\n" . $CIDRAM['reCAPTCHA']['UsrHash'] . ':') !== false) {
                $CIDRAM['reCAPTCHA']['UsrSalt'] = base64_decode(substr($_COOKIE['CIDRAM'], $CIDRAM['reCAPTCHA']['Split']));
                if ($CIDRAM['Config']['recaptcha']['lockip']) {
                    $CIDRAM['reCAPTCHA']['UsrMeld'] = $CIDRAM['Meld'](
                        $CIDRAM['Salt'],
                        $CIDRAM['reCAPTCHA']['UsrSalt'],
                        $_SERVER[$CIDRAM['Config']['general']['ipaddr']]
                    );
                } else {
                    $CIDRAM['reCAPTCHA']['UsrMeld'] = $CIDRAM['Meld'](
                        $CIDRAM['Salt'],
                        $CIDRAM['reCAPTCHA']['UsrSalt']
                    );
                }
                if (strpos($CIDRAM['reCAPTCHA']['UsrMeld'], "\x00") !== false) {
                    $CIDRAM['reCAPTCHA']['UsrMeld'] = str_replace("\x00", '', $CIDRAM['reCAPTCHA']['UsrMeld']);
                }
            } else {
                $CIDRAM['reCAPTCHA']['UsrMeld'] = $CIDRAM['reCAPTCHA']['UsrSalt'] = $CIDRAM['reCAPTCHA']['UsrHash'] = '';
            }
        } else {
            $CIDRAM['reCAPTCHA']['UsrMeld'] = $CIDRAM['reCAPTCHA']['UsrSalt'] = $CIDRAM['reCAPTCHA']['UsrHash'] = '';
        }

        /**
         * Verify whether they've passed the CAPTCHA, update cookies and/or
         * generate CAPTCHA fields.
         */
        if (
            $CIDRAM['reCAPTCHA']['UsrHash'] &&
            $CIDRAM['reCAPTCHA']['UsrMeld'] &&
            password_verify($CIDRAM['reCAPTCHA']['UsrMeld'], $CIDRAM['reCAPTCHA']['UsrHash'])
        ) {

            $CIDRAM['reCAPTCHA']['Bypass'] = true;
            $CIDRAM['BlockInfo']['SignatureCount'] = 0;

        } else {

            if (
                $CIDRAM['Config']['recaptcha']['usemode'] === 1 || (
                    $CIDRAM['Config']['recaptcha']['usemode'] === 2 &&
                    $CIDRAM['Config']['recaptcha']['enabled'] === true
                )
            ) {
                /** Set status for reCAPTCHA block information. */
                $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['lang']['recaptcha_enabled'];
                /** We've received a response. */
                if (!empty($_POST['g-recaptcha-response'])) {
                    $CIDRAM['reCAPTCHA']['Loggable'] = true;
                    /**
                     * Fetch results from the reCAPTCHA API.
                     * (Documentation: https://developers.google.com/recaptcha/docs/verify).
                     */
                    $CIDRAM['reCAPTCHA']['Results'] = $CIDRAM['Request']('https://www.google.com/recaptcha/api/siteverify', array(
                        'secret' => $CIDRAM['Config']['recaptcha']['secret'],
                        'response' => $_POST['g-recaptcha-response'],
                        'remoteip' => $_SERVER[$CIDRAM['Config']['general']['ipaddr']]
                    ));
                    $CIDRAM['reCAPTCHA']['Offset'] = strpos($CIDRAM['reCAPTCHA']['Results'], '"success": ');
                    $CIDRAM['reCAPTCHA']['Bypass'] = (
                        ($CIDRAM['reCAPTCHA']['Offset'] !== false) &&
                        (substr($CIDRAM['reCAPTCHA']['Results'], $CIDRAM['reCAPTCHA']['Offset'] + 11, 4) === 'true')
                    );
                    if ($CIDRAM['reCAPTCHA']['Bypass']) {
                        /** Generate client-side salt. */
                        $CIDRAM['reCAPTCHA']['UsrSalt'] = $CIDRAM['GenerateSalt']();
                        /** Generate authentication hash. */
                        $CIDRAM['reCAPTCHA']['Cookie'] = ($CIDRAM['Config']['recaptcha']['lockip']) ?
                            $CIDRAM['Meld'](
                                $CIDRAM['Salt'],
                                $CIDRAM['reCAPTCHA']['UsrSalt'],
                                $_SERVER[$CIDRAM['Config']['general']['ipaddr']]
                            ) : $CIDRAM['Meld'](
                                $CIDRAM['Salt'],
                                $CIDRAM['reCAPTCHA']['UsrSalt']
                            );
                        if (strpos($CIDRAM['reCAPTCHA']['Cookie'], "\x00") !== false) {
                            $CIDRAM['reCAPTCHA']['Cookie'] = str_replace("\x00", '', $CIDRAM['reCAPTCHA']['Cookie']);
                        }
                        $CIDRAM['reCAPTCHA']['UsrHash'] = password_hash($CIDRAM['reCAPTCHA']['Cookie'], PASSWORD_DEFAULT);
                        $CIDRAM['reCAPTCHA']['Cookie'] = $CIDRAM['reCAPTCHA']['UsrHash'] . '|' . base64_encode($CIDRAM['reCAPTCHA']['UsrSalt']);
                        setcookie('CIDRAM', $CIDRAM['reCAPTCHA']['Cookie'], $CIDRAM['Now'] + $CIDRAM['reCAPTCHA']['Expiry'], '/', (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '', false, true);
                        $CIDRAM['BlockInfo']['SignatureCount'] = 0;
                        $CIDRAM['reCAPTCHA']['HashList'] .= $CIDRAM['reCAPTCHA']['UsrHash'] . ':' . ($CIDRAM['Now'] + $CIDRAM['reCAPTCHA']['Expiry']) . "\n";
                        $CIDRAM['reCAPTCHA']['HashListMod'] = true;
                        /** Set status for reCAPTCHA block information. */
                        $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['lang']['recaptcha_passed'];
                    } else {
                        /** Set status for reCAPTCHA block information. */
                        $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['lang']['recaptcha_failed'];
                    }
                }
                if (!$CIDRAM['reCAPTCHA']['Bypass']) {
                    $CIDRAM['Config']['template_data']['recaptcha_api_include'] =
                        "\n    <script src='https://www.google.com/recaptcha/api.js'></script>";
                    $CIDRAM['Config']['template_data']['recaptcha_div_include'] =
                        "\n    <hr />\n    <p class=\"textStrong\">{recaptcha_message}<br />{recaptcha_cookie_warning}" .
                        "<br /><br /></p>\n    <form method=\"POST\" action=\"\"><div class=\"g-recaptcha\" data-sitekey=\"" .
                        $CIDRAM['Config']['recaptcha']['sitekey'] . "\"></div><br /><br />" .
                        "<input type=\"submit\" value=\"{recaptcha_submit}\" /></form>";
                }

            }

        }

        /** Update the hash list if any changes were made. */
        if ($CIDRAM['reCAPTCHA']['HashListMod']) {
            $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'hashes.dat', 'w');
            fwrite($CIDRAM['Handle'], $CIDRAM['reCAPTCHA']['HashList']);
            fclose($CIDRAM['Handle']);
        }

    }

    if (empty($CIDRAM['Config']['template_data']['recaptcha_api_include'])) {
        $CIDRAM['Config']['template_data']['recaptcha_api_include'] = '';
    }
    if (empty($CIDRAM['Config']['template_data']['recaptcha_div_include'])) {
        $CIDRAM['Config']['template_data']['recaptcha_div_include'] = '';
    }

    if (!$CIDRAM['reCAPTCHA']['Bypass']) {

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

    /** Writing to the reCAPTCHA logfile (if this has been enabled). */
    if ($CIDRAM['Config']['recaptcha']['logfile'] && $CIDRAM['reCAPTCHA']['Loggable']) {
        /** Determining date/time information for the logfile name. */
        if (substr_count($CIDRAM['Config']['recaptcha']['logfile'], '{')) {
            $CIDRAM['Config']['recaptcha']['logfile'] =
                $CIDRAM['Time2Logfile']($CIDRAM['Now'], $CIDRAM['Config']['recaptcha']['logfile']);
        }
        $CIDRAM['reCAPTCHA']['LogfileData'] =
            $CIDRAM['lang']['field_ipaddr'] . $_SERVER[$CIDRAM['Config']['general']['ipaddr']] . ' - ' .
            $CIDRAM['lang']['field_datetime'] . $CIDRAM['BlockInfo']['DateTime'] . ' - ' .
            $CIDRAM['lang']['field_reCAPTCHA_state'] . $CIDRAM['BlockInfo']['reCAPTCHA'] . "\n";
        $CIDRAM['reCAPTCHA']['LogfileHandle'] = fopen($CIDRAM['Vault'] . $CIDRAM['Config']['recaptcha']['logfile'], 'a');
        fwrite($CIDRAM['reCAPTCHA']['LogfileHandle'], $CIDRAM['reCAPTCHA']['LogfileData']);
        fclose($CIDRAM['reCAPTCHA']['LogfileHandle']);
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

/** If any signatures were triggered, log it, generate output, then die. */
if ($CIDRAM['BlockInfo']['SignatureCount']) {

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
