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
 * This file: Language handler (last modified: 2019.08.17).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** If the language directive is empty, default to English. */
if (empty($CIDRAM['Config']['general']['lang'])) {
    $CIDRAM['Config']['general']['lang'] = 'en';
}

/** L10N data. */
$CIDRAM['L10N'] = ['Configured' => [], 'ConfiguredData' => '', 'Fallbacks' => [], 'FallbackData' => ''];

/** If the language directive is set to English, don't bother about fallbacks. */
if ($CIDRAM['Config']['general']['lang'] === 'en') {

    /** Standard L10N data. */
    $CIDRAM['L10N']['Configured'][] = $CIDRAM['Vault'] . 'lang/lang.en.yaml';
    if (
        !$CIDRAM['Config']['general']['disable_frontend'] &&
        file_exists($CIDRAM['Vault'] . 'frontend.php') &&
        file_exists($CIDRAM['Vault'] . 'fe_assets/frontend.html') &&
        ($CIDRAM['Direct'] || !empty($CIDRAM['Alternate']))
    ) {
        /** Front-end L10N data. */
        $CIDRAM['L10N']['Configured'][] = $CIDRAM['Vault'] . 'lang/lang.en.fe.yaml';
    }

/** If the language directive isn't set to English, we'll use English as the fallback. */
} else {

    /** Standard L10N data. */
    $CIDRAM['L10N']['Configured'][] = $CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Config']['general']['lang'] . '.yaml';
    $CIDRAM['L10N']['Fallbacks'][] = $CIDRAM['Vault'] . 'lang/lang.en.yaml';
    if (
        !$CIDRAM['Config']['general']['disable_frontend'] &&
        file_exists($CIDRAM['Vault'] . 'frontend.php') &&
        file_exists($CIDRAM['Vault'] . 'fe_assets/frontend.html') &&
        ($CIDRAM['Direct'] || !empty($CIDRAM['Alternate']))
    ) {
        /** Front-end L10N data. */
        $CIDRAM['L10N']['Configured'][] = $CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Config']['general']['lang'] . '.fe.yaml';
        $CIDRAM['L10N']['Fallbacks'][] = $CIDRAM['Vault'] . 'lang/lang.en.fe.yaml';
    }

}

/** Load the L10N data. */
foreach ($CIDRAM['L10N']['Configured'] as $CIDRAM['L10N']['ThisConfigured']) {
    $CIDRAM['L10N']['ConfiguredData'] .= $CIDRAM['ReadFile']($CIDRAM['L10N']['ThisConfigured']);
}

/** Parse the L10N data. */
$CIDRAM['L10N']['ConfiguredData'] = (new \Maikuolan\Common\YAML($CIDRAM['L10N']['ConfiguredData']))->Data;

/** Load the L10N fallback data. */
foreach ($CIDRAM['L10N']['Fallbacks'] as $CIDRAM['L10N']['ThisFallback']) {
    $CIDRAM['L10N']['FallbackData'] .= $CIDRAM['ReadFile']($CIDRAM['L10N']['ThisFallback']);
}

/** Parse the L10N fallback data. */
$CIDRAM['L10N']['FallbackData'] = (new \Maikuolan\Common\YAML($CIDRAM['L10N']['FallbackData']))->Data;

/** Build final L10N object. */
$CIDRAM['L10N'] = new \Maikuolan\Common\L10N($CIDRAM['L10N']['ConfiguredData'], $CIDRAM['L10N']['FallbackData']);

/** Load client-specified L10N data if it's possible to do so. */
if (empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $CIDRAM['Client-L10N'] = &$CIDRAM['L10N'];
    $CIDRAM['L10N-Lang-Attache'] = '';
} else {
    $CIDRAM['Client-L10N'] = [
        'Accepted' => preg_replace(['~^([^,]*).*$~', '~[^-a-z]~'], ['\1', ''], strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']))
    ];
    if (
        $CIDRAM['Config']['general']['lang'] !== $CIDRAM['Client-L10N']['Accepted'] &&
        file_exists($CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Client-L10N']['Accepted'] . '.yaml')
    ) {
        $CIDRAM['Client-L10N']['Data'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Client-L10N']['Accepted'] . '.yaml');
    }
    if (empty($CIDRAM['Client-L10N']['Data'])) {
        $CIDRAM['Client-L10N']['Accepted'] = preg_replace('~^([^-]*).*$~', '\1', $CIDRAM['Client-L10N']['Accepted']);
        if (
            $CIDRAM['Config']['general']['lang'] !== $CIDRAM['Client-L10N']['Accepted'] &&
            file_exists($CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Client-L10N']['Accepted'] . '.yaml')
        ) {
            $CIDRAM['Client-L10N']['Data'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Client-L10N']['Accepted'] . '.yaml');
        }
    }

    /** Process client-specific L10N data. */
    if (empty($CIDRAM['Client-L10N']['Data'])) {
        $CIDRAM['L10N-Lang-Attache'] = '';
        $CIDRAM['Client-L10N'] = [];
    } else {
        $CIDRAM['Client-L10N']['Data'] = (new \Maikuolan\Common\YAML($CIDRAM['Client-L10N']['Data']))->Data ?: [];
        $CIDRAM['L10N-Lang-Attache'] = ($CIDRAM['Config']['general']['lang'] === $CIDRAM['Client-L10N']['Accepted']) ? '' : sprintf(
            ' lang="%s" dir="%s"',
            $CIDRAM['Client-L10N']['Accepted'],
            isset($CIDRAM['Client-L10N']['Data']['Text Direction']) ? $CIDRAM['Client-L10N']['Data']['Text Direction'] : 'ltr'
        );
        $CIDRAM['Client-L10N'] = $CIDRAM['Client-L10N']['Data'];
    }

    /** Build final client-specific L10N object. */
    $CIDRAM['Client-L10N'] = new \Maikuolan\Common\L10N($CIDRAM['Client-L10N'], $CIDRAM['L10N']);
}
