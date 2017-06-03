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
 * This file: Temporary hotfixes file (last modified: 2017.06.03).
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
            'https://raw.githubusercontent.com/CIDRAM/CIDRAM/master/vault/cidramblocklists.dat'
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

/** Hotfix for missing "components.dat" file and changed remote path. */
if (true) { // switch 170603-1
    $CIDRAM['HotfixData'] = '';

    if (file_exists($CIDRAM['Vault'] . 'components.dat')) {
        $CIDRAM['OriginalData'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'components.dat');
        $CIDRAM['HotfixData'] = str_ireplace('Maikuolan/CIDRAM', 'CIDRAM/CIDRAM', $CIDRAM['OriginalData']);
    } else {
        $CIDRAM['OriginalData'] = '';
        $CIDRAM['HotfixData'] = $CIDRAM['Request'](
            'https://raw.githubusercontent.com/CIDRAM/CIDRAM/master/vault/components.dat'
        );
    }

    if ($CIDRAM['HotfixData'] && $CIDRAM['HotfixData'] !== $CIDRAM['OriginalData']) {
        $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'components.dat', 'w');
        fwrite($CIDRAM['Handle'], $CIDRAM['HotfixData']);
        fclose($CIDRAM['Handle']);
    }

    /** Update switch. */
    $CIDRAM['ThisFile'] = str_replace(
        "\nif (true) { // switch 170603-1\n",
        "\nif (false) {\n",
        $CIDRAM['ThisFile']
    );
    $CIDRAM['Hotfixed'] = true;
}

/** Hotfix for missing "modules.dat" file and changed remote path. */
if (true) { // switch 170603-2
    $CIDRAM['HotfixData'] = '';

    if (file_exists($CIDRAM['Vault'] . 'modules.dat')) {
        $CIDRAM['OriginalData'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'modules.dat');
        $CIDRAM['HotfixData'] = str_ireplace('Maikuolan/CIDRAM-Extras', 'CIDRAM/CIDRAM-Extras', $CIDRAM['OriginalData']);
    } else {
        $CIDRAM['OriginalData'] = '';
        $CIDRAM['HotfixData'] = $CIDRAM['Request'](
            'https://raw.githubusercontent.com/CIDRAM/CIDRAM/master/vault/modules.dat'
        );
    }

    if ($CIDRAM['HotfixData'] && $CIDRAM['HotfixData'] !== $CIDRAM['OriginalData']) {
        $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'modules.dat', 'w');
        fwrite($CIDRAM['Handle'], $CIDRAM['HotfixData']);
        fclose($CIDRAM['Handle']);
    }

    /** Update switch. */
    $CIDRAM['ThisFile'] = str_replace(
        "\nif (true) { // switch 170603-2\n",
        "\nif (false) {\n",
        $CIDRAM['ThisFile']
    );
    $CIDRAM['Hotfixed'] = true;
}

/** Hotfix for missing "themes.dat" file and changed remote path. */
if (true) { // switch 170603-3
    $CIDRAM['HotfixData'] = '';

    if (file_exists($CIDRAM['Vault'] . 'themes.dat')) {
        $CIDRAM['OriginalData'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'themes.dat');
        $CIDRAM['HotfixData'] = str_ireplace('Maikuolan/CIDRAM-Extras', 'CIDRAM/CIDRAM-Extras', $CIDRAM['OriginalData']);
    } else {
        $CIDRAM['OriginalData'] = '';
        $CIDRAM['HotfixData'] = $CIDRAM['Request'](
            'https://raw.githubusercontent.com/CIDRAM/CIDRAM/master/vault/themes.dat'
        );
    }

    if ($CIDRAM['HotfixData'] && $CIDRAM['HotfixData'] !== $CIDRAM['OriginalData']) {
        $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'themes.dat', 'w');
        fwrite($CIDRAM['Handle'], $CIDRAM['HotfixData']);
        fclose($CIDRAM['Handle']);
    }

    /** Update switch. */
    $CIDRAM['ThisFile'] = str_replace(
        "\nif (true) { // switch 170603-3\n",
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
if (true) { // switch WP-1.0.0
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
        "\nif (true) { // switch WP-1.0.0\n",
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
