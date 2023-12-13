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
 * This file: The file manager page (last modified: 2023.12.13).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'file-manager' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.File Manager'), $this->L10N->getString('tip.File Manager'));

/** Load doughnut template file upon request. */
if (empty($this->CIDRAM['QueryVars']['show'])) {
    $this->FE['ChartJSPath'] = '';
    $DoughnutFile = '';
} else {
    if (file_exists($this->AssetsPath . 'frontend/_chartjs.html')) {
        $DoughnutFile = $this->readFile($this->AssetsPath . 'frontend/_chartjs.html');
    } else {
        $DoughnutFile = '<tr><td class="h4f" colspan="2"><div class="s">{DoughnutHTML}</div></td></tr>';
    }
    if (file_exists($this->AssetsPath . 'frontend/chart.min.js')) {
        $this->FE['ChartJSPath'] = '?cidram-asset=chart.min.js';
    } else {
        $this->FE['ChartJSPath'] = '';
    }
}

/** Set vault path for doughnut display. */
$this->FE['VaultPath'] = str_replace('\\', '/', $this->Vault) . '*';

/** Prepare components metadata working array. */
$this->Components = ['Files' => [], 'Components' => [], 'ComponentFiles' => [], 'Names' => []];

/** Show/hide doughnuts link and etc. */
if (!$DoughnutFile) {
    $this->FE['FMgrFormTarget'] = 'cidram-page=file-manager';
    $this->FE['ShowHideLink'] = '<a href="?cidram-page=file-manager&show=true">' . $this->L10N->getString('label.Show') . '</a>';
} else {
    $this->FE['FMgrFormTarget'] = 'cidram-page=file-manager&show=true';
    $this->FE['ShowHideLink'] = '<a href="?cidram-page=file-manager">' . $this->L10N->getString('label.Hide') . '</a>';
}

/** Fetch components metadata. */
$this->readInstalledMetadata($this->Components['Components']);

/** Identifying file component correlations. */
foreach ($this->Components['Components'] as $ComponentName => &$ComponentData) {
    if (isset($ComponentData['Files']) && is_array($ComponentData['Files'])) {
        foreach ($ComponentData['Files'] as $ThisFile => $FileData) {
            $ThisFile = $this->canonical($ThisFile);
            $this->Components['Files'][$ThisFile] = $ComponentName;
        }
    }
    $this->prepareName($ComponentData, $ComponentName);
    if (isset($ComponentData['Name']) && strlen($ComponentData['Name'])) {
        $this->Components['Names'][$ComponentName] = $ComponentData['Name'];
    }
    $ComponentData = 0;
}

/** Upload a new file. */
if (isset($_POST['do'], $_FILES['upload-file']['name']) && $_POST['do'] === 'upload-file') {
    /** Check whether safe. */
    $SafeToContinue = (
        basename($_FILES['upload-file']['name']) === $_FILES['upload-file']['name'] &&
        $this->pathSecurityCheck($_FILES['upload-file']['name']) &&
        isset($_FILES['upload-file']['tmp_name'], $_FILES['upload-file']['error']) &&
        $_FILES['upload-file']['error'] === UPLOAD_ERR_OK &&
        is_uploaded_file($_FILES['upload-file']['tmp_name']) &&
        !is_link($this->Vault . $_FILES['upload-file']['name'])
    );

    /** If the filename already exists, delete the old file before moving the new file. */
    if ($SafeToContinue && is_readable($this->Vault . $_FILES['upload-file']['name'])) {
        if (is_dir($this->Vault . $_FILES['upload-file']['name'])) {
            if ($this->isDirEmpty($this->Vault . $_FILES['upload-file']['name'])) {
                rmdir($this->Vault . $_FILES['upload-file']['name']);
            } else {
                $SafeToContinue = false;
            }
        } else {
            unlink($this->Vault . $_FILES['upload-file']['name']);
        }
    }

    /** Move the newly uploaded file to the designated location. */
    if ($SafeToContinue) {
        if (rename($_FILES['upload-file']['tmp_name'], $this->Vault . $_FILES['upload-file']['name'])) {
            $this->FE['state_msg'] = $this->L10N->getString('response.File successfully uploaded');
            header('HTTP/1.0 201 Created');
            header('HTTP/1.1 201 Created');
            header('Status: 201 Created');
        } else {
            $this->FE['state_msg'] = $this->L10N->getString('response.Failed to upload');
        }
    } else {
        $this->FE['state_msg'] = $this->L10N->getString('response.Failed to upload');
    }
}

/** A form was submitted. */
elseif (
    isset($_POST['filename'], $_POST['do']) &&
    is_readable($this->Vault . $_POST['filename']) &&
    $this->pathSecurityCheck($_POST['filename'])
) {
    /** Delete a file. */
    if ($_POST['do'] === 'delete-file') {
        if (is_dir($this->Vault . $_POST['filename'])) {
            if (
                $this->isDirEmpty($this->Vault . $_POST['filename']) &&
                rmdir($this->Vault . $_POST['filename'])
            ) {
                $this->FE['state_msg'] = $this->L10N->getString('response.Directory successfully deleted');
            } else {
                $this->FE['state_msg'] = $this->L10N->getString('response.Failed to delete');
            }
        } elseif (unlink($this->Vault . $_POST['filename'])) {
            /** Remove empty directories. */
            $this->deleteDirectory($_POST['filename']);

            $this->FE['state_msg'] = $this->L10N->getString('response.File successfully deleted');
        } else {
            $this->FE['state_msg'] = $this->L10N->getString('response.Failed to delete');
        }
    }

    /** Rename a file. */
    if ($_POST['do'] === 'rename-file' && isset($_POST['filename'])) {
        if (isset($_POST['filename_new'])) {
            /** Check whether safe. */
            $SafeToContinue = (
                $this->pathSecurityCheck($_POST['filename']) &&
                $this->pathSecurityCheck($_POST['filename_new']) &&
                $_POST['filename'] !== $_POST['filename_new']
            );

            /** If the destination already exists, delete it before renaming the new file. */
            if (
                $SafeToContinue &&
                file_exists($this->Vault . $_POST['filename_new']) &&
                is_readable($this->Vault . $_POST['filename_new'])
            ) {
                if (is_dir($this->Vault . $_POST['filename_new'])) {
                    if (
                        !$this->isDirEmpty($this->Vault . $_POST['filename_new']) ||
                        !rmdir($this->Vault . $_POST['filename_new'])
                    ) {
                        $SafeToContinue = false;
                    }
                } elseif (!unlink($this->Vault . $_POST['filename_new'])) {
                    $SafeToContinue = false;
                }
            }

            /** Rename the file. */
            if ($SafeToContinue) {
                /** Add parent directories. */
                $this->buildPath($this->Vault . $_POST['filename_new']);

                if (rename($this->Vault . $_POST['filename'], $this->Vault . $_POST['filename_new'])) {
                    /** Remove empty directories. */
                    $this->deleteDirectory($_POST['filename']);

                    /** Update state message. */
                    $this->FE['state_msg'] = $this->L10N->getString(
                        is_dir($this->Vault . $_POST['filename_new']) ? 'response.Directory successfully renamed' : 'response.File successfully renamed'
                    );
                } else {
                    $this->FE['state_msg'] = $this->L10N->getString('response.Failed to rename');
                }
            } else {
                $this->FE['state_msg'] = $this->L10N->getString('response.Failed to rename');
            }
        } else {
            $this->FE['FE_Title'] .= ' – ' . $this->L10N->getString('field.Rename') . ' – ' . $_POST['filename'];
            $this->FE['filename'] = $_POST['filename'];

            /** Parse output. */
            $this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_files_rename.html')), true);

            /** Send output. */
            echo $this->sendOutput();
            die;
        }
    }

    /** Edit a file. */
    if ($_POST['do'] === 'edit-file') {
        if (isset($_POST['content'])) {
            $_POST['content'] = str_replace("\r", '', $_POST['content']);
            $this->CIDRAM['OldData'] = $this->readFile($this->Vault . $_POST['filename']);
            if (strpos($this->CIDRAM['OldData'], "\r\n") !== false && strpos($this->CIDRAM['OldData'], "\n\n") === false) {
                $_POST['content'] = str_replace("\n", "\r\n", $_POST['content']);
            }

            $Handle = fopen($this->Vault . $_POST['filename'], 'wb');
            fwrite($Handle, $_POST['content']);
            fclose($Handle);

            $this->FE['state_msg'] = $this->L10N->getString('response.File successfully modified');
        } else {
            $this->FE['FE_Title'] .= ' – ' . $_POST['filename'];
            $this->FE['filename'] = $_POST['filename'];
            $this->FE['content'] = htmlentities($this->readFile($this->Vault . $_POST['filename']));

            /** Component update file overwrite warning. */
            if (isset($this->Components['Files'][$_POST['filename']])) {
                $this->FE['state_msg'] = sprintf(
                    $this->L10N->getString('warning.Likely to be overwritten'),
                    $this->Components['Files'][$_POST['filename']]
                );
            }

            /** PHP file warning. */
            if (preg_match('~\.php$~i', $_POST['filename'])) {
                $this->FE['JS'] .= "\nfunction wfp(d){};";
                if ($this->FE['state_msg'] !== '') {
                    $this->FE['state_msg'] .= '<br />';
                }
                $this->FE['state_msg'] .= $this->L10N->getString('warning.Editing PHP files');
            } else {
                $this->FE['JS'] .= "\nfunction wfp(d){d.includes('<?php')?showid('wfps'):hideid('wfps')};";
                $this->FE['state_msg'] .= $this->FE['state_msg'] !== '' ? '<span id="wfps"><br />' : '<span id="wfps">';
                $this->FE['state_msg'] .= $this->L10N->getString('warning.Editing PHP files') . '</span>';
            }

            /** Parse output. */
            $this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_files_edit.html')), true);

            /** Send output. */
            echo $this->sendOutput();
            die;
        }
    }

    /** Download a file. */
    if ($_POST['do'] === 'download-file') {
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: Binary');
        header('Content-disposition: attachment; filename="' . basename($_POST['filename']) . '"');
        echo $this->readFile($this->Vault . $_POST['filename']);
        die;
    }
}

/** Template for file rows. */
$this->FE['FilesRow'] = $this->readFile($this->getAssetPath('_files_row.html'));

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_files.html')), true);

/** Initialise files data variable. */
$this->FE['FilesData'] = '';

/** Total size. */
$this->FE['TotalSize'] = 0;

/** Fetch files data. */
$this->CIDRAM['FilesArray'] = $this->fileManagerRecursiveList($this->Vault);

if (!$DoughnutFile) {
    $this->FE['Doughnut'] = '';
} else {
    /** Sort doughnut values. */
    arsort($this->Components['Components']);

    /** Initialise doughnut values. */
    $this->FE['DoughnutValues'] = [];

    /** Initialise doughnut labels. */
    $this->FE['DoughnutLabels'] = [];

    /** Initialise doughnut colours. */
    $this->FE['DoughnutColours'] = [];

    /** Initialise doughnut legend. */
    $this->FE['DoughnutHTML'] = $this->L10N->getString('tip.Click the component name for more details') . '<br /><ul class="pieul">';

    /** Building doughnut values. */
    foreach ($this->Components['Components'] as $ComponentName => $ComponentData) {
        $ComponentSize = $ComponentData;
        $this->formatFileSize($ComponentSize);
        $Listed = '';
        if (!empty($this->Components['ComponentFiles'][$ComponentName])) {
            $ThisComponentFiles = &$this->Components['ComponentFiles'][$ComponentName];
            arsort($ThisComponentFiles);
            $Listed .= '<ul class="comSub">';
            foreach ($ThisComponentFiles as $ThisFile => $ThisFileSize) {
                $this->formatFileSize($ThisFileSize);
                $Listed .= sprintf(
                    '<li><span class="txtBl" style="font-size:0.9em">%s – %s</span></li>',
                    $ThisFile,
                    $ThisFileSize
                );
            }
            $Listed .= '</ul>';
        }
        $ComponentName .= ' – ' . $ComponentSize;
        $this->FE['DoughnutValues'][] = $ComponentData;
        $this->FE['DoughnutLabels'][] = $ComponentName;
        if (strlen($this->FE['ChartJSPath'])) {
            $ThisColour = $this->rgb($ComponentName);
            $RGB = implode(',', $ThisColour['Values']);
            $this->FE['DoughnutColours'][] = '#' . $ThisColour['Hash'];
            $this->FE['DoughnutHTML'] .= sprintf(
                '<li style="background:linear-gradient(90deg,rgba(%1$s,%5$s),rgba(%1$s,%6$s));color:#%2$s"><span class="comCat"><span class="txtBl">%3$s</span></span>%4$s</li>',
                $RGB,
                $ThisColour['Hash'],
                $ComponentName,
                $Listed,
                $this->FE['FE_Align'] === 'left' ? '.3' : '0',
                $this->FE['FE_Align'] === 'left' ? '0' : '.3'
            ) . "\n";
        } else {
            $this->FE['DoughnutHTML'] .= sprintf('<li><span class="comCat">%1$s</span>%2$s</li>', $ComponentName, $Listed) . "\n";
        }
    }

    /** Close doughnut legend and append necessary JavaScript for doughnut menu toggle. */
    $this->FE['DoughnutHTML'] .= '</ul>' . $this->CIDRAM['MenuToggle'];

    /** Finalise doughnut values. */
    $this->FE['DoughnutValues'] = '[' . implode(', ', $this->FE['DoughnutValues']) . ']';

    /** Finalise doughnut labels. */
    $this->FE['DoughnutLabels'] = '["' . implode('", "', $this->FE['DoughnutLabels']) . '"]';

    /** Finalise doughnut colours. */
    $this->FE['DoughnutColours'] = '["' . implode('", "', $this->FE['DoughnutColours']) . '"]';

    /** Finalise doughnut. */
    $this->FE['Doughnut'] = $this->parseVars($this->FE, $DoughnutFile, true);
}

/** Process files data. */
array_walk($this->CIDRAM['FilesArray'], function ($ThisFile): void {
    $Base = '<option value="%s"%s>%s</option>';
    $ThisFile['ThisOptions'] = '';
    if (!$ThisFile['Directory'] || $this->isDirEmpty($this->Vault . $ThisFile['Filename'])) {
        $ThisFile['ThisOptions'] .= sprintf($Base, 'delete-file', ' class="txtRd"', $this->L10N->getString('field.Delete'));
        $ThisFile['ThisOptions'] .= sprintf($Base, 'rename-file', $ThisFile['Directory'] && !$ThisFile['CanEdit'] ? ' selected' : '', $this->L10N->getString('field.Rename'));
    }
    if ($ThisFile['CanEdit']) {
        $ThisFile['ThisOptions'] .= sprintf($Base, 'edit-file', ' selected', $this->L10N->getString('field.Edit'));
    }
    if (!$ThisFile['Directory']) {
        $ThisFile['ThisOptions'] .= sprintf($Base, 'download-file', $ThisFile['CanEdit'] ? '' : ' selected', $this->L10N->getString('field.Download'));
    }
    if ($ThisFile['ThisOptions']) {
        $ThisFile['ThisOptions'] =
            '<select name="do">' . $ThisFile['ThisOptions'] . '</select>' .
            '<input type="submit" value="' . $this->L10N->getString('field.OK') . '" class="auto" />';
    }
    $this->FE['FilesData'] .= $this->parseVars($this->FE + $ThisFile, $this->FE['FilesRow'], true);
});

/** Total size. */
$this->formatFileSize($this->FE['TotalSize']);

/** Disk free space. */
$this->FE['FreeSpace'] = disk_free_space(__DIR__);

/** Disk total space. */
$this->FE['TotalSpace'] = disk_total_space(__DIR__);

/** Disk total usage. */
$this->FE['TotalUsage'] = $this->FE['TotalSpace'] - $this->FE['FreeSpace'];

$this->formatFileSize($this->FE['FreeSpace']);
$this->formatFileSize($this->FE['TotalSpace']);
$this->formatFileSize($this->FE['TotalUsage']);

/** Send output. */
echo $this->sendOutput();
