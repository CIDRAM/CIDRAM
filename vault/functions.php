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
 * This file: Functions file (last modified: 2017.12.22).
 */

/**
 * Extends compatibility with CIDRAM to PHP 5.4.x by introducing some simple
 * polyfills for functions introduced with newer versions of PHP.
 */
if (substr(PHP_VERSION, 0, 4) === '5.4.') {
    require $CIDRAM['Vault'] . 'php5.4.x.php';
}

/**
 * Reads and returns the contents of files.
 *
 * @param string $File Path and filename of the file to read.
 * @return string|bool Content of the file returned by the function (or false
 *      on failure).
 */
$CIDRAM['ReadFile'] = function ($File) {
    if (!is_file($File) || !is_readable($File)) {
        return false;
    }
    /**
     * $Blocksize represents the size of each block to be read from the target
     * file. 131072 = 128KB. Decreasing this value will increase stability but
     * decrease performance, whereas increasing this value will increase
     * performance but decrease stability.
     */
    $Blocksize = 131072;
    $Filesize = filesize($File);
    $Size = ($Filesize && $Blocksize) ? ceil($Filesize / $Blocksize) : 0;
    $Data = '';
    if ($Size > 0) {
        $Handle = fopen($File, 'rb');
        $r = 0;
        while ($r < $Size) {
            $Data .= fread($Handle, $Blocksize);
            $r++;
        }
        fclose($Handle);
    }
    return $Data ?: false;
};

/**
 * Replaces encapsulated substrings within an input string with the value of
 * elements within an input array, whose keys correspond to the substrings.
 * Accepts two input parameters: An input array (1), and an input string (2).
 *
 * @param array $Needle The input array (the needle[/s]).
 * @param string $Haystack The input string (the haystack).
 * @return string The resultant string.
 */
$CIDRAM['ParseVars'] = function ($Needle, $Haystack) {
    if (!is_array($Needle) || empty($Haystack)) {
        return '';
    }
    array_walk($Needle, function($Value, $Key) use (&$Haystack) {
        if (!is_array($Value)) {
            $Haystack = str_replace('{' . $Key . '}', $Value, $Haystack);
        }
    });
    return $Haystack;
};

/**
 * Fetches instructions from the `ignore.dat` file.
 *
 * @return bool Which sections should be ignored by CIDRAM.
 */
$CIDRAM['FetchIgnores'] = function () use (&$CIDRAM) {
    $IgnoreMe = [];
    $IgnoreFile = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'ignore.dat');
    if (strpos($IgnoreFile, "\r")) {
        $IgnoreFile = (
            strpos($IgnoreFile, "\r\n") !== false
        ) ? str_replace("\r", '', $IgnoreFile) : str_replace("\r", "\n", $IgnoreFile);
    }
    $IgnoreFile = "\n" . $IgnoreFile . "\n";
    $PosB = -1;
    while (true) {
        $PosA = strpos($IgnoreFile, "\nIgnore ", ($PosB + 1));
        if ($PosA === false) {
            break;
        }
        $PosA += 8;
        if (!$PosB = strpos($IgnoreFile, "\n", $PosA)) {
            break;
        }
        $Tag = substr($IgnoreFile, $PosA, ($PosB - $PosA));
        if (strlen($Tag)) {
            $IgnoreMe[$Tag] = true;
        }
        $PosB--;
    }
    return $IgnoreMe;
};

/**
 * Tests whether $Addr is an IPv4 address, and if it is, expands its potential
 * factors (i.e., constructs an array containing the CIDRs that contain $Addr).
 * Returns false if $Addr is *not* an IPv4 address, and otherwise, returns the
 * contructed array.
 *
 * @param string $Addr Refer to the description above.
 * @param bool $ValidateOnly If true, just checks if the IP is valid only.
 * @param int $FactorLimit Maximum number of CIDRs to return (default: 32).
 * @return bool|array Refer to the description above.
 */
$CIDRAM['ExpandIPv4'] = function ($Addr, $ValidateOnly = false, $FactorLimit = 32) {
    if (!preg_match(
        '/^([01]?[0-9]{1,2}|2[0-4][0-9]|25[0-5])\.([01]?[0-9]{1,2}|2[0-4][0-' .
        '9]|25[0-5])\.([01]?[0-9]{1,2}|2[0-4][0-9]|25[0-5])\.([01]?[0-9]{1,2' .
        '}|2[0-4][0-9]|25[0-5])$/i',
    $Addr, $Octets)) {
        return false;
    }
    if ($ValidateOnly) {
        return true;
    }
    $CIDRs = [];
    $Base = [0, 0, 0, 0];
    for ($Cycle = 0; $Cycle < 4; $Cycle++) {
        for ($Size = 128, $Step = 0; $Step < 8; $Step++, $Size /= 2) {
            $CIDR = $Step + ($Cycle * 8);
            $Base[$Cycle] = floor($Octets[$Cycle + 1] / $Size) * $Size;
            $CIDRs[$CIDR] = $Base[0] . '.' . $Base[1] . '.' . $Base[2] . '.' . $Base[3] . '/' . ($CIDR + 1);
            if ($CIDR >= $FactorLimit) {
                break 2;
            }
        }
    }
    return $CIDRs;
};

/**
 * Tests whether $Addr is an IPv6 address, and if it is, expands its potential
 * factors (i.e., constructs an array containing the CIDRs that contain $Addr).
 * Returns false if $Addr is *not* an IPv6 address, and otherwise, returns the
 * contructed array.
 *
 * @param string $Addr Refer to the description above.
 * @param bool $ValidateOnly If true, just checks if the IP is valid only.
 * @param int $FactorLimit Maximum number of CIDRs to return (default: 128).
 * @return bool|array Refer to the description above.
 */
$CIDRAM['ExpandIPv6'] = function ($Addr, $ValidateOnly = false, $FactorLimit = 128) {
    /**
     * The REGEX pattern used by this `preg_match` call was adapted from the
     * IPv6 REGEX pattern that can be found at
     * http://sroze.io/2008/10/09/regex-ipv4-et-ipv6/
     */
    if (!preg_match(
        '/^(([0-9a-f]{1,4}\:){7}[0-9a-f]{1,4})|(([0-9a-f]{1,4}\:){6}\:[0-9a-' .
        'f]{1,4})|(([0-9a-f]{1,4}\:){5}\:([0-9a-f]{1,4}\:)?[0-9a-f]{1,4})|((' .
        '[0-9a-f]{1,4}\:){4}\:([0-9a-f]{1,4}\:){0,2}[0-9a-f]{1,4})|(([0-9a-f' .
        ']{1,4}\:){3}\:([0-9a-f]{1,4}\:){0,3}[0-9a-f]{1,4})|(([0-9a-f]{1,4}' .
        '\:){2}\:([0-9a-f]{1,4}\:){0,4}[0-9a-f]{1,4})|(([0-9a-f]{1,4}\:){6}(' .
        '(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b).){3}(\b((25[0-5])|(' .
        '1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9a-f]{1,4}\:){0,5}\:((\b((25' .
        '[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b).){3}(\b((25[0-5])|(1\d{2})' .
        '|(2[0-4]\d)|(\d{1,2}))\b))|(\:\:([0-9a-f]{1,4}\:){0,5}((\b((25[0-5]' .
        ')|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b).){3}(\b((25[0-5])|(1\d{2})|(2[0' .
        '-4]\d)|(\d{1,2}))\b))|([0-9a-f]{1,4}\:\:([0-9a-f]{1,4}\:){0,5}[0-9a' .
        '-f]{1,4})|(\:\:([0-9a-f]{1,4}\:){0,6}[0-9a-f]{1,4})|(([0-9a-f]{1,4}' .
        '\:){1,7}\:)$/i',
    $Addr)) {
        return false;
    }
    if ($ValidateOnly) {
        return true;
    }
    $NAddr = $Addr;
    if (preg_match('/^\:\:/i', $NAddr)) {
        $NAddr = '0' . $NAddr;
    }
    if (preg_match('/\:\:$/i', $NAddr)) {
        $NAddr .= '0';
    }
    if (substr_count($NAddr, '::')) {
        $c = 7 - substr_count($Addr, ':');
        $Arr = [':0:', ':0:0:', ':0:0:0:', ':0:0:0:0:', ':0:0:0:0:0:', ':0:0:0:0:0:0:'];
        if (!isset($Arr[$c])) {
            return false;
        }
        $NAddr = str_replace('::', $Arr[$c], $Addr);
        unset($Arr);
    }
    $NAddr = explode(':', $NAddr);
    if (count($NAddr) !== 8) {
        return false;
    }
    for ($i = 0; $i < 8; $i++) {
        $NAddr[$i] = hexdec($NAddr[$i]);
    }
    $CIDRs = [];
    $Base = [0, 0, 0, 0, 0, 0, 0, 0];
    for ($Cycle = 0; $Cycle < 8; $Cycle++) {
        for ($Size = 32768, $Step = 0; $Step < 16; $Step++, $Size /= 2) {
            $CIDR = $Step + ($Cycle * 16);
            $Base[$Cycle] = dechex(floor($NAddr[$Cycle] / $Size) * $Size);
            $CIDRs[$CIDR] = $Base[0] . ':' . $Base[1] . ':' . $Base[2] . ':' . $Base[3] . ':' . $Base[4] . ':' . $Base[5] . ':' . $Base[6] . ':' . $Base[7] . '/' . ($CIDR + 1);
            if ($CIDR >= $FactorLimit) {
                break 2;
            }
        }
    }
    if ($FactorLimit > 128) {
        $FactorLimit = 128;
    }
    for ($CIDR = 0; $CIDR < $FactorLimit; $CIDR++) {
        if (strpos($CIDRs[$CIDR], '::') !== false) {
            $CIDRs[$CIDR] = preg_replace('/(\:0)*\:\:(0\:)*/i', '::', $CIDRs[$CIDR], 1);
            $CIDRs[$CIDR] = str_replace('::0/', '::/', $CIDRs[$CIDR]);
            continue;
        }
        if (strpos($CIDRs[$CIDR], ':0:0/') !== false) {
            $CIDRs[$CIDR] = preg_replace('/(\:0){2,}\//i', '::/', $CIDRs[$CIDR], 1);
            continue;
        }
        if (strpos($CIDRs[$CIDR], ':0:0:') !== false) {
            $CIDRs[$CIDR] = preg_replace('/(\:0)+\:(0\:)+/i', '::', $CIDRs[$CIDR], 1);
            $CIDRs[$CIDR] = str_replace('::0/', '::/', $CIDRs[$CIDR]);
            continue;
        }
    }
    return $CIDRs;
};

/**
 * Checks CIDRs (generally, potential factors expanded from IP addresses)
 * against the IPv4/IPv6 signature files, and if any matches are found,
 * increments `$CIDRAM['BlockInfo']['SignatureCount']`, and
 * appends to `$CIDRAM['BlockInfo']['ReasonMessage']`.
 *
 * @param array $Files Which IPv4/IPv6 signature files to check against.
 * @param array $Factors Which CIDRs/factors to check against.
 * @return bool Returns true.
 */
$CIDRAM['CheckFactors'] = function ($Files, $Factors) use (&$CIDRAM) {
    $Counts = [
        'Files' => count($Files),
        'Factors' => count($Factors)
    ];
    if (!isset($CIDRAM['FileCache'])) {
        $CIDRAM['FileCache'] = [];
    }
    for ($FileIndex = 0; $FileIndex < $Counts['Files']; $FileIndex++) {
        if (!$Files[$FileIndex]) {
            continue;
        }
        if ($Counts['Factors'] === 32) {
            $DefTag = $Files[$FileIndex] . '-IPv4';
        } elseif ($Counts['Factors'] === 128) {
            $DefTag = $Files[$FileIndex] . '-IPv6';
        } else {
            $DefTag = $Files[$FileIndex] . '-Unknown';
        }
        $FileExtension = strtolower(substr($Files[$FileIndex], -4));
        if (!isset($CIDRAM['FileCache'][$Files[$FileIndex]])) {
            $CIDRAM['FileCache'][$Files[$FileIndex]] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $Files[$FileIndex]);
        }
        if (!$Files[$FileIndex] = $CIDRAM['FileCache'][$Files[$FileIndex]]) {
            continue;
        }
        if (
            $FileExtension === '.csv' &&
            strpos($Files[$FileIndex], "\n") === false &&
            strpos($Files[$FileIndex], "\r") === false &&
            strpos($Files[$FileIndex], ",") !== false
        ) {
            $Files[$FileIndex] = ',' . $Files[$FileIndex] . ',';
            $SigFormat = 'CSV';
        } else {
            $SigFormat = 'DAT';
        }
        if ($Counts['Factors'] === 32) {
            if ($SigFormat === 'CSV') {
                $NoCIDR = ',' . substr($Factors[31], 0, -3) . ',';
                $LastCIDR = ',' . $Factors[31] . ',';
            } else {
                $NoCIDR = "\n" . substr($Factors[31], 0, -3) . ' ';
                $LastCIDR = "\n" . $Factors[31] . ' ';
            }
        } elseif ($Counts['Factors'] === 128) {
            if ($SigFormat === 'CSV') {
                $NoCIDR = ',' . substr($Factors[127], 0, -4) . ',';
                $LastCIDR = ',' . $Factors[127] . ',';
            } else {
                $NoCIDR = "\n" . substr($Factors[127], 0, -4) . ' ';
                $LastCIDR = "\n" . $Factors[127] . ' ';
            }
        }
        if (strpos($Files[$FileIndex], $NoCIDR) !== false) {
            $Files[$FileIndex] = str_replace($NoCIDR, $LastCIDR, $Files[$FileIndex]);
        }
        if ($SigFormat === 'CSV') {
            $LN = ' ("' . $DefTag . '", L0:F' . $FileIndex . ')';
            for ($FactorIndex = 0; $FactorIndex < $Counts['Factors']; $FactorIndex++) {
                if ($Infractions = substr_count($Files[$FileIndex], ',' . $Factors[$FactorIndex] . ',')) {
                    if (!$CIDRAM['CIDRAM_sapi']) {
                        $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['lang']['ReasonMessage_Generic'];
                        if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
                            $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
                        }
                        $CIDRAM['BlockInfo']['WhyReason'] .= $CIDRAM['lang']['Short_Generic'] . $LN;
                    }
                    if (!empty($CIDRAM['BlockInfo']['Signatures'])) {
                        $CIDRAM['BlockInfo']['Signatures'] .= ', ';
                    }
                    $CIDRAM['BlockInfo']['Signatures'] .= $Factors[$FactorIndex];
                    $CIDRAM['BlockInfo']['SignatureCount'] += $Infractions;
                }
            }
            continue;
        }
        if (strpos($Files[$FileIndex], "\r") !== false) {
            $Files[$FileIndex] =
                (strpos($Files[$FileIndex], "\r\n")) ?
                str_replace("\r", '', $Files[$FileIndex]) :
                str_replace("\r", "\n", $Files[$FileIndex]);
        }
        $Files[$FileIndex] = "\n" . $Files[$FileIndex] . "\n";
        for ($FactorIndex = 0; $FactorIndex < $Counts['Factors']; $FactorIndex++) {
            $PosB = -1;
            while (true) {
                $PosA = strpos($Files[$FileIndex], "\n" . $Factors[$FactorIndex] . ' ', ($PosB + 1));
                if ($PosA === false) {
                    break;
                }
                $PosA += strlen($Factors[$FactorIndex]) + 2;
                if (!$PosB = strpos($Files[$FileIndex], "\n", $PosA)) {
                    break;
                }
                if (
                    ($PosX = strpos($Files[$FileIndex], "\nExpires: ", $PosA)) &&
                    ($PosY = strpos($Files[$FileIndex], "\n", ($PosX + 1))) &&
                    !substr_count($Files[$FileIndex], "\n\n", $PosA, ($PosX - $PosA + 1)) &&
                    ($Expires = $CIDRAM['FetchExpires'](substr($Files[$FileIndex], ($PosX + 10), ($PosY - $PosX - 10)))) &&
                    $Expires < $CIDRAM['Now']
                ) {
                    continue;
                }
                $Tag = (
                    ($PosX = strpos($Files[$FileIndex], "\nTag: ", $PosA)) &&
                    ($PosY = strpos($Files[$FileIndex], "\n", ($PosX + 1))) &&
                    !substr_count($Files[$FileIndex], "\n\n", $PosA, ($PosX - $PosA + 1))
                ) ? substr($Files[$FileIndex], ($PosX + 6), ($PosY - $PosX - 6)) : $DefTag;
                if (!empty($CIDRAM['Ignore'][$Tag])) {
                    continue;
                }
                if (
                    ($PosX = strpos($Files[$FileIndex], "\n---\n", $PosA)) &&
                    ($PosY = strpos($Files[$FileIndex], "\n\n", ($PosX + 1))) &&
                    !substr_count($Files[$FileIndex], "\n\n", $PosA, ($PosX - $PosA + 1))
                ) {
                    $YAML = $CIDRAM['YAML'](substr($Files[$FileIndex], ($PosX + 5), ($PosY - $PosX - 5)), $CIDRAM['Config']);
                }
                $LN = ' ("' . $Tag . '", L' . substr_count($Files[$FileIndex], "\n", 0, $PosA) . ':F' . $FileIndex . ')';
                $Signature = substr($Files[$FileIndex], $PosA, ($PosB - $PosA));
                if (!$Category = substr($Signature, 0, strpos($Signature, ' '))) {
                    $Category = $Signature;
                } else {
                    $Signature = substr($Signature, strpos($Signature, ' ') + 1);
                }
                if ($Category === 'Run' && !$CIDRAM['CIDRAM_sapi']) {
                    if (!isset($CIDRAM['RunParamResCache'])) {
                        $CIDRAM['RunParamResCache'] = [];
                    }
                    if (isset($CIDRAM['RunParamResCache'][$Signature]) && is_object($CIDRAM['RunParamResCache'][$Signature])) {
                        $CIDRAM['RunParamResCache'][$Signature]($Factors, $FactorIndex, $LN);
                    } else {
                        if (file_exists($CIDRAM['Vault'] . $Signature)) {
                            require_once $CIDRAM['Vault'] . $Signature;
                        } else {
                            throw new \Exception($CIDRAM['ParseVars'](
                                ['FileName' => $Signature],
                                '[CIDRAM] ' . $CIDRAM['lang']['Error_MissingRequire']
                            ));
                        }
                    }
                } elseif ($Category === 'Whitelist') {
                    $CIDRAM['BlockInfo']['Signatures'] = $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['BlockInfo']['WhyReason'] = '';
                    $CIDRAM['BlockInfo']['SignatureCount'] = 0;
                    $CIDRAM['Whitelisted'] = true;
                    break 3;
                } elseif ($Category === 'Greylist') {
                    $CIDRAM['BlockInfo']['Signatures'] = $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['BlockInfo']['WhyReason'] = '';
                    $CIDRAM['BlockInfo']['SignatureCount'] = 0;
                    break 2;
                } elseif ($Category === 'Deny') {
                    if ($Signature === 'Bogon' && !$CIDRAM['CIDRAM_sapi']) {
                        if (!$CIDRAM['Config']['signatures']['block_bogons']) {
                            continue;
                        }
                        $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['lang']['ReasonMessage_Bogon'];
                        if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
                            $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
                        }
                        $CIDRAM['BlockInfo']['WhyReason'] .= $CIDRAM['lang']['Short_Bogon'] . $LN;
                    } elseif ($Signature === 'Cloud' && !$CIDRAM['CIDRAM_sapi']) {
                        if (!$CIDRAM['Config']['signatures']['block_cloud']) {
                            continue;
                        }
                        $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['lang']['ReasonMessage_Cloud'];
                        if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
                            $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
                        }
                        $CIDRAM['BlockInfo']['WhyReason'] .= $CIDRAM['lang']['Short_Cloud'] . $LN;
                    } elseif ($Signature === 'Generic' && !$CIDRAM['CIDRAM_sapi']) {
                        if (!$CIDRAM['Config']['signatures']['block_generic']) {
                            continue;
                        }
                        $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['lang']['ReasonMessage_Generic'];
                        if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
                            $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
                        }
                        $CIDRAM['BlockInfo']['WhyReason'] .= $CIDRAM['lang']['Short_Generic'] . $LN;
                    } elseif ($Signature === 'Proxy' && !$CIDRAM['CIDRAM_sapi']) {
                        if (!$CIDRAM['Config']['signatures']['block_proxies']) {
                            continue;
                        }
                        $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['lang']['ReasonMessage_Proxy'];
                        if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
                            $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
                        }
                        $CIDRAM['BlockInfo']['WhyReason'] .= $CIDRAM['lang']['Short_Proxy'] . $LN;
                    } elseif ($Signature === 'Spam' && !$CIDRAM['CIDRAM_sapi']) {
                        if (!$CIDRAM['Config']['signatures']['block_spam']) {
                            continue;
                        }
                        $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['lang']['ReasonMessage_Spam'];
                        if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
                            $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
                        }
                        $CIDRAM['BlockInfo']['WhyReason'] .= $CIDRAM['lang']['Short_Spam'] . $LN;
                    } else {
                        $CIDRAM['BlockInfo']['ReasonMessage'] = $Signature;
                        if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
                            $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
                        }
                        $CIDRAM['BlockInfo']['WhyReason'] .= $Signature . $LN;
                    }
                    if (!empty($CIDRAM['BlockInfo']['Signatures'])) {
                        $CIDRAM['BlockInfo']['Signatures'] .= ', ';
                    }
                    $CIDRAM['BlockInfo']['Signatures'] .= $Factors[$FactorIndex];
                    $CIDRAM['BlockInfo']['SignatureCount']++;
                }
            }
        }
    }
    return true;
};

/**
 * Initialises all IPv4/IPv6 tests.
 *
 * @param string $Addr The IP address to check.
 * @return bool Returns false if all tests fail, and otherwise, returns true.
 */
$CIDRAM['RunTests'] = function ($Addr) use (&$CIDRAM) {
    if (!isset($CIDRAM['BlockInfo'])) {
        return false;
    }
    if (!isset($CIDRAM['Ignore'])) {
        $CIDRAM['Ignore'] = $CIDRAM['FetchIgnores']();
    }
    $CIDRAM['Whitelisted'] = false;
    $CIDRAM['LastTestIP'] = 0;
    if ($IPv4Factors = $CIDRAM['ExpandIPv4']($Addr)) {
        $IPv4Files = empty(
            $CIDRAM['Config']['signatures']['ipv4']
        ) ? [] : explode(',', $CIDRAM['Config']['signatures']['ipv4']);
        try {
            $IPv4Test = $CIDRAM['CheckFactors']($IPv4Files, $IPv4Factors);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        if ($IPv4Test) {
            $CIDRAM['LastTestIP'] = 4;
        }
    } else {
        $IPv4Test = false;
    }
    if ($IPv6Factors = $CIDRAM['ExpandIPv6']($Addr)) {
        $IPv6Files = empty(
            $CIDRAM['Config']['signatures']['ipv6']
        ) ? [] : explode(',', $CIDRAM['Config']['signatures']['ipv6']);
        try {
            $IPv6Test = $CIDRAM['CheckFactors']($IPv6Files, $IPv6Factors);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        if ($IPv6Test) {
            $CIDRAM['LastTestIP'] = 6;
        }
    } else {
        $IPv6Test = false;
    }
    return ($IPv4Test || $IPv6Test);
};

/**
 * A very simple closure for preparing validator/fixer messages in CLI-mode.
 *
 * @param string $lvl Error level.
 * @param string $msg The unprepared message (in).
 * @return string The prepared message (out).
 */
$CIDRAM['ValidatorMsg'] = function ($lvl, $msg) {
    return wordwrap(sprintf(' [%s] %s', $lvl, $msg), 78, "\n ") . "\n\n";
};

/**
 * Reduces code duplicity (the contained code used by multiple parts of the
 * script for dealing with expiry tags).
 *
 * @param string $in Expiry tag.
 * @return int|bool A unix timestamp representing the expiry tag, or false if
 *      the expiry tag doesn't contain a valid ISO 8601 date/time.
 */
$CIDRAM['FetchExpires'] = function ($in) {
    if (
        preg_match(
            '/^([12][0-9]{3})(?:\xe2\x88\x92|[\x2d-\x2f\x5c])?(0[1-9]|1[0-2])(?:\xe2' .
            '\x88\x92|[\x2d-\x2f\x5c])?(0[1-9]|[1-2][0-9]|3[01])\x20?T?([01][0-9]|2[' .
            '0-3])[\x2d\x2e\x3a]?([0-5][0-9])[\x2d\x2e\x3a]?([0-5][0-9])$/i',
        $in, $Arr) ||
        preg_match(
            '/^([12][0-9]{3})(?:\xe2\x88\x92|[\x2d-\x2f\x5c])?(0[1-9]|1[0-2])(?:\xe2' .
            '\x88\x92|[\x2d-\x2f\x5c])?(0[1-9]|[1-2][0-9]|3[01])\x20?T?([01][0-9]|2[' .
            '0-3])[\x2d\x2e\x3a]?([0-5][0-9])$/i',
        $in, $Arr) ||
        preg_match(
            '/^([12][0-9]{3})(?:\xe2\x88\x92|[\x2d-\x2f\x5c])?(0[1-9]|1[0-2])(?:\xe2' .
            '\x88\x92|[\x2d-\x2f\x5c])?(0[1-9]|[1-2][0-9]|3[01])\x20?T?([01][0-9]|2[' .
            '0-3])$/i',
        $in, $Arr) ||
        preg_match(
            '/^([12][0-9]{3})(?:\xe2\x88\x92|[\x2d-\x2f\x5c])?(0[1-9]|1[0-2])(?:\xe2' .
            '\x88\x92|[\x2d-\x2f\x5c])?(0[1-9]|[1-2][0-9]|3[01])$/i',
        $in, $Arr) ||
        preg_match('/^([12][0-9]{3})(?:\xe2\x88\x92|[\x2d-\x2f\x5c])?(0[1-9]|1[0-2])$/i', $in, $Arr) ||
        preg_match('/^([12][0-9]{3})$/i', $in, $Arr)
    ) {
        $Arr = [
            (int)$Arr[1],
            isset($Arr[2]) ? (int)$Arr[2] : 1,
            isset($Arr[3]) ? (int)$Arr[3] : 1,
            isset($Arr[4]) ? (int)$Arr[4] : 0,
            isset($Arr[5]) ? (int)$Arr[5] : 0,
            isset($Arr[6]) ? (int)$Arr[6] : 0
        ];
        $Expires = mktime($Arr[3], $Arr[4], $Arr[5], $Arr[1], $Arr[2], $Arr[0]);
        return $Expires ?: false;
    }
    return false;
};

/**
 * A simple closure for replacing date/time placeholders with corresponding
 * date/time information. Used by the logfiles and some timestamps.
 *
 * @param int $Time A unix timestamp.
 * @param string|array $In An input or an array of inputs to manipulate.
 * @return string|array The adjusted input(/s).
 */
$CIDRAM['TimeFormat'] = function ($Time, $In) use (&$CIDRAM) {
    $Time = date('dmYHisDMP', $Time);
    $values = [
        'dd' => substr($Time, 0, 2),
        'mm' => substr($Time, 2, 2),
        'yyyy' => substr($Time, 4, 4),
        'yy' => substr($Time, 6, 2),
        'hh' => substr($Time, 8, 2),
        'ii' => substr($Time, 10, 2),
        'ss' => substr($Time, 12, 2),
        'Day' => substr($Time, 14, 3),
        'Mon' => substr($Time, 17, 3),
        'tz' => substr($Time, 20, 3) . substr($Time, 24, 2),
        't:z' => substr($Time, 20, 6)
    ];
    $values['d'] = (int)$values['dd'];
    $values['m'] = (int)$values['mm'];
    return is_array($In) ? array_map(function ($Item) use (&$values, &$CIDRAM) {
        return $CIDRAM['ParseVars']($values, $Item);
    }, $In) : $CIDRAM['ParseVars']($values, $In);
};

/**
 * Normalises values defined by the YAML closure.
 *
 * @param string|int|bool $Value The value to be normalised.
 * @param int $ValueLen The length of the value to be normalised.
 * @param string|int|bool $ValueLow The value to be normalised, lowercased.
 */
$CIDRAM['YAML-Normalise-Value'] = function (&$Value, $ValueLen, $ValueLow) {
    if (substr($Value, 0, 1) === '"' && substr($Value, $ValueLen - 1) === '"') {
        $Value = substr($Value, 1, $ValueLen - 2);
    } elseif (substr($Value, 0, 1) === '\'' && substr($Value, $ValueLen - 1) === '\'') {
        $Value = substr($Value, 1, $ValueLen - 2);
    } elseif ($ValueLow === 'true' || $ValueLow === 'y') {
        $Value = true;
    } elseif ($ValueLow === 'false' || $ValueLow === 'n') {
        $Value = false;
    } elseif (substr($Value, 0, 2) === '0x' && ($HexTest = substr($Value, 2)) && !preg_match('/[^a-f0-9]/i', $HexTest) && !($ValueLen % 2)) {
        $Value = hex2bin($HexTest);
    } else {
        $ValueInt = (int)$Value;
        if (strlen($ValueInt) === $ValueLen && $Value == $ValueInt && $ValueLen > 1) {
            $Value = $ValueInt;
        }
    }
    if (!$Value) {
        $Value = false;
    }
};

/**
 * A simplified YAML-like parser. Note: This is intended to adequately serve
 * the needs of this package in a way that should feel familiar to users of
 * YAML, but it isn't a true YAML implementation and it doesn't adhere to any
 * specifications, official or otherwise.
 *
 * @param string $In The data to parse.
 * @param array $Arr Where to save the results.
 * @param bool $VM Validator Mode (if true, results won't be saved).
 * @param int $Depth Tab depth (inherited through recursion; ignore it).
 * @return bool Returns false if errors are encountered, and true otherwise.
 */
$CIDRAM['YAML'] = function ($In, &$Arr, $VM = false, $Depth = 0) use (&$CIDRAM) {
    if (!is_array($Arr)) {
        if ($VM) {
            return false;
        }
        $Arr = [];
    }
    if (!substr_count($In, "\n")) {
        return false;
    }
    $In = str_replace("\r", '', $In);
    $Key = $Value = $SendTo = '';
    $TabLen = $SoL = 0;
    while ($SoL !== false) {
        if (($EoL = strpos($In, "\n", $SoL)) === false) {
            $ThisLine = substr($In, $SoL);
        } else {
            $ThisLine = substr($In, $SoL, $EoL - $SoL);
        }
        $SoL = ($EoL === false) ? false : $EoL + 1;
        $ThisLine = preg_replace(["/#.*$/", "/\x20+$/"], '', $ThisLine);
        if (empty($ThisLine) || $ThisLine === "\n") {
            continue;
        }
        $ThisTab = 0;
        while (($Chr = substr($ThisLine, $ThisTab, 1)) && ($Chr === ' ' || $Chr === "\t")) {
            $ThisTab++;
        }
        if ($ThisTab > $Depth) {
            if ($TabLen === 0) {
                $TabLen = $ThisTab;
            }
            $SendTo .= $ThisLine . "\n";
            continue;
        } elseif ($ThisTab < $Depth) {
            return false;
        } elseif (!empty($SendTo)) {
            if (empty($Key)) {
                return false;
            }
            if (!isset($Arr[$Key])) {
                if ($VM) {
                    return false;
                }
                $Arr[$Key] = false;
            }
            if (!$CIDRAM['YAML']($SendTo, $Arr[$Key], $VM, $TabLen)) {
                return false;
            }
            $SendTo = '';
        }
        if (substr($ThisLine, -1) === ':') {
            $Key = substr($ThisLine, $ThisTab, -1);
            $KeyLen = strlen($Key);
            $KeyLow = strtolower($Key);
            $CIDRAM['YAML-Normalise-Value']($Key, $KeyLen, $KeyLow);
            if (!isset($Arr[$Key])) {
                if ($VM) {
                    return false;
                }
                $Arr[$Key] = false;
            }
        } elseif (substr($ThisLine, $ThisTab, 2) === '- ') {
            $Value = substr($ThisLine, $ThisTab + 2);
            $ValueLen = strlen($Value);
            $ValueLow = strtolower($Value);
            $CIDRAM['YAML-Normalise-Value']($Value, $ValueLen, $ValueLow);
            if (!$VM && $ValueLen > 0) {
                $Arr[] = $Value;
            }
        } elseif (($DelPos = strpos($ThisLine, ': ')) !== false) {
            $Key = substr($ThisLine, $ThisTab, $DelPos - $ThisTab);
            $KeyLen = strlen($Key);
            $KeyLow = strtolower($Key);
            $CIDRAM['YAML-Normalise-Value']($Key, $KeyLen, $KeyLow);
            if (!$Key) {
                return false;
            }
            $Value = substr($ThisLine, $ThisTab + $KeyLen + 2);
            $ValueLen = strlen($Value);
            $ValueLow = strtolower($Value);
            $CIDRAM['YAML-Normalise-Value']($Value, $ValueLen, $ValueLow);
            if (!$VM && $ValueLen > 0) {
                $Arr[$Key] = $Value;
            }
        } elseif (strpos($ThisLine, ':') === false && strlen($ThisLine) > 1) {
            $Key = $ThisLine;
            $KeyLen = strlen($Key);
            $KeyLow = strtolower($Key);
            $CIDRAM['YAML-Normalise-Value']($Key, $KeyLen, $KeyLow);
            if (!isset($Arr[$Key])) {
                if ($VM) {
                    return false;
                }
                $Arr[$Key] = false;
            }
        }
    }
    if (!empty($SendTo) && !empty($Key)) {
        if (!isset($Arr[$Key])) {
            if ($VM) {
                return false;
            }
            $Arr[$Key] = [];
        }
        if (!$CIDRAM['YAML']($SendTo, $Arr[$Key], $VM, $TabLen)) {
            return false;
        }
    }
    return true;
};

/**
 * Fix incorrect typecasting for some for some variables that sometimes default
 * to strings instead of booleans or integers.
 */
$CIDRAM['AutoType'] = function (&$Var, $Type = '') use (&$CIDRAM) {
    if ($Type === 'string' || $Type === 'timezone') {
        $Var = (string)$Var;
    } elseif ($Type === 'int' || $Type === 'integer') {
        $Var = (int)$Var;
    } elseif ($Type === 'real' || $Type === 'double' || $Type === 'float') {
        $Var = (real)$Var;
    } elseif ($Type === 'bool' || $Type === 'boolean') {
        $Var = (strtolower($Var) !== 'false' && $Var);
    } elseif ($Type === 'kb') {
        $Var = $CIDRAM['ReadBytes']($Var, 1);
    } else {
        $LVar = strtolower($Var);
        if ($LVar === 'true') {
            $Var = true;
        } elseif ($LVar === 'false') {
            $Var = false;
        } elseif ($Var !== true && $Var !== false) {
            $Var = (int)$Var;
        }
    }
};

/**
 * Used to send cURL requests.
 *
 * @param string $URI The resource to request.
 * @param array $Params (Optional) An associative array of key-value pairs to
 *      to send along with the request.
 * @param string $Timeout An optional timeout limit (defaults to 12 seconds).
 * @return string The results of the request.
 */
$CIDRAM['Request'] = function ($URI, $Params = '', $Timeout = '') use (&$CIDRAM) {
    if (!$Timeout) {
        $Timeout = $CIDRAM['Timeout'];
    }

    /** Initialise the cURL session. */
    $Request = curl_init($URI);

    $LCURI = strtolower($URI);
    $SSL = (substr($LCURI, 0, 6) === 'https:');

    curl_setopt($Request, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($Request, CURLOPT_HEADER, false);
    if (empty($Params)) {
        curl_setopt($Request, CURLOPT_POST, false);
    } else {
        curl_setopt($Request, CURLOPT_POST, true);
        curl_setopt($Request, CURLOPT_POSTFIELDS, $Params);
    }
    if ($SSL) {
        curl_setopt($Request, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        curl_setopt($Request, CURLOPT_SSL_VERIFYPEER, false);
    }
    curl_setopt($Request, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($Request, CURLOPT_MAXREDIRS, 1);
    curl_setopt($Request, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($Request, CURLOPT_TIMEOUT, $Timeout);
    curl_setopt($Request, CURLOPT_USERAGENT, $CIDRAM['ScriptUA']);

    /** Execute and get the response. */
    $Response = curl_exec($Request);

    /** Close the cURL session. */
    curl_close($Request);

    /** Return the results of the request. */
    return $Response;
};

/**
 * Performs reverse DNS lookups for IP addresses, to resolve their hostnames.
 * This is functionally equivalent to the in-built "gethostbyaddr" PHP
 * function, but with the added benefit of being able to specify which DNS
 * servers to use for lookups, and of being able to enforce timeouts, which
 * should help to avoid some of the problems normally encountered by using
 * "gethostbyaddr".
 *
 * @param string $Addr The IP address to look up.
 * @param string $DNS An optional, comma delimited list of DNS servers to use.
 * @param string $Timeout The timeout limit (optional; defaults to 5 seconds).
 * @return string The hostname on success, or the IP address on failure.
 */
$CIDRAM['DNS-Reverse'] = function ($Addr, $DNS = '', $Timeout = 5) use (&$CIDRAM) {

    /** We've already got it cached. We can return the results early. */
    if (isset($CIDRAM['Cache']['DNS-Reverses'][$Addr]['Host'])) {
        return $CIDRAM['Cache']['DNS-Reverses'][$Addr]['Host'];
    }

    /** The IP address is IPv4. */
    if (strpos($Addr, '.') !== false && strpos($Addr, ':') === false && preg_match(
        '/^([01]?[0-9]{1,2}|2[0-4][0-9]|25[0-5])\.([01]?[0-9]{1,2}|2[0-4][0-' .
        '9]|25[0-5])\.([01]?[0-9]{1,2}|2[0-4][0-9]|25[0-5])\.([01]?[0-9]{1,2' .
        '}|2[0-4][0-9]|25[0-5])$/i',
    $Addr, $Octets)) {
        $Lookup =
            chr(strlen($Octets[4])) . $Octets[4] .
            chr(strlen($Octets[3])) . $Octets[3] .
            chr(strlen($Octets[2])) . $Octets[2] .
            chr(strlen($Octets[1])) . $Octets[1] .
            "\7in-addr\4arpa\0\0\x0c\0\1";
    }

    /** The IP address is IPv6. */
    elseif (strpos($Addr, '.') === false && strpos($Addr, ':') !== false && $CIDRAM['ExpandIPv6']($Addr, true)) {
        $Lookup = $Addr;
        if (strpos($Addr, '::') !== false) {
            $Repeat = 8 - substr_count($Addr, ':');
            $Lookup = str_replace('::', str_repeat(':0', ($Repeat < 1 ? 0 : $Repeat)) . ':', $Lookup);
        }
        while (strlen($Lookup) < 39) {
            $Lookup = preg_replace(
                ['/^:/', '/:$/', '/^([0-9a-f]{1,3}):/i', '/:([0-9a-f]{1,3})$/i', '/:([0-9a-f]{1,3}):/i'],
                ['0:', ':0', '0\1:', ':0\1', ':0\1:'],
                $Lookup
            );
        }
        $Lookup = strrev(preg_replace(['/\:/', '/(.)/'], ['', "\\1\1"], $Lookup)) . "\3ip6\4arpa\0\0\x0c\0\1";
    }

    /** The IP address is.. wrong. Let's exit the closure. */
    else {
        return $Addr;
    }

    /** Some safety mechanisms. */
    if (!isset($CIDRAM['_allow_url_fopen'])) {
        $CIDRAM['_allow_url_fopen'] = ini_get('allow_url_fopen');
        $CIDRAM['_allow_url_fopen'] = !(!$CIDRAM['_allow_url_fopen'] || $CIDRAM['_allow_url_fopen'] == 'Off');
    }
    if (empty($Lookup) || !function_exists('fsockopen') || !$CIDRAM['_allow_url_fopen']) {
        return $Addr;
    }

    /** Prepare cache. */
    if (!isset($CIDRAM['Cache']['DNS-Reverses'])) {
        $CIDRAM['Cache']['DNS-Reverses'] = [];
    }
    $CIDRAM['Cache']['DNS-Reverses'][$Addr] = ['Host' => $Addr, 'Time' => $CIDRAM['Now'] + 21600];
    $CIDRAM['CacheModified'] = true;

    /** DNS is disabled. Let's exit the closure. */
    if (!$DNS && !$DNS = $CIDRAM['Config']['general']['default_dns']) {
        return $Addr;
    }

    /** Expand list of lookup servers. */
    $DNS = explode(',', $DNS);

    /** UDP padding. */
    $LeftPad = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT) . "\1\0\0\1\0\0\0\0\0\0";

    /** Perform the lookup. */
    foreach ($DNS as $Server) {
        if (!empty($Response) || !$Server) {
            break;
        }

        $Handle = fsockopen('udp://' . $Server, 53);
        if ($Handle !== false) {
            fwrite($Handle, $LeftPad . $Lookup);
            stream_set_timeout($Handle, $Timeout);
            stream_set_blocking($Handle, true);
            $Response = fread($Handle, 1024);
            fclose($Handle);
        }
    }

    /** No response, or failed lookup. Let's exit the closure. */
    if (empty($Response)) {
        return $CIDRAM['Cache']['DNS-Reverses'][$Addr]['Host'] = $Addr;
    }

    /** We got a response! Now let's process it accordingly. */
    $Host = '';
    if (($Pos = strpos($Response, $Lookup)) !== false) {
        $Pos += strlen($Lookup) + 12;
        while (($Byte = substr($Response, $Pos, 1)) && $Byte !== "\0") {
            if ($Host) {
                $Host .= '.';
            }
            $Len = hexdec(bin2hex($Byte));
            $Host .= substr($Response, $Pos + 1, $Len);
            $Pos += 1 + $Len;
        }
    }

    /** Return results. */
    return $CIDRAM['Cache']['DNS-Reverses'][$Addr]['Host'] = preg_replace('/[^0-9a-z._~-]/i', '', $Host) ?: $Addr;

};

/** Aliases for "DNS-Reverse". */
$CIDRAM['DNS-Reverse-IPv4'] = $CIDRAM['DNS-Reverse-IPv6'] = function ($Addr, $DNS = '', $Timeout = 5) use (&$CIDRAM) {
    return $CIDRAM['DNS-Reverse']($Addr, $DNS, $Timeout);
};

/**
 * Performs forward DNS lookups for hostnames, to resolve their IP address.
 * This is functionally equivalent to the in-built PHP function
 * "gethostbyname", but with the added benefits of having IPv6 support and of
 * being able to enforce timeout limits, which should help to avoid some of the
 * problems normally associated with using "gethostbyname").
 *
 * @param string $Host The hostname to look up.
 * @param string $Timeout The timeout limit (optional; defaults to 5 seconds).
 * @return string The IP address on success, or an empty string on failure.
 */
$CIDRAM['DNS-Resolve'] = function ($Host, $Timeout = 5) use (&$CIDRAM) {
    if (isset($CIDRAM['Cache']['DNS-Forwards'][$Host]['IPAddr'])) {
        return $CIDRAM['Cache']['DNS-Forwards'][$Host]['IPAddr'];
    }
    if (!isset($CIDRAM['Cache']['DNS-Forwards'])) {
        $CIDRAM['Cache']['DNS-Forwards'] = [];
    }
    $CIDRAM['Cache']['DNS-Forwards'][$Host] = ['IPAddr' => '', 'Time' => $CIDRAM['Now'] + 21600];
    $CIDRAM['CacheModified'] = true;

    static $Valid = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-._~';
    $Host = urlencode($Host);
    if (($HostLen = strlen($Host)) > 253) {
        return '';
    }
    $URI = 'https://dns.google.com/resolve?name=' . urlencode($Host) . '&random_padding=';
    $PadLen = 204 - $HostLen;
    if ($PadLen < 1) {
        $PadLen = 972 - $HostLen;
    }
    while ($PadLen > 0) {
        $PadLen--;
        $URI .= str_shuffle($Valid)[0];
    }

    if (!$Results = json_decode($CIDRAM['Request']($URI, '', $Timeout), true)) {
        return '';
    }
    return $CIDRAM['Cache']['DNS-Forwards'][$Host]['IPAddr'] =
        empty($Results['Answer'][0]['data']) ? '' : preg_replace('/[^0-9a-f.:]/i', '', $Results['Answer'][0]['data']);
};

/**
 * Distinguishes between bots masquerading as popular search engines and real,
 * legitimate search engines. Tracking is disabled for real, legitimate search
 * engines, and those masquerading as them are blocked. If DNS is unresolvable
 * and/or if it can't be determined whether a request has originated from a
 * fake or a legitimate source, it takes no action (i.e., doesn't mess with
 * tracking and doesn't block anything).
 *
 * @param string|array $Domains Accepted domain/hostname partials.
 * @param string $Friendly A friendly name to use in logfiles.
 * @param bool $ReverseOnly Skips forward lookups if true.
 * @return bool Returns true when a determination is successfully made, and
 *      false when a determination isn't able to be made.
 */
$CIDRAM['DNS-Reverse-Forward'] = function ($Domains, $Friendly, $ReverseOnly = false) use (&$CIDRAM) {
    if (empty($CIDRAM['Hostname'])) {
        /** Fetch the hostname. */
        $CIDRAM['Hostname'] = $CIDRAM['DNS-Reverse']($CIDRAM['BlockInfo']['IPAddr']);
    }
    /** Force domains to be an array. */
    $CIDRAM['Arrayify']($Domains);
    /** Do nothing more if we weren't able to resolve the DNS hostname. */
    if (!$CIDRAM['Hostname'] || $CIDRAM['Hostname'] === $CIDRAM['BlockInfo']['IPAddr']) {
        return false;
    }
    $Pass = false;
    /** Compare the hostname against the accepted domain/hostname partials. */
    foreach ($Domains as $Domain) {
        $Len = strlen($Domain) * -1;
        if (substr($CIDRAM['Hostname'], $Len) === $Domain) {
            $Pass = true;
            break;
        }
    }
    /**
     * Resolve the hostname to the original IP address (if $ReverseOnly is
     * false); Act according to the results and return.
     */
    if ($Pass && (
        $ReverseOnly || $CIDRAM['DNS-Resolve']($CIDRAM['Hostname']) === $CIDRAM['BlockInfo']['IPAddr'])
    ) {
        /** It's the real deal; Disable tracking. */
        $CIDRAM['Trackable'] = false;
    } else {
        /** It's a fake; Block it. */
        $Reason = $CIDRAM['ParseVars'](['ua' => $Friendly], $CIDRAM['lang']['fake_ua']);
        $CIDRAM['BlockInfo']['ReasonMessage'] = $Reason;
        if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
            $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
        }
        $CIDRAM['BlockInfo']['WhyReason'] .= $Reason;
        if (!empty($CIDRAM['BlockInfo']['Signatures'])) {
            $CIDRAM['BlockInfo']['Signatures'] .= ', ';
        }
        $Debug = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        $CIDRAM['BlockInfo']['Signatures'] .= basename($Debug['file']) . ':L' . $Debug['line'];
        $CIDRAM['BlockInfo']['SignatureCount']++;
    }
    return true;
};

/**
 * Checks whether an IP is expected. If so, tracking is disabled for the IP
 * being checked, and if not, the request is blocked. Has no return value.
 *
 * @param string|array $Expected IPs expected.
 * @param string $Friendly A friendly name to use in logfiles.
 */
$CIDRAM['UA-IP-Match'] = function ($Expected, $Friendly) use (&$CIDRAM) {
    $CIDRAM['Arrayify']($Expected);
    /** Compare the actual IP of the request against the expected IPs. */
    if (in_array($CIDRAM['BlockInfo']['IPAddr'], $Expected)) {
        /** Disable tracking. */
        $CIDRAM['Trackable'] = false;
    } else {
        /** Block it. */
        $Reason = $CIDRAM['ParseVars'](['ua' => $Friendly], $CIDRAM['lang']['fake_ua']);
        $CIDRAM['BlockInfo']['ReasonMessage'] = $Reason;
        if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
            $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
        }
        $CIDRAM['BlockInfo']['WhyReason'] .= $Reason;
        if (!empty($CIDRAM['BlockInfo']['Signatures'])) {
            $CIDRAM['BlockInfo']['Signatures'] .= ', ';
        }
        $Debug = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        $CIDRAM['BlockInfo']['Signatures'] .= basename($Debug['file']) . ':L' . $Debug['line'];
        $CIDRAM['BlockInfo']['SignatureCount']++;
    }
};

/**
 * A default closure for handling signature triggers within module files.
 *
 * @param bool $Condition Include any variable or PHP code which can be
 *      evaluated for truthiness. Truthiness is evaluated, and if true, the
 *      signature is "triggered". If false, the signature is *not* "triggered".
 * @param string $ReasonShort Cited in the "Why Blocked" field when the
 *      signature is triggered and thus included within logfile entries.
 * @param string $ReasonLong Message displayed to the user/client when blocked,
 *      to explain why they've been blocked. Optional. Defaults to the standard
 *      "Access Denied!" message.
 * @param array $DefineOptions An optional array containing key/value pairs,
 *      used to define configuration options specific to the request instance.
 *      Configuration options will be applied when the signature is triggered.
 * @return bool Returns true if the signature was triggered, and false if it
 *      wasn't. Should correspond to the truthiness of $Condition.
 */
$CIDRAM['Trigger'] = function ($Condition, $ReasonShort, $ReasonLong = '', $DefineOptions = []) use (&$CIDRAM) {
    if (!$Condition) {
        return false;
    }
    if (!$ReasonLong) {
        $ReasonLong = $CIDRAM['lang']['denied'];
    }
    if (is_array($DefineOptions) && !empty($DefineOptions)) {
        foreach ($DefineOptions as $CatKey => $CatValue) {
            if (is_array($CatValue) && !empty($CatValue)) {
                foreach ($CatValue as $OptionKey => $OptionValue) {
                    $CIDRAM['Config'][$CatKey][$OptionKey] = $OptionValue;
                }
            }
        }
    }
    $CIDRAM['BlockInfo']['ReasonMessage'] = $ReasonLong;
    if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
        $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
    }
    $CIDRAM['BlockInfo']['WhyReason'] .= $ReasonShort;
    if (!empty($CIDRAM['BlockInfo']['Signatures'])) {
        $CIDRAM['BlockInfo']['Signatures'] .= ', ';
    }
    $Debug = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
    $CIDRAM['BlockInfo']['Signatures'] .= basename($Debug['file']) . ':L' . $Debug['line'];
    $CIDRAM['BlockInfo']['SignatureCount']++;
    return true;
};

/**
 * A default closure for handling signature bypasses within module files.
 *
 * @param bool $Condition Include any variable or PHP code which can be
 *      evaluated for truthiness. Truthiness is evaluated, and if true, the
 *      bypass is "triggered". If false, the bypass is *not* "triggered".
 * @param string $ReasonShort Cited in the "Why Blocked" field when the
 *      bypass is triggered (included within logfile entries if there are still
 *      other preexisting signatures which have otherwise been triggered).
 * @param array $DefineOptions An optional array containing key/value pairs,
 *      used to define configuration options specific to the request instance.
 *      Configuration options will be applied when the bypass is triggered.
 * @return bool Returns true if the bypass was triggered, and false if it
 *      wasn't. Should correspond to the truthiness of $Condition.
 */
$CIDRAM['Bypass'] = function ($Condition, $ReasonShort, $DefineOptions = []) use (&$CIDRAM) {
    if (!$Condition) {
        return false;
    }
    if (is_array($DefineOptions) && !empty($DefineOptions)) {
        foreach ($DefineOptions as $CatKey => $CatValue) {
            if (is_array($CatValue) && !empty($CatValue)) {
                foreach ($CatValue as $OptionKey => $OptionValue) {
                    $CIDRAM['Config'][$CatKey][$OptionKey] = $OptionValue;
                }
            }
        }
    }
    if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
        $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
    }
    $CIDRAM['BlockInfo']['WhyReason'] .= $ReasonShort;
    if (!empty($CIDRAM['BlockInfo']['Signatures'])) {
        $CIDRAM['BlockInfo']['Signatures'] .= ', ';
    }
    $Debug = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
    $CIDRAM['BlockInfo']['Signatures'] .= basename($Debug['file']) . ':L' . $Debug['line'];
    $CIDRAM['BlockInfo']['SignatureCount']--;
    return true;
};

/**
 * Used to generate new salts when necessary, which may be occasionally used by
 * some specific optional peripheral features (note: should not be considered
 * cryptographically secure; especially so for versions of PHP < 7).
 *
 * @return string Salt.
 */
$CIDRAM['GenerateSalt'] = function () {
    static $MinLen = 32;
    static $MaxLen = 72;
    static $MinChr = 1;
    static $MaxChr = 255;
    $Salt = '';
    if (function_exists('random_int')) {
        try {
            $Length = random_int($MinLen, $MaxLen);
        } catch (\Exception $e) {
            $Length = rand($MinLen, $MaxLen);
        }
    } else {
        $Length = rand($MinLen, $MaxLen);
    }
    if (function_exists('random_bytes')) {
        try {
            $Salt = random_bytes($Length);
        } catch (\Exception $e) {
            $Salt = '';
        }
    }
    if (empty($Salt)) {
        if (function_exists('random_int')) {
            try {
                for ($Index = 0; $Index < $Length; $Index++) {
                    $Salt .= chr(random_int($MinChr, $MaxChr));
                }
            } catch (\Exception $e) {
                $Salt = '';
                for ($Index = 0; $Index < $Length; $Index++) {
                    $Salt .= chr(rand($MinChr, $MaxChr));
                }
            }
        } else {
            for ($Index = 0; $Index < $Length; $Index++) {
                $Salt .= chr(rand($MinChr, $MaxChr));
            }
        }
    }
    return $Salt;
};

/**
 * Meld together two or more strings by padding to equal length and
 * bitshifting each by each other.
 *
 * @return string The melded string.
 */
$CIDRAM['Meld'] = function () {
    $Strings = func_get_args();
    $StrLens = array_map('strlen', $Strings);
    $WalkLen = max($StrLens);
    $Count = count($Strings);
    for ($Index = 0; $Index < $Count; $Index++) {
        if ($StrLens[$Index] < $WalkLen) {
            $Strings[$Index] = str_pad($Strings[$Index], $WalkLen, "\xff");
        }
    }
    for ($Lt = $Strings[0], $Index = 1, $Meld = ''; $Index < $Count; $Index++, $Meld = '') {
        $Rt = $Strings[$Index];
        for ($Caret = 0; $Caret < $WalkLen; $Caret++) {
            $Meld .= $Lt[$Caret] ^ $Rt[$Caret];
        }
        $Lt = $Meld;
    }
    $Meld = $Lt;
    return $Meld;
};

/**
 * Clears expired entries from sections of the "cache.dat" file and clears
 * empty sections.
 */
$CIDRAM['ClearFromCache'] = function ($Section) use (&$CIDRAM) {
    if (isset($CIDRAM['Cache'][$Section])) {
        foreach ($CIDRAM['Cache'][$Section] as $Key => $Value) {
            if ($Value['Time'] < $CIDRAM['Now']) {
                unset($CIDRAM['Cache'][$Section][$Key]);
                $CIDRAM['CacheModified'] = true;
            }
        }
        if (!count($CIDRAM['Cache'][$Section])) {
            unset($CIDRAM['Cache'][$Section]);
            $CIDRAM['CacheModified'] = true;
        }
    }
};

/** Clears expired entries from a list. */
$CIDRAM['ClearExpired'] = function (&$List, &$Check) use (&$CIDRAM) {
    if ($List) {
        $End = 0;
        while (true) {
            $Begin = $End;
            if (!$End = strpos($List, "\n", $Begin + 1)) {
                break;
            }
            $Line = substr($List, $Begin, $End - $Begin);
            if ($Split = strrpos($Line, ',')) {
                $Expiry = (int)substr($Line, $Split + 1);
                if ($Expiry < $CIDRAM['Now']) {
                    $List = str_replace($Line, '', $List);
                    $End = 0;
                    $Check = true;
                }
            }
        }
    }
};

/** If input isn't an array, make it so. Remove empty elements. */
$CIDRAM['Arrayify'] = function (&$Input) {
    if (!is_array($Input)) {
        $Input = [$Input];
    }
    $Input = array_filter($Input);
};

/**
 * Read byte value configuration directives as byte values.
 *
 * @param string $In Input.
 * @param int $Mode Operating mode. 0 for true byte values, 1 for validating.
 *      Default is 0.
 * @return string|int Output (depends on operating mode).
 */
$CIDRAM['ReadBytes'] = function ($In, $Mode = 0) {
    if (preg_match('/[KMGT][oB]$/i', $In)) {
        $Unit = substr($In, -2, 1);
    } elseif (preg_match('/[KMGToB]$/i', $In)) {
        $Unit = substr($In, -1);
    }
    $Unit = isset($Unit) ? strtoupper($Unit) : 'K';
    $In = (real)$In;
    if ($Mode === 1) {
        return $Unit === 'B' || $Unit === 'o' ? $In . 'B' : $In . $Unit . 'B';
    }
    $Multiply = ['K' => 1024, 'M' => 1048576, 'G' => 1073741824, 'T' => 1099511627776];
    return (int)floor($In * (isset($Multiply[$Unit]) ? $Multiply[$Unit] : 1));
};

/**
 * Add to page output and block event logfile fields.
 *
 * @param string $FieldName Name of the field (generally, the L10N label).
 * @param string $FieldName Data for the field.
 */
$CIDRAM['AddField'] = function ($FieldName, $FieldData) use (&$CIDRAM) {
    $CIDRAM['FieldTemplates']['Logs'] .= $FieldName . $FieldData . "\n";
    $CIDRAM['FieldTemplates']['Output'][] = '<span class="textLabel">' . $FieldName . '</span>' . $FieldData . "<br />";
};

/**
 * Resolves an 6to4 IPv6 address to its IPv4 address counterpart.
 *
 * @param string $In An IPv6 address.
 * @return string An IPv4 address.
 */
$CIDRAM['Resolve6to4'] = function ($In) {
    $Parts = explode(':', substr($In, 5), 8);
    if (count($Parts) < 2 || preg_match('~[^0-9a-f]~i', $Parts[0]) || preg_match('~[^0-9a-f]~i', $Parts[1])) {
        return '';
    }
    $Parts[0] = hexdec($Parts[0]) ?: 0;
    $Parts[1] = hexdec($Parts[1]) ?: 0;
    $Octets = [0 => floor($Parts[0] / 256), 1 => 0, 2 => floor($Parts[1] / 256), 3 => 0];
    $Octets[1] = $Parts[0] - ($Octets[0] * 256);
    $Octets[3] = $Parts[1] - ($Octets[2] * 256);
    return implode('.', $Octets);
};

/** Initialise cache. */
$CIDRAM['InitialiseCache'] = function () use (&$CIDRAM) {
    $CIDRAM['CacheModified'] = false;

    /** Prepare the cache. */
    if (!file_exists($CIDRAM['Vault'] . 'cache.dat')) {
        $Handle = fopen($CIDRAM['Vault'] . 'cache.dat', 'w');
        $CIDRAM['Cache'] = ['Counter' => 0];
        fwrite($Handle, serialize($CIDRAM['Cache']));
        fclose($Handle);
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
};
