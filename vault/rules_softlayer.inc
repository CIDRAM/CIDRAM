<?php
/**
 * Description to go here.
 *
 * This file: File description here.
 *
 * @package Maikuolan/IPTestScript
 *
 * @todo EVERYTHING! This is still an ALPHA.
 */

/**
 * Prevents execution from outside of IPTestScript.
 */
if(!defined('IPTestScript'))die('[IPTestScript] This should not be accessed directly.');

/**
 * Prevents execution from outside of the IP test functions.
 */
if(!isset($cidr[$i]))die('[IPTestScript] This should not be accessed directly.');

$bypass=false;

/** ShowyouBot bypass. */
if(substr_count($GLOBALS['IPTestScript']['BlockInfo']['UALC'],'showyoubot'))$bypass=true;

/** Disqus bypass. */
if(substr_count($GLOBALS['IPTestScript']['BlockInfo']['UALC'],'disqus'))$bypass=true;

/** Feedspot bypass. */
if(substr_count($GLOBALS['IPTestScript']['BlockInfo']['UA'],'Feedspot http://www.feedspot.com'))$bypass=true;

/** Superfeedr bypass. */
if(substr_count($GLOBALS['IPTestScript']['BlockInfo']['UA'],'Superfeedr bot/2.0'))$bypass=true;

/** Feedbot bypass. */
if(substr_count($GLOBALS['IPTestScript']['BlockInfo']['UA'],'Feedbot'))$bypass=true;

if(!$bypass)
    {
    $GLOBALS['IPTestScript']['BlockInfo']['ReasonMessage'].=$GLOBALS['IPTestScript']['lang']['ReasonMessage_Cloud'];
    if(!empty($GLOBALS['IPTestScript']['BlockInfo']['Signatures']))$GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=', ';
    $GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=$cidr[$i];
    $GLOBALS['IPTestScript']['BlockInfo']['SignatureCount']++;
    }
$bypass=false;
