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
 * This file: Output generator (last modified: 2016.05.28).
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

/** Run the IPv4 test. */
$CIDRAM['TestIPv4'] = $CIDRAM['IPv4Test']($_SERVER[$CIDRAM['Config']['general']['ipaddr']]);

/** Run the IPv6 test. */
$CIDRAM['TestIPv6'] = $CIDRAM['IPv6Test']($_SERVER[$CIDRAM['Config']['general']['ipaddr']]);

/** If both tests fail, report an invalid IP address and kill the request. */
if (!$CIDRAM['TestIPv4'] && !$CIDRAM['TestIPv6']) {
    $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['lang']['ReasonMessage_BadIP'];
    $CIDRAM['BlockInfo']['Signatures'] = '';
    $CIDRAM['BlockInfo']['WhyReason'] = $CIDRAM['lang']['Short_BadIP'];
    $CIDRAM['BlockInfo']['SignatureCount']++;
}

/**
 * If any signatures were triggered and logging is enabled, increment the
 * counter.
 */
if (
    $CIDRAM['BlockInfo']['SignatureCount'] && (
        $CIDRAM['Config']['general']['logfile'] ||
        $CIDRAM['Config']['general']['logfileApache'] ||
        $CIDRAM['Config']['general']['logfileSerialized']
    )
) {
    $CIDRAM['Cache']['Counter']++;
    $CIDRAM['CacheModified'] = true;
}
$CIDRAM['BlockInfo']['Counter'] = $CIDRAM['Cache']['Counter'];

/** Update the cache. */
if ($CIDRAM['CacheModified']) {
    $CIDRAM['handle'] = fopen($CIDRAM['Vault'] . 'cache.dat', 'w');
    fwrite($CIDRAM['handle'], serialize($CIDRAM['Cache']));
    fclose($CIDRAM['handle']);
}

/** If any signatures were triggered, log it, generate output, then die. */
if ($CIDRAM['BlockInfo']['SignatureCount']) {

    $CIDRAM['template_file'] =
        (!$CIDRAM['Config']['template_data']['css_url']) ? 'template.html' : 'template_custom.html';

    $CIDRAM['Parsables'] = $CIDRAM['lang'] + $CIDRAM['BlockInfo'] + $CIDRAM['Config']['template_data'];

    if (!$CIDRAM['Config']['general']['silent_mode']) {

        if ($CIDRAM['Config']['general']['forbid_on_block'] == 503) {
            $CIDRAM['errCode'] = 503;
            header('HTTP/1.0 503 Service Unavailable');
            header('HTTP/1.1 503 Service Unavailable');
            header('Status: 503 Service Unavailable');
        } elseif ($CIDRAM['Config']['general']['forbid_on_block'] && $CIDRAM['Config']['general']['forbid_on_block'] != 200) {
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
                    '<strong><a href="mailto:' . $CIDRAM['Config']['general']['emailaddr'] . '?subject=CIDRAM%20Event&body=' .
                    urlencode($CIDRAM['ParseVars']($CIDRAM['Parsables'],
                        "{field_id}{Counter}\n{field_scriptversion}{ScriptIdent}\n{field_datetime" .
                        "}{DateTime}\n{field_ipaddr}{IPAddr}\n{field_query}{Query}\n{field_referr" .
                        "er}{Referrer}\n{field_sigcount}{SignatureCount}\n{field_sigref}{Signatur" .
                        "es}\n{field_whyreason}{WhyReason}!\n{field_ua}{UA}\n{field_rURI}{rURI}\n\n"
                    )) . '">' . $CIDRAM['lang']['click_here'] . '</a></strong>';
                $CIDRAM['BlockInfo']['EmailAddr'] = "\n<p><strong>" . $CIDRAM['ParseVars'](array(
                    'ClickHereLink' => $CIDRAM['BlockInfo']['EmailAddr']
                ), $CIDRAM['lang']['Support_Email']) . '</strong></p>';
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

    die($CIDRAM['html']);

}
