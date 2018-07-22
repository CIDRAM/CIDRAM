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
 * This file: Custom rules file for some specific CIDRs (last modified: 2018.07.23).
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
$CIDRAM['RunParamResCache']['rules_specific.php'] = function ($Factors = [], $FactorIndex = 0, $LN = 0, $Tag = '') use (&$CIDRAM) {

    /** Handle PSINet prefixes here. */
    if ($Tag === 'PSINet, Inc') {

        /** Skip processing if signatures have already been triggered or if the "block_generic" directive is false. */
        if ($CIDRAM['BlockInfo']['SignatureCount'] > 0 || !$CIDRAM['Config']['signatures']['block_generic']) {
            return;
        }

        if (!$CIDRAM['CIDRAM_sapi']) {
            $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['lang']['ReasonMessage_Generic'];
            if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
                $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
            }
            $CIDRAM['BlockInfo']['WhyReason'] .= $CIDRAM['lang']['Short_Generic'] . $LN;
            if (!empty($CIDRAM['BlockInfo']['Signatures'])) {
                $CIDRAM['BlockInfo']['Signatures'] .= ', ';
            }
        }
        $CIDRAM['BlockInfo']['Signatures'] .= $Factors[$FactorIndex];
        $CIDRAM['BlockInfo']['SignatureCount']++;

        /** Exit. */
        return;

    }

    /** Skip further processing if the "block_cloud" directive is false, or if no section tag has been defined. */
    if (!$CIDRAM['Config']['signatures']['block_cloud'] || !$Tag) {
        return;
    }

    /** Amazon AWS bypasses. */
    if ($Tag === 'Amazon.com, Inc') {
        /** DuckDuckGo bypass. */
        if (preg_match('~duckduck(?:go-favicons-)?bot~', $CIDRAM['BlockInfo']['UALC'])) {
            return;
        }
        /** Pinterest bypass. */
        if (strpos($CIDRAM['BlockInfo']['UALC'], 'pinterest') !== false) {
            return;
        }
        /** Embedly bypass. */
        if (strpos($CIDRAM['BlockInfo']['UALC'], 'embedly') !== false) {
            return;
        }
    }

    /** Bingbot bypasses. */
    if ($Tag === 'Azure' && preg_match('~(?:msn|bing)bot|bingpreview~', $CIDRAM['BlockInfo']['UALC'])) {
        $CIDRAM['Flag-Bypass-Bingbot-Check'] = true;
        return 2;
    }

    /** Bypass for "googlealert.com", "gigaalert.com", "copyscape.com". **/
    if ($Tag === 'Rackspace Hosting' && $Factors[31] === '162.13.83.46/32') {
        return;
    }

    /** Ensure that Jetpack isn't blocked via Automattic. */
    if ($Tag === 'Automattic' && strpos($CIDRAM['BlockInfo']['UALC'], 'jetpack') !== false) {
        return;
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
    $CIDRAM['BlockInfo']['Signatures'] .= $Factors[$FactorIndex];
    $CIDRAM['BlockInfo']['SignatureCount']++;

};

/** Execute object. */
$RunExitCode = $CIDRAM['RunParamResCache']['rules_specific.php']($Factors, $FactorIndex, $LN, $Tag);
