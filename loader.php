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
 * This file: The loader (last modified: 2017.01.05).
 */

/**
 * CIDRAM should only be loaded once per PHP instance. To ensure this, we check
 * for the existence of a "CIDRAM" constant. If it doesn't exist, we define it
 * and continue loading. If it already exists, we do nothing.
 */
if (!defined('CIDRAM')) {
    define('CIDRAM', true);

    if (!version_compare(PHP_VERSION, '5.4.0', '>=')) {
        header('Content-Type: text/plain');
        die('[CIDRAM] Not compatible with PHP versions below 5.4.0; Please update PHP in order to use CIDRAM.');
    }

    /** Create an array for our working data. */
    $CIDRAM = array();

    /** Determine the location of the "vault" directory. */
    $CIDRAM['Vault'] = __DIR__ . '/vault/';

    /** Kill the script if we can't find the vault directory. */
    if (!is_dir($CIDRAM['Vault'])) {
        header('Content-Type: text/plain');
        die(
            '[CIDRAM] Vault directory not correctly set: Can\'t continue. Refer to do' .
            'cumentation if this is a first-time run, and if problems persist, seek a' .
            'ssistance.'
        );
    }

    /** Checks whether we're calling CIDRAM directly or through a hook. */
    $CIDRAM['Direct'] = function () {
        return (str_replace("\\", '/', strtolower($_SERVER['SCRIPT_FILENAME'])) === str_replace("\\", '/', strtolower(__FILE__)));
    };

    /**
     * Check whether the functions file exists; Kill the script if it doesn't.
     */
    if (!file_exists($CIDRAM['Vault'] . 'functions.php')) {
        header('Content-Type: text/plain');
        die('[CIDRAM] Functions file missing! Please reinstall CIDRAM.');
    }
    /** Load the functions file. */
    require $CIDRAM['Vault'] . 'functions.php';

    /**
     * Check whether the configuration handler exists; If it doesn't, kill the
     * script.
     */
    if (!file_exists($CIDRAM['Vault'] . 'config.php')) {
        header('Content-Type: text/plain');
        die('[CIDRAM] Configuration handler missing! Please reinstall CIDRAM.');
    }
    /** Load the configuration handler. */
    require $CIDRAM['Vault'] . 'config.php';

    /**
     * Check whether the language handler exists; Kill the script if it
     * doesn't.
     */
    if (!file_exists($CIDRAM['Vault'] . 'lang.php')) {
        header('Content-Type: text/plain');
        die('[CIDRAM] Language handler missing! Please reinstall CIDRAM.');
    }
    /** Load the language handler. */
    require $CIDRAM['Vault'] . 'lang.php';

    if (!$CIDRAM['CIDRAM_sapi']) {

        /**
         * Check whether the output generator exists; Kill the script if it
         * doesn't; Load it if it does. Skip this check if we're in CLI-mode.
         */
        if (!file_exists($CIDRAM['Vault'] . 'outgen.php')) {
            header('Content-Type: text/plain');
            die('[CIDRAM] Output generator missing! Please reinstall CIDRAM.');
        }
        require $CIDRAM['Vault'] . 'outgen.php';

        /**
         * Check whether the front-end handler and the front-end template file
         * exist; If they do, load the front-end handler. Skip this check if
         * front-end access is disabled.
         */
        if (
            !$CIDRAM['Config']['general']['disable_frontend'] &&
            file_exists($CIDRAM['Vault'] . 'frontend.php') &&
            file_exists($CIDRAM['Vault'] . 'fe_assets/frontend.html') &&
            $CIDRAM['Direct']()
        ) {
            require $CIDRAM['Vault'] . 'frontend.php';
        }

    } else {

        /**
         * Check whether the CLI handler exists; Load it if it does.
         * Skip this check if we're not in CLI-mode.
         */
        if (!$CIDRAM['Config']['general']['disable_cli'] && file_exists($CIDRAM['Vault'] . 'cli.php')) {
            require $CIDRAM['Vault'] . 'cli.php';
        }

    }

    /** Unset our working data so that we can exit cleanly. */
    unset($CIDRAM);
}
