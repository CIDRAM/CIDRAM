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
 * This file: General methods used by the front-end (last modified: 2022.11.05).
 */

namespace CIDRAM\CIDRAM;

trait FrontEndMethods
{
    /**
     * Get a limited subset of all available cache entries.
     *
     * @param string $Pattern The pattern for which entries to return.
     * @param string $Replacement An optional replacement for entry names.
     * @param ?callable $Sort An optional callable to sort entries.
     * @return array An array of matching entries.
     */
    public function getAllEntriesWhere(string $Pattern, string $Replacement = '', ?callable $Sort = null): array
    {
        $Out = [];
        foreach ($this->Cache->getAllEntries() as $EntryName => $EntryData) {
            if (isset($EntryData['Time']) && $EntryData['Time'] > 0 && $EntryData['Time'] < $this->Now) {
                continue;
            }
            if (preg_match($Pattern, $EntryName)) {
                if ($Replacement !== '') {
                    $EntryName = preg_replace($Pattern, $Replacement, $EntryName);
                }
                $Out[$EntryName] = $EntryData;
            }
        }
        if ($Sort !== null && is_callable($Sort)) {
            uasort($Out, $Sort);
        }
        return $Out;
    }

    /**
     * Format filesize information.
     *
     * @param int $Filesize
     * @return void
     */
    private function formatFileSize(int &$Filesize): void
    {
        $Scale = ['field_size_bytes', 'field_size_KB', 'field_size_MB', 'field_size_GB', 'field_size_TB', 'field_size_PB'];
        $Iterate = 0;
        while ($Filesize > 1024) {
            $Filesize /= 1024;
            $Iterate++;
            if ($Iterate > 4) {
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
        $List = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($Base, \RecursiveDirectoryIterator::FOLLOW_SYMLINKS), \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($List as $Item => $List) {
            $Key++;
            $ThisName = substr($Item, $Offset);
            if (preg_match('~^(?:/\.\.|./\.|\.{3})$~', str_replace("\\", '/', substr($Item, -3)))) {
                continue;
            }
            $Arr[$Key] = ['Filename' => $this->canonical($ThisName), 'CanEdit' => false];
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
                if (isset($this->FE['TotalSize'])) {
                    $this->FE['TotalSize'] += $Arr[$Key]['Filesize'];
                }
                if (isset($this->Components['Components'])) {
                    $Component = $this->L10N->getString('field_filetype_unknown');
                    if (isset($this->Components['Files'][$Arr[$Key]['Filename']])) {
                        $Component = $this->Components['Names'][$this->Components['Files'][$Arr[$Key]['Filename']]] ?? $this->Components['Files'][$Arr[$Key]['Filename']];
                    } elseif (preg_match('~(?:[^|/]\.ht|\.safety$|^salt\.dat$)~i', $Arr[$Key]['Filename'])) {
                        $Component = $this->L10N->getString('label_fmgr_safety');
                    } elseif (preg_match('~config\.yml$~i', $Arr[$Key]['Filename'])) {
                        $Component = $this->L10N->getString('link_config');
                    } elseif ($this->isLogFile($Arr[$Key]['Filename'])) {
                        $Component = $this->L10N->getString('link_logs');
                    } elseif ($Arr[$Key]['Filename'] === 'auxiliary.yml') {
                        $Component = $this->L10N->getString('link_aux');
                    } elseif (preg_match('/(?:^ignore\.dat|_custom\.dat|\.sig|\.inc)$/i', $Arr[$Key]['Filename'])) {
                        $Component = $this->L10N->getString('label_fmgr_other_sig');
                    } elseif (preg_match('~(?:\.tmp|\.rollback|^(?:cache|hashes|ipbypass|rl)\.dat)$~i', $Arr[$Key]['Filename'])) {
                        $Component = $this->L10N->getString('label_fmgr_cache_data');
                    } elseif ($Arr[$Key]['Filename'] === 'installed.yml') {
                        $Component = $this->L10N->getString('label_fmgr_updates_metadata');
                    }
                    if (!isset($this->Components['Components'][$Component])) {
                        $this->Components['Components'][$Component] = 0;
                    }
                    $this->Components['Components'][$Component] += $Arr[$Key]['Filesize'];
                    if (!isset($this->Components['ComponentFiles'][$Component])) {
                        $this->Components['ComponentFiles'][$Component] = [];
                    }
                    $this->Components['ComponentFiles'][$Component][$Arr[$Key]['Filename']] = $Arr[$Key]['Filesize'];
                }
                if (($ExtDel = strrpos($Item, '.')) !== false) {
                    $Ext = strtoupper(substr($Item, $ExtDel + 1));
                    if ($Ext === '') {
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
     * Determine the final IP address covered by an IPv4 CIDR. This method is
     * used by the range calculator.
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
     * Determine the final IP address covered by an IPv6 CIDR. This method is
     * used by the range calculator.
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
     * Get the appropriate path for a specified asset as per the defined theme.
     *
     * @param string $Asset The asset filename.
     * @param bool $CanFail Is failure acceptable? (Default: False)
     * @throws Exception if the asset can't be found.
     * @return string The asset path.
     */
    private function getAssetPath(string $Asset, bool $CanFail = false): string
    {
        if (file_exists($this->AssetsPath . 'frontend/' . $this->Configuration['frontend']['theme'] . '/' . $Asset)) {
            return $this->AssetsPath . 'frontend/' . $this->Configuration['frontend']['theme'] . '/' . $Asset;
        }
        if (file_exists($this->AssetsPath . 'frontend/default/' . $Asset)) {
            return $this->AssetsPath . 'frontend/default/' . $Asset;
        }
        if (file_exists($this->AssetsPath . 'frontend/' . $Asset)) {
            return $this->AssetsPath . 'frontend/' . $Asset;
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
            $this->FE[$Switch] = empty($this->CIDRAM['QueryVars'][$Switch]) ? false : (
                ($this->CIDRAM['QueryVars'][$Switch] === 'true' && !$State) ||
                ($this->CIDRAM['QueryVars'][$Switch] !== 'true' && $State)
            );
            if ($State) {
                $StateModified = true;
            }
            if ($this->FE[$Switch]) {
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
        $this->FE['SL_Signatures'] = 0;
        $this->FE['SL_Sections'] = 0;
        $this->FE['SL_Files'] = count($Files);
        $this->FE['SL_Unique'] = 0;
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
                    $this->FE['SL_Sections']++;
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
                $this->FE['SL_Signatures']++;
            }
        }
        $Class = 'ng2';
        ksort($SectionMeta);
        $this->FE['SL_Unique'] = count($SectionMeta);
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
                $OriginDisplay = '<code>' . $Origin . '</code>' . ($this->FE['Flags'] ? ' – <span class="flag ' . $Origin . '"></span>' : '');
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
        $this->FE['FE_Title'] = 'CIDRAM – ' . $Title;

        /** Fetch and prepare username. */
        if ($Username = (empty($this->FE['User']) ? '' : $this->FE['User'])) {
            $Username = preg_replace('~^([^<>]+)<[^<>]+>$~', '\1', $Username);
            if (($AtChar = strpos($Username, '@')) !== false) {
                $Username = substr($Username, 0, $AtChar);
            }
        }

        /** Prepare page tooltip/description. */
        $this->FE['FE_Tip'] = $this->parseVars(['username' => $Username], $Tips);

        /** Load main front-end JavaScript data. */
        $this->FE['JS'] = $JS ? $this->readFile($this->getAssetPath('scripts.js')) : '';
    }

    /**
     * Send page output for front-end pages (plus some other final prepwork).
     *
     * @return string Page output.
     */
    private function sendOutput(): string
    {
        if ($this->FE['JS']) {
            $this->FE['JS'] = "\n<script type=\"text/javascript\">" . $this->FE['JS'] . '</script>';
        }
        $Template = $this->FE['Template'];
        $Labels = [];
        $Segments = [];
        if (isset($this->FE['UserState']) && $this->FE['UserState'] === 1) {
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
        return $this->parseVars(array_merge($this->L10N->Data, $this->FE), $Template);
    }

    /**
     * Confirm whether a file is a log file (used by the file manager and the
     * logs page).
     *
     * @param string $File The path/name of the file to be confirmed.
     * @return bool True if it's a log file; False if it isn't.
     */
    private function isLogFile(string $File): bool
    {
        $FileLC = strtolower($File);
        if (preg_match('~\.log(?:\.gz)?$~', strtolower($FileLC))) {
            return true;
        }
        if ($this->Events->assigned('isLogFile')) {
            $this->Events->fireEvent('isLogFile');
            $this->Events->destroyEvent('isLogFile');
        }
        if (!isset($this->CIDRAM['LogPatterns']) || !is_array($this->CIDRAM['LogPatterns'])) {
            return false;
        }
        foreach ($this->CIDRAM['LogPatterns'] as $LogPattern) {
            if (preg_match($LogPattern, $FileLC)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Generates JavaScript prompts for confirmation front-end actions (used by
     * the IP tracking and statistics pages).
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
     * A quicker way to add entries to the front-end logs file.
     *
     * @param string $IPAddr The IP address triggering the log event.
     * @param string $User The user triggering the log event.
     * @param string $Message The message to be logged.
     * @return void
     */
    private function frontendLogger(string $IPAddr, string $User, string $Message): void
    {
        /** Guard. */
        if (
            empty($this->FE['DateTime']) ||
            $this->Configuration['frontend']['frontend_log'] === '' ||
            !($File = $this->buildPath($this->Vault . $this->Configuration['frontend']['frontend_log']))
        ) {
            return;
        }

        $Data = $this->Configuration['legal']['pseudonymise_ip_addresses'] ? $this->pseudonymiseIp($IPAddr) : $IPAddr;
        $Data .= ' - ' . $this->FE['DateTime'] . ' - "' . $User . '" - ' . $Message . "\n";

        $Truncate = $this->readBytes($this->Configuration['logging']['truncate']);
        $WriteMode = (!file_exists($File) || $Truncate > 0 && filesize($File) >= $Truncate) ? 'wb' : 'ab';
        $Handle = fopen($File, $WriteMode);
        fwrite($Handle, $Data);
        fclose($Handle);
        if ($WriteMode === 'wb') {
            $this->logRotation($this->Configuration['frontend']['frontend_log']);
        }
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
     * Generate a clickable list from an array (used by the cache data page).
     *
     * @param array $Arr The array to convert from.
     * @param string $DeleteKey The key to use for async calls to delete a cache entry.
     * @param int $Depth Current cache entry list depth.
     * @param string $ParentKey An optional key of the parent data source.
     * @return string The generated clickable list.
     */
    private function arrayToClickableList(array $Arr = [], string $DeleteKey = '', int $Depth = 0, string $ParentKey = ''): string
    {
        if ($Depth === 0) {
            $this->CIDRAM['ListGroups'] = [];
            $NewArr = [];
            foreach ($Arr as $Key => $Value) {
                $Matches = [];
                if (preg_match('~^([^-]+)-(.+)$~', $Key, $Matches) && !isset($Arr[$Matches[1]])) {
                    if (!isset($NewArr[$Matches[1]])) {
                        $NewArr[$Matches[1]] = [];
                        $this->CIDRAM['ListGroups'][$Matches[1]] = true;
                    }
                    $NewArr[$Matches[1]][$Matches[2]] = $Value;
                    continue;
                }
                $NewArr[$Key] = $Value;
            }
            $Arr = $NewArr;
            unset($NewArr);
        }
        $Output = '';
        $Count = count($Arr);
        foreach ($Arr as $Key => $Value) {
            if (is_string($Value) && !$this->Demojibakefier->checkConformity($Value)) {
                continue;
            }
            if ($Depth === 1 && isset($this->CIDRAM['ListGroups'][$ParentKey])) {
                $Delete = sprintf(
                    ' – (<span style="cursor:pointer" onclick="javascript:%s(\'%s\')"><code class="s"><span class="txtRd">⌧</span>%s</code></span>)',
                    $DeleteKey,
                    addslashes($ParentKey . '-' . $Key),
                    $this->L10N->getString('field_delete')
                );
                $Output .= '<span id="' . $ParentKey . '-' . $Key . 'Container">';
            } elseif ($Depth === 0) {
                $Delete = sprintf(
                    ' – (<span style="cursor:pointer" onclick="javascript:%s(\'%s\')"><code class="s"><span class="txtRd">⌧</span>%s</code></span>)',
                    $DeleteKey,
                    (isset($this->CIDRAM['ListGroups'][$Key]) ? '^' : '') . addslashes($Key),
                    $this->L10N->getString('field_delete')
                );
                $Output .= '<span id="' . $Key . 'Container">';
            } else {
                $Delete = '';
            }
            $Output .= '<li>';
            if (is_string($Value)) {
                if (substr($Value, 0, 2) === '{"' && substr($Value, -2) === '"}') {
                    $Try = json_decode($Value, true);
                    if ($Try !== null) {
                        $Value = $Try;
                    }
                } elseif (
                    preg_match('~\.ya?ml$~i', $Key) ||
                    (preg_match('~^(?:Data|\d+)$~', $Key) && preg_match('~\.ya?ml$~i', $ParentKey))
                ) {
                    $Try = [];
                    if ($this->YAML->process($Value, $Try) && !empty($Try)) {
                        $Value = $Try;
                    }
                } elseif (substr($Value, 0, 2) === '["' && substr($Value, -2) === '"]' && strpos($Value, '","') !== false) {
                    $Value = explode('","', substr($Value, 2, -2));
                }
            }
            if (is_array($Value)) {
                if ($Depth === 0 || ($Depth === 1 && isset($this->CIDRAM['ListGroups'][$ParentKey]))) {
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
                $Output .= $this->arrayToClickableList($Value, $DeleteKey, $Depth + 1, $Key) . '</ul>';
            } else {
                if ($Key === 'Time' && preg_match('~^\d+$~', $Value)) {
                    $Key = $this->L10N->getString('label_expires');
                    $Value = $this->timeFormat($Value, $this->Configuration['general']['time_format']);
                }
                $Class = ($Key === $this->L10N->getString('field_size') || $Key === $this->L10N->getString('label_expires')) ? 'txtRd' : 's';
                $Text = ($Count === 1 && $Key === 0) ? $Value : $Key . ($Class === 's' ? ' => ' : ' ') . $Value;
                $Output .= '<code class="' . $Class . '" style="word-wrap:break-word;word-break:break-all">' . $this->ltrInRtf(
                    str_replace(['<', '>'], ['&lt;', '&gt;'], $Text)
                ) . '</code>' . $Delete;
            }
            $Output .= '</li>';
            if ($Depth === 1 && isset($this->CIDRAM['ListGroups'][$ParentKey])) {
                $Output .= '</span>';
            } elseif ($Depth === 0) {
                $Output .= '<br /></span>';
            }
        }
        if ($Depth === 0) {
            unset($this->CIDRAM['ListGroups']);
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
            !isset($this->L10N->Data['Text Direction']) ||
            $this->L10N->Data['Text Direction'] !== 'rtl'
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
                $Aggregator = new Aggregator($Format);
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
                $Aggregator = new Aggregator($Format);
                return trim($Aggregator->aggregate($Minuend));
            }
        );
        $StrObject->iterateClosure(function (string $Data): string {
            return "\n" . $Data;
        }, true);
        return trim($StrObject->recompile());
    }

    /**
     * Generates a message describing the relative difference between the
     * specified time and the current time (used by the IP tracking and
     * auxiliary rules pages).
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
     * Update the configuration.
     *
     * @return bool Whether succeeded or failed.
     */
    private function updateConfiguration(): bool
    {
        if (!is_file($this->FE['ActiveConfigFile']) || !is_writable($this->FE['ActiveConfigFile'])) {
            return false;
        }
        $Reconstructed = $this->YAML->reconstruct($this->Configuration);
        $Handle = fopen($this->FE['ActiveConfigFile'], 'wb');
        if (!is_resource($Handle)) {
            return false;
        }
        $Err = fwrite($Handle, $Reconstructed);
        fclose($Handle);
        return $Err !== false;
    }

    /**
     * Get path from component type.
     *
     * @param string $Type "ipv4", "ipv6", "modules", "imports", or "events".
     * @return string The path.
     */
    private function pathFromComponentType(string $Type): string
    {
        if ($Type === 'ipv4' || $Type === 'ipv6') {
            return $this->SignaturesPath;
        }
        if ($Type === 'modules') {
            return $this->ModulesPath;
        }
        if ($Type === 'imports') {
            return $this->ImportsPath;
        }
        if ($Type === 'events') {
            return $this->EventsPath;
        }
        return $this->Vault;
    }
}
