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
 * This file: Report to AbuseIPDB page (last modified: 2023.12.15).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'abuseipdb' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('label.Report to AbuseIPDB'), 'This page is still under construction and won\'t work properly yet.', false);

if (!isset($_POST['apikey']) && isset($this->Configuration['abuseipdb']['api_key'])) {
    $_POST['apikey'] = $this->Configuration['abuseipdb']['api_key'];
}
foreach (['address', 'comments', 'apikey'] as $Field) {
    $this->FE[$Field] = isset($_POST[$Field]) ? str_replace(['&', '<', '>', '"'], ['&amp;', '&lt;', '&gt;', '&quot;'], $_POST[$Field]) : '';
}
for ($Iterator = 1; $Iterator < 24; $Iterator++) {
    $this->FE['CatSwitch' . $Iterator] = isset($_POST['CatSwitch' . $Iterator]) && $_POST['CatSwitch' . $Iterator] === 'on' ? ' checked' : '';
}

unset($Iterator, $Field);

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_abuseipdb.html')), true);

/** Send output. */
echo $this->sendOutput();
