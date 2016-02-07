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
 * This file: CIDRAM output generator (last modified: 2016.02.08).
 *
 * @package Maikuolan/CIDRAM
 */

$CIDRAM['CacheModified'] = false;
/** Prepare the cache. */
if (!file_exists($CIDRAM['Vault'] . 'cache.dat')) {
    $CIDRAM['handle'] = fopen($CIDRAM['Vault'] . 'cache.dat', 'w');
    $CIDRAM['Cache'] = array();
    $CIDRAM['Cache']['Counter'] = 0;
    fwrite($CIDRAM['handle'], serialize($CIDRAM['Cache']));
    fclose($CIDRAM['handle']);
    if (!file_exists($CIDRAM['Vault'] . 'cache.dat')) {
        header('Content-Type: text/plain');
        die('[CIDRAM] ' . $CIDRAM['lang']['Error_WriteCache']);
    }
} else {
    $CIDRAM['Cache'] =
        unserialize(CIDRAM_ReadFile($CIDRAM['Vault'] . 'cache.dat'));
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
$CIDRAM['BlockInfo'] = array();
$CIDRAM['BlockInfo']['DateTime'] = date('r');
$CIDRAM['BlockInfo']['IPAddr'] = $_SERVER[$CIDRAM['Config']['general']['ipaddr']];
$CIDRAM['BlockInfo']['ScriptIdent'] = $CIDRAM['ScriptIdent'];
$CIDRAM['BlockInfo']['Query'] = $CIDRAM['Query'];
$CIDRAM['BlockInfo']['Referrer'] =
    (!empty($_SERVER['HTTP_REFERER'])) ?
    $_SERVER['HTTP_REFERER'] :
    '';
$CIDRAM['BlockInfo']['UA'] =
    (!empty($_SERVER['HTTP_USER_AGENT'])) ?
    $_SERVER['HTTP_USER_AGENT'] :
    '';
$CIDRAM['BlockInfo']['UALC'] = strtolower($CIDRAM['BlockInfo']['UA']);
$CIDRAM['BlockInfo']['ReasonMessage'] = '';
$CIDRAM['BlockInfo']['SignatureCount'] = 0;
$CIDRAM['BlockInfo']['Signatures'] = '';
$CIDRAM['BlockInfo']['xmlLang'] = $CIDRAM['Config']['general']['lang'];

/** Run the IPv4 test. */
$CIDRAM['TestIPv4'] = IPv4Test($_SERVER[$CIDRAM['Config']['general']['ipaddr']]);

/** Run the IPv6 test. */
$CIDRAM['TestIPv6'] = IPv6Test($_SERVER[$CIDRAM['Config']['general']['ipaddr']]);

/** If both tests fail, report an invalid IP address and kill the request. */
if (!$CIDRAM['TestIPv4'] && !$CIDRAM['TestIPv6']) {
    $CIDRAM['BlockInfo']['ReasonMessage'] .= $CIDRAM['lang']['ReasonMessage_BadIP'];
    if (!empty($CIDRAM['BlockInfo']['Signatures'])) {
        $CIDRAM['BlockInfo']['Signatures'] .= ', ';
    }
    $CIDRAM['BlockInfo']['Signatures'] .= $CIDRAM['lang']['Short_BadIP'];
    $CIDRAM['BlockInfo']['SignatureCount']++;
}

/**
 * If any signatures were triggered and logging is enabled, increment the
 * counter.
 */
if ($CIDRAM['BlockInfo']['SignatureCount'] && $CIDRAM['Config']['general']['logfile']) {
    $CIDRAM['Cache']['Counter']++;
    $CIDRAM['CacheModified'] = true;
}
$CIDRAM['BlockInfo']['Counter'] = $CIDRAM['Cache']['Counter'];

/** Update the cache. */
if ($CIDRAM['CacheModified']) {
    $CIDRAM['handle']=fopen($CIDRAM['Vault'] . 'cache.dat', 'w');
    fwrite($CIDRAM['handle'], serialize($CIDRAM['Cache']));
    fclose($CIDRAM['handle']);
}

/** If any signatures were triggered, log it, generate output, then die. */
if ($CIDRAM['BlockInfo']['SignatureCount']) {
    $CIDRAM['template_file'] = 'template.html';
    if ($CIDRAM['Config']['general']['logfile']) {
        $CIDRAM['logfileData'] = array();
        $CIDRAM['logfileData']['d'] =
            (!file_exists($CIDRAM['Vault'] . $CIDRAM['Config']['general']['logfile'])) ?
            "\x3c\x3fphp die; \x3f\x3e\n\n" :
            '';
        $CIDRAM['logfileData']['d'] .=
            ParseVars(
                $CIDRAM['lang'],
                ParseVars(
                    $CIDRAM['BlockInfo'],
                    "{field_id}{Counter}\n{field_scriptversion}{ScriptIdent}" .
                    "\n{field_datetime}{DateTime}\n{field_ipaddr}{IPAddr}\n" .
                    "{field_query}{Query}\n{field_referrer}{Referrer}\n" .
                    "{field_sigcount}{SignatureCount}\n{field_sigref}" .
                    "{Signatures}\n{field_ua}{UA}\n\n"
                )
            );
        $CIDRAM['logfileData']['f'] = fopen($CIDRAM['Vault'] . $CIDRAM['Config']['general']['logfile'], 'a');
        fwrite($CIDRAM['logfileData']['f'], $CIDRAM['logfileData']['d']);
        fclose($CIDRAM['logfileData']['f']);
        unset($CIDRAM['logfileData']);
    }
    if (!file_exists($CIDRAM['Vault'] . $CIDRAM['template_file'])) {
        header('Content-Type: text/plain');
        die('[CIDRAM] '.$CIDRAM['lang']['denied']);
    }
    if (!$CIDRAM['Config']['general']['emailaddr']) {
        $CIDRAM['BlockInfo']['EmailAddr'] = '';
    } else {
        $CIDRAM['BlockInfo']['EmailAddr'] =
            '<strong><a href="mailto:' .
            $CIDRAM['Config']['general']['emailaddr'] .
            '?subject=CIDRAM%20Event&body=' . urlencode(ParseVars(
                $CIDRAM['lang'],
                ParseVars(
                    $CIDRAM['BlockInfo'],
                    "{field_id}{Counter}\n{field_scriptversion}{ScriptIdent}" .
                    "\n{field_datetime}{DateTime}\n{field_ipaddr}{IPAddr}\n" .
                    "{field_query}{Query}\n{field_referrer}{Referrer}\n" .
                    "{field_sigcount}{SignatureCount}\n{field_sigref}" .
                    "{Signatures}\n{field_ua}{UA}\n\n{preamble}\n\n"
                )
            )) .
            '">' . $CIDRAM['lang']['click_here'] . '</a></strong>';
        $CIDRAM['BlockInfo']['EmailAddr'] = "\n<p><strong>" . ParseVars(array(
            'ClickHereLink' => $CIDRAM['BlockInfo']['EmailAddr']
        ), $CIDRAM['lang']['Support_Email']) . '</strong></p>';
    }
    if ($CIDRAM['Config']['general']['forbid_on_block']) {
        header('HTTP/1.0 403 Forbidden');
        header('HTTP/1.1 403 Forbidden');
        header('Status: 403 Forbidden');
    }
    $CIDRAM['html'] = ParseVars(
        $CIDRAM['lang'], ParseVars(
            $CIDRAM['BlockInfo'], CIDRAM_ReadFile(
                $CIDRAM['Vault'] . $CIDRAM['template_file']
            )
        )
    );
    die($CIDRAM['html']);
}
