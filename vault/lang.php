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
 * This file: Language handler (last modified: 2019.04.21).
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

/** If the language directive isn't set to English, we'll use English as the fallback. */
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
$CIDRAM['L10N']['ConfiguredData'] = (new \Maikuolan\Common\YAML($CIDRAM['L10N']['ConfiguredData']))->Data;

/** Load the L10N fallback data. */
foreach ($CIDRAM['L10N']['Fallbacks'] as $CIDRAM['L10N']['ThisFallback']) {
    $CIDRAM['L10N']['FallbackData'] .= $CIDRAM['ReadFile']($CIDRAM['L10N']['ThisFallback']);
}

/** Parse the L10N fallback data. */
$CIDRAM['L10N']['FallbackData'] = (new \Maikuolan\Common\YAML($CIDRAM['L10N']['FallbackData']))->Data;

/** Build final L10N object. */
$CIDRAM['L10N'] = new \Maikuolan\Common\L10N($CIDRAM['L10N']['ConfiguredData'], $CIDRAM['L10N']['FallbackData']);

/** Reference L10N object's contained data to ensure things don't break until we can properly implement the new object. */
$CIDRAM['lang'] = &$CIDRAM['L10N']->Data;

/** Temporary hotfix for missing textDir variable. */
$CIDRAM['lang']['textDir'] = (isset($CIDRAM['lang']['Text Direction']) && $CIDRAM['lang']['Text Direction'] === 'rtl') ? 'rtl' : 'ltr';

/** Will remove later (temporary variable). */
$CIDRAM['Config']['general']['lang_override'] = false;

/** Load user language overrides if possible and enabled. */
if ($CIDRAM['Config']['general']['lang_override'] && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $CIDRAM['lang_user'] = $CIDRAM['lang'];
    $CIDRAM['user_lang'] = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    if (($CIDRAM['lang_pos'] = strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], ',')) !== false) {
        $CIDRAM['user_lang'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, $CIDRAM['lang_pos']);
    }
    if (
        empty($CIDRAM['Config']['Config Defaults']['general']['lang']['choices'][$CIDRAM['user_lang']]) &&
        ($CIDRAM['lang_pos'] = strpos($CIDRAM['user_lang'], '-')) !== false
    ) {
        $CIDRAM['user_lang'] = substr($CIDRAM['user_lang'], 0, $CIDRAM['lang_pos']);
        if (empty($CIDRAM['Config']['Config Defaults']['general']['lang']['choices'][$CIDRAM['user_lang']])) {
            $CIDRAM['user_lang'] = '';
        }
    }

    /** Load the necessary language data. */
    if (
        $CIDRAM['user_lang'] &&
        $CIDRAM['user_lang'] !== $CIDRAM['Config']['general']['lang'] &&
        file_exists($CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['user_lang'] . '.php')
    ) {
        // rewrite this!!!
        require $CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['user_lang'] . '.php';
    }

    $CIDRAM['Swap']($CIDRAM['lang_user'], $CIDRAM['lang']);
    unset($CIDRAM['user_lang'], $CIDRAM['lang_pos']);
} else {
    $CIDRAM['lang_user'] = &$CIDRAM['lang'];
}
