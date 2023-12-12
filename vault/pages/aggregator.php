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
 * This file: The aggregator page (last modified: 2023.12.12).
 */

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'aggregator' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.Aggregator'), $this->L10N->getString('tip.Aggregator'));

/** Output format. */
$OutputFormat = (isset($_POST['format']) && $_POST['format'] === 'Netmask') ? 1 : 0;

/** Whether to preserve tags and comments. */
$Preserve = (isset($_POST['preserve']) && $_POST['preserve'] === 'on') ? 1 : 0;

/** Output format menu. */
$this->FE['OutputFormat'] = sprintf(
    '%1$sCIDR" value="CIDR"%2$s%6$sformatCIDR">%3$s</label><br />%1$sNetmask" value="Netmask"%4$s%6$sformatNetmask">%5$s</label><br /><input type="checkbox" class="auto" name="preserve" id="preserve"%7$s%6$spreserve">%8$s</label>',
    '<input type="radio" class="auto" name="format" id="format',
    $OutputFormat !== 1 ? ' checked' : '',
    $this->L10N->getString('field.Generate output as CIDRs'),
    $OutputFormat === 1 ? ' checked' : '',
    $this->L10N->getString('field.Generate output as netmasks'),
    ' /><label class="s" for="',
    $Preserve === 1 ? ' checked' : '',
    $this->L10N->getString('field.Preserve tags and comments')
);

/** Data was submitted for aggregation. */
if (!empty($_POST['input'])) {
    $this->FE['input'] = str_replace("\r", '', trim($_POST['input']));
    $this->CIDRAM['Aggregator'] = new Aggregator($OutputFormat);
    $this->CIDRAM['Aggregator']->Results = true;
    if ($Preserve) {
        $StrObject = new \Maikuolan\Common\ComplexStringHandler("\n" . $this->FE['input'] . "\n", self::REGEX_TAGS, function (string $Data): string {
            if (!$Data = trim($Data)) {
                return '';
            }
            return $this->CIDRAM['Aggregator']->aggregate($Data);
        });
        $StrObject->iterateClosure(function (string $Data): string {
            return "\n" . $Data;
        }, true);
        $this->FE['output'] = trim($StrObject->recompile());
        unset($StrObject);
    } else {
        $this->FE['output'] = $this->CIDRAM['Aggregator']->aggregate($this->FE['input']);
    }
    $this->FE['ResultLine'] = sprintf(
        $this->L10N->getString('label.results'),
        '<span class="txtRd">' . $this->NumberFormatter->format($this->CIDRAM['Aggregator']->NumberEntered) . '</span>',
        '<span class="txtRd">' . $this->NumberFormatter->format($this->CIDRAM['Aggregator']->NumberRejected) . '</span>',
        '<span class="txtRd">' . $this->NumberFormatter->format($this->CIDRAM['Aggregator']->NumberAccepted) . '</span>',
        '<span class="txtRd">' . $this->NumberFormatter->format($this->CIDRAM['Aggregator']->NumberMerged) . '</span>',
        '<span class="txtRd">' . $this->NumberFormatter->format($this->CIDRAM['Aggregator']->NumberReturned) . '</span>'
    );
    unset($this->CIDRAM['Aggregator']);
} else {
    $this->FE['output'] = $this->FE['input'] = '';
}

/** Calculate page load time (useful for debugging). */
$this->FE['ProcessTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
$this->FE['state_msg'] .= sprintf(
    $this->L10N->getPlural($this->FE['ProcessTime'], 'label.Page request completed in %s seconds'),
    '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['ProcessTime'], 3) . '</span>'
);

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_aggregator.html')), true);

/** Strip output row if input doesn't exist. */
if ($this->FE['input']) {
    $this->FE['FE_Content'] = str_replace(['<!-- Output Begin -->', '<!-- Output End -->'], '', $this->FE['FE_Content']);
} else {
    $this->FE['FE_Content'] =
        substr($this->FE['FE_Content'], 0, strpos($this->FE['FE_Content'], '<!-- Output Begin -->')) .
        substr($this->FE['FE_Content'], strpos($this->FE['FE_Content'], '<!-- Output End -->') + 19);
}

/** Send output. */
echo $this->sendOutput();
