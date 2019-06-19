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
 * This file: Configuration handler (last modified: 2019.05.11).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** CIDRAM version number (SemVer). */
$CIDRAM['ScriptVersion'] = '2.0.0';

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

/** Attempts to parse the CIDRAM configuration file. */
if(!isset($GLOBALS['CIDRAM_Config'])) {
    $CIDRAM['Config'] = parse_ini_file($CIDRAM['Vault'] . 'config.ini', true);
} else {
    $CIDRAM['Config'] = $GLOBALS['CIDRAM_Config'];
}

/** Kills the script if parsing the configuration file fails. */
if ($CIDRAM['Config'] === false) {
    header('Content-Type: text/plain');
    die('[CIDRAM] Configuration file is corrupt! Please reconfigure CIDRAM.');
}

/** Checks for the existence of the HTTP_HOST configuration overrides file. */
if (
    !empty($_SERVER['HTTP_HOST']) &&
    ($CIDRAM['Domain'] = preg_replace('/^www\./', '', strtolower($_SERVER['HTTP_HOST']))) &&
    !preg_match('/[^.\da-z-]/', $CIDRAM['Domain']) &&
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
$CIDRAM['Config']['Config Defaults'] = (new \Maikuolan\Common\YAML($CIDRAM['ReadFile']($CIDRAM['Vault'] . 'config.yaml')))->Data;

/** Kills the script if parsing the configuration defaults file fails. */
if (empty($CIDRAM['Config']['Config Defaults']['Config Defaults'])) {
    header('Content-Type: text/plain');
    die('[CIDRAM] Configuration defaults file is corrupt! Please reinstall CIDRAM.');
} else {
    $CIDRAM['Config']['Config Defaults'] = $CIDRAM['Config']['Config Defaults']['Config Defaults'];
}

/** Perform fallbacks and autotyping for missing configuration directives. */
$CIDRAM['Config']['Temp'] = [];
foreach ($CIDRAM['Config']['Config Defaults'] as $CIDRAM['Config']['Temp']['KeyCat'] => $CIDRAM['Config']['Temp']['DCat']) {
    if (!isset($CIDRAM['Config'][$CIDRAM['Config']['Temp']['KeyCat']])) {
        $CIDRAM['Config'][$CIDRAM['Config']['Temp']['KeyCat']] = [];
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
$CIDRAM['IPAddr'] = (
    $CIDRAM['Config']['general']['ipaddr'] !== 'REMOTE_ADDR' && empty($_SERVER[$CIDRAM['Config']['general']['ipaddr']])
) ? 'REMOTE_ADDR' : $CIDRAM['Config']['general']['ipaddr'];

/** Ensure we have an IP address variable to work with. */
if (!isset($_SERVER[$CIDRAM['IPAddr']])) {
    $_SERVER[$CIDRAM['IPAddr']] = '';
}

/** Adjusted present time. */
$CIDRAM['Now'] = time() + ($CIDRAM['Config']['general']['time_offset'] * 60);

/** Set timezone. */
if (!empty($CIDRAM['Config']['general']['timezone']) && $CIDRAM['Config']['general']['timezone'] !== 'SYSTEM') {
    date_default_timezone_set($CIDRAM['Config']['general']['timezone']);
}

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
) ? constant($CIDRAM['Config']['general']['default_algo']) : 1;

/** Revert script ident if "hide_version" is true. */
if (!empty($CIDRAM['Config']['general']['hide_version'])) {
    $CIDRAM['ScriptIdent'] = 'CIDRAM';
}
