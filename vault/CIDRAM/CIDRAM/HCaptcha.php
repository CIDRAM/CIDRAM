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
 * This file: HCaptcha class (last modified: 2026.03.17).
 */

namespace CIDRAM\CIDRAM;

class HCaptcha extends Captcha
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
        $this->Messages = \array_flip(\explode("\n", $this->CIDRAM->Configuration['captcha']['messages']));

        /** What to lock CAPTCHAs to. */
        $LockTo = $this->CIDRAM->Configuration['captcha']['lockto']['hcaptcha'] ?? 'user';

        if ($LockTo === 'ip') {
            /** Attempt to load the IP bypass list. */
            if (\file_exists($this->CIDRAM->Vault . 'ipbypass.dat')) {
                $BypassList = $this->CIDRAM->readFile($this->CIDRAM->Vault . 'ipbypass.dat');
                $BypassListModified = false;
            } else {
                $BypassList = "IP BYPASS LIST\n--------------\n";
                $BypassListModified = true;
            }

            /** Cycle through the IP bypass list and remove any expired IPs. */
            $this->clearExpired($BypassList, $BypassListModified);

            /**
             * Verify whether a hCaptcha instance has already been completed before
             * for the current IP, populate relevant variables, and generate fields.
             */
            if (\strpos($BypassList, "\n" . $this->CIDRAM->ipAddr . ',') !== false) {
                $this->Bypass = true;
                $this->resetSCT();
            } else {
                /** Set hCaptcha status. */
                $this->CIDRAM->BlockInfo['CAPTCHA'] = \sprintf($this->CIDRAM->L10N->getString('state.Enabled'), 'hCaptcha');

                /** We've received a response. */
                if (isset($_POST['hc-response'])) {
                    $Loggable = true;
                    $this->doResponse();
                    if ($this->Bypass) {
                        $this->resetSCT();

                        /** Append to the IP bypass list. */
                        $BypassList .= $this->CIDRAM->ipAddr . ',' . (
                            $this->CIDRAM->Now + ($this->CIDRAM->Configuration['captcha']['expiry'] * 3600)
                        ) . "\n";
                        $BypassListModified = true;

                        $this->generatePassed('hCaptcha', 'HCaptcha');
                    } else {
                        $this->generateFailed('hCaptcha', 'HCaptcha');
                    }
                }

                /** HCaptcha template data included if hCaptcha isn't being bypassed. */
                $this->generateContainer(false, isset($this->Messages['api_message:hcaptcha']));
            }

            /** Update the IP bypass list if any changes were made. */
            if ($BypassListModified) {
                $Handle = \fopen($this->CIDRAM->Vault . 'ipbypass.dat', 'wb');
                \fwrite($Handle, $BypassList);
                \fclose($Handle);
            }
        } else {
            if (\file_exists($this->CIDRAM->Vault . 'hashes.dat')) {
                $HastList = $this->CIDRAM->readFile($this->CIDRAM->Vault . 'hashes.dat');
                $HastListModified = false;
            } else {
                $HastList = "HASH LIST\n---------\n";
                $HastListModified = true;
            }

            /** Cycle through the hash list and remove any expired hashes. */
            $this->clearExpired($HastList, $HastListModified);

            /**
             * Determine whether a hCaptcha instance has already been completed by the
             * user and populate relevant variables.
             */
            if (!empty($_COOKIE['CIDRAM']) && ($Split = \strpos($_COOKIE['CIDRAM'], ',')) !== false) {
                $UserHash = \substr($_COOKIE['CIDRAM'], 0, $Split);
                if (\strpos($HastList, "\n" . $UserHash . ',') !== false) {
                    $UserSalt = \base64_decode(\substr($_COOKIE['CIDRAM'], $Split));
                    $UserMeld = $LockTo === 'both' ? $this->meld($Salt, $UserSalt, $this->CIDRAM->ipAddr) : $this->meld($Salt, $UserSalt);
                }
            }
            if (!isset($UserMeld) || \strlen($UserMeld) === 0) {
                $UserMeld = '';
                $UserSalt = '';
                $UserHash = '';
            }

            /** Verify whether they've passed, update cookies, generate fields. */
            if ($UserHash !== '' && $UserMeld !== '' && \password_verify($UserMeld, $UserHash)) {
                $this->Bypass = true;
                $this->resetSCT();
            } else {
                /** Set hCaptcha status. */
                $this->CIDRAM->BlockInfo['CAPTCHA'] = \sprintf($this->CIDRAM->L10N->getString('state.Enabled'), 'hCaptcha');

                /** We've received a response. */
                if (isset($_POST['hc-response'])) {
                    $Loggable = true;
                    $this->doResponse();
                    if ($this->Bypass) {
                        /** Generate client-side salt. */
                        $UserSalt = $this->CIDRAM->generateSalt();

                        /** Generate authentication hash. */
                        $Cookie = $LockTo === 'both' ? $this->meld($Salt, $UserSalt, $this->CIDRAM->ipAddr) : $this->meld($Salt, $UserSalt);

                        /** Purge null bytes. */
                        if (\strpos($Cookie, "\0") !== false) {
                            $Cookie = \str_replace("\0", '', $Cookie);
                        }

                        $UserHash = \password_hash($Cookie, $this->DefaultAlgo);
                        $Cookie = $UserHash . ',' . \base64_encode($UserSalt);
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
                        $this->generatePassed('hCaptcha', 'HCaptcha');
                    } else {
                        $this->generateFailed('hCaptcha', 'HCaptcha');
                    }
                }

                /** HCaptcha template data included if hCaptcha isn't being bypassed. */
                $this->generateContainer(isset($this->Messages['cookie_warning:hcaptcha']), isset($this->Messages['api_message:hcaptcha']));
            }

            /** Update the hash list if any changes were made. */
            if ($HastListModified) {
                $Handle = \fopen($this->CIDRAM->Vault . 'hashes.dat', 'wb');
                \fwrite($Handle, $HastList);
                \fclose($Handle);
            }
        }

        /** Writes to the CAPTCHA log file. */
        if (!empty($Loggable)) {
            $this->CIDRAM->Events->fireEvent('writeToCaptchaLog');
        }
    }

    /**
     * Generate hCaptcha form template data.
     *
     * @param string $SiteKey The sitekey to use.
     * @param bool $CookieWarn Whether to display a cookie warning.
     * @param bool $ApiMessage Whether to display messages about the API used.
     * @return string The template form data.
     */
    private function generateTemplateData(string $SiteKey, bool $CookieWarn = false, bool $ApiMessage = false): string
    {
        /** Append to CAPTCHA statistics if necessary. */
        if (isset($this->CIDRAM->Stages['Statistics:Enable'], $this->CIDRAM->StatisticsTrackedCAPTCHAs['HCaptcha:Served'])) {
            $this->CIDRAM->Cache->incEntry('Statistics-HCaptcha:Served');
        }

        header(\sprintf(
            'Content-Security-Policy: default-src \'none\'; connect-src %1$s; frame-src %1$s; script-src %1$s \'unsafe-inline\'; style-src \'unsafe-inline\';',
            '\'self\' https://assets.hcaptcha.com https://hcaptcha.com https://newassets.hcaptcha.com/'
        ));
        $Script = \sprintf(
            '<script src="https://hcaptcha.com/1/api.js?hl=%s&onload=onloadHCaptcha&render=explicit" async defer></script>',
            $this->CIDRAM->ClientL10N->getString('hl.hcaptcha') ?: $this->CIDRAM->L10N->getString('hl.hcaptcha')
        ) . '<script type="text/javascript">document.getElementById(\'hostnameoverride\').value=window.location.hostname;</script>';
        $MsgCookieWarning = $this->CIDRAM->ClientL10N->getString('captcha_cookie_warning') ?: $this->CIDRAM->L10N->getString('captcha_cookie_warning');
        return $this->CIDRAM->Configuration['captcha']['api']['hcaptcha'] === 'Invisible' ? \sprintf(
            "\n<hr />\n<p class=\"detected\"%s>%s%s<br /></p>\n" .
            '<div class="gForm">' .
                '<div id="hcform" class="h-captcha" data-sitekey="%s" data-theme="%s" data-callback="onSubmitCallback" data-size="invisible"></div>' .
            "</div>\n" .
            '<form id="gF" method="POST" action="" class="gForm" onsubmit="javascript:hcaptcha.execute()">' .
                '<input id="rData" type="hidden" name="hc-response" value="" />%s' .
            "</form>\n<script type=\"text/javascript\">document.addEventListener('DOMContentLoaded',function(){document.getElementById('gF').action=window.location});function onSubmitCallback(token){document.getElementById('rData').value=hcaptcha.getResponse(window.document.hcwidget);document.getElementById('gF').submit()}</script>\n",
            $this->CIDRAM->CIDRAM['L10N-Lang-Attache'],
            $ApiMessage ? ($this->CIDRAM->ClientL10N->getString('captcha_message_invisible') ?: $this->CIDRAM->L10N->getString('captcha_message_invisible')) : '',
            $CookieWarn ? '<br />' . $MsgCookieWarning : '',
            $SiteKey,
            $this->determineTheme(),
            self::TEMPLATE_INSERT
        ) . $Script . "\n" : \sprintf(
            "\n<hr />\n<p class=\"detected\"%s>%s%s<br /></p>\n" .
            '<form id="gF" method="POST" action="" class="gForm">' .
                '<input id="rData" type="hidden" name="hc-response" value="" />' .
                '<div id="hcform" data-theme="%s" data-callback="onSubmitCallback"></div>' .
                '<div>%s<input type="submit" value="%s" /></div>' .
            "</form>\n<script type=\"text/javascript\">document.addEventListener('DOMContentLoaded',function(){document.getElementById('gF').action=window.location});function onSubmitCallback(token){document.getElementById('rData').value=hcaptcha.getResponse(window.document.hcwidget)}</script>\n",
            $this->CIDRAM->CIDRAM['L10N-Lang-Attache'],
            $ApiMessage ? ($this->CIDRAM->ClientL10N->getString('captcha_message') ?: $this->CIDRAM->L10N->getString('captcha_message')) : '',
            $CookieWarn ? '<br />' . $MsgCookieWarning : '',
            $this->determineTheme(),
            self::TEMPLATE_INSERT,
            $this->CIDRAM->ClientL10N->getString('label.Submit') ?: $this->CIDRAM->L10N->getString('label.Submit')
        ) . $Script;
    }

    /**
     * Generate hCaptcha callback data.
     *
     * @param string $SiteKey The sitekey to use.
     * @return string The callback data.
     */
    private function generateCallbackData(string $SiteKey): string
    {
        return \sprintf(
            "\n  <script type=\"text/javascript\">var onloadHCaptcha=function(){window.document.hcwidget=hcaptcha.render('hcform',{sitekey:'%s',theme:'%s'})%s}</script>",
            $SiteKey,
            $this->determineTheme(),
            $this->CIDRAM->Configuration['captcha']['api']['hcaptcha'] === 'Invisible' ? ';hcaptcha.execute()' : ''
        );
    }

    /**
     * Fetch results from the hCaptcha API.
     * @link https://docs.hcaptcha.com/
     *
     * @return void
     */
    private function doResponse(): void
    {
        $this->Results = $this->CIDRAM->Request->request('https://api.hcaptcha.com/siteverify', [
            'secret' => $this->CIDRAM->Configuration['captcha']['hcaptcha_secret'],
            'response' => $_POST['hc-response'],
            'remoteip' => $this->CIDRAM->ipAddr
        ]);
        $this->Bypass = (\strpos($this->Results, '"success":true,') !== false);
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
            $this->CIDRAM->Configuration['captcha']['hcaptcha_sitekey']
        );
        $this->CIDRAM->CIDRAM['FieldTemplates']['captcha_div_include'] = $this->generateTemplateData(
            $this->CIDRAM->Configuration['captcha']['hcaptcha_sitekey'],
            $CookieWarn,
            $ApiMessage
        );
    }
}
