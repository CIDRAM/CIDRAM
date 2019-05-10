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
 * This file: Extended rules for AS6939 CIDRs (last modified: 2019.05.10).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** Prevents execution from outside of the CheckFactors closure. */
if (!isset($Factors[$FactorIndex])) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** Safety. */
if (!isset($CIDRAM['RunParamResCache'])) {
    $CIDRAM['RunParamResCache'] = [];
}

/** Define object for these rules for later recall. */
$CIDRAM['RunParamResCache']['rules_as6939.php'] = function (array $Factors = [], $FactorIndex = 0, $LN = 0, $Tag = '') use (&$CIDRAM) {

    /** Skip further processing if the "block_cloud" directive is false. */
    if (!$CIDRAM['Config']['signatures']['block_cloud']) {
        return;
    }

    /** Access provider block bypass. */
    if ($Factors[23] === '65.49.67.0/24') {
        return;
    }

    /** Feedly/Feedspot bypass. */
    if (
        preg_match('/\.getpebble\.com$/i', $CIDRAM['BlockInfo']['UALC']) ||
        strpos($CIDRAM['BlockInfo']['UA'], 'Feedspot http://www.feedspot.com') !== false ||
        strpos($CIDRAM['BlockInfo']['UA'], 'Feedly') !== false
    ) {
        return;
    }

    /**
     * Puffin browser bypass (Affected ranges: 45.33.128.0/20 and 107.178.32.0/20. Don't need to bypass
     * 101.127.206.0/23 and 101.127.208.0/23, because they belong to AS4657, which isn't blocked anyway).
     * See: https://www.puffinbrowser.com/help/developer/#article-how-can-i-block-puffin-web-browser
     */
    if (strpos($CIDRAM['BlockInfo']['UA'], 'Puffin') !== false && (
        $Factors[19] === '45.33.128.0/20' || $Factors[19] === '107.178.32.0/20'
    )) {
        return;
    }

    if (!$CIDRAM['CIDRAM_sapi']) {
        $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['L10N']->getString('ReasonMessage_Cloud');
        if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
            $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
        }
        $CIDRAM['BlockInfo']['WhyReason'] .= $CIDRAM['L10N']->getString('Short_Cloud') . $LN;
        if (!empty($CIDRAM['BlockInfo']['Signatures'])) {
            $CIDRAM['BlockInfo']['Signatures'] .= ', ';
        }
    }
    $CIDRAM['BlockInfo']['Signatures'] .= $Factors[$FactorIndex];
    $CIDRAM['BlockInfo']['SignatureCount']++;

};

/** Execute object. */
$RunExitCode = $CIDRAM['RunParamResCache']['rules_as6939.php']($Factors, $FactorIndex, $LN, $Tag);
