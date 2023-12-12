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
 * This file: The updates page (last modified: 2023.12.12).
 */

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'updates' || !($this->FE['Permissions'] === 1 || ($this->FE['Permissions'] === 3 && $this->FE['CronMode'] !== ''))) {
    die;
}

/** Include major version notice (if relevant). */
if ($this->CIDRAM['MajorVersionNotice']) {
    $this->FE['state_msg'] .= $this->CIDRAM['MajorVersionNotice'] . '<hr />';
}

$this->FE['UpdatesFormTarget'] = 'cidram-page=updates';
$this->FE['UpdatesFormTargetControls'] = '';
$StateModified = false;
$this->filterSwitch(
    ['hide-non-outdated', 'hide-unused', 'sort-by-name', 'descending-order'],
    $_POST['FilterSelector'] ?? '',
    $StateModified,
    $this->FE['UpdatesFormTarget'],
    $this->FE['UpdatesFormTargetControls']
);
if ($StateModified) {
    header('Location: ?' . $this->FE['UpdatesFormTarget']);
    die;
}
unset($StateModified);

/** Updates page form boilerplate. */
$this->FE['CFBoilerplate'] =
    '<form action="?%s" method="POST" style="display:inline">' .
    '<input name="cidram-form-target" type="hidden" value="updates" />' .
    '<input name="do" type="hidden" value="%s" />';

/** Prepare components metadata working array. */
$this->Components = [
    'Meta' => [],
    'Macros' => [],
    'Installed Versions' => ['PHP' => PHP_VERSION],
    'Available Versions' => [],
    'In Use' => [],
    'Install Together' => [],
    'Outdated' => [],
    'OutdatedSignatureFiles' => [],
    'Verify' => [],
    'Repairable' => [],
    'Out' => []
];
$this->fetchRemotesData();

/** Fetch components metadata. */
$this->readInstalledMetadata($this->Components['Meta']);

/** Fetch components metadata. */
$this->readInstalledMacros($this->Components['Macros']);

/** Check current versions beforehand (needed for dependency checks). */
$this->checkVersions($this->Components['Meta'], $this->Components['Installed Versions']);

/** Check available versions beforehand (needed for dependency checks). */
$this->checkVersions($this->Components['RemoteMeta'], $this->Components['Available Versions']);

$this->FE['Indexes'] = [];

/** Calculate shared files. */
$this->calculateShared();

/** A form has been submitted. */
if (empty($this->Alternate) && $this->FE['FormTarget'] === 'updates') {
    /** Components. */
    if (!empty($_POST['do']) && !empty($_POST['ID'])) {
        /** Trigger updates handler. */
        $this->updatesHandler($_POST['do'], $_POST['ID']);

        /** Trigger signatures update log event. */
        if (!empty($this->CIDRAM['SignaturesUpdateEvent'])) {
            $this->CIDRAM['SignaturesUpdateEvent'] = sprintf(
                $this->L10N->getString('response.Signature files have been updated (%s)'),
                $this->timeFormat(
                    $this->CIDRAM['SignaturesUpdateEvent'],
                    $this->Configuration['general']['time_format']
                )
            );
            $this->Events->fireEvent('writeToSignaturesUpdateEventLog', $this->CIDRAM['SignaturesUpdateEvent']);
        }
    }

    /** Macros. */
    if (!empty($_POST['Macro']) && is_string($_POST['Macro'])) {
        if (isset($this->Components['Macros'][$_POST['Macro']]['On Execute'])) {
            $this->executor($this->Components['Macros'][$_POST['Macro']]['On Execute']);
        } else {
            $this->FE['state_msg'] .= $this->L10N->getString('response.Failed to execute') . '<br />';
        }
    }

    /** Check again, since the information might've been updated. */
    $this->checkVersions($this->Components['Meta'], $this->Components['Installed Versions']);

    /** Recalculate shared files. */
    $this->calculateShared();
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.Updates'), $this->L10N->getString('tip.Updates'));

$this->FE['UpdatesRow'] = $this->readFile($this->getAssetPath('_updates_row.html'));
$this->FE['MacrosRow'] = $this->parseVars(['UpdatesFormTarget' => $this->FE['UpdatesFormTarget']], $this->readFile($this->getAssetPath('_updates_macro.html')));

/** Prepare installed component metadata and options for display. */
foreach ($this->Components['Meta'] as $Key => &$this->Components['ThisComponent']) {
    /** Fall back to component key if the component's name isn't defined. */
    if (empty($this->Components['ThisComponent']['Name']) && $this->L10N->getString('Name.' . $Key) === '') {
        $this->Components['ThisComponent']['Name'] = $Key;
    }

    /** Execute any necessary preload instructions. */
    if (!empty($this->Components['ThisComponent']['When Checking'])) {
        $this->executor($this->Components['ThisComponent']['When Checking']);
    }

    /** Determine whether all dependency constraints have been met. */
    $this->checkConstraints($this->Components['ThisComponent'], false, $Key);

    $this->prepareName($this->Components['ThisComponent'], $Key);
    $this->prepareExtendedDescription($this->Components['ThisComponent'], $Key);
    $this->Components['ThisComponent']['ID'] = $Key;
    $this->Components['ThisComponent']['Options'] = '';
    $this->Components['ThisComponent']['StatusOptions'] = '';
    $this->Components['ThisComponent']['StatClass'] = '';
    if (isset($this->Components['Available Versions'][$Key])) {
        $this->Components['ThisComponent']['Latest'] = $this->Components['Available Versions'][$Key];
    } else {
        $this->Components['ThisComponent']['Latest'] = $this->L10N->getString('response.Unable to determine');
        $this->Components['ThisComponent']['StatClass'] = 's';
    }

    /** Guard against component metadata missing at the upstream. */
    if (!isset($this->Components['RemoteMeta'][$Key])) {
        $this->Components['RemoteMeta'][$Key] = [];
    }

    /** Determine whether all dependency constraints have been met. */
    $this->checkConstraints($this->Components['RemoteMeta'][$Key], true);
    foreach (['Dependency Status', 'All Constraints Met'] as $CopyItem) {
        $this->Components['ThisComponent']['Remote ' . $CopyItem] = $this->Components['RemoteMeta'][$Key][$CopyItem] ?? '';
    }
    unset($CopyItem);
    if (isset($this->Components['RemoteMeta'][$Key]['Install Together'])) {
        if (!isset($this->Components['Install Together'][$Key])) {
            $this->Components['Install Together'][$Key] = [];
        }
        $this->Components['Install Together'][$Key] = array_merge(
            $this->Components['Install Together'][$Key],
            $this->Components['RemoteMeta'][$Key]['Install Together']
        );
    }

    if (!empty($this->Components['RemoteMeta'][$Key]['Name'])) {
        $this->Components['ThisComponent']['Name'] =
            $this->Components['RemoteMeta'][$Key]['Name'];
        $this->prepareName($this->Components['ThisComponent'], $Key);
    }
    if (
        empty($this->Components['ThisComponent']['False Positive Risk']) &&
        !empty($this->Components['RemoteMeta'][$Key]['False Positive Risk'])
    ) {
        $this->Components['ThisComponent']['False Positive Risk'] =
            $this->Components['RemoteMeta'][$Key]['False Positive Risk'];
    }
    if (!empty($this->Components['RemoteMeta'][$Key]['Extended Description'])) {
        $this->Components['ThisComponent']['Extended Description'] =
            $this->Components['RemoteMeta'][$Key]['Extended Description'];
        $this->prepareExtendedDescription($this->Components['ThisComponent'], $Key);
    }
    if ($this->Components['ThisComponent']['StatClass'] === '' && isset($this->Components['ThisComponent']['Version'])) {
        if (!empty($this->Components['ThisComponent']['Latest']) && $this->CIDRAM['Operation']->singleCompare(
            $this->Components['ThisComponent']['Version'],
            '<' . $this->Components['ThisComponent']['Latest']
        )) {
            $this->Components['ThisComponent']['Outdated'] = true;
            $this->Components['ThisComponent']['RowClass'] = 'r';
            $this->Components['ThisComponent']['StatClass'] = 'txtRd';
            $this->Components['ThisComponent']['StatusOptions'] = $this->L10N->getString('response.Outdated');
            if (!empty($this->Components['ThisComponent']['Remote All Constraints Met'])) {
                $this->Components['Outdated'][] = $Key;
                if ($this->Components['ThisComponent']['Has Signatures'] === true) {
                    $this->Components['OutdatedSignatureFiles'][] = $Key;
                }
                $this->Components['ThisComponent']['Options'] .=
                    '<option value="update-component">' . $this->L10N->getString('field.Update') . '</option>';
            }
        } else {
            $this->Components['ThisComponent']['StatClass'] = 'txtGn';
            $this->Components['ThisComponent']['StatusOptions'] = $this->L10N->getString('response.Already up-to-date');
            if (isset($this->Components['RemoteMeta'][$Key]['Files'], $this->Components['ThisComponent']['Files']) && (
                serialize(array_keys($this->Components['RemoteMeta'][$Key]['Files'])) === serialize(array_keys($this->Components['ThisComponent']['Files']))
            )) {
                $this->Components['Repairable'][] = $Key;
                $this->Components['ThisComponent']['Options'] .= '<option value="repair-component">' . $this->L10N->getString('field.Repair') . '</option>';
            }
        }
    }
    if (!empty($this->Components['ThisComponent']['Files'])) {
        $Activable = $this->isActivable($this->Components['ThisComponent']);
        $this->Components['In Use'][$Key] = $this->isInUse($this->Components['ThisComponent']);
        if (preg_match(sprintf(
            '~^(?:theme/(?:%s|%s)|CIDRAM.*|Common Classes Package)$~i',
            preg_quote($this->Configuration['frontend']['theme']),
            preg_quote($this->Configuration['template_data']['theme'])
        ), $Key) || $this->Components['In Use'][$Key] !== 0) {
            if ($this->Components['In Use'][$Key] === -1) {
                $this->appendToString(
                    $this->Components['ThisComponent']['StatusOptions'],
                    '<hr />',
                    '<div class="txtOe">' . $this->L10N->getString('label.Component is partially active') . '</div>'
                );
            } else {
                $this->appendToString(
                    $this->Components['ThisComponent']['StatusOptions'],
                    '<hr />',
                    '<div class="txtGn">' . $this->L10N->getString('label.Component is active') . '</div>'
                );
            }
            if ($Activable) {
                $this->Components['ThisComponent']['Options'] .= '<option value="deactivate-component">' . $this->L10N->getString('field.Deactivate') . '</option>';
                if (!isset($this->Components['ThisComponent']['Uninstallable']) || $this->Components['ThisComponent']['Uninstallable'] !== false) {
                    $this->Components['ThisComponent']['Options'] .=
                        '<option value="deactivate-and-uninstall-component">' .
                        $this->L10N->getString('field.Deactivate') . ' + ' . $this->L10N->getString('field.Uninstall') .
                        '</option>';
                }
            }
        } else {
            if ($Activable) {
                $this->Components['ThisComponent']['Options'] .=
                    '<option value="activate-component">' . $this->L10N->getString('field.Activate') . '</option>';
            }
            if (!isset($this->Components['ThisComponent']['Uninstallable']) || $this->Components['ThisComponent']['Uninstallable'] !== false) {
                $this->Components['ThisComponent']['Options'] .=
                    '<option value="uninstall-component">' . $this->L10N->getString('field.Uninstall') . '</option>';
            }
            if (
                !empty($this->Components['ThisComponent']['Provisional']) ||
                ($this->Configuration['general']['lang_override'] && preg_match('~^l10n/~', $this->Components['ThisComponent']['Name']))
            ) {
                $this->appendToString(
                    $this->Components['ThisComponent']['StatusOptions'],
                    '<hr />',
                    '<div class="txtOe">' . $this->L10N->getString('label.Component is provisional') . '</div>'
                );
            } else {
                $this->appendToString(
                    $this->Components['ThisComponent']['StatusOptions'],
                    '<hr />',
                    '<div class="txtRd">' . $this->L10N->getString('label.Component is inactive') . '</div>'
                );
            }
        }
    }
    $this->Components['ThisComponent']['VersionSize'] = 0;
    $this->Components['ThisComponent']['Options'] .=
        '<option value="verify-component" selected>' . $this->L10N->getString('field.Verify') . '</option>';
    $this->Components['Verify'][] = $Key;
    if (isset($this->Components['ThisComponent']['Files'])) {
        foreach ($this->Components['ThisComponent']['Files'] as $ThisFile) {
            if (isset($ThisFile['Checksum']) && strlen($ThisFile['Checksum'])) {
                if (($Delimiter = strpos($ThisFile['Checksum'], ':')) !== false) {
                    $this->Components['ThisComponent']['VersionSize'] += (int)substr($ThisFile['Checksum'], $Delimiter + 1);
                }
            }
        }
    }
    if ($this->Components['ThisComponent']['VersionSize'] > 0) {
        $this->formatFileSize($this->Components['ThisComponent']['VersionSize']);
        $this->Components['ThisComponent']['VersionSize'] = sprintf(
            '<br />%s %s',
            $this->L10N->getString('field.size.Total size'),
            $this->Components['ThisComponent']['VersionSize']
        );
    } else {
        $this->Components['ThisComponent']['VersionSize'] = '';
    }
    $this->Components['ThisComponent']['LatestSize'] = 0;
    if (isset($this->Components['RemoteMeta'][$Key]['Files'])) {
        foreach ($this->Components['RemoteMeta'][$Key]['Files'] as $ThisFile) {
            if (isset($ThisFile['Checksum']) && strlen($ThisFile['Checksum'])) {
                if (($Delimiter = strpos($ThisFile['Checksum'], ':')) !== false) {
                    $this->Components['ThisComponent']['LatestSize'] += (int)substr($ThisFile['Checksum'], $Delimiter + 1);
                }
            }
        }
    }
    if ($this->Components['ThisComponent']['LatestSize'] > 0) {
        $this->formatFileSize($this->Components['ThisComponent']['LatestSize']);
        $this->Components['ThisComponent']['LatestSize'] = sprintf(
            '<br />%s %s',
            $this->L10N->getString('field.size.Total size'),
            $this->Components['ThisComponent']['LatestSize']
        );
    } else {
        $this->Components['ThisComponent']['LatestSize'] = '';
    }
    if (!empty($this->Components['ThisComponent']['Options'])) {
        $this->appendToString(
            $this->Components['ThisComponent']['StatusOptions'],
            '<hr />',
            '<select name="do" class="auto">' . $this->Components['ThisComponent']['Options'] .
            '</select><input type="submit" value="' . $this->L10N->getString('field.OK') . '" class="auto" />'
        );
        $this->Components['ThisComponent']['Options'] = '';
    }

    /** Append changelog. */
    $this->Components['ThisComponent']['ChangelogFormatted'] = empty(
        $this->Components['ThisComponent']['Changelog']
    ) ? '' : '<br /><a href="' . $this->Components['ThisComponent']['Changelog'] . '" rel="noopener external">Changelog</a>';

    /** Append filename (downstream). */
    $this->Components['ThisComponent']['Filename'] = (
        empty($this->Components['ThisComponent']['Files']) ||
        count($this->Components['ThisComponent']['Files']) !== 1
    ) ? '' : '<br />' . $this->L10N->getString('field.Filename') . ' ' . key($this->Components['ThisComponent']['Files']);

    /** Append filename (upstream). */
    $this->Components['ThisComponent']['RemoteFilename'] = (
        empty($this->Components['RemoteMeta'][$Key]['Files']) ||
        count($this->Components['RemoteMeta'][$Key]['Files']) !== 1
    ) ? '' : '<br />' . $this->L10N->getString('field.Filename') . ' ' . key($this->Components['RemoteMeta'][$Key]['Files']);

    /** Finalise entry. */
    if (
        !($this->FE['hide-non-outdated'] && empty($this->Components['ThisComponent']['Outdated'])) &&
        !($this->FE['hide-unused'] && empty($this->Components['ThisComponent']['Files']))
    ) {
        if (empty($this->Components['ThisComponent']['RowClass'])) {
            $this->Components['ThisComponent']['RowClass'] = 'h1';
        }
        if (!empty($this->FE['sort-by-name']) && !empty($this->Components['ThisComponent']['Name'])) {
            $this->Components['ThisComponent']['SortKey'] = $this->Components['ThisComponent']['Name'];
        } else {
            $this->Components['ThisComponent']['SortKey'] = $Key;
        }
        $this->FE['Indexes'][$this->Components['ThisComponent']['SortKey']] = '';
        if (isset($PreviousIndex)) {
            if (substr($PreviousIndex, 0, 6) !== substr($this->Components['ThisComponent']['ID'], 0, 6)) {
                $this->FE['Indexes'][$this->Components['ThisComponent']['SortKey']] .= '<br />';
            }
        }
        $PreviousIndex = $this->Components['ThisComponent']['ID'];
        $this->FE['Indexes'][$this->Components['ThisComponent']['SortKey']] .= sprintf(
            "<a href=\"#%s\">%s</a><br />\n      ",
            $this->Components['ThisComponent']['ID'],
            $this->Components['ThisComponent']['Name']
        );
        $this->Components['Out'][$this->Components['ThisComponent']['SortKey']] = $this->parseVars(
            $this->arrayFlatten($this->Components['ThisComponent']) + $this->arrayFlatten($this->FE),
            $this->FE['UpdatesRow']
        );
    }
}

/** Update request via Cronable. */
if (!empty($this->Alternate) && (
    (
        $this->FE['CronMode'] === 'Signatures' &&
        !empty($this->Components['OutdatedSignatureFiles']) &&
        ($BuildUse = 'OutdatedSignatureFiles')
    ) || (
        $this->FE['CronMode'] !== '' &&
        $this->FE['CronMode'] !== 'Signatures' &&
        !empty($this->Components['Outdated']) &&
        ($BuildUse = 'Outdated')
    )
)) {
    /** Fetch dependency installation triggers. */
    $this->Components['Build'] = $this->Components[$BuildUse];
    foreach ($this->Components[$BuildUse] as $Key) {
        if (isset($this->Components['Install Together'][$Key])) {
            $this->Components['Build'] = array_merge(
                $this->Components['Build'],
                $this->Components['Install Together'][$Key]
            );
        }
    }
    $this->Components[$BuildUse] = array_unique($this->Components['Build']);

    /** Trigger updates handler. */
    $this->updatesHandler('update-component', $this->Components[$BuildUse]);

    /** Trigger signatures update log event. */
    if (!empty($this->CIDRAM['SignaturesUpdateEvent'])) {
        $this->CIDRAM['SignaturesUpdateEvent'] = sprintf(
            $this->L10N->getString('response.Signature files have been updated (%s)'),
            $this->timeFormat(
                $this->CIDRAM['SignaturesUpdateEvent'],
                $this->Configuration['general']['time_format']
            )
        );
        $this->Events->fireEvent('writeToSignaturesUpdateEventLog', $this->CIDRAM['SignaturesUpdateEvent']);
    }

    /** Check again, since the information might've been updated. */
    $this->checkVersions($this->Components['Meta'], $this->Components['Installed Versions']);
}

/** Prepare newly found component metadata and options for display. */
foreach ($this->Components['RemoteMeta'] as $Key => &$this->Components['ThisComponent']) {
    if (
        isset($this->Components['Meta'][$Key]) ||
        empty($this->Components['ThisComponent']['Version']) ||
        empty($this->Components['ThisComponent']['Files'])
    ) {
        continue;
    }

    /** Fall back to component key if the component's name isn't defined. */
    if (empty($this->Components['ThisComponent']['Name']) && $this->L10N->getString('Name.' . $Key) === '') {
        $this->Components['ThisComponent']['Name'] = $Key;
    }

    /** Determine whether all dependency constraints have been met. */
    $this->checkConstraints($this->Components['ThisComponent'], true);
    $this->Components['ThisComponent']['Remote Dependency Status'] = $this->Components['ThisComponent']['Dependency Status'];
    $this->Components['ThisComponent']['Dependency Status'] = '';
    $this->Components['ThisComponent']['Remote All Constraints Met'] = $this->Components['ThisComponent']['All Constraints Met'];
    if (isset($this->Components['ThisComponent']['Install Together'])) {
        if (!isset($this->Components['Install Together'][$Key])) {
            $this->Components['Install Together'][$Key] = [];
        }
        $this->Components['Install Together'][$Key] = array_merge(
            $this->Components['Install Together'][$Key],
            $this->Components['ThisComponent']['Install Together']
        );
    }

    $this->prepareName($this->Components['ThisComponent'], $Key);
    $this->prepareExtendedDescription($this->Components['ThisComponent'], $Key);
    $this->Components['ThisComponent']['ID'] = $Key;
    $this->Components['ThisComponent']['Latest'] = $this->Components['ThisComponent']['Version'];
    $this->Components['ThisComponent']['Version'] = $this->L10N->getString('response.Component not installed');
    $this->Components['ThisComponent']['StatClass'] = 'txtRd';
    $this->Components['ThisComponent']['RowClass'] = 'h2';
    $this->Components['ThisComponent']['VersionSize'] = '';
    $this->Components['ThisComponent']['LatestSize'] = 0;
    if (isset($this->Components['ThisComponent']['Files'])) {
        foreach ($this->Components['ThisComponent']['Files'] as $ThisFile) {
            if (isset($ThisFile['Checksum']) && strlen($ThisFile['Checksum'])) {
                if (($Delimiter = strpos($ThisFile['Checksum'], ':')) !== false) {
                    $this->Components['ThisComponent']['LatestSize'] += (int)substr($ThisFile['Checksum'], $Delimiter + 1);
                }
            }
        }
    }
    if ($this->Components['ThisComponent']['LatestSize'] > 0) {
        $this->formatFileSize($this->Components['ThisComponent']['LatestSize']);
        $this->Components['ThisComponent']['LatestSize'] = sprintf(
            '<br />%s %s',
            $this->L10N->getString('field.size.Total size'),
            $this->Components['ThisComponent']['LatestSize']
        );
    } else {
        $this->Components['ThisComponent']['LatestSize'] = '';
    }
    $this->Components['ThisComponent']['StatusOptions'] = $this->L10N->getString('response.Component not installed');
    if (!empty($this->Components['ThisComponent']['Remote All Constraints Met'])) {
        $this->Components['ThisComponent']['StatusOptions'] .= '<br /><select name="do" class="auto">' .
            '<option value="update-component">' . $this->L10N->getString('field.Install') . '</option>';
        if ($this->isActivable($this->Components['ThisComponent'])) {
            $this->Components['ThisComponent']['StatusOptions'] .=
                '<option value="update-and-activate-component">' .
                $this->L10N->getString('field.Install') . ' + ' . $this->L10N->getString('field.Activate') .
                '</option>';
        }
        $this->Components['ThisComponent']['StatusOptions'] .= '</select><input type="submit" value="' . $this->L10N->getString('field.OK') . '" class="auto" />';
    }

    /** Append changelog. */
    $this->Components['ThisComponent']['ChangelogFormatted'] = empty(
        $this->Components['ThisComponent']['Changelog']
    ) ? '' : '<br /><a href="' . $this->Components['ThisComponent']['Changelog'] . '" rel="noopener external">Changelog</a>';

    /** Append filename (downstream). */
    $this->Components['ThisComponent']['Filename'] = '';

    /** Append filename (upstream). */
    $this->Components['ThisComponent']['RemoteFilename'] = (
        empty($this->Components['ThisComponent']['Files']) ||
        count($this->Components['ThisComponent']['Files']) !== 1
    ) ? '' : '<br />' . $this->L10N->getString('field.Filename') . ' ' . key($this->Components['ThisComponent']['Files']);

    /** Finalise entry. */
    if (!$this->FE['hide-unused']) {
        if (!empty($this->FE['sort-by-name']) && !empty($this->Components['ThisComponent']['Name'])) {
            $this->Components['ThisComponent']['SortKey'] = $this->Components['ThisComponent']['Name'];
        } else {
            $this->Components['ThisComponent']['SortKey'] = $Key;
        }
        $this->FE['Indexes'][$this->Components['ThisComponent']['SortKey']] = '';
        if (isset($PreviousIndex)) {
            if (substr($PreviousIndex, 0, 6) !== substr($this->Components['ThisComponent']['ID'], 0, 6)) {
                $this->FE['Indexes'][$this->Components['ThisComponent']['SortKey']] .= '<br />';
            }
        }
        $PreviousIndex = $this->Components['ThisComponent']['ID'];
        $this->FE['Indexes'][$this->Components['ThisComponent']['SortKey']] .= sprintf(
            "<a href=\"#%s\">%s</a><br />\n      ",
            $this->Components['ThisComponent']['ID'],
            $this->Components['ThisComponent']['Name']
        );
        $this->Components['Out'][$this->Components['ThisComponent']['SortKey']] = $this->parseVars(
            $this->arrayFlatten($this->Components['ThisComponent']) + $this->arrayFlatten($this->FE),
            $this->FE['UpdatesRow']
        );
    }
}

/** Finalise output and unset working data. */
$this->FE['Indexes'] = $this->sortComponents($this->FE['Indexes']);
$this->FE['Components'] = $this->sortComponents($this->Components['Out']);

$this->Components['CountOutdated'] = count($this->Components['Outdated']);
$this->Components['CountOutdatedSignatureFiles'] = count($this->Components['OutdatedSignatureFiles']);
$this->Components['CountVerify'] = count($this->Components['Verify']);
$this->Components['CountRepairable'] = count($this->Components['Repairable']);

/** Preparing the update all, verify all, repair all buttons. */
$this->FE['UpdateAll'] = (
    $this->Components['CountOutdated'] ||
    $this->Components['CountOutdatedSignatureFiles'] ||
    $this->Components['CountVerify'] ||
    $this->Components['CountRepairable']
) ? '<hr />' : '';

/** Instructions to update all signature files (but not necessarily everything). */
if ($this->Components['CountOutdatedSignatureFiles']) {
    $this->FE['UpdateAll'] .= sprintf($this->FE['CFBoilerplate'], $this->FE['UpdatesFormTarget'], 'update-component');
    foreach ($this->Components['OutdatedSignatureFiles'] as $this->Components['ThisOutdated']) {
        $this->FE['UpdateAll'] .= '<input name="ID[]" type="hidden" value="' . $this->Components['ThisOutdated'] . '" />';
    }
    $this->FE['UpdateAll'] .= '<input type="submit" value="' . $this->L10N->getString('field.Update signature files') . '" class="auto" /></form>';
}

/** Instructions to update everything at once. */
if ($this->Components['CountOutdated'] && $this->Components['CountOutdated'] !== $this->Components['CountOutdatedSignatureFiles']) {
    $this->FE['UpdateAll'] .= sprintf($this->FE['CFBoilerplate'], $this->FE['UpdatesFormTarget'], 'update-component');
    foreach ($this->Components['Outdated'] as $this->Components['ThisOutdated']) {
        $this->FE['UpdateAll'] .= '<input name="ID[]" type="hidden" value="' . $this->Components['ThisOutdated'] . '" />';
    }
    $this->FE['UpdateAll'] .= '<input type="submit" value="' . $this->L10N->getString('field.Update all') . '" class="auto" /></form>';
}

/** Instructions to repair everything at once. */
if ($this->Components['CountRepairable']) {
    $this->FE['UpdateAll'] .= sprintf($this->FE['CFBoilerplate'], $this->FE['UpdatesFormTarget'], 'repair-component');
    foreach ($this->Components['Repairable'] as $this->Components['ThisRepairable']) {
        $this->FE['UpdateAll'] .= '<input name="ID[]" type="hidden" value="' . $this->Components['ThisRepairable'] . '" />';
    }
    $this->FE['UpdateAll'] .= '<input type="submit" value="' . $this->L10N->getString('field.Repair all') . '" class="auto" /></form>';
}

/** Instructions to verify everything at once. */
if ($this->Components['CountVerify']) {
    $this->FE['UpdateAll'] .= sprintf($this->FE['CFBoilerplate'], $this->FE['UpdatesFormTarget'], 'verify-component');
    foreach ($this->Components['Verify'] as $this->Components['ThisVerify']) {
        $this->FE['UpdateAll'] .= '<input name="ID[]" type="hidden" value="' . $this->Components['ThisVerify'] . '" />';
    }
    $this->FE['UpdateAll'] .= '<input type="submit" value="' . $this->L10N->getString('field.Verify all') . '" class="auto" /></form>';
}

$this->FE['Macros'] = '';

/** Prepare macros for display. */
foreach ($this->Components['Macros'] as $Key => &$MacroData) {
    if (!isset($MacroData['On Execute'])) {
        continue;
    }
    $MacroData['Key'] = $Key;
    $MacroData['Name'] = $this->L10N->getString('macro.Name.' . $Key) ?: $Key;
    $MacroData['Description'] = $this->L10N->getString('macro.Description.' . $Key);
    $MacroData['Options'] = '<input type="submit" value="' . $this->L10N->getString('field.Execute') . '" class="auto" />';
    $this->FE['Macros'] .= $this->parseVars($this->arrayFlatten($MacroData), $this->FE['MacrosRow']);
}
unset($MacrosData, $Key);

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_updates.html')), true) . $this->CIDRAM['MenuToggle'];

/** Process dependency installation triggers. */
foreach ($this->Components['Install Together'] as $Key => $this->Components['ID']) {
    $this->Components['Build'] = '';
    $this->Components['ID'] = array_unique($this->Components['ID']);
    foreach ($this->Components['ID'] as $this->Components['ThisID']) {
        $this->Components['Build'] .= '<input name="InstallTogether[]" type="hidden" value="' . $this->Components['ThisID'] . '" />';
    }
    $this->FE['FE_Content'] = str_replace(
        '<input name="ID[]" type="hidden" value="' . $Key . '" />',
        $this->Components['Build'] . '<input name="ID[]" type="hidden" value="' . $Key . '" />',
        $this->FE['FE_Content']
    );
}

/** Send output. */
if ($this->FE['CronMode'] === '') {
    /** Normal page output. */
    echo $this->sendOutput();
} elseif ($this->FE['CronType'] === 'localUpdate') {
    /** Returned state message for Cronable (updating locally). */
    $GLOBALS['Results'] = ['state_msg' => str_ireplace(
        ['<code>', '</code>', '<br />', '<hr />'],
        ['[', ']', "\n", "\n---\n"],
        $this->FE['state_msg']
    )];
} elseif (!empty($this->FE['state_msg'])) {
    /** Returned state message for Cronable. */
    echo json_encode(['state_msg' => str_ireplace(
        ['<code>', '</code>', '<br />', '<hr />'],
        ['[', ']', "\n", "\n---\n"],
        $this->FE['state_msg']
    )]);
} elseif (!empty($_POST['do']) && $_POST['do'] === 'get-list' && (
    $this->Components['CountOutdated'] > 0 ||
    $this->Components['CountOutdatedSignatureFiles'] > 0
)) {
    /** Returned list of outdated components for Cronable. */
    echo json_encode([
        'state_msg' => str_ireplace(
            ['<code>', '</code>', '<br />', '<hr />'],
            ['[', ']', "\n", "\n---\n"],
            $this->FE['state_msg']
        ),
        'outdated' => $this->Components['CountOutdated'] > 0 ? $this->Components['Outdated'] : [],
        'outdated_signature_files' => $this->Components['CountOutdatedSignatureFiles'] > 0 ? $this->Components['OutdatedSignatureFiles'] : []
    ]);
}

/** Cleanup. */
unset($this->FE['CFBoilerplate'], $this->CIDRAM['Operation']);
