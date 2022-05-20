<?php
/**
 * This file is an optional extension of the CIDRAM package.
 * Homepage: https://cidram.github.io/
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: Methods used by the configuration page and configuration filters (last modified: 2022.05.20).
 */

namespace CIDRAM\CIDRAM;

trait Configuration
{
    /**
     * Filter the available language options provided by the configuration page
     * on the basis of the availability of the corresponding language files.
     *
     * @param string $ChoiceKey Language code.
     * @return bool Valid/Invalid.
     */
    private function filterLang(string $ChoiceKey): bool
    {
        $Core = $this->Vault . 'l10n' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . $ChoiceKey;
        $FrontEnd = $this->Vault . 'l10n' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . $ChoiceKey;
        return (file_exists($Core . '.yml') && file_exists($FrontEnd . '.yml'));
    }

    /**
     * Filter the available hash algorithms provided by the configuration page
     * on the basis of availability.
     *
     * @param string $ChoiceKey Hash algorithm.
     * @return bool Available/Unavailable.
     */
    private function filterByDefined(string $ChoiceKey): bool
    {
        return defined($ChoiceKey);
    }

    /**
     * Filter the available theme options provided by the configuration page on
     * the basis of availability.
     *
     * @param string $ChoiceKey Theme ID.
     * @return bool Valid/Invalid.
     */
    private function filterThemeCore(string $ChoiceKey): bool
    {
        return ($ChoiceKey === 'default') ? true : file_exists($this->Vault . 'assets/core/template_' . $ChoiceKey . '.html');
    }

    /**
     * Filter the available theme options provided by the configuration page on
     * the basis of availability.
     *
     * @param string $ChoiceKey Theme ID.
     * @return bool Valid/Invalid.
     */
    private function filterThemeFrontEnd(string $ChoiceKey): bool
    {
        return ($ChoiceKey === 'default') ? true : file_exists($this->Vault . 'assets/frontend/' . $ChoiceKey . '/frontend.css');
    }

    /**
     * Replaces labels with corresponding L10N data (if there's any).
     *
     * @param string $Label The actual label.
     * @return void
     */
    private function replaceLabelWithL10n(string &$Label): void
    {
        foreach (['', 'response_', 'label_', 'field_'] as $Prefix) {
            if (isset($this->L10N->Data[$Prefix . $Label])) {
                $Label = $this->L10N->getString($Prefix . $Label);
                return;
            }
        }
    }

    /**
     * Parses an array of L10N data references from L10N data to an array.
     *
     * @param string|array $References The L10N data references.
     * @return array An array of L10N data.
     */
    private function arrayFromL10nToArray($References): array
    {
        if (!is_array($References)) {
            $References = [$References];
        }
        $Out = [];
        foreach ($References as $Reference) {
            if (isset($this->L10N->Data[$Reference])) {
                $Reference = $this->L10N->Data[$Reference];
            }
            if (!is_array($Reference)) {
                $Reference = [$Reference];
            }
            foreach ($Reference as $Key => $Value) {
                if (is_int($Key)) {
                    $Out[] = $Value;
                    continue;
                }
                $Out[$Key] = $Value;
            }
        }
        return $Out;
    }
}
