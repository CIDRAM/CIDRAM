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

if(!isset($IPTestScript['Config']['general']))$IPTestScript['Config']['general']=array();
if(empty($IPTestScript['Config']['general']['lang']))$IPTestScript['Config']['general']['lang']='en';
$IPTestScript['lang']=array();
if(!file_exists($IPTestScript['Vault'].'lang/lang.'.$IPTestScript['Config']['general']['lang'].'.inc'))plaintext_echo_die('[IPTestScript] Language undefined or incorrectly defined. Can\'t continue.');
require $IPTestScript['Vault'].'lang/lang.'.$IPTestScript['Config']['general']['lang'].'.inc';
