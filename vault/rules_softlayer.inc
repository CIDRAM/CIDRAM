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
 * This file: CIDRAM custom Soft Layer rules (last modified: 2016.02.07).
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

/** ShowyouBot bypass. */
if (substr_count($GLOBALS['CIDRAM']['BlockInfo']['UALC'], 'showyoubot')) {
    $bypass=true;
}

/** Disqus bypass. */
if (substr_count($GLOBALS['CIDRAM']['BlockInfo']['UALC'], 'disqus')) {
    $bypass=true;
}

/** Feedspot bypass. */
if (substr_count($GLOBALS['CIDRAM']['BlockInfo']['UA'], 'Feedspot http://www.feedspot.com')) {
    $bypass=true;
}

/** Superfeedr bypass. */
if (substr_count($GLOBALS['CIDRAM']['BlockInfo']['UA'], 'Superfeedr bot/2.0')) {
    $bypass=true;
}

/** Feedbot bypass. */
if (substr_count($GLOBALS['CIDRAM']['BlockInfo']['UA'], 'Feedbot')) {
    $bypass=true;
}

if (!$bypass)
    {
    $GLOBALS['CIDRAM']['BlockInfo']['ReasonMessage'].=$GLOBALS['CIDRAM']['lang']['ReasonMessage_Cloud'];
    if (!empty($GLOBALS['CIDRAM']['BlockInfo']['Signatures']))$GLOBALS['CIDRAM']['BlockInfo']['Signatures'].=', ';
    $GLOBALS['CIDRAM']['BlockInfo']['Signatures'].=$cidr[$i];
    $GLOBALS['CIDRAM']['BlockInfo']['SignatureCount']++;
    }
$bypass=false;
