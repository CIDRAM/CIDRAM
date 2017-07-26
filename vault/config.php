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
 * This file: Configuration handler (last modified: 2017.07.25).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** CIDRAM version number (SemVer). */
$CIDRAM['ScriptVersion'] = '1.0.1';

/** CIDRAM version identifier (complete notation). */
$CIDRAM['ScriptIdent'] = 'CIDRAM v' . $CIDRAM['ScriptVersion'];

/** CIDRAM User Agent (for external requests). */
$CIDRAM['ScriptUA'] = $CIDRAM['ScriptIdent'] . ' (https://cidram.github.io/)';

/** Default timeout (for external requests). */
$CIDRAM['Timeout'] = 12;

/** Determine PHP path. */
$CIDRAM['CIDRAM_PHP'] = defined('PHP_BINARY') ? PHP_BINARY : '';

/** Determine the operating system in use. */
$CIDRAM['CIDRAM_OS'] = strtoupper(substr(PHP_OS, 0, 3));

/** CIDRAM favicon. */
$CIDRAM['favicon'] =
    'R0lGODlhEAAQAMIBAAAAAGYAAJkAAMz//2YAAGYAAGYAAGYAACH5BAEKAAQALAAAAAAQABA' .
    'AAANBCLrcKjBK+eKQN76RIb+g0oGewAmiZZbZRppnC0y0BgR4rutK8OWfn2jgI3KKxeHvyB' .
    'wMkc0kIEp13nZYnGPLSAAAOw==';

/** Checks whether the CIDRAM configuration file is readable. */
if (!is_readable($CIDRAM['Vault'] . 'config.ini')) {
    header('Content-Type: text/plain');
    die('[CIDRAM] Can\'t read the configuration file! Please reconfigure CIDRAM.');
}

/** Checks whether the CIDRAM configuration defaults file is readable. */
if (!is_readable($CIDRAM['Vault'] . 'config.yaml')) {
    header('Content-Type: text/plain');
    die('[CIDRAM] Can\'t read the configuration defaults file! Please reconfigure CIDRAM.');
}

/** Attempts to parse the CIDRAM configuration file. */
$CIDRAM['Config'] = parse_ini_file($CIDRAM['Vault'] . 'config.ini', true);

/** Kills the script if parsing the configuration file fails. */
if ($CIDRAM['Config'] === false) {
    header('Content-Type: text/plain');
    die('[CIDRAM] Configuration file is corrupt! Please reconfigure CIDRAM.');
}

/** Checks for the existence of the HTTP_HOST configuration overrides file. */
if (
    !empty($_SERVER['HTTP_HOST']) &&
    ($CIDRAM['Domain'] = preg_replace('/^www\./', '', strtolower($_SERVER['HTTP_HOST']))) &&
    !preg_match('/[^.0-9a-z-]/', $CIDRAM['Domain']) &&
    is_readable($CIDRAM['Vault'] . $CIDRAM['Domain'] . '.config.ini')
) {
    /** Attempts to parse the configuration overrides file. */
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
$CIDRAM['YAML']($CIDRAM['ReadFile']($CIDRAM['Vault'] . 'config.yaml'), $CIDRAM['Config']);

/** Kills the script if parsing the configuration defaults file fails. */
if (empty($CIDRAM['Config']['Config Defaults'])) {
    header('Content-Type: text/plain');
    die('[CIDRAM] Configuration defaults file is corrupt! Please reinstall CIDRAM.');
}

/** Perform fallbacks and autotyping for missing configuration directives. */
$CIDRAM['Config']['Temp'] = array();
foreach ($CIDRAM['Config']['Config Defaults'] as $CIDRAM['Config']['Temp']['KeyCat'] => $CIDRAM['Config']['Temp']['DCat']) {
    if (!isset($CIDRAM['Config'][$CIDRAM['Config']['Temp']['KeyCat']])) {
        $CIDRAM['Config'][$CIDRAM['Config']['Temp']['KeyCat']] = array();
    }
    if (isset($CIDRAM['Config']['Temp']['Cat'])) {
        unset($CIDRAM['Config']['Temp']['Cat']);
    }
    $CIDRAM['Config']['Temp']['Cat'] = &$CIDRAM['Config'][$CIDRAM['Config']['Temp']['KeyCat']];
    if (!is_array($CIDRAM['Config']['Temp']['DCat'])) {
        continue;
    }
    foreach ($CIDRAM['Config']['Temp']['DCat'] as $CIDRAM['Config']['Temp']['KeyDir'] => $CIDRAM['Config']['Temp']['DDir']) {
        if (
            !isset($CIDRAM['Config']['Temp']['Cat'][$CIDRAM['Config']['Temp']['KeyDir']]) &&
            isset($CIDRAM['Config']['Temp']['DDir']['default'])
        ) {
            $CIDRAM['Config']['Temp']['Cat'][$CIDRAM['Config']['Temp']['KeyDir']] = $CIDRAM['Config']['Temp']['DDir']['default'];
        }
        if (isset($CIDRAM['Config']['Temp']['Dir'])) {
            unset($CIDRAM['Config']['Temp']['Dir']);
        }
        $CIDRAM['Config']['Temp']['Dir'] = &$CIDRAM['Config']['Temp']['Cat'][$CIDRAM['Config']['Temp']['KeyDir']];
        if (isset($CIDRAM['Config']['Temp']['DDir']['type'])) {
            $CIDRAM['AutoType']($CIDRAM['Config']['Temp']['Dir'], $CIDRAM['Config']['Temp']['DDir']['type']);
        }
    }
}
unset($CIDRAM['Config']['Temp']);

/** Failsafe for weird ipaddr configuration. */
if ($CIDRAM['Config']['general']['ipaddr'] !== 'REMOTE_ADDR' && empty($_SERVER[$CIDRAM['Config']['general']['ipaddr']])) {
    $CIDRAM['Config']['general']['ipaddr'] = 'REMOTE_ADDR';
}

/** Adjusted present time. */
$CIDRAM['Now'] = time() + ($CIDRAM['Config']['general']['timeOffset'] * 60);

/** Set timezone. */
if (!empty($CIDRAM['Config']['general']['timezone']) && $CIDRAM['Config']['general']['timezone'] !== 'SYSTEM') {
    date_default_timezone_set($CIDRAM['Config']['general']['timezone']);
}

/** Determine whether operating in CLI-mode. */
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

/**
 * Process the request query and query variables (if any exist); These may be
 * occasionally used by certain extended rulesets.
 */
if (!empty($_SERVER['QUERY_STRING'])) {
    $CIDRAM['Query'] = $_SERVER['QUERY_STRING'];
    parse_str($_SERVER['QUERY_STRING'], $CIDRAM['QueryVars']);
} else {
    $CIDRAM['Query'] = '';
    $CIDRAM['QueryVars'] = array();
}
