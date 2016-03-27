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
 * This file: CLI handler (last modified: 2016.03.27).
 */

/** Fallback for missing $_SERVER superglobal. */
if (!isset($_SERVER)) {
    $_SERVER = array();
}

$CIDRAM['argv'] = array(
    isset($argv[0]) ? $argv[0] : '',
    isset($argv[1]) ? $argv[1] : '',
    isset($argv[2]) ? $argv[2] : '',
);

if ($CIDRAM['argv'][1] === '-h') {
    /** CIDRAM CLI-mode help. */
    echo $CIDRAM['lang']['CLI_H'];
} elseif ($CIDRAM['argv'][1] === '-c') {
    /** Check if an IP address is blocked by the CIDRAM signature files. */
    echo "\n";
    /** Prepare variables to simulate the normal IP checking process. */
    $CIDRAM['BlockInfo'] = array(
        'IPAddr' => $CIDRAM['argv'][2],
        'Query' => $CIDRAM['Query'],
        'Referrer' => '',
        'UA' => '',
        'UALC' => '',
        'ReasonMessage' => '',
        'SignatureCount' => 0,
        'Signatures' => '',
        'WhyReason' => '',
        'xmlLang' => $CIDRAM['Config']['general']['lang']
    );
    $CIDRAM['TestIPv4'] = $CIDRAM['IPv4Test']($CIDRAM['argv'][2]);
    $CIDRAM['TestIPv6'] = $CIDRAM['IPv6Test']($CIDRAM['argv'][2]);
    if (!$CIDRAM['TestIPv4'] && !$CIDRAM['TestIPv6']) {
        echo wordwrap($CIDRAM['ParseVars'](array('IP' => $CIDRAM['argv'][2]), $CIDRAM['lang']['CLI_Bad_IP']), 79, "\n ");
    } else {
        echo
            ($CIDRAM['BlockInfo']['SignatureCount']) ?
            wordwrap($CIDRAM['ParseVars'](array('IP' => $CIDRAM['argv'][2]), $CIDRAM['lang']['CLI_IP_Blocked']), 79, "\n ") :
            wordwrap($CIDRAM['ParseVars'](array('IP' => $CIDRAM['argv'][2]), $CIDRAM['lang']['CLI_IP_Not_Blocked']), 79, "\n ");
    }
    echo "\n";
} elseif ($CIDRAM['argv'][1] === '-g') {
    /** Generate CIDRs from an IP address. */
    echo "\n";
    $CIDRAM['TestIPv4'] = $CIDRAM['IPv4Test']($CIDRAM['argv'][2], true);
    $CIDRAM['TestIPv6'] = $CIDRAM['IPv6Test']($CIDRAM['argv'][2], true);
    if (!empty($CIDRAM['TestIPv4'])) {
        var_dump($CIDRAM['TestIPv4']);
    } elseif (!empty($CIDRAM['TestIPv6'])) {
        var_dump($CIDRAM['TestIPv6']);
    } else {
        echo wordwrap($CIDRAM['ParseVars'](array('IP' => $CIDRAM['argv'][2]), $CIDRAM['lang']['CLI_Bad_IP']), 79, "\n ");
    }
    echo "\n";
}
