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
 * This file: The file manager page (last modified: 2026.06.22).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'file-manager' || $this->FE['Permissions'] !== 1) {
    die;
}

$FMData = [];

/** Desabotage POST data. */
foreach (['do_action', 'filename', 'filename_new', 'content'] as $Key) {
    if (isset($_POST[$Key])) {
        $FMData[$Key] = $this->desabotage($_POST[$Key]);
    }
}
unset($Key);

/** The base path to use. */
if (isset($this->CIDRAM['QueryVars']['basepath']) && $this->CIDRAM['QueryVars']['basepath'] !== '') {
    $this->FE['basepath'] = $this->canonical($this->CIDRAM['QueryVars']['basepath']);
    if (\substr($this->FE['basepath'], -1) !== '/') {
        $this->FE['basepath'] .= '/';
    }
} else {
    $this->FE['basepath'] = $this->Vault;
}
$this->FE['basepathActionAttach'] = $this->FE['basepath'] === $this->Vault ? '' : '&basepath=' . $this->FE['basepath'];

/** Self-enforced memory limit. */
$this->FE['MemoryLimit'] = $this->readBytes(\ini_get('memory_limit')) - \memory_get_peak_usage(true);

/** Prepare data for display. */
if (!$this->FE['ASYNC']) {
    /** Page initial prepwork. */
    $this->initialPrepwork($this->L10N->getString('link.File Manager'), $this->L10N->getString('tip.File Manager'));

    /** Scripting for the file manager. */
    $this->FE['JS'] .= $this->readFile($this->getAssetPath('fm.js'));

    /** Prepare components metadata working array. */
    $this->Components = ['Files' => [], 'Components' => [], 'Names' => []];

    /** Fetch components metadata. */
    $this->readInstalledMetadata($this->Components['Components']);

    /** Identifying components. */
    foreach ($this->Components['Components'] as $ComponentName => $ComponentData) {
        $this->prepareName($ComponentData, $ComponentName);
        if (isset($ComponentData['Files']) && \is_array($ComponentData['Files'])) {
            foreach ($ComponentData['Files'] as $ThisFile => $FileData) {
                $ThisFile = $this->canonical($ThisFile);
                $this->Components['Files'][$ThisFile] = $ComponentData['Name'] ?: $ComponentName;
            }
        }
    }
    unset($FileData, $ComponentData, $ComponentName);

    if (isset($FMData['do_action'], $_FILES['upload-file']['name']) && $FMData['do_action'] === 'upload-file') {
        /** Check whether safe. */
        $SafeToContinue = (
            \basename($_FILES['upload-file']['name']) === $_FILES['upload-file']['name'] &&
            $this->pathSecurityCheck($_FILES['upload-file']['name']) &&
            isset($_FILES['upload-file']['tmp_name'], $_FILES['upload-file']['error']) &&
            $_FILES['upload-file']['error'] === \UPLOAD_ERR_OK &&
            \is_uploaded_file($_FILES['upload-file']['tmp_name']) &&
            !\is_link($this->FE['basepath'] . $_FILES['upload-file']['name'])
        );

        /** If the filename already exists, delete the old file before moving the new file. */
        if ($SafeToContinue && \is_readable($this->FE['basepath'] . $_FILES['upload-file']['name'])) {
            if (\is_dir($this->FE['basepath'] . $_FILES['upload-file']['name'])) {
                if ($this->isDirEmpty($this->FE['basepath'] . $_FILES['upload-file']['name'])) {
                    \rmdir($this->FE['basepath'] . $_FILES['upload-file']['name']);
                } else {
                    $SafeToContinue = false;
                }
            } else {
                \unlink($this->FE['basepath'] . $_FILES['upload-file']['name']);
            }
        }

        /** Move the newly uploaded file to the designated location. */
        if ($SafeToContinue) {
            if (rename($_FILES['upload-file']['tmp_name'], $this->FE['basepath'] . $_FILES['upload-file']['name'])) {
                $this->FE['state_msg'] = $this->L10N->getString('response.File successfully uploaded');
                \header('HTTP/1.0 201 Created');
                \header('HTTP/1.1 201 Created');
                \header('Status: 201 Created');
            } else {
                $this->FE['state_msg'] = $this->L10N->getString('response.Failed to upload');
            }
        } else {
            $this->FE['state_msg'] = $this->L10N->getString('response.Failed to upload');
        }
    } elseif (isset($FMData['filename'], $FMData['do_action']) && \is_readable($this->FE['basepath'] . $FMData['filename']) && $this->pathSecurityCheck($FMData['filename'])) {
        /** Edit a file. */
        if ($FMData['do_action'] === 'edit-file') {
            if (isset($FMData['content'])) {
                $FMData['content'] = \str_replace("\r", '', $FMData['content']);
                $this->CIDRAM['OldData'] = $this->readFile($this->FE['basepath'] . $FMData['filename']);
                if (\strpos($this->CIDRAM['OldData'], "\r\n") !== false && \strpos($this->CIDRAM['OldData'], "\n\n") === false) {
                    $FMData['content'] = \str_replace("\n", "\r\n", $FMData['content']);
                }

                $Handle = \fopen($this->FE['basepath'] . $FMData['filename'], 'wb');
                \fwrite($Handle, $FMData['content']);
                \fclose($Handle);

                $this->FE['state_msg'] = $this->L10N->getString('response.File successfully modified');
            } elseif (!\file_exists($this->FE['basepath'] . $FMData['filename']) || !\is_readable($this->FE['basepath'] . $FMData['filename'])) {
                $this->FE['state_msg'] = \sprintf($this->L10N->getString('response.Failed to access %s'), $this->FE['basepath'] . $FMData['filename']);
            } elseif (\filesize($this->FE['basepath'] . $FMData['filename']) >= $this->FE['MemoryLimit']) {
                $this->FE['state_msg'] = \sprintf($this->L10N->getString('response.Failed to access %s'), $this->FE['basepath'] . $FMData['filename']) . $this->L10N->getString('response.The targeted file_s size exceeds PHP_s memory limit');
            } else {
                $this->FE['FE_Title'] .= ' – ' . $FMData['filename'];
                $this->FE['filename'] = $FMData['filename'];
                $this->FE['content'] = $this->readFile($this->FE['basepath'] . $FMData['filename']);

                /** Component update file overwrite warning. */
                if (isset($this->Components['Files'][$FMData['filename']])) {
                    $this->FE['state_msg'] = \sprintf($this->L10N->getString('warning.Likely to be overwritten'), $this->Components['Files'][$FMData['filename']]);
                }

                /** File corruption warning. */
                if (!$this->Demojibakefier->checkConformity($this->FE['content'])) {
                    if ($this->FE['state_msg'] !== '') {
                        $this->FE['state_msg'] .= '<br />';
                    }
                    $this->FE['state_msg'] .= $this->L10N->getString('warning.Likely to become corrupted');
                }

                /** Ensure safe for textarea display. */
                $this->FE['content'] = \htmlentities($this->FE['content']);

                /** PHP file warning. */
                if (\preg_match('~\.php$~i', $FMData['filename'])) {
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
                $this->Events->fireEvent('final');
                die;
            }
        }

        /** Download a file. */
        if ($FMData['do_action'] === 'download-file') {
            $this->Events->fireEvent('final');
            \header('Content-Type: application/octet-stream');
            \header('Content-Transfer-Encoding: Binary');
            \header('Content-disposition: attachment; filename="' . \basename($FMData['filename']) . '"');
            echo $this->readFile($this->FE['basepath'] . $FMData['filename']);
            die;
        }

        /** Duplicate a file. */
        if ($FMData['do_action'] === 'duplicate-file') {
            $FMData['filename'] = $this->canonical($this->FE['basepath'] . $FMData['filename']);
            if (!\is_writable(\dirname($FMData['filename']))) {
                $this->FE['state_msg'] = $this->L10N->getString('response.Directory _%s_ isn_t writable') . ' ' . $this->L10N->getString('response.Failed to duplicate');
            } else {
                $FMBase = \basename($FMData['filename']);
                $Ext = ($DecPos = \strrpos($FMBase, '.')) === false ? '' : \substr($FMBase, $DecPos);
                $Target = $this->copyIterableName($Ext === '' ? $FMData['filename'] : \preg_replace('~' . \preg_quote($Ext) . '$~i', '', $FMData['filename']), function (string $Filename) use ($Ext): bool {
                    return \file_exists($Filename . $Ext);
                });
                $this->FE['state_msg'] = $this->copyFile($FMData['filename'], $Target . $Ext);
                unset($Target, $Ext, $FMBase);
            }
        }
    }

    /** Template for file rows. */
    $this->FE['FilesRow'] = $this->readFile($this->getAssetPath('_files_row.html'));

    /** Fetch files data. */
    try {
        $Files = $this->fileManagerRecursiveList($this->FE['basepath']);
    } catch (\UnexpectedValueException | \Exception $Exception) {
        $Files = $this->fileManagerRecursiveList($this->FE['basepath'], false);
    }

    /** Whether to display recursive display controls. */
    if ($this->FE['CanShowRecursive'] === 1) {
        $this->FE['Recursive Display Controls'] = \sprintf(
            '<span id="subdirsCtrl" class="navicon switchCtrl swOp hoverglow" onclick="javascript:y=document.getElementById(\'subdirsCtrl\'),y.classList.contains(\'swOp\')?' .
            '(y.classList.add(\'swCl\'),y.classList.remove(\'swOp\'),qOrd=document.querySelectorAll(\'div.isSub\'),qOrd.forEach((x)=>{x.classList.add(\'sHide\')}),document.getElementById(\'subdirsState\').textContent=\'%1$s\'):' .
            '(y.classList.add(\'swOp\'),y.classList.remove(\'swCl\'),qOrd=document.querySelectorAll(\'div.isSub\'),qOrd.forEach((x)=>{x.classList.remove(\'sHide\')}),document.getElementById(\'subdirsState\').textContent=\'%2$s\')"' .
            ' aria-labelledby="subdirsState" tabindex="0" role="button"></span> <span id="subdirsState">%2$s</span>',
            $this->L10N->getString('label.Content of subdirectories is hidden'),
            $this->L10N->getString('label.Content of subdirectories is shown')
        );
    } elseif ($this->FE['CanShowRecursive'] === -1) {
        $this->FE['Recursive Display Controls'] = \sprintf(
            '<span class="bckpicon"><span id="subdirsCtrl" class="navicon switchCtrl swOp hoverglow" aria-labelledby="subdirsState" role="button"></span><span class="bckpicon auxrd disabledoverlay"></span></span> <span id="subdirsState">%s</span>',
            $this->L10N->getString('label.Content of subdirectories is shown')
        );
    } else {
        $this->FE['Recursive Display Controls'] = \sprintf(
            '<span class="bckpicon"><span id="subdirsCtrl" class="navicon switchCtrl swCl hoverglow" aria-labelledby="subdirsState" role="button"></span><span class="bckpicon auxrd disabledoverlay"></span></span> <span id="subdirsState">%s</span>',
            $this->L10N->getString('label.Content of subdirectories is hidden')
        );
    }

    /** Parse output. */
    $this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_files.html')), true);

    /** Initialise files data variable. */
    $this->FE['FilesData'] = '';

    /** Process files data. */
    \array_walk($Files, function ($ThisFile): void {
        $ThisFile['ThisOptions'] = [];
        $ThisFile['FilenameID'] = \preg_replace('~^0+~', '', \bin2hex($ThisFile['Filename']));
        if ($ThisFile['CanEdit'] && $ThisFile['Readable'] && $ThisFile['Writable']) {
            $ThisFile['ThisOptions'][] = \sprintf(
                '<code onclick="javascript:document.getElementById(\'fmControlDoAction\').value=\'edit-file\';document.getElementById(\'fmControlFilename\').value=document.getElementById(\'File%1$s\').textContent;document.getElementById(\'fmControl\').submit()"><span class="auxicon auxbl edit" title="%2$s"></span><span class="s fmicontxt">%2$s</span></code>',
                $ThisFile['FilenameID'],
                $this->L10N->getString('field.Edit')
            );
        }
        if (!$ThisFile['Directory']) {
            if ($ThisFile['Readable']) {
                $ThisFile['ThisOptions'][] = \sprintf(
                    '<code onclick="javascript:document.getElementById(\'fmControlDoAction\').value=\'download-file\';document.getElementById(\'fmControlFilename\').value=document.getElementById(\'File%1$s\').textContent;document.getElementById(\'fmControl\').submit()"><span class="auxicon auxbl download" title="%2$s"></span><span class="s fmicontxt">%2$s</span></code>',
                    $ThisFile['FilenameID'],
                    $this->L10N->getString('field.Download')
                );
            }
            if ($ThisFile['Deletable']) {
                $ThisFile['ThisOptions'][] = \sprintf(
                    '<code onclick="javascript:document.getElementById(\'fmControlDoAction\').value=\'duplicate-file\';document.getElementById(\'fmControlFilename\').value=document.getElementById(\'File%1$s\').textContent;document.getElementById(\'fmControl\').submit()"><span class="auxicon auxbl duplicate" title="%2$s"></span><span class="s fmicontxt">%2$s</span></code>',
                    $ThisFile['FilenameID'],
                    $this->L10N->getString('label.Duplicate')
                );
            }
        }
        if ((!$ThisFile['Directory'] || !\is_readable($this->FE['basepath'] . $ThisFile['Filename']) || $this->isDirEmpty($this->FE['basepath'] . $ThisFile['Filename'])) && $ThisFile['Deletable']) {
            $ThisFile['ThisOptions'][] = \sprintf(
                '<code onclick="javascript:hideid(\'File%1$s\');hideid(\'Icon%1$s\');hideid(\'DeleteControls%1$s\');showid(\'RenameControls%1$s\');document.getElementById(\'RenameInput%1$s\').focus()"><span class="auxicon auxbl rename" title="%2$s"></span><span class="s fmicontxt">%2$s</span></code>',
                $ThisFile['FilenameID'],
                $this->L10N->getString('field.Rename')
            );
            $ThisFile['ThisOptions'][] = \sprintf(
                '<code onclick="javascript:hideid(\'File%1$s\');hideid(\'Icon%1$s\');hideid(\'RenameControls%1$s\');showid(\'DeleteControls%1$s\');document.getElementById(\'Name%1$s\').classList.add(\'r\');document.getElementById(\'Size%1$s\').classList.add(\'r\');document.getElementById(\'Component%1$s\').classList.add(\'r\');document.getElementById(\'Options%1$s\').classList.add(\'rf\')"><span class="auxicon auxrd delete" title="%2$s"></span><span class="s fmicontxt">%2$s</span></code>',
                $ThisFile['FilenameID'],
                $this->L10N->getString('field.Delete')
            );
            $ThisFile['DeleteConfirmText'] = \sprintf($this->L10N->getString('confirm.Delete'), '<span id="DeleteConfirmContent' . $ThisFile['FilenameID'] . '">' . $ThisFile['Filename'] . '</span>');
        } else {
            $ThisFile['DeleteConfirmText'] = '';
        }
        $ThisFile['ThisOptions'] = \implode('<span class="emsep"> – </span><br class="brsep" />', $ThisFile['ThisOptions']);
        if (\substr($ThisFile['Icon'], 0, 5) === 'icon=') {
            $ThisFile['Icon'] = \sprintf('<span class="fmicon auxbl %s" id="Icon%s"></span>', \substr($ThisFile['Icon'], 5), $ThisFile['FilenameID']);
        } else {
            $ThisFile['Icon'] = \sprintf('<img src="?cidram-page=icon&%s&theme=%s" alt="Icon" class="ico" id="Icon%s" />', $ThisFile['Icon'], $this->FE['theme'], $ThisFile['FilenameID']);
        }
        if ($ThisFile['Directory'] && \is_readable($this->FE['basepath'] . $ThisFile['Filename']) && !$this->isDirEmpty($this->FE['basepath'] . $ThisFile['Filename'])) {
            $ThisFile['DirLinkOpen'] = '<a href="?cidram-page=file-manager&basepath=' . $this->canonical($this->FE['basepath'] . $ThisFile['Filename'] . '/') . '">';
            $ThisFile['DirLinkClose'] = '</a>';
        } else {
            $ThisFile['DirLinkOpen'] = '';
            $ThisFile['DirLinkClose'] = '';
        }
        $this->FE['FilesData'] .= $this->parseVars($this->FE + $ThisFile, $this->FE['FilesRow'], true);
    });

    /** Send output. */
    echo $this->sendOutput();
} elseif (isset($FMData['filename'], $FMData['filename_new']) && \is_readable($this->FE['basepath'] . $FMData['filename'])) {
    /** Should fail if the old filename or the new filename aren't safe or if they're the same. */
    $SafeToContinue = ($this->pathSecurityCheck($FMData['filename']) && $this->pathSecurityCheck($FMData['filename_new']) && $FMData['filename'] !== $FMData['filename_new']);

    /** If the destination already exists, delete it before renaming the new file. */
    if ($SafeToContinue && \file_exists($this->FE['basepath'] . $FMData['filename_new']) && \is_readable($this->FE['basepath'] . $FMData['filename_new'])) {
        if (\is_dir($this->FE['basepath'] . $FMData['filename_new'])) {
            if (!$this->isDirEmpty($this->FE['basepath'] . $FMData['filename_new']) || !\rmdir($this->FE['basepath'] . $FMData['filename_new'])) {
                $SafeToContinue = false;
            }
        } elseif (!\unlink($this->FE['basepath'] . $FMData['filename_new'])) {
            $SafeToContinue = false;
        }
    }

    if ($SafeToContinue) {
        /** Add parent directories. */
        $this->buildPath($this->FE['basepath'] . $FMData['filename_new']);

        /** Rename the file. */
        if (rename($this->FE['basepath'] . $FMData['filename'], $this->FE['basepath'] . $FMData['filename_new'])) {
            $this->deleteDirectory($FMData['filename']);
            $this->FE['state_msg'] = 'OK';
        } else {
            $this->FE['state_msg'] = $this->L10N->getString('response.Failed to rename');
        }
    } else {
        $this->FE['state_msg'] = $this->L10N->getString('response.Failed to rename');
    }

    /** Return results to the async call for the rename operation. */
    echo $this->FE['state_msg'];
} elseif (isset($FMData['filename'], $FMData['do_action']) && $FMData['do_action'] === 'delete-file') {
    if (\is_dir($this->FE['basepath'] . $FMData['filename'])) {
        if (\is_readable($this->FE['basepath'] . $FMData['filename']) && $this->isDirEmpty($this->FE['basepath'] . $FMData['filename']) && \rmdir($this->FE['basepath'] . $FMData['filename'])) {
            $this->FE['state_msg'] = 'OK';
        } else {
            $this->FE['state_msg'] = $this->L10N->getString('response.Failed to delete');
        }
    } elseif (\unlink($this->FE['basepath'] . $FMData['filename'])) {
        $this->deleteDirectory($FMData['filename']);
        $this->FE['state_msg'] = 'OK';
    } else {
        $this->FE['state_msg'] = $this->L10N->getString('response.Failed to delete');
    }

    /** Return results to the async call for the delete operation. */
    echo $this->FE['state_msg'];
}

/** Cleanup. */
unset($this->FE['MemoryLimit'], $FMData);
