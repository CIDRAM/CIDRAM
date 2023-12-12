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
 * This file: The sections list page (last modified: 2023.12.12).
 */

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'sections' || $this->FE['Permissions'] !== 1) {
    die;
}

if (!$this->FE['ASYNC']) {
    /** Page initial prepwork. */
    $this->initialPrepwork($this->L10N->getString('link.Sections List'), $this->L10N->getString('tip.Sections List'));

    /** Append async globals. */
    $this->FE['JS'] .=
        "function slx(a,b,c,d){window['SectionName']=a,window['Action']=b,$('POST','',['SectionName','Action'],null," .
        "function(e){hide(c),show(d,'block')},null)}";

    /** Add flags CSS. */
    if ($this->FE['Flags'] = file_exists($this->AssetsPath . 'frontend/flags.css')) {
        $this->FE['OtherHead'] .= "\n  <link rel=\"stylesheet\" type=\"text/css\" href=\"?cidram-page=flags\" />";
    }

    /** Process signature files. */
    $this->FE['Data'] = (
        strlen($this->Configuration['components']['ipv4']) === 0 &&
        strlen($this->Configuration['components']['ipv6']) === 0
    ) ? '    <div class="txtRd">' . $this->L10N->getString('warning.No signature files are active') . "</div>\n" : $this->sectionsHandler(
        array_unique(explode("\n", $this->Configuration['components']['ipv4'] . "\n" . $this->Configuration['components']['ipv6']))
    );

    /** Calculate and append page load time, and append totals. */
    $this->FE['ProcessTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
    $this->FE['Data'] = '<div class="s">' . sprintf(
        $this->L10N->getPlural($this->FE['ProcessTime'], 'label.Page request completed in %s seconds'),
        '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['ProcessTime'], 3) . '</span>'
    ) . '<br />' . sprintf(
        $this->L10N->getString('state_sl_totals'),
        '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['SL_Signatures'] ?? 0) . '</span>',
        '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['SL_Sections'] ?? 0) . '</span>',
        '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['SL_Files'] ?? 0) . '</span>',
        '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['SL_Unique'] ?? 0) . '</span>'
    ) . '</div><hr />' . $this->FE['Data'];

    /** Parse output. */
    $this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_sections.html')), true);

    /** Send output. */
    echo $this->sendOutput();
} elseif (isset($_POST['SectionName'], $_POST['Action'])) {
    /** Fetch current ignores data. */
    $IgnoreData = $this->readFile($this->Vault . 'ignore.dat') ?: '';

    if ($_POST['Action'] === 'unignore' && preg_match("~\nIgnore " . $_POST['SectionName'] . "\n~", $IgnoreData)) {
        $IgnoreData = preg_replace("~\nIgnore " . $_POST['SectionName'] . "\n~", "\n", $IgnoreData);
        $Handle = fopen($this->Vault . 'ignore.dat', 'wb');
        fwrite($Handle, $IgnoreData);
        fclose($Handle);
    } elseif ($_POST['Action'] === 'ignore' && !preg_match("~\nIgnore " . $_POST['SectionName'] . "\n~", $IgnoreData)) {
        if (strpos($IgnoreData, "\n# End front-end generated ignore rules.") === false) {
            $IgnoreData .= "\n# Begin front-end generated ignore rules.\n# End front-end generated ignore rules.\n";
        }
        $IgnoreData = substr($IgnoreData, 0, strrpos(
            $IgnoreData,
            "# End front-end generated ignore rules.\n"
        )) . 'Ignore ' . $_POST['SectionName'] . "\n" . substr($IgnoreData, strrpos(
            $IgnoreData,
            "# End front-end generated ignore rules.\n"
        ));
        $Handle = fopen($this->Vault . 'ignore.dat', 'wb');
        fwrite($Handle, $IgnoreData);
        fclose($Handle);
    }

    /** Cleanup. */
    unset($Handle, $IgnoreData);
}
