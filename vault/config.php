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
 * This file: Configuration handler (last modified: 2022.05.07).
 */

/** CIDRAM version number (SemVer). */
$CIDRAM['ScriptVersion'] = '3.0.0';

/** CIDRAM version identifier (complete notation). */
$CIDRAM['ScriptIdent'] = 'CIDRAM v' . $CIDRAM['ScriptVersion'];

/** CIDRAM User Agent (for external requests). */
$CIDRAM['ScriptUA'] = $CIDRAM['ScriptIdent'] . ' (https://cidram.github.io/)';

/** Determine PHP path. */
$CIDRAM['CIDRAM_PHP'] = defined('PHP_BINARY') ? PHP_BINARY : '';

/** Fetch domain segment of HTTP_HOST (needed for writing cookies safely). */
$CIDRAM['HTTP_HOST'] = empty($_SERVER['HTTP_HOST']) ? '' : (
    strpos($_SERVER['HTTP_HOST'], ':') === false ? $_SERVER['HTTP_HOST'] : substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], ':'))
);

/** Allow post override of HTTP_HOST (assists with proxied front-end pages). */
$CIDRAM['HostnameOverride'] = $_POST['hostname'] ?? '';

/** Checks whether the CIDRAM defaults file is readable. */
if (!is_readable($CIDRAM['Vault'] . 'defaults.yml')) {
    header('Content-Type: text/plain');
    die('[CIDRAM] Can\'t read the defaults file! Can\'t continue until this is resolved.');
}

/** Attempts to parse the CIDRAM defaults file. */
$CIDRAM['YAML']->process($CIDRAM['ReadFile']($CIDRAM['Vault'] . 'defaults.yml'), $CIDRAM, 0, true);

/** Kills the script if parsing the configuration defaults file fails. */
if (empty($CIDRAM['Config Defaults'])) {
    header('Content-Type: text/plain');
    die('[CIDRAM] Configuration defaults file is corrupt! Can\'t continue until this is resolved.');
}

if (isset($GLOBALS['CIDRAM_Config'])) {
    /** Provides a means of running tests with configuration values specific to those tests. */
    $CIDRAM['Config'] = $GLOBALS['CIDRAM_Config'];
} else {
    /** Configuration to be populated here. */
    $CIDRAM['Config'] = [];

    /** Attempts to parse the standard CIDRAM configuration file. */
    if (is_readable($CIDRAM['Vault'] . 'config.yml')) {
        $CIDRAM['YAML']->process($CIDRAM['ReadFile']($CIDRAM['Vault'] . 'config.yml'), $CIDRAM['Config']);
    }
}

/** Checks for the existence of the HTTP_HOST "configuration overrides file". */
if (
    !empty($_SERVER['HTTP_HOST']) &&
    ($CIDRAM['Domain'] = preg_replace('/^www\./', '', strtolower($_SERVER['HTTP_HOST']))) &&
    !preg_match('/[^.\da-z-]/', $CIDRAM['Domain']) &&
    is_readable($CIDRAM['Vault'] . $CIDRAM['Domain'] . '.config.yml')
) {
    /** Attempts to parse the overrides file found (this is configuration specific to the requested domain). */
    if ($CIDRAM['YAML']->process($CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['Domain'] . '.config.yml'), $CIDRAM['Overrides'])) {
        array_walk($CIDRAM['Overrides'], function ($Keys, $Category) use (&$CIDRAM) {
            foreach ($Keys as $Directive => $Value) {
                $CIDRAM['Config'][$Category][$Directive] = $Value;
            }
        });
        $CIDRAM['Overrides'] = true;
    } else {
        $CIDRAM['Overrides'] = false;
    }
}

/** Check for supplementary configuration. */
foreach (array_merge(
    $CIDRAM['Supplementary']($CIDRAM['Config']['general']['config_imports'] ?? '', $CIDRAM['Vault']),
    $CIDRAM['Supplementary']($CIDRAM['Config']['signatures']['ipv4'] ?? '', $CIDRAM['SignaturesPath']),
    $CIDRAM['Supplementary']($CIDRAM['Config']['signatures']['ipv6'] ?? '', $CIDRAM['SignaturesPath']),
    $CIDRAM['Supplementary']($CIDRAM['Config']['signatures']['modules'] ?? '', $CIDRAM['ModulesPath'])
) as $CIDRAM['Supplement']) {
    $CIDRAM['YAML']->process($CIDRAM['ReadFile']($CIDRAM['Supplement']), $CIDRAM);
}

/** Cleanup. */
unset($CIDRAM['Supplement']);

/** Perform fallbacks and autotyping for missing configuration directives. */
$CIDRAM['Fallback']($CIDRAM['Config Defaults'], $CIDRAM['Config']);

/** Fetch the IP address of the current request. */
$CIDRAM['IPAddr'] = (new \Maikuolan\Common\IPHeader($CIDRAM['Config']['general']['ipaddr']))->Resolution;

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
) ? constant($CIDRAM['Config']['general']['default_algo']) : PASSWORD_DEFAULT;

/** Instantiate the request class. */
$CIDRAM['Request'] = new \Maikuolan\Common\Request();
$CIDRAM['Request']->DefaultTimeout = $CIDRAM['Config']['general']['default_timeout'];
$CIDRAM['ChannelsDataArray'] = [];
$CIDRAM['YAML']->process($CIDRAM['ReadFile']($CIDRAM['Vault'] . 'channels.yaml'), $CIDRAM['ChannelsDataArray']);
$CIDRAM['Request']->Channels = $CIDRAM['ChannelsDataArray'] ?: [];
unset($CIDRAM['ChannelsDataArray']);
if (!isset($CIDRAM['Request']->Channels['Triggers'])) {
    $CIDRAM['Request']->Channels['Triggers'] = [];
}
$CIDRAM['Request']->Disabled = $CIDRAM['Config']['general']['disabled_channels'];
$CIDRAM['Request']->UserAgent = $CIDRAM['ScriptUA'];
$CIDRAM['Request']->SendToOut = (defined('DEV_DEBUG_MODE') && DEV_DEBUG_MODE === true);

/** CIDRAM favicon. */
foreach (['ico', 'png', 'jpg', 'gif'] as $CIDRAM['favicon_extension']) {
    if (!is_readable($CIDRAM['Vault'] . 'favicon_' . $CIDRAM['Config']['template_data']['theme'] . '.' . $CIDRAM['favicon_extension'])) {
        continue;
    }
    $CIDRAM['favicon'] = base64_encode($CIDRAM['ReadFile'](
        $CIDRAM['Vault'] . 'favicon_' . $CIDRAM['Config']['template_data']['theme'] . '.' . $CIDRAM['favicon_extension']
    ));
}
if (empty($CIDRAM['favicon'])) {
    $CIDRAM['favicon'] =
        'R0lGODlhEAAQAMIBAAAAAGYAAJkAAMz//2YAAGYAAGYAAGYAACH5BAEKAAQALAAAAAAQABAAAANBCLrcKjBK+eKQ' .
        'N76RIb+g0oGewAmiZZbZRppnC0y0BgR4rutK8OWfn2jgI3KKxeHvyBwMkc0kIEp13nZYnGPLSAAAOw==';
}

/** If the language directive is empty, default to English. */
if (empty($CIDRAM['Config']['general']['lang'])) {
    $CIDRAM['Config']['general']['lang'] = 'en';
}

/** Load CIDRAM core L10N data. */
$CIDRAM['LoadL10N']($CIDRAM['Vault'] . 'l10n' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR);

/** Used to format numbers according to the specified configuration. */
$CIDRAM['NumberFormatter'] = new \Maikuolan\Common\NumberFormatter($CIDRAM['Config']['general']['numbers']);

/** Used to ensure correct encoding, hide bad data, etc. */
$CIDRAM['Demojibakefier'] = new \Maikuolan\Common\Demojibakefier();
