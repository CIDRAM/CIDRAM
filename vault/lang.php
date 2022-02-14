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
 * This file: Language handler (last modified: 2022.02.13).
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
$CIDRAM['L10N'] = [
    'Configured' => [],
    'ConfiguredData' => '',
    'ConfiguredDataArray' => [],
    'Fallbacks' => [],
    'FallbackData' => '',
    'FallbackDataArray' => []
];

/**
 * If the language directive is set to English, don't bother about fallbacks.
 * If it isn't set to English, we'll use English as the fallback.
 */
if ($CIDRAM['Config']['general']['lang'] === 'en') {
    if ($CIDRAM['CIDRAM_sapi'] && empty($CIDRAM['Alternate'])) {
        /** CLI-mode L10N data. */
        $CIDRAM['L10N']['Configured'][] = $CIDRAM['Vault'] . 'lang/lang.en.cli.yaml';
    } else {
        /** Standard L10N data. */
        $CIDRAM['L10N']['Configured'][] = $CIDRAM['Vault'] . 'lang/lang.en.yaml';
        if (
            !$CIDRAM['Config']['general']['disable_frontend'] &&
            file_exists($CIDRAM['Vault'] . 'frontend.php') &&
            file_exists($CIDRAM['Vault'] . 'fe_assets/frontend.html') &&
            ($CIDRAM['Direct']() || !empty($CIDRAM['Alternate']))
        ) {
            /** Front-end L10N data. */
            $CIDRAM['L10N']['Configured'][] = $CIDRAM['Vault'] . 'lang/lang.en.fe.yaml';
        }
    }
} else {
    if ($CIDRAM['CIDRAM_sapi'] && empty($CIDRAM['Alternate'])) {
        /** CLI-mode L10N data. */
        $CIDRAM['L10N']['Configured'][] = $CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Config']['general']['lang'] . '.cli.yaml';
        $CIDRAM['L10N']['Fallbacks'][] = $CIDRAM['Vault'] . 'lang/lang.en.cli.yaml';
    } else {
        /** Standard L10N data. */
        $CIDRAM['L10N']['Configured'][] = $CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Config']['general']['lang'] . '.yaml';
        $CIDRAM['L10N']['Fallbacks'][] = $CIDRAM['Vault'] . 'lang/lang.en.yaml';
        if (
            !$CIDRAM['Config']['general']['disable_frontend'] &&
            file_exists($CIDRAM['Vault'] . 'frontend.php') &&
            file_exists($CIDRAM['Vault'] . 'fe_assets/frontend.html') &&
            ($CIDRAM['Direct']() || !empty($CIDRAM['Alternate']))
        ) {
            /** Front-end L10N data. */
            $CIDRAM['L10N']['Configured'][] = $CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Config']['general']['lang'] . '.fe.yaml';
            $CIDRAM['L10N']['Fallbacks'][] = $CIDRAM['Vault'] . 'lang/lang.en.fe.yaml';
        }
    }
}

/** Load the L10N data. */
foreach ($CIDRAM['L10N']['Configured'] as $CIDRAM['L10N']['ThisConfigured']) {
    $CIDRAM['L10N']['ConfiguredData'] .= $CIDRAM['ReadFile']($CIDRAM['L10N']['ThisConfigured']);
}

/** Parse the L10N data. */
$CIDRAM['YAML']->process($CIDRAM['L10N']['ConfiguredData'], $CIDRAM['L10N']['ConfiguredDataArray']);

/** Load the L10N fallback data. */
foreach ($CIDRAM['L10N']['Fallbacks'] as $CIDRAM['L10N']['ThisFallback']) {
    $CIDRAM['L10N']['FallbackData'] .= $CIDRAM['ReadFile']($CIDRAM['L10N']['ThisFallback']);
}

/** Parse the L10N fallback data. */
$CIDRAM['YAML']->process($CIDRAM['L10N']['FallbackData'], $CIDRAM['L10N']['FallbackDataArray']);

/** Build final L10N object. */
$CIDRAM['L10N'] = new \Maikuolan\Common\L10N($CIDRAM['L10N']['ConfiguredDataArray'], $CIDRAM['L10N']['FallbackDataArray']);

/** Assign language rules. */
if ($CIDRAM['Config']['general']['lang'] === 'en') {
    $CIDRAM['L10N']->autoAssignRules('en');
} else {
    $CIDRAM['L10N']->autoAssignRules($CIDRAM['Config']['general']['lang'], 'en');
}

/** Load client-specified L10N data if it's possible to do so. */
if (!$CIDRAM['Config']['general']['lang_override'] || empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $CIDRAM['Client-L10N'] = &$CIDRAM['L10N'];
    $CIDRAM['L10N-Lang-Attache'] = '';
} else {
    $CIDRAM['Client-L10N'] = [];
    $CIDRAM['Client-L10N-Accepted'] = preg_replace(
        ['~^([^,]*).*$~', '~[^-a-z]~'],
        ['\1', ''],
        strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE'])
    );
    if (
        $CIDRAM['Config']['general']['lang'] !== $CIDRAM['Client-L10N-Accepted'] &&
        is_readable($CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Client-L10N-Accepted'] . '.yaml')
    ) {
        $CIDRAM['Client-L10N']['Data'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Client-L10N-Accepted'] . '.yaml');
    }
    if (empty($CIDRAM['Client-L10N']['Data'])) {
        $CIDRAM['Client-L10N-Accepted'] = preg_replace('~^([^-]*).*$~', '\1', $CIDRAM['Client-L10N-Accepted']);
        if (
            $CIDRAM['Config']['general']['lang'] !== $CIDRAM['Client-L10N-Accepted'] &&
            is_readable($CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Client-L10N-Accepted'] . '.yaml')
        ) {
            $CIDRAM['Client-L10N']['Data'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Client-L10N-Accepted'] . '.yaml');
        }
    }

    /** Process client-specific L10N data. */
    if (empty($CIDRAM['Client-L10N']['Data'])) {
        $CIDRAM['L10N-Lang-Attache'] = '';
        $CIDRAM['Client-L10N'] = [];
    } else {
        $CIDRAM['Client-L10N']['DataArray'] = [];
        $CIDRAM['YAML']->process($CIDRAM['Client-L10N']['Data'], $CIDRAM['Client-L10N']['DataArray']);
        $CIDRAM['L10N-Lang-Attache'] = ($CIDRAM['Config']['general']['lang'] === $CIDRAM['Client-L10N-Accepted']) ? '' : sprintf(
            ' lang="%s" dir="%s"',
            $CIDRAM['Client-L10N-Accepted'],
            isset($CIDRAM['Client-L10N']['DataArray']['Text Direction']) ? $CIDRAM['Client-L10N']['DataArray']['Text Direction'] : 'ltr'
        );
        $CIDRAM['Client-L10N'] = $CIDRAM['Client-L10N']['DataArray'];
    }

    /** Build final client-specific L10N object. */
    $CIDRAM['Client-L10N'] = new \Maikuolan\Common\L10N($CIDRAM['Client-L10N'], $CIDRAM['L10N']);
    $CIDRAM['Client-L10N']->autoAssignRules($CIDRAM['Client-L10N-Accepted']);
    unset($CIDRAM['Client-L10N-Accepted']);
}
