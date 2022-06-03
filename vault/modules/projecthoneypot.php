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
 * This file: Project Honeypot module (last modified: 2022.05.18).
 *
 * False positive risk (an approximate, rough estimate only): « [ ]Low [x]Medium [ ]High »
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
     * Project Honeypot's HTTP:BL API currently can only handle IPv4 (i.e., not
     * IPv6). So, we shouldn't continue for the instance if the request isn't
     * originating from an IPv4 connection.
     */
    if (empty($this->CIDRAM['LastTestIP']) || $this->CIDRAM['LastTestIP'] !== 4) {
        return;
    }

    /**
     * We can't perform lookups without an API key, so we should check for that,
     * too.
     */
    if (empty($this->Configuration['projecthoneypot']['api_key'])) {
        return;
    }

    /** Normalised, lower-cased request URI; Used to determine whether the module needs to do anything for the request. */
    $LCURI = preg_replace('/\s/', '', strtolower($this->BlockInfo['rURI']));

    /** If the request isn't attempting to access a sensitive page (login, registration page, etc), exit. */
    if (!$this->Configuration['projecthoneypot']['lookup_strategy'] && !$this->isSensitive($LCURI)) {
        return;
    }

    /** Marks for use with reCAPTCHA and hCAPTCHA. */
    $EnableCaptcha = ['recaptcha' => ['enabled' => true], 'hcaptcha' => ['enabled' => true]];

    /** Local Project Honeypot cache entry expiry time (successful lookups). */
    $Expiry = $this->Now + 604800;

    /** Local Project Honeypot cache entry expiry time (failed lookups). */
    $ExpiryFailed = $this->Now + 3600;

    /** Build local Project Honeypot cache if it doesn't already exist. */
    $this->initialiseCacheSection('Project Honeypot');

    /**
     * Only execute if not already blocked for some other reason, if the IP is valid, if not from a private or reserved
     * range, and if the lookup limit hasn't already been exceeded (reduces superfluous lookups).
     */
    if (
        isset($this->CIDRAM['Project Honeypot']['429']) ||
        !$this->honourLookup() ||
        filter_var($this->BlockInfo['IPAddr'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false
    ) {
        return;
    }

    /** Executed if there aren't any cache entries corresponding to the IP of the request. */
    if (!isset($this->CIDRAM['Project Honeypot'][$this->BlockInfo['IPAddr']])) {
        /** Build the lookup query. */
        $Lookup = preg_replace(
            '~^(\d+)\.(\d+)\.(\d+)\.(\d+)$~',
            $this->Configuration['projecthoneypot']['api_key'] . '.\4.\3.\2.\1.dnsbl.httpbl.org',
            $this->BlockInfo['IPAddrResolved'] ?: $this->BlockInfo['IPAddr']
        );

        /** Perform Project Honeypot lookup. */
        $Data = dnsResolve($Lookup, $this->Configuration['projecthoneypot']['timeout_limit']);

        if ($this->Request->MostRecentStatusCode === 429) {
            /** Lookup limit has been exceeded. */
            $this->CIDRAM['Project Honeypot']['429'] = ['Time' => $Expiry];
        } else {
            /**
             * Validate or substitute.
             *
             * @link https://www.projecthoneypot.org/httpbl_api.php
             */
            if (preg_match('~^127\.\d+\.\d+\.\d+$~', $Data)) {
                $Data = explode('.', $Data);
                $Data = [
                    'Days since last activity' => $Data[1],
                    'Threat score' => $Data[2],
                    'Type of visitor' => $Data[3],
                    'Time' => $Expiry
                ];
            } else {
                $Data = [
                    'Days since last activity' => -1,
                    'Threat score' => -1,
                    'Type of visitor' => -1,
                    'Time' => $ExpiryFailed
                ];
            }

            /** Generate local Project Honeypot cache entry. */
            $this->CIDRAM['Project Honeypot'][$this->BlockInfo['IPAddr']] = $Data;
        }

        /** Cache update flag. */
        $this->CIDRAM['Project Honeypot-Modified'] = true;
    }

    /** Block the request if the IP is listed by Project Honeypot. */
    $this->trigger(
        (
            $this->CIDRAM['Project Honeypot'][$this->BlockInfo['IPAddr']]['Threat score'] >= $this->Configuration['projecthoneypot']['minimum_threat_score'] &&
            $this->CIDRAM['Project Honeypot'][$this->BlockInfo['IPAddr']]['Days since last activity'] <= $this->Configuration['projecthoneypot']['max_age_in_days']
        ),
        'Project Honeypot Lookup',
        $this->L10N->getString('ReasonMessage_Generic') . '<br />' . sprintf(
            $this->L10N->getString('request_removal'),
            'https://www.projecthoneypot.org/ip_' . ($this->BlockInfo['IPAddrResolved'] ?: $this->BlockInfo['IPAddr'])
        ),
        $this->CIDRAM['Project Honeypot'][$this->BlockInfo['IPAddr']]['Threat score'] <= $this->Configuration['projecthoneypot']['max_ts_for_captcha'] ? $EnableCaptcha : []
    );
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
