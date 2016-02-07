<?php
/**
 * This file is a part of the CIDRAM package, and can be downloaded for free
 * from {@link https://github.com/Maikuolan/CIDRAM/ GitHub}.
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.md
 *
 * This file: CIDRAM custom rules for AS6939 (last modified: 2016.02.07).
 *
 * @package Maikuolan/CIDRAM
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** Prevents execution from outside of the IP test functions. */
if (!isset($cidr[$i])) {
    die('[CIDRAM] This should not be accessed directly.');
}

$bypass=false;

/** Feedly/Feedspot bypass. */
if (preg_match('/\.getpebble\.com$/i',$GLOBALS['CIDRAM']['BlockInfo']['UALC'])||substr_count($GLOBALS['CIDRAM']['BlockInfo']['UA'],'Feedspot http://www.feedspot.com')||substr_count($GLOBALS['CIDRAM']['BlockInfo']['UA'],'Feedly'))$bypass=true;

/** Access provider block bypass. */
if ($cidr[23]==='65.49.67.0/24')$bypass=true;

if (!$bypass)
    {
    $GLOBALS['CIDRAM']['BlockInfo']['ReasonMessage'].=$GLOBALS['CIDRAM']['lang']['ReasonMessage_Cloud'];
    if (!empty($GLOBALS['CIDRAM']['BlockInfo']['Signatures']))$GLOBALS['CIDRAM']['BlockInfo']['Signatures'].=', ';
    $GLOBALS['CIDRAM']['BlockInfo']['Signatures'].=$cidr[$i];
    $GLOBALS['CIDRAM']['BlockInfo']['SignatureCount']++;
    }
$bypass=false;
