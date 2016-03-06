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
 * This file: The loader (last modified: 2016.03.07).
 *
 * @package Maikuolan/CIDRAM
 */

/**
 * CIDRAM should only be loaded once per PHP instance. To ensure this, we check
 * for the existence of a "CIDRAM" constant. If it doesn't exist, we define it
 * and continue loading. If it already exists, we do nothing.
 */
if (!defined('CIDRAM')) {
    define('CIDRAM', true);

    /**
     * Create an array for our working data.
     */
    $CIDRAM = array();

    /** CIDRAM version number (SemVer). */
    $CIDRAM['ScriptVersion'] = '0.1.1';

    /** CIDRAM version identifier (complete notation). */
    $CIDRAM['ScriptIdent'] = 'CIDRAM v' . $CIDRAM['ScriptVersion'];

    /** CIDRAM User Agent (for external requests). */
    $CIDRAM['ScriptUA'] =
        'CIDRAM v' . $CIDRAM['ScriptVersion'] .
        ' (https://github.com/Maikuolan/CIDRAM)';

    /** Determine the location of the "vault" directory. */
    $CIDRAM['Vault'] = __DIR__ . '/vault/';

    /** Kill the script if we can't find the vault directory. */
    if (!is_dir($CIDRAM['Vault'])) {
        header('Content-Type: text/plain');
        die(
            '[CIDRAM] Vault directory not correctly set: Can\'t continue. ' .
            'Refer to documentation if this is a first-time run, and if ' .
            'problems persist, seek assistance.'
        );
    }

    /**
     * Process the request query and query variables (if any exist); These may
     * be occasionally used by certain extended rulesets.
     */
    if (!empty($_SERVER['QUERY_STRING'])) {
        $CIDRAM['Query'] = $_SERVER['QUERY_STRING'];
        parse_str($_SERVER['QUERY_STRING'], $CIDRAM['QueryVars']);
    } else {
        $CIDRAM['Query'] = '';
        $CIDRAM['QueryVars'] = array();
    }

    /** Parses the CIDRAM configuration file. */
    $CIDRAM['Config'] =
        @(!file_exists($CIDRAM['Vault'] . 'config.ini')) ?
        false :
        parse_ini_file($CIDRAM['Vault'] . 'config.ini', true);

    /** Kill the script if we fail to parse the configuration file. */
    if (!is_array($CIDRAM['Config'])) {
        header('Content-Type: text/plain');
        die(
            '[CIDRAM] Could not read config.ini: Can\'t continue. '.
            'Refer to the documentation if this is a first-time run, and if '.
            'problems persist, seek assistance.'
        );
    }

    /** Fallback for missing "general" configuration category. */
    if (!isset($CIDRAM['Config']['general'])) {
        $CIDRAM['Config']['general'] = array();
    }
    /** Fallback for missing "ipaddr" configuration directive. */
    if (!isset($CIDRAM['Config']['general']['ipaddr'])) {
        $CIDRAM['Config']['general']['ipaddr'] = 'REMOTE_ADDR';
    }
    /** Fallback for missing "emailaddr" configuration directive. */
    if (!isset($CIDRAM['Config']['general']['emailaddr'])) {
        $CIDRAM['Config']['general']['emailaddr'] = '';
    }

    /** Fallback for missing "signatures" configuration category. */
    if (!isset($CIDRAM['Config']['signatures'])) {
        $CIDRAM['Config']['signatures'] = array();
    }
    /** Fallback for missing "block_cloud" configuration directive. */
    if (!isset($CIDRAM['Config']['signatures']['block_cloud'])) {
        $CIDRAM['Config']['signatures']['block_cloud'] = true;
    }
    /** Fallback for missing "block_bogons" configuration directive. */
    if (!isset($CIDRAM['Config']['signatures']['block_bogons'])) {
        $CIDRAM['Config']['signatures']['block_bogons'] = true;
    }
    /** Fallback for missing "block_generic" configuration directive. */
    if (!isset($CIDRAM['Config']['signatures']['block_generic'])) {
        $CIDRAM['Config']['signatures']['block_generic'] = true;
    }
    /** Fallback for missing "block_spam" configuration directive. */
    if (!isset($CIDRAM['Config']['signatures']['block_spam'])) {
        $CIDRAM['Config']['signatures']['block_spam'] = true;
    }

    /** Determine PHP path. */
    $CIDRAM['CIDRAM_PHP'] = defined('PHP_BINARY') ? PHP_BINARY : '';

    /** Determine the operating system in use. */
    $CIDRAM['CIDRAM_OS'] = strtoupper(substr(PHP_OS, 0, 3));

    /** Determine if operating in CLI. */
    $CIDRAM['CIDRAM_sapi'] = php_sapi_name();

    /** Check if the language handler exists; Kill the script if it doesn't. */
    if (!file_exists($CIDRAM['Vault'] . 'lang.inc')) {
        header('Content-Type: text/plain');
        die('[CIDRAM] Language handler missing! Please reinstall CIDRAM.');
    }
    /** Load the language handler. */
    require $CIDRAM['Vault'] . 'lang.inc';

    /** Check if the functions file exists; Kill the script if it doesn't. */
    if (!file_exists($CIDRAM['Vault'] . 'functions.inc')) {
        header('Content-Type: text/plain');
        die('[CIDRAM] Functions file missing! Please reinstall CIDRAM.');
    }
    /** Load the functions file. */
    require $CIDRAM['Vault'] . 'functions.inc';

    /**
     * Check if the output generator exists; Kill the script if it doesn't;
     * Load it if it does. Skip this check if we're in CLI-mode.
     */
    if ($CIDRAM['CIDRAM_sapi'] !== 'cli') {
        if (!file_exists($CIDRAM['Vault'] . 'outgen.inc')) {
            header('Content-Type: text/plain');
            die('[CIDRAM] Output generator missing! Please reinstall CIDRAM.');
        }
        require $CIDRAM['Vault'] . 'outgen.inc';
    }

    /**
     * Check if the CLI handler exists; Load it if it does.
     * Skip this check if we're not in CLI-mode.
     */
    if ($CIDRAM['CIDRAM_sapi'] === 'cli') {
        if (file_exists($CIDRAM['Vault'] . 'cli.inc')) {
            require $CIDRAM['Vault'] . 'cli.inc';
        }
    }

    /** Unset our working data so that we can exit cleanly. */
    unset($CIDRAM);

}
