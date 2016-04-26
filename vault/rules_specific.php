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
 * This file: Custom rules file for some specific CIDRs (last modified: 2016.04.27).
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
if (!$CIDRAM['Config']['signatures']['block_cloud']) {
    continue;
}

/** Bypass for "googlealert.com", "gigaalert.com", "copyscape.com". **/
if ($Addr === '162.13.83.46') {
    continue;
}

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
$CIDRAM['BlockInfo']['Signatures'] .= $cidr[$i];
$CIDRAM['BlockInfo']['SignatureCount']++;
