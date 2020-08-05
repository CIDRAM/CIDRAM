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
 * This file: Configuration handler (last modified: 2020.08.04).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** CIDRAM version number (SemVer). */
$CIDRAM['ScriptVersion'] = '1.17.3';

/** CIDRAM version identifier (complete notation). */
$CIDRAM['ScriptIdent'] = 'CIDRAM v' . $CIDRAM['ScriptVersion'];

/** CIDRAM User Agent (for external requests). */
$CIDRAM['ScriptUA'] = $CIDRAM['ScriptIdent'] . ' (https://cidram.github.io/)';

/** Default timeout (for external requests). */
$CIDRAM['Timeout'] = 12;

/** Determine PHP path. */
$CIDRAM['CIDRAM_PHP'] = defined('PHP_BINARY') ? PHP_BINARY : '';

/** Fetch domain segment of HTTP_HOST (needed for writing cookies safely). */
$CIDRAM['HTTP_HOST'] = empty($_SERVER['HTTP_HOST']) ? '' : (
    strpos($_SERVER['HTTP_HOST'], ':') === false ? $_SERVER['HTTP_HOST'] : substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], ':'))
);

/** Allow post override of HTTP_HOST (assists with proxied front-end pages). */
$CIDRAM['HostnameOverride'] = empty($_POST['hostname']) ? '' : $_POST['hostname'];

/** CIDRAM favicon. */
$CIDRAM['favicon'] =
    'R0lGODlhEAAQAMIBAAAAAGYAAJkAAMz//2YAAGYAAGYAAGYAACH5BAEKAAQALAAAAAAQABA' .
    'AAANBCLrcKjBK+eKQN76RIb+g0oGewAmiZZbZRppnC0y0BgR4rutK8OWfn2jgI3KKxeHvyB' .
    'wMkc0kIEp13nZYnGPLSAAAOw==';

/** Checks whether the CIDRAM configuration file is readable. */
if (!isset($GLOBALS['CIDRAM_Config']) && !is_readable($CIDRAM['Vault'] . 'config.ini')) {
    header('Content-Type: text/plain');
    die('[CIDRAM] Can\'t read the configuration file! Please reconfigure CIDRAM.');
}

/** Checks whether the CIDRAM configuration defaults file is readable. */
if (!is_readable($CIDRAM['Vault'] . 'config.yaml')) {
    header('Content-Type: text/plain');
    die('[CIDRAM] Can\'t read the configuration defaults file! Please reconfigure CIDRAM.');
}

if (isset($GLOBALS['CIDRAM_Config'])) {

    /** Provides a means of running tests with configuration values specific to those tests. */
    $CIDRAM['Config'] = $GLOBALS['CIDRAM_Config'];
} else {

    /** Attempts to parse the standard CIDRAM configuration file. */
    $CIDRAM['Config'] = parse_ini_file($CIDRAM['Vault'] . 'config.ini', true);
}

/** Kills the script if it isn't able to load any configuration. */
if ($CIDRAM['Config'] === false) {
    header('Content-Type: text/plain');
    die('[CIDRAM] Configuration file is corrupt! Please reconfigure CIDRAM.');
}

/** Checks for the existence of the HTTP_HOST "configuration overrides file". */
if (
    !empty($_SERVER['HTTP_HOST']) &&
    ($CIDRAM['Domain'] = preg_replace('/^www\./', '', strtolower($_SERVER['HTTP_HOST']))) &&
    !preg_match('/[^.\da-z-]/', $CIDRAM['Domain']) &&
    is_readable($CIDRAM['Vault'] . $CIDRAM['Domain'] . '.config.ini')
) {

    /** Attempts to parse the overrides file found (this is configuration specific to the requested domain). */
    if ($CIDRAM['Overrides'] = parse_ini_file($CIDRAM['Vault'] . $CIDRAM['Domain'] . '.config.ini', true)) {
        array_walk($CIDRAM['Overrides'], function ($Keys, $Category) use (&$CIDRAM) {
            foreach ($Keys as $Directive => $Value) {
                $CIDRAM['Config'][$Category][$Directive] = $Value;
            }
        });
        $CIDRAM['Overrides'] = true;
    }
}

/** Kills the script if parsing the configuration overrides file fails. */
if (isset($CIDRAM['Overrides']) && $CIDRAM['Overrides'] === false) {
    header('Content-Type: text/plain');
    die('[CIDRAM] Configuration overrides file is corrupt! Can\'t continue until this is resolved.');
}

/** Attempts to parse the CIDRAM configuration defaults file. */
$CIDRAM['YAML']->process($CIDRAM['ReadFile']($CIDRAM['Vault'] . 'config.yaml'), $CIDRAM['Config']);

/** Kills the script if parsing the configuration defaults file fails. */
if (empty($CIDRAM['Config']['Config Defaults'])) {
    header('Content-Type: text/plain');
    die('[CIDRAM] Configuration defaults file is corrupt! Please reinstall CIDRAM.');
}

/** Check for supplementary configuration relating to IPv4 signature files. */
if (!empty($CIDRAM['Config']['signatures']['ipv4'])) {
    foreach ($CIDRAM['Supplementary']($CIDRAM['Config']['signatures']['ipv4']) as $CIDRAM['Supplement']) {
        $CIDRAM['YAML']->process($CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['Supplement']), $CIDRAM['Config']);
    }
}

/** Check for supplementary configuration relating to IPv6 signature files. */
if (!empty($CIDRAM['Config']['signatures']['ipv6'])) {
    foreach ($CIDRAM['Supplementary']($CIDRAM['Config']['signatures']['ipv6']) as $CIDRAM['Supplement']) {
        $CIDRAM['YAML']->process($CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['Supplement']), $CIDRAM['Config']);
    }
}

/** Check for supplementary configuration relating to modules. */
if (!empty($CIDRAM['Config']['signatures']['modules'])) {
    foreach ($CIDRAM['Supplementary']($CIDRAM['Config']['signatures']['modules']) as $CIDRAM['Supplement']) {
        $CIDRAM['YAML']->process($CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['Supplement']), $CIDRAM['Config']);
    }
}

/** Cleanup. */
unset($CIDRAM['Supplement']);

/** Perform fallbacks and autotyping for missing configuration directives. */
$CIDRAM['Fallback']($CIDRAM['Config']['Config Defaults'], $CIDRAM['Config']);

/** Failsafe for weird ipaddr configuration. */
$CIDRAM['IPAddr'] = (
    $CIDRAM['Config']['general']['ipaddr'] !== 'REMOTE_ADDR' && empty($_SERVER[$CIDRAM['Config']['general']['ipaddr']])
) ? 'REMOTE_ADDR' : $CIDRAM['Config']['general']['ipaddr'];

/** Ensure we have an IP address variable to work with. */
if (!isset($_SERVER[$CIDRAM['IPAddr']])) {
    $_SERVER[$CIDRAM['IPAddr']] = '';
}

/** Adjusted present time. */
$CIDRAM['Now'] = time() + ($CIDRAM['Config']['general']['timeOffset'] * 60);

/** Set timezone. */
if (!empty($CIDRAM['Config']['general']['timezone']) && $CIDRAM['Config']['general']['timezone'] !== 'SYSTEM') {
    date_default_timezone_set($CIDRAM['Config']['general']['timezone']);
}

/** Determine whether operating in CLI-mode (this check is specific for v1; it isn't needed for v2). */
$CIDRAM['CIDRAM_sapi'] = empty($_SERVER['REQUEST_METHOD']) || substr(php_sapi_name(), 0, 3) === 'cli' || (
    empty($_SERVER[$CIDRAM['IPAddr']]) &&
    empty($_SERVER['HTTP_USER_AGENT']) &&
    !empty($_SERVER['argc']) &&
    is_numeric($_SERVER['argc']) &&
    $_SERVER['argc'] > 0
);

/**
 * Process the request query and query variables (if any exist); These may be
 * occasionally used by certain extended rulesets.
 */
if (!empty($_SERVER['QUERY_STRING'])) {
    $CIDRAM['Query'] = $_SERVER['QUERY_STRING'];
    parse_str($_SERVER['QUERY_STRING'], $CIDRAM['QueryVars']);
} else {
    $CIDRAM['Query'] = '';
    $CIDRAM['QueryVars'] = [];
}

/** Set default hashing algorithm. */
$CIDRAM['DefaultAlgo'] = (
    !empty($CIDRAM['Config']['general']['default_algo']) && defined($CIDRAM['Config']['general']['default_algo'])
) ? constant($CIDRAM['Config']['general']['default_algo']) : PASSWORD_DEFAULT;

/** Revert script ident if "hide_version" is true. */
if (!empty($CIDRAM['Config']['general']['hide_version'])) {
    $CIDRAM['ScriptIdent'] = 'CIDRAM';
}
