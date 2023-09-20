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
 * This file: ReCaptcha class (last modified: 2023.09.18).
 */

namespace CIDRAM\CIDRAM;

class ReCaptcha extends Captcha
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

        /** Refer to the documentation regarding the behaviour of "lockuser". */
        if ($this->CIDRAM->Configuration['recaptcha']['lockuser']) {
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
             * Determine whether a reCAPTCHA instance has already been completed by the
             * user and populate relevant variables.
             */
            if (!empty($_COOKIE['CIDRAM']) && ($Split = strpos($_COOKIE['CIDRAM'], ',')) !== false) {
                $UserHash = substr($_COOKIE['CIDRAM'], 0, $Split);
                if (strpos($HastList, "\n" . $UserHash . ',') !== false) {
                    $UserSalt = base64_decode(substr($_COOKIE['CIDRAM'], $Split));
                    if ($this->CIDRAM->Configuration['recaptcha']['lockip']) {
                        $UserMeld = $this->meld($Salt, $UserSalt, $this->CIDRAM->ipAddr);
                    } else {
                        $UserMeld = $this->meld($Salt, $UserSalt);
                    }
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
                $this->CIDRAM->BlockInfo['SignatureCount'] = 0;
                $this->CIDRAM->Cache->deleteEntry('Tracking-' . $this->CIDRAM->BlockInfo['IPAddr']);
                $this->CIDRAM->Cache->deleteEntry('Tracking-' . $this->CIDRAM->BlockInfo['IPAddr'] . '-MinimumTime');
            } else {
                /** Set CAPTCHA status. */
                $this->CIDRAM->BlockInfo['CAPTCHA'] = $this->CIDRAM->L10N->getString('state_enabled');

                /** We've received a response. */
                if (isset($_POST['g-recaptcha-response'])) {
                    $Loggable = true;
                    $this->doResponse();
                    if ($this->Bypass) {
                        /** Generate client-side salt. */
                        $UserSalt = $this->generateSalt();

                        /** Generate authentication hash. */
                        if ($this->CIDRAM->Configuration['recaptcha']['lockip']) {
                            $Cookie = $this->meld($Salt, $UserSalt, $this->CIDRAM->ipAddr);
                        } else {
                            $Cookie = $this->meld($Salt, $UserSalt);
                        }
                        if (strpos($Cookie, "\0") !== false) {
                            $Cookie = str_replace("\0", '', $Cookie);
                        }
                        $UserHash = password_hash($Cookie, $this->DefaultAlgo);
                        $Cookie = $UserHash . ',' . base64_encode($UserSalt);
                        setcookie(
                            'CIDRAM',
                            $Cookie,
                            $this->CIDRAM->Now + ($this->CIDRAM->Configuration['recaptcha']['expiry'] * 3600),
                            '/',
                            $this->CIDRAM->CIDRAM['HostnameOverride'] ?: $this->CIDRAM->CIDRAM['HTTP_HOST'],
                            false,
                            true
                        );

                        /** Reset signature count. */
                        $this->CIDRAM->BlockInfo['SignatureCount'] = 0;

                        /** Append to the hash list. */
                        $HastList .= $UserHash . ',' . ($this->CIDRAM->Now + ($this->CIDRAM->Configuration['recaptcha']['expiry'] * 3600)) . "\n";
                        $HastListModified = true;
                        $this->generatePassed();
                    } else {
                        $this->generateFailed();
                    }
                }

                /**
                 * reCAPTCHA template data included if reCAPTCHA isn't being bypassed.
                 * Note: Cookie warning IS included here due to expected behaviour when lockuser is TRUE.
                 */
                $this->generateContainer(
                    $this->CIDRAM->Configuration['recaptcha']['show_cookie_warning'],
                    $this->CIDRAM->Configuration['recaptcha']['show_api_message']
                );
            }

            /** Update the hash list if any changes were made. */
            if ($HastListModified) {
                $Handle = fopen($this->CIDRAM->Vault . 'hashes.dat', 'wb');
                fwrite($Handle, $HastList);
                fclose($Handle);
            }
        } else {
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
             * Verify whether a reCAPTCHA instance has already been completed before
             * for the current IP, populate relevant variables, and generate fields.
             */
            if (strpos($BypassList, "\n" . $this->CIDRAM->ipAddr . ',') !== false) {
                $this->Bypass = true;
                $this->CIDRAM->BlockInfo['SignatureCount'] = 0;
                $this->CIDRAM->Cache->deleteEntry('Tracking-' . $this->CIDRAM->BlockInfo['IPAddr']);
                $this->CIDRAM->Cache->deleteEntry('Tracking-' . $this->CIDRAM->BlockInfo['IPAddr'] . '-MinimumTime');
            } else {
                /** Set CAPTCHA status. */
                $this->CIDRAM->BlockInfo['CAPTCHA'] = $this->CIDRAM->L10N->getString('state_enabled');

                /** We've received a response. */
                if (isset($_POST['g-recaptcha-response'])) {
                    $Loggable = true;
                    $this->doResponse();
                    if ($this->Bypass) {
                        /** Reset signature count. */
                        $this->CIDRAM->BlockInfo['SignatureCount'] = 0;

                        /** Append to the IP bypass list. */
                        $BypassList .= $this->CIDRAM->ipAddr . ',' . (
                            $this->CIDRAM->Now + ($this->CIDRAM->Configuration['recaptcha']['expiry'] * 3600)
                        ) . "\n";
                        $BypassListModified = true;

                        $this->generatePassed();
                    } else {
                        $this->generateFailed();
                    }
                }

                /**
                 * reCAPTCHA template data included if reCAPTCHA isn't being bypassed.
                 * Note: Cookie warning is NOT included here due to expected behaviour when lockuser is FALSE.
                 */
                $this->generateContainer(false, $this->CIDRAM->Configuration['recaptcha']['show_api_message']);
            }

            /** Update the IP bypass list if any changes were made. */
            if ($BypassListModified) {
                $Handle = fopen($this->CIDRAM->Vault . 'ipbypass.dat', 'wb');
                fwrite($Handle, $BypassList);
                fclose($Handle);
            }
        }

        /** Guard. */
        if (
            empty($Loggable) ||
            empty($this->CIDRAM->BlockInfo) ||
            $this->CIDRAM->Configuration['recaptcha']['recaptcha_log'] === '' ||
            !($Filename = $this->CIDRAM->buildPath($this->CIDRAM->Vault . $this->CIDRAM->Configuration['recaptcha']['recaptcha_log']))
        ) {
            return;
        }

        $Truncate = $this->CIDRAM->readBytes($this->CIDRAM->Configuration['logging']['truncate']);
        $WriteMode = (!file_exists($Filename) || $Truncate > 0 && filesize($Filename) >= $Truncate) ? 'wb' : 'ab';
        $Data = sprintf(
            '%1$s%7$s%2$s - %3$s%7$s%4$s - %5$s%7$s%6$s',
            $this->CIDRAM->L10N->getString('field.IP address'),
            $this->CIDRAM->Configuration['legal']['pseudonymise_ip_addresses'] ? $this->CIDRAM->pseudonymiseIp($this->CIDRAM->ipAddr) : $this->CIDRAM->ipAddr,
            $this->CIDRAM->L10N->getString('field.DateTime'),
            $this->CIDRAM->BlockInfo['DateTime'],
            $this->CIDRAM->L10N->getString('field.CAPTCHA state'),
            $this->CIDRAM->BlockInfo['CAPTCHA'],
            $this->CIDRAM->L10N->getString('pair_separator')
        ) . "\n";

        /** Adds a second new line in case of combined log files. */
        if ($this->CIDRAM->Configuration['recaptcha']['recaptcha_log'] === $this->CIDRAM->Configuration['logging']['standard_log']) {
            $Data .= "\n";
        }

        $File = fopen($Filename, $WriteMode);
        fwrite($File, $Data);
        fclose($File);
        if ($WriteMode === 'wb') {
            $this->CIDRAM->logRotation($this->CIDRAM->Configuration['recaptcha']['recaptcha_log']);
        }
    }

    /**
     * Generate reCAPTCHA form template data.
     *
     * @param string $SiteKey The sitekey to use.
     * @param string $API The API to use.
     * @param bool $CookieWarn Whether to display a cookie warning.
     * @param bool $ApiMessage Whether to display messages about the API used.
     * @return string The template form data.
     */
    private function generateTemplateData(string $SiteKey, string $API, bool $CookieWarn = false, bool $ApiMessage = false): string
    {
        $Script = '<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>';
        $Script .= '<script type="text/javascript">document.getElementById(\'hostnameoverride\').value=window.location.hostname;</script>';
        return $API === 'Invisible' ? sprintf(
            "\n<hr />\n<p class=\"detected\"%s>%s%s<br /></p>\n" .
            '<div class="gForm">' .
                '<div id="gForm" class="g-recaptcha" data-sitekey="%s" data-theme="%s" data-callback="onSubmitCallback" data-size="invisible"></div>' .
            "</div>\n" .
            '<form id="gF" method="POST" action="" class="gForm">' .
                '<input id="rData" type="hidden" name="g-recaptcha-response" value="" />%s' .
            "</form>\n" .
            "<script type=\"text/javascript\">function onSubmitCallback(token){document.getElementById('rData').value=token;document.getElementById('gF').submit()}</script>\n",
            $this->CIDRAM->CIDRAM['L10N-Lang-Attache'],
            $ApiMessage ? '{captcha_message_invisible}' : '',
            $CookieWarn ? '<br />{captcha_cookie_warning}' : '',
            $SiteKey,
            $this->determineTheme(),
            $this->TemplateInsert
        ) . $Script . "\n" : sprintf(
            "\n<hr />\n<p class=\"detected\"%s>%s%s<br /></p>\n" .
            '<form method="POST" action="" class="gForm" onsubmit="javascript:grecaptcha.execute()">' .
                '<div id="gForm" data-theme="%s"></div><div>%s<input type="submit" value="{label.Submit}" /></div>' .
            "</form>\n",
            $this->CIDRAM->CIDRAM['L10N-Lang-Attache'],
            $ApiMessage ? '{captcha_message}' : '',
            $CookieWarn ? '<br />{captcha_cookie_warning}' : '',
            $this->determineTheme(),
            $this->TemplateInsert
        ) . $Script;
    }

    /**
     * Generate reCAPTCHA callback data.
     *
     * @param string $SiteKey The sitekey to use.
     * @param string $API The API to use.
     * @return string The callback data.
     */
    private function generateCallbackData(string $SiteKey, string $API): string
    {
        return sprintf(
            "\n  <script type=\"text/javascript\">var onloadCallback=function(){grecaptcha.render(%s)%s}</script>",
            "'gForm',{'sitekey':'" . $SiteKey . "'" . ($API === 'Invisible' ? ",'size':'invisible'" : '') . '}',
            ($API === 'Invisible') ? ';grecaptcha.execute()' : ''
        );
    }

    /**
     * Fetch results from the reCAPTCHA API.
     * @link https://developers.google.com/recaptcha/docs/verify
     *
     * @return void
     */
    private function doResponse(): void
    {
        $this->Results = $this->CIDRAM->Request->request('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $this->CIDRAM->Configuration['recaptcha']['secret'],
            'response' => $_POST['g-recaptcha-response'],
            'remoteip' => $this->CIDRAM->ipAddr
        ]);
        $this->Bypass = (strpos($this->Results, '"success": true,') !== false);
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

        $this->CIDRAM->CIDRAM['FieldTemplates']['captcha_api_include'] = $this->generateCallbackData(
            $this->CIDRAM->Configuration['recaptcha']['sitekey'],
            $this->CIDRAM->Configuration['recaptcha']['api']
        );
        $this->CIDRAM->CIDRAM['FieldTemplates']['captcha_div_include'] = $this->generateTemplateData(
            $this->CIDRAM->Configuration['recaptcha']['sitekey'],
            $this->CIDRAM->Configuration['recaptcha']['api'],
            $CookieWarn,
            $ApiMessage
        );
    }
}
