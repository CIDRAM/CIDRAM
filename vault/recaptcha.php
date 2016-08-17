<?php
/**
 * This file is a part of the CIDRAM package, and can be downloaded for free
 * from {@link https://github.com/Maikuolan/CIDRAM/ GitHub}.
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: reCAPTCHA module (last modified: 2016.08.17).
 */

/** Attempt to load the hash list. */
if (file_exists($CIDRAM['Vault'] . 'hashes.dat')) {
    $CIDRAM['reCAPTCHA']['HashList'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'hashes.dat');
    $CIDRAM['reCAPTCHA']['HashListMod'] = false;
} else {
    $CIDRAM['reCAPTCHA']['HashList'] = "HASH LIST\n---------\n";
    $CIDRAM['reCAPTCHA']['HashListMod'] = true;
}

/** Cycle through the hash list and remove any expired hashes. */
if ($CIDRAM['reCAPTCHA']['HashList']) {
    $CIDRAM['reCAPTCHA']['HashPosEnd'] = 0;
    while (true) {
        $CIDRAM['reCAPTCHA']['HashPosBegin'] = $CIDRAM['reCAPTCHA']['HashPosEnd'];
        if ($CIDRAM['reCAPTCHA']['HashPosEnd'] = strpos($CIDRAM['reCAPTCHA']['HashList'], "\n", $CIDRAM['reCAPTCHA']['HashPosBegin'] + 1)) {
            $CIDRAM['reCAPTCHA']['HashLine'] = substr(
                $CIDRAM['reCAPTCHA']['HashList'],
                $CIDRAM['reCAPTCHA']['HashPosBegin'],
                $CIDRAM['reCAPTCHA']['HashPosEnd'] - $CIDRAM['reCAPTCHA']['HashPosBegin']
            );
            if ($CIDRAM['reCAPTCHA']['Split'] = strpos($CIDRAM['reCAPTCHA']['HashLine'], ':')) {
                $CIDRAM['reCAPTCHA']['HashExpiry'] = (int)substr($CIDRAM['reCAPTCHA']['HashLine'], $CIDRAM['reCAPTCHA']['Split'] + 1);
                if ($CIDRAM['reCAPTCHA']['HashExpiry'] < $CIDRAM['Now']) {
                    $CIDRAM['reCAPTCHA']['HashList'] = str_replace($CIDRAM['reCAPTCHA']['HashLine'], '', $CIDRAM['reCAPTCHA']['HashList']);
                    $CIDRAM['reCAPTCHA']['HashPosEnd'] = 0;
                    $CIDRAM['reCAPTCHA']['HashListMod'] = true;
                }
            }
        } else {
            break;
        }
    }
}

/**
 * Determine whether the user has already entered a CAPTCHA before and populate
 * relevant variables.
 */
if (!empty($_COOKIE['CIDRAM']) && $CIDRAM['reCAPTCHA']['Split'] = strpos($_COOKIE['CIDRAM'], '|')) {
    $CIDRAM['reCAPTCHA']['UsrHash'] = substr($_COOKIE['CIDRAM'], 0, $CIDRAM['reCAPTCHA']['Split']);
    if (strpos($CIDRAM['reCAPTCHA']['HashList'], "\n" . $CIDRAM['reCAPTCHA']['UsrHash'] . ':') !== false) {
        $CIDRAM['reCAPTCHA']['UsrSalt'] = base64_decode(substr($_COOKIE['CIDRAM'], $CIDRAM['reCAPTCHA']['Split']));
        if ($CIDRAM['Config']['recaptcha']['lockip']) {
            $CIDRAM['reCAPTCHA']['UsrMeld'] = $CIDRAM['Meld'](
                $CIDRAM['Salt'],
                $CIDRAM['reCAPTCHA']['UsrSalt'],
                $_SERVER[$CIDRAM['Config']['general']['ipaddr']]
            );
        } else {
            $CIDRAM['reCAPTCHA']['UsrMeld'] = $CIDRAM['Meld'](
                $CIDRAM['Salt'],
                $CIDRAM['reCAPTCHA']['UsrSalt']
            );
        }
        if (strpos($CIDRAM['reCAPTCHA']['UsrMeld'], "\x00") !== false) {
            $CIDRAM['reCAPTCHA']['UsrMeld'] = str_replace("\x00", '', $CIDRAM['reCAPTCHA']['UsrMeld']);
        }
    }
}
if (empty($CIDRAM['reCAPTCHA']['UsrMeld'])) {
    $CIDRAM['reCAPTCHA']['UsrMeld'] = $CIDRAM['reCAPTCHA']['UsrSalt'] = $CIDRAM['reCAPTCHA']['UsrHash'] = '';
}

/**
 * Verify whether they've passed the CAPTCHA, update cookies and/or generate
 * CAPTCHA fields.
 */
if (
    $CIDRAM['reCAPTCHA']['UsrHash'] &&
    $CIDRAM['reCAPTCHA']['UsrMeld'] &&
    password_verify($CIDRAM['reCAPTCHA']['UsrMeld'], $CIDRAM['reCAPTCHA']['UsrHash'])
) {

    $CIDRAM['reCAPTCHA']['Bypass'] = true;
    $CIDRAM['BlockInfo']['SignatureCount'] = 0;

} else {

    if (
        $CIDRAM['Config']['recaptcha']['usemode'] === 1 || (
            $CIDRAM['Config']['recaptcha']['usemode'] === 2 &&
            $CIDRAM['Config']['recaptcha']['enabled'] === true
        )
    ) {
        /** Set status for reCAPTCHA block information. */
        $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['lang']['recaptcha_enabled'];
        /** We've received a response. */
        if (!empty($_POST['g-recaptcha-response'])) {
            $CIDRAM['reCAPTCHA']['Loggable'] = true;
            /**
             * Fetch results from the reCAPTCHA API.
             * (Documentation: https://developers.google.com/recaptcha/docs/verify).
             */
            $CIDRAM['reCAPTCHA']['Results'] = $CIDRAM['Request']('https://www.google.com/recaptcha/api/siteverify', array(
                'secret' => $CIDRAM['Config']['recaptcha']['secret'],
                'response' => $_POST['g-recaptcha-response'],
                'remoteip' => $_SERVER[$CIDRAM['Config']['general']['ipaddr']]
            ));
            $CIDRAM['reCAPTCHA']['Offset'] = strpos($CIDRAM['reCAPTCHA']['Results'], '"success": ');
            $CIDRAM['reCAPTCHA']['Bypass'] = (
                ($CIDRAM['reCAPTCHA']['Offset'] !== false) &&
                (substr($CIDRAM['reCAPTCHA']['Results'], $CIDRAM['reCAPTCHA']['Offset'] + 11, 4) === 'true')
            );
            if ($CIDRAM['reCAPTCHA']['Bypass']) {
                /** Generate client-side salt. */
                $CIDRAM['reCAPTCHA']['UsrSalt'] = $CIDRAM['GenerateSalt']();
                /** Generate authentication hash. */
                $CIDRAM['reCAPTCHA']['Cookie'] = ($CIDRAM['Config']['recaptcha']['lockip']) ?
                    $CIDRAM['Meld'](
                        $CIDRAM['Salt'],
                        $CIDRAM['reCAPTCHA']['UsrSalt'],
                        $_SERVER[$CIDRAM['Config']['general']['ipaddr']]
                    ) : $CIDRAM['Meld'](
                        $CIDRAM['Salt'],
                        $CIDRAM['reCAPTCHA']['UsrSalt']
                    );
                if (strpos($CIDRAM['reCAPTCHA']['Cookie'], "\x00") !== false) {
                    $CIDRAM['reCAPTCHA']['Cookie'] = str_replace("\x00", '', $CIDRAM['reCAPTCHA']['Cookie']);
                }
                $CIDRAM['reCAPTCHA']['UsrHash'] = password_hash($CIDRAM['reCAPTCHA']['Cookie'], PASSWORD_DEFAULT);
                $CIDRAM['reCAPTCHA']['Cookie'] = $CIDRAM['reCAPTCHA']['UsrHash'] . '|' . base64_encode($CIDRAM['reCAPTCHA']['UsrSalt']);
                setcookie('CIDRAM', $CIDRAM['reCAPTCHA']['Cookie'], $CIDRAM['Now'] + $CIDRAM['reCAPTCHA']['Expiry'], '/', (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '', false, true);
                $CIDRAM['BlockInfo']['SignatureCount'] = 0;
                $CIDRAM['reCAPTCHA']['HashList'] .= $CIDRAM['reCAPTCHA']['UsrHash'] . ':' . ($CIDRAM['Now'] + $CIDRAM['reCAPTCHA']['Expiry']) . "\n";
                $CIDRAM['reCAPTCHA']['HashListMod'] = true;
                /** Set status for reCAPTCHA block information. */
                $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['lang']['recaptcha_passed'];
            } else {
                /** Set status for reCAPTCHA block information. */
                $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['lang']['recaptcha_failed'];
            }
        }
        if (!$CIDRAM['reCAPTCHA']['Bypass']) {
            $CIDRAM['Config']['template_data']['recaptcha_api_include'] =
                "\n<script src='https://www.google.com/recaptcha/api.js'></script>";
            $CIDRAM['Config']['template_data']['recaptcha_div_include'] =
                "\n<hr />\n<p class=\"textStrong\">{recaptcha_message}<br />{recaptcha_cookie_warning}" .
                "<br /><br /></p>\n<form method=\"POST\" action=\"\"><div class=\"g-recaptcha\" data-sitekey=\"" .
                $CIDRAM['Config']['recaptcha']['sitekey'] . "\"></div><br /><br />" .
                "<input type=\"submit\" value=\"{recaptcha_submit}\" /></form>";
        }

    }

}

/** Update the hash list if any changes were made. */
if ($CIDRAM['reCAPTCHA']['HashListMod']) {
    $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'hashes.dat', 'w');
    fwrite($CIDRAM['Handle'], $CIDRAM['reCAPTCHA']['HashList']);
    fclose($CIDRAM['Handle']);
}

/** Writing to the reCAPTCHA logfile (if this has been enabled). */
if ($CIDRAM['Config']['recaptcha']['logfile'] && $CIDRAM['reCAPTCHA']['Loggable']) {
    /** Determining date/time information for the logfile name. */
    if (substr_count($CIDRAM['Config']['recaptcha']['logfile'], '{')) {
        $CIDRAM['Config']['recaptcha']['logfile'] =
            $CIDRAM['Time2Logfile']($CIDRAM['Now'], $CIDRAM['Config']['recaptcha']['logfile']);
    }
    $CIDRAM['reCAPTCHA']['LogfileData'] =
        $CIDRAM['lang']['field_ipaddr'] . $_SERVER[$CIDRAM['Config']['general']['ipaddr']] . ' - ' .
        $CIDRAM['lang']['field_datetime'] . $CIDRAM['BlockInfo']['DateTime'] . ' - ' .
        $CIDRAM['lang']['field_reCAPTCHA_state'] . $CIDRAM['BlockInfo']['reCAPTCHA'] . "\n";
    $CIDRAM['reCAPTCHA']['LogfileHandle'] = fopen($CIDRAM['Vault'] . $CIDRAM['Config']['recaptcha']['logfile'], 'a');
    fwrite($CIDRAM['reCAPTCHA']['LogfileHandle'], $CIDRAM['reCAPTCHA']['LogfileData']);
    fclose($CIDRAM['reCAPTCHA']['LogfileHandle']);
}
