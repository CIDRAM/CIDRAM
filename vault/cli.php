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
 * This file: CLI handler (last modified: 2016.03.17).
 *
 * @package Maikuolan/CIDRAM
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
    echo
        "\nCIDRAM CLI-mode help.\n\nUsage:\n    /path/to/php/php.exe /path/t" .
        "o/cidram/loader.php -Flag (Input)\n\nFlags:\n    -h      Display th" .
        "is help information.\n    -c      Check if an IP address is blocked" .
        "by the CIDRAM signature files.\n    -g      Generate CIDRs from an " .
        "IP address.\n\nInput:\n    Can be any valid IPv4 or IPv6 IP address" .
        ".\n\nExamples:\n    -c 192.168.0.0/16\n    -c 127.0.0.1/32\n    -c " .
        "2001:db8::/32\n    -c 2002::1/128\n\n";
} elseif ($CIDRAM['argv'][1] === '-c') {
    /** Generate CIDRs from an IP address. */
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
        'xmlLang' => $CIDRAM['Config']['general']['lang']
    );
    $CIDRAM['TestIPv4'] = $CIDRAM['IPv4Test']($CIDRAM['argv'][2]);
    $CIDRAM['TestIPv6'] = $CIDRAM['IPv6Test']($CIDRAM['argv'][2]);
    if (!$CIDRAM['TestIPv4'] && !$CIDRAM['TestIPv6']) {
        echo
            'The specified IP address, "' . $CIDRAM['argv'][2] . '", is not ' .
            "a valid IPv4 or IPv6 IP address!\n";
    } else {
        echo
            ($CIDRAM['BlockInfo']['SignatureCount']) ?
                'The specified IP address, "' . $CIDRAM['argv'][2] . '", IS bloc' .
                "ked by one or more of the CIDRAM signature files.\n"
            :
                'The specified IP address, "' . $CIDRAM['argv'][2] . '", is NOT ' .
                "blocked by any of the CIDRAM signature files.\n";
    }
} elseif ($CIDRAM['argv'][1] === '-g') {
    /** Check if an IP address is blocked by the CIDRAM signature files. */
    echo "\n";
    $CIDRAM['TestIPv4'] = $CIDRAM['IPv4Test']($CIDRAM['argv'][2], true);
    $CIDRAM['TestIPv6'] = $CIDRAM['IPv6Test']($CIDRAM['argv'][2], true);
    if (!empty($CIDRAM['TestIPv4'])) {
        var_dump($CIDRAM['TestIPv4']);
    } elseif (!empty($CIDRAM['TestIPv6'])) {
        var_dump($CIDRAM['TestIPv6']);
    } else {
        echo
            'The specified IP address, "' . $CIDRAM['argv'][2] . '", is not ' .
            "a valid IPv4 or IPv6 IP address!\n";
    }
    echo "\n";
}
