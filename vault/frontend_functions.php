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
 * This file: Front-end functions file (last modified: 2019.12.10).
 */

/**
 * Syncs downstream metadata with upstream metadata to remove superfluous
 * entries. Installed components are ignored.
 *
 * @param string $Downstream Downstream/local data.
 * @param string $Upstream Upstream/remote data.
 * @return string Patched/synced data (or an empty string on failure).
 */
$CIDRAM['Congruency'] = function ($Downstream, $Upstream) use (&$CIDRAM) {
    if (empty($Downstream) || empty($Upstream)) {
        return '';
    }
    $DownstreamArray = (new \Maikuolan\Common\YAML($Downstream))->Data;
    $UpstreamArray = (new \Maikuolan\Common\YAML($Upstream))->Data;
    foreach ($DownstreamArray as $Element => $Data) {
        if (!isset($Data['Version']) && !isset($Data['Files']) && !isset($UpstreamArray[$Element])) {
            $Downstream = preg_replace("~\n" . preg_quote($Element) . ":?(\n [^\n]*)*\n~i", "\n", $Downstream);
        }
    }
    return $Downstream;
};

/**
 * Can be used to delete some files via the front-end.
 *
 * @param string $File The file to delete.
 * @return bool Success or failure.
 */
$CIDRAM['Delete'] = function ($File) use (&$CIDRAM) {
    if (preg_match('~^(\'.*\'|".*")$~', $File)) {
        $File = substr($File, 1, -1);
    }
    if (!empty($File) && file_exists($CIDRAM['Vault'] . $File) && $CIDRAM['Traverse']($File)) {
        if (!unlink($CIDRAM['Vault'] . $File)) {
            return false;
        }
        $CIDRAM['DeleteDirectory']($File);
        return true;
    }
    return false;
};

/**
 * Can be used to patch parts of files after updating via the front-end.
 *
 * @param string $Query The instruction to execute.
 * @return bool Success or failure.
 */
$CIDRAM['In'] = function ($Query) use (&$CIDRAM) {
    if (!isset($CIDRAM['Updater-IO']) || !$Delimiter = substr($Query, 0, 1)) {
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
    if (empty($QueryParts[0]) || empty($QueryParts[1]) || !file_exists($CIDRAM['Vault'] . $QueryParts[0]) || !is_readable($CIDRAM['Vault'] . $QueryParts[0])) {
        return false;
    }

    /** Fetch file content. */
    $Data = $CIDRAM['Updater-IO']->readFile($CIDRAM['Vault'] . $QueryParts[0]);

    /** Normalise main instruction. */
    $QueryParts[1] = strtolower($QueryParts[1]);

    /** Replace file content. */
    if ($QueryParts[1] === 'replace' && !empty($QueryParts[3]) && strtolower($QueryParts[3]) === 'with') {
        $Data = preg_replace($QueryParts[2], (isset($QueryParts[4]) ? $QueryParts[4] : ''), $Data);
        return $CIDRAM['Updater-IO']->writeFile($CIDRAM['Vault'] . $QueryParts[0], $Data);
    }

    /** Nothing done. Return false (failure). */
    return false;
};

/**
 * Adds integer values; Returns zero if the sum total is negative or if any
 * contained values aren't integers, and otherwise, returns the sum total.
 */
$CIDRAM['ZeroMin'] = function () {
    $Sum = 0;
    foreach (func_get_args() as $Value) {
        $IntValue = (int)$Value;
        if ($IntValue !== $Value) {
            return 0;
        }
        $Sum += $IntValue;
    }
    return $Sum < 0 ? 0 : $Sum;
};

/**
 * Format filesize information.
 *
 * @param int $Filesize
 */
$CIDRAM['FormatFilesize'] = function (&$Filesize) use (&$CIDRAM) {
    $Scale = ['field_size_bytes', 'field_size_KB', 'field_size_MB', 'field_size_GB', 'field_size_TB'];
    $Iterate = 0;
    $Filesize = (int)$Filesize;
    while ($Filesize > 1024) {
        $Filesize = $Filesize / 1024;
        $Iterate++;
        if ($Iterate > 4) {
            break;
        }
    }
    $Filesize = $CIDRAM['NumberFormatter']->format($Filesize, ($Iterate === 0) ? 0 : 2) . ' ' . $CIDRAM['L10N']->getPlural($Filesize, $Scale[$Iterate]);
};

/**
 * Remove an entry from the front-end cache data.
 *
 * @param string $Source Variable containing cache file data.
 * @param bool $Rebuild Flag indicating to rebuild cache file.
 * @param string $Entry Name of the cache entry to be deleted.
 */
$CIDRAM['FECacheRemove'] = function (&$Source, &$Rebuild, $Entry) use (&$CIDRAM) {

    /** Override if using a different preferred caching mechanism. */
    if ($CIDRAM['Cache']->Using && $CIDRAM['Cache']->Using !== 'FF') {
        $CIDRAM['Cache']->deleteEntry($Entry);
        return;
    }

    /** Default process. */
    $Entry64 = base64_encode($Entry);
    while (($EntryPos = strpos($Source, "\n" . $Entry64 . ',')) !== false) {
        $EoL = strpos($Source, "\n", $EntryPos + 1);
        if ($EoL !== false) {
            $Line = substr($Source, $EntryPos, $EoL - $EntryPos);
            $Source = str_replace($Line, '', $Source);
            $Rebuild = true;
        }
    }
};

/**
 * Add an entry to the front-end cache data.
 *
 * @param string $Source Variable containing cache file data.
 * @param bool $Rebuild Flag indicating to rebuild cache file.
 * @param string $Entry Name of the cache entry to be added.
 * @param string $Data Cache entry data (what should be cached).
 * @param int $Expires When should the cache entry expire (be deleted).
 */
$CIDRAM['FECacheAdd'] = function (&$Source, &$Rebuild, $Entry, $Data, $Expires) use (&$CIDRAM) {

    /** Override if using a different preferred caching mechanism. */
    if ($CIDRAM['Cache']->Using && $CIDRAM['Cache']->Using !== 'FF') {
        $CIDRAM['Cache']->setEntry($Entry, $Data, $Expires - $CIDRAM['Now']);
        return;
    }

    /** Default process. */
    $CIDRAM['FECacheRemove']($Source, $Rebuild, $Entry);
    $Expires = (int)$Expires;
    $NewLine = base64_encode($Entry) . ',' . base64_encode($Data) . ',' . $Expires . "\n";
    $Source .= $NewLine;
    $Rebuild = true;
};

/**
 * Get an entry from the front-end cache data.
 *
 * @param string $Source Variable containing cache file data.
 * @param string $Entry Name of the cache entry to get.
 * @return string|bool Returned cache entry data (or false on failure).
 */
$CIDRAM['FECacheGet'] = function (&$Source, $Entry) use (&$CIDRAM) {

    /** Override if using a different preferred caching mechanism. */
    if ($CIDRAM['Cache']->Using && $CIDRAM['Cache']->Using !== 'FF') {
        return $CIDRAM['Cache']->getEntry($Entry);
    }

    /** Default process. */
    $Entry = base64_encode($Entry);
    $EntryPos = strpos($Source, "\n" . $Entry . ',');
    if ($EntryPos !== false) {
        $EoL = strpos($Source, "\n", $EntryPos + 1);
        if ($EoL !== false) {
            $Line = substr($Source, $EntryPos, $EoL - $EntryPos);
            $Entry = explode(',', $Line);
            if (!empty($Entry[1])) {
                return base64_decode($Entry[1]);
            }
        }
    }
    return false;
};

/**
 * Compare two different versions of CIDRAM, or two different versions of a
 * component for CIDRAM, to see which is newer (mostly used by the updater).
 *
 * @param string $A The 1st version string.
 * @param string $B The 2nd version string.
 * @return bool True if the 2nd version is newer than the 1st version, and false
 *      otherwise (i.e., if they're the same, or if the 1st version is newer).
 */
$CIDRAM['VersionCompare'] = function ($A, $B) {
    $Normalise = function (&$Ver) {
        $Ver =
            preg_match('~^v?(\d+)$~i', $Ver, $Matches) ?:
            preg_match('~^v?(\d+)\.(\d+)$~i', $Ver, $Matches) ?:
            preg_match('~^v?(\d+)\.(\d+)\.(\d+)(alpha\d|RC\d{1,2}|-[.\d\w_+\\/]+)?$~i', $Ver, $Matches) ?:
            preg_match('~^(\d{1,4})[.-](\d{1,2})[.-](\d{1,4})(RC\d{1,2}|[.+-][\d\w_+\\/]+)?$~i', $Ver, $Matches) ?:
            preg_match('~^([\w]+)-([\d\w]+)-([\d\w]+)$~i', $Ver, $Matches);
        $Ver = [
            'Major' => isset($Matches[1]) ? $Matches[1] : 0,
            'Minor' => isset($Matches[2]) ? $Matches[2] : 0,
            'Patch' => isset($Matches[3]) ? $Matches[3] : 0,
            'Build' => isset($Matches[4]) ? $Matches[4] : 0
        ];
        if ($Ver['Build'] && substr($Ver['Build'], 0, 1) === '-') {
            $Ver['Build'] = substr($Ver['Build'], 1);
        }
        $Ver = array_map(function ($Var) {
            $VarInt = (int)$Var;
            $VarLen = strlen($Var);
            if ($Var == $VarInt && strlen($VarInt) === $VarLen && $VarLen > 1) {
                return $VarInt;
            }
            return strtolower($Var);
        }, $Ver);
    };
    $Normalise($A);
    $Normalise($B);
    return (
        $B['Major'] > $A['Major'] || (
            $B['Major'] === $A['Major'] &&
            $B['Minor'] > $A['Minor']
        ) || (
            $B['Major'] === $A['Major'] &&
            $B['Minor'] === $A['Minor'] &&
            $B['Patch'] > $A['Patch']
        ) || (
            $B['Major'] === $A['Major'] &&
            $B['Minor'] === $A['Minor'] &&
            $B['Patch'] === $A['Patch'] &&
            !empty($A['Build']) && (
                empty($B['Build']) || $B['Build'] > $A['Build']
            )
        )
    );
};

/**
 * Remove sub-arrays from an array.
 *
 * @param array $Arr An array.
 * @return array An array.
 */
$CIDRAM['ArrayFlatten'] = function (array $Arr) {
    return array_filter($Arr, function () {
        return (!is_array(func_get_args()[0]));
    });
};

/**
 * Reduce an L10N array down to a single relevant string.
 *
 * @param array $Arr An L10N array.
 * @param string $Lang The language that we're hoping to isolate from the array.
 */
$CIDRAM['IsolateL10N'] = function (array &$Arr, $Lang) {
    if (isset($Arr[$Lang])) {
        $Arr = $Arr[$Lang];
    } elseif (isset($Arr['en'])) {
        $Arr = $Arr['en'];
    } else {
        $Key = key($Arr);
        $Arr = $Arr[$Key];
    }
};

/**
 * Append one or two values to a string, depending on whether that string is
 * empty prior to calling the closure (allows cleaner code in some areas).
 *
 * @param string $String The string to work with.
 * @param string $Delimit Appended first, if the string is not empty.
 * @param string $Append Appended second, and always (empty or otherwise).
 */
$CIDRAM['AppendToString'] = function (&$String, $Delimit = '', $Append = '') {
    if (!empty($String)) {
        $String .= $Delimit;
    }
    $String .= $Append;
};

/**
 * Performs some simple sanity checks on files (used by the updater).
 *
 * @param string $FileName The name of the file to be checked.
 * @param string $FileData The content of the file to be checked.
 * @return bool True when passed; False when failed.
 */
$CIDRAM['SanityCheck'] = function ($FileName, $FileData) {

    /** Check whether YAML is valid. */
    if (preg_match('~\.ya?ml$~i', $FileName)) {
        $ThisYAML = new \Maikuolan\Common\YAML();
        if (!($ThisYAML->process($FileData, $ThisYAML->Data))) {
            return false;
        }
    }

    /** A very simple, rudimentary check for unwanted, possibly maliciously inserted HTML. */
    if ($FileData && preg_match('~<(?:html|body)~i', $FileData)) {
        return false;
    }

    /** Passed. */
    return true;
};

/**
 * Used by the file manager to generate a list of the files contained in a
 * working directory (normally, the vault).
 *
 * @param string $Base The path to the working directory.
 * @return array A list of the files contained in the working directory.
 */
$CIDRAM['FileManager-RecursiveList'] = function ($Base) use (&$CIDRAM) {
    $Arr = [];
    $Key = -1;
    $Offset = strlen($Base);
    $List = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($Base), RecursiveIteratorIterator::SELF_FIRST);
    foreach ($List as $Item => $List) {
        $Key++;
        $ThisName = substr($Item, $Offset);
        if (preg_match('~^(?:/\.\.|./\.|\.{3})$~', str_replace("\\", '/', substr($Item, -3)))) {
            continue;
        }
        $Arr[$Key] = ['Filename' => $ThisName];
        if (is_dir($Item)) {
            $Arr[$Key]['CanEdit'] = false;
            $Arr[$Key]['Directory'] = true;
            $Arr[$Key]['Filesize'] = 0;
            $Arr[$Key]['Filetype'] = $CIDRAM['L10N']->getString('field_filetype_directory');
            $Arr[$Key]['Icon'] = 'icon=directory';
        } elseif (is_file($Item)) {
            $Arr[$Key]['CanEdit'] = true;
            $Arr[$Key]['Directory'] = false;
            $Arr[$Key]['Filesize'] = filesize($Item);
            if (isset($CIDRAM['FE']['TotalSize'])) {
                $CIDRAM['FE']['TotalSize'] += $Arr[$Key]['Filesize'];
            }
            if (isset($CIDRAM['Components']['Components'])) {
                $Component = $CIDRAM['L10N']->getString('field_filetype_unknown');
                $ThisNameFixed = str_replace("\\", '/', $ThisName);
                if (isset($CIDRAM['Components']['Files'][$ThisNameFixed])) {
                    if (!empty($CIDRAM['Components']['Names'][$CIDRAM['Components']['Files'][$ThisNameFixed]])) {
                        $Component = $CIDRAM['Components']['Names'][$CIDRAM['Components']['Files'][$ThisNameFixed]];
                    } else {
                        $Component = $CIDRAM['Components']['Files'][$ThisNameFixed];
                    }
                    if ($Component === 'CIDRAM') {
                        $Component .= ' (' . $CIDRAM['L10N']->getString('field_component') . ')';
                    }
                } elseif (preg_match('~(?:[^|/]\.ht|\.safety$|^salt\.dat$)~i', $ThisNameFixed)) {
                    $Component = $CIDRAM['L10N']->getString('label_fmgr_safety');
                } elseif (preg_match('/^config\.ini$/i', $ThisNameFixed)) {
                    $Component = $CIDRAM['L10N']->getString('link_config');
                } elseif ($CIDRAM['FileManager-IsLogFile']($ThisNameFixed)) {
                    $Component = $CIDRAM['L10N']->getString('link_logs');
                } elseif (preg_match('/(?:^auxiliary\.yaml|^ignore\.dat|_custom\.dat|\.sig|\.inc)$/i', $ThisNameFixed)) {
                    $Component = $CIDRAM['L10N']->getString('label_fmgr_other_sig');
                } elseif (preg_match('~(?:\.tmp|\.rollback|^(?:cache|hashes|ipbypass|fe_assets/frontend)\.dat)$~i', $ThisNameFixed)) {
                    $Component = $CIDRAM['L10N']->getString('label_fmgr_cache_data');
                } elseif (preg_match('/\.(?:dat|ya?ml)$/i', $ThisNameFixed)) {
                    $Component = $CIDRAM['L10N']->getString('label_fmgr_updates_metadata');
                }
                if (!isset($CIDRAM['Components']['Components'][$Component])) {
                    $CIDRAM['Components']['Components'][$Component] = 0;
                }
                $CIDRAM['Components']['Components'][$Component] += $Arr[$Key]['Filesize'];
                if (!isset($CIDRAM['Components']['ComponentFiles'][$Component])) {
                    $CIDRAM['Components']['ComponentFiles'][$Component] = [];
                }
                $CIDRAM['Components']['ComponentFiles'][$Component][$ThisNameFixed] = $Arr[$Key]['Filesize'];
            }
            if (($ExtDel = strrpos($Item, '.')) !== false) {
                $Ext = strtoupper(substr($Item, $ExtDel + 1));
                if (!$Ext) {
                    $Arr[$Key]['Filetype'] = $CIDRAM['L10N']->getString('field_filetype_unknown');
                    $Arr[$Key]['Icon'] = 'icon=unknown';
                    $CIDRAM['FormatFilesize']($Arr[$Key]['Filesize']);
                    continue;
                }
                $Arr[$Key]['Filetype'] = $CIDRAM['ParseVars'](['EXT' => $Ext], $CIDRAM['L10N']->getString('field_filetype_info'));
                if ($Ext === 'ICO') {
                    $Arr[$Key]['CanEdit'] = false;
                    $Arr[$Key]['Icon'] = 'file=' . urlencode($Arr[$Key]['Filename']);
                    $CIDRAM['FormatFilesize']($Arr[$Key]['Filesize']);
                    continue;
                }
                if (preg_match(
                    '/^(?:.?[BGL]Z.?|7Z|A(CE|LZ|P[KP]|R[CJ]?)?|B([AH]|Z2?)|CAB|DMG|' .
                    'I(CE|SO)|L(HA|Z[HOWX]?)|P(AK|AQ.?|CK|EA)|RZ|S(7Z|EA|EN|FX|IT.?|QX)|' .
                    'X(P3|Z)|YZ1|Z(IP.?|Z)?|(J|M|PH|R|SH|T|X)AR)$/'
                , $Ext)) {
                    $Arr[$Key]['CanEdit'] = false;
                    $Arr[$Key]['Icon'] = 'icon=archive';
                } elseif (preg_match('/^[SDX]?HT[AM]L?$/', $Ext)) {
                    $Arr[$Key]['Icon'] = 'icon=html';
                } elseif (preg_match('/^(?:CSV|JSON|NEON|SQL|YAML)$/', $Ext)) {
                    $Arr[$Key]['Icon'] = 'icon=ods';
                } elseif (preg_match('/^(?:PDF|XDP)$/', $Ext)) {
                    $Arr[$Key]['CanEdit'] = false;
                    $Arr[$Key]['Icon'] = 'icon=pdf';
                } elseif (preg_match('/^DOC[XT]?$/', $Ext)) {
                    $Arr[$Key]['CanEdit'] = false;
                    $Arr[$Key]['Icon'] = 'icon=doc';
                } elseif (preg_match('/^XLS[XT]?$/', $Ext)) {
                    $Arr[$Key]['CanEdit'] = false;
                    $Arr[$Key]['Icon'] = 'icon=xls';
                } elseif (preg_match('/^(?:CSS|JS|OD[BFGPST]|P(HP|PT))$/', $Ext)) {
                    $Arr[$Key]['Icon'] = 'icon=' . strtolower($Ext);
                    if (!preg_match('/^(?:CSS|JS|PHP)$/', $Ext)) {
                        $Arr[$Key]['CanEdit'] = false;
                    }
                } elseif (preg_match('/^(?:FLASH|SWF)$/', $Ext)) {
                    $Arr[$Key]['CanEdit'] = false;
                    $Arr[$Key]['Icon'] = 'icon=swf';
                } elseif (preg_match(
                    '/^(?:BM[2P]|C(D5|GM)|D(IB|W[FG]|XF)|ECW|FITS|GIF|IMG|J(F?IF?|P[2S]|PE?G?2?|XR)|P(BM|CX|DD|GM|IC|N[GMS]|PM|S[DP])|S(ID|V[AG])|TGA|W(BMP?|EBP|MP)|X(CF|BMP))$/'
                , $Ext)) {
                    $Arr[$Key]['CanEdit'] = false;
                    $Arr[$Key]['Icon'] = 'icon=image';
                } elseif (preg_match(
                    '/^(?:H?264|3GP(P2)?|A(M[CV]|VI)|BIK|D(IVX|V5?)|F([4L][CV]|MV)|GIFV|HLV|' .
                    'M(4V|OV|P4|PE?G[4V]?|KV|VR)|OGM|V(IDEO|OB)|W(EBM|M[FV]3?)|X(WMV|VID))$/'
                , $Ext)) {
                    $Arr[$Key]['CanEdit'] = false;
                    $Arr[$Key]['Icon'] = 'icon=video';
                } elseif (preg_match(
                    '/^(?:3GA|A(AC|IFF?|SF|U)|CDA|FLAC?|M(P?4A|IDI|KA|P[A23])|OGG|PCM|' .
                    'R(AM?|M[AX])|SWA|W(AVE?|MA))$/'
                , $Ext)) {
                    $Arr[$Key]['CanEdit'] = false;
                    $Arr[$Key]['Icon'] = 'icon=audio';
                } elseif (preg_match('/^(?:MD|NFO|RTF|TXT)$/', $Ext)) {
                    $Arr[$Key]['Icon'] = 'icon=text';
                }
            } else {
                $Arr[$Key]['Filetype'] = $CIDRAM['L10N']->getString('field_filetype_unknown');
            }
        }
        if (empty($Arr[$Key]['Icon'])) {
            $Arr[$Key]['Icon'] = 'icon=unknown';
        }
        if ($Arr[$Key]['Filesize']) {
            $CIDRAM['FormatFilesize']($Arr[$Key]['Filesize']);
            $Arr[$Key]['Filesize'] .= ' ⏰ <em>' . $CIDRAM['TimeFormat'](filemtime($Item), $CIDRAM['Config']['general']['timeFormat']) . '</em>';
        } else {
            $Arr[$Key]['Filesize'] = '';
        }
    }
    return $Arr;
};

/**
 * Used by the file manager and the updates pages to fetch the components list.
 *
 * @param string $Base The path to the working directory.
 * @param array $Arr The array to use for rendering components file YAML data.
 */
$CIDRAM['FetchComponentsLists'] = function ($Base, array &$Arr) use (&$CIDRAM) {
    $Files = new DirectoryIterator($Base);
    foreach ($Files as $ThisFile) {
        if (!empty($ThisFile) && preg_match('/\.(?:dat|inc|ya?ml)$/i', $ThisFile)) {
            $Data = $CIDRAM['ReadFile']($Base . $ThisFile);
            if (substr($Data, 0, 4) === "---\n" && ($EoYAML = strpos($Data, "\n\n")) !== false) {
                $CIDRAM['YAML']->process(substr($Data, 4, $EoYAML - 4), $Arr);
            }
        }
    }
};

/**
 * Checks paths for directory traversal and ensures that they only contain
 * expected characters.
 *
 * @param string $Path The path to check.
 * @return bool False when directory traversals and/or unexpected characters
 *      are detected, and true otherwise.
 */
$CIDRAM['FileManager-PathSecurityCheck'] = function ($Path) {
    $Path = str_replace("\\", '/', $Path);
    if (
        preg_match('~(?://|[^!\d\w\._-]$)~i', $Path) ||
        preg_match('~^(?:/\.\.|./\.|\.{3})$~', str_replace("\\", '/', substr($Path, -3)))
    ) {
        return false;
    }
    $Path = preg_split('@/@', $Path, -1, PREG_SPLIT_NO_EMPTY);
    $Valid = true;
    array_walk($Path, function ($Segment) use (&$Valid) {
        if (empty($Segment) || preg_match('/(?:[\x00-\x1f\x7f]+|^\.+$)/i', $Segment)) {
            $Valid = false;
        }
    });
    return $Valid;
};

/**
 * Used by the logs viewer to generate a list of the logfiles contained in a
 * working directory (normally, the vault).
 *
 * @param string $Base The path to the working directory.
 * @return array A list of the logfiles contained in the working directory.
 */
$CIDRAM['Logs-RecursiveList'] = function ($Base) use (&$CIDRAM) {
    $Arr = [];
    $List = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($Base), RecursiveIteratorIterator::SELF_FIRST);
    foreach ($List as $Item => $List) {
        $ThisName = str_replace("\\", '/', substr($Item, strlen($Base)));
        if (!is_file($Item) || !is_readable($Item) || is_dir($Item) || !$CIDRAM['FileManager-IsLogFile']($ThisName)) {
            continue;
        }
        $Arr[$ThisName] = ['Filename' => $ThisName, 'Filesize' => filesize($Item)];
        $CIDRAM['FormatFilesize']($Arr[$ThisName]['Filesize']);
    }
    ksort($Arr);
    return $Arr;
};

/**
 * Checks whether a component is in use (front-end closure).
 *
 * @param array $Component An array of the component metadata.
 * @return bool True for when in use; False for when not in use.
 */
$CIDRAM['IsInUse'] = function (array $Component) use (&$CIDRAM) {
    $Files = empty($Component['Files']['To']) ? [] : $Component['Files']['To'];
    $CIDRAM['Arrayify']($Files);
    $UsedWith = empty($Component['Used with']) ? '' : $Component['Used with'];
    $Description = empty($Component['Extended Description']) ? '' : $Component['Extended Description'];
    if (is_array($Description)) {
        $CIDRAM['IsolateL10N']($Description, $CIDRAM['Config']['general']['lang']);
    }
    foreach ($Files as $File) {
        $File = preg_quote($File);
        if ((($UsedWith === 'ipv4' || strpos($Description, 'signatures-&gt;ipv4') !== false) && preg_match(
            '~,(?:[\w\d]+:)?' . $File . ',~',
            ',' . $CIDRAM['Config']['signatures']['ipv4'] . ','
        )) || (($UsedWith === 'ipv6' || strpos($Description, 'signatures-&gt;ipv6') !== false) && preg_match(
            '~,(?:[\w\d]+:)?' . $File . ',~',
            ',' . $CIDRAM['Config']['signatures']['ipv6'] . ','
        )) || (($UsedWith === 'modules' || strpos($Description, 'signatures-&gt;modules') !== false) && preg_match(
            '~,(?:[\w\d]+:)?' . $File . ',~',
            ',' . $CIDRAM['Config']['signatures']['modules'] . ','
        )) || (
            !$UsedWith &&
            strpos($Description, 'signatures-&gt;ipv4') === false &&
            strpos($Description, 'signatures-&gt;ipv6') === false &&
            strpos($Description, 'signatures-&gt;modules') === false && (
                preg_match('~,(?:[\w\d]+:)?' . $File . ',~', ',' . $CIDRAM['Config']['signatures']['ipv4'] . ',') ||
                preg_match('~,(?:[\w\d]+:)?' . $File . ',~', ',' . $CIDRAM['Config']['signatures']['ipv6'] . ',') ||
                preg_match('~,(?:[\w\d]+:)?' . $File . ',~', ',' . $CIDRAM['Config']['signatures']['modules'] . ',')
            )
        )) {
            return true;
        }
    }
    return false;
};

/**
 * Determine the final IP address covered by an IPv4 CIDR. This closure is used
 * by the CIDR Calculator.
 *
 * @param string $First The first IP address.
 * @param int $Factor The range number (or CIDR factor number).
 * @return string The final IP address.
 */
$CIDRAM['IPv4GetLast'] = function ($First, $Factor) {
    $Octets = explode('.', $First);
    $Split = $Bracket = $Factor / 8;
    $Split -= floor($Split);
    $Split = (int)(8 - ($Split * 8));
    $Octet = floor($Bracket);
    if ($Octet < 4) {
        $Octets[$Octet] += pow(2, $Split) - 1;
    }
    while ($Octet < 3) {
        $Octets[$Octet + 1] = 255;
        $Octet++;
    }
    return implode('.', $Octets);
};

/**
 * Determine the final IP address covered by an IPv6 CIDR. This closure is used
 * by the CIDR Calculator.
 *
 * @param string $First The first IP address.
 * @param int $Factor The range number (or CIDR factor number).
 * @return string The final IP address.
 */
$CIDRAM['IPv6GetLast'] = function ($First, $Factor) {
    if (strpos($First, '::') !== false) {
        $Abr = 7 - substr_count($First, ':');
        $Arr = [':0:', ':0:0:', ':0:0:0:', ':0:0:0:0:', ':0:0:0:0:0:', ':0:0:0:0:0:0:'];
        $First = str_replace('::', $Arr[$Abr], $First);
    }
    $Octets = explode(':', $First);
    $Octet = 8;
    while ($Octet > 0) {
        $Octet--;
        $Octets[$Octet] = hexdec($Octets[$Octet]);
    }
    $Split = $Bracket = $Factor / 16;
    $Split -= floor($Split);
    $Split = (int)(16 - ($Split * 16));
    $Octet = floor($Bracket);
    if ($Octet < 8) {
        $Octets[$Octet] += pow(2, $Split) - 1;
    }
    while ($Octet < 7) {
        $Octets[$Octet + 1] = 65535;
        $Octet++;
    }
    $Octet = 8;
    while ($Octet > 0) {
        $Octet--;
        $Octets[$Octet] = dechex($Octets[$Octet]);
    }
    $Last = implode(':', $Octets);
    if (strpos($Last . '/', ':0:0/') !== false) {
        $Last = preg_replace('/(\:0){2,}$/i', '::', $Last, 1);
    } elseif (strpos($Last, ':0:0:') !== false) {
        $Last = preg_replace('/(?:(\:0)+\:(0\:)+|\:\:0$)/i', '::', $Last, 1);
    }
    return $Last;
};

/** Fetch remote data (front-end updates page). */
$CIDRAM['FetchRemote'] = function () use (&$CIDRAM) {
    $CIDRAM['Components']['ThisComponent']['RemoteData'] = '';
    $CIDRAM['FetchRemote-ContextFree'](
        $CIDRAM['Components']['ThisComponent']['RemoteData'],
        $CIDRAM['Components']['ThisComponent']['Remote']
    );
};

/**
 * Fetch remote data (context-free).
 *
 * @param string $RemoteData Where to put the remote data.
 * @param string $Remote Where to get the remote data.
 */
$CIDRAM['FetchRemote-ContextFree'] = function (&$RemoteData, &$Remote) use (&$CIDRAM) {
    $RemoteData = $CIDRAM['FECacheGet']($CIDRAM['FE']['Cache'], $Remote);
    if (!$RemoteData) {
        $RemoteData = $CIDRAM['Request']($Remote);
        if (strtolower(substr($Remote, -2)) === 'gz' && substr($RemoteData, 0, 2) === "\x1f\x8b") {
            $RemoteData = gzdecode($RemoteData);
        }
        if (empty($RemoteData)) {
            $RemoteData = '-';
        }
        $CIDRAM['FECacheAdd'](
            $CIDRAM['FE']['Cache'],
            $CIDRAM['FE']['Rebuild'],
            $Remote,
            $RemoteData,
            $CIDRAM['Now'] + 3600
        );
    }
};

/**
 * Checks whether component is activable.
 *
 * @param array $Component An array of the component metadata.
 * @return bool True for when activable; False for when not activable.
 */
$CIDRAM['IsActivable'] = function (array &$Component) {
    return (!empty($Component['Used with']) || (!empty($Component['Extended Description']) && strpos($Component['Extended Description'], 'signatures-&gt;') !== false));
};

/**
 * Activate component (front-end updates page).
 *
 * @param string $Type Value can be ipv4, ipv6, or modules.
 * @param string $ID The ID of the component to activate.
 */
$CIDRAM['ActivateComponent'] = function ($Type, $ID) use (&$CIDRAM) {
    $CIDRAM['Activation'][$Type] = array_unique(array_filter(
        explode(',', $CIDRAM['Activation'][$Type]),
        function ($Component) use (&$CIDRAM) {
            $Component = (strpos($Component, ':') === false) ? $Component : substr($Component, strpos($Component, ':') + 1);
            return ($Component && file_exists($CIDRAM['Vault'] . $Component));
        }
    ));
    foreach ($CIDRAM['Components']['Meta'][$ID]['Files']['To'] as $ThisFile) {
        $Ext = (($DecPos = strpos($ThisFile, '.')) !== false) ? substr($ThisFile, $DecPos + 1) : '';
        if (
            !empty($ThisFile) &&
            !preg_match('~^(?:css|gif|html?|jpe?g|js|png|ya?ml)$~i', $Ext) &&
            file_exists($CIDRAM['Vault'] . $ThisFile) &&
            empty($CIDRAM['Activation'][$Type][$ThisFile]) &&
            $CIDRAM['Traverse']($ThisFile)
        ) {
            $CIDRAM['Activation'][$Type][] = $ThisFile;
        }
    }
    if (count($CIDRAM['Activation'][$Type])) {
        sort($CIDRAM['Activation'][$Type]);
    }
    $CIDRAM['Activation'][$Type] = implode(',', $CIDRAM['Activation'][$Type]);
    if ($CIDRAM['Activation'][$Type] !== $CIDRAM['Config']['signatures'][$Type]) {
        $CIDRAM['Activation']['Modified'] = true;
    }
};

/**
 * Deactivate component (front-end updates page).
 *
 * @param string $Type Value can be ipv4, ipv6, or modules.
 * @param string $ID The ID of the component to deactivate.
 */
$CIDRAM['DeactivateComponent'] = function ($Type, $ID) use (&$CIDRAM) {
    $CIDRAM['Deactivation'][$Type] = array_unique(array_filter(
        explode(',', $CIDRAM['Deactivation'][$Type]),
        function ($Component) use (&$CIDRAM) {
            $Component = (strpos($Component, ':') === false) ? $Component : substr($Component, strpos($Component, ':') + 1);
            return ($Component && file_exists($CIDRAM['Vault'] . $Component));
        }
    ));
    if (count($CIDRAM['Deactivation'][$Type])) {
        sort($CIDRAM['Deactivation'][$Type]);
    }
    $CIDRAM['Deactivation'][$Type] = ',' . implode(',', $CIDRAM['Deactivation'][$Type]) . ',';
    foreach ($CIDRAM['Components']['Meta'][$ID]['Files']['To'] as $CIDRAM['Deactivation']['ThisFile']) {
        $CIDRAM['Deactivation'][$Type] = preg_replace(
            '~,(?:[\w\d]+:)?' . preg_quote($CIDRAM['Deactivation']['ThisFile']) . ',~',
            ',',
            $CIDRAM['Deactivation'][$Type]
        );
    }
    $CIDRAM['Deactivation'][$Type] = substr($CIDRAM['Deactivation'][$Type], 1, -1);
    if ($CIDRAM['Deactivation'][$Type] !== $CIDRAM['Config']['signatures'][$Type]) {
        $CIDRAM['Deactivation']['Modified'] = true;
    }
};

/**
 * Prepares component extended description (front-end updates page).
 *
 * @param array $Arr Metadata of the component to be prepared.
 * @param string $Key A key to use to help find L10N data for the component description.
 */
$CIDRAM['PrepareExtendedDescription'] = function (array &$Arr, $Key = '') use (&$CIDRAM) {
    $Key = 'Extended Description ' . $Key;
    if (isset($CIDRAM['L10N']->Data[$Key])) {
        $Arr['Extended Description'] = $CIDRAM['L10N']->getString($Key);
    } elseif (empty($Arr['Extended Description'])) {
        $Arr['Extended Description'] = '';
    }
    if (is_array($Arr['Extended Description'])) {
        $CIDRAM['IsolateL10N']($Arr['Extended Description'], $CIDRAM['Config']['general']['lang']);
    }
    if (!empty($Arr['Used with']) && strpos($Arr['Extended Description'], 'signatures-&gt;') === false) {
        $Arr['Extended Description'] .=
            '<br /><em>' . $CIDRAM['L10N']->getString('label_used_with') .
            '<code>signatures-&gt;' . $Arr['Used with'] . '</code></em>';
    }
    if (!empty($Arr['False Positive Risk'])) {
        if ($Arr['False Positive Risk'] === 'Low') {
            $State = $CIDRAM['L10N']->getString('state_risk_low');
            $Class = 'txtGn';
        } elseif ($Arr['False Positive Risk'] === 'Medium') {
            $State = $CIDRAM['L10N']->getString('state_risk_medium');
            $Class = 'txtOe';
        } elseif ($Arr['False Positive Risk'] === 'High') {
            $State = $CIDRAM['L10N']->getString('state_risk_high');
            $Class = 'txtRd';
        } else {
            return;
        }
        $Arr['Extended Description'] .=
            '<br /><em>' . $CIDRAM['L10N']->getString('label_false_positive_risk') .
            '<span class="' . $Class . '">' . $State . '</span></em>';
    }
};

/**
 * Prepares component name (front-end updates page).
 *
 * @param array $Arr Metadata of the component to be prepared.
 * @param string $Key A key to use to help find L10N data for the component name.
 */
$CIDRAM['PrepareName'] = function (array &$Arr, $Key = '') use (&$CIDRAM) {
    $Key = 'Name ' . $Key;
    if (isset($CIDRAM['L10N']->Data[$Key])) {
        $Arr['Name'] = $CIDRAM['L10N']->getString($Key);
    } elseif (empty($Arr['Name'])) {
        $Arr['Name'] = '';
    }
    if (is_array($Arr['Name'])) {
        $CIDRAM['IsolateL10N']($Arr['Name'], $CIDRAM['Config']['general']['lang']);
    }
};

/**
 * Duplication avoidance (front-end updates page).
 *
 * @param string $Targets
 * @return bool Always false.
 */
$CIDRAM['ComponentFunctionUpdatePrep'] = function ($Targets) use (&$CIDRAM) {
    if (!empty($CIDRAM['Components']['Meta'][$Targets]['Files'])) {
        $CIDRAM['PrepareExtendedDescription']($CIDRAM['Components']['Meta'][$Targets]);
        $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$Targets]['Files']);
        $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$Targets]['Files']['To']);
        return $CIDRAM['IsInUse']($CIDRAM['Components']['Meta'][$Targets]);
    }
    return false;
};

/**
 * Simulates block event (used by the IP tracking and IP test pages).
 *
 * @param string $Addr The IP address to test against.
 * @param bool $Modules Specifies whether to test against modules.
 * @param bool $Aux Specifies whether to test against auxiliary rules.
 * @param bool $Verification Specifies whether to test against search engine and social media verification.
 */
$CIDRAM['SimulateBlockEvent'] = function ($Addr, $Modules = false, $Aux = false, $Verification = false) use (&$CIDRAM) {

    /** Reset bypass flags (needed to prevent falsing due to search engine verification). */
    $CIDRAM['ResetBypassFlags']();

    /** Reset "don't log" flag. */
    $CIDRAM['Flag Don\'t Log'] = false;

    /** Reset hostname (needed to prevent falsing due to repeat module calls involving hostname lookups). */
    $CIDRAM['Hostname'] = '';

    /** Populate BlockInfo. */
    $CIDRAM['BlockInfo'] = [
        'ID' => $CIDRAM['GenerateID'](),
        'IPAddr' => $Addr,
        'IPAddrResolved' => $CIDRAM['Resolve6to4']($Addr),
        'Query' => !empty($CIDRAM['FE']['custom-query']) ? $CIDRAM['FE']['custom-query'] : 'SimulateBlockEvent',
        'Referrer' => !empty($CIDRAM['FE']['custom-referrer']) ? $CIDRAM['FE']['custom-referrer'] : 'SimulateBlockEvent',
        'UA' => !empty($CIDRAM['FE']['custom-ua']) ? $CIDRAM['FE']['custom-ua'] : 'SimulateBlockEvent',
        'ReasonMessage' => '',
        'SignatureCount' => 0,
        'Signatures' => '',
        'WhyReason' => '',
        'xmlLang' => $CIDRAM['Config']['general']['lang'],
        'rURI' => 'SimulateBlockEvent'
    ];
    $CIDRAM['BlockInfo']['UALC'] = strtolower($CIDRAM['BlockInfo']['UA']);

    /** Appending query onto the reconstructed URI. */
    if (!empty($CIDRAM['FE']['custom-query'])) {
        $CIDRAM['BlockInfo']['rURI'] .= '?' . $CIDRAM['FE']['custom-query'];
    }

    /** Standard IP check. */
    try {
        $CIDRAM['Caught'] = false;
        $CIDRAM['TestResults'] = $CIDRAM['RunTests']($Addr);
    } catch (\Exception $e) {
        $CIDRAM['Caught'] = true;
    }

    /** Resolved IP check. */
    if ($CIDRAM['BlockInfo']['IPAddrResolved']) {
        if (!empty($CIDRAM['ThisIP']['IPAddress'])) {
            $CIDRAM['ThisIP']['IPAddress'] .= ' (' . $CIDRAM['BlockInfo']['IPAddrResolved'] . ')';
        }
        try {
            $CIDRAM['TestResults'] = ($CIDRAM['RunTests']($CIDRAM['BlockInfo']['IPAddrResolved']) || $CIDRAM['TestResults']);
        } catch (\Exception $e) {
            $CIDRAM['Caught'] = true;
        }
    }

    /** Instantiate report orchestrator (used by some modules). */
    $CIDRAM['Reporter'] = new \CIDRAM\Core\Reporter();

    /** Execute modules, if any have been enabled. */
    if ($Modules && $CIDRAM['Config']['signatures']['modules'] && empty($CIDRAM['Whitelisted'])) {
        $CIDRAM['InitialiseErrorHandler']();

        /** Explode module list and cycle through all modules. */
        $Modules = explode(',', $CIDRAM['Config']['signatures']['modules']);
        array_walk($Modules, function ($Module) use (&$CIDRAM) {
            $Module = (strpos($Module, ':') === false) ? $Module : substr($Module, strpos($Module, ':') + 1);
            $Infractions = $CIDRAM['BlockInfo']['SignatureCount'];
            if (file_exists($CIDRAM['Vault'] . $Module) && is_readable($CIDRAM['Vault'] . $Module)) {
                require $CIDRAM['Vault'] . $Module;
            }
        });

        $CIDRAM['ModuleErrors'] = $CIDRAM['Errors'];
        $CIDRAM['RestoreErrorHandler']();
    }

    /** Execute search engine verification. */
    if ($Verification && empty($CIDRAM['Whitelisted'])) {
        $CIDRAM['SearchEngineVerification']();
    }

    /** Execute social media verification. */
    if ($Verification && empty($CIDRAM['Whitelisted'])) {
        $CIDRAM['SocialMediaVerification']();
    }

    /** Process auxiliary rules, if any exist. */
    if ($Aux && empty($CIDRAM['Whitelisted'])) {
        $CIDRAM['InitialiseErrorHandler']();
        $CIDRAM['Aux']();
        $CIDRAM['AuxErrors'] = $CIDRAM['Errors'];
        $CIDRAM['RestoreErrorHandler']();
    }

    /**
     * Destroying the reporter (we won't process reports in this case, because we're only simulating block events,
     * as opposed to checking against actual, real requests; still needed to set it though to prevent errors).
     */
    unset($CIDRAM['Reporter']);

};

/**
 * Filter the available language options provided by the configuration page on
 * the basis of the availability of the corresponding language files.
 *
 * @param string $ChoiceKey Language code.
 * @return bool Valid/Invalid.
 */
$CIDRAM['FilterLang'] = function ($ChoiceKey) use (&$CIDRAM) {
    $Path = $CIDRAM['Vault'] . 'lang/lang.' . $ChoiceKey;
    return (file_exists($Path . '.yaml') && file_exists($Path . '.fe.yaml'));
};

/**
 * Filter the available hash algorithms provided by the configuration page on
 * the basis of their availability.
 *
 * @param string $ChoiceKey Hash algorithm.
 * @return bool Valid/Invalid.
 */
$CIDRAM['FilterAlgo'] = function ($ChoiceKey) use (&$CIDRAM) {
    if ($ChoiceKey === 'PASSWORD_ARGON2I') {
        return $CIDRAM['VersionCompare'](PHP_VERSION, '7.2.0RC1');
    }
    if ($ChoiceKey === 'PASSWORD_ARGON2ID') {
        return $CIDRAM['VersionCompare'](PHP_VERSION, '7.3.0');
    }
    return true;
};

/**
 * Filter the available theme options provided by the configuration page on
 * the basis of their availability.
 *
 * @param string $ChoiceKey Theme ID.
 * @return bool Valid/Invalid.
 */
$CIDRAM['FilterTheme'] = function ($ChoiceKey) use (&$CIDRAM) {
    if ($ChoiceKey === 'default') {
        return true;
    }
    $Path = $CIDRAM['Vault'] . 'fe_assets/' . $ChoiceKey . '/';
    return (file_exists($Path . 'frontend.css') || file_exists($CIDRAM['Vault'] . 'template_' . $ChoiceKey . '.html'));
};

/**
 * Attempt to perform some simple formatting for the log data.
 *
 * @param string $In The log data to be formatted.
 * @param string $BlockLink Used as the basis for links inserted into displayed log data used for searching related log data.
 * @param string $Current The current search query (if it exists). Used to avoid inserting unnecessary links.
 * @param string $FieldSeparator Used to distinguish between a field's name and its content.
 * @param bool $Flags Tells the formatter whether the flags CSS file is available.
 */
$CIDRAM['Formatter'] = function (&$In, $BlockLink = '', $Current = '', $FieldSeparator = ': ', $Flags = false) {
    static $MaxBlockSize = 65536;
    if (strpos($In, "<br />\n") === false) {
        $In = '<div class="hB hFd s">' . $In . '</div>';
        return;
    }
    $Out = '';
    $In = "\n" . $In;
    $Len = strlen($In);
    $Caret = 0;
    $BlockSeparator = (strpos($In, "<br />\n<br />\n") !== false) ? "<br />\n<br />\n" : "<br />\n";
    $BlockSeparatorLen = strlen($BlockSeparator);
    $Demojibakefier = new \Maikuolan\Common\Demojibakefier();
    while ($Caret < $Len) {
        $Remainder = $Len - $Caret;
        if ($Remainder < $MaxBlockSize && $Remainder < ini_get('pcre.backtrack_limit')) {
            $Section = substr($In, $Caret) . $BlockSeparator;
            $Caret = $Len;
        } else {
            $SectionLen = strrpos(substr($In, $Caret, $MaxBlockSize), $BlockSeparator);
            $Section = substr($In, $Caret, $SectionLen) . $BlockSeparator;
            $Caret += $SectionLen;
        }
        preg_match_all('~(&lt;\?(?:(?!&lt;\?)[^\n])+\?&gt;|<\?(?:(?!<\?)[^\n])+\?>|\{\?(?:(?!\{\?)[^\n])+\?\})~i', $Section, $Parts);
        foreach ($Parts[0] as $ThisPart) {
            if (strlen($ThisPart) > 512 || strpos($ThisPart, "\n") !== false) {
                continue;
            }
            $Section = str_replace($ThisPart, '<code>' . $ThisPart . '</code>', $Section);
        }
        if (strpos($Section, "<br />\n<br />\n") !== false) {
            preg_match_all('~\n((?!：)[^\n:]+)' . $FieldSeparator . '((?:(?!<br />)[^\n])+)~i', $Section, $Parts);
            if (count($Parts[1])) {
                $Parts[1] = array_unique($Parts[1]);
                foreach ($Parts[1] as $ThisPart) {
                    $Section = str_replace(
                        "\n" . $ThisPart . $FieldSeparator,
                        "\n<span class=\"textLabel\">" . $ThisPart . '</span>' . $FieldSeparator,
                        $Section
                    );
                }
            }
            if (count($Parts[2]) && $BlockSeparatorLen === 14) {
                $Parts[2] = array_unique($Parts[2]);
                foreach ($Parts[2] as $ThisPart) {
                    $ThisPartUnsafe = str_replace(['&gt;', '&lt;'], ['>', '<'], $ThisPart);
                    $TestString = $Demojibakefier->guard($ThisPartUnsafe);
                    $Alternate = (
                        $TestString !== $ThisPartUnsafe && $Demojibakefier->Last
                    ) ? '<code dir="ltr">🔁' . $Demojibakefier->Last . '➡️UTF-8' . $FieldSeparator . '</code>' . str_replace(['<', '>'], ['&lt;', '&gt;'], $TestString) . "<br />\n" : '';
                    if (!$ThisPart || $ThisPart === $Current) {
                        $Section = str_replace(
                            $FieldSeparator . $ThisPart . "<br />\n",
                            $FieldSeparator . $ThisPart . "<br />\n" . $Alternate,
                            $Section
                        );
                        continue;
                    }
                    $Enc = str_replace('=', '_', base64_encode($ThisPart));
                    $Section = str_replace(
                        $FieldSeparator . $ThisPart . "<br />\n",
                        $FieldSeparator . $ThisPart . ' <a href="' . $BlockLink . '&search=' . $Enc . '">»</a>' . "<br />\n" . $Alternate,
                        $Section
                    );
                }
            }
            preg_match_all('~\n((?:(?!：)[^\n:]+)' . $FieldSeparator . '(?:(?!<br />)[^\n])+)~i', $Section, $Parts);
            if (count($Parts[1])) {
                foreach ($Parts[1] as $ThisPart) {
                    $Section = str_replace("\n" . $ThisPart . "<br />\n", "\n<span class=\"s\">" . $ThisPart . "</span><br />\n", $Section);
                }
            }
            preg_match_all('~\("([^()"]+)", L~', $Section, $Parts);
            if (count($Parts[1])) {
                $Parts[1] = array_unique($Parts[1]);
                foreach ($Parts[1] as $ThisPart) {
                    $Section = str_replace(
                        '("' . $ThisPart . '", L',
                        '("<a href="' . $BlockLink . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '">' . $ThisPart . '</a>", L',
                        $Section
                    );
                }
            }
            preg_match_all('~\[([A-Z]{2})\]~', $Section, $Parts);
            if (count($Parts[1])) {
                if ($Flags) {
                    $OuterOpen = '';
                    $OuterClose = '';
                    $InnerOpen = '<span class="flag ';
                    $InnerClose = '"><span></span></span>';
                } else {
                    $OuterOpen = '[';
                    $OuterClose = ']';
                    $InnerOpen = '';
                    $InnerClose = '';
                }
                foreach ($Parts[1] as $ThisPart) {
                    $Section = str_replace(
                        '[' . $ThisPart . ']',
                        $OuterOpen . '<a href="' . $BlockLink . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '">' . $InnerOpen . $ThisPart . $InnerClose . '</a>' . $OuterClose,
                        $Section
                    );
                }
            }
        }
        $Out .= substr($Section, 0, $BlockSeparatorLen * -1);
    }
    $Out = substr($Out, 1);
    $In = '';
    $BlockStart = 0;
    $BlockEnd = 0;
    while ($BlockEnd !== false) {
        $Darken = empty($Darken);
        $Style = '<div class="h' . ($Darken ? 'B' : 'W') . ' hFd fW">';
        $BlockEnd = strpos($Out, $BlockSeparator, $BlockStart);
        $In .= $Style . substr($Out, $BlockStart, $BlockEnd - $BlockStart + $BlockSeparatorLen) . '</div>';
        $BlockStart = $BlockEnd + $BlockSeparatorLen;
    }
    $In = str_replace("<br />\n</div>", "<br /></div>\n", $In);
};

/**
 * Attempt to tally log data.
 *
 * @param string $In The log data to be tallied.
 * @param string $BlockLink Used as the basis for links inserted into displayed log data used for searching related log data.
 * @param array $Exclusions Instructs which fields should be excluded from the tally.
 * @return string A tally of the log data (or an empty string when valid log data isn't supplied).
 */
$CIDRAM['Tally'] = function ($In, $BlockLink, array $Exclusions = []) use (&$CIDRAM) {
    if (empty($In)) {
        return '';
    }
    $Data = [];
    $PosA = $PosB = 0;
    while (($PosB = strpos($In, "\n", $PosA)) !== false) {
        $Line = substr($In, $PosA, $PosB - $PosA);
        $PosA = $PosB + 1;
        if (empty($Line)) {
            continue;
        }
        foreach ($Exclusions as $Exclusion) {
            $Len = strlen($Exclusion);
            if (substr($Line, 0, $Len) === $Exclusion) {
                continue 2;
            }
        }
        $Separator = (strpos($Line, '：') !== false) ? '：' : ': ';
        if (($SeparatorPos = strpos($Line, $Separator)) === false) {
            continue;
        }
        $Field = trim(substr($Line, 0, $SeparatorPos));
        $Entry = trim(substr($Line, $SeparatorPos + strlen($Separator)));
        if (!isset($Data[$Field])) {
            $Data[$Field] = [];
        }
        if (!isset($Data[$Field][$Entry])) {
            $Data[$Field][$Entry] = 0;
        }
        $Data[$Field][$Entry]++;
    }
    $Data['Origin'] = [];
    for ($A = 65; $A < 91; $A++) {
        for ($B = 65; $B < 91; $B++) {
            $Code = '[' . chr($A) . chr($B) . ']';
            if ($Count = substr_count($In, $Code)) {
                $Data['Origin'][$Code] = $Count;
            }
        }
    }
    if (empty($Data['Origin'])) {
        unset($Data['Origin']);
    }
    $Out = '<table>';
    foreach ($Data as $Field => $Entries) {
        $Out .= '<tr><td class="h2f" colspan="2"><div class="s">' . $Field . "</div></td></tr>\n";
        arsort($Entries, SORT_NUMERIC);
        foreach ($Entries as $Entry => $Count) {
            if (!(substr($Entry, 0, 1) === '[' && substr($Entry, 3, 1) === ']')) {
                $Entry .= ' <a href="' . $BlockLink . '&search=' . str_replace('=', '_', base64_encode($Entry)) . '">»</a>';
            }
            preg_match_all('~\("([^()"]+)", L~', $Entry, $Parts);
            if (count($Parts[1])) {
                foreach ($Parts[1] as $ThisPart) {
                    $Entry = str_replace(
                        '("' . $ThisPart . '", L',
                        '("<a href="' . $BlockLink . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '">' . $ThisPart . '</a>", L',
                        $Entry
                    );
                }
            }
            preg_match_all('~\[([A-Z]{2})\]~', $Entry, $Parts);
            if (count($Parts[1])) {
                foreach ($Parts[1] as $ThisPart) {
                    $Entry = str_replace('[' . $ThisPart . ']', $CIDRAM['FE']['Flags'] ? (
                        '<a href="' . $BlockLink . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '"><span class="flag ' . $ThisPart . '"></span></a>'
                    ) : (
                        '[<a href="' . $BlockLink . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '">' . $ThisPart . '</a>]'
                    ), $Entry);
                }
            }
            $Percent = $CIDRAM['FE']['EntryCount'] ? ($Count / $CIDRAM['FE']['EntryCount']) * 100 : 0;
            $Out .= '<tr><td class="h1 fW">' . $Entry . '</td><td class="h1f"><div class="s">' . $CIDRAM['NumberFormatter']->format($Count) . ' (' . $CIDRAM['NumberFormatter']->format($Percent, 2) . "%)</div></td></tr>\n";
        }
    }
    $Out .= '</table>';
    return $Out;
};

/**
 * Get the appropriate path for a specified asset as per the defined theme.
 *
 * @param string $Asset The asset filename.
 * @param bool $CanFail Is failure acceptable? (Default: False)
 * @throws Exception if the asset can't be found.
 * @return string The asset path.
 */
$CIDRAM['GetAssetPath'] = function ($Asset, $CanFail = false) use (&$CIDRAM) {
    if (
        $CIDRAM['Config']['template_data']['theme'] !== 'default' &&
        file_exists($CIDRAM['Vault'] . 'fe_assets/' . $CIDRAM['Config']['template_data']['theme'] . '/' . $Asset)
    ) {
        return $CIDRAM['Vault'] . 'fe_assets/' . $CIDRAM['Config']['template_data']['theme'] . '/' . $Asset;
    }
    if (file_exists($CIDRAM['Vault'] . 'fe_assets/' . $Asset)) {
        return $CIDRAM['Vault'] . 'fe_assets/' . $Asset;
    }
    if ($CanFail) {
        return '';
    }
    throw new \Exception('Asset not found');
};

/**
 * Executes a list of closures or commands when specific conditions are met.
 *
 * @param string|array $Closures The list of closures or commands to execute.
 * @param bool $Queue Whether to queue the operation or perform immediately.
 */
$CIDRAM['FE_Executor'] = function ($Closures = false, $Queue = false) use (&$CIDRAM) {
    if ($Queue && $Closures !== false) {
        if (empty($CIDRAM['FE_Executor_Queue'])) {
            $CIDRAM['FE_Executor_Queue'] = [];
        }
        $CIDRAM['FE_Executor_Queue'][] = $Closures;
        return;
    }
    if ($Closures === false && !empty($CIDRAM['FE_Executor_Queue'])) {
        foreach ($CIDRAM['FE_Executor_Queue'] as $QueueItem) {
            $CIDRAM['FE_Executor']($QueueItem);
        }
        $CIDRAM['FE_Executor_Queue'] = [];
        return;
    }
    $CIDRAM['Arrayify']($Closures);
    foreach ($Closures as $Closure) {
        if (isset($CIDRAM[$Closure]) && is_object($CIDRAM[$Closure])) {
            $CIDRAM[$Closure]();
        } elseif (($Pos = strpos($Closure, ' ')) !== false) {
            $Params = substr($Closure, $Pos + 1);
            $Closure = substr($Closure, 0, $Pos);
            if (isset($CIDRAM[$Closure]) && is_object($CIDRAM[$Closure])) {
                $CIDRAM[$Closure]($Params);
            }
        }
    }
};

/**
 * Updates plugin version cited in the WordPress plugins dashboard, if this
 * copy of CIDRAM is running as a WordPress plugin.
 */
$CIDRAM['WP-Ver'] = function () use (&$CIDRAM) {
    if (
        !empty($CIDRAM['Components']['RemoteMeta']['CIDRAM']['Version']) &&
        ($ThisData = $CIDRAM['Updater-IO']->readFile($CIDRAM['Vault'] . '../cidram.php'))
    ) {
        $PlugHead = "\x3C\x3Fphp\n/**\n * Plugin Name: CIDRAM\n * Version: ";
        if (substr($ThisData, 0, 45) === $PlugHead) {
            $PlugHeadEnd = strpos($ThisData, "\n", 45);
            $CIDRAM['Updater-IO']->writeFile(
                $CIDRAM['Vault'] . '../cidram.php',
                $PlugHead . $CIDRAM['Components']['RemoteMeta']['CIDRAM Core']['Version'] . substr($ThisData, $PlugHeadEnd)
            );
        }
    }
};

/** Used to format numbers according to the specified configuration. */
$CIDRAM['NumberFormatter'] = new \Maikuolan\Common\NumberFormatter($CIDRAM['Config']['general']['numbers']);

/** Used by the Number_L10N_JS closure (separated out to improve memory footprint). */
$CIDRAM['Number_L10N_JS_Sets'] = [
    'NoSep-1' => ['.', '', 3, 'return l10nd', 1],
    'NoSep-2' => [',', '', 3, 'return l10nd', 1],
    'Latin-1' => ['.', ',', 3, 'return l10nd', 1],
    'Latin-2' => ['.', ' ', 3, 'return l10nd', 1],
    'Latin-3' => [',', '.', 3, 'return l10nd', 1],
    'Latin-4' => [',', ' ', 3, 'return l10nd', 1],
    'Latin-5' => ['·', ',', 3, 'return l10nd', 1],
    'China-1' => ['.', ',', 4, 'return l10nd', 1],
    'India-1' => ['.', ',', 2, 'return l10nd', 0],
    'India-2' => ['.', ',', 2, 'var nls=[\'०\',\'१\',\'२\',\'३\',\'४\',\'५\',\'६\',\'७\',\'८\',\'९\'];return nls[l10nd]||l10nd', 0],
    'India-3' => ['.', ',', 2, 'var nls=[\'૦\',\'૧\',\'૨\',\'૩\',\'૪\',\'૫\',\'૬\',\'૭\',\'૮\',\'૯\'];return nls[l10nd]||l10nd', 0],
    'India-4' => ['.', ',', 2, 'var nls=[\'੦\',\'੧\',\'੨\',\'੩\',\'੪\',\'੫\',\'੬\',\'੭\',\'੮\',\'੯\'];return nls[l10nd]||l10nd', 0],
    'India-5' => ['.', ',', 2, 'var nls=[\'೦\',\'೧\',\'೨\',\'೩\',\'೪\',\'೫\',\'೬\',\'೭\',\'೮\',\'೯\'];return nls[l10nd]||l10nd', 0],
    'India-6' => ['.', ',', 2, 'var nls=[\'౦\',\'౧\',\'౨\',\'౩\',\'౪\',\'౫\',\'౬\',\'౭\',\'౮\',\'౯\'];return nls[l10nd]||l10nd', 0],
    'Arabic-1' => ['٫', '', 3, 'var nls=[\'٠\',\'١\',\'٢\',\'٣\',\'٤\',\'٥\',\'٦\',\'٧\',\'٨\',\'٩\'];return nls[l10nd]||l10nd', 1],
    'Arabic-2' => ['٫', '٬', 3, 'var nls=[\'٠\',\'١\',\'٢\',\'٣\',\'٤\',\'٥\',\'٦\',\'٧\',\'٨\',\'٩\'];return nls[l10nd]||l10nd', 1],
    'Arabic-3' => ['٫', '٬', 3, 'var nls=[\'۰\',\'۱\',\'۲\',\'۳\',\'۴\',\'۵\',\'۶\',\'۷\',\'۸\',\'۹\'];return nls[l10nd]||l10nd', 1],
    'Arabic-4' => ['٫', '٬', 2, 'var nls=[\'۰\',\'۱\',\'۲\',\'۳\',\'۴\',\'۵\',\'۶\',\'۷\',\'۸\',\'۹\'];return nls[l10nd]||l10nd', 0],
    'Bengali-1' => ['.', ',', 2, 'var nls=[\'০\',\'১\',\'২\',\'৩\',\'৪\',\'৫\',\'৬\',\'৭\',\'৮\',\'৯\'];return nls[l10nd]||l10nd', 0],
    'Burmese-1' => ['.', '', 3, 'var nls=[\'၀\',\'၁\',\'၂\',\'၃\',\'၄\',\'၅\',\'၆\',\'၇\',\'၈\',\'၉\'];return nls[l10nd]||l10nd', 1],
    'Khmer-1' => [',', '.', 3, 'var nls=[\'០\',\'១\',\'២\',\'៣\',\'៤\',\'៥\',\'៦\',\'៧\',\'៨\',\'៩\'];return nls[l10nd]||l10nd', 1],
    'Lao-1' => ['.', '', 3, 'var nls=[\'໐\',\'໑\',\'໒\',\'໓\',\'໔\',\'໕\',\'໖\',\'໗\',\'໘\',\'໙\'];return nls[l10nd]||l10nd', 1],
    'Thai-1' => ['.', ',', 3, 'var nls=[\'๐\',\'๑\',\'๒\',\'๓\',\'๔\',\'๕\',\'๖\',\'๗\',\'๘\',\'๙\'];return nls[l10nd]||l10nd', 1],
    'Thai-2' => ['.', '', 3, 'var nls=[\'๐\',\'๑\',\'๒\',\'๓\',\'๔\',\'๕\',\'๖\',\'๗\',\'๘\',\'๙\'];return nls[l10nd]||l10nd', 1],
];

/**
 * Generates JavaScript code for localising numbers locally.
 *
 * @return string The JavaScript code.
 */
$CIDRAM['Number_L10N_JS'] = function () use (&$CIDRAM) {
    $Base =
        'function l10nn(l10nd){%4$s};function nft(r){var x=r.indexOf(\'.\')!=-1?' .
        '\'%1$s\'+r.replace(/^.*\./gi,\'\'):\'\',n=r.replace(/\..*$/gi,\'\').rep' .
        'lace(/[^0-9]/gi,\'\'),t=n.length;for(e=\'\',b=%5$d,i=1;i<=t;i++){b>%3$d' .
        '&&(b=1,e=\'%2$s\'+e);var e=l10nn(n.substring(t-i,t-(i-1)))+e;b++}var t=' .
        'x.length;for(y=\'\',b=1,i=1;i<=t;i++){var y=l10nn(x.substring(t-i,t-(i-' .
        '1)))+y}return e+y}';
    if (
        !empty($CIDRAM['Config']['general']['numbers']) &&
        isset($CIDRAM['Number_L10N_JS_Sets'][$CIDRAM['Config']['general']['numbers']])
    ) {
        $Set = $CIDRAM['Number_L10N_JS_Sets'][$CIDRAM['Config']['general']['numbers']];
    } else {
        $Set = $CIDRAM['Number_L10N_JS_Sets']['Latin-1'];
    }
    return sprintf($Base, $Set[0], $Set[1], $Set[2], $Set[3], $Set[4]);
};

/**
 * Switch control for front-end page filters.
 *
 * @param array $Switches Names of available switches.
 * @param string $Selector Switch selector variable.
 * @param bool $StateModified Determines whether the filter state has been modified.
 * @param string $Redirect Reconstructed path to redirect to when the state changes.
 * @param string $Options Reconstructed filter controls.
 */
$CIDRAM['FilterSwitch'] = function (array $Switches, $Selector, &$StateModified, &$Redirect, &$Options) use (&$CIDRAM) {
    foreach ($Switches as $Switch) {
        $State = (!empty($Selector) && $Selector === $Switch);
        $CIDRAM['FE'][$Switch] = empty($CIDRAM['QueryVars'][$Switch]) ? false : (
            ($CIDRAM['QueryVars'][$Switch] === 'true' && !$State) ||
            ($CIDRAM['QueryVars'][$Switch] !== 'true' && $State)
        );
        if ($State) {
            $StateModified = true;
        }
        if ($CIDRAM['FE'][$Switch]) {
            $Redirect .= '&' . $Switch . '=true';
            $LangItem = 'switch-' . $Switch . '-set-false';
        } else {
            $Redirect .= '&' . $Switch . '=false';
            $LangItem = 'switch-' . $Switch . '-set-true';
        }
        $Label = $CIDRAM['L10N']->getString($LangItem) ?: $LangItem;
        $Options .= '<option value="' . $Switch . '">' . $Label . '</option>';
    }
};

/**
 * Appends test data onto component metadata.
 *
 * @param array $Component The component to append test data onto.
 * @param bool $ReturnState Whether to operate as a function or a procedure.
 * @return bool|null Indicates whether tests have passed, when operating as a function.
 */
$CIDRAM['AppendTests'] = function (array &$Component, $ReturnState = false) use (&$CIDRAM) {
    $TestData = $CIDRAM['FECacheGet'](
        $CIDRAM['FE']['Cache'],
        $CIDRAM['Components']['RemoteMeta'][$Component['ID']]['Tests']
    );
    if (!$TestData) {
        $TestData = $CIDRAM['Request'](
            $CIDRAM['Components']['RemoteMeta'][$Component['ID']]['Tests']
        ) ?: '-';
        $CIDRAM['FECacheAdd'](
            $CIDRAM['FE']['Cache'],
            $CIDRAM['FE']['Rebuild'],
            $CIDRAM['Components']['RemoteMeta'][$Component['ID']]['Tests'],
            $TestData,
            $CIDRAM['Now'] + 1800
        );
    }
    if (substr($TestData, 0, 1) === '{' && substr($TestData, -1) === '}') {
        $TestData = json_decode($TestData, true, 5);
    }
    if (!empty($TestData['statuses']) && is_array($TestData['statuses'])) {
        $TestsTotal = 0;
        $TestsPassed = 0;
        $TestDetails = '';
        foreach ($TestData['statuses'] as $ThisStatus) {
            if (empty($ThisStatus['context']) || empty($ThisStatus['state'])) {
                continue;
            }
            $TestsTotal++;
            $StatusHead = '';
            if ($ThisStatus['state'] === 'success') {
                if ($TestsPassed !== '?') {
                    $TestsPassed++;
                }
                $StatusHead .= '<span class="txtGn">✔️ ';
            } elseif ($ThisStatus['state'] === 'pending') {
                $TestsPassed = '?';
                $StatusHead .= '<span class="txtOe">❓ ';
            } else {
                if ($ReturnState) {
                    return false;
                }
                $StatusHead .= '<span class="txtRd">❌ ';
            }
            $StatusHead .= empty($ThisStatus['target_url']) ? $ThisStatus['context'] : (
                '<a href="' . $ThisStatus['target_url'] . '">' . $ThisStatus['context'] . '</a>'
            );
            if (!$ReturnState) {
                $CIDRAM['AppendToString']($TestDetails, '<br />', $StatusHead . '</span>');
            }
        }
        if (!$ReturnState) {
            if ($TestsTotal === $TestsPassed) {
                $TestClr = 'txtGn';
            } else {
                $TestClr = ($TestsPassed === '?' || $TestsPassed >= ($TestsTotal / 2)) ? 'txtOe' : 'txtRd';
            }
            $TestsTotal = sprintf(
                '<span class="%1$s">%2$s/%3$s</span><br /><span id="%4$s-showtests">' .
                '<input class="auto" type="button" onclick="javascript:showid(\'%4$s-tests\');hideid(\'%4$s-showtests\');showid(\'%4$s-hidetests\')" value="+" />' .
                '</span><span id="%4$s-hidetests" style="display:none">' .
                '<input class="auto" type="button" onclick="javascript:hideid(\'%4$s-tests\');showid(\'%4$s-showtests\');hideid(\'%4$s-hidetests\')" value="-" />' .
                '</span><span id="%4$s-tests" style="display:none"><br />%5$s</span>',
                $TestClr,
                ($TestsPassed === '?' ? '?' : $CIDRAM['NumberFormatter']->format($TestsPassed)),
                $CIDRAM['NumberFormatter']->format($TestsTotal),
                $Component['ID'],
                $TestDetails
            );
            $CIDRAM['AppendToString'](
                $Component['StatusOptions'],
                '<hr />',
                '<div class="s">' . $CIDRAM['L10N']->getString('label_tests') . ' ' . $TestsTotal
            );
        }
    }
    if ($ReturnState) {
        return true;
    }
};

/**
 * Traversal detection.
 *
 * @param string $Path The path to check for traversal.
 * @return bool True when the path is traversal-free. False when traversal has been detected.
 */
$CIDRAM['Traverse'] = function ($Path) {
    return !preg_match('~(?:[\./]{2}|[\x01-\x1f\[-^`?*$])~i', str_replace("\\", '/', $Path));
};

/**
 * Custom sort an array by key and then implode the results.
 *
 * @param array $Arr The array to sort.
 * @return string The sorted, imploded array.
 */
$CIDRAM['UpdatesSortFunc'] = function (array $Arr) use (&$CIDRAM) {
    $Type = isset($CIDRAM['FE']['sort-by-name']) ? $CIDRAM['FE']['sort-by-name'] : false;
    $Order = isset($CIDRAM['FE']['descending-order']) ? $CIDRAM['FE']['descending-order'] : false;
    uksort($Arr, function ($A, $B) use ($Type, $Order) {
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
};

/**
 * Updates handler.
 *
 * @param string $Action The action to perform (update/install, verify,
 *      uninstall, activate, deactivate).
 * @return string|array The ID (or IDs) of the component (or components) to
 *      perform the specified action upon.
 */
$CIDRAM['UpdatesHandler'] = function ($Action, $ID = '') use (&$CIDRAM) {

    /** Support for executor calls. */
    if (empty($ID) && ($Pos = strpos($Action, ' ')) !== false) {
        $ID = substr($Action, $Pos + 1);
        $Action = trim(substr($Action, 0, $Pos));
        $ID = (strpos($ID, ',') === false) ? trim($ID) : array_map('trim', explode(',', $ID));
    }

    /** Update a component. */
    if ($Action === 'update-component') {
        $CIDRAM['UpdatesHandler-Update']($ID);
    }

    /** Update (or install) and activate a component (one-step solution). */
    if (!is_array($ID) && $Action === 'update-and-activate-component') {
        $CIDRAM['UpdatesHandler-Update']($ID);
        $CIDRAM['UpdatesHandler-Activate']($ID);
    }

    /** Repair a component. */
    if ($Action === 'repair-component') {
        $CIDRAM['UpdatesHandler-Repair']($ID);
    }

    /** Verify a component. */
    if ($Action === 'verify-component') {
        $CIDRAM['UpdatesHandler-Verify']($ID);
    }

    /** Uninstall a component. */
    if (!is_array($ID) && $Action === 'uninstall-component') {
        $CIDRAM['UpdatesHandler-Uninstall']($ID);
    }

    /** Activate a component. */
    if (!is_array($ID) && $Action === 'activate-component') {
        $CIDRAM['UpdatesHandler-Activate']($ID);
    }

    /** Deactivate a component. */
    if (!is_array($ID) && $Action === 'deactivate-component') {
        $CIDRAM['UpdatesHandler-Deactivate']($ID);
    }

    /** Deactivate and uninstall a component (one-step solution). */
    if (!is_array($ID) && $Action === 'deactivate-and-uninstall-component') {
        $CIDRAM['UpdatesHandler-Deactivate']($ID);
        $CIDRAM['UpdatesHandler-Uninstall']($ID);
    }

    /** Process and empty executor queue. */
    $CIDRAM['FE_Executor']();

};

/**
 * Updates handler: Update a component.
 *
 * @param string|array $ID The ID (or array of IDs) of the component(/s) to update.
 */
$CIDRAM['UpdatesHandler-Update'] = function ($ID) use (&$CIDRAM) {
    $CIDRAM['Arrayify']($ID);
    $Congruents = [];
    foreach ($ID as $ThisTarget) {
        if (!isset(
            $CIDRAM['Components']['Meta'][$ThisTarget]['Remote'],
            $CIDRAM['Components']['Meta'][$ThisTarget]['Reannotate']
        )) {
            continue;
        }
        $BytesAdded = 0;
        $BytesRemoved = 0;
        $TimeRequired = microtime(true);
        if ($Reactivate = $CIDRAM['IsInUse']($CIDRAM['Components']['Meta'][$ThisTarget])) {
            $CIDRAM['UpdatesHandler-Deactivate']($ThisTarget);
        }
        $CIDRAM['Components']['RemoteMeta'] = [];
        $CIDRAM['Components']['Meta'][$ThisTarget]['RemoteData'] = '';
        $CIDRAM['FetchRemote-ContextFree'](
            $CIDRAM['Components']['Meta'][$ThisTarget]['RemoteData'],
            $CIDRAM['Components']['Meta'][$ThisTarget]['Remote']
        );
        $UpdateFailed = false;
        if (
            substr($CIDRAM['Components']['Meta'][$ThisTarget]['RemoteData'], 0, 4) === "---\n" &&
            ($CIDRAM['Components']['EoYAML'] = strpos(
                $CIDRAM['Components']['Meta'][$ThisTarget]['RemoteData'], "\n\n"
            )) !== false &&
            $CIDRAM['YAML']->process(
                substr($CIDRAM['Components']['Meta'][$ThisTarget]['RemoteData'], 4, $CIDRAM['Components']['EoYAML'] - 4),
                $CIDRAM['Components']['RemoteMeta']
            ) &&
            !empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Minimum Required']) &&
            !$CIDRAM['VersionCompare'](
                $CIDRAM['ScriptVersion'],
                $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Minimum Required']
            ) &&
            (
                empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Minimum Required PHP']) ||
                !$CIDRAM['VersionCompare'](PHP_VERSION, $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Minimum Required PHP'])
            ) &&
            !empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From']) &&
            !empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To']) &&
            !empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Reannotate']) &&
            $CIDRAM['Traverse']($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Reannotate']) &&
            ($ThisReannotate = $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Reannotate']) &&
            file_exists($CIDRAM['Vault'] . $ThisReannotate) &&
            ($OldMeta = $CIDRAM['Updater-IO']->readFile($CIDRAM['Vault'] . $ThisReannotate)) &&
            preg_match("~(\n" . preg_quote($ThisTarget) . ":?)(\n [^\n]*)*\n~i", $OldMeta, $OldMetaMatches) &&
            ($OldMetaMatches = $OldMetaMatches[0]) &&
            ($NewMeta = $CIDRAM['Components']['Meta'][$ThisTarget]['RemoteData']) &&
            preg_match("~(\n" . preg_quote($ThisTarget) . ":?)(\n [^\n]*)*\n~i", $NewMeta, $NewMetaMatches) &&
            ($NewMetaMatches = $NewMetaMatches[0]) &&
            (!$CIDRAM['FE']['CronMode'] || empty(
                $CIDRAM['Components']['Meta'][$ThisTarget]['Tests']
            ) || $CIDRAM['AppendTests']($CIDRAM['Components']['Meta'][$ThisTarget], true))
        ) {
            $Congruents[$ThisReannotate] = $NewMeta;
            $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']);
            $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From']);
            $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To']);
            if (!empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum'])) {
                $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum']);
            }
            $NewMeta = str_replace($OldMetaMatches, $NewMetaMatches, $OldMeta);
            $Count = count($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From']);
            $CIDRAM['RemoteFiles'] = [];
            $CIDRAM['IgnoredFiles'] = [];
            $Rollback = false;
            /** Write new and updated files and directories. */
            for ($Iterate = 0; $Iterate < $Count; $Iterate++) {
                if (empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'][$Iterate])) {
                    continue;
                }
                $ThisFileName = $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'][$Iterate];
                /** Rolls back to previous version or uninstalls if an update/install fails. */
                if ($Rollback) {
                    if (
                        isset($CIDRAM['RemoteFiles'][$ThisFileName]) &&
                        !isset($CIDRAM['IgnoredFiles'][$ThisFileName]) &&
                        is_readable($CIDRAM['Vault'] . $ThisFileName)
                    ) {
                        $BytesAdded -= filesize($CIDRAM['Vault'] . $ThisFileName);
                        unlink($CIDRAM['Vault'] . $ThisFileName);
                        if (is_readable($CIDRAM['Vault'] . $ThisFileName . '.rollback')) {
                            $BytesRemoved -= filesize($CIDRAM['Vault'] . $ThisFileName . '.rollback');
                            rename($CIDRAM['Vault'] . $ThisFileName . '.rollback', $CIDRAM['Vault'] . $ThisFileName);
                        }
                    }
                    continue;
                }
                if (
                    !empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum'][$Iterate]) &&
                    !empty($CIDRAM['Components']['Meta'][$ThisTarget]['Files']['Checksum'][$Iterate]) && (
                        $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum'][$Iterate] ===
                        $CIDRAM['Components']['Meta'][$ThisTarget]['Files']['Checksum'][$Iterate]
                    )
                ) {
                    $CIDRAM['IgnoredFiles'][$ThisFileName] = true;
                    continue;
                }
                if (
                    empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From'][$Iterate]) ||
                    !($ThisFile = $CIDRAM['Request'](
                        $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From'][$Iterate]
                    ))
                ) {
                    $Iterate = 0;
                    $Rollback = true;
                    continue;
                }
                if (
                    strtolower(substr(
                        $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From'][$Iterate], -2
                    )) === 'gz' &&
                    strtolower(substr($ThisFileName, -2)) !== 'gz' &&
                    substr($ThisFile, 0, 2) === "\x1f\x8b"
                ) {
                    $ThisFile = gzdecode($ThisFile);
                }
                if (!empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum'][$Iterate])) {
                    $ThisChecksum = $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum'][$Iterate];
                    $ThisLen = strlen($ThisFile);
                    if (
                        (md5($ThisFile) . ':' . $ThisLen) !== $ThisChecksum &&
                        (sha1($ThisFile) . ':' . $ThisLen) !== $ThisChecksum &&
                        (hash('sha256', $ThisFile) . ':' . $ThisLen) !== $ThisChecksum
                    ) {
                        $CIDRAM['FE']['state_msg'] .=
                            '<code>' . $ThisTarget . '</code> – ' .
                            '<code>' . $ThisFileName . '</code> – ' .
                            $CIDRAM['L10N']->getString('response_checksum_error') . '<br />';
                        if (!empty($CIDRAM['Components']['Meta'][$ThisTarget]['On Checksum Error'])) {
                            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ThisTarget]['On Checksum Error'], true);
                        }
                        $Iterate = 0;
                        $Rollback = true;
                        continue;
                    }
                }
                if (
                    preg_match('~\.(?:css|dat|gif|inc|jpe?g|php|png|ya?ml|[a-z]{0,2}db)$~i', $ThisFileName) &&
                    !$CIDRAM['SanityCheck']($ThisFileName, $ThisFile)
                ) {
                    $CIDRAM['FE']['state_msg'] .= sprintf(
                        '<code>%s</code> – <code>%s</code> – %s<br />',
                        $ThisTarget,
                        $ThisFileName,
                        $CIDRAM['L10N']->getString('response_sanity_1')
                    );
                    if (!empty($CIDRAM['Components']['Meta'][$ThisTarget]['On Sanity Error'])) {
                        $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ThisTarget]['On Sanity Error'], true);
                    }
                    $Iterate = 0;
                    $Rollback = true;
                    continue;
                }
                $ThisName = $ThisFileName;
                $ThisPath = $CIDRAM['Vault'];
                while (strpos($ThisName, '/') !== false || strpos($ThisName, "\\") !== false) {
                    $CIDRAM['Separator'] = (strpos($ThisName, '/') !== false) ? '/' : "\\";
                    $CIDRAM['ThisDir'] = substr($ThisName, 0, strpos($ThisName, $CIDRAM['Separator']));
                    $ThisPath .= $CIDRAM['ThisDir'] . '/';
                    $ThisName = substr($ThisName, strlen($CIDRAM['ThisDir']) + 1);
                    if (!file_exists($ThisPath) || !is_dir($ThisPath)) {
                        mkdir($ThisPath);
                    }
                }
                if (is_readable($CIDRAM['Vault'] . $ThisFileName)) {
                    $BytesRemoved += filesize($CIDRAM['Vault'] . $ThisFileName);
                    if (file_exists($CIDRAM['Vault'] . $ThisFileName . '.rollback')) {
                        unlink($CIDRAM['Vault'] . $ThisFileName . '.rollback');
                    }
                    rename($CIDRAM['Vault'] . $ThisFileName, $CIDRAM['Vault'] . $ThisFileName . '.rollback');
                }
                $BytesAdded += strlen($ThisFile);
                $Handle = fopen($CIDRAM['Vault'] . $ThisFileName, 'wb');
                $CIDRAM['RemoteFiles'][$ThisFileName] = fwrite($Handle, $ThisFile);
                $CIDRAM['RemoteFiles'][$ThisFileName] = ($CIDRAM['RemoteFiles'][$ThisFileName] !== false);
                fclose($Handle);
                $ThisFile = '';
            }
            if ($Rollback) {
                /** Prune unwanted empty directories (update/install failure+rollback). */
                if (
                    !empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To']) &&
                    is_array($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'])
                ) {
                    array_walk($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'], function ($ThisFile) use (&$CIDRAM) {
                        if (!empty($ThisFile) && $CIDRAM['Traverse']($ThisFile)) {
                            $CIDRAM['DeleteDirectory']($ThisFile);
                        }
                    });
                }
                $UpdateFailed = true;
            } else {
                /** Prune unwanted files and directories (update/install success). */
                if (!empty($CIDRAM['Components']['Meta'][$ThisTarget]['Files']['To'])) {
                    $ThisArr = $CIDRAM['Components']['Meta'][$ThisTarget]['Files']['To'];
                    $CIDRAM['Arrayify']($ThisArr);
                    array_walk($ThisArr, function ($ThisFile) use (&$CIDRAM) {
                        if (!empty($ThisFile) && $CIDRAM['Traverse']($ThisFile)) {
                            if (file_exists($CIDRAM['Vault'] . $ThisFile . '.rollback')) {
                                unlink($CIDRAM['Vault'] . $ThisFile . '.rollback');
                            }
                            if (
                                !isset($CIDRAM['RemoteFiles'][$ThisFile]) &&
                                !isset($CIDRAM['IgnoredFiles'][$ThisFile]) &&
                                file_exists($CIDRAM['Vault'] . $ThisFile)
                            ) {
                                $BytesRemoved += filesize($CIDRAM['Vault'] . $ThisFile);
                                unlink($CIDRAM['Vault'] . $ThisFile);
                                $CIDRAM['DeleteDirectory']($ThisFile);
                            }
                        }
                    });
                    unset($ThisArr);
                }

                /** Assign updated component annotation. */
                $CIDRAM['Updater-IO']->writeFile($CIDRAM['Vault'] . $ThisReannotate, $NewMeta);

                $CIDRAM['FE']['state_msg'] .= '<code>' . $ThisTarget . '</code> – ';
                if (
                    empty($CIDRAM['Components']['Meta'][$ThisTarget]['Version']) &&
                    empty($CIDRAM['Components']['Meta'][$ThisTarget]['Files'])
                ) {
                    $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_component_successfully_installed');
                    if (!empty($CIDRAM['Components']['Meta'][$ThisTarget]['When Install Succeeds'])) {
                        $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ThisTarget]['When Install Succeeds'], true);
                    }
                } else {
                    $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_component_successfully_updated');
                    if (!empty($CIDRAM['Components']['Meta'][$ThisTarget]['When Update Succeeds'])) {
                        $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ThisTarget]['When Update Succeeds'], true);
                    }
                }

                /** Replace downstream meta with upstream meta. */
                $CIDRAM['Components']['Meta'][$ThisTarget] = $CIDRAM['Components']['RemoteMeta'][$ThisTarget];
            }
        } else {
            $UpdateFailed = true;
        }
        if ($UpdateFailed) {
            $CIDRAM['FE']['state_msg'] .= '<code>' . $ThisTarget . '</code> – ';
            if (
                empty($CIDRAM['Components']['Meta'][$ThisTarget]['Version']) &&
                empty($CIDRAM['Components']['Meta'][$ThisTarget]['Files'])
            ) {
                $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_failed_to_install');
                if (!empty($CIDRAM['Components']['Meta'][$ThisTarget]['When Install Fails'])) {
                    $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ThisTarget]['When Install Fails'], true);
                }
            } else {
                $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_failed_to_update');
                if (!empty($CIDRAM['Components']['Meta'][$ThisTarget]['When Update Fails'])) {
                    $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ThisTarget]['When Update Fails'], true);
                }
            }
        }
        $CIDRAM['FormatFilesize']($BytesAdded);
        $CIDRAM['FormatFilesize']($BytesRemoved);
        $CIDRAM['FE']['state_msg'] .= sprintf(
            $CIDRAM['FE']['CronMode'] ? " « +%s | -%s | %s »\n" : ' <code><span class="txtGn">+%s</span> | <span class="txtRd">-%s</span> | <span class="txtOe">%s</span></code><br />',
            $BytesAdded,
            $BytesRemoved,
            $CIDRAM['NumberFormatter']->format(microtime(true) - $TimeRequired, 3)
        );
        if ($Reactivate) {
            $CIDRAM['UpdatesHandler-Activate']($ThisTarget);
        }
    }

    /** Remove superfluous metadata. */
    foreach ($Congruents as $File => $Upstream) {
        $Downstream = $CIDRAM['Congruency']($CIDRAM['Updater-IO']->readFile($CIDRAM['Vault'] . $File), $Upstream);
        $CIDRAM['Updater-IO']->writeFile($CIDRAM['Vault'] . $File, $Downstream);
    }

    /** Cleanup. */
    unset($CIDRAM['RemoteFiles'], $CIDRAM['IgnoredFiles']);
};

/**
 * Updates handler: Uninstall a component.
 *
 * @param string $ID The ID of the component to uninstall.
 */
$CIDRAM['UpdatesHandler-Uninstall'] = function ($ID) use (&$CIDRAM) {
    $InUse = $CIDRAM['ComponentFunctionUpdatePrep']($ID);
    $BytesRemoved = 0;
    $TimeRequired = microtime(true);
    $CIDRAM['FE']['state_msg'] .= '<code>' . $ID . '</code> – ';
    if (
        empty($InUse) &&
        !empty($CIDRAM['Components']['Meta'][$ID]['Files']['To']) &&
        !empty($CIDRAM['Components']['Meta'][$ID]['Reannotate']) &&
        (!empty($CIDRAM['Components']['Meta'][$ID]['Uninstallable']) || !empty($CIDRAM['RestrictUninstallBypass'])) &&
        ($OldMeta = $CIDRAM['Updater-IO']->readFile($CIDRAM['Vault'] . $CIDRAM['Components']['Meta'][$ID]['Reannotate'])) &&
        preg_match("~(\n" . preg_quote($ID) . ":?)(\n [^\n]*)*\n~i", $OldMeta, $OldMetaMatches) &&
        ($OldMetaMatches = $OldMetaMatches[0])
    ) {
        $NewMeta = str_replace($OldMetaMatches, preg_replace(
            ["/\n Files:(\n  [^\n]*)*\n/i", "/\n Version: [^\n]*\n/i"],
            "\n",
            $OldMetaMatches
        ), $OldMeta);
        array_walk($CIDRAM['Components']['Meta'][$ID]['Files']['To'], function ($ThisFile) use (&$CIDRAM, &$BytesRemoved) {
            if (!empty($ThisFile) && $CIDRAM['Traverse']($ThisFile)) {
                if (file_exists($CIDRAM['Vault'] . $ThisFile)) {
                    $BytesRemoved += filesize($CIDRAM['Vault'] . $ThisFile);
                    unlink($CIDRAM['Vault'] . $ThisFile);
                }
                if (file_exists($CIDRAM['Vault'] . $ThisFile . '.rollback')) {
                    $BytesRemoved += filesize($CIDRAM['Vault'] . $ThisFile . '.rollback');
                    unlink($CIDRAM['Vault'] . $ThisFile . '.rollback');
                }
                $CIDRAM['DeleteDirectory']($ThisFile);
            }
        });
        $CIDRAM['Updater-IO']->writeFile($CIDRAM['Vault'] . $CIDRAM['Components']['Meta'][$ID]['Reannotate'], $NewMeta);
        $CIDRAM['Components']['Meta'][$ID]['Version'] = false;
        $CIDRAM['Components']['Meta'][$ID]['Files'] = false;
        $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_component_successfully_uninstalled');
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Uninstall Succeeds'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Uninstall Succeeds'], true);
        }
    } else {
        $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_component_uninstall_error');
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Uninstall Fails'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Uninstall Fails'], true);
        }
    }
    $CIDRAM['FormatFilesize']($BytesRemoved);
    $CIDRAM['FE']['state_msg'] .= sprintf(
        $CIDRAM['FE']['CronMode'] ? " « -%s | %s »\n" : ' <code><span class="txtRd">-%s</span> | <span class="txtOe">%s</span></code><br />',
        $BytesRemoved,
        $CIDRAM['NumberFormatter']->format(microtime(true) - $TimeRequired, 3)
    );
};

/**
 * Updates handler: Activate a component.
 *
 * @param string $ID The ID of the component to activate.
 */
$CIDRAM['UpdatesHandler-Activate'] = function ($ID) use (&$CIDRAM) {
    $CIDRAM['Activation'] = [
        'Config' => $CIDRAM['Updater-IO']->readFile($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile']),
        'ipv4' => $CIDRAM['Config']['signatures']['ipv4'],
        'ipv6' => $CIDRAM['Config']['signatures']['ipv6'],
        'modules' => $CIDRAM['Config']['signatures']['modules'],
        'Modified' => false
    ];
    $InUse = $CIDRAM['ComponentFunctionUpdatePrep']($ID);
    $CIDRAM['FE']['state_msg'] .= '<code>' . $ID . '</code> – ';
    if (
        empty($InUse) &&
        !empty($CIDRAM['Components']['Meta'][$ID]['Files']['To']) && (
            !empty($CIDRAM['Components']['Meta'][$ID]['Used with']) || !empty($CIDRAM['Components']['Meta'][$ID]['Extended Description'])
    )) {
        $UsedWith = empty($CIDRAM['Components']['Meta'][$ID]['Used with']) ? '' : $CIDRAM['Components']['Meta'][$ID]['Used with'];
        $Description = empty($CIDRAM['Components']['Meta'][$ID]['Extended Description']) ? '' : $CIDRAM['Components']['Meta'][$ID]['Extended Description'];
        if ($UsedWith === 'ipv4' || strpos($Description, 'signatures-&gt;ipv4') !== false) {
            $CIDRAM['ActivateComponent']('ipv4', $ID);
        }
        if ($UsedWith === 'ipv6' || strpos($Description, 'signatures-&gt;ipv6') !== false) {
            $CIDRAM['ActivateComponent']('ipv6', $ID);
        }
        if ($UsedWith === 'modules' || strpos($Description, 'signatures-&gt;modules') !== false) {
            $CIDRAM['ActivateComponent']('modules', $ID);
        }
    }
    if (!$CIDRAM['Activation']['Modified'] || !$CIDRAM['Activation']['Config']) {
        $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_activation_failed') . '<br />';
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Activation Fails'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Activation Fails'], true);
        }
    } else {
        $EOL = (strpos($CIDRAM['Activation']['Config'], "\r\n") !== false) ? "\r\n" : "\n";
        $CIDRAM['Activation']['Config'] = str_replace([
            $EOL . "ipv4='" . $CIDRAM['Config']['signatures']['ipv4'] . "'" . $EOL,
            $EOL . "ipv6='" . $CIDRAM['Config']['signatures']['ipv6'] . "'" . $EOL,
            $EOL . "modules='" . $CIDRAM['Config']['signatures']['modules'] . "'" . $EOL
        ], [
            $EOL . "ipv4='" . $CIDRAM['Activation']['ipv4'] . "'" . $EOL,
            $EOL . "ipv6='" . $CIDRAM['Activation']['ipv6'] . "'" . $EOL,
            $EOL . "modules='" . $CIDRAM['Activation']['modules'] . "'" . $EOL
        ], $CIDRAM['Activation']['Config']);
        $CIDRAM['Config']['signatures']['ipv4'] = $CIDRAM['Activation']['ipv4'];
        $CIDRAM['Config']['signatures']['ipv6'] = $CIDRAM['Activation']['ipv6'];
        $CIDRAM['Config']['signatures']['modules'] = $CIDRAM['Activation']['modules'];
        $CIDRAM['Updater-IO']->writeFile($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile'], $CIDRAM['Activation']['Config']);
        $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_activated') . '<br />';
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Activation Succeeds'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Activation Succeeds'], true);
        }
    }
    /** Cleanup. */
    unset($CIDRAM['Activation']);
};

/**
 * Updates handler: Deactivate a component.
 *
 * @param string $ID The ID of the component to deactivate.
 */
$CIDRAM['UpdatesHandler-Deactivate'] = function ($ID) use (&$CIDRAM) {
    $CIDRAM['Deactivation'] = [
        'Config' => $CIDRAM['Updater-IO']->readFile($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile']),
        'ipv4' => $CIDRAM['Config']['signatures']['ipv4'],
        'ipv6' => $CIDRAM['Config']['signatures']['ipv6'],
        'modules' => $CIDRAM['Config']['signatures']['modules'],
        'Modified' => false
    ];
    $InUse = false;
    $CIDRAM['FE']['state_msg'] .= '<code>' . $ID . '</code> – ';
    if (!empty($CIDRAM['Components']['Meta'][$ID]['Files'])) {
        $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$ID]['Files']);
        $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$ID]['Files']['To']);
        $ThisComponent = $CIDRAM['Components']['Meta'][$ID];
        $CIDRAM['PrepareExtendedDescription']($ThisComponent);
        $InUse = $CIDRAM['IsInUse']($ThisComponent);
        unset($ThisComponent);
    }
    if (!empty($InUse) && !empty($CIDRAM['Components']['Meta'][$ID]['Files']['To'])) {
        $CIDRAM['DeactivateComponent']('ipv4', $ID);
        $CIDRAM['DeactivateComponent']('ipv6', $ID);
        $CIDRAM['DeactivateComponent']('modules', $ID);
    }
    if (!$CIDRAM['Deactivation']['Modified'] || !$CIDRAM['Deactivation']['Config']) {
        $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_deactivation_failed') . '<br />';
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Deactivation Fails'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Deactivation Fails'], true);
        }
    } else {
        $EOL = (strpos($CIDRAM['Deactivation']['Config'], "\r\n") !== false) ? "\r\n" : "\n";
        $CIDRAM['Deactivation']['Config'] = str_replace([
            $EOL . "ipv4='" . $CIDRAM['Config']['signatures']['ipv4'] . "'" . $EOL,
            $EOL . "ipv6='" . $CIDRAM['Config']['signatures']['ipv6'] . "'" . $EOL,
            $EOL . "modules='" . $CIDRAM['Config']['signatures']['modules'] . "'" . $EOL
        ], [
            $EOL . "ipv4='" . $CIDRAM['Deactivation']['ipv4'] . "'" . $EOL,
            $EOL . "ipv6='" . $CIDRAM['Deactivation']['ipv6'] . "'" . $EOL,
            $EOL . "modules='" . $CIDRAM['Deactivation']['modules'] . "'" . $EOL
        ], $CIDRAM['Deactivation']['Config']);
        $CIDRAM['Config']['signatures']['ipv4'] = $CIDRAM['Deactivation']['ipv4'];
        $CIDRAM['Config']['signatures']['ipv6'] = $CIDRAM['Deactivation']['ipv6'];
        $CIDRAM['Config']['signatures']['modules'] = $CIDRAM['Deactivation']['modules'];
        $CIDRAM['Updater-IO']->writeFile($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile'], $CIDRAM['Deactivation']['Config']);
        $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_deactivated') . '<br />';
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Deactivation Succeeds'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Deactivation Succeeds'], true);
        }
    }
    /** Cleanup. */
    unset($CIDRAM['Deactivation']);
};

/**
 * Updates handler: Repair a component.
 *
 * @param string|array $ID The ID (or array of IDs) of the component(/s) to repair.
 */
$CIDRAM['UpdatesHandler-Repair'] = function ($ID) use (&$CIDRAM) {
    $CIDRAM['Arrayify']($ID);
    foreach ($ID as $ThisTarget) {
        if (!isset(
            $CIDRAM['Components']['Meta'][$ThisTarget]['Files'],
            $CIDRAM['Components']['Meta'][$ThisTarget]['Files']['To'],
            $CIDRAM['Components']['Meta'][$ThisTarget]['Remote']
        )) {
            continue;
        }
        $BytesAdded = 0;
        $BytesRemoved = 0;
        $TimeRequired = microtime(true);
        $RepairFailed = false;
        if ($Reactivate = $CIDRAM['IsInUse']($CIDRAM['Components']['Meta'][$ThisTarget])) {
            $CIDRAM['UpdatesHandler-Deactivate']($ThisTarget);
        }
        $CIDRAM['FE']['state_msg'] .= '<code>' . $ThisTarget . '</code> – ';
        if (!isset($CIDRAM['Components']['RemoteMeta'], $CIDRAM['Components']['RemoteMeta'][$ThisTarget])) {
            $CIDRAM['Components']['RemoteMeta'] = [];
            $RemoteData = '';
            $CIDRAM['FetchRemote-ContextFree']($RemoteData, $CIDRAM['Components']['Meta'][$ThisTarget]['Remote']);
            if (substr($RemoteData, 0, 4) === "---\n" && ($EoYAML = strpos($RemoteData, "\n\n")) !== false) {
                $CIDRAM['YAML']->process(substr($RemoteData, 4, $EoYAML - 4), $CIDRAM['Components']['RemoteMeta']);
            }
        }
        if (isset(
            $CIDRAM['Components']['RemoteMeta'][$ThisTarget],
            $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files'],
            $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'],
            $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From'],
            $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum']
        )) {
            $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$ThisTarget]['Files']['To']);
            $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To']);
            $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From']);
            $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum']);
        } else {
            $RepairFailed = true;
        }
        if (
            !$RepairFailed &&
            $CIDRAM['Components']['Meta'][$ThisTarget]['Files']['To'] === $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To']
        ) {
            $Files = count($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To']);
            for ($Iterator = 0; $Iterator < $Files; $Iterator++) {
                if (!isset(
                    $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'][$Iterator],
                    $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From'][$Iterator],
                    $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum'][$Iterator]
                ) || !$CIDRAM['Traverse']($CIDRAM['Vault'] . $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'][$Iterator])) {
                    $RepairFailed = true;
                    break;
                }
                $RemoteFileTo = $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'][$Iterator];
                $RemoteFileFrom = $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From'][$Iterator];
                $RemoteChecksum = $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum'][$Iterator];
                if (file_exists($CIDRAM['Vault'] . $RemoteFileTo . '.rollback')) {
                    $BytesRemoved += filesize($CIDRAM['Vault'] . $RemoteFileTo . '.rollback');
                    unlink($CIDRAM['Vault'] . $RemoteFileTo . '.rollback');
                }
                $LocalFile = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $RemoteFileTo);
                $LocalFileSize = strlen($LocalFile);
                if (
                    (md5($LocalFile) . ':' . $LocalFileSize) === $RemoteChecksum ||
                    (sha1($LocalFile) . ':' . $LocalFileSize) === $RemoteChecksum ||
                    (hash('sha256', $LocalFile) . ':' . $LocalFileSize) === $RemoteChecksum
                ) {
                    continue;
                }
                $RemoteFile = $CIDRAM['Request']($RemoteFileFrom);
                if (
                    strtolower(substr($RemoteFileFrom, -2)) === 'gz' &&
                    strtolower(substr($RemoteFileTo, -2)) !== 'gz' &&
                    substr($RemoteFile, 0, 2) === "\x1f\x8b"
                ) {
                    $RemoteFile = gzdecode($RemoteFile);
                }
                $RemoteFileSize = strlen($RemoteFile);
                if ((
                    (md5($RemoteFile) . ':' . $RemoteFileSize) !== $RemoteChecksum &&
                    (sha1($RemoteFile) . ':' . $RemoteFileSize) !== $RemoteChecksum &&
                    (hash('sha256', $RemoteFile) . ':' . $RemoteFileSize) !== $RemoteChecksum
                ) || (
                    preg_match('~\.(?:css|dat|gif|inc|jpe?g|php|png|ya?ml|[a-z]{0,2}db)$~i', $RemoteFileTo) &&
                    !$CIDRAM['SanityCheck']($RemoteFileTo, $RemoteFile)
                )) {
                    $RepairFailed = true;
                    continue;
                }
                $ThisName = $RemoteFileTo;
                $ThisPath = $CIDRAM['Vault'];
                while (strpos($ThisName, '/') !== false || strpos($ThisName, "\\") !== false) {
                    $Separator = (strpos($ThisName, '/') !== false) ? '/' : "\\";
                    $ThisDir = substr($ThisName, 0, strpos($ThisName, $Separator));
                    $ThisPath .= $ThisDir . '/';
                    $ThisName = substr($ThisName, strlen($ThisDir) + 1);
                    if (!file_exists($ThisPath) || !is_dir($ThisPath)) {
                        mkdir($ThisPath);
                    }
                }
                if (!is_writable($CIDRAM['Vault'] . $RemoteFileTo)) {
                    $RepairFailed = true;
                    continue;
                }
                $BytesRemoved += $LocalFileSize;
                $BytesAdded += $RemoteFileSize;
                $Handle = fopen($CIDRAM['Vault'] . $RemoteFileTo, 'wb');
                fwrite($Handle, $RemoteFile);
                fclose($Handle);
            }
        } else {
            $RepairFailed = true;
        }
        if (
            !$RepairFailed &&
            !empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Reannotate']) &&
            $CIDRAM['Traverse']($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Reannotate']) &&
            ($ThisReannotate = $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Reannotate']) &&
            file_exists($CIDRAM['Vault'] . $ThisReannotate) &&
            ($OldMeta = $CIDRAM['Updater-IO']->readFile($CIDRAM['Vault'] . $ThisReannotate)) &&
            preg_match("~(\n" . preg_quote($ThisTarget) . ":?)(\n [^\n]*)*\n~i", $OldMeta, $OldMetaMatches) &&
            ($OldMetaMatches = $OldMetaMatches[0]) &&
            ($NewMeta = $RemoteData) &&
            preg_match("~(\n" . preg_quote($ThisTarget) . ":?)(\n [^\n]*)*\n~i", $NewMeta, $NewMetaMatches) &&
            ($NewMetaMatches = $NewMetaMatches[0])
        ) {
            $NewMeta = str_replace($OldMetaMatches, $NewMetaMatches, $OldMeta);

            /** Assign updated component annotation. */
            $CIDRAM['Updater-IO']->writeFile($CIDRAM['Vault'] . $ThisReannotate, $NewMeta);

            /** Repair operation succeeded. */
            $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_repair_process_completed');
            if (!empty($CIDRAM['Components']['Meta'][$ThisTarget]['When Repair Succeeds'])) {
                $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ThisTarget]['When Repair Succeeds'], true);
            }

            /** Replace downstream meta with upstream meta. */
            $CIDRAM['Components']['Meta'][$ThisTarget] = $CIDRAM['Components']['RemoteMeta'][$ThisTarget];

        } else {
            $RepairFailed = true;

            /** Repair operation failed. */
            $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_repair_process_failed');
            if (!empty($CIDRAM['Components']['Meta'][$ThisTarget]['When Repair Fails'])) {
                $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ThisTarget]['When Repair Fails'], true);
            }

        }
        $CIDRAM['FormatFilesize']($BytesAdded);
        $CIDRAM['FormatFilesize']($BytesRemoved);
        $CIDRAM['FE']['state_msg'] .= sprintf(
            $CIDRAM['FE']['CronMode'] ? " « +%s | -%s | %s »\n" : ' <code><span class="txtGn">+%s</span> | <span class="txtRd">-%s</span> | <span class="txtOe">%s</span></code><br />',
            $BytesAdded,
            $BytesRemoved,
            $CIDRAM['NumberFormatter']->format(microtime(true) - $TimeRequired, 3)
        );
        if ($Reactivate) {
            $CIDRAM['UpdatesHandler-Activate']($ThisTarget);
        }
    }
};

/**
 * Updates handler: Verify a component.
 *
 * @param string|array $ID The ID (or array of IDs) of the component(/s) to verify.
 */
$CIDRAM['UpdatesHandler-Verify'] = function ($ID) use (&$CIDRAM) {
    $CIDRAM['Arrayify']($ID);
    foreach ($ID as $ThisID) {
        $Table = '<blockquote class="ng1 comSub">';
        if (empty($CIDRAM['Components']['Meta'][$ThisID]['Files'])) {
            continue;
        }
        $TheseFiles = $CIDRAM['Components']['Meta'][$ThisID]['Files'];
        if (!empty($TheseFiles['To'])) {
            $CIDRAM['Arrayify']($TheseFiles['To']);
        }
        $Count = count($TheseFiles['To']);
        if (!empty($TheseFiles['Checksum'])) {
            $CIDRAM['Arrayify']($TheseFiles['Checksum']);
        }
        $Passed = true;
        for ($Iterate = 0; $Iterate < $Count; $Iterate++) {
            $ThisFile = $TheseFiles['To'][$Iterate];
            $ThisFileData = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $ThisFile);

            /** Sanity check. */
            if (
                preg_match('~\.(?:css|dat|gif|inc|jpe?g|php|png|ya?ml|[a-z]{0,2}db)$~i', $ThisFile)
            ) {
                $Class = $CIDRAM['SanityCheck']($ThisFile, $ThisFileData) ? 'txtGn' : 'txtRd';
                $Sanity = sprintf('<span class="%s">%s</span>', $Class, $CIDRAM['L10N']->getString(
                    $Class === 'txtGn' ? 'response_passed' : 'response_failed'
                ));
                if ($Class === 'txtRd') {
                    $Passed = false;
                }
            } else {
                $Sanity = sprintf('<span class="txtOe">%s</span>', $CIDRAM['L10N']->getString('response_skipped'));
            }

            $Checksum = empty($TheseFiles['Checksum'][$Iterate]) ? '' : $TheseFiles['Checksum'][$Iterate];
            $Len = strlen($ThisFileData);
            $HashPartLen = strpos($Checksum, ':') ?: 64;
            if ($HashPartLen === 32) {
                $Actual = md5($ThisFileData) . ':' . $Len;
            } else {
                $Actual = (($HashPartLen === 40) ? sha1($ThisFileData) : hash('sha256', $ThisFileData)) . ':' . $Len;
            }

            /** Integrity check. */
            if ($Checksum) {
                if ($Actual !== $Checksum) {
                    $Class = 'txtRd';
                    $Passed = false;
                } else {
                    $Class = 'txtGn';
                }
                $Integrity = sprintf('<span class="%s">%s</span>', $Class, $CIDRAM['L10N']->getString(
                    $Class === 'txtGn' ? 'response_passed' : 'response_failed'
                ));
            } else {
                $Class = 's';
                $Integrity = sprintf('<span class="txtOe">%s</span>', $CIDRAM['L10N']->getString('response_skipped'));
            }

            /** Append results. */
            $Table .= sprintf(
                '<code>%1$s</code> – %7$s%8$s – %9$s%10$s<br />%2$s – <code class="%6$s">%3$s</code><br />%4$s – <code class="%6$s">%5$s</code><hr />',
                $ThisFile,
                $CIDRAM['L10N']->getString('label_actual'),
                $Actual ?: '?',
                $CIDRAM['L10N']->getString('label_expected'),
                $Checksum ?: '?',
                $Class,
                $CIDRAM['L10N']->getString('label_integrity_check'),
                $Integrity,
                $CIDRAM['L10N']->getString('label_sanity_check'),
                $Sanity
            );
        }
        $Table .= '</blockquote>';
        $CIDRAM['FE']['state_msg'] .= sprintf(
            '<div><span class="comCat" style="cursor:pointer"><code>%s</code> – <span class="%s">%s</span></span>%s</div>',
            $ThisID,
            ($Passed ? 's' : 'txtRd'),
            $CIDRAM['L10N']->getString($Passed ? 'response_verification_success' : 'response_verification_failed'),
            $Table
        );
    }
};

/**
 * Normalise linebreaks.
 *
 * @param string $Data The data to normalise.
 */
$CIDRAM['NormaliseLinebreaks'] = function (&$Data) {
    if (strpos($Data, "\r")) {
        $Data = (strpos($Data, "\r\n") !== false) ? str_replace("\r", '', $Data) : str_replace("\r", "\n", $Data);
    }
};

/**
 * Signature files handler for sections list.
 *
 * @param array $Files The signature files to process.
 * @return string Generated sections list data.
 */
$CIDRAM['SectionsHandler'] = function (array $Files) use (&$CIDRAM) {
    if (!isset($CIDRAM['Ignore'])) {
        $CIDRAM['Ignore'] = $CIDRAM['FetchIgnores']();
    }
    $CIDRAM['FE']['SL_Signatures'] = 0;
    $CIDRAM['FE']['SL_Sections'] = 0;
    $CIDRAM['FE']['SL_Files'] = count($Files);
    $CIDRAM['FE']['SL_Unique'] = 0;
    $Out = '';
    $SectionsForIgnore = [];
    $SignaturesCount = [];
    $SectionMeta = [];
    $BaseSectionMeta = ['Deny' => 0, 'Bogon' => 0, 'Cloud' => 0, 'Generic' => 0, 'Legal' => 0, 'Malware' => 0, 'Proxy' => 0, 'Spam' => 0, 'Run' => 0, 'Greylist' => 0, 'Whitelist' => 0];
    $ThisSectionMeta = $BaseSectionMeta;
    foreach ($Files as $File) {
        $Data = $File && is_readable($CIDRAM['Vault'] . $File) ? $CIDRAM['ReadFile']($CIDRAM['Vault'] . $File) : '';
        if (!$Data) {
            continue;
        }
        $CIDRAM['NormaliseLinebreaks']($Data);
        $Data = "\n" . $Data . "\n";
        $PosB = -1;
        $ThisCount = 0;
        $OriginCount = 0;
        while (true) {
            $PosA = strpos($Data, "\n", $PosB + 1);
            if ($PosA === false) {
                break;
            }
            $PosA++;
            if (!$PosB = strpos($Data, "\n", $PosA)) {
                break;
            }
            $Line = substr($Data, $PosA, $PosB - $PosA);
            $PosB--;
            if (substr($Line, -1) === "\n") {
                $Line = substr($Line, 0, -1);
            }
            if (substr($Line, 0, 5) === 'Tag: ') {
                $Tag = substr($Line, 5);
                $CIDRAM['FE']['SL_Sections']++;
                if (!isset($SectionsForIgnore[$Tag])) {
                    $SectionsForIgnore[$Tag] = empty($CIDRAM['Ignore'][$Tag]);
                }
                if (!isset($SignaturesCount[$Tag])) {
                    $SignaturesCount[$Tag] = 0;
                }
                $SignaturesCount[$Tag] += $ThisCount;
                $ThisCount = 0;
                if (!isset($SectionMeta[$Tag])) {
                    $SectionMeta[$Tag] = $BaseSectionMeta;
                }
                foreach ($ThisSectionMeta as $MetaKey => $MetaValue) {
                    if (!isset($SectionMeta[$Tag][$MetaKey])) {
                        $SectionMeta[$Tag][$MetaKey] = 0;
                    }
                    $SectionMeta[$Tag][$MetaKey] += $MetaValue;
                }
                $ThisSectionMeta = $BaseSectionMeta;
                continue;
            }
            if (substr($Line, 0, 8) === 'Origin: ') {
                $Origin = substr($Line, 8);
                if ($CIDRAM['FE']['Flags']) {
                    $Origin = '<span class="flag ' . $Origin . '"></span>';
                }
                $ThisSectionMeta[$Origin] = $OriginCount;
                $OriginCount = 0;
                continue;
            }
            if (!$Line || preg_match('~^([\n#]|Expires|Defers to)~', $Line) || strpos($Line, '/') === false) {
                continue;
            }
            $ThisCount++;
            $OriginCount++;
            $CIDRAM['FE']['SL_Signatures']++;
            if (($XPos = strpos($Line, 'Deny ')) !== false && ($Speculate = substr($Line, $XPos + 5))) {
                if (!preg_match('~^(?:Bogon|Cloud|Generic|Legal|Malware|Proxy|Spam)$~', $Speculate)) {
                    $Speculate = 'Deny';
                }
                $ThisSectionMeta[$Speculate]++;
            } elseif (($XPos = strpos($Line, 'Run ')) !== false && substr($Line, $XPos + 4)) {
                $ThisSectionMeta['Run']++;
            } elseif (strpos($Line, 'Greylist') !== false) {
                $ThisSectionMeta['Greylist']++;
            } elseif (strpos($Line, 'Whitelist') !== false) {
                $ThisSectionMeta['Whitelist']++;
            }
        }
    }
    $Class = 'ng2';
    ksort($SectionsForIgnore);
    $CIDRAM['FE']['SL_Unique'] = count($SectionsForIgnore);
    foreach ($SectionsForIgnore as $Section => $State) {
        $ThisCount = $CIDRAM['NumberFormatter']->format(isset($SignaturesCount[$Section]) ? $SignaturesCount[$Section] : 0);
        $Class = (isset($Class) && $Class === 'ng2') ? 'ng1' : 'ng2';
        $SectionSafe = preg_replace('~[^\da-z]~i', '', $Section);
        $SectionLabel = $Section . ' (<span class="txtRd">' . $ThisCount . '</span>)';
        $SectionBreakdown = '';
        $Next = '';
        $HasOrigin = false;
        foreach ($SectionMeta[$Section] as $BreakdownItem => $Quantity) {
            if ($Next === 'Origin') {
                $SectionBreakdown .= sprintf(
                    '<span class="%1$s">' . ($SectionBreakdown ? ' – ' : '') . '<a href="javascript:void()" onclick="javascript:hide(\'%1$s\');show(\'%2$s\')">%3$s</a></span><span class="%2$s" style="display:none">',
                    'originLink' . $SectionSafe,
                    'originContent' . $SectionSafe,
                    $CIDRAM['L10N']->getString('label_show_by_origin')
                );
                $HasOrigin = true;
            }
            $Next = ($BreakdownItem === 'Whitelist') ? 'Origin' : '';
            if ($Quantity) {
                $Quantity = $CIDRAM['NumberFormatter']->format($Quantity);
                $SectionBreakdown .= ($SectionBreakdown ? ' – ' : '') . $BreakdownItem . ': ' . $Quantity;
            }
        }
        if ($HasOrigin) {
            $SectionBreakdown .= "</span>";
        }
        $Out .= sprintf(
            '<div class="%1$s sectionControlNotIgnored%2$s"><strong>%3$s%4$s</strong><br /><em>%5$s</em></div>',
            $Class,
            $State ? $SectionSafe : $SectionSafe . '" style="display:none',
            $SectionLabel,
            ' – <a href="javascript:void()" onclick="javascript:slx(\'' . $Section . '\',\'ignore\',\'sectionControlNotIgnored' . $SectionSafe . '\',\'sectionControlIgnored' . $SectionSafe . '\')">' . $CIDRAM['L10N']->getString('label_ignore') . '</a>',
            $SectionBreakdown
        ) . sprintf(
            '<div class="%1$s sectionControlIgnored%2$s"><strong>%3$s%4$s</strong><br /><em>%5$s</em></div>',
            $Class,
            $SectionSafe . '" style="filter:grayscale(50%) contrast(50%)' . ($State ? ';display:none' : ''),
            $SectionLabel . ' – ' . $CIDRAM['L10N']->getString('state_ignored'),
            ' – <a href="javascript:void()" onclick="javascript:slx(\'' . $Section . '\',\'unignore\',\'sectionControlIgnored' . $SectionSafe . '\',\'sectionControlNotIgnored' . $SectionSafe . '\')">' . $CIDRAM['L10N']->getString('label_unignore') . '</a>',
            $SectionBreakdown
        );
    }
    return $Out;
};

/**
 * Tally IPv6 count.
 *
 * @param array $Arr
 * @param int $Range (1-128)
 */
$CIDRAM['RangeTablesTallyIPv6'] = function (array &$Arr, $Range) {
    $Order = ceil($Range / 16) - 1;
    $Arr[$Order] += pow(2, (128 - $Range) % 16);
};

/**
 * Finalise IPv6 count.
 *
 * @param array $Arr Values of the IPv6 octets.
 * @return array A base value (first parameter), and the power it would need to
 *      be raised by (second parameter) to accurately reflect the total amount.
 */
$CIDRAM['RangeTablesFinaliseIPv6'] = function (array $Arr) {
    for ($Iter = 7; $Iter > 0; $Iter--) {
        if (!empty($Arr[$Iter + 1])) {
            $Arr[$Iter] += (floor($Arr[$Iter + 1] / 655.36) / 100);
        }
        while ($Arr[$Iter] >= 65536) {
            $Arr[$Iter] -= 65536;
            $Arr[$Iter - 1] += 1;
        }
    }
    $Power = 0;
    foreach ($Arr as $Order => $Value) {
        if ($Value) {
            $Power = (7 - $Order) * 16;
            break;
        }
    }
    return [$Value, $Power];
};

/**
 * Fetch some data about CIDRs.
 *
 * @param string $Data The signature file data.
 * @param int $Offset The current offset position.
 * @param string $Needle A needle to identify the parts of the data we're looking for.
 * @param bool $HasOrigin Whether the data has an origin tag.
 * @return array|bool The CIDR's parameter and origin (when available), or false on failure.
 */
$CIDRAM['RangeTablesFetchLine'] = function (&$Data, &$Offset, &$Needle, &$HasOrigin) {
    $Check = strpos($Data, $Needle, $Offset);
    if ($Check !== false) {
        $NeedleLen = strlen($Needle);
        $LFPos = strpos($Data, "\n", $Check + $NeedleLen);
        if ($LFPos !== false) {
            if ($Deduct = $LFPos - $Check - $NeedleLen) {
                $Param = trim(substr($Data, $Check + $NeedleLen + 1, $Deduct - 1));
                $Offset = $Check + $NeedleLen + strlen($Param) + 1;
            } else {
                $Param = '';
                $Offset = $Check + $NeedleLen;
            }
        } else {
            $Param = trim(substr($Data, $Check + $NeedleLen));
            $Offset = false;
        }
        $From = $Check > 128 ? $Check - 128 : 0;
        $CPos = strrpos(substr($Data, $From, $Check - $From), "\n");
        if (substr($Data, $CPos + 1, 1) === '#') {
            return false;
        }
        $Origin = '??';
        if ($Offset !== false && $HasOrigin) {
            $CPos = strpos($Data, "\n\n", $Offset);
            $OPos = strpos($Data, "\nOrigin: ", $Offset);
            if ($OPos !== false && ($CPos === false || $CPos > $OPos)) {
                $Origin = substr($Data, $OPos + 9, 2);
            }
        }
        $Param = trim($Param) ?: '';
        return ['Param' => $Param, 'Origin' => $Origin];
    }
    $Offset = false;
    return false;
};

/**
 * Iterate range tables files.
 *
 * @param array $Arr Where we're populating it all.
 * @param array $Files The currently active (IPv4 or IPv6) signature files.
 * @param array $SigTypes The various types of signatures supported.
 * @param int $MaxRange (32 or 128).
 * @param string $IPType (IPv4 or IPv6).
 */
$CIDRAM['RangeTablesIterateFiles'] = function (array &$Arr, array $Files, array $SigTypes, $MaxRange, $IPType) use (&$CIDRAM) {
    foreach ($Files as $File) {
        $File = (strpos($File, ':') === false) ? $File : substr($File, strpos($File, ':') + 1);
        $Data = $File && is_readable($CIDRAM['Vault'] . $File) ? $CIDRAM['ReadFile']($CIDRAM['Vault'] . $File) : '';
        if (!$Data) {
            continue;
        }
        $CIDRAM['NormaliseLinebreaks']($Data);
        $HasOrigin = (strpos($Data, "\nOrigin: ") !== false);
        foreach ($SigTypes as $SigType) {
            for ($Range = 1; $Range <= $MaxRange; $Range++) {
                if ($MaxRange === 32) {
                    $Order = pow(2, $MaxRange - $Range);
                }
                $Offset = 0;
                $Needle = '/' . $Range . ' ' . $SigType;
                while ($Offset !== false) {
                    if ($Entry = $CIDRAM['RangeTablesFetchLine']($Data, $Offset, $Needle, $HasOrigin)) {
                        if (empty($Arr[$IPType][$SigType][$Range][$Entry['Param']])) {
                            $Arr[$IPType][$SigType][$Range][$Entry['Param']] = 0;
                        }
                        $Arr[$IPType][$SigType][$Range][$Entry['Param']]++;
                        if ($MaxRange === 32) {
                            if (empty($Arr[$IPType][$SigType]['Total'][$Entry['Param']])) {
                                $Arr[$IPType][$SigType]['Total'][$Entry['Param']] = 0;
                            }
                            $Arr[$IPType][$SigType]['Total'][$Entry['Param']] += $Order;
                        } elseif ($MaxRange === 128) {
                            if (empty($Arr[$IPType][$SigType]['Total'][$Entry['Param']])) {
                                $Arr[$IPType][$SigType]['Total'][$Entry['Param']] = [0, 0, 0, 0, 0, 0, 0, 0];
                            }
                            $CIDRAM['RangeTablesTallyIPv6']($Arr[$IPType][$SigType]['Total'][$Entry['Param']], $Range);
                        }
                        if ($Entry['Origin']) {
                            if (empty($Arr[$IPType . '-Origin'][$SigType][$Range][$Entry['Origin']])) {
                                $Arr[$IPType . '-Origin'][$SigType][$Range][$Entry['Origin']] = 0;
                            }
                            $Arr[$IPType . '-Origin'][$SigType][$Range][$Entry['Origin']]++;
                            if ($MaxRange === 32) {
                                if (empty($Arr[$IPType . '-Origin'][$SigType]['Total'][$Entry['Origin']])) {
                                    $Arr[$IPType . '-Origin'][$SigType]['Total'][$Entry['Origin']] = 0;
                                }
                                $Arr[$IPType . '-Origin'][$SigType]['Total'][$Entry['Origin']] += $Order;
                            } elseif ($MaxRange === 128) {
                                if (empty($Arr[$IPType . '-Origin'][$SigType]['Total'][$Entry['Origin']])) {
                                    $Arr[$IPType . '-Origin'][$SigType]['Total'][$Entry['Origin']] = [0, 0, 0, 0, 0, 0, 0, 0];
                                }
                                $CIDRAM['RangeTablesTallyIPv6']($Arr[$IPType . '-Origin'][$SigType]['Total'][$Entry['Origin']], $Range);
                            }
                        }
                    }
                }
            }
        }
    }
};

/**
 * Iterate range tables data.
 *
 * @param array $Arr
 * @param array $Out
 * @param string $JS
 * @param string $SigType
 * @param int $MaxRange (32 or 128).
 * @param string $IPType (IPv4 or IPv6).
 * @param string $ZeroPlus (txtGn, txtRd or txtOe, depending on the type of signature we're working with).
 * @param string $Class The table entry class that our JavaScript will need to work with.
 */
$CIDRAM['RangeTablesIterateData'] = function (
    array &$Arr,
    array &$Out,
    &$JS,
    $SigType,
    $MaxRange,
    $IPType,
    $ZeroPlus,
    $Class
) use (&$CIDRAM) {
    for ($Range = 1; $Range <= $MaxRange; $Range++) {
        $Size = '*Math.pow(2,' . ($MaxRange - $Range) . ')';
        if (count($Arr[$IPType][$SigType][$Range])) {
            $StatClass = $ZeroPlus;
            arsort($Arr[$IPType][$SigType][$Range]);
            foreach ($Arr[$IPType][$SigType][$Range] as $Param => &$Count) {
                if ($IPType === 'IPv4') {
                    $ThisID = $IPType . preg_replace('~[^\da-z]~i', '_', $SigType . $Range . $Param);
                    $Total = '<span id="' . $ThisID . '"></span>';
                    $JS .= 'w(\'' . $ThisID . '\',nft((' . $Count . $Size . ').toString()));';
                    $Count = $CIDRAM['NumberFormatter']->format($Count) . ' (' . $Total . ')';
                } else {
                    $Count = $CIDRAM['NumberFormatter']->format($Count);
                }
                if ($Param) {
                    $Count = $Param . ' – ' . $Count;
                }
            }
            $Arr[$IPType][$SigType][$Range] = implode('<br />', $Arr[$IPType][$SigType][$Range]);
            if (count($Arr[$IPType . '-Origin'][$SigType][$Range])) {
                arsort($Arr[$IPType . '-Origin'][$SigType][$Range]);
                foreach ($Arr[$IPType . '-Origin'][$SigType][$Range] as $Origin => &$Count) {
                    if ($IPType === 'IPv4') {
                        $ThisID = $IPType . preg_replace('~[^\da-z]~i', '_', $SigType . $Range . $Origin);
                        $Total = '<span id="' . $ThisID . '"></span>';
                        $JS .= 'w(\'' . $ThisID . '\',nft((' . $Count . $Size . ').toString()));';
                        $Count = $CIDRAM['NumberFormatter']->format($Count) . ' (' . $Total . ')';
                    } else {
                        $Count = $CIDRAM['NumberFormatter']->format($Count);
                    }
                    $Count = '<code class="hB">' . $Origin . '</code> – ' . (
                        $CIDRAM['FE']['Flags'] && $Origin !== '??' ? '<span class="flag ' . $Origin . '"></span> – ' : ''
                    ) . $Count;
                }
                $Arr[$IPType . '-Origin'][$SigType][$Range] = implode('<br />', $Arr[$IPType . '-Origin'][$SigType][$Range]);
                $Arr[$IPType][$SigType][$Range] .= '<hr />' . $Arr[$IPType . '-Origin'][$SigType][$Range];
            }
        } else {
            $StatClass = 's';
            $Arr[$IPType][$SigType][$Range] = '';
        }
        if ($Arr[$IPType][$SigType][$Range]) {
            if (!isset($Out[$IPType . '/' . $Range])) {
                $Out[$IPType . '/' . $Range] = '';
            }
            $Out[$IPType . '/' . $Range] .= '<span style="display:none" class="' . $Class . ' ' . $StatClass . '">' . $Arr[$IPType][$SigType][$Range] . '</span>';
        }
    }
    if (count($Arr[$IPType][$SigType]['Total'])) {
        $StatClass = $ZeroPlus;
        if ($MaxRange === 32) {
            arsort($Arr[$IPType][$SigType]['Total']);
        } elseif ($MaxRange === 128) {
            uasort($Arr[$IPType][$SigType]['Total'], function ($A, $B) {
                for ($i = 0; $i < 8; $i++) {
                    if ($A[$i] !== $B[$i]) {
                        return $A[$i] > $B[$i] ? -1 : 1;
                    }
                }
                return 0;
            });
        }
        foreach ($Arr[$IPType][$SigType]['Total'] as $Param => &$Count) {
            if ($MaxRange === 32) {
                $ThisID = $IPType . preg_replace('~[^\da-z]~i', '_', $SigType . 'Total' . $Param);
                $JS .= 'w(\'' . $ThisID . '\',nft((' . $Count . ').toString()));';
            } elseif ($MaxRange === 128) {
                $Count = $CIDRAM['RangeTablesFinaliseIPv6']($Count);
                $Count[1] = $Count[1] ? '+\' × \'+nft((2).toString())+\'<sup>^\'+nft((' . $Count[1] . ').toString())+\'</sup>\'' : '';
                $ThisID = $IPType . preg_replace('~[^\da-z]~i', '_', $SigType . 'Total' . $Param);
                $JS .= 'w(\'' . $ThisID . '\',' . ($Count[1] ? '\'~\'+' : '') . 'nft((' . $Count[0] . ').toString())' . $Count[1] . ');';
            }
            $Count = '<span id="' . $ThisID . '"></span>';
            if ($Param) {
                $Count = $Param . ' – ' . $Count;
            }
        }
        $Arr[$IPType][$SigType]['Total'] = implode('<br />', $Arr[$IPType][$SigType]['Total']);
        if (count($Arr[$IPType . '-Origin'][$SigType]['Total'])) {
            arsort($Arr[$IPType . '-Origin'][$SigType]['Total']);
            foreach ($Arr[$IPType . '-Origin'][$SigType]['Total'] as $Origin => &$Count) {
                if ($MaxRange === 32) {
                    $ThisID = $IPType . preg_replace('~[^\da-z]~i', '_', $SigType . 'Total' . $Origin);
                    $JS .= 'w(\'' . $ThisID . '\',nft((' . $Count . ').toString()));';
                } elseif ($MaxRange === 128) {
                    $Count = $CIDRAM['RangeTablesFinaliseIPv6']($Count);
                    $Count[1] = $Count[1] ? '+\' × \'+nft((2).toString())+\'<sup>^\'+nft((' . $Count[1] . ').toString())+\'</sup>\'' : '';
                    $ThisID = $IPType . preg_replace('~[^\da-z]~i', '_', $SigType . 'Total' . $Origin);
                    $JS .= 'w(\'' . $ThisID . '\',' . ($Count[1] ? '\'~\'+' : '') . 'nft((' . $Count[0] . ').toString())' . $Count[1] . ');';
                }
                $Count = '<code class="hB">' . $Origin . '</code> – ' . (
                    $CIDRAM['FE']['Flags'] && $Origin !== '??' ? '<span class="flag ' . $Origin . '"></span> – ' : ''
                ) . '<span id="' . $ThisID . '"></span>';
            }
            $Arr[$IPType . '-Origin'][$SigType]['Total'] = implode('<br />', $Arr[$IPType . '-Origin'][$SigType]['Total']);
            $Arr[$IPType][$SigType]['Total'] .= '<hr />' . $Arr[$IPType . '-Origin'][$SigType]['Total'];
        }
    } else {
        $StatClass = 's';
        $Arr[$IPType][$SigType]['Total'] = '';
    }
    if ($Arr[$IPType][$SigType]['Total']) {
        if (!isset($Out[$IPType . '/Total'])) {
            $Out[$IPType . '/Total'] = '';
        }
        $Out[$IPType . '/Total'] .= '<span style="display:none" class="' . $Class . ' ' . $StatClass . '">' . $Arr[$IPType][$SigType]['Total'] . '</span>';
    }
};

/**
 * Range tables handler.
 *
 * @param array $IPv4 The currently active IPv4 signature files.
 * @param array $IPv6 The currently active IPv6 signature files.
 * @return string Some JavaScript generated to populate the range tables data.
 */
$CIDRAM['RangeTablesHandler'] = function (array $IPv4, array $IPv6) use (&$CIDRAM) {
    $CIDRAM['FE']['rangeCatOptions'] = '';
    $Arr = ['IPv4' => [], 'IPv4-Origin' => [], 'IPv6' => [], 'IPv6-Origin' => []];
    $SigTypes = ['Run', 'Whitelist', 'Greylist', 'Deny'];
    foreach ($SigTypes as $SigType) {
        $Arr['IPv4'][$SigType] = ['Total' => []];
        $Arr['IPv4-Origin'][$SigType] = ['Total' => []];
        $Arr['IPv6'][$SigType] = ['Total' => []];
        $Arr['IPv6-Origin'][$SigType] = ['Total' => []];
        for ($Range = 1; $Range <= 32; $Range++) {
            $Arr['IPv4'][$SigType][$Range] = [];
            $Arr['IPv4-Origin'][$SigType][$Range] = [];
        }
        for ($Range = 1; $Range <= 128; $Range++) {
            $Arr['IPv6'][$SigType][$Range] = [];
            $Arr['IPv6-Origin'][$SigType][$Range] = [];
        }
    }
    $Out = [];
    $CIDRAM['RangeTablesIterateFiles']($Arr, $IPv4, $SigTypes, 32, 'IPv4');
    $CIDRAM['RangeTablesIterateFiles']($Arr, $IPv6, $SigTypes, 128, 'IPv6');
    $CIDRAM['FE']['Labels'] = '';
    $JS = '';
    foreach ($SigTypes as $SigType) {
        $Class = 'sigtype_' . strtolower($SigType);
        $CIDRAM['FE']['rangeCatOptions'] .= "\n      <option value=\"" . $Class . '">' . $SigType . '</option>';
        $CIDRAM['FE']['Labels'] .= '<span style="display:none" class="s ' . $Class . '">' . $CIDRAM['L10N']->getString('label_signature_type') . ' ' . $SigType . '</span>';
        if ($SigType === 'Run') {
            $ZeroPlus = 'txtOe';
        } else {
            $ZeroPlus = ($SigType === 'Whitelist' || $SigType === 'Greylist') ? 'txtGn' : 'txtRd';
        }
        $CIDRAM['RangeTablesIterateData']($Arr, $Out, $JS, $SigType, 32, 'IPv4', $ZeroPlus, $Class);
        $CIDRAM['RangeTablesIterateData']($Arr, $Out, $JS, $SigType, 128, 'IPv6', $ZeroPlus, $Class);
    }
    $CIDRAM['FE']['RangeRows'] = '';
    foreach ([['IPv4', 32], ['IPv6', 128]] as $Build) {
        for ($Range = 1; $Range <= $Build[1]; $Range++) {
            $Label = $Build[0] . '/' . $Range;
            if (!empty($Out[$Label])) {
                foreach ($SigTypes as $SigType) {
                    $Class = 'sigtype_' . strtolower($SigType);
                    if (strpos($Out[$Label], $Class) === false) {
                        $Out[$Label] .= '<span style="display:none" class="' . $Class . ' s">-</span>';
                    }
                }
                $ThisArr = ['RangeType' => $Label, 'NumOfCIDRs' => $Out[$Label], 'state_loading' => $CIDRAM['L10N']->getString('state_loading')];
                $CIDRAM['FE']['RangeRows'] .= $CIDRAM['ParseVars']($ThisArr, $CIDRAM['FE']['RangeRow']);
            }
        }
    }
    foreach (['IPv4', 'IPv6'] as $IPType) {
        $Label = $IPType . '/' . $CIDRAM['L10N']->getString('label_total');
        $Internal = $IPType . '/Total';
        if (!empty($Out[$Internal])) {
            foreach ($SigTypes as $SigType) {
                $Class = 'sigtype_' . strtolower($SigType);
                if (strpos($Out[$Internal], $Class) === false) {
                    $Out[$Internal] .= '<span style="display:none" class="' . $Class . ' s">-</span>';
                }
            }
            $ThisArr = ['RangeType' => $Label, 'NumOfCIDRs' => $Out[$Internal], 'state_loading' => $CIDRAM['L10N']->getString('state_loading')];
            $CIDRAM['FE']['RangeRows'] .= $CIDRAM['ParseVars']($ThisArr, $CIDRAM['FE']['RangeRow']);
        }
    }
    return $JS;
};

/**
 * Assign some basic variables (initial prepwork for most front-end pages).
 *
 * @param string $Title The page title.
 * @param string $Tips The page "tip" to include ("Hello username! Here you can...").
 * @param bool $JS Whether to include the standard front-end JavaScript boilerplate.
 */
$CIDRAM['InitialPrepwork'] = function ($Title = '', $Tips = '', $JS = true) use (&$CIDRAM) {

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = 'CIDRAM – ' . $Title;

    /** Fetch and prepare username. */
    if ($Username = (empty($CIDRAM['FE']['UserRaw']) ? '' : $CIDRAM['FE']['UserRaw'])) {
        $Username = preg_replace('~^([^<>]+)<[^<>]+>$~', '\1', $Username);
        if (($AtChar = strpos($Username, '@')) !== false) {
            $Username = substr($Username, 0, $AtChar);
        }
    }

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](['username' => $Username], $Tips);

    /** Load main front-end JavaScript data. */
    $CIDRAM['FE']['JS'] = $JS ? $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('scripts.js')) : '';

};

/**
 * Send page output for front-end pages (plus some other final prepwork).
 *
 * @return string Page output.
 */
$CIDRAM['SendOutput'] = function () use (&$CIDRAM) {
    if ($CIDRAM['FE']['JS']) {
        $CIDRAM['FE']['JS'] = "\n<script type=\"text/javascript\">" . $CIDRAM['FE']['JS'] . '</script>';
    }
    return $CIDRAM['ParseVars']($CIDRAM['L10N']->Data + $CIDRAM['FE'], $CIDRAM['FE']['Template']);
};

/**
 * Confirm whether a file is a logfile (used by the file manager and logs viewer).
 *
 * @param string $File The path/name of the file to be confirmed.
 * @return bool True if it's a logfile; False if it isn't.
 */
$CIDRAM['FileManager-IsLogFile'] = function ($File) use (&$CIDRAM) {
    static $Pattern_logfile = false;
    if (!$Pattern_logfile && $CIDRAM['Config']['general']['logfile']) {
        $Pattern_logfile = $CIDRAM['BuildLogPattern']($CIDRAM['Config']['general']['logfile'], true);
    }
    static $Pattern_logfileApache = false;
    if (!$Pattern_logfileApache && $CIDRAM['Config']['general']['logfileApache']) {
        $Pattern_logfileApache = $CIDRAM['BuildLogPattern']($CIDRAM['Config']['general']['logfileApache'], true);
    }
    static $Pattern_logfileSerialized = false;
    if (!$Pattern_logfileSerialized && $CIDRAM['Config']['general']['logfileSerialized']) {
        $Pattern_logfileSerialized = $CIDRAM['BuildLogPattern']($CIDRAM['Config']['general']['logfileSerialized'], true);
    }
    static $Pattern_FrontEndLog = false;
    if (!$Pattern_FrontEndLog && $CIDRAM['Config']['general']['FrontEndLog']) {
        $Pattern_FrontEndLog = $CIDRAM['BuildLogPattern']($CIDRAM['Config']['general']['FrontEndLog'], true);
    }
    static $Pattern_reCAPTCHA_logfile = false;
    if (!$Pattern_reCAPTCHA_logfile && $CIDRAM['Config']['recaptcha']['logfile']) {
        $Pattern_reCAPTCHA_logfile = $CIDRAM['BuildLogPattern']($CIDRAM['Config']['recaptcha']['logfile'], true);
    }
    static $Pattern_PHPMailer_EventLog = false;
    if (!$Pattern_PHPMailer_EventLog && $CIDRAM['Config']['PHPMailer']['EventLog']) {
        $Pattern_PHPMailer_EventLog = $CIDRAM['BuildLogPattern']($CIDRAM['Config']['PHPMailer']['EventLog'], true);
    }
    return preg_match('~\.log(?:\.gz)?$~', strtolower($File)) || (
        $CIDRAM['Config']['general']['logfile'] && preg_match($Pattern_logfile, $File)
    ) || (
        $CIDRAM['Config']['general']['logfileApache'] && preg_match($Pattern_logfileApache, $File)
    ) || (
        $CIDRAM['Config']['general']['logfileSerialized'] && preg_match($Pattern_logfileSerialized, $File)
    ) || (
        $CIDRAM['Config']['general']['FrontEndLog'] && preg_match($Pattern_FrontEndLog, $File)
    ) || (
        $CIDRAM['Config']['recaptcha']['logfile'] && preg_match($Pattern_reCAPTCHA_logfile, $File)
    ) || (
        $CIDRAM['Config']['PHPMailer']['EventLog'] && preg_match($Pattern_PHPMailer_EventLog, $File)
    );
};

/**
 * Generates JavaScript snippets for confirmation prompts for front-end actions.
 *
 * @param string $Action The action being taken to be confirmed.
 * @param string $Form The ID of the form to be submitted when the action is confirmed.
 * @return string The JavaScript snippet.
 */
$CIDRAM['GenerateConfirm'] = function ($Action, $Form) use (&$CIDRAM) {
    $Confirm = str_replace(["'", '"'], ["\'", '\x22'], sprintf($CIDRAM['L10N']->getString('confirm_action'), $Action));
    return 'javascript:confirm(\'' . $Confirm . '\')&&document.getElementById(\'' . $Form . '\').submit()';
};

/**
 * A quicker way to add entries to the front-end logfile.
 *
 * @param string $IPAddr The IP address triggering the log event.
 * @param string $User The user triggering the log event.
 * @param string $Message The message to be logged.
 */
$CIDRAM['FELogger'] = function ($IPAddr, $User, $Message) use (&$CIDRAM) {

    /** Guard. */
    if (!$CIDRAM['Config']['general']['FrontEndLog'] || empty($CIDRAM['FE']['DateTime'])) {
        return;
    }

    /** Applies formatting for dynamic log filenames. */
    $File = $CIDRAM['TimeFormat']($CIDRAM['Now'], $CIDRAM['Config']['general']['FrontEndLog']);

    $Data = $CIDRAM['Config']['legal']['pseudonymise_ip_addresses'] ? $CIDRAM['Pseudonymise-IP']($IPAddr) : $IPAddr;
    $Data .= ' - ' . $CIDRAM['FE']['DateTime'] . ' - "' . $User . '" - ' . $Message . "\n";

    $WriteMode = (!file_exists($CIDRAM['Vault'] . $File) || (
        $CIDRAM['Config']['general']['truncate'] > 0 &&
        filesize($CIDRAM['Vault'] . $File) >= $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate'])
    )) ? 'w' : 'a';

    /** Build the path to the log and write it. */
    if ($CIDRAM['BuildLogPath']($File)) {
        $Handle = fopen($CIDRAM['Vault'] . $File, $WriteMode);
        fwrite($Handle, $Data);
        fclose($Handle);
        if ($WriteMode === 'w') {
            $CIDRAM['LogRotation']($CIDRAM['Config']['general']['FrontEndLog']);
        }
    }
};

/**
 * Wrapper for PHPMailer functionality.
 *
 * @param array $Recipients An array of recipients to send to.
 * @param string $Subject The subject line of the email.
 * @param string $Body The HTML content of the email.
 * @param string $AltBody The alternative plain-text content of the email.
 * @param array $Attachments An optional array of attachments.
 * @return bool Operation failed (false) or succeeded (true).
 */
$CIDRAM['SendEmail'] = function (array $Recipients = [], $Subject = '', $Body = '', $AltBody = '', array $Attachments = []) use (&$CIDRAM) {

    /** Prepare event logging. */
    $EventLogData = sprintf(
        '%s - %s - ',
        $CIDRAM['Config']['legal']['pseudonymise_ip_addresses'] ? $CIDRAM['Pseudonymise-IP']($_SERVER[$CIDRAM['IPAddr']]) : $_SERVER[$CIDRAM['IPAddr']],
        isset($CIDRAM['FE']['DateTime']) ? $CIDRAM['FE']['DateTime'] : $CIDRAM['TimeFormat'](
            $CIDRAM['Now'],
            $CIDRAM['Config']['general']['timeFormat']
        )
    );

    /** Operation success state. */
    $State = false;

    /** Check whether class exists to either load it and continue or fail the operation. */
    if (!class_exists('\PHPMailer\PHPMailer\PHPMailer')) {
        $EventLogData .= $CIDRAM['L10N']->getString('state_failed_missing') . "\n";
    } else {
        try {

            /** Create a new PHPMailer instance. */
            $Mail = new \PHPMailer\PHPMailer\PHPMailer();

            /** Tell PHPMailer to use SMTP. */
            $Mail->isSMTP();

            /** Disable debugging. */
            $Mail->SMTPDebug = 0;

            /** Skip authorisation process for some extreme problematic cases. */
            if ($CIDRAM['Config']['PHPMailer']['SkipAuthProcess']) {
                $Mail->SMTPOptions = ['ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]];
            }

            /** Set mail server hostname. */
            $Mail->Host = $CIDRAM['Config']['PHPMailer']['Host'];

            /** Set the SMTP port. */
            $Mail->Port = $CIDRAM['Config']['PHPMailer']['Port'];

            /** Set the encryption system to use. */
            if (
                !empty($CIDRAM['Config']['PHPMailer']['SMTPSecure']) &&
                $CIDRAM['Config']['PHPMailer']['SMTPSecure'] !== '-'
            ) {
                $Mail->SMTPSecure = $CIDRAM['Config']['PHPMailer']['SMTPSecure'];
            }

            /** Set whether to use SMTP authentication. */
            $Mail->SMTPAuth = $CIDRAM['Config']['PHPMailer']['SMTPAuth'];

            /** Set the username to use for SMTP authentication. */
            $Mail->Username = $CIDRAM['Config']['PHPMailer']['Username'];

            /** Set the password to use for SMTP authentication. */
            $Mail->Password = $CIDRAM['Config']['PHPMailer']['Password'];

            /** Set the email sender address and name. */
            $Mail->setFrom(
                $CIDRAM['Config']['PHPMailer']['setFromAddress'],
                $CIDRAM['Config']['PHPMailer']['setFromName']
            );

            /** Set the optional "reply to" address and name. */
            if (
                !empty($CIDRAM['Config']['PHPMailer']['addReplyToAddress']) &&
                !empty($CIDRAM['Config']['PHPMailer']['addReplyToName'])
            ) {
                $Mail->addReplyTo(
                    $CIDRAM['Config']['PHPMailer']['addReplyToAddress'],
                    $CIDRAM['Config']['PHPMailer']['addReplyToName']
                );
            }

            /** Used by logging when send succeeds. */
            $SuccessDetails = '';

            /** Set the recipient address and name. */
            foreach ($Recipients as $Recipient) {
                if (empty($Recipient['Address']) || empty($Recipient['Name'])) {
                    continue;
                }
                $Mail->addAddress($Recipient['Address'], $Recipient['Name']);
                $SuccessDetails .= (($SuccessDetails) ? ', ' : '') . $Recipient['Name'] . ' <' . $Recipient['Address'] . '>';
            }

            /** Set the subject line of the email. */
            $Mail->Subject = $Subject;

            /** Tell PHPMailer that the email is written using HTML. */
            $Mail->isHTML = true;

            /** Set the HTML body of the email. */
            $Mail->Body = $Body;

            /** Set the alternative, plain-text body of the email. */
            $Mail->AltBody = $AltBody;

            /** Process attachments. */
            foreach ($Attachments as $Attachment) {
                $Mail->addAttachment($Attachment);
            }

            /** Send it! */
            $State = $Mail->send();

            /** Log the results of the send attempt. */
            $EventLogData .= ($State ? sprintf(
                $CIDRAM['L10N']->getString('state_email_sent'),
                $SuccessDetails
            ) : $CIDRAM['L10N']->getString('response_error') . ' - ' . $Mail->ErrorInfo) . "\n";

        } catch (\Exception $e) {

            /** An exeption occurred. Log the information. */
            $EventLogData .= $CIDRAM['L10N']->getString('response_error') . ' - ' . $e->getMessage() . "\n";

        }
    }

    /** Write to the event log. */
    $CIDRAM['Events']->fireEvent('writeToPHPMailerEventLog', $EventLogData);

    /** Exit. */
    return $State;
};

/**
 * Generates very simple 8-digit numbers used for 2FA.
 *
 * @return int An 8-digit number.
 */
$CIDRAM['2FA-Number'] = function () {
    static $MinInt = 10000000;
    static $MaxInt = 99999999;
    if (function_exists('random_int')) {
        try {
            $Key = random_int($MinInt, $MaxInt);
        } catch (\Exception $e) {
            $Key = rand($MinInt, $MaxInt);
        }
    }
    return isset($Key) ? $Key : rand($MinInt, $MaxInt);
};

/**
 * Generates the rules data displayed on the auxiliary rules page.
 */
$CIDRAM['AuxGenerateFEData'] = function () use (&$CIDRAM) {

    /** Populate output here. */
    $Output = '';

    /** Potential sources. */
    $Sources = preg_replace('~(?: | )?(?:：|:) ?$~', '', $CIDRAM['SourcesL10N']);

    /** Potential modes. */
    static $Modes = ['Whitelist', 'Greylist', 'Block', 'Bypass'];

    /** Attempt to parse the auxiliary rules file. */
    if (!isset($CIDRAM['AuxData'])) {
        $CIDRAM['AuxData'] = (new \Maikuolan\Common\YAML($CIDRAM['ReadFile']($CIDRAM['Vault'] . 'auxiliary.yaml')))->Data;
    }

    /** Count entries (needed for offering first and last move options). */
    $Count = count($CIDRAM['AuxData']);
    $Current = 1;

    /** Iterate through the auxiliary rules. */
    foreach ($CIDRAM['AuxData'] as $Name => $Data) {

        /** Rule row ID. */
        $RuleClass = preg_replace('~^0+~', '', bin2hex($Name));

        /** Figure out what options are available for the rule. */
        $Options = ['<input type="button" onclick="javascript:%1$s(\'%2$s\',\'%3$s\')" value="%4$s" class="auto" />'];
        $Options['delRule'] = sprintf($Options[0], 'delRule', $Name, $RuleClass, $CIDRAM['L10N']->getString('field_delete'));
        if ($Count > 1) {
            if ($Current !== 1) {
                $Options['moveToTop'] = sprintf($Options[0], 'moveToTop', $Name, $RuleClass, $CIDRAM['L10N']->getString('label_aux_move_top'));
            }
            if ($Current !== $Count) {
                $Options['moveToBottom'] = sprintf($Options[0], 'moveToBottom', $Name, $RuleClass, $CIDRAM['L10N']->getString('label_aux_move_bottom'));
            }
            $Current++;
        }
        $Options[0] = '';
        $Options = implode('', $Options);

        /** Begin generating rule output. */
        $Output .= sprintf(
            '        <tr class="%2$s">%1$s  <td class="h4"><div class="s">%3$s</div></td>%1$s  <td class="h4f">%4$s</td>%1$s</tr>' .
            '%1$s<tr class="%2$s">%1$s  <td class="h3f" colspan="2">',
            "\n        ",
            $RuleClass,
            $Name,
            $Options
        );

        /** Detailed reason. */
        if (!empty($Data['Reason'])) {
            $Output .= '<span class="s">' . $CIDRAM['L10N']->getString('label_aux_reason') . '</span><br />';
            $Output .= '<ul><li>' . $Data['Reason'] . '</li></ul>';
        }

        /** Iterate through actions. */
        foreach ([
            ['Whitelist', 'optActWhl'],
            ['Greylist', 'optActGrl'],
            ['Block', 'optActBlk'],
            ['Bypass', 'optActByp'],
            ['Don\'t log', 'optActLog']
        ] as $Action) {

            /** Skip action if the current rule doesn't use this action. */
            if (empty($Data[$Action[0]])) {
                continue;
            }

            /** Show the appropriate label for this action. */
            $Output .= '<span class="s">' . $CIDRAM['FE'][$Action[1]] . '</span><br />';

            /** Show the method to be used. */
            $Output .= '<span class="s">' . (isset($Data['Method']) ? (
                $Data['Method'] === 'RegEx' ? $CIDRAM['FE']['optMtdReg'] : (
                    $Data['Method'] === 'WinEx' ? $CIDRAM['FE']['optMtdWin'] : $CIDRAM['FE']['optMtdStr']
                )
            ) : $CIDRAM['FE']['optMtdStr']) . '</span><br />';

            /** Begin writing conditions list. */
            $Output .= '<ul>';

            /** List all "not equals" conditions . */
            if (!empty($Data[$Action[0]]['But not if matches'])) {

                /** Iterate through sources. */
                foreach ($Data[$Action[0]]['But not if matches'] as $Source => $Values) {
                    $ThisSource = isset($Sources[$Source]) ? $Sources[$Source] : $Source;
                    if (!is_array($Values)) {
                        $Values = [$Values];
                    }
                    foreach ($Values as $Value) {
                        $Output .= "\n            <li>" . $ThisSource . ' ≠ <code>' . $Value . '</code></li>';
                    }
                }

            }

            /** List all "equals" conditions . */
            if (!empty($Data[$Action[0]]['If matches'])) {

                /** Iterate through sources. */
                foreach ($Data[$Action[0]]['If matches'] as $Source => $Values) {
                    $ThisSource = isset($Sources[$Source]) ? $Sources[$Source] : $Source;
                    if (!is_array($Values)) {
                        $Values = [$Values];
                    }
                    foreach ($Values as $Value) {
                        $Output .= "\n            <li>" . $ThisSource . ' = <code>' . $Value . '</code></li>';
                    }
                }

            }

            /** Finish writing conditions list. */
            $Output .= "\n          </ul><br />";

        }

        /** Describe matching logic used. */
        if (!empty($Data['Logic']) && $Data['Logic'] !== 'Any') {
            $Output .= '<em>' . $CIDRAM['L10N']->getString('label_aux_logic_all') . '</em>';
        } else {
            $Output .= '<em>' . $CIDRAM['L10N']->getString('label_aux_logic_any') . '</em>';
        }

        /** Finish writing new rule. */
        $Output .= "</td>\n        </tr>\n";

    }

    /** Exit with generated output. */
    return $Output;

};

/**
 * Generate select options from an associative array.
 *
 * @param array $Options An associative array of the options to generate.
 * @param string $Trim An optional regex of data to remove from labels.
 * @return string The generated options.
 */
$CIDRAM['GenerateOptions'] = function (array $Options, $Trim = '') {
    $Output = '';
    foreach ($Options as $Value => $Label) {
        if ($Trim) {
            $Label = preg_replace($Trim, '', $Label);
        }
        $Output .= '<option value="' . $Value . '">' . $Label . '</option>';
    }
    return $Output;
};

/**
 * Generate a clickable list from an array.
 *
 * @param array $Arr The array to convert from.
 * @param string $DeleteKey The key to use for async calls to delete a cache entry.
 * @param int $Depth Current cache entry list depth.
 * @param string $ParentKey An optional key of the parent data source.
 * @return string The generated clickable list.
 */
$CIDRAM['ArrayToClickableList'] = function (array $Arr = [], $DeleteKey = '', $Depth = 0, $ParentKey = '') use (&$CIDRAM) {
    $Output = '';
    $Count = count($Arr);
    $Prefix = substr($DeleteKey, 0, 2) === 'fe' ? 'FE' : '';
    foreach ($Arr as $Key => $Value) {
        $Delete = ($Depth === 0) ? ' – (<span style="cursor:pointer" onclick="javascript:' . $DeleteKey . '(\'' . addslashes($Key) . '\')"><code class="s">' . $CIDRAM['L10N']->getString('field_delete') . '</code></span>)' : '';
        $Output .= ($Depth === 0 ? '<span id="' . $Key . $Prefix . 'Container">' : '') . '<li>';
        if (!is_array($Value)) {
            if (substr($Value, 0, 2) === '{"' && substr($Value, -2) === '"}') {
                $Try = json_decode($Value, true);
                if ($Try !== null) {
                    $Value = $Try;
                }
            } elseif (
                preg_match('~\.ya?ml$~i', $Key) ||
                (preg_match('~^(?:Data|\d+)$~', $Key) && preg_match('~\.ya?ml$~i', $ParentKey)) ||
                substr($Value, 0, 4) === "---\n"
            ) {
                $Try = new \Maikuolan\Common\YAML();
                if ($Try->process($Value, $Try->Data) && !empty($Try->Data)) {
                    $Value = $Try->Data;
                }
            } elseif (substr($Value, 0, 2) === '["' && substr($Value, -2) === '"]' && strpos($Value, '","') !== false) {
                $Value = explode('","', substr($Value, 2, -2));
            }
        }
        if (is_array($Value)) {
            if ($Depth === 0) {
                $SizeField = $CIDRAM['L10N']->getString('field_size') ?: 'Size';
                $Size = isset($Value['Data']) && is_string($Value['Data']) ? strlen($Value['Data']) : (
                    isset($Value[0]) && is_string($Value[0]) ? strlen($Value[0]) : false
                );
                if ($Size !== false) {
                    $CIDRAM['FormatFilesize']($Size);
                    $Value[$SizeField] = $Size;
                }
            }
            $Output .= '<span class="comCat" style="cursor:pointer"><code class="s">' . str_replace(['<', '>'], ['&lt;', '&gt;'], $Key) . '</code></span>' . $Delete . '<ul class="comSub">';
            $Output .= $CIDRAM['ArrayToClickableList']($Value, $DeleteKey, $Depth + 1, $Key);
            $Output .= '</ul>';
        } else {
            if ($Key === 'Time' && preg_match('~^\d+$~', $Value)) {
                $Key = $CIDRAM['L10N']->getString('label_expires');
                $Value = $CIDRAM['TimeFormat']($Value, $CIDRAM['Config']['general']['timeFormat']);
            }
            $Class = ($Key === $CIDRAM['L10N']->getString('field_size') || $Key === $CIDRAM['L10N']->getString('label_expires')) ? 'txtRd' : 's';
            $Text = ($Count === 1 && $Key === 0) ? $Value : $Key . ($Class === 's' ? ' => ' : '') . $Value;
            $Output .= '<code class="' . $Class . '" style="word-wrap:break-word;word-break:break-all">' . str_replace(['<', '>'], ['&lt;', '&gt;'], $Text) . '</code>' . $Delete;
        }
        $Output .= '</li>' . ($Depth === 0 ? '<br /></span>' : '');
    }
    return $Output;
};

/**
 * Append to the current state message.
 *
 * @param string $Message What to append.
 */
$CIDRAM['Message'] = function ($Message) use (&$CIDRAM) {
    if (isset($CIDRAM['FE']['state_msg'])) {
        if ($CIDRAM['FE']['state_msg'] || substr($CIDRAM['FE']['state_msg'], -6) !== '<br />') {
            $CIDRAM['FE']['state_msg'] .= '<br />';
        }
        $CIDRAM['FE']['state_msg'] .= $Message . '<br />';
    }
};

/**
 * Supplied string is used to generate arbitrary values used as RGB information
 * for CSS styling.
 *
 * @param string $String The supplied string to use.
 * @param int $Mode Whether to return the values as an array of integers,
 *      a hash-like string, or both.
 * @return string|array an array of integers, a hash-like string, or both.
 */
$CIDRAM['RGB'] = function ($String = '', $Mode = 0) {
    $Diff = [247, 127, 31];
    if (is_string($String) && !empty($String)) {
        $String = str_split($String);
        foreach ($String as $Char) {
            $Char = ord($Char);
            $Diff[0] = ($Diff[0] >> 1) + (($Diff[2] & 1) === 1 ? 128 : 0);
            $Diff[1] = ($Diff[1] >> 1) + (($Diff[0] & 1) === 1 ? 128 : 0);
            $Diff[2] = ($Diff[2] >> 1) + (($Diff[1] & 1) === 1 ? 128 : 0);
            $Diff[0] ^= $Char;
        }
    }
    if ($Mode === 1) {
        return $Diff;
    }
    for ($Hash = '', $Index = 0; $Index < 3; $Index++) {
        $Hash .= str_pad(bin2hex(chr($Diff[$Index])), 2, '0', STR_PAD_LEFT);
    }
    if ($Mode === 2) {
        return $Hash;
    }
    return ['Values' => $Diff, 'Hash' => $Hash];
};

/**
 * Provides stronger support for LTR inside RTL text.
 *
 * @param string $String The string to work with.
 * @return string The string, modified if necessary.
 */
$CIDRAM['LTRinRTF'] = function ($String = '') use (&$CIDRAM) {

    /** Get direction. */
    $Direction = (
        !isset($CIDRAM['L10N']) ||
        empty($CIDRAM['L10N']->Data['Text Direction']) ||
        $CIDRAM['L10N']->Data['Text Direction'] !== 'rtl'
    ) ? 'ltr' : 'rtl';

    /** If the page isn't RTL, the string should be returned verbatim. */
    if ($Direction !== 'rtl') {
        return $String;
    }

    /** Modify the string to better suit RTL directionality and return it. */
    return preg_replace(
        ['~^(.+)-&gt;(.+)$~i', '~^(.+)➡(.+)$~i'],
        ['\2&lt;-\1', '\2⬅\1'],
        $String
    );

};
