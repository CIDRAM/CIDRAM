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
 * This file: Captcha class (last modified: 2023.02.04).
 */

namespace CIDRAM\CIDRAM;

abstract class Captcha
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
                $Strings[$Index] = str_pad($Strings[$Index], $WalkLen, "\xFF");
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
            $this->CIDRAM->CIDRAM['FieldTemplates']['theme'],
            $this->CIDRAM->CIDRAM['Config Defaults']['template_data']['theme']['lightdark'][$this->CIDRAM->CIDRAM['FieldTemplates']['theme']]
        )) {
            return 'light';
        }
        return $this->CIDRAM->CIDRAM['Config Defaults']['template_data']['theme']['lightdark'][$this->CIDRAM->CIDRAM['FieldTemplates']['theme']];
    }

    /**
     * Generate data for failed attempts.
     *
     * @return void
     */
    public function generateFailed(): void
    {
        /** Set CAPTCHA status. */
        $this->CIDRAM->BlockInfo['CAPTCHA'] = $this->CIDRAM->L10N->getString('state_failed');

        /** Append to reCAPTCHA statistics if necessary. */
        if (isset($this->CIDRAM->Stages['Statistics:Enable'], $this->CIDRAM->StatisticsTracked['CAPTCHAs-Failed'])) {
            $this->CIDRAM->Cache->incEntry('Statistics-CAPTCHAs-Failed');
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
        $this->CIDRAM->BlockInfo['CAPTCHA'] = $this->CIDRAM->L10N->getString('state_passed');

        /** Append to reCAPTCHA statistics if necessary. */
        if (isset($this->CIDRAM->Stages['Statistics:Enable'], $this->CIDRAM->StatisticsTracked['CAPTCHAs-Passed'])) {
            $this->CIDRAM->Cache->incEntry('Statistics-CAPTCHAs-Passed');
        }
    }

    /**
     * Fetch the salt file or generate it if it doesn't exist.
     *
     * @return string The salt.
     */
    public function generateSalt(): string
    {
        if (!is_readable($this->CIDRAM->Vault . 'salt.dat')) {
            $Salt = $this->CIDRAM->generateSalt();
            if (is_writable($this->CIDRAM->Vault)) {
                $Handle = fopen($this->CIDRAM->Vault . 'salt.dat', 'wb');
                fwrite($Handle, $Salt);
                fclose($Handle);
            }
            return $Salt;
        }
        return $this->CIDRAM->readFile($this->CIDRAM->Vault . 'salt.dat');
    }

    /**
     * Clears expired entries from a list.
     *
     * @param string $List The list to clear from.
     * @param bool $Check A flag indicating when changes have occurred.
     * @return void
     */
    public function clearExpired(string &$List, bool &$Check): void
    {
        if (strlen($List) === 0) {
            return;
        }
        $End = 0;
        while (true) {
            $Begin = $End;
            if (!$End = strpos($List, "\n", $Begin + 1)) {
                break;
            }
            $Line = substr($List, $Begin, $End - $Begin);
            if ($Split = strrpos($Line, ',')) {
                $Expiry = (int)substr($Line, $Split + 1);
                if ($Expiry < $this->CIDRAM->Now) {
                    $List = str_replace($Line, '', $List);
                    $End = 0;
                    $Check = true;
                }
            }
        }
    }
}
