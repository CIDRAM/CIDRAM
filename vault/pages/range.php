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
 * This file: The range tables page (last modified: 2023.12.13).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'range' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.Range Tables'), $this->L10N->getString('tip.Range Tables'));

/** Append number localisation JS. */
$this->FE['JS'] .= $this->numberL10nJs() . "\n";

/** Add flags CSS. */
if ($this->FE['Flags'] = file_exists($this->AssetsPath . 'frontend/flags.css')) {
    $this->FE['OtherHead'] .= "\n  <link rel=\"stylesheet\" type=\"text/css\" href=\"?cidram-page=flags\" />";
}

/** Template for range rows. */
$this->FE['RangeRow'] = $this->readFile($this->getAssetPath('_range_row.html'));

/** Where to populate signature file data for the matrix. */
$this->FE['Matrix-Data'] = '';

/** Process signature files and fetch returned JavaScript stuff. */
$this->FE['JSFOOT'] = $this->rangeTablesHandler(
    array_unique(explode("\n", $this->Configuration['components']['ipv4'])),
    array_unique(explode("\n", $this->Configuration['components']['ipv6']))
);

/** Process matrix data. */
if ($this->FE['Matrix-Data']) {
    $this->FE['Matrix'] = sprintf(
        '<br /><table><tr><td class="spanner"><img src="data:image/png;base64,%s" alt="CIDRAM signature file analysis" /></td></tr></table>',
        base64_encode($this->matrixCreate($this->FE['Matrix-Data']))
    );
} else {
    $this->FE['Matrix'] = '';
}

/** Calculate and append page load time, and append totals. */
$this->FE['ProcTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
$this->FE['ProcTime'] = '<div class="s">' . sprintf(
    $this->L10N->getPlural($this->FE['ProcTime'], 'label.Page request completed in %s seconds'),
    '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['ProcTime'], 3) . '</span>'
) . '</div>' . $this->FE['Matrix'];

/** Cleanup. */
unset($this->FE['Matrix'], $this->FE['Matrix-Data']);

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_range.html')), true);

/** Send output. */
echo $this->sendOutput();
