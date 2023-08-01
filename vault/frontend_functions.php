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
 * This file: Front-end functions file (last modified: 2023.08.01).
 */

/**
 * Syncs downstream metadata with upstream metadata to remove superfluous
 * entries. Installed components are ignored.
 *
 * @param string $Downstream Downstream/local data.
 * @param string $Upstream Upstream/remote data.
 * @return string Patched/synced data (or an empty string on failure).
 */
$CIDRAM['Congruency'] = function (string $Downstream, string $Upstream) use (&$CIDRAM): string {
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
$CIDRAM['Delete'] = function (string $File) use (&$CIDRAM): bool {
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
$CIDRAM['In'] = function (string $Query) use (&$CIDRAM): bool {
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
        $Data = preg_replace($QueryParts[2], ($QueryParts[4] ?? ''), $Data);
        return $CIDRAM['Updater-IO']->writeFile($CIDRAM['Vault'] . $QueryParts[0], $Data);
    }

    /** Nothing done. Return false (failure). */
    return false;
};

/**
 * Adds integer values; Returns zero if the sum total is negative or if any
 * contained values aren't integers, and otherwise, returns the sum total.
 */
$CIDRAM['ZeroMin'] = function (...$Values): int {
    $Sum = 0;
    foreach ($Values as $Value) {
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
 * @return void
 */
$CIDRAM['FormatFilesize'] = function (int &$Filesize) use (&$CIDRAM): void {
    $Scale = ['field_size_bytes', 'field_size_KB', 'field_size_MB', 'field_size_GB', 'field_size_TB', 'field_size_PB'];
    $Iterate = 0;
    while ($Filesize > 1024) {
        $Filesize /= 1024;
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
 * @return void
 */
$CIDRAM['FECacheRemove'] = function (string &$Source, bool &$Rebuild, string $Entry) use (&$CIDRAM): void {
    /** Override if using a different preferred caching mechanism. */
    if ($CIDRAM['Cache']->Using && $CIDRAM['Cache']->Using !== 'FF') {
        if ($Entry === '__') {
            $CIDRAM['Cache']->clearCache();
        } else {
            $CIDRAM['Cache']->deleteEntry($Entry);
        }
        return;
    }

    /** Default process for clearing all. */
    if ($Entry === '__') {
        $Source = "\n";
        $Rebuild = true;
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
 * @return void
 */
$CIDRAM['FECacheAdd'] = function (string &$Source, bool &$Rebuild, string $Entry, string $Data, int $Expires) use (&$CIDRAM): void {
    /** Override if using a different preferred caching mechanism. */
    if ($CIDRAM['Cache']->Using && $CIDRAM['Cache']->Using !== 'FF') {
        $CIDRAM['Cache']->setEntry($Entry, $Data, $Expires - $CIDRAM['Now']);
        $CIDRAM['CacheEntry-' . $Entry] = $Data;
        return;
    }

    /** Default process. */
    $CIDRAM['FECacheRemove']($Source, $Rebuild, $Entry);
    $NewLine = base64_encode($Entry) . ',' . base64_encode($Data) . ',' . $Expires . "\n";
    $Source .= $NewLine;
    $Rebuild = true;
};

/**
 * Get an entry from the front-end cache data.
 *
 * @param string $Source Variable containing cache file data.
 * @param bool $Rebuild Flag indicating to rebuild cache file.
 * @param string $Entry Name of the cache entry to get.
 * @return string|bool Returned cache entry data (or false on failure).
 */
$CIDRAM['FECacheGet'] = function (string &$Source, string $Entry) use (&$CIDRAM) {
    /** Override if using a different preferred caching mechanism. */
    if ($CIDRAM['Cache']->Using && $CIDRAM['Cache']->Using !== 'FF') {
        /** Check whether already fetched for this instance. */
        if (isset($CIDRAM['CacheEntry-' . $Entry])) {
            return $CIDRAM['CacheEntry-' . $Entry];
        }

        /** Fetch the cache entry. */
        return $CIDRAM['CacheEntry-' . $Entry] = $CIDRAM['Cache']->getEntry($Entry);
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
 * Remove sub-arrays from an array.
 *
 * @param array $Arr An array.
 * @return array An array.
 */
$CIDRAM['ArrayFlatten'] = function (array $Arr): array {
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
$CIDRAM['IsolateL10N'] = function (array &$Arr, string $Lang) {
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
$CIDRAM['AppendToString'] = function (string &$String, string $Delimit = '', string $Append = '') {
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
$CIDRAM['SanityCheck'] = function (string $FileName, string $FileData): bool {
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
};

/**
 * Used by the file manager to generate a list of the files contained in a
 * working directory (normally, the vault).
 *
 * @param string $Base The path to the working directory.
 * @return array A list of the files contained in the working directory.
 */
$CIDRAM['FileManager-RecursiveList'] = function (string $Base) use (&$CIDRAM): array {
    $Arr = [];
    $Key = -1;
    $Offset = strlen($Base);
    $List = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($Base, \RecursiveDirectoryIterator::FOLLOW_SYMLINKS), \RecursiveIteratorIterator::SELF_FIRST);
    foreach ($List as $Item => $List) {
        $Key++;
        $ThisName = substr($Item, $Offset);
        if (preg_match('~^(?:/\.\.|./\.|\.{3})$~', str_replace("\\", '/', substr($Item, -3)))) {
            continue;
        }
        $Arr[$Key] = ['Filename' => $ThisName, 'CanEdit' => false];
        if (is_dir($Item)) {
            $Arr[$Key]['Directory'] = true;
            $Arr[$Key]['Filesize'] = 0;
            $Arr[$Key]['Filetype'] = $CIDRAM['L10N']->getString('field_filetype_directory');
            $Arr[$Key]['Icon'] = 'icon=directory';
        } elseif (is_file($Item)) {
            $Arr[$Key]['Directory'] = false;
            $Arr[$Key]['Filesize'] = filesize($Item);
            $Arr[$Key]['Filetype'] = $CIDRAM['L10N']->getString('field_filetype_unknown');
            $Arr[$Key]['Icon'] = 'icon=text';
            if (isset($CIDRAM['FE']['TotalSize'])) {
                $CIDRAM['FE']['TotalSize'] += $Arr[$Key]['Filesize'];
            }
            if (isset($CIDRAM['Components']['Components'])) {
                $Component = $CIDRAM['L10N']->getString('field_filetype_unknown');
                $ThisNameFixed = str_replace("\\", '/', $ThisName);
                if (isset($CIDRAM['Components']['Files'][$ThisNameFixed])) {
                    $Component = $CIDRAM['Components']['Names'][$CIDRAM['Components']['Files'][$ThisNameFixed]] ?? $CIDRAM['Components']['Files'][$ThisNameFixed];
                } elseif (preg_match('~(?:[^|/]\.ht|\.safety$|^salt\.dat$)~i', $ThisNameFixed)) {
                    $Component = $CIDRAM['L10N']->getString('label_fmgr_safety');
                } elseif (preg_match('~config\.ini$~i', $ThisNameFixed)) {
                    $Component = $CIDRAM['L10N']->getString('link_config');
                } elseif ($CIDRAM['FileManager-IsLogFile']($ThisNameFixed)) {
                    $Component = $CIDRAM['L10N']->getString('link_logs');
                } elseif ($ThisNameFixed === 'auxiliary.yaml') {
                    $Component = $CIDRAM['L10N']->getString('link_aux');
                } elseif (preg_match('/(?:^ignore\.dat|_custom\.dat|\.sig|\.inc)$/i', $ThisNameFixed)) {
                    $Component = $CIDRAM['L10N']->getString('label_fmgr_other_sig');
                } elseif (preg_match('~(?:\.tmp|\.rollback|^(?:cache|hashes|ipbypass|fe_assets/frontend|rl)\.dat)$~i', $ThisNameFixed)) {
                    $Component = $CIDRAM['L10N']->getString('label_fmgr_cache_data');
                } elseif (preg_match('/^(?:components|themes|modules)\.dat$/', $ThisNameFixed)) {
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
                if ($Ext === '') {
                    $CIDRAM['FormatFilesize']($Arr[$Key]['Filesize']);
                    continue;
                }
                $Arr[$Key]['Filetype'] = sprintf($CIDRAM['L10N']->getString('field_filetype_info'), $Ext);
                if ($Ext === 'ICO') {
                    $Arr[$Key]['Icon'] = 'file=' . urlencode($Arr[$Key]['Filename']);
                    $CIDRAM['FormatFilesize']($Arr[$Key]['Filesize']);
                    continue;
                }
                if (preg_match('/^(?:CSV|ODS|XLS[XT]?)$/', $Ext)) {
                    $Arr[$Key]['Icon'] = 'icon=spreadsheet';
                } elseif (preg_match('/^(?:ODP|PDF|PP[ST]X?|XDP)$/', $Ext)) {
                    $Arr[$Key]['Icon'] = 'icon=presentation';
                } elseif (preg_match('/^(?:DOC[XT]?|ODT|RTF)$/', $Ext)) {
                    $Arr[$Key]['Icon'] = 'icon=document';
                } elseif (preg_match('/^(?:[OM]?DB|SQL)$/', $Ext)) {
                    $Arr[$Key]['Icon'] = 'icon=database';
                } elseif (preg_match('/^(?:ODF|TEX)$/', $Ext)) {
                    $Arr[$Key]['Icon'] = 'icon=formulas';
                } elseif (preg_match('/^ODG$/', $Ext)) {
                    $Arr[$Key]['Icon'] = 'icon=graphs';
                } elseif (preg_match(
                    '/^(?:BM[2P]|C(D5|GM)|D(IB|W[FG]|XF)|ECW|FITS|GIF|IMG|J(F?IF?|P[2S]|PE?G?2?|XR)|P(BM|CX|DD|GM|IC|N[GMS]|PM|S[DP])|S(ID|V[AG])|TGA|W(BMP?|EBP|MP)|X(CF|BMP))$/',
                    $Ext
                )) {
                    $Arr[$Key]['Icon'] = 'icon=image';
                } elseif (preg_match(
                    '/^(?:H?264|3GP(P2)?|A(M[CV]|VI)|BIK|D(IVX|V5?)|F([4L][CV]|LASH|MV)|GIFV|HLV|' .
                    'M(4V|OV|P4|PE?G[4V]?|KV|VR)|OGM|SWF|V(IDEO|OB)|W(EBM|M[FV]3?)|X(WMV|VID))$/',
                    $Ext
                )) {
                    $Arr[$Key]['Icon'] = 'icon=video';
                } elseif (preg_match(
                    '/^(?:3GA|A(AC|IFF?|SF|U)|CDA|FLAC?|M(P?4A|IDI|KA|P[A23])|OGG|PCM|' .
                    'R(AM?|M[AX])|SWA|W(AVE?|MA))$/',
                    $Ext
                )) {
                    $Arr[$Key]['Icon'] = 'icon=audio';
                }
                if (preg_match('/^(?:[BD]AT|CSS|[SDX]?HT[AM]L?|INC|JS|MD|NEON|I?NFO|PHP\d?|PY|TXT|YA?ML)$/', $Ext)) {
                    $Arr[$Key]['CanEdit'] = true;
                }
            }
        }
        if ($Arr[$Key]['Filesize']) {
            $CIDRAM['FormatFilesize']($Arr[$Key]['Filesize']);
            $Arr[$Key]['Filesize'] .= ' ⏰ <em>' . $CIDRAM['TimeFormat'](filemtime($Item), $CIDRAM['Config']['general']['time_format']) . '</em>';
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
 * @param array $Arr The array to use for rendering the components metadata.
 * @return void
 */
$CIDRAM['FetchComponentsLists'] = function (string $Base, array &$Arr) use (&$CIDRAM): void {
    $Files = new \DirectoryIterator($Base);
    foreach ($Files as $ThisFile) {
        if (!empty($ThisFile) && preg_match('/\.(?:dat|inc|ya?ml)$/i', $ThisFile)) {
            $Data = $CIDRAM['ReadFile']($Base . $ThisFile);
            if ($Data = $CIDRAM['ExtractPage']($Data)) {
                $CIDRAM['YAML']->process($Data, $Arr);
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
$CIDRAM['FileManager-PathSecurityCheck'] = function (string $Path): bool {
    $Path = str_replace("\\", '/', $Path);
    if (
        preg_match('~(?://|[^!\d\w\._-]$)~i', $Path) ||
        preg_match('~^(?:/\.\.|./\.|\.{3})$~', str_replace("\\", '/', substr($Path, -3)))
    ) {
        return false;
    }
    $Path = preg_split('@/@', $Path, -1, PREG_SPLIT_NO_EMPTY);
    $Valid = true;
    array_walk($Path, function ($Segment) use (&$Valid): void {
        if (empty($Segment) || preg_match('/(?:[\x00-\x1F\x7F]+|^\.+$)/i', $Segment)) {
            $Valid = false;
        }
    });
    return $Valid;
};

/**
 * Used by the logs viewer to generate a list of the log files contained in a
 * working directory (normally, the vault).
 *
 * @param string $Base The path to the working directory.
 * @param string $Order Whether to sort the list in ascending or descending order.
 * @return array A list of the log files contained in the working directory.
 */
$CIDRAM['Logs-RecursiveList'] = function (string $Base, string $Order = 'ascending') use (&$CIDRAM): array {
    $Arr = [];
    $List = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($Base, \RecursiveDirectoryIterator::FOLLOW_SYMLINKS), \RecursiveIteratorIterator::SELF_FIRST);
    foreach ($List as $Item => $List) {
        $ThisName = str_replace("\\", '/', substr($Item, strlen($Base)));
        $Normalised = $ThisName;
        if (!is_file($Item) || !is_readable($Item) || is_dir($Item) || !$CIDRAM['FileManager-IsLogFile']($ThisName, $Normalised)) {
            continue;
        }
        $Arr[$Normalised] = ['Filename' => $ThisName, 'Filesize' => filesize($Item)];
        $CIDRAM['FormatFilesize']($Arr[$Normalised]['Filesize']);
    }
    if ($Order === 'ascending') {
        ksort($Arr);
    } elseif ($Order === 'descending') {
        krsort($Arr);
    }
    return $Arr;
};

/**
 * Checks whether a component is in use (front-end closure).
 *
 * @param array $Component An array of the component metadata.
 * @return int 1 when in use.
 *             0 when not in use.
 *            -1 when *partially* in use (e.g., when misconfigured).
 */
$CIDRAM['IsInUse'] = function (array $Component) use (&$CIDRAM): int {
    if (!empty($Component['Name']) && $Component['Name'] === 'L10N: ' . $CIDRAM['L10N']->getString('Local Name')) {
        return 1;
    }
    $Files = $Component['Files']['To'] ?? [];
    $CIDRAM['Arrayify']($Files);
    $UsedWith = $Component['Used with'] ?? '';
    $Description = $Component['Extended Description'] ?? '';
    if (is_array($Description)) {
        $CIDRAM['IsolateL10N']($Description, $CIDRAM['Config']['general']['lang']);
    }
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
            if ($ThisUsedWith !== 'imports' && preg_match('~\.ya?ml$~i', $File)) {
                continue;
            }
        }
        if (
            preg_match('~^$|\.(?:css|gif|html?|jpe?g|js|png)$|^(?:classes|fe_assets)[\x2F\x5C]~i', $File) ||
            !file_exists($CIDRAM['Vault'] . $File)
        ) {
            continue;
        }
        if (($ThisUsedWith === 'imports' && preg_match(
            '~,(?:[\w\d]+:)?' . $FileSafe . ',~',
            ',' . $CIDRAM['Config']['general']['config_imports'] . ','
        )) || ($ThisUsedWith === 'events' && preg_match(
            '~,(?:[\w\d]+:)?' . $FileSafe . ',~',
            ',' . $CIDRAM['Config']['general']['events'] . ','
        )) || (($ThisUsedWith === 'ipv4' || strpos($Description, 'signatures-&gt;ipv4') !== false) && preg_match(
            '~,(?:[\w\d]+:)?' . $FileSafe . ',~',
            ',' . $CIDRAM['Config']['signatures']['ipv4'] . ','
        )) || (($ThisUsedWith === 'ipv6' || strpos($Description, 'signatures-&gt;ipv6') !== false) && preg_match(
            '~,(?:[\w\d]+:)?' . $FileSafe . ',~',
            ',' . $CIDRAM['Config']['signatures']['ipv6'] . ','
        )) || (($ThisUsedWith === 'modules' || strpos($Description, 'signatures-&gt;modules') !== false) && preg_match(
            '~,(?:[\w\d]+:)?' . $FileSafe . ',~',
            ',' . $CIDRAM['Config']['signatures']['modules'] . ','
        ))) {
            $Out = (!isset($Out) || $Out === 1) ? 1 : -1;
        } else {
            $Out = (!isset($Out) || $Out === 0) ? 0 : -1;
        }
    }
    return $Out ?? 0;
};

/**
 * Determine the final IP address covered by an IPv4 CIDR. This closure is used
 * by the CIDR Calculator.
 *
 * @param string $First The first IP address.
 * @param int $Factor The range number (or CIDR factor number).
 * @return string The final IP address.
 */
$CIDRAM['IPv4GetLast'] = function (string $First, int $Factor): string {
    $Octets = explode('.', $First);
    $Split = $Bracket = $Factor / 8;
    $Split -= floor($Split);
    $Split = (int)(8 - ($Split * 8));
    $Octet = floor($Bracket);
    if ($Octet < 4) {
        $Octets[$Octet] += (2 ** $Split) - 1;
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
$CIDRAM['IPv6GetLast'] = function (string $First, int $Factor): string {
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
        $Octets[$Octet] += (2 ** $Split) - 1;
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
        $Last = preg_replace('/(:0){2,}$/i', '::', $Last, 1);
    } elseif (strpos($Last, ':0:0:') !== false) {
        $Last = preg_replace('/(?:(:0)+:(0:)+|::0$)/i', '::', $Last, 1);
    }
    return $Last;
};

/**
 * Fetch remote data (front-end updates page).
 *
 * @return void
 */
$CIDRAM['FetchRemote'] = function () use (&$CIDRAM): void {
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
 * @return void
 */
$CIDRAM['FetchRemote-ContextFree'] = function (string &$RemoteData, string &$Remote) use (&$CIDRAM): void {
    $RemoteData = $CIDRAM['FECacheGet']($CIDRAM['FE']['Cache'], $Remote);
    if ($RemoteData === false) {
        $RemoteData = $CIDRAM['Request']($Remote);
        if (strtolower(substr($Remote, -2)) === 'gz' && substr($RemoteData, 0, 2) === "\x1F\x8B") {
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
$CIDRAM['IsActivable'] = function (array &$Component) use (&$CIDRAM): bool {
    if (!empty($Component['Used with']) && $CIDRAM['Has']($Component['Used with'], ['imports', 'events', 'ipv4', 'ipv6', 'modules'])) {
        return true;
    }
    if (!isset($Component['Extended Description'])) {
        return false;
    }
    $Description = $Component['Extended Description'];
    if (is_array($Description)) {
        $CIDRAM['IsolateL10N']($Description, $CIDRAM['Config']['general']['lang']);
    }
    return (is_string($Description) && $Description && strpos($Description, 'signatures-&gt;') !== false);
};

/**
 * Deactivate component (front-end updates page).
 *
 * @param string $Type Value can be ipv4, ipv6, or modules.
 * @param string $ID The ID of the component to deactivate.
 * @return void
 */
$CIDRAM['DeactivateComponent'] = function (string $Type, string $ID) use (&$CIDRAM): void {
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
    foreach ($CIDRAM['Components']['Meta'][$ID]['Files']['To'] as $File) {
        $CIDRAM['Deactivation'][$Type] = preg_replace(
            '~,(?:[\w\d]+:)?' . preg_quote($File) . ',~',
            ',',
            $CIDRAM['Deactivation'][$Type]
        );
    }
    $CIDRAM['Deactivation'][$Type] = substr($CIDRAM['Deactivation'][$Type], 1, -1);
    if ($Type === 'imports') {
        if ($CIDRAM['Deactivation']['imports'] !== $CIDRAM['Config']['general']['config_imports']) {
            $CIDRAM['Deactivation']['Modified'] = true;
        }
        return;
    }
    if ($Type === 'events') {
        if ($CIDRAM['Deactivation']['events'] !== $CIDRAM['Config']['general']['events']) {
            $CIDRAM['Deactivation']['Modified'] = true;
        }
        return;
    }
    if ($CIDRAM['Deactivation'][$Type] !== $CIDRAM['Config']['signatures'][$Type]) {
        $CIDRAM['Deactivation']['Modified'] = true;
    }
};

/**
 * Prepares component extended description (front-end updates page).
 *
 * @param array $Arr Metadata of the component to be prepared.
 * @param string $Key A key to use to help find L10N data for the component description.
 * @return void
 */
$CIDRAM['PrepareExtendedDescription'] = function (array &$Arr, string $Key = '') use (&$CIDRAM): void {
    $Key = 'Extended Description ' . $Key;
    if (isset($CIDRAM['L10N']->Data[$Key])) {
        $Arr['Extended Description'] = $CIDRAM['L10N']->getString($Key);
    } elseif (empty($Arr['Extended Description'])) {
        $Arr['Extended Description'] = '';
    }
    if (is_array($Arr['Extended Description'])) {
        $CIDRAM['IsolateL10N']($Arr['Extended Description'], $CIDRAM['Config']['general']['lang']);
    }
    if (
        !empty($Arr['Used with']) &&
        !is_array($Arr['Used with']) &&
        strpos($Arr['Extended Description'], '-&gt;') === false
    ) {
        $Arr['Extended Description'] .= sprintf(
            '<br /><em>%s <code>signatures-&gt;%s</code></em>',
            $CIDRAM['L10N']->getString('label_used_with'),
            $Arr['Used with']
        );
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
        $Arr['Extended Description'] .= sprintf(
            '<br /><em>%s <span class="%s">%s</span></em>',
            $CIDRAM['L10N']->getString('label_false_positive_risk'),
            $Class,
            $State
        );
    }
};

/**
 * Prepares component name (front-end updates page).
 *
 * @param array $Arr Metadata of the component to be prepared.
 * @param string $Key A key to use to help find L10N data for the component name.
 * @return void
 */
$CIDRAM['PrepareName'] = function (array &$Arr, string $Key = '') use (&$CIDRAM): void {
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
 * @param string $Target
 * @return int 1 when the component is in use.
 *             0 when the component is not in use.
 *            -1 when the component is *partially* in use.
 */
$CIDRAM['ComponentFunctionUpdatePrep'] = function (string $Target) use (&$CIDRAM): int {
    if (!empty($CIDRAM['Components']['Meta'][$Target]['Files'])) {
        $CIDRAM['PrepareExtendedDescription']($CIDRAM['Components']['Meta'][$Target]);
        $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$Target]['Files']);
        $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$Target]['Files']['To']);
        return $CIDRAM['IsInUse']($CIDRAM['Components']['Meta'][$Target]);
    }
    return 0;
};

/**
 * Simulates block event (used by the IP tracking and IP test pages).
 *
 * @param string $Addr The IP address to test against.
 * @param bool $Modules Specifies whether to test against modules.
 * @param bool $Aux Specifies whether to test against auxiliary rules.
 * @param bool $Verification Specifies whether to test against search engine and social media verification.
 * @return void
 */
$CIDRAM['SimulateBlockEvent'] = function (string $Addr, bool $Modules = false, bool $Aux = false, bool $Verification = false) use (&$CIDRAM): void {
    $CIDRAM['Stage'] = '';

    /** Reset bypass flags (needed to prevent falsing due to search engine verification). */
    $CIDRAM['ResetBypassFlags']();

    /** Initialise SimulateBlockEvent. */
    foreach ($CIDRAM['Config']['Provide']['Initialise SimulateBlockEvent'] as $InitialiseKey => $InitialiseValue) {
        if (is_array($InitialiseValue) && isset($CIDRAM[$InitialiseKey]) && is_array($CIDRAM[$InitialiseKey])) {
            $CIDRAM[$InitialiseKey] = array_replace_recursive($CIDRAM[$InitialiseKey], $InitialiseValue);
            continue;
        }
        $CIDRAM[$InitialiseKey] = $InitialiseValue;
    }

    /** To be populated by webhooks. */
    $CIDRAM['Webhooks'] = [];

    /** Reset request profiling. */
    $CIDRAM['Profile'] = [];

    /** Reset factors. */
    $CIDRAM['Factors'] = [];

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
        'ASNLookup' => 0,
        'CCLookup' => 'XX',
        'Verified' => '',
        'Expired' => '',
        'Ignored' => '',
        'xmlLang' => $CIDRAM['Config']['general']['lang'],
        'rURI' => 'SimulateBlockEvent'
    ];
    $CIDRAM['BlockInfo']['UALC'] = strtolower($CIDRAM['BlockInfo']['UA']);

    /** Appending query onto the reconstructed URI. */
    if (!empty($CIDRAM['FE']['custom-query'])) {
        $CIDRAM['BlockInfo']['rURI'] .= '?' . $CIDRAM['FE']['custom-query'];
    }

    if (strlen($Addr)) {
        $CIDRAM['Stage'] = 'Tests';

        /** Catch run errors. */
        $CIDRAM['InitialiseErrorHandler']();

        /** Standard IP check. */
        try {
            $CIDRAM['Caught'] = false;
            $CIDRAM['TestResults'] = $CIDRAM['RunTests']($Addr, true);
        } catch (\Exception $e) {
            $CIDRAM['Caught'] = true;
        }

        /** Resolved IP check. */
        if ($CIDRAM['BlockInfo']['IPAddrResolved']) {
            if (!empty($CIDRAM['ThisIP']['IPAddress'])) {
                $CIDRAM['ThisIP']['IPAddress'] .= ' (' . $CIDRAM['BlockInfo']['IPAddrResolved'] . ')';
            }
            try {
                $CIDRAM['TestResults'] = ($CIDRAM['RunTests']($CIDRAM['BlockInfo']['IPAddrResolved'], true) || $CIDRAM['TestResults']);
            } catch (\Exception $e) {
                $CIDRAM['Caught'] = true;
            }
        }

        /** Prepare run errors. */
        $CIDRAM['RunErrors'] = $CIDRAM['Errors'];
        $CIDRAM['RestoreErrorHandler']();
    }

    /** Perform forced hostname lookup if this has been enabled. */
    if ($CIDRAM['Config']['general']['force_hostname_lookup']) {
        $CIDRAM['Hostname'] = $CIDRAM['DNS-Reverse']($CIDRAM['BlockInfo']['IPAddrResolved'] ?: $CIDRAM['BlockInfo']['IPAddr']);
    }

    /** Instantiate report orchestrator (used by some modules). */
    $CIDRAM['Reporter'] = new \CIDRAM\Core\Reporter();

    /** Execute modules, if any have been enabled. */
    if ($Modules && $CIDRAM['Config']['signatures']['modules'] && empty($CIDRAM['Whitelisted'])) {
        $CIDRAM['Stage'] = 'Modules';
        if (!isset($CIDRAM['ModuleResCache'])) {
            $CIDRAM['ModuleResCache'] = [];
        }
        $CIDRAM['InitialiseErrorHandler']();
        $Modules = explode(',', $CIDRAM['Config']['signatures']['modules']);
        if (!$CIDRAM['Config']['signatures']['tracking_override']) {
            $RestoreTrackingOptionsOverride = $CIDRAM['Tracking options override'] ?? '';
        }

        /**
         * Doing this with array_walk instead of foreach to ensure that modules
         * have their own scope and that superfluous data isn't preserved.
         */
        array_walk($Modules, function ($Module) use (&$CIDRAM): void {
            if (
                !empty($CIDRAM['Whitelisted']) ||
                preg_match('~^(?:classes|fe_assets)[\x2F\x5C]|\.(css|gif|html?|jpe?g|js|png|ya?ml)$~i', $Module)
            ) {
                return;
            }
            $Module = (strpos($Module, ':') === false) ? $Module : substr($Module, strpos($Module, ':') + 1);
            $Infractions = $CIDRAM['BlockInfo']['SignatureCount'];
            if (isset($CIDRAM['ModuleResCache'][$Module]) && is_object($CIDRAM['ModuleResCache'][$Module])) {
                $CIDRAM['ModuleResCache'][$Module]($Infractions);
            } elseif (file_exists($CIDRAM['Vault'] . $Module) && is_readable($CIDRAM['Vault'] . $Module)) {
                require $CIDRAM['Vault'] . $Module;
            }
        });

        if (
            !$CIDRAM['Config']['signatures']['tracking_override'] &&
            !empty($CIDRAM['Tracking options override']) &&
            isset($RestoreTrackingOptionsOverride)
        ) {
            $CIDRAM['Tracking options override'] = $RestoreTrackingOptionsOverride;
        }

        $CIDRAM['ModuleErrors'] = $CIDRAM['Errors'];
        $CIDRAM['RestoreErrorHandler']();
    }

    /** Execute search engine verification. */
    if ($Verification && empty($CIDRAM['Whitelisted'])) {
        $CIDRAM['Stage'] = 'SearchEngineVerification';
        $CIDRAM['SearchEngineVerification']();
    }

    /** Execute social media verification. */
    if ($Verification && empty($CIDRAM['Whitelisted'])) {
        $CIDRAM['Stage'] = 'SocialMediaVerification';
        $CIDRAM['SocialMediaVerification']();
    }

    /** Execute other verification. */
    if ($Verification && empty($CIDRAM['Whitelisted'])) {
        $CIDRAM['Stage'] = 'OtherVerification';
        $CIDRAM['OtherVerification']();
    }

    /** Process auxiliary rules, if any exist. */
    if ($Aux && empty($CIDRAM['Whitelisted'])) {
        $CIDRAM['Stage'] = 'Aux';
        $CIDRAM['InitialiseErrorHandler']();
        $CIDRAM['Aux']();
        $CIDRAM['AuxErrors'] = $CIDRAM['Errors'];
        $CIDRAM['RestoreErrorHandler']();
    }

    $CIDRAM['Stage'] = 'Reporting';

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
$CIDRAM['FilterLang'] = function (string $ChoiceKey) use (&$CIDRAM): bool {
    $Path = $CIDRAM['Vault'] . 'lang/lang.' . $ChoiceKey;
    return (file_exists($Path . '.yaml') && file_exists($Path . '.fe.yaml'));
};

/**
 * Filter the available hash algorithms provided by the configuration page on
 * the basis of their availability.
 *
 * @param string $ChoiceKey Hash algorithm.
 * @return bool Available/Unavailable.
 */
$CIDRAM['FilterByDefined'] = function (string $ChoiceKey): bool {
    return defined($ChoiceKey);
};

/**
 * Filter the available theme options provided by the configuration page on
 * the basis of their availability.
 *
 * @param string $ChoiceKey Theme ID.
 * @return bool Valid/Invalid.
 */
$CIDRAM['FilterTheme'] = function (string $ChoiceKey) use (&$CIDRAM): bool {
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
 * @return void
 */
$CIDRAM['Formatter'] = function (string &$In, string $BlockLink = '', string $Current = '', string $FieldSeparator = ': ', bool $Flags = false) use (&$CIDRAM): void {
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
    while ($Caret < $Len) {
        $Remainder = $Len - $Caret;
        if ($Remainder < \CIDRAM\Core\Constants::MAX_BLOCKSIZE && $Remainder < ini_get('pcre.backtrack_limit')) {
            $Section = substr($In, $Caret) . $BlockSeparator;
            $Caret = $Len;
        } else {
            $SectionLen = strrpos(substr($In, $Caret, \CIDRAM\Core\Constants::MAX_BLOCKSIZE), $BlockSeparator);
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
                $TestString = $CIDRAM['Demojibakefier']->guard($ThisPartUnsafe);
                $Alternate = (
                    $TestString !== $ThisPartUnsafe && $CIDRAM['Demojibakefier']->Last
                ) ? '<code dir="ltr">🔁' . $CIDRAM['Demojibakefier']->Last . '➡️UTF-8' . $FieldSeparator . '</code>' . str_replace(['<', '>'], ['&lt;', '&gt;'], $TestString) . "<br />\n" : '';
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
                    $FieldSeparator . $ThisPart . ' <a href="' . $CIDRAM['PaginationRemoveFrom']($BlockLink) . '&search=' . $Enc . '">»</a>' . "<br />\n" . $Alternate,
                    $Section
                );
            }
        }
        preg_match_all('~ - ((?!：)[^\n:-]+)' . $FieldSeparator . '(?!<br />)[^<> \n-]+~i', $Section, $Parts);
        if (count($Parts[1])) {
            $Parts[1] = array_unique($Parts[1]);
            foreach ($Parts[1] as $ThisPart) {
                $Section = str_replace(
                    ' - ' . $ThisPart . $FieldSeparator,
                    ' - <span class="textLabel">' . $ThisPart . '</span>' . $FieldSeparator,
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
                    '("<a href="' . $CIDRAM['PaginationRemoveFrom']($BlockLink) . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '">' . $ThisPart . '</a>", L',
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
                    $OuterOpen . '<a href="' . $CIDRAM['PaginationRemoveFrom']($BlockLink) . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '">' . $InnerOpen . $ThisPart . $InnerClose . '</a>' . $OuterClose,
                    $Section
                );
            }
        }
        $Out .= substr($Section, 0, $BlockSeparatorLen * -1);
    }
    $Out = substr($Out, 1);
    $In = [];
    $BlockStart = 0;
    $BlockEnd = 0;
    while ($BlockEnd !== false) {
        $Darken = empty($Darken);
        $Style = '<div style="overflow:visible auto" class="h' . ($Darken ? 'B' : 'W') . ' hFd fW">';
        $BlockEnd = strpos($Out, $BlockSeparator, $BlockStart);
        $In[] = $Style . substr($Out, $BlockStart, $BlockEnd - $BlockStart + $BlockSeparatorLen) . '</div>';
        $BlockStart = $BlockEnd + $BlockSeparatorLen;
    }
    $In = str_replace("<br />\n</div>", "<br /></div>\n", implode('', $In));
};

/**
 * Attempt to tally log data.
 *
 * @param string $In The log data to be tallied.
 * @param string $BlockLink Used as the basis for links inserted into displayed log data used for searching related log data.
 * @param array $Exclusions Instructs which fields should be excluded from the tally.
 * @return string A tally of the log data (or an empty string when valid log data isn't supplied).
 */
$CIDRAM['Tally'] = function (string $In, string $BlockLink, array $Exclusions = []) use (&$CIDRAM): string {
    if (empty($In)) {
        return '';
    }
    $Data = [];
    $PosA = 0;
    while (($PosB = strpos($In, "\n", $PosA)) !== false) {
        $Line = substr($In, $PosA, $PosB - $PosA);
        $PosA = $PosB + 1;
        if (strlen($Line) === 0) {
            continue;
        }
        foreach ($Exclusions as $Exclusion) {
            if (substr($Line, 0, strlen($Exclusion)) === $Exclusion) {
                continue 2;
            }
        }
        $Separator = (strpos($Line, '：') !== false) ? '：' : ': ';
        $FieldsCount = substr_count($Line, ' - ') + 1;
        $FieldsCountMatch = (substr_count($Line, $Separator) === $FieldsCount);
        $Fields = ($FieldsCountMatch && $FieldsCount > 1) ? explode(' - ', $Line) : [$Line];
        foreach ($Fields as $FieldRaw) {
            if (($SeparatorPos = strpos($FieldRaw, $Separator)) === false) {
                continue;
            }
            $Field = trim(substr($FieldRaw, 0, $SeparatorPos));
            if (in_array($Field, $Exclusions, true)) {
                continue;
            }
            $Entry = trim(substr($FieldRaw, $SeparatorPos + strlen($Separator)));
            if (!isset($Data[$Field])) {
                $Data[$Field] = [];
            }
            if (!isset($Data[$Field][$Entry])) {
                $Data[$Field][$Entry] = 0;
            }
            $Data[$Field][$Entry]++;
        }
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
    $Out = '<table id="logsTA">';
    foreach ($Data as $Field => $Entries) {
        $Out .= '<tr><td class="h2f" colspan="2"><div class="s">' . $Field . "</div></td></tr>\n";
        if ($CIDRAM['FE']['SortOrder'] === 'descending') {
            arsort($Entries, SORT_NUMERIC);
        } else {
            asort($Entries, SORT_NUMERIC);
        }
        foreach ($Entries as $Entry => $Count) {
            if (!(substr($Entry, 0, 1) === '[' && substr($Entry, 3, 1) === ']')) {
                $Entry .= ' <a href="' . $CIDRAM['PaginationRemoveFrom']($BlockLink) . '&search=' . str_replace('=', '_', base64_encode($Entry)) . '">»</a>';
            }
            preg_match_all('~\("([^()"]+)", L~', $Entry, $Parts);
            if (count($Parts[1])) {
                foreach ($Parts[1] as $ThisPart) {
                    $Entry = str_replace(
                        '("' . $ThisPart . '", L',
                        '("<a href="' . $CIDRAM['PaginationRemoveFrom']($BlockLink) . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '">' . $ThisPart . '</a>", L',
                        $Entry
                    );
                }
            }
            preg_match_all('~\[([A-Z]{2})\]~', $Entry, $Parts);
            if (count($Parts[1])) {
                foreach ($Parts[1] as $ThisPart) {
                    $Entry = str_replace('[' . $ThisPart . ']', $CIDRAM['FE']['Flags'] ? (
                        '<a href="' . $CIDRAM['PaginationRemoveFrom']($BlockLink) . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '"><span class="flag ' . $ThisPart . '"></span></a>'
                    ) : (
                        '[<a href="' . $CIDRAM['PaginationRemoveFrom']($BlockLink) . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '">' . $ThisPart . '</a>]'
                    ), $Entry);
                }
            }
            if (!empty($CIDRAM['FE']['EntryCountPaginated'])) {
                $Percent = $CIDRAM['FE']['EntryCountPaginated'] ? ($Count / $CIDRAM['FE']['EntryCountPaginated']) * 100 : 0;
            } elseif (!empty($CIDRAM['FE']['EntryCount'])) {
                $Percent = $CIDRAM['FE']['EntryCount'] ? ($Count / $CIDRAM['FE']['EntryCount']) * 100 : 0;
            } elseif (!empty($CIDRAM['FE']['EntryCountBefore'])) {
                $Percent = $CIDRAM['FE']['EntryCountBefore'] ? ($Count / $CIDRAM['FE']['EntryCountBefore']) * 100 : 0;
            } else {
                $Percent = 0;
            }
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
$CIDRAM['GetAssetPath'] = function (string $Asset, bool $CanFail = false) use (&$CIDRAM): string {
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
 * @return void
 */
$CIDRAM['FE_Executor'] = function ($Closures = false, bool $Queue = false) use (&$CIDRAM): void {
    if ($Queue && $Closures !== false) {
        /** Guard. */
        if (empty($CIDRAM['FE_Executor_Queue']) || !is_array($CIDRAM['FE_Executor_Queue'])) {
            $CIDRAM['FE_Executor_Queue'] = [];
        }

        /** Add to the executor queue. */
        if (is_array($Closures)) {
            $CIDRAM['FE_Executor_Queue'] = array_merge($CIDRAM['FE_Executor_Queue'], $Closures);
        } else {
            $CIDRAM['FE_Executor_Queue'][] = $Closures;
        }
        return;
    }

    if ($Closures === false) {
        if (!empty($CIDRAM['FE_Executor_Queue']) && is_array($CIDRAM['FE_Executor_Queue'])) {
            /** We'll iterate an array from the local scope to guard against infinite loops. */
            $Items = $CIDRAM['FE_Executor_Queue'];

            /** Purge the queue before iterating. */
            $CIDRAM['FE_Executor_Queue'] = [];

            /** Recursively iterate through the executor queue. */
            foreach ($Items as $QueueItem) {
                $CIDRAM['FE_Executor']($QueueItem);
            }
        }
        return;
    }

    /** Guard. */
    $CIDRAM['Arrayify']($Closures);

    /** Recursively execute all closures in the current queue item. */
    foreach ($Closures as $Closure) {
        /** Guard. */
        if (is_array($Closure)) {
            foreach ($Closure as $Item) {
                $CIDRAM['FE_Executor']($Item);
            }
            continue;
        }

        /** All logic, data traversal, dot notation, etc handled here. */
        $Closure = $CIDRAM['Operation']->ifCompare($CIDRAM, $Closure);

        if (isset($CIDRAM[$Closure]) && is_object($CIDRAM[$Closure])) {
            $CIDRAM[$Closure]();
        } elseif (($Pos = strpos($Closure, ' ')) !== false) {
            $Params = substr($Closure, $Pos + 1);
            $Closure = substr($Closure, 0, $Pos);
            if (isset($CIDRAM[$Closure]) && is_object($CIDRAM[$Closure])) {
                $Params = $CIDRAM['Operation']->ifCompare($CIDRAM, $Params);
                $CIDRAM[$Closure]($Params);
            }
        }
    }
};

/**
 * Updates plugin version cited in the WordPress plugins dashboard, if this
 * copy of CIDRAM is running as a WordPress plugin.
 *
 * @return void
 */
$CIDRAM['WP-Ver'] = function () use (&$CIDRAM): void {
    if (
        !empty($CIDRAM['Components']['RemoteMeta']['CIDRAM Core']['Version']) &&
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

/** Used to ensure correct encoding, hide bad data, etc. */
$CIDRAM['Demojibakefier'] = new \Maikuolan\Common\Demojibakefier();

/**
 * Generates JavaScript code for localising numbers locally.
 *
 * @return string The JavaScript code.
 */
$CIDRAM['Number_L10N_JS'] = function () use (&$CIDRAM): string {
    if ($CIDRAM['NumberFormatter']->ConversionSet === 'Western') {
        $ConvJS = 'return l10nd';
    } else {
        $ConvJS = 'var nls=[' . $CIDRAM['NumberFormatter']->getSetCSV(
            $CIDRAM['NumberFormatter']->ConversionSet
        ) . '];return nls[l10nd]||l10nd';
    }
    return sprintf(
        'function l10nn(l10nd){%4$s};function nft(r){var x=r.indexOf(\'.\')!=-1?' .
        '\'%1$s\'+r.replace(/^.*\./gi,\'\'):\'\',n=r.replace(/\..*$/gi,\'\').rep' .
        'lace(/[^0-9]/gi,\'\'),t=n.length;for(e=\'\',b=%5$d,i=1;i<=t;i++){b>%3$d' .
        '&&(b=1,e=\'%2$s\'+e);var e=l10nn(n.substring(t-i,t-(i-1)))+e;b++}var t=' .
        'x.length;for(y=\'\',b=1,i=1;i<=t;i++){var y=l10nn(x.substring(t-i,t-(i-' .
        '1)))+y}return e+y}',
        $CIDRAM['NumberFormatter']->DecimalSeparator,
        $CIDRAM['NumberFormatter']->GroupSeparator,
        $CIDRAM['NumberFormatter']->GroupSize,
        $ConvJS,
        $CIDRAM['NumberFormatter']->GroupOffset + 1
    );
};

/**
 * Switch control for front-end page filters.
 *
 * @param array $Switches Names of available switches.
 * @param string $Selector Switch selector variable.
 * @param bool $StateModified Determines whether the filter state has been modified.
 * @param string $Redirect Reconstructed path to redirect to when the state changes.
 * @param string $Options Reconstructed filter controls.
 * @return void
 */
$CIDRAM['FilterSwitch'] = function (array $Switches, string $Selector, bool &$StateModified, string &$Redirect, string &$Options) use (&$CIDRAM): void {
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
 * Traversal detection.
 *
 * @param string $Path The path to check for traversal.
 * @return bool True when the path is traversal-free. False when traversal has been detected.
 */
$CIDRAM['Traverse'] = function (string $Path): bool {
    return !preg_match(
        '~(?://|(?<![\da-z])\.\.(?![\da-z])|/\.(?![\da-z])|(?<![\da-z])\./|[\x01-\x1F\[-^`?*$])~i',
        str_replace("\\", '/', $Path)
    );
};

/**
 * Custom sort an array by key and then implode the results.
 *
 * @param array $Arr The array to sort.
 * @return string The sorted, imploded array.
 */
$CIDRAM['UpdatesSortFunc'] = function (array $Arr) use (&$CIDRAM): string {
    $Type = $CIDRAM['FE']['sort-by-name'] ?? false;
    $Order = $CIDRAM['FE']['descending-order'] ?? false;
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
};

/**
 * Updates handler.
 *
 * @param string $Action The action to perform (update/install, verify,
 *      uninstall, activate, deactivate).
 * @param string|array $ID The IDs of the components to perform the specified
 *      action upon.
 * @return void
 */
$CIDRAM['UpdatesHandler'] = function (string $Action, $ID = '') use (&$CIDRAM): void {
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
        $CIDRAM['UpdatesHandler-Update']($ID);
    }

    /** Update (or install) and activate a component (one-step solution). */
    if ($Action === 'update-and-activate-component') {
        $CIDRAM['Arrayify']($ID);
        foreach ($ID as $ThisID) {
            $CIDRAM['UpdatesHandler-Update']([$ThisID]);
            if (
                isset($CIDRAM['Components']['Meta'][$ThisID]) &&
                $CIDRAM['IsActivable']($CIDRAM['Components']['Meta'][$ThisID]) &&
                $CIDRAM['IsInUse']($CIDRAM['Components']['Meta'][$ThisID]) !== 1
            ) {
                $CIDRAM['UpdatesHandler-Activate']([$ThisID]);
            }
        }
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
    if ($Action === 'uninstall-component') {
        $CIDRAM['UpdatesHandler-Uninstall']($ID);
    }

    /** Activate a component. */
    if ($Action === 'activate-component') {
        $CIDRAM['UpdatesHandler-Activate']($ID);
    }

    /** Deactivate a component. */
    if ($Action === 'deactivate-component') {
        $CIDRAM['UpdatesHandler-Deactivate']($ID);
    }

    /** Deactivate and uninstall a component (one-step solution). */
    if ($Action === 'deactivate-and-uninstall-component') {
        $CIDRAM['Arrayify']($ID);
        foreach ($ID as $ThisID) {
            if (
                isset($CIDRAM['Components']['Meta'][$ThisID]) &&
                $CIDRAM['IsActivable']($CIDRAM['Components']['Meta'][$ThisID]) &&
                $CIDRAM['IsInUse']($CIDRAM['Components']['Meta'][$ThisID]) !== 0
            ) {
                $CIDRAM['UpdatesHandler-Deactivate']([$ThisID]);
            }
            $CIDRAM['UpdatesHandler-Uninstall']([$ThisID]);
        }
    }

    /** Process and empty executor queue. */
    $CIDRAM['FE_Executor']();
};

/**
 * Updates handler: Update a component.
 *
 * @param string|array $ID The IDs of the components to update.
 * @return void
 */
$CIDRAM['UpdatesHandler-Update'] = function ($ID) use (&$CIDRAM): void {
    $CIDRAM['Arrayify']($ID);

    /** Fetch dependency installation triggers. */
    if (!empty($_POST['InstallTogether']) && is_array($_POST['InstallTogether'])) {
        $ID = array_merge($ID, $_POST['InstallTogether']);
    }

    $ID = array_unique($ID);
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
        $Reactivate = $CIDRAM['IsActivable']($CIDRAM['Components']['Meta'][$ThisTarget]) ? $CIDRAM['IsInUse']($CIDRAM['Components']['Meta'][$ThisTarget]) : 0;
        if ($Reactivate !== 0) {
            $CIDRAM['UpdatesHandler-Deactivate']($ThisTarget);
        }
        $NewMeta = '';
        $CIDRAM['FetchRemote-ContextFree']($NewMeta, $CIDRAM['Components']['Meta'][$ThisTarget]['Remote']);
        $CIDRAM['CheckConstraints']($CIDRAM['Components']['RemoteMeta'][$ThisTarget], true);
        $UpdateFailed = false;
        $SafeTarget = preg_quote($ThisTarget);
        if (
            $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['All Constraints Met'] &&
            !empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From']) &&
            !empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To']) &&
            !empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Reannotate']) &&
            $CIDRAM['Traverse']($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Reannotate']) &&
            ($ThisReannotate = $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Reannotate']) &&
            file_exists($CIDRAM['Vault'] . $ThisReannotate) &&
            ($OldMeta = $CIDRAM['Updater-IO']->readFile($CIDRAM['Vault'] . $ThisReannotate)) &&
            preg_match("~(\n" . $SafeTarget . ":?)(\n [^\n]*)*\n~i", $OldMeta, $OldMetaMatches) &&
            ($OldMetaMatches = $OldMetaMatches[0]) &&
            preg_match("~(\n" . $SafeTarget . ":?)(\n [^\n]*)*\n~i", $NewMeta, $NewMetaMatches) &&
            ($NewMetaMatches = $NewMetaMatches[0])
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
                    strtolower(
                        substr($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['From'][$Iterate], -2)
                    ) === 'gz' &&
                    strtolower(substr($ThisFileName, -2)) !== 'gz' &&
                    substr($ThisFile, 0, 2) === "\x1F\x8B"
                ) {
                    $ThisFile = gzdecode($ThisFile);
                }
                if (!empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum'][$Iterate])) {
                    $ThisChecksum = $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['Checksum'][$Iterate];
                    $ThisLen = strlen($ThisFile);
                    if (
                        (hash('md5', $ThisFile) . ':' . $ThisLen) !== $ThisChecksum &&
                        (hash('sha1', $ThisFile) . ':' . $ThisLen) !== $ThisChecksum &&
                        (hash('sha256', $ThisFile) . ':' . $ThisLen) !== $ThisChecksum
                    ) {
                        $CIDRAM['FE']['state_msg'] .=
                            '<code>' . $ThisTarget . '</code> – ' .
                            '<code>' . $ThisFileName . '</code> – ' .
                            $CIDRAM['L10N']->getString('response_checksum_error') . '<br />';
                        if (!empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['On Checksum Error'])) {
                            $CIDRAM['FE_Executor']($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['On Checksum Error']);
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
                    if (!empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['On Sanity Error'])) {
                        $CIDRAM['FE_Executor']($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['On Sanity Error']);
                    }
                    $Iterate = 0;
                    $Rollback = true;
                    continue;
                }
                $CIDRAM['BuildPath']($CIDRAM['Vault'] . $ThisFileName);
                if (is_readable($CIDRAM['Vault'] . $ThisFileName)) {
                    $BytesRemoved += filesize($CIDRAM['Vault'] . $ThisFileName);
                    if (file_exists($CIDRAM['Vault'] . $ThisFileName . '.rollback')) {
                        $BytesRemoved += filesize($CIDRAM['Vault'] . $ThisFileName . '.rollback');
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
                    foreach ($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Files']['To'] as $ThisFile) {
                        if (!empty($ThisFile) && $CIDRAM['Traverse']($ThisFile)) {
                            $CIDRAM['DeleteDirectory']($ThisFile);
                        }
                    }
                }
                $UpdateFailed = true;
            } else {
                /** Prune unwanted files and directories (update/install success). */
                if (!empty($CIDRAM['Components']['Meta'][$ThisTarget]['Files']['To'])) {
                    $ThisArr = $CIDRAM['Components']['Meta'][$ThisTarget]['Files']['To'];
                    $CIDRAM['Arrayify']($ThisArr);
                    foreach ($ThisArr as $ThisFile) {
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
                    }
                    unset($ThisFile, $ThisArr);
                }

                /** Assign updated component annotation. */
                $CIDRAM['Updater-IO']->writeFile($CIDRAM['Vault'] . $ThisReannotate, $NewMeta);

                $CIDRAM['FE']['state_msg'] .= '<code>' . $ThisTarget . '</code> – ';
                if (
                    empty($CIDRAM['Components']['Meta'][$ThisTarget]['Version']) &&
                    empty($CIDRAM['Components']['Meta'][$ThisTarget]['Files'])
                ) {
                    $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_component_successfully_installed');
                    if (!empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['When Install Succeeds'])) {
                        $CIDRAM['FE_Executor']($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['When Install Succeeds']);
                    }
                } else {
                    $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_component_successfully_updated');
                    if (!empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['When Update Succeeds'])) {
                        $CIDRAM['FE_Executor']($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['When Update Succeeds']);
                    }
                }

                /** Replace downstream meta with upstream meta. */
                $CIDRAM['Components']['Meta'][$ThisTarget] = $CIDRAM['Components']['RemoteMeta'][$ThisTarget];

                /** Set trigger for signatures update event. */
                if (
                    !empty($CIDRAM['Components']['Meta'][$ThisTarget]['Used with']) &&
                    $CIDRAM['Has']($CIDRAM['Components']['Meta'][$ThisTarget]['Used with'], ['ipv4', 'ipv6'])
                ) {
                    $CIDRAM['SignaturesUpdateEvent'] = $CIDRAM['Now'];
                }
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
                if (!empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['When Install Fails'])) {
                    $CIDRAM['FE_Executor']($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['When Install Fails']);
                }
            } else {
                $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_failed_to_update');
                if (!empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['When Update Fails'])) {
                    $CIDRAM['FE_Executor']($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['When Update Fails']);
                }
            }
        }
        $CIDRAM['FormatFilesize']($BytesAdded);
        $CIDRAM['FormatFilesize']($BytesRemoved);
        $CIDRAM['FE']['state_msg'] .= sprintf(
            $CIDRAM['FE']['CronMode'] !== '' ? " « +%s | -%s | %s »\n" : ' <code><span class="txtGn">+%s</span> | <span class="txtRd">-%s</span> | <span class="txtOe">%s</span></code><br />',
            $BytesAdded,
            $BytesRemoved,
            $CIDRAM['NumberFormatter']->format(microtime(true) - $TimeRequired, 3)
        );
        if ($Reactivate !== 0) {
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
 * @param string|array $ID The ID of the component to uninstall.
 * @return void
 */
$CIDRAM['UpdatesHandler-Uninstall'] = function ($ID) use (&$CIDRAM): void {
    if (is_array($ID)) {
        $ID = current($ID);
    }
    $InUse = $CIDRAM['ComponentFunctionUpdatePrep']($ID);
    $BytesRemoved = 0;
    $TimeRequired = microtime(true);
    $CIDRAM['FE']['state_msg'] .= '<code>' . $ID . '</code> – ';
    if (
        $InUse === 0 &&
        !empty($CIDRAM['Components']['Meta'][$ID]['Files']['To']) &&
        !empty($CIDRAM['Components']['Meta'][$ID]['Reannotate']) &&
        !empty($CIDRAM['Components']['Meta'][$ID]['Uninstallable']) &&
        ($OldMeta = $CIDRAM['Updater-IO']->readFile($CIDRAM['Vault'] . $CIDRAM['Components']['Meta'][$ID]['Reannotate'])) &&
        preg_match("~(\n" . preg_quote($ID) . ":?)(\n [^\n]*)*\n~i", $OldMeta, $OldMetaMatches) &&
        ($OldMetaMatches = $OldMetaMatches[0])
    ) {
        $NewMeta = str_replace($OldMetaMatches, preg_replace(
            ["/\n Files:(\n  [^\n]*)*\n/i", "/\n Version: [^\n]*\n/i"],
            "\n",
            $OldMetaMatches
        ), $OldMeta);
        foreach ($CIDRAM['Components']['Meta'][$ID]['Files']['To'] as $ThisFile) {
            if (empty($ThisFile) || !$CIDRAM['Traverse']($ThisFile)) {
                continue;
            }
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
        $CIDRAM['Updater-IO']->writeFile($CIDRAM['Vault'] . $CIDRAM['Components']['Meta'][$ID]['Reannotate'], $NewMeta);
        $CIDRAM['Components']['Meta'][$ID]['Version'] = false;
        $CIDRAM['Components']['Meta'][$ID]['Files'] = false;
        $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_component_successfully_uninstalled');
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Uninstall Succeeds'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Uninstall Succeeds']);
        }
    } else {
        $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_component_uninstall_error');
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Uninstall Fails'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Uninstall Fails']);
        }
    }
    $CIDRAM['FormatFilesize']($BytesRemoved);
    $CIDRAM['FE']['state_msg'] .= sprintf(
        $CIDRAM['FE']['CronMode'] !== '' ? " « -%s | %s »\n" : ' <code><span class="txtRd">-%s</span> | <span class="txtOe">%s</span></code><br />',
        $BytesRemoved,
        $CIDRAM['NumberFormatter']->format(microtime(true) - $TimeRequired, 3)
    );
};

/**
 * Updates handler: Activate a component.
 *
 * @param string|array $ID The ID of the component to activate.
 * @return void
 */
$CIDRAM['UpdatesHandler-Activate'] = function ($ID) use (&$CIDRAM): void {
    if (is_array($ID)) {
        $ID = current($ID);
    }
    $Activation = [
        'Config' => $CIDRAM['Updater-IO']->readFile($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile']),
        'imports' => $CIDRAM['Config']['general']['config_imports'],
        'events' => $CIDRAM['Config']['general']['events'],
        'ipv4' => $CIDRAM['Config']['signatures']['ipv4'],
        'ipv6' => $CIDRAM['Config']['signatures']['ipv6'],
        'modules' => $CIDRAM['Config']['signatures']['modules'],
        'Modified' => false
    ];
    foreach (['imports', 'events', 'ipv4', 'ipv6', 'modules'] as $Type) {
        $Activation[$Type] = array_unique(array_filter(
            explode(',', $Activation[$Type]),
            function ($Component) use (&$CIDRAM) {
                $Component = (strpos($Component, ':') === false) ? $Component : substr($Component, strpos($Component, ':') + 1);
                return ($Component && file_exists($CIDRAM['Vault'] . $Component));
            }
        ));
    }
    $InUse = $CIDRAM['ComponentFunctionUpdatePrep']($ID);
    $CIDRAM['FE']['state_msg'] .= '<code>' . $ID . '</code> – ';
    if ($InUse !== 1 && !empty($CIDRAM['Components']['Meta'][$ID]['Files']['To']) && (
        !empty($CIDRAM['Components']['Meta'][$ID]['Used with']) ||
        !empty($CIDRAM['Components']['Meta'][$ID]['Extended Description'])
    )) {
        $UsedWith = $CIDRAM['Components']['Meta'][$ID]['Used with'] ?? '';
        $Description = $CIDRAM['Components']['Meta'][$ID]['Extended Description'] ?? '';
        if (is_array($Description)) {
            $CIDRAM['IsolateL10N']($Description, $CIDRAM['Config']['general']['lang']);
        }
        foreach ($CIDRAM['Components']['Meta'][$ID]['Files']['To'] as $File) {
            $FileSafe = preg_quote($File);
            if (is_array($UsedWith)) {
                $ThisUsedWith = (string)array_shift($UsedWith);
            } else {
                $ThisUsedWith = $UsedWith;
                if ($ThisUsedWith !== 'imports' && preg_match('~\.ya?ml$~i', $File)) {
                    continue;
                }
            }
            if (
                preg_match('~^$|\.(?:css|gif|html?|jpe?g|js|png)$|^(?:classes|fe_assets)[\x2F\x5C]~i', $File) ||
                !file_exists($CIDRAM['Vault'] . $File) ||
                !$CIDRAM['Traverse']($File)
            ) {
                continue;
            }
            if ($ThisUsedWith === '') {
                foreach (['ipv4', 'ipv6', 'modules'] as $Type) {
                    if (strpos($Description, 'signatures-&gt;' . $Type) !== false) {
                        $Activation[$Type][] = $File;
                    }
                }
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
    if ($Activation['imports'] !== $CIDRAM['Config']['general']['config_imports']) {
        $Activation['Modified'] = true;
    }
    if ($Activation['events'] !== $CIDRAM['Config']['general']['events']) {
        $Activation['Modified'] = true;
    }
    foreach (['ipv4', 'ipv6', 'modules'] as $Type) {
        if ($Activation[$Type] !== $CIDRAM['Config']['signatures'][$Type]) {
            $Activation['Modified'] = true;
        }
    }
    if (!$Activation['Modified'] || !$Activation['Config']) {
        $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_activation_failed') . '<br />';
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Activation Fails'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Activation Fails']);
        }
    } else {
        $EOL = (strpos($Activation['Config'], "\r\n") !== false) ? "\r\n" : "\n";
        $Activation['Config'] = str_replace([
            $EOL . "config_imports='" . $CIDRAM['Config']['general']['config_imports'] . "'" . $EOL,
            $EOL . "events='" . $CIDRAM['Config']['general']['events'] . "'" . $EOL,
            $EOL . "ipv4='" . $CIDRAM['Config']['signatures']['ipv4'] . "'" . $EOL,
            $EOL . "ipv6='" . $CIDRAM['Config']['signatures']['ipv6'] . "'" . $EOL,
            $EOL . "modules='" . $CIDRAM['Config']['signatures']['modules'] . "'" . $EOL
        ], [
            $EOL . "config_imports='" . $Activation['imports'] . "'" . $EOL,
            $EOL . "events='" . $Activation['events'] . "'" . $EOL,
            $EOL . "ipv4='" . $Activation['ipv4'] . "'" . $EOL,
            $EOL . "ipv6='" . $Activation['ipv6'] . "'" . $EOL,
            $EOL . "modules='" . $Activation['modules'] . "'" . $EOL
        ], $Activation['Config']);
        $CIDRAM['Config']['general']['config_imports'] = $Activation['imports'];
        $CIDRAM['Config']['general']['events'] = $Activation['events'];
        $CIDRAM['Config']['signatures']['ipv4'] = $Activation['ipv4'];
        $CIDRAM['Config']['signatures']['ipv6'] = $Activation['ipv6'];
        $CIDRAM['Config']['signatures']['modules'] = $Activation['modules'];
        $CIDRAM['Updater-IO']->writeFile($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile'], $Activation['Config']);
        $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_activated') . '<br />';
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Activation Succeeds'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Activation Succeeds']);
        }
        $Success = true;
    }

    /** Deal with dependency activation. */
    if (
        !empty($Success) &&
        !empty($CIDRAM['Components']['Meta'][$ID]['Dependencies']) &&
        is_array($CIDRAM['Components']['Meta'][$ID]['Dependencies'])
    ) {
        foreach ($CIDRAM['Components']['Meta'][$ID]['Dependencies'] as $Dependency => $Constraints) {
            if (
                !isset($CIDRAM['Components']['Meta'][$Dependency]) ||
                empty($CIDRAM['Components']['Installed Versions'][$Dependency]) ||
                !$CIDRAM['IsActivable']($CIDRAM['Components']['Meta'][$Dependency]) ||
                $CIDRAM['IsInUse']($CIDRAM['Components']['Meta'][$Dependency]) === 1
            ) {
                continue;
            }
            $CIDRAM['UpdatesHandler-Activate']($Dependency);
        }
    }
};

/**
 * Updates handler: Deactivate a component.
 *
 * @param string|array $ID The ID of the component to deactivate.
 * @return void
 */
$CIDRAM['UpdatesHandler-Deactivate'] = function ($ID) use (&$CIDRAM): void {
    if (is_array($ID)) {
        $ID = current($ID);
    }
    $CIDRAM['Deactivation'] = [
        'Config' => $CIDRAM['Updater-IO']->readFile($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile']),
        'imports' => $CIDRAM['Config']['general']['config_imports'],
        'events' => $CIDRAM['Config']['general']['events'],
        'ipv4' => $CIDRAM['Config']['signatures']['ipv4'],
        'ipv6' => $CIDRAM['Config']['signatures']['ipv6'],
        'modules' => $CIDRAM['Config']['signatures']['modules'],
        'Modified' => false
    ];
    $InUse = 0;
    $CIDRAM['FE']['state_msg'] .= '<code>' . $ID . '</code> – ';
    if (!empty($CIDRAM['Components']['Meta'][$ID]['Files'])) {
        $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$ID]['Files']);
        $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$ID]['Files']['To']);
        $ThisComponent = $CIDRAM['Components']['Meta'][$ID];
        $CIDRAM['PrepareExtendedDescription']($ThisComponent);
        $InUse = $CIDRAM['IsInUse']($ThisComponent);
        unset($ThisComponent);
    }
    if ($InUse !== 0 && !empty($CIDRAM['Components']['Meta'][$ID]['Files']['To'])) {
        foreach (['imports', 'events', 'ipv4', 'ipv6', 'modules'] as $Type) {
            $CIDRAM['DeactivateComponent']($Type, $ID);
        }
    }
    if (!$CIDRAM['Deactivation']['Modified'] || !$CIDRAM['Deactivation']['Config']) {
        $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_deactivation_failed') . '<br />';
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Deactivation Fails'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Deactivation Fails']);
        }
    } else {
        $EOL = (strpos($CIDRAM['Deactivation']['Config'], "\r\n") !== false) ? "\r\n" : "\n";
        $CIDRAM['Deactivation']['Config'] = str_replace([
            $EOL . "config_imports='" . $CIDRAM['Config']['general']['config_imports'] . "'" . $EOL,
            $EOL . "events='" . $CIDRAM['Config']['general']['events'] . "'" . $EOL,
            $EOL . "ipv4='" . $CIDRAM['Config']['signatures']['ipv4'] . "'" . $EOL,
            $EOL . "ipv6='" . $CIDRAM['Config']['signatures']['ipv6'] . "'" . $EOL,
            $EOL . "modules='" . $CIDRAM['Config']['signatures']['modules'] . "'" . $EOL
        ], [
            $EOL . "config_imports='" . $CIDRAM['Deactivation']['imports'] . "'" . $EOL,
            $EOL . "events='" . $CIDRAM['Deactivation']['events'] . "'" . $EOL,
            $EOL . "ipv4='" . $CIDRAM['Deactivation']['ipv4'] . "'" . $EOL,
            $EOL . "ipv6='" . $CIDRAM['Deactivation']['ipv6'] . "'" . $EOL,
            $EOL . "modules='" . $CIDRAM['Deactivation']['modules'] . "'" . $EOL
        ], $CIDRAM['Deactivation']['Config']);
        $CIDRAM['Config']['general']['config_imports'] = $CIDRAM['Deactivation']['imports'];
        $CIDRAM['Config']['general']['events'] = $CIDRAM['Deactivation']['events'];
        $CIDRAM['Config']['signatures']['ipv4'] = $CIDRAM['Deactivation']['ipv4'];
        $CIDRAM['Config']['signatures']['ipv6'] = $CIDRAM['Deactivation']['ipv6'];
        $CIDRAM['Config']['signatures']['modules'] = $CIDRAM['Deactivation']['modules'];
        $CIDRAM['Updater-IO']->writeFile($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile'], $CIDRAM['Deactivation']['Config']);
        $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_deactivated') . '<br />';
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Deactivation Succeeds'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Deactivation Succeeds']);
        }
    }

    /** Cleanup. */
    unset($CIDRAM['Deactivation']);
};

/**
 * Updates handler: Repair a component.
 *
 * @param string|array $ID The IDs of the components to repair.
 * @return void
 */
$CIDRAM['UpdatesHandler-Repair'] = function ($ID) use (&$CIDRAM): void {
    $CIDRAM['Arrayify']($ID);
    $ID = array_unique($ID);
    foreach ($ID as $ThisTarget) {
        if (!isset(
            $CIDRAM['Components']['Meta'][$ThisTarget]['Files']['To'],
            $CIDRAM['Components']['Meta'][$ThisTarget]['Remote']
        )) {
            continue;
        }
        $BytesAdded = 0;
        $BytesRemoved = 0;
        $TimeRequired = microtime(true);
        $RepairFailed = false;
        $Reactivate = $CIDRAM['IsActivable']($CIDRAM['Components']['Meta'][$ThisTarget]) ? $CIDRAM['IsInUse']($CIDRAM['Components']['Meta'][$ThisTarget]) : 0;
        if ($Reactivate !== 0) {
            $CIDRAM['UpdatesHandler-Deactivate']($ThisTarget);
        }
        $CIDRAM['FE']['state_msg'] .= '<code>' . $ThisTarget . '</code> – ';
        $TempMeta = [];
        $RemoteData = '';
        $CIDRAM['FetchRemote-ContextFree']($RemoteData, $CIDRAM['Components']['Meta'][$ThisTarget]['Remote']);
        if ($Extracted = $CIDRAM['ExtractPage']($RemoteData)) {
            $CIDRAM['YAML']->process($Extracted, $TempMeta);
        }
        if (!isset($CIDRAM['Components']['RemoteMeta'], $CIDRAM['Components']['RemoteMeta'][$ThisTarget])) {
            if (!isset($CIDRAM['Components']['RemoteMeta'])) {
                $CIDRAM['Components']['RemoteMeta'] = [];
            }
            foreach ($TempMeta as $TempKey => $TempData) {
                if (!isset($CIDRAM['Components']['RemoteMeta'][$TempKey])) {
                    $CIDRAM['Components']['RemoteMeta'][$TempKey] = $TempData;
                }
            }
        }
        if (isset(
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
                    (hash('md5', $LocalFile) . ':' . $LocalFileSize) === $RemoteChecksum ||
                    (hash('sha1', $LocalFile) . ':' . $LocalFileSize) === $RemoteChecksum ||
                    (hash('sha256', $LocalFile) . ':' . $LocalFileSize) === $RemoteChecksum
                ) {
                    continue;
                }
                $RemoteFile = $CIDRAM['Request']($RemoteFileFrom);
                if (
                    strtolower(substr($RemoteFileFrom, -2)) === 'gz' &&
                    strtolower(substr($RemoteFileTo, -2)) !== 'gz' &&
                    substr($RemoteFile, 0, 2) === "\x1F\x8B"
                ) {
                    $RemoteFile = gzdecode($RemoteFile);
                }
                $RemoteFileSize = strlen($RemoteFile);
                if ((
                    (hash('md5', $RemoteFile) . ':' . $RemoteFileSize) !== $RemoteChecksum &&
                    (hash('sha1', $RemoteFile) . ':' . $RemoteFileSize) !== $RemoteChecksum &&
                    (hash('sha256', $RemoteFile) . ':' . $RemoteFileSize) !== $RemoteChecksum
                ) || (
                    preg_match('~\.(?:css|dat|gif|inc|jpe?g|php|png|ya?ml|[a-z]{0,2}db)$~i', $RemoteFileTo) &&
                    !$CIDRAM['SanityCheck']($RemoteFileTo, $RemoteFile)
                )) {
                    $RepairFailed = true;
                    continue;
                }
                $CIDRAM['BuildPath']($CIDRAM['Vault'] . $RemoteFileTo);
                if (file_exists($CIDRAM['Vault'] . $RemoteFileTo) && !is_writable($CIDRAM['Vault'] . $RemoteFileTo)) {
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
        $SafeTarget = preg_quote($ThisTarget);
        if (
            !$RepairFailed &&
            !empty($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Reannotate']) &&
            $CIDRAM['Traverse']($CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Reannotate']) &&
            ($ThisReannotate = $CIDRAM['Components']['RemoteMeta'][$ThisTarget]['Reannotate']) &&
            file_exists($CIDRAM['Vault'] . $ThisReannotate) &&
            ($OldMeta = $CIDRAM['Updater-IO']->readFile($CIDRAM['Vault'] . $ThisReannotate)) &&
            preg_match("~(\n" . $SafeTarget . ":?)(\n [^\n]*)*\n~i", $OldMeta, $OldMetaMatches) &&
            ($OldMetaMatches = $OldMetaMatches[0]) &&
            ($NewMeta = $RemoteData) &&
            preg_match("~(\n" . $SafeTarget . ":?)(\n [^\n]*)*\n~i", $NewMeta, $NewMetaMatches) &&
            ($NewMetaMatches = $NewMetaMatches[0])
        ) {
            $NewMeta = str_replace($OldMetaMatches, $NewMetaMatches, $OldMeta);

            /** Assign updated component annotation. */
            $CIDRAM['Updater-IO']->writeFile($CIDRAM['Vault'] . $ThisReannotate, $NewMeta);

            /** Repair operation succeeded. */
            $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_repair_process_completed');
            if (!empty($CIDRAM['Components']['Meta'][$ThisTarget]['When Repair Succeeds'])) {
                $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ThisTarget]['When Repair Succeeds']);
            }

            /** Replace downstream meta with upstream meta. */
            $CIDRAM['Components']['Meta'][$ThisTarget] = $CIDRAM['Components']['RemoteMeta'][$ThisTarget];
        } else {
            $RepairFailed = true;

            /** Repair operation failed. */
            $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_repair_process_failed');
            if (!empty($CIDRAM['Components']['Meta'][$ThisTarget]['When Repair Fails'])) {
                $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ThisTarget]['When Repair Fails']);
            }
        }
        $CIDRAM['FormatFilesize']($BytesAdded);
        $CIDRAM['FormatFilesize']($BytesRemoved);
        $CIDRAM['FE']['state_msg'] .= sprintf(
            $CIDRAM['FE']['CronMode'] !== '' ? " « +%s | -%s | %s »\n" : ' <code><span class="txtGn">+%s</span> | <span class="txtRd">-%s</span> | <span class="txtOe">%s</span></code><br />',
            $BytesAdded,
            $BytesRemoved,
            $CIDRAM['NumberFormatter']->format(microtime(true) - $TimeRequired, 3)
        );
        if ($Reactivate !== 0) {
            $CIDRAM['UpdatesHandler-Activate']($ThisTarget);
        }
    }
};

/**
 * Updates handler: Verify a component.
 *
 * @param string|array $ID The IDs of the components to verify.
 * @return void
 */
$CIDRAM['UpdatesHandler-Verify'] = function ($ID) use (&$CIDRAM): void {
    $CIDRAM['Arrayify']($ID);
    $ID = array_unique($ID);
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
                $Actual = hash('md5', $ThisFileData) . ':' . $Len;
            } else {
                $Actual = (($HashPartLen === 40) ? hash('sha1', $ThisFileData) : hash('sha256', $ThisFileData)) . ':' . $Len;
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
                '<code>%1$s</code> – %7$s %8$s – %9$s %10$s<br />%2$s – <code class="%6$s">%3$s</code><br />%4$s – <code class="%6$s">%5$s</code><hr />',
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
 * @return void
 */
$CIDRAM['NormaliseLinebreaks'] = function (string &$Data) {
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
$CIDRAM['SectionsHandler'] = function (array $Files) use (&$CIDRAM): string {
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
    $FilesCount = [];
    $SectionMeta = [];
    $ThisSectionMeta = [];
    foreach ($Files as $File) {
        $Data = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $File);
        if (strlen($Data) === 0) {
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
                foreach ($ThisSectionMeta as $ThisOrigin => $ThisQuantity) {
                    if (!isset($SectionsForIgnore[$Tag . ':' . $ThisOrigin])) {
                        $SectionsForIgnore[$Tag . ':' . $ThisOrigin] = empty($CIDRAM['Ignore'][$Tag . ':' . $ThisOrigin]);
                    }
                }
                if (!isset($SignaturesCount[$Tag])) {
                    $SignaturesCount[$Tag] = 0;
                }
                $SignaturesCount[$Tag] += $ThisCount;
                $ThisCount = 0;
                if (!isset($FilesCount[$Tag])) {
                    $FilesCount[$Tag] = [];
                }
                if (!isset($FilesCount[$Tag][$File])) {
                    $FilesCount[$Tag][$File] = true;
                }
                if (!isset($SectionMeta[$Tag])) {
                    $SectionMeta[$Tag] = [];
                }
                foreach ($ThisSectionMeta as $ThisOrigin => $ThisQuantity) {
                    if (!isset($SectionMeta[$Tag][$ThisOrigin])) {
                        $SectionMeta[$Tag][$ThisOrigin] = 0;
                    }
                    $SectionMeta[$Tag][$ThisOrigin] += $ThisQuantity;
                }
                $ThisSectionMeta = [];
                continue;
            }
            if (substr($Line, 0, 8) === 'Origin: ') {
                $Origin = substr($Line, 8);
                if (!isset($ThisSectionMeta[$Origin])) {
                    $ThisSectionMeta[$Origin] = 0;
                }
                $ThisSectionMeta[$Origin] += $OriginCount;
                $OriginCount = 0;
                continue;
            }
            if (!$Line || preg_match('~^([\n#]|Expires|Defers to)~', $Line) || strpos($Line, '/') === false) {
                continue;
            }
            $ThisCount++;
            $OriginCount++;
            $CIDRAM['FE']['SL_Signatures']++;
        }
    }
    $Class = 'ng2';
    ksort($SectionMeta);
    $CIDRAM['FE']['SL_Unique'] = count($SectionMeta);
    foreach ($SectionMeta as $Section => $Counts) {
        $ThisCount = $SignaturesCount[$Section] ?? 0;
        $ThisFiles = isset($FilesCount[$Section]) ? count($FilesCount[$Section]) : 0;
        $ThisCount = sprintf(
            $CIDRAM['L10N']->getPlural($ThisFiles, 'label_sections_across_x_files'),
            sprintf(
                $CIDRAM['L10N']->getPlural($ThisCount, 'label_sections_x_signatures'),
                '<span class="txtRd">' . $CIDRAM['NumberFormatter']->format($ThisCount) . '</span>'
            ),
            '<span class="txtRd">' . $CIDRAM['NumberFormatter']->format($ThisFiles) . '</span>'
        );
        $Class = (isset($Class) && $Class === 'ng2') ? 'ng1' : 'ng2';
        $SectionSafe = preg_replace('~[^\da-z]~i', '', $Section);
        $SectionLabel = $Section . ' (' . $ThisCount . ')';
        $OriginOut = '';
        arsort($Counts);
        foreach ($Counts as $Origin => $Quantity) {
            $State = !empty($SectionsForIgnore[$Section . ':' . $Origin]);
            $OriginSafe = $SectionSafe . preg_replace('~[^A-Z]~', '', $Origin);
            $Quantity = sprintf(
                $CIDRAM['L10N']->getPlural($Quantity, 'label_sections_x_signatures'),
                $CIDRAM['NumberFormatter']->format($Quantity)
            );
            $OriginDisplay = '<code>' . $Origin . '</code>' . ($CIDRAM['FE']['Flags'] ? ' – <span class="flag ' . $Origin . '"></span>' : '');
            $OriginOut .= "\n" . sprintf(
                '<div class="sectionControlNotIgnored%s">%s – %s – %s<a href="javascript:void()" onclick="javascript:slx(\'%s:%s\',\'%s</a></div>',
                $OriginSafe . '" style="transform:skew(-18deg)' . ($State ? '' : ';display:none'),
                $OriginDisplay,
                $Quantity,
                '',
                $Section,
                $Origin,
                'ignore\',\'sectionControlNotIgnored' . $OriginSafe . '\',\'sectionControlIgnored' . $OriginSafe . '\')">' . $CIDRAM['L10N']->getString('label_ignore')
            ) . sprintf(
                '<div class="sectionControlIgnored%s">%s – %s – %s<a href="javascript:void()" onclick="javascript:slx(\'%s:%s\',\'%s</a></div>',
                $OriginSafe . '" style="transform:skew(-18deg);filter:grayscale(75%) contrast(50%)' . ($State ? ';display:none' : ''),
                $OriginDisplay,
                $Quantity,
                $CIDRAM['L10N']->getString('state_ignored') . ' – ',
                $Section,
                $Origin,
                'unignore\',\'sectionControlIgnored' . $OriginSafe . '\',\'sectionControlNotIgnored' . $OriginSafe . '\')">' . $CIDRAM['L10N']->getString('label_unignore')
            );
        }
        $State = !empty($SectionsForIgnore[$Section]);
        $Out .= "\n" . sprintf(
            '<div class="%s sectionControlNotIgnored%s"><strong>%s%s</strong><br />%s</div>',
            $Class,
            $State ? $SectionSafe : $SectionSafe . '" style="display:none',
            $SectionLabel,
            ' – <a href="javascript:void()" onclick="javascript:slx(\'' . $Section . '\',\'ignore\',\'sectionControlNotIgnored' . $SectionSafe . '\',\'sectionControlIgnored' . $SectionSafe . '\')">' . $CIDRAM['L10N']->getString('label_ignore') . '</a>',
            $OriginOut
        ) . sprintf(
            '<div class="%s sectionControlIgnored%s"><strong>%s%s</strong><br />%s</div>',
            $Class,
            $SectionSafe . '" style="filter:grayscale(50%) contrast(50%)' . ($State ? ';display:none' : ''),
            $SectionLabel . ' – ' . $CIDRAM['L10N']->getString('state_ignored'),
            ' – <a href="javascript:void()" onclick="javascript:slx(\'' . $Section . '\',\'unignore\',\'sectionControlIgnored' . $SectionSafe . '\',\'sectionControlNotIgnored' . $SectionSafe . '\')">' . $CIDRAM['L10N']->getString('label_unignore') . '</a>',
            $OriginOut
        );
    }
    return $Out;
};

/**
 * Tally IPv6 count.
 *
 * @param array $Arr
 * @param int $Range (1-128)
 * @return void
 */
$CIDRAM['RangeTablesTallyIPv6'] = function (array &$Arr, int $Range) {
    $Order = ceil($Range / 16) - 1;
    $Arr[$Order] += 2 ** ((128 - $Range) % 16);
};

/**
 * Finalise IPv6 count.
 *
 * @param array $Arr Values of the IPv6 octets.
 * @return array A base value (first parameter), and the power it would need to
 *      be raised by (second parameter) to accurately reflect the total amount.
 */
$CIDRAM['RangeTablesFinaliseIPv6'] = function (array $Arr): array {
    for ($Iter = 7; $Iter > 0; $Iter--) {
        if (!empty($Arr[$Iter + 1])) {
            $Arr[$Iter] += (floor($Arr[$Iter + 1] / 655.36) / 100);
        }
        while ($Arr[$Iter] >= \CIDRAM\Core\Constants::MAX_BLOCKSIZE) {
            $Arr[$Iter] -= \CIDRAM\Core\Constants::MAX_BLOCKSIZE;
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
$CIDRAM['RangeTablesFetchLine'] = function (string &$Data, int &$Offset, string &$Needle, bool &$HasOrigin) {
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
        $Tag = '';
        $CPos = strpos($Data, "\n\n", $Offset);
        if ($Offset !== false) {
            if ($HasOrigin) {
                $OPos = strpos($Data, "\nOrigin: ", $Offset);
                if ($OPos !== false && ($CPos === false || $CPos > $OPos)) {
                    $Origin = substr($Data, $OPos + 9, 2);
                }
            }
            $TPos = strpos($Data, "\nTag: ", $Offset);
            if ($TPos !== false && ($CPos === false || $CPos > $TPos)) {
                $TEPos = strpos($Data, "\n", $TPos + 1);
                $Tag = substr($Data, $TPos + 6, $TEPos - $TPos - 6);
            }
        }
        $Param = preg_replace([
            '~ until (\d{4})[.-](\d\d)[.-](\d\d)$~i',
            '~ from (\d{4})[.-](\d\d)[.-](\d\d)$~i'
        ], '', trim($Param));
        return ['Param' => $Param, 'Origin' => $Origin, 'Tag' => $Tag];
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
 * @return void
 */
$CIDRAM['RangeTablesIterateFiles'] = function (array &$Arr, array $Files, array $SigTypes, int $MaxRange, string $IPType) use (&$CIDRAM): void {
    if (!isset($CIDRAM['Ignore'])) {
        $CIDRAM['Ignore'] = $CIDRAM['FetchIgnores']();
    }
    foreach ($Files as $File) {
        $File = (strpos($File, ':') === false) ? $File : substr($File, strpos($File, ':') + 1);
        $Data = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $File);
        if (strlen($Data) === 0) {
            continue;
        }
        if (isset($CIDRAM['FE']['Matrix-Data']) && class_exists('\Maikuolan\Common\Matrix') && function_exists('imagecreatetruecolor')) {
            $CIDRAM['FE']['Matrix-Data'] .= $Data;
        }
        $CIDRAM['NormaliseLinebreaks']($Data);
        $HasOrigin = (strpos($Data, "\nOrigin: ") !== false);
        foreach ($SigTypes as $SigType) {
            for ($Range = 1; $Range <= $MaxRange; $Range++) {
                if ($MaxRange === 32) {
                    $Order = 2 ** ($MaxRange - $Range);
                }
                $Offset = 0;
                $Needle = '/' . $Range . ' ' . $SigType;
                while ($Offset !== false) {
                    if (!$Entry = $CIDRAM['RangeTablesFetchLine']($Data, $Offset, $Needle, $HasOrigin)) {
                        break;
                    }
                    $Into = (!empty($Entry['Tag']) && !empty($CIDRAM['Ignore'][$Entry['Tag']])) ? $IPType . '-Ignored' : $IPType;
                    foreach ([$Into, $IPType . '-Total'] as $ThisInto) {
                        if (empty($Arr[$ThisInto][$SigType][$Range][$Entry['Param']])) {
                            $Arr[$ThisInto][$SigType][$Range][$Entry['Param']] = 0;
                        }
                        $Arr[$ThisInto][$SigType][$Range][$Entry['Param']]++;
                        if ($MaxRange === 32) {
                            if (empty($Arr[$ThisInto][$SigType]['Total'][$Entry['Param']])) {
                                $Arr[$ThisInto][$SigType]['Total'][$Entry['Param']] = 0;
                            }
                            $Arr[$ThisInto][$SigType]['Total'][$Entry['Param']] += $Order;
                        } elseif ($MaxRange === 128) {
                            if (empty($Arr[$ThisInto][$SigType]['Total'][$Entry['Param']])) {
                                $Arr[$ThisInto][$SigType]['Total'][$Entry['Param']] = [0, 0, 0, 0, 0, 0, 0, 0];
                            }
                            $CIDRAM['RangeTablesTallyIPv6']($Arr[$ThisInto][$SigType]['Total'][$Entry['Param']], $Range);
                        }
                        if (!$Entry['Origin']) {
                            continue;
                        }
                        if (empty($Arr[$ThisInto . '-Origin'][$SigType][$Range][$Entry['Origin']])) {
                            $Arr[$ThisInto . '-Origin'][$SigType][$Range][$Entry['Origin']] = 0;
                        }
                        $Arr[$ThisInto . '-Origin'][$SigType][$Range][$Entry['Origin']]++;
                        if ($MaxRange === 32) {
                            if (empty($Arr[$ThisInto . '-Origin'][$SigType]['Total'][$Entry['Origin']])) {
                                $Arr[$ThisInto . '-Origin'][$SigType]['Total'][$Entry['Origin']] = 0;
                            }
                            $Arr[$ThisInto . '-Origin'][$SigType]['Total'][$Entry['Origin']] += $Order;
                        } elseif ($MaxRange === 128) {
                            if (empty($Arr[$ThisInto . '-Origin'][$SigType]['Total'][$Entry['Origin']])) {
                                $Arr[$ThisInto . '-Origin'][$SigType]['Total'][$Entry['Origin']] = [0, 0, 0, 0, 0, 0, 0, 0];
                            }
                            $CIDRAM['RangeTablesTallyIPv6']($Arr[$ThisInto . '-Origin'][$SigType]['Total'][$Entry['Origin']], $Range);
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
    string &$JS,
    string $SigType,
    int $MaxRange,
    string $IPType,
    string $ZeroPlus,
    string $Class
) use (&$CIDRAM) {
    for ($Range = 1; $Range <= $MaxRange; $Range++) {
        $Size = '*Math.pow(2,' . ($MaxRange - $Range) . ')';
        foreach ([$IPType, $IPType . '-Ignored', $IPType . '-Total'] as $IgnoreState) {
            if (count($Arr[$IgnoreState][$SigType][$Range])) {
                $StatClass = $ZeroPlus;
                arsort($Arr[$IgnoreState][$SigType][$Range]);
                foreach ($Arr[$IgnoreState][$SigType][$Range] as $Param => &$Count) {
                    if ($IPType === 'IPv4') {
                        $ThisID = preg_replace('~[^\da-z]~i', '_', $IgnoreState . $SigType . $Range . $Param);
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
                $Arr[$IgnoreState][$SigType][$Range] = implode('<br />', $Arr[$IgnoreState][$SigType][$Range]);
                if (count($Arr[$IgnoreState . '-Origin'][$SigType][$Range])) {
                    arsort($Arr[$IgnoreState . '-Origin'][$SigType][$Range]);
                    foreach ($Arr[$IgnoreState . '-Origin'][$SigType][$Range] as $Origin => &$Count) {
                        if ($IPType === 'IPv4') {
                            $ThisID = preg_replace('~[^\da-z]~i', '_', $IgnoreState . $SigType . $Range . $Origin);
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
                    $Arr[$IgnoreState . '-Origin'][$SigType][$Range] = implode('<br /><br />', $Arr[$IgnoreState . '-Origin'][$SigType][$Range]);
                    $Arr[$IgnoreState][$SigType][$Range] .= '<hr />' . $Arr[$IgnoreState . '-Origin'][$SigType][$Range];
                }
            } else {
                $StatClass = 's';
                $Arr[$IgnoreState][$SigType][$Range] = '';
            }
            if ($Arr[$IgnoreState][$SigType][$Range]) {
                if (!isset($Out[$IgnoreState . '/' . $Range])) {
                    $Out[$IgnoreState . '/' . $Range] = '';
                }
                $Out[$IgnoreState . '/' . $Range] .= '<span style="display:none" class="' . $Class . ' ' . $StatClass . '">' . $Arr[$IgnoreState][$SigType][$Range] . '</span>';
            }
        }
    }
    foreach ([$IPType, $IPType . '-Ignored', $IPType . '-Total'] as $IgnoreState) {
        if (count($Arr[$IgnoreState][$SigType]['Total'])) {
            $StatClass = $ZeroPlus;
            if ($MaxRange === 32) {
                arsort($Arr[$IgnoreState][$SigType]['Total']);
            } elseif ($MaxRange === 128) {
                uasort($Arr[$IgnoreState][$SigType]['Total'], function ($A, $B): int {
                    for ($i = 0; $i < 8; $i++) {
                        if ($A[$i] !== $B[$i]) {
                            return $A[$i] > $B[$i] ? -1 : 1;
                        }
                    }
                    return 0;
                });
            }
            foreach ($Arr[$IgnoreState][$SigType]['Total'] as $Param => &$Count) {
                if ($MaxRange === 32) {
                    $ThisID = preg_replace('~[^\da-z]~i', '_', $IgnoreState . $SigType . 'Total' . $Param);
                    $JS .= 'w(\'' . $ThisID . '\',nft((' . $Count . ').toString()));';
                } elseif ($MaxRange === 128) {
                    $Count = $CIDRAM['RangeTablesFinaliseIPv6']($Count);
                    $Count[1] = $Count[1] ? '+\' × \'+nft((2).toString())+\'<sup>^\'+nft((' . $Count[1] . ').toString())+\'</sup>\'' : '';
                    $ThisID = preg_replace('~[^\da-z]~i', '_', $IgnoreState . $SigType . 'Total' . $Param);
                    $JS .= 'w(\'' . $ThisID . '\',' . ($Count[1] ? '\'~\'+' : '') . 'nft((' . $Count[0] . ').toString())' . $Count[1] . ');';
                }
                $Count = '<span id="' . $ThisID . '"></span>';
                if ($Param) {
                    $Count = $Param . ' – ' . $Count;
                }
            }
            $Arr[$IgnoreState][$SigType]['Total'] = implode('<br />', $Arr[$IgnoreState][$SigType]['Total']);
            if (count($Arr[$IgnoreState . '-Origin'][$SigType]['Total'])) {
                arsort($Arr[$IgnoreState . '-Origin'][$SigType]['Total']);
                foreach ($Arr[$IgnoreState . '-Origin'][$SigType]['Total'] as $Origin => &$Count) {
                    if ($MaxRange === 32) {
                        $ThisID = preg_replace('~[^\da-z]~i', '_', $IgnoreState . $SigType . 'Total' . $Origin);
                        $JS .= 'w(\'' . $ThisID . '\',nft((' . $Count . ').toString()));';
                    } elseif ($MaxRange === 128) {
                        $Count = $CIDRAM['RangeTablesFinaliseIPv6']($Count);
                        $Count[1] = $Count[1] ? '+\' × \'+nft((2).toString())+\'<sup>^\'+nft((' . $Count[1] . ').toString())+\'</sup>\'' : '';
                        $ThisID = preg_replace('~[^\da-z]~i', '_', $IgnoreState . $SigType . 'Total' . $Origin);
                        $JS .= 'w(\'' . $ThisID . '\',' . ($Count[1] ? '\'~\'+' : '') . 'nft((' . $Count[0] . ').toString())' . $Count[1] . ');';
                    }
                    $Count = '<code class="hB">' . $Origin . '</code> – ' . (
                        $CIDRAM['FE']['Flags'] && $Origin !== '??' ? '<span class="flag ' . $Origin . '"></span> – ' : ''
                    ) . '<span id="' . $ThisID . '"></span>';
                }
                $Arr[$IgnoreState . '-Origin'][$SigType]['Total'] = implode('<br /><br />', $Arr[$IgnoreState . '-Origin'][$SigType]['Total']);
                $Arr[$IgnoreState][$SigType]['Total'] .= '<hr />' . $Arr[$IgnoreState . '-Origin'][$SigType]['Total'];
            }
        } else {
            $StatClass = 's';
            $Arr[$IgnoreState][$SigType]['Total'] = '';
        }
        if ($Arr[$IgnoreState][$SigType]['Total']) {
            if (!isset($Out[$IgnoreState . '/Total'])) {
                $Out[$IgnoreState . '/Total'] = '';
            }
            $Out[$IgnoreState . '/Total'] .= '<span style="display:none" class="' . $Class . ' ' . $StatClass . '">' . $Arr[$IgnoreState][$SigType]['Total'] . '</span>';
        }
    }
};

/**
 * Range tables handler.
 *
 * @param array $IPv4 The currently active IPv4 signature files.
 * @param array $IPv6 The currently active IPv6 signature files.
 * @return string Some JavaScript generated to populate the range tables data.
 */
$CIDRAM['RangeTablesHandler'] = function (array $IPv4, array $IPv6) use (&$CIDRAM): string {
    $Arr = ['IPv4' => [], 'IPv4-Origin' => [], 'IPv6' => [], 'IPv6-Origin' => []];
    $SigTypes = ['Run', 'Whitelist', 'Greylist', 'Deny'];
    foreach ($SigTypes as $SigType) {
        $Arr['IPv4'][$SigType] = ['Total' => []];
        $Arr['IPv4-Origin'][$SigType] = ['Total' => []];
        $Arr['IPv6'][$SigType] = ['Total' => []];
        $Arr['IPv6-Origin'][$SigType] = ['Total' => []];
        $Arr['IPv4-Ignored'][$SigType] = ['Total' => []];
        $Arr['IPv4-Ignored-Origin'][$SigType] = ['Total' => []];
        $Arr['IPv6-Ignored'][$SigType] = ['Total' => []];
        $Arr['IPv6-Ignored-Origin'][$SigType] = ['Total' => []];
        $Arr['IPv4-Total'][$SigType] = ['Total' => []];
        $Arr['IPv4-Total-Origin'][$SigType] = ['Total' => []];
        $Arr['IPv6-Total'][$SigType] = ['Total' => []];
        $Arr['IPv6-Total-Origin'][$SigType] = ['Total' => []];
        for ($Range = 1; $Range <= 32; $Range++) {
            $Arr['IPv4'][$SigType][$Range] = [];
            $Arr['IPv4-Origin'][$SigType][$Range] = [];
            $Arr['IPv4-Ignored'][$SigType][$Range] = [];
            $Arr['IPv4-Ignored-Origin'][$SigType][$Range] = [];
            $Arr['IPv4-Total'][$SigType][$Range] = [];
            $Arr['IPv4-Total-Origin'][$SigType][$Range] = [];
        }
        for ($Range = 1; $Range <= 128; $Range++) {
            $Arr['IPv6'][$SigType][$Range] = [];
            $Arr['IPv6-Origin'][$SigType][$Range] = [];
            $Arr['IPv6-Ignored'][$SigType][$Range] = [];
            $Arr['IPv6-Ignored-Origin'][$SigType][$Range] = [];
            $Arr['IPv6-Total'][$SigType][$Range] = [];
            $Arr['IPv6-Total-Origin'][$SigType][$Range] = [];
        }
    }
    $Out = [];
    $CIDRAM['RangeTablesIterateFiles']($Arr, $IPv4, $SigTypes, 32, 'IPv4');
    $CIDRAM['RangeTablesIterateFiles']($Arr, $IPv6, $SigTypes, 128, 'IPv6');
    $CIDRAM['FE']['Labels'] = '';
    $JS = '';
    $RangeCatOptions = [];
    $Styling = ['Run' => ' class="txtOe"', 'Whitelist' => ' class="txtGn"', 'Greylist' => ' class="txtGn"', 'Deny' => ' class="txtRd"'];
    foreach ($SigTypes as $SigType) {
        $Class = 'sigtype_' . strtolower($SigType);
        $RangeCatOptions[] = '<option value="' . $Class . '"' . ($Styling[$SigType] ?? '') . '>' . $SigType . '</option>';
        $CIDRAM['FE']['Labels'] .= '<span style="display:none" class="s ' . $Class . '">' . $CIDRAM['L10N']->getString('label_signature_type') . ' ' . $SigType . '</span>';
        if ($SigType === 'Run') {
            $ZeroPlus = 'txtOe';
        } else {
            $ZeroPlus = ($SigType === 'Whitelist' || $SigType === 'Greylist') ? 'txtGn' : 'txtRd';
        }
        $CIDRAM['RangeTablesIterateData']($Arr, $Out, $JS, $SigType, 32, 'IPv4', $ZeroPlus, $Class);
        $CIDRAM['RangeTablesIterateData']($Arr, $Out, $JS, $SigType, 128, 'IPv6', $ZeroPlus, $Class);
    }
    $CIDRAM['FE']['rangeCatOptions'] = implode("\n            ", $RangeCatOptions);
    $CIDRAM['FE']['RangeRows'] = '';
    foreach ([['IPv4', 32], ['IPv6', 128]] as $Build) {
        for ($Range = 1; $Range <= $Build[1]; $Range++) {
            foreach ([
                [$Build[0] . '/' . $Range, $Build[0] . '/' . $Range],
                [$Build[0] . '-Ignored/' . $Range, $Build[0] . '/' . $Range . ' (' . $CIDRAM['L10N']->getString('state_ignored') . ')'],
                [$Build[0] . '-Total/' . $Range, $Build[0] . '/' . $Range . ' (' . $CIDRAM['L10N']->getString('label_total') . ')']
            ] as $Label) {
                if (!empty($Out[$Label[0]])) {
                    foreach ($SigTypes as $SigType) {
                        $Class = 'sigtype_' . strtolower($SigType);
                        if (strpos($Out[$Label[0]], $Class) === false) {
                            $Out[$Label[0]] .= '<span style="display:none" class="' . $Class . ' s">-</span>';
                        }
                    }
                    $ThisArr = ['RangeType' => $Label[1], 'NumOfCIDRs' => $Out[$Label[0]], 'state_loading' => $CIDRAM['L10N']->getString('state_loading')];
                    $CIDRAM['FE']['RangeRows'] .= $CIDRAM['ParseVars']($ThisArr, $CIDRAM['FE']['RangeRow']);
                }
            }
        }
    }
    $Loading = $CIDRAM['L10N']->getString('state_loading');
    foreach ([
        ['', $CIDRAM['L10N']->getString('label_total')],
        ['-Ignored', $CIDRAM['L10N']->getString('label_total') . ' (' . $CIDRAM['L10N']->getString('state_ignored') . ')'],
        ['-Total', $CIDRAM['L10N']->getString('label_total') . ' (' . $CIDRAM['L10N']->getString('label_total') . ')']
    ] as $Label) {
        $ThisRight = '<table><tr><td>';
        $InternalIPv4 = 'IPv4' . $Label[0] . '/Total';
        $InternalIPv6 = 'IPv6' . $Label[0] . '/Total';
        if (isset($Out[$InternalIPv4]) && strlen($Out[$InternalIPv4])) {
            foreach ($SigTypes as $SigType) {
                $Class = 'sigtype_' . strtolower($SigType);
                if (strpos($Out[$InternalIPv4], $Class) === false) {
                    $Out[$InternalIPv4] .= '<span style="display:none" class="' . $Class . ' s">-</span>';
                }
            }
            $ThisRight .= $Out[$InternalIPv4];
        }
        $ThisRight .= '</td><td>';
        if (isset($Out[$InternalIPv6]) && strlen($Out[$InternalIPv6])) {
            foreach ($SigTypes as $SigType) {
                $Class = 'sigtype_' . strtolower($SigType);
                if (strpos($Out[$InternalIPv6], $Class) === false) {
                    $Out[$InternalIPv6] .= '<span style="display:none" class="' . $Class . ' s">-</span>';
                }
            }
            $ThisRight .= $Out[$InternalIPv6];
        }
        $ThisRight .= '</td></tr></table>';
        $CIDRAM['FE']['RangeRows'] .= $CIDRAM['ParseVars']([
            'RangeType' => $Label[1],
            'NumOfCIDRs' => $ThisRight,
            'state_loading' => $Loading
        ], $CIDRAM['FE']['RangeRow']);
    }
    return $JS;
};

/**
 * Assign some basic variables (initial prepwork for most front-end pages).
 *
 * @param string $Title The page title.
 * @param string $Tips The page "tip" to include ("Hello username! Here you can...").
 * @param bool $JS Whether to include the standard front-end JavaScript boilerplate.
 * @return void
 */
$CIDRAM['InitialPrepwork'] = function (string $Title = '', string $Tips = '', bool $JS = true) use (&$CIDRAM): void {
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
$CIDRAM['SendOutput'] = function () use (&$CIDRAM): string {
    if ($CIDRAM['FE']['JS']) {
        $CIDRAM['FE']['JS'] = "\n<script type=\"text/javascript\">" . $CIDRAM['FE']['JS'] . '</script>';
    }
    $Template = $CIDRAM['FE']['Template'];
    $Labels = [];
    $Segments = [];
    if (empty($CIDRAM['Config']['general']['disable_webfonts'])) {
        $Labels[] = 'WebFont';
    } else {
        $Segments[] = 'WebFont';
    }
    if (isset($CIDRAM['FE']['UserState']) && $CIDRAM['FE']['UserState'] === 1) {
        $Labels[] = 'Logged In';
        $Segments[] = 'Logged Out';
    } else {
        $Labels[] = 'Logged Out';
        $Segments[] = 'Logged In';
    }
    foreach ($Labels as $Label) {
        $Template = str_replace(['<!-- ' . $Label . ' Begin -->', '<!-- ' . $Label . ' End -->'], '', $Template);
    }
    foreach ($Segments as $Segment) {
        $BPos = strpos($Template, '<!-- ' . $Segment . ' Begin -->');
        $EPos = strpos($Template, '<!-- ' . $Segment . ' End -->');
        if ($BPos !== false && $EPos !== false) {
            $Template = substr($Template, 0, $BPos) . substr($Template, $EPos + strlen($Segment) + 13);
        }
    }
    return $CIDRAM['ParseVars'](array_merge($CIDRAM['L10N']->Data, $CIDRAM['FE']), $Template);
};

/**
 * Confirms whether a file is a log file (used by the file manager and logs viewer).
 *
 * @param string $File The path/name of the file to be confirmed.
 * @param string $Normalised A normalised name for the log, useful for better sorting.
 * @return bool True if it's a log file; False if it isn't.
 */
$CIDRAM['FileManager-IsLogFile'] = function (string $File, string &$Normalised = '') use (&$CIDRAM): bool {
    static $Pattern_logfile = false;
    if (!$Pattern_logfile && $CIDRAM['Config']['general']['logfile']) {
        $Pattern_logfile = $CIDRAM['BuildLogPattern']($CIDRAM['Config']['general']['logfile'], true);
    }
    static $Pattern_logfile_apache = false;
    if (!$Pattern_logfile_apache && $CIDRAM['Config']['general']['logfile_apache']) {
        $Pattern_logfile_apache = $CIDRAM['BuildLogPattern']($CIDRAM['Config']['general']['logfile_apache'], true);
    }
    static $Pattern_logfile_serialized = false;
    if (!$Pattern_logfile_serialized && $CIDRAM['Config']['general']['logfile_serialized']) {
        $Pattern_logfile_serialized = $CIDRAM['BuildLogPattern']($CIDRAM['Config']['general']['logfile_serialized'], true);
    }
    static $Pattern_frontend_log = false;
    if (!$Pattern_frontend_log && $CIDRAM['Config']['general']['frontend_log']) {
        $Pattern_frontend_log = $CIDRAM['BuildLogPattern']($CIDRAM['Config']['general']['frontend_log'], true);
    }
    static $Pattern_reCAPTCHA_logfile = false;
    if (!$Pattern_reCAPTCHA_logfile && $CIDRAM['Config']['recaptcha']['logfile']) {
        $Pattern_reCAPTCHA_logfile = $CIDRAM['BuildLogPattern']($CIDRAM['Config']['recaptcha']['logfile'], true);
    }
    static $Pattern_HCaptcha_logfile = false;
    if (!$Pattern_HCaptcha_logfile && $CIDRAM['Config']['hcaptcha']['logfile']) {
        $Pattern_HCaptcha_logfile = $CIDRAM['BuildLogPattern']($CIDRAM['Config']['hcaptcha']['logfile'], true);
    }
    static $Pattern_PHPMailer_EventLog = false;
    if (!$Pattern_PHPMailer_EventLog && $CIDRAM['Config']['PHPMailer']['event_log']) {
        $Pattern_PHPMailer_EventLog = $CIDRAM['BuildLogPattern']($CIDRAM['Config']['PHPMailer']['event_log'], true);
    }
    return preg_match('~\.log(?:\.gz)?$~', strtolower($File)) || (
        $CIDRAM['Config']['general']['logfile'] && preg_match($Pattern_logfile, $File)
    ) || (
        $CIDRAM['Config']['general']['logfile_apache'] && preg_match($Pattern_logfile_apache, $File)
    ) || (
        $CIDRAM['Config']['general']['logfile_serialized'] && preg_match($Pattern_logfile_serialized, $File)
    ) || (
        $CIDRAM['Config']['general']['frontend_log'] && preg_match($Pattern_frontend_log, $File)
    ) || (
        $CIDRAM['Config']['recaptcha']['logfile'] && preg_match($Pattern_reCAPTCHA_logfile, $File)
    ) || (
        $CIDRAM['Config']['hcaptcha']['logfile'] && preg_match($Pattern_HCaptcha_logfile, $File)
    ) || (
        $CIDRAM['Config']['PHPMailer']['event_log'] && preg_match($Pattern_PHPMailer_EventLog, $File)
    );
};

/**
 * Generates JavaScript snippets for confirmation prompts for front-end actions.
 *
 * @param string $Action The action being taken to be confirmed.
 * @param string $Form The ID of the form to be submitted when the action is confirmed.
 * @return string The JavaScript snippet.
 */
$CIDRAM['GenerateConfirm'] = function (string $Action, string $Form) use (&$CIDRAM): string {
    $Confirm = str_replace(["'", '"'], ["\'", '\x22'], sprintf($CIDRAM['L10N']->getString('confirm_action'), $Action));
    return 'javascript:confirm(\'' . $Confirm . '\')&&document.getElementById(\'' . $Form . '\').submit()';
};

/**
 * A quicker way to add entries to the front-end log file.
 *
 * @param string $IPAddr The IP address triggering the log event.
 * @param string $User The user triggering the log event.
 * @param string $Message The message to be logged.
 * @return void
 */
$CIDRAM['FELogger'] = function (string $IPAddr, string $User, string $Message) use (&$CIDRAM): void {
    /** Guard. */
    if (
        empty($CIDRAM['FE']['DateTime']) ||
        !$CIDRAM['Config']['general']['frontend_log'] ||
        !($File = $CIDRAM['BuildPath']($CIDRAM['Vault'] . $CIDRAM['Config']['general']['frontend_log']))
    ) {
        return;
    }

    $Data = $CIDRAM['Config']['legal']['pseudonymise_ip_addresses'] ? $CIDRAM['Pseudonymise-IP']($IPAddr) : $IPAddr;
    $Data .= ' - ' . $CIDRAM['FE']['DateTime'] . ' - "' . $User . '" - ' . $Message . "\n";

    $Truncate = $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate']);
    $WriteMode = (!file_exists($File) || $Truncate > 0 && filesize($File) >= $Truncate) ? 'wb' : 'ab';
    $Handle = fopen($File, $WriteMode);
    fwrite($Handle, $Data);
    fclose($Handle);
    if ($WriteMode === 'wb') {
        $CIDRAM['LogRotation']($CIDRAM['Config']['general']['frontend_log']);
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
$CIDRAM['SendEmail'] = function (array $Recipients = [], string $Subject = '', string $Body = '', string $AltBody = '', array $Attachments = []) use (&$CIDRAM): bool {
    /** Prepare event logging. */
    $EventLogData = sprintf(
        '%s - %s - ',
        $CIDRAM['Config']['legal']['pseudonymise_ip_addresses'] ? $CIDRAM['Pseudonymise-IP']($CIDRAM['IPAddr']) : $CIDRAM['IPAddr'],
        $CIDRAM['FE']['DateTime'] ?? $CIDRAM['TimeFormat']($CIDRAM['Now'], $CIDRAM['Config']['general']['time_format'])
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

            /** Tell PHPMailer to always use UTF-8. */
            $Mail->CharSet = 'utf-8';

            /** Disable debugging. */
            $Mail->SMTPDebug = 0;

            /** Skip authorisation process for some extreme problematic cases. */
            if ($CIDRAM['Config']['PHPMailer']['skip_auth_process']) {
                $Mail->SMTPOptions = ['ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]];
            }

            /** Set mail server hostname. */
            $Mail->Host = $CIDRAM['Config']['PHPMailer']['host'];

            /** Set the SMTP port. */
            $Mail->Port = $CIDRAM['Config']['PHPMailer']['port'];

            /** Set the encryption system to use. */
            if (
                !empty($CIDRAM['Config']['PHPMailer']['smtp_secure']) &&
                $CIDRAM['Config']['PHPMailer']['smtp_secure'] !== '-'
            ) {
                $Mail->SMTPSecure = $CIDRAM['Config']['PHPMailer']['smtp_secure'];
            }

            /** Set whether to use SMTP authentication. */
            $Mail->SMTPAuth = $CIDRAM['Config']['PHPMailer']['smtp_auth'];

            /** Set the username to use for SMTP authentication. */
            $Mail->Username = $CIDRAM['Config']['PHPMailer']['username'];

            /** Set the password to use for SMTP authentication. */
            $Mail->Password = $CIDRAM['Config']['PHPMailer']['password'];

            /** Set the email sender address and name. */
            $Mail->setFrom(
                $CIDRAM['Config']['PHPMailer']['set_from_address'],
                $CIDRAM['Config']['PHPMailer']['set_from_name']
            );

            /** Set the optional "reply to" address and name. */
            if (
                !empty($CIDRAM['Config']['PHPMailer']['add_reply_to_address']) &&
                !empty($CIDRAM['Config']['PHPMailer']['add_reply_to_name'])
            ) {
                $Mail->addReplyTo(
                    $CIDRAM['Config']['PHPMailer']['add_reply_to_address'],
                    $CIDRAM['Config']['PHPMailer']['add_reply_to_name']
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
            if (is_array($Attachments)) {
                foreach ($Attachments as $Attachment) {
                    $Mail->addAttachment($Attachment);
                }
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
$CIDRAM['2FA-Number'] = function (): int {
    try {
        $Key = random_int(\CIDRAM\Core\Constants::TWO_FACTOR_MIN_INT, \CIDRAM\Core\Constants::TWO_FACTOR_MAX_INT);
    } catch (\Exception $e) {
        $Key = rand(\CIDRAM\Core\Constants::TWO_FACTOR_MIN_INT, \CIDRAM\Core\Constants::TWO_FACTOR_MAX_INT);
    }
    return $Key;
};

/**
 * Generates the rules data displayed on the auxiliary rules page.
 *
 * @param bool $Mode Whether view mode (false) or edit mode (true).
 * @return string The generated auxiliary rules data.
 */
$CIDRAM['AuxGenerateFEData'] = function (bool $Mode = false) use (&$CIDRAM): string {
    /** Populate output here. */
    $Output = '';

    /** JavaScript stuff to append to output after everything else. */
    $JSAppend = '';

    /** Potential sources. */
    $Sources = $CIDRAM['GenerateLabels']($CIDRAM['Config']['Provide']['Auxiliary Rules']['Sources'], $CIDRAM['RegExLabels']);

    /** Attempt to parse the auxiliary rules file. */
    if (!isset($CIDRAM['AuxData'])) {
        $CIDRAM['AuxData'] = [];
        $CIDRAM['YAML']->process($CIDRAM['ReadFile']($CIDRAM['Vault'] . 'auxiliary.yaml'), $CIDRAM['AuxData']);
    }

    /** Count entries (needed for offering first and last move options). */
    $Count = count($CIDRAM['AuxData']);

    /** Make entries safe for display at the front-end. */
    $CIDRAM['RecursiveReplace']($CIDRAM['AuxData'], ['<', '>', '"'], ['&lt;', '&gt;', '&quot;']);

    if ($Mode) {
        /** Append empty rule if editing. */
        $CIDRAM['AuxData'][' '] = [];
        $Count++;
        $Current = 0;
    } else {
        /** Useful to know whether we're at the first or last rule (due to the "move to the ..." options. */
        $Current = 1;
    }

    /** Style class. */
    $StyleClass = 'ng1';

    /** Update button before. */
    if ($Mode) {
        $Output .= sprintf(
            '<div class="%s"><center><input type="submit" value="%s" class="auto" /></center></div>',
            $StyleClass,
            $CIDRAM['L10N']->getString('field_update_all')
        );
    };

    /** Iterate through the auxiliary rules. */
    foreach ($CIDRAM['AuxData'] as $Name => $Data) {
        /** Rule row ID. */
        $RuleClass = preg_replace('~^0+~', '', bin2hex($Name));

        /** Edit mode. */
        if ($Mode) {
            /** Update cell style. */
            $StyleClass = $StyleClass === 'ng1' ? 'ng2' : 'ng1';

            /** Rule begin and sticky. */
            $Output .= sprintf(
                '%s<div class="%s"><div style="float:%s;position:sticky;top:0px;overflow:hidden;z-index;-1"><span class="s">%s</span></div>',
                "\n      ",
                $StyleClass,
                $CIDRAM['FE']['FE_Align_Reverse'],
                ($Name === ' ' && count($Data) === 0) ? '' : sprintf($CIDRAM['L10N']->getString('label_current_data_for'), $Name)
            );

            /** Rule name. */
            $Output .= sprintf(
                '%1$s<div class="iCntr">%1$s  <div class="iLabl s">%3$s</div><div class="iCntn"><input type="text" name="ruleName[%4$s]" class="f400" value="%2$s" /></div></div>',
                "\n      ",
                $Name === ' ' ? '' : $Name,
                $CIDRAM['L10N']->getString('field_new_name'),
                $Current
            );

            /** Set rule priority (rearranges the rules). */
            $Output .= sprintf(
                '%1$s<div class="iCntr"><div class="iLabl s">%3$s</div>%1$s  <div class="iCntn"><input type="text" name="rulePriority[%2$s]" class="f400" value="%2$s" /></div></div>',
                "\n      ",
                $Current,
                $CIDRAM['L10N']->getString('field_execution_order')
            );

            /** Rule reason. */
            $Output .= sprintf(
                '<div class="iCntr"><div class="iLabl s" id="%4$sruleReasonDt">%2$s</div><div class="iCntn" id="%4$sruleReasonDd"><input type="text" name="ruleReason[%3$s]" class="f400" value="%1$s" /></div></div>',
                $Data['Reason'] ?? '',
                $CIDRAM['L10N']->getString('label_aux_reason'),
                $Current,
                $RuleClass
            );

            /** Redirect target. */
            $Output .= sprintf(
                '<div class="iCntr"><div class="iLabl s" id="%4$sruleTargetDt">%2$s</div><div class="iCntn" id="%4$sruleTargetDd"><input type="text" name="ruleTarget[%3$s]" class="f400" value="%1$s" /></div></div>',
                $Data['Target'] ?? '',
                $CIDRAM['L10N']->getString('label_aux_target'),
                $Current,
                $RuleClass
            );

            /** Run target. */
            $Output .= sprintf(
                '<div class="iCntr"><div class="iLabl s" id="%4$sruleRunDt">%2$s</div><div class="iCntn" id="%4$sruleRunDd"><input type="text" name="ruleRun[%3$s]" class="f400" value="%1$s" /></div></div>',
                $Data['Run']['File'] ?? '',
                $CIDRAM['L10N']->getString('label_aux_run'),
                $Current,
                $RuleClass
            );

            /** From. */
            $Output .= sprintf(
                '<div class="iCntr"><div class="iLabl s" id="%4$sfromDt">%2$s</div><div class="iCntn" id="%4$sfromDd"><input type="date" name="from[%3$s]" class="f400" value="%1$s" min="%5$s" /></div></div>',
                isset($Data['From']) ? str_replace('.', '-', $Data['From']) : '',
                $CIDRAM['L10N']->getString('label_aux_from'),
                $Current,
                $RuleClass,
                $CIDRAM['FE']['Y-m-d']
            );

            /** Expiry. */
            $Output .= sprintf(
                '<div class="iCntr"><div class="iLabl s" id="%4$sexpiryDt">%2$s</div><div class="iCntn" id="%4$sexpiryDd"><input type="date" name="expiry[%3$s]" class="f400" value="%1$s" min="%5$s" /></div></div>',
                isset($Data['Expiry']) ? str_replace('.', '-', $Data['Expiry']) : '',
                $CIDRAM['L10N']->getString('label_aux_expiry'),
                $Current,
                $RuleClass,
                $CIDRAM['FE']['Y-m-d']
            );

            /** Status code override. */
            $Output .= sprintf('<div class="iCntr"><div class="iLabl s">%1$s</div><div class="iCntn">', $CIDRAM['L10N']->getString('label_aux_http_status_code_override'));
            $Output .= sprintf(
                '<span id="%1$sstatGroupX" class="statGroup"><input type="radio" class="auto" id="%1$sstatusCodeX" name="statusCode[%3$s]" value="0" %2$s/><label for="%1$sstatusCodeX">🗙</label></span>',
                $RuleClass,
                empty($Data['Status Code']) ? 'checked="true" ' : '',
                $Current
            );
            foreach ([['3', ['301', '302', '307', '308']], ['45', ['403', '410', '418', '451', '503']]] as $StatGroup) {
                $Output .= sprintf('<span id="%1$sstatGroup%2$s" class="statGroup">', $RuleClass, $StatGroup[0]);
                foreach ($StatGroup[1] as $StatusCode) {
                    $Output .= sprintf(
                        '<input type="radio" class="auto" id="%1$sstatusCode%2$s" name="statusCode[%4$s]" value="%2$s" %3$s/><label for="%1$sstatusCode%2$s">%2$s</label>',
                        $RuleClass,
                        $StatusCode,
                        isset($Data['Status Code']) && $Data['Status Code'] === $StatusCode ? ' checked="true"' : '',
                        $Current
                    );
                }
                $Output .= '</span>';
            }
            $Output .= '</div></div>';

            /** Where to get conditions from. */
            $ConditionsFrom = '';

            /** Action menu. */
            $Output .= sprintf('<div class="iCntr"><div class="iLabl"><select id="act%1$s" name="act[%1$s]" class="auto" onchange="javascript:onAuxActionChange(this.value,\'%2$s\',\'%1$s\')">', $Current, $RuleClass);
            foreach ([
                ['actWhl', 'optActWhl', 'Whitelist'],
                ['actGrl', 'optActGrl', 'Greylist'],
                ['actBlk', 'optActBlk', 'Block'],
                ['actByp', 'optActByp', 'Bypass'],
                ['actLog', 'optActLog', 'Don\'t log'],
                ['actRdr', 'optActRdr', 'Redirect'],
                ['actRun', 'optActRun', 'Run'],
                ['actPro', 'optActPro', 'Profile']
            ] as $MenuOption) {
                $Output .= sprintf(
                    '<option value="%1$s"%2$s>%3$s</option>',
                    $MenuOption[0],
                    empty($Data[$MenuOption[2]]) ? '' : ' selected',
                    $CIDRAM['FE'][$MenuOption[1]]
                );
                if (!empty($Data[$MenuOption[2]])) {
                    $ConditionsFrom = $MenuOption[2];
                    $JSAppend .= sprintf('onAuxActionChange(\'%s\',\'%s\',\'%s\');', $MenuOption[0], $RuleClass, $Current);
                }
            }
            if ($ConditionsFrom === '') {
                $JSAppend .= sprintf('onAuxActionChange(\'actWhl\',\'%s\',\'%s\');', $RuleClass, $Current);
            }
            $Output .= sprintf(
                '</select><input type="button" onclick="javascript:addCondition(\'%s\')" value="%s" class="auto" /></div>',
                $Current,
                $CIDRAM['L10N']->getString('field_add_more_conditions')
            );
            $Output .= sprintf('<div class="iCntn" id="%1$sconditions">', $Current);

            /** Populate conditions. */
            if ($ConditionsFrom && is_array($Data[$ConditionsFrom])) {
                $Iteration = 0;
                $ConditionFormTemplate =
                    "\n<div>" .
                    '<select name="conSourceType[%1$s][%2$s]" class="auto">%3$s</select>' .
                    '<select name="conIfOrNot[%1$s][%2$s]" class="auto"><option value="If"%6$s>=</option><option value="Not"%7$s>≠</option></select>' .
                    '<input type="text" name="conSourceValue[%1$s][%2$s]" placeholder="%4$s" class="f400" value="%5$s" /></div>';
                foreach ([['If matches', ' selected', ''], ['But not if matches', '', ' selected']] as $ModeSet) {
                    if (isset($Data[$ConditionsFrom][$ModeSet[0]]) && is_array($Data[$ConditionsFrom][$ModeSet[0]])) {
                        foreach ($Data[$ConditionsFrom][$ModeSet[0]] as $Key => $Values) {
                            $ThisSources = str_replace('value="' . $Key . '">', 'value="' . $Key . '" selected>', $CIDRAM['FE']['conSources']);
                            foreach ($Values as $Condition) {
                                $Output .= sprintf(
                                    $ConditionFormTemplate,
                                    $Current,
                                    $Iteration,
                                    $ThisSources,
                                    $CIDRAM['L10N']->getString('tip_condition_placeholder'),
                                    $Condition,
                                    $ModeSet[1],
                                    $ModeSet[2]
                                );
                                $Iteration++;
                            }
                        }
                    }
                }
            }
            $Output .= '</div></div>';

            /** Webhook button. */
            $Output .= sprintf(
                '<div class="iCntr"><div class="iLabl"><input type="button" onclick="javascript:addWebhook(\'%1$s\')" value="%2$s" class="auto" /></div><div class="iCntn" id="%1$swebhooks">',
                $Current,
                $CIDRAM['L10N']->getString('field_add_webhook')
            );

            /** Populate webhooks. */
            if (isset($Data['Webhooks']) && is_array($Data['Webhooks'])) {
                $Iteration = 0;
                foreach ($Data['Webhooks'] as $Webhook) {
                    $Output .= sprintf(
                        '<input type="text" name="webhooks[%1$s][%2$s]" placeholder="%3$s" class="f400" value="%4$s" />',
                        $Current,
                        $Iteration,
                        $CIDRAM['L10N']->getString('tip_condition_placeholder'),
                        $Webhook
                    );
                    $Iteration++;
                }
            }
            $Output .= '</div></div>';

            /** Match method. */
            if (empty($Data['Method'])) {
                $MethodData = [' selected', '', ''];
            } elseif ($Data['Method'] === 'RegEx') {
                $MethodData = ['', ' selected', ''];
            } elseif ($Data['Method'] === 'WinEx') {
                $MethodData = ['', '', ' selected'];
            } else {
                $MethodData = ['', '', ''];
            }
            $Output .= sprintf(
                '<div class="iCntr"><div class="iLabl"><select name="mtd[%1$s]" class="auto"><option value="mtdStr"%5$s>%2$s</option><option value="mtdReg"%6$s>%3$s</option><option value="mtdWin"%7$s>%4$s</option></select></div></div>',
                $Current,
                $CIDRAM['FE']['optMtdStr'],
                $CIDRAM['FE']['optMtdReg'],
                $CIDRAM['FE']['optMtdWin'],
                $MethodData[0],
                $MethodData[1],
                $MethodData[2]
            );

            /** Match logic. */
            if (empty($Data['Logic']) || $Data['Logic'] === 'Any') {
                $LogicData = [' selected', ''];
            } elseif ($Data['Logic'] === 'All') {
                $LogicData = ['', ' selected'];
            } else {
                $LogicData = ['', ''];
            }
            $Output .= sprintf(
                '<div class="iCntr"><div class="iLabl"><select id="logic[%1$s]" name="logic[%1$s]" class="flong"><option value="Any"%4$s>%2$s</option><option value="All"%5$s>%3$s</option></select></div></div>',
                $Current,
                $CIDRAM['L10N']->getString('label_aux_logic_any'),
                $CIDRAM['L10N']->getString('label_aux_logic_all'),
                $LogicData[0],
                $LogicData[1]
            );

            /** Other options and special flags. */
            foreach ($CIDRAM['Config']['Provide']['Auxiliary Rules']['Flags'] as $FlagSetName => $FlagSet) {
                $FlagKey = preg_replace('~[^A-Za-z]~', '', $FlagSetName);
                $UseDefaultState = true;
                $Options = '';
                if (isset($FlagSet['Label'])) {
                    $FlagSetName = $CIDRAM['L10N']->getString($FlagSet['Label']) ?: $FlagSetName;
                    unset($FlagSet['Label']);
                }
                foreach ($FlagSet as $FlagName => $FlagData) {
                    if (empty($Data[$FlagName])) {
                        $Selected = '';
                    } else {
                        $UseDefaultState = false;
                        $Selected = ' selected';
                    }
                    $Options .= sprintf(
                        '<option value="%s"%s>%s</option>',
                        $FlagName,
                        $Selected,
                        isset($FlagData['Label']) ? ($CIDRAM['L10N']->getString($FlagData['Label']) ?: $FlagName) : $FlagName
                    );
                }
                $Options = sprintf(
                    '<select name="%s[%s]" class="auto"><option value="Default State"%s>%s</option>',
                    $FlagKey,
                    $Current,
                    $UseDefaultState ? ' selected' : '',
                    $CIDRAM['L10N']->getString('label_aux_special_default_state')
                ) . $Options . '</select><br /><br />';
                $Output .= sprintf(
                    '<div class="iLabl s"><label for="%s[%s]">%s</label></div><div class="iCntn">%s</div>',
                    $FlagKey,
                    $Current,
                    trim($FlagSetName . $CIDRAM['L10N']->getString('pair_separator')),
                    $Options
                );
            }

            /** Rule notes. */
            $Output .= sprintf(
                '<div class="iCntr"><div class="iLabl s">%1$s</div><div class="iCntn"><textarea id="Notes[%2$s]" name="Notes[%2$s]" class="half">%3$s</textarea></div></div>',
                $CIDRAM['L10N']->getString('label_aux_notes'),
                $Current,
                $Data['Notes'] ?? ''
            );

            /** Finish writing new rule. */
            $Output .= '</div>';
            $Current++;
            continue;
        }

        /** Figure out which options are available for the rule (view mode). */
        $Options = ['(<span style="cursor:pointer" onclick="javascript:%s(\'' . addslashes($Name) . '\',\'' . $RuleClass . '\')"><code class="s">%s</code></span>)'];
        if (empty($Data['Disable this rule'])) {
            $Options['disableRule'] = sprintf($Options[0], 'disableRule', '<span style="position:relative;top:-2px" class="txtRd">⏸</span>' . $CIDRAM['L10N']->getString('label_aux_special_disable'));
        } else {
            $Options['enableRule'] = sprintf($Options[0], 'enableRule', '<span style="position:relative;top:-3px" class="txtGn">▶</span>' . $CIDRAM['L10N']->getString('label_aux_special_enable'));
        }
        if ($Count > 1) {
            if ($Current !== 1) {
                if ($Current !== 2) {
                    $Options['moveUp'] = sprintf($Options[0], 'moveUp', '<span class="txtBl">↑</span>' . $CIDRAM['L10N']->getString('label_aux_move_up'));
                }
                $Options['moveToTop'] = sprintf($Options[0], 'moveToTop', '<span class="txtBl">↑↑</span>' . $CIDRAM['L10N']->getString('label_aux_move_top'));
            }
            if ($Current !== $Count) {
                if ($Current !== ($Count - 1)) {
                    $Options['moveDown'] = sprintf($Options[0], 'moveDown', '<span class="txtBl">↓</span>' . $CIDRAM['L10N']->getString('label_aux_move_down'));
                }
                $Options['moveToBottom'] = sprintf($Options[0], 'moveToBottom', '<span class="txtBl">↓↓</span>' . $CIDRAM['L10N']->getString('label_aux_move_bottom'));
            }
        }
        unset($Options[0]);
        $Options['exportRule'] = sprintf(
            '(<span style="cursor:pointer" onclick="javascript:{document.getElementById(\'xprtName\').value=\'%s\';document.getElementById(\'xprtForm\').submit()}"><code class="s">%s</code></span>)',
            addslashes($Name),
            $CIDRAM['L10N']->getString('label_export')
        );
        $Options['delRule'] = sprintf(
            '(<span style="cursor:pointer" onclick="javascript:confirm(\'%s\')&&delRule(\'' . addslashes($Name) . '\',\'' . $RuleClass . '\')"><code class="s"><span class="txtRd">⌧</span>%s</code></span>)',
            str_replace(["'", '"'], ["\'", '\x22'], sprintf($CIDRAM['L10N']->getString('confirm_delete'), $Name)),
            $CIDRAM['L10N']->getString('field_delete')
        );
        $Options = implode(' ', $Options);
        if (substr($Options, 0, 1) === '(' && substr($Options, -1) === ')') {
            $Options = sprintf(
                '<span style="display:inline-block">(<span style="cursor:pointer" id="heaven%1$s" class="scaleXToOne" onclick="javascript:heavenToggle(\'%1$s\')"><code style="s">☰</code></span><span id="hidden%1$s" class="scaleXToZero">%2$s</span>)</span>',
                $RuleClass,
                substr($Options, 1, -1)
            );
        }
        $Options = ' – ' . $Options;

        /** Begin generating rule output. */
        $Output .= sprintf(
            '%1$s<li class="%2$s"><span class="comCat s">%3$s</span>%4$s%5$s%1$s  <ul class="comSub">',
            "\n      ",
            $RuleClass . (empty($Data['Disable this rule']) ? '' : ' hB fBlur"'),
            $Name,
            $Options,
            isset($Data['Notes']) ? '<div class="iCntn"><em>' . str_replace(['<', '>', "\n"], ['&lt;', '&gt;', "<br />\n"], $Data['Notes']) . '</em></div>' : ''
        );

        /** Additional details about the rule to print to the page (e.g., detailed block reason). */
        foreach ([
            ['Reason', 'label_aux_reason'],
            ['Target', 'label_aux_target'],
            ['Webhooks', 'label_aux_webhooks']
        ] as $Details) {
            if (!empty($Data[$Details[0]]) && $Label = $CIDRAM['L10N']->getString($Details[1])) {
                if (is_array($Data[$Details[0]])) {
                    $Data[$Details[0]] = implode('</div><div class="iCntn">', $Data[$Details[0]]);
                }
                $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $Label . '</div><div class="iCntn">' . $Data[$Details[0]] . '</div></div></li>';
            }
        }

        /** Populate from and expiry. */
        foreach ([
            ['From', 'label_aux_from'],
            ['Expiry', 'label_aux_expiry']
        ] as $Details) {
            if (!empty($Data[$Details[0]]) && $Label = $CIDRAM['L10N']->getString($Details[1])) {
                if (preg_match('~^(\d{4})[.-](\d\d)[.-](\d\d)$~', $Data[$Details[0]], $Details[2])) {
                    $Data[$Details[0]] .= ' (' . $CIDRAM['RelativeTime'](
                        mktime(0, 0, 0, (int)$Details[2][2], (int)$Details[2][3], (int)$Details[2][1])
                    ) . ')';
                }
                $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $Label . '</div><div class="iCntn">' . $Data[$Details[0]] . '</div></div></li>';
            }
        }

        /** Display the status code to be applied. */
        if (!empty($Data['Status Code']) && $Data['Status Code'] > 200 && $StatusCode = $CIDRAM['GetStatusHTTP']($Data['Status Code'])) {
            $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $CIDRAM['L10N']->getString('label_aux_http_status_code_override') . '</div><div class="iCntn">' . $Data['Status Code'] . ' ' . $StatusCode . '</div></div></li>';
        }

        /** Iterate through actions. */
        foreach ([
            ['Whitelist', 'optActWhl'],
            ['Greylist', 'optActGrl'],
            ['Block', 'optActBlk'],
            ['Bypass', 'optActByp'],
            ['Don\'t log', 'optActLog'],
            ['Redirect', 'optActRdr'],
            ['Run', 'optActRun'],
            ['Profile', 'optActPro']
        ] as $Action) {
            /** Skip action if the current rule doesn't use this action. */
            if (empty($Data[$Action[0]])) {
                continue;
            }

            /** Show the appropriate label for this action. */
            $Output .= sprintf(
                '%1$s<li>%1$s  <div class="iCntr">%1$s    <div class="iLabl s">%2$s</div>',
                "\n          ",
                $CIDRAM['FE'][$Action[1]]
            );

            /** List all "not equals" conditions . */
            if (!empty($Data[$Action[0]]['But not if matches'])) {
                /** Iterate through sources. */
                foreach ($Data[$Action[0]]['But not if matches'] as $Source => $Values) {
                    $ThisSource = $Sources[$Source] ?? $Source;
                    if (!is_array($Values)) {
                        $Values = [$Values];
                    }
                    foreach ($Values as $Value) {
                        if ($Value === '') {
                            $Value = '&nbsp;';
                        }
                        $Operator = $CIDRAM['OperatorFromAuxValue']($Value, true);
                        $Output .= "\n              <div class=\"iCntn\"><span style=\"float:" . $CIDRAM['FE']['FE_Align'] . '">' . $ThisSource . '&nbsp;' . $Operator . '&nbsp;</span><code>' . $Value . '</code></div>';
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
                        if ($Value === '') {
                            $Value = '&nbsp;';
                        }
                        $Operator = $CIDRAM['OperatorFromAuxValue']($Value);
                        $Output .= "\n              <div class=\"iCntn\"><span style=\"float:" . $CIDRAM['FE']['FE_Align'] . '">' . $ThisSource . '&nbsp;' . $Operator . '&nbsp;</span><code>' . $Value . '</code></div>';
                    }
                }
            }

            /** Finish writing conditions list. */
            $Output .= "\n            </div>\n          </li>";
        }

        /** Cite the file to run. */
        if (!empty($Data['Run']['File']) && $Label = $CIDRAM['L10N']->getString('label_aux_run')) {
            $Output .= "\n            <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $Label . '</div><div class="iCntn">' . $Data['Run']['File'] . '</div></div></li>';
        }

        /** Display other options and special flags. */
        $Flags = [];
        foreach ($CIDRAM['Config']['Provide']['Auxiliary Rules']['Flags'] as $FlagSetName => $FlagSet) {
            foreach ($FlagSet as $FlagName => $FlagData) {
                if (!is_array($FlagData) || empty($FlagData['Label'])) {
                    continue;
                }
                $Label = isset($FlagData['Label']) ? ($CIDRAM['L10N']->getString($FlagData['Label']) ?: $FlagData['Label']) : $FlagData['Label'];
                if (!empty($Data[$FlagName])) {
                    $Flags[] = $Label;
                }
            }
        }
        if (count($Flags)) {
            $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $CIDRAM['L10N']->getString('label_aux_special') . '</div><div class="iCntn">' . implode('<br />', $Flags) . '</div></div></li>';
        }

        /** Show the method to be used. */
        $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl\"><em>" . (isset($Data['Method']) ? (
            $Data['Method'] === 'RegEx' ? $CIDRAM['FE']['optMtdReg'] : (
                $Data['Method'] === 'WinEx' ? $CIDRAM['FE']['optMtdWin'] : $CIDRAM['FE']['optMtdStr']
            )
        ) : $CIDRAM['FE']['optMtdStr']) . '</em></div></div></li>';

        /** Describe matching logic used. */
        $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl\"><em>" . $CIDRAM['L10N']->getString(
            (!empty($Data['Logic']) && $Data['Logic'] !== 'Any') ? 'label_aux_logic_all' : 'label_aux_logic_any'
        ) . '</em></div></div></li>';

        /** Finish writing new rule. */
        $Output .= "\n        </ul>\n      </li>";
        $Current++;
    }

    /** Update button after. */
    if ($Mode) {
        $StyleClass = $StyleClass === 'ng1' ? 'ng2' : 'ng1';
        $Output .= sprintf(
            '<div class="%s"><center><input type="submit" value="%s" class="auto" /></center></div>',
            $StyleClass,
            $CIDRAM['L10N']->getString('field_update_all')
        );
    };

    /** Exit with generated output. */
    return $Output . '<script type="text/javascript">' . $JSAppend . '</script>';
};

/**
 * Generate select options from an associative array.
 *
 * @param array $Options An associative array of the options to generate.
 * @param string $Trim An optional regex of data to remove from the labels.
 * @param bool $JS Whether generating for JavaScript.
 * @return string The generated options.
 */
$CIDRAM['GenerateOptions'] = function (array $Options, string $Trim = '', bool $JS = false) use (&$CIDRAM): string {
    $Output = '';
    foreach ($Options as $Value => $Label) {
        if (is_array($Label)) {
            $Output .= $CIDRAM['GenerateOptions']($Label, $Trim, $JS);
            continue;
        }
        $Label = $CIDRAM['L10N']->getString($Label) ?: $Label;
        if ($Trim) {
            $Label = preg_replace($Trim, '', $Label);
        }
        if ($JS) {
            $Output .= "\n  x = document.createElement('option'),\n  x.setAttribute('value', '" . $Value . "'),\n  x.innerHTML = '" . $Label . "',\n  t.appendChild(x),";
        } else {
            $Output .= '<option value="' . $Value . '">' . $Label . '</option>';
        }
    }
    return $Output;
};

/**
 * Generate labels from an associative array.
 *
 * @param array $Options An associative array of the labels to generate.
 * @param string $Trim An optional regex of data to remove from the labels.
 * @return array The generated labels.
 */
$CIDRAM['GenerateLabels'] = function (array $Options, string $Trim = '') use (&$CIDRAM): array {
    $Output = [];
    foreach ($Options as $Value => $Label) {
        if (is_array($Label)) {
            $Output = array_merge($Output, $CIDRAM['GenerateLabels']($Label, $Trim));
            continue;
        }
        $Label = $CIDRAM['L10N']->getString($Label) ?: $Label;
        if ($Trim) {
            $Label = preg_replace($Trim, '', $Label);
        }
        $Output[$Value] = $Label;
    }
    return $Output;
};

/**
 * Procedure to populate methods, actions, and sources used by the auxiliary rules page.
 *
 * @return void
 */
$CIDRAM['PopulateMethodsActions'] = function () use (&$CIDRAM): void {
    /** Append JavaScript specific to the auxiliary rules page. */
    $CIDRAM['FE']['JS'] .= $CIDRAM['ParseVars'](
        ['tip_condition_placeholder' => $CIDRAM['L10N']->getString('tip_condition_placeholder')],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('auxiliary.js'))
    );

    /** Populate methods. */
    $CIDRAM['FE']['optMtdStr'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_method'), $CIDRAM['L10N']->getString('label_aux_mtdStr'));
    $CIDRAM['FE']['optMtdReg'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_method'), $CIDRAM['L10N']->getString('label_aux_mtdReg'));
    $CIDRAM['FE']['optMtdWin'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_method'), $CIDRAM['L10N']->getString('label_aux_mtdWin'));

    /** Populate actions. */
    $CIDRAM['FE']['optActWhl'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_action'), $CIDRAM['L10N']->getString('label_aux_actWhl'));
    $CIDRAM['FE']['optActGrl'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_action'), $CIDRAM['L10N']->getString('label_aux_actGrl'));
    $CIDRAM['FE']['optActBlk'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_action'), $CIDRAM['L10N']->getString('label_aux_actBlk'));
    $CIDRAM['FE']['optActByp'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_action'), $CIDRAM['L10N']->getString('label_aux_actByp'));
    $CIDRAM['FE']['optActLog'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_action'), $CIDRAM['L10N']->getString('label_aux_actLog'));
    $CIDRAM['FE']['optActRdr'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_action'), $CIDRAM['L10N']->getString('label_aux_actRdr'));
    $CIDRAM['FE']['optActRun'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_action'), $CIDRAM['L10N']->getString('label_aux_actRun'));
    $CIDRAM['FE']['optActPro'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_action'), $CIDRAM['L10N']->getString('label_aux_actPro'));

    /** Populate sources. */
    $CIDRAM['FE']['conSources'] = $CIDRAM['GenerateOptions']($CIDRAM['Config']['Provide']['Auxiliary Rules']['Sources'], $CIDRAM['RegExLabels']);

    /** Populate sources for JavaScript. */
    $CIDRAM['FE']['conSourcesJS'] = $CIDRAM['GenerateOptions']($CIDRAM['Config']['Provide']['Auxiliary Rules']['Sources'], $CIDRAM['RegExLabels'], true);
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
$CIDRAM['ArrayToClickableList'] = function (array $Arr = [], string $DeleteKey = '', int $Depth = 0, string $ParentKey = '') use (&$CIDRAM): string {
    $Output = '';
    $Count = count($Arr);
    $Prefix = substr($DeleteKey, 0, 2) === 'fe' ? 'FE' : '';
    foreach ($Arr as $Key => $Value) {
        if (is_string($Value) && !$CIDRAM['Demojibakefier']->checkConformity($Value)) {
            continue;
        }
        $Delete = ($Depth === 0) ? ' – (<span style="cursor:pointer" onclick="javascript:' . $DeleteKey . '(\'' . addslashes($Key) . '\')"><code class="s"><span class="txtRd">⌧</span>' . $CIDRAM['L10N']->getString('field_delete') . '</code></span>)' : '';
        $Output .= ($Depth === 0 ? '<span id="' . $Key . $Prefix . 'Container">' : '') . '<li>';
        if (is_string($Value)) {
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
            $Output .= '<span class="comCat"><code class="s">' . str_replace(['<', '>'], ['&lt;', '&gt;'], $Key) . '</code></span>' . $Delete . '<ul class="comSub">';
            $Output .= $CIDRAM['ArrayToClickableList']($Value, $DeleteKey, $Depth + 1, $Key);
            $Output .= '</ul>';
        } else {
            if ($Key === 'Time' && preg_match('~^\d+$~', $Value)) {
                $Key = $CIDRAM['L10N']->getString('label_expires');
                $Value = $CIDRAM['TimeFormat']($Value, $CIDRAM['Config']['general']['time_format']);
            }
            $Class = ($Key === $CIDRAM['L10N']->getString('field_size') || $Key === $CIDRAM['L10N']->getString('label_expires')) ? 'txtRd' : 's';
            $Text = ($Count === 1 && $Key === 0) ? $Value : $Key . ($Class === 's' ? ' => ' : ' ') . $Value;
            $Output .= '<code class="' . $Class . '" style="word-wrap:break-word;word-break:break-all">' . $CIDRAM['LTRinRTF'](
                str_replace(['<', '>'], ['&lt;', '&gt;'], $Text)
            ) . '</code>' . $Delete;
        }
        $Output .= '</li>' . ($Depth === 0 ? '<br /></span>' : '');
    }
    return $Output;
};

/**
 * Append to the current state message.
 *
 * @param string $Message What to append.
 * @return void
 */
$CIDRAM['Message'] = function (string $Message) use (&$CIDRAM): void {
    if (isset($CIDRAM['FE']['state_msg'])) {
        if ($Try = $CIDRAM['L10N']->getString($Message)) {
            $Message = $Try;
        }
        $CIDRAM['FE']['state_msg'] .= $Message . '<br />';
    }
};

/**
 * Append to the current executor queue.
 *
 * @param string $Message What to append.
 * @return void
 */
$CIDRAM['Queue'] = function (string $Message) use (&$CIDRAM): void {
    $CIDRAM['FE_Executor']($Message, true);
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
$CIDRAM['RGB'] = function (string $String = '', int $Mode = 0) {
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
$CIDRAM['LTRinRTF'] = function (string $String = '') use (&$CIDRAM): string {
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
    while (true) {
        $NewString = preg_replace(
            ['~^(.+)( +)-&gt;( +)(.+)$~i', '~^(.+)-&gt;(.+)$~i', '~^(.+)( +)➡( +)(.+)$~i', '~^(.+)➡(.+)$~i', '~^(.+)( +)=&gt;( +)(.+)$~i', '~^(.+)=&gt;(.+)$~i'],
            ['\4\2&lt;-\3\1', '\2&lt;-\1', '\4\2⬅\3\1', '\2⬅\1', '\4\2&lt;=\3\1', '\2&lt;=\1'],
            $String
        );
        if ($NewString === $String) {
            return $NewString;
        }
        $String = $NewString;
    }
};

/**
 * Splits a CIDR into two smaller CIDRs of the same total value.
 *
 * @param string $CIDR The CIDR to split.
 * @return array An array containing two elements (the smaller CIDRs), or an
 *      empty array on faliure (e.g., supplied data isn't a valid CIDR).
 */
$CIDRAM['SplitCIDR'] = function (string $CIDR) use (&$CIDRAM): array {
    if (($Pos = strpos($CIDR, '/')) === false) {
        return [];
    }
    $Base = substr($CIDR, 0, $Pos);
    $Factor = substr($CIDR, $Pos + 1);
    if ($CIDRs = $CIDRAM['ExpandIPv4']($Base, false, $Factor + 1)) {
        $Is = 4;
    } elseif ($CIDRs = $CIDRAM['ExpandIPv6']($Base, false, $Factor + 1)) {
        $Is = 6;
    } else {
        return [];
    }
    if ($Factor < 1 || ($Is === 4 && $Factor >= 32) || ($Is === 6 && $Factor >= 128)) {
        return [];
    }
    $Split = [$CIDRs[$Factor]];
    $Last = ($Is === 4) ? $CIDRAM['IPv4GetLast']($Base, $Factor) : $CIDRAM['IPv6GetLast']($Base, $Factor);
    $CIDRs = ($Is === 4) ? $CIDRAM['ExpandIPv4']($Last, false, $Factor + 1) : $CIDRAM['ExpandIPv6']($Last, false, $Factor + 1);
    if (isset($CIDRs[$Factor])) {
        $Split[] = $CIDRs[$Factor];
        return $Split;
    }
    return [];
};

/**
 * Returns the intersect of two sets of CIDRs.
 *
 * @param string $A Set A.
 * @param string $B Set B.
 * @param int $Format The format to return the results as.
 *      1 = Netmasks. 0 = CIDRs.
 * @return string The intersect.
 */
$CIDRAM['IntersectCIDR'] = function (string $A = '', string $B = '', int $Format = 0) use (&$CIDRAM): string {
    $StrObject = new \Maikuolan\Common\ComplexStringHandler(
        $A . "\n",
        $CIDRAM['RegExTags'],
        function (string $Data) use (&$CIDRAM, $B, $Format): string {
            $Data = "\n" . $CIDRAM['Aggregator']->aggregate($Data) . "\n";
            $Intersect = '';
            foreach ([['B', 'Data'], ['Data', 'B']] as $Points) {
                $LPos = 0;
                while (($NPos = strpos(${$Points[0]}, "\n", $LPos)) !== false) {
                    $Line = substr(${$Points[0]}, $LPos, $NPos - $LPos);
                    $LPos = $NPos + 1;
                    if (($DPos = strpos($Line, '/')) !== false) {
                        $Range = substr($Line, $DPos + 1);
                        $Base = substr($Line, 0, $DPos);
                    } else {
                        continue;
                    }
                    if (!$CIDRs = $CIDRAM['ExpandIPv4']($Base)) {
                        if (!$CIDRs = $CIDRAM['ExpandIPv6']($Base)) {
                            continue;
                        }
                    }
                    foreach ($CIDRs as $Key => $Actual) {
                        if (strpos(${$Points[1]}, "\n" . $Actual . "\n") === false) {
                            continue;
                        }
                        if (($Key + 1) > (int)$Range) {
                            $Intersect .= $Actual . "\n";
                        } else {
                            $Intersect .= $Line . "\n";
                        }
                        break;
                    }
                }
            }
            $Aggregator = new \CIDRAM\Aggregator\Aggregator($CIDRAM, $Format);
            return trim($Aggregator->aggregate($Intersect));
        }
    );
    $StrObject->iterateClosure(function (string $Data): string {
        return "\n" . $Data;
    }, true);
    return trim($StrObject->recompile());
};

/**
 * Subtracts subtrahend CIDRs from minuend CIDRs and returns the difference.
 *
 * @param string $Minuend The minuend (assumes no erroneous data).
 * @param string $Subtrahend The subtrahend (assumes no erroneous data).
 * @param int $Format The format to return the results as.
 *      1 = Netmasks. 0 = CIDRs.
 * @return string The difference.
 */
$CIDRAM['SubtractCIDR'] = function (string $Minuend = '', string $Subtrahend = '', int $Format = 0) use (&$CIDRAM): string {
    $StrObject = new \Maikuolan\Common\ComplexStringHandler(
        $Minuend . "\n",
        $CIDRAM['RegExTags'],
        function (string $Minuend) use (&$CIDRAM, $Subtrahend, $Format): string {
            $Minuend = "\n" . $CIDRAM['Aggregator']->aggregate($Minuend . "\n" . $Subtrahend) . "\n";
            $LPos = 0;
            while (($NPos = strpos($Subtrahend, "\n", $LPos)) !== false) {
                $Line = substr($Subtrahend, $LPos, $NPos - $LPos);
                $LPos = $NPos + 1;
                if (($DPos = strpos($Line, '/')) !== false) {
                    $Range = substr($Line, $DPos + 1);
                    $Base = substr($Line, 0, $DPos);
                } else {
                    continue;
                }
                if (!$CIDRs = $CIDRAM['ExpandIPv4']($Base, false, $Range)) {
                    if (!$CIDRs = $CIDRAM['ExpandIPv6']($Base, false, $Range)) {
                        continue;
                    }
                }
                foreach ($CIDRs as $Key => $Actual) {
                    if (strpos($Minuend, "\n" . $Actual . "\n") === false) {
                        continue;
                    }
                    if ($Range > ($Key + 1) && $Split = $CIDRAM['SplitCIDR']($Actual)) {
                        $Minuend .= implode("\n", $Split) . "\n";
                    }
                    $Minuend = str_replace("\n" . $Actual . "\n", "\n", $Minuend);
                }
            }
            $Aggregator = new \CIDRAM\Aggregator\Aggregator($CIDRAM, $Format);
            return trim($Aggregator->aggregate($Minuend));
        }
    );
    $StrObject->iterateClosure(function (string $Data): string {
        return "\n" . $Data;
    }, true);
    return trim($StrObject->recompile());
};

/**
 * Extract page beginning with delimiter from YAML data or an empty string.
 *
 * @param string $Data The YAML data.
 * @return string The extracted YAML page or an empty string on failure.
 */
$CIDRAM['ExtractPage'] = function (string $Data = ''): string {
    if (substr($Data, 0, 4) !== "---\n" || substr($Data, -1) !== "\n") {
        return '';
    }
    return substr($Data, 4);
};

/**
 * A callback closure used by the matrix handler to increment coordinates.
 *
 * @param string $Current The value of the current coordinate.
 * @param string $Key The key of the current coordinate (expected, but not used by this callback).
 * @param string $Previous The value of the previous coordinate (expected, but not used by this callback).
 * @param string $KeyPrevious The key of the previous coordinate (expected, but not used by this callback).
 * @param string $Next The value of the next coordinate (expected, but not used by this callback).
 * @param string $KeyNext The key of the next coordinate (expected, but not used by this callback).
 * @param string $Step Can be used to manipulate the vector trajectory (expected, but not used by this callback).
 * @param string $Amount Contains information such as the type and amount of value to be added to the coordinate.
 */
$CIDRAM['Matrix-Increment'] = function (&$Current, $Key, &$Previous, $KeyPrevious, &$Next, $KeyNext, &$Step, $Amount) {
    if (
        !is_array($Current) ||
        !isset($Current['R'], $Current['G'], $Current['B'], $Amount[0], $Amount[1]) ||
        ($Amount[0] !== 'R' && $Amount[0] !== 'G' && $Amount[0] !== 'B')
    ) {
        return;
    }
    $Current[$Amount[0]] += $Amount[1];
};

/**
 * A callback closure used by the matrix handler to limit a coordinate's RGB values.
 *
 * @param string $Current The value of the current coordinate.
 */
$CIDRAM['Matrix-Limit'] = function (&$Current) {
    if (!is_array($Current) || !isset($Current['R'], $Current['G'], $Current['B'])) {
        return;
    }
    foreach ($Current as &$RGB) {
        if (!is_int($RGB) || $RGB < 0) {
            $RGB = 0;
        }
        $RGB = ceil($RGB);
        if ($RGB > 255) {
            $RGB = 255;
        }
    }
};

/**
 * A callback closure used by the matrix handler to draw an image from a matrix.
 *
 * @param string $Current The value of the current coordinate.
 * @param string $Key The key of the current coordinate.
 * @param string $Previous The value of the previous coordinate (expected, but not used by this callback).
 * @param string $KeyPrevious The key of the previous coordinate (expected, but not used by this callback).
 * @param string $Next The value of the next coordinate (expected, but not used by this callback).
 * @param string $KeyNext The key of the next coordinate (expected, but not used by this callback).
 * @param string $Step Can be used to manipulate the vector trajectory (expected, but not used by this callback).
 * @param string $Offsets Contains offsets between the matrix coordinates to the image XY coordinates.
 * @return void
 */
$CIDRAM['Matrix-Draw'] = function (&$Current, $Key, &$Previous, $KeyPrevious, &$Next, $KeyNext, &$Step, $Offsets) use (&$CIDRAM): void {
    if (!is_array($Current) || !is_array($Offsets) || !isset($Current['R'], $Current['G'], $Current['B'], $CIDRAM['Matrix-Image'])) {
        return;
    }
    $Colour = ($Current['R'] * 65536) + ($Current['G'] * 256) + $Current['B'];
    $XY = explode(',', $Key);
    $X = $XY[0] ?? 0;
    $Y = $XY[1] ?? 0;
    if (is_array($Offsets) && isset($Offsets[0], $Offsets[1]) && is_int($Offsets[0]) && is_int($Offsets[1])) {
        $X += $Offsets[0];
        $Y += $Offsets[1];
    }
    imagesetpixel($CIDRAM['Matrix-Image'], $X, $Y, $Colour);
};

/**
 * Yields the ranges of the signatures in the currently active signature files
 * as values to be used as coordinates in the matrices generated by the
 * matrix handler for use at the front-end range tables page.
 *
 * @param string $Source The contents of the currently active signature files.
 */
$CIDRAM['Matrix-Create-Generator'] = function (string &$Source) use (&$CIDRAM): \Generator {
    $SPos = 0;
    while (($FPos = strpos($Source, "\n", $SPos)) !== false) {
        $Mark = [];
        if (isset($CIDRAM['Matrix-%Print'])) {
            $CIDRAM['Matrix-%Print']();
        }
        $Line = substr($Source, $SPos, $FPos - $SPos);
        $SPos = $FPos + 1;
        if ($Line === '' || substr($Line, 0, 1) === '#') {
            continue;
        }
        $Matches = [];
        if (preg_match('~^(\d+).(\d+).(\d+).(\d+)/(\d+) (Deny|Whitelist|Greylist|Run)~', $Line, $Matches)) {
            $Mark['6or4'] = 4;
            if ($Matches[6] === 'Deny') {
                $Mark['Colour'] = 'R';
            } elseif ($Matches[6] === 'Run') {
                $Mark['Colour'] = 'B';
            } else {
                $Mark['Colour'] = 'G';
            }
            if ($Matches[5] <= 8) {
                for ($Iterant = $Matches[5], $To = 256; $Iterant > 0; $Iterant--) {
                    $To /= 2;
                }
                $To += $Matches[1] - 1;
                $Mark['Range'] = $Matches[1] . '-' . $To . ',0-255';
                $Mark['Amount'] = 255;
            } elseif ($Matches[5] <= 16) {
                for ($Iterant = $Matches[5] - 8, $To = 256; $Iterant > 0; $Iterant--) {
                    $To /= 2;
                }
                $To += $Matches[2] - 1;
                $Mark['Range'] = $Matches[1] . ',' . $Matches[2] . '-' . $To;
                $Mark['Amount'] = 255;
            } elseif ($Matches[5] <= 24) {
                for ($Iterant = $Matches[5] - 16, $To = 256; $Iterant > 0; $Iterant--) {
                    $To /= 2;
                }
                $To += $Matches[3] - 1;
                $Mark['Range'] = $Matches[1] . ',' . $Matches[2];
                $Mark['Amount'] = $To;
            } else {
                for ($Iterant = $Matches[5] - 24, $To = 256; $Iterant > 0; $Iterant--) {
                    $To /= 2;
                }
                $To += $Matches[4] - 1;
                $Mark['Range'] = $Matches[1] . ',' . $Matches[2];
                $Mark['Amount'] = $To / 256;
            }
            yield $Mark;
        } elseif (
            preg_match('~^()([\da-f]{1,2})()()::/(\d+) (Deny|Whitelist|Greylist|Run)~', $Line, $Matches) ||
            preg_match('~^([\da-f]{1,2})([\da-f]{2})()()::/(\d+) (Deny|Whitelist|Greylist|Run)~', $Line, $Matches) ||
            preg_match('~^([\da-f]{1,2})([\da-f]{2}):()([\da-f]{1,2})::/(\d+) (Deny|Whitelist|Greylist|Run)~', $Line, $Matches) ||
            preg_match('~^([\da-f]{1,2})([\da-f]{2}):([\da-f]{1,2})([\da-f]{2})::/(\d+) (Deny|Whitelist|Greylist|Run)~', $Line, $Matches)
        ) {
            $Mark['6or4'] = 6;
            if ($Matches[6] === 'Deny') {
                $Mark['Colour'] = 'R';
            } elseif ($Matches[6] === 'Run') {
                $Mark['Colour'] = 'B';
            } else {
                $Mark['Colour'] = 'G';
            }
            if ($Matches[5] <= 8) {
                for ($Iterant = $Matches[5], $To = 256; $Iterant > 0; $Iterant--) {
                    $To /= 2;
                }
                $To += hexdec($Matches[1]) - 1;
                $Mark['Range'] = hexdec($Matches[1]) . '-' . $To . ',0-255';
                $Mark['Amount'] = 255;
            } elseif ($Matches[5] <= 16) {
                for ($Iterant = $Matches[5] - 8, $To = 256; $Iterant > 0; $Iterant--) {
                    $To /= 2;
                }
                $To += hexdec($Matches[2]) - 1;
                $Mark['Range'] = hexdec($Matches[1]) . ',' . hexdec($Matches[2]) . '-' . $To;
                $Mark['Amount'] = 255;
            } elseif ($Matches[5] <= 24) {
                for ($Iterant = $Matches[5] - 16, $To = 256; $Iterant > 0; $Iterant--) {
                    $To /= 2;
                }
                $To += hexdec($Matches[3]) - 1;
                $Mark['Range'] = hexdec($Matches[1]) . ',' . hexdec($Matches[2]);
                $Mark['Amount'] = $To;
            } else {
                for ($Iterant = $Matches[5] - 24, $To = 256; $Iterant > 0; $Iterant--) {
                    $To /= 2;
                }
                $To += hexdec($Matches[4]) - 1;
                $Mark['Range'] = hexdec($Matches[1]) . ',' . hexdec($Matches[2]);
                $Mark['Amount'] = $To / 256;
            }
            yield $Mark;
        }
    }
};

/**
 * Uses the matrix handler to create an image from the ranges of the signatures
 * in the currently active signature files (requires GD functionality).
 *
 * @param string $Source The contents of the currently active signature files.
 * @param string $Destination Where to save the image file after rendering it
*       with the CLI tool (has no effect when CLI is false and can be omitted).
 * @param bool $CLI Should be true when called via the CLI tool and should
 *      otherwise always be false.
 * @return string Raw PNG data (or an empty string if CLI is true).
 */
$CIDRAM['Matrix-Create'] = function (string &$Source, string $Destination = '', bool $CLI = false) use (&$CIDRAM): string {
    if ($CLI) {
        $Splits = ['Percentage' => 0, 'Skip' => 0];
        $Splits['Max'] = substr_count($Source, "\n");
        $CIDRAM['Matrix-%Print'] = function () use (&$Splits, &$CIDRAM) {
            $Splits['Percentage']++;
            $Splits['Skip']++;
            if ($Splits['Percentage'] >= $Splits['Max']) {
                $Splits['Max'] = $Splits['Percentage'] + 1;
            }
            $Current = $Splits['Percentage'] / $Splits['Max'];
            if ($Splits['Skip'] > 24) {
                $Splits['Skip'] = 0;
                $Memory = memory_get_usage();
                $CIDRAM['FormatFilesize']($Memory);
                echo "\rWorking ... " . $CIDRAM['NumberFormatter']->format($Current, 2) . '% (' . $CIDRAM['TimeFormat'](time(), $CIDRAM['Config']['general']['time_format']) . ') <RAM: ' . $Memory . '>';
            }
        };
        echo "\rWorking ...";
    } elseif (isset($CIDRAM['Matrix-%Print'])) {
        unset($CIDRAM['Matrix-%Print']);
    }

    $IPv4 = new \Maikuolan\Common\Matrix();
    $IPv4->createMatrix(2, 256, ['R' => 0, 'G' => 0, 'B' => 0]);

    $IPv6 = new \Maikuolan\Common\Matrix();
    $IPv6->createMatrix(2, 256, ['R' => 0, 'G' => 0, 'B' => 0]);

    foreach ($CIDRAM['Matrix-Create-Generator']($Source) as $Mark) {
        if ($Mark['6or4'] === 4) {
            $IPv4->iterateCallback($Mark['Range'], $CIDRAM['Matrix-Increment'], $Mark['Colour'], $Mark['Amount']);
        } elseif ($Mark['6or4'] === 6) {
            $IPv6->iterateCallback($Mark['Range'], $CIDRAM['Matrix-Increment'], $Mark['Colour'], $Mark['Amount']);
        }
    }

    $IPv4->iterateCallback('0-255,0-255', $CIDRAM['Matrix-Limit']);
    $IPv6->iterateCallback('0-255,0-255', $CIDRAM['Matrix-Limit']);

    $Wheel = new \Maikuolan\Common\Matrix();
    $Wheel->createMatrix(2, 24, ['R' => 0, 'G' => 0, 'B' => 0]);
    $Wheel->iterateCallback('0-8,12-15', $CIDRAM['Matrix-Increment'], 'R', 32);
    $Wheel->iterateCallback('0-8,8-15', $CIDRAM['Matrix-Increment'], 'R', 32);
    $Wheel->iterateCallback('0-8,4-15', $CIDRAM['Matrix-Increment'], 'R', 32);
    $Wheel->iterateCallback('0-8,0-15', $CIDRAM['Matrix-Increment'], 'R', 32);
    $Wheel->iterateCallback('5-13,16-19', $CIDRAM['Matrix-Increment'], 'B', 32);
    $Wheel->iterateCallback('5-13,12-19', $CIDRAM['Matrix-Increment'], 'B', 32);
    $Wheel->iterateCallback('5-13,8-19', $CIDRAM['Matrix-Increment'], 'B', 32);
    $Wheel->iterateCallback('5-13,4-19', $CIDRAM['Matrix-Increment'], 'B', 32);
    $Wheel->iterateCallback('10-18,20-23', $CIDRAM['Matrix-Increment'], 'G', 32);
    $Wheel->iterateCallback('10-18,16-23', $CIDRAM['Matrix-Increment'], 'G', 32);
    $Wheel->iterateCallback('10-18,12-23', $CIDRAM['Matrix-Increment'], 'G', 32);
    $Wheel->iterateCallback('10-18,8-23', $CIDRAM['Matrix-Increment'], 'G', 32);

    $CIDRAM['Matrix-Image'] = imagecreatetruecolor(544, 308);

    /** Roofs. */
    imageline($CIDRAM['Matrix-Image'], 10, 48, 269, 48, 16777215);
    imageline($CIDRAM['Matrix-Image'], 284, 48, 543, 48, 16777215);

    /** Walls. */
    imageline($CIDRAM['Matrix-Image'], 10, 48, 10, 307, 16777215);
    imageline($CIDRAM['Matrix-Image'], 269, 48, 269, 307, 16777215);
    imageline($CIDRAM['Matrix-Image'], 284, 48, 284, 307, 16777215);
    imageline($CIDRAM['Matrix-Image'], 543, 48, 543, 307, 16777215);

    /** Floors. */
    imageline($CIDRAM['Matrix-Image'], 10, 307, 269, 307, 16777215);
    imageline($CIDRAM['Matrix-Image'], 284, 307, 543, 307, 16777215);

    imagestring($CIDRAM['Matrix-Image'], 2, 12, 2, 'CIDRAM signature file analysis (image generated ' . date('Y.m.d', time()) . ').', 16777215);
    imagefilledrectangle($CIDRAM['Matrix-Image'], 12, 14, 22, 24, 16711680);
    imagestring($CIDRAM['Matrix-Image'], 2, 24, 12, '"Deny" signatures', 16711680);
    imagefilledrectangle($CIDRAM['Matrix-Image'], 130, 14, 140, 24, 65280);
    imagestring($CIDRAM['Matrix-Image'], 2, 142, 12, '"Whitelist" + "Greylist" signatures', 65280);
    imagefilledrectangle($CIDRAM['Matrix-Image'], 356, 14, 366, 24, 255);
    imagestring($CIDRAM['Matrix-Image'], 2, 368, 12, '"Run" signatures', 255);

    imagestring($CIDRAM['Matrix-Image'], 2, 14, 36, '< 0.0.0.0/8        IPv4      255.0.0.0/8 >', 16777215);
    imagestring($CIDRAM['Matrix-Image'], 2, 288, 36, '< 00xx::/8         IPv6         ffxx::/8 >', 16777215);
    imagestringup($CIDRAM['Matrix-Image'], 2, -2, 304, '< x.255.0.0/16                x.0.0.0/16 >', 16777215);
    imagestringup($CIDRAM['Matrix-Image'], 2, 272, 304, '< xxff::/16                    xx00::/16 >', 16777215);

    $IPv4->iterateCallback('0-255,0-255', $CIDRAM['Matrix-Draw'], 12, 50);
    $IPv6->iterateCallback('0-255,0-255', $CIDRAM['Matrix-Draw'], 286, 50);
    $Wheel->iterateCallback('0-24,0-24', $CIDRAM['Matrix-Draw'], 519, 2);

    if ($CLI) {
        if (!$Destination) {
            $Destination = 'export.png';
        }
        imagepng($CIDRAM['Matrix-Image'], $CIDRAM['Vault'] . $Destination);
        $Memory = memory_get_usage();
        $CIDRAM['FormatFilesize']($Memory);
        echo "\rWorking ... " . $CIDRAM['NumberFormatter']->format(100, 2) . '% (' . $CIDRAM['TimeFormat'](time(), $CIDRAM['Config']['general']['time_format']) . ') <RAM: ' . $Memory . '>' . "\n\n";
    } else {
        ob_start();
        imagepng($CIDRAM['Matrix-Image']);
        $Out = ob_get_contents();
        ob_end_clean();
        return $Out;
    }
    imagedestroy($CIDRAM['Matrix-Image']);
    return '';
};

/**
 * Determine whether all dependency constraints have been met.
 *
 * @param array $ThisComponent Reference to the component being worked upon.
 * @param bool $Source False for installed; True for available.
 * @param string $Name If specified, if not installed, won't check constraints.
 * @return void
 */
$CIDRAM['CheckConstraints'] = function (array &$ThisComponent, bool $Source = false, string $Name = '') use (&$CIDRAM): void {
    $ThisComponent['All Constraints Met'] = true;
    $ThisComponent['Dependency Status'] = '';
    if (!isset($ThisComponent['Dependencies']) && !empty($ThisComponent['Minimum Required'])) {
        $ThisComponent['Dependencies'] = ['CIDRAM Core' => '>=' . $ThisComponent['Minimum Required']];
    }
    if (!isset($ThisComponent['Dependencies']) || !is_array($ThisComponent['Dependencies']) || (
        $Name && !isset($CIDRAM['Components']['Installed Versions'][$Name])
    )) {
        return;
    }
    foreach ($ThisComponent['Dependencies'] as $Dependency => $Constraints) {
        $Dependency = str_replace('{lang}', $CIDRAM['Config']['general']['lang'], $Dependency);
        if ($Constraints === 'Latest') {
            if (isset($CIDRAM['Components']['Available Versions'][$Dependency])) {
                $Constraints = '>=' . $CIDRAM['Components']['Available Versions'][$Dependency];
            }
        }
        if ($Constraints === 'Latest' || strlen($Constraints) < 1) {
            $ThisComponent['All Constraints Met'] = false;
            $ThisComponent['Dependency Status'] .= sprintf(
                '<span class="txtRd">%s – %s</span><br />',
                $Dependency,
                $CIDRAM['L10N']->getString('response_not_satisfied')
            );
        } elseif ((
            isset($CIDRAM['Components']['Installed Versions'][$Dependency]) &&
            $CIDRAM['Operation']->singleCompare($CIDRAM['Components']['Installed Versions'][$Dependency], $Constraints)
        ) || (
            extension_loaded($Dependency) &&
            ($CIDRAM['Components']['Installed Versions'][$Dependency] = (new \ReflectionExtension($Dependency))->getVersion()) &&
            $CIDRAM['Operation']->singleCompare($CIDRAM['Components']['Installed Versions'][$Dependency], $Constraints)
        )) {
            $ThisComponent['Dependency Status'] .= sprintf(
                '<span class="txtGn">%s%s – %s</span><br />',
                $Dependency,
                $Constraints === '*' ? '' : ' (' . $Constraints . ')',
                $CIDRAM['L10N']->getString('response_satisfied')
            );
        } elseif (
            $Source &&
            isset($CIDRAM['Components']['Available Versions'][$Dependency]) &&
            $CIDRAM['Operation']->singleCompare($CIDRAM['Components']['Available Versions'][$Dependency], $Constraints)
        ) {
            $ThisComponent['Dependency Status'] .= sprintf(
                '<span class="txtOe">%s%s – %s</span><br />',
                $Dependency,
                $Constraints === '*' ? '' : ' (' . $Constraints . ')',
                $CIDRAM['L10N']->getString('response_ready_to_install')
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
                $CIDRAM['L10N']->getString('response_not_satisfied')
            );
        }
    }
    if ($ThisComponent['Dependency Status']) {
        $ThisComponent['Dependency Status'] = sprintf(
            '<hr /><small><span class="s">%s</span><br />%s</small>',
            $CIDRAM['L10N']->getString('label_dependencies'),
            $ThisComponent['Dependency Status']
        );
    }
};

/**
 * Determine which components are currently installed or available.
 *
 * @param array $Source Components metadata source.
 * @param array $To Where to set the information.
 * @return void
 */
$CIDRAM['CheckVersions'] = function (array &$Source, array &$To) use (&$CIDRAM): void {
    foreach ($Source as $Key => &$Component) {
        if (!empty($Component['Version']) && !empty($Component['Files']['To'])) {
            $To[$Key] = $Component['Version'];
        }
    }
};

/**
 * Recursively replace strings by reference.
 *
 * @param string|array $In The data to be worked with.
 * @param string|array $What What to replace.
 * @param string|array $With What to replace it with.
 * @return void
 */
$CIDRAM['RecursiveReplace'] = function (&$In, $What, $With) use (&$CIDRAM): void {
    if (is_string($In)) {
        $In = str_replace($What, $With, $In);
    }
    if (is_array($In)) {
        foreach ($In as &$Item) {
            $CIDRAM['RecursiveReplace']($Item, $What, $With);
        }
    }
};

/**
 * Check whether a variable has a value.
 *
 * @param mixed $Haystack What we're looking in (may be an array, or may be scalar).
 * @param mixed $Needle What we're looking for (may be an array, or may be scalar).
 * @return bool True if it does; False if it doesn't.
 */
$CIDRAM['Has'] = function ($Haystack, $Needle) use (&$CIDRAM): bool {
    if (is_array($Haystack)) {
        foreach ($Haystack as $ThisHaystack) {
            if ($CIDRAM['Has']($ThisHaystack, $Needle)) {
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
};

/**
 * Fetch L10N from module configuration L10N.
 *
 * @param string $Entry The L10N entry we're trying to fetch.
 * @return string The L10N entry, or an empty string on failure.
 */
$CIDRAM['FromModuleConfigL10N'] = function (string $Entry) use (&$CIDRAM): string {
    return $CIDRAM['Config']['L10N'][$CIDRAM['Config']['general']['lang']][$Entry] ?? $CIDRAM['Config']['L10N'][$Entry] ?? '';
};

/**
 * Split before the line at the specified boundary.
 *
 * @param string $Data The data to split.
 * @param string $Boundary Where to apply the split.
 * @return array The split data.
 */
$CIDRAM['SplitBeforeLine'] = function (string $Data, string $Boundary): array {
    $Len = strlen($Data);
    if ($Len < 1) {
        return ['', ''];
    }
    if ($Boundary === '') {
        return ['', $Data];
    }
    $BPos = strpos($Data, $Boundary);
    if ($BPos === false || $BPos < 1) {
        return ['', $Data];
    }
    $Offset = ($Len - $BPos) * -1;
    $LPos = strrpos($Data, "\n", $Offset);
    if ($LPos === false) {
        return ['', $Data];
    }
    return [substr($Data, 0, $LPos + 1), substr($Data, $LPos + 1)];
};

/**
 * Isolate the entry of the first field in a block.
 *
 * @param string $Block The block to isolate from.
 * @param string $Boundary The field separator.
 * @return string The isolated entry.
 */
$CIDRAM['IsolateFirstFieldEntry'] = function (string $Block, string $Separator): string {
    $Segment = '';
    if (($Position = strpos($Block, $Separator)) !== false) {
        $Segment = substr($Block, $Position + strlen($Separator));
        if (($FieldEndPos = strpos($Segment, "\n")) !== false) {
            $Segment = substr($Segment, 0, $FieldEndPos);
        }
    }
    return $Segment ?: '';
};

/**
 * Step through blocks.
 *
 * @param string $Data The data to step through.
 * @param int|false $Needle The needle position.
 * @param int|false $End The end position.
 * @param string $SearchQuery The search query (optional).
 * @param string $Direction Which direction to step through.
 * @return bool Whether successfully stepped or not.
 */
$CIDRAM['StepBlock'] = function (string $Data, &$Needle, $End, string $SearchQuery = '', string $Direction = '>') use (&$CIDRAM): bool {
    /** Guard. */
    if (!is_int($End) || $Data === '' || ($Direction !== '<' && $Direction !== '>')) {
        return false;
    }

    /** Needed for guards. */
    $DataLen = strlen($Data);

    /** Directionality. */
    if ($Direction === '>') {
        $StrFunction = 'strpos';
    } else {
        $StrFunction = 'strrpos';
        $End = ((strlen($Data) - $Needle) * -1) - strlen($CIDRAM['FE']['BlockSeparator']);
    }

    /** Guard against the needle being outside the range of the data length. */
    if (($End > 0 && $End > $DataLen) || ($End < 0 && ($End * -1) > $DataLen)) {
        $Needle = false;
        return false;
    }

    /** Step with search query. */
    if (strlen($SearchQuery)) {
        return (
            ($Needle = $StrFunction($Data, (
                $CIDRAM['FE']['BlockSeparator'] === "\n\n" ? $CIDRAM['FE']['FieldSeparator'] . $SearchQuery . "\n" : $SearchQuery
            ), $End)) !== false ||
            ($Needle = $StrFunction($Data, '("' . $SearchQuery . '", L', $End)) !== false ||
            (strlen($SearchQuery) === 2 && ($Needle = $StrFunction($Data, '[' . $SearchQuery . ']', $End)) !== false)
        );
    }

    /** Step without search query. */
    return (($Needle = $StrFunction($Data, $CIDRAM['FE']['BlockSeparator'], $End)) !== false);
};

/**
 * Builds a "from" link for pagination navigation.
 *
 * @param string $Label The label for the link.
 * @param string $Needle The needle to be used for the link.
 * @return void
 */
$CIDRAM['PaginationFromLink'] = function (string $Label, string $Needle) use (&$CIDRAM): void {
    $From = urlencode($CIDRAM['FE']['From']);
    $URLNeedle = urlencode($Needle);
    if (strpos($CIDRAM['FE']['BlockLink'], '&from=' . $From) !== false) {
        $Link = str_replace('&from=' . $From, '&from=' . $URLNeedle, $CIDRAM['FE']['BlockLink']);
    } else {
        $Link = $CIDRAM['FE']['BlockLink'] . '&from=' . $URLNeedle;
    }
    if (!empty($CIDRAM['QueryVars']['search'])) {
        $Link .= '&search=' . $CIDRAM['QueryVars']['search'];
    }
    $CIDRAM['FE']['SearchInfo'] .= sprintf(' %s <a href="%s">%s</a>', $CIDRAM['L10N']->getString($Label), $Link, $Needle);
};

/**
 * Removes the "from" parameter for log filtering.
 *
 * @param string $Link The link to clean.
 * @return string The cleaned link.
 */
$CIDRAM['PaginationRemoveFrom'] = function (string $Link): string {
    return preg_replace(['~\?from=[^&]+~i', '~&from=[^&]+~i'], ['?', ''], $Link);
};

/**
 * Swaps an element in an array to the top or the bottom.
 *
 * @param array $Arr The array to be worked.
 * @param string $Target The key of the element to be swapped.
 * @param bool $Direction False for top, true for bottom.
 * @return array The worked array.
 */
$CIDRAM['SwapAssocArrayElementTopBottom'] = function (array $Arr, string $Target, bool $Direction): array {
    if (!isset($Arr[$Target])) {
        return $Arr;
    }
    $Split = [$Target => $Arr[$Target]];
    unset($Arr[$Target]);
    $Arr = $Direction ? array_merge($Arr, $Split) : array_merge($Split, $Arr);
    return $Arr;
};

/**
 * Swaps the position of an element in an associative array up or down by one.
 *
 * @param array $Arr The associative array to be worked.
 * @param string $Target The key of the element to be swapped.
 * @param bool $Direction False for up, true for down.
 * @return array The worked array.
 */
$CIDRAM['SwapAssocArrayElementUpDown'] = function (array $Arr, string $Target, bool $Direction): array {
    if (!isset($Arr[$Target])) {
        return $Arr;
    }
    $Keys = [];
    $Values = [];
    $Index = 0;
    foreach ($Arr as $Key => $Value) {
        $Keys[$Index] = $Key;
        $Values[$Index] = $Value;
        if ($Key === $Target) {
            $TargetIndex = $Index;
        }
        $Index++;
    }
    if (!isset($TargetIndex, $Keys[$TargetIndex], $Values[$TargetIndex])) {
        return $Arr;
    }
    if ($Direction) {
        if (!isset($Keys[$TargetIndex + 1], $Values[$TargetIndex + 1])) {
            return $Arr;
        }
        [$Keys[$TargetIndex], $Keys[$TargetIndex + 1]] = [$Keys[$TargetIndex + 1], $Keys[$TargetIndex]];
        [$Values[$TargetIndex], $Values[$TargetIndex + 1]] = [$Values[$TargetIndex + 1], $Values[$TargetIndex]];
    } else {
        if (!isset($Keys[$TargetIndex - 1], $Values[$TargetIndex - 1])) {
            return $Arr;
        }
        [$Keys[$TargetIndex], $Keys[$TargetIndex - 1]] = [$Keys[$TargetIndex - 1], $Keys[$TargetIndex]];
        [$Values[$TargetIndex], $Values[$TargetIndex - 1]] = [$Values[$TargetIndex - 1], $Values[$TargetIndex]];
    }
    return array_combine($Keys, $Values);
};

/**
 * Reconstructs and updates the auxiliary rules data.
 *
 * @return bool True when succeeded.
 */
$CIDRAM['ReconstructUpdateAuxData'] = function () use (&$CIDRAM): bool {
    if (($NewAuxArr = $CIDRAM['YAML']->reconstruct($CIDRAM['AuxData'])) && strlen($NewAuxArr) > 2) {
        $Handle = fopen($CIDRAM['Vault'] . 'auxiliary.yaml', 'wb');
        if ($Handle !== false) {
            fwrite($Handle, $NewAuxArr);
            fclose($Handle);
            return true;
        }
    }
    return false;
};

/**
 * Generates a message describing the relative difference between the specified
 * time and the current time.
 *
 * @param int $Time The specified time (unix time).
 * @return string The message.
 */
$CIDRAM['RelativeTime'] = function (int $Time) use (&$CIDRAM): string {
    $Time -= $CIDRAM['Now'];
    if ($Time < -31536000) {
        $Time = (int)($Time / -31536000);
        return sprintf(
            $CIDRAM['L10N']->getPlural($Time, 'time_years_ago'),
            $CIDRAM['NumberFormatter']->format($Time)
        );
    }
    if ($Time < -2629800) {
        $Time = (int)($Time / -2629800);
        return sprintf(
            $CIDRAM['L10N']->getPlural($Time, 'time_months_ago'),
            $CIDRAM['NumberFormatter']->format($Time)
        );
    }
    if ($Time < -86400) {
        $Time = (int)($Time / -86400);
        return sprintf(
            $CIDRAM['L10N']->getPlural($Time, 'time_days_ago'),
            $CIDRAM['NumberFormatter']->format($Time)
        );
    }
    if ($Time < -3600) {
        $Time = (int)($Time / -3600);
        return sprintf(
            $CIDRAM['L10N']->getPlural($Time, 'time_hours_ago'),
            $CIDRAM['NumberFormatter']->format($Time)
        );
    }
    if ($Time < -60) {
        $Time = (int)($Time / -60);
        return sprintf(
            $CIDRAM['L10N']->getPlural($Time, 'time_minutes_ago'),
            $CIDRAM['NumberFormatter']->format($Time)
        );
    }
    if ($Time < 0) {
        $Time = (int)($Time * -1);
        return sprintf(
            $CIDRAM['L10N']->getPlural($Time, 'time_seconds_ago'),
            $CIDRAM['NumberFormatter']->format($Time)
        );
    }
    if ($Time > 31536000) {
        $Time = (int)($Time / 31536000);
        return sprintf(
            $CIDRAM['L10N']->getPlural($Time, 'time_years_from_now'),
            $CIDRAM['NumberFormatter']->format($Time)
        );
    }
    if ($Time > 2629800) {
        $Time = (int)($Time / 2629800);
        return sprintf(
            $CIDRAM['L10N']->getPlural($Time, 'time_months_from_now'),
            $CIDRAM['NumberFormatter']->format($Time)
        );
    }
    if ($Time > 86400) {
        $Time = (int)($Time / 86400);
        return sprintf(
            $CIDRAM['L10N']->getPlural($Time, 'time_days_from_now'),
            $CIDRAM['NumberFormatter']->format($Time)
        );
    }
    if ($Time > 3600) {
        $Time = (int)($Time / 3600);
        return sprintf(
            $CIDRAM['L10N']->getPlural($Time, 'time_hours_from_now'),
            $CIDRAM['NumberFormatter']->format($Time)
        );
    }
    if ($Time > 60) {
        $Time = (int)($Time / 60);
        return sprintf(
            $CIDRAM['L10N']->getPlural($Time, 'time_minutes_from_now'),
            $CIDRAM['NumberFormatter']->format($Time)
        );
    }
    $Time = (int)$Time;
    return sprintf(
        $CIDRAM['L10N']->getPlural($Time, 'time_seconds_from_now'),
        $CIDRAM['NumberFormatter']->format($Time)
    );
};

/**
 * Replaces labels with corresponding L10N data (if there's any).
 *
 * @param string $Label The actual label.
 * @return void
 */
$CIDRAM['ReplaceLabelWithL10N'] = function (string &$Label) use (&$CIDRAM): void {
    foreach (['', 'response_', 'label_', 'field_'] as $Prefix) {
        if (isset($CIDRAM['L10N']->Data[$Prefix . $Label])) {
            $Label = preg_replace($CIDRAM['RegExLabels'], '', $CIDRAM['L10N']->getString($Prefix . $Label));
            return;
        }
    }
};

/**
 * Parses an array of L10N data references from L10N data to an array.
 *
 * @param string|array $References The L10N data references.
 * @return array An array of L10N data.
 */
$CIDRAM['ArrayFromL10NDataToArray'] = function ($References) use (&$CIDRAM): array {
    if (!is_array($References)) {
        $References = [$References];
    }
    $Out = [];
    foreach ($References as $Reference) {
        $Try = '';
        if (isset($CIDRAM['L10N']->Data[$Reference])) {
            $Try = $CIDRAM['L10N']->Data[$Reference];
        }
        if ($Try === '') {
            if (($SPos = strpos($Reference, ' ')) !== '') {
                $Try = (($TryFrom = $CIDRAM['L10N']->getString(substr($Reference, 0, $SPos))) !== '' && strpos($TryFrom, '%s') !== '') ? sprintf($TryFrom, substr($Reference, $SPos + 1)) : $Reference;
            } else {
                $Try = $Reference;
            }
        }
        $Reference = $Try;
        if (!is_array($Reference)) {
            $Reference = [$Reference];
        }
        foreach ($Reference as $Key => $Value) {
            if (is_int($Key)) {
                $Out[] = $Value;
                continue;
            }
            $Out[$Key] = $Value;
        }
    }
    return $Out;
};

/**
 * Process all current request and bandwidth usage for this period.
 *
 * @param string $Data The data to be processed.
 * @return array The processed data.
 */
$CIDRAM['ProcessRLUsage'] = function (string $Data): array {
    $Pos = 0;
    $EoS = strlen($Data);
    $Out = [];
    while ($Pos < $EoS) {
        $Time = substr($Data, $Pos, 4);
        if (strlen($Time) !== 4) {
            break;
        }
        $Time = unpack('l*', $Time);
        $Pos += 4;
        $Bandwidth = substr($Data, $Pos, 4);
        if (strlen($Bandwidth) !== 4) {
            break;
        }
        $Bandwidth = unpack('l*', $Bandwidth);
        $Pos += 4;
        $BlockSize = substr($Data, $Pos, 4);
        if (strlen($BlockSize) !== 4) {
            break;
        }
        $BlockSize = unpack('l*', $BlockSize);
        $Pos += 4;
        $Block = substr($Data, $Pos, $BlockSize[1]);
        $Pos += $BlockSize[1];
        if (isset($Out[$Block])) {
            $Out[$Block]['Bandwidth'] += $Bandwidth[1];
            $Out[$Block]['Requests']++;
            $Out[$Block]['Newest'] = $Time[1];
        } else {
            $Out[$Block] = ['Bandwidth' => $Bandwidth[1], 'Requests' => 1, 'Oldest' => $Time[1], 'Newest' => $Time[1]];
        }
    }
    return $Out;
};

/**
 * Process minified form data.
 *
 * @param string $MinifiedKey The key for the minified form data.
 * @return void
 */
$CIDRAM['ProcessMinifiedFormData'] = function (string $MinifiedKey) use (&$CIDRAM): void {
    if (!isset($_POST[$MinifiedKey]) || substr($_POST[$MinifiedKey], 0, 1) !== '{' || substr($_POST[$MinifiedKey], -1) !== '}') {
        return;
    }
    $CIDRAM['InitialiseErrorHandler']();
    $MinifiedFormData = json_decode($_POST[$MinifiedKey], true);
    $CIDRAM['RestoreErrorHandler']();
    if (!is_array($MinifiedFormData)) {
        return;
    }
    $ToMerge = [];
    $ToBase = [];
    foreach ($MinifiedFormData as $Key => $Value) {
        if (preg_match('~^(.+)\[(\d+)\]\[(?:New)?\d*\]$|^"(.+)\[(\d+)\]\[(?:New)?\d*\]"$~', $Key, $Index)) {
            if (!isset($ToMerge[$Index[1]])) {
                $ToMerge[$Index[1]] = [];
            }
            if (!isset($ToMerge[$Index[1]][$Index[2]])) {
                $ToMerge[$Index[1]][$Index[2]] = [];
            }
            $ToMerge[$Index[1]][$Index[2]][] = $Value;
            continue;
        }
        if (preg_match('~^(.+)\[(?:New)?\d*\]$|^"(.+)\[(?:New)?\d*\]"$~', $Key, $Index)) {
            if (!isset($ToMerge[$Index[1]])) {
                $ToMerge[$Index[1]] = [];
            }
            $ToMerge[$Index[1]][] = $Value;
            continue;
        }
        $ToBase[$Key] = $Value;
    }
    $MinifiedFormData = array_merge($ToBase, $ToMerge);
    $_POST = array_replace($_POST, $MinifiedFormData);
    unset($_POST[$MinifiedKey]);
};

/**
 * Perform callback against an array where a callback matches.
 *
 * @param array $Arr The array to work upon.
 * @param callable $Perform The callable to perform.
 * @param int $Depth The current depth.
 * @return void
 */
$CIDRAM['CallableRecursive'] = function (array &$Arr, callable $Perform, int $Depth = 0) use (&$CIDRAM) {
    foreach ($Arr as $Key => &$Value) {
        if (!$Perform($Value, $Depth)) {
            break;
        }
        if (is_array($Value)) {
            $CIDRAM['CallableRecursive']($Value, $Perform, $Depth + 1);
        }
    }
};

/**
 * Fetch an etaggable asset as requested by the client.
 *
 * @param string $Asset The path to the asset.
 * @param ?callable $Callback An optional callback.
 * @return never
 */
$CIDRAM['eTaggable'] = function (string $Asset, ?callable $Callback = null) use (&$CIDRAM): void {
    header_remove('Cache-Control');
    if ($CIDRAM['FileManager-PathSecurityCheck']($Asset) && !preg_match('~[^\da-z._]~i', $Asset)) {
        $ThisAsset = $CIDRAM['GetAssetPath']($Asset, true);
        if (strlen($ThisAsset) && is_readable($ThisAsset) && ($ThisAssetDel = strrpos($ThisAsset, '.')) !== false) {
            $Success = false;
            $Type = strtolower(substr($ThisAsset, $ThisAssetDel + 1));
            if ($Type === 'jpeg') {
                $Type = 'jpg';
            }
            if (preg_match('/^(?:gif|jpg|png|webp)$/', $Type)) {
                $MimeType = 'Content-Type: image/' . $Type;
                $Success = true;
            } elseif ($Type === 'svg') {
                $MimeType = 'Content-Type: image/svg+xml';
                $Success = true;
            } elseif ($Type === 'ico') {
                $MimeType = 'Content-Type: image/x-icon';
                $Success = true;
            } elseif ($Type === 'js') {
                $MimeType = 'Content-Type: text/javascript';
                $Success = true;
            } elseif ($Type === 'css') {
                $MimeType = 'Content-Type: text/css';
                $Success = true;
            }
            if ($Success) {
                $AssetData = $CIDRAM['ReadFile']($ThisAsset);
                $OldETag = $_SERVER['HTTP_IF_NONE_MATCH'] ?? '';
                $NewETag = hash('sha256', $AssetData) . '-' . strlen($AssetData);
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s T', filemtime($ThisAsset)));
                header('ETag: "' . $NewETag . '"');
                header('Expires: ' . gmdate('D, d M Y H:i:s T', $CIDRAM['Now'] + 15552000));
                if (preg_match('~(?:^|, )(?:"' . $NewETag . '"|' . $NewETag . ')(?:$|, )~', $OldETag)) {
                    header('HTTP/1.0 304 Not Modified');
                    header('HTTP/1.1 304 Not Modified');
                    header('Status: 304 Not Modified');
                    die;
                }
                header($MimeType);
                if (is_callable($Callback)) {
                    $AssetData = $Callback($AssetData);
                }
                echo $AssetData;
                die;
            }
        }
        header('HTTP/1.0 404 Not Found');
        header('HTTP/1.1 404 Not Found');
        header('Status: 404 Not Found');
        die;
    }
    header('HTTP/1.0 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    header('Status: 403 Forbidden');
    die;
};

/**
 * Replace array keys according to a supplied closure/callable.
 *
 * @param array $Arr The array from which to replace keys.
 * @param callable $Perform The closure/callable to use to determine the replacement key.
 * @return array The array with replaced keys.
 */
$CIDRAM['arrayReplaceKeys'] = function (array $Arr, callable $Perform): array {
    $Out = [];
    foreach ($Arr as $Item) {
        $NewKey = $Perform($Item);
        if (is_string($NewKey) || is_int($NewKey)) {
            $Out[$NewKey] = $Item;
        }
    }
    return $Out;
};
