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
 * This file: Polyfills for PHP 5.4.X (last modified: 2020.11.29).
 */

if (!defined('PASSWORD_BCRYPT')) {
    define('PASSWORD_BCRYPT', 1);
}

if (!defined('PASSWORD_DEFAULT')) {
    define('PASSWORD_DEFAULT', PASSWORD_BCRYPT);
}

if (!function_exists('password_hash')) {
    function password_hash($Password, $Algo, array $Options = [])
    {
        $Cost = empty($Options['cost']) ? 10 : (int)$Options['cost'];
        if (empty($Options['salt'])) {
            if ($Algo === 1) {
                $CostLen = strlen($Cost);
                if ($Cost < 4) {
                    $Cost = 4;
                }
                if ($Cost > 31 || $CostLen > 2) {
                    $Cost = 31;
                } elseif ($CostLen < 2) {
                    $Cost = '0' . $Cost;
                }
                $Salt = '$2y$' . $Cost . '$';
                $Length = 22;
                $Range = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                while ($Length--) {
                    $Salt .= str_shuffle($Range)[0];
                }
            } else {
                $Salt = '';
            }
        } else {
            $Salt = (string)$Options['salt'];
        }
        return crypt($Password, $Salt);
    }
}

if (!function_exists('password_verify')) {
    function password_verify($Password, $Hash)
    {
        return (!empty($Password) && !empty($Hash) && $Hash === crypt($Password, $Hash));
    }
}
