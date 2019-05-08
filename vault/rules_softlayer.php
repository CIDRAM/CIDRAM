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
 * This file: Custom rules file for Soft Layer (last modified: 2019.04.30).
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
$CIDRAM['RunParamResCache']['rules_softlayer.php'] = function ($Factors = [], $FactorIndex = 0, $LN = 0, $Tag = '') use (&$CIDRAM) {

    /** Skip further processing if the "block_cloud" directive is false. */
    if (!$CIDRAM['Config']['signatures']['block_cloud']) {
        return;
    }

    /** ShowyouBot bypass. */
    if (strpos($CIDRAM['BlockInfo']['UALC'], 'showyoubot') !== false) {
        return;
    }

    /** Disqus bypass. */
    if (strpos($CIDRAM['BlockInfo']['UALC'], 'disqus') !== false) {
        return;
    }

    /** Feedspot bypass. */
    if (strpos($CIDRAM['BlockInfo']['UA'], 'Feedspot http://www.feedspot.com') !== false) {
        return;
    }

    /** Superfeedr bypass. */
    if (strpos($CIDRAM['BlockInfo']['UA'], 'Superfeedr bot/2.0') !== false) {
        return;
    }

    /** Feedbot bypass. */
    if (strpos($CIDRAM['BlockInfo']['UA'], 'Feedbot') !== false) {
        return;
    }

    $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['L10N']->getString('ReasonMessage_Cloud');
    if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
        $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
    }
    $CIDRAM['BlockInfo']['WhyReason'] .= $CIDRAM['L10N']->getString('Short_Cloud') . $LN;
    if (!empty($CIDRAM['BlockInfo']['Signatures'])) {
        $CIDRAM['BlockInfo']['Signatures'] .= ', ';
    }
    $CIDRAM['BlockInfo']['Signatures'] .= $Factors[$FactorIndex];
    $CIDRAM['BlockInfo']['SignatureCount']++;

};

/** Execute object. */
$RunExitCode = $CIDRAM['RunParamResCache']['rules_softlayer.php']($Factors, $FactorIndex, $LN, $Tag);
