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
 * This file: Custom rules file for AS6939 (last modified: 2016.06.18).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** Prevents execution from outside of the CheckFactors closure. */
if (!isset($Factors[$FactorIndex])) {
    die('[CIDRAM] This should not be accessed directly.');
}

$bypass = false;

/** Skip further processing if the "block_cloud" directive is false. */
if (!$CIDRAM['Config']['signatures']['block_cloud']) {
    $bypass = true;
}

/** Access provider block bypass. */
if ($Factors[23] === '65.49.67.0/24') {
    $bypass = true;
}

/** Feedly/Feedspot bypass. */
if (
    preg_match('/\.getpebble\.com$/i', $CIDRAM['BlockInfo']['UALC']) ||
    substr_count($CIDRAM['BlockInfo']['UA'], 'Feedspot http://www.feedspot.com') ||
    substr_count($CIDRAM['BlockInfo']['UA'], 'Feedly')
) {
    $bypass = true;
}

if (!$bypass) {
    if (!$CIDRAM['CIDRAM_sapi']) {
        $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['lang']['ReasonMessage_Cloud'];
        if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
            $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
        }
        $CIDRAM['BlockInfo']['WhyReason'] .= $CIDRAM['lang']['Short_Cloud'] . $LN;
        if (!empty($CIDRAM['BlockInfo']['Signatures'])) {
            $CIDRAM['BlockInfo']['Signatures'] .= ', ';
        }
    }
    $CIDRAM['BlockInfo']['Signatures'] .= $Factors[$FactorIndex];
    $CIDRAM['BlockInfo']['SignatureCount']++;
}
