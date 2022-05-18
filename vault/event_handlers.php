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
 * This file: Event handlers file (last modified: 2022.05.18).
 */

/**
 * Writes to the standard logfile.
 *
 * @return bool True on success; False on failure.
 */
$this->Events->addHandler('writeToLog', function (): bool {
    /** Guard. */
    if (
        $this->Configuration['general']['logfile'] === '' ||
        !($Filename = $this->buildPath($this->Vault . $this->Configuration['general']['logfile']))
    ) {
        return false;
    }

    $Truncate = $this->readBytes($this->Configuration['general']['truncate']);
    $Data = !file_exists($Filename) || $Truncate > 0 && filesize($Filename) >= $Truncate ? "\x3c\x3fphp die; \x3f\x3e\n\n" : '';
    $WriteMode = !empty($Data) ? 'wb' : 'ab';
    $Data .= $this->parseVars($this->CIDRAM['Parsables'], $this->CIDRAM['FieldTemplates']['Logs'] . "\n");

    $File = fopen($Filename, $WriteMode);
    fwrite($File, $Data);
    fclose($File);
    if ($WriteMode === 'wb') {
        $this->logRotation($this->Configuration['general']['logfile']);
    }
    return true;
});

/**
 * Writes to the Apache-style logfile.
 *
 * @return bool True on success; False on failure.
 */
$this->Events->addHandler('writeToLog', function (): bool {
    /** Guard. */
    if (
        empty($this->BlockInfo) ||
        $this->Configuration['general']['logfile_apache'] === '' ||
        !($Filename = $this->buildPath($this->Vault . $this->Configuration['general']['logfile_apache']))
    ) {
        return false;
    }

    $Data = sprintf(
        "%s - - [%s] \"%s %s %s\" %s %s \"%s\" \"%s\"\n",
        $this->BlockInfo['IPAddr'],
        $this->BlockInfo['DateTime'],
        $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN',
        $_SERVER['REQUEST_URI'] ?? '/',
        $_SERVER['SERVER_PROTOCOL'] ?? 'UNKNOWN/x.x',
        $this->CIDRAM['errCode'],
        strlen($this->CIDRAM['HTML']),
        $this->BlockInfo['Referrer'] ?? '-',
        $this->BlockInfo['UA'] ?? '-'
    );
    $Truncate = $this->readBytes($this->Configuration['general']['truncate']);
    $WriteMode = !file_exists($Filename) || $Truncate > 0 && filesize($Filename) >= $Truncate ? 'wb' : 'ab';

    $File = fopen($Filename, $WriteMode);
    fwrite($File, $Data);
    fclose($File);
    if ($WriteMode === 'wb') {
        $this->logRotation($this->Configuration['general']['logfile_apache']);
    }
    return true;
});

/**
 * Writes to the serialised logfile.
 *
 * @return bool True on success; False on failure.
 */
$this->Events->addHandler('writeToLog', function (): bool {
    /** Guard. */
    if (
        empty($this->BlockInfo) ||
        $this->Configuration['general']['logfile_serialized'] === '' ||
        !($Filename = $this->buildPath($this->Vault . $this->Configuration['general']['logfile_serialized']))
    ) {
        return false;
    }

    $BlockInfo = $this->BlockInfo;
    unset($BlockInfo['EmailAddr'], $BlockInfo['UALC'], $BlockInfo['favicon']);

    /** Remove empty entries prior to serialising. */
    $BlockInfo = array_filter($BlockInfo, function ($Value): bool {
        return !(is_string($Value) && empty($Value));
    });

    $Truncate = $this->readBytes($this->Configuration['general']['truncate']);
    $WriteMode = !file_exists($Filename) || $Truncate > 0 && filesize($Filename) >= $Truncate ? 'wb' : 'ab';

    $File = fopen($Filename, $WriteMode);
    fwrite($File, serialize($BlockInfo) . "\n");
    fclose($File);
    if ($WriteMode === 'wb') {
        $this->logRotation($this->Configuration['general']['logfile_serialized']);
    }
    return true;
});

/**
 * Prepares any caught errors for writing to the default error log.
 *
 * @return bool True on success; False on failure.
 */
$this->Events->addHandler('error', function (string $Data): bool {
    /** Guard. */
    if (
        $this->Configuration['general']['error_log'] === '' ||
        !isset($this->CIDRAM['Stage'], $this->CIDRAM['Stages'], $this->CIDRAM['Stages'][$this->CIDRAM['Stage'] . ':LogErrors'])
    ) {
        return false;
    }

    if (!isset($this->CIDRAM['Pending-Error-Log-Data'])) {
        $this->CIDRAM['Pending-Error-Log-Data'] = '';
    }
    $Data = unserialize($Data) ?: [];
    $Message = sprintf(
        '[%s] Error at %s:L%d (error code %d)%s.',
        date('c', time()),
        empty($Data[2]) ? '?' : $Data[2],
        empty($Data[3]) ? 0 : $Data[3],
        empty($Data[0]) ? 0 : $Data[0],
        empty($Data[1]) ? '' : ': "' . $Data[1] . '"'
    );
    if ($this->CIDRAM['Stage'] === 'Tests') {
        $Message .= ' This was caused by one or more of your currently active signature files. Try running your signature files through the signature fixer, and check over any connected or peripheral extended rules (e.g., any PHP files called via "Run") for mistakes.';
    } elseif ($this->CIDRAM['Stage'] === 'Aux') {
        $Message .= ' This was caused by an auxiliary rule.';
        if (!empty($Data[1]) && substr($Data[1], 0, 5) === 'preg_') {
            $Message .= ' Please review any regular expressions used as part of your auxiliary rules.';
        }
    } elseif ($this->CIDRAM['Stage'] === 'Modules' && !empty($Data[2])) {
        $Message .= sprintf(' This was caused by the "%s" module.', $Data[2]);
        if (!empty($Data[1]) && substr($Data[1], 0, 5) === 'preg_' && !empty($Data[3])) {
            $Message .= sprintf(' Please review the regular expression at line %d.', $Data[3]);
        }
    } else {
        $Message .= sprintf(' Eep.. Something went wrong during "%s".', $this->CIDRAM['Stage']);
    }
    $this->CIDRAM['Pending-Error-Log-Data'] .= $Message . "\n";
    return true;
});

/**
 * Writes to the default error log.
 *
 * @return bool True on success; False on failure.
 */
$this->Events->addHandler('final', function (): bool {
    /** Cleanup. */
    unset($this->CIDRAM['Stage']);

    /** Guard. */
    if (
        !isset($this->CIDRAM['Pending-Error-Log-Data']) ||
        $this->Configuration['general']['error_log'] === '' ||
        !($File = $this->buildPath($this->Vault . $this->Configuration['general']['error_log']))
    ) {
        return false;
    }

    $Truncate = $this->readBytes($this->Configuration['general']['truncate']);
    if (!file_exists($File) || !filesize($File) || $Truncate > 0 && filesize($File) >= $Truncate) {
        $WriteMode = 'wb';
        $Data = $this->L10N->getString('error_log_header') . "\n=====\n" . $this->CIDRAM['Pending-Error-Log-Data'];
    } else {
        $WriteMode = 'ab';
        $Data = $this->CIDRAM['Pending-Error-Log-Data'];
    }

    $Handle = fopen($File, $WriteMode);
    fwrite($Handle, $Data);
    fclose($Handle);
    if ($WriteMode === 'wb') {
        $this->logRotation($this->Configuration['general']['error_log']);
    }
    return true;
});

/**
 * Writes to the PHPMailer event log.
 *
 * @param string $Data What to write.
 * @return bool True on success; False on failure.
 */
$this->Events->addHandler('writeToPHPMailerEventLog', function (string $Data): bool {
    /** Guard. */
    if (
        $this->Configuration['PHPMailer']['event_log'] === '' ||
        !($EventLog = $this->buildPath($this->Vault . $this->Configuration['PHPMailer']['event_log']))
    ) {
        return false;
    }

    $Truncate = $this->readBytes($this->Configuration['general']['truncate']);
    $WriteMode = (!file_exists($EventLog) || $Truncate > 0 && filesize($EventLog) >= $Truncate) ? 'wb' : 'ab';
    $Handle = fopen($EventLog, $WriteMode);
    fwrite($Handle, $Data);
    fclose($Handle);
    if ($WriteMode === 'wb') {
        $this->logRotation($this->Configuration['PHPMailer']['event_log']);
    }
    return true;
});

/**
 * Writes to the signatures update event log.
 *
 * @param string $Data What to write.
 * @return bool True on success; False on failure.
 */
$this->Events->addHandler('writeToSignaturesUpdateEventLog', function (string $Data): bool {
    /** Guard. */
    if (
        $this->Configuration['general']['signatures_update_event_log'] === '' ||
        !($UpdatesLog = $this->buildPath($this->Vault . $this->Configuration['general']['signatures_update_event_log']))
    ) {
        return false;
    }

    /** Add lines based on whether the path is shared with other logs. */
    if ($this->Configuration['general']['signatures_update_event_log'] === $this->Configuration['general']['logfile']) {
        $Data .= "\n\n";
    } else {
        $Data .= "\n";
    }

    $Truncate = $this->readBytes($this->Configuration['general']['truncate']);
    $WriteMode = (!file_exists($UpdatesLog) || $Truncate > 0 && filesize($UpdatesLog) >= $Truncate) ? 'wb' : 'ab';
    $Handle = fopen($UpdatesLog, $WriteMode);
    fwrite($Handle, $Data);
    fclose($Handle);
    if ($WriteMode === 'wb') {
        $this->logRotation($this->Configuration['general']['signatures_update_event_log']);
    }
    return true;
});
