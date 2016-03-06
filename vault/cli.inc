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
 * This file: CLI handler (last modified: 2016.03.07).
 *
 * @package Maikuolan/CIDRAM
 */

/**
 * For testing IPv4 in CLI:
 * var_dump($CIDRAM['IPv4Test']($argv[1],true));
 *
 * For testing IPv6 in CLI:
 * var_dump($CIDRAM['IPv6Test']($argv[1],true));
 *
 * CLI usage:
 * php.exe /path/to/script.php %IPAddr%
 *
 * Future CLI-mode code will be put in this file.
 */
