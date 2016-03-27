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
 * This file: Custom rules file for AS6939 (last modified: 2016.03.28).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** Prevents execution from outside of the IP test functions. */
if (!isset($cidr[$i])) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** Skip further processing if the `block_cloud` directive is false. */
if(!$CIDRAM['Config']['signatures']['block_cloud']) {
    continue;
}

$bypass = false;

/** Feedly/Feedspot bypass. */
if (
    preg_match('/\.getpebble\.com$/i', $CIDRAM['BlockInfo']['UALC']) ||
    substr_count($CIDRAM['BlockInfo']['UA'], 'Feedspot http://www.feedspot.com') ||
    substr_count($CIDRAM['BlockInfo']['UA'], 'Feedly')
) {
    $bypass = true;
}

/** Access provider block bypass. */
if ($cidr[23] === '65.49.67.0/24') {
    $bypass = true;
}

if (!$bypass) {
    $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['lang']['ReasonMessage_Cloud'];
    if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
        $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
    }
    $CIDRAM['BlockInfo']['WhyReason'] .= $CIDRAM['lang']['Short_Cloud'] . $LN;
    if (!empty($CIDRAM['BlockInfo']['Signatures'])) {
        $CIDRAM['BlockInfo']['Signatures'] .= ', ';
    }
    $CIDRAM['BlockInfo']['Signatures'] .= $cidr[$i];
    $CIDRAM['BlockInfo']['SignatureCount']++;
}

$bypass = false;
