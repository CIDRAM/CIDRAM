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
 * This file: The signature file fixer page (last modified: 2023.12.12).
 */

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'fixer' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.Signature File Fixer'), $this->L10N->getString('tip.Signature File Fixer'));

/** Preferred source. */
$PreferredSource = $_POST['preferredSource'] ?? '';

/** Direct input. */
$this->FE['DirectInput'] = $_POST['DirectInput'] ?? '';

/** Preferred source menu. */
$this->FE['PreferredSource'] = sprintf(
    '%1$sList" value="List"%2$s %7$s%6$spreferredSourceList">%3$s</label><br />%1$sInput" value="Input"%4$s %7$s%6$spreferredSourceInput">%5$s</label>',
    '<input type="radio" class="auto" name="preferredSource" id="preferredSource',
    $PreferredSource === 'List' ? ' checked' : '',
    $this->L10N->getString('field.Currently active signature files'),
    $PreferredSource === 'Input' ? ' checked' : '',
    $this->L10N->getString('field.Direct input'),
    ' /><label class="s" for="',
    'onchange="javascript:{hideid(\'preferredSourceListDiv\');hideid(\'preferredSourceInputDiv\');showid(this.id+\'Div\');showid(\'submitButton\');}"'
);

/** Whether to show or hide preferred source sections. */
$this->FE['styleList'] = $PreferredSource === 'List' ? '' : ' style="display:none"';
$this->FE['styleInput'] = $PreferredSource === 'Input' ? '' : ' style="display:none"';
$this->FE['submitButtonVisibility'] = empty($PreferredSource) ? ' style="display:none"' : '';

/** Generate a list of currently active signature files. */
$this->FE['ActiveSignatureFiles'] = '<div style="display:grid;margin:38px;grid-template-columns:auto">';
$GIClass = 'gridHB';
foreach (explode("\n", $this->Configuration['components']['ipv4'] . "\n" . $this->Configuration['components']['ipv6']) as $SigSource) {
    $GIClass = $GIClass !== 'gridHA' ? 'gridHA' : 'gridHB';
    $SigSourceID = preg_replace('~[^\da-z]~i', '_', $SigSource);
    $this->FE['ActiveSignatureFiles'] .= sprintf(
        '<div class="gridboxitem %4$s"><span class="s gridlabel"><input type="radio" class="auto" name="sigFile" id="%1$s" value="%2$s" %3$s/><label for="%1$s">%2$s</label></span></div>',
        $SigSourceID,
        $SigSource,
        (!empty($_POST['sigFile']) && $_POST['sigFile'] === $SigSource) ? 'checked ' : '',
        $GIClass
    );
}
$this->FE['ActiveSignatureFiles'] .= '</div>';
unset($SigSourceID, $SigSource, $GIClass);

/** Fixer output. */
$this->FE['FixerOutput'] = '';

/** Prepare to process a currently active signature file. */
if ($PreferredSource === 'List' && !empty($_POST['sigFile'])) {
    if (!isset($this->CIDRAM['FileCache'])) {
        $this->CIDRAM['FileCache'] = [];
    }
    if (!isset($this->CIDRAM['FileCache'][$_POST['sigFile']])) {
        $this->CIDRAM['FileCache'][$_POST['sigFile']] = $this->readFile($this->SignaturesPath . $_POST['sigFile']);
    }
    if (!empty($this->CIDRAM['FileCache'][$_POST['sigFile']])) {
        $this->FE['FixerOutput'] = $this->CIDRAM['FileCache'][$_POST['sigFile']];
    }
}

/** Prepare to process via direct input. */
if ($PreferredSource === 'Input' && !empty($_POST['DirectInput'])) {
    $this->FE['FixerOutput'] = $_POST['DirectInput'];
}

/** Process (validate; attempt to fix) data. */
if ($this->FE['FixerOutput']) {
    $Fixer = [
        'Time' => microtime(true),
        'Changes' => 0,
        'Aggregator' => new Aggregator(),
        'Before' => hash('sha256', $this->FE['FixerOutput']) . ':' . strlen($this->FE['FixerOutput'])
    ];
    $Fixer['Aggregator']->Results = true;
    if (strpos($this->FE['FixerOutput'], "\r") !== false) {
        $this->FE['FixerOutput'] = str_replace("\r", '', $this->FE['FixerOutput']);
        $Fixer['Changes']++;
    }
    $Fixer['StrObject'] = new \Maikuolan\Common\ComplexStringHandler("\n" . $this->FE['FixerOutput'] . "\n", self::REGEX_TAGS, function (string $Data) use (&$Fixer): string {
        if (!$Data = trim($Data)) {
            return '';
        }
        $Output = '';
        $EoLPos = $NEoLPos = 0;
        while ($NEoLPos !== false) {
            $Set = $Previous = '';
            while (true) {
                if (($NEoLPos = strpos($Data, "\n", $EoLPos)) === false) {
                    $Line = trim(substr($Data, $EoLPos));
                } else {
                    $Line = trim(substr($Data, $EoLPos, $NEoLPos - $EoLPos));
                    $NEoLPos++;
                }
                $Param = (($Pos = strpos($Line, ' ')) !== false) ? substr($Line, $Pos + 1) : 'Deny Generic';
                $Param = preg_replace(['~^\s+|\s+$~', '~(\S+)\s+(\S+)~'], ['', '\1 \2'], $Param);
                if ($Previous === '') {
                    $Previous = $Param;
                }
                if ($Param !== $Previous) {
                    $NEoLPos = 0;
                    break;
                }
                if ($Line) {
                    $Set .= $Line . "\n";
                }
                if ($NEoLPos === false) {
                    break;
                }
                $EoLPos = $NEoLPos;
            }
            if ($Set = $Fixer['Aggregator']->aggregate(trim($Set))) {
                $Set = preg_replace('~$~m', ' ' . $Previous, $Set);
                $Output .= $Set . "\n";
            }
            $Fixer['Changes'] += $Fixer['Aggregator']->NumberRejected;
            $Fixer['Changes'] += $Fixer['Aggregator']->NumberMerged;
        }
        return trim($Output);
    });
    $Fixer['StrObject']->iterateClosure(function (string $Data) use (&$Fixer) {
        if (($Pos = strpos($Data, "---\n")) !== false && substr($Data, $Pos - 1, 1) === "\n") {
            $YAML = substr($Data, $Pos + 4);
            if (($HPos = strpos($YAML, "\n#")) !== false) {
                $After = substr($YAML, $HPos);
                $YAML = substr($YAML, 0, $HPos + 1);
            } else {
                $After = '';
            }
            $BeforeCount = substr_count($YAML, "\n");
            $Arr = [];
            $this->YAML->process($YAML, $Arr);
            $NewData = substr($Data, 0, $Pos + 4) . $this->YAML->reconstruct($Arr);
            if (($Add = $BeforeCount - substr_count($NewData, "\n") + 1) > 0) {
                $NewData .= str_repeat("\n", $Add);
            }
            $NewData .= $After;
            if ($Data !== $NewData) {
                $Fixer['Changes']++;
                $Data = $NewData;
            }
        }
        return "\n" . $Data;
    }, true);
    $this->FE['FixerOutput'] = trim($Fixer['StrObject']->recompile()) . "\n";
    $Fixer['After'] = hash('sha256', $this->FE['FixerOutput']) . ':' . strlen($this->FE['FixerOutput']);
    if ($Fixer['Before'] !== $Fixer['After'] && !$Fixer['Changes']) {
        $Fixer['Changes']++;
    }
    $Fixer['Time'] = microtime(true) - $Fixer['Time'];
    $Fixer = '<div class="s">' . sprintf($this->L10N->getString('state_fixer'), sprintf(
        $this->L10N->getPlural($Fixer['Changes'], 'state_fixer_changed'),
        '<span class="txtRd">' . $this->NumberFormatter->format($Fixer['Changes']) . '</span>'
    ), sprintf(
        $this->L10N->getPlural($Fixer['Time'], 'state_fixer_seconds'),
        '<span class="txtRd">' . $this->NumberFormatter->format($Fixer['Time'], 3) . '</span>'
    )) . '<br /><blockquote><code>' . $Fixer['Before'] . '</code><br />↪️<code>' . $Fixer['After'] . '</code></blockquote></div>';
    $this->FE['FixerOutput'] = '<hr />' . $Fixer . '<br /><textarea name="FixerOutput" id="fixerOutput">' . str_replace(
        ['&', '<', '>'],
        ['&amp;', '&lt;', '&gt;'],
        $this->FE['FixerOutput']
    ) . '</textarea><br /><br />';

    /** Copy SVG. */
    $this->FE['FixerOutput'] .= '<span class="s">' . sprintf(
        '<span id="fxOS" onclick="javascript:if(navigator.clipboard){navigator.cl' .
        'ipboard.writeText(getElementById(\'fixerOutput\').value);getElementById(' .
        '\'fxOS_copied\').className=\'sFade\'}else{getElementById(\'fxOS_failed\'' .
        ').style.className=\'sFade\'}"><script type="text/javascript">copySvg();<' .
        '/script></span><span id="fxOS_copied"%1$s">✔️ %2$s</span><span id="fxOS_' .
        'failed"%1$s">❌ %3$s</span>',
        ' class="sHide" onanimationend="javascript:this.className=\'sHide\'',
        $this->L10N->getString('response.Copied'),
        $this->L10N->getString('response.Failed')
    ) . '</span>';

    /** Cleanup. */
    unset($Fixer);
}

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_fixer.html')), true);

/** Send output. */
echo $this->sendOutput();
