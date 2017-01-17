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
 * This file: Temporary hotfixes file (last modified: 2017.01.17).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['ThisFile'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'hotfixes.php');

/** Hotfix for "cidramblocklists.dat" missing file and changed remote path. */
if (true) { // switch 170117
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
        "\nif (true) { // switch 170117\n",
        "\nif (false) {\n",
        $CIDRAM['ThisFile']
    );
}

$CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'hotfixes.php', 'w');
fwrite($CIDRAM['Handle'], $CIDRAM['ThisFile']);
fclose($CIDRAM['Handle']);
