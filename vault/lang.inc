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
 * This file: CIDRAM language handler (last modified: 2016.02.07).
 *
 * @package Maikuolan/CIDRAM
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

if (!isset($CIDRAM['Config']['general']))$CIDRAM['Config']['general']=array();
if (empty($CIDRAM['Config']['general']['lang']))$CIDRAM['Config']['general']['lang']='en';
$CIDRAM['lang']=array();
if (!file_exists($CIDRAM['Vault'].'lang/lang.'.$CIDRAM['Config']['general']['lang'].'.inc'))plaintext_echo_die('[CIDRAM] Language undefined or incorrectly defined. Can\'t continue.');
require $CIDRAM['Vault'].'lang/lang.'.$CIDRAM['Config']['general']['lang'].'.inc';
