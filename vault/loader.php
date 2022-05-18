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
 * This file: The loader (last modified: 2022.05.18).
 */

/** Blocks direct access (must be accessed through a hook instead). */
if (
    !isset($_SERVER['SCRIPT_FILENAME']) ||
    str_replace("\\", '/', strtolower(realpath($_SERVER['SCRIPT_FILENAME']))) === str_replace("\\", '/', strtolower(__FILE__))
) {
    header('HTTP/1.0 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    header('Status: 403 Forbidden');
    die('[CIDRAM] This should not be accessed directly.');
}

/** Version check. */
if (!version_compare(PHP_VERSION, '7.2.0', '>=')) {
    header('Content-Type: text/plain');
    die('[CIDRAM] Not compatible with PHP versions below 7.2.0; Please update PHP in order to use CIDRAM.');
}

/** CIDRAM class autoloader. */
spl_autoload_register(function ($Class) {
    $Path = preg_split('~[\\\/]~', $Class, -1, PREG_SPLIT_NO_EMPTY);
    if (count($Path) > 3) {
        $Path = [$Path[0], $Path[1], array_pop($Path)];
    }
    $Path = __DIR__ . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $Path) . '.php';
    if (is_readable($Path)) {
        require $Path;
    }
});
