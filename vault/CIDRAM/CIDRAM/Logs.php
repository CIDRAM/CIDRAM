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
 * This file: Methods used by the logs page (last modified: 2023.06.20).
 */

namespace CIDRAM\CIDRAM;

trait Logs
{
    /**
     * Used by the logs viewer to generate a list of the log files contained in a
     * working directory (normally, the vault).
     *
     * @param string $Base The path to the working directory.
     * @param string $Order Whether to sort the list in ascending or descending order.
     * @return array A list of the log files contained in the working directory.
     */
    private function logsRecursiveList(string $Base, string $Order = 'ascending'): array
    {
        $Arr = [];
        $List = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($Base), \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($List as $Item => $List) {
            $ThisName = str_replace("\\", '/', substr($Item, strlen($Base)));
            $Normalised = $ThisName;
            if (!is_file($Item) || !is_readable($Item) || is_dir($Item) || !$this->isLogFile($ThisName, $Normalised)) {
                continue;
            }
            $Arr[$Normalised] = ['Filename' => $ThisName, 'Filesize' => filesize($Item)];
            $this->formatFileSize($Arr[$Normalised]['Filesize']);
        }
        if ($Order === 'ascending') {
            ksort($Arr);
        } elseif ($Order === 'descending') {
            krsort($Arr);
        }
        return $Arr;
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
        $this->CIDRAM['BlockSeparator'] = (strpos($In, "<br />\n<br />\n") !== false) ? "<br />\n<br />\n" : "<br />\n";
        $BlockSeparatorLen = strlen($this->CIDRAM['BlockSeparator']);
        while ($Caret < $Len) {
            $Remainder = $Len - $Caret;
            if ($Remainder < self::MAX_BLOCKSIZE && $Remainder < ini_get('pcre.backtrack_limit')) {
                $Section = substr($In, $Caret) . $this->CIDRAM['BlockSeparator'];
                $Caret = $Len;
            } else {
                $SectionLen = strrpos(substr($In, $Caret, self::MAX_BLOCKSIZE), $this->CIDRAM['BlockSeparator']);
                $Section = substr($In, $Caret, $SectionLen) . $this->CIDRAM['BlockSeparator'];
                $Caret += $SectionLen;
            }

            /** Add code tags. */
            preg_match_all('~(&lt;\?(?:(?!&lt;\?)[^\n])+\?&gt;|<\?(?:(?!<\?)[^\n])+\?>|\{\?(?:(?!\{\?)[^\n])+\?\})~i', $Section, $Parts);
            foreach ($Parts[0] as $ThisPart) {
                if (strlen($ThisPart) > 512 || strpos($ThisPart, "\n") !== false) {
                    continue;
                }
                $Section = str_replace($ThisPart, '<code>' . $ThisPart . '</code>', $Section);
            }

            /** Add label styles. */
            if (preg_match_all('~\n(- .*|(?!：)[^\n:]+)' . $FieldSeparator . '((?:(?!<br />)[^\n])+)~i', $Section, $Parts) && count($Parts[1])) {
                $Parts[1] = array_unique($Parts[1]);
                foreach ($Parts[1] as $ThisPart) {
                    $Section = str_replace(
                        "\n" . $ThisPart . $FieldSeparator,
                        "\n<span class=\"textLabel\">" . $ThisPart . '</span>' . $FieldSeparator,
                        $Section
                    );
                }
            }

            /** Fix bad encoding and add block info search links. */
            if (isset($Parts[2]) && is_array($Parts[2]) && count($Parts[2]) && $BlockSeparatorLen === 14) {
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
                        $FieldSeparator . $ThisPart . ' <a href="' . $this->paginationRemoveFrom($BlockLink) . '&search=' . $Enc . '">»</a>' . "<br />\n" . $Alternate,
                        $Section
                    );
                }
            }

            /** Make satisfied red. */
            $Section = preg_replace('~(?<=' . $FieldSeparator . ')' . $this->L10N->getString('response_satisfied') . '(?=\n|<br />| <a href)~', '<span class="txtRd">' . $this->L10N->getString('response_satisfied') . '</span>', $Section);

            /** Add pair styles. */
            if (preg_match_all('~\n((?:<span class="textLabel">.*|(?!：)[^\n:]+)' . $FieldSeparator . '(?:(?!<br />)[^\n])+)~i', $Section, $Parts) && count($Parts[1])) {
                foreach ($Parts[1] as $ThisPart) {
                    $Section = str_replace("\n" . $ThisPart . "<br />\n", "\n<span class=\"s\">" . $ThisPart . "</span><br />\n", $Section);
                }
            }

            /** Add signature section name search links. */
            if (preg_match_all('~\("([^()"]+)", L~', $Section, $Parts) && count($Parts[1])) {
                $Parts[1] = array_unique($Parts[1]);
                foreach ($Parts[1] as $ThisPart) {
                    $Section = str_replace(
                        '("' . $ThisPart . '", L',
                        '("<a href="' . $this->paginationRemoveFrom($BlockLink) . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '">' . $ThisPart . '</a>", L',
                        $Section
                    );
                }
            }

            /** Add country flags. */
            if (preg_match_all('~\[([A-Z]{2})\]~', $Section, $Parts) && count($Parts[1])) {
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
                        $OuterOpen . '<a href="' . $this->paginationRemoveFrom($BlockLink) . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '">' . $InnerOpen . $ThisPart . $InnerClose . '</a>' . $OuterClose,
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
            $BlockEnd = strpos($Out, $this->CIDRAM['BlockSeparator'], $BlockStart);
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
        if ($In === '') {
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
            if ($this->FE['SortOrder'] === 'descending') {
                arsort($Entries, SORT_NUMERIC);
            } else {
                asort($Entries, SORT_NUMERIC);
            }
            foreach ($Entries as $Entry => $Count) {
                if (!(substr($Entry, 0, 1) === '[' && substr($Entry, 3, 1) === ']')) {
                    $Entry .= ' <a href="' . $this->paginationRemoveFrom($BlockLink) . '&search=' . str_replace('=', '_', base64_encode($Entry)) . '">»</a>';
                }
                preg_match_all('~\("([^()"]+)", L~', $Entry, $Parts);
                if (count($Parts[1])) {
                    foreach ($Parts[1] as $ThisPart) {
                        $Entry = str_replace(
                            '("' . $ThisPart . '", L',
                            '("<a href="' . $this->paginationRemoveFrom($BlockLink) . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '">' . $ThisPart . '</a>", L',
                            $Entry
                        );
                    }
                }
                preg_match_all('~\[([A-Z]{2})\]~', $Entry, $Parts);
                if (count($Parts[1])) {
                    foreach ($Parts[1] as $ThisPart) {
                        $Entry = str_replace('[' . $ThisPart . ']', $this->FE['Flags'] ? (
                            '<a href="' . $this->paginationRemoveFrom($BlockLink) . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '"><span class="flag ' . $ThisPart . '"></span></a>'
                        ) : (
                            '[<a href="' . $this->paginationRemoveFrom($BlockLink) . '&search=' . str_replace('=', '_', base64_encode($ThisPart)) . '">' . $ThisPart . '</a>]'
                        ), $Entry);
                    }
                }
                if (!empty($this->FE['EntryCountPaginated'])) {
                    $Percent = $this->FE['EntryCountPaginated'] ? ($Count / $this->FE['EntryCountPaginated']) * 100 : 0;
                } elseif (!empty($this->FE['EntryCount'])) {
                    $Percent = $this->FE['EntryCount'] ? ($Count / $this->FE['EntryCount']) * 100 : 0;
                } elseif (!empty($this->FE['EntryCountBefore'])) {
                    $Percent = $this->FE['EntryCountBefore'] ? ($Count / $this->FE['EntryCountBefore']) * 100 : 0;
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
            $End = ((strlen($Data) - $Needle) * -1) - strlen($this->CIDRAM['BlockSeparator']);
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
                    $this->CIDRAM['BlockSeparator'] === "\n\n" ? $this->FE['FieldSeparator'] . $SearchQuery . "\n" : $SearchQuery
                ), $End)) !== false ||
                ($Needle = $StrFunction($Data, '("' . $SearchQuery . '", L', $End)) !== false ||
                (strlen($SearchQuery) === 2 && ($Needle = $StrFunction($Data, '[' . $SearchQuery . ']', $End)) !== false)
            );
        }

        /** Step without search query. */
        return (($Needle = $StrFunction($Data, $this->CIDRAM['BlockSeparator'], $End)) !== false);
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
        $From = urlencode($this->FE['From']);
        $URLNeedle = urlencode($Needle);
        if (strpos($this->FE['BlockLink'], '&from=' . $From) !== false) {
            $Link = str_replace('&from=' . $From, '&from=' . $URLNeedle, $this->FE['BlockLink']);
        } else {
            $Link = $this->FE['BlockLink'] . '&from=' . $URLNeedle;
        }
        if (!empty($this->CIDRAM['QueryVars']['search'])) {
            $Link .= '&search=' . urlencode($this->CIDRAM['QueryVars']['search']);
        }
        $this->FE['SearchInfo'] .= sprintf(' %s <a href="%s">%s</a>', $this->L10N->getString($Label), $Link, $Needle);
    }

    /**
     * Removes the "from" parameter for log filtering.
     *
     * @param string $Link The link to clean.
     * @return string The cleaned link.
     */
    private function paginationRemoveFrom(string $Link): string
    {
        return preg_replace(['~\?from=[^&]+~i', '~&from=[^&]+~i'], ['?', ''], $Link);
    }

    /**
     * Replace array keys according to a supplied closure/callable.
     *
     * @param array $Arr The array from which to replace keys.
     * @param callable $Perform The closure/callable to use to determine the replacement key.
     * @return array The array with replaced keys.
     */
    private function arrayReplaceKeys(array $Arr, callable $Perform): array
    {
        $Out = [];
        foreach ($Arr as $Item) {
            $NewKey = $Perform($Item);
            if (is_string($NewKey) || is_int($NewKey)) {
                $Out[$NewKey] = $Item;
            }
        }
        return $Out;
    }
}
