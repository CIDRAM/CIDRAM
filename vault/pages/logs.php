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
 * This file: The logs page (last modified: 2023.12.12).
 */

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'logs' || $this->FE['Permissions'] <= 0) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.Logs'), $this->L10N->getString('tip.Logs'));

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_logs.html')), true);

/** Sort order */
$this->FE['SortOrder'] = (empty($this->CIDRAM['QueryVars']['sortOrder']) || $this->CIDRAM['QueryVars']['sortOrder'] === 'ascending') ? 'ascending' : 'descending';

/** Initialise array for fetching logs data. */
$this->FE['LogFiles'] = ['Files' => $this->arrayReplaceKeys($this->logsRecursiveList($this->Vault, $this->FE['SortOrder']), function (array $Item): string {
    return $Item['Filename'] ?? '';
}), 'Out' => ''];

$this->FE['SearchInfo'] = '';
$this->FE['SearchQuery'] = '';

/** Default field separator. */
$this->FE['FieldSeparator'] = ': ';

/** Add flags CSS. */
if ($this->FE['Flags'] = file_exists($this->AssetsPath . 'frontend/flags.css')) {
    $this->FE['OtherHead'] .= "\n  <link rel=\"stylesheet\" type=\"text/css\" href=\"?cidram-page=flags\" />";
}

/** How to display the log data? */
if (!isset($this->CIDRAM['QueryVars']['textMode'])) {
    $this->FE['TextModeLinks'] = 'simple';
} elseif ($this->CIDRAM['QueryVars']['textMode'] === 'fancy') {
    $this->FE['TextModeLinks'] = 'fancy';
} elseif ($this->CIDRAM['QueryVars']['textMode'] === 'tally') {
    $this->FE['TextModeLinks'] = 'tally';
} else {
    $this->FE['TextModeLinks'] = 'simple';
}

/** Remember display preferences? */
$this->FE['Remember'] = isset($this->CIDRAM['QueryVars']['remember']) && $this->CIDRAM['QueryVars']['remember'] === 'on';

/** Paginate entries? */
$this->FE['Paginate'] = isset($this->CIDRAM['QueryVars']['paginate']) && $this->CIDRAM['QueryVars']['paginate'] === 'on';

/** Entries per page. */
$this->FE['PerPage'] = ($this->FE['Paginate'] && isset($this->CIDRAM['QueryVars']['perpage'])) ? (int)$this->CIDRAM['QueryVars']['perpage'] : 20;

/** Which entry to start from. */
$this->FE['From'] = ($this->FE['Paginate'] && isset($this->CIDRAM['QueryVars']['from'])) ? $this->CIDRAM['QueryVars']['from'] : '';

/** The first entry for the next pagination page. */
$this->FE['Next'] = '';

/** The first entry for the previous pagination page. */
$this->FE['Previous'] = '';

/** Define query for search filters. */
$this->FE['BlockLink'] = sprintf(
    '?cidram-page=logs&textMode=%s&sortOrder=%s%s%s%s%s%s',
    $this->FE['TextModeLinks'],
    $this->FE['SortOrder'],
    $this->FE['Remember'] ? '&remember=on' : '',
    $this->FE['Paginate'] ? '&paginate=on' : '',
    $this->FE['PerPage'] > 0 && $this->FE['PerPage'] !== 20 ? '&perpage=' . $this->FE['PerPage'] : '',
    $this->FE['From'] ? '&from=' . urlencode($this->FE['From']) : '',
    empty($this->CIDRAM['QueryVars']['logfile']) ? '' : '&logfile=' . $this->CIDRAM['QueryVars']['logfile']
);

/** Remember search filters. */
if ($this->FE['Remember'] && $this->FE['BlockLink'] !== $this->FE['CachedLogsLink']) {
    $this->Cache->setEntry('CachedLogsLink-' . $this->FE['User'], $this->FE['BlockLink'], 31536000);
    $this->FE['FE_Content'] = str_replace(
        ' href="' . $this->FE['CachedLogsLink'] . '">',
        ' href="' . $this->FE['BlockLink'] . '">',
        $this->FE['FE_Content']
    );
}

/** Define log data. */
if (empty($this->CIDRAM['QueryVars']['logfile'])) {
    $this->FE['logfileData'] = $this->L10N->getString('label.No log file selected');
} elseif (!isset($this->FE['LogFiles']['Files'][$this->CIDRAM['QueryVars']['logfile']])) {
    $this->FE['logfileData'] = $this->L10N->getString('label.Selected log file doesn_t exist');
} else {
    if (strtolower(substr($this->CIDRAM['QueryVars']['logfile'], -3)) === '.gz') {
        $GZLogHandler = gzopen($this->Vault . $this->CIDRAM['QueryVars']['logfile'], 'rb');
        $this->FE['logfileData'] = '';
        if (is_resource($GZLogHandler)) {
            while (!gzeof($GZLogHandler)) {
                $this->FE['logfileData'] .= gzread($GZLogHandler, self::FILE_BLOCKSIZE);
            }
            gzclose($GZLogHandler);
        }
        unset($GZLogHandler);
    } else {
        $this->FE['logfileData'] = $this->readFile($this->Vault . $this->CIDRAM['QueryVars']['logfile']);
    }
    if (strpos($this->FE['logfileData'], '：') !== false) {
        $this->FE['FieldSeparator'] = '：';
    }

    $this->CIDRAM['BlockSeparator'] = (strpos($this->FE['logfileData'], "\n\n") !== false) ? "\n\n" : "\n";
    $BlockSepLen = strlen($this->CIDRAM['BlockSeparator']);

    /** Strip PHP header. */
    if (substr($this->FE['logfileData'], 0, 15) === "\x3C\x3Fphp die; \x3F\x3E\n\n") {
        $this->FE['logfileData'] = substr($this->FE['logfileData'], 15);
    }

    /** Reverse entries order for viewing descending entries. */
    if ($this->FE['SortOrder'] === 'descending') {
        $this->FE['logfileData'] = explode($this->CIDRAM['BlockSeparator'], $this->FE['logfileData']);
        $this->FE['logfileData'] = implode($this->CIDRAM['BlockSeparator'], array_reverse($this->FE['logfileData']));
        if (substr($this->FE['logfileData'], 0, $BlockSepLen) === $this->CIDRAM['BlockSeparator']) {
            $this->FE['logfileData'] = substr($this->FE['logfileData'], $BlockSepLen) . $this->CIDRAM['BlockSeparator'];
        }
    }

    /** Determine entries count before and search query. */
    if (empty($this->CIDRAM['QueryVars']['search'])) {
        $this->FE['SearchQuery'] = '';
        $this->FE['EntryCountBefore'] = !str_replace("\n", '', $this->FE['logfileData']) ? 0 : (
            substr_count($this->FE['logfileData'], "\n\n") ?: substr_count($this->FE['logfileData'], "\n")
        );
    } else {
        $this->FE['SearchQuery'] = base64_decode(str_replace('_', '=', $this->CIDRAM['QueryVars']['search']));
        $this->FE['EntryCountBefore'] = 0;
    }

    /** Handle pagination lower boundary. */
    if ($this->FE['Paginate']) {
        $this->FE['logfileData'] = $this->splitBeforeLine($this->FE['logfileData'], $this->FE['From']);
        $this->FE['EstAft'] = substr_count($this->FE['logfileData'][0], $this->CIDRAM['BlockSeparator']);
        $this->FE['EstFore'] = substr_count($this->FE['logfileData'][1], $this->CIDRAM['BlockSeparator']);
        $Needle = strlen($this->FE['logfileData'][0]);
        $Iterations = 0;
        while ($this->stepThroughBlocks($this->FE['logfileData'][0], $Needle, 0, $this->FE['SearchQuery'], '<')) {
            if ($this->FE['SearchQuery'] !== '') {
                $this->stepThroughBlocks($this->FE['logfileData'][0], $Needle, 0, '', '<');
            }
            $Iterations++;
            if (!empty($this->CIDRAM['QueryVars']['search'])) {
                $this->FE['EntryCountBefore']++;
            }
            if (($Iterations > $this->FE['PerPage']) && !$this->FE['Previous']) {
                $this->FE['Previous'] = $this->isolateFirstFieldEntry(
                    substr($this->FE['logfileData'][0], $Needle + $BlockSepLen),
                    $this->FE['FieldSeparator']
                );
            }
        }
        if (!$this->FE['Previous']) {
            $this->FE['Previous'] = $this->isolateFirstFieldEntry(
                $this->FE['logfileData'][0],
                $this->FE['FieldSeparator']
            );
        }
        if ($this->FE['From'] === '') {
            $this->FE['From'] = $this->isolateFirstFieldEntry(
                $this->FE['logfileData'][1],
                $this->FE['FieldSeparator']
            );
        }
        if ($this->FE['Previous'] === $this->FE['From']) {
            $this->FE['Previous'] = '';
        }
        $this->FE['logfileData'] = $this->FE['logfileData'][1];
        unset($Iterations);
    }

    /** Pagination counter. */
    $this->FE['Paginated'] = 1;

    /** Handle block filtering. */
    if (!empty($this->FE['logfileData']) && !empty($this->CIDRAM['QueryVars']['search'])) {
        $NewLogFileData = '';
        $Needle = 0;
        $BlockEnd = 0;
        $this->FE['EntryCountPaginated'] = 0;
        while ($this->stepThroughBlocks($this->FE['logfileData'], $Needle, $BlockEnd, $this->FE['SearchQuery'])) {
            $this->FE['EntryCountBefore']++;
            $BlockStart = strrpos(substr($this->FE['logfileData'], 0, $Needle), $this->CIDRAM['BlockSeparator'], $BlockEnd);
            $BlockEnd = strpos($this->FE['logfileData'], $this->CIDRAM['BlockSeparator'], $Needle);
            if ($this->FE['Paginate']) {
                if ($this->FE['From'] === '') {
                    $this->FE['From'] = $this->isolateFirstFieldEntry(
                        substr($this->FE['logfileData'], $BlockStart, $BlockEnd - $BlockStart),
                        $this->FE['FieldSeparator']
                    );
                }
                $this->FE['Paginated']++;
                if ($this->FE['Paginated'] > ($this->FE['PerPage'] + 1)) {
                    if (!$this->FE['Next']) {
                        $this->FE['Next'] = $this->isolateFirstFieldEntry(
                            substr($this->FE['logfileData'], $BlockStart, $BlockEnd - $BlockStart),
                            $this->FE['FieldSeparator']
                        );
                    }
                    continue;
                }
                $this->FE['EntryCountPaginated']++;
                $NewLogFileData .= substr($this->FE['logfileData'], $BlockStart, $BlockEnd - $BlockStart);
            } else {
                $NewLogFileData .= substr($this->FE['logfileData'], $BlockStart, $BlockEnd - $BlockStart);
            }
        }
        $this->FE['logfileData'] = rtrim($NewLogFileData) . $this->CIDRAM['BlockSeparator'];
        unset($Needle, $this->CIDRAM['BlockSeparator'], $BlockEnd, $BlockStart, $NewLogFileData);
        $this->FE['SearchInfoRender'] = (
            $this->FE['Flags'] && preg_match('~^[A-Z]{2}$~', $this->FE['SearchQuery'])
        ) ? '<span class="flag ' . $this->FE['SearchQuery'] . '"><span></span></span>' : '<code>' . $this->FE['SearchQuery'] . '</code>';
        if ($this->FE['Paginate']) {
            if (($TryRange = $this->FE['EstAft'] + $this->FE['PerPage']) > $this->FE['EntryCountBefore']) {
                $TryRange = $this->FE['EntryCountBefore'];
            }
            if ($this->FE['EstAft'] > $TryRange) {
                $this->FE['EstAft'] = $TryRange - $this->FE['EntryCountBefore'];
            }
            $this->FE['SearchInfo'] = '<br />' . sprintf(
                $this->L10N->getPlural($this->FE['EntryCountBefore'], 'label.Displaying %s entry that cites %s'),
                '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['EstAft'] + 1) .
                '-' . $this->NumberFormatter->format($TryRange) . '</span>' .
                '<span class="txtBl">(</span><span class="txtRd">' . $this->NumberFormatter->format($this->FE['EntryCountPaginated']) . '</span><span class="txtBl">)/</span>' .
                '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['EntryCountBefore']) . '</span>',
                $this->FE['SearchInfoRender']
            );
            if ($this->FE['From']) {
                $this->FE['SearchInfo'] .= '<br />' . sprintf(
                    $this->L10N->getString('label.Starting from %s'),
                    '<span class="txtRd">' . $this->FE['From'] . '</span>'
                );
                if ($this->FE['Previous']) {
                    $this->paginationFromLink('label.Previous', $this->FE['Previous']);
                }
                if ($this->FE['Next']) {
                    $this->paginationFromLink('label.Next', $this->FE['Next']);
                }
                if (isset($this->FE['EstAft'])) {
                    $this->FE['EstWidth'] = floor((($TryRange - $this->FE['EstAft']) / ($this->FE['EntryCountBefore'] ?: 1)) * 10000) / 100;
                    $this->FE['EstAft'] = floor(($this->FE['EstAft'] / ($this->FE['EntryCountBefore'] ?: 1)) * 10000) / 100;
                    if ($this->FE['EstAft'] >= 100) {
                        $this->FE['EstAft'] = 0;
                        $this->FE['EstWidth'] = 100;
                    }
                    $this->FE['SearchInfo'] .= sprintf(
                        '<br /><div style="width:100%%;height:2px;overflow:visible;background-color:rgba(0,192,0,.4);margin:1px 0 1px 0">' .
                        '<div style="position:relative;%s:%s%%;top:-1px;width:%s%%;height:4px;overflow:visible;background-color:rgba(192,0,0,.5);margin:0"></div></div>',
                        $this->FE['FE_Align'],
                        $this->FE['EstAft'],
                        $this->FE['EstWidth']
                    );
                }
            }
        } else {
            $this->FE['SearchInfo'] = '<br />' . sprintf(
                $this->L10N->getPlural($this->FE['EntryCountBefore'], 'label.Displaying %s entry that cites %s'),
                '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['EntryCountBefore']) . '</span>',
                $this->FE['SearchInfoRender']
            );
        }
    } else {
        if ($this->FE['Paginate']) {
            $NewLogFileData = '';
            $OriginalLogDataLen = strlen($this->FE['logfileData']);
            $BlockStart = 0;
            $BlockEnd = 0;
            if ($this->FE['From'] === '') {
                $this->FE['From'] = $this->isolateFirstFieldEntry(
                    $this->FE['logfileData'],
                    $this->FE['FieldSeparator']
                );
            }
            while (true) {
                $BlockOffset = $BlockStart + $BlockSepLen;
                if ($BlockOffset >= $OriginalLogDataLen) {
                    break;
                }
                $BlockEnd = strpos($this->FE['logfileData'], $this->CIDRAM['BlockSeparator'], $BlockOffset);
                if ($BlockEnd === false) {
                    break;
                }
                $NewLogFileData .= substr($this->FE['logfileData'], $BlockStart, ($BlockEnd - $BlockStart) + $BlockSepLen);
                $this->FE['Paginated']++;
                if ($this->FE['Paginated'] > $this->FE['PerPage']) {
                    if (!$this->FE['Next']) {
                        $this->FE['Next'] = $this->isolateFirstFieldEntry(
                            substr($this->FE['logfileData'], $BlockEnd + $BlockSepLen),
                            $this->FE['FieldSeparator']
                        );
                    }
                    break;
                }
                $BlockStart = $BlockEnd + $BlockSepLen;
            }
            $this->FE['logfileData'] = $NewLogFileData;
            unset($BlockOffset, $BlockSepLen, $this->CIDRAM['BlockSeparator'], $BlockEnd, $BlockStart, $OriginalLogDataLen, $NewLogFileData);
        }
        $this->FE['EntryCount'] = !str_replace("\n", '', $this->FE['logfileData']) ? 0 : (
            substr_count($this->FE['logfileData'], "\n\n") ?: substr_count($this->FE['logfileData'], "\n")
        );
        if ($this->FE['Paginate']) {
            if (($TryRange = $this->FE['EstAft'] + $this->FE['PerPage']) > $this->FE['EntryCountBefore']) {
                $TryRange = $this->FE['EntryCountBefore'];
            }
            if ($this->FE['EstAft'] > $TryRange) {
                $this->FE['EstAft'] = $TryRange - $this->FE['EntryCountBefore'];
            }
            $this->FE['SearchInfo'] = '<br />' . sprintf(
                $this->L10N->getPlural($this->FE['EntryCountBefore'], 'label.Displaying %s entry'),
                '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['EstAft'] + 1) .
                '-' . $this->NumberFormatter->format($TryRange) . '</span>' .
                '<span class="txtBl">(</span><span class="txtRd">' . $this->NumberFormatter->format($this->FE['EntryCount']) . '</span><span class="txtBl">)/</span>' .
                '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['EntryCountBefore']) . '</span>'
            );
            if ($this->FE['From']) {
                $this->FE['SearchInfo'] .= '<br />' . sprintf(
                    $this->L10N->getString('label.Starting from %s'),
                    '<span class="txtRd">' . $this->FE['From'] . '</span>'
                );
                if ($this->FE['Previous']) {
                    $this->paginationFromLink('label.Previous', $this->FE['Previous']);
                }
                if ($this->FE['Next']) {
                    $this->paginationFromLink('label.Next', $this->FE['Next']);
                }
                if (isset($this->FE['EstAft'])) {
                    $this->FE['EstWidth'] = floor((($TryRange - $this->FE['EstAft']) / ($this->FE['EntryCountBefore'] ?: 1)) * 10000) / 100;
                    $this->FE['EstAft'] = floor(($this->FE['EstAft'] / ($this->FE['EntryCountBefore'] ?: 1)) * 10000) / 100;
                    if ($this->FE['EstAft'] >= 100) {
                        $this->FE['EstAft'] = 0;
                        $this->FE['EstWidth'] = 100;
                    }
                    $this->FE['SearchInfo'] .= sprintf(
                        '<br /><div style="width:100%%;height:2px;overflow:visible;background-color:rgba(0,192,0,.4);margin:1px 0 1px 0">' .
                        '<div style="position:relative;%s:%s%%;top:-1px;width:%s%%;height:4px;overflow:visible;background-color:rgba(192,0,0,.5);margin:0"></div></div>',
                        $this->FE['FE_Align'],
                        $this->FE['EstAft'],
                        $this->FE['EstWidth']
                    );
                }
            }
        } else {
            $this->FE['SearchInfo'] = '<br />' . sprintf(
                $this->L10N->getPlural($this->FE['EntryCount'], 'label.Displaying %s entry'),
                '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['EntryCount']) . '</span>'
            );
        }
    }

    $this->FE['logfileData'] = $this->FE['TextModeLinks'] === 'fancy' ? str_replace(
        ['<', '>', "\r", "\n"],
        ['&lt;', '&gt;', '', "<br />\n"],
        $this->FE['logfileData']
    ) : str_replace(
        ['<', '>', "\r"],
        ['&lt;', '&gt;', ''],
        $this->FE['logfileData']
    );
    $this->FE['mod_class_nav'] = ' big';
    $this->FE['mod_class_right'] = ' extend';
}
if (empty($this->FE['mod_class_nav'])) {
    $this->FE['mod_class_nav'] = ' extend';
    $this->FE['mod_class_right'] = ' big';
}

/** Logs control form. */
$this->FE['TextModeSwitchLink'] = sprintf(
    '<td class="h4"><span class="s"><label for="textMode">%1$s</label><br /><select name="textMode" class="auto" title="%1$s">' .
    '<option value="simple"%2$s>%3$s</option>' .
    '<option value="fancy"%4$s>%5$s</option>' .
    '<option value="tally"%6$s>%7$s</option>' .
    '</select></span></td><td class="h4f"><span class="s">' .
    '<input type="radio" class="auto" name="sortOrder" value="ascending" id="sOa"%8$s /><label for="sOa">%9$s</label><br />' .
    '<input type="radio" class="auto" name="sortOrder" value="descending" id="sOd"%10$s /><label for="sOd">%11$s</label>' .
    '</span></td></tr><tr><td class="h4"><span class="s">' .
    '<input type="checkbox" name="paginate" class="auto" id="paginate"%16$s /><label for="paginate">%17$s</label><br />' .
    '<label for="perpage">%18$s</label><br /><input type="number" name="perpage" class="auto" id="perpage" value="%19$d" />' .
    '</span></td><td class="h4f"><span class="s">' .
    '<input type="checkbox" name="remember" class="auto" id="remember"%12$s /><label for="remember">%13$s</label><br />' .
    '<input type="hidden" name="logfile" value="%14$s" /><input type="submit" value="%15$s" />' .
    '</span></td>',
    $this->L10N->getString('label.Text formatting'),
    $this->FE['TextModeLinks'] === 'simple' ? ' selected' : '',
    $this->L10N->getString('label.Simple'),
    $this->FE['TextModeLinks'] === 'fancy' ? ' selected' : '',
    $this->L10N->getString('label.Fancy'),
    $this->FE['TextModeLinks'] === 'tally' ? ' selected' : '',
    $this->L10N->getString('label.Tally'),
    $this->FE['SortOrder'] === 'ascending' ? ' checked' : '',
    $this->L10N->getString('switch-descending-order-set-false'),
    $this->FE['SortOrder'] === 'descending' ? ' checked' : '',
    $this->L10N->getString('switch-descending-order-set-true'),
    $this->FE['Remember'] ? ' checked' : '',
    $this->L10N->getString('label.Remember'),
    $this->CIDRAM['QueryVars']['logfile'] ?? '',
    $this->L10N->getString('field.OK'),
    $this->FE['Paginate'] ? ' checked' : '',
    $this->L10N->getString('label.Paginate'),
    $this->L10N->getString('label.Entries per page'),
    $this->FE['PerPage']
);

/** Prepare log data formatting. */
if ($this->FE['TextModeLinks'] === 'fancy') {
    $this->formatter($this->FE['logfileData'], $this->FE['BlockLink'], $this->FE['SearchQuery'], $this->FE['FieldSeparator'], $this->FE['Flags']);
} elseif ($this->FE['TextModeLinks'] === 'tally') {
    $this->FE['logfileData'] = $this->tally(
        $this->FE['logfileData'],
        $this->FE['BlockLink'],
        [$this->L10N->getString('field.ID'), $this->L10N->getString('field.DateTime')]
    );
} else {
    $this->FE['logfileData'] = '<textarea id="logsTA" readonly>' . trim($this->FE['logfileData']) . '</textarea>';
}

/** Generate a list of the logs. */
foreach ($this->FE['LogFiles']['Files'] as $ThisLogFile) {
    $this->FE['LogFiles']['Out'] .= sprintf(
        '      <a href="?cidram-page=logs&textMode=%1$s&sortOrder=%2$s%3$s&logfile=%4$s">%4$s</a> – %5$s<br />',
        $this->FE['TextModeLinks'],
        $this->FE['SortOrder'],
        $this->FE['Remember'] ? '&remember=on' : '',
        $ThisLogFile['Filename'],
        $ThisLogFile['Filesize']
    ) . "\n";
}
unset($ThisLogFile);

/** Calculate page load time (useful for debugging). */
$this->FE['ProcessTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
$this->FE['SearchInfo'] = '<td colspan="2" class="spanner">' . sprintf(
    $this->L10N->getPlural($this->FE['ProcessTime'], 'label.Page request completed in %s seconds'),
    '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['ProcessTime'], 3) . '</span>'
) . $this->FE['SearchInfo'] . '</td>';

/** Set log-file list or no log files available message. */
$this->FE['LogFiles'] = $this->FE['LogFiles']['Out'] ?: $this->L10N->getString('label.No log files available');

/** Send output. */
echo $this->sendOutput();
