<?php
/**
 * This file is a part of the CIDRAM package, and can be downloaded for free
 * from {@link https://github.com/Maikuolan/CIDRAM/ GitHub}.
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: Custom rules file for Soft Layer (last modified: 2016.06.18).
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

/** ShowyouBot bypass. */
if (substr_count($CIDRAM['BlockInfo']['UALC'], 'showyoubot')) {
    $bypass = true;
}

/** Disqus bypass. */
if (substr_count($CIDRAM['BlockInfo']['UALC'], 'disqus')) {
    $bypass = true;
}

/** Feedspot bypass. */
if (substr_count($CIDRAM['BlockInfo']['UA'], 'Feedspot http://www.feedspot.com')) {
    $bypass = true;
}

/** Superfeedr bypass. */
if (substr_count($CIDRAM['BlockInfo']['UA'], 'Superfeedr bot/2.0')) {
    $bypass = true;
}

/** Feedbot bypass. */
if (substr_count($CIDRAM['BlockInfo']['UA'], 'Feedbot')) {
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
