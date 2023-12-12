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
 * This file: The calculator page (last modified: 2023.12.12).
 */

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'calculator' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.Calculator'), $this->L10N->getString('tip.Calculator'));

/** Template for result rows. */
$this->FE['CalcRow'] = $this->parseVars([], $this->readFile($this->getAssetPath('_calculator_row.html')), true);

/** Initialise results data. */
$this->FE['Ranges'] = '';

/** Process the IP address entered for range calculation. */
if (isset($_POST['address']) && strlen($_POST['address'])) {
    $this->FE['address'] = $_POST['address'];
    if (!$CIDRs = $this->expandIpv4($_POST['address'])) {
        $CIDRs = $this->expandIpv6($_POST['address']);
    }
} else {
    $this->FE['address'] = '';
}

/** Process CIDRs. */
if (!empty($CIDRs)) {
    $Aggregator = new Aggregator(1);
    $Factors = count($CIDRs);
    foreach ($CIDRs as $Key => $CIDR) {
        $First = substr($CIDR, 0, strlen($CIDR) - strlen($Key + 1) - 1);
        if ($Factors === 32) {
            $Last = $this->ipv4GetLast($First, $Key + 1);
        } elseif ($Factors === 128) {
            $Last = $this->ipv6GetLast($First, $Key + 1);
        } else {
            $Last = $this->L10N->getString('response.Error');
        }
        $Netmask = $CIDR;
        $Aggregator->convertToNetmasks($Netmask);
        $Arr = ['CIDR' => $CIDR, 'Netmask' => $Netmask, 'ID' => preg_replace('~[^\dA-fa-f]~', '_', $CIDR), 'Range' => $First . ' – ' . $Last];
        $this->FE['Ranges'] .= $this->parseVars($Arr, $this->FE['CalcRow']);
    }
    unset($Aggregator);
}

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_calculator.html')), true);

/** Send output. */
echo $this->sendOutput();
