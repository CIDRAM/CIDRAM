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
 * This file: Front-end methods file (last modified: 2022.05.19).
 */

namespace CIDRAM\CIDRAM;

trait FrontEndMethods
{
    /**
     * Format filesize information.
     *
     * @param int $Filesize
     * @return void
     */
    private function formatFileSize(int &$Filesize): void
    {
        $Scale = ['field_size_bytes', 'field_size_KB', 'field_size_MB', 'field_size_GB', 'field_size_TB'];
        $Iterate = 0;
        while ($Filesize > 1024) {
            $Filesize /= 1024;
            $Iterate++;
            if ($Iterate > 3) {
                break;
            }
        }
        $Filesize = $this->NumberFormatter->format($Filesize, ($Iterate === 0) ? 0 : 2) . ' ' . $this->L10N->getPlural($Filesize, $Scale[$Iterate]);
    }

    /**
     * Used by the file manager to generate a list of the files contained in a
     * working directory (normally, the vault).
     *
     * @param string $Base The path to the working directory.
     * @return array A list of the files contained in the working directory.
     */
    private function fileManagerRecursiveList(string $Base): array
    {
        $Arr = [];
        $Key = -1;
        $Offset = strlen($Base);
        $List = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($Base), \RecursiveIteratorIterator::SELF_FIRST);
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
                $Arr[$Key]['Filetype'] = $this->L10N->getString('field_filetype_directory');
                $Arr[$Key]['Icon'] = 'icon=directory';
            } elseif (is_file($Item)) {
                $Arr[$Key]['Directory'] = false;
                $Arr[$Key]['Filesize'] = filesize($Item);
                $Arr[$Key]['Filetype'] = $this->L10N->getString('field_filetype_unknown');
                $Arr[$Key]['Icon'] = 'icon=text';
                if (isset($this->CIDRAM['FE']['TotalSize'])) {
                    $this->CIDRAM['FE']['TotalSize'] += $Arr[$Key]['Filesize'];
                }
                if (isset($this->CIDRAM['Components']['Components'])) {
                    $Component = $this->L10N->getString('field_filetype_unknown');
                    $ThisNameFixed = str_replace("\\", '/', $ThisName);
                    if (isset($this->CIDRAM['Components']['Files'][$ThisNameFixed])) {
                        $Component = $this->CIDRAM['Components']['Names'][$this->CIDRAM['Components']['Files'][$ThisNameFixed]] ?? $this->CIDRAM['Components']['Files'][$ThisNameFixed];
                    } elseif (preg_match('~(?:[^|/]\.ht|\.safety$|^salt\.dat$)~i', $ThisNameFixed)) {
                        $Component = $this->L10N->getString('label_fmgr_safety');
                    } elseif (preg_match('~config\.yml$~i', $ThisNameFixed)) {
                        $Component = $this->L10N->getString('link_config');
                    } elseif ($this->isLogFile($ThisNameFixed)) {
                        $Component = $this->L10N->getString('link_logs');
                    } elseif ($ThisNameFixed === 'auxiliary.yml') {
                        $Component = $this->L10N->getString('link_aux');
                    } elseif (preg_match('/(?:^ignore\.dat|_custom\.dat|\.sig|\.inc)$/i', $ThisNameFixed)) {
                        $Component = $this->L10N->getString('label_fmgr_other_sig');
                    } elseif (preg_match('~(?:\.tmp|\.rollback|^(?:cache|hashes|ipbypass|assets/frontend/frontend|rl)\.dat)$~i', $ThisNameFixed)) {
                        $Component = $this->L10N->getString('label_fmgr_cache_data');
                    } elseif ($ThisNameFixed === 'installed.yml') {
                        $Component = $this->L10N->getString('label_fmgr_updates_metadata');
                    }
                    if (!isset($this->CIDRAM['Components']['Components'][$Component])) {
                        $this->CIDRAM['Components']['Components'][$Component] = 0;
                    }
                    $this->CIDRAM['Components']['Components'][$Component] += $Arr[$Key]['Filesize'];
                    if (!isset($this->CIDRAM['Components']['ComponentFiles'][$Component])) {
                        $this->CIDRAM['Components']['ComponentFiles'][$Component] = [];
                    }
                    $this->CIDRAM['Components']['ComponentFiles'][$Component][$ThisNameFixed] = $Arr[$Key]['Filesize'];
                }
                if (($ExtDel = strrpos($Item, '.')) !== false) {
                    $Ext = strtoupper(substr($Item, $ExtDel + 1));
                    if (!strlen($Ext)) {
                        $this->formatFileSize($Arr[$Key]['Filesize']);
                        continue;
                    }
                    $Arr[$Key]['Filetype'] = sprintf($this->L10N->getString('field_filetype_info'), $Ext);
                    if ($Ext === 'ICO') {
                        $Arr[$Key]['Icon'] = 'file=' . urlencode($Arr[$Key]['Filename']);
                        $this->formatFileSize($Arr[$Key]['Filesize']);
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
                $this->formatFileSize($Arr[$Key]['Filesize']);
                $Arr[$Key]['Filesize'] .= ' ⏰ <em>' . $this->timeFormat(filemtime($Item), $this->Configuration['general']['time_format']) . '</em>';
            } else {
                $Arr[$Key]['Filesize'] = '';
            }
        }
        return $Arr;
    }

    /**
     * Checks paths for directory traversal and ensures that they only contain
     * expected characters.
     *
     * @param string $Path The path to check.
     * @return bool False when directory traversals and/or unexpected characters
     *      are detected, and true otherwise.
     */
    private function pathSecurityCheck(string $Path): bool
    {
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
    }

    /**
     * Used by the logs viewer to generate a list of the logfiles contained in a
     * working directory (normally, the vault).
     *
     * @param string $Base The path to the working directory.
     * @return array A list of the logfiles contained in the working directory.
     */
    private function logsRecursiveList(string $Base): array
    {
        $Arr = [];
        $List = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($Base), \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($List as $Item => $List) {
            $ThisName = str_replace("\\", '/', substr($Item, strlen($Base)));
            if (!is_file($Item) || !is_readable($Item) || is_dir($Item) || !$this->isLogFile($ThisName)) {
                continue;
            }
            $Arr[$ThisName] = ['Filename' => $ThisName, 'Filesize' => filesize($Item)];
            $this->formatFileSize($Arr[$ThisName]['Filesize']);
        }
        ksort($Arr);
        return $Arr;
    }

    /**
     * Determine the final IP address covered by an IPv4 CIDR. This closure is used
     * by the range calculator.
     *
     * @param string $First The first IP address.
     * @param int $Factor The range number (or CIDR factor number).
     * @return string The final IP address.
     */
    private function ipv4GetLast(string $First, int $Factor): string
    {
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
    }

    /**
     * Determine the final IP address covered by an IPv6 CIDR. This closure is used
     * by the range calculator.
     *
     * @param string $First The first IP address.
     * @param int $Factor The range number (or CIDR factor number).
     * @return string The final IP address.
     */
    private function ipv6GetLast(string $First, int $Factor): string
    {
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
    }

    /**
     * Prepares component name (used by both the updater and the file manager).
     *
     * @param array $Arr Metadata of the component to be prepared.
     * @param string $Key A key to use to help find L10N data for the component name.
     * @return void
     */
    private function prepareName(array &$Arr, string $Key = ''): void
    {
        $Key = 'Name ' . $Key;
        if (isset($this->L10N->Data[$Key])) {
            $Arr['Name'] = $this->L10N->getString($Key);
        } elseif (empty($Arr['Name'])) {
            $Arr['Name'] = '';
        }
    }


    /**
     * Filter the available language options provided by the configuration page
     * on the basis of the availability of the corresponding language files.
     *
     * @param string $ChoiceKey Language code.
     * @return bool Valid/Invalid.
     */
    private function filterLang(string $ChoiceKey): bool
    {
        $Core = $this->Vault . 'l10n' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . $ChoiceKey;
        $FrontEnd = $this->Vault . 'l10n' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . $ChoiceKey;
        return (file_exists($Core . '.yml') && file_exists($FrontEnd . '.yml'));
    }

    /**
     * Filter the available hash algorithms provided by the configuration page
     * on the basis of availability.
     *
     * @param string $ChoiceKey Hash algorithm.
     * @return bool Available/Unavailable.
     */
    private function filterByDefined(string $ChoiceKey): bool
    {
        return defined($ChoiceKey);
    }

    /**
     * Filter the available theme options provided by the configuration page on
     * the basis of availability.
     *
     * @param string $ChoiceKey Theme ID.
     * @return bool Valid/Invalid.
     */
    private function filterThemeCore(string $ChoiceKey): bool
    {
        return ($ChoiceKey === 'default') ? true : file_exists($this->Vault . 'assets/core/template_' . $ChoiceKey . '.html');
    }

    /**
     * Filter the available theme options provided by the configuration page on
     * the basis of availability.
     *
     * @param string $ChoiceKey Theme ID.
     * @return bool Valid/Invalid.
     */
    private function filterThemeFrontEnd(string $ChoiceKey): bool
    {
        return ($ChoiceKey === 'default') ? true : file_exists($this->Vault . 'assets/frontend/' . $ChoiceKey . '/frontend.css');
    }

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
    private function formatter(string &$In, string $BlockLink = '', string $Current = '', string $FieldSeparator = ': ', bool $Flags = false): void
    {
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
            if ($Remainder < self::MAX_BLOCKSIZE && $Remainder < ini_get('pcre.backtrack_limit')) {
                $Section = substr($In, $Caret) . $BlockSeparator;
                $Caret = $Len;
            } else {
                $SectionLen = strrpos(substr($In, $Caret, self::MAX_BLOCKSIZE), $BlockSeparator);
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
                    $TestString = $this->Demojibakefier->guard($ThisPartUnsafe);
                    $Alternate = (
                        $TestString !== $ThisPartUnsafe && $this->Demojibakefier->Last
                    ) ? '<code dir="ltr">🔁' . $this->Demojibakefier->Last . '➡️UTF-8' . $FieldSeparator . '</code>' . str_replace(['<', '>'], ['&lt;', '&gt;'], $TestString) . "<br />\n" : '';
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
            $Out .= substr($Section, 0, $BlockSeparatorLen * -1);
        }
        $Out = substr($Out, 1);
        $In = [];
        $BlockStart = 0;
        $BlockEnd = 0;
        while ($BlockEnd !== false) {
            $Darken = empty($Darken);
            $Style = '<div class="h' . ($Darken ? 'B' : 'W') . ' hFd fW">';
            $BlockEnd = strpos($Out, $BlockSeparator, $BlockStart);
            $In[] = $Style . substr($Out, $BlockStart, $BlockEnd - $BlockStart + $BlockSeparatorLen) . '</div>';
            $BlockStart = $BlockEnd + $BlockSeparatorLen;
        }
        $In = str_replace("<br />\n</div>", "<br /></div>\n", implode('', $In));
    }

    /**
     * Attempt to tally log data.
     *
     * @param string $In The log data to be tallied.
     * @param string $BlockLink Used as the basis for links inserted into displayed log data used for searching related log data.
     * @param array $Exclusions Instructs which fields should be excluded from the tally.
     * @return string A tally of the log data (or an empty string when valid log data isn't supplied).
     */
    private function tally(string $In, string $BlockLink, array $Exclusions = []): string
    {
        if (empty($In)) {
            return '';
        }
        $Doubles = strpos($In, "\n\n") !== false;
        $Data = [];
        $PosA = 0;
        while (($PosB = strpos($In, "\n", $PosA)) !== false) {
            $Line = substr($In, $PosA, $PosB - $PosA);
            $PosA = $PosB + 1;
            if (strlen($Line) === 0) {
                continue;
            }
            foreach ($Exclusions as $Exclusion) {
                $Len = strlen($Exclusion);
                if (substr($Line, 0, $Len) === $Exclusion) {
                    continue 2;
                }
            }
            $Separator = (strpos($Line, '：') !== false) ? '：' : ': ';
            $FieldsCount = substr_count($Line, ' - ') + 1;
            if ($Doubles === false && substr_count($Line, $Separator) === $FieldsCount && $FieldsCount > 1) {
                $Fields = explode(' - ', $Line);
            } else {
                $Fields = [$Line];
            }
            foreach ($Fields as $FieldRaw) {
                if (($SeparatorPos = strpos($FieldRaw, $Separator)) === false) {
                    continue;
                }
                $Field = trim(substr($FieldRaw, 0, $SeparatorPos));
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
        $Out = '<table>';
        foreach ($Data as $Field => $Entries) {
            $Out .= '<tr><td class="h2f" colspan="2"><div class="s">' . $Field . "</div></td></tr>\n";
            if ($this->CIDRAM['FE']['SortOrder'] === 'descending') {
                arsort($Entries, SORT_NUMERIC);
            } else {
                asort($Entries, SORT_NUMERIC);
            }
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
                        $Entry = str_replace('[' . $ThisPart . ']', $this->CIDRAM['FE']['Flags'] ? (
                            '<a href="' . $BlockLink . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '"><span class="flag ' . $ThisPart . '"></span></a>'
                        ) : (
                            '[<a href="' . $BlockLink . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '">' . $ThisPart . '</a>]'
                        ), $Entry);
                    }
                }
                if (!empty($this->CIDRAM['FE']['EntryCountPaginated'])) {
                    $Percent = $this->CIDRAM['FE']['EntryCountPaginated'] ? ($Count / $this->CIDRAM['FE']['EntryCountPaginated']) * 100 : 0;
                } elseif (!empty($this->CIDRAM['FE']['EntryCount'])) {
                    $Percent = $this->CIDRAM['FE']['EntryCount'] ? ($Count / $this->CIDRAM['FE']['EntryCount']) * 100 : 0;
                } elseif (!empty($this->CIDRAM['FE']['EntryCountBefore'])) {
                    $Percent = $this->CIDRAM['FE']['EntryCountBefore'] ? ($Count / $this->CIDRAM['FE']['EntryCountBefore']) * 100 : 0;
                } else {
                    $Percent = 0;
                }
                $Out .= '<tr><td class="h1 fW">' . $Entry . '</td><td class="h1f"><div class="s">' . $this->NumberFormatter->format($Count) . ' (' . $this->NumberFormatter->format($Percent, 2) . "%)</div></td></tr>\n";
            }
        }
        $Out .= '</table>';
        return $Out;
    }

    /**
     * Get the appropriate path for a specified asset as per the defined theme.
     *
     * @param string $Asset The asset filename.
     * @param bool $CanFail Is failure acceptable? (Default: False)
     * @throws Exception if the asset can't be found.
     * @return string The asset path.
     */
    private function getAssetPath(string $Asset, bool $CanFail = false): string
    {
        if (file_exists($this->Vault . 'assets/frontend/' . $this->Configuration['frontend']['theme'] . '/' . $Asset)) {
            return $this->Vault . 'assets/frontend/' . $this->Configuration['frontend']['theme'] . '/' . $Asset;
        }
        if (file_exists($this->Vault . 'assets/frontend/default/' . $Asset)) {
            return $this->Vault . 'assets/frontend/default/' . $Asset;
        }
        if (file_exists($this->Vault . 'assets/frontend/' . $Asset)) {
            return $this->Vault . 'assets/frontend/' . $Asset;
        }
        if ($CanFail) {
            return '';
        }
        throw new \Exception('Asset not found');
    }

    /**
     * Generates JavaScript code for localising numbers locally.
     *
     * @return string The JavaScript code.
     */
    private function numberL10nJs(): string
    {
        if ($this->NumberFormatter->ConversionSet === 'Western') {
            $ConvJS = 'return l10nd';
        } else {
            $ConvJS = 'var nls=[' . $this->NumberFormatter->getSetCSV(
                $this->NumberFormatter->ConversionSet
            ) . '];return nls[l10nd]||l10nd';
        }
        return sprintf(
            'function l10nn(l10nd){%4$s};function nft(r){var x=r.indexOf(\'.\')!=-1?' .
            '\'%1$s\'+r.replace(/^.*\./gi,\'\'):\'\',n=r.replace(/\..*$/gi,\'\').rep' .
            'lace(/[^0-9]/gi,\'\'),t=n.length;for(e=\'\',b=%5$d,i=1;i<=t;i++){b>%3$d' .
            '&&(b=1,e=\'%2$s\'+e);var e=l10nn(n.substring(t-i,t-(i-1)))+e;b++}var t=' .
            'x.length;for(y=\'\',b=1,i=1;i<=t;i++){var y=l10nn(x.substring(t-i,t-(i-' .
            '1)))+y}return e+y}',
            $this->NumberFormatter->DecimalSeparator,
            $this->NumberFormatter->GroupSeparator,
            $this->NumberFormatter->GroupSize,
            $ConvJS,
            $this->NumberFormatter->GroupOffset + 1
        );
    }

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
    private function filterSwitch(array $Switches, string $Selector, bool &$StateModified, string &$Redirect, string &$Options): void
    {
        foreach ($Switches as $Switch) {
            $State = (!empty($Selector) && $Selector === $Switch);
            $this->CIDRAM['FE'][$Switch] = empty($this->CIDRAM['QueryVars'][$Switch]) ? false : (
                ($this->CIDRAM['QueryVars'][$Switch] === 'true' && !$State) ||
                ($this->CIDRAM['QueryVars'][$Switch] !== 'true' && $State)
            );
            if ($State) {
                $StateModified = true;
            }
            if ($this->CIDRAM['FE'][$Switch]) {
                $Redirect .= '&' . $Switch . '=true';
                $LangItem = 'switch-' . $Switch . '-set-false';
            } else {
                $Redirect .= '&' . $Switch . '=false';
                $LangItem = 'switch-' . $Switch . '-set-true';
            }
            $Label = $this->L10N->getString($LangItem) ?: $LangItem;
            $Options .= '<option value="' . $Switch . '">' . $Label . '</option>';
        }
    }

    /**
     * Normalise linebreaks.
     *
     * @param string $Data The data to normalise.
     * @return void
     */
    private function normaliseLinebreaks(string &$Data)
    {
        if (strpos($Data, "\r")) {
            $Data = (strpos($Data, "\r\n") !== false) ? str_replace("\r", '', $Data) : str_replace("\r", "\n", $Data);
        }
    }

    /**
     * Signature files handler for sections list.
     *
     * @param array $Files The signature files to process.
     * @return string Generated sections list data.
     */
    private function sectionsHandler(array $Files): string
    {
        if (!isset($this->CIDRAM['Ignore'])) {
            $this->CIDRAM['Ignore'] = $this->fetchIgnores();
        }
        $this->CIDRAM['FE']['SL_Signatures'] = 0;
        $this->CIDRAM['FE']['SL_Sections'] = 0;
        $this->CIDRAM['FE']['SL_Files'] = count($Files);
        $this->CIDRAM['FE']['SL_Unique'] = 0;
        $Out = '';
        $SectionsForIgnore = [];
        $SignaturesCount = [];
        $FilesCount = [];
        $SectionMeta = [];
        $ThisSectionMeta = [];
        foreach ($Files as $File) {
            $Data = $this->readFile($this->SignaturesPath . $File);
            if (strlen($Data) === 0) {
                continue;
            }
            $this->normaliseLinebreaks($Data);
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
                    $this->CIDRAM['FE']['SL_Sections']++;
                    if (!isset($SectionsForIgnore[$Tag])) {
                        $SectionsForIgnore[$Tag] = empty($this->CIDRAM['Ignore'][$Tag]);
                    }
                    foreach ($ThisSectionMeta as $ThisOrigin => $ThisQuantity) {
                        if (!isset($SectionsForIgnore[$Tag . ':' . $ThisOrigin])) {
                            $SectionsForIgnore[$Tag . ':' . $ThisOrigin] = empty($this->CIDRAM['Ignore'][$Tag . ':' . $ThisOrigin]);
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
                $this->CIDRAM['FE']['SL_Signatures']++;
            }
        }
        $Class = 'ng2';
        ksort($SectionMeta);
        $this->CIDRAM['FE']['SL_Unique'] = count($SectionMeta);
        foreach ($SectionMeta as $Section => $Counts) {
            $ThisCount = $SignaturesCount[$Section] ?? 0;
            $ThisFiles = isset($FilesCount[$Section]) ? count($FilesCount[$Section]) : 0;
            $ThisCount = sprintf(
                $this->L10N->getPlural($ThisFiles, 'label_sections_across_x_files'),
                sprintf(
                    $this->L10N->getPlural($ThisCount, 'label_sections_x_signatures'),
                    '<span class="txtRd">' . $this->NumberFormatter->format($ThisCount) . '</span>'
                ),
                '<span class="txtRd">' . $this->NumberFormatter->format($ThisFiles) . '</span>'
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
                    $this->L10N->getPlural($Quantity, 'label_sections_x_signatures'),
                    $this->NumberFormatter->format($Quantity)
                );
                $OriginDisplay = '<code>' . $Origin . '</code>' . ($this->CIDRAM['FE']['Flags'] ? ' – <span class="flag ' . $Origin . '"></span>' : '');
                $OriginOut .= "\n" . sprintf(
                    '<div class="sectionControlNotIgnored%s">%s – %s – %s<a href="javascript:void()" onclick="javascript:slx(\'%s:%s\',\'%s</a></div>',
                    $OriginSafe . '" style="transform:skew(-18deg)' . ($State ? '' : ';display:none'),
                    $OriginDisplay,
                    $Quantity,
                    '',
                    $Section,
                    $Origin,
                    'ignore\',\'sectionControlNotIgnored' . $OriginSafe . '\',\'sectionControlIgnored' . $OriginSafe . '\')">' . $this->L10N->getString('label_ignore')
                ) . sprintf(
                    '<div class="sectionControlIgnored%s">%s – %s – %s<a href="javascript:void()" onclick="javascript:slx(\'%s:%s\',\'%s</a></div>',
                    $OriginSafe . '" style="transform:skew(-18deg);filter:grayscale(75%) contrast(50%)' . ($State ? ';display:none' : ''),
                    $OriginDisplay,
                    $Quantity,
                    $this->L10N->getString('state_ignored') . ' – ',
                    $Section,
                    $Origin,
                    'unignore\',\'sectionControlIgnored' . $OriginSafe . '\',\'sectionControlNotIgnored' . $OriginSafe . '\')">' . $this->L10N->getString('label_unignore')
                );
            }
            $State = !empty($SectionsForIgnore[$Section]);
            $Out .= "\n" . sprintf(
                '<div class="%s sectionControlNotIgnored%s"><strong>%s%s</strong><br />%s</div>',
                $Class,
                $State ? $SectionSafe : $SectionSafe . '" style="display:none',
                $SectionLabel,
                ' – <a href="javascript:void()" onclick="javascript:slx(\'' . $Section . '\',\'ignore\',\'sectionControlNotIgnored' . $SectionSafe . '\',\'sectionControlIgnored' . $SectionSafe . '\')">' . $this->L10N->getString('label_ignore') . '</a>',
                $OriginOut
            ) . sprintf(
                '<div class="%s sectionControlIgnored%s"><strong>%s%s</strong><br />%s</div>',
                $Class,
                $SectionSafe . '" style="filter:grayscale(50%) contrast(50%)' . ($State ? ';display:none' : ''),
                $SectionLabel . ' – ' . $this->L10N->getString('state_ignored'),
                ' – <a href="javascript:void()" onclick="javascript:slx(\'' . $Section . '\',\'unignore\',\'sectionControlIgnored' . $SectionSafe . '\',\'sectionControlNotIgnored' . $SectionSafe . '\')">' . $this->L10N->getString('label_unignore') . '</a>',
                $OriginOut
            );
        }
        return $Out;
    }

    /**
     * Tally IPv6 count.
     *
     * @param array $Arr
     * @param int $Range (1-128)
     * @return void
     */
    private function rangeTablesTallyIpv6(array &$Arr, int $Range)
    {
        $Order = ceil($Range / 16) - 1;
        $Arr[$Order] += 2 ** ((128 - $Range) % 16);
    }

    /**
     * Finalise IPv6 count.
     *
     * @param array $Arr Values of the IPv6 octets.
     * @return array A base value (first parameter), and the power it would need to
     *      be raised by (second parameter) to accurately reflect the total amount.
     */
    private function rangeTablesFinaliseIpv6(array $Arr): array
    {
        for ($Iter = 7; $Iter > 0; $Iter--) {
            if (!empty($Arr[$Iter + 1])) {
                $Arr[$Iter] += (floor($Arr[$Iter + 1] / 655.36) / 100);
            }
            while ($Arr[$Iter] >= self::MAX_BLOCKSIZE) {
                $Arr[$Iter] -= self::MAX_BLOCKSIZE;
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
    }

    /**
     * Fetch some data about CIDRs.
     *
     * @param string $Data The signature file data.
     * @param int $Offset The current offset position.
     * @param string $Needle A needle to identify the parts of the data we're looking for.
     * @param bool $HasOrigin Whether the data has an origin tag.
     * @return array|bool The CIDR's parameter and origin (when available), or false on failure.
     */
    private function rangeTablesFetchLine(string &$Data, int &$Offset, string &$Needle, bool &$HasOrigin)
    {
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
    }

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
    private function rangeTablesIterateFiles(array &$Arr, array $Files, array $SigTypes, int $MaxRange, string $IPType): void
    {
        if (!isset($this->CIDRAM['Ignore'])) {
            $this->CIDRAM['Ignore'] = $this->fetchIgnores();
        }
        foreach ($Files as $File) {
            $File = (strpos($File, ':') === false) ? $File : substr($File, strpos($File, ':') + 1);
            $Data = $this->readFile($this->SignaturesPath . $File);
            if (strlen($Data) === 0) {
                continue;
            }
            if (isset($this->CIDRAM['FE']['Matrix-Data']) && class_exists('\Maikuolan\Common\Matrix') && function_exists('imagecreatetruecolor')) {
                $this->CIDRAM['FE']['Matrix-Data'] .= $Data;
            }
            $this->normaliseLinebreaks($Data);
            $HasOrigin = (strpos($Data, "\nOrigin: ") !== false);
            foreach ($SigTypes as $SigType) {
                for ($Range = 1; $Range <= $MaxRange; $Range++) {
                    if ($MaxRange === 32) {
                        $Order = 2 ** ($MaxRange - $Range);
                    }
                    $Offset = 0;
                    $Needle = '/' . $Range . ' ' . $SigType;
                    while ($Offset !== false) {
                        if (!$Entry = $this->rangeTablesFetchLine($Data, $Offset, $Needle, $HasOrigin)) {
                            break;
                        }
                        $Into = (!empty($Entry['Tag']) && !empty($this->CIDRAM['Ignore'][$Entry['Tag']])) ? $IPType . '-Ignored' : $IPType;
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
                                $this->rangeTablesTallyIpv6($Arr[$ThisInto][$SigType]['Total'][$Entry['Param']], $Range);
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
                                $this->rangeTablesTallyIpv6($Arr[$ThisInto . '-Origin'][$SigType]['Total'][$Entry['Origin']], $Range);
                            }
                        }
                    }
                }
            }
        }
    }

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
    private function rangeTablesIterateData(array &$Arr, array &$Out, string &$JS, string $SigType, int $MaxRange, string $IPType, string $ZeroPlus, string $Class)
    {
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
                            $Count = $this->NumberFormatter->format($Count) . ' (' . $Total . ')';
                        } else {
                            $Count = $this->NumberFormatter->format($Count);
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
                                $Count = $this->NumberFormatter->format($Count) . ' (' . $Total . ')';
                            } else {
                                $Count = $this->NumberFormatter->format($Count);
                            }
                            $Count = '<code class="hB">' . $Origin . '</code> – ' . (
                                $this->CIDRAM['FE']['Flags'] && $Origin !== '??' ? '<span class="flag ' . $Origin . '"></span> – ' : ''
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
                        $Count = $this->rangeTablesFinaliseIpv6($Count);
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
                            $Count = $this->rangeTablesFinaliseIpv6($Count);
                            $Count[1] = $Count[1] ? '+\' × \'+nft((2).toString())+\'<sup>^\'+nft((' . $Count[1] . ').toString())+\'</sup>\'' : '';
                            $ThisID = preg_replace('~[^\da-z]~i', '_', $IgnoreState . $SigType . 'Total' . $Origin);
                            $JS .= 'w(\'' . $ThisID . '\',' . ($Count[1] ? '\'~\'+' : '') . 'nft((' . $Count[0] . ').toString())' . $Count[1] . ');';
                        }
                        $Count = '<code class="hB">' . $Origin . '</code> – ' . (
                            $this->CIDRAM['FE']['Flags'] && $Origin !== '??' ? '<span class="flag ' . $Origin . '"></span> – ' : ''
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
    }

    /**
     * Range tables handler.
     *
     * @param array $IPv4 The currently active IPv4 signature files.
     * @param array $IPv6 The currently active IPv6 signature files.
     * @return string Some JavaScript generated to populate the range tables data.
     */
    private function rangeTablesHandler(array $IPv4, array $IPv6): string
    {
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
        $this->rangeTablesIterateFiles($Arr, $IPv4, $SigTypes, 32, 'IPv4');
        $this->rangeTablesIterateFiles($Arr, $IPv6, $SigTypes, 128, 'IPv6');
        $this->CIDRAM['FE']['Labels'] = '';
        $JS = '';
        $RangeCatOptions = [];
        foreach ($SigTypes as $SigType) {
            $Class = 'sigtype_' . strtolower($SigType);
            $RangeCatOptions[] = '<option value="' . $Class . '">' . $SigType . '</option>';
            $this->CIDRAM['FE']['Labels'] .= '<span style="display:none" class="s ' . $Class . '">' . $this->L10N->getString('label_signature_type') . ' ' . $SigType . '</span>';
            if ($SigType === 'Run') {
                $ZeroPlus = 'txtOe';
            } else {
                $ZeroPlus = ($SigType === 'Whitelist' || $SigType === 'Greylist') ? 'txtGn' : 'txtRd';
            }
            $this->rangeTablesIterateData($Arr, $Out, $JS, $SigType, 32, 'IPv4', $ZeroPlus, $Class);
            $this->rangeTablesIterateData($Arr, $Out, $JS, $SigType, 128, 'IPv6', $ZeroPlus, $Class);
        }
        $this->CIDRAM['FE']['rangeCatOptions'] = implode("\n            ", $RangeCatOptions);
        $this->CIDRAM['FE']['RangeRows'] = '';
        foreach ([['IPv4', 32], ['IPv6', 128]] as $Build) {
            for ($Range = 1; $Range <= $Build[1]; $Range++) {
                foreach ([
                    [$Build[0] . '/' . $Range, $Build[0] . '/' . $Range],
                    [$Build[0] . '-Ignored/' . $Range, $Build[0] . '/' . $Range . ' (' . $this->L10N->getString('state_ignored') . ')'],
                    [$Build[0] . '-Total/' . $Range, $Build[0] . '/' . $Range . ' (' . $this->L10N->getString('label_total') . ')']
                ] as $Label) {
                    if (!empty($Out[$Label[0]])) {
                        foreach ($SigTypes as $SigType) {
                            $Class = 'sigtype_' . strtolower($SigType);
                            if (strpos($Out[$Label[0]], $Class) === false) {
                                $Out[$Label[0]] .= '<span style="display:none" class="' . $Class . ' s">-</span>';
                            }
                        }
                        $ThisArr = ['RangeType' => $Label[1], 'NumOfCIDRs' => $Out[$Label[0]], 'state_loading' => $this->L10N->getString('state_loading')];
                        $this->CIDRAM['FE']['RangeRows'] .= $this->parseVars($ThisArr, $this->CIDRAM['FE']['RangeRow']);
                    }
                }
            }
        }
        $Loading = $this->L10N->getString('state_loading');
        foreach ([
            ['', $this->L10N->getString('label_total')],
            ['-Ignored', $this->L10N->getString('label_total') . ' (' . $this->L10N->getString('state_ignored') . ')'],
            ['-Total', $this->L10N->getString('label_total') . ' (' . $this->L10N->getString('label_total') . ')']
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
            $this->CIDRAM['FE']['RangeRows'] .= $this->parseVars([
                'RangeType' => $Label[1],
                'NumOfCIDRs' => $ThisRight,
                'state_loading' => $Loading
            ], $this->CIDRAM['FE']['RangeRow']);
        }
        return $JS;
    }

    /**
     * Assign some basic variables (initial prepwork for most front-end pages).
     *
     * @param string $Title The page title.
     * @param string $Tips The page "tip" to include ("Hello username! Here you can...").
     * @param bool $JS Whether to include the standard front-end JavaScript boilerplate.
     * @return void
     */
    private function initialPrepwork(string $Title = '', string $Tips = '', bool $JS = true): void
    {
        /** Set page title. */
        $this->CIDRAM['FE']['FE_Title'] = 'CIDRAM – ' . $Title;

        /** Fetch and prepare username. */
        if ($Username = (empty($this->CIDRAM['FE']['User']) ? '' : $this->CIDRAM['FE']['User'])) {
            $Username = preg_replace('~^([^<>]+)<[^<>]+>$~', '\1', $Username);
            if (($AtChar = strpos($Username, '@')) !== false) {
                $Username = substr($Username, 0, $AtChar);
            }
        }

        /** Prepare page tooltip/description. */
        $this->CIDRAM['FE']['FE_Tip'] = $this->parseVars(['username' => $Username], $Tips);

        /** Load main front-end JavaScript data. */
        $this->CIDRAM['FE']['JS'] = $JS ? $this->readFile($this->getAssetPath('scripts.js')) : '';
    }

    /**
     * Send page output for front-end pages (plus some other final prepwork).
     *
     * @return string Page output.
     */
    private function sendOutput(): string
    {
        if ($this->CIDRAM['FE']['JS']) {
            $this->CIDRAM['FE']['JS'] = "\n<script type=\"text/javascript\">" . $this->CIDRAM['FE']['JS'] . '</script>';
        }
        $Template = $this->CIDRAM['FE']['Template'];
        $Labels = [];
        $Segments = [];
        if (isset($this->CIDRAM['FE']['UserState']) && $this->CIDRAM['FE']['UserState'] === 1) {
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
        return $this->parseVars(array_merge($this->L10N->Data, $this->CIDRAM['FE']), $Template);
    }

    /**
     * Confirm whether a file is a logfile (used by the file manager and logs viewer).
     *
     * @param string $File The path/name of the file to be confirmed.
     * @return bool True if it's a logfile; False if it isn't.
     */
    private function isLogFile(string $File): bool
    {
        static $Pattern_logfile = false;
        if (!$Pattern_logfile && $this->Configuration['general']['logfile']) {
            $Pattern_logfile = $this->buildLogPattern($this->Configuration['general']['logfile'], true);
        }
        static $Pattern_logfile_apache = false;
        if (!$Pattern_logfile_apache && $this->Configuration['general']['logfile_apache']) {
            $Pattern_logfile_apache = $this->buildLogPattern($this->Configuration['general']['logfile_apache'], true);
        }
        static $Pattern_logfile_serialized = false;
        if (!$Pattern_logfile_serialized && $this->Configuration['general']['logfile_serialized']) {
            $Pattern_logfile_serialized = $this->buildLogPattern($this->Configuration['general']['logfile_serialized'], true);
        }
        static $Pattern_frontend_log = false;
        if (!$Pattern_frontend_log && $this->Configuration['frontend']['frontend_log']) {
            $Pattern_frontend_log = $this->buildLogPattern($this->Configuration['frontend']['frontend_log'], true);
        }
        static $Pattern_reCAPTCHA_logfile = false;
        if (!$Pattern_reCAPTCHA_logfile && $this->Configuration['recaptcha']['logfile']) {
            $Pattern_reCAPTCHA_logfile = $this->buildLogPattern($this->Configuration['recaptcha']['logfile'], true);
        }
        static $Pattern_HCaptcha_logfile = false;
        if (!$Pattern_HCaptcha_logfile && $this->Configuration['hcaptcha']['logfile']) {
            $Pattern_HCaptcha_logfile = $this->buildLogPattern($this->Configuration['hcaptcha']['logfile'], true);
        }
        static $Pattern_PHPMailer_EventLog = false;
        if (!$Pattern_PHPMailer_EventLog && $this->Configuration['PHPMailer']['event_log']) {
            $Pattern_PHPMailer_EventLog = $this->buildLogPattern($this->Configuration['PHPMailer']['event_log'], true);
        }
        return preg_match('~\.log(?:\.gz)?$~', strtolower($File)) || (
            $this->Configuration['general']['logfile'] && preg_match($Pattern_logfile, $File)
        ) || (
            $this->Configuration['general']['logfile_apache'] && preg_match($Pattern_logfile_apache, $File)
        ) || (
            $this->Configuration['general']['logfile_serialized'] && preg_match($Pattern_logfile_serialized, $File)
        ) || (
            $this->Configuration['frontend']['frontend_log'] && preg_match($Pattern_frontend_log, $File)
        ) || (
            $this->Configuration['recaptcha']['logfile'] && preg_match($Pattern_reCAPTCHA_logfile, $File)
        ) || (
            $this->Configuration['hcaptcha']['logfile'] && preg_match($Pattern_HCaptcha_logfile, $File)
        ) || (
            $this->Configuration['PHPMailer']['event_log'] && preg_match($Pattern_PHPMailer_EventLog, $File)
        );
    }

    /**
     * Generates JavaScript snippets for confirmation prompts for front-end actions.
     *
     * @param string $Action The action being taken to be confirmed.
     * @param string $Form The ID of the form to be submitted when the action is confirmed.
     * @return string The JavaScript snippet.
     */
    private function generateConfirmation(string $Action, string $Form): string
    {
        $Confirm = str_replace(["'", '"'], ["\'", '\x22'], sprintf($this->L10N->getString('confirm_action'), $Action));
        return 'javascript:confirm(\'' . $Confirm . '\')&&document.getElementById(\'' . $Form . '\').submit()';
    }

    /**
     * A quicker way to add entries to the front-end logfile.
     *
     * @param string $IPAddr The IP address triggering the log event.
     * @param string $User The user triggering the log event.
     * @param string $Message The message to be logged.
     * @return void
     */
    private function FELogger(string $IPAddr, string $User, string $Message): void
    {
        /** Guard. */
        if (
            empty($this->CIDRAM['FE']['DateTime']) ||
            !$this->Configuration['frontend']['frontend_log'] ||
            !($File = $this->buildPath($this->Vault . $this->Configuration['frontend']['frontend_log']))
        ) {
            return;
        }

        $Data = $this->Configuration['legal']['pseudonymise_ip_addresses'] ? $this->pseudonymiseIp($IPAddr) : $IPAddr;
        $Data .= ' - ' . $this->CIDRAM['FE']['DateTime'] . ' - "' . $User . '" - ' . $Message . "\n";

        $Truncate = $this->readBytes($this->Configuration['general']['truncate']);
        $WriteMode = (!file_exists($File) || $Truncate > 0 && filesize($File) >= $Truncate) ? 'wb' : 'ab';
        $Handle = fopen($File, $WriteMode);
        fwrite($Handle, $Data);
        fclose($Handle);
        if ($WriteMode === 'wb') {
            $this->logRotation($this->Configuration['frontend']['frontend_log']);
        }
    }

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
    private function sendEmail(array $Recipients = [], string $Subject = '', string $Body = '', string $AltBody = '', array $Attachments = []): bool
    {
        /** Prepare event logging. */
        $EventLogData = sprintf(
            '%s - %s - ',
            $this->Configuration['legal']['pseudonymise_ip_addresses'] ? $this->pseudonymiseIp($this->ipAddr) : $this->ipAddr,
            $this->CIDRAM['FE']['DateTime'] ?? $this->timeFormat($this->Now, $this->Configuration['general']['time_format'])
        );

        /** Operation success state. */
        $State = false;

        /** Check whether class exists to either load it and continue or fail the operation. */
        if (!class_exists('\PHPMailer\PHPMailer\PHPMailer')) {
            $EventLogData .= $this->L10N->getString('state_failed_missing') . "\n";
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
                if ($this->Configuration['PHPMailer']['skip_auth_process']) {
                    $Mail->SMTPOptions = ['ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]];
                }

                /** Set mail server hostname. */
                $Mail->Host = $this->Configuration['PHPMailer']['host'];

                /** Set the SMTP port. */
                $Mail->Port = $this->Configuration['PHPMailer']['port'];

                /** Set the encryption system to use. */
                if (
                    !empty($this->Configuration['PHPMailer']['smtp_secure']) &&
                    $this->Configuration['PHPMailer']['smtp_secure'] !== '-'
                ) {
                    $Mail->SMTPSecure = $this->Configuration['PHPMailer']['smtp_secure'];
                }

                /** Set whether to use SMTP authentication. */
                $Mail->SMTPAuth = $this->Configuration['PHPMailer']['smtp_auth'];

                /** Set the username to use for SMTP authentication. */
                $Mail->Username = $this->Configuration['PHPMailer']['username'];

                /** Set the password to use for SMTP authentication. */
                $Mail->Password = $this->Configuration['PHPMailer']['password'];

                /** Set the email sender address and name. */
                $Mail->setFrom(
                    $this->Configuration['PHPMailer']['set_from_address'],
                    $this->Configuration['PHPMailer']['set_from_name']
                );

                /** Set the optional "reply to" address and name. */
                if (
                    !empty($this->Configuration['PHPMailer']['add_reply_to_address']) &&
                    !empty($this->Configuration['PHPMailer']['add_reply_to_name'])
                ) {
                    $Mail->addReplyTo(
                        $this->Configuration['PHPMailer']['add_reply_to_address'],
                        $this->Configuration['PHPMailer']['add_reply_to_name']
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
                    $this->L10N->getString('state_email_sent'),
                    $SuccessDetails
                ) : $this->L10N->getString('response_error') . ' - ' . $Mail->ErrorInfo) . "\n";
            } catch (\Exception $e) {
                /** An exeption occurred. Log the information. */
                $EventLogData .= $this->L10N->getString('response_error') . ' - ' . $e->getMessage() . "\n";
            }
        }

        /** Write to the event log. */
        $this->Events->fireEvent('writeToPHPMailerEventLog', $EventLogData);

        /** Exit. */
        return $State;
    }

    /**
     * Generates very simple 8-digit numbers used for 2FA.
     *
     * @return int An 8-digit number.
     */
    private function twoFactorNumber(): int
    {
        try {
            $Key = random_int(self::TWO_FACTOR_MIN_INT, self::TWO_FACTOR_MAX_INT);
        } catch (\Exception $e) {
            $Key = rand(self::TWO_FACTOR_MIN_INT, self::TWO_FACTOR_MAX_INT);
        }
        return $Key;
    }

    /**
     * Generates the rules data displayed on the auxiliary rules page.
     *
     * @param bool $Mode Whether view mode (false) or edit mode (true).
     * @return string The generated auxiliary rules data.
     */
    private function generateRules(bool $Mode = false): string
    {
        /** Populate output here. */
        $Output = '';

        /** JavaScript stuff to append to output after everything else. */
        $JSAppend = '';

        /** Potential sources. */
        $Sources = $this->generateLabels($this->CIDRAM['Provide']['Auxiliary Rules']['Sources']);

        /** Attempt to parse the auxiliary rules file. */
        if (!isset($this->CIDRAM['AuxData'])) {
            $this->CIDRAM['AuxData'] = [];
            $this->YAML->process($this->readFile($this->Vault . 'auxiliary.yml'), $this->CIDRAM['AuxData']);
        }

        /** Count entries (needed for offering first and last move options). */
        $Count = count($this->CIDRAM['AuxData']);

        /** Make entries safe for display at the front-end. */
        $this->recursiveReplace($this->CIDRAM['AuxData'], ['<', '>', '"'], ['&lt;', '&gt;', '&quot;']);

        if ($Mode) {
            /** Append empty rule if editing. */
            $this->CIDRAM['AuxData'][' '] = [];
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
                $this->L10N->getString('field_update_all')
            );
        };

        /** Used to generate IDs for radio fields. */
        $GridID = 'AAAA';

        /** Used to link radio field IDs with checkFlagsSelected. */
        $JSAuxAppend = '';

        /** Iterate through the auxiliary rules. */
        foreach ($this->CIDRAM['AuxData'] as $Name => $Data) {
            /** Rule row ID. */
            $RuleClass = preg_replace('~^0+~', '', bin2hex($Name));

            /** Edit mode. */
            if ($Mode) {
                /** Update cell style. */
                $StyleClass = $StyleClass === 'ng1' ? 'ng2' : 'ng1';

                /** Rule begin and rule name. */
                $Output .= sprintf(
                    '%1$s<div class="%2$s"><div class="iCntr">%1$s  <div class="iLabl s">%4$s</div><div class="iCntn"><input type="text" name="ruleName[%5$s]" class="f400" value="%3$s" /></div></div>',
                    "\n      ",
                    $StyleClass,
                    $Name === ' ' ? '' : $Name,
                    $this->L10N->getString('field_new_name'),
                    $Current
                );

                /** Set rule priority (rearranges the rules). */
                $Output .= sprintf(
                    '%1$s<div class="iCntr"><div class="iLabl s">%3$s</div>%1$s  <div class="iCntn"><input type="text" name="rulePriority[%2$s]" class="f400" value="%2$s" /></div></div>',
                    "\n      ",
                    $Current,
                    $this->L10N->getString('field_execution_order')
                );

                /** Rule reason. */
                $Output .= sprintf(
                    '<div class="iCntr"><div class="iLabl s" id="%4$sruleReasonDt">%2$s</div><div class="iCntn" id="%4$sruleReasonDd"><input type="text" name="ruleReason[%3$s]" class="f400" value="%1$s" /></div></div>',
                    $Data['Reason'] ?? '',
                    $this->L10N->getString('label_aux_reason'),
                    $Current,
                    $RuleClass
                );

                /** Redirect target. */
                $Output .= sprintf(
                    '<div class="iCntr"><div class="iLabl s" id="%4$sruleTargetDt">%2$s</div><div class="iCntn" id="%4$sruleTargetDd"><input type="text" name="ruleTarget[%3$s]" class="f400" value="%1$s" /></div></div>',
                    $Data['Target'] ?? '',
                    $this->L10N->getString('label_aux_target'),
                    $Current,
                    $RuleClass
                );

                /** Run target. */
                $Output .= sprintf(
                    '<div class="iCntr"><div class="iLabl s" id="%4$sruleRunDt">%2$s</div><div class="iCntn" id="%4$sruleRunDd"><input type="text" name="ruleRun[%3$s]" class="f400" value="%1$s" /></div></div>',
                    $Data['Run']['File'] ?? '',
                    $this->L10N->getString('label_aux_run'),
                    $Current,
                    $RuleClass
                );

                /** From. */
                $Output .= sprintf(
                    '<div class="iCntr"><div class="iLabl s" id="%4$sfromDt">%2$s</div><div class="iCntn" id="%4$sfromDd"><input type="date" name="from[%3$s]" class="f400" value="%1$s" min="%5$s" /></div></div>',
                    isset($Data['From']) ? str_replace('.', '-', $Data['From']) : '',
                    $this->L10N->getString('label_aux_from'),
                    $Current,
                    $RuleClass,
                    $this->CIDRAM['FE']['Y-m-d']
                );

                /** Expiry. */
                $Output .= sprintf(
                    '<div class="iCntr"><div class="iLabl s" id="%4$sexpiryDt">%2$s</div><div class="iCntn" id="%4$sexpiryDd"><input type="date" name="expiry[%3$s]" class="f400" value="%1$s" min="%5$s" /></div></div>',
                    isset($Data['Expiry']) ? str_replace('.', '-', $Data['Expiry']) : '',
                    $this->L10N->getString('label_aux_expiry'),
                    $Current,
                    $RuleClass,
                    $this->CIDRAM['FE']['Y-m-d']
                );

                /** Status code override. */
                $Output .= sprintf('<div class="iCntr"><div class="iLabl s">%1$s</div><div class="iCntn">', $this->L10N->getString('label_aux_http_status_code_override'));
                $Output .= sprintf(
                    '<span id="%1$sstatGroupX" class="statGroup"><input type="radio" class="auto" id="%1$sstatusCodeX" name="statusCode[%3$s]" value="0" %2$s/><label for="%1$sstatusCodeX">🗙</label></span>',
                    $RuleClass,
                    empty($Data['Status Code']) ? 'checked="true" ' : '',
                    $Current
                );
                foreach ([['3', ['301', '307', '308']], ['45', ['403', '410', '418', '451', '503']]] as $StatGroup) {
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
                $Output .= sprintf('<div class="iCntr"><div class="iLabl"><select id="act%1$s" name="act[%1$s]" class="auto" onchange="javascript:onauxActionChange(this.value,\'%2$s\',\'%1$s\')">', $Current, $RuleClass);
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
                        $this->CIDRAM['FE'][$MenuOption[1]]
                    );
                    if (!empty($Data[$MenuOption[2]])) {
                        $ConditionsFrom = $MenuOption[2];
                        $JSAppend .= sprintf('onauxActionChange(\'%1$s\',\'%2$s\',\'%3$s\');', $MenuOption[0], $RuleClass, $Current);
                    }
                }
                $Output .= sprintf(
                    '</select><input type="button" onclick="javascript:addCondition(\'%2$s\')" value="%1$s" class="auto" /></div>',
                    $this->L10N->getString('field_add_more_conditions'),
                    $Current
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
                                $ThisSources = str_replace('value="' . $Key . '">', 'value="' . $Key . '" selected>', $this->CIDRAM['FE']['conSources']);
                                foreach ($Values as $Condition) {
                                    $Output .= sprintf(
                                        $ConditionFormTemplate,
                                        $Current,
                                        $Iteration,
                                        $ThisSources,
                                        $this->L10N->getString('tip_condition_placeholder'),
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
                    $this->L10N->getString('field_add_webhook')
                );

                /** Populate webhooks. */
                if (isset($Data['Webhooks']) && is_array($Data['Webhooks'])) {
                    $Iteration = 0;
                    foreach ($Data['Webhooks'] as $Webhook) {
                        $Output .= sprintf(
                            '<input type="text" name="webhooks[%1$s][%2$s]" placeholder="%3$s" class="f400" value="%4$s" />',
                            $Current,
                            $Iteration,
                            $this->L10N->getString('tip_condition_placeholder'),
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
                    $this->CIDRAM['FE']['optMtdStr'],
                    $this->CIDRAM['FE']['optMtdReg'],
                    $this->CIDRAM['FE']['optMtdWin'],
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
                    $this->L10N->getString('label_aux_logic_any'),
                    $this->L10N->getString('label_aux_logic_all'),
                    $LogicData[0],
                    $LogicData[1]
                );

                /** Other options and special flags. */
                $Output .= '<div class="gridbox">';
                foreach ($this->CIDRAM['Provide']['Auxiliary Rules']['Flags'] as $FlagSetName => $FlagSet) {
                    $FlagKey = preg_replace('~[^A-Za-z]~', '', $FlagSetName);
                    $UseDefaultState = true;
                    foreach ($FlagSet as $FlagName => $FlagData) {
                        if ($FlagName === 'Empty' && isset($FlagData['Decoration'])) {
                            $Output .= sprintf(
                                '<div class="gridboxitem" style="%s"></div>',
                                $FlagData['Decoration'] . 'filter:grayscale(.75)'
                            );
                            continue;
                        }
                        if (!isset($FlagData['Label'])) {
                            $Output .= '<div class="gridboxitem"></div>';
                            continue;
                        }
                        $Label = $this->L10N->getString($FlagData['Label']) ?: $FlagData['Label'];
                        $Filter = $FlagData['Decoration'] ?? '';
                        if (empty($Data[$FlagName])) {
                            $Filter .= 'filter:grayscale(.75)';
                            $ThisSelected = '';
                        } else {
                            $Filter .= 'filter:grayscale(0)';
                            $ThisSelected = ' checked';
                            $UseDefaultState = false;
                        }
                        $Output .= sprintf(
                            '<label><div class="gridboxitem" style="%1$s" id="%6$s"><input type="radio" class="auto" name="%2$s[%3$d]" value="%4$s" onchange="javascript:checkFlagsSelected()"%7$s /> <strong>%5$s</strong></div></label>',
                            $Filter,
                            $FlagKey,
                            $Current,
                            $FlagName,
                            $Label,
                            $GridID,
                            $ThisSelected
                        );
                        $JSAuxAppend .= ($JSAuxAppend ? ',' : '') . "'" . $GridID . "'";
                        $GridID++;
                    }
                    $Output .= sprintf(
                        '<label><div class="gridboxitem" style="%1$s" id="%6$s"><input type="radio" class="auto" name="%2$s[%3$d]" value="%4$s" onchange="javascript:checkFlagsSelected()"%7$s /> <strong>%5$s</strong></div></label>',
                        $this->CIDRAM['FE']['Empty'] . ($UseDefaultState ? 'filter:grayscale(0)' : 'filter:grayscale(.75)'),
                        $FlagKey,
                        $Current,
                        'Default State',
                        $this->L10N->getString('label_aux_special_default_state'),
                        $GridID,
                        $UseDefaultState ? ' checked' : ''
                    );
                    $JSAuxAppend .= ($JSAuxAppend ? ',' : '') . "'" . $GridID . "'";
                    $GridID++;
                }

                /** Rule notes. */
                $Output .= sprintf(
                    '</div><div class="iCntr"><div class="iLabl s">%1$s</div><div class="iCntn"><textarea id="Notes[%2$s]" name="Notes[%2$s]" class="half">%3$s</textarea></div></div>',
                    $this->L10N->getString('label_aux_notes'),
                    $Current,
                    $Data['Notes'] ?? ''
                );

                /** Finish writing new rule. */
                $Output .= '</div>';
                $Current++;
            } else {
                /** Figure out which options are available for the rule. */
                $Options = ['(<span style="cursor:pointer" onclick="javascript:%s(\'' . addslashes($Name) . '\',\'' . $RuleClass . '\')"><code class="s">%s</code></span>)'];
                $Options['delRule'] = sprintf($Options[0], 'delRule', $this->L10N->getString('field_delete'));
                if ($Count > 1) {
                    if ($Current !== 1) {
                        if ($Current !== 2) {
                            $Options['moveUp'] = sprintf($Options[0], 'moveUp', $this->L10N->getString('label_aux_move_up'));
                        }
                        $Options['moveToTop'] = sprintf($Options[0], 'moveToTop', $this->L10N->getString('label_aux_move_top'));
                    }
                    if ($Current !== $Count) {
                        if ($Current !== ($Count - 1)) {
                            $Options['moveDown'] = sprintf($Options[0], 'moveDown', $this->L10N->getString('label_aux_move_down'));
                        }
                        $Options['moveToBottom'] = sprintf($Options[0], 'moveToBottom', $this->L10N->getString('label_aux_move_bottom'));
                    }
                }
                unset($Options[0]);
                $Options = ' – ' . implode(' ', $Options);

                /** Begin generating rule output. */
                $Output .= sprintf(
                    '%1$s<li class="%2$s"><span class="comCat s">%3$s</span>%4$s%5$s%1$s  <ul class="comSub">',
                    "\n      ",
                    $RuleClass,
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
                    if (!empty($Data[$Details[0]]) && $Label = $this->L10N->getString($Details[1])) {
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
                    if (!empty($Data[$Details[0]]) && $Label = $this->L10N->getString($Details[1])) {
                        if (preg_match('~^(\d{4})[.-](\d\d)[.-](\d\d)$~', $Data[$Details[0]], $Details[2])) {
                            $Data[$Details[0]] .= ' (' . $this->relativeTime(
                                mktime(0, 0, 0, (int)$Details[2][2], (int)$Details[2][3], (int)$Details[2][1])
                            ) . ')';
                        }
                        $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $Label . '</div><div class="iCntn">' . $Data[$Details[0]] . '</div></div></li>';
                    }
                }

                /** Display the status code to be applied. */
                if (!empty($Data['Status Code']) && $Data['Status Code'] > 200 && $StatusCode = $this->getStatusHTTP($Data['Status Code'])) {
                    $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $this->L10N->getString('label_aux_http_status_code_override') . '</div><div class="iCntn">' . $Data['Status Code'] . ' ' . $StatusCode . '</div></div></li>';
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
                        $this->CIDRAM['FE'][$Action[1]]
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
                                $Operator = $this->operatorFromAuxValue($Value, true);
                                $Output .= "\n              <div class=\"iCntn\"><span style=\"float:" . $this->CIDRAM['FE']['FE_Align'] . '">' . $ThisSource . '&nbsp;' . $Operator . '&nbsp;</span><code>' . $Value . '</code></div>';
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
                                $Operator = $this->operatorFromAuxValue($Value);
                                $Output .= "\n              <div class=\"iCntn\"><span style=\"float:" . $this->CIDRAM['FE']['FE_Align'] . '">' . $ThisSource . '&nbsp;' . $Operator . '&nbsp;</span><code>' . $Value . '</code></div>';
                            }
                        }
                    }

                    /** Finish writing conditions list. */
                    $Output .= "\n            </div>\n          </li>";
                }

                /** Cite the file to run. */
                if (!empty($Data['Run']['File']) && $Label = $this->L10N->getString('label_aux_run')) {
                    $Output .= "\n            <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $Label . '</div><div class="iCntn">' . $Data['Run']['File'] . '</div></div></li>';
                }

                /** Display other options and special flags. */
                $Flags = [];
                foreach ($this->CIDRAM['Provide']['Auxiliary Rules']['Flags'] as $FlagSetName => $FlagSet) {
                    foreach ($FlagSet as $FlagName => $FlagData) {
                        if (!is_array($FlagData) || empty($FlagData['Label'])) {
                            continue;
                        }
                        $Label = $this->L10N->getString($FlagData['Label']) ?: $FlagData['Label'];
                        if (!empty($Data[$FlagName])) {
                            $Flags[] = $Label;
                        }
                    }
                }
                if (count($Flags)) {
                    $Output .= "\n            <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $this->L10N->getString('label_aux_special') . '</div><div class="iCntn">' . implode('<br />', $Flags) . '</div></div></li>';
                }

                /** Show the method to be used. */
                $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl\"><em>" . (isset($Data['Method']) ? (
                    $Data['Method'] === 'RegEx' ? $this->CIDRAM['FE']['optMtdReg'] : (
                        $Data['Method'] === 'WinEx' ? $this->CIDRAM['FE']['optMtdWin'] : $this->CIDRAM['FE']['optMtdStr']
                    )
                ) : $this->CIDRAM['FE']['optMtdStr']) . '</em></div></div></li>';

                /** Describe matching logic used. */
                $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl\"><em>" . $this->L10N->getString(
                    (!empty($Data['Logic']) && $Data['Logic'] !== 'Any') ? 'label_aux_logic_all' : 'label_aux_logic_any'
                ) . '</em></div></div></li>';

                /** Finish writing new rule. */
                $Output .= "\n        </ul>\n      </li>";
                $Current++;
            }
        }

        /** Update button after. */
        if ($Mode) {
            $StyleClass = $StyleClass === 'ng1' ? 'ng2' : 'ng1';
            $Output .= sprintf(
                '<div class="%s"><center><input type="submit" value="%s" class="auto" /></center></div>',
                $StyleClass,
                $this->L10N->getString('field_update_all')
            );
        };

        /** Exit with generated output. */
        return $Output . '<script type="text/javascript">window.auxFlags=['. $JSAuxAppend . '];' . $JSAppend . '</script>';
    }

    /**
     * Generate select options from an associative array.
     *
     * @param array $Options An associative array of the options to generate.
     * @param bool $JS Whether generating for JavaScript.
     * @return string The generated options.
     */
    private function generateOptions(array $Options, bool $JS = false): string
    {
        $Output = '';
        foreach ($Options as $Value => $Label) {
            if (is_array($Label)) {
                $Output .= $this->generateOptions($Label, $JS);
                continue;
            }
            $Label = $this->L10N->getString($Label) ?: $Label;
            if ($JS) {
                $Output .= "\n  x = document.createElement('option'),\n  x.setAttribute('value', '" . $Value . "'),\n  x.innerHTML = '" . $Label . "',\n  t.appendChild(x),";
            } else {
                $Output .= '<option value="' . $Value . '">' . $Label . '</option>';
            }
        }
        return $Output;
    }

    /**
     * Generate labels from an associative array.
     *
     * @param array $Options An associative array of the labels to generate.
     * @return array The generated labels.
     */
    private function generateLabels(array $Options): array
    {
        $Output = [];
        foreach ($Options as $Value => $Label) {
            if (is_array($Label)) {
                $Output = array_merge($Output, $this->generateLabels($Label));
                continue;
            }
            $Label = $this->L10N->getString($Label) ?: $Label;
            $Output[$Value] = $Label;
        }
        return $Output;
    }

    /**
     * Procedure to populate methods, actions, and sources used by the
     * auxiliary rules page.
     *
     * @return void
     */
    private function populateMethodsActions(): void
    {
        /** Append JavaScript specific to the auxiliary rules page. */
        $this->CIDRAM['FE']['JS'] .= $this->parseVars(
            ['tip_condition_placeholder' => $this->L10N->getString('tip_condition_placeholder')],
            $this->readFile($this->getAssetPath('auxiliary.js'))
        );

        /** Populate methods. */
        $this->CIDRAM['FE']['optMtdStr'] = sprintf($this->L10N->getString('label_aux_menu_method'), $this->L10N->getString('label_aux_mtdStr'));
        $this->CIDRAM['FE']['optMtdReg'] = sprintf($this->L10N->getString('label_aux_menu_method'), $this->L10N->getString('label_aux_mtdReg'));
        $this->CIDRAM['FE']['optMtdWin'] = sprintf($this->L10N->getString('label_aux_menu_method'), $this->L10N->getString('label_aux_mtdWin'));

        /** Populate actions. */
        $this->CIDRAM['FE']['optActWhl'] = sprintf($this->L10N->getString('label_aux_menu_action'), $this->L10N->getString('label_aux_actWhl'));
        $this->CIDRAM['FE']['optActGrl'] = sprintf($this->L10N->getString('label_aux_menu_action'), $this->L10N->getString('label_aux_actGrl'));
        $this->CIDRAM['FE']['optActBlk'] = sprintf($this->L10N->getString('label_aux_menu_action'), $this->L10N->getString('label_aux_actBlk'));
        $this->CIDRAM['FE']['optActByp'] = sprintf($this->L10N->getString('label_aux_menu_action'), $this->L10N->getString('label_aux_actByp'));
        $this->CIDRAM['FE']['optActLog'] = sprintf($this->L10N->getString('label_aux_menu_action'), $this->L10N->getString('label_aux_actLog'));
        $this->CIDRAM['FE']['optActRdr'] = sprintf($this->L10N->getString('label_aux_menu_action'), $this->L10N->getString('label_aux_actRdr'));
        $this->CIDRAM['FE']['optActRun'] = sprintf($this->L10N->getString('label_aux_menu_action'), $this->L10N->getString('label_aux_actRun'));
        $this->CIDRAM['FE']['optActPro'] = sprintf($this->L10N->getString('label_aux_menu_action'), $this->L10N->getString('label_aux_actPro'));

        /** Populate sources. */
        $this->CIDRAM['FE']['conSources'] = $this->generateOptions($this->CIDRAM['Provide']['Auxiliary Rules']['Sources']);

        /** Populate sources for JavaScript. */
        $this->CIDRAM['FE']['conSourcesJS'] = $this->generateOptions($this->CIDRAM['Provide']['Auxiliary Rules']['Sources'], true);
    }

    /**
     * Generate a clickable list from an array.
     *
     * @param array $Arr The array to convert from.
     * @param string $DeleteKey The key to use for async calls to delete a cache entry.
     * @param int $Depth Current cache entry list depth.
     * @param string $ParentKey An optional key of the parent data source.
     * @return string The generated clickable list.
     */
    public function arrayToClickableList(array $Arr = [], string $DeleteKey = '', int $Depth = 0, string $ParentKey = ''): string
    {
        $Output = '';
        $Count = count($Arr);
        $Prefix = substr($DeleteKey, 0, 2) === 'fe' ? 'FE' : '';
        foreach ($Arr as $Key => $Value) {
            if (is_string($Value) && !$this->Demojibakefier->checkConformity($Value)) {
                continue;
            }
            $Delete = ($Depth === 0) ? ' – (<span style="cursor:pointer" onclick="javascript:' . $DeleteKey . '(\'' . addslashes($Key) . '\')"><code class="s">' . $this->L10N->getString('field_delete') . '</code></span>)' : '';
            $Output .= ($Depth === 0 ? '<span id="' . $Key . $Prefix . 'Container">' : '') . '<li>';
            if (!is_array($Value)) {
                if (substr($Value, 0, 2) === '{"' && substr($Value, -2) === '"}') {
                    $Try = json_decode($Value, true);
                    if ($Try !== null) {
                        $Value = $Try;
                    }
                } elseif (
                    preg_match('~\.ya?ml$~i', $Key) ||
                    (preg_match('~^(?:Data|\d+)$~', $Key) && preg_match('~\.ya?ml$~i', $ParentKey))
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
                    $SizeField = $this->L10N->getString('field_size') ?: 'Size';
                    $Size = isset($Value['Data']) && is_string($Value['Data']) ? strlen($Value['Data']) : (
                        isset($Value[0]) && is_string($Value[0]) ? strlen($Value[0]) : false
                    );
                    if ($Size !== false) {
                        $this->formatFileSize($Size);
                        $Value[$SizeField] = $Size;
                    }
                }
                $Output .= '<span class="comCat"><code class="s">' . str_replace(['<', '>'], ['&lt;', '&gt;'], $Key) . '</code></span>' . $Delete . '<ul class="comSub">';
                $Output .= $this->arrayToClickableList($Value, $DeleteKey, $Depth + 1, $Key);
                $Output .= '</ul>';
            } else {
                if ($Key === 'Time' && preg_match('~^\d+$~', $Value)) {
                    $Key = $this->L10N->getString('label_expires');
                    $Value = $this->timeFormat($Value, $this->Configuration['general']['time_format']);
                }
                $Class = ($Key === $this->L10N->getString('field_size') || $Key === $this->L10N->getString('label_expires')) ? 'txtRd' : 's';
                $Text = ($Count === 1 && $Key === 0) ? $Value : $Key . ($Class === 's' ? ' => ' : ' ') . $Value;
                $Output .= '<code class="' . $Class . '" style="word-wrap:break-word;word-break:break-all">' . str_replace(['<', '>'], ['&lt;', '&gt;'], $Text) . '</code>' . $Delete;
            }
            $Output .= '</li>' . ($Depth === 0 ? '<br /></span>' : '');
        }
        return $Output;
    }

    /**
     * Supplied string is used to generate arbitrary values used as RGB
     * information for CSS styling (used by the file manager).
     *
     * @param string $String The supplied string to use.
     * @param int $Mode Whether to return the values as an array of integers,
     *      a hash-like string, or both.
     * @return string|array an array of integers, a hash-like string, or both.
     */
    private function rgb(string $String = '', int $Mode = 0)
    {
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
    }

    /**
     * Provides stronger support for LTR inside RTL text.
     *
     * @param string $String The string to work with.
     * @return string The string, modified if necessary.
     */
    private function ltrInRtf(string $String = ''): string
    {
        /** Get direction. */
        $Direction = (
            !isset($this->L10N) ||
            empty($this->L10N->Data['Text Direction']) ||
            $this->L10N->Data['Text Direction'] !== 'rtl'
        ) ? 'ltr' : 'rtl';

        /** If the page isn't RTL, the string should be returned verbatim. */
        if ($Direction !== 'rtl') {
            return $String;
        }

        /** Modify the string to better suit RTL directionality and return it. */
        while (true) {
            $NewString = preg_replace(
                ['~^(.+)-&gt;(.+)$~i', '~^(.+)➡(.+)$~i'],
                ['\2&lt;-\1', '\2⬅\1'],
                $String
            );
            if ($NewString === $String) {
                return $NewString;
            }
            $String = $NewString;
        }
    }

    /**
     * Splits a CIDR into two smaller CIDRs of the same total value.
     *
     * @param string $CIDR The CIDR to split.
     * @return array An array containing two elements (the smaller CIDRs), or an
     *      empty array on faliure (e.g., supplied data isn't a valid CIDR).
     */
    private function splitCidr(string $CIDR): array
    {
        if (($Pos = strpos($CIDR, '/')) === false) {
            return [];
        }
        $Base = substr($CIDR, 0, $Pos);
        $Factor = substr($CIDR, $Pos + 1);
        if ($CIDRs = $this->expandIpv4($Base, false, $Factor + 1)) {
            $Is = 4;
        } elseif ($CIDRs = $this->expandIpv6($Base, false, $Factor + 1)) {
            $Is = 6;
        } else {
            return [];
        }
        if ($Factor < 1 || ($Is === 4 && $Factor >= 32) || ($Is === 6 && $Factor >= 128)) {
            return [];
        }
        $Split = [$CIDRs[$Factor]];
        $Last = ($Is === 4) ? $this->ipv4GetLast($Base, $Factor) : $this->ipv6GetLast($Base, $Factor);
        $CIDRs = ($Is === 4) ? $this->expandIpv4($Last, false, $Factor + 1) : $this->expandIpv6($Last, false, $Factor + 1);
        if (isset($CIDRs[$Factor])) {
            $Split[] = $CIDRs[$Factor];
            return $Split;
        }
        return [];
    }

    /**
     * Returns the intersect of two sets of CIDRs.
     *
     * @param string $A Set A.
     * @param string $B Set B.
     * @param int $Format The format to return the results as.
     *      1 = Netmasks. 0 = CIDRs.
     * @return string The intersect.
     */
    private function intersectCidr(string $A = '', string $B = '', int $Format = 0): string
    {
        $StrObject = new \Maikuolan\Common\ComplexStringHandler(
            $A . "\n",
            $this->CIDRAM['RegExTags'],
            function (string $Data) use ($B, $Format): string {
                $Data = "\n" . $this->CIDRAM['Aggregator']->aggregate($Data) . "\n";
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
                        if (!$CIDRs = $this->expandIpv4($Base)) {
                            if (!$CIDRs = $this->expandIpv6($Base)) {
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
                $Aggregator = new \CIDRAM\CIDRAM\Aggregator($Format);
                return trim($Aggregator->aggregate($Intersect));
            }
        );
        $StrObject->iterateClosure(function (string $Data): string {
            return "\n" . $Data;
        }, true);
        return trim($StrObject->recompile());
    }

    /**
     * Subtracts subtrahend CIDRs from minuend CIDRs and returns the difference.
     *
     * @param string $Minuend The minuend (assumes no erroneous data).
     * @param string $Subtrahend The subtrahend (assumes no erroneous data).
     * @param int $Format The format to return the results as.
     *      1 = Netmasks. 0 = CIDRs.
     * @return string The difference.
     */
    private function subtractCidr(string $Minuend = '', string $Subtrahend = '', int $Format = 0): string
    {
        $StrObject = new \Maikuolan\Common\ComplexStringHandler(
            $Minuend . "\n",
            $this->CIDRAM['RegExTags'],
            function (string $Minuend) use ($Subtrahend, $Format): string {
                $Minuend = "\n" . $this->CIDRAM['Aggregator']->aggregate($Minuend . "\n" . $Subtrahend) . "\n";
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
                    if (!$CIDRs = $this->expandIpv4($Base, false, $Range)) {
                        if (!$CIDRs = $this->expandIpv6($Base, false, $Range)) {
                            continue;
                        }
                    }
                    foreach ($CIDRs as $Key => $Actual) {
                        if (strpos($Minuend, "\n" . $Actual . "\n") === false) {
                            continue;
                        }
                        if ($Range > ($Key + 1) && $Split = $this->splitCidr($Actual)) {
                            $Minuend .= implode("\n", $Split) . "\n";
                        }
                        $Minuend = str_replace("\n" . $Actual . "\n", "\n", $Minuend);
                    }
                }
                $Aggregator = new \CIDRAM\CIDRAM\Aggregator($Format);
                return trim($Aggregator->aggregate($Minuend));
            }
        );
        $StrObject->iterateClosure(function (string $Data): string {
            return "\n" . $Data;
        }, true);
        return trim($StrObject->recompile());
    }

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
    private function matrixIncrement(&$Current, $Key, &$Previous, $KeyPrevious, &$Next, $KeyNext, &$Step, $Amount)
    {
        if (
            !is_array($Current) ||
            !isset($Current['R'], $Current['G'], $Current['B'], $Amount[0], $Amount[1]) ||
            ($Amount[0] !== 'R' && $Amount[0] !== 'G' && $Amount[0] !== 'B')
        ) {
            return;
        }
        $Current[$Amount[0]] += $Amount[1];
    }

    /**
     * A callback closure used by the matrix handler to limit a coordinate's RGB values.
     *
     * @param string $Current The value of the current coordinate.
     */
    private function matrixLimit(&$Current)
    {
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
    }

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
    private function matrixDraw(&$Current, $Key, &$Previous, $KeyPrevious, &$Next, $KeyNext, &$Step, $Offsets): void
    {
        if (!is_array($Current) || !is_array($Offsets) || !isset($Current['R'], $Current['G'], $Current['B'], $this->CIDRAM['Matrix-Image'])) {
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
        imagesetpixel($this->CIDRAM['Matrix-Image'], $X, $Y, $Colour);
    }

    /**
     * Yields the ranges of the signatures in the currently active signature
     * files as values to be used as coordinates in the matrices generated by
     * the matrix handler for use at the front-end range tables page.
     *
     * @param string $Source The contents of the currently active signature files.
     */
    private function matrixCreateGenerator(string &$Source): \Generator
    {
        $SPos = 0;
        while (($FPos = strpos($Source, "\n", $SPos)) !== false) {
            $Mark = [];
            if (isset($this->CIDRAM['Matrix-%Print'])) {
                $this->CIDRAM['Matrix-%Print']();
            }
            $Line = substr($Source, $SPos, $FPos - $SPos);
            $SPos = $FPos + 1;
            if (!strlen($Line) || substr($Line, 0, 1) === '#') {
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
                preg_match('~^()([\da-f]{1,2})()()\:\:/(\d+) (Deny|Whitelist|Greylist|Run)~', $Line, $Matches) ||
                preg_match('~^([\da-f]{1,2})([\da-f]{2})()()\:\:/(\d+) (Deny|Whitelist|Greylist|Run)~', $Line, $Matches) ||
                preg_match('~^([\da-f]{1,2})([\da-f]{2})\:()([\da-f]{1,2})\:\:/(\d+) (Deny|Whitelist|Greylist|Run)~', $Line, $Matches) ||
                preg_match('~^([\da-f]{1,2})([\da-f]{2})\:([\da-f]{1,2})([\da-f]{2})\:\:/(\d+) (Deny|Whitelist|Greylist|Run)~', $Line, $Matches)
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
    }

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
    private function matrixCreate(string &$Source, string $Destination = '', bool $CLI = false): string
    {
        if ($CLI) {
            $Splits = ['Percentage' => 0, 'Skip' => 0];
            $Splits['Max'] = substr_count($Source, "\n");
            $this->CIDRAM['Matrix-%Print'] = function () use (&$Splits) {
                $Splits['Percentage']++;
                $Splits['Skip']++;
                if ($Splits['Percentage'] >= $Splits['Max']) {
                    $Splits['Max'] = $Splits['Percentage'] + 1;
                }
                $Current = $Splits['Percentage'] / $Splits['Max'];
                if ($Splits['Skip'] > 24) {
                    $Splits['Skip'] = 0;
                    $Memory = memory_get_usage();
                    $this->formatFileSize($Memory);
                    echo "\rWorking ... " . $this->NumberFormatter->format($Current, 2) . '% (' . $this->timeFormat(time(), $this->Configuration['general']['time_format']) . ') <RAM: ' . $Memory . '>';
                }
            };
            echo "\rWorking ...";
        } elseif (isset($this->CIDRAM['Matrix-%Print'])) {
            unset($this->CIDRAM['Matrix-%Print']);
        }

        $IPv4 = new \Maikuolan\Common\Matrix();
        $IPv4->createMatrix(2, 256, ['R' => 0, 'G' => 0, 'B' => 0]);

        $IPv6 = new \Maikuolan\Common\Matrix();
        $IPv6->createMatrix(2, 256, ['R' => 0, 'G' => 0, 'B' => 0]);

        foreach ($this->matrixCreateGenerator($Source) as $Mark) {
            if ($Mark['6or4'] === 4) {
                $IPv4->iterateCallback($Mark['Range'], $this->matrixIncrement, $Mark['Colour'], $Mark['Amount']);
            } elseif ($Mark['6or4'] === 6) {
                $IPv6->iterateCallback($Mark['Range'], $this->matrixIncrement, $Mark['Colour'], $Mark['Amount']);
            }
        }

        $IPv4->iterateCallback('0-255,0-255', $this->matrixLimit);
        $IPv6->iterateCallback('0-255,0-255', $this->matrixLimit);

        $Wheel = new \Maikuolan\Common\Matrix();
        $Wheel->createMatrix(2, 24, ['R' => 0, 'G' => 0, 'B' => 0]);
        $Wheel->iterateCallback('0-8,12-15', $this->matrixIncrement, 'R', 32);
        $Wheel->iterateCallback('0-8,8-15', $this->matrixIncrement, 'R', 32);
        $Wheel->iterateCallback('0-8,4-15', $this->matrixIncrement, 'R', 32);
        $Wheel->iterateCallback('0-8,0-15', $this->matrixIncrement, 'R', 32);
        $Wheel->iterateCallback('5-13,16-19', $this->matrixIncrement, 'B', 32);
        $Wheel->iterateCallback('5-13,12-19', $this->matrixIncrement, 'B', 32);
        $Wheel->iterateCallback('5-13,8-19', $this->matrixIncrement, 'B', 32);
        $Wheel->iterateCallback('5-13,4-19', $this->matrixIncrement, 'B', 32);
        $Wheel->iterateCallback('10-18,20-23', $this->matrixIncrement, 'G', 32);
        $Wheel->iterateCallback('10-18,16-23', $this->matrixIncrement, 'G', 32);
        $Wheel->iterateCallback('10-18,12-23', $this->matrixIncrement, 'G', 32);
        $Wheel->iterateCallback('10-18,8-23', $this->matrixIncrement, 'G', 32);

        $this->CIDRAM['Matrix-Image'] = imagecreatetruecolor(544, 308);

        /** Roofs. */
        imageline($this->CIDRAM['Matrix-Image'], 10, 48, 269, 48, 16777215);
        imageline($this->CIDRAM['Matrix-Image'], 284, 48, 543, 48, 16777215);

        /** Walls. */
        imageline($this->CIDRAM['Matrix-Image'], 10, 48, 10, 307, 16777215);
        imageline($this->CIDRAM['Matrix-Image'], 269, 48, 269, 307, 16777215);
        imageline($this->CIDRAM['Matrix-Image'], 284, 48, 284, 307, 16777215);
        imageline($this->CIDRAM['Matrix-Image'], 543, 48, 543, 307, 16777215);

        /** Floors. */
        imageline($this->CIDRAM['Matrix-Image'], 10, 307, 269, 307, 16777215);
        imageline($this->CIDRAM['Matrix-Image'], 284, 307, 543, 307, 16777215);

        imagestring($this->CIDRAM['Matrix-Image'], 2, 12, 2, 'CIDRAM signature file analysis (image generated ' . date('Y.m.d', time()) . ').', 16777215);
        imagefilledrectangle($this->CIDRAM['Matrix-Image'], 12, 14, 22, 24, 16711680);
        imagestring($this->CIDRAM['Matrix-Image'], 2, 24, 12, '"Deny" signatures', 16711680);
        imagefilledrectangle($this->CIDRAM['Matrix-Image'], 130, 14, 140, 24, 65280);
        imagestring($this->CIDRAM['Matrix-Image'], 2, 142, 12, '"Whitelist" + "Greylist" signatures', 65280);
        imagefilledrectangle($this->CIDRAM['Matrix-Image'], 356, 14, 366, 24, 255);
        imagestring($this->CIDRAM['Matrix-Image'], 2, 368, 12, '"Run" signatures', 255);

        imagestring($this->CIDRAM['Matrix-Image'], 2, 14, 36, '< 0.0.0.0/8        IPv4      255.0.0.0/8 >', 16777215);
        imagestring($this->CIDRAM['Matrix-Image'], 2, 288, 36, '< 00xx::/8         IPv6         ffxx::/8 >', 16777215);
        imagestringup($this->CIDRAM['Matrix-Image'], 2, -2, 304, '< x.255.0.0/16                x.0.0.0/16 >', 16777215);
        imagestringup($this->CIDRAM['Matrix-Image'], 2, 272, 304, '< xxff::/16                    xx00::/16 >', 16777215);

        $IPv4->iterateCallback('0-255,0-255', $this->matrixDraw, 12, 50);
        $IPv6->iterateCallback('0-255,0-255', $this->matrixDraw, 286, 50);
        $Wheel->iterateCallback('0-24,0-24', $this->matrixDraw, 519, 2);

        if ($CLI) {
            if (!$Destination) {
                $Destination = 'export.png';
            }
            imagepng($this->CIDRAM['Matrix-Image'], $this->Vault . $Destination);
            $Memory = memory_get_usage();
            $this->formatFileSize($Memory);
            echo "\rWorking ... " . $this->NumberFormatter->format(100, 2) . '% (' . $this->timeFormat(time(), $this->Configuration['general']['time_format']) . ') <RAM: ' . $Memory . '>' . "\n\n";
        } else {
            ob_start();
            imagepng($this->CIDRAM['Matrix-Image']);
            $Out = ob_get_contents();
            ob_end_clean();
            return $Out;
        }
        imagedestroy($this->CIDRAM['Matrix-Image']);
        return '';
    }

    /**
     * Recursively replace strings by reference.
     *
     * @param string|array $In The data to be worked with.
     * @param string|array $What What to replace.
     * @param string|array $With What to replace it with.
     * @return void
     */
    private function recursiveReplace(&$In, $What, $With): void
    {
        if (is_string($In)) {
            $In = str_replace($What, $With, $In);
        }
        if (is_array($In)) {
            foreach ($In as &$Item) {
                $this->recursiveReplace($Item, $What, $With);
            }
        }
    }

    /**
     * Fetch L10N from module configuration L10N.
     *
     * @param string $Entry The L10N entry we're trying to fetch.
     * @return string The L10N entry, or an empty string on failure.
    $this->CIDRAM['FromModuleConfigL10N'] = function (string $Entry): string {
        return $this->Configuration['L10N'][$this->Configuration['general']['lang']][$Entry] ?? $this->Configuration['L10N'][$Entry] ?? '';
    };
     */

    /**
     * Split before the line at the specified boundary.
     *
     * @param string $Data The data to split.
     * @param string $Boundary Where to apply the split.
     * @return array The split data.
     */
    private function splitBeforeLine(string $Data, string $Boundary): array
    {
        $Len = strlen($Data);
        if ($Len < 1) {
            return ['', ''];
        }
        if (!strlen($Boundary)) {
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
    }

    /**
     * Isolate the entry of the first field in a block.
     *
     * @param string $Block The block to isolate from.
     * @param string $Boundary The field separator.
     * @return string The isolated entry.
     */
    private function isolateFirstFieldEntry(string $Block, string $Separator): string
    {
        $Segment = '';
        if (($Position = strpos($Block, $Separator)) !== false) {
            $Segment = substr($Block, $Position + strlen($Separator));
            if (($FieldEndPos = strpos($Segment, "\n")) !== false) {
                $Segment = substr($Segment, 0, $FieldEndPos);
            }
        }
        return $Segment ?: '';
    }

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
    private function stepThroughBlocks(string $Data, &$Needle, $End, string $SearchQuery = '', string $Direction = '>'): bool
    {
        /** Guard. */
        if (!is_int($End) || !strlen($Data) || ($Direction !== '<' && $Direction !== '>')) {
            return false;
        }

        /** Directionality. */
        if ($Direction === '>') {
            $StrFunction = 'strpos';
        } else {
            $StrFunction = 'strrpos';
            $End = ((strlen($Data) - $Needle) * -1) - strlen($this->CIDRAM['FE']['BlockSeparator']);
        }

        /** Step with search query. */
        if (strlen($SearchQuery)) {
            return (
                ($Needle = $StrFunction($Data, (
                    $this->CIDRAM['FE']['BlockSeparator'] === "\n\n" ? $this->CIDRAM['FE']['FieldSeparator'] . $SearchQuery . "\n" : $SearchQuery
                ), $End)) !== false ||
                ($Needle = $StrFunction($Data, '("' . $SearchQuery . '", L', $End)) !== false ||
                (strlen($SearchQuery) === 2 && ($Needle = $StrFunction($Data, '[' . $SearchQuery . ']', $End)) !== false)
            );
        }

        /** Step without search query. */
        return (($Needle = $StrFunction($Data, $this->CIDRAM['FE']['BlockSeparator'], $End)) !== false);
    }

    /**
     * Builds a "from" link for pagination navigation.
     *
     * @param string $Label The label for the link.
     * @param string $Needle The needle to be used for the link.
     * @return void
     */
    private function paginationFromLink(string $Label, string $Needle): void
    {
        if (strpos($this->CIDRAM['FE']['BlockLink'], '&from=' . $this->CIDRAM['FE']['From']) !== false) {
            $Link = str_replace('&from=' . $this->CIDRAM['FE']['From'], '&from=' . $Needle, $this->CIDRAM['FE']['BlockLink']);
        } else {
            $Link = $this->CIDRAM['FE']['BlockLink'] . '&from=' . $Needle;
        }
        if (!empty($this->CIDRAM['QueryVars']['search'])) {
            $Link .= '&search=' . $this->CIDRAM['QueryVars']['search'];
        }
        $this->CIDRAM['FE']['SearchInfo'] .= sprintf(' %s <a href="%s">%s</a>', $this->L10N->getString($Label), $Link, $Needle);
    }

    /**
     * Swaps an element in an associative array to the top or the bottom.
     *
     * @param array $Arr The array to be worked.
     * @param string $Target The key of the element to be swapped.
     * @param bool $Direction False for top, true for bottom.
     * @return array The worked array.
     */
    private function swapAssociativeArrayElements(array $Arr, string $Target, bool $Direction): array
    {
        if (!isset($Arr[$Target])) {
            return $Arr;
        }
        $Split = [$Target => $Arr[$Target]];
        unset($Arr[$Target]);
        $Arr = $Direction ? array_merge($Arr, $Split) : array_merge($Split, $Arr);
        return $Arr;
    }

    /**
     * Swaps the position of an element in an associative array up or down by one.
     *
     * @param array $Arr The associative array to be worked.
     * @param string $Target The key of the element to be swapped.
     * @param bool $Direction False for up, true for down.
     * @return array The worked array.
     */
    private function swapAssociativeArrayElementsByOne(array $Arr, string $Target, bool $Direction): array
    {
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
    }

    /**
     * Reconstructs and updates the auxiliary rules data.
     *
     * @return bool True when succeeded.
     */
    private function updateAuxData(): bool
    {
        if (($NewAuxData = $this->YAML->reconstruct($this->CIDRAM['AuxData'])) && strlen($NewAuxData) > 2) {
            $Handle = fopen($this->Vault . 'auxiliary.yml', 'wb');
            if ($Handle !== false) {
                fwrite($Handle, $NewAuxData);
                fclose($Handle);
                return true;
            }
        }
        return false;
    }

    /**
     * Generates a message describing the relative difference between the specified
     * time and the current time.
     *
     * @param int $Time The specified time (unix time).
     * @return string The message.
     */
    private function relativeTime(int $Time): string
    {
        $Time -= $this->Now;
        if ($Time < -31536000) {
            $Time = (int)($Time / -31536000);
            return sprintf(
                $this->L10N->getPlural($Time, 'time_years_ago'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time < -2629800) {
            $Time = (int)($Time / -2629800);
            return sprintf(
                $this->L10N->getPlural($Time, 'time_months_ago'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time < -86400) {
            $Time = (int)($Time / -86400);
            return sprintf(
                $this->L10N->getPlural($Time, 'time_days_ago'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time < -3600) {
            $Time = (int)($Time / -3600);
            return sprintf(
                $this->L10N->getPlural($Time, 'time_hours_ago'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time < -60) {
            $Time = (int)($Time / -60);
            return sprintf(
                $this->L10N->getPlural($Time, 'time_minutes_ago'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time < 0) {
            $Time = (int)($Time * -1);
            return sprintf(
                $this->L10N->getPlural($Time, 'time_seconds_ago'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time > 31536000) {
            $Time = (int)($Time / 31536000);
            return sprintf(
                $this->L10N->getPlural($Time, 'time_years_from_now'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time > 2629800) {
            $Time = (int)($Time / 2629800);
            return sprintf(
                $this->L10N->getPlural($Time, 'time_months_from_now'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time > 86400) {
            $Time = (int)($Time / 86400);
            return sprintf(
                $this->L10N->getPlural($Time, 'time_days_from_now'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time > 3600) {
            $Time = (int)($Time / 3600);
            return sprintf(
                $this->L10N->getPlural($Time, 'time_hours_from_now'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time > 60) {
            $Time = (int)($Time / 60);
            return sprintf(
                $this->L10N->getPlural($Time, 'time_minutes_from_now'),
                $this->NumberFormatter->format($Time)
            );
        }
        $Time = (int)$Time;
        return sprintf(
            $this->L10N->getPlural($Time, 'time_seconds_from_now'),
            $this->NumberFormatter->format($Time)
        );
    }

    /**
     * Replaces labels with corresponding L10N data (if there's any).
     *
     * @param string $Label The actual label.
     * @return void
     */
    private function replaceLabelWithL10n(string &$Label): void
    {
        foreach (['', 'response_', 'label_', 'field_'] as $Prefix) {
            if (isset($this->L10N->Data[$Prefix . $Label])) {
                $Label = $this->L10N->getString($Prefix . $Label);
                return;
            }
        }
    }

    /**
     * Parses an array of L10N data references from L10N data to an array.
     *
     * @param string|array $References The L10N data references.
     * @return array An array of L10N data.
     */
    private function arrayFromL10nToArray($References): array
    {
        if (!is_array($References)) {
            $References = [$References];
        }
        $Out = [];
        foreach ($References as $Reference) {
            if (isset($this->L10N->Data[$Reference])) {
                $Reference = $this->L10N->Data[$Reference];
            }
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
    }
}
