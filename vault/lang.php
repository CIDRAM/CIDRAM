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
 * This file: Language handler (last modified: 2018.09.25).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** Swaps two variables (in PHP7: "[$First, $Second] = [$Second, $First];"). */
$CIDRAM['Swap'] = function (&$First, &$Second) {
    $Working = $First;
    $First = $Second;
    $Second = $Working;
};

/** Default language plurality rule. */
$CIDRAM['Plural-Rule'] = function ($Num) {
    return $Num === 1 ? 0 : 1;
};

/** Select string based on plural rule. */
$CIDRAM['Plural'] = function ($Num, $String) use (&$CIDRAM) {
    if (!is_array($String)) {
        return $String;
    }
    $Choice = $CIDRAM['Plural-Rule']($Num);
    if (!empty($String[$Choice])) {
        return $String[$Choice];
    }
    return empty($String[0]) ? '' : $String[0];
};

/** If the language directive is empty, default to English. */
if (empty($CIDRAM['Config']['general']['lang'])) {
    $CIDRAM['Config']['general']['lang'] = 'en';
}

/** Create the language data array. */
$CIDRAM['lang'] = [];

if ($CIDRAM['CIDRAM_sapi'] && empty($CIDRAM['Alternate'])) {

    /**
     * Kill the script if the CLI-mode language data file corresponding to the
     * language directive (%CIDRAM%/vault/lang/lang.%%.cli.php) doesn't exist.
     */
    if (!file_exists($CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Config']['general']['lang'] . '.cli.php')) {
        header('Content-Type: text/plain');
        die('[CIDRAM] Language undefined or incorrectly defined. Can\'t continue.');
    }
    /** Load the necessary language data. */
    require $CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Config']['general']['lang'] . '.cli.php';

} else {

    /**
     * Kill the script if the language data file corresponding to the language
     * directive (%CIDRAM%/vault/lang/lang.%%.php) doesn't exist.
     */
    if (!file_exists($CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Config']['general']['lang'] . '.php')) {
        header('Content-Type: text/plain');
        die('[CIDRAM] Language undefined or incorrectly defined. Can\'t continue.');
    }
    /** Load the necessary language data. */
    require $CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Config']['general']['lang'] . '.php';

    /** Load front-end language data if necessary. */
    if (
        !$CIDRAM['Config']['general']['disable_frontend'] &&
        file_exists($CIDRAM['Vault'] . 'frontend.php') &&
        file_exists($CIDRAM['Vault'] . 'fe_assets/frontend.html') &&
        ($CIDRAM['Direct']() || !empty($CIDRAM['Alternate']))
    ) {
        /**
         * Kill the script if the front-end language data file corresponding to
         * the language directive (%CIDRAM%/vault/lang/lang.%%.fe.php) doesn't
         * exist.
         */
        if (!file_exists($CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Config']['general']['lang'] . '.fe.php')) {
            header('Content-Type: text/plain');
            die('[CIDRAM] Language undefined or incorrectly defined. Can\'t continue.');
        }
        /** Load the necessary language data. */
        require $CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['Config']['general']['lang'] . '.fe.php';
    }

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
            require $CIDRAM['Vault'] . 'lang/lang.' . $CIDRAM['user_lang'] . '.php';
        }

        $CIDRAM['Swap']($CIDRAM['lang_user'], $CIDRAM['lang']);
        unset($CIDRAM['user_lang'], $CIDRAM['lang_pos']);
    } else {
        $CIDRAM['lang_user'] = &$CIDRAM['lang'];
    }

}
