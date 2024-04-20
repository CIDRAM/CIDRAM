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
 * This file: Methods used to simulate block events (last modified: 2024.04.19).
 */

namespace CIDRAM\CIDRAM;

trait SimulateBlockEvent
{
    /**
     * Simulates block events (used by the IP tracking and IP test pages).
     *
     * @param string $Addr The IP address to test against.
     * @param bool $Tests Switch for signature file testing.
     * @param bool $Modules Switch for modules.
     * @param bool $SEV Switch for search engine verification.
     * @param bool $SMV Switch for social media verification.
     * @param bool $OV Switch for other verification.
     * @param bool $Aux Switch for auxiliary rules.
     * @return void
     */
    public function simulateBlockEvent(string $Addr, bool $Tests = true, bool $Modules = false, bool $SEV = false, bool $SMV = false, bool $OV = false, bool $Aux = false): void
    {
        $this->Stage = '';
        $ConfiguredStages = $this->Stages;
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

        if (isset($this->CIDRAM['TestMode']) && $this->CIDRAM['TestMode'] === 2) {
            $UA = $Addr;
            $Addr = isset($this->FE['ip-addr-focus']) ? preg_replace('~[^\da-f:./]~i', '', $this->FE['ip-addr-focus']) : '';
        } elseif (isset($this->FE['custom-ua-focus']) && $this->FE['custom-ua-focus'] !== '') {
            $UA = $this->FE['custom-ua-focus'];
        } elseif (isset($this->FE['custom-ua']) && $this->FE['custom-ua'] !== '') {
            $UA = $this->FE['custom-ua'];
        } else {
            $UA = 'SimulateBlockEvent';
        }
        $UA = str_replace(['&quot;', '&gt;', '&lt;', '&amp;'], ['"', '>', '<', '&'], $UA);

        /** Populate BlockInfo. */
        $this->BlockInfo = [
            'ID' => $this->generateId(),
            'ScriptIdent' => $this->ScriptIdent,
            'DateTime' => $this->FE['DateTime'],
            'IPAddr' => $Addr,
            'IPAddrResolved' => $this->resolve6to4($Addr),
            'Query' => str_replace(['&quot;', '&gt;', '&lt;', '&amp;'], ['"', '>', '<', '&'], $this->FE['custom-query'] ?? '') ?: 'SimulateBlockEvent',
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
            'Inspection' => '',
            'xmlLang' => $this->L10NAccepted
        ];
        if (isset($this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']])) {
            $this->BlockInfo['Infractions'] = $this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']];
        } elseif (($Try = $this->Cache->getEntry('Tracking-' . $this->BlockInfo['IPAddr'])) === false) {
            $this->BlockInfo['Infractions'] = 0;
        } else {
            $this->BlockInfo['Infractions'] = $Try;
        }

        /** Appending query onto the reconstructed URI. */
        if (!empty($this->FE['custom-query'])) {
            $this->BlockInfo['rURI'] .= '?' . $this->FE['custom-query'];
        }

        /** Instantiate report orchestrator (used by some modules). */
        $this->Reporter = new Reporter($this->Events);

        /** Ban check (will split to its own stage for v4; e.g., 'BanCheck'; keeping as 'Tracking' for now to prevent BC breaks between minor/patch releases). */
        if ($Addr !== '') {
            $this->Stage = 'Tracking';
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
                $this->BlockInfo['ReasonMessage'] = $this->L10N->getString('ReasonMessage_Banned');
                $this->BlockInfo['WhyReason'] = $this->L10N->getString('Short_Banned');
                $this->BlockInfo['SignatureCount']++;
            }
            unset($DoBan);
            $this->Stage = '';
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
            $this->Stage = '';
        }

        /** Perform forced hostname lookup if this has been enabled. */
        if ($this->Configuration['general']['force_hostname_lookup']) {
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

        /**
         * Determine HTTP status code. Priority (from highest to lowest):
         * - silent_mode(30x)
         * - ban_override(4xx~5xx)
         * - rate_limiting(429)
         * - Auxiliary Rules(4xx~5xx)
         * - http_response_header_code(4xx~5xx)
         * - Auxiliary Rules(30x)
         * - Other (or 200 if not blocked)
         * Block event simulation doesn't account for:
         * - nonblocked_status_code(4xx) (CAPTCHA completion status determined at request time).
         */
        if ($this->BlockInfo['SignatureCount'] > 0) {
            $this->CIDRAM['ThisStatusHTTP'] = (
                ($this->Configuration['general']['silent_mode'] !== '' && ($Try = (
                    $this->Configuration['general']['silent_mode_response_header_code'] > 300 &&
                    $this->Configuration['general']['silent_mode_response_header_code'] < 309
                ) ? $this->Configuration['general']['silent_mode_response_header_code'] : 301)) ||
                (!empty($this->CIDRAM['Banned']) && $this->Configuration['general']['ban_override'] > 400 && ($Try = $this->Configuration['general']['ban_override'])) ||
                (!empty($this->CIDRAM['RL_Status']) && $this->BlockInfo['SignatureCount'] === 1 && ($Try = 429)) ||
                (!empty($this->CIDRAM['Aux Status Code']) && $this->CIDRAM['Aux Status Code'] > 400 && ($Try = $this->CIDRAM['Aux Status Code'])) ||
                ($this->Configuration['general']['http_response_header_code'] > 400 && ($Try = $this->Configuration['general']['http_response_header_code'])) ||
                (!empty($this->CIDRAM['ThisStatusHTTP']) && $this->CIDRAM['ThisStatusHTTP'] !== 200 && ($Try = $this->CIDRAM['ThisStatusHTTP']))
            ) ? $Try : '200 OK';
        } else {
            $this->CIDRAM['ThisStatusHTTP'] = (
                (!empty($this->CIDRAM['Aux Redirect']) && !empty($this->CIDRAM['Aux Status Code']) && $this->CIDRAM['Aux Status Code'] > 300 && $this->CIDRAM['Aux Status Code'] < 400 && ($Try = $this->CIDRAM['Aux Status Code'])) ||
                (!empty($this->CIDRAM['ThisStatusHTTP']) && $this->CIDRAM['ThisStatusHTTP'] !== 200 && ($Try = $this->CIDRAM['ThisStatusHTTP']))
            ) ? $Try : '200 OK';
        }
        if (is_int($this->CIDRAM['ThisStatusHTTP']) && ($Try = $this->getStatusHTTP($this->CIDRAM['ThisStatusHTTP'])) !== '') {
            $this->CIDRAM['ThisStatusHTTP'] .= ' ' . $Try;
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
        if (!($this->Cache instanceof \Maikuolan\Common\Cache)) {
            $this->initialiseCache();
        }
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
                $this->simulateBlockEvent($ThisAddr, true, $Modules, $Verification, $Verification, $Verification, $Aux);
                $Results[$ThisAddr] = $this->BlockInfo;
            }
            return $Results;
        }
        $this->simulateBlockEvent($Addr, true, $Modules, $Verification, $Verification, $Verification, $Aux);
        return $this->BlockInfo;
    }
}
