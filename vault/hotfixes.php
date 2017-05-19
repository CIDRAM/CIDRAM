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
 * This file: Temporary hotfixes file (last modified: 2017.05.19).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** Fetch temporary hotfixes file raw data. */
$CIDRAM['ThisFile'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'hotfixes.php');

/** Flag for updating switches. */
$CIDRAM['Hotfixed'] = false;

/** Hotfix for missing "cidramblocklists.dat" file and changed remote path. */
if (true) { // switch 170117-1
    $CIDRAM['HotfixData'] = '';

    if (file_exists($CIDRAM['Vault'] . 'cidramblocklists.dat')) {
        $CIDRAM['OriginalData'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'cidramblocklists.dat');
        $CIDRAM['HotfixData'] = str_ireplace('/zbblock-range-blocks/', '/blocklists/', $CIDRAM['OriginalData']);
    } else {
        $CIDRAM['OriginalData'] = '';
        $CIDRAM['HotfixData'] = $CIDRAM['Request'](
            'https://raw.githubusercontent.com/Maikuolan/CIDRAM/master/vault/cidramblocklists.dat'
        );
    }

    if ($CIDRAM['HotfixData'] && $CIDRAM['HotfixData'] !== $CIDRAM['OriginalData']) {
        $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'cidramblocklists.dat', 'w');
        fwrite($CIDRAM['Handle'], $CIDRAM['HotfixData']);
        fclose($CIDRAM['Handle']);
    }

    /** Update switch. */
    $CIDRAM['ThisFile'] = str_replace(
        "\nif (true) { // switch 170117-1\n",
        "\nif (false) {\n",
        $CIDRAM['ThisFile']
    );
    $CIDRAM['Hotfixed'] = true;
}

/** Hotfix for missing "modules.dat" file. */
if (true) { // switch 170117-2
    $CIDRAM['HotfixData'] = '';

    if (!file_exists($CIDRAM['Vault'] . 'modules.dat')) {
        if ($CIDRAM['HotfixData'] = $CIDRAM['Request'](
            'https://raw.githubusercontent.com/Maikuolan/CIDRAM/master/vault/modules.dat'
        )) {
            $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'modules.dat', 'w');
            fwrite($CIDRAM['Handle'], $CIDRAM['HotfixData']);
            fclose($CIDRAM['Handle']);
        }
    }

    /** Update switch. */
    $CIDRAM['ThisFile'] = str_replace(
        "\nif (true) { // switch 170117-2\n",
        "\nif (false) {\n",
        $CIDRAM['ThisFile']
    );
    $CIDRAM['Hotfixed'] = true;
}

/** Hotfix for missing "themes.dat" file. */
if (true) { // switch 170519
    $CIDRAM['HotfixData'] = '';

    if (!file_exists($CIDRAM['Vault'] . 'themes.dat')) {
        if ($CIDRAM['HotfixData'] = $CIDRAM['Request'](
            'https://raw.githubusercontent.com/Maikuolan/CIDRAM/master/vault/themes.dat'
        )) {
            $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'themes.dat', 'w');
            fwrite($CIDRAM['Handle'], $CIDRAM['HotfixData']);
            fclose($CIDRAM['Handle']);
        }
    }

    /** Update switch. */
    $CIDRAM['ThisFile'] = str_replace(
        "\nif (true) { // switch 170519\n",
        "\nif (false) {\n",
        $CIDRAM['ThisFile']
    );
    $CIDRAM['Hotfixed'] = true;
}

/** Temporary hotfix for duplicated component entries. */
if (true) { // switch 170129
    /** An array listing our DAT files. */
    $CIDRAM['DATs'] = array('components.dat', 'cidramblocklists.dat', 'modules.dat', 'themes.dat');

    /** Iterate and fix. */
    array_walk($CIDRAM['DATs'], function ($DAT) use (&$CIDRAM) {
        if (
            is_readable($CIDRAM['Vault'] . $DAT) &&
            ($Data = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $DAT))
        ) {
            if (($DLN = strpos($Data, "\n\n")) === false) {
                $NewData = $Data . "\n\n";
            } else {
                $NewData = substr($Data, 0, $DLN) . "\n\n";
            }
            if (($NewData !== $Data) && ($CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . $DAT, 'w'))) {
                fwrite($CIDRAM['Handle'], $NewData);
                fclose($CIDRAM['Handle']);
            }
        }
    });

    /** Update switch. */
    $CIDRAM['ThisFile'] = str_replace(
        "\nif (true) { // switch 170129\n",
        "\nif (false) {\n",
        $CIDRAM['ThisFile']
    );
    $CIDRAM['Hotfixed'] = true;
}

/**
 * Update plugin version cited in the WordPress plugins dashboard, if this copy
 * of CIDRAM is running as a WordPress plugin.
 */
if (true) { // switch WP-1.0.0-DEV
    if (
        file_exists($CIDRAM['Vault'] . '../cidram.php') &&
        is_readable($CIDRAM['Vault'] . '../cidram.php') &&
        ($CIDRAM['ThisData'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . '../cidram.php'))
    ) {
        $CIDRAM['PlugHead'] = "\x3c\x3fphp\n/**\n * Plugin Name: CIDRAM\n * Version: ";
        if (substr($CIDRAM['ThisData'], 0, 45) === $CIDRAM['PlugHead']) {
            $CIDRAM['PlugHeadEnd'] = strpos($CIDRAM['ThisData'], "\n", 45);
            $CIDRAM['ThisData'] = $CIDRAM['PlugHead'] . $CIDRAM['ScriptVersion'] . substr($CIDRAM['ThisData'], $CIDRAM['PlugHeadEnd']);
            $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . '../cidram.php', 'w');
            fwrite($CIDRAM['Handle'], $CIDRAM['ThisData']);
            fclose($CIDRAM['Handle']);
        }
    }

    /** Update switch. */
    $CIDRAM['ThisFile'] = str_replace(
        "\nif (true) { // switch WP-1.0.0-DEV\n",
        "\nif (false) {\n",
        $CIDRAM['ThisFile']
    );
    $CIDRAM['Hotfixed'] = true;
}

/** Update temporary hotfixes file switches. */
if ($CIDRAM['Hotfixed']) {
    $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'hotfixes.php', 'w');
    fwrite($CIDRAM['Handle'], $CIDRAM['ThisFile']);
    fclose($CIDRAM['Handle']);
}
