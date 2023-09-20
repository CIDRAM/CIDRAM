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
 * This file: Default event handlers (last modified: 2023.09.19).
 */

/**
 * Writes to the standard log file.
 *
 * @return bool True on success; False on failure.
 */
$this->Events->addHandler('writeToLog', function (): bool {
    /** Guard. */
    if (
        $this->Configuration['logging']['standard_log'] === '' ||
        !empty($this->CIDRAM['Suppress logging']) ||
        !($Filename = $this->buildPath($this->Vault . $this->Configuration['logging']['standard_log']))
    ) {
        return false;
    }

    $Truncate = $this->readBytes($this->Configuration['logging']['truncate']);
    $Data = !file_exists($Filename) || $Truncate > 0 && filesize($Filename) >= $Truncate ? "\x3c\x3fphp die; \x3f\x3e\n\n" : '';
    $WriteMode = !empty($Data) ? 'wb' : 'ab';
    $Data .= $this->parseVars($this->CIDRAM['Parsables'], $this->CIDRAM['FieldTemplates']['Logs'] . "\n");

    if (!is_resource($File = fopen($Filename, $WriteMode))) {
        trigger_error('The "writeToLog" event failed to open "' . $Filename . '" for writing.');
        return false;
    }
    fwrite($File, $Data);
    fclose($File);
    if ($WriteMode === 'wb') {
        $this->logRotation($this->Configuration['logging']['standard_log']);
    }
    return true;
});

/**
 * Writes to the Apache-style log file.
 *
 * @return bool True on success; False on failure.
 */
$this->Events->addHandler('writeToLog', function (): bool {
    /** Guard. */
    if (
        empty($this->BlockInfo) ||
        $this->Configuration['logging']['apache_style_log'] === '' ||
        !empty($this->CIDRAM['Suppress logging']) ||
        !($Filename = $this->buildPath($this->Vault . $this->Configuration['logging']['apache_style_log']))
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
    $Truncate = $this->readBytes($this->Configuration['logging']['truncate']);
    $WriteMode = !file_exists($Filename) || $Truncate > 0 && filesize($Filename) >= $Truncate ? 'wb' : 'ab';

    if (!is_resource($File = fopen($Filename, $WriteMode))) {
        trigger_error('The "writeToLog" event failed to open "' . $Filename . '" for writing.');
        return false;
    }
    fwrite($File, $Data);
    fclose($File);
    if ($WriteMode === 'wb') {
        $this->logRotation($this->Configuration['logging']['apache_style_log']);
    }
    return true;
});

/**
 * Writes to the serialised log file.
 *
 * @return bool True on success; False on failure.
 */
$this->Events->addHandler('writeToLog', function (): bool {
    /** Guard. */
    if (
        empty($this->BlockInfo) ||
        $this->Configuration['logging']['serialised_log'] === '' ||
        !empty($this->CIDRAM['Suppress logging']) ||
        !($Filename = $this->buildPath($this->Vault . $this->Configuration['logging']['serialised_log']))
    ) {
        return false;
    }

    $BlockInfo = $this->BlockInfo;
    unset($BlockInfo['EmailAddr'], $BlockInfo['UALC'], $BlockInfo['favicon']);

    /** Remove empty entries prior to serialising. */
    $BlockInfo = array_filter($BlockInfo, function ($Value): bool {
        return !(is_string($Value) && empty($Value));
    });

    $Truncate = $this->readBytes($this->Configuration['logging']['truncate']);
    $WriteMode = !file_exists($Filename) || $Truncate > 0 && filesize($Filename) >= $Truncate ? 'wb' : 'ab';

    if (!is_resource($File = fopen($Filename, $WriteMode))) {
        trigger_error('The "writeToLog" event failed to open "' . $Filename . '" for writing.');
        return false;
    }
    fwrite($File, serialize($BlockInfo) . "\n");
    fclose($File);
    if ($WriteMode === 'wb') {
        $this->logRotation($this->Configuration['logging']['serialised_log']);
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
        $this->Configuration['logging']['error_log'] === '' ||
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
        $this->Configuration['logging']['error_log'] === '' ||
        !($File = $this->buildPath($this->Vault . $this->Configuration['logging']['error_log']))
    ) {
        return false;
    }

    $Truncate = $this->readBytes($this->Configuration['logging']['truncate']);
    if (!file_exists($File) || !filesize($File) || $Truncate > 0 && filesize($File) >= $Truncate) {
        $WriteMode = 'wb';
        $Data = $this->L10N->getString('error_log_header') . "\n=====\n" . $this->CIDRAM['Pending-Error-Log-Data'];
    } else {
        $WriteMode = 'ab';
        $Data = $this->CIDRAM['Pending-Error-Log-Data'];
    }

    if (!is_resource($Handle = fopen($File, $WriteMode))) {
        return false;
    }
    fwrite($Handle, $Data);
    fclose($Handle);
    if ($WriteMode === 'wb') {
        $this->logRotation($this->Configuration['logging']['error_log']);
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
        $this->Configuration['frontend']['signatures_update_event_log'] === '' ||
        !($UpdatesLog = $this->buildPath($this->Vault . $this->Configuration['frontend']['signatures_update_event_log']))
    ) {
        return false;
    }

    /** Add lines based on whether the path is shared with other logs. */
    if ($this->Configuration['frontend']['signatures_update_event_log'] === $this->Configuration['logging']['standard_log']) {
        $Data .= "\n\n";
    } else {
        $Data .= "\n";
    }

    $Truncate = $this->readBytes($this->Configuration['logging']['truncate']);
    $WriteMode = (!file_exists($UpdatesLog) || $Truncate > 0 && filesize($UpdatesLog) >= $Truncate) ? 'wb' : 'ab';
    if (!is_resource($Handle = fopen($UpdatesLog, $WriteMode))) {
        trigger_error('The "writeToSignaturesUpdateEventLog" event failed to open "' . $UpdatesLog . '" for writing.');
        return false;
    }
    fwrite($Handle, $Data);
    fclose($Handle);
    if ($WriteMode === 'wb') {
        $this->logRotation($this->Configuration['frontend']['signatures_update_event_log']);
    }
    return true;
});

/**
 * Build default log paths.
 *
 * @return true
 */
$this->Events->addHandler('isLogFile', function (): bool {
    if (!isset($this->CIDRAM['LogPatterns'])) {
        $this->CIDRAM['LogPatterns'] = [];
    }
    if ($this->Configuration['logging']['standard_log'] !== '') {
        $this->CIDRAM['LogPatterns'][] = $this->buildLogPattern($this->Configuration['logging']['standard_log'], true);
    }
    if ($this->Configuration['logging']['apache_style_log'] !== '') {
        $this->CIDRAM['LogPatterns'][] = $this->buildLogPattern($this->Configuration['logging']['apache_style_log'], true);
    }
    if ($this->Configuration['logging']['serialised_log'] !== '') {
        $this->CIDRAM['LogPatterns'][] = $this->buildLogPattern($this->Configuration['logging']['serialised_log'], true);
    }
    if ($this->Configuration['logging']['error_log'] !== '') {
        $this->CIDRAM['LogPatterns'][] = $this->buildLogPattern($this->Configuration['logging']['error_log'], true);
    }
    if ($this->Configuration['logging']['outbound_request_log'] !== '') {
        $this->CIDRAM['LogPatterns'][] = $this->buildLogPattern($this->Configuration['logging']['outbound_request_log'], true);
    }
    if ($this->Configuration['logging']['report_log'] !== '') {
        $this->CIDRAM['LogPatterns'][] = $this->buildLogPattern($this->Configuration['logging']['report_log'], true);
    }
    if ($this->Configuration['frontend']['frontend_log'] !== '') {
        $this->CIDRAM['LogPatterns'][] = $this->buildLogPattern($this->Configuration['frontend']['frontend_log'], true);
    }
    if ($this->Configuration['recaptcha']['recaptcha_log'] !== '') {
        $this->CIDRAM['LogPatterns'][] = $this->buildLogPattern($this->Configuration['recaptcha']['recaptcha_log'], true);
    }
    if ($this->Configuration['hcaptcha']['hcaptcha_log'] !== '') {
        $this->CIDRAM['LogPatterns'][] = $this->buildLogPattern($this->Configuration['hcaptcha']['hcaptcha_log'], true);
    }
    return true;
});

/**
 * Writes to the report log.
 *
 * @param string $Data What to write.
 * @param array $Misc Other data passed from the events handler.
 * @return bool True on success; False on failure.
 */
$this->Events->addHandler('writeToReportLog', function (string $Data, array $Misc): bool {
    /** Guard. */
    if (
        !isset($Misc[0]) ||
        $this->Configuration['logging']['report_log'] === '' ||
        !($Filename = $this->buildPath($this->Vault . $this->Configuration['logging']['report_log']))
    ) {
        return false;
    }

    $Data = sprintf(
        '%1$s%3$s%8$s%2$s%3$s%4$s%5$s%3$s%6$s%7$s%3$s%9$s',
        $this->L10N->getString('label.Report log'),
        $this->L10N->getString('field.DateTime'),
        $this->L10N->getString('pair_separator'),
        date('c', time()) . "\n",
        $this->L10N->getString('field.IP address'),
        $Misc[0] . "\n",
        $this->L10N->getString('field.Comments'),
        $this->L10N->getString(isset($this->CIDRAM['Report OK']) && $this->CIDRAM['Report OK'] > 0 ? 'field.OK' : 'response.Failed') . "\n",
        $Data
    ) . "\n\n";

    $Truncate = $this->readBytes($this->Configuration['logging']['truncate']);
    $WriteMode = (!file_exists($Filename) || $Truncate > 0 && filesize($Filename) >= $Truncate) ? 'wb' : 'ab';
    if (!is_resource($File = fopen($Filename, $WriteMode))) {
        trigger_error('The "writeToReportLog" event failed to open "' . $Filename . '" for writing.');
        return false;
    }
    fwrite($File, $Data);
    fclose($File);
    if ($WriteMode === 'wb') {
        $this->logRotation($this->Configuration['logging']['report_log']);
    }
    return true;
});
