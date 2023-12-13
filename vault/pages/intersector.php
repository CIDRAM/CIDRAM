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
 * This file: The intersector page (last modified: 2023.12.13).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'intersector' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.Intersector'), $this->L10N->getString('tip.Intersector'));

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
$this->FE['Intersector_A'] = $_POST['A'] ?? '';
$this->FE['Intersector_B'] = $_POST['B'] ?? '';

/** Default value for output. */
$this->FE['Intersector_AB'] = '';

/** Data was submitted for intersection. */
if (isset($_POST['A'], $_POST['B'])) {
    $Intersection = [
        'A' => str_replace("\r", '', trim($_POST['A'])),
        'B' => str_replace("\r", '', trim($_POST['B']))
    ];

    /** We'll aggregate the latter set before intersecting it with the former. */
    $this->CIDRAM['Aggregator'] = new Aggregator();
    if ($Intersection['B']) {
        $Intersection['B'] = "\n" . $this->CIDRAM['Aggregator']->aggregate($Intersection['B']) . "\n";
    }

    /** Beginning intersection process here. */
    if ($Intersection['A'] && $Intersection['B']) {
        $this->FE['Intersector_AB'] = $this->intersectCidr(
            $Intersection['A'],
            $Intersection['B'],
            $OutputFormat
        );
    }

    /** Cleanup. */
    unset($Intersection, $this->CIDRAM['Aggregator']);
}

/** Calculate page load time (useful for debugging). */
$this->FE['ProcessTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
$this->FE['state_msg'] .= sprintf(
    $this->L10N->getPlural($this->FE['ProcessTime'], 'label.Page request completed in %s seconds'),
    '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['ProcessTime'], 3) . '</span>'
);

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_intersector.html')), true);

/** Strip output row if input doesn't exist. */
if ($this->FE['Intersector_AB'] !== '') {
    $this->FE['FE_Content'] = str_replace(['<!-- Output Begin -->', '<!-- Output End -->'], '', $this->FE['FE_Content']);
} else {
    $this->FE['FE_Content'] =
        substr($this->FE['FE_Content'], 0, strpos($this->FE['FE_Content'], '<!-- Output Begin -->')) .
        substr($this->FE['FE_Content'], strpos($this->FE['FE_Content'], '<!-- Output End -->') + 19);
}

/** Send output. */
echo $this->sendOutput();
