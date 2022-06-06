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
 * This file: Stop Forum Spam module (last modified: 2022.06.06).
 *
 * False positive risk (an approximate, rough estimate only): « [x]Low [ ]Medium [ ]High »
 */

/** Safety. */
if (!isset($this->CIDRAM['ModuleResCache'])) {
    $this->CIDRAM['ModuleResCache'] = [];
}

/** Defining as closure for later recall (no params; no return value). */
$this->CIDRAM['ModuleResCache'][$Module] = function () {
    /** Guard. */
    if (empty($this->BlockInfo['IPAddr'])) {
        return;
    }

    /**
     * Normalised, lower-cased request URI; Used to determine whether the
     * module needs to do anything for the request.
     */
    $LCURI = preg_replace('/\s/', '', strtolower($this->BlockInfo['rURI']));

    /**
     * If the request isn't attempting to access a sensitive page (login,
     * registration page, etc), exit.
     */
    if (!$this->isSensitive($LCURI)) {
        return;
    }

    /** Check whether the lookup limit has been exceeded. */
    if (!isset($this->CIDRAM['SFS-429'])) {
        $this->CIDRAM['SFS-429'] = $this->Cache->getEntry('SFS-429') ? true : false;
    }

    /**
     * Only execute if not already blocked for some other reason, if the IP is
     * valid, if not from a private or reserved range, and if the lookup limit
     * hasn't already been exceeded (reduces superfluous lookups).
     */
    if (
        $this->CIDRAM['SFS-429'] ||
        !$this->honourLookup() ||
        filter_var($this->BlockInfo['IPAddr'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false
    ) {
        return;
    }

    /** Marks for use with reCAPTCHA and hCAPTCHA. */
    $EnableCaptcha = ['recaptcha' => ['enabled' => true], 'hcaptcha' => ['enabled' => true]];

    /** Executed if there aren't any cache entries corresponding to the IP of the request. */
    if (
        !isset($this->CIDRAM['SFS-' . $this->BlockInfo['IPAddr']]) ||
        $this->CIDRAM['SFS-' . $this->BlockInfo['IPAddr']] === false
    ) {
        $this->CIDRAM['SFS-' . $this->BlockInfo['IPAddr']] = $this->Cache->getEntry('SFS-' . $this->BlockInfo['IPAddr']);
        if ($this->CIDRAM['SFS-' . $this->BlockInfo['IPAddr']] === false) {
            /** Perform SFS lookup. */
            $Lookup = $this->Request->request(
                'https://www.stopforumspam.com/api',
                ['ip' => $this->BlockInfo['IPAddr'], 'f' => 'serial'],
                $this->Configuration['sfs']['timeout_limit']
            );

            if ($this->Request->MostRecentStatusCode === 429) {
                /** Lookup limit has been exceeded. */
                $this->Cache->setEntry('SFS-429', true, 604800);
                $this->CIDRAM['SFS-429'] = true;
                return;
            }

            /** Generate local SFS cache entry. */
            if ((strpos($Lookup, 's:7:"success";') !== false) && (strpos($Lookup, 's:2:"ip";') !== false)) {
                if (preg_match('~"frequency";i:(\d+);"~i', $Lookup, $Frequency)) {
                    $Frequency = (int)$Frequency;
                } else {
                    $Frequency = 0;
                }
                $this->CIDRAM['SFS-' . $this->BlockInfo['IPAddr']] = $Frequency;
                $Expiry = 604800;
            } else {
                $this->CIDRAM['SFS-' . $this->BlockInfo['IPAddr']] = 0;
                $Expiry = 3600;
            }

            /** Update cache. */
            $this->Cache->setEntry(
                'SFS-' . $this->BlockInfo['IPAddr'],
                $this->CIDRAM['SFS-' . $this->BlockInfo['IPAddr']],
                $Expiry
            );
        }
    }

    /** Block the request if the IP is listed by SFS. */
    $this->trigger(
        (
            isset($this->CIDRAM['SFS-' . $this->BlockInfo['IPAddr']]) &&
            is_int($this->CIDRAM['SFS-' . $this->BlockInfo['IPAddr']]) &&
            $this->CIDRAM['SFS-' . $this->BlockInfo['IPAddr']] > 0
        ),
        'SFS Lookup',
        $this->L10N->getString('ReasonMessage_Generic') . '<br />' . sprintf($this->L10N->getString('request_removal'), 'https://www.stopforumspam.com/removal'),
        $this->Configuration['sfs']['offer_captcha'] ? $EnableCaptcha : []
    );
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
