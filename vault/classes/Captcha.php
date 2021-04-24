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
 * This file: Captcha class (last modified: 2021.04.24).
 */

namespace CIDRAM\Core;

class Captcha
{
    /**
     * @var string Verification results.
     */
    private $Results = '';

    /**
     * @var string Appended to template data.
     */
    private $TemplateInsert = '<input type="hidden" id="hostnameoverride" name="hostname" value="">';

    /**
     * @var bool Whether to bypass the request.
     */
    private $Bypass = false;

    /**
     * @var array The main CIDRAM array passed by reference.
     */
    private $CIDRAM;

    /**
     * Meld together two or more strings by padding to equal length and
     * bitshifting each by each other.
     *
     * @return string The melded string.
     */
    private function meld(string ...$Strings): string
    {
        $StrLens = array_map('strlen', $Strings);
        $WalkLen = max($StrLens);
        $Count = count($Strings);
        for ($Index = 0; $Index < $Count; $Index++) {
            if ($StrLens[$Index] < $WalkLen) {
                $Strings[$Index] = str_pad($Strings[$Index], $WalkLen, "\xff");
            }
        }
        for ($Lt = $Strings[0], $Index = 1, $Meld = ''; $Index < $Count; $Index++, $Meld = '') {
            $Rt = $Strings[$Index];
            for ($Caret = 0; $Caret < $WalkLen; $Caret++) {
                $Meld .= $Lt[$Caret] ^ $Rt[$Caret];
            }
            $Lt = $Meld;
        }
        $Meld = $Lt;
        return $Meld;
    }
}
