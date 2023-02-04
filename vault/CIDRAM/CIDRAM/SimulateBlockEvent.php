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
 * This file: Methods used to simulate block events (last modified: 2023.02.05).
 */

namespace CIDRAM\CIDRAM;

trait SimulateBlockEvent
{
    /**
     * Simulates block events (used by the IP tracking and IP test pages).
     *
     * @param string $Addr The IP address to test against.
     * @param bool $Modules Specifies whether to test against modules.
     * @param bool $Aux Specifies whether to test against auxiliary rules.
     * @param bool $Verification Specifies whether to test against search
     *          engine and social media verification.
     * @return void
     */
    public function simulateBlockEvent(string $Addr, bool $Modules = false, bool $Aux = false, bool $Verification = false): void
    {
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

        /** Populate BlockInfo. */
        $this->BlockInfo = [
            'ID' => $this->generateId(),
            'ScriptIdent' => $this->ScriptIdent,
            'DateTime' => $this->FE['DateTime'],
            'IPAddr' => $Addr,
            'IPAddrResolved' => $this->resolve6to4($Addr),
            'Query' => !empty($this->FE['custom-query']) ? $this->FE['custom-query'] : 'SimulateBlockEvent',
            'Referrer' => !empty($this->FE['custom-referrer']) ? $this->FE['custom-referrer'] : 'SimulateBlockEvent',
            'UA' => !empty($this->FE['custom-ua']) ? $this->FE['custom-ua'] : 'SimulateBlockEvent',
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
            'xmlLang' => $this->Configuration['general']['lang']
        ];
        if (isset($this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']])) {
            $this->BlockInfo['Infractions'] = $this->CIDRAM['Tracking-' . $this->BlockInfo['IPAddr']];
        } elseif (($Try = $this->Cache->getEntry('Tracking-' . $this->BlockInfo['IPAddr'])) === false) {
            $this->BlockInfo['Infractions'] = 0;
        } else {
            $this->BlockInfo['Infractions'] = $Try;
        }
        $this->BlockInfo['UALC'] = strtolower($this->BlockInfo['UA']);

        /** Appending query onto the reconstructed URI. */
        if (!empty($this->FE['custom-query'])) {
            $this->BlockInfo['rURI'] .= '?' . $this->FE['custom-query'];
        }

        if (strlen($Addr)) {
            /** Catch run errors. */
            $this->initialiseErrorHandler();

            /** Standard IP check. */
            try {
                $this->CIDRAM['Caught'] = false;
                $this->CIDRAM['TestResults'] = $this->runTests($Addr, true);
            } catch (\Exception $e) {
                $this->CIDRAM['Caught'] = true;
            }

            /** Resolved IP check. */
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

            /** Prepare run errors. */
            $this->CIDRAM['RunErrors'] = $this->CIDRAM['Errors'];
            $this->restoreErrorHandler();
        }

        if (isset($this->Stages['Tests:Tracking']) && $this->BlockInfo['SignatureCount'] > 0) {
            $this->BlockInfo['Infractions'] += $this->BlockInfo['SignatureCount'];
        }

        /** Perform forced hostname lookup if this has been enabled. */
        if ($this->Configuration['general']['force_hostname_lookup']) {
            $this->CIDRAM['Hostname'] = $this->dnsReverse($this->BlockInfo['IPAddrResolved'] ?: $this->BlockInfo['IPAddr']);
        }

        /** Instantiate report orchestrator (used by some modules). */
        $this->Reporter = new Reporter($this->Events);

        /** Execute modules, if any have been enabled. */
        if ($Modules && $this->Configuration['components']['modules'] && empty($this->CIDRAM['Whitelisted'])) {
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
                } elseif (file_exists($this->ModulesPath . $Module) && is_readable($this->ModulesPath . $Module)) {
                    require $this->ModulesPath . $Module;
                }
                if (isset($this->Stages['Modules:Tracking']) && $this->BlockInfo['SignatureCount'] > $Before) {
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
        if ($Verification && empty($this->CIDRAM['Whitelisted'])) {
            $this->searchEngineVerification();
        }

        /** Execute social media verification. */
        if ($Verification && empty($this->CIDRAM['Whitelisted'])) {
            $this->socialMediaVerification();
        }

        /** Execute other verification. */
        if ($Verification && empty($this->CIDRAM['Whitelisted'])) {
            $this->otherVerification();
        }

        /** Process auxiliary rules, if any exist. */
        if ($Aux && empty($this->CIDRAM['Whitelisted'])) {
            $this->initialiseErrorHandler();
            $Before = $this->BlockInfo['SignatureCount'];
            $this->aux();
            if (isset($this->Stages['Aux:Tracking']) && $this->BlockInfo['SignatureCount'] > $Before) {
                $this->BlockInfo['Infractions'] += $this->BlockInfo['SignatureCount'] - $Before;
            }
            $this->CIDRAM['AuxErrors'] = $this->CIDRAM['Errors'];
            $this->restoreErrorHandler();
        }

        /**
         * Destroying the reporter (we won't process reports in this case, because we're only simulating block events,
         * as opposed to checking against actual, real requests; still needed to set it though to prevent errors).
         */
        $this->Reporter = null;
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
                $this->simulateBlockEvent($ThisAddr, $Modules);
                $Results[$ThisAddr] = $this->BlockInfo;
            }
            return $Results;
        }
        $this->simulateBlockEvent($Addr, $Modules);
        return $this->BlockInfo;
    }
}
