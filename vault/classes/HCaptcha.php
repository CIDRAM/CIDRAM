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
 * This file: HCaptcha class (last modified: 2022.02.07).
 */

namespace CIDRAM\Core;

class HCaptcha extends Captcha
{
    /**
     * Constructor.
     *
     * @param array $CIDRAM The main CIDRAM array passed by reference.
     * @return void
     */
    public function __construct(&$CIDRAM)
    {
        $this->CIDRAM = &$CIDRAM;
        $Salt = $this->generateSalt();

        /** Refer to the documentation regarding the behaviour of "lockuser". */
        if ($this->CIDRAM['Config']['hcaptcha']['lockuser']) {
            if (file_exists($this->CIDRAM['Vault'] . 'hashes.dat')) {
                $HastList = $this->CIDRAM['ReadFile']($this->CIDRAM['Vault'] . 'hashes.dat');
                $HastListModified = false;
            } else {
                $HastList = "HASH LIST\n---------\n";
                $HastListModified = true;
            }

            /** Cycle through the hash list and remove any expired hashes. */
            $this->CIDRAM['ClearExpired']($HastList, $HastListModified);

            /**
             * Determine whether a HCaptcha instance has already been completed by the
             * user and populate relevant variables.
             */
            if (!empty($_COOKIE['CIDRAM']) && $Split = strpos($_COOKIE['CIDRAM'], ',')) {
                $UserHash = substr($_COOKIE['CIDRAM'], 0, $Split);
                if (strpos($HastList, "\n" . $UserHash . ',') !== false) {
                    $UserSalt = base64_decode(substr($_COOKIE['CIDRAM'], $Split));
                    if ($this->CIDRAM['Config']['hcaptcha']['lockip']) {
                        $UserMeld = $this->meld($Salt, $UserSalt, $this->CIDRAM['IPAddr']);
                    } else {
                        $UserMeld = $this->meld($Salt, $UserSalt);
                    }
                    if (strpos($UserMeld, "\0") !== false) {
                        $UserMeld = str_replace("\0", '', $UserMeld);
                    }
                }
            }
            if (empty($UserMeld)) {
                $UserMeld = '';
                $UserSalt = '';
                $UserHash = '';
            }

            /** Verify whether they've passed, update cookies, generate fields. */
            if ($UserHash && $UserMeld && password_verify($UserMeld, $UserHash)) {
                $this->Bypass = true;
                $this->CIDRAM['BlockInfo']['SignatureCount'] = 0;

                /** Fix for infraction escalation bug. */
                if (isset($this->CIDRAM['Tracking'][$this->CIDRAM['BlockInfo']['IPAddr']])) {
                    unset($this->CIDRAM['Tracking'][$this->CIDRAM['BlockInfo']['IPAddr']]);
                    $this->CIDRAM['Tracking-Modified'] = true;
                }
            } else {
                /** Set CAPTCHA status. */
                $this->CIDRAM['BlockInfo']['CAPTCHA'] = $this->CIDRAM['L10N']->getString('state_enabled');

                /** We've received a response. */
                if (isset($_POST['hc-response'])) {
                    $Loggable = true;
                    $this->doResponse();
                    if ($this->Bypass) {
                        /** Generate client-side salt. */
                        $UserSalt = $this->CIDRAM['GenerateSalt']();

                        /** Generate authentication hash. */
                        if ($this->CIDRAM['Config']['hcaptcha']['lockip']) {
                            $Cookie = $this->meld($Salt, $UserSalt, $this->CIDRAM['IPAddr']);
                        } else {
                            $Cookie = $this->meld($Salt, $UserSalt);
                        }
                        if (strpos($Cookie, "\0") !== false) {
                            $Cookie = str_replace("\0", '', $Cookie);
                        }
                        $UserHash = password_hash($Cookie, $this->CIDRAM['DefaultAlgo']);
                        $Cookie = $UserHash . ',' . base64_encode($UserSalt);
                        setcookie(
                            'CIDRAM',
                            $Cookie,
                            $this->CIDRAM['Now'] + ($this->CIDRAM['Config']['hcaptcha']['expiry'] * 3600),
                            '/',
                            $this->CIDRAM['HostnameOverride'] ?: $this->CIDRAM['HTTP_HOST'],
                            false,
                            true
                        );

                        /** Reset signature count. */
                        $this->CIDRAM['BlockInfo']['SignatureCount'] = 0;

                        /** Append to the hash list. */
                        $HastList .= $UserHash . ',' . ($this->CIDRAM['Now'] + ($this->CIDRAM['Config']['hcaptcha']['expiry'] * 3600)) . "\n";
                        $HastListModified = true;
                        $this->generatePassed();
                    } else {
                        $this->generateFailed();
                    }
                }

                /**
                 * HCaptcha template data included if HCaptcha isn't being bypassed.
                 * Note: Cookie warning IS included here due to expected behaviour when lockuser is TRUE.
                 */
                $this->generateContainer(
                    $this->CIDRAM['Config']['hcaptcha']['show_cookie_warning'],
                    $this->CIDRAM['Config']['hcaptcha']['show_api_message']
                );
            }

            /** Update the hash list if any changes were made. */
            if ($HastListModified) {
                $Handle = fopen($this->CIDRAM['Vault'] . 'hashes.dat', 'wb');
                fwrite($Handle, $HastList);
                fclose($Handle);
            }
        } else {
            /** Attempt to load the IP bypass list. */
            if (file_exists($this->CIDRAM['Vault'] . 'ipbypass.dat')) {
                $BypassList = $this->CIDRAM['ReadFile']($this->CIDRAM['Vault'] . 'ipbypass.dat');
                $BypassListModified = false;
            } else {
                $BypassList = "IP BYPASS LIST\n--------------\n";
                $BypassListModified = true;
            }

            /** Cycle through the IP bypass list and remove any expired IPs. */
            $this->CIDRAM['ClearExpired']($BypassList, $BypassListModified);

            /**
             * Verify whether a HCaptcha instance has already been completed before
             * for the current IP, populate relevant variables, and generate fields.
             */
            if (strpos($BypassList, "\n" . $this->CIDRAM['IPAddr'] . ',') !== false) {
                $this->Bypass = true;
                $this->CIDRAM['BlockInfo']['SignatureCount'] = 0;

                /** Fix for infraction escalation bug. */
                if (isset($this->CIDRAM['Tracking'][$this->CIDRAM['BlockInfo']['IPAddr']])) {
                    unset($this->CIDRAM['Tracking'][$this->CIDRAM['BlockInfo']['IPAddr']]);
                    $this->CIDRAM['Tracking-Modified'] = true;
                }
            } else {
                /** Set CAPTCHA status. */
                $this->CIDRAM['BlockInfo']['CAPTCHA'] = $this->CIDRAM['L10N']->getString('state_enabled');

                /** We've received a response. */
                if (isset($_POST['hc-response'])) {
                    $Loggable = true;
                    $this->doResponse();
                    if ($this->Bypass) {
                        /** Reset signature count. */
                        $this->CIDRAM['BlockInfo']['SignatureCount'] = 0;

                        /** Append to the IP bypass list. */
                        $BypassList .= $this->CIDRAM['IPAddr'] . ',' . (
                            $this->CIDRAM['Now'] + ($this->CIDRAM['Config']['hcaptcha']['expiry'] * 3600)
                        ) . "\n";
                        $BypassListModified = true;

                        $this->generatePassed();
                    } else {
                        $this->generateFailed();
                    }
                }

                /**
                 * HCaptcha template data included if HCaptcha isn't being bypassed.
                 * Note: Cookie warning is NOT included here due to expected behaviour when lockuser is FALSE.
                 */
                $this->generateContainer(false, $this->CIDRAM['Config']['hcaptcha']['show_api_message']);
            }

            /** Update the IP bypass list if any changes were made. */
            if ($BypassListModified) {
                $Handle = fopen($this->CIDRAM['Vault'] . 'ipbypass.dat', 'wb');
                fwrite($Handle, $BypassList);
                fclose($Handle);
            }
        }

        /** Guard. */
        if (
            empty($Loggable) ||
            empty($this->CIDRAM['BlockInfo']) ||
            strlen($this->CIDRAM['Config']['hcaptcha']['logfile']) === 0 ||
            !($Filename = $this->CIDRAM['BuildPath']($this->CIDRAM['Vault'] . $this->CIDRAM['Config']['hcaptcha']['logfile']))
        ) {
            return;
        }

        $Truncate = $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate']);
        $WriteMode = (!file_exists($Filename) || $Truncate > 0 && filesize($Filename) >= $Truncate) ? 'wb' : 'ab';
        $Data = sprintf(
            "%1\$s%2\$s - %3\$s%4\$s - %5\$s%6\$s\n",
            $this->CIDRAM['L10N']->getString('field_ipaddr'),
            $this->CIDRAM['Config']['legal']['pseudonymise_ip_addresses'] ? $this->CIDRAM['Pseudonymise-IP']($this->CIDRAM['IPAddr']) : $this->CIDRAM['IPAddr'],
            $this->CIDRAM['L10N']->getString('field_datetime'),
            $this->CIDRAM['BlockInfo']['DateTime'],
            $this->CIDRAM['L10N']->getString('field_captcha'),
            $this->CIDRAM['BlockInfo']['CAPTCHA']
        );

        /** Adds a second newline to match the standard block events logfile in case of combining the logfiles. */
        if ($this->CIDRAM['Config']['hcaptcha']['logfile'] === $this->CIDRAM['Config']['general']['logfile']) {
            $Data .= "\n";
        }

        $File = fopen($Filename, $WriteMode);
        fwrite($File, $Data);
        fclose($File);
        if ($WriteMode === 'wb') {
            $this->CIDRAM['LogRotation']($this->CIDRAM['Config']['hcaptcha']['logfile']);
        }
    }

    /**
     * Generate HCaptcha form template data.
     *
     * @param string $SiteKey The sitekey to use.
     * @param string $API The API to use.
     * @param bool $CookieWarn Whether to display a cookie warning.
     * @param bool $ApiMessage Whether to display messages about the API used.
     * @return string The template form data.
     */
    private function generateTemplateData($SiteKey, $API, $CookieWarn = false, $ApiMessage = false)
    {
        header(sprintf(
            "Content-Security-Policy: default-src 'none';\nconnect-src %2\$s;\nframe-src %2\$s;\nscript-src %1\$s %2\$s;",
            'https://assets.hcaptcha.com',
            'https://hcaptcha.com'
        ));
        $Script = '<script src="https://hcaptcha.com/1/api.js?onload=onloadCallback&render=explicit" async defer></script>';
        $Script .= '<script type="text/javascript">document.getElementById(\'hostnameoverride\').value=window.location.hostname;</script>';
        return $API === 'Invisible' ? sprintf(
            "\n<hr />\n<p class=\"detected\">%s%s<br /></p>\n" .
            '<div class="gForm">' .
                '<div id="hcform" class="h-captcha" data-sitekey="%s" data-theme="%s" data-callback="onSubmitCallback" data-size="invisible"></div>' .
            "</div>\n" .
            '<form id="gF" method="POST" action="" class="gForm" onsubmit="javascript:hcaptcha.execute()">' .
                '<input id="rData" type="hidden" name="hc-response" value="" />%s' .
            "</form>\n" .
            "<script type=\"text/javascript\">function onSubmitCallback(token){document.getElementById('rData').value=hcaptcha.getResponse(window.document.hcwidget);document.getElementById('gF').submit()}</script>\n",
            $ApiMessage ? '{captcha_message_invisible}' : '',
            $CookieWarn ? '<br />{captcha_cookie_warning}' : '',
            $SiteKey,
            $this->determineTheme(),
            $this->TemplateInsert
        ) . $Script . "\n" : sprintf(
            "\n<hr />\n<p class=\"detected\">%s%s<br /></p>\n" .
            '<form method="POST" action="" class="gForm">' .
                '<input id="rData" type="hidden" name="hc-response" value="" />' .
                '<div id="hcform" data-theme="%s" data-callback="onSubmitCallback"></div>' .
                '<div>%s<input type="submit" value="{label_submit}" /></div>' .
            "</form>\n" .
            "<script type=\"text/javascript\">function onSubmitCallback(token){document.getElementById('rData').value=hcaptcha.getResponse(window.document.hcwidget)}</script>\n",
            $ApiMessage ? '{captcha_message}' : '',
            $CookieWarn ? '<br />{captcha_cookie_warning}' : '',
            $this->determineTheme(),
            $this->TemplateInsert
        ) . $Script;
    }

    /**
     * Generate HCaptcha callback data.
     *
     * @param string $SiteKey The sitekey to use.
     * @param string $API The API to use.
     * @return string The callback data.
     */
    private function generateCallbackData($SiteKey, $API)
    {
        return sprintf(
            "\n  <script type=\"text/javascript\">var onloadCallback=function(){window.document.hcwidget=hcaptcha.render(%s)%s}</script>",
            "'hcform',{sitekey:'" . $SiteKey . "', theme:'" . $this->determineTheme() . "'}",
            ($API === 'Invisible') ? ';hcaptcha.execute()' : ''
        );
    }

    /**
     * Fetch results from the HCaptcha API.
     * @link https://docs.hcaptcha.com/switch
     *
     * @return void
     */
    private function doResponse()
    {
        $this->Results = $this->CIDRAM['Request']('https://hcaptcha.com/siteverify', [
            'secret' => $this->CIDRAM['Config']['hcaptcha']['secret'],
            'response' => $_POST['hc-response'],
            'remoteip' => $this->CIDRAM['IPAddr']
        ]);
        $this->Bypass = (strpos($this->Results, '"success":true,') !== false);
    }

    /**
     * Data generation container.
     *
     * @param bool $CookieWarn Whether to display a cookie warning.
     * @param bool $ApiMessage Whether to display messages about the API used.
     * @return void
     */
    private function generateContainer($CookieWarn = false, $ApiMessage = false)
    {
        /** Guard. */
        if ($this->Bypass) {
            return;
        }

        $this->CIDRAM['Config']['template_data']['captcha_api_include'] = $this->generateCallbackData(
            $this->CIDRAM['Config']['hcaptcha']['sitekey'],
            $this->CIDRAM['Config']['hcaptcha']['api']
        );
        $this->CIDRAM['Config']['template_data']['captcha_div_include'] = $this->generateTemplateData(
            $this->CIDRAM['Config']['hcaptcha']['sitekey'],
            $this->CIDRAM['Config']['hcaptcha']['api'],
            $CookieWarn,
            $ApiMessage
        );
    }
}
