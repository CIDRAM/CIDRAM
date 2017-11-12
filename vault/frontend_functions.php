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
 * This file: Front-end functions file (last modified: 2017.11.12).
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
            $Base = preg_replace("~\n" . $Element . ":?(\n [^\n]*)*\n~i", "\n", $Base);
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
 * Can be used to delete some empty directories via the front-end.
 *
 * @param string $Dir The directory to delete.
 */
$CIDRAM['DeleteDirectory'] = function ($Dir) use (&$CIDRAM) {
    while (strrpos($Dir, '/') !== false || strrpos($Dir, "\\") !== false) {
        $Separator = (strrpos($Dir, '/') !== false) ? '/' : "\\";
        $Dir = substr($Dir, 0, strrpos($Dir, $Separator));
        if (is_dir($CIDRAM['Vault'] . $Dir) && $CIDRAM['FileManager-IsDirEmpty']($CIDRAM['Vault'] . $Dir)) {
            rmdir($CIDRAM['Vault'] . $Dir);
        } else {
            break;
        }
    }
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
            preg_match('~^v?([0-9]+)\.([0-9]+)\.([0-9]+)(RC[0-9]{1,2}|-[0-9a-z_+\\/]+)?$~i', $Ver, $Matches) ?:
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
                } else {
                    $LastFour = strtolower(substr($ThisNameFixed, -4));
                    if (
                        $LastFour === '.tmp' ||
                        $ThisNameFixed === 'cache.dat' ||
                        $ThisNameFixed === 'fe_assets/frontend.dat' ||
                        substr($ThisNameFixed, -9) === '.rollback'
                    ) {
                        $Component = $CIDRAM['lang']['label_fmgr_cache_data'];
                    } elseif ($LastFour === '.log' || $LastFour === '.txt') {
                        $Component = $CIDRAM['lang']['link_logs'];
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
    array_walk($Path, function($Segment) use (&$Valid) {
        if (empty($Segment) || preg_match('/(?:[\x00-\x1f\x7f]+|^\.+$)/i', $Segment)) {
            $Valid = false;
        }
    });
    return $Valid;
};

/**
 * Checks whether the specified directory is empty.
 *
 * @param string $Directory The directory to check.
 * @return bool True if empty; False if not empty.
 */
$CIDRAM['FileManager-IsDirEmpty'] = function ($Directory) {
    return !((new \FilesystemIterator($Directory))->valid());
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
        if (
            preg_match('~^(?:/\.\.|./\.|\.{3})$~', str_replace("\\", '/', substr($Item, -3))) ||
            !preg_match('~(?:logfile|\.(txt|log)$)~i', $Item) ||
            !file_exists($Item) ||
            is_dir($Item) ||
            !is_file($Item) ||
            !is_readable($Item)
        ) {
            continue;
        }
        $ThisName = substr($Item, strlen($Base));
        $Arr[$ThisName] = ['Filename' => $ThisName, 'Filesize' => filesize($Item)];
        $CIDRAM['FormatFilesize']($Arr[$ThisName]['Filesize']);
    }
    return $Arr;
};

/**
 * Checks whether a component is in use (front-end closure).
 *
 * @param array $Files The list of files to be checked.
 * @param array $Files The component extended description (used to determine
 *      which type of component it is).
 * @return bool Returns true (in use) or false (not in use).
 */
$CIDRAM['IsInUse'] = function ($Files, $Description) use (&$CIDRAM) {
    foreach ($Files as $File) {
        if ((
            strpos($Description, 'signatures-&gt;ipv4') !== false &&
            strpos(',' . $CIDRAM['Config']['signatures']['ipv4'] . ',', ',' . $File . ',') !== false
        ) || (
            strpos($Description, 'signatures-&gt;ipv6') !== false &&
            strpos(',' . $CIDRAM['Config']['signatures']['ipv6'] . ',', ',' . $File . ',') !== false
        ) || (
            strpos($Description, 'signatures-&gt;modules') !== false &&
            strpos(',' . $CIDRAM['Config']['signatures']['modules'] . ',', ',' . $File . ',') !== false
        ) || (
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

/** Activate component (front-end updates page). */
$CIDRAM['ActivateComponent'] = function ($Type) use (&$CIDRAM) {
    $CIDRAM['Activation'][$Type] = array_unique(array_filter(
        explode(',', $CIDRAM['Activation'][$Type]),
        function ($Component) use (&$CIDRAM) {
            return ($Component && file_exists($CIDRAM['Vault'] . $Component));
        }
    ));
    foreach ($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To'] as $CIDRAM['Activation']['ThisFile']) {
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
        $CIDRAM['Activation']['modified'] = true;
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
    foreach ($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To'] as $CIDRAM['Deactivation']['ThisFile']) {
        $CIDRAM['Deactivation'][$Type] =
            str_replace(',' . $CIDRAM['Deactivation']['ThisFile'] . ',', ',', $CIDRAM['Deactivation'][$Type]);
    }
    $CIDRAM['Deactivation'][$Type] = substr($CIDRAM['Deactivation'][$Type], 1, -1);
    if ($CIDRAM['Deactivation'][$Type] !== $CIDRAM['Config']['signatures'][$Type]) {
        $CIDRAM['Deactivation']['modified'] = true;
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
    if (!empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Files'])) {
        $CIDRAM['PrepareExtendedDescription']($CIDRAM['Components']['Meta'][$_POST['ID']]);
        $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']);
        $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To']);
        $CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['InUse'] = $CIDRAM['IsInUse'](
            $CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To'],
            $CIDRAM['Components']['Meta'][$_POST['ID']]['Extended Description']
        );
    }
};

/** Duplication avoidance (front-end IP test page and IP tracking page). */
$CIDRAM['SimulateBlockEvent'] = function ($Addr) use (&$CIDRAM) {
    $CIDRAM['BlockInfo'] = [
        'IPAddr' => $Addr,
        'Query' => 'SimulateBlockEvent',
        'Referrer' => '',
        'UA' => '',
        'UALC' => '',
        'ReasonMessage' => '',
        'SignatureCount' => 0,
        'Signatures' => '',
        'WhyReason' => '',
        'xmlLang' => $CIDRAM['Config']['general']['lang'],
        'rURI' => 'SimulateBlockEvent'
    ];
    try {
        $CIDRAM['Caught'] = false;
        $CIDRAM['TestResults'] = $CIDRAM['RunTests']($Addr);
    } catch (\Exception $e) {
        $CIDRAM['Caught'] = true;
    }
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
$CIDRAM['Formatter'] = function (&$In) {
    $Len = strlen($In);
    if ($Len > 65536 || $Len > ini_get('pcre.backtrack_limit')) {
        return;
    }
    preg_match_all('~(&lt;\?.*\?&gt;|<\?.*\?>|\{.*\})~i', $In, $Parts);
    foreach ($Parts[0] as $ThisPart) {
        if (strlen($ThisPart) > 512 || strpos($ThisPart, "\n") !== false) {
            continue;
        }
        $In = str_replace($ThisPart, '<code>' . $ThisPart . '</code>', $In);
    }
    if (strpos($In, "<br />\n<br />\n") !== false) {
        preg_match_all('~\n([^\n:]+): [^\n]+~i', $In, $Parts);
        foreach ($Parts[1] as $ThisPart) {
            $In = str_replace("\n" . $ThisPart . ': ', "\n<span class=\"textLabel\">" . $ThisPart . '</span>: ', $In);
        }
        preg_match_all('~\n([^\n:]+): [^\n]+~i', $In, $Parts);
        foreach ($Parts[0] as $ThisPart) {
            $In = str_replace("\n" . substr($ThisPart, 1) . "\n", "\n<span class=\"s\">" . substr($ThisPart, 1) . "</span>\n", $In);
        }
    }
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
    if (!empty($CIDRAM['ForceVersionWarning']) || $CIDRAM['VersionCompare']($Version, '5.6.31') || (
        !$CIDRAM['VersionCompare']($Version, '7.0.0') && $CIDRAM['VersionCompare']($Version, '7.0.17')
    ) || (
        !$CIDRAM['VersionCompare']($Version, '7.1.0') && $CIDRAM['VersionCompare']($Version, '7.1.3')
    )) {
        $Level += 2;
    }
    if ($CIDRAM['VersionCompare']($Version, '7.0.0') || (
        !$CIDRAM['VersionCompare']($Date, '2017.12.3') && $CIDRAM['VersionCompare']($Version, '7.1.0')
    ) || (
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
$CIDRAM['FilterSwitch'] = function($Switches, $Selector, &$StateModified, &$Redirect, &$Options) use (&$CIDRAM) {
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
$CIDRAM['AppendTests'] = function (&$Component) use (&$CIDRAM) {
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
                $StatusHead .= '<span class="txtRd">❌ ';
            }
            if (empty($ThisStatus['target_url'])) {
                $StatusHead .= $ThisStatus['context'];
            } else {
                $StatusHead .= '<a href="' . $ThisStatus['target_url'] . '">' . $ThisStatus['context'] . '</a>';
            }
            $CIDRAM['AppendToString']($TestDetails, '<br />', $StatusHead . '</span>');
        }
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
        $CIDRAM['AppendToString']($Component['StatusOptions'], '<hr />',
            '<div class="s">' . $CIDRAM['lang']['label_tests'] . ' ' . $TestsTotal
        );
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
