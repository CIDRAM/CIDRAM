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
 * This file: Methods used by the configuration page and configuration filters (last modified: 2023.10.12).
 */

namespace CIDRAM\CIDRAM;

trait Configuration
{
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
        return ($ChoiceKey === 'default') ? true : file_exists($this->AssetsPath . 'core/template_' . $ChoiceKey . '.html');
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
        return ($ChoiceKey === 'default') ? true : file_exists($this->AssetsPath . 'frontend/' . $ChoiceKey . '/frontend.css');
    }

    /**
     * Replaces labels with corresponding L10N data (if there's any).
     *
     * @param string $Label The actual label.
     * @return void
     */
    private function replaceLabelWithL10n(string &$Label): void
    {
        foreach (['', 'response.', 'label.', 'field.'] as $Prefix) {
            if (($Try = $this->L10N->getString($Prefix . $Label)) !== '') {
                $Label = $Try;
                return;
            }
        }
    }
}
