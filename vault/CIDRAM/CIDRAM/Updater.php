<?php
/**
 * This file is an optional extension of the CIDRAM package.
 * Homepage: https://cidram.github.io/
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: Methods for updating CIDRAM components (last modified: 2022.05.22).
 */

namespace CIDRAM\CIDRAM;

trait Updater
{
    /**
     * Sometimes used by the updater to partially patch parts of files.
     *
     * @param string $Query The instruction to execute.
     * @return bool Success or failure.
     */
    private function in(string $Query): bool
    {
        if (!isset($this->CIDRAM['Updater-IO']) || !$Delimiter = substr($Query, 0, 1)) {
            return false;
        }
        $QueryParts = explode($Delimiter, $Query);
        $CountParts = count($QueryParts);
        if (!($CountParts % 2)) {
            return false;
        }
        $Arr = [];
        for ($Iter = 0; $Iter < $CountParts; $Iter++) {
            if ($Iter % 2) {
                $Arr[] = $QueryParts[$Iter];
                continue;
            }
            $QueryParts[$Iter] = preg_split('~ +~', $QueryParts[$Iter], -1, PREG_SPLIT_NO_EMPTY);
            foreach ($QueryParts[$Iter] as $ThisPart) {
                $Arr[] = $ThisPart;
            }
        }
        $QueryParts = $Arr;
        unset($ThisPart, $Iter, $Arr, $CountParts);

        /** Safety mechanism. */
        if (empty($QueryParts[0]) || empty($QueryParts[1]) || !file_exists($this->Vault . $QueryParts[0]) || !is_readable($this->Vault . $QueryParts[0])) {
            return false;
        }

        /** Fetch file content. */
        $Data = $this->CIDRAM['Updater-IO']->readFile($this->Vault . $QueryParts[0]);

        /** Normalise main instruction. */
        $QueryParts[1] = strtolower($QueryParts[1]);

        /** Replace file content. */
        if ($QueryParts[1] === 'replace' && !empty($QueryParts[3]) && strtolower($QueryParts[3]) === 'with') {
            $Data = preg_replace($QueryParts[2], ($QueryParts[4] ?? ''), $Data);
            return $this->CIDRAM['Updater-IO']->writeFile($this->Vault . $QueryParts[0], $Data);
        }

        /** Nothing done. Return false (failure). */
        return false;
    }

    /**
     * Sometimes used by the updater to delete files via metadata commands.
     *
     * @param string $File The file to delete.
     * @return bool Success or failure.
     */
    private function delete(string $File): bool
    {
        if (preg_match('~^(\'.*\'|".*")$~', $File)) {
            $File = substr($File, 1, -1);
        }
        if (!empty($File) && file_exists($this->Vault . $File) && $this->freeFromTraversal($File)) {
            if (!unlink($this->Vault . $File)) {
                return false;
            }
            $this->deleteDirectory($File);
            return true;
        }
        return false;
    }

    /**
     * Append to the current state message.
     *
     * @param string $Message What to append.
     * @return void
     */
    private function message(string $Message): void
    {
        if (isset($this->FE['state_msg'])) {
            if ($Try = $this->L10N->getString($Message)) {
                $Message = $Try;
            }
            $this->FE['state_msg'] .= $Message . '<br />';
        }
    }

    /**
     * Remove sub-arrays from an array.
     *
     * @param array $Arr An array.
     * @return array An array.
     */
    private function arrayFlatten(array $Arr): array
    {
        return array_filter($Arr, function () {
            return (!is_array(func_get_args()[0]));
        });
    }

    /**
     * Append one or two values to a string, depending on whether that string is
     * empty prior to calling the method (allows cleaner code in some areas).
     *
     * @param string $String The string to work with.
     * @param string $Delimit Appended first, if the string is not empty.
     * @param string $Append Appended second, and always (empty or otherwise).
     */
    private function appendToString(string &$String, string $Delimit = '', string $Append = '')
    {
        if (!empty($String)) {
            $String .= $Delimit;
        }
        $String .= $Append;
    }

    /**
     * Performs some simple sanity checks on files (used by the updater).
     *
     * @param string $FileName The name of the file to be checked.
     * @param string $FileData The content of the file to be checked.
     * @return bool True when passed; False when failed.
     */
    private function sanityCheck(string $FileName, string $FileData): bool
    {
        /** A very simple, rudimentary check for unwanted, possibly maliciously inserted HTML. */
        if ($FileData && preg_match('~<(?:html|body)~i', $FileData)) {
            return false;
        }

        /** Check whether YAML is valid. */
        if (preg_match('~\.ya?ml$~i', $FileName)) {
            $ThisYAML = new \Maikuolan\Common\YAML();
            if (!($ThisYAML->process($FileData, $ThisYAML->Data))) {
                return false;
            }
            return true;
        }

        /** Check whether GIF is valid. */
        if (strtolower(substr($FileName, -4)) === '.gif') {
            $Sample = substr($FileData, 0, 6);
            return $Sample === 'GIF87a' || $Sample === 'GIF89a';
        }

        /** Check whether PNG is valid. */
        if (strtolower(substr($FileName, -4)) === '.png') {
            return substr($FileData, 0, 4) === "\x89PNG";
        }

        /** Passed. */
        return true;
    }

    /**
     * Used by the file manager and the updates pages to fetch the determine which
     * components are currently installed.
     *
     * @param array $Arr The array to use for rendering components file YAML data.
     * @return void
     */
    private function readInstalledMetadata(array &$Arr): void
    {
        $Data = $this->CIDRAM['Updater-IO']->readFile($this->Vault . 'installed.yml');
        if (strlen($Data)) {
            $this->YAML->process($Data, $Arr);
        }
    }

    /**
     * Checks whether a component is in use.
     *
     * @param array $Component An array of the component metadata.
     * @return int 1 when in use.
     *             0 when not in use.
     *            -1 when *partially* in use (e.g., when misconfigured).
     */
    private function isInUse(array $Component): int
    {
        if (!empty($Component['Name']) && $Component['Name'] === 'L10N: ' . $this->L10N->getString('Local Name')) {
            return 1;
        }
        $Files = $Component['Files'] ?? [];
        $this->arrayify($Files);
        foreach ($Files as $FileName => $FileMeta) {
            $UsedWith = $FileMeta['Used with'] ?? '';
            if (
                $UsedWith !== 'ipv4' &&
                $UsedWith !== 'ipv6' &&
                $UsedWith !== 'modules' &&
                $UsedWith !== 'imports' &&
                $UsedWith !== 'events'
            ) {
                continue;
            }
            if (($UsedWith === 'ipv4' || $UsedWith === 'ipv6') && substr($FileName, 0, 11) === 'signatures/') {
                $FileNameSafe = preg_quote(substr($FileName, 11));
            } elseif (
                ($UsedWith === 'modules' && substr($FileName, 0, 8) === 'modules/') ||
                ($UsedWith === 'imports' && substr($FileName, 0, 8) === 'imports/')
            ) {
                $FileNameSafe = preg_quote(substr($FileName, 8));
            } elseif ($UsedWith === 'events' && substr($FileName, 0, 7) === 'events/') {
                $FileNameSafe = preg_quote(substr($FileName, 7));
            } else {
                $FileNameSafe = preg_quote($FileName);
            }
            if (preg_match('~\n(?:[\w\d]+:)?' . $FileNameSafe . '\n~', "\n" . $this->Configuration['components'][$UsedWith] . "\n")) {
                $Out = (!isset($Out) || $Out === 1) ? 1 : -1;
            } else {
                $Out = (!isset($Out) || $Out === 0) ? 0 : -1;
            }
        }
        return $Out ?? 0;
    }

    /**
     * Fetch remote data.
     *
     * @return void
     */
    private function fetchRemotesData(): void
    {
        $Remotes = explode("\n", $this->Configuration['frontend']['remotes']);
        if (!isset($this->Components['RemoteMeta'])) {
            $this->Components['RemoteMeta'] = [];
        }
        foreach ($Remotes as $ThisRemote) {
            $RemoteData = $this->Cache->getEntry($ThisRemote);
            if ($RemoteData === false) {
                $RemoteData = $this->Request->request($ThisRemote);
                if (strtolower(substr($ThisRemote, -2)) === 'gz' && substr($RemoteData, 0, 2) === "\x1F\x8B") {
                    $RemoteData = gzdecode($RemoteData);
                }
                if (empty($RemoteData)) {
                    $RemoteData = '-';
                }
                $this->Cache->setEntry($ThisRemote, $RemoteData, 3600);
            }
            $this->YAML->process($RemoteData, $this->Components['RemoteMeta']);
        }
    }

    /**
     * Checks whether component is activable.
     *
     * @param array $Component An array of the component metadata.
     * @return bool True for when activable; False for when not activable.
     */
    private function isActivable(array $Component): bool
    {
        if (!isset($Component['Files'])) {
            return false;
        }
        foreach ($Component['Files'] as $FileName => $FileMeta) {
            if (isset($FileMeta['Used with']) && (
                $FileMeta['Used with'] === 'imports' ||
                $FileMeta['Used with'] === 'events' ||
                $FileMeta['Used with'] === 'ipv4' ||
                $FileMeta['Used with'] === 'ipv6' ||
                $FileMeta['Used with'] === 'modules'
            )) {
                return true;
            }
        }
        return false;
    }

    /**
     * Prepares component extended description.
     *
     * @param array $Arr Metadata of the component to be prepared.
     * @param string $Key A key to use to help find L10N data for the component description.
     * @return void
     */
    private function prepareExtendedDescription(array &$Arr, string $Key = ''): void
    {
        $Key = 'Extended Description ' . $Key;
        if (isset($this->L10N->Data[$Key])) {
            $Arr['Extended Description'] = $this->L10N->getString($Key);
        } elseif (!isset($Arr['Extended Description'])) {
            $Arr['Extended Description'] = '';
        }
        if (!empty($Arr['False Positive Risk'])) {
            if ($Arr['False Positive Risk'] === 'Low') {
                $State = $this->L10N->getString('state_risk_low');
                $Class = 'txtGn';
            } elseif ($Arr['False Positive Risk'] === 'Medium') {
                $State = $this->L10N->getString('state_risk_medium');
                $Class = 'txtOe';
            } elseif ($Arr['False Positive Risk'] === 'High') {
                $State = $this->L10N->getString('state_risk_high');
                $Class = 'txtRd';
            } else {
                return;
            }
            $Arr['Extended Description'] .= sprintf(
                '<br /><em>%s <span class="%s">%s</span></em>',
                $this->L10N->getString('label_false_positive_risk'),
                $Class,
                $State
            );
        }
    }

    /**
     * Duplication avoidance.
     *
     * @param string $Target
     * @return int 1 when the component is in use.
     *             0 when the component is not in use.
     *            -1 when the component is *partially* in use.
     */
    private function componentUpdatePrep(string $Target): int
    {
        if (empty($this->Components['Meta'][$Target]['Files'])) {
            return 0;
        }
        $this->prepareExtendedDescription($this->Components['Meta'][$Target]);
        $this->arrayify($this->Components['Meta'][$Target]['Files']);
        return $this->isInUse($this->Components['Meta'][$Target]);
    }

    /**
     * Executes a list of methods or commands when specific conditions are met.
     *
     * @param string|array $Methods The list of methods or commands to execute.
     * @param bool $Queue Whether to queue the operation or perform immediately.
     * @return void
     */
    private function executor($Methods = false, bool $Queue = false): void
    {
        if ($Queue && $Methods !== false) {
            /** Guard. */
            if (empty($this->CIDRAM['ExecutorQueue']) || !is_array($this->CIDRAM['ExecutorQueue'])) {
                $this->CIDRAM['ExecutorQueue'] = [];
            }

            /** Add to the executor queue. */
            $this->CIDRAM['ExecutorQueue'][] = $Methods;
            return;
        }

        if ($Methods === false && !empty($this->CIDRAM['ExecutorQueue']) && is_array($this->CIDRAM['ExecutorQueue'])) {
            /** We'll iterate an array from the local scope to guard against infinite loops. */
            $Items = $this->CIDRAM['ExecutorQueue'];

            /** Purge the queue before iterating. */
            $this->CIDRAM['ExecutorQueue'] = [];

            /** Recursively iterate through the executor queue. */
            foreach ($Items as $QueueItem) {
                $this->executor($QueueItem);
            }
            return;
        }

        /** Guard. */
        $this->arrayify($Methods);

        /** Recursively execute all methods in the current queue item. */
        foreach ($Methods as $Method) {
            /** All logic, data traversal, dot notation, etc handled here. */
            $Method = $this->CIDRAM['Operation']->ifCompare($this->CIDRAM, $Method);

            if (method_exists($this, $Method)) {
                $this->{$Method}();
            } elseif (($Pos = strpos($Method, ' ')) !== false) {
                $Params = substr($Method, $Pos + 1);
                $Method = substr($Method, 0, $Pos);
                if (method_exists($this, $Method)) {
                    $Params = $this->CIDRAM['Operation']->ifCompare($this->CIDRAM, $Params);
                    $this->{$Method}($Params);
                }
            }
        }
    }

    /**
     * Updates plugin version cited in the WordPress plugins dashboard, if this
     * copy of CIDRAM is running as a WordPress plugin.
     *
     * @return void
     */
    private function wpVer(): void
    {
        if (
            !empty($this->Components['RemoteMeta']['CIDRAM']['Version']) &&
            ($ThisData = $this->CIDRAM['Updater-IO']->readFile($this->Vault . '../cidram.php'))
        ) {
            $PlugHead = "\x3C\x3Fphp\n/**\n * Plugin Name: CIDRAM\n * Version: ";
            if (substr($ThisData, 0, 45) === $PlugHead) {
                $PlugHeadEnd = strpos($ThisData, "\n", 45);
                $this->CIDRAM['Updater-IO']->writeFile(
                    $this->Vault . '../cidram.php',
                    $PlugHead . $this->Components['RemoteMeta']['CIDRAM Core']['Version'] . substr($ThisData, $PlugHeadEnd)
                );
            }
        }
    }

    /**
     * Traversal detection.
     *
     * @param string $Path The path to check for traversal.
     * @return bool True when the path is traversal-free. False when traversal has been detected.
     */
    private function freeFromTraversal(string $Path): bool
    {
        return !preg_match(
            '~(?://|(?<![\da-z])\.\.(?![\da-z])|/\.(?![\da-z])|(?<![\da-z])\./|[\x01-\x1F\[-^`?*$])~i',
            str_replace("\\", '/', $Path)
        );
    }

    /**
     * Custom sort an array by key and then implode the results.
     *
     * @param array $Arr The array to sort.
     * @return string The sorted, imploded array.
     */
    private function sortComponents(array $Arr): string
    {
        $Type = $this->FE['sort-by-name'] ?? false;
        $Order = $this->FE['descending-order'] ?? false;
        uksort($Arr, function (string $A, string $B) use ($Type, $Order) {
            if (!$Type) {
                $Priority = '~^(?:CIDRAM|Common Classes Package|IPv[46]|l10n/)~i';
                $CheckA = preg_match($Priority, $A);
                $CheckB = preg_match($Priority, $B);
                if ($CheckA && !$CheckB) {
                    return $Order ? 1 : -1;
                }
                if ($CheckB && !$CheckA) {
                    return $Order ? -1 : 1;
                }
            }
            if ($A < $B) {
                return $Order ? 1 : -1;
            }
            if ($A > $B) {
                return $Order ? -1 : 1;
            }
            return 0;
        });
        return implode('', $Arr);
    }

    /**
     * Updates handler.
     *
     * @param string $Action The action to perform (update/install, verify,
     *      uninstall, activate, deactivate).
     * @param string|array $ID The IDs of the components to perform the specified
     *      action upon.
     * @return void
     */
    private function updatesHandler(string $Action, $ID = ''): void
    {
        /** Support for executor calls. */
        if ($ID === '' && ($Pos = strpos($Action, ' ')) !== false) {
            $ID = substr($Action, $Pos + 1);
            $Action = trim(substr($Action, 0, $Pos));
            $ID = (strpos($ID, ',') === false) ? trim($ID) : array_map('trim', explode(',', $ID));
        }

        /** Strip empty IDs. */
        if (is_array($ID)) {
            $ID = array_filter($ID, function ($Value) {
                return $Value !== '';
            });
        }

        /** Guard. */
        if (empty($ID)) {
            return;
        }

        /** Update (or install) a component. */
        if ($Action === 'update-component') {
            $this->updatesHandlerUpdate($ID);
        }

        /** Update (or install) and activate a component (one-step solution). */
        if ($Action === 'update-and-activate-component') {
            $this->arrayify($ID);
            foreach ($ID as $ThisID) {
                $this->updatesHandlerUpdate([$ThisID]);
                if (
                    isset($this->Components['Meta'][$ThisID]) &&
                    $this->isActivable($this->Components['Meta'][$ThisID]) &&
                    $this->isInUse($this->Components['Meta'][$ThisID]) !== 1
                ) {
                    $this->updatesHandlerActivate([$ThisID]);
                }
            }
        }

        /** Repair a component. */
        if ($Action === 'repair-component') {
            $this->updatesHandlerRepair($ID);
        }

        /** Verify a component. */
        if ($Action === 'verify-component') {
            $this->updatesHandlerVerify($ID);
        }

        /** Uninstall a component. */
        if ($Action === 'uninstall-component') {
            $this->updatesHandlerUninstall($ID);
        }

        /** Activate a component. */
        if ($Action === 'activate-component') {
            $this->updatesHandlerActivate($ID);
        }

        /** Deactivate a component. */
        if ($Action === 'deactivate-component') {
            $this->updatesHandlerDeactivate($ID);
        }

        /** Deactivate and uninstall a component (one-step solution). */
        if ($Action === 'deactivate-and-uninstall-component') {
            $this->arrayify($ID);
            foreach ($ID as $ThisID) {
                if (
                    isset($this->Components['Meta'][$ThisID]) &&
                    $this->isActivable($this->Components['Meta'][$ThisID]) &&
                    $this->isInUse($this->Components['Meta'][$ThisID]) !== 0
                ) {
                    $this->updatesHandlerDeactivate([$ThisID]);
                }
                $this->updatesHandlerUninstall([$ThisID]);
            }
        }

        /** Process and empty executor queue. */
        $this->executor();
    }

    /**
     * Updates handler: Update a component.
     *
     * @param string|array $ID The IDs of the components to update.
     * @return void
     */
    private function updatesHandlerUpdate($ID): void
    {
        $this->arrayify($ID);

        /** Fetch dependency installation triggers. */
        if (!empty($_POST['InstallTogether']) && is_array($_POST['InstallTogether'])) {
            $ID = array_merge($ID, $_POST['InstallTogether']);
        }

        /** Iterate through all supplied component IDs. */
        foreach (array_unique($ID) as $ThisTarget) {
            $BytesAdded = 0;
            $BytesRemoved = 0;
            $TimeRequired = microtime(true);
            $HasSigs = false;
            if (isset($this->Components['Meta'][$ThisTarget])) {
                $Reactivate = $this->isInUse($this->Components['Meta'][$ThisTarget]);
            } else {
                $Reactivate = 0;
            }
            if ($Reactivate !== 0) {
                $this->updatesHandlerDeactivate($ThisTarget);
            }
            if (isset($this->Components['RemoteMeta'][$ThisTarget]['Files'])) {
                $this->checkConstraints($this->Components['RemoteMeta'][$ThisTarget], true);
                $UpdateFailed = false;
            } else {
                $UpdateFailed = true;
            }
            if (
                $this->Components['RemoteMeta'][$ThisTarget]['All Constraints Met'] &&
                isset($this->Components['RemoteMeta'][$ThisTarget]['Files'])
            ) {
                $this->arrayify($this->Components['RemoteMeta'][$ThisTarget]['Files']);
                $RemoteFiles = [];
                $IgnoredFiles = [];
                $Rollback = false;
                while (true) {
                    foreach ($this->Components['RemoteMeta'][$ThisTarget]['Files'] as $FileName => $FileMeta) {
                        if (strlen($FileName) === 0) {
                            continue;
                        }

                        /** Rolls back to previous version or uninstalls if an update/install fails. */
                        if ($Rollback) {
                            if (
                                isset($RemoteFiles[$FileName]) &&
                                !isset($IgnoredFiles[$FileName]) &&
                                is_readable($this->Vault . $FileName)
                            ) {
                                $BytesAdded -= filesize($this->Vault . $FileName);
                                unlink($this->Vault . $FileName);
                                if (is_readable($this->Vault . $FileName . '.rollback')) {
                                    $BytesRemoved -= filesize($this->Vault . $FileName . '.rollback');
                                    rename($this->Vault . $FileName . '.rollback', $this->Vault . $FileName);
                                }
                            }
                            continue;
                        }
                        if (
                            isset($FileMeta['Checksum']) &&
                            isset($this->Components['Meta'][$ThisTarget]['Files'][$FileName]['Checksum']) &&
                            $FileMeta['Checksum'] === $this->Components['Meta'][$ThisTarget]['Files'][$FileName]['Checksum']
                        ) {
                            $IgnoredFiles[$FileName] = true;
                            continue;
                        }
                        if (
                            !isset($FileMeta['From']) ||
                            strlen($FileMeta['From']) === 0 ||
                            strlen($ThisFile = $this->Request->request($FileMeta['From'])) === 0
                        ) {
                            $Rollback = true;
                            continue 2;
                        }
                        if (
                            strtolower(substr($FileMeta['From'], -2)) === 'gz' &&
                            strtolower(substr($FileName, -2)) !== 'gz' &&
                            substr($ThisFile, 0, 2) === "\x1F\x8B"
                        ) {
                            $ThisFile = gzdecode($ThisFile);
                        }
                        if (isset($FileMeta['Checksum']) && strlen($FileMeta['Checksum'])) {
                            $ThisLen = strlen($ThisFile);
                            if (hash('sha256', $ThisFile) . ':' . $ThisLen !== $FileMeta['Checksum']) {
                                $this->FE['state_msg'] .=
                                    '<code>' . $ThisTarget . '</code> – ' .
                                    '<code>' . $FileName . '</code> – ' .
                                    $this->L10N->getString('response_checksum_error') . '<br />';
                                if (!empty($this->Components['Meta'][$ThisTarget]['On Checksum Error'])) {
                                    $this->executor($this->Components['Meta'][$ThisTarget]['On Checksum Error'], true);
                                }
                                $Rollback = true;
                                continue 2;
                            }
                        }
                        if (
                            preg_match('~\.(?:css|dat|gif|png|ya?ml)$~i', $FileName) &&
                            !$this->sanityCheck($FileName, $ThisFile)
                        ) {
                            $this->FE['state_msg'] .= sprintf(
                                '<code>%s</code> – <code>%s</code> – %s<br />',
                                $ThisTarget,
                                $FileName,
                                $this->L10N->getString('response_sanity_1')
                            );
                            if (!empty($this->Components['Meta'][$ThisTarget]['On Sanity Error'])) {
                                $this->executor($this->Components['Meta'][$ThisTarget]['On Sanity Error'], true);
                            }
                            $Rollback = true;
                            continue 2;
                        }
                        $this->buildPath($this->Vault . $FileName);
                        if (is_readable($this->Vault . $FileName)) {
                            $BytesRemoved += filesize($this->Vault . $FileName);
                            if (file_exists($this->Vault . $FileName . '.rollback')) {
                                $BytesRemoved += filesize($this->Vault . $FileName . '.rollback');
                                unlink($this->Vault . $FileName . '.rollback');
                            }
                            rename($this->Vault . $FileName, $this->Vault . $FileName . '.rollback');
                        }
                        $BytesAdded += strlen($ThisFile);
                        $Handle = fopen($this->Vault . $FileName, 'wb');
                        $RemoteFiles[$FileName] = fwrite($Handle, $ThisFile);
                        $RemoteFiles[$FileName] = true;
                        fclose($Handle);
                        if (
                            isset($FileMeta['Used with']) &&
                            ($FileMeta['Used with'] === 'ipv4' || $FileMeta['Used with'] === 'ipv6')
                        ) {
                            $HasSigs = true;
                        }
                    }
                    break;
                }

                if ($Rollback) {
                    /** Prune unwanted empty directories (update/install failure+rollback). */
                    if (
                        isset($this->Components['RemoteMeta'][$ThisTarget]['Files']) &&
                        is_array($this->Components['RemoteMeta'][$ThisTarget]['Files'])
                    ) {
                        foreach ($this->Components['RemoteMeta'][$ThisTarget]['Files'] as $FileName => $FileMeta) {
                            if (strlen($FileName) > 0 && $this->freeFromTraversal($FileName)) {
                                $this->deleteDirectory($FileName);
                            }
                        }
                    }
                    $UpdateFailed = true;
                } else {
                    /** Prune unwanted files and directories (update/install success). */
                    if (isset($this->Components['Meta'][$ThisTarget]['Files'])) {
                        $this->arrayify($this->Components['Meta'][$ThisTarget]['Files']);
                        foreach ($this->Components['Meta'][$ThisTarget]['Files'] as $FileName => $FileMeta) {
                            if (strlen($FileName) === 0 || !$this->freeFromTraversal($FileName)) {
                                continue;
                            }
                            if (file_exists($this->Vault . $FileName . '.rollback')) {
                                unlink($this->Vault . $FileName . '.rollback');
                            }
                            if (
                                !isset($RemoteFiles[$FileName]) &&
                                !isset($IgnoredFiles[$FileName]) &&
                                file_exists($this->Vault . $FileName) &&
                                (empty($this->Components['Shared'][$FileName]) || $this->Components['Shared'][$FileName] < 2)
                            ) {
                                $BytesRemoved += filesize($this->Vault . $FileName);
                                unlink($this->Vault . $FileName);
                                $this->deleteDirectory($FileName);
                            }
                        }
                    }

                    $this->FE['state_msg'] .= '<code>' . $ThisTarget . '</code> – ';
                    if (
                        empty($this->Components['Meta'][$ThisTarget]['Version']) &&
                        empty($this->Components['Meta'][$ThisTarget]['Files'])
                    ) {
                        $this->FE['state_msg'] .= $this->L10N->getString('response_component_successfully_installed');
                        if (!empty($this->Components['Meta'][$ThisTarget]['When Install Succeeds'])) {
                            $this->executor($this->Components['Meta'][$ThisTarget]['When Install Succeeds'], true);
                        }
                    } else {
                        $this->FE['state_msg'] .= $this->L10N->getString('response_component_successfully_updated');
                        if (!empty($this->Components['Meta'][$ThisTarget]['When Update Succeeds'])) {
                            $this->executor($this->Components['Meta'][$ThisTarget]['When Update Succeeds'], true);
                        }
                    }

                    /** Assign updated component meta. */
                    $this->Components['Meta'][$ThisTarget] = $this->Components['RemoteMeta'][$ThisTarget];

                    /** Update the component metadata file. */
                    $this->CIDRAM['Updater-IO']->writeFile(
                        $this->Vault . 'installed.yml',
                        $this->YAML->reconstruct($this->Components['Meta'])
                    );

                    /** Set trigger for signatures update event. */
                    if ($HasSigs) {
                        $this->CIDRAM['SignaturesUpdateEvent'] = $this->Now;
                    }
                }
            } else {
                $UpdateFailed = true;
            }
            if ($UpdateFailed) {
                $this->FE['state_msg'] .= '<code>' . $ThisTarget . '</code> – ';
                if (
                    empty($this->Components['Meta'][$ThisTarget]['Version']) &&
                    empty($this->Components['Meta'][$ThisTarget]['Files'])
                ) {
                    $this->FE['state_msg'] .= $this->L10N->getString('response_failed_to_install');
                    if (!empty($this->Components['Meta'][$ThisTarget]['When Install Fails'])) {
                        $this->executor($this->Components['Meta'][$ThisTarget]['When Install Fails'], true);
                    }
                } else {
                    $this->FE['state_msg'] .= $this->L10N->getString('response_failed_to_update');
                    if (!empty($this->Components['Meta'][$ThisTarget]['When Update Fails'])) {
                        $this->executor($this->Components['Meta'][$ThisTarget]['When Update Fails'], true);
                    }
                }
            }
            $this->formatFileSize($BytesAdded);
            $this->formatFileSize($BytesRemoved);
            $this->FE['state_msg'] .= sprintf(
                $this->FE['CronMode'] !== '' ? " « +%s | -%s | %s »\n" : ' <code><span class="txtGn">+%s</span> | <span class="txtRd">-%s</span> | <span class="txtOe">%s</span></code><br />',
                $BytesAdded,
                $BytesRemoved,
                $this->NumberFormatter->format(microtime(true) - $TimeRequired, 3)
            );
            if ($Reactivate !== 0) {
                $this->updatesHandlerActivate($ThisTarget);
            }
        }
    }

    /**
     * Updates handler: Uninstall a component.
     *
     * @param string|array $ID The ID of the component to uninstall.
     * @return void
     */
    private function updatesHandlerUninstall($ID): void
    {
        if (is_array($ID)) {
            $ID = current($ID);
        }
        $InUse = $this->componentUpdatePrep($ID);
        $BytesRemoved = 0;
        $TimeRequired = microtime(true);
        $this->FE['state_msg'] .= '<code>' . $ID . '</code> – ';
        if (
            $InUse === 0 &&
            !empty($this->Components['Meta'][$ID]['Files']) &&
            !empty($this->Components['Meta'][$ID]['Uninstallable'])
        ) {
            $this->arrayify($this->Components['Meta'][$ID]['Files']);

            /** Iterate through and remove all the component's files. */
            foreach ($this->Components['Meta'][$ID]['Files'] as $FileName => $FileMeta) {
                if (strlen($FileName) === 0 || !$this->freeFromTraversal($FileName)) {
                    continue;
                }
                if (file_exists($this->Vault . $FileName . '.rollback')) {
                    $BytesRemoved += filesize($this->Vault . $FileName . '.rollback');
                    unlink($this->Vault . $FileName . '.rollback');
                }
                if (!empty($this->Components['Shared'][$FileName]) && $this->Components['Shared'][$FileName] > 1) {
                    continue;
                }
                if (file_exists($this->Vault . $FileName)) {
                    $BytesRemoved += filesize($this->Vault . $FileName);
                    unlink($this->Vault . $FileName);
                }
                $this->deleteDirectory($FileName);
            }

            $this->FE['state_msg'] .= $this->L10N->getString('response_component_successfully_uninstalled');
            if (!empty($this->Components['Meta'][$ID]['When Uninstall Succeeds'])) {
                $this->executor($this->Components['Meta'][$ID]['When Uninstall Succeeds'], true);
            }

            /** Remove downstream meta. */
            unset($this->Components['Meta'][$ID]);

            /** Update the component metadata file. */
            $this->CIDRAM['Updater-IO']->writeFile(
                $this->Vault . 'installed.yml',
                $this->YAML->reconstruct($this->Components['Meta'])
            );
        } else {
            $this->FE['state_msg'] .= $this->L10N->getString('response_component_uninstall_error');
            if (!empty($this->Components['Meta'][$ID]['When Uninstall Fails'])) {
                $this->executor($this->Components['Meta'][$ID]['When Uninstall Fails'], true);
            }
        }
        $this->formatFileSize($BytesRemoved);
        $this->FE['state_msg'] .= sprintf(
            $this->FE['CronMode'] !== '' ? " « -%s | %s »\n" : ' <code><span class="txtRd">-%s</span> | <span class="txtOe">%s</span></code><br />',
            $BytesRemoved,
            $this->NumberFormatter->format(microtime(true) - $TimeRequired, 3)
        );
    }

    /**
     * Updates handler: Activate a component.
     *
     * @param string|array $ID The ID of the component to activate.
     * @return void
     */
    private function updatesHandlerActivate($ID): void
    {
        if (is_array($ID)) {
            $ID = current($ID);
        }
        $Activation = [
            'ipv4' => $this->Configuration['components']['ipv4'],
            'ipv6' => $this->Configuration['components']['ipv6'],
            'modules' => $this->Configuration['components']['modules'],
            'imports' => $this->Configuration['components']['imports'],
            'events' => $this->Configuration['components']['events'],
            'Modified' => false
        ];
        foreach (['ipv4', 'ipv6', 'modules', 'imports', 'events'] as $Type) {
            $Activation[$Type] = array_unique(array_filter(
                explode("\n", $Activation[$Type]),
                function ($Component) use ($Type) {
                    $Component = (strpos($Component, ':') === false) ? $Component : substr($Component, strpos($Component, ':') + 1);
                    return (strlen($Component) > 0 && file_exists($this->pathFromComponentType($Type) . $Component));
                }
            ));
        }
        $InUse = $this->componentUpdatePrep($ID);
        $this->FE['state_msg'] .= '<code>' . $ID . '</code> – ';
        if ($InUse !== 1 && !empty($this->Components['Meta'][$ID]['Files'])) {
            foreach ($this->Components['Meta'][$ID]['Files'] as $FileName => $FileMeta) {
                $UsedWith = $FileMeta['Used with'] ?? '';
                if (
                    $UsedWith !== 'ipv4' &&
                    $UsedWith !== 'ipv6' &&
                    $UsedWith !== 'modules' &&
                    $UsedWith !== 'imports' &&
                    $UsedWith !== 'events'
                ) {
                    continue;
                }
                $Activation[$UsedWith][] = $File;
            }
        }
        foreach (['ipv4', 'ipv6', 'modules', 'imports', 'events'] as $Type) {
            if (count($Activation[$Type])) {
                sort($Activation[$Type]);
            }
            $Activation[$Type] = implode("\n", $Activation[$Type]);
            if ($Activation[$Type] !== $this->Configuration['components'][$Type]) {
                $Activation['Modified'] = true;
            }
        }
        if (!$Activation['Modified']) {
            $this->FE['state_msg'] .= $this->L10N->getString('response_activation_failed') . '<br />';
            if (!empty($this->Components['Meta'][$ID]['When Activation Fails'])) {
                $this->executor($this->Components['Meta'][$ID]['When Activation Fails'], true);
            }
        } else {
            $this->Configuration['components']['ipv4'] = $Activation['ipv4'];
            $this->Configuration['components']['ipv6'] = $Activation['ipv6'];
            $this->Configuration['components']['modules'] = $Activation['modules'];
            $this->Configuration['components']['imports'] = $Activation['imports'];
            $this->Configuration['components']['events'] = $Activation['events'];
            if ($this->updateConfiguration()) {
                $this->FE['state_msg'] .= $this->L10N->getString('response_activated') . '<br />';
                if (!empty($this->Components['Meta'][$ID]['When Activation Succeeds'])) {
                    $this->executor($this->Components['Meta'][$ID]['When Activation Succeeds'], true);
                }
                $Success = true;
            } else {
                $this->FE['state_msg'] .= $this->L10N->getString('response_activation_failed') . '<br />';
                if (!empty($this->Components['Meta'][$ID]['When Activation Fails'])) {
                    $this->executor($this->Components['Meta'][$ID]['When Activation Fails'], true);
                }
            }
        }

        /** Deal with dependency activation. */
        if (
            !empty($Success) &&
            !empty($this->Components['Meta'][$ID]['Dependencies']) &&
            is_array($this->Components['Meta'][$ID]['Dependencies'])
        ) {
            foreach ($this->Components['Meta'][$ID]['Dependencies'] as $Dependency => $Constraints) {
                if (
                    !isset($this->Components['Meta'][$Dependency]) ||
                    empty($this->Components['Installed Versions'][$Dependency]) ||
                    !$this->isActivable($this->Components['Meta'][$Dependency]) ||
                    $this->isInUse($this->Components['Meta'][$Dependency]) === 1
                ) {
                    continue;
                }
                $this->updatesHandlerActivate($Dependency);
            }
        }
    }

    /**
     * Updates handler: Deactivate a component.
     *
     * @param string|array $ID The ID of the component to deactivate.
     * @return void
     */
    private function updatesHandlerDeactivate($ID): void
    {
        if (is_array($ID)) {
            $ID = current($ID);
        }
        $this->CIDRAM['Deactivation'] = [
            'ipv4' => $this->Configuration['components']['ipv4'],
            'ipv6' => $this->Configuration['components']['ipv6'],
            'modules' => $this->Configuration['components']['modules'],
            'imports' => $this->Configuration['components']['imports'],
            'events' => $this->Configuration['components']['events'],
            'Modified' => false
        ];
        foreach (['ipv4', 'ipv6', 'modules', 'imports', 'events'] as $Type) {
            $this->CIDRAM['Deactivation'][$Type] = array_unique(array_filter(
                explode("\n", $this->CIDRAM['Deactivation'][$Type]),
                function ($Component) {
                    return ($Component = (strpos($Component, ':') === false) ? $Component : substr($Component, strpos($Component, ':') + 1));
                }
            ));
            if (count($this->CIDRAM['Deactivation'][$Type])) {
                sort($this->CIDRAM['Deactivation'][$Type]);
            }
            $this->CIDRAM['Deactivation'][$Type] = "\n" . implode("\n", $this->CIDRAM['Deactivation'][$Type]) . "\n";
        }
        $this->FE['state_msg'] .= '<code>' . $ID . '</code> – ';
        if (!empty($this->Components['Meta'][$ID]['Files'])) {
            $this->arrayify($this->Components['Meta'][$ID]['Files']);
            foreach ($this->Components['Meta'][$ID]['Files'] as $FileName => $FileMeta) {
                $this->CIDRAM['Deactivation'][$Type] = preg_replace(
                    '~\n(?:[\w\d]+:)?' . preg_quote($FileName) . '\n~',
                    "\n",
                    $this->CIDRAM['Deactivation'][$Type]
                );
                $this->CIDRAM['Deactivation'][$Type] = substr($this->CIDRAM['Deactivation'][$Type], 1, -1);
            }
        }
        if (
            $this->CIDRAM['Deactivation']['ipv4'] !== $this->Configuration['components']['ipv4'] ||
            $this->CIDRAM['Deactivation']['ipv6'] !== $this->Configuration['components']['ipv6'] ||
            $this->CIDRAM['Deactivation']['modules'] !== $this->Configuration['components']['modules'] ||
            $this->CIDRAM['Deactivation']['imports'] !== $this->Configuration['components']['imports'] ||
            $this->CIDRAM['Deactivation']['events'] !== $this->Configuration['components']['events']
        ) {
            $this->CIDRAM['Deactivation']['Modified'] = true;
        }
        if (!$this->CIDRAM['Deactivation']['Modified']) {
            $this->FE['state_msg'] .= $this->L10N->getString('response_deactivation_failed') . '<br />';
            if (!empty($this->Components['Meta'][$ID]['When Deactivation Fails'])) {
                $this->executor($this->Components['Meta'][$ID]['When Deactivation Fails'], true);
            }
        } else {
            $this->Configuration['components']['ipv4'] = $this->CIDRAM['Deactivation']['ipv4'];
            $this->Configuration['components']['ipv6'] = $this->CIDRAM['Deactivation']['ipv6'];
            $this->Configuration['components']['modules'] = $this->CIDRAM['Deactivation']['modules'];
            $this->Configuration['components']['imports'] = $this->CIDRAM['Deactivation']['imports'];
            $this->Configuration['components']['events'] = $this->CIDRAM['Deactivation']['events'];
            if ($this->updateConfiguration()) {
                $this->FE['state_msg'] .= $this->L10N->getString('response_deactivated') . '<br />';
                if (!empty($this->Components['Meta'][$ID]['When Deactivation Succeeds'])) {
                    $this->executor($this->Components['Meta'][$ID]['When Deactivation Succeeds'], true);
                }
                $Success = true;
            } else {
                $this->FE['state_msg'] .= $this->L10N->getString('response_deactivation_failed') . '<br />';
                if (!empty($this->Components['Meta'][$ID]['When Deactivation Fails'])) {
                    $this->executor($this->Components['Meta'][$ID]['When Deactivation Fails'], true);
                }
            }
        }

        /** Cleanup. */
        unset($this->CIDRAM['Deactivation']);
    }

    /**
     * Updates handler: Repair a component.
     *
     * @param string|array $ID The IDs of the components to repair.
     * @return void
     */
    private function updatesHandlerRepair($ID): void
    {
        $this->arrayify($ID);
        $ID = array_unique($ID);
        foreach ($ID as $ThisTarget) {
            if (!isset($this->Components['Meta'][$ThisTarget])) {
                continue;
            }
            if (!isset($this->Components['Meta'][$ThisTarget]['Files'])) {
                $this->Components['Meta'][$ThisTarget]['Files'] = [];
            }
            $BytesAdded = 0;
            $BytesRemoved = 0;
            $TimeRequired = microtime(true);
            $Touched = [];
            if (!isset($this->Components['RemoteMeta'][$ThisTarget]['Files'])) {
                $RepairFailed = true;
            } else {
                $this->arrayify($this->Components['Meta'][$ThisTarget]['Files']);
                $this->arrayify($this->Components['RemoteMeta'][$ThisTarget]['Files']);
                $RepairFailed = false;
            }
            $Reactivate = $this->isInUse($this->Components['Meta'][$ThisTarget]);
            if ($Reactivate !== 0) {
                $this->updatesHandlerDeactivate($ThisTarget);
            }
            $this->FE['state_msg'] .= '<code>' . $ThisTarget . '</code> – ';
            if (!$RepairFailed) {
                foreach ($this->Components['RemoteMeta'][$ThisTarget]['Files'] as $FileName => $FileMeta) {
                    if (!isset($FileMeta['From'], $FileMeta['Checksum']) || !$this->freeFromTraversal($this->Vault . $FileName)) {
                        $RepairFailed = true;
                        break;
                    }
                    if (file_exists($this->Vault . $FileName . '.rollback')) {
                        $BytesRemoved += filesize($this->Vault . $FileName . '.rollback');
                        unlink($this->Vault . $FileName . '.rollback');
                    }
                    $LocalFile = $this->readFile($this->Vault . $FileName);
                    $LocalFileSize = strlen($LocalFile);
                    if (hash('sha256', $LocalFile) . ':' . $LocalFileSize === $FileMeta['Checksum']) {
                        $Touched[$FileName] = true;
                        continue;
                    }
                    $RemoteFile = $this->Request->request($FileMeta['From']);
                    if (
                        strtolower(substr($FileMeta['From'], -2)) === 'gz' &&
                        strtolower(substr($FileName, -2)) !== 'gz' &&
                        substr($RemoteFile, 0, 2) === "\x1F\x8B"
                    ) {
                        $RemoteFile = gzdecode($RemoteFile);
                    }
                    $RemoteFileSize = strlen($RemoteFile);
                    if (hash('sha256', $RemoteFile) . ':' . $RemoteFileSize !== $FileMeta['Checksum'] || (
                        preg_match('~\.(?:css|dat|gif|png|ya?ml)$~i', $FileName) &&
                        !$this->sanityCheck($FileName, $RemoteFile)
                    )) {
                        $RepairFailed = true;
                        continue;
                    }
                    $this->buildPath($this->Vault . $FileName);
                    if (file_exists($this->Vault . $FileName) && !is_writable($this->Vault . $FileName)) {
                        $RepairFailed = true;
                        continue;
                    }
                    $Handle = fopen($this->Vault . $FileName, 'wb');
                    fwrite($Handle, $RemoteFile);
                    fclose($Handle);
                    $BytesRemoved += $LocalFileSize;
                    $BytesAdded += $RemoteFileSize;
                    $Touched[$FileName] = true;
                }
                if (!$RepairFailed) {
                    foreach ($this->Components['Meta'][$ThisTarget]['Files'] as $FileName => $FileMeta) {
                        if (
                            strlen($FileName) === 0 ||
                            !$this->freeFromTraversal($this->Vault . $FileName) ||
                            !empty($Touched[$FileName]) ||
                            (!empty($this->Components['Shared'][$FileName]) && $this->Components['Shared'][$FileName] > 1)
                        ) {
                            continue;
                        }
                        if (file_exists($this->Vault . $FileName . '.rollback')) {
                            $BytesRemoved += filesize($this->Vault . $FileName . '.rollback');
                            unlink($this->Vault . $FileName . '.rollback');
                        }
                        if (file_exists($this->Vault . $FileName)) {
                            $BytesRemoved += filesize($this->Vault . $FileName);
                            unlink($this->Vault . $FileName);
                        }
                    }
                }
            } else {
                $RepairFailed = true;
            }
            if (!$RepairFailed && is_writable($this->Vault . 'installed.yml')) {
                /** Replace downstream meta with upstream meta. */
                $this->Components['Meta'][$ThisTarget] = $this->Components['RemoteMeta'][$ThisTarget];

                /** Update the component metadata file. */
                $this->CIDRAM['Updater-IO']->writeFile(
                    $this->Vault . 'installed.yml',
                    $this->YAML->reconstruct($this->Components['Meta'])
                );

                /** Repair operation succeeded. */
                $this->FE['state_msg'] .= $this->L10N->getString('response_repair_process_completed');
                if (!empty($this->Components['Meta'][$ThisTarget]['When Repair Succeeds'])) {
                    $this->executor($this->Components['Meta'][$ThisTarget]['When Repair Succeeds'], true);
                }
            } else {
                $RepairFailed = true;

                /** Repair operation failed. */
                $this->FE['state_msg'] .= $this->L10N->getString('response_repair_process_failed');
                if (!empty($this->Components['Meta'][$ThisTarget]['When Repair Fails'])) {
                    $this->executor($this->Components['Meta'][$ThisTarget]['When Repair Fails'], true);
                }
            }
            $this->formatFileSize($BytesAdded);
            $this->formatFileSize($BytesRemoved);
            $this->FE['state_msg'] .= sprintf(
                $this->FE['CronMode'] !== '' ? " « +%s | -%s | %s »\n" : ' <code><span class="txtGn">+%s</span> | <span class="txtRd">-%s</span> | <span class="txtOe">%s</span></code><br />',
                $BytesAdded,
                $BytesRemoved,
                $this->NumberFormatter->format(microtime(true) - $TimeRequired, 3)
            );
            if ($Reactivate !== 0) {
                $this->updatesHandlerActivate($ThisTarget);
            }
        }
    }

    /**
     * Updates handler: Verify a component.
     *
     * @param string|array $ID The IDs of the components to verify.
     * @return void
     */
    private function updatesHandlerVerify($ID): void
    {
        $this->arrayify($ID);
        $ID = array_unique($ID);
        foreach ($ID as $ThisID) {
            $Table = '<blockquote class="ng1 comSub">';
            if (empty($this->Components['Meta'][$ThisID]['Files'])) {
                continue;
            }
            $TheseFiles = $this->Components['Meta'][$ThisID]['Files'];
            $this->arrayify($TheseFiles);
            $Passed = true;
            foreach ($TheseFiles as $ThisFile => $Metadata) {
                $ThisFileData = $this->readFile($this->Vault . $ThisFile);

                /** Sanity check. */
                if (preg_match('~\.(?:css|dat|gif|png|ya?ml)$~i', $ThisFile)) {
                    $Class = $this->sanityCheck($ThisFile, $ThisFileData) ? 'txtGn' : 'txtRd';
                    $Sanity = sprintf('<span class="%s">%s</span>', $Class, $this->L10N->getString(
                        $Class === 'txtGn' ? 'response_passed' : 'response_failed'
                    ));
                    if ($Class === 'txtRd') {
                        $Passed = false;
                    }
                } else {
                    $Sanity = sprintf('<span class="txtOe">%s</span>', $this->L10N->getString('response_skipped'));
                }

                $Checksum = $Metadata['Checksum'] ?? '';
                $Len = strlen($ThisFileData);
                $HashPartLen = strpos($Checksum, ':') ?: 64;
                $Actual = hash('sha256', $ThisFileData) . ':' . $Len;

                /** Integrity check. */
                if (strlen($Checksum)) {
                    if ($Actual !== $Checksum) {
                        $Class = 'txtRd';
                        $Passed = false;
                    } else {
                        $Class = 'txtGn';
                    }
                    $Integrity = sprintf('<span class="%s">%s</span>', $Class, $this->L10N->getString(
                        $Class === 'txtGn' ? 'response_passed' : 'response_failed'
                    ));
                } else {
                    $Class = 's';
                    $Integrity = sprintf('<span class="txtOe">%s</span>', $this->L10N->getString('response_skipped'));
                }

                /** Append results. */
                $Table .= sprintf(
                    '<code>%1$s</code> – %7$s %8$s – %9$s %10$s<br />%2$s – <code class="%6$s">%3$s</code><br />%4$s – <code class="%6$s">%5$s</code><hr />',
                    $ThisFile,
                    $this->L10N->getString('label_actual'),
                    $Actual ?: '?',
                    $this->L10N->getString('label_expected'),
                    $Checksum ?: '?',
                    $Class,
                    $this->L10N->getString('label_integrity_check'),
                    $Integrity,
                    $this->L10N->getString('label_sanity_check'),
                    $Sanity
                );
            }
            $Table .= '</blockquote>';
            $this->FE['state_msg'] .= sprintf(
                '<div><span class="comCat" style="cursor:pointer"><code>%s</code> – <span class="%s">%s</span></span>%s</div>',
                $ThisID,
                ($Passed ? 's' : 'txtRd'),
                $this->L10N->getString($Passed ? 'response_verification_success' : 'response_verification_failed'),
                $Table
            );
        }
    }

    /**
     * Determine whether all dependency constraints have been met.
     *
     * @param array $ThisComponent Reference to the component being worked upon.
     * @param bool $Source False for installed; True for available.
     * @param string $Name If specified, if not installed, won't check constraints.
     * @return void
     */
    private function checkConstraints(array &$ThisComponent, bool $Source = false, string $Name = ''): void
    {
        $ThisComponent['All Constraints Met'] = true;
        $ThisComponent['Dependency Status'] = '';
        if (!isset($ThisComponent['Dependencies']) && !empty($ThisComponent['Minimum Required'])) {
            $ThisComponent['Dependencies'] = ['CIDRAM Core' => '>=' . $ThisComponent['Minimum Required']];
        }
        if (!isset($ThisComponent['Dependencies']) || !is_array($ThisComponent['Dependencies']) || (
            $Name && !isset($this->Components['Installed Versions'][$Name])
        )) {
            return;
        }
        foreach ($ThisComponent['Dependencies'] as $Dependency => $Constraints) {
            $Dependency = str_replace('{lang}', $this->Configuration['general']['lang'], $Dependency);
            if ($Constraints === 'Latest') {
                if (isset($this->Components['Available Versions'][$Dependency])) {
                    $Constraints = '>=' . $this->Components['Available Versions'][$Dependency];
                }
            }
            if ($Constraints === 'Latest' || strlen($Constraints) < 1) {
                $ThisComponent['All Constraints Met'] = false;
                $ThisComponent['Dependency Status'] .= sprintf(
                    '<span class="txtRd">%s – %s</span><br />',
                    $Dependency,
                    $this->L10N->getString('response_not_satisfied')
                );
            } elseif ((
                isset($this->Components['Installed Versions'][$Dependency]) &&
                $this->CIDRAM['Operation']->singleCompare($this->Components['Installed Versions'][$Dependency], $Constraints)
            ) || (
                extension_loaded($Dependency) &&
                ($this->Components['Installed Versions'][$Dependency] = (new \ReflectionExtension($Dependency))->getVersion()) &&
                $this->CIDRAM['Operation']->singleCompare($this->Components['Installed Versions'][$Dependency], $Constraints)
            )) {
                $ThisComponent['Dependency Status'] .= sprintf(
                    '<span class="txtGn">%s%s – %s</span><br />',
                    $Dependency,
                    $Constraints === '*' ? '' : ' (' . $Constraints . ')',
                    $this->L10N->getString('response_satisfied')
                );
            } elseif (
                $Source &&
                isset($this->Components['Available Versions'][$Dependency]) &&
                $this->CIDRAM['Operation']->singleCompare($this->Components['Available Versions'][$Dependency], $Constraints)
            ) {
                $ThisComponent['Dependency Status'] .= sprintf(
                    '<span class="txtOe">%s%s – %s</span><br />',
                    $Dependency,
                    $Constraints === '*' ? '' : ' (' . $Constraints . ')',
                    $this->L10N->getString('response_ready_to_install')
                );
                if (!isset($ThisComponent['Install Together'])) {
                    $ThisComponent['Install Together'] = [];
                }
                $ThisComponent['Install Together'][] = $Dependency;
            } else {
                $ThisComponent['All Constraints Met'] = false;
                $ThisComponent['Dependency Status'] .= sprintf(
                    '<span class="txtRd">%s%s – %s</span><br />',
                    $Dependency,
                    $Constraints === '*' ? '' : ' (' . $Constraints . ')',
                    $this->L10N->getString('response_not_satisfied')
                );
            }
        }
        if ($ThisComponent['Dependency Status']) {
            $ThisComponent['Dependency Status'] = sprintf(
                '<hr /><small><span class="s">%s</span><br />%s</small>',
                $this->L10N->getString('label_dependencies'),
                $ThisComponent['Dependency Status']
            );
        }
    }

    /**
     * Determine which components are currently installed or available.
     *
     * @param array $Source Components metadata source.
     * @param array $To Where to set the information.
     * @return void
     */
    private function checkVersions(array $Source, array &$To): void
    {
        foreach ($Source as $Key => $Component) {
            if (
                !empty($Component['Version']) &&
                isset($Component['Files']) &&
                is_array($Component['Files']) &&
                count($Component['Files'])
            ) {
                $To[$Key] = $Component['Version'];
            }
        }
    }

    /**
     * Check whether a variable has a value.
     *
     * @param mixed $Haystack What we're looking in (may be an array, or may be scalar).
     * @param mixed $Needle What we're looking for (may be an array, or may be scalar).
     * @return bool True if it does; False if it doesn't.
     */
    private function has($Haystack, $Needle): bool
    {
        if (is_array($Haystack)) {
            foreach ($Haystack as $ThisHaystack) {
                if ($this->has($ThisHaystack, $Needle)) {
                    return true;
                }
            }
            return false;
        }
        if (is_array($Needle)) {
            foreach ($Needle as $ThisNeedle) {
                if ($ThisNeedle === $Haystack) {
                    return true;
                }
            }
            return false;
        }
        return $Needle === $Haystack;
    }

    /**
     * Calculate shared files.
     *
     * @return void
     */
    private function calculateShared(): void
    {
        if (!isset($this->Components['Meta'])) {
            return;
        }
        if (!isset($this->Components['Shared'])) {
            $this->Components['Shared'] = [];
        }
        foreach ($this->Components['Meta'] as &$Component) {
            if (!isset($Component['Files'])) {
                continue;
            }
            $Component['Has Signatures'] = false;
            foreach ($Component['Files'] as $FileName => $FileMeta) {
                if (!isset($this->Components['Shared'][$FileName])) {
                    $this->Components['Shared'][$FileName] = 0;
                }
                $this->Components['Shared'][$FileName]++;

                /** Determine whether this component includes any signature files. */
                if (isset($FileMeta['Used with']) && (
                    $FileMeta['Used with'] === 'ipv4' ||
                    $FileMeta['Used with'] === 'ipv6'
                )) {
                    $Component['Has Signatures'] = true;
                }
            }
        }
    }
}
