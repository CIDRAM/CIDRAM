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
 * This file: reCAPTCHA module (last modified: 2019.09.22).
 */

/**
 * Fetch results from the reCAPTCHA API.
 * Documentation: https://developers.google.com/recaptcha/docs/verify
 */
$CIDRAM['reCAPTCHA']['DoResponse'] = function () use (&$CIDRAM) {
    $CIDRAM['reCAPTCHA']['Results'] = $CIDRAM['Request']('https://www.google.com/recaptcha/api/siteverify', [
        'secret' => $CIDRAM['Config']['recaptcha']['secret'],
        'response' => $_POST['g-recaptcha-response'],
        'remoteip' => $_SERVER[$CIDRAM['IPAddr']]
    ]);
    $Offset = strpos($CIDRAM['reCAPTCHA']['Results'], '"success": ');
    $CIDRAM['reCAPTCHA']['Bypass'] = (
        $Offset !== false && substr($CIDRAM['reCAPTCHA']['Results'], $Offset + 11, 4) === 'true'
    );
};

/** Generate reCAPTCHA form template data. */
$CIDRAM['reCAPTCHA']['GenerateTemplateData'] = function (string $SiteKey, string $API, bool $CookieWarn = false): string {
    $CookieWarn = $CookieWarn ? '<br />{recaptcha_cookie_warning}' : '';
    return $API === 'Invisible' ?
        "\n<hr />\n<p class=\"detected\">{recaptcha_message_invisible}" . $CookieWarn . "<br /></p>\n" .
        "<div class=\"gForm\">" .
            "<div id=\"gForm\" class=\"g-recaptcha\" data-sitekey=\"" . $SiteKey .
            "\" data-callback=\"onSubmitCallback\" data-size=\"invisible\"></div>" .
        "</div>\n" .
        "<form id=\"gF\" method=\"POST\" action=\"\" class=\"gForm\">" .
            "<input id=\"rData\" type=\"hidden\" name=\"g-recaptcha-response\" value=\"\">" .
        "</form>\n" .
        "<script type=\"text/javascript\">function onSubmitCallback(token){document.getElementById('rData').value=token;document.getElementById('gF').submit()}</script>\n" .
        "<script src=\"https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit\" async defer></script>\n"
    :
        "\n<hr />\n<p class=\"detected\">{recaptcha_message}" . $CookieWarn . "<br /></p>\n" .
        "<form method=\"POST\" action=\"\" class=\"gForm\" onsubmit=\"javascript:grecaptcha.execute();\">" .
            "<div id=\"gForm\"></div><div><input type=\"submit\" value=\"{recaptcha_submit}\" /></div>" .
        "</form>\n" .
        "<script src=\"https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit\" async defer></script>";
};

/** Generate reCAPTCHA callback data. */
$CIDRAM['reCAPTCHA']['GenerateCallbackData'] = function (string $SiteKey, string $API): string {
    $Params = "'gForm',{'sitekey':'" . $SiteKey . "'" . ($API === 'Invisible' ? ",'size':'invisible'" : '') . '}';
    $More = ($API === 'Invisible') ? "grecaptcha.execute();" : '';
    return
        "\n  <script type=\"text/javascript\">" .
        "var onloadCallback=function(){grecaptcha.render(" . $Params . ');' . $More . '}</script>';
};

/** Generate data for failed attempts. */
$CIDRAM['reCAPTCHA']['GenerateFailed'] = function () use (&$CIDRAM) {
    /** Set status for reCAPTCHA block information. */
    $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['L10N']->getString('recaptcha_failed');
    /** Append to reCAPTCHA statistics if necessary. */
    if ($CIDRAM['Config']['general']['statistics']) {
        $CIDRAM['Statistics']['reCAPTCHA-Failed']++;
        $CIDRAM['Statistics-Modified'] = true;
    }
};

/** Generate data for passed attempts. */
$CIDRAM['reCAPTCHA']['GeneratePassed'] = function () use (&$CIDRAM) {
    /** Set status for reCAPTCHA block information. */
    $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['L10N']->getString('recaptcha_passed');
    /** Append to reCAPTCHA statistics if necessary. */
    if ($CIDRAM['Config']['general']['statistics']) {
        $CIDRAM['Statistics']['reCAPTCHA-Passed']++;
        $CIDRAM['Statistics-Modified'] = true;
    }
};

/** Data generation container. */
$CIDRAM['reCAPTCHA']['GenerateContainer'] = function (bool $CookieWarn = false) use (&$CIDRAM) {
    if (!$CIDRAM['reCAPTCHA']['Bypass']) {
        $CIDRAM['Config']['template_data']['recaptcha_api_include'] = $CIDRAM['reCAPTCHA']['GenerateCallbackData'](
            $CIDRAM['Config']['recaptcha']['sitekey'], $CIDRAM['Config']['recaptcha']['api']
        );
        $CIDRAM['Config']['template_data']['recaptcha_div_include'] = $CIDRAM['reCAPTCHA']['GenerateTemplateData'](
            $CIDRAM['Config']['recaptcha']['sitekey'], $CIDRAM['Config']['recaptcha']['api'], $CookieWarn
        );
    }
};

/** Refer to the documentation regarding the behaviour of "lockuser". */
if ($CIDRAM['Config']['recaptcha']['lockuser']) {

    /** Attempt to load the hash list. */
    if (file_exists($CIDRAM['Vault'] . 'hashes.dat')) {
        $CIDRAM['reCAPTCHA']['HashList'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'hashes.dat');
        $CIDRAM['reCAPTCHA']['HashListMod'] = false;
    } else {
        $CIDRAM['reCAPTCHA']['HashList'] = "HASH LIST\n---------\n";
        $CIDRAM['reCAPTCHA']['HashListMod'] = true;
    }

    /** Cycle through the hash list and remove any expired hashes. */
    $CIDRAM['ClearExpired']($CIDRAM['reCAPTCHA']['HashList'], $CIDRAM['reCAPTCHA']['HashListMod']);

    /**
     * Determine whether a reCAPTCHA instance has already been completed by the
     * user and populate relevant variables.
     */
    if (!empty($_COOKIE['CIDRAM']) && $CIDRAM['reCAPTCHA']['Split'] = strpos($_COOKIE['CIDRAM'], ',')) {
        $CIDRAM['reCAPTCHA']['UsrHash'] = substr($_COOKIE['CIDRAM'], 0, $CIDRAM['reCAPTCHA']['Split']);
        if (strpos($CIDRAM['reCAPTCHA']['HashList'], "\n" . $CIDRAM['reCAPTCHA']['UsrHash'] . ',') !== false) {
            $CIDRAM['reCAPTCHA']['UsrSalt'] = base64_decode(substr($_COOKIE['CIDRAM'], $CIDRAM['reCAPTCHA']['Split']));
            $CIDRAM['reCAPTCHA']['UsrMeld'] = $CIDRAM['Config']['recaptcha']['lockip'] ? $CIDRAM['Meld'](
                $CIDRAM['Salt'], $CIDRAM['reCAPTCHA']['UsrSalt'], $_SERVER[$CIDRAM['IPAddr']]
            ) : $CIDRAM['Meld'](
                $CIDRAM['Salt'], $CIDRAM['reCAPTCHA']['UsrSalt']
            );
            if (strpos($CIDRAM['reCAPTCHA']['UsrMeld'], "\x00") !== false) {
                $CIDRAM['reCAPTCHA']['UsrMeld'] = str_replace("\x00", '', $CIDRAM['reCAPTCHA']['UsrMeld']);
            }
        }
    }
    if (empty($CIDRAM['reCAPTCHA']['UsrMeld'])) {
        $CIDRAM['reCAPTCHA']['UsrMeld'] = $CIDRAM['reCAPTCHA']['UsrSalt'] = $CIDRAM['reCAPTCHA']['UsrHash'] = '';
    }

    /** Verify whether they've passed, update cookies, generate fields. */
    if (
        $CIDRAM['reCAPTCHA']['UsrHash'] &&
        $CIDRAM['reCAPTCHA']['UsrMeld'] &&
        password_verify($CIDRAM['reCAPTCHA']['UsrMeld'], $CIDRAM['reCAPTCHA']['UsrHash'])
    ) {

        $CIDRAM['reCAPTCHA']['Bypass'] = true;
        $CIDRAM['BlockInfo']['SignatureCount'] = 0;

        /** Fix for infraction escalation bug. */
        if (isset($CIDRAM['Tracking'][$CIDRAM['BlockInfo']['IPAddr']])) {
            unset($CIDRAM['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]);
            $CIDRAM['Tracking-Modified'] = true;
        }

    } else {

        /** Set status for reCAPTCHA block information. */
        $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['L10N']->getString('recaptcha_enabled');
        /** We've received a response. */
        if (isset($_POST['g-recaptcha-response'])) {
            $CIDRAM['reCAPTCHA']['Loggable'] = true;
            $CIDRAM['reCAPTCHA']['DoResponse']();
            if ($CIDRAM['reCAPTCHA']['Bypass']) {
                /** Generate client-side salt. */
                $CIDRAM['reCAPTCHA']['UsrSalt'] = $CIDRAM['GenerateSalt']();
                /** Generate authentication hash. */
                $CIDRAM['reCAPTCHA']['Cookie'] = $CIDRAM['Config']['recaptcha']['lockip'] ? $CIDRAM['Meld'](
                    $CIDRAM['Salt'], $CIDRAM['reCAPTCHA']['UsrSalt'], $_SERVER[$CIDRAM['IPAddr']]
                ) : $CIDRAM['Meld'](
                    $CIDRAM['Salt'], $CIDRAM['reCAPTCHA']['UsrSalt']
                );
                if (strpos($CIDRAM['reCAPTCHA']['Cookie'], "\x00") !== false) {
                    $CIDRAM['reCAPTCHA']['Cookie'] = str_replace("\x00", '', $CIDRAM['reCAPTCHA']['Cookie']);
                }
                $CIDRAM['reCAPTCHA']['UsrHash'] = password_hash($CIDRAM['reCAPTCHA']['Cookie'], $CIDRAM['DefaultAlgo']);
                $CIDRAM['reCAPTCHA']['Cookie'] = $CIDRAM['reCAPTCHA']['UsrHash'] . ',' . base64_encode($CIDRAM['reCAPTCHA']['UsrSalt']);
                setcookie('CIDRAM', $CIDRAM['reCAPTCHA']['Cookie'], $CIDRAM['Now'] + $CIDRAM['reCAPTCHA']['Expiry'], '/', $CIDRAM['HTTP_HOST'], false, true);
                /** Reset signature count. */
                $CIDRAM['BlockInfo']['SignatureCount'] = 0;
                /** Append to the hash list. */
                $CIDRAM['reCAPTCHA']['HashList'] .= $CIDRAM['reCAPTCHA']['UsrHash'] . ',' . ($CIDRAM['Now'] + $CIDRAM['reCAPTCHA']['Expiry']) . "\n";
                $CIDRAM['reCAPTCHA']['HashListMod'] = true;
                $CIDRAM['reCAPTCHA']['GeneratePassed']();
            } else {
                $CIDRAM['reCAPTCHA']['GenerateFailed']();
            }
        }

        /**
         * reCAPTCHA template data included if reCAPTCHA not being bypassed.
         * Note: Cookie warning IS included here due to expected behaviour when lockuser is TRUE.
         */
        $CIDRAM['reCAPTCHA']['GenerateContainer']($CIDRAM['Config']['recaptcha']['show_cookie_warning']);

    }

    /** Update the hash list if any changes were made. */
    if ($CIDRAM['reCAPTCHA']['HashListMod']) {
        $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'hashes.dat', 'w');
        fwrite($CIDRAM['Handle'], $CIDRAM['reCAPTCHA']['HashList']);
        fclose($CIDRAM['Handle']);
    }

} else {

    /** Attempt to load the IP bypass list. */
    if (file_exists($CIDRAM['Vault'] . 'ipbypass.dat')) {
        $CIDRAM['reCAPTCHA']['BypassList'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'ipbypass.dat');
        $CIDRAM['reCAPTCHA']['BypassListMod'] = false;
    } else {
        $CIDRAM['reCAPTCHA']['BypassList'] = "IP BYPASS LIST\n--------------\n";
        $CIDRAM['reCAPTCHA']['BypassListMod'] = true;
    }

    /** Cycle through the IP bypass list and remove any expired IPs. */
    $CIDRAM['ClearExpired']($CIDRAM['reCAPTCHA']['BypassList'], $CIDRAM['reCAPTCHA']['BypassListMod']);

    /**
     * Verify whether a reCAPTCHA instance has already been completed before
     * for the current IP, populate relevant variables, generate fields.
     */
    if (strpos($CIDRAM['reCAPTCHA']['BypassList'], "\n" . $_SERVER[$CIDRAM['IPAddr']] . ',') !== false) {

        $CIDRAM['reCAPTCHA']['Bypass'] = true;
        $CIDRAM['BlockInfo']['SignatureCount'] = 0;

        /** Fix for infraction escalation bug. */
        if (isset($CIDRAM['Tracking'][$CIDRAM['BlockInfo']['IPAddr']])) {
            unset($CIDRAM['Tracking'][$CIDRAM['BlockInfo']['IPAddr']]);
            $CIDRAM['Tracking-Modified'] = true;
        }

    } else {

        /** Set status for reCAPTCHA block information. */
        $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['L10N']->getString('recaptcha_enabled');
        /** We've received a response. */
        if (isset($_POST['g-recaptcha-response'])) {
            $CIDRAM['reCAPTCHA']['Loggable'] = true;
            $CIDRAM['reCAPTCHA']['DoResponse']();
            if ($CIDRAM['reCAPTCHA']['Bypass']) {
                /** Reset signature count. */
                $CIDRAM['BlockInfo']['SignatureCount'] = 0;
                /** Append to the IP bypass list. */
                $CIDRAM['reCAPTCHA']['BypassList'] .= $_SERVER[$CIDRAM['IPAddr']] . ',' . (
                    $CIDRAM['Now'] + $CIDRAM['reCAPTCHA']['Expiry']
                ) . "\n";
                $CIDRAM['reCAPTCHA']['BypassListMod'] = true;
                $CIDRAM['reCAPTCHA']['GeneratePassed']();
            } else {
                $CIDRAM['reCAPTCHA']['GenerateFailed']();
            }
        }

        /**
         * reCAPTCHA template data included if reCAPTCHA not being bypassed.
         * Note: Cookie warning is NOT included here due to expected behaviour when lockuser is FALSE.
         */
        $CIDRAM['reCAPTCHA']['GenerateContainer']();

    }

    /** Update the IP bypass list if any changes were made. */
    if ($CIDRAM['reCAPTCHA']['BypassListMod']) {
        $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'ipbypass.dat', 'w');
        fwrite($CIDRAM['Handle'], $CIDRAM['reCAPTCHA']['BypassList']);
        fclose($CIDRAM['Handle']);
    }

}

/** Fire reCAPTCHA write to log event. */
$CIDRAM['Events']->fireEvent('reCaptchaLog');
