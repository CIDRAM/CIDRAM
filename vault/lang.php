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
 * This file: Language handler (last modified: 2016.10.06).
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
     * Kill the script if the CLI-mode language data file corresponding to the
     * language directive (%CIDRAM%/vault/lang/lang.%%.cli.php) doesn't exist.
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

    /** Load front-end language data if necessary. */
    if (
        !$CIDRAM['Config']['general']['disable_frontend'] &&
        file_exists($CIDRAM['Vault'] . 'frontend.php') &&
        file_exists($CIDRAM['Vault'] . 'fe_assets/frontend.html') &&
        $CIDRAM['Direct']
    ) {
        /**
         * Kill the script if the front-end language data file corresponding to
         * the language directive (%CIDRAM%/vault/lang/lang.%%.fe.php) doesn't
         * exist.
         */
        if (!file_exists($CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Config']['general']['lang'] . '.fe.php')) {
            header('Content-Type: text/plain');
            die('[CIDRAM] Language undefined or incorrectly defined. Can\'t continue.');
        }
        /** Load the necessary language data. */
        require $CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Config']['general']['lang'] . '.fe.php';
    }

}
