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
 * This file: Project Honeypot module (last modified: 2024.05.20).
 *
 * False positive risk (an approximate, rough estimate only): « [ ]Low [x]Medium [ ]High »
 */

/** Safety. */
if (!isset($this->CIDRAM['ModuleResCache'])) {
    $this->CIDRAM['ModuleResCache'] = [];
}

/** Project Honeypot type of visitor controls. */
$this->CIDRAM['PHVC'] = array_flip(explode("\n", $this->Configuration['projecthoneypot']['type_of_visitor']));

/** Defining as closure for later recall (no params; no return value). */
$this->CIDRAM['ModuleResCache'][$Module] = function () {
    /** Guard. */
    if (empty($this->BlockInfo['IPAddr']) || $this->Configuration['projecthoneypot']['lookup_strategy'] === 0) {
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
     * We can't perform lookups without an API key, so we should check for
     * that, too.
     */
    if (!strlen($this->Configuration['projecthoneypot']['api_key'])) {
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
    if ($this->Configuration['projecthoneypot']['lookup_strategy'] !== 1 && !$this->isSensitive($LCURI)) {
        return;
    }

    /** Check whether the lookup limit has been exceeded. */
    if (!isset($this->CIDRAM['Project Honeypot-429'])) {
        $this->CIDRAM['Project Honeypot-429'] = $this->Cache->getEntry('Project Honeypot-429') ? true : false;
    }

    /**
     * Only execute if not already blocked for some other reason, if the IP is
     * valid, if not from a private or reserved range, and if the lookup limit
     * hasn't already been exceeded (reduces superfluous lookups).
     */
    if (
        $this->CIDRAM['Project Honeypot-429'] ||
        !$this->honourLookup() ||
        filter_var($this->BlockInfo['IPAddr'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false
    ) {
        return;
    }

    /** Executed if there aren't any cache entries corresponding to the IP of the request. */
    if (
        !isset($this->CIDRAM['Project Honeypot-' . $this->BlockInfo['IPAddr']]) ||
        $this->CIDRAM['Project Honeypot-' . $this->BlockInfo['IPAddr']] === false
    ) {
        $this->CIDRAM['Project Honeypot-' . $this->BlockInfo['IPAddr']] = $this->Cache->getEntry('Project Honeypot-' . $this->BlockInfo['IPAddr']);
        if ($this->CIDRAM['Project Honeypot-' . $this->BlockInfo['IPAddr']] === false) {
            /** Build the lookup query. */
            $Lookup = preg_replace(
                '~^(\d+)\.(\d+)\.(\d+)\.(\d+)$~',
                $this->Configuration['projecthoneypot']['api_key'] . '.\4.\3.\2.\1.dnsbl.httpbl.org',
                $this->BlockInfo['IPAddr']
            );

            /** Perform Project Honeypot lookup. */
            $Data = $this->dnsResolve($Lookup, $this->Configuration['projecthoneypot']['timeout_limit']);

            if ($this->Request->MostRecentStatusCode === 429) {
                /** Lookup limit has been exceeded. */
                $this->Cache->setEntry('Project Honeypot-429', true, $this->Configuration['projecthoneypot']['timeout_rl']->getAsSeconds());
                $this->CIDRAM['Project Honeypot-429'] = true;
                return;
            }

            /**
             * Validate or substitute.
             *
             * @link https://www.projecthoneypot.org/httpbl_api.php
             */
            if (preg_match('~^127\.\d+\.\d+\.\d+$~', $Data)) {
                $Data = explode('.', $Data);
                $this->CIDRAM['Project Honeypot-' . $this->BlockInfo['IPAddr']] = [
                    'Days since last activity' => $Data[1],
                    'Threat score' => $Data[2],
                    'Type of visitor' => (int)$Data[3]
                ];
                $this->Cache->setEntry(
                    'Project Honeypot-' . $this->BlockInfo['IPAddr'],
                    $this->CIDRAM['Project Honeypot-' . $this->BlockInfo['IPAddr']],
                    $this->Configuration['projecthoneypot']['expire_good']->getAsSeconds()
                );
            } else {
                $this->CIDRAM['Project Honeypot-' . $this->BlockInfo['IPAddr']] = [
                    'Days since last activity' => -1,
                    'Threat score' => -1,
                    'Type of visitor' => -1
                ];
                $this->Cache->setEntry(
                    'Project Honeypot-' . $this->BlockInfo['IPAddr'],
                    $this->CIDRAM['Project Honeypot-' . $this->BlockInfo['IPAddr']],
                    $this->Configuration['projecthoneypot']['expire_bad']->getAsSeconds()
                );
            }
        }
    }

    /** Visitor type matrix. */
    if (isset($this->CIDRAM['Project Honeypot-' . $this->BlockInfo['IPAddr']]['Type of visitor'])) {
        $Try = (int)$this->CIDRAM['Project Honeypot-' . $this->BlockInfo['IPAddr']]['Type of visitor'];
        $Visitor = ['SearchEngine' => $Try === 0, 'Suspicious' => false, 'Harvester' => false, 'CommentSpammer' => false, 'Other' => false];
        if ($Visitor['SearchEngine'] && isset($this->CIDRAM['PHVC']['SearchEngine:Profile'])) {
            $this->addProfileEntry('Search Engine');
        }
        if ($Try > 7) {
            $Visitor['Other'] = true;
            $Try %= 8;
        }
        if ($Try > 3) {
            $Visitor['CommentSpammer'] = true;
            $Try -= 4;
            if (isset($this->CIDRAM['PHVC']['CommentSpammer:Profile'])) {
                $this->addProfileEntry('Comment Spammer');
            }
        }
        if ($Try > 1) {
            $Visitor['Harvester'] = true;
            $Try -= 2;
            if (isset($this->CIDRAM['PHVC']['Harvester:Profile'])) {
                $this->addProfileEntry('Harvester');
            }
        }
        if ($Try > 0) {
            $Visitor['Suspicious'] = true;
            if (isset($this->CIDRAM['PHVC']['Suspicious:Profile'])) {
                $this->addProfileEntry('Suspicious');
            }
        }
    }

    /** Block the request if the IP is listed by Project Honeypot and fits the constraints configured. */
    if ($this->trigger(
        (
            $this->CIDRAM['Project Honeypot-' . $this->BlockInfo['IPAddr']]['Threat score'] >= $this->Configuration['projecthoneypot']['minimum_threat_score'] &&
            $this->CIDRAM['Project Honeypot-' . $this->BlockInfo['IPAddr']]['Days since last activity'] <= $this->Configuration['projecthoneypot']['max_age_in_days'] &&
            (
                ($Visitor['SearchEngine'] && isset($this->CIDRAM['PHVC']['SearchEngine:Block'])) ||
                ($Visitor['Suspicious'] && isset($this->CIDRAM['PHVC']['Suspicious:Block'])) ||
                ($Visitor['Harvester'] && isset($this->CIDRAM['PHVC']['Harvester:Block'])) ||
                ($Visitor['CommentSpammer'] && isset($this->CIDRAM['PHVC']['CommentSpammer:Block'])) ||
                ($Visitor['Other'] && isset($this->CIDRAM['PHVC']['Other:Block']))
            )
        ),
        'Project Honeypot Lookup',
        $this->L10N->getString('ReasonMessage_Generic') . '<br />' . sprintf(
            $this->L10N->getString('request_removal'),
            'https://www.projecthoneypot.org/ip_' . $this->BlockInfo['IPAddr']
        ))) {
        /** Fetch options. */
        $Options = array_flip(explode("\n", $this->Configuration['projecthoneypot']['options']));
        if (isset($Options['MarkForUseWithReCAPTCHA'])) {
            $this->Configuration['recaptcha']['enabled'] = true;
        }
        if (isset($Options['ForciblyDisableReCAPTCHA'])) {
            $this->Configuration['recaptcha']['usemode'] = 0;
            $this->Configuration['recaptcha']['forcibly_disabled'] = true;
        }
        if (isset($Options['MarkForUseWithHCAPTCHA'])) {
            $this->Configuration['hcaptcha']['enabled'] = true;
        }
        if (isset($Options['ForciblyDisableHCAPTCHA'])) {
            $this->Configuration['hcaptcha']['usemode'] = 0;
            $this->Configuration['hcaptcha']['forcibly_disabled'] = true;
        }
    }
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
