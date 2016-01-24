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

/** Feedly/Feedspot bypass. */
if(preg_match('/\.getpebble\.com$/i',$GLOBALS['IPTestScript']['BlockInfo']['UALC'])||substr_count($GLOBALS['IPTestScript']['BlockInfo']['UA'],'Feedspot http://www.feedspot.com')||substr_count($GLOBALS['IPTestScript']['BlockInfo']['UA'],'Feedly'))$bypass=true;

/** Access provider block bypass. */
if($cidr[23]==='65.49.67.0/24')$bypass=true;

if(!$bypass)
    {
    $GLOBALS['IPTestScript']['BlockInfo']['ReasonMessage'].=$GLOBALS['IPTestScript']['lang']['ReasonMessage_Cloud'];
    if(!empty($GLOBALS['IPTestScript']['BlockInfo']['Signatures']))$GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=', ';
    $GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=$cidr[$i];
    $GLOBALS['IPTestScript']['BlockInfo']['SignatureCount']++;
    }
$bypass=false;
