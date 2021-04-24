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
 * This file: Event handlers file (last modified: 2021.04.24).
 */

/**
 * Writes to the standard logfile.
 *
 * @return bool True on success; False on failure.
 */
$CIDRAM['Events']->addHandler('writeToLog', function () use (&$CIDRAM): bool {
    /** Guard. */
    if (
        !$CIDRAM['Config']['general']['logfile'] ||
        !($Filename = $CIDRAM['BuildPath']($CIDRAM['Vault'] . $CIDRAM['Config']['general']['logfile']))
    ) {
        return false;
    }

    $Data = !file_exists($Filename) || (
        $CIDRAM['Config']['general']['truncate'] > 0 &&
        filesize($Filename) >= $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate'])
    ) ? "\x3c\x3fphp die; \x3f\x3e\n\n" : '';
    $WriteMode = !empty($Data) ? 'wb' : 'ab';
    $Data .= $CIDRAM['ParseVars']($CIDRAM['Parsables'], $CIDRAM['FieldTemplates']['Logs'] . "\n");

    $File = fopen($Filename, $WriteMode);
    fwrite($File, $Data);
    fclose($File);
    if ($WriteMode === 'wb') {
        $CIDRAM['LogRotation']($CIDRAM['Config']['general']['logfile']);
    }
    return true;
});

/**
 * Writes to the Apache-style logfile.
 *
 * @return bool True on success; False on failure.
 */
$CIDRAM['Events']->addHandler('writeToLog', function () use (&$CIDRAM): bool {
    /** Guard. */
    if (
        empty($CIDRAM['BlockInfo']) ||
        !$CIDRAM['Config']['general']['logfile_apache'] ||
        !($Filename = $CIDRAM['BuildPath']($CIDRAM['Vault'] . $CIDRAM['Config']['general']['logfile_apache']))
    ) {
        return false;
    }

    $Data = sprintf(
        "%s - - [%s] \"%s %s %s\" %s %s \"%s\" \"%s\"\n",
        $CIDRAM['BlockInfo']['IPAddr'],
        $CIDRAM['BlockInfo']['DateTime'],
        $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN',
        $_SERVER['REQUEST_URI'] ?? '/',
        $_SERVER['SERVER_PROTOCOL'] ?? 'UNKNOWN/x.x',
        $CIDRAM['errCode'],
        strlen($CIDRAM['HTML']),
        $CIDRAM['BlockInfo']['Referrer'] ?? '-',
        $CIDRAM['BlockInfo']['UA'] ?? '-'
    );
    $WriteMode = !file_exists($Filename) || (
        $CIDRAM['Config']['general']['truncate'] > 0 &&
        filesize($Filename) >= $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate'])
    ) ? 'wb' : 'ab';

    $File = fopen($Filename, $WriteMode);
    fwrite($File, $Data);
    fclose($File);
    if ($WriteMode === 'wb') {
        $CIDRAM['LogRotation']($CIDRAM['Config']['general']['logfile_apache']);
    }
    return true;
});

/**
 * Writes to the serialised logfile.
 *
 * @return bool True on success; False on failure.
 */
$CIDRAM['Events']->addHandler('writeToLog', function () use (&$CIDRAM): bool {
    /** Guard. */
    if (
        empty($CIDRAM['BlockInfo']) ||
        !$CIDRAM['Config']['general']['logfile_serialized'] ||
        !($Filename = $CIDRAM['BuildPath']($CIDRAM['Vault'] . $CIDRAM['Config']['general']['logfile_serialized']))
    ) {
        return false;
    }

    $BlockInfo = $CIDRAM['BlockInfo'];
    unset($BlockInfo['EmailAddr'], $BlockInfo['UALC'], $BlockInfo['favicon']);

    /** Remove empty entries prior to serialising. */
    $BlockInfo = array_filter($BlockInfo, function ($Value): bool {
        return !(is_string($Value) && empty($Value));
    });

    $WriteMode = !file_exists($Filename) || (
        $CIDRAM['Config']['general']['truncate'] > 0 &&
        filesize($Filename) >= $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate'])
    ) ? 'wb' : 'ab';

    $File = fopen($Filename, $WriteMode);
    fwrite($File, serialize($BlockInfo) . "\n");
    fclose($File);
    if ($WriteMode === 'wb') {
        $CIDRAM['LogRotation']($CIDRAM['Config']['general']['logfile_serialized']);
    }
    return true;
});

/**
 * Prepares any caught errors for writing to the default error log.
 *
 * @return bool True on success; False on failure.
 */
$CIDRAM['Events']->addHandler('error', function (string $Data) use (&$CIDRAM): bool {
    /** Guard. */
    if (
        !$CIDRAM['Config']['general']['error_log'] ||
        empty($CIDRAM['Stage']) ||
        !$CIDRAM['Request']->inCsv($CIDRAM['Stage'], $CIDRAM['Config']['general']['error_log_stages'])
    ) {
        return false;
    }

    if (!isset($CIDRAM['Pending-Error-Log-Data'])) {
        $CIDRAM['Pending-Error-Log-Data'] = '';
    }
    $Data = unserialize($Data);
    $Message = sprintf(
        '[%s] Error at %s:L%d (error code %d)%s.',
        date('c', time()),
        empty($Data[2]) ? '?' : $Data[2],
        empty($Data[3]) ? 0 : $Data[3],
        empty($Data[0]) ? 0 : $Data[0],
        empty($Data[1]) ? '' : ': "' . $Data[1] . '"'
    );
    if ($CIDRAM['Stage'] === 'Tests') {
        $Message .= ' This was caused by one or more of your currently active signature files. Try running your signature files through the signature fixer, and check over any connected or peripheral extended rules (e.g., any PHP files called via "Run") for mistakes.';
    } elseif ($CIDRAM['Stage'] === 'Aux') {
        $Message .= ' This was caused by an auxiliary rule.';
        if (!empty($Data[1]) && substr($Data[1], 0, 5) === 'preg_') {
            $Message .= ' Please review any regular expressions used as part of your auxiliary rules.';
        }
    } elseif ($CIDRAM['Stage'] === 'Modules' && !empty($Data[2])) {
        $Message .= sprintf(' This was caused by the "%s" module.', $Data[2]);
        if (!empty($Data[1]) && substr($Data[1], 0, 5) === 'preg_' && !empty($Data[3])) {
            $Message .= sprintf(' Please review the regular expression at line %d.', $Data[3]);
        }
    } else {
        $Message .= sprintf(' Eep.. Something went wrong during "%s".', $CIDRAM['Stage']);
    }
    $CIDRAM['Pending-Error-Log-Data'] .= $Message . "\n";
    return true;
});

/**
 * Writes to the default error log.
 *
 * @return bool True on success; False on failure.
 */
$CIDRAM['Events']->addHandler('final', function () use (&$CIDRAM): bool {
    /** Cleanup. */
    unset($CIDRAM['Stage']);

    /** Guard. */
    if (
        !isset($CIDRAM['Pending-Error-Log-Data']) ||
        !$CIDRAM['Config']['general']['error_log'] ||
        !($File = $CIDRAM['BuildPath']($CIDRAM['Vault'] . $CIDRAM['Config']['general']['error_log']))
    ) {
        return false;
    }

    if (!file_exists($File) || !filesize($File) || (
        $CIDRAM['Config']['general']['truncate'] > 0 &&
        filesize($File) >= $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate'])
    )) {
        $WriteMode = 'wb';
        $Data = $CIDRAM['L10N']->getString('error_log_header') . "\n=====\n" . $CIDRAM['Pending-Error-Log-Data'];
    } else {
        $WriteMode = 'ab';
        $Data = $CIDRAM['Pending-Error-Log-Data'];
    }

    $Handle = fopen($File, $WriteMode);
    fwrite($Handle, $Data);
    fclose($Handle);
    if ($WriteMode === 'wb') {
        $CIDRAM['LogRotation']($CIDRAM['Config']['general']['error_log']);
    }
    return true;
});

/**
 * Writes to the PHPMailer event log.
 *
 * @param string $Data What to write.
 * @return bool True on success; False on failure.
 */
$CIDRAM['Events']->addHandler('writeToPHPMailerEventLog', function (string $Data) use (&$CIDRAM): bool {
    /** Guard. */
    if (
        !$CIDRAM['Config']['PHPMailer']['event_log'] ||
        !($EventLog = $CIDRAM['BuildPath']($CIDRAM['Vault'] . $CIDRAM['Config']['PHPMailer']['event_log']))
    ) {
        return false;
    }

    $WriteMode = (!file_exists($EventLog) || (
        $CIDRAM['Config']['general']['truncate'] > 0 &&
        filesize($EventLog) >= $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate'])
    )) ? 'wb' : 'ab';

    $Handle = fopen($EventLog, $WriteMode);
    fwrite($Handle, $Data);
    fclose($Handle);
    if ($WriteMode === 'wb') {
        $CIDRAM['LogRotation']($CIDRAM['Config']['PHPMailer']['event_log']);
    }
    return true;
});
