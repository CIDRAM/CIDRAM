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
 * This file: Friendly Captcha class (last modified: 2026.03.15).
 */

namespace CIDRAM\CIDRAM;

class FriendlyCaptcha extends Captcha
{
    /**
     * Constructor.
     *
     * @param array $CIDRAM The main CIDRAM object passed by reference.
     * @return void
     */
    public function __construct(&$CIDRAM)
    {
        $this->CIDRAM = &$CIDRAM;
        $Salt = $this->generateSalt();

        /** Initialise messages. */
        $this->Messages = array_flip(explode("\n", $this->CIDRAM->Configuration['captcha']['messages']));

        /** What to lock CAPTCHAs to. */
        $LockTo = $this->CIDRAM->Configuration['captcha']['lockto']['friendly'] ?? 'user';

        if ($LockTo === 'ip') {
            /** Attempt to load the IP bypass list. */
            if (file_exists($this->CIDRAM->Vault . 'ipbypass.dat')) {
                $BypassList = $this->CIDRAM->readFile($this->CIDRAM->Vault . 'ipbypass.dat');
                $BypassListModified = false;
            } else {
                $BypassList = "IP BYPASS LIST\n--------------\n";
                $BypassListModified = true;
            }

            /** Cycle through the IP bypass list and remove any expired IPs. */
            $this->clearExpired($BypassList, $BypassListModified);

            /**
             * Verify whether a Friendly Captcha instance has already been completed before
             * for the current IP, populate relevant variables, and generate fields.
             */
            if (strpos($BypassList, "\n" . $this->CIDRAM->ipAddr . ',') !== false) {
                $this->Bypass = true;
                $this->resetSCT();
            } else {
                /** Set Friendly Captcha status. */
                $this->CIDRAM->BlockInfo['CAPTCHA'] = sprintf($this->CIDRAM->L10N->getString('state.Enabled'), 'Friendly Captcha');

                /** We've received a response. */
                if (
                    ($this->CIDRAM->Configuration['captcha']['api']['friendly'] === 'v1' && isset($_POST['frc-captcha-solution'])) ||
                    ($this->CIDRAM->Configuration['captcha']['api']['friendly'] === 'v2' && isset($_POST['frc-captcha-response']))
                ) {
                    $Loggable = true;
                    $this->doResponse();
                    if ($this->Bypass) {
                        $this->resetSCT();

                        /** Append to the IP bypass list. */
                        $BypassList .= $this->CIDRAM->ipAddr . ',' . (
                            $this->CIDRAM->Now + ($this->CIDRAM->Configuration['captcha']['expiry'] * 3600)
                        ) . "\n";
                        $BypassListModified = true;

                        $this->generatePassed('Friendly Captcha', 'FriendlyCaptcha');
                    } else {
                        $this->generateFailed('Friendly Captcha', 'FriendlyCaptcha');
                    }
                }

                /** Friendly Captcha template data included if Friendly Captcha isn't being bypassed. */
                $this->generateContainer(false, isset($this->Messages['api_message:friendly']));
            }

            /** Update the IP bypass list if any changes were made. */
            if ($BypassListModified) {
                $Handle = fopen($this->CIDRAM->Vault . 'ipbypass.dat', 'wb');
                fwrite($Handle, $BypassList);
                fclose($Handle);
            }
        } else {
            if (file_exists($this->CIDRAM->Vault . 'hashes.dat')) {
                $HastList = $this->CIDRAM->readFile($this->CIDRAM->Vault . 'hashes.dat');
                $HastListModified = false;
            } else {
                $HastList = "HASH LIST\n---------\n";
                $HastListModified = true;
            }

            /** Cycle through the hash list and remove any expired hashes. */
            $this->clearExpired($HastList, $HastListModified);

            /**
             * Determine whether a Friendly Captcha instance has already been completed by the
             * user and populate relevant variables.
             */
            if (!empty($_COOKIE['CIDRAM']) && ($Split = strpos($_COOKIE['CIDRAM'], ',')) !== false) {
                $UserHash = substr($_COOKIE['CIDRAM'], 0, $Split);
                if (strpos($HastList, "\n" . $UserHash . ',') !== false) {
                    $UserSalt = base64_decode(substr($_COOKIE['CIDRAM'], $Split));
                    $UserMeld = $LockTo === 'both' ? $this->meld($Salt, $UserSalt, $this->CIDRAM->ipAddr) : $this->meld($Salt, $UserSalt);
                }
            }
            if (!isset($UserMeld) || strlen($UserMeld) === 0) {
                $UserMeld = '';
                $UserSalt = '';
                $UserHash = '';
            }

            /** Verify whether they've passed, update cookies, generate fields. */
            if ($UserHash !== '' && $UserMeld !== '' && password_verify($UserMeld, $UserHash)) {
                $this->Bypass = true;
                $this->resetSCT();
            } else {
                /** Set Friendly Captcha status. */
                $this->CIDRAM->BlockInfo['CAPTCHA'] = sprintf($this->CIDRAM->L10N->getString('state.Enabled'), 'Friendly Captcha');

                /** We've received a response. */
                if (
                    ($this->CIDRAM->Configuration['captcha']['api']['friendly'] === 'v1' && isset($_POST['frc-captcha-solution'])) ||
                    ($this->CIDRAM->Configuration['captcha']['api']['friendly'] === 'v2' && isset($_POST['frc-captcha-response']))
                ) {
                    $Loggable = true;
                    $this->doResponse();
                    if ($this->Bypass) {
                        /** Generate client-side salt. */
                        $UserSalt = $this->CIDRAM->generateSalt();

                        /** Generate authentication hash. */
                        $Cookie = $LockTo === 'both' ? $this->meld($Salt, $UserSalt, $this->CIDRAM->ipAddr) : $this->meld($Salt, $UserSalt);

                        /** Purge null bytes. */
                        if (strpos($Cookie, "\0") !== false) {
                            $Cookie = str_replace("\0", '', $Cookie);
                        }

                        $UserHash = password_hash($Cookie, $this->DefaultAlgo);
                        $Cookie = $UserHash . ',' . base64_encode($UserSalt);
                        setcookie(
                            'CIDRAM',
                            $Cookie,
                            $this->CIDRAM->Now + ($this->CIDRAM->Configuration['captcha']['expiry'] * 3600),
                            '/',
                            $this->CIDRAM->CIDRAM['HostnameOverride'] ?: $this->CIDRAM->CIDRAM['HTTP_HOST'],
                            false,
                            true
                        );
                        $this->resetSCT();

                        /** Append to the hash list. */
                        $HastList .= $UserHash . ',' . ($this->CIDRAM->Now + ($this->CIDRAM->Configuration['captcha']['expiry'] * 3600)) . "\n";
                        $HastListModified = true;
                        $this->generatePassed('Friendly Captcha', 'FriendlyCaptcha');
                    } else {
                        $this->generateFailed('Friendly Captcha', 'FriendlyCaptcha');
                    }
                }

                /** Friendly Captcha template data included if Friendly Captcha isn't being bypassed. */
                $this->generateContainer(isset($this->Messages['cookie_warning:friendly']), isset($this->Messages['api_message:friendly']));
            }

            /** Update the hash list if any changes were made. */
            if ($HastListModified) {
                $Handle = fopen($this->CIDRAM->Vault . 'hashes.dat', 'wb');
                fwrite($Handle, $HastList);
                fclose($Handle);
            }
        }

        /** Writes to the CAPTCHA log file. */
        if (!empty($Loggable)) {
            $this->CIDRAM->Events->fireEvent('writeToCaptchaLog');
        }
    }

    /**
     * Generate Friendly Captcha form template data.
     *
     * @param string $SiteKey The sitekey to use.
     * @param bool $CookieWarn Whether to display a cookie warning.
     * @param bool $ApiMessage Whether to display messages about the API used.
     * @return string The template form data.
     */
    private function generateTemplateData(string $SiteKey, bool $CookieWarn = false, bool $ApiMessage = false): string
    {
        /** Append to CAPTCHA statistics if necessary. */
        if (isset($this->CIDRAM->Stages['Statistics:Enable'], $this->CIDRAM->StatisticsTrackedCAPTCHAs['FriendlyCaptcha:Served'])) {
            $this->CIDRAM->Cache->incEntry('Statistics-FriendlyCaptcha:Served');
        }

        if ($this->CIDRAM->Configuration['captcha']['api']['friendly'] === 'v1') {
            $Script = '<script type="module" src="https://cdn.jsdelivr.net/npm/friendly-challenge@0.9.18/widget.module.min.js" async defer></script><script nomodule src="https://cdn.jsdelivr.net/npm/friendly-challenge@0.9.18/widget.min.js" async defer></script>';
        } else {
            $Script = '<script type="module" src="https://cdn.jsdelivr.net/npm/@friendlycaptcha/sdk@0.1.31/site.min.js" async defer></script><script nomodule src="https://cdn.jsdelivr.net/npm/@friendlycaptcha/sdk@0.1.31/site.compat.min.js" async defer></script>';
        }
        return sprintf(
            "\n<hr />\n<p class=\"detected\"%s>%s%s<br /></p>\n" .
            '<form id="FCf" method="POST" action="" class="gForm">' .
                '<div class="frc-captcha" data-sitekey="%s" data-theme="%s" lang="%s"></div>' .
                '<div>%s<input type="submit" value="%s" /></div>' .
            "</form>\n",
            $this->CIDRAM->CIDRAM['L10N-Lang-Attache'],
            $ApiMessage ? ($this->CIDRAM->ClientL10N->getString('captcha_message') ?: $this->CIDRAM->L10N->getString('captcha_message')) : '',
            $CookieWarn ? '<br />' . ($this->CIDRAM->ClientL10N->getString('captcha_cookie_warning') ?: $this->CIDRAM->L10N->getString('captcha_cookie_warning')) : '',
            $SiteKey,
            $this->determineTheme(),
            $this->CIDRAM->ClientL10N->getString('hl.friendly') ?: $this->CIDRAM->L10N->getString('hl.friendly'),
            $this->TemplateInsert,
            $this->CIDRAM->ClientL10N->getString('label.Submit') ?: $this->CIDRAM->L10N->getString('label.Submit')
        ) . $Script . '<script type="text/javascript">document.addEventListener(\'DOMContentLoaded\',function(){document.getElementById(\'FCf\').action=window.location});document.getElementById(\'hostnameoverride\').value=window.location.hostname;</script>';
    }

    /**
     * Fetch results from the Friendly Captcha API.
     * @link https://developer.friendlycaptcha.com/docs/v1
     * @link https://developer.friendlycaptcha.com/docs/v2
     *
     * @return void
     */
    private function doResponse(): void
    {
        if ($this->CIDRAM->Configuration['captcha']['api']['friendly'] === 'v1') {
            $this->Results = $this->CIDRAM->Request->request('https://api.friendlycaptcha.com/api/v1/siteverify', [
                'solution' => $_POST['frc-captcha-solution'],
                'sitekey' => $this->CIDRAM->Configuration['captcha']['friendly_sitekey'],
                'secret' => $this->CIDRAM->Configuration['captcha']['friendly_apikey']
            ]);
            $this->Bypass = (strpos($this->Results, '"success":true') !== false);
        } elseif ($this->CIDRAM->Configuration['captcha']['api']['friendly'] === 'v2') {
            $this->Results = $this->CIDRAM->Request->request('https://global.frcapi.com/api/v2/captcha/siteverify', [
                'response' => $_POST['frc-captcha-response'],
                'sitekey' => $this->CIDRAM->Configuration['captcha']['friendly_sitekey']
            ], -1, ['X-API-Key: ' . $this->CIDRAM->Configuration['captcha']['friendly_apikey']]);
            $this->Bypass = (strpos($this->Results, '"success":true') !== false);
        }
    }

    /**
     * Data generation container.
     *
     * @param bool $CookieWarn Whether to display a cookie warning.
     * @param bool $ApiMessage Whether to display messages about the API used.
     * @return void
     */
    private function generateContainer(bool $CookieWarn = false, bool $ApiMessage = false): void
    {
        /** Guard. */
        if ($this->Bypass) {
            return;
        }

        $this->CIDRAM->CIDRAM['FieldTemplates']['captcha_api_include'] = '';
        $this->CIDRAM->CIDRAM['FieldTemplates']['captcha_div_include'] = $this->generateTemplateData(
            $this->CIDRAM->Configuration['captcha']['friendly_sitekey'],
            $CookieWarn,
            $ApiMessage
        );
    }
}
