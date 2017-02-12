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
 * This file: Functions file (last modified: 2017.02.11).
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
        $Haystack = str_replace('{' . $Key . '}', $Value, $Haystack);
    });
    return $Haystack;
};

/**
 * Fetches instructions from the `ignore.dat` file.
 *
 * @return bool Which sections should be ignored by CIDRAM.
 */
$CIDRAM['FetchIgnores'] = function () use (&$CIDRAM) {
    $IgnoreMe = array();
    $IgnoreFile = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'ignore.dat');
    if (strpos($IgnoreFile, "\r")) {
        $IgnoreFile =
            (strpos($IgnoreFile, "\r\n")) ?
            str_replace("\r", '', $IgnoreFile) :
            str_replace("\r", "\n", $IgnoreFile);
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
    }
    return $IgnoreMe;
};

/**
 * Tests whether $Addr is an IPv4 address, and if it is, expands its potential
 * factors (ie, constructs an array containing the CIDRs that contain $Addr).
 * Returns false if $Addr is *not* an IPv4 address, and otherwise, returns the
 * contructed array.
 *
 * @param string $Addr Refer to the description above.
 * @return bool|array Refer to the description above.
 */
$CIDRAM['ExpandIPv4'] = function ($Addr) use (&$CIDRAM) {
    if (!preg_match(
        '/^([01]?[0-9]{1,2}|2[0-4][0-9]|25[0-5])\.([01]?[0-9]{1,2}|2[0-4][0-' .
        '9]|25[0-5])\.([01]?[0-9]{1,2}|2[0-4][0-9]|25[0-5])\.([01]?[0-9]{1,2' .
        '}|2[0-4][0-9]|25[0-5])$/i',
    $Addr, $Octets)) {
        return false;
    }
    $CIDRs = array(0 => ($Octets[1] < 128) ? '0.0.0.0/1' : '128.0.0.0/1');
    $CIDRs[1] = (floor($Octets[1] / 64) * 64) . '.0.0.0/2';
    $CIDRs[2] = (floor($Octets[1] / 32) * 32) . '.0.0.0/3';
    $CIDRs[3] = (floor($Octets[1] / 16) * 16) . '.0.0.0/4';
    $CIDRs[4] = (floor($Octets[1] / 8) * 8) . '.0.0.0/5';
    $CIDRs[5] = (floor($Octets[1] / 4) * 4) . '.0.0.0/6';
    $CIDRs[6] = (floor($Octets[1] / 2) * 2) . '.0.0.0/7';
    $CIDRs[7] = $Octets[1] . '.0.0.0/8';
    $CIDRs[8] = $Octets[1] . '.' . (($Octets[2] < 128) ? '0' : '128') . '.0.0/9';
    $CIDRs[9] = $Octets[1] . '.' . (floor($Octets[2] / 64) * 64) . '.0.0/10';
    $CIDRs[10] = $Octets[1] . '.' . (floor($Octets[2] / 32) * 32) . '.0.0/11';
    $CIDRs[11] = $Octets[1] . '.' . (floor($Octets[2] / 16) * 16) . '.0.0/12';
    $CIDRs[12] = $Octets[1] . '.' . (floor($Octets[2] / 8) * 8) . '.0.0/13';
    $CIDRs[13] = $Octets[1] . '.' . (floor($Octets[2] / 4) * 4) . '.0.0/14';
    $CIDRs[14] = $Octets[1] . '.' . (floor($Octets[2] / 2) * 2) . '.0.0/15';
    $CIDRs[15] = $Octets[1] . '.' . $Octets[2] . '.0.0/16';
    $CIDRs[16] = $Octets[1] . '.' . $Octets[2] . '.' . (($Octets[3] < 128) ? '0' : '128') . '.0/17';
    $CIDRs[17] = $Octets[1] . '.' . $Octets[2] . '.' . (floor($Octets[3] / 64) * 64) . '.0/18';
    $CIDRs[18] = $Octets[1] . '.' . $Octets[2] . '.' . (floor($Octets[3] / 32) * 32) . '.0/19';
    $CIDRs[19] = $Octets[1] . '.' . $Octets[2] . '.' . (floor($Octets[3] / 16) * 16) . '.0/20';
    $CIDRs[20] = $Octets[1] . '.' . $Octets[2] . '.' . (floor($Octets[3] / 8) * 8) . '.0/21';
    $CIDRs[21] = $Octets[1] . '.' . $Octets[2] . '.' . (floor($Octets[3] / 4) * 4) . '.0/22';
    $CIDRs[22] = $Octets[1] . '.' . $Octets[2] . '.' . (floor($Octets[3] / 2) * 2) . '.0/23';
    $CIDRs[23] = $Octets[1] . '.' . $Octets[2] . '.' . $Octets[3] . '.0/24';
    $CIDRs[24] = $Octets[1] . '.' . $Octets[2] . '.' . $Octets[3] . '.' . (($Octets[4] < 128) ? '0' : '128') . '/25';
    $CIDRs[25] = $Octets[1] . '.' . $Octets[2] . '.' . $Octets[3] . '.' . (floor($Octets[4] / 64) * 64) . '/26';
    $CIDRs[26] = $Octets[1] . '.' . $Octets[2] . '.' . $Octets[3] . '.' . (floor($Octets[4] / 32) * 32) . '/27';
    $CIDRs[27] = $Octets[1] . '.' . $Octets[2] . '.' . $Octets[3] . '.' . (floor($Octets[4] / 16) * 16) . '/28';
    $CIDRs[28] = $Octets[1] . '.' . $Octets[2] . '.' . $Octets[3] . '.' . (floor($Octets[4] / 8) * 8) . '/29';
    $CIDRs[29] = $Octets[1] . '.' . $Octets[2] . '.' . $Octets[3] . '.' . (floor($Octets[4] / 4) * 4) . '/30';
    $CIDRs[30] = $Octets[1] . '.' . $Octets[2] . '.' . $Octets[3] . '.' . (floor($Octets[4] / 2) * 2) . '/31';
    $CIDRs[31] = $Octets[1] . '.' . $Octets[2] . '.' . $Octets[3] . '.' . $Octets[4] . '/32';
    return $CIDRs;
};

/**
 * Tests whether $Addr is an IPv6 address, and if it is, expands its potential
 * factors (ie, constructs an array containing the CIDRs that contain $Addr).
 * Returns false if $Addr is *not* an IPv6 address, and otherwise, returns the
 * contructed array.
 *
 * @param string $Addr Refer to the description above.
 * @return bool|array Refer to the description above.
 */
$CIDRAM['ExpandIPv6'] = function ($Addr) use (&$CIDRAM) {
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
    $NAddr = $Addr;
    if (preg_match('/^\:\:/i', $NAddr)) {
        $NAddr = '0' . $NAddr;
    }
    if (preg_match('/\:\:$/i', $NAddr)) {
        $NAddr .= '0';
    }
    if (substr_count($NAddr, '::')) {
        $c = 7 - substr_count($Addr, ':');
        $Arr = array(':0:', ':0:0:', ':0:0:0:', ':0:0:0:0:', ':0:0:0:0:0:', ':0:0:0:0:0:0:');
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
    $NAddr[0] = hexdec($NAddr[0]);
    $NAddr[1] = hexdec($NAddr[1]);
    $NAddr[2] = hexdec($NAddr[2]);
    $NAddr[3] = hexdec($NAddr[3]);
    $NAddr[4] = hexdec($NAddr[4]);
    $NAddr[5] = hexdec($NAddr[5]);
    $NAddr[6] = hexdec($NAddr[6]);
    $NAddr[7] = hexdec($NAddr[7]);
    $CIDRs = array(0 => ($NAddr[0] < 32768) ? '0::/1' : '8000::/1');
    $CIDRs[1] = dechex(floor($NAddr[0] / 16384) * 16384) . '::/2';
    $CIDRs[2] = dechex(floor($NAddr[0] / 8192) * 8192) . '::/3';
    $CIDRs[3] = dechex(floor($NAddr[0] / 4096) * 4096) . '::/4';
    $CIDRs[4] = dechex(floor($NAddr[0] / 2048) * 2048) . '::/5';
    $CIDRs[5] = dechex(floor($NAddr[0] / 1024) * 1024) . '::/6';
    $CIDRs[6] = dechex(floor($NAddr[0] / 512) * 512) . '::/7';
    $CIDRs[7] = dechex(floor($NAddr[0] / 256) * 256) . '::/8';
    $CIDRs[8] = dechex(floor($NAddr[0] / 128) * 128) . '::/9';
    $CIDRs[9] = dechex(floor($NAddr[0] / 64) * 64) . '::/10';
    $CIDRs[10] = dechex(floor($NAddr[0] / 32) * 32) . '::/11';
    $CIDRs[11] = dechex(floor($NAddr[0] / 16) * 16) . '::/12';
    $CIDRs[12] = dechex(floor($NAddr[0] / 8) * 8) . '::/13';
    $CIDRs[13] = dechex(floor($NAddr[0] / 4) * 4) . '::/14';
    $CIDRs[14] = dechex(floor($NAddr[0] / 2) * 2) . '::/15';
    $NAddr[0] = dechex($NAddr[0]);
    $CIDRs[15] = $NAddr[0] . '::/16';
    $CIDRs[16] = ($NAddr[1] < 32768) ? $NAddr[0] . '::/17' : $NAddr[0] . ':8000::/17';
    $CIDRs[17] = $NAddr[0] . ':' . dechex(floor($NAddr[1] / 16384) * 16384) . '::/18';
    $CIDRs[18] = $NAddr[0] . ':' . dechex(floor($NAddr[1] / 8192) * 8192) . '::/19';
    $CIDRs[19] = $NAddr[0] . ':' . dechex(floor($NAddr[1] / 4096) * 4096) . '::/20';
    $CIDRs[20] = $NAddr[0] . ':' . dechex(floor($NAddr[1] / 2048) * 2048) . '::/21';
    $CIDRs[21] = $NAddr[0] . ':' . dechex(floor($NAddr[1] / 1024) * 1024) . '::/22';
    $CIDRs[22] = $NAddr[0] . ':' . dechex(floor($NAddr[1] / 512) * 512) . '::/23';
    $CIDRs[23] = $NAddr[0] . ':' . dechex(floor($NAddr[1] / 256) * 256) . '::/24';
    $CIDRs[24] = $NAddr[0] . ':' . dechex(floor($NAddr[1] / 128) * 128) . '::/25';
    $CIDRs[25] = $NAddr[0] . ':' . dechex(floor($NAddr[1] / 64) * 64) . '::/26';
    $CIDRs[26] = $NAddr[0] . ':' . dechex(floor($NAddr[1] / 32) * 32) . '::/27';
    $CIDRs[27] = $NAddr[0] . ':' . dechex(floor($NAddr[1] / 16) * 16) . '::/28';
    $CIDRs[28] = $NAddr[0] . ':' . dechex(floor($NAddr[1] / 8) * 8) . '::/29';
    $CIDRs[29] = $NAddr[0] . ':' . dechex(floor($NAddr[1] / 4) * 4) . '::/30';
    $CIDRs[30] = $NAddr[0] . ':' . dechex(floor($NAddr[1] / 2) * 2) . '::/31';
    $NAddr[1] = dechex($NAddr[1]);
    $CIDRs[31] = $NAddr[0] . ':' . $NAddr[1] . '::/32';
    $CIDRs[32] = ($NAddr[2] < 32768) ?
        $NAddr[0] . ':' . $NAddr[1] . '::/33' :
        $NAddr[0] . ':' . $NAddr[1] . ':8000::/33';
    $CIDRs[33] = $NAddr[0] . ':' . $NAddr[1] . ':' . dechex(floor($NAddr[2] / 16384) * 16384) . '::/34';
    $CIDRs[34] = $NAddr[0] . ':' . $NAddr[1] . ':' . dechex(floor($NAddr[2] / 8192) * 8192) . '::/35';
    $CIDRs[35] = $NAddr[0] . ':' . $NAddr[1] . ':' . dechex(floor($NAddr[2] / 4096) * 4096) . '::/36';
    $CIDRs[36] = $NAddr[0] . ':' . $NAddr[1] . ':' . dechex(floor($NAddr[2] / 2048) * 2048) . '::/37';
    $CIDRs[37] = $NAddr[0] . ':' . $NAddr[1] . ':' . dechex(floor($NAddr[2] / 1024) * 1024) . '::/38';
    $CIDRs[38] = $NAddr[0] . ':' . $NAddr[1] . ':' . dechex(floor($NAddr[2] / 512) * 512) . '::/39';
    $CIDRs[39] = $NAddr[0] . ':' . $NAddr[1] . ':' . dechex(floor($NAddr[2] / 256) * 256) . '::/40';
    $CIDRs[40] = $NAddr[0] . ':' . $NAddr[1] . ':' . dechex(floor($NAddr[2] / 128) * 128) . '::/41';
    $CIDRs[41] = $NAddr[0] . ':' . $NAddr[1] . ':' . dechex(floor($NAddr[2] / 64) * 64) . '::/42';
    $CIDRs[42] = $NAddr[0] . ':' . $NAddr[1] . ':' . dechex(floor($NAddr[2] / 32) * 32) . '::/43';
    $CIDRs[43] = $NAddr[0] . ':' . $NAddr[1] . ':' . dechex(floor($NAddr[2] / 16) * 16) . '::/44';
    $CIDRs[44] = $NAddr[0] . ':' . $NAddr[1] . ':' . dechex(floor($NAddr[2] / 8) * 8) . '::/45';
    $CIDRs[45] = $NAddr[0] . ':' . $NAddr[1] . ':' . dechex(floor($NAddr[2] / 4) * 4) . '::/46';
    $CIDRs[46] = $NAddr[0] . ':' . $NAddr[1] . ':' . dechex(floor($NAddr[2] / 2) * 2) . '::/47';
    $NAddr[2] = dechex($NAddr[2]);
    $CIDRs[47] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . '::/48';
    $CIDRs[48] = ($NAddr[3] < 32768) ?
        $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . '::/49' :
        $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':8000::/49';
    $CIDRs[49] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . dechex(floor($NAddr[3] / 16384) * 16384) . '::/50';
    $CIDRs[50] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . dechex(floor($NAddr[3] / 8192) * 8192) . '::/51';
    $CIDRs[51] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . dechex(floor($NAddr[3] / 4096) * 4096) . '::/52';
    $CIDRs[52] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . dechex(floor($NAddr[3] / 2048) * 2048) . '::/53';
    $CIDRs[53] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . dechex(floor($NAddr[3] / 1024) * 1024) . '::/54';
    $CIDRs[54] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . dechex(floor($NAddr[3] / 512) * 512) . '::/55';
    $CIDRs[55] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . dechex(floor($NAddr[3] / 256) * 256) . '::/56';
    $CIDRs[56] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . dechex(floor($NAddr[3] / 128) * 128) . '::/57';
    $CIDRs[57] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . dechex(floor($NAddr[3] / 64) * 64) . '::/58';
    $CIDRs[58] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . dechex(floor($NAddr[3] / 32) * 32) . '::/59';
    $CIDRs[59] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . dechex(floor($NAddr[3] / 16) * 16) . '::/60';
    $CIDRs[60] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . dechex(floor($NAddr[3] / 8) * 8) . '::/61';
    $CIDRs[61] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . dechex(floor($NAddr[3] / 4) * 4) . '::/62';
    $CIDRs[62] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . dechex(floor($NAddr[3] / 2) * 2) . '::/63';
    $NAddr[3] = dechex($NAddr[3]);
    $CIDRs[63] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . '::/64';
    $CIDRs[64] = ($NAddr[4] < 32768) ?
        $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . '::/65' :
        $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':8000::/65';
    $CIDRs[65] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . dechex(floor($NAddr[4] / 16384) * 16384) . '::/66';
    $CIDRs[66] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . dechex(floor($NAddr[4] / 8192) * 8192) . '::/67';
    $CIDRs[67] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . dechex(floor($NAddr[4] / 4096) * 4096) . '::/68';
    $CIDRs[68] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . dechex(floor($NAddr[4] / 2048) * 2048) . '::/69';
    $CIDRs[69] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . dechex(floor($NAddr[4] / 1024) * 1024) . '::/70';
    $CIDRs[70] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . dechex(floor($NAddr[4] / 512) * 512) . '::/71';
    $CIDRs[71] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . dechex(floor($NAddr[4] / 256) * 256) . '::/72';
    $CIDRs[72] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . dechex(floor($NAddr[4] / 128) * 128) . '::/73';
    $CIDRs[73] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . dechex(floor($NAddr[4] / 64) * 64) . '::/74';
    $CIDRs[74] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . dechex(floor($NAddr[4] / 32) * 32) . '::/75';
    $CIDRs[75] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . dechex(floor($NAddr[4] / 16) * 16) . '::/76';
    $CIDRs[76] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . dechex(floor($NAddr[4] / 8) * 8) . '::/77';
    $CIDRs[77] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . dechex(floor($NAddr[4] / 4) * 4) . '::/78';
    $CIDRs[78] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . dechex(floor($NAddr[4] / 2) * 2) . '::/79';
    $NAddr[4] = dechex($NAddr[4]);
    $CIDRs[79] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . '::/80';
    $CIDRs[80] = ($NAddr[5] < 32768) ?
        $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . '::/81' :
        $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':8000::/81';
    $CIDRs[81] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . dechex(floor($NAddr[5] / 16384) * 16384) . '::/82';
    $CIDRs[82] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . dechex(floor($NAddr[5] / 8192) * 8192) . '::/83';
    $CIDRs[83] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . dechex(floor($NAddr[5] / 4096) * 4096) . '::/84';
    $CIDRs[84] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . dechex(floor($NAddr[5] / 2048) * 2048) . '::/85';
    $CIDRs[85] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . dechex(floor($NAddr[5] / 1024) * 1024) . '::/86';
    $CIDRs[86] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . dechex(floor($NAddr[5] / 512) * 512) . '::/87';
    $CIDRs[87] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . dechex(floor($NAddr[5] / 256) * 256) . '::/88';
    $CIDRs[88] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . dechex(floor($NAddr[5] / 128) * 128) . '::/89';
    $CIDRs[89] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . dechex(floor($NAddr[5] / 64) * 64) . '::/90';
    $CIDRs[90] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . dechex(floor($NAddr[5] / 32) * 32) . '::/91';
    $CIDRs[91] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . dechex(floor($NAddr[5] / 16) * 16) . '::/92';
    $CIDRs[92] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . dechex(floor($NAddr[5] / 8) * 8) . '::/93';
    $CIDRs[93] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . dechex(floor($NAddr[5] / 4) * 4) . '::/94';
    $CIDRs[94] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . dechex(floor($NAddr[5] / 2) * 2) . '::/95';
    $NAddr[5] = dechex($NAddr[5]);
    $CIDRs[95] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . '::/96';
    $CIDRs[96] = ($NAddr[6] < 32768) ?
        $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . '::/97' :
        $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':8000:0/97';
    $CIDRs[97] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . dechex(floor($NAddr[6] / 16384) * 16384) . ':0/98';
    $CIDRs[98] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . dechex(floor($NAddr[6] / 8192) * 8192) . ':0/99';
    $CIDRs[99] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . dechex(floor($NAddr[6] / 4096) * 4096) . ':0/100';
    $CIDRs[100] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . dechex(floor($NAddr[6] / 2048) * 2048) . ':0/101';
    $CIDRs[101] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . dechex(floor($NAddr[6] / 1024) * 1024) . ':0/102';
    $CIDRs[102] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . dechex(floor($NAddr[6] / 512) * 512) . ':0/103';
    $CIDRs[103] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . dechex(floor($NAddr[6] / 256) * 256) . ':0/104';
    $CIDRs[104] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . dechex(floor($NAddr[6] / 128) * 128) . ':0/105';
    $CIDRs[105] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . dechex(floor($NAddr[6] / 64) * 64) . ':0/106';
    $CIDRs[106] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . dechex(floor($NAddr[6] / 32) * 32) . ':0/107';
    $CIDRs[107] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . dechex(floor($NAddr[6] / 16) * 16) . ':0/108';
    $CIDRs[108] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . dechex(floor($NAddr[6] / 8) * 8) . ':0/109';
    $CIDRs[109] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . dechex(floor($NAddr[6] / 4) * 4) . ':0/110';
    $CIDRs[110] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . dechex(floor($NAddr[6] / 2) * 2) . ':0/111';
    $NAddr[6] = dechex($NAddr[6]);
    $CIDRs[111] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':0/112';
    $CIDRs[112] = ($NAddr[7] < 32768) ?
        $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':0/113' :
        $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':8000/113';
    $CIDRs[113] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':' . dechex(floor($NAddr[7] / 16384) * 16384) . '/114';
    $CIDRs[114] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':' . dechex(floor($NAddr[7] / 8192) * 8192) . '/115';
    $CIDRs[115] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':' . dechex(floor($NAddr[7] / 4096) * 4096) . '/116';
    $CIDRs[116] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':' . dechex(floor($NAddr[7] / 2048) * 2048) . '/117';
    $CIDRs[117] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':' . dechex(floor($NAddr[7] / 1024) * 1024) . '/118';
    $CIDRs[118] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':' . dechex(floor($NAddr[7] / 512) * 512) . '/119';
    $CIDRs[119] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':' . dechex(floor($NAddr[7] / 256) * 256) . '/120';
    $CIDRs[120] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':' . dechex(floor($NAddr[7] / 128) * 128) . '/121';
    $CIDRs[121] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':' . dechex(floor($NAddr[7] / 64) * 64) . '/122';
    $CIDRs[122] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':' . dechex(floor($NAddr[7] / 32) * 32) . '/123';
    $CIDRs[123] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':' . dechex(floor($NAddr[7] / 16) * 16) . '/124';
    $CIDRs[124] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':' . dechex(floor($NAddr[7] / 8) * 8) . '/125';
    $CIDRs[125] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':' . dechex(floor($NAddr[7] / 4) * 4) . '/126';
    $CIDRs[126] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':' . dechex(floor($NAddr[7] / 2) * 2) . '/127';
    $NAddr[7] = dechex($NAddr[7]);
    $CIDRs[127] = $NAddr[0] . ':' . $NAddr[1] . ':' . $NAddr[2] . ':' . $NAddr[3] . ':' . $NAddr[4] . ':' . $NAddr[5] . ':' . $NAddr[6] . ':' . $NAddr[7] . '/128';
    for ($i = 0; $i < 128; $i++) {
        if (strpos($CIDRs[$i], '::') !== false) {
            $CIDRs[$i] = preg_replace('/(\:0)*\:\:(0\:)*/i', '::', $CIDRs[$i], 1);
            $CIDRs[$i] = str_replace('::0/', '::/', $CIDRs[$i]);
            continue;
        }
        if (strpos($CIDRs[$i], ':0:0/') !== false) {
            $CIDRs[$i] = preg_replace('/(\:0){2,}\//i', '::/', $CIDRs[$i], 1);
            continue;
        }
        if (strpos($CIDRs[$i], ':0:0:') !== false) {
            $CIDRs[$i] = preg_replace('/(\:0)+\:(0\:)+/i', '::', $CIDRs[$i], 1);
            $CIDRs[$i] = str_replace('::0/', '::/', $CIDRs[$i]);
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
    $Counts = array(
        'Files' => count($Files),
        'Factors' => count($Factors)
    );
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
        if (!$Files[$FileIndex] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $Files[$FileIndex])) {
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
                if (isset($CIDRAM['Ignore'][$Tag]) && $CIDRAM['Ignore'][$Tag]) {
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
                    if (file_exists($CIDRAM['Vault'] . $Signature)) {
                        require_once $CIDRAM['Vault'] . $Signature;
                    } else {
                        throw new \Exception($CIDRAM['ParseVars'](
                            array('FileName' => $Signature),
                            '[CIDRAM] ' . $CIDRAM['lang']['Error_MissingRequire']
                        ));
                    }
                } elseif ($Category === 'Whitelist') {
                    $CIDRAM['BlockInfo']['Signatures'] = $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['BlockInfo']['WhyReason'] = '';
                    $CIDRAM['BlockInfo']['SignatureCount'] = 0;
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
    $CIDRAM['Ignore'] = $CIDRAM['FetchIgnores']();
    if ($IPv4Factors = $CIDRAM['ExpandIPv4']($Addr)) {
        if (empty($CIDRAM['Config']['signatures']['ipv4'])) {
            $IPv4Files = array();
        } elseif (strpos($CIDRAM['Config']['signatures']['ipv4'], ',') !== false) {
            $IPv4Files = explode(',', $CIDRAM['Config']['signatures']['ipv4']);
        } else {
            $IPv4Files = array($CIDRAM['Config']['signatures']['ipv4']);
        }
        try {
            $IPv4Test = $CIDRAM['CheckFactors']($IPv4Files, $IPv4Factors);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    } else {
        $IPv4Test = false;
    }
    if ($IPv6Factors = $CIDRAM['ExpandIPv6']($Addr)) {
        if (empty($CIDRAM['Config']['signatures']['ipv6'])) {
            $IPv6Files = array();
        } elseif (strpos($CIDRAM['Config']['signatures']['ipv6'], ',') !== false) {
            $IPv6Files = explode(',', $CIDRAM['Config']['signatures']['ipv6']);
        } else {
            $IPv6Files = array($CIDRAM['Config']['signatures']['ipv6']);
        }
        try {
            $IPv6Test = $CIDRAM['CheckFactors']($IPv6Files, $IPv6Factors);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    } else {
        $IPv6Test = false;
    }
    if (!$IPv4Test && !$IPv6Test) {
        return false;
    }
    return true;
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
        $Arr = array(
            (int)$Arr[1],
            (isset($Arr[2])) ? (int)$Arr[2] : 1,
            (isset($Arr[3])) ? (int)$Arr[3] : 1,
            (isset($Arr[4])) ? (int)$Arr[4] : 0,
            (isset($Arr[5])) ? (int)$Arr[5] : 0,
            (isset($Arr[6])) ? (int)$Arr[6] : 0
        );
        $Expires = mktime($Arr[3], $Arr[4], $Arr[5], $Arr[1], $Arr[2], $Arr[0]);
        return ($Expires) ? $Expires : false;
    }
    return false;
};

/**
 * A simple closure for replacing date/time placeholders in the logfile
 * directives with corresponding date/time information.
 *
 * @param int $time A unix timestamp.
 * @param string|array $dir A directive entry or an array of directive entries.
 * @return string|array The adjusted directive entry or entries.
 */
$CIDRAM['Time2Logfile'] = function ($time, $dir) use (&$CIDRAM) {
    $time = date('dmYH', $time);
    $values = array(
        'dd' => substr($time, 0, 2),
        'mm' => substr($time, 2, 2),
        'yyyy' => substr($time, 4, 4),
        'yy' => substr($time, 6, 2),
        'hh' => substr($time, 8, 2)
    );
    if (is_array($dir)) {
        return array_map(function ($Item) use (&$values, &$CIDRAM) {
            return $CIDRAM['ParseVars']($values, $Item);
        }, $dir);
    }
    return $CIDRAM['ParseVars']($values, $dir);
};

/**
 * Normalises values defined by the YAML closure.
 *
 * @param string|int|bool The value to be normalised.
 * @param int The length of the value.
 * @param string|int|bool The value to be normalised, lowercased.
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
 * A simplified YAML-like parser. Note: This is intended to be adequately serve
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
        $Arr = array();
    }
    if (!substr_count($In, "\n")) {
        return false;
    }
    $In = str_replace("\r", '', $In);
    $Key = $Value = $SendTo = '';
    $TabLen = $SoL = 0;
    while ($SoL !== false) {
        if  (($EoL = strpos($In, "\n", $SoL)) === false) {
            $ThisLine = substr($In, $SoL);
        } else {
            $ThisLine = substr($In, $SoL, $EoL - $SoL);
        }
        $SoL = ($EoL === false) ? false : $EoL + 1;
        $ThisLine = preg_replace(array("/#.*$/", "/\x20+$/"), '', $ThisLine);
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
            $Arr[$Key] = array();
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
$CIDRAM['AutoType'] = function (&$Var, $Type = '') {
    if ($Type === 'string') {
        $Var = (string)$Var;
    } elseif ($Type === 'int') {
        $Var = (int)$Var;
    } elseif ($Type === 'bool') {
        $Var = (strtolower($Var) !== 'false' && $Var);
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
 * Performs reverse DNS lookups for IPv4 IP addresses, to resolve their
 * hostnames. This is functionally equivalent to the in-built PHP function
 * "gethostbyaddr", but with the added benefits of being able to specify which
 * DNS servers to use for lookups, and of being able to enforce timeout limits,
 * which should help to avoid some of the problems normally associated with
 * using "gethostbyaddr".
 *
 * @param string $Addr The IPv4 IP address to look up.
 * @param string $DNS An optional, comma delimited list of DNS servers to use.
 * @param string $Timeout The timeout limit (optional; defaults to 5 seconds).
 * @return string The hostname on success, or the IP address on failure.
 */
$CIDRAM['DNS-Reverse-IPv4'] = function ($Addr, $DNS = '', $Timeout = 5) use (&$CIDRAM) {
    if (isset($CIDRAM['Cache']['DNS-Reverses'][$Addr]['Host'])) {
        return $CIDRAM['Cache']['DNS-Reverses'][$Addr]['Host'];
    }
    if (!isset($CIDRAM['Cache']['DNS-Reverses'])) {
        $CIDRAM['Cache']['DNS-Reverses'] = array();
    }
    $CIDRAM['Cache']['DNS-Reverses'][$Addr] = array('Host' => '', 'Time' => $CIDRAM['Now'] + 21600);
    $CIDRAM['CacheModified'] = true;

    if (!$DNS) {
        $DNS = $CIDRAM['Config']['general']['default_dns'];
    }
    $DNS = explode(',', $DNS);

    $LeftPad = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT) . "\1\0\0\1\0\0\0\0\0\0";
    if (preg_match(
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
    } else {
        return '';
    }
    foreach ($DNS as $Server) {
        if (!empty($Response)) {
            break;
        }
        $Handle = fsockopen('udp://' . $Server, 53);
        fwrite($Handle, $LeftPad . $Lookup);
        stream_set_timeout($Handle, $Timeout);
        $Response = fread($Handle, 1024);
        fclose($Handle);
    }
    if (empty($Response)) {
        return $CIDRAM['Cache']['DNS-Reverses'][$Addr]['Host'] = $Addr;
    }
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
    return $CIDRAM['Cache']['DNS-Reverses'][$Addr]['Host'] = preg_replace('/[^0-9a-z._~-]/i', '', $Host) ?: $Addr;
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
        $CIDRAM['Cache']['DNS-Forwards'] = array();
    }
    $CIDRAM['Cache']['DNS-Forwards'][$Host] = array('IPAddr' => '', 'Time' => $CIDRAM['Now'] + 21600);
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
 * fake or a legitimate source, it takes no action (ie, doesn't mess with
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
        $CIDRAM['Hostname'] = $CIDRAM['DNS-Reverse-IPv4']($CIDRAM['BlockInfo']['IPAddr']);
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
        $Reason = $CIDRAM['ParseVars'](array('ua' => $Friendly), $CIDRAM['lang']['fake_ua']);
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
        $Reason = $CIDRAM['ParseVars'](array('ua' => $Friendly), $CIDRAM['lang']['fake_ua']);
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
$CIDRAM['Trigger'] = function ($Condition, $ReasonShort, $ReasonLong = '', $DefineOptions = array()) use (&$CIDRAM) {
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
$CIDRAM['Bypass'] = function ($Condition, $ReasonShort, $DefineOptions = array()) use (&$CIDRAM) {
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

/**
 * Clears expired entries from a list.
 */
$CIDRAM['ClearExpired'] = function (&$List, &$Check) use (&$CIDRAM) {
    if ($List) {
        $End = 0;
        while (true) {
            $Begin = $End;
            if ($End = strpos($List, "\n", $Begin + 1)) {
                $Line = substr($List, $Begin, $End - $Begin);
                if ($Split = strrpos($Line, ',')) {
                    $Expiry = (int)substr($Line, $Split + 1);
                    if ($Expiry < $CIDRAM['Now']) {
                        $List = str_replace($Line, '', $List);
                        $End = 0;
                        $Check = true;
                    }
                }
            } else {
                break;
            }
        }
    }
};

/**
 * Adds integer values; Returns zero if the sum total is negative or if any
 * contained values aren't integers, and otherwise, returns the sum total.
 */
$CIDRAM['ZeroMin'] = function () {
    $Values = func_get_args();
    $Count = count($Values);
    $Sum = 0;
    for ($Index = 0; $Index < $Count; $Index++) {
        $ThisValue = (int)$Values[$Index];
        if ($ThisValue !== $Values[$Index]) {
            return 0;
        }
        $Sum += $ThisValue;
    }
    if ($Sum < 0) {
        return 0;
    }
    return $Sum;
};

/** Wrap state message for front-end. */
$CIDRAM['WrapRedText'] = function($Err) {
    return '<div class="txtRd">' . $Err . '<br /><br /></div>';
};

/** Format filesize information. */
$CIDRAM['FormatFilesize'] = function (&$Filesize) use (&$CIDRAM) {
    $Scale = array(
        $CIDRAM['lang']['field_size_bytes'],
        $CIDRAM['lang']['field_size_KB'],
        $CIDRAM['lang']['field_size_MB'],
        $CIDRAM['lang']['field_size_GB'],
        $CIDRAM['lang']['field_size_TB']
    );
    $Iterate = 0;
    $Filesize = (int)$Filesize;
    while ($Filesize > 1024) {
        $Filesize = $Filesize / 1024;
        $Iterate++;
        if ($Iterate > 4) {
            break;
        }
    }
    $Filesize = number_format($Filesize, ($Iterate === 0) ? 0 : 2) . ' ' . $Scale[$Iterate];
};

$CIDRAM['FECacheRemove'] = function (&$Source, &$Rebuild, $Entry) {
    $Entry64 = base64_encode($Entry);
    while (($EntryPos = strpos($Source, "\n" . $Entry64 . ',')) !== false) {
        $EoL = strpos($Source, "\n", $EntryPos + 1);
        if ($EoL !== false) {
            $Line = substr($Source, $EntryPos, $EoL - $EntryPos);
            $Source = str_replace($Line, '', $Source);
            $Rebuild = true;
        }
    }
};

$CIDRAM['FECacheAdd'] = function (&$Source, &$Rebuild, $Entry, $Data, $Expires) use (&$CIDRAM) {
    $CIDRAM['FECacheRemove']($Source, $Rebuild, $Entry);
    $Expires = (int)$Expires;
    $NewLine = base64_encode($Entry) . ',' . base64_encode($Data) . ',' . $Expires . "\n";
    $Source .= $NewLine;
    $Rebuild = true;
};

$CIDRAM['FECacheGet'] = function ($Source, $Entry) {
    $Entry = base64_encode($Entry);
    $EntryPos = strpos($Source, "\n" . $Entry . ',');
    if ($EntryPos !== false) {
        $EoL = strpos($Source, "\n", $EntryPos + 1);
        if ($EoL !== false) {
            $Line = substr($Source, $EntryPos, $EoL - $EntryPos);
            $Entry = explode(',', $Line);
            if (!empty($Entry[1])) {
                return base64_decode($Entry[1]);
            }
        }
    }
    return false;
};

/**
 * Compare two different versions of CIDRAM, or two different versions of a
 * component for CIDRAM, to see which is newer (used by the updater).
 *
 * @param string $A The 1st version string.
 * @param string $B The 2nd version string.
 * return bool True if the 2nd version is newer than the 1st version, and false
 *      otherwise (ie, if they're the same, or if the 1st version is newer).
 */
$CIDRAM['VersionCompare'] = function ($A, $B) {
    $Normalise = function (&$Ver) {
        $Ver =
            preg_match("\x01" . '^v?([0-9]+)$' . "\x01i", $Ver, $Matches) ?:
            preg_match("\x01" . '^v?([0-9]+)\.([0-9]+)$' . "\x01i", $Ver, $Matches) ?:
            preg_match("\x01" . '^v?([0-9]+)\.([0-9]+)\.([0-9]+)(-[0-9a-z_+\\/]+)?$' . "\x01i", $Ver, $Matches) ?:
            preg_match("\x01" . '^([0-9]{1,4})[.-]([0-9]{1,2})[.-]([0-9]{1,4})([.+-][0-9a-z_+\\/]+)?$' . "\x01i", $Ver, $Matches) ?:
            preg_match("\x01" . '^([a-z]+)-([0-9a-z]+)-([0-9a-z]+)$' . "\x01i", $Ver, $Matches);
        $Ver = array(
            'Major' => isset($Matches[1]) ? $Matches[1] : 0,
            'Minor' => isset($Matches[2]) ? $Matches[2] : 0,
            'Patch' => isset($Matches[3]) ? $Matches[3] : 0,
            'Build' => isset($Matches[4]) ? substr($Matches[4], 1) : 0
        );
        $Ver = array_map(function ($Var) {
            $VarInt = (int)$Var;
            $VarLen = strlen($Var);
            if ($Var == $VarInt && strlen($VarInt) === $VarLen && $VarLen > 1) {
                return $VarInt;
            }
            return strtolower($Var);
        }, $Ver);
    };
    $Normalise($A);
    $Normalise($B);
    return (
        $B['Major'] > $A['Major'] || (
            $B['Major'] === $A['Major'] &&
            $B['Minor'] > $A['Minor']
        ) || (
            $B['Major'] === $A['Major'] &&
            $B['Minor'] === $A['Minor'] &&
            $B['Patch'] > $A['Patch']
        ) || (
            $B['Major'] === $A['Major'] &&
            $B['Minor'] === $A['Minor'] &&
            $B['Patch'] === $A['Patch'] &&
            !empty($A['Build']) && (
                empty($B['Build']) || $B['Build'] > $A['Build']
            )
        )
    );
};

/**
 * Remove sub-arrays from an array.
 *
 * @param array $Arr An array.
 * return array An array.
 */
$CIDRAM['ArrayFlatten'] = function ($Arr) {
    return array_filter($Arr, function () {
        return (!is_array(func_get_args()[0]));
    });
};

/** Isolate a L10N array down to a single relevant L10N string. */
$CIDRAM['IsolateL10N'] = function (&$Arr, $Lang) {
    if (isset($Arr[$Lang])) {
        $Arr = $Arr[$Lang];
    } elseif (isset($Arr['en'])) {
        $Arr = $Arr['en'];
    } else {
        $Key = key($Arr);
        $Arr = $Arr[$Key];
    }
};

/**
 * Append one or two values to a string, depending on whether that string is
 * empty prior to calling the closure (allows cleaner code in some areas).
 *
 * @param string $String The string to work with.
 * @param string $Delimit Appended first, if the string is not empty.
 * @param string $Append Appended second, and always (empty or otherwise).
 */
$CIDRAM['AppendToString'] = function (&$String, $Delimit = '', $Append = '') {
    if (!empty($String)) {
        $String .= $Delimit;
    }
    $String .= $Append;
};

/** If input isn't an array, make it so. Remove empty elements. */
$CIDRAM['Arrayify'] = function (&$Input) {
    if (!is_array($Input)) {
        $Input = array($Input);
    }
    $Input = array_filter($Input);
};

/**
 * Used by the File Manager to generate a list of the files contained in a
 * working directory (normally, the vault).
 *
 * @param string $Base The path to the working directory.
 * @return array A list of the files contained in the working directory.
 */
$CIDRAM['FileManager-RecursiveList'] = function ($Base) use (&$CIDRAM) {
    $Arr = array();
    $Key = -1;
    $Offset = strlen($Base);
    $List = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($Base), RecursiveIteratorIterator::SELF_FIRST);
    foreach ($List as $Item => $List) {
        $Key++;
        $ThisName = substr($Item, $Offset);
        if (preg_match("\x01" . '^(?:/\.\.|./\.|\.{3})$' . "\x01", str_replace("\\", '/', substr($Item, -3)))) {
            continue;
        }
        $Arr[$Key] = array('Filename' => $ThisName);
        if (is_dir($Item)) {
            $Arr[$Key]['CanEdit'] = false;
            $Arr[$Key]['Directory'] = true;
            $Arr[$Key]['Filesize'] = 0;
            $Arr[$Key]['Filetype'] = $CIDRAM['lang']['field_filetype_directory'];
            $Arr[$Key]['Icon'] = 'icon=directory';
        } elseif (is_file($Item)) {
            $Arr[$Key]['CanEdit'] = true;
            $Arr[$Key]['Directory'] = false;
            $Arr[$Key]['Filesize'] = filesize($Item);
            if (($ExtDel = strrpos($Item, '.')) !== false) {
                $Ext = strtoupper(substr($Item, $ExtDel + 1));
                if (!$Ext) {
                    $Arr[$Key]['Filetype'] = $CIDRAM['lang']['field_filetype_unknown'];
                    $Arr[$Key]['Icon'] = 'icon=unknown';
                    $CIDRAM['FormatFilesize']($Arr[$Key]['Filesize']);
                    continue;
                }
                $Arr[$Key]['Filetype'] = $CIDRAM['ParseVars'](array('EXT' => $Ext), $CIDRAM['lang']['field_filetype_info']);
                if ($Ext === 'ICO') {
                    $Arr[$Key]['Icon'] = 'file=' . urlencode($Prepend . $Item);
                    $CIDRAM['FormatFilesize']($Arr[$Key]['Filesize']);
                    continue;
                }
                if (preg_match(
                    '/^(?:.?[BGL]Z.?|7Z|A(CE|LZ|P[KP]|R[CJ]?)?|B([AH]|Z2?)|CAB|DMG|' .
                    'I(CE|SO)|L(HA|Z[HOWX]?)|P(AK|AQ.?|CK|EA)|RZ|S(7Z|EA|EN|FX|IT.?|QX)|' .
                    'X(P3|Z)|YZ1|Z(IP.?|Z)?|(J|M|PH|R|SH|T|X)AR)$/'
                , $Ext)) {
                    $Arr[$Key]['CanEdit'] = false;
                    $Arr[$Key]['Icon'] = 'icon=archive';
                } elseif (preg_match('/^[SDX]?HT[AM]L?$/', $Ext)) {
                    $Arr[$Key]['Icon'] = 'icon=html';
                } elseif (preg_match('/^(?:CSV|JSON|NEON|SQL|YAML)$/', $Ext)) {
                    $Arr[$Key]['Icon'] = 'icon=ods';
                } elseif (preg_match('/^(?:PDF|XDP)$/', $Ext)) {
                    $Arr[$Key]['CanEdit'] = false;
                    $Arr[$Key]['Icon'] = 'icon=pdf';
                } elseif (preg_match('/^DOC[XT]?$/', $Ext)) {
                    $Arr[$Key]['CanEdit'] = false;
                    $Arr[$Key]['Icon'] = 'icon=doc';
                } elseif (preg_match('/^XLS[XT]?$/', $Ext)) {
                    $Arr[$Key]['CanEdit'] = false;
                    $Arr[$Key]['Icon'] = 'icon=xls';
                } elseif (preg_match('/^(?:CSS|JS|OD[BFGPST]|P(HP|PT))$/', $Ext)) {
                    $Arr[$Key]['Icon'] = 'icon=' . strtolower($Ext);
                    if (!preg_match('/^(?:CSS|JS|PHP)$/', $Ext)) {
                        $Arr[$Key]['CanEdit'] = false;
                    }
                } elseif (preg_match('/^(?:FLASH|SWF)$/', $Ext)) {
                    $Arr[$Key]['CanEdit'] = false;
                    $Arr[$Key]['Icon'] = 'icon=swf';
                } elseif (preg_match(
                    '/^(?:BM[2P]|C(D5|GM)|D(IB|W[FG]|XF)|ECW|FITS|GIF|IMG|J(F?IF?|P[2S]|PE?G?2?|XR)|P(BM|CX|DD|GM|IC|N[GMS]|PM|S[DP])|S(ID|V[AG])|TGA|W(BMP?|EBP|MP)|X(CF|BMP))$/'
                , $Ext)) {
                    $Arr[$Key]['CanEdit'] = false;
                    $Arr[$Key]['Icon'] = 'icon=image';
                } elseif (preg_match(
                    '/^(?:H?264|3GP(P2)?|A(M[CV]|VI)|BIK|D(IVX|V5?)|F([4L][CV]|MV)|GIFV|HLV|' .
                    'M(4V|OV|P4|PE?G[4V]?|KV|VR)|OGM|V(IDEO|OB)|W(EBM|M[FV]3?)|X(WMV|VID))$/'
                , $Ext)) {
                    $Arr[$Key]['CanEdit'] = false;
                    $Arr[$Key]['Icon'] = 'icon=video';
                } elseif (preg_match(
                    '/^(?:3GA|A(AC|IFF?|SF|U)|CDA|FLAC?|M(P?4A|IDI|KA|P[A23])|OGG|PCM|' .
                    'R(AM?|M[AX])|SWA|W(AVE?|MA))$/'
                , $Ext)) {
                    $Arr[$Key]['CanEdit'] = false;
                    $Arr[$Key]['Icon'] = 'icon=audio';
                } elseif (preg_match('/^(?:MD|NFO|RTF|TXT)$/', $Ext)) {
                    $Arr[$Key]['Icon'] = 'icon=text';
                }
            } else {
                $Arr[$Key]['Filetype'] = $CIDRAM['lang']['field_filetype_unknown'];
            }
        }
        if (empty($Arr[$Key]['Icon'])) {
            $Arr[$Key]['Icon'] = 'icon=unknown';
        }
        if ($Arr[$Key]['Filesize']) {
            $CIDRAM['FormatFilesize']($Arr[$Key]['Filesize']);
        } else {
            $Arr[$Key]['Filesize'] = '';
        }
    }
    return $Arr;
};

/**
 * Checks paths for directory traversal and ensures that they only contain
 * expected characters.
 *
 * @param string $Path The path to check.
 * @return bool False when directory traversals and/or unexpected characters
 *      are detected, and true otherwise.
 */
$CIDRAM['FileManager-PathSecurityCheck'] = function ($Path) {
    $Path = str_replace("\\", '/', $Path);
    if (
        preg_match("\x01" . '(?://|[^!0-9A-Za-z\._-]$)' . "\x01", $Path) ||
        preg_match("\x01" . '^(?:/\.\.|./\.|\.{3})$' . "\x01", str_replace("\\", '/', substr($Path, -3)))
    ) {
        return false;
    }
    $Path = preg_split('@/@', $Path, -1, PREG_SPLIT_NO_EMPTY);
    $Valid = true;
    array_walk($Path, function($Segment) use (&$Valid) {
        if (empty($Segment) || preg_match('/(?:[^!0-9a-z\x20\._-{}()]+|^\.+$)/i', $Segment)) {
            $Valid = false;
        }
    });
    return $Valid;
};

/**
 * Checks whether the specified directory is empty.
 *
 * @param string $Directory The directory to check.
 * @return bool True if empty; False if not empty.
 */
$CIDRAM['FileManager-IsDirEmpty'] = function ($Directory) {
    return !((new \FilesystemIterator($Directory))->valid());
};

/**
 * Used by the logs viewer to generate a list of the logfiles contained in a
 * working directory (normally, the vault).
 *
 * @param string $Base The path to the working directory.
 * @return array A list of the logfiles contained in the working directory.
 */
$CIDRAM['Logs-RecursiveList'] = function ($Base) use (&$CIDRAM) {
    $Arr = array();
    $List = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($Base), RecursiveIteratorIterator::SELF_FIRST);
    foreach ($List as $Item => $List) {
        if (
            preg_match("\x01" . '^(?:/\.\.|./\.|\.{3})$' . "\x01", str_replace("\\", '/', substr($Item, -3))) ||
            !preg_match("\x01" . '(?:logfile|\.(txt|log)$)' . "\x01i", $Item) ||
            !file_exists($Item) ||
            is_dir($Item) ||
            !is_file($Item) ||
            !is_readable($Item)
        ) {
            continue;
        }
        $ThisName = substr($Item, strlen($Base));
        $Arr[$ThisName] = array('Filename' => $ThisName, 'Filesize' => filesize($Item));
        $CIDRAM['FormatFilesize']($Arr[$ThisName]['Filesize']);
    }
    return $Arr;
};

/**
 * Checks whether a component is in use (front-end closure).
 *
 * @param array $Files The list of files to be checked.
 * @return bool Returns true (in use) or false (not in use).
 */
$CIDRAM['IsInUse'] = function ($Files) use (&$CIDRAM) {
    foreach ($Files as $File) {
        if (
            strpos(',' . $CIDRAM['Config']['signatures']['ipv4'] . ',', ',' . $File . ',') !== false ||
            strpos(',' . $CIDRAM['Config']['signatures']['ipv6'] . ',', ',' . $File . ',') !== false ||
            strpos(',' . $CIDRAM['Config']['signatures']['modules'] . ',', ',' . $File . ',') !== false
        ) {
            return true;
        }
    }
    return false;
};

/**
 * Determine the final IP address covered by an IPv4 CIDR. This closure is used
 * by the CIDR Calculator.
 *
 * @param string $First The first IP address.
 * @param int $Factor The range number (or CIDR factor number).
 * @return string The final IP address.
 */
$CIDRAM['IPv4GetLast'] = function ($First, $Factor) {
    $Octets = explode('.', $First);
    $Split = $Bracket = $Factor / 8;
    $Split -= floor($Split);
    $Split = (int)(8 - ($Split * 8));
    $Octet = floor($Bracket);
    if ($Octet < 4) {
        $Octets[$Octet] += pow(2, $Split) - 1;
    }
    while ($Octet < 3) {
        $Octets[$Octet + 1] = 255;
        $Octet++;
    }
    return implode('.', $Octets);
};

/**
 * Determine the final IP address covered by an IPv6 CIDR. This closure is used
 * by the CIDR Calculator.
 *
 * @param string $First The first IP address.
 * @param int $Factor The range number (or CIDR factor number).
 * @return string The final IP address.
 */
$CIDRAM['IPv6GetLast'] = function ($First, $Factor) {
    if (substr_count($First, '::')) {
        $Abr = 7 - substr_count($First, ':');
        $Arr = array(':0:', ':0:0:', ':0:0:0:', ':0:0:0:0:', ':0:0:0:0:0:', ':0:0:0:0:0:0:');
        $First = str_replace('::', $Arr[$Abr], $First);
    }
    $Octets = explode(':', $First);
    $Octet = 8;
    while ($Octet > 0) {
        $Octet--;
        $Octets[$Octet] = hexdec($Octets[$Octet]);
    }
    $Split = $Bracket = $Factor / 16;
    $Split -= floor($Split);
    $Split = (int)(16 - ($Split * 16));
    $Octet = floor($Bracket);
    if ($Octet < 8) {
        $Octets[$Octet] += pow(2, $Split) - 1;
    }
    while ($Octet < 7) {
        $Octets[$Octet + 1] = 65535;
        $Octet++;
    }
    $Octet = 8;
    while ($Octet > 0) {
        $Octet--;
        $Octets[$Octet] = dechex($Octets[$Octet]);
    }
    $Last = implode(':', $Octets);
    if (strpos($Last . '/', ':0:0/') !== false) {
        $Last = preg_replace('/(\:0){2,}$/i', '::', $Last, 1);
    } elseif (strpos($Last, ':0:0:') !== false) {
        $Last = preg_replace('/(?:(\:0)+\:(0\:)+|\:\:0$)/i', '::', $Last, 1);
    }
    return $Last;
};
