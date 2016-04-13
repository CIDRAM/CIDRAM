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
 * This file: Language handler (last modified: 2016.04.12).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** If the language directive is empty, default to English. */
if (empty($CIDRAM['Config']['general']['lang'])) {
    $CIDRAM['Config']['general']['lang'] = 'en';
}

/** Create the language data array. */
$CIDRAM['lang'] = array();

if ($CIDRAM['CIDRAM_sapi']) {
    /**
     * Kill the script if the language data file corresponding to the language
     * directive for CLI-mode (%CIDRAM%/vault/lang/lang.%%.cli.php) doesn't exist.
     */
    if (!file_exists($CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Config']['general']['lang'] . '.cli.php')) {
        header('Content-Type: text/plain');
        die('[CIDRAM] Language undefined or incorrectly defined. Can\'t continue.');
    }
    /** Load the necessary language data. */
    require $CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Config']['general']['lang'] . '.cli.php';
} else {
    /**
     * Kill the script if the language data file corresponding to the language
     * directive (%CIDRAM%/vault/lang/lang.%%.php) doesn't exist.
     */
    if (!file_exists($CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Config']['general']['lang'] . '.php')) {
        header('Content-Type: text/plain');
        die('[CIDRAM] Language undefined or incorrectly defined. Can\'t continue.');
    }
    /** Load the necessary language data. */
    require $CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Config']['general']['lang'] . '.php';
}
