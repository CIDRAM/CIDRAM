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
 * This file: The loader (last modified: 2016.05.28).
 */

/**
 * CIDRAM should only be loaded once per PHP instance. To ensure this, we check
 * for the existence of a "CIDRAM" constant. If it doesn't exist, we define it
 * and continue loading. If it already exists, we do nothing.
 */
if (!defined('CIDRAM')) {
    define('CIDRAM', true);

    /** Create an array for our working data. */
    $CIDRAM = array();

    /** CIDRAM version number (SemVer). */
    $CIDRAM['ScriptVersion'] = '0.3.0-DEV';

    /** CIDRAM version identifier (complete notation). */
    $CIDRAM['ScriptIdent'] = 'CIDRAM v' . $CIDRAM['ScriptVersion'];

    /** CIDRAM User Agent (for external requests). */
    $CIDRAM['ScriptUA'] = $CIDRAM['ScriptIdent'] . ' (http://maikuolan.github.io/CIDRAM/)';

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

    /** Checks whether the CIDRAM configuration file is readable. */
    if (!is_readable($CIDRAM['Vault'] . 'config.ini')) {
        header('Content-Type: text/plain');
        die('[CIDRAM] Can\'t read the configuration file! Please reconfigure CIDRAM.');
    }

    /** Attempts to parse the CIDRAM configuration file. */
    $CIDRAM['Config'] = parse_ini_file($CIDRAM['Vault'] . 'config.ini', true);
    if ($CIDRAM['Config'] === false) {
        header('Content-Type: text/plain');
        die('[CIDRAM] Configuration file is corrupt! Please reconfigure CIDRAM.');
    }

    /** Fallback for missing "general" configuration category. */
    if (!isset($CIDRAM['Config']['general'])) {
        $CIDRAM['Config']['general'] = array();
    }
    /** Fallback for missing "logfile" configuration directive. */
    if (!isset($CIDRAM['Config']['general']['logfile'])) {
        $CIDRAM['Config']['general']['logfile'] = '';
    }
    /** Fallback for missing "logfileApache" configuration directive. */
    if (!isset($CIDRAM['Config']['general']['logfileApache'])) {
        $CIDRAM['Config']['general']['logfileApache'] = '';
    }
    /** Fallback for missing "logfileSerialized" configuration directive. */
    if (!isset($CIDRAM['Config']['general']['logfileSerialized'])) {
        $CIDRAM['Config']['general']['logfileSerialized'] = '';
    }
    /** Fallback for missing "timeOffset" configuration directive. */
    if (!isset($CIDRAM['Config']['general']['timeOffset'])) {
        $CIDRAM['Config']['general']['timeOffset'] = 0;
    }
    /** Fallback for missing "ipaddr" configuration directive. */
    if (
        empty($CIDRAM['Config']['general']['ipaddr']) || (
            $CIDRAM['Config']['general']['ipaddr'] !== 'REMOTE_ADDR' &&
            empty($_SERVER[$CIDRAM['Config']['general']['ipaddr']])
        )
    ) {
        $CIDRAM['Config']['general']['ipaddr'] = 'REMOTE_ADDR';
    }
    /** Fallback for missing "forbid_on_block" configuration directive. */
    if (!isset($CIDRAM['Config']['general']['forbid_on_block'])) {
        $CIDRAM['Config']['general']['forbid_on_block'] = false;
    }
    /** Fallback for missing "silent_mode" configuration directive. */
    if (!isset($CIDRAM['Config']['general']['silent_mode'])) {
        $CIDRAM['Config']['general']['silent_mode'] = '';
    }
    /** Fallback for missing "emailaddr" configuration directive. */
    if (!isset($CIDRAM['Config']['general']['emailaddr'])) {
        $CIDRAM['Config']['general']['emailaddr'] = '';
    }
    /** Fallback for missing "disable_cli" configuration directive. */
    if (!isset($CIDRAM['Config']['general']['disable_cli'])) {
        $CIDRAM['Config']['general']['disable_cli'] = false;
    }

    /** Fallback for missing "signatures" configuration category. */
    if (!isset($CIDRAM['Config']['signatures'])) {
        $CIDRAM['Config']['signatures'] = array();
    }
    /** Fallback for missing "ipv4" configuration directive. */
    if (!isset($CIDRAM['Config']['signatures']['ipv4'])) {
        $CIDRAM['Config']['signatures']['ipv4'] = 'ipv4.dat,ipv4_custom.dat';
    }
    /** Fallback for missing "ipv6" configuration directive. */
    if (!isset($CIDRAM['Config']['signatures']['ipv6'])) {
        $CIDRAM['Config']['signatures']['ipv4'] = 'ipv6.dat,ipv6_custom.dat';
    }
    /** Fallback for missing "block_cloud" configuration directive. */
    if (!isset($CIDRAM['Config']['signatures']['block_cloud'])) {
        $CIDRAM['Config']['signatures']['block_cloud'] = true;
    }
    /** Fallback for missing "block_bogons" configuration directive. */
    if (!isset($CIDRAM['Config']['signatures']['block_bogons'])) {
        $CIDRAM['Config']['signatures']['block_bogons'] = false;
    }
    /** Fallback for missing "block_generic" configuration directive. */
    if (!isset($CIDRAM['Config']['signatures']['block_generic'])) {
        $CIDRAM['Config']['signatures']['block_generic'] = true;
    }
    /** Fallback for missing "block_proxies" configuration directive. */
    if (!isset($CIDRAM['Config']['signatures']['block_proxies'])) {
        $CIDRAM['Config']['signatures']['block_proxies'] = false;
    }
    /** Fallback for missing "block_spam" configuration directive. */
    if (!isset($CIDRAM['Config']['signatures']['block_spam'])) {
        $CIDRAM['Config']['signatures']['block_spam'] = true;
    }

    /** Fallback for missing "template_data" configuration category. */
    if (!isset($CIDRAM['Config']['template_data'])) {
        $CIDRAM['Config']['template_data'] = array();
    }
    /** Fallback for missing "css_url" configuration directive. */
    if (!isset($CIDRAM['Config']['template_data']['css_url'])) {
        $CIDRAM['Config']['template_data']['css_url'] = '';
    }

    /** Adjusted present time. */
    $CIDRAM['Now'] = time() + ($CIDRAM['Config']['general']['timeOffset'] * 60);

    /** Determine PHP path. */
    $CIDRAM['CIDRAM_PHP'] = defined('PHP_BINARY') ? PHP_BINARY : '';

    /** Determine the operating system in use. */
    $CIDRAM['CIDRAM_OS'] = strtoupper(substr(PHP_OS, 0, 3));

    /** Determine if operating in CLI-mode. */
    $CIDRAM['CIDRAM_sapi'] = (
        empty($_SERVER['REQUEST_METHOD']) ||
        substr(php_sapi_name(), 0, 3) === 'cli' ||
        (
            empty($_SERVER[$CIDRAM['Config']['general']['ipaddr']]) &&
            empty($_SERVER['HTTP_USER_AGENT']) &&
            !empty($_SERVER['argc']) &&
            is_numeric($_SERVER['argc']) &&
            $_SERVER['argc'] > 0
        )
    );

    /** Check if the language handler exists; Kill the script if it doesn't. */
    if (!file_exists($CIDRAM['Vault'] . 'lang.php')) {
        header('Content-Type: text/plain');
        die('[CIDRAM] Language handler missing! Please reinstall CIDRAM.');
    }
    /** Load the language handler. */
    require $CIDRAM['Vault'] . 'lang.php';

    /** Check if the functions file exists; Kill the script if it doesn't. */
    if (!file_exists($CIDRAM['Vault'] . 'functions.php')) {
        header('Content-Type: text/plain');
        die('[CIDRAM] Functions file missing! Please reinstall CIDRAM.');
    }
    /** Load the functions file. */
    require $CIDRAM['Vault'] . 'functions.php';

    if (!$CIDRAM['CIDRAM_sapi']) {

        /**
         * Check if the output generator exists; Kill the script if it doesn't;
         * Load it if it does. Skip this check if we're in CLI-mode.
         */
        if (!file_exists($CIDRAM['Vault'] . 'outgen.php')) {
            header('Content-Type: text/plain');
            die('[CIDRAM] Output generator missing! Please reinstall CIDRAM.');
        }
        require $CIDRAM['Vault'] . 'outgen.php';

    } else {

        /**
         * Check if the CLI handler exists; Load it if it does.
         * Skip this check if we're not in CLI-mode.
         */
        if (!$CIDRAM['Config']['general']['disable_cli'] && file_exists($CIDRAM['Vault'] . 'cli.php')) {
            require $CIDRAM['Vault'] . 'cli.php';
        }

    }

    /** Unset our working data so that we can exit cleanly. */
    unset($CIDRAM);
}
