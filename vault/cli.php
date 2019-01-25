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
 * This file: CLI handler (last modified: 2019.01.25).
 */

/** Fallback for missing $_SERVER superglobal. */
if (!isset($_SERVER)) {
    $_SERVER = [];
}

$CIDRAM['argv'] = [
    isset($argv[0]) ? $argv[0] : '',
    isset($argv[1]) ? $argv[1] : '',
    isset($argv[2]) ? $argv[2] : '',
];

if ($CIDRAM['argv'][1] === '-h') {
    /** CIDRAM CLI-mode help. */
    echo $CIDRAM['lang']['CLI_H'];
} elseif ($CIDRAM['argv'][1] === '-c') {
    /** Check if an IP address is blocked by the CIDRAM signature files. */
    echo "\n";
    /** Prepare variables to simulate the normal IP checking process. */
    $CIDRAM['BlockInfo'] = [
        'IPAddr' => $CIDRAM['argv'][2],
        'Query' => $CIDRAM['Query'],
        'Referrer' => '',
        'UA' => '',
        'UALC' => '',
        'ReasonMessage' => '',
        'SignatureCount' => 0,
        'Signatures' => '',
        'WhyReason' => '',
        'xmlLang' => $CIDRAM['Config']['general']['lang'],
        'rURI' => 'CLI'
    ];
    try {
        $CIDRAM['Caught'] = false;
        $CIDRAM['TestResults'] = $CIDRAM['RunTests']($CIDRAM['argv'][2]);
    } catch (\Exception $e) {
        $CIDRAM['Caught'] = true;
        echo wordwrap($e->getMessage(), 78, "\n ");
    }
    if (!$CIDRAM['Caught']) {
        if (!$CIDRAM['TestResults']) {
            echo wordwrap($CIDRAM['ParseVars'](['IP' => $CIDRAM['argv'][2]], $CIDRAM['lang']['CLI_Bad_IP']), 78, "\n ");
        } else {
            echo wordwrap($CIDRAM['ParseVars'](['IP' => $CIDRAM['argv'][2]], (
                $CIDRAM['BlockInfo']['SignatureCount'] ? $CIDRAM['lang']['CLI_IP_Blocked'] : $CIDRAM['lang']['CLI_IP_Not_Blocked']
            )), 78, "\n ");
        }
    }
    echo "\n";
} elseif ($CIDRAM['argv'][1] === '-g') {
    /** Generate CIDRs from an IP address. */
    echo "\n";
    $CIDRAM['TestResults'] = $CIDRAM['ExpandIPv4']($CIDRAM['argv'][2]);
    if (empty($CIDRAM['TestResults'])) {
        $CIDRAM['TestResults'] = $CIDRAM['ExpandIPv6']($CIDRAM['argv'][2]);
    }
    if (empty($CIDRAM['TestResults'])) {
        echo wordwrap($CIDRAM['ParseVars'](['IP' => $CIDRAM['argv'][2]], $CIDRAM['lang']['CLI_Bad_IP']), 78, "\n ");
    } else {
        echo ' ' . implode("\n ", $CIDRAM['TestResults']);
    }
    echo "\n";
} elseif ($CIDRAM['argv'][1] === '-v') {
    /** Validates signature files. */
    echo "\n";
    $FileToValidate = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['argv'][2]);
    echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_0'], sprintf($CIDRAM['lang']['CLI_V_Started'], $CIDRAM['TimeFormat']($CIDRAM['Now'], $CIDRAM['Config']['general']['timeFormat'])));
    if (empty($FileToValidate)) {
        die($CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_3'], $CIDRAM['lang']['CLI_VF_Empty']) . "\n");
    }
    if (strpos($FileToValidate, "\r")) {
        echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_1'], $CIDRAM['lang']['CLI_V_CRLF']);
        $FileToValidate = (strpos($FileToValidate, "\r\n") !== false) ? str_replace("\r", '', $FileToValidate) : str_replace("\r", "\n", $FileToValidate);
    }
    if (substr($FileToValidate, -1) !== "\n") {
        echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_1'], $CIDRAM['lang']['CLI_V_Terminal_LF']);
        $FileToValidate .= "\n";
    }
    echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_0'], $CIDRAM['lang']['CLI_V_LineByLine']);
    $ArrayToValidate = explode("\n", $FileToValidate);
    $c = count($ArrayToValidate);
    $YAMLM = $YAMLL = false;
    for ($i = 0; $i < $c; $i++) {
        $Len = strlen($ArrayToValidate[$i]);
        if ($Len > 120) {
            echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_1'], sprintf($CIDRAM['lang']['CLI_VL_L120'], $i));
        } elseif (!$Len) {
            continue;
        }
        if (isset($ArrayToValidate[$i + 1]) && $ArrayToValidate[$i + 1] === $ArrayToValidate[$i]) {
            echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_0'], sprintf($CIDRAM['lang']['CLI_VL_Mergeable'], $i, ($i + 1)));
        }
        if (preg_match('/\s+$/', $ArrayToValidate[$i])) {
            echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_1'], sprintf($CIDRAM['lang']['CLI_VL_Whitespace'], $i));
        }
        if (substr($ArrayToValidate[$i], 0, 1) === '#') {
            continue;
        }
        if (strpos($ArrayToValidate[$i], "\t") !== false) {
            echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_1'], sprintf($CIDRAM['lang']['CLI_VL_Tabs'], $i));
        } elseif (preg_match('/[^\x20-\xff]/', $ArrayToValidate[$i])) {
            echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_1'], sprintf($CIDRAM['lang']['CLI_VL_CC'], $i));
        }
        if (substr($ArrayToValidate[$i], 0, 5) === 'Tag: ') {
            if ($Len > 25) {
                echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_1'], sprintf($CIDRAM['lang']['CLI_VL_Tags'], $i));
            }
            continue;
        }
        if (substr($ArrayToValidate[$i], 0, 9) === 'Expires: ') {
            if (!$CIDRAM['FetchExpires'](substr($ArrayToValidate[$i], 9))) {
                echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_2'], sprintf($CIDRAM['lang']['CLI_VL_Expiry'], $i));
            }
            continue;
        }
        if (substr($ArrayToValidate[$i], 0, 8) === 'Origin: ') {
            if (!preg_match('~^[A-Z]{2}$~', substr($ArrayToValidate[$i], 8))) {
                echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_2'], sprintf($CIDRAM['lang']['CLI_VL_Expiry'], $i));
            }
            continue;
        }
        if ($YAMLM) {
            $YAMLD .= $ArrayToValidate[$i] . "\n";
            if (empty($ArrayToValidate[$i + 1])) {
                $YAMLM = false;
                $YAMLO = new \Maikuolan\Common\YAML();
                if (!($YAMLO->process($YAMLD, $YAMLO->Data))) {
                    echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_2'], sprintf($CIDRAM['lang']['CLI_VL_YAML'], $YAMLL));
                }
                unset($YAMLO);
            }
            continue;
        }
        if (!$YAMLM && $ArrayToValidate[$i] === '---') {
            $YAMLM = true;
            $YAMLL = $i;
            $YAMLD = '';
            continue;
        }
        $Sig = ['Base' => (
            ($BasePos = strpos($ArrayToValidate[$i], ' ')) !== false
        ) ? substr($ArrayToValidate[$i], 0, $BasePos) : $ArrayToValidate[$i]];
        if (!$Sig['x'] = strpos($Sig['Base'], '/')) {
            echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_1'], sprintf($CIDRAM['lang']['CLI_VL_Syntax'], $i));
            continue;
        }
        $Sig['Initial'] = substr($Sig['Base'], 0, $Sig['x']);
        $Sig['Prefix'] = substr($Sig['Base'], $Sig['x'] + 1);
        $Sig['PrefixInt'] = (int)$Sig['Prefix'];
        $Sig['Key'] = $Sig['PrefixInt'] - 1;
        $Sig['IPv4'] = $CIDRAM['ExpandIPv4']($Sig['Initial']);
        $Sig['IPv6'] = $CIDRAM['ExpandIPv6']($Sig['Initial']);
        if (!$Sig['IPv4'] && !$Sig['IPv6']) {
            echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_2'], sprintf($CIDRAM['lang']['CLI_VL_Invalid'], $i, $Sig['Initial']));
            continue;
        }
        if ($Sig['Base'] !== $ArrayToValidate[$i]) {
            $Sig['Function'] = substr($ArrayToValidate[$i], strlen($Sig['Base']) + 1);
            if ($Sig['x'] = strpos($Sig['Function'], ' ')) {
                $Sig['Param'] = substr($Sig['Function'], $Sig['x'] + 1);
                $Sig['Function'] = substr($Sig['Function'], 0, $Sig['x']);
            } else {
                $Sig['Param'] = '';
            }
            if ($Sig['Function'] !== 'Deny' && $Sig['Function'] !== 'Whitelist' && $Sig['Function'] !== 'Greylist' && $Sig['Function'] !== 'Run') {
                echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_2'], sprintf($CIDRAM['lang']['CLI_VL_Unrecognised'], $i));
            }
        } else {
            $Sig['Param'] = $Sig['Function'] = '';
            echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_2'], sprintf($CIDRAM['lang']['CLI_VL_Missing'], $i));
        }
        if ($Sig['Function'] === 'Deny' && ($Sig['n'] = substr_count($FileToValidate, "\n" . $Sig['Base'] . ' Deny ')) && $Sig['n'] > 1) {
            echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_1'], sprintf($CIDRAM['lang']['CLI_VL_Duplicated'], $i, $Sig['Base'], $Sig['n']));
        } elseif ($Sig['Function'] === 'Whitelist' && ($Sig['n'] = substr_count($FileToValidate, "\n" . $Sig['Base'] . ' Whitelist')) && $Sig['n'] > 1) {
            echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_1'], sprintf($CIDRAM['lang']['CLI_VL_Duplicated'], $i, $Sig['Base'], $Sig['n']));
        } elseif ($Sig['Function'] === 'Greylist' && ($Sig['n'] = substr_count($FileToValidate, "\n" . $Sig['Base'] . ' Greylist')) && $Sig['n'] > 1) {
            echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_1'], sprintf($CIDRAM['lang']['CLI_VL_Duplicated'], $i, $Sig['Base'], $Sig['n']));
        } elseif ($Sig['Function'] === 'Run' && ($Sig['n'] = substr_count($FileToValidate, "\n" . $Sig['Base'] . ' Run ')) && $Sig['n'] > 1) {
            echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_1'], sprintf($CIDRAM['lang']['CLI_VL_Duplicated'], $i, $Sig['Base'], $Sig['n']));
        }
        if ($Sig['IPv4']) {
            if ($Sig['Key'] < 0 || $Sig['Key'] > 31) {
                echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_2'], sprintf($CIDRAM['lang']['CLI_VL_Nontriggerable_Range'], $i, $Sig['Base'], $Sig['Prefix']));
            } else {
                if ($Sig['IPv4'][$Sig['Key']] !== $Sig['Base']) {
                    echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_2'], sprintf($CIDRAM['lang']['CLI_VL_Nontriggerable'], $i, $Sig['Base'], $Sig['IPv4'][$Sig['Key']]));
                }
                for ($Sig['Iterator'] = 0; $Sig['Iterator'] < $Sig['Key']; $Sig['Iterator']++) {
                    if (
                        ($Sig['Function'] === 'Deny' && substr_count($FileToValidate, "\n" . $Sig['IPv4'][$Sig['Iterator']] . ' Deny')) ||
                        ($Sig['Function'] === 'Whitelist' && substr_count($FileToValidate, "\n" . $Sig['IPv4'][$Sig['Iterator']] . ' Whitelist')) ||
                        ($Sig['Function'] === 'Greylist' && substr_count($FileToValidate, "\n" . $Sig['IPv4'][$Sig['Iterator']] . ' Greylist')) ||
                        ($Sig['Function'] === 'Run' && substr_count($FileToValidate, "\n" . $Sig['IPv4'][$Sig['Iterator']] . ' Run'))
                    ) {
                        echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_1'], sprintf($CIDRAM['lang']['CLI_VL_Subordinate'], $i, $Sig['Base'], $Sig['IPv4'][$Sig['Iterator']]));
                    }
                }
                for ($Sig['Iterator'] = $Sig['PrefixInt']; $Sig['Iterator'] < 32; $Sig['Iterator']++) {
                    if (substr_count($FileToValidate, "\n" . $Sig['IPv4'][$Sig['Iterator']] . ' ')) {
                        echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_1'], sprintf($CIDRAM['lang']['CLI_VL_Superset'], $i, $Sig['Base'], $Sig['IPv4'][$Sig['Iterator']]));
                    }
                }
            }
        } elseif ($Sig['IPv6']) {
            if ($Sig['Key'] < 0 || $Sig['Key'] > 127) {
                echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_2'], sprintf($CIDRAM['lang']['CLI_VL_Nontriggerable_Range'], $i, $Sig['Base'], $Sig['Prefix']));
            } else {
                if ($Sig['IPv6'][$Sig['Key']] !== $Sig['Base']) {
                    echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_2'], sprintf($CIDRAM['lang']['CLI_VL_Nontriggerable'], $i, $Sig['Base'], $Sig['IPv6'][$Sig['Key']]));
                }
                for ($Sig['Iterator'] = 0; $Sig['Iterator'] < $Sig['Key']; $Sig['Iterator']++) {
                    if (
                        ($Sig['Function'] === 'Deny' && substr_count($FileToValidate, "\n" . $Sig['IPv6'][$Sig['Iterator']] . ' Deny')) ||
                        ($Sig['Function'] === 'Whitelist' && substr_count($FileToValidate, "\n" . $Sig['IPv6'][$Sig['Iterator']] . ' Whitelist')) ||
                        ($Sig['Function'] === 'Greylist' && substr_count($FileToValidate, "\n" . $Sig['IPv6'][$Sig['Iterator']] . ' Greylist')) ||
                        ($Sig['Function'] === 'Run' && substr_count($FileToValidate, "\n" . $Sig['IPv6'][$Sig['Iterator']] . ' Run'))
                    ) {
                        echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_1'], sprintf($CIDRAM['lang']['CLI_VL_Subordinate'], $i, $Sig['Base'], $Sig['IPv6'][$Sig['Iterator']]));
                    }
                }
                for ($Sig['Iterator'] = $Sig['PrefixInt']; $Sig['Iterator'] < 128; $Sig['Iterator']++) {
                    if (substr_count($FileToValidate, "\n" . $Sig['IPv6'][$Sig['Iterator']] . ' ')) {
                        echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_1'], sprintf($CIDRAM['lang']['CLI_VL_Superset'], $i, $Sig['Base'], $Sig['IPv6'][$Sig['Iterator']]));
                    }
                }
            }
        }
    }
    echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_0'], sprintf($CIDRAM['lang']['CLI_V_Finished'], $CIDRAM['TimeFormat']($CIDRAM['Now'], $CIDRAM['Config']['general']['timeFormat']))) . "\n";
} elseif ($CIDRAM['argv'][1] === '-f') {
    /** Attempts to automatically fix signature files. */
    echo "\n";
    $FileToValidate = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['argv'][2]);
    echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_0'], sprintf($CIDRAM['lang']['CLI_F_Started'], $CIDRAM['TimeFormat']($CIDRAM['Now'], $CIDRAM['Config']['general']['timeFormat'])));
    if (empty($FileToValidate)) {
        die($CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_3'], $CIDRAM['lang']['CLI_VF_Empty']) . "\n");
    }
    $ModCheckBefore = '[' . md5($FileToValidate) . ':' . strlen($FileToValidate) . ']';
    $Operations = $Changes = 0;
    if ($LNs = substr_count($FileToValidate, "\r")) {
        $FileToValidate = (strpos($FileToValidate, "\r\n")) ? str_replace("\r", '', $FileToValidate) : str_replace("\r", "\n", $FileToValidate);
        $Changes += $LNs;
        $Operations++;
    }
    if ($LNs = substr_count($FileToValidate, "\t")) {
        $FileToValidate = str_replace("\t", '    ', $FileToValidate);
        $Changes += $LNs;
        $Operations++;
    }
    if (preg_match('/[^\x0a\x20-\xff]/', $FileToValidate)) {
        $LenBefore = strlen($FileToValidate);
        $FileToValidate = preg_replace('/[^\x0a\x20-\xff]/', '', $FileToValidate);
        $Changes += $LenBefore - strlen($FileToValidate);
        $Operations++;
    }
    if (substr($FileToValidate, -1) !== "\n") {
        $FileToValidate .= "\n";
        $Changes++;
        $Operations++;
    }
    $FileToValidate = "\n" . $FileToValidate;
    $ArrayToValidate = explode("\n", $FileToValidate);
    $c = count($ArrayToValidate);
    $YAMLM = $YAMLL = false;
    for ($i = 0; $i < $c; $i++) {
        if (!$Len = strlen($ArrayToValidate[$i])) {
            continue;
        }
        if (isset($ArrayToValidate[$i + 1]) && $ArrayToValidate[$i + 1] === $ArrayToValidate[$i]) {
            $FileToValidate = str_replace("\n" . $ArrayToValidate[$i] . "\n" . $ArrayToValidate[$i] . "\n", "\n" . $ArrayToValidate[$i] . "\n", $FileToValidate);
            $Changes++;
            $Operations++;
            continue;
        }
        if (preg_match('/\s+$/', $ArrayToValidate[$i])) {
            $FileToValidate = str_replace($ArrayToValidate[$i] . "\n", preg_replace('/\s+$/', '', $ArrayToValidate[$i]) . "\n", $FileToValidate);
            $Changes++;
            $Operations++;
        }
        if (preg_match('~^(?:(?:Tag|Expires|Origin|Defers to): |#)~', $ArrayToValidate[$i])) {
            continue;
        }
        if ($YAMLM) {
            if (empty($ArrayToValidate[$i + 1])) {
                $YAMLM = false;
            }
            continue;
        }
        if (!$YAMLM && $ArrayToValidate[$i] === '---') {
            $YAMLM = true;
            continue;
        }
        $Sig = ['Base' => (
            ($BasePos = strpos($ArrayToValidate[$i], ' ')) !== false
        ) ? substr($ArrayToValidate[$i], 0, $BasePos) : $ArrayToValidate[$i]];
        if (!$Sig['x'] = strpos($Sig['Base'], '/')) {
            $FileToValidate = str_replace("\n" . $ArrayToValidate[$i] . "\n", "\n# " . $ArrayToValidate[$i] . "\n", $FileToValidate);
            $Changes++;
            $Operations++;
            continue;
        }
        $Sig['Initial'] = substr($Sig['Base'], 0, $Sig['x']);
        $Sig['Prefix'] = substr($Sig['Base'], $Sig['x'] + 1);
        $Sig['PrefixInt'] = (int)$Sig['Prefix'];
        $Sig['Key'] = $Sig['PrefixInt'] - 1;
        $Sig['IPv4'] = $CIDRAM['ExpandIPv4']($Sig['Initial']);
        $Sig['IPv6'] = $CIDRAM['ExpandIPv6']($Sig['Initial']);
        if (!$Sig['IPv4'] && !$Sig['IPv6']) {
            $FileToValidate = str_replace("\n" . $ArrayToValidate[$i] . "\n", "\n# " . $ArrayToValidate[$i] . "\n", $FileToValidate);
            $Changes++;
            $Operations++;
            continue;
        }
        if ($Sig['Base'] === $ArrayToValidate[$i]) {
            $Sig['Param'] = $Sig['Function'] = '';
            $FileToValidate = str_replace("\n" . $ArrayToValidate[$i] . "\n", "\n# " . $ArrayToValidate[$i] . "\n", $FileToValidate);
            $Changes++;
            $Operations++;
            continue;
        }
        $Sig['Function'] = substr($ArrayToValidate[$i], strlen($Sig['Base']) + 1);
        if ($Sig['x'] = strpos($Sig['Function'], ' ')) {
            $Sig['Param'] = substr($Sig['Function'], $Sig['x'] + 1);
            $Sig['Function'] = substr($Sig['Function'], 0, $Sig['x']);
        } else {
            $Sig['Param'] = '';
        }
        if ($Sig['Function'] === 'Deny' && ($Sig['n'] = substr_count($FileToValidate, "\n" . $Sig['Base'] . ' Deny ')) && $Sig['n'] > 1) {
            $Sig['x'] = strpos($FileToValidate, "\n" . $Sig['Base'] . ' Deny ') + strlen("\n" . $Sig['Base'] . ' Deny ');
            $Sig['FilePartial'] = [substr($FileToValidate, 0, $Sig['x']), substr($FileToValidate, $Sig['x'])];
            $Sig['FilePartial'][1] = preg_replace("\x1a\n" . addslashes($Sig['Base']) . " Deny[^\n]*\n\x1ai", "\n", $Sig['FilePartial'][1]);
            $FileToValidate = $Sig['FilePartial'][0] . $Sig['FilePartial'][1];
            $Sig['FilePartial'] = '';
            $Changes += $Sig['n'] - 1;
            $Operations++;
        } elseif ($Sig['Function'] === 'Whitelist' && ($Sig['n'] = substr_count($FileToValidate, "\n" . $Sig['Base'] . ' Whitelist')) && $Sig['n'] > 1) {
            $Sig['x'] = strpos($FileToValidate, "\n" . $Sig['Base'] . ' Whitelist') + strlen("\n" . $Sig['Base'] . ' Whitelist');
            $Sig['FilePartial'] = [substr($FileToValidate, 0, $Sig['x']), substr($FileToValidate, $Sig['x'])];
            $Sig['FilePartial'][1] = preg_replace("\x1a\n" . addslashes($Sig['Base']) . " Whitelist[^\n]*\n\x1ai", "\n", $Sig['FilePartial'][1]);
            $FileToValidate = $Sig['FilePartial'][0] . $Sig['FilePartial'][1];
            $Sig['FilePartial'] = '';
            $Changes += $Sig['n'] - 1;
            $Operations++;
        } elseif ($Sig['Function'] === 'Greylist' && ($Sig['n'] = substr_count($FileToValidate, "\n" . $Sig['Base'] . ' Greylist')) && $Sig['n'] > 1) {
            $Sig['x'] = strpos($FileToValidate, "\n" . $Sig['Base'] . ' Greylist') + strlen("\n" . $Sig['Base'] . ' Greylist');
            $Sig['FilePartial'] = [substr($FileToValidate, 0, $Sig['x']), substr($FileToValidate, $Sig['x'])];
            $Sig['FilePartial'][1] = preg_replace("\x1a\n" . addslashes($Sig['Base']) . " Greylist[^\n]*\n\x1ai", "\n", $Sig['FilePartial'][1]);
            $FileToValidate = $Sig['FilePartial'][0] . $Sig['FilePartial'][1];
            $Sig['FilePartial'] = '';
            $Changes += $Sig['n'] - 1;
            $Operations++;
        }
        if ($Sig['IPv4']) {
            if ($Sig['Key'] >= 0 && $Sig['IPv4'][$Sig['Key']] !== $Sig['Base'] && $LNs = substr_count($FileToValidate, "\n" . $Sig['Base'] . ' ')) {
                $FileToValidate = str_replace("\n" . $Sig['Base'] . ' ', "\n" . $Sig['IPv4'][$Sig['Key']] . ' ', $FileToValidate);
                $Changes += $LNs;
                $Operations++;
                $Sig['Base'] = $Sig['IPv4'][$Sig['Key']];
                $Sig['Initial'] = substr($Sig['Base'], 0, strpos($Sig['Base'], '/'));
            }
            for ($Sig['Iterator'] = 0; $Sig['Iterator'] < $Sig['Key']; $Sig['Iterator']++) {
                if ($Sig['Function'] === 'Deny' && substr_count($FileToValidate, "\n" . $Sig['IPv4'][$Sig['Iterator']] . ' Deny')) {
                    $FileToValidate = preg_replace("\x1a\n" . addslashes($Sig['Base']) . " Deny[^\n]*\n\x1ai", "\n", $FileToValidate);
                    $Changes++;
                    $Operations++;
                }
                if ($Sig['Function'] === 'Whitelist' && substr_count($FileToValidate, "\n" . $Sig['IPv4'][$Sig['Iterator']] . ' Whitelist')) {
                    $FileToValidate = preg_replace("\x1a\n" . addslashes($Sig['Base']) . " Whitelist[^\n]*\n\x1ai", "\n", $FileToValidate);
                    $Changes++;
                    $Operations++;
                }
                if ($Sig['Function'] === 'Greylist' && substr_count($FileToValidate, "\n" . $Sig['IPv4'][$Sig['Iterator']] . ' Greylist')) {
                    $FileToValidate = preg_replace("\x1a\n" . addslashes($Sig['Base']) . " Greylist[^\n]*\n\x1ai", "\n", $FileToValidate);
                    $Changes++;
                    $Operations++;
                }
            }
        } elseif ($Sig['IPv6']) {
            if ($Sig['Key'] >= 0 && $Sig['IPv6'][$Sig['Key']] !== $Sig['Base'] && $LNs = substr_count($FileToValidate, "\n" . $Sig['Base'] . ' ')) {
                $FileToValidate = str_replace("\n" . $Sig['Base'] . ' ', "\n" . $Sig['IPv6'][$Sig['Key']] . ' ', $FileToValidate);
                $Changes += $LNs;
                $Operations++;
                $Sig['Base'] = $Sig['IPv6'][$Sig['Key']];
                $Sig['Initial'] = substr($Sig['Base'], 0, strpos($Sig['Base'], '/'));
            }
            for ($Sig['Iterator'] = 0; $Sig['Iterator'] < $Sig['Key']; $Sig['Iterator']++) {
                if ($Sig['Function'] === 'Deny' && substr_count($FileToValidate, "\n" . $Sig['IPv6'][$Sig['Iterator']] . ' Deny')) {
                    $FileToValidate = preg_replace("\x1a\n" . addslashes($Sig['Base']) . " Deny[^\n]*\n\x1ai", "\n", $FileToValidate);
                    $Changes++;
                    $Operations++;
                }
                if ($Sig['Function'] === 'Whitelist' && substr_count($FileToValidate, "\n" . $Sig['IPv6'][$Sig['Iterator']] . ' Whitelist')) {
                    $FileToValidate = preg_replace("\x1a\n" . addslashes($Sig['Base']) . " Whitelist[^\n]*\n\x1ai", "\n", $FileToValidate);
                    $Changes++;
                    $Operations++;
                }
                if ($Sig['Function'] === 'Greylist' && substr_count($FileToValidate, "\n" . $Sig['IPv6'][$Sig['Iterator']] . ' Greylist')) {
                    $FileToValidate = preg_replace("\x1a\n" . addslashes($Sig['Base']) . " Greylist[^\n]*\n\x1ai", "\n", $FileToValidate);
                    $Changes++;
                    $Operations++;
                }
            }
        }
    }
    $FileToValidate = substr($FileToValidate, 1);
    if ($ModCheckBefore !== '[' . md5($FileToValidate) . ':' . strlen($FileToValidate) . ']') {
        $Handle = fopen($CIDRAM['Vault'] . $CIDRAM['argv'][2] . '.fixed', 'w');
        fwrite($Handle, $FileToValidate);
        fclose($Handle);
    }
    echo $CIDRAM['ValidatorMsg']($CIDRAM['lang']['CLI_VF_Level_0'], sprintf($CIDRAM['lang']['CLI_F_Finished'], $Changes, $Operations, $CIDRAM['TimeFormat']($CIDRAM['Now'], $CIDRAM['Config']['general']['timeFormat']))) . "\n";
}
