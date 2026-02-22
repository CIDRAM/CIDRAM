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
 * This file: Methods used to simulate block events (last modified: 2026.02.22).
 */

namespace CIDRAM\CIDRAM;

trait SimulateBlockEvent
{
    /**
     * Simulates block events (used by the IP tracking and IP test pages).
     *
     * @param string $Addr The IP address to test against.
     * @param bool $BanCheck Switch for ban check.
     * @param bool $Tests Switch for signature file testing.
     * @param bool $Modules Switch for modules.
     * @param bool $SEV Switch for search engine verification.
     * @param bool $SMV Switch for social media verification.
     * @param bool $OV Switch for other verification.
     * @param bool $Aux Switch for auxiliary rules.
     * @return void
     */
    public function simulateBlockEvent(string $Addr, bool $BanCheck, bool $Tests = true, bool $Modules = false, bool $SEV = false, bool $SMV = false, bool $OV = false, bool $Aux = false): void
    {
        $this->Stage = '';
        $ConfiguredStages = $this->Stages;
        if ($BanCheck) {
            $this->Stages['BanCheck:Enable'] = true;
        } else {
            unset($this->Stages['BanCheck:Enable']);
        }
        if ($Tests) {
            $this->Stages['Tests:Enable'] = true;
        } else {
            unset($this->Stages['Tests:Enable']);
        }
        if ($Modules) {
            $this->Stages['Modules:Enable'] = true;
        } else {
            unset($this->Stages['Modules:Enable']);
        }
        if ($SEV) {
            $this->Stages['SearchEngineVerification:Enable'] = true;
        } else {
            unset($this->Stages['SearchEngineVerification:Enable']);
        }
        if ($SMV) {
            $this->Stages['SocialMediaVerification:Enable'] = true;
        } else {
            unset($this->Stages['SocialMediaVerification:Enable']);
        }
        if ($OV) {
            $this->Stages['OtherVerification:Enable'] = true;
        } else {
            unset($this->Stages['OtherVerification:Enable']);
        }
        if ($Aux) {
            $this->Stages['Aux:Enable'] = true;
        } else {
            unset($this->Stages['Aux:Enable']);
        }

        /** Reset bypass flags (needed to prevent falsing due to search engine verification). */
        $this->resetBypassFlags();

        /** Initialise SimulateBlockEvent. */
        foreach ($this->CIDRAM['Provide']['Initialise SimulateBlockEvent'] as $InitialiseKey => $InitialiseValue) {
            if (!property_exists($this, $InitialiseKey)) {
                continue;
            }
            if (is_array($InitialiseValue) && isset($this->$InitialiseKey) && is_array($this->$InitialiseKey)) {
                $this->$InitialiseKey = array_replace_recursive($this->$InitialiseKey, $InitialiseValue);
                continue;
            }
            $this->$InitialiseKey = $InitialiseValue;
        }

        /** To be populated by webhooks. */
        $this->Webhooks = [];

        /** Reset request profiling. */
        $this->Profiles = [];

        /** Reset factors. */
        $this->CIDRAM['Factors'] = [];

        $TestMode = $this->CIDRAM['TestMode'] ?? 1;
        $this->CIDRAM['ThisIP']['Assumption'] = '';
        if ($TestMode === 3) {
            $Query = $Addr;
            $Addr = isset($this->FE['ip-addr']) ? $this->correctFieldInput($this->FE['ip-addr']) : '';
            $UA = $this->FE['custom-ua'] ?? '';
        } elseif ($TestMode === 2) {
            $UA = $Addr;
            $Addr = isset($this->FE['ip-addr']) ? $this->correctFieldInput($this->FE['ip-addr']) : '';
            $Query = $this->FE['custom-query'] ?? '';
        } else {
            $UA = $this->FE['custom-ua'] ?? '';
            $Query = $this->FE['custom-query'] ?? '';
        }
        $UA = str_replace(['&quot;', '&gt;', '&lt;', '&amp;'], ['"', '>', '<', '&'], $UA) ?: 'SimulateBlockEvent';
        $Query = str_replace(['&quot;', '&gt;', '&lt;', '&amp;'], ['"', '>', '<', '&'], $Query) ?: 'SimulateBlockEvent';
        if (!empty($this->CIDRAM['Can state assumptions']) && isset($this->CIDRAM['Assumptions'][$Addr]) && $this->CIDRAM['Assumptions'][$Addr] !== $Addr) {
            $this->CIDRAM['ThisIP']['Assumption'] = '<br /><small>(' . sprintf($this->L10N->getString('label.Entered %s Assuming %s'), $this->CIDRAM['Assumptions'][$Addr], $Addr) . ')</small>';
        }

        /** Populate BlockInfo. */
        $this->BlockInfo = [
            'ID' => $this->generateId(),
            'ScriptIdent' => $this->ScriptIdent,
            'DateTime' => $this->FE['DateTime'],
            'IPAddr' => $Addr,
            'IPAddrResolved' => $this->resolve6to4($Addr),
            'Query' => $Query,
            'Referrer' => str_replace(['&quot;', '&gt;', '&lt;', '&amp;'], ['"', '>', '<', '&'], $this->FE['custom-referrer'] ?? '') ?: 'SimulateBlockEvent',
            'UA' => $UA,
            'UALC' => strtolower($UA),
            'SignatureCount' => 0,
            'Signatures' => '',
            'WhyReason' => '',
            'ReasonMessage' => '',
            'rURI' => 'SimulateBlockEvent',
            'ASNLookup' => 0,
            'CCLookup' => 'XX',
            'Verified' => '',
            'Expired' => '',
            'Ignored' => '',
            'Request_Method' => 'SimulateBlockEvent',
            'Protocol' => 'SimulateBlockEvent',
            'SEC_CH_UA_PLATFORM' => '',
            'SEC_CH_UA_MOBILE' => '',
            'SEC_CH_UA' => '',
            'Inspection' => '',
            'ClientL10NAccepted' => $this->ClientL10NAccepted,
            'xmlLang' => $this->L10NAccepted
        ];
        if (isset($this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']])) {
            $this->BlockInfo['Infractions'] = $this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']];
        } elseif (($Try = $this->Cache->getEntry('Tracking-' . $this->BlockInfo['IPAddr'])) === false) {
            $this->BlockInfo['Infractions'] = 0;
            $this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']] = 0;
        } else {
            $this->BlockInfo['Infractions'] = $this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']] = (int)$Try;
        }

        /** Appending query onto the reconstructed URI. */
        if ($Query !== 'SimulateBlockEvent' && $Query !== '') {
            $this->BlockInfo['rURI'] .= '?' . $Query;
        }

        /** Reset tokens. */
        $this->Tokens = [];

        /** Instantiate report orchestrator (used by some modules). */
        $this->Reporter = new Reporter($this->Events);

        /** Check whether banned. */
        if ($Addr !== '' && isset($this->Stages['BanCheck:Enable'])) {
            $this->Stage = 'BanCheck';
            $DoBan = false;
            if ($this->BlockInfo['Infractions'] >= $this->Configuration['signatures']['infraction_limit']) {
                $DoBan = true;
            } elseif ($Addr !== $this->BlockInfo['IPAddrResolved']) {
                $Try = $this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddrResolved']] ?? $this->Cache->getEntry('Tracking-' . $this->BlockInfo['IPAddrResolved']);
                if ($Try !== false && $Try >= $this->Configuration['signatures']['infraction_limit']) {
                    $DoBan = true;
                }
            }
            if ($DoBan) {
                $this->CIDRAM['Banned'] = true;
                $this->BlockInfo['ReasonMessage'] = $this->ClientL10N->getString('ReasonMessage.Banned') ?: $this->L10N->getString('ReasonMessage.Banned');
                $this->BlockInfo['WhyReason'] = $this->L10N->getString('Short.Banned');
                $this->BlockInfo['SignatureCount']++;
            }
            unset($DoBan);
        }

        if ($Tests && $Addr !== '') {
            $this->Stage = 'Tests';
            $this->initialiseErrorHandler();
            $Before = $this->BlockInfo['SignatureCount'];

            /** Execute signature files tests. */
            try {
                $this->CIDRAM['Caught'] = false;
                $this->CIDRAM['TestResults'] = $this->runTests($Addr, true);
            } catch (\Exception $e) {
                $this->CIDRAM['Caught'] = true;
            }

            /** Execute for resolved IP address if necessary. */
            if ($this->BlockInfo['IPAddrResolved']) {
                if (!empty($this->CIDRAM['ThisIP']['IPAddress'])) {
                    $this->CIDRAM['ThisIP']['IPAddress'] .= ' (' . $this->BlockInfo['IPAddrResolved'] . ')';
                }
                try {
                    $this->CIDRAM['TestResults'] = ($this->runTests($this->BlockInfo['IPAddrResolved'], true) || $this->CIDRAM['TestResults']);
                } catch (\Exception $e) {
                    $this->CIDRAM['Caught'] = true;
                }
            }

            if (isset($this->Stages['Tests:Tracking']) && $this->BlockInfo['SignatureCount'] !== $Before) {
                $this->BlockInfo['Infractions'] += $this->BlockInfo['SignatureCount'] - $Before;
            }
            $this->CIDRAM['RunErrors'] = $this->CIDRAM['Errors'];
            $this->restoreErrorHandler();
        }

        /** Perform forced hostname lookup if this has been enabled. */
        if ($this->Configuration['general']['force_hostname_lookup']) {
            $this->Stage = '';
            $this->CIDRAM['Hostname'] = $this->dnsReverse($this->BlockInfo['IPAddrResolved'] ?: $this->BlockInfo['IPAddr']);
        }

        /** Execute modules, if any have been enabled. */
        if ($Modules && $this->Configuration['components']['modules'] !== '' && empty($this->CIDRAM['Whitelisted'])) {
            $this->Stage = 'Modules';
            if (!isset($this->CIDRAM['ModuleResCache'])) {
                $this->CIDRAM['ModuleResCache'] = [];
            }
            $this->initialiseErrorHandler();
            $Modules = explode("\n", $this->Configuration['components']['modules']);
            if (!$this->Configuration['signatures']['tracking_override']) {
                $RestoreTrackingOptionsOverride = $this->CIDRAM['Tracking options override'] ?? '';
            }

            /**
             * Doing this with array_walk instead of foreach to ensure that modules
             * have their own scope and that superfluous data isn't preserved.
             */
            array_walk($Modules, function ($Module): void {
                if (!empty($this->CIDRAM['Whitelisted'])) {
                    return;
                }
                $Module = (strpos($Module, ':') === false) ? $Module : substr($Module, strpos($Module, ':') + 1);
                $Before = $this->BlockInfo['SignatureCount'];
                if (isset($this->CIDRAM['ModuleResCache'][$Module]) && is_object($this->CIDRAM['ModuleResCache'][$Module])) {
                    $this->CIDRAM['ModuleResCache'][$Module]();
                } elseif (!$this->isReserved($Module) && is_readable($this->ModulesPath . $Module)) {
                    require $this->ModulesPath . $Module;
                }
                if (isset($this->Stages['Modules:Tracking']) && $this->BlockInfo['SignatureCount'] !== $Before) {
                    $this->BlockInfo['Infractions'] += $this->BlockInfo['SignatureCount'] - $Before;
                }
            });

            if (
                !$this->Configuration['signatures']['tracking_override'] &&
                !empty($this->CIDRAM['Tracking options override']) &&
                isset($RestoreTrackingOptionsOverride)
            ) {
                $this->CIDRAM['Tracking options override'] = $RestoreTrackingOptionsOverride;
            }

            $this->CIDRAM['ModuleErrors'] = $this->CIDRAM['Errors'];
            $this->restoreErrorHandler();
        }

        /** Execute search engine verification. */
        if ($SEV && empty($this->CIDRAM['Whitelisted'])) {
            $this->Stage = 'SearchEngineVerification';
            $this->searchEngineVerification();
        }

        /** Execute social media verification. */
        if ($SMV && empty($this->CIDRAM['Whitelisted'])) {
            $this->Stage = 'SocialMediaVerification';
            $this->socialMediaVerification();
        }

        /** Execute other verification. */
        if ($OV && empty($this->CIDRAM['Whitelisted'])) {
            $this->Stage = 'OtherVerification';
            $this->otherVerification();
        }

        /** Execute auxiliary rules, if any exist. */
        if ($Aux && empty($this->CIDRAM['Whitelisted'])) {
            $this->Stage = 'Aux';
            $this->initialiseErrorHandler();
            $Before = $this->BlockInfo['SignatureCount'];
            $this->aux();
            if (isset($this->Stages['Aux:Tracking']) && $this->BlockInfo['SignatureCount'] !== $Before) {
                $this->BlockInfo['Infractions'] += $this->BlockInfo['SignatureCount'] - $Before;
            }
            $this->CIDRAM['AuxErrors'] = $this->CIDRAM['Errors'];
            $this->restoreErrorHandler();
        }

        $this->Stages = $ConfiguredStages;
        unset($ConfiguredStages);

        /**
         * Destroying the reporter (we won't process reports in this case, because we're only simulating block events,
         * as opposed to checking against actual, real requests; still needed to set it though to prevent errors).
         */
        $this->Stage = 'Reporting';
        $this->Reporter = null;
        $this->Stage = '';

        /** Clear to prevent potential interference with later execution within the same request. */
        unset($this->CIDRAM['Trigger notifications']);

        /** Fix for non-integer status codes. */
        $this->CIDRAM['Aux Status Code'] = empty($this->CIDRAM['Aux Status Code']) ? 0 : (int)$this->CIDRAM['Aux Status Code'];

        /**
         * Determine HTTP status code. Precedence (from highest to lowest):
         * 1. Silent mode (3xx).
         * 2. Banned due to exceeding the infraction limit (general.http_response_header_code.banned).
         * 3. Rate limiting (429); Resource conflicts (signatures.conflict_response).
         * 4. Auxiliary rules which set a "HTTP status code override" when blocking (4xx/5xx).
         * 5. Blocked for legal reasons (general.http_response_header_code.legal).
         * 6. Blocked for other reasons (general.http_response_header_code.default).
         * 7. Auxiliary rules which set a "HTTP status code override" when redirecting (3xx).
         * 8. Not blocked and CAPTCHA required (captcha.nonblocked_status_code.*).
         * 9. Not blocked and no CAPTCHA required (200).
         */
        if ($this->BlockInfo['SignatureCount'] > 0) {
            $this->CIDRAM['ThisStatusHTTP'] = (
                ($this->Configuration['general']['silent_mode'] !== '' && ($Try = (
                    $this->Configuration['general']['silent_mode_response_header_code'] > 300 &&
                    $this->Configuration['general']['silent_mode_response_header_code'] < 309
                ) ? $this->Configuration['general']['silent_mode_response_header_code'] : 301)) ||
                (!empty($this->CIDRAM['Banned']) && $this->Configuration['general']['http_response_header_code']['banned'] >= 200 && ($Try = $this->Configuration['general']['http_response_header_code']['banned'])) ||
                (!empty($this->CIDRAM['Other Status']) && !empty($this->CIDRAM['Other Status Code']) && ($Try = $this->CIDRAM['Other Status Code'])) ||
                ($this->CIDRAM['Aux Status Code'] > 400 && ($Try = $this->CIDRAM['Aux Status Code'])) ||
                (!empty($this->CIDRAM['Legal block triggered']) && $this->Configuration['general']['http_response_header_code']['legal'] >= 200 && ($Try = $this->Configuration['general']['http_response_header_code']['legal'])) ||
                ($this->Configuration['general']['http_response_header_code']['default'] >= 200 && ($Try = $this->Configuration['general']['http_response_header_code']['default']))
            ) ? $Try : '200 OK';
        } elseif (!empty($this->CIDRAM['Aux Redirect']) && $this->CIDRAM['Aux Status Code'] > 300 && $this->CIDRAM['Aux Status Code'] < 400 && ($Try = $this->CIDRAM['Aux Status Code'])) {
            $this->CIDRAM['ThisStatusHTTP'] = $Try;
        } else {
            $this->CIDRAM['ThisStatusHTTP'] = '200 OK';
            if (empty($this->CIDRAM['Whitelisted']) && empty($this->BlockInfo['Verified'])) {
                foreach ([
                    ['hcaptcha_sitekey', 'hcaptcha_secret', 'HCaptcha', 'hcaptcha'],
                    ['friendly_sitekey', 'friendly_apikey', 'FriendlyCaptcha', 'friendly'],
                    ['turnstile_sitekey', 'turnstile_secret', 'CloudflareTurnstile', 'cloudflare']
                ] as $CAPTCHA) {
                    if (
                        $this->Configuration['captcha'][$CAPTCHA[0]] !== '' &&
                        $this->Configuration['captcha'][$CAPTCHA[1]] !== '' &&
                        (
                            ($this->Configuration['captcha']['usemode'][$CAPTCHA[3]] >= 3 && $this->Configuration['captcha']['usemode'][$CAPTCHA[3]] <= 5) ||
                            ($this->Configuration['captcha']['usemode'][$CAPTCHA[3]] === 6 && (
                                isset($this->BlockInfo['rURI']) &&
                                $this->isSensitive(preg_replace('/\s/', '', strtolower($this->BlockInfo['rURI'])))
                            ))
                        )
                    ) {
                        $this->CIDRAM['ThisStatusHTTP'] = $this->Configuration['captcha']['nonblocked_status_code'][$CAPTCHA[3]];
                        break;
                    }
                }
            }
        }
        if (is_int($this->CIDRAM['ThisStatusHTTP'])) {
            if (($Try = $this->getStatusHTTP($this->CIDRAM['ThisStatusHTTP'])) !== '') {
                $this->CIDRAM['ThisStatusHTTP'] .= ' ' . $Try;
            } elseif ($this->CIDRAM['ThisStatusHTTP'] === 200) {
                $this->CIDRAM['ThisStatusHTTP'] .= ' ' . $this->L10N->getString('field.OK');
            } else {
                $this->CIDRAM['ThisStatusHTTP'] .= ' ' . $this->L10N->getString('field.Unknown');
            }
        }
    }

    /**
     * Public API lookup method.
     *
     * @param string|array $Addr An address or array of addresses to look up.
     * @param bool $Modules True to enable testing against modules.
     * @param bool $Aux True to enable testing against auxiliary rules.
     * @param bool $Verification True to verify search engines et al.
     * @param string $UA An optional custom user agent to cite for the lookup.
     * @param string $UA An optional custom referrer to cite for the lookup.
     * @return array The results of the lookup.
     */
    public function lookup($Addr = '', bool $Modules = false, bool $Aux = false, bool $Verification = false, string $Query = '', string $Referrer = '', string $UA = ''): array
    {
        $this->initialiseCache();
        $this->FE = ['DateTime' => $this->timeFormat($this->Now, $this->Configuration['general']['time_format'])];
        if ($this->Stages === []) {
            $this->Stages = array_flip(explode("\n", $this->Configuration['general']['stages']));
        }
        if ($this->Shorthand === []) {
            $this->Shorthand = array_flip(explode("\n", $this->Configuration['signatures']['shorthand']));
        }
        if (strlen($Query)) {
            $this->FE['custom-query'] = $Query;
        }
        if (strlen($Referrer)) {
            $this->FE['custom-referrer'] = $Referrer;
        }
        if (strlen($UA)) {
            $this->FE['custom-ua'] = $UA;
        }
        if (is_array($Addr)) {
            $Results = [];
            foreach ($Addr as $ThisAddr) {
                $this->simulateBlockEvent($ThisAddr, true, true, $Modules, $Verification, $Verification, $Verification, $Aux);
                $Results[$ThisAddr] = $this->BlockInfo;
            }
            return $Results;
        }
        $this->simulateBlockEvent($Addr, true, true, $Modules, $Verification, $Verification, $Verification, $Aux);
        return $this->BlockInfo;
    }

    /**
     * Perform corrections to IP address field input for IP testing.
     *
     * @param string $Input The input to correct.
     * @return string The corrected input.
     */
    private function correctFieldInput(string $Input = ''): string
    {
        return preg_replace(['~([.:])x(?:/.+)?$~i', '~[/, ].*$|&[a-z]+;|(?!.*:)(?<!:.{0,4})[^\d.](?!.*:)|(?!.*\.)(?<!\..{0,3})[^\da-f:](?!.*\.)~i', '~\.{2,}~', '~:{3,}~'], ['{\1}0', '', '.', '::'], $Input);
    }
}
