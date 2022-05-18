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
 * This file: Captcha class (last modified: 2022.05.18).
 */

namespace CIDRAM\CIDRAM;

class Captcha
{
    /**
     * @var string Verification results.
     */
    public $Results = '';

    /**
     * @var string Appended to template data.
     */
    public $TemplateInsert = '<input type="hidden" id="hostnameoverride" name="hostname" value="">';

    /**
     * @var bool Whether to bypass the request.
     */
    public $Bypass = false;

    /**
     * @var array The main CIDRAM array passed by reference.
     */
    public $CIDRAM;

    /**
     * Meld together two or more strings by padding to equal length and
     * bitshifting each by each other.
     *
     * @return string The melded string.
     */
    public function meld(string ...$Strings): string
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

    /**
     * Determine the theme to use.
     *
     * @return string The theme to use (light or dark).
     */
    public function determineTheme(): string
    {
        if (!isset(
            $this->CIDRAM['FieldTemplates']['theme'],
            $this->CIDRAM['Config Defaults']['template_data']['theme']['lightdark'][$this->CIDRAM['FieldTemplates']['theme']]
        )) {
            return 'light';
        }
        return $this->CIDRAM['Config Defaults']['template_data']['theme']['lightdark'][$this->CIDRAM['FieldTemplates']['theme']];
    }

    /**
     * Generate data for failed attempts.
     *
     * @return void
     */
    public function generateFailed(): void
    {
        /** Set CAPTCHA status. */
        $this->CIDRAM['BlockInfo']['CAPTCHA'] = $this->CIDRAM['L10N']->getString('state_failed');

        /** Append to reCAPTCHA statistics if necessary. */
        if (isset($this->Stages['Statistics:Enable'], $this->StatisticsTracked['CAPTCHAs-Failed'])) {
            $this->Statistics['CAPTCHAs-Failed']++;
            $this->CIDRAM['Statistics-Modified'] = true;
        }
    }

    /**
     * Generate data for passed attempts.
     *
     * @return void
     */
    public function generatePassed(): void
    {
        /** Set CAPTCHA status. */
        $this->CIDRAM['BlockInfo']['CAPTCHA'] = $this->CIDRAM['L10N']->getString('state_passed');

        /** Append to reCAPTCHA statistics if necessary. */
        if (isset($this->Stages['Statistics:Enable'], $this->StatisticsTracked['CAPTCHAs-Passed'])) {
            $this->Statistics['CAPTCHAs-Passed']++;
            $this->CIDRAM['Statistics-Modified'] = true;
        }
    }

    /**
     * Fetch the salt file or generate it if it doesn't exist.
     *
     * @return string The salt.
     */
    public function generateSalt(): string
    {
        if (!file_exists($this->CIDRAM['Vault'] . 'salt.dat')) {
            $Salt = $this->CIDRAM['GenerateSalt']();
            $Handle = fopen($this->CIDRAM['Vault'] . 'salt.dat', 'wb');
            fwrite($Handle, $Salt);
            fclose($Handle);
            return $Salt;
        }
        return $this->CIDRAM['ReadFile']($this->CIDRAM['Vault'] . 'salt.dat');
    }
}
