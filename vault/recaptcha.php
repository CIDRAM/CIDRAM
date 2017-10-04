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
 * This file: reCAPTCHA module (last modified: 2017.10.03).
 */

/**
 * Fetch results from the reCAPTCHA API.
 * (Documentation: https://developers.google.com/recaptcha/docs/verify).
 */
$CIDRAM['reCAPTCHA']['DoResponse'] = function () use (&$CIDRAM) {
    $CIDRAM['reCAPTCHA']['Results'] = $CIDRAM['Request']('https://www.google.com/recaptcha/api/siteverify', array(
        'secret' => $CIDRAM['Config']['recaptcha']['secret'],
        'response' => $_POST['g-recaptcha-response'],
        'remoteip' => $_SERVER[$CIDRAM['Config']['general']['ipaddr']]
    ));
    $Offset = strpos($CIDRAM['reCAPTCHA']['Results'], '"success": ');
    $CIDRAM['reCAPTCHA']['Bypass'] = (
        $Offset !== false && substr($CIDRAM['reCAPTCHA']['Results'], $Offset + 11, 4) === 'true'
    );
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
     * Verify whether they've passed the CAPTCHA, update cookies and/or
     * generate CAPTCHA fields.
     */
    if (
        $CIDRAM['reCAPTCHA']['UsrHash'] &&
        $CIDRAM['reCAPTCHA']['UsrMeld'] &&
        password_verify($CIDRAM['reCAPTCHA']['UsrMeld'], $CIDRAM['reCAPTCHA']['UsrHash'])
    ) {

        $CIDRAM['reCAPTCHA']['Bypass'] = true;
        $CIDRAM['BlockInfo']['SignatureCount'] = 0;

    } else {

        /** Set status for reCAPTCHA block information. */
        $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['lang']['recaptcha_enabled'];
        /** We've received a response. */
        if (!empty($_POST['g-recaptcha-response'])) {
            $CIDRAM['reCAPTCHA']['Loggable'] = true;
            $CIDRAM['reCAPTCHA']['DoResponse']();
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
                $CIDRAM['reCAPTCHA']['UsrHash'] = password_hash($CIDRAM['reCAPTCHA']['Cookie'], $CIDRAM['DefaultAlgo']);
                $CIDRAM['reCAPTCHA']['Cookie'] = $CIDRAM['reCAPTCHA']['UsrHash'] . ',' . base64_encode($CIDRAM['reCAPTCHA']['UsrSalt']);
                setcookie('CIDRAM', $CIDRAM['reCAPTCHA']['Cookie'], $CIDRAM['Now'] + $CIDRAM['reCAPTCHA']['Expiry'], '/', (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '', false, true);
                /** Reset signature count. */
                $CIDRAM['BlockInfo']['SignatureCount'] = 0;
                /** Append to the hash list. */
                $CIDRAM['reCAPTCHA']['HashList'] .= $CIDRAM['reCAPTCHA']['UsrHash'] . ',' . ($CIDRAM['Now'] + $CIDRAM['reCAPTCHA']['Expiry']) . "\n";
                $CIDRAM['reCAPTCHA']['HashListMod'] = true;
                /** Set status for reCAPTCHA block information. */
                $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['lang']['recaptcha_passed'];
                /** Append to reCAPTCHA statistics if necessary. */
                if ($CIDRAM['Config']['general']['statistics']) {
                    $CIDRAM['Cache']['Statistics']['reCAPTCHA-Passed']++;
                    $CIDRAM['CacheModified'] = true;
                }
            } else {
                /** Set status for reCAPTCHA block information. */
                $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['lang']['recaptcha_failed'];
                /** Append to reCAPTCHA statistics if necessary. */
                if ($CIDRAM['Config']['general']['statistics']) {
                    $CIDRAM['Cache']['Statistics']['reCAPTCHA-Failed']++;
                    $CIDRAM['CacheModified'] = true;
                }
            }
        }
        if (!$CIDRAM['reCAPTCHA']['Bypass']) {
            $CIDRAM['Config']['template_data']['recaptcha_api_include'] =
                "\n<script src='https://www.google.com/recaptcha/api.js'></script>";
            $CIDRAM['Config']['template_data']['recaptcha_div_include'] =
                "\n<hr />\n<p class=\"detected\">{recaptcha_message}<br />{recaptcha_cookie_warning}<br /><br /></p>" .
                "\n<form method=\"POST\" action=\"\" class=\"center\"><div class=\"g-recaptcha\" data-sitekey=\"" .
                $CIDRAM['Config']['recaptcha']['sitekey'] . "\"></div><br /><br />" .
                "<input type=\"submit\" value=\"{recaptcha_submit}\" /></form>";
        }

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
     * for the current IP, populate relevant variables and/or generate CAPTCHA
     * fields.
     */
    if (strpos($CIDRAM['reCAPTCHA']['BypassList'], "\n" . $_SERVER[$CIDRAM['Config']['general']['ipaddr']] . ',') !== false) {

        $CIDRAM['reCAPTCHA']['Bypass'] = true;
        $CIDRAM['BlockInfo']['SignatureCount'] = 0;

    } else {

        /** Set status for reCAPTCHA block information. */
        $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['lang']['recaptcha_enabled'];
        /** We've received a response. */
        if (!empty($_POST['g-recaptcha-response'])) {
            $CIDRAM['reCAPTCHA']['Loggable'] = true;
            $CIDRAM['reCAPTCHA']['DoResponse']();
            if ($CIDRAM['reCAPTCHA']['Bypass']) {
                /** Reset signature count. */
                $CIDRAM['BlockInfo']['SignatureCount'] = 0;
                /** Append to the IP bypass list. */
                $CIDRAM['reCAPTCHA']['BypassList'] .=
                    $_SERVER[$CIDRAM['Config']['general']['ipaddr']] . ',' .
                    ($CIDRAM['Now'] + $CIDRAM['reCAPTCHA']['Expiry']) . "\n";
                $CIDRAM['reCAPTCHA']['BypassListMod'] = true;
                /** Set status for reCAPTCHA block information. */
                $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['lang']['recaptcha_passed'];
                /** Append to reCAPTCHA statistics if necessary. */
                if ($CIDRAM['Config']['general']['statistics']) {
                    $CIDRAM['Cache']['Statistics']['reCAPTCHA-Passed']++;
                    $CIDRAM['CacheModified'] = true;
                }
            } else {
                /** Set status for reCAPTCHA block information. */
                $CIDRAM['BlockInfo']['reCAPTCHA'] = $CIDRAM['lang']['recaptcha_failed'];
                /** Append to reCAPTCHA statistics if necessary. */
                if ($CIDRAM['Config']['general']['statistics']) {
                    $CIDRAM['Cache']['Statistics']['reCAPTCHA-Failed']++;
                    $CIDRAM['CacheModified'] = true;
                }
            }
        }
        if (!$CIDRAM['reCAPTCHA']['Bypass']) {
            $CIDRAM['Config']['template_data']['recaptcha_api_include'] =
                "\n<script src='https://www.google.com/recaptcha/api.js'></script>";
            $CIDRAM['Config']['template_data']['recaptcha_div_include'] =
                "\n<hr />\n<p class=\"detected\">{recaptcha_message}<br /><br /></p>\n" .
                "<form method=\"POST\" action=\"\"><div class=\"g-recaptcha\" data-sitekey=\"" .
                $CIDRAM['Config']['recaptcha']['sitekey'] . "\"></div><br /><br />" .
                "<input type=\"submit\" value=\"{recaptcha_submit}\" /></form>";
        }

    }

    /** Update the IP bypass list if any changes were made. */
    if ($CIDRAM['reCAPTCHA']['BypassListMod']) {
        $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'ipbypass.dat', 'w');
        fwrite($CIDRAM['Handle'], $CIDRAM['reCAPTCHA']['BypassList']);
        fclose($CIDRAM['Handle']);
    }

}

/** Writing to the reCAPTCHA logfile (if this has been enabled). */
if ($CIDRAM['Config']['recaptcha']['logfile'] && $CIDRAM['reCAPTCHA']['Loggable']) {
    /** Determining date/time information for the logfile name. */
    if (substr_count($CIDRAM['Config']['recaptcha']['logfile'], '{')) {
        $CIDRAM['Config']['recaptcha']['logfile'] =
            $CIDRAM['TimeFormat']($CIDRAM['Now'], $CIDRAM['Config']['recaptcha']['logfile']);
    }
    $CIDRAM['reCAPTCHA']['WriteMode'] = (
        !file_exists($CIDRAM['Vault'] . $CIDRAM['Config']['recaptcha']['logfile']) || (
            $CIDRAM['Config']['general']['truncate'] > 0 &&
            filesize($CIDRAM['Vault'] . $CIDRAM['Config']['recaptcha']['logfile']) >= $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate'])
        )
    ) ? 'w' : 'a';
    $CIDRAM['reCAPTCHA']['LogfileData'] =
        $CIDRAM['lang']['field_ipaddr'] . $_SERVER[$CIDRAM['Config']['general']['ipaddr']] . ' - ' .
        $CIDRAM['lang']['field_datetime'] . $CIDRAM['BlockInfo']['DateTime'] . ' - ' .
        $CIDRAM['lang']['field_reCAPTCHA_state'] . $CIDRAM['BlockInfo']['reCAPTCHA'] . "\n";
    $CIDRAM['reCAPTCHA']['LogfileHandle'] = fopen($CIDRAM['Vault'] . $CIDRAM['Config']['recaptcha']['logfile'], $CIDRAM['reCAPTCHA']['WriteMode']);
    fwrite($CIDRAM['reCAPTCHA']['LogfileHandle'], $CIDRAM['reCAPTCHA']['LogfileData']);
    fclose($CIDRAM['reCAPTCHA']['LogfileHandle']);
}
