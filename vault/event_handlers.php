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
 * This file: Event handlers file (last modified: 2019.09.24).
 */

/**
 * Writes to the standard logfile.
 *
 * @return bool True on success; False on failure.
 */
$CIDRAM['Events']->addHandler('writeToLog', function () use (&$CIDRAM): bool {

    /** Guard. */
    if (!$CIDRAM['Config']['general']['logfile'] || empty($CIDRAM['LogFileNames']['logfile'])) {
        return false;
    }

    $Data = !file_exists($CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfile']) || (
        $CIDRAM['Config']['general']['truncate'] > 0 &&
        filesize($CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfile']) >= $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate'])
    ) ? "\x3c\x3fphp die; \x3f\x3e\n\n" : '';
    $WriteMode = !empty($Data) ? 'w' : 'a';
    $Data .= $CIDRAM['ParseVars']($CIDRAM['Parsables'], $CIDRAM['FieldTemplates']['Logs'] . "\n");

    /** Build the path to the log and write it. */
    if ($CIDRAM['BuildLogPath']($CIDRAM['LogFileNames']['logfile'])) {
        $File = fopen($CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfile'], $WriteMode);
        fwrite($File, $Data);
        fclose($File);
        if ($WriteMode === 'w') {
            $CIDRAM['LogRotation']($CIDRAM['Config']['general']['logfile']);
        }
        return true;
    }

    return false;
});

/**
 * Writes to the Apache-style logfile.
 *
 * @return bool True on success; False on failure.
 */
$CIDRAM['Events']->addHandler('writeToLog', function () use (&$CIDRAM): bool {

    /** Guard. */
    if (
        !$CIDRAM['Config']['general']['logfile_apache'] ||
        empty($CIDRAM['LogFileNames']['logfile_apache']) ||
        empty($CIDRAM['BlockInfo'])
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
    $WriteMode = !file_exists($CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfile_apache']) || (
        $CIDRAM['Config']['general']['truncate'] > 0 &&
        filesize($CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfile_apache']) >= $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate'])
    ) ? 'w' : 'a';

    /** Build the path to the log and write it. */
    if ($CIDRAM['BuildLogPath']($CIDRAM['LogFileNames']['logfile_apache'])) {
        $File = fopen($CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfile_apache'], $WriteMode);
        fwrite($File, $Data);
        fclose($File);
        if ($WriteMode === 'w') {
            $CIDRAM['LogRotation']($CIDRAM['Config']['general']['logfile_apache']);
        }
        return true;
    }

    return false;
});

/**
 * Writes to the serialised logfile.
 *
 * @return bool True on success; False on failure.
 */
$CIDRAM['Events']->addHandler('writeToLog', function () use (&$CIDRAM): bool {

    /** Guard. */
    if (
        !$CIDRAM['Config']['general']['logfile_serialized'] ||
        empty($CIDRAM['LogFileNames']['logfile_serialized']) ||
        empty($CIDRAM['BlockInfo'])
    ) {
        return false;
    }

    $BlockInfo = $CIDRAM['BlockInfo'];
    unset($BlockInfo['EmailAddr'], $BlockInfo['UALC'], $BlockInfo['favicon']);
    /** Remove empty entries prior to serialising. */
    $BlockInfo = array_filter($BlockInfo, function ($Value) {
        return !(is_string($Value) && empty($Value));
    });
    $WriteMode = !file_exists($CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfile_serialized']) || (
        $CIDRAM['Config']['general']['truncate'] > 0 &&
        filesize($CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfile_serialized']) >= $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate'])
    ) ? 'w' : 'a';

    /** Build the path to the log and write it. */
    if ($CIDRAM['BuildLogPath']($CIDRAM['LogFileNames']['logfile_serialized'])) {
        $File = fopen($CIDRAM['Vault'] . $CIDRAM['LogFileNames']['logfile_serialized'], $WriteMode);
        fwrite($File, serialize($BlockInfo) . "\n");
        fclose($File);
        if ($WriteMode === 'w') {
            $CIDRAM['LogRotation']($CIDRAM['Config']['general']['logfile_serialized']);
        }
        return true;
    }

    return false;
});

/**
 * Writing to the reCAPTCHA logfile (if this has been enabled).
 *
 * @return bool True on success; False on failure.
 */
$CIDRAM['Events']->addHandler('reCaptchaLog', function () use (&$CIDRAM): bool {

    /** Guard. */
    if (!$CIDRAM['Config']['recaptcha']['logfile'] || !$CIDRAM['reCAPTCHA']['Loggable'] || empty($CIDRAM['BlockInfo'])) {
        return false;
    }

    /** Applies formatting for dynamic log filenames. */
    $Filename = $CIDRAM['TimeFormat']($CIDRAM['Now'], $CIDRAM['Config']['recaptcha']['logfile']);

    $WriteMode = !file_exists($CIDRAM['Vault'] . $Filename) || (
        $CIDRAM['Config']['general']['truncate'] > 0 &&
        filesize($CIDRAM['Vault'] . $Filename) >= $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate'])
    ) ? 'w' : 'a';
    $Data = sprintf(
        "%1\$s%2\$s - %3\$s%4\$s - %5\$s%6\$s\n",
        $CIDRAM['L10N']->getString('field_ipaddr'),
        $CIDRAM['Config']['legal']['pseudonymise_ip_addresses'] ? $CIDRAM['Pseudonymise-IP']($_SERVER[$CIDRAM['IPAddr']]) : $_SERVER[$CIDRAM['IPAddr']],
        $CIDRAM['L10N']->getString('field_datetime'),
        $CIDRAM['BlockInfo']['DateTime'],
        $CIDRAM['L10N']->getString('field_reCAPTCHA_state'),
        $CIDRAM['BlockInfo']['reCAPTCHA']
    );

    /** Adds a second newline to match the standard block events logfile in case of combining the logfiles. */
    if ($CIDRAM['Config']['recaptcha']['logfile'] === $CIDRAM['Config']['general']['logfile']) {
        $Data .= "\n";
    }

    /** Build the path to the log and write it. */
    if ($CIDRAM['BuildLogPath']($Filename)) {
        $File = fopen($CIDRAM['Vault'] . $Filename, $WriteMode);
        fwrite($File, $Data);
        fclose($File);
        if ($WriteMode === 'w') {
            $CIDRAM['LogRotation']($CIDRAM['Config']['recaptcha']['logfile']);
        }
        return true;
    }

    return false;
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
        !$CIDRAM['in_csv']($CIDRAM['Stage'], $CIDRAM['Config']['general']['error_log_stages'])
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
    if (!$CIDRAM['Config']['general']['error_log'] || !isset($CIDRAM['Pending-Error-Log-Data'])) {
        return false;
    }

    /** Applies formatting for dynamic log filenames. */
    $File = $CIDRAM['TimeFormat']($CIDRAM['Now'], $CIDRAM['Config']['general']['error_log']);

    if (!file_exists($CIDRAM['Vault'] . $File) || !filesize($CIDRAM['Vault'] . $File) || (
        $CIDRAM['Config']['general']['truncate'] > 0 &&
        filesize($CIDRAM['Vault'] . $File) >= $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate'])
    )) {
        $WriteMode = 'w';
        $Data = $CIDRAM['L10N']->getString('error_log_header') . "\n=====\n" . $CIDRAM['Pending-Error-Log-Data'];
    } else {
        $WriteMode = 'a';
        $Data = $CIDRAM['Pending-Error-Log-Data'];
    }

    /** Build the path to the log and write it. */
    if ($CIDRAM['BuildLogPath']($File)) {
        $Handle = fopen($CIDRAM['Vault'] . $File, $WriteMode);
        fwrite($Handle, $Data);
        fclose($Handle);
        if ($WriteMode === 'w') {
            $CIDRAM['LogRotation']($CIDRAM['Config']['general']['error_log']);
        }
        return true;
    }

    return false;
});

/**
 * Writes to the PHPMailer event log.
 *
 * @param string $Data What to write.
 * @return bool True on success; False on failure.
 */
$CIDRAM['Events']->addHandler('writeToPHPMailerEventLog', function (string $Data) use (&$CIDRAM): bool {

    /** Guard. */
    if (!$CIDRAM['Config']['PHPMailer']['event_log']) {
        return false;
    }

    /** Applies formatting for dynamic log filenames. */
    $EventLog = $CIDRAM['TimeFormat']($CIDRAM['Now'], $CIDRAM['Config']['PHPMailer']['event_log']);

    $WriteMode = (!file_exists($CIDRAM['Vault'] . $EventLog) || (
        $CIDRAM['Config']['general']['truncate'] > 0 &&
        filesize($CIDRAM['Vault'] . $EventLog) >= $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate'])
    )) ? 'w' : 'a';

    /** Build the path to the log and write it. */
    if ($CIDRAM['BuildLogPath']($EventLog)) {
        $Handle = fopen($CIDRAM['Vault'] . $EventLog, $WriteMode);
        fwrite($Handle, $Data);
        fclose($Handle);
        if ($WriteMode === 'w') {
            $CIDRAM['LogRotation']($CIDRAM['Config']['PHPMailer']['event_log']);
        }
        return true;
    }

    return false;
});
