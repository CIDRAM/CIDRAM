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
 * This file: The subtractor page (last modified: 2023.12.12).
 */

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'subtractor' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.Subtractor'), $this->L10N->getString('tip.Subtractor'));

/** Output format. */
$OutputFormat = (isset($_POST['format']) && $_POST['format'] === 'Netmask') ? 1 : 0;

/** Output format menu. */
$this->FE['OutputFormat'] = sprintf(
    '%1$sCIDR" value="CIDR"%2$s%6$sformatCIDR">%3$s</label><br />%1$sNetmask" value="Netmask"%4$s%6$sformatNetmask">%5$s</label>',
    '<input type="radio" class="auto" name="format" id="format',
    $OutputFormat !== 1 ? ' checked' : '',
    $this->L10N->getString('field.Generate output as CIDRs'),
    $OutputFormat === 1 ? ' checked' : '',
    $this->L10N->getString('field.Generate output as netmasks'),
    ' /><label class="s" for="'
);

/** Default values for inputs. */
$this->FE['Subtractor_A'] = $_POST['A'] ?? '';
$this->FE['Subtractor_B'] = $_POST['B'] ?? '';

/** Default value for output. */
$this->FE['Subtractor_AB'] = '';

/** Data was submitted for subtraction. */
if (isset($_POST['A'], $_POST['B'])) {
    $Subtraction = [
        'A' => str_replace("\r", '', trim($_POST['A'])),
        'B' => str_replace("\r", '', trim($_POST['B']))
    ];

    /**
     * We'll aggregate B prior to subtraction for better optimisation.
     */
    $this->CIDRAM['Aggregator'] = new Aggregator();
    if ($Subtraction['B']) {
        $Subtraction['B'] = $this->CIDRAM['Aggregator']->aggregate($Subtraction['B']) . "\n";
    }

    /** Beginning subtraction process here. */
    if ($Subtraction['A'] && $Subtraction['B']) {
        $this->FE['Subtractor_AB'] = $this->subtractCidr(
            $Subtraction['A'],
            $Subtraction['B'],
            $OutputFormat
        );
    }

    /** Cleanup. */
    unset($Subtraction, $this->CIDRAM['Aggregator']);
}

/** Calculate page load time (useful for debugging). */
$this->FE['ProcessTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
$this->FE['state_msg'] .= sprintf(
    $this->L10N->getPlural($this->FE['ProcessTime'], 'label.Page request completed in %s seconds'),
    '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['ProcessTime'], 3) . '</span>'
);

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_subtractor.html')), true);

/** Strip output row if input doesn't exist. */
if ($this->FE['Subtractor_AB'] !== '') {
    $this->FE['FE_Content'] = str_replace(['<!-- Output Begin -->', '<!-- Output End -->'], '', $this->FE['FE_Content']);
} else {
    $this->FE['FE_Content'] =
        substr($this->FE['FE_Content'], 0, strpos($this->FE['FE_Content'], '<!-- Output Begin -->')) .
        substr($this->FE['FE_Content'], strpos($this->FE['FE_Content'], '<!-- Output End -->') + 19);
}

/** Send output. */
echo $this->sendOutput();
