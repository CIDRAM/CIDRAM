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
 * This file: Optional cookie scanner module (last modified: 2023.03.09).
 *
 * False positive risk (an approximate, rough estimate only): « [x]Low [ ]Medium [ ]High »
 *
 * Many thanks to Michael Hopkins, the creator of ZB Block (GNU/GPLv2) and its
 * cookie scanner module, which the cookie scanner module for CIDRAM is based
 * upon and inspired by.
 */

/** Safety. */
if (!isset($this->CIDRAM['ModuleResCache'])) {
    $this->CIDRAM['ModuleResCache'] = [];
}

/** Defining as closure for later recall (no params; no return value). */
$this->CIDRAM['ModuleResCache'][$Module] = function () {
    /** Count cookies. */
    $Cookies = count($_COOKIE);

    /** Guard and protection against flooding. */
    if (!$Cookies || $this->trigger($Cookies > 30, 'Cookie flood', 'Cookie flood detected!')) {
        return;
    }

    /** Signatures start from here. */
    foreach ($_COOKIE as $Key => $Value) {
        /** MyBB fix (skip iteration if value/key are unexpected types). */
        if (is_array($Key) || is_array($Value) || is_object($Key) || is_object($Value)) {
            continue;
        }

        $KeyLC = strtolower($Key);
        $ValueLC = strtolower($Value);
        $ThisPair = $Key . '->' . $Value;
        $ThisPairN = preg_replace('/\s/', '', strtolower($ThisPair));

        $this->trigger(preg_match('/(?:\+A(?:CI|D[sw4]|[FH][s0]|GA)-|U\+003[EC])/', $ThisPair), 'UTF-7 entities detected in cookie'); // 2017.01.02

        $this->trigger(preg_match('/\((?:["\']{2})?\)/', $ThisPairN), 'Command injection detected in cookie'); // 2017.01.02
        $this->trigger(preg_match(
            '/(?:_once|able|as(c|hes|sert)|c(hr|ode|ontents)|e(cho|regi|scape|va' .
            'l)|ex(ec|ists)?|f(ile|late|unction)|hex2bin|get(c|csv|ss?)?|i(f|ncl' .
            'ude)|len(gth)?|nt|open|p(ress|lace|lode|uts)|print(f|_r)?|re(ad|pla' .
            'ce|quire|store)|rot13|s(tart|ystem)|w(hil|rit)e)["\':(\[{<$]/i',
            $ThisPairN
        ), 'Command injection detected in cookie'); // 2017.01.02
        $this->trigger(
            preg_match('/\$(?:globals|_(cookie|env|files|get|post|request|se(rver|ssion)))/', $ThisPairN),
            'Command injection detected in cookie'
        ); // 2017.01.20
        $this->trigger(preg_match('/add(?:handler|type|inputfilter)/', $ThisPairN), 'Command injection detected in cookie'); // 2017.01.02
        $this->trigger(preg_match('/http_(?:cmd|sum)/', $ThisPairN), 'Command injection detected in cookie'); // 2017.01.02
        $this->trigger(preg_match('/pa(?:rse_ini_file|ssthru)/', $ThisPairN), 'Command injection detected in cookie'); // 2017.01.02
        $this->trigger(preg_match('/rewrite(?:cond|rule)/', $ThisPairN), 'Command injection detected in cookie'); // 2017.01.02
        $this->trigger(preg_match('/set(?:handl|inputfilt)er/', $ThisPairN), 'Command injection detected in cookie'); // 2017.01.20
        $this->trigger(preg_match('/u(?:nserializ|ploadedfil)e/', $ThisPairN), 'Command injection detected in cookie'); // 2017.01.20
        $this->trigger(strpos($ThisPairN, '$http_raw_post_data') !== false, 'Command injection detected in cookie'); // 2017.01.02
        $this->trigger(strpos($ThisPairN, 'dotnet_load') !== false, 'Command injection detected in cookie'); // 2017.01.02
        $this->trigger(strpos($ThisPairN, 'execcgi') !== false, 'Command injection detected in cookie'); // 2017.01.02
        $this->trigger(strpos($ThisPairN, 'forcetype') !== false, 'Command injection detected in cookie'); // 2017.01.02
        $this->trigger(strpos($ThisPairN, 'move_uploaded_file') !== false, 'Command injection detected in cookie'); // 2017.01.02
        $this->trigger(strpos($ThisPairN, 'symlink') !== false, 'Command injection detected in cookie'); // 2017.01.02
        $this->trigger(strpos($ThisPairN, 'tmp_name') !== false, 'Command injection detected in cookie'); // 2017.01.02
        $this->trigger(strpos($ThisPairN, '_contents') !== false, 'Command injection detected in cookie'); // 2017.01.02

        $this->trigger(preg_match('/ap(?:ache_[\w\d_]{4,16}|c_[\w\d_]{3,16})\(/', $ThisPairN), 'Function call detected in cookie'); // 2018.06.24
        $this->trigger(preg_match('/curl_[\w\d_]{4,10}\(/', $ThisPairN), 'Function call detected in cookie'); // 2018.06.24
        $this->trigger(preg_match('/ftp_[\w\d_]{3,7}\(/', $ThisPairN), 'Function call detected in cookie'); // 2018.06.24
        $this->trigger(preg_match('/mysqli?(?:_|::)[\w\d_]{4,9}\(/', $ThisPairN), 'Function call detected in cookie'); // 2018.06.24
        $this->trigger(preg_match('/phpads_[\w\d_]{4,12}\(/', $ThisPairN), 'Function call detected in cookie'); // 2018.06.24
        $this->trigger(preg_match('/posix_[\w\d_]{4,19}\(/', $ThisPairN), 'Function call detected in cookie'); // 2018.06.24
        $this->trigger(preg_match('/proc_[\w\d_]{4,10}\(/', $ThisPairN), 'Function call detected in cookie'); // 2018.06.24

        $this->trigger(preg_match('/\'(?:uploadedfile|move_uploaded_file|tmp_name)\'/', $ThisPairN), 'Probe attempt'); // 2017.01.02

        $this->trigger($Key === 'SESSUNIVUCADIACOOKIE', 'Hotlinking detected', 'Hotlinking not allowed!'); // 2017.01.02

        $this->trigger($Key === 'arp_scroll_position' && strpos($ThisPairN, '400') !== false, 'Bad cookie'); // 2017.01.02
        $this->trigger($Key === 'BALANCEID' && strpos($ThisPairN, 'balancer.') !== false, 'Bad cookie'); // 2017.01.02
        $this->trigger($Key === 'BX', 'Bad cookie'); // 2017.01.02
        $this->trigger($Key === 'ja_edenite_tpl', 'Bad cookie'); // 2017.01.02
        $this->trigger($Key === 'phpbb3_1fh61_', 'Bad cookie'); // 2017.01.02

        $this->trigger((
            ($Key === 'CUSTOMER' || $Key === 'CUSTOMER_INFO' || $Key === 'NEWMESSAGE') &&
            strpos($ThisPairN, 'deleted') !== false
        ), 'Cookie hack detected'); // 2017.01.02

        /** These signatures can set extended tracking options. */
        if (
            $this->trigger(strpos($ThisPairN, '@$' . '_[' . ']=' . '@!' . '+_') !== false, 'Shell upload attempted via cookies') || // 2017.01.02
            $this->trigger(strpos($ThisPairN, 'linkirc') !== false, 'Shell upload attempted via cookies') || // 2017.01.02
            $this->trigger($Key === '()' || $Value === '()', 'Cookie hack detected (Bash/Shellshock)') || // 2017.01.02
            $this->trigger($KeyLC === 'rm ' . '-rf' || $ValueLC === 'rm ' . '-rf', 'Cookie hack detected') || // 2017.01.02
            $this->trigger(preg_match('/:(?:\{\w:|[\w\d][;:]\})/', $ThisPairN), 'Cookie hack detected') || // 2018.06.24
            $this->trigger((
                ($Value === -1 || $Value === '-1') &&
                ($Key === 'ASP_NET_SessionId' || $Key === 'CID' || $Key === 'SID' || $Key === 'NID')
            ), 'ASP.NET hack detected') // 2017.01.02
        ) {
            $this->CIDRAM['Tracking options override'] = 'extended';
        }
    }

    /** Reporting. */
    if (!empty($this->BlockInfo['IPAddr'])) {
        if (strpos($this->BlockInfo['WhyReason'], 'Function call detected in cookie') !== false) {
            $this->Reporter->report([15], ['Function call detected in cookie.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Command injection detected in cookie') !== false) {
            $this->Reporter->report([15], ['Command injection detected in cookie.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Cookie hack detected') !== false) {
            $this->Reporter->report([15], ['Cookie hack detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Shell upload attempted via cookies') !== false) {
            $this->Reporter->report([15], ['Shell upload attempted via cookies.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Probe attempt') !== false) {
            $this->Reporter->report([21], ['Probe attempt detected.'], $this->BlockInfo['IPAddr']);
        }
    }
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
