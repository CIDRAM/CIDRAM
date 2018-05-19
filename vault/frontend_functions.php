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
 * This file: Front-end functions file (last modified: 2018.05.19).
 */

/**
 * Validates or ensures that two different sets of component metadata share the
 * same base elements (or components). One set acts as a model for which base
 * elements are expected, and if additional/superfluous entries are found in
 * the other set (the base), they'll be removed. Installed components are
 * ignored as to future-proof legacy support (just removes non-installed
 * components).
 *
 * @param string $Base The base set (generally, the local copy).
 * @param string $Model The model set (generally, the remote copy).
 * @param bool $Validate Validate (true) or ensure congruency (false; default).
 * @return string|bool If $Validate is true, returns true|false according to
 *      whether the sets are congruent. If $Validate is false, returns the
 *      corrected $Base set.
 */
$CIDRAM['Congruency'] = function ($Base, $Model, $Validate = false) use (&$CIDRAM) {
    if (empty($Base) || empty($Model)) {
        return $Validate ? false : '';
    }
    $BaseArr = $ModelArr = [];
    $CIDRAM['YAML']($Base, $BaseArr);
    $CIDRAM['YAML']($Model, $ModelArr);
    foreach ($BaseArr as $Element => $Data) {
        if (!isset($Data['Version']) && !isset($Data['Files']) && !isset($ModelArr[$Element])) {
            if ($Validate) {
                return false;
            }
            $Base = preg_replace("~\n" . preg_quote($Element) . ":?(\n [^\n]*)*\n~i", "\n", $Base);
        }
    }
    return $Validate ? true : $Base;
};

/**
 * Can be used to delete some files via the front-end.
 *
 * @param string $File The file to delete.
 * @return bool Success or failure.
 */
$CIDRAM['Delete'] = function ($File) use (&$CIDRAM) {
    if ((substr($File, 0, 1) === '"' && substr($File, -1) === '"') || (substr($File, 0, 1) === "'" && substr($File, -1) === "'")) {
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
    if (!$Delimiter = substr($Query, 0, 1)) {
        return false;
    }
    $QueryParts = explode($Delimiter, $Query);
    $CountParts = count($QueryParts);
    if ($CountParts % 2) {
        $Arr = [];
        for ($Iter = 0; $Iter < $CountParts; $Iter++) {
            if ($Iter % 2) {
                $Arr[] = $QueryParts[$Iter];
            } else {
                $QueryParts[$Iter] = preg_split('~ +~', $QueryParts[$Iter], -1, PREG_SPLIT_NO_EMPTY);
                foreach ($QueryParts[$Iter] as $ThisPart) {
                    $Arr[] = $ThisPart;
                }
            }
        }
        $QueryParts = $Arr;
        unset($ThisPart, $Iter, $Arr);
    } else {
        $QueryParts = preg_split('~ +~', $Query, -1, PREG_SPLIT_NO_EMPTY);
    }
    if (empty($QueryParts[0]) || empty($QueryParts[1]) || !file_exists($CIDRAM['Vault'] . $QueryParts[0]) || !is_readable($CIDRAM['Vault'] . $QueryParts[0])) {
        return false;
    }
    $QueryParts[1] = strtolower($QueryParts[1]);

    /** Replace file content. */
    if ($QueryParts[1] === 'replace' && !empty($QueryParts[3]) && strtolower($QueryParts[3]) === 'with') {
        $FileData = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $QueryParts[0]);
        $NewFileData = preg_replace($QueryParts[2], (isset($QueryParts[4]) ? $QueryParts[4] : ''), $FileData);
        if ($NewFileData !== $FileData && is_writable($CIDRAM['Vault'] . $QueryParts[0])) {
            $Handle = fopen($CIDRAM['Vault'] . $QueryParts[0], 'w');
            fwrite($Handle, $NewFileData);
            fclose($Handle);
            return true;
        }
    }

    return false;
};

/**
 * Can be used to delete some files via the front-end.
 *
 * @param string $File The file to delete.
 * @return bool Success or failure.
 */
$CIDRAM['Delete'] = function ($File) use (&$CIDRAM) {
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

/** Format filesize information. */
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
    $Filesize = $CIDRAM['Number_L10N']($Filesize, ($Iterate === 0) ? 0 : 2) . ' ' . $CIDRAM['Plural']($Filesize, $CIDRAM['lang'][$Scale[$Iterate]]);
};

/**
 * Remove an entry from the front-end cache data.
 *
 * @param string $Source Variable containing cache file data.
 * @param bool $Rebuild Flag indicating to rebuild cache file.
 * @param string $Entry Name of the cache entry to be deleted.
 */
$CIDRAM['FECacheRemove'] = function (&$Source, &$Rebuild, $Entry) {
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
 * @param bool $Rebuild Flag indicating to rebuild cache file.
 * @param string $Entry Name of the cache entry to get.
 * return string|bool Returned cache entry data (or false on failure).
 */
$CIDRAM['FECacheGet'] = function ($Source, $Entry) {
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
 * return bool True if the 2nd version is newer than the 1st version, and false
 *      otherwise (i.e., if they're the same, or if the 1st version is newer).
 */
$CIDRAM['VersionCompare'] = function ($A, $B) {
    $Normalise = function (&$Ver) {
        $Ver =
            preg_match('~^v?([0-9]+)$~i', $Ver, $Matches) ?:
            preg_match('~^v?([0-9]+)\.([0-9]+)$~i', $Ver, $Matches) ?:
            preg_match('~^v?([0-9]+)\.([0-9]+)\.([0-9]+)(RC[0-9]{1,2}|-[.0-9a-z_+\\/]+)?$~i', $Ver, $Matches) ?:
            preg_match('~^([0-9]{1,4})[.-]([0-9]{1,2})[.-]([0-9]{1,4})(RC[0-9]{1,2}|[.+-][0-9a-z_+\\/]+)?$~i', $Ver, $Matches) ?:
            preg_match('~^([a-z]+)-([0-9a-z]+)-([0-9a-z]+)$~i', $Ver, $Matches);
        $Ver = [
            'Major' => isset($Matches[1]) ? $Matches[1] : 0,
            'Minor' => isset($Matches[2]) ? $Matches[2] : 0,
            'Patch' => isset($Matches[3]) ? $Matches[3] : 0,
            'Build' => isset($Matches[4]) ? substr($Matches[4], 1) : 0
        ];
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
 * return array An array.
 */
$CIDRAM['ArrayFlatten'] = function ($Arr) {
    return array_filter($Arr, function () {
        return (!is_array(func_get_args()[0]));
    });
};

/** Isolate a L10N array down to a single relevant L10N string. */
$CIDRAM['IsolateL10N'] = function (&$Arr, $Lang) {
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
            $Arr[$Key]['Filetype'] = $CIDRAM['lang']['field_filetype_directory'];
            $Arr[$Key]['Icon'] = 'icon=directory';
        } elseif (is_file($Item)) {
            $Arr[$Key]['CanEdit'] = true;
            $Arr[$Key]['Directory'] = false;
            $Arr[$Key]['Filesize'] = filesize($Item);
            if (isset($CIDRAM['FE']['TotalSize'])) {
                $CIDRAM['FE']['TotalSize'] += $Arr[$Key]['Filesize'];
            }
            if (isset($CIDRAM['Components']['Components'])) {
                $Component = $CIDRAM['lang']['field_filetype_unknown'];
                $ThisNameFixed = str_replace("\\", '/', $ThisName);
                if (isset($CIDRAM['Components']['Files'][$ThisNameFixed])) {
                    if (!empty($CIDRAM['Components']['Names'][$CIDRAM['Components']['Files'][$ThisNameFixed]])) {
                        $Component = $CIDRAM['Components']['Names'][$CIDRAM['Components']['Files'][$ThisNameFixed]];
                    } else {
                        $Component = $CIDRAM['Components']['Files'][$ThisNameFixed];
                    }
                    if ($Component === 'CIDRAM') {
                        $Component .= ' (' . $CIDRAM['lang']['field_component'] . ')';
                    }
                } elseif (substr($ThisNameFixed, -10) === 'config.ini') {
                    $Component = $CIDRAM['lang']['link_config'];
                } elseif ($CIDRAM['FileManager-IsLogFile']($ThisNameFixed)) {
                    $Component = $CIDRAM['lang']['link_logs'];
                } else {
                    $LastFour = strtolower(substr($ThisNameFixed, -4));
                    if (
                        $LastFour === '.tmp' ||
                        $ThisNameFixed === 'cache.dat' ||
                        $ThisNameFixed === 'fe_assets/frontend.dat' ||
                        substr($ThisNameFixed, -9) === '.rollback'
                    ) {
                        $Component = $CIDRAM['lang']['label_fmgr_cache_data'];
                    } elseif (preg_match('/^\.(?:dat|inc|ya?ml)$/i', $LastFour)) {
                        $Component = $CIDRAM['lang']['label_fmgr_updates_metadata'];
                    }
                }
                if (!isset($CIDRAM['Components']['Components'][$Component])) {
                    $CIDRAM['Components']['Components'][$Component] = 0;
                }
                $CIDRAM['Components']['Components'][$Component] += $Arr[$Key]['Filesize'];
            }
            if (($ExtDel = strrpos($Item, '.')) !== false) {
                $Ext = strtoupper(substr($Item, $ExtDel + 1));
                if (!$Ext) {
                    $Arr[$Key]['Filetype'] = $CIDRAM['lang']['field_filetype_unknown'];
                    $Arr[$Key]['Icon'] = 'icon=unknown';
                    $CIDRAM['FormatFilesize']($Arr[$Key]['Filesize']);
                    continue;
                }
                $Arr[$Key]['Filetype'] = $CIDRAM['ParseVars'](['EXT' => $Ext], $CIDRAM['lang']['field_filetype_info']);
                if ($Ext === 'ICO') {
                    $Arr[$Key]['Icon'] = 'file=' . urlencode($Prepend . $Item);
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
                $Arr[$Key]['Filetype'] = $CIDRAM['lang']['field_filetype_unknown'];
            }
        }
        if (empty($Arr[$Key]['Icon'])) {
            $Arr[$Key]['Icon'] = 'icon=unknown';
        }
        if ($Arr[$Key]['Filesize']) {
            $CIDRAM['FormatFilesize']($Arr[$Key]['Filesize']);
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
$CIDRAM['FetchComponentsLists'] = function ($Base, &$Arr) use (&$CIDRAM) {
    $Files = new DirectoryIterator($Base);
    foreach ($Files as $ThisFile) {
        if (!empty($ThisFile) && preg_match('/\.(?:dat|inc|ya?ml)$/i', $ThisFile)) {
            $Data = $CIDRAM['ReadFile']($Base . $ThisFile);
            if (substr($Data, 0, 4) === "---\n" && ($EoYAML = strpos($Data, "\n\n")) !== false) {
                $CIDRAM['YAML'](substr($Data, 4, $EoYAML - 4), $Arr);
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
        preg_match('~(?://|[^!0-9A-Za-z\._-]$)~', $Path) ||
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
    return $Arr;
};

/** Checks whether a component is in use (front-end closure). */
$CIDRAM['IsInUse'] = function (&$Component) use (&$CIDRAM) {
    $Files = empty($Component['Files']['To']) ? [] : $Component['Files']['To'];
    $UsedWith = empty($Component['Used with']) ? '' : $Component['Used with'];
    $Description = empty($Component['Extended Description']) ? '' : $Component['Extended Description'];
    foreach ($Files as $File) {
        if ((($UsedWith === 'ipv4' || strpos($Description, 'signatures-&gt;ipv4') !== false) && strpos(
            ',' . $CIDRAM['Config']['signatures']['ipv4'] . ',',
            ',' . $File . ','
        ) !== false) || (($UsedWith === 'ipv6' || strpos($Description, 'signatures-&gt;ipv6') !== false) && strpos(
            ',' . $CIDRAM['Config']['signatures']['ipv6'] . ',',
            ',' . $File . ','
        ) !== false) || (($UsedWith === 'modules' || strpos($Description, 'signatures-&gt;modules') !== false) && strpos(
            ',' . $CIDRAM['Config']['signatures']['modules'] . ',',
            ',' . $File . ','
        ) !== false) || (
            !$UsedWith &&
            strpos($Description, 'signatures-&gt;ipv4') === false &&
            strpos($Description, 'signatures-&gt;ipv6') === false &&
            strpos($Description, 'signatures-&gt;modules') === false && (
                strpos(',' . $CIDRAM['Config']['signatures']['ipv4'] . ',', ',' . $File . ',') !== false ||
                strpos(',' . $CIDRAM['Config']['signatures']['ipv6'] . ',', ',' . $File . ',') !== false ||
                strpos(',' . $CIDRAM['Config']['signatures']['modules'] . ',', ',' . $File . ',') !== false
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
    if (substr_count($First, '::')) {
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
    $CIDRAM['Components']['ThisComponent']['RemoteData'] = $CIDRAM['FECacheGet'](
        $CIDRAM['FE']['Cache'],
        $CIDRAM['Components']['ThisComponent']['Remote']
    );
    if (!$CIDRAM['Components']['ThisComponent']['RemoteData']) {
        $CIDRAM['Components']['ThisComponent']['RemoteData'] = $CIDRAM['Request']($CIDRAM['Components']['ThisComponent']['Remote']);
        if (
            strtolower(substr($CIDRAM['Components']['ThisComponent']['Remote'], -2)) === 'gz' &&
            substr($CIDRAM['Components']['ThisComponent']['RemoteData'], 0, 2) === "\x1f\x8b"
        ) {
            $CIDRAM['Components']['ThisComponent']['RemoteData'] = gzdecode($CIDRAM['Components']['ThisComponent']['RemoteData']);
        }
        if (empty($CIDRAM['Components']['ThisComponent']['RemoteData'])) {
            $CIDRAM['Components']['ThisComponent']['RemoteData'] = '-';
        }
        $CIDRAM['FECacheAdd'](
            $CIDRAM['FE']['Cache'],
            $CIDRAM['FE']['Rebuild'],
            $CIDRAM['Components']['ThisComponent']['Remote'],
            $CIDRAM['Components']['ThisComponent']['RemoteData'],
            $CIDRAM['Now'] + 3600
        );
    }
};

/** Check whether component is activable. */
$CIDRAM['IsActivable'] = function (&$Component) {
    return (!empty($Component['Used with']) || strpos($Component['Extended Description'], 'signatures-&gt;') !== false);
};

/** Activate component (front-end updates page). */
$CIDRAM['ActivateComponent'] = function ($Type) use (&$CIDRAM) {
    $CIDRAM['Activation'][$Type] = array_unique(array_filter(
        explode(',', $CIDRAM['Activation'][$Type]),
        function ($Component) use (&$CIDRAM) {
            return ($Component && file_exists($CIDRAM['Vault'] . $Component));
        }
    ));
    foreach ($CIDRAM['Components']['Meta'][$CIDRAM['Targets']]['Files']['To'] as $CIDRAM['Activation']['ThisFile']) {
        if (
            !empty($CIDRAM['Activation']['ThisFile']) &&
            file_exists($CIDRAM['Vault'] . $CIDRAM['Activation']['ThisFile']) &&
            $CIDRAM['Traverse']($CIDRAM['Activation']['ThisFile'])
        ) {
            $CIDRAM['Activation'][$Type][] = $CIDRAM['Activation']['ThisFile'];
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

/** Deactivate component (front-end updates page). */
$CIDRAM['DeactivateComponent'] = function ($Type) use (&$CIDRAM) {
    $CIDRAM['Deactivation'][$Type] = array_unique(array_filter(
        explode(',', $CIDRAM['Deactivation'][$Type]),
        function ($Component) use (&$CIDRAM) {
            return ($Component && file_exists($CIDRAM['Vault'] . $Component));
        }
    ));
    if (count($CIDRAM['Deactivation'][$Type])) {
        sort($CIDRAM['Deactivation'][$Type]);
    }
    $CIDRAM['Deactivation'][$Type] = ',' . implode(',', $CIDRAM['Deactivation'][$Type]) . ',';
    foreach ($CIDRAM['Components']['Meta'][$CIDRAM['Targets']]['Files']['To'] as $CIDRAM['Deactivation']['ThisFile']) {
        $CIDRAM['Deactivation'][$Type] =
            str_replace(',' . $CIDRAM['Deactivation']['ThisFile'] . ',', ',', $CIDRAM['Deactivation'][$Type]);
    }
    $CIDRAM['Deactivation'][$Type] = substr($CIDRAM['Deactivation'][$Type], 1, -1);
    if ($CIDRAM['Deactivation'][$Type] !== $CIDRAM['Config']['signatures'][$Type]) {
        $CIDRAM['Deactivation']['Modified'] = true;
    }
};

/** Prepares component extended description (front-end updates page). */
$CIDRAM['PrepareExtendedDescription'] = function (&$Arr, $Key = '') use (&$CIDRAM) {
    $Key = 'Extended Description: ' . $Key;
    if (isset($CIDRAM['lang'][$Key])) {
        $Arr['Extended Description'] = $CIDRAM['lang'][$Key];
    } elseif (empty($Arr['Extended Description'])) {
        $Arr['Extended Description'] = '';
    }
    if (is_array($Arr['Extended Description'])) {
        $CIDRAM['IsolateL10N']($Arr['Extended Description'], $CIDRAM['Config']['general']['lang']);
    }
    if (!empty($Arr['Used with']) && strpos($Arr['Extended Description'], 'signatures-&gt;') === false) {
        $Arr['Extended Description'] .=
            '<br /><em>' . $CIDRAM['lang']['label_used_with'] .
            '<code>signatures-&gt;' . $Arr['Used with'] . '</code></em>';
    }
    if (!empty($Arr['False Positive Risk'])) {
        if ($Arr['False Positive Risk'] === 'Low') {
            $State = $CIDRAM['lang']['state_risk_low'];
            $Class = 'txtGn';
        } elseif ($Arr['False Positive Risk'] === 'Medium') {
            $State = $CIDRAM['lang']['state_risk_medium'];
            $Class = 'txtOe';
        } elseif ($Arr['False Positive Risk'] === 'High') {
            $State = $CIDRAM['lang']['state_risk_high'];
            $Class = 'txtRd';
        } else {
            return;
        }
        $Arr['Extended Description'] .=
            '<br /><em>' . $CIDRAM['lang']['label_false_positive_risk'] .
            '<span class="' . $Class . '">' . $State . '</span></em>';
    }
};

/** Prepares component name (front-end updates page). */
$CIDRAM['PrepareName'] = function (&$Arr, $Key = '') use (&$CIDRAM) {
    $Key = 'Name: ' . $Key;
    if (isset($CIDRAM['lang'][$Key])) {
        $Arr['Name'] = $CIDRAM['lang'][$Key];
    } elseif (empty($Arr['Name'])) {
        $Arr['Name'] = '';
    }
    if (is_array($Arr['Name'])) {
        $CIDRAM['IsolateL10N']($Arr['Name'], $CIDRAM['Config']['general']['lang']);
    }
};

/** Duplication avoidance (front-end updates page). */
$CIDRAM['ComponentFunctionUpdatePrep'] = function () use (&$CIDRAM) {
    if (!empty($CIDRAM['Components']['Meta'][$CIDRAM['Targets']]['Files'])) {
        $CIDRAM['PrepareExtendedDescription']($CIDRAM['Components']['Meta'][$CIDRAM['Targets']]);
        $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$CIDRAM['Targets']]['Files']);
        $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$CIDRAM['Targets']]['Files']['To']);
        return $CIDRAM['IsInUse']($CIDRAM['Components']['Meta'][$CIDRAM['Targets']]);
    }
    return false;
};

/**
 * Simulates block event (used by the IP tracking and IP test pages).
 *
 * @param string $Addr The IP address to test against.
 * @param bool $Modules Specifies whether to test against modules.
 */
$CIDRAM['SimulateBlockEvent'] = function ($Addr, $Modules = false) use (&$CIDRAM) {

    /** Initialise cache. */
    $CIDRAM['InitialiseCache']();

    /** Reset bypass flags (needed to prevent falsing due to search engine verification). */
    $CIDRAM['ResetBypassFlags']();

    /** Reset hostname (needed to prevent falsing due to repeat module calls involving hostname lookups). */
    $CIDRAM['Hostname'] = '';

    /** Populate BlockInfo. */
    $CIDRAM['BlockInfo'] = [
        'Counter' => 0,
        'IPAddr' => $Addr,
        'Query' => 'SimulateBlockEvent',
        'Referrer' => '',
        'UA' => !empty($CIDRAM['FE']['custom-ua']) ? $CIDRAM['FE']['custom-ua'] : 'SimulateBlockEvent',
        'ReasonMessage' => '',
        'SignatureCount' => 0,
        'Signatures' => '',
        'WhyReason' => '',
        'xmlLang' => $CIDRAM['Config']['general']['lang'],
        'rURI' => 'SimulateBlockEvent'
    ];
    $CIDRAM['BlockInfo']['UALC'] = strtolower($CIDRAM['BlockInfo']['UA']);

    /** Standard IP checks. */
    try {
        $CIDRAM['Caught'] = false;
        $CIDRAM['TestResults'] = $CIDRAM['RunTests']($Addr);
    } catch (\Exception $e) {
        $CIDRAM['Caught'] = true;
    }

    /** 6to4 IP checks. */
    if (substr($CIDRAM['BlockInfo']['IPAddr'], 0, 5) === '2002:') {
        $CIDRAM['BlockInfo']['IPAddrResolved'] = $CIDRAM['Resolve6to4']($Addr);
        if (!empty($CIDRAM['ThisIP']['IPAddress'])) {
            $CIDRAM['ThisIP']['IPAddress'] .= ' (' . $CIDRAM['BlockInfo']['IPAddrResolved'] . ')';
        }
        try {
            $CIDRAM['TestResults'] = ($CIDRAM['RunTests']($CIDRAM['BlockInfo']['IPAddrResolved']) || $CIDRAM['TestResults']);
        } catch (\Exception $e) {
            $CIDRAM['Caught'] = true;
        }
    }

    /** Module checks. */
    if ($Modules && !empty($CIDRAM['Config']['signatures']['modules']) && empty($CIDRAM['Whitelisted'])) {

        /** Explode module list and cycle through all modules. */
        $Modules = explode(',', $CIDRAM['Config']['signatures']['modules']);
        array_walk($Modules, function ($Module) use (&$CIDRAM) {
            $Infractions = $CIDRAM['BlockInfo']['SignatureCount'];
            if (file_exists($CIDRAM['Vault'] . $Module) && is_readable($CIDRAM['Vault'] . $Module)) {
                require $CIDRAM['Vault'] . $Module;
            }
        });

    }

    /**
     * Block bots masquerading as popular search engines and disable tracking
     * for real, legitimate search engines.
     */
    $CIDRAM['SearchEngineVerification']();

    /** Update the cache. */
    if ($CIDRAM['CacheModified']) {
        $Handle = fopen($CIDRAM['Vault'] . 'cache.dat', 'w');
        fwrite($Handle, serialize($CIDRAM['Cache']));
        fclose($Handle);
    }

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
    return (file_exists($Path . '.php') && file_exists($Path . '.fe.php'));
};

/**
 * Filter the available hash algorithms provided by the configuration page on
 * the basis of their availability.
 *
 * @param string $ChoiceKey Hash algorithm.
 * @return bool Valid/Invalid.
 */
$CIDRAM['FilterAlgo'] = function ($ChoiceKey) use (&$CIDRAM) {
    return ($ChoiceKey === 'PASSWORD_ARGON2I') ? !$CIDRAM['VersionCompare'](PHP_VERSION, '7.2.0RC1') : true;
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

/** Attempt to perform some simple formatting for the log data. */
$CIDRAM['Formatter'] = function (&$In, $BlockLink = '', $Current = '', $FieldSeparator = ': ', $Flags) {
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
                    if (!$ThisPart || $ThisPart === $Current) {
                        continue;
                    }
                    $Enc = str_replace('=', '_', base64_encode($ThisPart));
                    $Section = str_replace(
                        $FieldSeparator . $ThisPart . "<br />\n",
                        $FieldSeparator . $ThisPart . ' <a href="' . $BlockLink . '&search=' . $Enc . '">»</a>' . "<br />\n",
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
                foreach ($Parts[1] as $ThisPart) {
                    $Section = str_replace(
                        '[' . $ThisPart . ']',
                        $Flags ? (
                            '<a href="' . $BlockLink . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '"><span class="flag ' . $ThisPart . '"><span></span></span></a>'
                        ) : (
                            '[<a href="' . $BlockLink . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '">' . $ThisPart . '</a>]'
                        ),
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
        $Style = '<div class="h' . ($Darken ? 'B' : 'W') . ' hFd">';
        $BlockEnd = strpos($Out, $BlockSeparator, $BlockStart);
        $In .= $Style . substr($Out, $BlockStart, $BlockEnd - $BlockStart + $BlockSeparatorLen) . '</div>';
        $BlockStart = $BlockEnd + $BlockSeparatorLen;
    }
    $In = str_replace("<br />\n</div>", "<br /></div>\n", $In);
};

/** Attempt to tally logfile data. */
$CIDRAM['Tally'] = function ($In, $BlockLink, $Exceptions = []) use (&$CIDRAM) {
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
        foreach ($Exceptions as $Exception) {
            $Len = strlen($Exception);
            if (substr($Line, 0, $Len) === $Exception) {
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
                        '<a href="' . $BlockLink . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '"><span class="flag ' . $ThisPart . '"><span></a>'
                    ) : (
                        '[<a href="' . $BlockLink . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '">' . $ThisPart . '</a>]'
                    ), $Entry);
                }
            }
            $Percent = $CIDRAM['FE']['EntryCount'] ? ($Count / $CIDRAM['FE']['EntryCount']) * 100 : 0;
            $Out .= '<tr><td class="h1">' . $Entry . '</td><td class="h1f"><div class="s">' . $CIDRAM['Number_L10N']($Count) . ' (' . $CIDRAM['Number_L10N']($Percent, 2) . "%)</div></td></tr>\n";
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
 * Determines whether to display warnings about the PHP version used (based
 * upon what we know at the time that the package was last updated; information
 * herein is likely to become stale very quickly when not updated frequently).
 *
 * References:
 * - secure.php.net/releases/
 * - secure.php.net/supported-versions.php
 * - cvedetails.com/vendor/74/PHP.html
 * - maikuolan.github.io/Compatibility-Charts/
 * - maikuolan.github.io/Vulnerability-Charts/php.html
 *
 * @param string $Version The PHP version used (defaults to PHP_VERSION).
 * return int Warning level.
 */
$CIDRAM['VersionWarning'] = function ($Version = PHP_VERSION) use (&$CIDRAM) {
    $Date = date('Y.n.j', $CIDRAM['Now']);
    $Level = 0;
    $Minor = substr($Version, 0, 4);
    if (!empty($CIDRAM['ForceVersionWarning']) || $CIDRAM['VersionCompare']($Version, '5.6.36') || substr($Version, 0, 2) === '6.' || (
        $Minor === '7.0.' && $CIDRAM['VersionCompare']($Version, '7.0.30')
    ) || (
        $Minor === '7.1.' && $CIDRAM['VersionCompare']($Version, '7.1.17')
    ) || (
        $Minor === '7.2.' && $CIDRAM['VersionCompare']($Version, '7.2.5')
    )) {
        $Level += 2;
    }
    if ($CIDRAM['VersionCompare']($Version, '7.1.0') || (
        !$CIDRAM['VersionCompare']($Date, '2018.12.1') && $CIDRAM['VersionCompare']($Version, '7.2.0')
    )) {
        $Level += 1;
    }
    $CIDRAM['ForceVersionWarning'] = false;
    return $Level;
};

/**
 * Executes a list of closures or commands when specific conditions are met.
 *
 * @param array|string $Closures The list of closures or commands to execute.
 */
$CIDRAM['FE_Executor'] = function ($Closures) use (&$CIDRAM) {
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
        file_exists($CIDRAM['Vault'] . '../cidram.php') &&
        is_readable($CIDRAM['Vault'] . '../cidram.php') &&
        !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Version']) &&
        ($ThisData = $CIDRAM['ReadFile']($CIDRAM['Vault'] . '../cidram.php'))
    ) {
        $PlugHead = "\x3C\x3Fphp\n/**\n * Plugin Name: CIDRAM\n * Version: ";
        if (substr($ThisData, 0, 45) === $PlugHead) {
            $PlugHeadEnd = strpos($ThisData, "\n", 45);
            $ThisData =
                $PlugHead .
                $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Version'] .
                substr($ThisData, $PlugHeadEnd);
            $Handle = fopen($CIDRAM['Vault'] . '../cidram.php', 'w');
            fwrite($Handle, $ThisData);
            fclose($Handle);
        }
    }
};

/**
 * Localises a number according to configuration specification.
 *
 * @param int $Number The number to localise.
 * @param int $Decimals Decimal places (optional).
 */
$CIDRAM['Number_L10N'] = function ($Number, $Decimals = 0) use (&$CIDRAM) {
    $Number = (real)$Number;
    $Sets = [
        'NoSep-1' => ['.', '', 3, false, 0],
        'NoSep-2' => [',', '', 3, false, 0],
        'Latin-1' => ['.', ',', 3, false, 0],
        'Latin-2' => ['.', ' ', 3, false, 0],
        'Latin-3' => [',', '.', 3, false, 0],
        'Latin-4' => [',', ' ', 3, false, 0],
        'Latin-5' => ['·', ',', 3, false, 0],
        'China-1' => ['.', ',', 4, false, 0],
        'India-1' => ['.', ',', 2, false, -1],
        'India-2' => ['.', ',', 2, ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'], -1],
        'Bengali-1' => ['.', ',', 2, ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'], -1],
        'Arabic-1' => ['٫', '', 3, ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'], 0],
        'Arabic-2' => ['٫', '٬', 3, ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'], 0],
        'Thai-1' => ['.', ',', 3, ['๐', '๑', '๒', '๓', '๔', '๕', '๖', '๗', '๘', '๙'], 0]
    ];
    $Set = empty($Sets[$CIDRAM['Config']['general']['numbers']]) ? 'Latin-1' : $Sets[$CIDRAM['Config']['general']['numbers']];
    $DecPos = strpos($Number, '.') ?: strlen($Number);
    if ($Decimals && $Set[0]) {
        $Fraction = substr($Number, $DecPos + 1, $Decimals);
        $Fraction .= str_repeat('0', $Decimals - strlen($Fraction));
    }
    for ($Formatted = '', $ThouPos = $Set[4], $Pos = 1; $Pos <= $DecPos; $Pos++) {
        if ($ThouPos >= $Set[2]) {
            $ThouPos = 1;
            $Formatted = $Set[1] . $Formatted;
        } else {
            $ThouPos++;
        }
        $NegPos = $DecPos - $Pos;
        $ThisChar = substr($Number, $NegPos, 1);
        $Formatted = empty($Set[3][$ThisChar]) ? $ThisChar . $Formatted : $Set[3][$ThisChar] . $Formatted;
    }
    if ($Decimals && $Set[0]) {
        $Formatted .= $Set[0];
        for ($FracLen = strlen($Fraction), $Pos = 0; $Pos < $FracLen; $Pos++) {
            $Formatted .= empty($Set[3][$Fraction[$Pos]]) ? $Fraction[$Pos] : $Set[3][$Fraction[$Pos]];
        }
    }
    return $Formatted;
};

/**
 * Generates JavaScript code for localising numbers according to configuration
 * specification.
 */
$CIDRAM['Number_L10N_JS'] = function () use (&$CIDRAM) {
    $Base =
        'function l10nn(l10nd){%4$s};function nft(r){var x=r.indexOf(\'.\')!=-1?' .
        '\'%1$s\'+r.replace(/^.*\./gi,\'\'):\'\',n=r.replace(/\..*$/gi,\'\').rep' .
        'lace(/[^0-9]/gi,\'\'),t=n.length;for(e=\'\',b=%5$d,i=1;i<=t;i++){b>%3$d' .
        '&&(b=1,e=\'%2$s\'+e);var e=l10nn(n.substring(t-i,t-(i-1)))+e;b++}var t=' .
        'x.length;for(y=\'\',b=1,i=1;i<=t;i++){var y=l10nn(x.substring(t-i,t-(i-' .
        '1)))+y}return e+y}';
    $Sets = [
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
        'Bengali-1' => ['.', ',', 2, 'var nls=[\'০\',\'১\',\'২\',\'৩\',\'৪\',\'৫\',\'৬\',\'৭\',\'৮\',\'৯\'];return nls[l10nd]||l10nd', 0],
        'Arabic-1' => ['٫', '', 3, 'var nls=[\'٠\',\'١\',\'٢\',\'٣\',\'٤\',\'٥\',\'٦\',\'٧\',\'٨\',\'٩\'];return nls[l10nd]||l10nd', 1],
        'Arabic-2' => ['٫', '٬', 3, 'var nls=[\'٠\',\'١\',\'٢\',\'٣\',\'٤\',\'٥\',\'٦\',\'٧\',\'٨\',\'٩\'];return nls[l10nd]||l10nd', 1],
        'Thai-1' => ['.', ',', 3, 'var nls=[\'๐\',\'๑\',\'๒\',\'๓\',\'๔\',\'๕\',\'๖\',\'๗\',\'๘\',\'๙\'];return nls[l10nd]||l10nd', 1],
    ];
    if (!empty($CIDRAM['Config']['general']['numbers']) && isset($Sets[$CIDRAM['Config']['general']['numbers']])) {
        $Set = $Sets[$CIDRAM['Config']['general']['numbers']];
        return sprintf($Base, $Set[0], $Set[1], $Set[2], $Set[3], $Set[4]);
    }
    return sprintf($Base, $Sets['Latin-1'][0], $Sets['Latin-1'][1], $Sets['Latin-1'][2], $Sets['Latin-1'][3], $Sets['Latin-1'][4]);
};

/**
 * Switch control for front-end page filters.
 *
 * @param array $Switches Names of available switches.
 * @param string $Selector Switch selector variable.
 * @param bool $StateModified Determines whether the filter state has been modified.
 * @param string $Redirect Reconstructed path to redirect to when the state changes.
 * @param string $Options Recontructed filter controls.
 */
$CIDRAM['FilterSwitch'] = function ($Switches, $Selector, &$StateModified, &$Redirect, &$Options) use (&$CIDRAM) {
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
        $Label = isset($CIDRAM['lang'][$LangItem]) ? $CIDRAM['lang'][$LangItem] : $LangItem;
        $Options .= '<option value="' . $Switch . '">' . $Label . '</option>';
    }
};

/** Duplication avoidance (front-end updates page). */
$CIDRAM['AppendTests'] = function (&$Component, $ReturnState = false) use (&$CIDRAM) {
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
            if (empty($ThisStatus['target_url'])) {
                $StatusHead .= $ThisStatus['context'];
            } else {
                $StatusHead .= '<a href="' . $ThisStatus['target_url'] . '">' . $ThisStatus['context'] . '</a>';
            }
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
                ($TestsPassed === '?' ? '?' : $CIDRAM['Number_L10N']($TestsPassed)),
                $CIDRAM['Number_L10N']($TestsTotal),
                $Component['ID'],
                $TestDetails
            );
            $CIDRAM['AppendToString'](
                $Component['StatusOptions'],
                '<hr />',
                '<div class="s">' . $CIDRAM['lang']['label_tests'] . ' ' . $TestsTotal
            );
        }
    }
    if ($ReturnState) {
        return true;
    }
};

/** Traversal detection. */
$CIDRAM['Traverse'] = function ($Path) {
    return !preg_match('~(?:[\./]{2}|[\x01-\x1f\[-^`?*$])~i', str_replace("\\", '/', $Path));
};

/** Sort function used by the front-end updates page. */
$CIDRAM['UpdatesSortFunc'] = function ($A, $B) {
    $CheckA = preg_match('/^(?:CIDRAM$|IPv[46]|l10n)/i', $A);
    $CheckB = preg_match('/^(?:CIDRAM$|IPv[46]|l10n)/i', $B);
    if ($CheckA && !$CheckB) {
        return -1;
    }
    if ($CheckB && !$CheckA) {
        return 1;
    }
    if ($A < $B) {
        return -1;
    }
    if ($A > $B) {
        return 1;
    }
    return 0;
};

/** Updates handler. */
$CIDRAM['UpdatesHandler'] = function ($Action, $ID) use (&$CIDRAM) {

    /** Define component targets. */
    $CIDRAM['Targets'] = $ID;

    /** Update a component. */
    if ($Action === 'update-component') {
        return $CIDRAM['UpdatesHandler-Update']($ID);
    }

    /** Uninstall a component. */
    if ($Action === 'uninstall-component') {
        return $CIDRAM['UpdatesHandler-Uninstall']($ID);
    }

    /** Activate a component. */
    if ($Action === 'activate-component') {
        return $CIDRAM['UpdatesHandler-Activate']($ID);
    }

    /** Deactivate a component. */
    if ($Action === 'deactivate-component') {
        return $CIDRAM['UpdatesHandler-Deactivate']($ID);
    }

    /** Verify a component. */
    if ($Action === 'verify-component') {
        return $CIDRAM['UpdatesHandler-Verify']($ID);
    }

};

/** Updates handler: Update a component. */
$CIDRAM['UpdatesHandler-Update'] = function ($ID) use (&$CIDRAM) {
    $CIDRAM['Arrayify']($ID);
    $FileData = [];
    $Annotations = [];
    foreach ($ID as $CIDRAM['Components']['ThisTarget']) {
        if (
            !isset($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Remote']) ||
            !isset($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Reannotate'])
        ) {
            continue;
        }
        $CIDRAM['Components']['BytesAdded'] = 0;
        $CIDRAM['Components']['BytesRemoved'] = 0;
        $CIDRAM['Components']['TimeRequired'] = microtime(true);
        $CIDRAM['Components']['RemoteMeta'] = [];
        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'] = $CIDRAM['FECacheGet'](
            $CIDRAM['FE']['Cache'],
            $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Remote']
        );
        if (!$CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData']) {
            $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'] = $CIDRAM['Request'](
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Remote']
            );
            if (
                strtolower(substr($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Remote'], -2)) === 'gz' &&
                substr($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'], 0, 2) === "\x1f\x8b"
            ) {
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'] = gzdecode(
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData']
                );
            }
            if (empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'])) {
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'] = '-';
            }
            $CIDRAM['FECacheAdd'](
                $CIDRAM['FE']['Cache'],
                $CIDRAM['FE']['Rebuild'],
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Remote'],
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'],
                $CIDRAM['Now'] + 3600
            );
        }
        $CIDRAM['UpdateFailed'] = false;
        if (
            substr($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'], 0, 4) === "---\n" &&
            ($CIDRAM['Components']['EoYAML'] = strpos(
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'], "\n\n"
            )) !== false &&
            $CIDRAM['YAML'](
                substr($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'], 4, $CIDRAM['Components']['EoYAML'] - 4),
                $CIDRAM['Components']['RemoteMeta']
            ) &&
            !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Minimum Required']) &&
            !$CIDRAM['VersionCompare'](
                $CIDRAM['ScriptVersion'],
                $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Minimum Required']
            ) &&
            (
                empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Minimum Required PHP']) ||
                !$CIDRAM['VersionCompare'](PHP_VERSION, $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Minimum Required PHP'])
            ) &&
            !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['From']) &&
            !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['To']) &&
            !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Reannotate']) &&
            $CIDRAM['Traverse']($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Reannotate']) &&
            ($ThisReannotate = $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Reannotate']) &&
            file_exists($CIDRAM['Vault'] . $ThisReannotate) &&
            ((
                !empty($FileData[$ThisReannotate]) &&
                $CIDRAM['Components']['OldMeta'] = $FileData[$ThisReannotate]
            ) || (
                $FileData[$ThisReannotate] = $CIDRAM['Components']['OldMeta'] = $CIDRAM['ReadFile'](
                    $CIDRAM['Vault'] . $ThisReannotate
                )
            )) &&
            preg_match(
                "\x01(\n" . preg_quote($CIDRAM['Components']['ThisTarget']) . ":?)(\n [^\n]*)*\n\x01i",
                $CIDRAM['Components']['OldMeta'],
                $CIDRAM['Components']['OldMetaMatches']
            ) &&
            ($CIDRAM['Components']['OldMetaMatches'] = $CIDRAM['Components']['OldMetaMatches'][0]) &&
            ($CIDRAM['Components']['NewMeta'] = $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData']) &&
            preg_match(
                "\x01(\n" . preg_quote($CIDRAM['Components']['ThisTarget']) . ":?)(\n [^\n]*)*\n\x01i",
                $CIDRAM['Components']['NewMeta'],
                $CIDRAM['Components']['NewMetaMatches']
            ) &&
            ($CIDRAM['Components']['NewMetaMatches'] = $CIDRAM['Components']['NewMetaMatches'][0]) &&
            (!$CIDRAM['FE']['CronMode'] || empty(
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Tests']
            ) || $CIDRAM['AppendTests']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']], true))
        ) {
            $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']);
            $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['From']);
            $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['To']);
            if (!empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['Checksum'])) {
                $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['Checksum']);
            }
            $CIDRAM['Components']['NewMeta'] = str_replace(
                $CIDRAM['Components']['OldMetaMatches'],
                $CIDRAM['Components']['NewMetaMatches'],
                $CIDRAM['Components']['OldMeta']
            );
            $Count = count($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['From']);
            $CIDRAM['RemoteFiles'] = [];
            $CIDRAM['IgnoredFiles'] = [];
            $Rollback = false;
            /** Write new and updated files and directories. */
            for ($Iterate = 0; $Iterate < $Count; $Iterate++) {
                if (empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['To'][$Iterate])) {
                    continue;
                }
                $ThisFileName = $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['To'][$Iterate];
                /** Rolls back to previous version or uninstalls if an update/install fails. */
                if ($Rollback) {
                    if (
                        isset($CIDRAM['RemoteFiles'][$ThisFileName]) &&
                        !isset($CIDRAM['IgnoredFiles'][$ThisFileName]) &&
                        is_readable($CIDRAM['Vault'] . $ThisFileName)
                    ) {
                        $CIDRAM['Components']['BytesAdded'] -= filesize($CIDRAM['Vault'] . $ThisFileName);
                        unlink($CIDRAM['Vault'] . $ThisFileName);
                        if (is_readable($CIDRAM['Vault'] . $ThisFileName . '.rollback')) {
                            $CIDRAM['Components']['BytesRemoved'] -= filesize($CIDRAM['Vault'] . $ThisFileName . '.rollback');
                            rename($CIDRAM['Vault'] . $ThisFileName . '.rollback', $CIDRAM['Vault'] . $ThisFileName);
                        }
                    }
                    continue;
                }
                if (
                    !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['Checksum'][$Iterate]) &&
                    !empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Files']['Checksum'][$Iterate]) && (
                        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['Checksum'][$Iterate] ===
                        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Files']['Checksum'][$Iterate]
                    )
                ) {
                    $CIDRAM['IgnoredFiles'][$ThisFileName] = true;
                    continue;
                }
                if (
                    empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['From'][$Iterate]) ||
                    !($ThisFile = $CIDRAM['Request'](
                        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['From'][$Iterate]
                    ))
                ) {
                    $Iterate = 0;
                    $Rollback = true;
                    continue;
                }
                if (
                    strtolower(substr(
                        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['From'][$Iterate], -2
                    )) === 'gz' &&
                    strtolower(substr($ThisFileName, -2)) !== 'gz' &&
                    substr($ThisFile, 0, 2) === "\x1f\x8b"
                ) {
                    $ThisFile = gzdecode($ThisFile);
                }
                if (
                    !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['Checksum'][$Iterate]) &&
                        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['Checksum'][$Iterate] !==
                        md5($ThisFile) . ':' . strlen($ThisFile)
                ) {
                    $CIDRAM['FE']['state_msg'] .=
                        '<code>' . $CIDRAM['Components']['ThisTarget'] . '</code> – ' .
                        '<code>' . $ThisFileName . '</code> – ' .
                        $CIDRAM['lang']['response_checksum_error'] . '<br />';
                    if (!empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['On Checksum Error'])) {
                        $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['On Checksum Error']);
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
                    $CIDRAM['Components']['BytesRemoved'] += filesize($CIDRAM['Vault'] . $ThisFileName);
                    if (file_exists($CIDRAM['Vault'] . $ThisFileName . '.rollback')) {
                        unlink($CIDRAM['Vault'] . $ThisFileName . '.rollback');
                    }
                    rename($CIDRAM['Vault'] . $ThisFileName, $CIDRAM['Vault'] . $ThisFileName . '.rollback');
                }
                $CIDRAM['Components']['BytesAdded'] += strlen($ThisFile);
                $Handle = fopen($CIDRAM['Vault'] . $ThisFileName, 'w');
                $CIDRAM['RemoteFiles'][$ThisFileName] = fwrite($Handle, $ThisFile);
                $CIDRAM['RemoteFiles'][$ThisFileName] = ($CIDRAM['RemoteFiles'][$ThisFileName] !== false);
                fclose($Handle);
                $ThisFile = '';
            }
            if ($Rollback) {
                /** Prune unwanted empty directories (update/install failure+rollback). */
                if (
                    !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['To']) &&
                    is_array($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['To'])
                ) {
                    array_walk($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['To'], function ($ThisFile) use (&$CIDRAM) {
                        if (!empty($ThisFile) && $CIDRAM['Traverse']($ThisFile)) {
                            $CIDRAM['DeleteDirectory']($ThisFile);
                        }
                    });
                }
                $CIDRAM['UpdateFailed'] = true;
            } else {
                /** Prune unwanted files and directories (update/install success). */
                if (!empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Files']['To'])) {
                    $ThisArr = $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Files']['To'];
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
                                $CIDRAM['Components']['BytesRemoved'] += filesize($CIDRAM['Vault'] . $ThisFile);
                                unlink($CIDRAM['Vault'] . $ThisFile);
                                $CIDRAM['DeleteDirectory']($ThisFile);
                            }
                        }
                    });
                    unset($ThisArr);
                }
                /** Assign updated component annotation. */
                $FileData[$ThisReannotate] = $CIDRAM['Components']['NewMeta'];
                if (!isset($Annotations[$ThisReannotate])) {
                    $Annotations[$ThisReannotate] = $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'];
                }
                $CIDRAM['FE']['state_msg'] .= '<code>' . $CIDRAM['Components']['ThisTarget'] . '</code> – ';
                if (
                    empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Version']) &&
                    empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Files'])
                ) {
                    $CIDRAM['FE']['state_msg'] .= $CIDRAM['lang']['response_component_successfully_installed'];
                    if (!empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['When Install Succeeds'])) {
                        $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['When Install Succeeds']);
                    }
                } else {
                    $CIDRAM['FE']['state_msg'] .= $CIDRAM['lang']['response_component_successfully_updated'];
                    if (!empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['When Update Succeeds'])) {
                        $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['When Update Succeeds']);
                    }
                }
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']] =
                    $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']];
            }
        } else {
            $CIDRAM['UpdateFailed'] = true;
        }
        if ($CIDRAM['UpdateFailed']) {
            $CIDRAM['FE']['state_msg'] .= '<code>' . $CIDRAM['Components']['ThisTarget'] . '</code> – ';
            if (
                empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Version']) &&
                empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Files'])
            ) {
                $CIDRAM['FE']['state_msg'] .= $CIDRAM['lang']['response_failed_to_install'];
                if (!empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['When Install Fails'])) {
                    $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['When Install Fails']);
                }
            } else {
                $CIDRAM['FE']['state_msg'] .= $CIDRAM['lang']['response_failed_to_update'];
                if (!empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['When Update Fails'])) {
                    $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['When Update Fails']);
                }
            }
        }
        $CIDRAM['FormatFilesize']($CIDRAM['Components']['BytesAdded']);
        $CIDRAM['FormatFilesize']($CIDRAM['Components']['BytesRemoved']);
        $CIDRAM['FE']['state_msg'] .= sprintf(
            $CIDRAM['FE']['CronMode'] ? " « +%s | -%s | %s »\n" : ' <code><span class="txtGn">+%s</span> | <span class="txtRd">-%s</span> | <span class="txtOe">%s</span></code><br />',
            $CIDRAM['Components']['BytesAdded'],
            $CIDRAM['Components']['BytesRemoved'],
            $CIDRAM['Number_L10N'](microtime(true) - $CIDRAM['Components']['TimeRequired'], 3)
        );
    }
    /** Update annotations. */
    foreach ($FileData as $ThisKey => $ThisFile) {
        /** Remove superfluous metadata. */
        if (!empty($Annotations[$ThisKey])) {
            $ThisFile = $CIDRAM['Congruency']($ThisFile, $Annotations[$ThisKey]);
        }
        $Handle = fopen($CIDRAM['Vault'] . $ThisKey, 'w');
        fwrite($Handle, $ThisFile);
        fclose($Handle);
    }
    /** Cleanup. */
    unset($CIDRAM['RemoteFiles'], $CIDRAM['IgnoredFiles']);
};

/** Updates handler: Uninstall a component. */
$CIDRAM['UpdatesHandler-Uninstall'] = function ($ID) use (&$CIDRAM) {
    $InUse = $CIDRAM['ComponentFunctionUpdatePrep']();
    $CIDRAM['Components']['BytesRemoved'] = 0;
    $CIDRAM['Components']['TimeRequired'] = microtime(true);
    if (
        empty($InUse) &&
        !empty($CIDRAM['Components']['Meta'][$ID]['Files']['To']) &&
        ($ID !== 'l10n/' . $CIDRAM['Config']['general']['lang']) &&
        ($ID !== 'theme/' . $CIDRAM['Config']['template_data']['theme']) &&
        ($ID !== 'CIDRAM') &&
        !empty($CIDRAM['Components']['Meta'][$ID]['Reannotate']) &&
        !empty($CIDRAM['Components']['Meta'][$ID]['Uninstallable']) &&
        ($CIDRAM['Components']['OldMeta'] = $CIDRAM['ReadFile'](
            $CIDRAM['Vault'] . $CIDRAM['Components']['Meta'][$ID]['Reannotate']
        )) &&
        preg_match(
            "\x01(\n" . preg_quote($ID) . ":?)(\n [^\n]*)*\n\x01i",
            $CIDRAM['Components']['OldMeta'],
            $CIDRAM['Components']['OldMetaMatches']
        ) &&
        ($CIDRAM['Components']['OldMetaMatches'] = $CIDRAM['Components']['OldMetaMatches'][0])
    ) {
        $CIDRAM['Components']['NewMeta'] = str_replace(
            $CIDRAM['Components']['OldMetaMatches'],
            preg_replace(
                ["/\n Files:(\n  [^\n]*)*\n/i", "/\n Version: [^\n]*\n/i"],
                "\n",
                $CIDRAM['Components']['OldMetaMatches']
            ),
            $CIDRAM['Components']['OldMeta']
        );
        array_walk($CIDRAM['Components']['Meta'][$ID]['Files']['To'], function ($ThisFile) use (&$CIDRAM) {
            if (!empty($ThisFile) && $CIDRAM['Traverse']($ThisFile)) {
                if (file_exists($CIDRAM['Vault'] . $ThisFile)) {
                    $CIDRAM['Components']['BytesRemoved'] += filesize($CIDRAM['Vault'] . $ThisFile);
                    unlink($CIDRAM['Vault'] . $ThisFile);
                }
                if (file_exists($CIDRAM['Vault'] . $ThisFile . '.rollback')) {
                    $CIDRAM['Components']['BytesRemoved'] += filesize($CIDRAM['Vault'] . $ThisFile . '.rollback');
                    unlink($CIDRAM['Vault'] . $ThisFile . '.rollback');
                }
                $CIDRAM['DeleteDirectory']($ThisFile);
            }
        });
        $Handle = fopen($CIDRAM['Vault'] . $CIDRAM['Components']['Meta'][$ID]['Reannotate'], 'w');
        fwrite($Handle, $CIDRAM['Components']['NewMeta']);
        fclose($Handle);
        $CIDRAM['Components']['Meta'][$ID]['Version'] = false;
        $CIDRAM['Components']['Meta'][$ID]['Files'] = false;
        $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_component_successfully_uninstalled'];
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Uninstall Succeeds'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Uninstall Succeeds']);
        }
    } else {
        $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_component_uninstall_error'];
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Uninstall Fails'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Uninstall Fails']);
        }
    }
    $CIDRAM['FormatFilesize']($CIDRAM['Components']['BytesRemoved']);
    $CIDRAM['FE']['state_msg'] .= sprintf(
        $CIDRAM['FE']['CronMode'] ? " « -%s | %s »\n" : ' <code><span class="txtRd">-%s</span> | <span class="txtOe">%s</span></code>',
        $CIDRAM['Components']['BytesRemoved'],
        $CIDRAM['Number_L10N'](microtime(true) - $CIDRAM['Components']['TimeRequired'], 3)
    );
};

/** Updates handler: Activate a component. */
$CIDRAM['UpdatesHandler-Activate'] = function ($ID) use (&$CIDRAM) {
    $CIDRAM['Activation'] = [
        'Config' => $CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile']),
        'ipv4' => $CIDRAM['Config']['signatures']['ipv4'],
        'ipv6' => $CIDRAM['Config']['signatures']['ipv6'],
        'modules' => $CIDRAM['Config']['signatures']['modules'],
        'Modified' => false
    ];
    $InUse = $CIDRAM['ComponentFunctionUpdatePrep']();
    if (
        empty($InUse) &&
        !empty($CIDRAM['Components']['Meta'][$ID]['Files']['To']) && (
            !empty($CIDRAM['Components']['Meta'][$ID]['Used with']) || !empty($CIDRAM['Components']['Meta'][$ID]['Extended Description'])
    )) {
        $UsedWith = empty($CIDRAM['Components']['Meta'][$ID]['Used with']) ? '' : $CIDRAM['Components']['Meta'][$ID]['Used with'];
        $Description = empty($CIDRAM['Components']['Meta'][$ID]['Extended Description']) ? '' : $CIDRAM['Components']['Meta'][$ID]['Extended Description'];
        if ($UsedWith === 'ipv4' || strpos($Description, 'signatures-&gt;ipv4') !== false) {
            $CIDRAM['ActivateComponent']('ipv4');
        }
        if ($UsedWith === 'ipv6' || strpos($Description, 'signatures-&gt;ipv6') !== false) {
            $CIDRAM['ActivateComponent']('ipv6');
        }
        if ($UsedWith === 'modules' || strpos($Description, 'signatures-&gt;modules') !== false) {
            $CIDRAM['ActivateComponent']('modules');
        }
    }
    if (!$CIDRAM['Activation']['Modified'] || !$CIDRAM['Activation']['Config']) {
        $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_activation_failed'];
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Activation Fails'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Activation Fails']);
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
        $Handle = fopen($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile'], 'w');
        fwrite($Handle, $CIDRAM['Activation']['Config']);
        fclose($Handle);
        $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_activated'];
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Activation Succeeds'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Activation Succeeds']);
        }
    }
    /** Cleanup. */
    unset($CIDRAM['Activation']);
};

/** Updates handler: Deactivate a component. */
$CIDRAM['UpdatesHandler-Deactivate'] = function ($ID) use (&$CIDRAM) {
    $CIDRAM['Deactivation'] = [
        'Config' => $CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile']),
        'ipv4' => $CIDRAM['Config']['signatures']['ipv4'],
        'ipv6' => $CIDRAM['Config']['signatures']['ipv6'],
        'modules' => $CIDRAM['Config']['signatures']['modules'],
        'Modified' => false
    ];
    $InUse = false;
    if (!empty($CIDRAM['Components']['Meta'][$ID]['Files'])) {
        $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$ID]['Files']);
        $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$ID]['Files']['To']);
        $ThisComponent = $CIDRAM['Components']['Meta'][$ID];
        $CIDRAM['PrepareExtendedDescription']($ThisComponent);
        $InUse = $CIDRAM['IsInUse']($ThisComponent);
        unset($ThisComponent);
    }
    if (!empty($InUse) && !empty($CIDRAM['Components']['Meta'][$ID]['Files']['To'])) {
        $CIDRAM['DeactivateComponent']('ipv4');
        $CIDRAM['DeactivateComponent']('ipv6');
        $CIDRAM['DeactivateComponent']('modules');
    }
    if (!$CIDRAM['Deactivation']['Modified'] || !$CIDRAM['Deactivation']['Config']) {
        $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_deactivation_failed'];
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Deactivation Fails'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Deactivation Fails']);
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
        $Handle = fopen($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile'], 'w');
        fwrite($Handle, $CIDRAM['Deactivation']['Config']);
        fclose($Handle);
        $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_deactivated'];
        if (!empty($CIDRAM['Components']['Meta'][$ID]['When Deactivation Succeeds'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$ID]['When Deactivation Succeeds']);
        }
    }
    /** Cleanup. */
    unset($CIDRAM['Deactivation']);
};

/** Updates handler: Verify a component. */
$CIDRAM['UpdatesHandler-Verify'] = function ($ID) use (&$CIDRAM) {
    $CIDRAM['Arrayify']($ID);
    foreach ($ID as $ThisID) {
        if (!empty($CIDRAM['Components']['Meta'][$ThisID]['Files'])) {
            $TheseFiles = $CIDRAM['Components']['Meta'][$ThisID]['Files'];
        }
        if (!empty($TheseFiles['To'])) {
            $CIDRAM['Arrayify']($TheseFiles['To']);
        }
        $Count = count($TheseFiles['To']);
        if (!empty($TheseFiles['Checksum'])) {
            $CIDRAM['Arrayify']($TheseFiles['Checksum']);
            if ($Count !== count($TheseFiles['Checksum'])) {
                $CIDRAM['FE']['state_msg'] .= '<code>' . $ThisID . '</code> – ' . $CIDRAM['lang']['response_verification_failed'] . '<br />';
                continue;
            }
        }
        $Passed = true;
        for ($Iterate = 0; $Iterate < $Count; $Iterate++) {
            $ThisFile = $TheseFiles['To'][$Iterate];
            $FileFailMsg = '<code>' . $ThisID . '</code> – <code>' . $ThisFile . '</code> – ' . $CIDRAM['lang']['response_possible_problem_found'] . '<br />';
            $Checksum = empty($TheseFiles['Checksum'][$Iterate]) ? false : $TheseFiles['Checksum'][$Iterate];
            if (!$ThisFileData = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $ThisFile)) {
                $CIDRAM['FE']['state_msg'] .= $FileFailMsg;
                $Passed = false;
            } elseif ($Checksum) {
                $Expected = md5($ThisFileData) . ':' . strlen($ThisFileData);
                if ($Expected !== $Checksum) {
                    $CIDRAM['FE']['state_msg'] .= $FileFailMsg;
                    $Passed = false;
                }
            }
        }
        $CIDRAM['FE']['state_msg'] .= '<code>' . $ThisID . '</code> – ' . (
            $Passed ? $CIDRAM['lang']['response_verification_success'] : $CIDRAM['lang']['response_verification_failed']
        ) . '<br />';
    }
};

/** Normalise linebreaks. */
$CIDRAM['NormaliseLinebreaks'] = function (&$Data) {
    if (strpos($Data, "\r")) {
        $Data = (strpos($Data, "\r\n") !== false) ? str_replace("\r", '', $Data) : str_replace("\r", "\n", $Data);
    }
};

/** Signature files handler for sections list. */
$CIDRAM['SectionsHandler'] = function ($Files) use (&$CIDRAM) {
    if (!isset($CIDRAM['Ignore'])) {
        $CIDRAM['Ignore'] = $CIDRAM['FetchIgnores']();
    }
    $CIDRAM['FE']['SL_Signatures'] = 0;
    $CIDRAM['FE']['SL_Sections'] = 0;
    $CIDRAM['FE']['SL_Files'] = count($Files);
    $Out = '';
    foreach ($Files as $File) {
        $Data = $File && is_readable($CIDRAM['Vault'] . $File) ? $CIDRAM['ReadFile']($CIDRAM['Vault'] . $File) : '';
        if (!$Data) {
            continue;
        }
        $CIDRAM['NormaliseLinebreaks']($Data);
        $Class = (isset($Class) && $Class === 'ng2') ? 'ng1' : 'ng2';
        $Details = ['Name' => $File . '/', 'Comments' => '', 'Signatures' => 0, 'Class' => $Class];
        $Data = "\n" . $Data . "\n";
        $PosB = -1;
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
            if ($Line === '# ---') {
                $Details['Comments'] = '';
            } elseif (preg_match('~^(?:#|[ /]\*)~', $Line)) {
                $Details['Comments'] .= $Line . '<br />';
            } elseif (substr($Line, 0, 5) === 'Tag: ') {
                $Tag = substr($Line, 5);
                $Details['Name'] .= $Tag;
                if (!empty($CIDRAM['Ignore'][$Tag])) {
                    $Details['Class'] .= '" style="filter:grayscale(50%) contrast(50%)';
                    $Details['Name'] .= ' – ' . $CIDRAM['lang']['state_ignored'];
                }
                if ($Details['Comments']) {
                    $Details['Comments'] = '<hr />' . $Details['Comments'];
                }
                $Details['Signatures'] = $CIDRAM['Number_L10N']($Details['Signatures']);
                $Out .= $CIDRAM['ParseVars']($Details, $CIDRAM['FE']['SectionsRow']);
                $CIDRAM['FE']['SL_Sections']++;
                $Class = (isset($Class) && $Class === 'ng2') ? 'ng1' : 'ng2';
                $Details = ['Name' => $File . '/', 'Comments' => '', 'Signatures' => 0, 'Class' => $Class];
            } elseif (preg_match('~^(?!(?:Tag|Expires|Origin): ).+~', $Line)) {
                $Details['Signatures']++;
                $CIDRAM['FE']['SL_Signatures']++;
            }
        }
        if ($Details['Signatures']) {
            if ($Details['Comments']) {
                $Details['Comments'] = '<hr />' . $Details['Comments'];
            }
            $Details['Signatures'] = $CIDRAM['Number_L10N']($Details['Signatures']);
            $Out .= $CIDRAM['ParseVars']($Details, $CIDRAM['FE']['SectionsRow']);
            $CIDRAM['FE']['SL_Sections']++;
        } else {
            $Class = (isset($Class) && $Class === 'ng2') ? 'ng1' : 'ng2';
        }
    }
    return $Out;
};

/** Tally IPv6 count. */
$CIDRAM['RangeTablesTallyIPv6'] = function (&$Arr, $Range) {
    $Order = ceil($Range / 16) - 1;
    $Arr[$Order] += pow(2, (128 - $Range) % 16);
};

/** Finalise IPv6 count. */
$CIDRAM['RangeTablesFinaliseIPv6'] = function ($Arr) {
    for ($Iter = 7; $Iter > 0; $Iter--) {
        if (!empty($Arr[$Iter + 1])) {
            $Arr[$Iter] += (floor($Arr[$Iter + 1] / 655.36) / 100);
        }
        while ($Arr[$Iter] >= 65536) {
            $Arr[$Iter] -= 65536;
            $Arr[$Iter - 1] += 1;
        }
    }
    foreach ($Arr as $Order => $Value) {
        if ($Value) {
            $Power = (7 - $Order) * 16;
            break;
        }
    }
    return [$Value, $Power];
};

/** Fetch some data about CIDRs. */
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

/** Iterate range tables files. */
$CIDRAM['RangeTablesIterateFiles'] = function (&$Arr, $Files, $SigTypes, $MaxRange, $IPType) use (&$CIDRAM) {
    foreach ($Files as $File) {
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

/** Iterate range tables data. */
$CIDRAM['RangeTablesIterateData'] = function (&$Arr, &$Out, &$JS, $SigType, $MaxRange, $IPType, $ZeroPlus, $Class) use (&$CIDRAM) {
    for ($Range = 1; $Range <= $MaxRange; $Range++) {
        $Size = '*Math.pow(2,' . ($MaxRange - $Range) . ')';
        if (count($Arr[$IPType][$SigType][$Range])) {
            $StatClass = $ZeroPlus;
            arsort($Arr[$IPType][$SigType][$Range]);
            foreach ($Arr[$IPType][$SigType][$Range] as $Param => &$Count) {
                if ($IPType === 'IPv4') {
                    $ThisID = $IPType . preg_replace('~[^0-9a-z]~i', '_', $SigType . $Range . $Param);
                    $Total = '<span id="' . $ThisID . '"></span>';
                    $JS .= 'w(\'' . $ThisID . '\',nft((' . $Count . $Size . ').toString()));';
                    $Count = $CIDRAM['Number_L10N']($Count) . ' (' . $Total . ')';
                } else {
                    $Count = $CIDRAM['Number_L10N']($Count);
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
                        $ThisID = $IPType . preg_replace('~[^0-9a-z]~i', '_', $SigType . $Range . $Origin);
                        $Total = '<span id="' . $ThisID . '"></span>';
                        $JS .= 'w(\'' . $ThisID . '\',nft((' . $Count . $Size . ').toString()));';
                        $Count = $CIDRAM['Number_L10N']($Count) . ' (' . $Total . ')';
                    } else {
                        $Count = $CIDRAM['Number_L10N']($Count);
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
                $ThisID = $IPType . preg_replace('~[^0-9a-z]~i', '_', $SigType . 'Total' . $Param);
                $JS .= 'w(\'' . $ThisID . '\',nft((' . $Count . ').toString()));';
            } elseif ($MaxRange === 128) {
                $Count = $CIDRAM['RangeTablesFinaliseIPv6']($Count);
                $Count[1] = $Count[1] ? '+\' × \'+nft((2).toString())+\'<sup>^\'+nft((' . $Count[1] . ').toString())+\'</sup>\'' : '';
                $ThisID = $IPType . preg_replace('~[^0-9a-z]~i', '_', $SigType . 'Total' . $Param);
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
                    $ThisID = $IPType . preg_replace('~[^0-9a-z]~i', '_', $SigType . 'Total' . $Origin);
                    $JS .= 'w(\'' . $ThisID . '\',nft((' . $Count . ').toString()));';
                } elseif ($MaxRange === 128) {
                    $Count = $CIDRAM['RangeTablesFinaliseIPv6']($Count);
                    $Count[1] = $Count[1] ? '+\' × \'+nft((2).toString())+\'<sup>^\'+nft((' . $Count[1] . ').toString())+\'</sup>\'' : '';
                    $ThisID = $IPType . preg_replace('~[^0-9a-z]~i', '_', $SigType . 'Total' . $Origin);
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

/** Range tables handler. */
$CIDRAM['RangeTablesHandler'] = function ($IPv4, $IPv6) use (&$CIDRAM) {
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
        $CIDRAM['FE']['rangeCatOptions'] .= "\n            <option value=\"" . $Class . '">' . $SigType . '</option>';
        $CIDRAM['FE']['Labels'] .= '<span style="display:none" class="s ' . $Class . '">' . $CIDRAM['lang']['label_signature_type'] . ' ' . $SigType . '</span>';
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
                $ThisArr = ['RangeType' => $Label, 'NumOfCIDRs' => $Out[$Label], 'state_loading' => $CIDRAM['lang']['state_loading']];
                $CIDRAM['FE']['RangeRows'] .= $CIDRAM['ParseVars']($ThisArr, $CIDRAM['FE']['RangeRow']);
            }
        }
    }
    foreach (['IPv4', 'IPv6'] as $IPType) {
        $Label = $IPType . '/' . $CIDRAM['lang']['label_total'];
        $Internal = $IPType . '/Total';
        if (!empty($Out[$Internal])) {
            foreach ($SigTypes as $SigType) {
                $Class = 'sigtype_' . strtolower($SigType);
                if (strpos($Out[$Internal], $Class) === false) {
                    $Out[$Internal] .= '<span style="display:none" class="' . $Class . ' s">-</span>';
                }
            }
            $ThisArr = ['RangeType' => $Label, 'NumOfCIDRs' => $Out[$Internal], 'state_loading' => $CIDRAM['lang']['state_loading']];
            $CIDRAM['FE']['RangeRows'] .= $CIDRAM['ParseVars']($ThisArr, $CIDRAM['FE']['RangeRow']);
        }
    }
    return $JS;
};

/** Assign some basic variables (initial prepwork for most front-end pages). */
$CIDRAM['InitialPrepwork'] = function ($Title = '', $Tips = '', $JS = true) use (&$CIDRAM) {

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $Title;

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = empty(
        $CIDRAM['FE']['UserRaw']
    ) ? $Tips : $CIDRAM['ParseVars'](['username' => $CIDRAM['FE']['UserRaw']], $Tips);

    /** Load main front-end JavaScript data. */
    $CIDRAM['FE']['JS'] = $JS ? $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('scripts.js')) : '';

};

/** Send page output for front-end pages (plus some other final prepwork). */
$CIDRAM['SendOutput'] = function () use (&$CIDRAM) {
    if ($CIDRAM['FE']['JS']) {
        $CIDRAM['FE']['JS'] = "\n<script type=\"text/javascript\">" . $CIDRAM['FE']['JS'] . '</script>';
    }
    return $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);
};

/** Confirm whether a file is a logfile (used by the file manager and logs viewer). */
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
    );
};

$CIDRAM['GenerateConfirm'] = function ($Action, $Form) use (&$CIDRAM) {
    $Confirm = str_replace(["'", '"'], ["\'", '\x22'], sprintf($CIDRAM['lang']['confirm_action'], $Action));
    return 'javascript:confirm(\'' . $Confirm . '\')&&document.getElementById(\'' . $Form . '\').submit()';
};
