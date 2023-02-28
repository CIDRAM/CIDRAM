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
 * This file: Default signature bypasses (last modified: 2023.02.28).
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

/**
 * Define object for these rules for later recall (all parameters inherited from CheckFactors).
 *
 * @param array $Factors All CIDR factors of the IP being checked.
 * @param int $FactorIndex The index of the CIDR factor of the triggered rule.
 * @param string $LN The line information generated by CheckFactors.
 * @param string $Tag The triggered rule's section's name (if there's any).
 */
$CIDRAM['RunParamResCache']['bypasses.php'] = function (array $Factors = [], int $FactorIndex = 0, string $LN = '', string $Tag = '') use (&$CIDRAM) {
    /** Skip processing if the bypasses aren't active. */
    if (!isset($CIDRAM['Config']['bypasses'])) {
        return;
    }

    /**
     * OVH rules (determine which directive the signatures should fall under,
     * since in order to do so, it requires additional checks beyond just the
     * range itself; i.e., checking the hostname).
     */
    if ($Tag === 'OVH Systems') {
        /** Fetch hostname. */
        if (empty($CIDRAM['Hostname'])) {
            $CIDRAM['Hostname'] = $CIDRAM['DNS-Reverse']($CIDRAM['BlockInfo']['IPAddr']);
        }

        /** ADSL hostnames (should fall under "spam" directive, since not a cloud service). */
        if (preg_match('~(?:dsl\.ovh|ovhtelecom)\.fr$~i', $CIDRAM['Hostname'])) {
            /** Return early if "block_spam" is false. */
            if (!$CIDRAM['Config']['signatures']['block_spam']) {
                return;
            }

            $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['L10N']->getString('ReasonMessage_Spam');
            if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
                $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
            }
            $CIDRAM['BlockInfo']['WhyReason'] .= $CIDRAM['L10N']->getString('Short_Spam') . $LN;
            $CIDRAM['AddProfileEntry']('Spam');
            if (!empty($CIDRAM['BlockInfo']['Signatures'])) {
                $CIDRAM['BlockInfo']['Signatures'] .= ', ';
            }
            $CIDRAM['BlockInfo']['Signatures'] .= $Factors[$FactorIndex];
            $CIDRAM['BlockInfo']['SignatureCount']++;

            /** Exit. */
            return;
        }

        /** Return early if "block_cloud" is false. */
        if (!$CIDRAM['Config']['signatures']['block_cloud']) {
            return;
        }

        $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['L10N']->getString('ReasonMessage_Cloud');
        if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
            $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
        }
        $CIDRAM['BlockInfo']['WhyReason'] .= $CIDRAM['L10N']->getString('Short_Cloud') . $LN;
        $CIDRAM['AddProfileEntry']('Cloud');
        if (!empty($CIDRAM['BlockInfo']['Signatures'])) {
            $CIDRAM['BlockInfo']['Signatures'] .= ', ';
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
        /**
         * AmazonAdBot bypass.
         * @link https://github.com/CIDRAM/CIDRAM/issues/260
         */
        if (
            $CIDRAM['Request']->inCsv('AmazonAdBot', $CIDRAM['Config']['bypasses']['used']) &&
            strpos($CIDRAM['BlockInfo']['UALC'], 'amazonadbot/') !== false
        ) {
            return;
        }

        /** DuckDuckGo bypass. */
        if (
            $CIDRAM['Request']->inCsv('DuckDuckBot', $CIDRAM['Config']['bypasses']['used']) &&
            preg_match('~duckduck(?:go-favicons-)?bot~', $CIDRAM['BlockInfo']['UALC'])
        ) {
            return 4;
        }

        /**
         * Embedly bypass.
         * @link https://github.com/CIDRAM/CIDRAM/issues/80
         */
        if (
            $CIDRAM['Request']->inCsv('Embedly', $CIDRAM['Config']['bypasses']['used']) &&
            strpos($CIDRAM['BlockInfo']['UALC'], 'embedly') !== false
        ) {
            return;
        }

        /**
         * Feedspot bypass.
         * @link https://udger.com/resources/ua-list/bot-detail?bot=Feedspotbot
         */
        if (
            $CIDRAM['Request']->inCsv('Feedspot', $CIDRAM['Config']['bypasses']['used']) &&
            strpos($CIDRAM['BlockInfo']['UA'], '+https://www.feedspot.com/fs/fetcher') !== false
        ) {
            return;
        }

        /**
         * Pinterest bypass.
         * @link https://github.com/CIDRAM/CIDRAM/issues/253
         */
        if (
            $CIDRAM['Request']->inCsv('Pinterest', $CIDRAM['Config']['bypasses']['used']) &&
            strpos($CIDRAM['BlockInfo']['UALC'], 'pinterest') !== false
        ) {
            return;
        }

        /**
         * Redditbot bypass.
         * @link https://github.com/CIDRAM/CIDRAM/issues/243
         */
        if (
            $CIDRAM['Request']->inCsv('Redditbot', $CIDRAM['Config']['bypasses']['used']) &&
            strpos($CIDRAM['BlockInfo']['UALC'], 'redditbot/') !== false
        ) {
            return;
        }

        /**
         * Snapchat bypass.
         * @link https://github.com/CIDRAM/CIDRAM/issues/422
         */
        if (
            $CIDRAM['Request']->inCsv('Snapchat', $CIDRAM['Config']['bypasses']['used']) &&
            preg_match('~developers\.snap\.com/robots$~', $CIDRAM['BlockInfo']['UALC'])
        ) {
            return;
        }
    }

    /** Azure bypasses. */
    if ($Tag === 'Azure') {
        /**
         * Bingbot bypass.
         * @link https://github.com/CIDRAM/CIDRAM/issues/242
         */
        if ($CIDRAM['Request']->inCsv('Bingbot', $CIDRAM['Config']['bypasses']['used'])) {
            if (empty($CIDRAM['Hostname'])) {
                $CIDRAM['Hostname'] = $CIDRAM['DNS-Reverse']($CIDRAM['BlockInfo']['IPAddr']);
            }
            if (
                preg_match('~^msnbot-\d+-\d+-\d+-\d+\.search\.msn\.com$~i', $CIDRAM['Hostname']) ||
                preg_match('~(?:msn|bing)bot|bingpreview~', $CIDRAM['BlockInfo']['UALC'])
            ) {
                $CIDRAM['Flag-Bypass-Bingbot-Check'] = true;
                return 4;
            }
        }

        /**
         * DuckDuckGo bypass.
         * @link https://duckduckgo.com/duckduckbot
         */
        if (
            $CIDRAM['Request']->inCsv('DuckDuckBot', $CIDRAM['Config']['bypasses']['used']) &&
            preg_match('~duckduck(?:go-favicons-)?bot~', $CIDRAM['BlockInfo']['UALC'])
        ) {
            return 4;
        }
    }

    /** Google bypasses. */
    if ($Tag === 'Google LLC') {
        /**
         * Googlebot bypass.
         */
        if ($CIDRAM['Request']->inCsv('Googlebot', $CIDRAM['Config']['bypasses']['used'])) {
            if (empty($CIDRAM['Hostname'])) {
                $CIDRAM['Hostname'] = $CIDRAM['DNS-Reverse']($CIDRAM['BlockInfo']['IPAddr']);
            }
            if (preg_match('~\.google(?:bot)?\.com$~i', $CIDRAM['Hostname'])) {
                return 2;
            }
        }

        /**
         * Google Fiber bypass.
         */
        if ($CIDRAM['Request']->inCsv('GoogleFiber', $CIDRAM['Config']['bypasses']['used'])) {
            if (empty($CIDRAM['Hostname'])) {
                $CIDRAM['Hostname'] = $CIDRAM['DNS-Reverse']($CIDRAM['BlockInfo']['IPAddr']);
            }
            if (preg_match('~\.googlefiber\.net$~i', $CIDRAM['Hostname'])) {
                return 2;
            }
        }
    }

    /** Huawei Cloud bypasses. */
    if ($Tag === 'Huawei Cloud Service') {
        /**
         * PetalBot bypass.
         * @link https://github.com/CIDRAM/CIDRAM/issues/254
         */
        if ($CIDRAM['Request']->inCsv('PetalBot', $CIDRAM['Config']['bypasses']['used'])) {
            if (empty($CIDRAM['Hostname'])) {
                $CIDRAM['Hostname'] = $CIDRAM['DNS-Reverse']($CIDRAM['BlockInfo']['IPAddr']);
            }
            if (
                preg_match('~\.(?:aspiegel|petalsearch)\.com$~i', $CIDRAM['Hostname']) ||
                strpos($CIDRAM['BlockInfo']['UALC'], 'petalbot') !== false
            ) {
                $CIDRAM['Flag-Bypass-PetalBot-Check'] = true;
                return 4;
            }
        }
    }

    /** Oracle bypasses. */
    if ($Tag === 'Oracle Corporation') {
        /**
         * Oracle Data Cloud Crawler (a.k.a., Grapeshot) bypass.
         * @link https://www.oracle.com/corporate/acquisitions/grapeshot/crawler.html
         */
        if (
            $CIDRAM['Request']->inCsv('Grapeshot', $CIDRAM['Config']['bypasses']['used']) &&
            strpos($CIDRAM['BlockInfo']['UALC'], 'grapeshot') !== false
        ) {
            return;
        }
    }

    /**
     * Automattic and SingleHop bypasses.
     * @link https://github.com/CIDRAM/CIDRAM/issues/65
     */
    if ($Tag === 'Automattic' || $Tag === 'SingleHop, Inc') {
        /**
         * WordPress general bypass (e.g., for REST API calls).
         * @link https://wordpress.org/support/topic/site-health-issues-4/
         */
        if (
            $CIDRAM['Request']->inCsv('WordPress REST API', $CIDRAM['Config']['bypasses']['used']) &&
            (defined('ABSPATH') || strtolower(str_replace("\\", '/', substr(__DIR__, -31))) === 'wp-content/plugins/cidram/vault')
        ) {
            return;
        }

        /** Feedbot bypass. */
        if (
            $CIDRAM['Request']->inCsv('Feedbot', $CIDRAM['Config']['bypasses']['used']) &&
            strpos($CIDRAM['BlockInfo']['UALC'], 'wp.com feedbot/1.0 (+https://wp.com)') !== false
        ) {
            return;
        }

        /** Jetpack bypass. */
        if (
            $CIDRAM['Request']->inCsv('Jetpack', $CIDRAM['Config']['bypasses']['used']) &&
            strpos($CIDRAM['BlockInfo']['UALC'], 'jetpack') !== false
        ) {
            return;
        }
    }

    /** AbuseIPDB webmaster verification bot bypass. */
    if (
        $Tag === 'Digital Ocean, Inc' &&
        $CIDRAM['Request']->inCsv('AbuseIPDB', $CIDRAM['Config']['bypasses']['used']) &&
        $CIDRAM['BlockInfo']['UA'] === 'AbuseIPDB_Bot/1.0'
    ) {
        return;
    }

    $CIDRAM['BlockInfo']['ReasonMessage'] = $CIDRAM['L10N']->getString('ReasonMessage_Cloud');
    if (!empty($CIDRAM['BlockInfo']['WhyReason'])) {
        $CIDRAM['BlockInfo']['WhyReason'] .= ', ';
    }
    $CIDRAM['BlockInfo']['WhyReason'] .= $CIDRAM['L10N']->getString('Short_Cloud') . $LN;
    $CIDRAM['AddProfileEntry']('Cloud');
    if (!empty($CIDRAM['BlockInfo']['Signatures'])) {
        $CIDRAM['BlockInfo']['Signatures'] .= ', ';
    }
    $CIDRAM['BlockInfo']['Signatures'] .= $Factors[$FactorIndex];
    $CIDRAM['BlockInfo']['SignatureCount']++;
};

/** Execute object. */
$RunExitCode = $CIDRAM['RunParamResCache']['bypasses.php']($Factors, $FactorIndex, $LN, $Tag);
