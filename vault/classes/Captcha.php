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
    private function meld()
    {
        $Strings = func_get_args();
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

    /**
     * Determine the theme to use.
     *
     * @return string The theme to use (light or dark).
     */
    private function determineTheme()
    {
        if (!isset(
            $this->CIDRAM['Config']['template_data']['theme'],
            $this->CIDRAM['Config']['Config Defaults']['template_data']['theme']['lightdark'][$CIDRAM['Config']['template_data']['theme']]
        ) {
            return 'light';
        }
        return $this->CIDRAM['Config']['Config Defaults']['template_data']['theme']['lightdark'][$CIDRAM['Config']['template_data']['theme']];
    }

    /**
     * Generate data for failed attempts.
     *
     * @return void
     */
    private function generateFailed()
    {
        /** Set CAPTCHA status. */
        $this->CIDRAM['BlockInfo']['CAPTCHA'] = $this->CIDRAM['L10N']->getString('state_failed');

        /** Append to reCAPTCHA statistics if necessary. */
        if ($this->CIDRAM['Config']['general']['statistics']) {
            $this->CIDRAM['Statistics']['CAPTCHAs-Failed']++;
            $this->CIDRAM['Statistics-Modified'] = true;
        }
    }

    /**
     * Generate data for passed attempts.
     *
     * @return void
     */
    private function generatePassed()
    {
        /** Set CAPTCHA status. */
        $this->CIDRAM['BlockInfo']['CAPTCHA'] = $this->CIDRAM['L10N']->getString('state_passed');

        /** Append to reCAPTCHA statistics if necessary. */
        if ($this->CIDRAM['Config']['general']['statistics']) {
            $this->CIDRAM['Statistics']['CAPTCHAs-Passed']++;
            $this->CIDRAM['Statistics-Modified'] = true;
        }
    }
}
