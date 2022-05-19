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
 * This file: The CIDRAM updater (last modified: 2022.05.19).
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
        if (isset($this->CIDRAM['FE']['state_msg'])) {
            if ($Try = $this->L10N->getString($Message)) {
                $Message = $Try;
            }
            $this->CIDRAM['FE']['state_msg'] .= $Message . '<br />';
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
     * empty prior to calling the closure (allows cleaner code in some areas).
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
     * Checks whether a component is in use (front-end closure).
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
        if (!isset($Component['Used with'])) {
            return 0;
        }
        $Files = $Component['Files']['To'];
        $this->arrayify($Files);
        if (!isset($Component['Used with'])) {
            return 0;
        }
        $UsedWith = $Component['Used with'];
        foreach ($Files as $File) {
            $FileSafe = preg_quote($File);
            if (is_array($UsedWith)) {
                $ThisUsedWith = (string)array_shift($UsedWith);
                if (
                    $ThisUsedWith !== 'imports' &&
                    $ThisUsedWith !== 'events' &&
                    $ThisUsedWith !== 'ipv4' &&
                    $ThisUsedWith !== 'ipv6' &&
                    $ThisUsedWith !== 'modules'
                ) {
                    continue;
                }
            } else {
                $ThisUsedWith = $UsedWith;
                if ($ThisUsedWith !== 'imports' && preg_match('~\.ya?ml$~i', $FileSafe)) {
                    continue;
                }
            }
            if (
                $ThisUsedWith === 'n/a' ||
                preg_match('~^$|\.(?:css|gif|html?|jpe?g|js|png)$|^(?:assets|classes)[\x2F\x5C]~i', $File) ||
                !file_exists($this->Vault . $File)
            ) {
                continue;
            }
            if (($ThisUsedWith === 'ipv4' || $ThisUsedWith === 'ipv6') && substr($File, 0, 11) === 'signatures/') {
                $FileSafe = preg_quote(substr($File, 11));
            } elseif ($ThisUsedWith === 'modules' && substr($File, 0, 8) === 'modules/') {
                $FileSafe = preg_quote(substr($File, 8));
            }
            if (($ThisUsedWith === 'imports' && preg_match(
                '~,(?:[\w\d]+:)?' . $FileSafe . ',~',
                ',' . $this->Configuration['general']['config_imports'] . ','
            )) || ($ThisUsedWith === 'events' && preg_match(
                '~,(?:[\w\d]+:)?' . $FileSafe . ',~',
                ',' . $this->Configuration['general']['events'] . ','
            )) || ($ThisUsedWith === 'ipv4' && preg_match(
                '~,(?:[\w\d]+:)?' . $FileSafe . ',~',
                ',' . $this->Configuration['signatures']['ipv4'] . ','
            )) || ($ThisUsedWith === 'ipv6' && preg_match(
                '~,(?:[\w\d]+:)?' . $FileSafe . ',~',
                ',' . $this->Configuration['signatures']['ipv6'] . ','
            )) || ($ThisUsedWith === 'modules' && preg_match(
                '~,(?:[\w\d]+:)?' . $FileSafe . ',~',
                ',' . $this->Configuration['signatures']['modules'] . ','
            ))) {
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
        if (!isset($this->CIDRAM['Components']['RemoteMeta'])) {
            $this->CIDRAM['Components']['RemoteMeta'] = [];
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
            $this->YAML->process($RemoteData, $this->CIDRAM['Components']['RemoteMeta']);
        }
    }

    /**
     * Checks whether component is activable.
     *
     * @param array $Component An array of the component metadata.
     * @return bool True for when activable; False for when not activable.
     */
    private function isActivable(array &$Component): bool
    {
        return (
            !empty($Component['Used with']) &&
            $this->has($Component['Used with'], ['imports', 'events', 'ipv4', 'ipv6', 'modules'])
        );
    }

    /**
     * Deactivate component.
     *
     * @param string $Type Value can be ipv4, ipv6, or modules.
     * @param string $ID The ID of the component to deactivate.
     * @return void
     */
    private function deactivateComponent(string $Type, string $ID): void
    {
        $this->CIDRAM['Deactivation'][$Type] = array_unique(array_filter(
            explode(',', $this->CIDRAM['Deactivation'][$Type]),
            function ($Component) {
                return ($Component = (strpos($Component, ':') === false) ? $Component : substr($Component, strpos($Component, ':') + 1));
            }
        ));
        if (count($this->CIDRAM['Deactivation'][$Type])) {
            sort($this->CIDRAM['Deactivation'][$Type]);
        }
        $this->CIDRAM['Deactivation'][$Type] = ',' . implode(',', $this->CIDRAM['Deactivation'][$Type]) . ',';
        foreach ($this->CIDRAM['Components']['Meta'][$ID]['Files']['To'] as $File) {
            $this->CIDRAM['Deactivation'][$Type] = preg_replace(
                '~,(?:[\w\d]+:)?' . preg_quote($File) . ',~',
                ',',
                $this->CIDRAM['Deactivation'][$Type]
            );
        }
        $this->CIDRAM['Deactivation'][$Type] = substr($this->CIDRAM['Deactivation'][$Type], 1, -1);
        if ($Type === 'imports') {
            if ($this->CIDRAM['Deactivation']['imports'] !== $this->Configuration['general']['config_imports']) {
                $this->CIDRAM['Deactivation']['Modified'] = true;
            }
            return;
        }
        if ($Type === 'events') {
            if ($this->CIDRAM['Deactivation']['events'] !== $this->Configuration['general']['events']) {
                $this->CIDRAM['Deactivation']['Modified'] = true;
            }
            return;
        }
        if ($this->CIDRAM['Deactivation'][$Type] !== $this->Configuration['signatures'][$Type]) {
            $this->CIDRAM['Deactivation']['Modified'] = true;
        }
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
        } elseif (empty($Arr['Extended Description'])) {
            $Arr['Extended Description'] = '';
        }
        if (
            !empty($Arr['Used with']) &&
            !is_array($Arr['Used with']) &&
            strpos($Arr['Extended Description'], '-&gt;') === false
        ) {
            $Arr['Extended Description'] .= sprintf(
                '<br /><em>%s <code>signatures-&gt;%s</code></em>',
                $this->L10N->getString('label_used_with'),
                $Arr['Used with']
            );
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
    public function componentUpdatePrep(string $Target): int
    {
        if (!empty($this->CIDRAM['Components']['Meta'][$Target]['Files'])) {
            $this->prepareExtendedDescription($this->CIDRAM['Components']['Meta'][$Target]);
            $this->arrayify($this->CIDRAM['Components']['Meta'][$Target]['Files']);
            $this->arrayify($this->CIDRAM['Components']['Meta'][$Target]['Files']['To']);
            return $this->isInUse($this->CIDRAM['Components']['Meta'][$Target]);
        }
        return 0;
    }

    /**
     * Executes a list of closures or commands when specific conditions are met.
     *
     * @param string|array $Closures The list of closures or commands to execute.
     * @param bool $Queue Whether to queue the operation or perform immediately.
     * @return void
     */
    private function executor($Closures = false, bool $Queue = false): void
    {
        if ($Queue && $Closures !== false) {
            /** Guard. */
            if (empty($this->CIDRAM['executor_Queue']) || !is_array($this->CIDRAM['executor_Queue'])) {
                $this->CIDRAM['executor_Queue'] = [];
            }

            /** Add to the executor queue. */
            $this->CIDRAM['executor_Queue'][] = $Closures;
            return;
        }

        if ($Closures === false && !empty($this->CIDRAM['executor_Queue']) && is_array($this->CIDRAM['executor_Queue'])) {
            /** We'll iterate an array from the local scope to guard against infinite loops. */
            $Items = $this->CIDRAM['executor_Queue'];

            /** Purge the queue before iterating. */
            $this->CIDRAM['executor_Queue'] = [];

            /** Recursively iterate through the executor queue. */
            foreach ($Items as $QueueItem) {
                $this->executor($QueueItem);
            }
            return;
        }

        /** Guard. */
        $this->arrayify($Closures);

        /** Recursively execute all closures in the current queue item. */
        foreach ($Closures as $Closure) {
            /** All logic, data traversal, dot notation, etc handled here. */
            $Closure = $this->CIDRAM['Operation']->ifCompare($this->CIDRAM, $Closure);

            if (isset($this->CIDRAM[$Closure]) && is_object($this->CIDRAM[$Closure])) {
                $this->CIDRAM[$Closure]();
            } elseif (($Pos = strpos($Closure, ' ')) !== false) {
                $Params = substr($Closure, $Pos + 1);
                $Closure = substr($Closure, 0, $Pos);
                if (isset($this->CIDRAM[$Closure]) && is_object($this->CIDRAM[$Closure])) {
                    $Params = $this->CIDRAM['Operation']->ifCompare($this->CIDRAM, $Params);
                    $this->CIDRAM[$Closure]($Params);
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
            !empty($this->CIDRAM['Components']['RemoteMeta']['CIDRAM']['Version']) &&
            ($ThisData = $this->CIDRAM['Updater-IO']->readFile($this->Vault . '../cidram.php'))
        ) {
            $PlugHead = "\x3C\x3Fphp\n/**\n * Plugin Name: CIDRAM\n * Version: ";
            if (substr($ThisData, 0, 45) === $PlugHead) {
                $PlugHeadEnd = strpos($ThisData, "\n", 45);
                $this->CIDRAM['Updater-IO']->writeFile(
                    $this->Vault . '../cidram.php',
                    $PlugHead . $this->CIDRAM['Components']['RemoteMeta']['CIDRAM Core']['Version'] . substr($ThisData, $PlugHeadEnd)
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
        $Type = $this->CIDRAM['FE']['sort-by-name'] ?? false;
        $Order = $this->CIDRAM['FE']['descending-order'] ?? false;
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
                    isset($this->CIDRAM['Components']['Meta'][$ThisID]) &&
                    $this->isActivable($this->CIDRAM['Components']['Meta'][$ThisID]) &&
                    $this->isInUse($this->CIDRAM['Components']['Meta'][$ThisID]) !== 1
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
                    isset($this->CIDRAM['Components']['Meta'][$ThisID]) &&
                    $this->isActivable($this->CIDRAM['Components']['Meta'][$ThisID]) &&
                    $this->isInUse($this->CIDRAM['Components']['Meta'][$ThisID]) !== 0
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
            if (isset($this->CIDRAM['Components']['Meta'][$ThisTarget])) {
                $Reactivate = $this->isInUse($this->CIDRAM['Components']['Meta'][$ThisTarget]);
            } else {
                $Reactivate = 0;
            }
            if ($Reactivate !== 0) {
                $this->updatesHandlerDeactivate($ThisTarget);
            }
            $this->checkConstraints($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget], true);
            $UpdateFailed = false;
            if (
                $this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['All Constraints Met'] &&
                !empty($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From']) &&
                !empty($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'])
            ) {
                $this->arrayify($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']);
                $this->arrayify($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From']);
                $this->arrayify($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To']);
                if (!empty($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum'])) {
                    $this->arrayify($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum']);
                }
                $Count = count($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From']);
                $RemoteFiles = [];
                $IgnoredFiles = [];
                $Rollback = false;

                /** Write new and updated files and directories. */
                for ($Iterate = 0; $Iterate < $Count; $Iterate++) {
                    if (empty($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'][$Iterate])) {
                        continue;
                    }
                    $ThisFileName = $this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'][$Iterate];

                    /** Rolls back to previous version or uninstalls if an update/install fails. */
                    if ($Rollback) {
                        if (
                            isset($RemoteFiles[$ThisFileName]) &&
                            !isset($IgnoredFiles[$ThisFileName]) &&
                            is_readable($this->Vault . $ThisFileName)
                        ) {
                            $BytesAdded -= filesize($this->Vault . $ThisFileName);
                            unlink($this->Vault . $ThisFileName);
                            if (is_readable($this->Vault . $ThisFileName . '.rollback')) {
                                $BytesRemoved -= filesize($this->Vault . $ThisFileName . '.rollback');
                                rename($this->Vault . $ThisFileName . '.rollback', $this->Vault . $ThisFileName);
                            }
                        }
                        continue;
                    }
                    if (
                        !empty($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum'][$Iterate]) &&
                        !empty($this->CIDRAM['Components']['Meta'][$ThisTarget]['Files']['Checksum'][$Iterate]) && (
                            $this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum'][$Iterate] ===
                            $this->CIDRAM['Components']['Meta'][$ThisTarget]['Files']['Checksum'][$Iterate]
                        )
                    ) {
                        $IgnoredFiles[$ThisFileName] = true;
                        continue;
                    }
                    if (
                        empty($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From'][$Iterate]) ||
                        !($ThisFile = $this->Request->request($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From'][$Iterate]))
                    ) {
                        $Iterate = 0;
                        $Rollback = true;
                        continue;
                    }
                    if (
                        strtolower(
                            substr($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From'][$Iterate], -2)
                        ) === 'gz' &&
                        strtolower(substr($ThisFileName, -2)) !== 'gz' &&
                        substr($ThisFile, 0, 2) === "\x1F\x8B"
                    ) {
                        $ThisFile = gzdecode($ThisFile);
                    }
                    if (!empty($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum'][$Iterate])) {
                        $ThisChecksum = $this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum'][$Iterate];
                        $ThisLen = strlen($ThisFile);
                        if (hash('sha256', $ThisFile) . ':' . $ThisLen !== $ThisChecksum) {
                            $this->CIDRAM['FE']['state_msg'] .=
                                '<code>' . $ThisTarget . '</code> – ' .
                                '<code>' . $ThisFileName . '</code> – ' .
                                $this->L10N->getString('response_checksum_error') . '<br />';
                            if (!empty($this->CIDRAM['Components']['Meta'][$ThisTarget]['On Checksum Error'])) {
                                $this->executor($this->CIDRAM['Components']['Meta'][$ThisTarget]['On Checksum Error'], true);
                            }
                            $Iterate = 0;
                            $Rollback = true;
                            continue;
                        }
                    }
                    if (
                        preg_match('~\.(?:css|dat|gif|inc|jpe?g|php|png|ya?ml|[a-z]{0,2}db)$~i', $ThisFileName) &&
                        !$this->sanityCheck($ThisFileName, $ThisFile)
                    ) {
                        $this->CIDRAM['FE']['state_msg'] .= sprintf(
                            '<code>%s</code> – <code>%s</code> – %s<br />',
                            $ThisTarget,
                            $ThisFileName,
                            $this->L10N->getString('response_sanity_1')
                        );
                        if (!empty($this->CIDRAM['Components']['Meta'][$ThisTarget]['On Sanity Error'])) {
                            $this->executor($this->CIDRAM['Components']['Meta'][$ThisTarget]['On Sanity Error'], true);
                        }
                        $Iterate = 0;
                        $Rollback = true;
                        continue;
                    }
                    $this->buildPath($this->Vault . $ThisFileName);
                    if (is_readable($this->Vault . $ThisFileName)) {
                        $BytesRemoved += filesize($this->Vault . $ThisFileName);
                        if (file_exists($this->Vault . $ThisFileName . '.rollback')) {
                            $BytesRemoved += filesize($this->Vault . $ThisFileName . '.rollback');
                            unlink($this->Vault . $ThisFileName . '.rollback');
                        }
                        rename($this->Vault . $ThisFileName, $this->Vault . $ThisFileName . '.rollback');
                    }
                    $BytesAdded += strlen($ThisFile);
                    $Handle = fopen($this->Vault . $ThisFileName, 'wb');
                    $RemoteFiles[$ThisFileName] = fwrite($Handle, $ThisFile);
                    $RemoteFiles[$ThisFileName] = ($RemoteFiles[$ThisFileName] !== false);
                    fclose($Handle);
                    $ThisFile = '';
                }

                if ($Rollback) {
                    /** Prune unwanted empty directories (update/install failure+rollback). */
                    if (
                        !empty($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To']) &&
                        is_array($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'])
                    ) {
                        foreach ($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'] as $ThisFile) {
                            if (!empty($ThisFile) && $this->freeFromTraversal($ThisFile)) {
                                $this->deleteDirectory($ThisFile);
                            }
                        }
                    }
                    $UpdateFailed = true;
                } else {
                    /** Prune unwanted files and directories (update/install success). */
                    if (!empty($this->CIDRAM['Components']['Meta'][$ThisTarget]['Files']['To'])) {
                        $ThisArr = $this->CIDRAM['Components']['Meta'][$ThisTarget]['Files']['To'];
                        $this->arrayify($ThisArr);
                        foreach ($ThisArr as $ThisFile) {
                            if (!empty($ThisFile) && $this->freeFromTraversal($ThisFile)) {
                                if (file_exists($this->Vault . $ThisFile . '.rollback')) {
                                    unlink($this->Vault . $ThisFile . '.rollback');
                                }
                                if (
                                    !isset($RemoteFiles[$ThisFile]) &&
                                    !isset($IgnoredFiles[$ThisFile]) &&
                                    file_exists($this->Vault . $ThisFile)
                                ) {
                                    $BytesRemoved += filesize($this->Vault . $ThisFile);
                                    unlink($this->Vault . $ThisFile);
                                    $this->deleteDirectory($ThisFile);
                                }
                            }
                        }
                        unset($ThisFile, $ThisArr);
                    }

                    $this->CIDRAM['FE']['state_msg'] .= '<code>' . $ThisTarget . '</code> – ';
                    if (
                        empty($this->CIDRAM['Components']['Meta'][$ThisTarget]['Version']) &&
                        empty($this->CIDRAM['Components']['Meta'][$ThisTarget]['Files'])
                    ) {
                        $this->CIDRAM['FE']['state_msg'] .= $this->L10N->getString('response_component_successfully_installed');
                        if (!empty($this->CIDRAM['Components']['Meta'][$ThisTarget]['When Install Succeeds'])) {
                            $this->executor($this->CIDRAM['Components']['Meta'][$ThisTarget]['When Install Succeeds'], true);
                        }
                    } else {
                        $this->CIDRAM['FE']['state_msg'] .= $this->L10N->getString('response_component_successfully_updated');
                        if (!empty($this->CIDRAM['Components']['Meta'][$ThisTarget]['When Update Succeeds'])) {
                            $this->executor($this->CIDRAM['Components']['Meta'][$ThisTarget]['When Update Succeeds'], true);
                        }
                    }

                    /** Assign updated component meta. */
                    $this->CIDRAM['Components']['Meta'][$ThisTarget] = $this->CIDRAM['Components']['RemoteMeta'][$ThisTarget];

                    /** Update the component metadata file. */
                    $this->CIDRAM['Updater-IO']->writeFile(
                        $this->Vault . 'installed.yml',
                        $this->YAML->reconstruct($this->CIDRAM['Components']['Meta'])
                    );

                    /** Set trigger for signatures update event. */
                    if (
                        !empty($this->CIDRAM['Components']['Meta'][$ThisTarget]['Used with']) &&
                        $this->has($this->CIDRAM['Components']['Meta'][$ThisTarget]['Used with'], ['ipv4', 'ipv6'])
                    ) {
                        $this->CIDRAM['SignaturesUpdateEvent'] = $this->Now;
                    }
                }
            } else {
                $UpdateFailed = true;
            }
            if ($UpdateFailed) {
                $this->CIDRAM['FE']['state_msg'] .= '<code>' . $ThisTarget . '</code> – ';
                if (
                    empty($this->CIDRAM['Components']['Meta'][$ThisTarget]['Version']) &&
                    empty($this->CIDRAM['Components']['Meta'][$ThisTarget]['Files'])
                ) {
                    $this->CIDRAM['FE']['state_msg'] .= $this->L10N->getString('response_failed_to_install');
                    if (!empty($this->CIDRAM['Components']['Meta'][$ThisTarget]['When Install Fails'])) {
                        $this->executor($this->CIDRAM['Components']['Meta'][$ThisTarget]['When Install Fails'], true);
                    }
                } else {
                    $this->CIDRAM['FE']['state_msg'] .= $this->L10N->getString('response_failed_to_update');
                    if (!empty($this->CIDRAM['Components']['Meta'][$ThisTarget]['When Update Fails'])) {
                        $this->executor($this->CIDRAM['Components']['Meta'][$ThisTarget]['When Update Fails'], true);
                    }
                }
            }
            $this->formatFileSize($BytesAdded);
            $this->formatFileSize($BytesRemoved);
            $this->CIDRAM['FE']['state_msg'] .= sprintf(
                $this->CIDRAM['FE']['CronMode'] !== '' ? " « +%s | -%s | %s »\n" : ' <code><span class="txtGn">+%s</span> | <span class="txtRd">-%s</span> | <span class="txtOe">%s</span></code><br />',
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
        $this->CIDRAM['FE']['state_msg'] .= '<code>' . $ID . '</code> – ';
        if (
            $InUse === 0 &&
            !empty($this->CIDRAM['Components']['Meta'][$ID]['Files']['To']) &&
            !empty($this->CIDRAM['Components']['Meta'][$ID]['Uninstallable'])
        ) {
            /** Iterate through and remove all the component's files. */
            foreach ($this->CIDRAM['Components']['Meta'][$ID]['Files']['To'] as $ThisFile) {
                if (empty($ThisFile) || !$this->freeFromTraversal($ThisFile)) {
                    continue;
                }
                if (file_exists($this->Vault . $ThisFile)) {
                    $BytesRemoved += filesize($this->Vault . $ThisFile);
                    unlink($this->Vault . $ThisFile);
                }
                if (file_exists($this->Vault . $ThisFile . '.rollback')) {
                    $BytesRemoved += filesize($this->Vault . $ThisFile . '.rollback');
                    unlink($this->Vault . $ThisFile . '.rollback');
                }
                $this->deleteDirectory($ThisFile);
            }

            $this->CIDRAM['FE']['state_msg'] .= $this->L10N->getString('response_component_successfully_uninstalled');
            if (!empty($this->CIDRAM['Components']['Meta'][$ID]['When Uninstall Succeeds'])) {
                $this->executor($this->CIDRAM['Components']['Meta'][$ID]['When Uninstall Succeeds'], true);
            }

            /** Remove downstream meta. */
            unset($this->CIDRAM['Components']['Meta'][$ID]);

            /** Update the component metadata file. */
            $this->CIDRAM['Updater-IO']->writeFile(
                $this->Vault . 'installed.yml',
                $this->YAML->reconstruct($this->CIDRAM['Components']['Meta'])
            );
        } else {
            $this->CIDRAM['FE']['state_msg'] .= $this->L10N->getString('response_component_uninstall_error');
            if (!empty($this->CIDRAM['Components']['Meta'][$ID]['When Uninstall Fails'])) {
                $this->executor($this->CIDRAM['Components']['Meta'][$ID]['When Uninstall Fails'], true);
            }
        }
        $this->formatFileSize($BytesRemoved);
        $this->CIDRAM['FE']['state_msg'] .= sprintf(
            $this->CIDRAM['FE']['CronMode'] !== '' ? " « -%s | %s »\n" : ' <code><span class="txtRd">-%s</span> | <span class="txtOe">%s</span></code><br />',
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
            'Config' => $this->CIDRAM['Updater-IO']->readFile($this->Vault . $this->CIDRAM['FE']['ActiveConfigFile']),
            'imports' => $this->Configuration['general']['config_imports'],
            'events' => $this->Configuration['general']['events'],
            'ipv4' => $this->Configuration['signatures']['ipv4'],
            'ipv6' => $this->Configuration['signatures']['ipv6'],
            'modules' => $this->Configuration['signatures']['modules'],
            'Modified' => false
        ];
        foreach (['imports', 'events', 'ipv4', 'ipv6', 'modules'] as $Type) {
            $Activation[$Type] = array_unique(array_filter(
                explode(',', $Activation[$Type]),
                function ($Component) use ($Type) {
                    $Component = (strpos($Component, ':') === false) ? $Component : substr($Component, strpos($Component, ':') + 1);
                    if ($Type === 'ipv4' || $Type === 'ipv6') {
                        $Path = $this->SignaturesPath;
                    } elseif ($Type === 'modules') {
                        $Path = $this->ModulesPath;
                    } else {
                        $Path = $this->Vault;
                    }
                    return ($Component && file_exists($Path . $Component));
                }
            ));
        }
        $InUse = $this->componentUpdatePrep($ID);
        $this->CIDRAM['FE']['state_msg'] .= '<code>' . $ID . '</code> – ';
        if ($InUse !== 1 && !empty($this->CIDRAM['Components']['Meta'][$ID]['Files']['To']) && (
            !empty($this->CIDRAM['Components']['Meta'][$ID]['Used with']) ||
            !empty($this->CIDRAM['Components']['Meta'][$ID]['Extended Description'])
        )) {
            $UsedWith = $this->CIDRAM['Components']['Meta'][$ID]['Used with'] ?? '';
            foreach ($this->CIDRAM['Components']['Meta'][$ID]['Files']['To'] as $File) {
                $FileSafe = preg_quote($File);
                if (is_array($UsedWith)) {
                    $ThisUsedWith = (string)array_shift($UsedWith);
                } else {
                    $ThisUsedWith = $UsedWith;
                    if ($ThisUsedWith !== 'imports' && preg_match('~\.ya?ml$~i', $FileSafe)) {
                        continue;
                    }
                }
                if (
                    preg_match('~^$|\.(?:css|gif|html?|jpe?g|js|png)$|^(?:assets|classes)[\x2F\x5C]~i', $File) ||
                    !file_exists($this->Vault . $File) ||
                    !$this->freeFromTraversal($File)
                ) {
                    continue;
                }
                if ($ThisUsedWith === '') {
                    continue;
                }
                if (
                    $ThisUsedWith !== 'imports' &&
                    $ThisUsedWith !== 'events' &&
                    $ThisUsedWith !== 'ipv4' &&
                    $ThisUsedWith !== 'ipv6' &&
                    $ThisUsedWith !== 'modules'
                ) {
                    continue;
                }
                $Activation[$ThisUsedWith][] = $File;
            }
        }
        foreach (['imports', 'events', 'ipv4', 'ipv6', 'modules'] as $Type) {
            if (count($Activation[$Type])) {
                sort($Activation[$Type]);
            }
            $Activation[$Type] = implode(',', $Activation[$Type]);
        }
        if ($Activation['imports'] !== $this->Configuration['general']['config_imports']) {
            $Activation['Modified'] = true;
        }
        if ($Activation['events'] !== $this->Configuration['general']['events']) {
            $Activation['Modified'] = true;
        }
        foreach (['ipv4', 'ipv6', 'modules'] as $Type) {
            if ($Activation[$Type] !== $this->Configuration['signatures'][$Type]) {
                $Activation['Modified'] = true;
            }
        }
        if (!$Activation['Modified'] || !$Activation['Config']) {
            $this->CIDRAM['FE']['state_msg'] .= $this->L10N->getString('response_activation_failed') . '<br />';
            if (!empty($this->CIDRAM['Components']['Meta'][$ID]['When Activation Fails'])) {
                $this->executor($this->CIDRAM['Components']['Meta'][$ID]['When Activation Fails'], true);
            }
        } else {
            $EOL = (strpos($Activation['Config'], "\r\n") !== false) ? "\r\n" : "\n";
            $Activation['Config'] = str_replace([
                $EOL . "config_imports='" . $this->Configuration['general']['config_imports'] . "'" . $EOL,
                $EOL . "events='" . $this->Configuration['general']['events'] . "'" . $EOL,
                $EOL . "ipv4='" . $this->Configuration['signatures']['ipv4'] . "'" . $EOL,
                $EOL . "ipv6='" . $this->Configuration['signatures']['ipv6'] . "'" . $EOL,
                $EOL . "modules='" . $this->Configuration['signatures']['modules'] . "'" . $EOL
            ], [
                $EOL . "config_imports='" . $Activation['imports'] . "'" . $EOL,
                $EOL . "events='" . $Activation['events'] . "'" . $EOL,
                $EOL . "ipv4='" . $Activation['ipv4'] . "'" . $EOL,
                $EOL . "ipv6='" . $Activation['ipv6'] . "'" . $EOL,
                $EOL . "modules='" . $Activation['modules'] . "'" . $EOL
            ], $Activation['Config']);
            $this->Configuration['general']['config_imports'] = $Activation['imports'];
            $this->Configuration['general']['events'] = $Activation['events'];
            $this->Configuration['signatures']['ipv4'] = $Activation['ipv4'];
            $this->Configuration['signatures']['ipv6'] = $Activation['ipv6'];
            $this->Configuration['signatures']['modules'] = $Activation['modules'];
            $this->CIDRAM['Updater-IO']->writeFile($this->Vault . $this->CIDRAM['FE']['ActiveConfigFile'], $Activation['Config']);
            $this->CIDRAM['FE']['state_msg'] .= $this->L10N->getString('response_activated') . '<br />';
            if (!empty($this->CIDRAM['Components']['Meta'][$ID]['When Activation Succeeds'])) {
                $this->executor($this->CIDRAM['Components']['Meta'][$ID]['When Activation Succeeds'], true);
            }
            $Success = true;
        }

        /** Deal with dependency activation. */
        if (
            !empty($Success) &&
            !empty($this->CIDRAM['Components']['Meta'][$ID]['Dependencies']) &&
            is_array($this->CIDRAM['Components']['Meta'][$ID]['Dependencies'])
        ) {
            foreach ($this->CIDRAM['Components']['Meta'][$ID]['Dependencies'] as $Dependency => $Constraints) {
                if (
                    !isset($this->CIDRAM['Components']['Meta'][$Dependency]) ||
                    empty($this->CIDRAM['Components']['Installed Versions'][$Dependency]) ||
                    !$this->isActivable($this->CIDRAM['Components']['Meta'][$Dependency]) ||
                    $this->isInUse($this->CIDRAM['Components']['Meta'][$Dependency]) === 1
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
            'Config' => $this->CIDRAM['Updater-IO']->readFile($this->Vault . $this->CIDRAM['FE']['ActiveConfigFile']),
            'imports' => $this->Configuration['general']['config_imports'],
            'events' => $this->Configuration['general']['events'],
            'ipv4' => $this->Configuration['signatures']['ipv4'],
            'ipv6' => $this->Configuration['signatures']['ipv6'],
            'modules' => $this->Configuration['signatures']['modules'],
            'Modified' => false
        ];
        $InUse = 0;
        $this->CIDRAM['FE']['state_msg'] .= '<code>' . $ID . '</code> – ';
        if (!empty($this->CIDRAM['Components']['Meta'][$ID]['Files'])) {
            $this->arrayify($this->CIDRAM['Components']['Meta'][$ID]['Files']);
            $this->arrayify($this->CIDRAM['Components']['Meta'][$ID]['Files']['To']);
            $ThisComponent = $this->CIDRAM['Components']['Meta'][$ID];
            $this->prepareExtendedDescription($ThisComponent);
            $InUse = $this->isInUse($ThisComponent);
            unset($ThisComponent);
        }
        if ($InUse !== 0 && !empty($this->CIDRAM['Components']['Meta'][$ID]['Files']['To'])) {
            foreach (['imports', 'events', 'ipv4', 'ipv6', 'modules'] as $Type) {
                $this->deactivateComponent($Type, $ID);
            }
        }
        if (!$this->CIDRAM['Deactivation']['Modified'] || !$this->CIDRAM['Deactivation']['Config']) {
            $this->CIDRAM['FE']['state_msg'] .= $this->L10N->getString('response_deactivation_failed') . '<br />';
            if (!empty($this->CIDRAM['Components']['Meta'][$ID]['When Deactivation Fails'])) {
                $this->executor($this->CIDRAM['Components']['Meta'][$ID]['When Deactivation Fails'], true);
            }
        } else {
            $EOL = (strpos($this->CIDRAM['Deactivation']['Config'], "\r\n") !== false) ? "\r\n" : "\n";
            $this->CIDRAM['Deactivation']['Config'] = str_replace([
                $EOL . "config_imports='" . $this->Configuration['general']['config_imports'] . "'" . $EOL,
                $EOL . "events='" . $this->Configuration['general']['events'] . "'" . $EOL,
                $EOL . "ipv4='" . $this->Configuration['signatures']['ipv4'] . "'" . $EOL,
                $EOL . "ipv6='" . $this->Configuration['signatures']['ipv6'] . "'" . $EOL,
                $EOL . "modules='" . $this->Configuration['signatures']['modules'] . "'" . $EOL
            ], [
                $EOL . "config_imports='" . $this->CIDRAM['Deactivation']['imports'] . "'" . $EOL,
                $EOL . "events='" . $this->CIDRAM['Deactivation']['events'] . "'" . $EOL,
                $EOL . "ipv4='" . $this->CIDRAM['Deactivation']['ipv4'] . "'" . $EOL,
                $EOL . "ipv6='" . $this->CIDRAM['Deactivation']['ipv6'] . "'" . $EOL,
                $EOL . "modules='" . $this->CIDRAM['Deactivation']['modules'] . "'" . $EOL
            ], $this->CIDRAM['Deactivation']['Config']);
            $this->Configuration['general']['config_imports'] = $this->CIDRAM['Deactivation']['imports'];
            $this->Configuration['general']['events'] = $this->CIDRAM['Deactivation']['events'];
            $this->Configuration['signatures']['ipv4'] = $this->CIDRAM['Deactivation']['ipv4'];
            $this->Configuration['signatures']['ipv6'] = $this->CIDRAM['Deactivation']['ipv6'];
            $this->Configuration['signatures']['modules'] = $this->CIDRAM['Deactivation']['modules'];
            $this->CIDRAM['Updater-IO']->writeFile($this->Vault . $this->CIDRAM['FE']['ActiveConfigFile'], $this->CIDRAM['Deactivation']['Config']);
            $this->CIDRAM['FE']['state_msg'] .= $this->L10N->getString('response_deactivated') . '<br />';
            if (!empty($this->CIDRAM['Components']['Meta'][$ID]['When Deactivation Succeeds'])) {
                $this->executor($this->CIDRAM['Components']['Meta'][$ID]['When Deactivation Succeeds'], true);
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
            if (!isset($this->CIDRAM['Components']['Meta'][$ThisTarget]['Files']['To'])) {
                continue;
            }
            $BytesAdded = 0;
            $BytesRemoved = 0;
            $TimeRequired = microtime(true);
            $RepairFailed = false;
            $Reactivate = $this->isInUse($this->CIDRAM['Components']['Meta'][$ThisTarget]);
            if ($Reactivate !== 0) {
                $this->updatesHandlerDeactivate($ThisTarget);
            }
            $this->CIDRAM['FE']['state_msg'] .= '<code>' . $ThisTarget . '</code> – ';
            if (isset(
                $this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'],
                $this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From'],
                $this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum']
            )) {
                $this->arrayify($this->CIDRAM['Components']['Meta'][$ThisTarget]['Files']['To']);
                $this->arrayify($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To']);
                $this->arrayify($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From']);
                $this->arrayify($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum']);
            } else {
                $RepairFailed = true;
            }
            if (
                !$RepairFailed &&
                $this->CIDRAM['Components']['Meta'][$ThisTarget]['Files']['To'] === $this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To']
            ) {
                $Files = count($this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To']);
                for ($Iterator = 0; $Iterator < $Files; $Iterator++) {
                    if (!isset(
                        $this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'][$Iterator],
                        $this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From'][$Iterator],
                        $this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum'][$Iterator]
                    ) || !$this->freeFromTraversal($this->Vault . $this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'][$Iterator])) {
                        $RepairFailed = true;
                        break;
                    }
                    $RemoteFileTo = $this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'][$Iterator];
                    $RemoteFileFrom = $this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From'][$Iterator];
                    $RemoteChecksum = $this->CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum'][$Iterator];
                    if (file_exists($this->Vault . $RemoteFileTo . '.rollback')) {
                        $BytesRemoved += filesize($this->Vault . $RemoteFileTo . '.rollback');
                        unlink($this->Vault . $RemoteFileTo . '.rollback');
                    }
                    $LocalFile = $this->readFile($this->Vault . $RemoteFileTo);
                    $LocalFileSize = strlen($LocalFile);
                    if (hash('sha256', $LocalFile) . ':' . $LocalFileSize === $RemoteChecksum) {
                        continue;
                    }
                    $RemoteFile = $this->Request->request($RemoteFileFrom);
                    if (
                        strtolower(substr($RemoteFileFrom, -2)) === 'gz' &&
                        strtolower(substr($RemoteFileTo, -2)) !== 'gz' &&
                        substr($RemoteFile, 0, 2) === "\x1F\x8B"
                    ) {
                        $RemoteFile = gzdecode($RemoteFile);
                    }
                    $RemoteFileSize = strlen($RemoteFile);
                    if (hash('sha256', $RemoteFile) . ':' . $RemoteFileSize !== $RemoteChecksum || (
                        preg_match('~\.(?:css|dat|gif|inc|jpe?g|php|png|ya?ml|[a-z]{0,2}db)$~i', $RemoteFileTo) &&
                        !$this->sanityCheck($RemoteFileTo, $RemoteFile)
                    )) {
                        $RepairFailed = true;
                        continue;
                    }
                    $this->buildPath($this->Vault . $RemoteFileTo);
                    if (file_exists($this->Vault . $RemoteFileTo) && !is_writable($this->Vault . $RemoteFileTo)) {
                        $RepairFailed = true;
                        continue;
                    }
                    $BytesRemoved += $LocalFileSize;
                    $BytesAdded += $RemoteFileSize;
                    $Handle = fopen($this->Vault . $RemoteFileTo, 'wb');
                    fwrite($Handle, $RemoteFile);
                    fclose($Handle);
                }
            } else {
                $RepairFailed = true;
            }
            if (!$RepairFailed && is_writable($this->Vault . 'installed.yml')) {
                /** Replace downstream meta with upstream meta. */
                $this->CIDRAM['Components']['Meta'][$ThisTarget] = $this->CIDRAM['Components']['RemoteMeta'][$ThisTarget];

                /** Update the component metadata file. */
                $this->CIDRAM['Updater-IO']->writeFile(
                    $this->Vault . 'installed.yml',
                    $this->YAML->reconstruct($this->CIDRAM['Components']['Meta'])
                );

                /** Repair operation succeeded. */
                $this->CIDRAM['FE']['state_msg'] .= $this->L10N->getString('response_repair_process_completed');
                if (!empty($this->CIDRAM['Components']['Meta'][$ThisTarget]['When Repair Succeeds'])) {
                    $this->executor($this->CIDRAM['Components']['Meta'][$ThisTarget]['When Repair Succeeds'], true);
                }
            } else {
                $RepairFailed = true;

                /** Repair operation failed. */
                $this->CIDRAM['FE']['state_msg'] .= $this->L10N->getString('response_repair_process_failed');
                if (!empty($this->CIDRAM['Components']['Meta'][$ThisTarget]['When Repair Fails'])) {
                    $this->executor($this->CIDRAM['Components']['Meta'][$ThisTarget]['When Repair Fails'], true);
                }
            }
            $this->formatFileSize($BytesAdded);
            $this->formatFileSize($BytesRemoved);
            $this->CIDRAM['FE']['state_msg'] .= sprintf(
                $this->CIDRAM['FE']['CronMode'] !== '' ? " « +%s | -%s | %s »\n" : ' <code><span class="txtGn">+%s</span> | <span class="txtRd">-%s</span> | <span class="txtOe">%s</span></code><br />',
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
            if (empty($this->CIDRAM['Components']['Meta'][$ThisID]['Files'])) {
                continue;
            }
            $TheseFiles = $this->CIDRAM['Components']['Meta'][$ThisID]['Files'];
            if (!empty($TheseFiles['To'])) {
                $this->arrayify($TheseFiles['To']);
            }
            $Count = count($TheseFiles['To']);
            if (!empty($TheseFiles['Checksum'])) {
                $this->arrayify($TheseFiles['Checksum']);
            }
            $Passed = true;
            for ($Iterate = 0; $Iterate < $Count; $Iterate++) {
                $ThisFile = $TheseFiles['To'][$Iterate];
                $ThisFileData = $this->readFile($this->Vault . $ThisFile);

                /** Sanity check. */
                if (
                    preg_match('~\.(?:css|dat|gif|inc|jpe?g|php|png|ya?ml|[a-z]{0,2}db)$~i', $ThisFile)
                ) {
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

                $Checksum = empty($TheseFiles['Checksum'][$Iterate]) ? '' : $TheseFiles['Checksum'][$Iterate];
                $Len = strlen($ThisFileData);
                $HashPartLen = strpos($Checksum, ':') ?: 64;
                $Actual = hash('sha256', $ThisFileData) . ':' . $Len;

                /** Integrity check. */
                if ($Checksum) {
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
            $this->CIDRAM['FE']['state_msg'] .= sprintf(
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
            $Name && !isset($this->CIDRAM['Components']['Installed Versions'][$Name])
        )) {
            return;
        }
        foreach ($ThisComponent['Dependencies'] as $Dependency => $Constraints) {
            $Dependency = str_replace('{lang}', $this->Configuration['general']['lang'], $Dependency);
            if ($Constraints === 'Latest') {
                if (isset($this->CIDRAM['Components']['Available Versions'][$Dependency])) {
                    $Constraints = '>=' . $this->CIDRAM['Components']['Available Versions'][$Dependency];
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
                isset($this->CIDRAM['Components']['Installed Versions'][$Dependency]) &&
                $this->CIDRAM['Operation']->singleCompare($this->CIDRAM['Components']['Installed Versions'][$Dependency], $Constraints)
            ) || (
                extension_loaded($Dependency) &&
                ($this->CIDRAM['Components']['Installed Versions'][$Dependency] = (new \ReflectionExtension($Dependency))->getVersion()) &&
                $this->CIDRAM['Operation']->singleCompare($this->CIDRAM['Components']['Installed Versions'][$Dependency], $Constraints)
            )) {
                $ThisComponent['Dependency Status'] .= sprintf(
                    '<span class="txtGn">%s%s – %s</span><br />',
                    $Dependency,
                    $Constraints === '*' ? '' : ' (' . $Constraints . ')',
                    $this->L10N->getString('response_satisfied')
                );
            } elseif (
                $Source &&
                isset($this->CIDRAM['Components']['Available Versions'][$Dependency]) &&
                $this->CIDRAM['Operation']->singleCompare($this->CIDRAM['Components']['Available Versions'][$Dependency], $Constraints)
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
            if (!empty($Component['Version']) && !empty($Component['Files']['To'])) {
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
}
