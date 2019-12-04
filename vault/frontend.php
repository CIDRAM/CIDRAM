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
 * This file: Front-end handler (last modified: 2019.12.01).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** Kill the script if the front-end functions file doesn't exist. */
if (!file_exists($CIDRAM['Vault'] . 'frontend_functions.php')) {
    header('Content-Type: text/plain');
    die('[CIDRAM] Front-end functions file missing! Please reinstall CIDRAM.');
}
/** Load the front-end functions file. */
require $CIDRAM['Vault'] . 'frontend_functions.php';

/** Set page selector if not already set. */
if (empty($CIDRAM['QueryVars']['cidram-page'])) {
    $CIDRAM['QueryVars']['cidram-page'] = '';
}

/** Populate common front-end variables. */
$CIDRAM['FE'] = [

    /** Main front-end HTML template file. */
    'Template' => $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('frontend.html')),

    /** Populated by front-end JavaScript data as per needed. */
    'JS' => '',

    /** Populated by any other header data required for the request (usually nothing). */
    'OtherHead' => '',

    /** Default password hash ("password"). */
    'DefaultPassword' => '$2y$10$FPF5Im9MELEvF5AYuuRMSO.QKoYVpsiu1YU9aDClgrU57XtLof/dK',

    /** Current default language. */
    'FE_Lang' => $CIDRAM['Config']['general']['lang'],

    /** Font magnification. */
    'Magnification' => $CIDRAM['Config']['template_data']['Magnification'],

    /** Warns if maintenance mode is enabled. */
    'MaintenanceWarning' => (
        $CIDRAM['Config']['general']['maintenance_mode']
    ) ? "\n<div class=\"center\"><span class=\"txtRd\">" . $CIDRAM['L10N']->getString('state_maintenance_mode') . '</span></div><hr />' : '',

    /** Define active configuration file. */
    'ActiveConfigFile' => !empty($CIDRAM['Overrides']) ? $CIDRAM['Domain'] . '.config.ini' : 'config.ini',

    /** Current time and date. */
    'DateTime' => $CIDRAM['TimeFormat']($CIDRAM['Now'], $CIDRAM['Config']['general']['timeFormat']),

    /** How the script identifies itself. */
    'ScriptIdent' => $CIDRAM['ScriptIdent'],

    /** Current default theme. */
    'theme' => $CIDRAM['Config']['template_data']['theme'],

    /** List of front-end users will be populated here. */
    'UserList' => "\n",

    /** List of front-end sessions will be populated here. */
    'SessionList' => "\n",

    /** Cache data will be populated here. */
    'Cache' => "\n",

    /**
     * The current user state.
     * -1 = Attempted and failed to log in.
     * 0 = Not logged in.
     * 1 = Logged in.
     * 2 = Logged in, but awaiting two-factor authentication.
     */
    'UserState' => 0,

    /** Taken from either $_POST['username'] or $_COOKIE['CIDRAM-ADMIN'] (the username claimed by the client). */
    'UserRaw' => '',

    /**
     * User permissions.
     * 0 = Not logged in, or awaiting two-factor authentication.
     * 1 = Complete access.
     * 2 = Logs access only.
     * 3 = Cronable.
     */
    'Permissions' => 0,

    /** Will be populated by messages reflecting the current request state. */
    'state_msg' => '',

    /** Will be populated by the current session data. */
    'ThisSession' => '',

    /** Will be populated by either [Log Out] or [Home | Log Out] links. */
    'bNav' => '&nbsp;',

    /** State reflecting whether the current request is cronable. */
    'CronMode' => !empty($_POST['CronMode']),

    /** The user agent of the current request. */
    'UA' => empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'],

    /** The IP address of the current request. */
    'YourIP' => empty($_SERVER[$CIDRAM['IPAddr']]) ? '' : $_SERVER[$CIDRAM['IPAddr']],

    /** Asynchronous mode. */
    'ASYNC' => !empty($_POST['ASYNC']),

    /** Will be populated by the page title. */
    'FE_Title' => ''

];

/** Menu toggle JavaScript, needed by some front-end pages. */
$CIDRAM['MenuToggle'] = '<script type="text/javascript">' .
    'var i,toggler=document.getElementsByClassName("comCat");for(i=0;i<toggl' .
    'er.length;i++)toggler[i].addEventListener("click",function(){this.paren' .
    'tElement.querySelector(".comSub").classList.toggle("active"),!this.clas' .
    'sList.toggle("caret-down")&&this.classList.toggle("caret-up")&&setTimeo' .
    'ut(function(t){t.classList.toggle("caret-up")},200,this)});</script>';

/** Fetch pips data. */
$CIDRAM['Pips_Path'] = $CIDRAM['GetAssetPath']('pips.php', true);
if (!empty($CIDRAM['Pips_Path']) && is_readable($CIDRAM['Pips_Path'])) {
    require $CIDRAM['Pips_Path'];
}

/** Handle webfonts. */
if (empty($CIDRAM['Config']['general']['disable_webfonts'])) {
    $CIDRAM['FE']['Template'] = str_replace(['<!-- WebFont Begin -->', '<!-- WebFont End -->'], '', $CIDRAM['FE']['Template']);
} else {
    $CIDRAM['WebFontPos'] = [
        'Begin' => strpos($CIDRAM['FE']['Template'], '<!-- WebFont Begin -->'),
        'End' => strpos($CIDRAM['FE']['Template'], '<!-- WebFont End -->')
    ];
    if ($CIDRAM['WebFontPos']['Begin'] !== false && $CIDRAM['WebFontPos']['End'] !== false) {
        $CIDRAM['FE']['Template'] = (
            substr($CIDRAM['FE']['Template'], 0, $CIDRAM['WebFontPos']['Begin']) .
            substr($CIDRAM['FE']['Template'], $CIDRAM['WebFontPos']['End'] + 20)
        );
    }
    unset($CIDRAM['WebFontPos']);
}

/** A fix for correctly displaying LTR/RTL text. */
if (empty($CIDRAM['L10N']->Data['Text Direction']) || $CIDRAM['L10N']->Data['Text Direction'] !== 'rtl') {
    $CIDRAM['L10N']->Data['Text Direction'] = 'ltr';
    $CIDRAM['FE']['FE_Align'] = 'left';
    $CIDRAM['FE']['FE_Align_Reverse'] = 'right';
    $CIDRAM['FE']['PIP_Input'] = $CIDRAM['FE']['PIP_Right'];
    $CIDRAM['FE']['Gradient_Degree'] = 90;
    $CIDRAM['FE']['Half_Border'] = 'solid solid none none';
    $CIDRAM['FE']['45deg'] = '45deg';
} else {
    $CIDRAM['FE']['FE_Align'] = 'right';
    $CIDRAM['FE']['FE_Align_Reverse'] = 'left';
    $CIDRAM['FE']['PIP_Input'] = $CIDRAM['FE']['PIP_Left'];
    $CIDRAM['FE']['Gradient_Degree'] = 270;
    $CIDRAM['FE']['Half_Border'] = 'solid none none solid';
    $CIDRAM['FE']['45deg'] = '-45deg';
}

/** A simple passthru for non-private theme images and related data. */
if (!empty($CIDRAM['QueryVars']['cidram-asset'])) {

    $CIDRAM['Success'] = false;

    if (
        $CIDRAM['FileManager-PathSecurityCheck']($CIDRAM['QueryVars']['cidram-asset']) &&
        !preg_match('~[^\da-z._]~i', $CIDRAM['QueryVars']['cidram-asset'])
    ) {
        $CIDRAM['ThisAsset'] = $CIDRAM['GetAssetPath']($CIDRAM['QueryVars']['cidram-asset'], true);
        if (
            $CIDRAM['ThisAsset'] &&
            is_readable($CIDRAM['ThisAsset']) &&
            ($CIDRAM['ThisAssetDel'] = strrpos($CIDRAM['ThisAsset'], '.')) !== false
        ) {
            $CIDRAM['ThisAssetType'] = strtolower(substr($CIDRAM['ThisAsset'], $CIDRAM['ThisAssetDel'] + 1));
            if ($CIDRAM['ThisAssetType'] === 'jpeg') {
                $CIDRAM['ThisAssetType'] = 'jpg';
            }
            if (preg_match('/^(gif|jpg|png|webp)$/', $CIDRAM['ThisAssetType'])) {
                /** Set asset mime-type (images). */
                header('Content-Type: image/' . $CIDRAM['ThisAssetType']);
                $CIDRAM['Success'] = true;
            } elseif ($CIDRAM['ThisAssetType'] === 'js') {
                /** Set asset mime-type (JavaScript). */
                header('Content-Type: text/javascript');
                $CIDRAM['Success'] = true;
            }
            if ($CIDRAM['Success']) {
                if (!empty($CIDRAM['QueryVars']['theme'])) {
                    /** Prevents needlessly reloading static assets. */
                    header('Last-Modified: ' . gmdate(DATE_RFC1123, filemtime($CIDRAM['ThisAsset'])));
                }
                /** Send asset data. */
                echo $CIDRAM['ReadFile']($CIDRAM['ThisAsset']);
            }
        }
    }

    if ($CIDRAM['Success']) {
        die;
    }
    unset($CIDRAM['ThisAssetType'], $CIDRAM['ThisAssetDel'], $CIDRAM['ThisAsset'], $CIDRAM['Success']);

}

/** A simple passthru for the front-end CSS. */
if ($CIDRAM['QueryVars']['cidram-page'] === 'css') {
    $CIDRAM['AssetPath'] = $CIDRAM['GetAssetPath']('frontend.css');
    /** Sets mime-type. */
    header('Content-Type: text/css');
    if (!empty($CIDRAM['QueryVars']['theme'])) {
        /** Prevents needlessly reloading static assets. */
        header('Last-Modified: ' . gmdate(DATE_RFC1123, filemtime($CIDRAM['AssetPath'])));
    }
    /** Sends asset data. */
    echo $CIDRAM['ParseVars']($CIDRAM['L10N']->Data + $CIDRAM['FE'], $CIDRAM['ReadFile']($CIDRAM['AssetPath']));
    die;
}

/** A simple passthru for the favicon. */
if ($CIDRAM['QueryVars']['cidram-page'] === 'favicon') {
    header('Content-Type: image/gif');
    echo base64_decode($CIDRAM['favicon']);
    die;
}

/** Set form target if not already set. */
$CIDRAM['FE']['FormTarget'] = empty($_POST['cidram-form-target']) ? '' : $_POST['cidram-form-target'];

/** Used by a safety mechanism against a potential attack vector. */
$CIDRAM['frontend.dat.safety'] = file_exists($CIDRAM['Vault'] . 'fe_assets/frontend.dat.safety');

/** Fetch user list, sessions list, and the front-end cache, or rebuild it if it doesn't exist. */
if ($CIDRAM['FE']['FrontEndData'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/frontend.dat')) {
    $CIDRAM['FE']['Rebuild'] = false;
} else {
    if ($CIDRAM['frontend.dat.safety']) {
        header('Content-Type: text/plain');
        die('[CIDRAM] ' . $CIDRAM['L10N']->getString('security_warning'));
    }
    $CIDRAM['FE']['FrontEndData'] = "USERS\n-----\nYWRtaW4=," . $CIDRAM['FE']['DefaultPassword'] . ",1\n\nSESSIONS\n--------\n\nCACHE\n-----\n";
    $CIDRAM['FE']['Rebuild'] = true;
}

/** Engage safety mechanism. */
if (!$CIDRAM['frontend.dat.safety']) {
    $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'fe_assets/frontend.dat.safety', 'wb');
    fwrite($CIDRAM['Handle'], '.');
    fclose($CIDRAM['Handle']);
}

$CIDRAM['FE']['UserListPos'] = strpos($CIDRAM['FE']['FrontEndData'], "USERS\n-----\n");
$CIDRAM['FE']['SessionListPos'] = strpos($CIDRAM['FE']['FrontEndData'], "SESSIONS\n--------\n");
$CIDRAM['FE']['CachePos'] = strpos($CIDRAM['FE']['FrontEndData'], "CACHE\n-----\n");
if ($CIDRAM['FE']['UserListPos'] !== false) {
    $CIDRAM['FE']['UserList'] = substr(
        $CIDRAM['FE']['FrontEndData'],
        $CIDRAM['FE']['UserListPos'] + 11,
        $CIDRAM['FE']['SessionListPos'] - $CIDRAM['FE']['UserListPos'] - 12
    );
}
if ($CIDRAM['FE']['SessionListPos'] !== false) {
    $CIDRAM['FE']['SessionList'] = substr(
        $CIDRAM['FE']['FrontEndData'],
        $CIDRAM['FE']['SessionListPos'] + 17,
        $CIDRAM['FE']['CachePos'] - $CIDRAM['FE']['SessionListPos'] - 18
    );
}
if ($CIDRAM['FE']['CachePos'] !== false) {
    $CIDRAM['FE']['Cache'] = substr(
        $CIDRAM['FE']['FrontEndData'],
        $CIDRAM['FE']['CachePos'] + 11
    );
}

/** Clear expired sessions. */
$CIDRAM['ClearExpired']($CIDRAM['FE']['SessionList'], $CIDRAM['FE']['Rebuild']);

/** Clear expired cache entries. */
$CIDRAM['ClearExpired']($CIDRAM['FE']['Cache'], $CIDRAM['FE']['Rebuild']);

/** Initialise cache. */
$CIDRAM['InitialiseCache']();

/** Brute-force security check. */
if (($CIDRAM['LoginAttempts'] = (int)$CIDRAM['FECacheGet'](
    $CIDRAM['FE']['Cache'], 'LoginAttempts' . $_SERVER[$CIDRAM['IPAddr']]
)) && ($CIDRAM['LoginAttempts'] >= $CIDRAM['Config']['general']['max_login_attempts'])) {
    header('Content-Type: text/plain');
    die('[CIDRAM] ' . $CIDRAM['L10N']->getString('max_login_attempts_exceeded'));
}

/** Brute-force security check (2FA). */
if (($CIDRAM['Failed2FA'] = (int)$CIDRAM['FECacheGet'](
    $CIDRAM['FE']['Cache'], 'Failed2FA' . $_SERVER[$CIDRAM['IPAddr']]
)) && ($CIDRAM['Failed2FA'] >= $CIDRAM['Config']['general']['max_login_attempts'])) {
    header('Content-Type: text/plain');
    die('[CIDRAM] ' . $CIDRAM['L10N']->getString('max_login_attempts_exceeded'));
}

/** Attempt to log in the user. */
if ($CIDRAM['FE']['FormTarget'] === 'login' || $CIDRAM['FE']['CronMode']) {
    if (!empty($_POST['username']) && empty($_POST['password'])) {
        $CIDRAM['FE']['UserState'] = -1;
        $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_login_password_field_empty');
    } elseif (empty($_POST['username']) && !empty($_POST['password'])) {
        $CIDRAM['FE']['UserState'] = -1;
        $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_login_username_field_empty');
    } elseif (!empty($_POST['username']) && !empty($_POST['password'])) {

        $CIDRAM['FE']['UserState'] = -1;
        $CIDRAM['FE']['UserRaw'] = $_POST['username'];
        $CIDRAM['FE']['User'] = base64_encode($CIDRAM['FE']['UserRaw']);
        $CIDRAM['FE']['UserPos'] = strpos($CIDRAM['FE']['UserList'], "\n" . $CIDRAM['FE']['User'] . ',');

        if ($CIDRAM['FE']['UserPos'] !== false) {
            $CIDRAM['FE']['UserOffset'] = $CIDRAM['FE']['UserPos'] + strlen($CIDRAM['FE']['User']) + 2;
            $CIDRAM['FE']['Password'] = substr(
                $CIDRAM['FE']['UserList'],
                $CIDRAM['FE']['UserOffset'],
                strpos($CIDRAM['FE']['UserList'], "\n", $CIDRAM['FE']['UserOffset']) - $CIDRAM['FE']['UserOffset']
            );
            $CIDRAM['FE']['Permissions'] = (int)substr($CIDRAM['FE']['Password'], -1);
            $CIDRAM['FE']['Password'] = substr($CIDRAM['FE']['Password'], 0, -2);
            if (password_verify($_POST['password'], $CIDRAM['FE']['Password'])) {
                $CIDRAM['FECacheRemove'](
                    $CIDRAM['FE']['Cache'], $CIDRAM['FE']['Rebuild'], 'LoginAttempts' . $_SERVER[$CIDRAM['IPAddr']]
                );
                if (($CIDRAM['FE']['Permissions'] === 3 && (
                    !$CIDRAM['FE']['CronMode'] || substr($CIDRAM['FE']['UA'], 0, 10) !== 'Cronable v'
                )) || !($CIDRAM['FE']['Permissions'] > 0 && $CIDRAM['FE']['Permissions'] <= 3)) {
                    $CIDRAM['FE']['Permissions'] = 0;
                    $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_login_wrong_endpoint');
                } else {
                    if (!$CIDRAM['FE']['CronMode']) {
                        $CIDRAM['FE']['SessionKey'] = md5($CIDRAM['GenerateSalt']());
                        $CIDRAM['FE']['Cookie'] = $_POST['username'] . $CIDRAM['FE']['SessionKey'];
                        setcookie('CIDRAM-ADMIN', $CIDRAM['FE']['Cookie'], $CIDRAM['Now'] + 604800, '/', $CIDRAM['HTTP_HOST'], false, true);
                        $CIDRAM['FE']['ThisSession'] = $CIDRAM['FE']['User'] . ',' . password_hash(
                            $CIDRAM['FE']['SessionKey'], $CIDRAM['DefaultAlgo']
                        ) . ',' . ($CIDRAM['Now'] + 604800) . "\n";
                        $CIDRAM['FE']['SessionList'] .= $CIDRAM['FE']['ThisSession'];

                        /** Prepare 2FA email. */
                        if ($CIDRAM['Config']['PHPMailer']['Enable2FA'] && preg_match('~^.+@.+$~', $CIDRAM['FE']['UserRaw'])) {
                            $CIDRAM['2FA-State'] = ['Number' => $CIDRAM['2FA-Number']()];
                            $CIDRAM['2FA-State']['Hash'] = password_hash($CIDRAM['2FA-State']['Number'], $CIDRAM['DefaultAlgo']);
                            $CIDRAM['FECacheAdd'](
                                $CIDRAM['FE']['Cache'],
                                $CIDRAM['FE']['Rebuild'],
                                '2FA-State:' . $CIDRAM['FE']['Cookie'],
                                '0' . $CIDRAM['2FA-State']['Hash'],
                                $CIDRAM['Now'] + 600
                            );
                            $CIDRAM['2FA-State']['Template'] = sprintf(
                                $CIDRAM['L10N']->getString('msg_template_2fa'),
                                $CIDRAM['FE']['UserRaw'],
                                $CIDRAM['2FA-State']['Number']
                            );
                            if (preg_match('~^[^<>]+<[^<>]+>$~', $CIDRAM['FE']['UserRaw'])) {
                                $CIDRAM['2FA-State']['Name'] = trim(preg_replace('~^([^<>]+)<[^<>]+>$~', '\1', $CIDRAM['FE']['UserRaw']));
                                $CIDRAM['2FA-State']['Address'] = trim(preg_replace('~^[^<>]+<([^<>]+)>$~', '\1', $CIDRAM['FE']['UserRaw']));
                            } else {
                                $CIDRAM['2FA-State']['Name'] = trim($CIDRAM['FE']['UserRaw']);
                                $CIDRAM['2FA-State']['Address'] = $CIDRAM['2FA-State']['Name'];
                            }
                            $CIDRAM['SendEmail'](
                                [['Name' => $CIDRAM['2FA-State']['Name'], 'Address' => $CIDRAM['2FA-State']['Address']]],
                                $CIDRAM['L10N']->getString('msg_subject_2fa'),
                                $CIDRAM['2FA-State']['Template'],
                                strip_tags($CIDRAM['2FA-State']['Template'])
                            );
                            $CIDRAM['FE']['UserState'] = 2;
                            unset($CIDRAM['2FA-State']);
                        } else {
                            $CIDRAM['FE']['UserState'] = 1;
                        }

                    } else {
                        $CIDRAM['FE']['UserState'] = 1;
                    }
                    if ($CIDRAM['FE']['UserState'] !== 1) {
                        $CIDRAM['FE']['Permissions'] = 0;
                    }
                    $CIDRAM['FE']['Rebuild'] = true;
                }
            } else {
                $CIDRAM['FE']['Permissions'] = 0;
                $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_login_invalid_password');
            }
        } else {
            $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_login_invalid_username');
        }

    }

    if ($CIDRAM['FE']['state_msg']) {
        $CIDRAM['LoginAttempts']++;
        $CIDRAM['TimeToAdd'] = ($CIDRAM['LoginAttempts'] > 4) ? ($CIDRAM['LoginAttempts'] - 4) * 86400 : 86400;
        $CIDRAM['FECacheAdd'](
            $CIDRAM['FE']['Cache'],
            $CIDRAM['FE']['Rebuild'],
            'LoginAttempts' . $_SERVER[$CIDRAM['IPAddr']],
            $CIDRAM['LoginAttempts'],
            $CIDRAM['Now'] + $CIDRAM['TimeToAdd']
        );
        if ($CIDRAM['Config']['general']['FrontEndLog']) {
            $CIDRAM['LoggerMessage'] = $CIDRAM['FE']['state_msg'];
        }
        if (!$CIDRAM['FE']['CronMode']) {
            $CIDRAM['FE']['state_msg'] = '<div class="txtRd">' . $CIDRAM['FE']['state_msg'] . '<br /><br /></div>';
        }
    } elseif ($CIDRAM['Config']['general']['FrontEndLog']) {
        $CIDRAM['LoggerMessage'] = $CIDRAM['L10N']->getString((
            $CIDRAM['Config']['PHPMailer']['Enable2FA'] &&
            $CIDRAM['FE']['Permissions'] === 0
        ) ? 'state_logged_in_2fa_pending' : 'state_logged_in');
    }

    /** Handle front-end logging. */
    $CIDRAM['FELogger']($_SERVER[$CIDRAM['IPAddr']], (
        empty($_POST['username']) ? '' : $_POST['username']
    ), empty($CIDRAM['LoggerMessage']) ? '' : $CIDRAM['LoggerMessage']);
    unset($CIDRAM['LoggerMessage']);
}

/** Determine whether the user has logged in. */
elseif (!empty($_COOKIE['CIDRAM-ADMIN'])) {

    $CIDRAM['FE']['UserState'] = -1;
    $CIDRAM['FE']['SessionKey'] = substr($_COOKIE['CIDRAM-ADMIN'], -32);
    $CIDRAM['FE']['UserRaw'] = substr($_COOKIE['CIDRAM-ADMIN'], 0, -32);
    $CIDRAM['FE']['User'] = base64_encode($CIDRAM['FE']['UserRaw']);
    $CIDRAM['FE']['SessionOffset'] = 0;

    if (!empty($CIDRAM['FE']['SessionKey']) && !empty($CIDRAM['FE']['User'])) {
        $CIDRAM['FE']['UserLen'] = strlen($CIDRAM['FE']['User']);
        while (($CIDRAM['FE']['SessionPos'] = strpos(
            $CIDRAM['FE']['SessionList'],
            "\n" . $CIDRAM['FE']['User'],
            $CIDRAM['FE']['SessionOffset']
        )) !== false) {
            $CIDRAM['FE']['SessionOffset'] = $CIDRAM['FE']['SessionPos'] + $CIDRAM['FE']['UserLen'] + 2;
            $CIDRAM['FE']['SessionEntry'] = substr(
                $CIDRAM['FE']['SessionList'],
                $CIDRAM['FE']['SessionOffset'],
                $CIDRAM['ZeroMin'](strpos(
                    $CIDRAM['FE']['SessionList'], "\n", $CIDRAM['FE']['SessionOffset']
                ), $CIDRAM['FE']['SessionOffset'] * -1)
            );
            $CIDRAM['FE']['SEDelimiter'] = strrpos($CIDRAM['FE']['SessionEntry'], ',');
            if ($CIDRAM['FE']['SEDelimiter'] !== false) {
                $CIDRAM['FE']['Expiry'] = (int)substr($CIDRAM['FE']['SessionEntry'], $CIDRAM['FE']['SEDelimiter'] + 1);
                $CIDRAM['FE']['UserHash'] = substr($CIDRAM['FE']['SessionEntry'], 0, $CIDRAM['FE']['SEDelimiter']);
            }
            if (
                !empty($CIDRAM['FE']['Expiry']) &&
                !empty($CIDRAM['FE']['UserHash']) &&
                ($CIDRAM['FE']['Expiry'] > $CIDRAM['Now']) &&
                password_verify($CIDRAM['FE']['SessionKey'], $CIDRAM['FE']['UserHash'])
            ) {
                $CIDRAM['FE']['UserPos'] = strpos($CIDRAM['FE']['UserList'], "\n" . $CIDRAM['FE']['User'] . ',');
                if ($CIDRAM['FE']['UserPos'] !== false) {
                    $CIDRAM['FE']['ThisSession'] = $CIDRAM['FE']['User'] . ',' . $CIDRAM['FE']['SessionEntry'] . "\n";
                    $CIDRAM['FE']['UserOffset'] = $CIDRAM['FE']['UserPos'] + $CIDRAM['FE']['UserLen'] + 2;
                    $CIDRAM['FE']['Permissions'] = (int)substr(substr(
                        $CIDRAM['FE']['UserList'],
                        $CIDRAM['FE']['UserOffset'],
                        strpos($CIDRAM['FE']['UserList'], "\n", $CIDRAM['FE']['UserOffset']) - $CIDRAM['FE']['UserOffset']
                    ), -1);

                    /** Handle 2FA stuff here. */
                    if ($CIDRAM['Config']['PHPMailer']['Enable2FA'] && preg_match('~^.+@.+$~', $CIDRAM['FE']['UserRaw'])) {
                        $CIDRAM['2FA-State'] = $CIDRAM['FECacheGet'](
                            $CIDRAM['FE']['Cache'],
                            '2FA-State:' . $_COOKIE['CIDRAM-ADMIN']
                        );
                        $CIDRAM['FE']['UserState'] = ((int)$CIDRAM['2FA-State'] === 1) ? 1 : 2;
                        if ($CIDRAM['FE']['UserState'] === 2 && $CIDRAM['FE']['FormTarget'] === '2fa' && !empty($_POST['2fa'])) {

                            /** User has submitted a 2FA code. Attempt to verify it. */
                            if (password_verify($_POST['2fa'], substr($CIDRAM['2FA-State'], 1))) {
                                $CIDRAM['FECacheAdd'](
                                    $CIDRAM['FE']['Cache'],
                                    $CIDRAM['FE']['Rebuild'],
                                    '2FA-State:' . $_COOKIE['CIDRAM-ADMIN'],
                                    '1',
                                    $CIDRAM['Now'] + 604800
                                );
                                $CIDRAM['FE']['UserState'] = 1;
                            }

                        }
                        unset($CIDRAM['2FA-State']);
                    } else {
                        $CIDRAM['FE']['UserState'] = 1;
                    }

                    /** Revert permissions if not authenticated. */
                    if ($CIDRAM['FE']['UserState'] !== 1) {
                        $CIDRAM['FE']['Permissions'] = 0;
                    }
                }
                break;
            }
        }
    }

    /** In case of 2FA form submission. */
    if ($CIDRAM['FE']['FormTarget'] === '2fa' && !empty($_POST['2fa'])) {
        if ($CIDRAM['FE']['UserState'] === 2) {
            $CIDRAM['Failed2FA']++;
            $CIDRAM['TimeToAdd'] = ($CIDRAM['Failed2FA'] > 4) ? ($CIDRAM['Failed2FA'] - 4) * 86400 : 86400;
            $CIDRAM['FECacheAdd'](
                $CIDRAM['FE']['Cache'],
                $CIDRAM['FE']['Rebuild'],
                'Failed2FA' . $_SERVER[$CIDRAM['IPAddr']],
                $CIDRAM['Failed2FA'],
                $CIDRAM['Now'] + $CIDRAM['TimeToAdd']
            );
            if ($CIDRAM['Config']['general']['FrontEndLog']) {
                $CIDRAM['FELogger']($_SERVER[$CIDRAM['IPAddr']], $CIDRAM['FE']['UserRaw'], $CIDRAM['L10N']->getString('response_2fa_invalid'));
            }
            $CIDRAM['FE']['state_msg'] = '<div class="txtRd">' . $CIDRAM['L10N']->getString('response_2fa_invalid') . '<br /><br /></div>';
        } else {
            $CIDRAM['FECacheRemove'](
                $CIDRAM['FE']['Cache'], $CIDRAM['FE']['Rebuild'], 'Failed2FA' . $_SERVER[$CIDRAM['IPAddr']]
            );
            if ($CIDRAM['Config']['general']['FrontEndLog']) {
                $CIDRAM['FELogger']($_SERVER[$CIDRAM['IPAddr']], $CIDRAM['FE']['UserRaw'], $CIDRAM['L10N']->getString('response_2fa_valid'));
            }
        }
    }

}

/** The user is attempting an asynchronous request without adequate permissions. */
if ($CIDRAM['FE']['UserState'] !== 1 && $CIDRAM['FE']['ASYNC']) {
    header('HTTP/1.0 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    header('Status: 403 Forbidden');
    die($CIDRAM['L10N']->getString('state_async_deny'));
}

/** Only execute this code block for users that are logged in or awaiting two-factor authentication. */
if (($CIDRAM['FE']['UserState'] === 1 || $CIDRAM['FE']['UserState'] === 2) && !$CIDRAM['FE']['CronMode']) {

    if ($CIDRAM['QueryVars']['cidram-page'] === 'logout') {

        /** Log out the user. */
        $CIDRAM['FE']['SessionList'] = str_ireplace($CIDRAM['FE']['ThisSession'], '', $CIDRAM['FE']['SessionList']);
        $CIDRAM['FE']['ThisSession'] = '';
        $CIDRAM['FE']['Rebuild'] = true;
        $CIDRAM['FE']['UserState'] = 0;
        $CIDRAM['FE']['Permissions'] = 0;
        setcookie('CIDRAM-ADMIN', '', -1, '/', $CIDRAM['HTTP_HOST'], false, true);
        $CIDRAM['FECacheRemove']($CIDRAM['FE']['Cache'], $CIDRAM['FE']['Rebuild'], '2FA-State:' . $_COOKIE['CIDRAM-ADMIN']);
        $CIDRAM['FELogger']($_SERVER[$CIDRAM['IPAddr']], $CIDRAM['FE']['UserRaw'], $CIDRAM['L10N']->getString('state_logged_out'));

    }

    /** If the user has complete access. */
    if ($CIDRAM['FE']['Permissions'] === 1) {

        $CIDRAM['FE']['nav'] = $CIDRAM['ParseVars'](
            $CIDRAM['L10N']->Data + $CIDRAM['FE'],
            $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_nav_complete_access.html'))
        );

    /** If the user has logs access only. */
    } elseif ($CIDRAM['FE']['Permissions'] === 2) {

        $CIDRAM['FE']['nav'] = $CIDRAM['ParseVars'](
            $CIDRAM['L10N']->Data + $CIDRAM['FE'],
            $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_nav_logs_access_only.html'))
        );

    }

}

$CIDRAM['FE']['bNavBR'] = ($CIDRAM['FE']['UserState'] === 1) ? '<br /><br />' : '<br />';

/** The user hasn't logged in, or hasn't authenticated yet. */
if ($CIDRAM['FE']['UserState'] !== 1 && !$CIDRAM['FE']['CronMode']) {

    /** Page initial prepwork. */
    $CIDRAM['InitialPrepwork']($CIDRAM['L10N']->getString('title_login'), $CIDRAM['L10N']->getString('tip_login'), false);

    if ($CIDRAM['FE']['UserState'] === 2) {

        /** Provide an option for the user to log out instead, if they'd prefer. */
        $CIDRAM['FE']['bNav'] = $CIDRAM['L10N']->getString('bNav_logout');

        /** Show them the two-factor authentication page. */
        $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
            $CIDRAM['L10N']->Data + $CIDRAM['FE'],
            $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_2fa.html'))
        );

    } else {

        /** Show them the login page. */
        $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
            $CIDRAM['L10N']->Data + $CIDRAM['FE'],
            $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_login.html'))
        );

    }

    /** Send output. */
    echo $CIDRAM['SendOutput']();

}

/**
 * The user has logged in, but hasn't selected anything to view. Show them the
 * front-end home page.
 */
elseif ($CIDRAM['QueryVars']['cidram-page'] === '' && !$CIDRAM['FE']['CronMode']) {

    /** Page initial prepwork. */
    $CIDRAM['InitialPrepwork']($CIDRAM['L10N']->getString('link_home'), $CIDRAM['L10N']->getString('tip_home'), false);

    /** CIDRAM version used. */
    $CIDRAM['FE']['ScriptVersion'] = $CIDRAM['ScriptVersion'];

    /** PHP version used. */
    $CIDRAM['FE']['info_php'] = PHP_VERSION;

    /** SAPI used. */
    $CIDRAM['FE']['info_sapi'] = php_sapi_name();

    /** Operating system used. */
    $CIDRAM['FE']['info_os'] = php_uname();

    $CIDRAM['FE']['bNav'] = $CIDRAM['L10N']->getString('bNav_logout');

    /** Build repository backup locations information. */
    $CIDRAM['FE']['BackupLocations'] = implode(' | ', [
        '<a href="https://bitbucket.org/Maikuolan/cidram">Bitbucket</a>',
        '<a href="https://sourceforge.net/projects/cidram/">SourceForge</a>'
    ]);

    /** Where to find remote version information? */
    $CIDRAM['RemoteVerPath'] = 'https://raw.githubusercontent.com/Maikuolan/Compatibility-Charts/gh-pages/';

    /** Fetch remote CIDRAM version information and cache it if necessary. */
    if (($CIDRAM['Remote-YAML-CIDRAM'] = $CIDRAM['FECacheGet']($CIDRAM['FE']['Cache'], 'cidram-ver.yaml')) === false) {
        $CIDRAM['Remote-YAML-CIDRAM'] = $CIDRAM['Request']($CIDRAM['RemoteVerPath'] . 'cidram-ver.yaml', [], 8);
        $CIDRAM['FECacheAdd']($CIDRAM['FE']['Cache'], $CIDRAM['FE']['Rebuild'], 'cidram-ver.yaml', $CIDRAM['Remote-YAML-CIDRAM'] ?: '-', $CIDRAM['Now'] + 86400);
    }

    /** Process remote CIDRAM version information. */
    if (empty($CIDRAM['Remote-YAML-CIDRAM'])) {

        /** CIDRAM latest stable. */
        $CIDRAM['FE']['info_cidram_stable'] = $CIDRAM['L10N']->getString('response_error');
        /** CIDRAM latest unstable. */
        $CIDRAM['FE']['info_cidram_unstable'] = $CIDRAM['L10N']->getString('response_error');
        /** CIDRAM branch latest stable. */
        $CIDRAM['FE']['info_cidram_branch'] = $CIDRAM['L10N']->getString('response_error');

    } else {

        $CIDRAM['Remote-YAML-CIDRAM-Array'] = (new \Maikuolan\Common\YAML($CIDRAM['Remote-YAML-CIDRAM']))->Data;

        /** CIDRAM latest stable. */
        $CIDRAM['FE']['info_cidram_stable'] = empty($CIDRAM['Remote-YAML-CIDRAM-Array']['Stable']) ?
            $CIDRAM['L10N']->getString('response_error') : $CIDRAM['Remote-YAML-CIDRAM-Array']['Stable'];
        /** CIDRAM latest unstable. */
        $CIDRAM['FE']['info_cidram_unstable'] = empty($CIDRAM['Remote-YAML-CIDRAM-Array']['Unstable']) ?
            $CIDRAM['L10N']->getString('response_error') : $CIDRAM['Remote-YAML-CIDRAM-Array']['Unstable'];
        /** CIDRAM branch latest stable. */
        if ($CIDRAM['ThisBranch'] = substr($CIDRAM['FE']['ScriptVersion'], 0, strpos($CIDRAM['FE']['ScriptVersion'], '.') ?: 0)) {
            $CIDRAM['ThisBranch'] = 'v' . ($CIDRAM['ThisBranch'] ?: 1);
            $CIDRAM['FE']['info_cidram_branch'] = empty($CIDRAM['Remote-YAML-CIDRAM-Array']['Branch'][$CIDRAM['ThisBranch']]['Latest']) ?
                $CIDRAM['L10N']->getString('response_error') : $CIDRAM['Remote-YAML-CIDRAM-Array']['Branch'][$CIDRAM['ThisBranch']]['Latest'];
        } else {
            $CIDRAM['FE']['info_php_branch'] = $CIDRAM['L10N']->getString('response_error');
        }

    }

    /** Cleanup. */
    unset($CIDRAM['Remote-YAML-CIDRAM-Array'], $CIDRAM['Remote-YAML-CIDRAM']);

    /** Fetch remote PHP version information and cache it if necessary. */
    if (($CIDRAM['Remote-YAML-PHP'] = $CIDRAM['FECacheGet']($CIDRAM['FE']['Cache'], 'php-ver.yaml')) === false) {
        $CIDRAM['Remote-YAML-PHP'] = $CIDRAM['Request']($CIDRAM['RemoteVerPath'] . 'php-ver.yaml', [], 8);
        $CIDRAM['FECacheAdd']($CIDRAM['FE']['Cache'], $CIDRAM['FE']['Rebuild'], 'php-ver.yaml', $CIDRAM['Remote-YAML-PHP'] ?: '-', $CIDRAM['Now'] + 86400);
    }

    /** Process remote PHP version information. */
    if (empty($CIDRAM['Remote-YAML-PHP'])) {

        /** PHP latest stable. */
        $CIDRAM['FE']['info_php_stable'] = $CIDRAM['L10N']->getString('response_error');
        /** PHP latest unstable. */
        $CIDRAM['FE']['info_php_unstable'] = $CIDRAM['L10N']->getString('response_error');
        /** PHP branch latest stable. */
        $CIDRAM['FE']['info_php_branch'] = $CIDRAM['L10N']->getString('response_error');

    } else {

        $CIDRAM['Remote-YAML-PHP-Array'] = (new \Maikuolan\Common\YAML($CIDRAM['Remote-YAML-PHP']))->Data;

        /** PHP latest stable. */
        $CIDRAM['FE']['info_php_stable'] = empty($CIDRAM['Remote-YAML-PHP-Array']['Stable']) ?
            $CIDRAM['L10N']->getString('response_error') : $CIDRAM['Remote-YAML-PHP-Array']['Stable'];
        /** PHP latest unstable. */
        $CIDRAM['FE']['info_php_unstable'] = empty($CIDRAM['Remote-YAML-PHP-Array']['Unstable']) ?
            $CIDRAM['L10N']->getString('response_error') : $CIDRAM['Remote-YAML-PHP-Array']['Unstable'];
        /** PHP branch latest stable. */
        if ($CIDRAM['ThisBranch'] = substr(PHP_VERSION, 0, strpos(PHP_VERSION, '.') ?: 0)) {
            $CIDRAM['ThisBranch'] .= substr(PHP_VERSION, strlen($CIDRAM['ThisBranch']) + 1, strpos(PHP_VERSION, '.', strlen($CIDRAM['ThisBranch'])) ?: 0);
            $CIDRAM['ThisBranch'] = 'php' . $CIDRAM['ThisBranch'];
            $CIDRAM['FE']['info_php_branch'] = empty($CIDRAM['Remote-YAML-PHP-Array']['Branch'][$CIDRAM['ThisBranch']]['Latest']) ?
                $CIDRAM['L10N']->getString('response_error') : $CIDRAM['Remote-YAML-PHP-Array']['Branch'][$CIDRAM['ThisBranch']]['Latest'];
        } else {
            $CIDRAM['FE']['info_php_branch'] = $CIDRAM['L10N']->getString('response_error');
        }

    }

    /** Cleanup. */
    unset($CIDRAM['Remote-YAML-PHP-Array'], $CIDRAM['Remote-YAML-PHP'], $CIDRAM['ThisBranch'], $CIDRAM['RemoteVerPath']);

    /** Extension availability. */
    $CIDRAM['FE']['Extensions'] = [];
    foreach ([
        ['Lib' => 'pcre', 'Name' => 'PCRE'],
        ['Lib' => 'curl', 'Name' => 'cURL'],
        ['Lib' => 'apcu', 'Name' => 'APCu'],
        ['Lib' => 'memcached', 'Name' => 'Memcached'],
        ['Lib' => 'redis', 'Name' => 'Redis'],
        ['Lib' => 'pdo', 'Name' => 'PDO', 'Drivers' => (class_exists('\PDO') ? \PDO::getAvailableDrivers() : [])]
    ] as $CIDRAM['ThisExtension']) {
        if (extension_loaded($CIDRAM['ThisExtension']['Lib'])) {
            $CIDRAM['ExtVer'] = (new ReflectionExtension($CIDRAM['ThisExtension']['Lib']))->getVersion();
            $CIDRAM['ThisResponse'] = '<span class="txtGn">' . $CIDRAM['L10N']->getString('response_yes') . ' (' . $CIDRAM['ExtVer'] . ')';
            if (!empty($CIDRAM['ThisExtension']['Drivers'])) {
                $CIDRAM['ThisResponse'] .= ', {' . implode(', ', $CIDRAM['ThisExtension']['Drivers']) . '}';
            }
            $CIDRAM['ThisResponse'] .= '</span>';
        } else {
            $CIDRAM['ThisResponse'] = '<span class="txtRd">' . $CIDRAM['L10N']->getString('response_no') . '</span>';
        }
        $CIDRAM['FE']['Extensions'][] = '    <li><small>' . $CIDRAM['LTRinRTF'](sprintf(
            '%1$s➡%2$s',
            $CIDRAM['ThisExtension']['Name'],
            $CIDRAM['ThisResponse']
        )) . '</small></li>';
    }
    $CIDRAM['FE']['Extensions'] = implode("\n", $CIDRAM['FE']['Extensions']);
    $CIDRAM['FE']['ExtensionIsAvailable'] = $CIDRAM['LTRinRTF']($CIDRAM['L10N']->getString('label_extension') . '➡' . $CIDRAM['L10N']->getString('label_installed_available'));
    unset($CIDRAM['ExtVer'], $CIDRAM['ThisResponse'], $CIDRAM['ThisExtension']);

    /** Process warnings. */
    $CIDRAM['FE']['Warnings'] = '';
    if (empty($CIDRAM['Config']['signatures']['ipv4']) && empty($CIDRAM['Config']['signatures']['ipv6'])) {
        $CIDRAM['FE']['Warnings'] .= '<li>' . $CIDRAM['L10N']->getString('warning_signatures_1') . '</li>';
    }
    if ($CIDRAM['FE']['Warnings']) {
        $CIDRAM['FE']['Warnings'] = '<hr />' . $CIDRAM['L10N']->getString('warning') . '<br /><div class="txtRd"><ul>' . $CIDRAM['FE']['Warnings'] . '</ul></div>';
    }

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['L10N']->Data + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_home.html'))
    ) . $CIDRAM['MenuToggle'];

    /** Send output. */
    echo $CIDRAM['SendOutput']();

}

/** A simple passthru for the file manager icons. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'icon' && $CIDRAM['FE']['Permissions'] === 1) {

    if (
        !empty($CIDRAM['QueryVars']['file']) &&
        $CIDRAM['FileManager-PathSecurityCheck']($CIDRAM['QueryVars']['file']) &&
        file_exists($CIDRAM['Vault'] . $CIDRAM['QueryVars']['file']) &&
        is_readable($CIDRAM['Vault'] . $CIDRAM['QueryVars']['file'])
    ) {
        header('Content-Type: image/x-icon');
        echo $CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['QueryVars']['file']);
    }

    elseif (!empty($CIDRAM['QueryVars']['icon'])) {

        $CIDRAM['Icons_Handler_Path'] = $CIDRAM['GetAssetPath']('icons.php');
        if (is_readable($CIDRAM['Icons_Handler_Path'])) {

            /** Fetch file manager icons data. */
            require $CIDRAM['Icons_Handler_Path'];

            /** Set mime-type. */
            header('Content-Type: image/gif');

            /** Prevents needlessly reloading static assets. */
            if (!empty($CIDRAM['QueryVars']['theme'])) {
                header('Last-Modified: ' . gmdate(DATE_RFC1123, filemtime($CIDRAM['Icons_Handler_Path'])));
            }

            /** Send icon data. */
            if (!empty($CIDRAM['Icons'][$CIDRAM['QueryVars']['icon']])) {
                echo gzinflate(base64_decode($CIDRAM['Icons'][$CIDRAM['QueryVars']['icon']]));
            } elseif (!empty($CIDRAM['Icons']['unknown'])) {
                echo gzinflate(base64_decode($CIDRAM['Icons']['unknown']));
            }

        }

    }

    die;

}

/** A simple passthru for the flags CSS. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'flags' && $CIDRAM['FE']['Permissions'] && file_exists($CIDRAM['Vault'] . 'fe_assets/flags.css')) {
    /** Sets mime-type. */
    header('Content-Type: text/css');
    /** Prevents needlessly reloading static assets. */
    header('Last-Modified: ' . gmdate(DATE_RFC1123, filemtime($CIDRAM['Vault'] . 'fe_assets/flags.css')));
    /** Sends asset data. */
    echo $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/flags.css');
    die;
}

/** Accounts. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'accounts' && $CIDRAM['FE']['Permissions'] === 1) {

    /** $_POST overrides for mobile display. */
    if (!empty($_POST['username']) && !empty($_POST['do_mob']) && (!empty($_POST['password_mob']) || $_POST['do_mob'] == 'delete-account')) {
        $_POST['do'] = $_POST['do_mob'];
    }
    if (empty($_POST['username']) && !empty($_POST['username_mob'])) {
        $_POST['username'] = $_POST['username_mob'];
    }
    if (empty($_POST['permissions']) && !empty($_POST['permissions_mob'])) {
        $_POST['permissions'] = $_POST['permissions_mob'];
    }
    if (empty($_POST['password']) && !empty($_POST['password_mob'])) {
        $_POST['password'] = $_POST['password_mob'];
    }

    /** A form has been submitted. */
    if ($CIDRAM['FE']['FormTarget'] === 'accounts' && !empty($_POST['do'])) {

        /** Create a new account. */
        if ($_POST['do'] === 'create-account' && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['permissions'])) {
            $CIDRAM['FE']['NewUser'] = $_POST['username'];
            $CIDRAM['FE']['NewPass'] = password_hash($_POST['password'], $CIDRAM['DefaultAlgo']);
            $CIDRAM['FE']['NewPerm'] = (int)$_POST['permissions'];
            $CIDRAM['FE']['NewUserB64'] = base64_encode($_POST['username']);
            if (strpos($CIDRAM['FE']['UserList'], "\n" . $CIDRAM['FE']['NewUserB64'] . ',') !== false) {
                $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_accounts_already_exists');
            } else {
                $CIDRAM['AccountsArray'] = [
                    'Iterate' => 0,
                    'Count' => 1,
                    'ByName' => [$CIDRAM['FE']['NewUser'] =>
                        $CIDRAM['FE']['NewUserB64'] . ',' .
                        $CIDRAM['FE']['NewPass'] . ',' .
                        $CIDRAM['FE']['NewPerm'] . "\n"
                    ]
                ];
                $CIDRAM['FE']['NewLineOffset'] = 0;
                while (($CIDRAM['FE']['NewLinePos'] = strpos(
                    $CIDRAM['FE']['UserList'], "\n", $CIDRAM['FE']['NewLineOffset'] + 1
                )) !== false) {
                    $CIDRAM['FE']['NewLine'] = substr(
                        $CIDRAM['FE']['UserList'],
                        $CIDRAM['FE']['NewLineOffset'] + 1,
                        $CIDRAM['FE']['NewLinePos'] - $CIDRAM['FE']['NewLineOffset']
                    );
                    $CIDRAM['RowInfo'] = explode(',', $CIDRAM['FE']['NewLine'], 3);
                    $CIDRAM['RowInfo'] = base64_decode($CIDRAM['RowInfo'][0]);
                    $CIDRAM['AccountsArray']['ByName'][$CIDRAM['RowInfo']] = $CIDRAM['FE']['NewLine'];
                    $CIDRAM['FE']['NewLineOffset'] = $CIDRAM['FE']['NewLinePos'];
                }
                ksort($CIDRAM['AccountsArray']['ByName']);
                $CIDRAM['FE']['UserList'] = "\n" . implode('', $CIDRAM['AccountsArray']['ByName']);
                $CIDRAM['FE']['Rebuild'] = true;
                unset($CIDRAM['AccountsArray']);
                $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_accounts_created');
            }
        }

        /** Delete an account. */
        if ($_POST['do'] === 'delete-account' && !empty($_POST['username'])) {
            $CIDRAM['FE']['User64'] = base64_encode($_POST['username']);
            $CIDRAM['FE']['UserLinePos'] = strpos($CIDRAM['FE']['UserList'], "\n" . $CIDRAM['FE']['User64'] . ',');
            if ($CIDRAM['FE']['UserLinePos'] === false) {
                $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_accounts_doesnt_exist');
            } else {
                $CIDRAM['FE']['UserLineEndPos'] = strpos($CIDRAM['FE']['UserList'], "\n", $CIDRAM['FE']['UserLinePos'] + 1);
                if ($CIDRAM['FE']['UserLineEndPos'] !== false) {
                    $CIDRAM['FE']['UserLine'] = substr(
                        $CIDRAM['FE']['UserList'],
                        $CIDRAM['FE']['UserLinePos'] + 1,
                        $CIDRAM['FE']['UserLineEndPos'] - $CIDRAM['FE']['UserLinePos']
                    );
                    $CIDRAM['FE']['UserList'] = str_replace($CIDRAM['FE']['UserLine'], '', $CIDRAM['FE']['UserList']);
                    $CIDRAM['FE']['Rebuild'] = true;
                    $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_accounts_deleted');
                }
            }
            $CIDRAM['FE']['UserLinePos'] = strpos($CIDRAM['FE']['SessionList'], "\n" . $CIDRAM['FE']['User64'] . ',');
            if ($CIDRAM['FE']['UserLinePos'] !== false) {
                $CIDRAM['FE']['UserLineEndPos'] = strpos($CIDRAM['FE']['SessionList'], "\n", $CIDRAM['FE']['UserLinePos'] + 1);
                if ($CIDRAM['FE']['UserLineEndPos'] !== false) {
                    $CIDRAM['FE']['SessionLine'] = substr(
                        $CIDRAM['FE']['SessionList'],
                        $CIDRAM['FE']['UserLinePos'] + 1,
                        $CIDRAM['FE']['UserLineEndPos'] - $CIDRAM['FE']['UserLinePos']
                    );
                    $CIDRAM['FE']['SessionList'] = str_replace($CIDRAM['FE']['SessionLine'], '', $CIDRAM['FE']['SessionList']);
                    $CIDRAM['FE']['Rebuild'] = true;
                }
            }
        }

        /** Update an account password. */
        if ($_POST['do'] === 'update-password' && !empty($_POST['username']) && !empty($_POST['password'])) {
            $CIDRAM['FE']['User64'] = base64_encode($_POST['username']);
            $CIDRAM['FE']['NewPass'] = password_hash($_POST['password'], $CIDRAM['DefaultAlgo']);
            $CIDRAM['FE']['UserLinePos'] = strpos($CIDRAM['FE']['UserList'], "\n" . $CIDRAM['FE']['User64'] . ',');
            if ($CIDRAM['FE']['UserLinePos'] === false) {
                $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_accounts_doesnt_exist');
            } else {
                $CIDRAM['FE']['UserLineEndPos'] = strpos($CIDRAM['FE']['UserList'], "\n", $CIDRAM['FE']['UserLinePos'] + 1);
                if ($CIDRAM['FE']['UserLineEndPos'] !== false) {
                    $CIDRAM['FE']['UserLine'] = substr(
                        $CIDRAM['FE']['UserList'],
                        $CIDRAM['FE']['UserLinePos'] + 1,
                        $CIDRAM['FE']['UserLineEndPos'] - $CIDRAM['FE']['UserLinePos']
                    );
                    $CIDRAM['FE']['UserPerm'] = substr($CIDRAM['FE']['UserLine'], -2, 1);
                    $CIDRAM['FE']['NewUserLine'] =
                        $CIDRAM['FE']['User64'] . ',' .
                        $CIDRAM['FE']['NewPass'] . ',' .
                        $CIDRAM['FE']['UserPerm'] . "\n";
                    $CIDRAM['FE']['UserList'] = str_replace($CIDRAM['FE']['UserLine'], $CIDRAM['FE']['NewUserLine'], $CIDRAM['FE']['UserList']);
                    $CIDRAM['FE']['Rebuild'] = true;
                    $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_accounts_password_updated');
                }
            }
        }

    }

    if (!$CIDRAM['FE']['ASYNC']) {

        /** Page initial prepwork. */
        $CIDRAM['InitialPrepwork']($CIDRAM['L10N']->getString('link_accounts'), $CIDRAM['L10N']->getString('tip_accounts'));

        /** Append async globals. */
        $CIDRAM['FE']['JS'] .= sprintf(
            'window[%3$s]=\'accounts\';function acc(e,d,i,t){var o=function(e){%4$se)' .
            '},a=function(){%4$s\'%1$s\')};window.username=%2$s(e).value,window.passw' .
            'ord=%2$s(d).value,window.do=%2$s(t).value,\'delete-account\'==window.do&' .
            '&$(\'POST\',\'\',[%3$s,\'username\',\'password\',\'do\'],a,function(e){%' .
            '4$se),hideid(i)},o),\'update-password\'==window.do&&$(\'POST\',\'\',[%3$' .
            's,\'username\',\'password\',\'do\'],a,o,o)}' . "\n",
            $CIDRAM['L10N']->getString('state_loading'),
            'document.getElementById',
            "'cidram-form-target'",
            "w('stateMsg',"
        );

        $CIDRAM['FE']['bNav'] = $CIDRAM['L10N']->getString('bNav_home_logout');

        $CIDRAM['FE']['AccountsRow'] = $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_accounts_row.html'));
        $CIDRAM['FE']['Accounts'] = '';
        $CIDRAM['FE']['NewLineOffset'] = 0;

        while (($CIDRAM['FE']['NewLinePos'] = strpos(
            $CIDRAM['FE']['UserList'], "\n", $CIDRAM['FE']['NewLineOffset'] + 1
        )) !== false) {
            $CIDRAM['FE']['NewLine'] = substr(
                $CIDRAM['FE']['UserList'],
                $CIDRAM['FE']['NewLineOffset'] + 1,
                $CIDRAM['FE']['NewLinePos'] - $CIDRAM['FE']['NewLineOffset'] - 1
            );
            $CIDRAM['RowInfo'] = ['DelPos' => strpos($CIDRAM['FE']['NewLine'], ','), 'AccWarnings' => ''];
            $CIDRAM['RowInfo']['AccUsername'] = substr($CIDRAM['FE']['NewLine'], 0, $CIDRAM['RowInfo']['DelPos']);
            $CIDRAM['RowInfo']['AccPassword'] = substr($CIDRAM['FE']['NewLine'], $CIDRAM['RowInfo']['DelPos'] + 1);
            $CIDRAM['RowInfo']['AccPermissions'] = (int)substr($CIDRAM['RowInfo']['AccPassword'], -1);
            if ($CIDRAM['RowInfo']['AccPermissions'] === 1) {
                $CIDRAM['RowInfo']['AccPermissions'] = $CIDRAM['L10N']->getString('state_complete_access');
            } elseif ($CIDRAM['RowInfo']['AccPermissions'] === 2) {
                $CIDRAM['RowInfo']['AccPermissions'] = $CIDRAM['L10N']->getString('state_logs_access_only');
            } elseif ($CIDRAM['RowInfo']['AccPermissions'] === 3) {
                $CIDRAM['RowInfo']['AccPermissions'] = 'Cronable';
            } else {
                $CIDRAM['RowInfo']['AccPermissions'] = $CIDRAM['L10N']->getString('response_error');
            }
            $CIDRAM['RowInfo']['AccPassword'] = substr($CIDRAM['RowInfo']['AccPassword'], 0, -2);
            if ($CIDRAM['RowInfo']['AccPassword'] === $CIDRAM['FE']['DefaultPassword']) {
                $CIDRAM['RowInfo']['AccWarnings'] .= '<br /><div class="txtRd">' . $CIDRAM['L10N']->getString('state_default_password') . '</div>';
            } elseif ((
                strlen($CIDRAM['RowInfo']['AccPassword']) !== 60 && strlen($CIDRAM['RowInfo']['AccPassword']) !== 96
            ) || (
                strlen($CIDRAM['RowInfo']['AccPassword']) === 60 && !preg_match('/^\$2.\$\d\d\$/', $CIDRAM['RowInfo']['AccPassword'])
            ) || (
                strlen($CIDRAM['RowInfo']['AccPassword']) === 96 && !preg_match('/^\$argon2i\$/', $CIDRAM['RowInfo']['AccPassword'])
            )) {
                $CIDRAM['RowInfo']['AccWarnings'] .= '<br /><div class="txtRd">' . $CIDRAM['L10N']->getString('state_password_not_valid') . '</div>';
            }
            if (strrpos($CIDRAM['FE']['SessionList'], "\n" . $CIDRAM['RowInfo']['AccUsername'] . ',') !== false) {
                $CIDRAM['RowInfo']['AccWarnings'] .= '<br /><div class="txtGn">' . $CIDRAM['L10N']->getString('state_logged_in') . '</div>';
            }
            $CIDRAM['RowInfo']['AccID'] = bin2hex($CIDRAM['RowInfo']['AccUsername']);
            $CIDRAM['RowInfo']['AccUsername'] = htmlentities(base64_decode($CIDRAM['RowInfo']['AccUsername']));
            $CIDRAM['FE']['NewLineOffset'] = $CIDRAM['FE']['NewLinePos'];
            $CIDRAM['FE']['Accounts'] .= $CIDRAM['ParseVars'](
                $CIDRAM['L10N']->Data + $CIDRAM['RowInfo'], $CIDRAM['FE']['AccountsRow']
            );
        }
        unset($CIDRAM['RowInfo']);

    }

    if ($CIDRAM['FE']['ASYNC']) {
        /** Send output (async). */
        echo $CIDRAM['FE']['state_msg'];
    } else {

        /** Parse output. */
        $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
            $CIDRAM['L10N']->Data + $CIDRAM['FE'],
            $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_accounts.html'))
        );

        /** Send output. */
        echo $CIDRAM['SendOutput']();

    }

}

/** Configuration. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'config' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Page initial prepwork. */
    $CIDRAM['InitialPrepwork']($CIDRAM['L10N']->getString('link_config'), $CIDRAM['L10N']->getString('tip_config'));

    /** Append number localisation JS. */
    $CIDRAM['FE']['JS'] .= $CIDRAM['Number_L10N_JS']() . "\n";

    $CIDRAM['FE']['bNav'] = $CIDRAM['L10N']->getString('bNav_home_logout');

    /** Directive template. */
    $CIDRAM['FE']['ConfigRow'] = $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_config_row.html'));

    $CIDRAM['FE']['Indexes'] = '<ul class="pieul">';

    /** Generate entries for display and regenerate configuration if any changes were submitted. */
    $CIDRAM['FE']['ConfigFields'] = sprintf(
        '<style>.showlink::before,.hidelink::before{content:"➖";display:inline-block;margin-%1$s:6px}.hidelink::before{transform:rotate(%2$s)}</style>',
        $CIDRAM['FE']['FE_Align_Reverse'],
        $CIDRAM['FE']['45deg']
    );
    $CIDRAM['RegenerateConfig'] = '';
    $CIDRAM['ConfigModified'] = (!empty($CIDRAM['QueryVars']['updated']) && $CIDRAM['QueryVars']['updated'] === 'true');
    foreach ($CIDRAM['Config']['Config Defaults'] as $CIDRAM['CatKey'] => $CIDRAM['CatValue']) {
        if (!is_array($CIDRAM['CatValue'])) {
            continue;
        }
        $CIDRAM['RegenerateConfig'] .= '[' . $CIDRAM['CatKey'] . ']';
        if ($CIDRAM['CatInfo'] = $CIDRAM['L10N']->getString('config_' . $CIDRAM['CatKey']) ?: (
            isset($CIDRAM['Config']['L10N']['config_' . $CIDRAM['CatKey']]) ? $CIDRAM['Config']['L10N']['config_' . $CIDRAM['CatKey']] : ''
        )) {
            $CIDRAM['CatInfo'] = '<br /><em>' . $CIDRAM['CatInfo'] . '</em>';
            $CIDRAM['RegenerateConfig'] .= "\r\n; " . wordwrap(str_replace(
                ['&amp;', '&lt;', '&gt;'],
                ['&', '<', '>'],
                strip_tags($CIDRAM['CatInfo'])
            ), 77, "\r\n; ");
        }
        $CIDRAM['RegenerateConfig'] .= "\r\n\r\n";
        $CIDRAM['FE']['ConfigFields'] .= sprintf(
                '<table><tr><td class="ng2"><div id="%1$s-container" class="s">' .
                '<a class="showlink" id="%1$s-showlink" href="#%1$s-container" onclick="javascript:showid(\'%1$s-hidelink\');hideid(\'%1$s-showlink\');show(\'%1$s-row\')">%1$s</a>' .
                '<a class="hidelink" id="%1$s-hidelink" %2$s href="#" onclick="javascript:showid(\'%1$s-showlink\');hideid(\'%1$s-hidelink\');hide(\'%1$s-row\')">%1$s</a>' .
                "%3\$s</div></td></tr></table>\n<span class=\"%1\$s-row\" %2\$s><table>\n",
            $CIDRAM['CatKey'],
            'style="display:none"',
            $CIDRAM['CatInfo']
        );
        $CIDRAM['CatData'] = '';
        foreach ($CIDRAM['CatValue'] as $CIDRAM['DirKey'] => $CIDRAM['DirValue']) {
            $CIDRAM['ThisDir'] = ['Preview' => '', 'Trigger' => '', 'FieldOut' => '', 'CatKey' => $CIDRAM['CatKey']];
            if (empty($CIDRAM['DirValue']['type']) || !isset($CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']])) {
                continue;
            }
            $CIDRAM['ThisDir']['DirLangKey'] = 'config_' . $CIDRAM['CatKey'] . '_' . $CIDRAM['DirKey'];
            $CIDRAM['ThisDir']['DirLangKeyOther'] = $CIDRAM['ThisDir']['DirLangKey'] . '_other';
            $CIDRAM['ThisDir']['DirName'] = $CIDRAM['LTRinRTF']($CIDRAM['CatKey'] . '➡' . $CIDRAM['DirKey']);
            $CIDRAM['ThisDir']['Friendly'] = $CIDRAM['L10N']->getString($CIDRAM['ThisDir']['DirLangKey'] . '_label') ?: (
                isset($CIDRAM['Config']['L10N'][$CIDRAM['ThisDir']['DirLangKey'] . '_label']) ? $CIDRAM['Config']['L10N'][$CIDRAM['ThisDir']['DirLangKey'] . '_label'] : ''
            ) ?: $CIDRAM['DirKey'];
            $CIDRAM['CatData'] .= sprintf(
                '<li><a onclick="javascript:showid(\'%1$s-hidelink\');hideid(\'%1$s-showlink\');show(\'%1$s-row\')" href="#%2$s">%3$s</a></li>',
                $CIDRAM['CatKey'],
                $CIDRAM['ThisDir']['DirLangKey'],
                $CIDRAM['ThisDir']['Friendly']
            );
            $CIDRAM['ThisDir']['DirLang'] =
                $CIDRAM['L10N']->getString($CIDRAM['ThisDir']['DirLangKey']) ?:
                $CIDRAM['L10N']->getString('config_' . $CIDRAM['CatKey']) ?:
                (isset($CIDRAM['Config']['L10N'][$CIDRAM['ThisDir']['DirLangKey']]) ? $CIDRAM['Config']['L10N'][$CIDRAM['ThisDir']['DirLangKey']] : '') ?:
                (isset($CIDRAM['Config']['L10N']['config_' . $CIDRAM['CatKey']]) ? $CIDRAM['Config']['L10N']['config_' . $CIDRAM['CatKey']] : '') ?:
                $CIDRAM['L10N']->getString('response_error');
            if (!empty($CIDRAM['DirValue']['experimental'])) {
                $CIDRAM['ThisDir']['DirLang'] = '<code class="exp">' . $CIDRAM['L10N']->getString('config_experimental') . '</code> ' . $CIDRAM['ThisDir']['DirLang'];
            }
            $CIDRAM['ThisDir']['autocomplete'] = empty($CIDRAM['DirValue']['autocomplete']) ? '' : sprintf(
                ' autocomplete="%s"',
                $CIDRAM['DirValue']['autocomplete']
            );
            $CIDRAM['RegenerateConfig'] .= '; ' . wordwrap(str_replace(
                ['&amp;', '&lt;', '&gt;'],
                ['&', '<', '>'],
                strip_tags($CIDRAM['ThisDir']['DirLang'])
            ), 77, "\r\n; ") . "\r\n";
            if (isset($_POST[$CIDRAM['ThisDir']['DirLangKey']])) {
                if (in_array($CIDRAM['DirValue']['type'], ['bool', 'float', 'int', 'kb', 'string', 'timezone', 'email', 'url'], true)) {
                    $CIDRAM['AutoType']($_POST[$CIDRAM['ThisDir']['DirLangKey']], $CIDRAM['DirValue']['type']);
                }
                if (!preg_match('/[^\x20-\xff"\']/', $_POST[$CIDRAM['ThisDir']['DirLangKey']]) && (
                    !isset($CIDRAM['DirValue']['choices']) ||
                    isset($CIDRAM['DirValue']['choices'][$_POST[$CIDRAM['ThisDir']['DirLangKey']]])
                )) {
                    $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] = $_POST[$CIDRAM['ThisDir']['DirLangKey']];
                    $CIDRAM['ConfigModified'] = true;
                } elseif (
                    !empty($CIDRAM['DirValue']['allow_other']) &&
                    $_POST[$CIDRAM['ThisDir']['DirLangKey']] === 'Other' &&
                    isset($_POST[$CIDRAM['ThisDir']['DirLangKeyOther']]) &&
                    !preg_match('/[^\x20-\xff"\']/', $_POST[$CIDRAM['ThisDir']['DirLangKeyOther']])
                ) {
                    $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] = $_POST[$CIDRAM['ThisDir']['DirLangKeyOther']];
                    $CIDRAM['ConfigModified'] = true;
                }
            } elseif (
                empty($CIDRAM['QueryVars']['updated']) &&
                $CIDRAM['ConfigModified'] &&
                $CIDRAM['DirValue']['type'] === 'checkbox' &&
                isset($CIDRAM['DirValue']['choices']) &&
                is_array($CIDRAM['DirValue']['choices'])
            ) {
                $CIDRAM['DirValue']['Posts'] = [];
                foreach ($CIDRAM['DirValue']['choices'] as $CIDRAM['DirValue']['ThisChoiceKey'] => $CIDRAM['DirValue']['ThisChoice']) {
                    if (!empty($_POST[$CIDRAM['ThisDir']['DirLangKey'] . '_' . $CIDRAM['DirValue']['ThisChoiceKey']])) {
                        $CIDRAM['DirValue']['Posts'][] = $CIDRAM['DirValue']['ThisChoiceKey'];
                    }
                }
                $CIDRAM['DirValue']['Posts'] = implode(',', $CIDRAM['DirValue']['Posts']) ?: '';
                $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] = $CIDRAM['DirValue']['Posts'];
            }
            if ($CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] === true) {
                $CIDRAM['RegenerateConfig'] .= $CIDRAM['DirKey'] . "=true\r\n\r\n";
            } elseif ($CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] === false) {
                $CIDRAM['RegenerateConfig'] .= $CIDRAM['DirKey'] . "=false\r\n\r\n";
            } elseif (in_array($CIDRAM['DirValue']['type'], ['float', 'int'], true)) {
                $CIDRAM['RegenerateConfig'] .= $CIDRAM['DirKey'] . '=' . $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] . "\r\n\r\n";
            } else {
                $CIDRAM['RegenerateConfig'] .= $CIDRAM['DirKey'] . '=\'' . $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] . "'\r\n\r\n";
            }
            if (isset($CIDRAM['DirValue']['preview'])) {
                $CIDRAM['ThisDir']['Preview'] = ($CIDRAM['DirValue']['preview'] === 'allow_other') ? '' : ' = <span id="' . $CIDRAM['ThisDir']['DirLangKey'] . '_preview"></span>';
                $CIDRAM['ThisDir']['Trigger'] = ' onchange="javascript:' . $CIDRAM['ThisDir']['DirLangKey'] . '_function();" onkeyup="javascript:' . $CIDRAM['ThisDir']['DirLangKey'] . '_function();"';
                if ($CIDRAM['DirValue']['preview'] === 'kb') {
                    $CIDRAM['ThisDir']['Preview'] .= sprintf(
                            '<script type="text/javascript">function %1$s_function(){var e=%7$s?%7$s(' .
                            '\'%1$s_field\').value:%8$s&&!%7$s?%8$s.%1$s_field.value:\'\',z=e.replace' .
                            '(/o$/i,\'b\').substr(-2).toLowerCase(),y=\'kb\'==z?1:\'mb\'==z?1024:\'gb' .
                            '\'==z?1048576:\'tb\'==z?1073741824:\'b\'==e.substr(-1)?.0009765625:1,e=e' .
                            '.replace(/[^0-9]*$/i,\'\'),e=isNaN(e)?0:e*y,t=0>e?\'0 %2$s\':1>e?nft((10' .
                            '24*e).toFixed(0))+\' %2$s\':1024>e?nft((1*e).toFixed(2))+\' %3$s\':10485' .
                            '76>e?nft((e/1024).toFixed(2))+\' %4$s\':1073741824>e?nft((e/1048576).toF' .
                            'ixed(2))+\' %5$s\':nft((e/1073741824).toFixed(2))+\' %6$s\';%7$s?%7$s(\'' .
                            '%1$s_preview\').innerHTML=t:%8$s&&!%7$s?%8$s.%1$s_preview.innerHTML=t:\'' .
                            '\'};%1$s_function();</script>',
                        $CIDRAM['ThisDir']['DirLangKey'],
                        $CIDRAM['L10N']->getPlural(0, 'field_size_bytes'),
                        $CIDRAM['L10N']->getString('field_size_KB'),
                        $CIDRAM['L10N']->getString('field_size_MB'),
                        $CIDRAM['L10N']->getString('field_size_GB'),
                        $CIDRAM['L10N']->getString('field_size_TB'),
                        'document.getElementById',
                        'document.all'
                    );
                } elseif ($CIDRAM['DirValue']['preview'] === 'seconds') {
                    $CIDRAM['ThisDir']['Preview'] .= sprintf(
                            '<script type="text/javascript">function %1$s_function(){var t=%9$s?%9$s(' .
                            '\'%1$s_field\').value:%10$s&&!%9$s?%10$s.%1$s_field.value:\'\',e=isNaN(t' .
                            ')?0:0>t?t*-1:t,n=e?Math.floor(e/31536e3):0,e=e?e-31536e3*n:0,o=e?Math.fl' .
                            'oor(e/2592e3):0,e=e-2592e3*o,l=e?Math.floor(e/604800):0,e=e-604800*l,r=e' .
                            '?Math.floor(e/86400):0,e=e-86400*r,d=e?Math.floor(e/3600):0,e=e-3600*d,i' .
                            '=e?Math.floor(e/60):0,e=e-60*i,f=e?Math.floor(1*e):0,a=nft(n.toString())' .
                            '+\' %2$s – \'+nft(o.toString())+\' %3$s – \'+nft(l.toString())+\' %4$s –' .
                            ' \'+nft(r.toString())+\' %5$s – \'+nft(d.toString())+\' %6$s – \'+nft(i.' .
                            'toString())+\' %7$s – \'+nft(f.toString())+\' %8$s\';%9$s?%9$s(\'%1$s_pr' .
                            'eview\').innerHTML=a:%10$s&&!%9$s?%10$s.%1$s_preview.innerHTML=a:\'\'}' .
                            '%1$s_function();</script>',
                        $CIDRAM['ThisDir']['DirLangKey'],
                        $CIDRAM['L10N']->getString('previewer_years'),
                        $CIDRAM['L10N']->getString('previewer_months'),
                        $CIDRAM['L10N']->getString('previewer_weeks'),
                        $CIDRAM['L10N']->getString('previewer_days'),
                        $CIDRAM['L10N']->getString('previewer_hours'),
                        $CIDRAM['L10N']->getString('previewer_minutes'),
                        $CIDRAM['L10N']->getString('previewer_seconds'),
                        'document.getElementById',
                        'document.all'
                    );
                } elseif ($CIDRAM['DirValue']['preview'] === 'minutes') {
                    $CIDRAM['ThisDir']['Preview'] .= sprintf(
                            '<script type="text/javascript">function %1$s_function(){var t=%9$s?%9$s(' .
                            '\'%1$s_field\').value:%10$s&&!%9$s?%10$s.%1$s_field.value:\'\',e=isNaN(t' .
                            ')?0:0>t?t*-1:t,n=e?Math.floor(e/525600):0,e=e?e-525600*n:0,o=e?Math.floo' .
                            'r(e/43200):0,e=e-43200*o,l=e?Math.floor(e/10080):0,e=e-10080*l,r=e?Math.' .
                            'floor(e/1440):0,e=e-1440*r,d=e?Math.floor(e/60):0,e=e-60*d,i=e?Math.floo' .
                            'r(e*1):0,e=e-i,f=e?Math.floor(60*e):0,a=nft(n.toString())+\' %2$s – \'+n' .
                            'ft(o.toString())+\' %3$s – \'+nft(l.toString())+\' %4$s – \'+nft(r.toStr' .
                            'ing())+\' %5$s – \'+nft(d.toString())+\' %6$s – \'+nft(i.toString())+\' ' .
                            '%7$s – \'+nft(f.toString())+\' %8$s\';%9$s?%9$s(\'%1$s_preview\').innerH' .
                            'TML=a:%10$s&&!%9$s?%10$s.%1$s_preview.innerHTML=a:\'\'}%1$s_function();<' .
                            '/script>',
                        $CIDRAM['ThisDir']['DirLangKey'],
                        $CIDRAM['L10N']->getString('previewer_years'),
                        $CIDRAM['L10N']->getString('previewer_months'),
                        $CIDRAM['L10N']->getString('previewer_weeks'),
                        $CIDRAM['L10N']->getString('previewer_days'),
                        $CIDRAM['L10N']->getString('previewer_hours'),
                        $CIDRAM['L10N']->getString('previewer_minutes'),
                        $CIDRAM['L10N']->getString('previewer_seconds'),
                        'document.getElementById',
                        'document.all'
                    );
                } elseif ($CIDRAM['DirValue']['preview'] === 'hours') {
                    $CIDRAM['ThisDir']['Preview'] .= sprintf(
                            '<script type="text/javascript">function %1$s_function(){var t=%9$s?%9$s(' .
                            '\'%1$s_field\').value:%10$s&&!%9$s?%10$s.%1$s_field.value:\'\',e=isNaN(t' .
                            ')?0:0>t?t*-1:t,n=e?Math.floor(e/8760):0,e=e?e-8760*n:0,o=e?Math.floor(e/' .
                            '720):0,e=e-720*o,l=e?Math.floor(e/168):0,e=e-168*l,r=e?Math.floor(e/24):' .
                            '0,e=e-24*r,d=e?Math.floor(e*1):0,e=e-d,i=e?Math.floor(60*e):0,e=e-(i/60)' .
                            ',f=e?Math.floor(3600*e):0,a=nft(n.toString())+\' %2$s – \'+nft(o.toStrin' .
                            'g())+\' %3$s – \'+nft(l.toString())+\' %4$s – \'+nft(r.toString())+\' ' .
                            '%5$s – \'+nft(d.toString())+\' %6$s – \'+nft(i.toString())+\' %7$s – \'+' .
                            'nft(f.toString())+\' %8$s\';%9$s?%9$s(\'%1$s_preview\').innerHTML=a:' .
                            '%10$s&&!%9$s?%10$s.%1$s_preview.innerHTML=a:\'\'}%1$s_function();</script>',
                        $CIDRAM['ThisDir']['DirLangKey'],
                        $CIDRAM['L10N']->getString('previewer_years'),
                        $CIDRAM['L10N']->getString('previewer_months'),
                        $CIDRAM['L10N']->getString('previewer_weeks'),
                        $CIDRAM['L10N']->getString('previewer_days'),
                        $CIDRAM['L10N']->getString('previewer_hours'),
                        $CIDRAM['L10N']->getString('previewer_minutes'),
                        $CIDRAM['L10N']->getString('previewer_seconds'),
                        'document.getElementById',
                        'document.all'
                    );
                } elseif ($CIDRAM['DirValue']['preview'] === 'allow_other') {
                    $CIDRAM['ThisDir']['Preview'] .= sprintf(
                            '<script type="text/javascript">function %1$s_function(){var e=%2$s?%2$s(' .
                            '\'%1$s_field\').value:%3$s&&!%2$s?%3$s.%1$s_field.value:\'\';e==\'Other\'' .
                            '?showid(\'%4$s_field\'):hideid(\'%4$s_field\')};%1$s_function();</script>',
                        $CIDRAM['ThisDir']['DirLangKey'],
                        'document.getElementById',
                        'document.all',
                        $CIDRAM['ThisDir']['DirLangKeyOther']
                    );
                }
            }
            if ($CIDRAM['DirValue']['type'] === 'timezone') {
                $CIDRAM['DirValue']['choices'] = ['SYSTEM' => $CIDRAM['L10N']->getString('field_system_timezone')];
                foreach (array_unique(DateTimeZone::listIdentifiers()) as $CIDRAM['DirValue']['ChoiceValue']) {
                    $CIDRAM['DirValue']['choices'][$CIDRAM['DirValue']['ChoiceValue']] = $CIDRAM['DirValue']['ChoiceValue'];
                }
            }
            if (isset($CIDRAM['DirValue']['choices'])) {
                if ($CIDRAM['DirValue']['type'] !== 'checkbox') {
                    $CIDRAM['ThisDir']['FieldOut'] = sprintf(
                        '<select class="auto" name="%1$s" id="%1$s_field"%2$s>',
                        $CIDRAM['ThisDir']['DirLangKey'],
                        $CIDRAM['ThisDir']['Trigger']
                    );
                }
                foreach ($CIDRAM['DirValue']['choices'] as $CIDRAM['ChoiceKey'] => $CIDRAM['ChoiceValue']) {
                    if (isset($CIDRAM['DirValue']['choice_filter'])) {
                        if (!$CIDRAM[$CIDRAM['DirValue']['choice_filter']]($CIDRAM['ChoiceKey'], $CIDRAM['ChoiceValue'])) {
                            continue;
                        }
                    }
                    $CIDRAM['ChoiceValue'] = $CIDRAM['TimeFormat']($CIDRAM['Now'], $CIDRAM['ChoiceValue']);
                    if (strpos($CIDRAM['ChoiceValue'], '{') !== false) {
                        $CIDRAM['ChoiceValue'] = $CIDRAM['ParseVars']($CIDRAM['L10N']->Data, $CIDRAM['ChoiceValue']);
                    }
                    if ($CIDRAM['DirValue']['type'] === 'checkbox') {
                        $CIDRAM['ThisDir']['FieldOut'] .= sprintf(
                            '<input type="checkbox" class="auto" name="%1$s" id="%1$s"%2$s /><label for="%1$s" class="s">%3$s</label><br />',
                            $CIDRAM['ThisDir']['DirLangKey'] . '_' . $CIDRAM['ChoiceKey'],
                            $CIDRAM['in_csv']($CIDRAM['ChoiceKey'], $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']]) ? ' checked' : '',
                            $CIDRAM['ChoiceValue']
                        );
                    } else {
                        $CIDRAM['ThisDir']['FieldOut'] .= sprintf(
                            '<option value="%1$s"%2$s>%3$s</option>',
                            $CIDRAM['ChoiceKey'],
                            $CIDRAM['ChoiceKey'] === $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] ? ' selected' : '',
                            $CIDRAM['ChoiceValue']
                        );
                    }
                }
                if ($CIDRAM['DirValue']['type'] !== 'checkbox') {
                    $CIDRAM['ThisDir']['SelectOther'] = !isset($CIDRAM['DirValue']['choices'][$CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']]]);
                    $CIDRAM['ThisDir']['FieldOut'] .= empty($CIDRAM['DirValue']['allow_other']) ? '</select>' : sprintf(
                        '<option value="Other"%1$s>%2$s</option></select> <input type="text"%3$s class="auto" name="%4$s" id="%4$s_field" value="%5$s" />',
                        $CIDRAM['ThisDir']['SelectOther'] ? ' selected' : '',
                        $CIDRAM['L10N']->getString('label_other'),
                        $CIDRAM['ThisDir']['SelectOther'] ? '' : ' style="display:none"',
                        $CIDRAM['ThisDir']['DirLangKeyOther'],
                        $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']]
                    );
                }
            } elseif ($CIDRAM['DirValue']['type'] === 'bool') {
                $CIDRAM['ThisDir']['FieldOut'] = sprintf(
                        '<select class="auto" name="%1$s" id="%1$s_field"%2$s>' .
                        '<option value="true"%5$s>%3$s</option><option value="false"%6$s>%4$s</option>' .
                        '</select>',
                    $CIDRAM['ThisDir']['DirLangKey'],
                    $CIDRAM['ThisDir']['Trigger'],
                    $CIDRAM['L10N']->getString('field_true'),
                    $CIDRAM['L10N']->getString('field_false'),
                    ($CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] ? ' selected' : ''),
                    ($CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] ? '' : ' selected')
                );
            } elseif (in_array($CIDRAM['DirValue']['type'], ['float', 'int'], true)) {
                $CIDRAM['ThisDir']['FieldOut'] = sprintf(
                    '<input type="number" name="%1$s" id="%1$s_field" value="%2$s"%3$s%4$s%5$s />',
                    $CIDRAM['ThisDir']['DirLangKey'],
                    $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']],
                    (isset($CIDRAM['DirValue']['step']) ? ' step="' . $CIDRAM['DirValue']['step'] . '"' : ''),
                    $CIDRAM['ThisDir']['Trigger'],
                    ($CIDRAM['DirValue']['type'] === 'int' ? ' inputmode="numeric"' : '')
                );
            } elseif ($CIDRAM['DirValue']['type'] === 'url' || (
                empty($CIDRAM['DirValue']['autocomplete']) && $CIDRAM['DirValue']['type'] === 'string'
            )) {
                $CIDRAM['ThisDir']['FieldOut'] = sprintf(
                    '<textarea name="%1$s" id="%1$s_field" class="half"%2$s%3$s>%4$s</textarea>',
                    $CIDRAM['ThisDir']['DirLangKey'],
                    $CIDRAM['ThisDir']['autocomplete'],
                    $CIDRAM['ThisDir']['Trigger'],
                    $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']]
                );
            } else {
                $CIDRAM['ThisDir']['FieldOut'] = sprintf(
                    '<input type="text" name="%1$s" id="%1$s_field" value="%2$s"%3$s%4$s />',
                    $CIDRAM['ThisDir']['DirLangKey'],
                    $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']],
                    $CIDRAM['ThisDir']['autocomplete'],
                    $CIDRAM['ThisDir']['Trigger']
                );
            }
            $CIDRAM['ThisDir']['FieldOut'] .= $CIDRAM['ThisDir']['Preview'];
            $CIDRAM['FE']['ConfigFields'] .= $CIDRAM['ParseVars'](
                $CIDRAM['L10N']->Data + $CIDRAM['ThisDir'], $CIDRAM['FE']['ConfigRow']
            );
        }
        $CIDRAM['CatKeyFriendly'] = $CIDRAM['L10N']->getString('config_' . $CIDRAM['CatKey'] . '_label') ?: (
            isset($CIDRAM['Config']['L10N']['config_' . $CIDRAM['CatKey'] . '_label']) ? $CIDRAM['Config']['L10N']['config_' . $CIDRAM['CatKey'] . '_label'] : ''
        ) ?: $CIDRAM['CatKey'];
        $CIDRAM['FE']['Indexes'] .= sprintf(
            '<li><span class="comCat" style="cursor:pointer">%1$s</span><ul class="comSub">%2$s</ul></li>',
            $CIDRAM['CatKeyFriendly'],
            $CIDRAM['CatData']
        );
        $CIDRAM['FE']['ConfigFields'] .= "</table></span>\n";
        $CIDRAM['RegenerateConfig'] .= "\r\n";
    }

    /** Update the currently active configuration file if any changes were made. */
    if ($CIDRAM['ConfigModified']) {
        $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_configuration_updated');
        $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile'], 'w');
        fwrite($CIDRAM['Handle'], $CIDRAM['RegenerateConfig']);
        fclose($CIDRAM['Handle']);
        if (empty($CIDRAM['QueryVars']['updated'])) {
            header('Location: ?cidram-page=config&updated=true');
            die;
        }
    }

    $CIDRAM['FE']['Indexes'] .= '</ul>';

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['L10N']->Data + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_config.html'))
    ) . $CIDRAM['MenuToggle'];

    /** Send output. */
    echo $CIDRAM['SendOutput']();

}

/** Cache data. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'cache-data' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Page initial prepwork. */
    $CIDRAM['InitialPrepwork']($CIDRAM['L10N']->getString('link_cache_data'), $CIDRAM['L10N']->getString('tip_cache_data'));

    $CIDRAM['FE']['bNav'] = $CIDRAM['L10N']->getString('bNav_home_logout');

    if ($CIDRAM['FE']['ASYNC']) {

        /** Delete a cache entry. */
        if (!empty($_POST['do']) && $_POST['do'] === 'delete') {
            if (!empty($_POST['cdi'])) {
                $CIDRAM['Cache']->deleteEntry($_POST['cdi']);
            } elseif (!empty($_POST['fecdi'])) {
                $CIDRAM['FECacheRemove']($CIDRAM['FE']['Cache'], $CIDRAM['FE']['Rebuild'], $_POST['fecdi']);
            }
        }

    } else {

        /** Append async globals. */
        $CIDRAM['FE']['JS'] .=
            "function cdd(d,n){window.cdi=d,window.do='delete',$('POST','',['cidram-f" .
            "orm-target','cdi','do'],null,function(o){hideid(d+'Container')})}window[" .
            "'cidram-form-target']='cache-data';function fecdd(d,n){window.fecdi=d,wi" .
            "ndow.do='delete',$('POST','',['cidram-form-target','fecdi','do'],null,fu" .
            "nction(o){hideid(d+'FEContainer')})}window['cidram-form-target']='cache-" .
            "data';";

        /** To be populated by the cache data. */
        $CIDRAM['FE']['CacheData'] = '';

        /** To be populated by the cache data. */
        $CIDRAM['PreferredSource'] = ($CIDRAM['Cache']->Using && $CIDRAM['Cache']->Using !== 'FF') ? $CIDRAM['Cache']->Using : 'cache.dat';

        /** Array of all cache items from all sources. */
        $CIDRAM['CacheArray'] = [
            'fe_assets/frontend.dat' => [],
            $CIDRAM['PreferredSource'] => []
        ];

        /** Get cache index data. */
        foreach ($CIDRAM['Cache']->getAllEntries() as $CIDRAM['ThisCacheName'] => $CIDRAM['ThisCacheItem']) {
            if (isset($CIDRAM['ThisCacheItem']['Time']) && $CIDRAM['ThisCacheItem']['Time'] > 0 && $CIDRAM['ThisCacheItem']['Time'] < $CIDRAM['Now']) {
                continue;
            }
            $CIDRAM['Arrayify']($CIDRAM['ThisCacheItem']);
            $CIDRAM['CacheArray'][$CIDRAM['PreferredSource']][$CIDRAM['ThisCacheName']] = $CIDRAM['ThisCacheItem'];
        }
        unset($CIDRAM['ThisCacheName'], $CIDRAM['ThisCacheItem'], $CIDRAM['PreferredSource']);

        /** Get front-end cache data. */
        if ($CIDRAM['CacheIndexData'] = $CIDRAM['FE']['Cache']) {
            foreach (explode("\n", $CIDRAM['CacheIndexData']) as $CIDRAM['CacheIndexData']) {
                if (!$CIDRAM['CacheIndexData']) {
                    continue;
                }
                $CIDRAM['CacheIndexData'] = explode(',', $CIDRAM['CacheIndexData']);
                $CIDRAM['ThisCacheEntryName'] = base64_decode($CIDRAM['CacheIndexData'][0]);
                if (isset($CIDRAM['CacheIndexData'][1])) {
                    $CIDRAM['CacheIndexData'][1] = base64_decode($CIDRAM['CacheIndexData'][1]);
                }
                $CIDRAM['CacheIndexData'][2] = ($CIDRAM['CacheIndexData'][2] >= 0 ? $CIDRAM['TimeFormat'](
                    $CIDRAM['CacheIndexData'][2],
                    $CIDRAM['Config']['general']['timeFormat']
                ) : $CIDRAM['L10N']->getString('label_never'));
                $CIDRAM['Arrayify']($CIDRAM['CacheIndexData'][1]);
                $CIDRAM['CacheArray']['fe_assets/frontend.dat'][$CIDRAM['ThisCacheEntryName']] = $CIDRAM['CacheIndexData'][1];
                $CIDRAM['CacheArray']['fe_assets/frontend.dat'][$CIDRAM['ThisCacheEntryName']][
                    $CIDRAM['L10N']->getString('label_expires') ?: 'Expires'
                ] = $CIDRAM['CacheIndexData'][2];
            }
        }
        unset($CIDRAM['ThisCacheEntryName'], $CIDRAM['CacheIndexData']);

        /** Begin processing all cache items from all sources. */
        foreach ($CIDRAM['CacheArray'] as $CIDRAM['CacheSourceName'] => $CIDRAM['CacheSourceData']) {
            if (empty($CIDRAM['CacheSourceData'])) {
                continue;
            }
            $CIDRAM['FE']['CacheData'] .= '<div class="ng1"><span class="s">' . $CIDRAM['CacheSourceName'] . '</span><br /><br /><ul class="pieul">' . $CIDRAM['ArrayToClickableList'](
                $CIDRAM['CacheSourceData'], ($CIDRAM['CacheSourceName'] === 'fe_assets/frontend.dat' ? 'fecdd' : 'cdd'), 0, $CIDRAM['CacheSourceName']
            ) . '</ul></div>';
        }
        unset($CIDRAM['CacheSourceData'], $CIDRAM['CacheSourceName'], $CIDRAM['CacheArray']);

        /** Cache is empty. */
        if (!$CIDRAM['FE']['CacheData']) {
            $CIDRAM['FE']['CacheData'] = '<div class="ng1"><span class="s">' . $CIDRAM['L10N']->getString('state_cache_is_empty') . '</span></div>';
        }

        /** Parse output. */
        $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
            $CIDRAM['L10N']->Data + $CIDRAM['FE'],
            $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_cache.html'))
        ) . $CIDRAM['MenuToggle'];

        /** Send output. */
        echo $CIDRAM['SendOutput']();

    }

}

/** Updates. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'updates' && ($CIDRAM['FE']['Permissions'] === 1 || ($CIDRAM['FE']['Permissions'] === 3 && $CIDRAM['FE']['CronMode']))) {

    $CIDRAM['FE']['UpdatesFormTarget'] = 'cidram-page=updates';
    $CIDRAM['FE']['UpdatesFormTargetControls'] = '';
    $CIDRAM['StateModified'] = false;
    $CIDRAM['FilterSwitch'](
        ['hide-non-outdated', 'hide-unused', 'sort-by-name', 'descending-order'],
        isset($_POST['FilterSelector']) ? $_POST['FilterSelector'] : '',
        $CIDRAM['StateModified'],
        $CIDRAM['FE']['UpdatesFormTarget'],
        $CIDRAM['FE']['UpdatesFormTargetControls']
    );
    if ($CIDRAM['StateModified']) {
        header('Location: ?' . $CIDRAM['FE']['UpdatesFormTarget']);
        die;
    }
    unset($CIDRAM['StateModified']);

    /** Useful for avoiding excessive IO operations when dealing with components. */
    $CIDRAM['Updater-IO'] = new \Maikuolan\Common\DelayedIO();

    /** Updates page form boilerplate. */
    $CIDRAM['CFBoilerplate'] =
        '<form action="?%s" method="POST" style="display:inline">' .
        '<input name="cidram-form-target" type="hidden" value="updates" />' .
        '<input name="do" type="hidden" value="%s" />';

    /** Prepare components metadata working array. */
    $CIDRAM['Components'] = ['Meta' => [], 'RemoteMeta' => []];

    /** Fetch components lists. */
    $CIDRAM['FetchComponentsLists']($CIDRAM['Vault'], $CIDRAM['Components']['Meta']);

    /** Cleanup. */
    unset($CIDRAM['Components']['Files']);

    $CIDRAM['FE']['Indexes'] = [];

    /** A form has been submitted. */
    if (empty($CIDRAM['Alternate']) && $CIDRAM['FE']['FormTarget'] === 'updates' && !empty($_POST['do']) && !empty($_POST['ID'])) {

        /** Trigger updates handler. */
        $CIDRAM['UpdatesHandler']($_POST['do'], $_POST['ID']);

    }

    /** Page initial prepwork. */
    $CIDRAM['InitialPrepwork']($CIDRAM['L10N']->getString('link_updates'), $CIDRAM['L10N']->getString('tip_updates'));

    $CIDRAM['FE']['bNav'] = $CIDRAM['L10N']->getString('bNav_home_logout');

    $CIDRAM['FE']['UpdatesRow'] = $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_updates_row.html'));

    $CIDRAM['Components'] = [
        'Meta' => $CIDRAM['Components']['Meta'],
        'RemoteMeta' => $CIDRAM['Components']['RemoteMeta'],
        'Remotes' => [],
        'Interdependent' => [],
        'Outdated' => [],
        'OutdatedSignatureFiles' => [],
        'Verify' => [],
        'Repairable' => [],
        'Out' => []
    ];

    /** Prepare installed component metadata and options for display. */
    foreach ($CIDRAM['Components']['Meta'] as $CIDRAM['Components']['Key'] => &$CIDRAM['Components']['ThisComponent']) {

        /** Skip if component is malformed. */
        if (empty($CIDRAM['Components']['ThisComponent']['Name']) && !$CIDRAM['L10N']->getString('Name ' . $CIDRAM['Components']['Key'])) {
            $CIDRAM['Components']['ThisComponent'] = '';
            continue;
        }

        /** Execute any necessary preload instructions. */
        if (!empty($CIDRAM['Components']['ThisComponent']['When Checking'])) {
            $CIDRAM['FE_Executor']($CIDRAM['Components']['ThisComponent']['When Checking']);
        }

        $CIDRAM['PrepareName']($CIDRAM['Components']['ThisComponent'], $CIDRAM['Components']['Key']);
        $CIDRAM['PrepareExtendedDescription']($CIDRAM['Components']['ThisComponent'], $CIDRAM['Components']['Key']);
        $CIDRAM['Components']['ThisComponent']['ID'] = $CIDRAM['Components']['Key'];
        $CIDRAM['Components']['ThisComponent']['Options'] = '';
        $CIDRAM['Components']['ThisComponent']['StatusOptions'] = '';
        $CIDRAM['Components']['ThisComponent']['StatClass'] = '';
        if (empty($CIDRAM['Components']['ThisComponent']['Version'])) {
            if (empty($CIDRAM['Components']['ThisComponent']['Files']['To'])) {
                $CIDRAM['Components']['ThisComponent']['RowClass'] = 'h2';
                $CIDRAM['Components']['ThisComponent']['Version'] = $CIDRAM['L10N']->getString('response_updates_not_installed');
                $CIDRAM['Components']['ThisComponent']['StatClass'] = 'txtRd';
                $CIDRAM['Components']['ThisComponent']['StatusOptions'] = $CIDRAM['L10N']->getString('response_updates_not_installed');
            } else {
                $CIDRAM['Components']['ThisComponent']['Version'] = $CIDRAM['L10N']->getString('response_updates_unable_to_determine');
                $CIDRAM['Components']['ThisComponent']['StatClass'] = 's';
            }
        }
        if (!empty($CIDRAM['Components']['ThisComponent']['Files'])) {
            $CIDRAM['Arrayify']($CIDRAM['Components']['ThisComponent']['Files']);
            $CIDRAM['Arrayify']($CIDRAM['Components']['ThisComponent']['Files']['To']);
            $CIDRAM['Arrayify']($CIDRAM['Components']['ThisComponent']['Files']['From']);
            if (isset($CIDRAM['Components']['ThisComponent']['Files']['Checksum'])) {
                $CIDRAM['Arrayify']($CIDRAM['Components']['ThisComponent']['Files']['Checksum']);
            }
        }
        if (empty($CIDRAM['Components']['ThisComponent']['Remote'])) {
            $CIDRAM['Components']['ThisComponent']['RemoteData'] = $CIDRAM['L10N']->getString('response_updates_unable_to_determine');
            if (!$CIDRAM['Components']['ThisComponent']['StatClass']) {
                $CIDRAM['Components']['ThisComponent']['StatClass'] = 's';
            }
        } else {
            $CIDRAM['FetchRemote']();
            if (
                substr($CIDRAM['Components']['ThisComponent']['RemoteData'], 0, 4) === "---\n" &&
                ($CIDRAM['Components']['EoYAML'] = strpos(
                    $CIDRAM['Components']['ThisComponent']['RemoteData'], "\n\n"
                )) !== false
            ) {

                /** Process remote components metadata. */
                if (!isset($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']])) {
                    $CIDRAM['YAML']->process(
                        substr($CIDRAM['Components']['ThisComponent']['RemoteData'], 4, $CIDRAM['Components']['EoYAML'] - 4),
                        $CIDRAM['Components']['RemoteMeta']
                    );
                }

                if (isset($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Version'])) {
                    $CIDRAM['Components']['ThisComponent']['Latest'] =
                        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Version'];
                } else {
                    if (!$CIDRAM['Components']['ThisComponent']['StatClass']) {
                        $CIDRAM['Components']['ThisComponent']['StatClass'] = 's';
                    }
                }
            } elseif (!$CIDRAM['Components']['ThisComponent']['StatClass']) {
                $CIDRAM['Components']['ThisComponent']['StatClass'] = 's';
            }
            if (!empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Name'])) {
                $CIDRAM['Components']['ThisComponent']['Name'] =
                    $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Name'];
                $CIDRAM['PrepareName']($CIDRAM['Components']['ThisComponent'], $CIDRAM['Components']['Key']);
            }
            if (
                empty($CIDRAM['Components']['ThisComponent']['False Positive Risk']) &&
                !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['False Positive Risk'])
            ) {
                $CIDRAM['Components']['ThisComponent']['False Positive Risk'] =
                    $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['False Positive Risk'];
            }
            if (
                empty($CIDRAM['Components']['ThisComponent']['Used with']) &&
                !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Used with'])
            ) {
                $CIDRAM['Components']['ThisComponent']['Used with'] = $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Used with'];
            }
            if (!empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Extended Description'])) {
                $CIDRAM['Components']['ThisComponent']['Extended Description'] =
                    $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Extended Description'];
                $CIDRAM['PrepareExtendedDescription']($CIDRAM['Components']['ThisComponent'], $CIDRAM['Components']['Key']);
            }
            if (!$CIDRAM['Components']['ThisComponent']['StatClass']) {
                if (!empty($CIDRAM['Components']['ThisComponent']['Latest']) && $CIDRAM['VersionCompare'](
                    $CIDRAM['Components']['ThisComponent']['Version'],
                    $CIDRAM['Components']['ThisComponent']['Latest']
                )) {
                    $CIDRAM['Components']['ThisComponent']['Outdated'] = true;
                    if (
                        $CIDRAM['Components']['Key'] === 'l10n/' . $CIDRAM['Config']['general']['lang'] ||
                        $CIDRAM['Components']['Key'] === 'theme/' . $CIDRAM['Config']['template_data']['theme']
                    ) {
                        $CIDRAM['Components']['Interdependent'][] = $CIDRAM['Components']['Key'];
                    }
                    $CIDRAM['Components']['Outdated'][] = $CIDRAM['Components']['Key'];
                    if ((
                        !empty($CIDRAM['Components']['ThisComponent']['Used with']) &&
                        substr($CIDRAM['Components']['ThisComponent']['Used with'], 0, 3) === 'ipv'
                    ) || (
                        !empty($CIDRAM['Components']['ThisComponent']['Extended Description']) &&
                        strpos($CIDRAM['Components']['ThisComponent']['Extended Description'], 'signatures-&gt;ipv') !== false
                    )) {
                        $CIDRAM['Components']['OutdatedSignatureFiles'][] = $CIDRAM['Components']['Key'];
                    }
                    $CIDRAM['Components']['ThisComponent']['RowClass'] = 'r';
                    $CIDRAM['Components']['ThisComponent']['StatClass'] = 'txtRd';
                    if (
                        empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required']) ||
                        $CIDRAM['VersionCompare'](
                            $CIDRAM['ScriptVersion'],
                            $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required']
                        )
                    ) {
                        $CIDRAM['Components']['ThisComponent']['StatusOptions'] = $CIDRAM['L10N']->getString('response_updates_outdated_manually');
                    } elseif (
                        !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required PHP']) &&
                        $CIDRAM['VersionCompare'](PHP_VERSION, $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required PHP'])
                    ) {
                        $CIDRAM['Components']['ThisComponent']['StatusOptions'] = $CIDRAM['ParseVars'](
                            ['V' => $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required PHP']],
                            $CIDRAM['L10N']->getString('response_updates_outdated_php_version')
                        );
                    } else {
                        $CIDRAM['Components']['ThisComponent']['StatusOptions'] = $CIDRAM['L10N']->getString('response_updates_outdated');
                        $CIDRAM['Components']['ThisComponent']['Options'] .=
                            '<option value="update-component">' . $CIDRAM['L10N']->getString('field_update') . '</option>';
                    }
                } else {
                    $CIDRAM['Components']['ThisComponent']['StatClass'] = 'txtGn';
                    $CIDRAM['Components']['ThisComponent']['StatusOptions'] = $CIDRAM['L10N']->getString('response_updates_already_up_to_date');
                    if (isset(
                        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files'],
                        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['To'],
                        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['From'],
                        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['Checksum'],
                        $CIDRAM['Components']['ThisComponent']['Files'],
                        $CIDRAM['Components']['ThisComponent']['Files']['To'],
                        $CIDRAM['Components']['ThisComponent']['Remote']
                    ) && (
                        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['To'] === $CIDRAM['Components']['ThisComponent']['Files']['To']
                    )) {
                        $CIDRAM['Components']['Repairable'][] = $CIDRAM['Components']['Key'];
                        $CIDRAM['Components']['ThisComponent']['Options'] .= '<option value="repair-component">' . $CIDRAM['L10N']->getString('field_repair') . '</option>';
                    }
                }
            }
            if (!empty($CIDRAM['Components']['ThisComponent']['Files']['To'])) {
                $CIDRAM['Activable'] = $CIDRAM['IsActivable']($CIDRAM['Components']['ThisComponent']);
                if (preg_match('~^(?:l10n/' . preg_quote(
                    $CIDRAM['Config']['general']['lang']
                ) . '|theme/' . preg_quote(
                    $CIDRAM['Config']['template_data']['theme']
                ) . '|CIDRAM.*|Common Classes Package)$~i', $CIDRAM['Components']['Key']) || $CIDRAM['IsInUse'](
                    $CIDRAM['Components']['ThisComponent']
                )) {
                    $CIDRAM['AppendToString']($CIDRAM['Components']['ThisComponent']['StatusOptions'], '<hr />',
                        '<div class="txtGn">' . $CIDRAM['L10N']->getString('state_component_is_active') . '</div>'
                    );
                    if ($CIDRAM['Activable']) {
                        $CIDRAM['Components']['ThisComponent']['Options'] .= '<option value="deactivate-component">' . $CIDRAM['L10N']->getString('field_deactivate') . '</option>';
                        if (!empty($CIDRAM['Components']['ThisComponent']['Uninstallable'])) {
                            $CIDRAM['Components']['ThisComponent']['Options'] .=
                                '<option value="deactivate-and-uninstall-component">' .
                                $CIDRAM['L10N']->getString('field_deactivate') . ' + ' . $CIDRAM['L10N']->getString('field_uninstall') .
                                '</option>';
                        }
                    }
                } else {
                    if ($CIDRAM['Activable']) {
                        $CIDRAM['Components']['ThisComponent']['Options'] .=
                            '<option value="activate-component">' . $CIDRAM['L10N']->getString('field_activate') . '</option>';
                    }
                    if (!empty($CIDRAM['Components']['ThisComponent']['Uninstallable'])) {
                        $CIDRAM['Components']['ThisComponent']['Options'] .=
                            '<option value="uninstall-component">' . $CIDRAM['L10N']->getString('field_uninstall') . '</option>';
                    }
                    if (!empty($CIDRAM['Components']['ThisComponent']['Provisional'])) {
                        $CIDRAM['AppendToString']($CIDRAM['Components']['ThisComponent']['StatusOptions'], '<hr />',
                            '<div class="txtOe">' . $CIDRAM['L10N']->getString('state_component_is_provisional') . '</div>'
                        );
                    } else {
                        $CIDRAM['AppendToString']($CIDRAM['Components']['ThisComponent']['StatusOptions'], '<hr />',
                            '<div class="txtRd">' . $CIDRAM['L10N']->getString('state_component_is_inactive') . '</div>'
                        );
                    }
                }
            }
        }
        if (empty($CIDRAM['Components']['ThisComponent']['Latest'])) {
            $CIDRAM['Components']['ThisComponent']['Latest'] = $CIDRAM['L10N']->getString('response_updates_unable_to_determine');
        } elseif (
            empty($CIDRAM['Components']['ThisComponent']['Files']['To']) &&
            !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['To'])
        ) {
            if (
                empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required PHP']) ||
                !$CIDRAM['VersionCompare'](PHP_VERSION, $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required PHP'])
            ) {
                $CIDRAM['Components']['ThisComponent']['Options'] .= '<option value="update-component">' . $CIDRAM['L10N']->getString('field_install') . '</option>';
                if ($CIDRAM['IsActivable']($CIDRAM['Components']['ThisComponent'])) {
                    $CIDRAM['Components']['ThisComponent']['Options'] .=
                        '<option value="update-and-activate-component">' .
                        $CIDRAM['L10N']->getString('field_install') . ' + ' . $CIDRAM['L10N']->getString('field_activate') .
                        '</option>';
                }
            } elseif ($CIDRAM['Components']['ThisComponent']['StatusOptions'] === $CIDRAM['L10N']->getString('response_updates_not_installed')) {
                $CIDRAM['Components']['ThisComponent']['StatusOptions'] = $CIDRAM['ParseVars'](
                    ['V' => $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required PHP']],
                    $CIDRAM['L10N']->getString('response_updates_not_installed_php')
                );
            }
        }
        $CIDRAM['Components']['ThisComponent']['VersionSize'] = 0;
        if (
            !empty($CIDRAM['Components']['ThisComponent']['Files']['To']) &&
            is_array($CIDRAM['Components']['ThisComponent']['Files']['To'])
        ) {
            $CIDRAM['Components']['ThisComponent']['Options'] .=
                '<option value="verify-component" selected>' . $CIDRAM['L10N']->getString('field_verify') . '</option>';
            $CIDRAM['Components']['Verify'][] = $CIDRAM['Components']['Key'];
        }
        if (
            !empty($CIDRAM['Components']['ThisComponent']['Files']['Checksum']) &&
            is_array($CIDRAM['Components']['ThisComponent']['Files']['Checksum'])
        ) {
            array_walk($CIDRAM['Components']['ThisComponent']['Files']['Checksum'], function ($Checksum) use (&$CIDRAM) {
                if (!empty($Checksum) && ($Delimiter = strpos($Checksum, ':')) !== false) {
                    $CIDRAM['Components']['ThisComponent']['VersionSize'] += (int)substr($Checksum, $Delimiter + 1);
                }
            });
        }
        if ($CIDRAM['Components']['ThisComponent']['VersionSize'] > 0) {
            $CIDRAM['FormatFilesize']($CIDRAM['Components']['ThisComponent']['VersionSize']);
            $CIDRAM['Components']['ThisComponent']['VersionSize'] =
                '<br />' . $CIDRAM['L10N']->getString('field_size') .
                $CIDRAM['Components']['ThisComponent']['VersionSize'];
        } else {
            $CIDRAM['Components']['ThisComponent']['VersionSize'] = '';
        }
        $CIDRAM['Components']['ThisComponent']['LatestSize'] = 0;
        if (
            !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['Checksum']) &&
            is_array($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['Checksum'])
        ) {
            array_walk($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['Checksum'], function ($Checksum) use (&$CIDRAM) {
                if (!empty($Checksum) && ($Delimiter = strpos($Checksum, ':')) !== false) {
                    $CIDRAM['Components']['ThisComponent']['LatestSize'] += (int)substr($Checksum, $Delimiter + 1);
                }
            });
        }
        if ($CIDRAM['Components']['ThisComponent']['LatestSize'] > 0) {
            $CIDRAM['FormatFilesize']($CIDRAM['Components']['ThisComponent']['LatestSize']);
            $CIDRAM['Components']['ThisComponent']['LatestSize'] =
                '<br />' . $CIDRAM['L10N']->getString('field_size') .
                $CIDRAM['Components']['ThisComponent']['LatestSize'];
        } else {
            $CIDRAM['Components']['ThisComponent']['LatestSize'] = '';
        }
        if (!empty($CIDRAM['Components']['ThisComponent']['Options'])) {
            $CIDRAM['AppendToString']($CIDRAM['Components']['ThisComponent']['StatusOptions'], '<hr />',
                '<select name="do" class="auto">' . $CIDRAM['Components']['ThisComponent']['Options'] .
                '</select><input type="submit" value="' . $CIDRAM['L10N']->getString('field_ok') . '" class="auto" />'
            );
            $CIDRAM['Components']['ThisComponent']['Options'] = '';
        }
        /** Append changelog. */
        $CIDRAM['Components']['ThisComponent']['Changelog'] = empty(
            $CIDRAM['Components']['ThisComponent']['Changelog']
        ) ? '' : '<br /><a href="' . $CIDRAM['Components']['ThisComponent']['Changelog'] . '">Changelog</a>';
        /** Append tests. */
        if (!empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisComponent']['ID']]['Tests'])) {
            $CIDRAM['AppendTests']($CIDRAM['Components']['ThisComponent']);
        }
        /** Append filename. */
        $CIDRAM['Components']['ThisComponent']['Filename'] = (
            empty($CIDRAM['Components']['ThisComponent']['Files']['To']) ||
            count($CIDRAM['Components']['ThisComponent']['Files']['To']) !== 1
        ) ? '' : '<br />' . $CIDRAM['L10N']->getString('field_filename') . $CIDRAM['Components']['ThisComponent']['Files']['To'][0];
        /** Finalise entry. */
        if (
            !($CIDRAM['FE']['hide-non-outdated'] && empty($CIDRAM['Components']['ThisComponent']['Outdated'])) &&
            !($CIDRAM['FE']['hide-unused'] && empty($CIDRAM['Components']['ThisComponent']['Files']['To']))
        ) {
            if (empty($CIDRAM['Components']['ThisComponent']['RowClass'])) {
                $CIDRAM['Components']['ThisComponent']['RowClass'] = 'h1';
            }
            if (!empty($CIDRAM['FE']['sort-by-name']) && !empty($CIDRAM['Components']['ThisComponent']['Name'])) {
                $CIDRAM['Components']['ThisComponent']['SortKey'] = $CIDRAM['Components']['ThisComponent']['Name'];
            } else {
                $CIDRAM['Components']['ThisComponent']['SortKey'] = $CIDRAM['Components']['Key'];
            }
            $CIDRAM['FE']['Indexes'][$CIDRAM['Components']['ThisComponent']['SortKey']] = sprintf(
                "<a href=\"#%s\">%s</a><br /><br />\n            ",
                $CIDRAM['Components']['ThisComponent']['ID'],
                $CIDRAM['Components']['ThisComponent']['Name']
            );
            $CIDRAM['Components']['Out'][$CIDRAM['Components']['ThisComponent']['SortKey']] = $CIDRAM['ParseVars'](
                $CIDRAM['L10N']->Data + $CIDRAM['ArrayFlatten']($CIDRAM['Components']['ThisComponent']) + $CIDRAM['ArrayFlatten']($CIDRAM['FE']),
                $CIDRAM['FE']['UpdatesRow']
            );
        }
    }

    /** Update request via Cronable. */
    if (!empty($CIDRAM['Alternate']) && !empty($UpdateAll) && !empty($CIDRAM['Components']['Outdated'])) {

        /** Trigger updates handler. */
        $CIDRAM['UpdatesHandler']('update-component', $CIDRAM['Components']['Outdated']);

    }

    /** Prepare newly found component metadata and options for display. */
    foreach ($CIDRAM['Components']['RemoteMeta'] as $CIDRAM['Components']['Key'] => &$CIDRAM['Components']['ThisComponent']) {
        if (
            isset($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]) ||
            empty($CIDRAM['Components']['ThisComponent']['Remote']) ||
            empty($CIDRAM['Components']['ThisComponent']['Version']) ||
            empty($CIDRAM['Components']['ThisComponent']['Files']['From']) ||
            empty($CIDRAM['Components']['ThisComponent']['Files']['To']) ||
            empty($CIDRAM['Components']['ThisComponent']['Reannotate']) ||
            !$CIDRAM['Traverse']($CIDRAM['Components']['ThisComponent']['Reannotate']) ||
            !file_exists($CIDRAM['Vault'] . $CIDRAM['Components']['ThisComponent']['Reannotate'])
        ) {
            continue;
        }
        $CIDRAM['Components']['ReannotateThis'] = $CIDRAM['Components']['ThisComponent']['Reannotate'];
        $CIDRAM['FetchRemote']();
        if (!preg_match(
            "~(\n" . preg_quote($CIDRAM['Components']['Key']) . ":?)(\n [^\n]*)*\n~i",
            $CIDRAM['Components']['ThisComponent']['RemoteData'],
            $CIDRAM['Components']['RemoteDataThis']
        )) {
            continue;
        }
        $CIDRAM['Components']['RemoteDataThis'] = preg_replace(
            ["/\n Files:(\n  [^\n]*)*\n/i", "/\n Version: [^\n]*\n/i"],
            "\n",
            $CIDRAM['Components']['RemoteDataThis'][0]
        );
        if (empty($CIDRAM['Components']['Remotes'][$CIDRAM['Components']['ReannotateThis']])) {
            $CIDRAM['Components']['Remotes'][$CIDRAM['Components']['ReannotateThis']] = $CIDRAM['Updater-IO']->readFile(
                $CIDRAM['Vault'] . $CIDRAM['Components']['ReannotateThis']
            );
        }
        if (substr(
            $CIDRAM['Components']['Remotes'][$CIDRAM['Components']['ReannotateThis']], -2
        ) !== "\n\n" || substr(
            $CIDRAM['Components']['Remotes'][$CIDRAM['Components']['ReannotateThis']], 0, 4
        ) !== "---\n") {
            continue;
        }
        $CIDRAM['ThisOffset'] = [0 => []];
        $CIDRAM['ThisOffset'][1] = preg_match(
            '/(\n+)$/',
            $CIDRAM['Components']['Remotes'][$CIDRAM['Components']['ReannotateThis']],
            $CIDRAM['ThisOffset'][0]
        );
        $CIDRAM['ThisOffset'] = strlen($CIDRAM['ThisOffset'][0][0]) * -1;
        $CIDRAM['Components']['Remotes'][$CIDRAM['Components']['ReannotateThis']] = substr(
            $CIDRAM['Components']['Remotes'][$CIDRAM['Components']['ReannotateThis']], 0, $CIDRAM['ThisOffset']
        ) . $CIDRAM['Components']['RemoteDataThis'] . "\n";
        $CIDRAM['PrepareName']($CIDRAM['Components']['ThisComponent'], $CIDRAM['Components']['Key']);
        $CIDRAM['PrepareExtendedDescription']($CIDRAM['Components']['ThisComponent'], $CIDRAM['Components']['Key']);
        $CIDRAM['Components']['ThisComponent']['ID'] = $CIDRAM['Components']['Key'];
        $CIDRAM['Components']['ThisComponent']['Latest'] = $CIDRAM['Components']['ThisComponent']['Version'];
        $CIDRAM['Components']['ThisComponent']['Version'] = $CIDRAM['L10N']->getString('response_updates_not_installed');
        $CIDRAM['Components']['ThisComponent']['StatClass'] = 'txtRd';
        $CIDRAM['Components']['ThisComponent']['RowClass'] = 'h2';
        $CIDRAM['Components']['ThisComponent']['VersionSize'] = '';
        $CIDRAM['Components']['ThisComponent']['LatestSize'] = 0;
        if (
            !empty($CIDRAM['Components']['ThisComponent']['Files']['Checksum']) &&
            is_array($CIDRAM['Components']['ThisComponent']['Files']['Checksum'])
        ) {
            foreach ($CIDRAM['Components']['ThisComponent']['Files']['Checksum'] as $CIDRAM['Components']['ThisChecksum']) {
                if (empty($CIDRAM['Components']['ThisChecksum'])) {
                    continue;
                }
                if (($CIDRAM['FilesDelimit'] = strpos($CIDRAM['Components']['ThisChecksum'], ':')) !== false) {
                    $CIDRAM['Components']['ThisComponent']['LatestSize'] +=
                        (int)substr($CIDRAM['Components']['ThisChecksum'], $CIDRAM['FilesDelimit'] + 1);
                }
            }
        }
        if ($CIDRAM['Components']['ThisComponent']['LatestSize'] > 0) {
            $CIDRAM['FormatFilesize']($CIDRAM['Components']['ThisComponent']['LatestSize']);
            $CIDRAM['Components']['ThisComponent']['LatestSize'] =
                '<br />' . $CIDRAM['L10N']->getString('field_size') .
                $CIDRAM['Components']['ThisComponent']['LatestSize'];
        } else {
            $CIDRAM['Components']['ThisComponent']['LatestSize'] = '';
        }
        if (
            !empty($CIDRAM['Components']['ThisComponent']['Minimum Required PHP']) &&
            $CIDRAM['VersionCompare'](PHP_VERSION, $CIDRAM['Components']['ThisComponent']['Minimum Required PHP'])
        ) {
            $CIDRAM['Components']['ThisComponent']['StatusOptions'] = $CIDRAM['ParseVars']([
                'V' => $CIDRAM['Components']['ThisComponent']['Minimum Required PHP']
            ], $CIDRAM['L10N']->getString('response_updates_not_installed_php'));
        } else {
            $CIDRAM['Components']['ThisComponent']['StatusOptions'] =
                $CIDRAM['L10N']->getString('response_updates_not_installed') . '<br /><select name="do" class="auto">' .
                '<option value="update-component">' . $CIDRAM['L10N']->getString('field_install') . '</option>';
            if ($CIDRAM['IsActivable']($CIDRAM['Components']['ThisComponent'])) {
                $CIDRAM['Components']['ThisComponent']['StatusOptions'] .=
                    '<option value="update-and-activate-component">' .
                    $CIDRAM['L10N']->getString('field_install') . ' + ' . $CIDRAM['L10N']->getString('field_activate') .
                    '</option>';
            }
            $CIDRAM['Components']['ThisComponent']['StatusOptions'] .= '</select><input type="submit" value="' . $CIDRAM['L10N']->getString('field_ok') . '" class="auto" />';
        }
        /** Append changelog. */
        $CIDRAM['Components']['ThisComponent']['Changelog'] = empty(
            $CIDRAM['Components']['ThisComponent']['Changelog']
        ) ? '' : '<br /><a href="' . $CIDRAM['Components']['ThisComponent']['Changelog'] . '">Changelog</a>';
        /** Append tests. */
        if (!empty($CIDRAM['Components']['ThisComponent']['Tests'])) {
            $CIDRAM['AppendTests']($CIDRAM['Components']['ThisComponent']);
        }
        /** Append filename (empty). */
        $CIDRAM['Components']['ThisComponent']['Filename'] = '';
        /** Finalise entry. */
        if (!$CIDRAM['FE']['hide-unused']) {
            if (!empty($CIDRAM['FE']['sort-by-name']) && !empty($CIDRAM['Components']['ThisComponent']['Name'])) {
                $CIDRAM['Components']['ThisComponent']['SortKey'] = $CIDRAM['Components']['ThisComponent']['Name'];
            } else {
                $CIDRAM['Components']['ThisComponent']['SortKey'] = $CIDRAM['Components']['Key'];
            }
            $CIDRAM['FE']['Indexes'][$CIDRAM['Components']['ThisComponent']['SortKey']] = sprintf(
                "<a href=\"#%s\">%s</a><br /><br />\n            ",
                $CIDRAM['Components']['ThisComponent']['ID'],
                $CIDRAM['Components']['ThisComponent']['Name']
            );
            $CIDRAM['Components']['Out'][$CIDRAM['Components']['ThisComponent']['SortKey']] = $CIDRAM['ParseVars'](
                $CIDRAM['L10N']->Data + $CIDRAM['ArrayFlatten']($CIDRAM['Components']['ThisComponent']) + $CIDRAM['ArrayFlatten']($CIDRAM['FE']),
                $CIDRAM['FE']['UpdatesRow']
            );
        }
    }
    /** Cleanup. */
    unset($CIDRAM['Components']['ThisComponent']);

    /** Write annotations for newly found component metadata. */
    array_walk($CIDRAM['Components']['Remotes'], function ($Remote, $Key) use (&$CIDRAM) {
        if (substr($Remote, -2) !== "\n\n" || substr($Remote, 0, 4) !== "---\n") {
            return;
        }
        $CIDRAM['Updater-IO']->writeFile($CIDRAM['Vault'] . $Key, $Remote);
    });

    /** Finalise output and unset working data. */
    $CIDRAM['FE']['Indexes'] = $CIDRAM['UpdatesSortFunc']($CIDRAM['FE']['Indexes']);
    $CIDRAM['FE']['Components'] = $CIDRAM['UpdatesSortFunc']($CIDRAM['Components']['Out']);

    $CIDRAM['Components']['CountOutdated'] = count($CIDRAM['Components']['Outdated']);
    $CIDRAM['Components']['CountOutdatedSignatureFiles'] = count($CIDRAM['Components']['OutdatedSignatureFiles']);
    $CIDRAM['Components']['CountVerify'] = count($CIDRAM['Components']['Verify']);
    $CIDRAM['Components']['CountRepairable'] = count($CIDRAM['Components']['Repairable']);

    /** Preparing the update all, verify all, repair all buttons. */
    $CIDRAM['FE']['UpdateAll'] = (
        $CIDRAM['Components']['CountOutdated'] ||
        $CIDRAM['Components']['CountOutdatedSignatureFiles'] ||
        $CIDRAM['Components']['CountVerify'] ||
        $CIDRAM['Components']['CountRepairable']
    ) ? '<hr />' : '';

    /** Instructions to update all signature files (but not necessarily everything). */
    if ($CIDRAM['Components']['CountOutdatedSignatureFiles']) {
        $CIDRAM['FE']['UpdateAll'] .= sprintf($CIDRAM['CFBoilerplate'], $CIDRAM['FE']['UpdatesFormTarget'], 'update-component');
        foreach ($CIDRAM['Components']['OutdatedSignatureFiles'] as $CIDRAM['Components']['ThisOutdated']) {
            $CIDRAM['FE']['UpdateAll'] .= '<input name="ID[]" type="hidden" value="' . $CIDRAM['Components']['ThisOutdated'] . '" />';
        }
        $CIDRAM['FE']['UpdateAll'] .= '<input type="submit" value="' . $CIDRAM['L10N']->getString('field_update_signatures_files') . '" class="auto" /></form>';
    }

    /** Instructions to update everything at once. */
    if ($CIDRAM['Components']['CountOutdated'] && $CIDRAM['Components']['CountOutdated'] !== $CIDRAM['Components']['CountOutdatedSignatureFiles']) {
        $CIDRAM['FE']['UpdateAll'] .= sprintf($CIDRAM['CFBoilerplate'], $CIDRAM['FE']['UpdatesFormTarget'], 'update-component');
        foreach ($CIDRAM['Components']['Outdated'] as $CIDRAM['Components']['ThisOutdated']) {
            $CIDRAM['FE']['UpdateAll'] .= '<input name="ID[]" type="hidden" value="' . $CIDRAM['Components']['ThisOutdated'] . '" />';
        }
        $CIDRAM['FE']['UpdateAll'] .= '<input type="submit" value="' . $CIDRAM['L10N']->getString('field_update_all') . '" class="auto" /></form>';
    }

    /** Instructions to repair everything at once. */
    if ($CIDRAM['Components']['CountRepairable']) {
        $CIDRAM['FE']['UpdateAll'] .= sprintf($CIDRAM['CFBoilerplate'], $CIDRAM['FE']['UpdatesFormTarget'], 'repair-component');
        foreach ($CIDRAM['Components']['Repairable'] as $CIDRAM['Components']['ThisRepairable']) {
            $CIDRAM['FE']['UpdateAll'] .= '<input name="ID[]" type="hidden" value="' . $CIDRAM['Components']['ThisRepairable'] . '" />';
        }
        $CIDRAM['FE']['UpdateAll'] .= '<input type="submit" value="' . $CIDRAM['L10N']->getString('field_repair_all') . '" class="auto" /></form>';
    }

    /** Instructions to verify everything at once. */
    if ($CIDRAM['Components']['CountVerify']) {
        $CIDRAM['FE']['UpdateAll'] .= sprintf($CIDRAM['CFBoilerplate'], $CIDRAM['FE']['UpdatesFormTarget'], 'verify-component');
        foreach ($CIDRAM['Components']['Verify'] as $CIDRAM['Components']['ThisVerify']) {
            $CIDRAM['FE']['UpdateAll'] .= '<input name="ID[]" type="hidden" value="' . $CIDRAM['Components']['ThisVerify'] . '" />';
        }
        $CIDRAM['FE']['UpdateAll'] .= '<input type="submit" value="' . $CIDRAM['L10N']->getString('field_verify_all') . '" class="auto" /></form>';
    }

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['L10N']->Data + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_updates.html'))
    ) . $CIDRAM['MenuToggle'];

    /** Inject interdependent components to each other's update instructions. */
    if (count($CIDRAM['Components']['Interdependent'])) {
        array_unshift($CIDRAM['Components']['Interdependent'], 'CIDRAM');
        $CIDRAM['Components']['AllInter'] = '<input name="ID[]" type="hidden" value="' . implode(
            '" /><input name="ID[]" type="hidden" value="',
            $CIDRAM['Components']['Interdependent']
        ) . '" />';
        foreach ($CIDRAM['Components']['Interdependent'] as $CIDRAM['Components']['ThisInter']) {
            $CIDRAM['FE']['FE_Content'] = str_replace(
                '<input name="ID" type="hidden" value="' . $CIDRAM['Components']['ThisInter'] . '" />',
                $CIDRAM['Components']['AllInter'],
                $CIDRAM['FE']['FE_Content']
            );
        }
    }

    /** Finalise IO operations all at once. */
    unset($CIDRAM['Updater-IO']);

    /** Send output. */
    if (!$CIDRAM['FE']['CronMode']) {
        /** Normal page output. */
        echo $CIDRAM['SendOutput']();
    } elseif (!empty($UpdateAll)) {
        /** Returned state message for Cronable (locally updating). */
        $Results = ['state_msg' => str_ireplace(['<code>', '</code>', '<br />'], ['[', ']', "\n"], $CIDRAM['FE']['state_msg'])];
    } elseif (!empty($CIDRAM['FE']['state_msg'])) {
        /** Returned state message for Cronable. */
        echo json_encode([
            'state_msg' => str_ireplace(['<code>', '</code>', '<br />'], ['[', ']', "\n"], $CIDRAM['FE']['state_msg'])
        ]);
    } elseif (!empty($_POST['do']) && $_POST['do'] === 'get-list' && count($CIDRAM['Components']['Outdated'])) {
        /** Returned list of outdated components for Cronable. */
        echo json_encode([
            'state_msg' => str_ireplace(['<code>', '</code>', '<br />'], ['[', ']', "\n"], $CIDRAM['FE']['state_msg']),
            'outdated' => $CIDRAM['Components']['Outdated']
        ]);
    }

    /** Cleanup. */
    unset($CIDRAM['Components'], $CIDRAM['CFBoilerplate']);

}

/** Signature file fixer. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'fixer' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Page initial prepwork. */
    $CIDRAM['InitialPrepwork']($CIDRAM['L10N']->getString('link_fixer'), $CIDRAM['L10N']->getString('tip_fixer'));

    $CIDRAM['FE']['bNav'] = $CIDRAM['L10N']->getString('bNav_home_logout');

    /** Preferred source. */
    $CIDRAM['PreferredSource'] = !empty($_POST['preferredSource']) ? $_POST['preferredSource'] : '';

    /** Direct input. */
    $CIDRAM['FE']['DirectInput'] = !empty($_POST['DirectInput']) ? $_POST['DirectInput'] : '';

    /** Preferred source menu. */
    $CIDRAM['FE']['PreferredSource'] = sprintf(
        '%1$sList" value="List"%2$s %7$s%6$spreferredSourceList">%3$s</label><br />%1$sInput" value="Input"%4$s %7$s%6$spreferredSourceInput">%5$s</label>',
        '<input type="radio" class="auto" name="preferredSource" id="preferredSource',
        $CIDRAM['PreferredSource'] === 'List' ? ' checked' : '',
        $CIDRAM['L10N']->getString('field_preferred_list'),
        $CIDRAM['PreferredSource'] === 'Input' ? ' checked' : '',
        $CIDRAM['L10N']->getString('field_preferred_direct_input'),
        ' /><label for="',
        'onchange="javascript:{hideid(\'preferredSourceListDiv\');hideid(\'preferredSourceInputDiv\');showid(this.id+\'Div\');showid(\'submitButton\');}"'
    );

    /** Whether to show or hide preferred source sections. */
    $CIDRAM['FE']['styleList'] = $CIDRAM['PreferredSource'] === 'List' ? '' : ' style="display:none"';
    $CIDRAM['FE']['styleInput'] = $CIDRAM['PreferredSource'] === 'Input' ? '' : ' style="display:none"';
    $CIDRAM['FE']['submitButtonVisibility'] = !empty($CIDRAM['PreferredSource']) ? '' : ' style="display:none"';

    /** Generate a list of currently active signature files. */
    $CIDRAM['FE']['ActiveSignatureFiles'] = [];
    foreach ([$CIDRAM['Config']['signatures']['ipv4'], $CIDRAM['Config']['signatures']['ipv6']] as $CIDRAM['SigSource']) {
        $CIDRAM['SigSource'] = explode(',', $CIDRAM['SigSource']);
        foreach ($CIDRAM['SigSource'] as $CIDRAM['SigSourceInner']) {
            $CIDRAM['SigSourceInnerID'] = preg_replace('~[^\da-z]~i', '_', $CIDRAM['SigSourceInner']);
            $CIDRAM['FE']['ActiveSignatureFiles'][$CIDRAM['SigSourceInner']] = sprintf(
                '<input type="radio" class="auto" name="sigFile" id="%1$s" value="%2$s" %3$s/><label for="%1$s">%2$s</label><br />',
                $CIDRAM['SigSourceInnerID'],
                $CIDRAM['SigSourceInner'],
                (!empty($_POST['sigFile']) && $_POST['sigFile'] === $CIDRAM['SigSourceInner']) ? 'checked ' : ''
            );
        }
    }
    unset($CIDRAM['SigSourceInnerID'], $CIDRAM['SigSourceInner']);
    ksort($CIDRAM['FE']['ActiveSignatureFiles']);
    $CIDRAM['FE']['ActiveSignatureFiles'] = implode($CIDRAM['FE']['ActiveSignatureFiles']);

    /** Fixer output. */
    $CIDRAM['FE']['FixerOutput'] = '';

    /** Prepare to process a currently active signature file. */
    if ($CIDRAM['PreferredSource'] === 'List' && !empty($_POST['sigFile'])) {
        if (!isset($CIDRAM['FileCache'])) {
            $CIDRAM['FileCache'] = [];
        }
        if (!isset($CIDRAM['FileCache'][$_POST['sigFile']])) {
            $CIDRAM['FileCache'][$_POST['sigFile']] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $_POST['sigFile']);
        }
        if (!empty($CIDRAM['FileCache'][$_POST['sigFile']])) {
            $CIDRAM['FE']['FixerOutput'] = $CIDRAM['FileCache'][$_POST['sigFile']];
        }
    }

    /** Prepare to process via direct input. */
    if ($CIDRAM['PreferredSource'] === 'Input' && !empty($_POST['DirectInput'])) {
        $CIDRAM['FE']['FixerOutput'] = $_POST['DirectInput'];
    }

    /** Process (validate; attempt to fix) data. */
    if ($CIDRAM['FE']['FixerOutput']) {
        $CIDRAM['Fixer'] = [
            'Time' => microtime(true),
            'Changes' => 0,
            'Aggregator' => new \CIDRAM\Aggregator\Aggregator($CIDRAM),
            'Before' => hash('sha256', $CIDRAM['FE']['FixerOutput']) . ':' . strlen($CIDRAM['FE']['FixerOutput'])
        ];
        if (strpos($CIDRAM['FE']['FixerOutput'], "\r") !== false) {
            $CIDRAM['FE']['FixerOutput'] = str_replace("\r", '', $CIDRAM['FE']['FixerOutput']);
            $CIDRAM['Fixer']['Changes']++;
        }
        $CIDRAM['Fixer']['StrObject'] = new \Maikuolan\Common\ComplexStringHandler(
            "\n" . $CIDRAM['FE']['FixerOutput'] . "\n",
            '~(?<=\n)(?:\n|Expires\: \d{4}\.\d\d\.\d\d|Origin\: [A-Z]{2}|(?:\#|Tag\: |Defers to\: )[^\n]+| *\/\*\*(?:\n *\*[^\n]*)*\/| *\/\*\*? [^\n*]+\*\/|---\n(?:[^\n:]+\:(?:\n +[^\n:]+\: [^\n]+)+)+)+\n~',
            function ($Data) use (&$CIDRAM) {
                if (!$Data = trim($Data)) {
                    return '';
                }
                $Output = '';
                $EoLPos = $NEoLPos = 0;
                while ($NEoLPos !== false) {
                    $Set = $Previous = '';
                    while (true) {
                        if (($NEoLPos = strpos($Data, "\n", $EoLPos)) === false) {
                            $Line = trim(substr($Data, $EoLPos));
                        } else {
                            $Line = trim(substr($Data, $EoLPos, $NEoLPos - $EoLPos));
                            $NEoLPos++;
                        }
                        $Param = (($Pos = strpos($Line, ' ')) !== false) ? substr($Line, $Pos + 1) : 'Deny Generic';
                        if (!$Previous) {
                            $Previous = $Param;
                        }
                        if ($Param !== $Previous) {
                            $NEoLPos = 0;
                            break;
                        }
                        if ($Line) {
                            $Set .= $Line . "\n";
                        }
                        if ($NEoLPos === false) {
                            break;
                        }
                        $EoLPos = $NEoLPos;
                    }
                    $CIDRAM['Results'] = ['In' => 0, 'Rejected' => 0, 'Accepted' => 0, 'Merged' => 0, 'Out' => 0];
                    if ($Set = $CIDRAM['Fixer']['Aggregator']->aggregate(trim($Set))) {
                        $Set = preg_replace('~$~m', ' ' . $Previous, $Set);
                        $Output .= $Set . "\n";
                    }
                    $CIDRAM['Fixer']['Changes'] += $CIDRAM['Results']['Rejected'];
                    $CIDRAM['Fixer']['Changes'] += $CIDRAM['Results']['Merged'];
                }
                return trim($Output);
            }
        );
        $CIDRAM['Fixer']['StrObject']->iterateClosure(function ($Data) use (&$CIDRAM) {
            if (($Pos = strpos($Data, "---\n")) !== false && substr($Data, $Pos - 1, 1) === "\n") {
                $YAML = substr($Data, $Pos + 4);
                if (($HPos = strpos($YAML, "\n#")) !== false) {
                    $After = substr($YAML, $HPos);
                    $YAML = substr($YAML, 0, $HPos + 1);
                } else {
                    $After = '';
                }
                $BeforeCount = substr_count($YAML, "\n");
                $Arr = [];
                $CIDRAM['YAML']->process($YAML, $Arr);
                $NewData = substr($Data, 0, $Pos + 4) . $CIDRAM['YAML']->reconstruct($Arr);
                if (($Add = $BeforeCount - substr_count($NewData, "\n") + 1) > 0) {
                    $NewData .= str_repeat("\n", $Add);
                }
                $NewData .= $After;
                if ($Data !== $NewData) {
                    $CIDRAM['Fixer']['Changes']++;
                    $Data = $NewData;
                }
            }
            return "\n" . $Data;
        }, true);
        $CIDRAM['FE']['FixerOutput'] = trim($CIDRAM['Fixer']['StrObject']->recompile()) . "\n";
        $CIDRAM['Fixer']['After'] = hash('sha256', $CIDRAM['FE']['FixerOutput']) . ':' . strlen($CIDRAM['FE']['FixerOutput']);
        if ($CIDRAM['Fixer']['Before'] !== $CIDRAM['Fixer']['After'] && !$CIDRAM['Fixer']['Changes']) {
            $CIDRAM['Fixer']['Changes']++;
        }
        $CIDRAM['Fixer']['Time'] = microtime(true) - $CIDRAM['Fixer']['Time'];
        $CIDRAM['Fixer'] = '<div class="s">' . sprintf($CIDRAM['L10N']->getString('state_fixer'), sprintf(
            $CIDRAM['L10N']->getPlural($CIDRAM['Fixer']['Changes'], 'state_fixer_changed'),
            $CIDRAM['NumberFormatter']->format($CIDRAM['Fixer']['Changes'])
        ), sprintf(
            $CIDRAM['L10N']->getPlural($CIDRAM['Fixer']['Time'], 'state_fixer_seconds'),
            $CIDRAM['NumberFormatter']->format($CIDRAM['Fixer']['Time'], 3)
        )) . '<br /><blockquote><code>' . $CIDRAM['Fixer']['Before'] . '</code><br />↪️<code>' . $CIDRAM['Fixer']['After'] . '</code></blockquote></div>';
        $CIDRAM['FE']['FixerOutput'] = '<hr />' . $CIDRAM['Fixer'] . '<br /><textarea name="FixerOutput">' . str_replace(
            ['&', '<', '>'],
            ['&amp;', '&lt;', '&gt;'],
            $CIDRAM['FE']['FixerOutput']
        ) . '</textarea><br />';
        unset($CIDRAM['Fixer']);
    }

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['L10N']->Data + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_fixer.html'))
    );

    /** Send output. */
    echo $CIDRAM['SendOutput']();

}

/** File Manager. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'file-manager' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Page initial prepwork. */
    $CIDRAM['InitialPrepwork']($CIDRAM['L10N']->getString('link_file_manager'), $CIDRAM['L10N']->getString('tip_file_manager'), false);

    $CIDRAM['FE']['bNav'] = $CIDRAM['L10N']->getString('bNav_home_logout');

    /** Load pie chart template file upon request. */
    if (empty($CIDRAM['QueryVars']['show'])) {
        $CIDRAM['FE']['ChartJSPath'] = $CIDRAM['PieFile'] = $CIDRAM['PiePath'] = '';
    } else {
        if ($CIDRAM['PiePath'] = $CIDRAM['GetAssetPath']('_chartjs.html', true)) {
            $CIDRAM['PieFile'] = $CIDRAM['ReadFile']($CIDRAM['PiePath']);
        } else {
            $CIDRAM['PieFile'] = '<tr><td class="h4f" colspan="2"><div class="s">{PieChartHTML}</div></td></tr>';
        }
        $CIDRAM['FE']['ChartJSPath'] = $CIDRAM['GetAssetPath']('Chart.min.js', true) ? '?cidram-asset=Chart.min.js&theme=default' : '';
    }

    /** Set vault path for pie chart display. */
    $CIDRAM['FE']['VaultPath'] = str_replace("\\", '/', $CIDRAM['Vault']) . '*';

    /** Prepare components metadata working array. */
    $CIDRAM['Components'] = ['Files' => [], 'Components' => [], 'ComponentFiles' => [], 'Names' => []];

    /** Show/hide pie charts link and etc. */
    if (!$CIDRAM['PieFile']) {

        $CIDRAM['FE']['FMgrFormTarget'] = 'cidram-page=file-manager';
        $CIDRAM['FE']['ShowHideLink'] = '<a href="?cidram-page=file-manager&show=true">' . $CIDRAM['L10N']->getString('label_show') . '</a>';

    } else {

        $CIDRAM['FE']['FMgrFormTarget'] = 'cidram-page=file-manager&show=true';
        $CIDRAM['FE']['ShowHideLink'] = '<a href="?cidram-page=file-manager">' . $CIDRAM['L10N']->getString('label_hide') . '</a>';

        /** Fetch components lists. */
        $CIDRAM['FetchComponentsLists']($CIDRAM['Vault'], $CIDRAM['Components']['Components']);

        /** Identifying file component correlations. */
        foreach ($CIDRAM['Components']['Components'] as $CIDRAM['Components']['ThisName'] => &$CIDRAM['Components']['ThisData']) {
            if (!empty($CIDRAM['Components']['ThisData']['Files']['To'])) {
                $CIDRAM['Arrayify']($CIDRAM['Components']['ThisData']['Files']['To']);
                foreach ($CIDRAM['Components']['ThisData']['Files']['To'] as $CIDRAM['Components']['ThisFile']) {
                    $CIDRAM['Components']['ThisFile'] = str_replace("\\", '/', $CIDRAM['Components']['ThisFile']);
                    $CIDRAM['Components']['Files'][$CIDRAM['Components']['ThisFile']] = $CIDRAM['Components']['ThisName'];
                }
            }
            $CIDRAM['PrepareName']($CIDRAM['Components']['ThisData'], $CIDRAM['Components']['ThisName']);
            if (!empty($CIDRAM['Components']['ThisData']['Name'])) {
                $CIDRAM['Components']['Names'][$CIDRAM['Components']['ThisName']] = $CIDRAM['Components']['ThisData']['Name'];
            }
            $CIDRAM['Components']['ThisData'] = 0;
        }

    }

    /** Upload a new file. */
    if (isset($_POST['do']) && $_POST['do'] === 'upload-file' && isset($_FILES['upload-file']['name'])) {

        /** Check whether safe. */
        $CIDRAM['SafeToContinue'] = (
            basename($_FILES['upload-file']['name']) === $_FILES['upload-file']['name'] &&
            $CIDRAM['FileManager-PathSecurityCheck']($_FILES['upload-file']['name']) &&
            isset($_FILES['upload-file']['tmp_name'], $_FILES['upload-file']['error']) &&
            $_FILES['upload-file']['error'] === UPLOAD_ERR_OK &&
            is_uploaded_file($_FILES['upload-file']['tmp_name']) &&
            !is_link($CIDRAM['Vault'] . $_FILES['upload-file']['name'])
        );

        /** If the filename already exists, delete the old file before moving the new file. */
        if ($CIDRAM['SafeToContinue'] && is_readable($CIDRAM['Vault'] . $_FILES['upload-file']['name'])) {
            if (is_dir($CIDRAM['Vault'] . $_FILES['upload-file']['name'])) {
                if ($CIDRAM['IsDirEmpty']($CIDRAM['Vault'] . $_FILES['upload-file']['name'])) {
                    rmdir($CIDRAM['Vault'] . $_FILES['upload-file']['name']);
                } else {
                    $CIDRAM['SafeToContinue'] = false;
                }
            } else {
                unlink($CIDRAM['Vault'] . $_FILES['upload-file']['name']);
            }
        }

        /** Move the newly uploaded file to the designated location. */
        if ($CIDRAM['SafeToContinue']) {
            rename($_FILES['upload-file']['tmp_name'], $CIDRAM['Vault'] . $_FILES['upload-file']['name']);
            $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_file_uploaded');
        } else {
            $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_upload_error');
        }

    }

    /** A form was submitted. */
    elseif (
        isset($_POST['filename'], $_POST['do']) &&
        is_readable($CIDRAM['Vault'] . $_POST['filename']) &&
        $CIDRAM['FileManager-PathSecurityCheck']($_POST['filename'])
    ) {

        /** Delete a file. */
        if ($_POST['do'] === 'delete-file') {

            if (is_dir($CIDRAM['Vault'] . $_POST['filename'])) {
                if ($CIDRAM['IsDirEmpty']($CIDRAM['Vault'] . $_POST['filename'])) {
                    rmdir($CIDRAM['Vault'] . $_POST['filename']);
                    $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_directory_deleted');
                } else {
                    $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_delete_error');
                }
            } else {
                unlink($CIDRAM['Vault'] . $_POST['filename']);

                /** Remove empty directories. */
                $CIDRAM['DeleteDirectory']($_POST['filename']);

                $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_file_deleted');
            }

        /** Rename a file. */
        } elseif ($_POST['do'] === 'rename-file' && isset($_POST['filename'])) {

            if (isset($_POST['filename_new'])) {

                /** Check whether safe. */
                $CIDRAM['SafeToContinue'] = (
                    $CIDRAM['FileManager-PathSecurityCheck']($_POST['filename']) &&
                    $CIDRAM['FileManager-PathSecurityCheck']($_POST['filename_new']) &&
                    $_POST['filename'] !== $_POST['filename_new']
                );

                /** If the destination already exists, delete it before renaming the new file. */
                if (
                    $CIDRAM['SafeToContinue'] &&
                    file_exists($CIDRAM['Vault'] . $_POST['filename_new']) &&
                    is_readable($CIDRAM['Vault'] . $_POST['filename_new'])
                ) {
                    if (is_dir($CIDRAM['Vault'] . $_POST['filename_new'])) {
                        if ($CIDRAM['IsDirEmpty']($CIDRAM['Vault'] . $_POST['filename_new'])) {
                            rmdir($CIDRAM['Vault'] . $_POST['filename_new']);
                        } else {
                            $CIDRAM['SafeToContinue'] = false;
                        }
                    } else {
                        unlink($CIDRAM['Vault'] . $_POST['filename_new']);
                    }
                }

                /** Rename the file. */
                if ($CIDRAM['SafeToContinue']) {

                    $CIDRAM['ThisName'] = $_POST['filename_new'];
                    $CIDRAM['ThisPath'] = $CIDRAM['Vault'];

                    /** Add parent directories. */
                    while (strpos($CIDRAM['ThisName'], '/') !== false || strpos($CIDRAM['ThisName'], "\\") !== false) {
                        $CIDRAM['Separator'] = (strpos($CIDRAM['ThisName'], '/') !== false) ? '/' : "\\";
                        $CIDRAM['ThisDir'] = substr($CIDRAM['ThisName'], 0, strpos($CIDRAM['ThisName'], $CIDRAM['Separator']));
                        $CIDRAM['ThisPath'] .= $CIDRAM['ThisDir'] . '/';
                        $CIDRAM['ThisName'] = substr($CIDRAM['ThisName'], strlen($CIDRAM['ThisDir']) + 1);
                        if (!file_exists($CIDRAM['ThisPath']) || !is_dir($CIDRAM['ThisPath'])) {
                            mkdir($CIDRAM['ThisPath']);
                        }
                    }

                    if (rename($CIDRAM['Vault'] . $_POST['filename'], $CIDRAM['Vault'] . $_POST['filename_new'])) {
                        /** Remove empty directories. */
                        $CIDRAM['DeleteDirectory']($_POST['filename']);

                        /** Update state message. */
                        $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString(
                            is_dir($CIDRAM['Vault'] . $_POST['filename_new']) ? 'response_directory_renamed' : 'response_file_renamed'
                        );
                    }

                } elseif (!$CIDRAM['FE']['state_msg']) {
                    $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_rename_error');
                }

            } else {

                $CIDRAM['FE']['FE_Title'] .= ' – ' . $CIDRAM['L10N']->getString('field_rename_file') . ' – ' . $_POST['filename'];
                $CIDRAM['FE']['filename'] = $_POST['filename'];

                /** Parse output. */
                $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
                    $CIDRAM['L10N']->Data + $CIDRAM['FE'],
                    $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_files_rename.html'))
                );

                /** Send output. */
                echo $CIDRAM['SendOutput']();
                die;

            }

        /** Edit a file. */
        } elseif ($_POST['do'] === 'edit-file') {

            if (isset($_POST['content'])) {

                $_POST['content'] = str_replace("\r", '', $_POST['content']);
                $CIDRAM['OldData'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $_POST['filename']);
                if (strpos($CIDRAM['OldData'], "\r\n") !== false && strpos($CIDRAM['OldData'], "\n\n") === false) {
                    $_POST['content'] = str_replace("\n", "\r\n", $_POST['content']);
                }

                $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . $_POST['filename'], 'w');
                fwrite($CIDRAM['Handle'], $_POST['content']);
                fclose($CIDRAM['Handle']);

                $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_file_edited');

            } else {

                $CIDRAM['FE']['FE_Title'] .= ' – ' . $_POST['filename'];
                $CIDRAM['FE']['filename'] = $_POST['filename'];
                $CIDRAM['FE']['content'] = htmlentities($CIDRAM['ReadFile']($CIDRAM['Vault'] . $_POST['filename']));

                /** Parse output. */
                $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
                    $CIDRAM['L10N']->Data + $CIDRAM['FE'],
                    $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_files_edit.html'))
                );

                /** Send output. */
                echo $CIDRAM['SendOutput']();
                die;

            }

        /** Download a file. */
        } elseif ($_POST['do'] === 'download-file') {

            header('Content-Type: application/octet-stream');
            header('Content-Transfer-Encoding: Binary');
            header('Content-disposition: attachment; filename="' . basename($_POST['filename']) . '"');
            echo $CIDRAM['ReadFile']($CIDRAM['Vault'] . $_POST['filename']);
            die;

        }

    }

    /** Template for file rows. */
    $CIDRAM['FE']['FilesRow'] = $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_files_row.html'));

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['L10N']->Data + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_files.html'))
    );

    /** Initialise files data variable. */
    $CIDRAM['FE']['FilesData'] = '';

    /** Total size. */
    $CIDRAM['FE']['TotalSize'] = 0;

    /** Fetch files data. */
    $CIDRAM['FilesArray'] = $CIDRAM['FileManager-RecursiveList']($CIDRAM['Vault']);

    if (!$CIDRAM['PieFile']) {
        $CIDRAM['FE']['PieChart'] = '';
    } else {

        /** Sort pie chart values. */
        arsort($CIDRAM['Components']['Components']);

        /** Initialise pie chart values. */
        $CIDRAM['FE']['PieChartValues'] = [];

        /** Initialise pie chart labels. */
        $CIDRAM['FE']['PieChartLabels'] = [];

        /** Initialise pie chart colours. */
        $CIDRAM['FE']['PieChartColours'] = [];

        /** Initialise pie chart legend. */
        $CIDRAM['FE']['PieChartHTML'] = '<ul class="pieul">' . $CIDRAM['L10N']->getString('tip_pie_html');

        /** Building pie chart values. */
        foreach ($CIDRAM['Components']['Components'] as $CIDRAM['Components']['ThisName'] => $CIDRAM['Components']['ThisData']) {
            if (empty($CIDRAM['Components']['ThisData'])) {
                continue;
            }
            $CIDRAM['Components']['ThisSize'] = $CIDRAM['Components']['ThisData'];
            $CIDRAM['FormatFilesize']($CIDRAM['Components']['ThisSize']);
            $CIDRAM['Components']['ThisListed'] = '';
            if (!empty($CIDRAM['Components']['ComponentFiles'][$CIDRAM['Components']['ThisName']])) {
                $CIDRAM['Components']['ThisComponentFiles'] = &$CIDRAM['Components']['ComponentFiles'][$CIDRAM['Components']['ThisName']];
                arsort($CIDRAM['Components']['ThisComponentFiles']);
                $CIDRAM['Components']['ThisListed'] .= '<ul class="comSub txtBl">';
                foreach ($CIDRAM['Components']['ThisComponentFiles'] as $CIDRAM['Components']['ThisFile'] => $CIDRAM['Components']['ThisFileSize']) {
                    $CIDRAM['FormatFilesize']($CIDRAM['Components']['ThisFileSize']);
                    $CIDRAM['Components']['ThisListed'] .= sprintf(
                        '<li style="font-size:0.9em">%1$s – %2$s</li>',
                        $CIDRAM['Components']['ThisFile'],
                        $CIDRAM['Components']['ThisFileSize']
                    );
                }
                $CIDRAM['Components']['ThisListed'] .= '</ul>';
            }
            $CIDRAM['Components']['ThisName'] .= ' – ' . $CIDRAM['Components']['ThisSize'];
            $CIDRAM['FE']['PieChartValues'][] = $CIDRAM['Components']['ThisData'];
            $CIDRAM['FE']['PieChartLabels'][] = $CIDRAM['Components']['ThisName'];
            if ($CIDRAM['PiePath']) {
                $CIDRAM['Components']['ThisColour'] = $CIDRAM['RGB']($CIDRAM['Components']['ThisName']);
                $CIDRAM['Components']['RGB'] = implode(',', $CIDRAM['Components']['ThisColour']['Values']);
                $CIDRAM['FE']['PieChartColours'][] = '#' . $CIDRAM['Components']['ThisColour']['Hash'];
                $CIDRAM['FE']['PieChartHTML'] .= sprintf(
                    '<li style="background:linear-gradient(90deg,rgba(%1$s,0.3),rgba(%1$s,0));color:#%2$s"><span class="comCat" style="cursor:pointer"><span class="txtBl">%3$s</span></span>%4$s</li>',
                    $CIDRAM['Components']['RGB'],
                    $CIDRAM['Components']['ThisColour']['Hash'],
                    $CIDRAM['Components']['ThisName'],
                    $CIDRAM['Components']['ThisListed']
                ) . "\n";
            } else {
                $CIDRAM['FE']['PieChartHTML'] .= sprintf(
                    '<li><span class="comCat" style="cursor:pointer">%1$s</span>%2$s</li>',
                    $CIDRAM['Components']['ThisName'],
                    $CIDRAM['Components']['ThisListed']
                ) . "\n";
            }
        }

        /** Close pie chart legend and append necessary JavaScript for pie chart menu toggle. */
        $CIDRAM['FE']['PieChartHTML'] .= '</ul>' . $CIDRAM['MenuToggle'];

        /** Finalise pie chart values. */
        $CIDRAM['FE']['PieChartValues'] = '[' . implode(', ', $CIDRAM['FE']['PieChartValues']) . ']';

        /** Finalise pie chart labels. */
        $CIDRAM['FE']['PieChartLabels'] = '["' . implode('", "', $CIDRAM['FE']['PieChartLabels']) . '"]';

        /** Finalise pie chart colours. */
        $CIDRAM['FE']['PieChartColours'] = '["' . implode('", "', $CIDRAM['FE']['PieChartColours']) . '"]';

        /** Finalise pie chart. */
        $CIDRAM['FE']['PieChart'] = $CIDRAM['ParseVars']($CIDRAM['L10N']->Data + $CIDRAM['FE'], $CIDRAM['PieFile']);

    }

    /** Cleanup. */
    unset($CIDRAM['PieFile'], $CIDRAM['PiePath'], $CIDRAM['Components']);

    /** Process files data. */
    array_walk($CIDRAM['FilesArray'], function ($ThisFile) use (&$CIDRAM) {
        $Base = '<option value="%s"%s>%s</option>';
        $ThisFile['ThisOptions'] = '';
        if (!$ThisFile['Directory'] || $CIDRAM['IsDirEmpty']($CIDRAM['Vault'] . $ThisFile['Filename'])) {
            $ThisFile['ThisOptions'] .= sprintf($Base, 'delete-file', '', $CIDRAM['L10N']->getString('field_delete'));
            $ThisFile['ThisOptions'] .= sprintf($Base, 'rename-file', $ThisFile['Directory'] && !$ThisFile['CanEdit'] ? ' selected' : '', $CIDRAM['L10N']->getString('field_rename_file'));
        }
        if ($ThisFile['CanEdit']) {
            $ThisFile['ThisOptions'] .= sprintf($Base, 'edit-file', ' selected', $CIDRAM['L10N']->getString('field_edit_file'));
        }
        if (!$ThisFile['Directory']) {
            $ThisFile['ThisOptions'] .= sprintf($Base, 'download-file', $ThisFile['CanEdit'] ? '' : ' selected', $CIDRAM['L10N']->getString('field_download_file'));
        }
        if ($ThisFile['ThisOptions']) {
            $ThisFile['ThisOptions'] =
                '<select name="do">' . $ThisFile['ThisOptions'] . '</select>' .
                '<input type="submit" value="' . $CIDRAM['L10N']->getString('field_ok') . '" class="auto" />';
        }
        $CIDRAM['FE']['FilesData'] .= $CIDRAM['ParseVars'](
            $CIDRAM['L10N']->Data + $CIDRAM['FE'] + $ThisFile, $CIDRAM['FE']['FilesRow']
        );
    });

    /** Total size. */
    $CIDRAM['FormatFilesize']($CIDRAM['FE']['TotalSize']);

    /** Disk free space. */
    $CIDRAM['FE']['FreeSpace'] = disk_free_space(__DIR__);

    /** Disk total space. */
    $CIDRAM['FE']['TotalSpace'] = disk_total_space(__DIR__);

    /** Disk total usage. */
    $CIDRAM['FE']['TotalUsage'] = $CIDRAM['FE']['TotalSpace'] - $CIDRAM['FE']['FreeSpace'];

    $CIDRAM['FormatFilesize']($CIDRAM['FE']['FreeSpace']);
    $CIDRAM['FormatFilesize']($CIDRAM['FE']['TotalSpace']);
    $CIDRAM['FormatFilesize']($CIDRAM['FE']['TotalUsage']);

    /** Send output. */
    echo $CIDRAM['SendOutput']();

}

/** Sections List. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'sections' && $CIDRAM['FE']['Permissions'] === 1) {

    if (!$CIDRAM['FE']['ASYNC']) {

        /** Page initial prepwork. */
        $CIDRAM['InitialPrepwork']($CIDRAM['L10N']->getString('link_sections_list'), $CIDRAM['L10N']->getString('tip_sections_list'));

        /** Append async globals. */
        $CIDRAM['FE']['JS'] .=
            "function slx(a,b,c,d){window['SectionName']=a,window['Action']=b,$('POST','',['SectionName','Action'],null," .
            "function(e){hide(c),show(d,'block')},null)}";

        $CIDRAM['FE']['bNav'] = $CIDRAM['L10N']->getString('bNav_home_logout');

        /** Add flags CSS. */
        if ($CIDRAM['FE']['Flags'] = file_exists($CIDRAM['Vault'] . 'fe_assets/flags.css')) {
            $CIDRAM['FE']['OtherHead'] .= "\n  <link rel=\"stylesheet\" type=\"text/css\" href=\"?cidram-page=flags\" />";
        }

        /** Process signature files. */
        $CIDRAM['FE']['Data'] = (
            (empty($CIDRAM['Config']['signatures']['ipv4']) && empty($CIDRAM['Config']['signatures']['ipv6']))
        ) ? '    <div class="txtRd">' . $CIDRAM['L10N']->getString('warning_signatures_1') . "</div>\n" : $CIDRAM['SectionsHandler'](
            array_unique(explode(',', $CIDRAM['Config']['signatures']['ipv4'] . ',' . $CIDRAM['Config']['signatures']['ipv6']))
        );

        /** Calculate and append page load time, and append totals. */
        $CIDRAM['FE']['ProcessTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
        $CIDRAM['FE']['Data'] = '<div class="s">' . sprintf(
            $CIDRAM['L10N']->getPlural($CIDRAM['FE']['ProcessTime'], 'state_loadtime'),
            $CIDRAM['NumberFormatter']->format($CIDRAM['FE']['ProcessTime'], 3)
        ) . '<br />' . sprintf(
            $CIDRAM['L10N']->getString('state_sl_totals'),
            $CIDRAM['NumberFormatter']->format(isset($CIDRAM['FE']['SL_Signatures']) ? $CIDRAM['FE']['SL_Signatures'] : 0),
            $CIDRAM['NumberFormatter']->format(isset($CIDRAM['FE']['SL_Sections']) ? $CIDRAM['FE']['SL_Sections'] : 0),
            $CIDRAM['NumberFormatter']->format(isset($CIDRAM['FE']['SL_Files']) ? $CIDRAM['FE']['SL_Files'] : 0),
            $CIDRAM['NumberFormatter']->format(isset($CIDRAM['FE']['SL_Unique']) ? $CIDRAM['FE']['SL_Unique'] : 0)
        ) . '</div><hr />' . $CIDRAM['FE']['Data'];

        /** Parse output. */
        $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
            $CIDRAM['L10N']->Data + $CIDRAM['FE'],
            $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_sections.html'))
        );

        /** Send output. */
        echo $CIDRAM['SendOutput']();

    } elseif (isset($_POST['SectionName'], $_POST['Action'])) {

        /** Fetch current ignores data. */
        $CIDRAM['IgnoreData'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'ignore.dat') ?: '';

        if ($_POST['Action'] === 'unignore' && preg_match("~\nIgnore " . $_POST['SectionName'] . "\n~", $CIDRAM['IgnoreData'])) {
            $CIDRAM['IgnoreData'] = preg_replace("~\nIgnore " . $_POST['SectionName'] . "\n~", "\n", $CIDRAM['IgnoreData']);
            $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'ignore.dat', 'wb');
            fwrite($CIDRAM['Handle'], $CIDRAM['IgnoreData']);
            fclose($CIDRAM['Handle']);
        } elseif ($_POST['Action'] === 'ignore' && !preg_match("~\nIgnore " . $_POST['SectionName'] . "\n~", $CIDRAM['IgnoreData'])) {
            if (strpos($CIDRAM['IgnoreData'], "\n# End front-end generated ignore rules.") === false) {
                $CIDRAM['IgnoreData'] .= "\n# Begin front-end generated ignore rules.\n# End front-end generated ignore rules.\n";
            }
            $CIDRAM['IgnoreData'] = substr($CIDRAM['IgnoreData'], 0, strrpos(
                $CIDRAM['IgnoreData'],
                "# End front-end generated ignore rules.\n"
            )) . "Ignore " . $_POST['SectionName'] . "\n" . substr($CIDRAM['IgnoreData'], strrpos(
                $CIDRAM['IgnoreData'],
                "# End front-end generated ignore rules.\n"
            ));
            $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'ignore.dat', 'wb');
            fwrite($CIDRAM['Handle'], $CIDRAM['IgnoreData']);
            fclose($CIDRAM['Handle']);
        }

        /** Cleanup. */
        unset($CIDRAM['Handle'], $CIDRAM['IgnoreData']);

    }

}

/** Range Tables. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'range' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Page initial prepwork. */
    $CIDRAM['InitialPrepwork']($CIDRAM['L10N']->getString('link_range'), $CIDRAM['L10N']->getString('tip_range'));

    /** Append number localisation JS. */
    $CIDRAM['FE']['JS'] .= $CIDRAM['Number_L10N_JS']() . "\n";

    $CIDRAM['FE']['bNav'] = $CIDRAM['L10N']->getString('bNav_home_logout');

    /** Add flags CSS. */
    if ($CIDRAM['FE']['Flags'] = file_exists($CIDRAM['Vault'] . 'fe_assets/flags.css')) {
        $CIDRAM['FE']['OtherHead'] .= "\n  <link rel=\"stylesheet\" type=\"text/css\" href=\"?cidram-page=flags\" />";
    }

    /** Template for range rows. */
    $CIDRAM['FE']['RangeRow'] = $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_range_row.html'));

    /** Process signature files and fetch returned JavaScript stuff. */
    $CIDRAM['FE']['JSFOOT'] = $CIDRAM['RangeTablesHandler'](
        array_unique(explode(',', $CIDRAM['Config']['signatures']['ipv4'])),
        array_unique(explode(',', $CIDRAM['Config']['signatures']['ipv6']))
    );

    /** Calculate and append page load time, and append totals. */
    $CIDRAM['FE']['ProcTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
    $CIDRAM['FE']['ProcTime'] = '<div class="s">' . sprintf(
        $CIDRAM['L10N']->getPlural($CIDRAM['FE']['ProcTime'], 'state_loadtime'),
        $CIDRAM['NumberFormatter']->format($CIDRAM['FE']['ProcTime'], 3)
    ) . '</div>';

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['L10N']->Data + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_range.html'))
    );

    /** Send output. */
    echo $CIDRAM['SendOutput']();

}

/** IP Aggregator. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'ip-aggregator' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Page initial prepwork. */
    $CIDRAM['InitialPrepwork']($CIDRAM['L10N']->getString('link_ip_aggregator'), $CIDRAM['L10N']->getString('tip_ip_aggregator'), false);

    $CIDRAM['FE']['bNav'] = $CIDRAM['L10N']->getString('bNav_home_logout');

    /** Output format. */
    $CIDRAM['OutputFormat'] = (isset($_POST['format']) && $_POST['format'] === 'Netmask') ? 1 : 0;

    /** Whether to preserve tags and comments. */
    $CIDRAM['Preserve'] = (isset($_POST['preserve']) && $_POST['preserve'] === 'on') ? 1 : 0;

    /** Output format menu. */
    $CIDRAM['FE']['OutputFormat'] = sprintf(
        '%1$sCIDR" value="CIDR"%2$s%6$sformatCIDR">%3$s</label><br />%1$sNetmask" value="Netmask"%4$s%6$sformatNetmask">%5$s</label><br /><input type="checkbox" class="auto" name="preserve" id="preserve"%7$s%6$spreserve">%8$s</label>',
        '<input type="radio" class="auto" name="format" id="format',
        $CIDRAM['OutputFormat'] !== 1 ? ' checked' : '',
        $CIDRAM['L10N']->getString('field_cidr'),
        $CIDRAM['OutputFormat'] === 1 ? ' checked' : '',
        $CIDRAM['L10N']->getString('field_netmask'),
        ' /><label for="',
        $CIDRAM['Preserve'] === 1 ? ' checked' : '',
        $CIDRAM['L10N']->getString('field_preserve')
    );

    /** Data was submitted for aggregation. */
    if (!empty($_POST['input'])) {
        $CIDRAM['FE']['input'] = str_replace("\r", '', trim($_POST['input']));
        $CIDRAM['Aggregator'] = new \CIDRAM\Aggregator\Aggregator($CIDRAM, $CIDRAM['OutputFormat']);
        if ($CIDRAM['Preserve']) {
            $CIDRAM['NetResults'] = ['In' => 0, 'Rejected' => 0, 'Accepted' => 0, 'Merged' => 0, 'Out' => 0];
            $CIDRAM['StrObject'] = new \Maikuolan\Common\ComplexStringHandler(
                "\n" . $CIDRAM['FE']['input'] . "\n",
                '~(?<=\n)(?:\n|Expires\: \d{4}\.\d\d\.\d\d|Origin\: [A-Z]{2}|(?:\#|Tag\: |Defers to\: )[^\n]+)+\n~',
                function ($Data) use (&$CIDRAM) {
                    if (!$Data = trim($Data)) {
                        return '';
                    }
                    $CIDRAM['Results'] = ['In' => 0, 'Rejected' => 0, 'Accepted' => 0, 'Merged' => 0, 'Out' => 0];
                    $Data = $CIDRAM['Aggregator']->aggregate($Data);
                    $CIDRAM['NetResults']['In'] += $CIDRAM['Results']['In'];
                    $CIDRAM['NetResults']['Rejected'] += $CIDRAM['Results']['Rejected'];
                    $CIDRAM['NetResults']['Accepted'] += $CIDRAM['Results']['Accepted'];
                    $CIDRAM['NetResults']['Merged'] += $CIDRAM['Results']['Merged'];
                    $CIDRAM['NetResults']['Out'] += $CIDRAM['Results']['Out'];
                    return $Data;
                }
            );
            $CIDRAM['StrObject']->iterateClosure(function ($Data) {
                return "\n" . $Data;
            }, true);
            $CIDRAM['FE']['output'] = trim($CIDRAM['StrObject']->recompile());
            $CIDRAM['Results'] = $CIDRAM['NetResults'];
            unset($CIDRAM['StrObject'], $CIDRAM['NetResults']);
        } else {
            $CIDRAM['Results'] = ['In' => 0, 'Rejected' => 0, 'Accepted' => 0, 'Merged' => 0, 'Out' => 0];
            $CIDRAM['FE']['output'] = $CIDRAM['Aggregator']->aggregate($CIDRAM['FE']['input']);
        }
        unset($CIDRAM['Aggregator']);
        $CIDRAM['FE']['ResultLine'] = sprintf(
            $CIDRAM['L10N']->getString('label_results'),
            '<span class="txtRd">' . $CIDRAM['NumberFormatter']->format($CIDRAM['Results']['In']) . '</span>',
            '<span class="txtRd">' . $CIDRAM['NumberFormatter']->format($CIDRAM['Results']['Rejected']) . '</span>',
            '<span class="txtRd">' . $CIDRAM['NumberFormatter']->format($CIDRAM['Results']['Accepted']) . '</span>',
            '<span class="txtRd">' . $CIDRAM['NumberFormatter']->format($CIDRAM['Results']['Merged']) . '</span>',
            '<span class="txtRd">' . $CIDRAM['NumberFormatter']->format($CIDRAM['Results']['Out']) . '</span>'
        );
    } else {
        $CIDRAM['FE']['output'] = $CIDRAM['FE']['input'] = '';
    }

    /** Calculate page load time (useful for debugging). */
    $CIDRAM['FE']['ProcessTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
    $CIDRAM['FE']['state_msg'] .= sprintf(
        $CIDRAM['L10N']->getPlural($CIDRAM['FE']['ProcessTime'], 'state_loadtime'),
        $CIDRAM['NumberFormatter']->format($CIDRAM['FE']['ProcessTime'], 3)
    );

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['L10N']->Data + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_ip_aggregator.html'))
    );

    /** Strip output row if input doesn't exist. */
    if ($CIDRAM['FE']['input']) {
        $CIDRAM['FE']['FE_Content'] = str_replace(['<!-- Output Begin -->', '<!-- Output End -->'], '', $CIDRAM['FE']['FE_Content']);
    } else {
        $CIDRAM['FE']['FE_Content'] =
            substr($CIDRAM['FE']['FE_Content'], 0, strpos($CIDRAM['FE']['FE_Content'], '<!-- Output Begin -->')) .
            substr($CIDRAM['FE']['FE_Content'], strpos($CIDRAM['FE']['FE_Content'], '<!-- Output End -->') + 19);
    }

    /** Send output. */
    echo $CIDRAM['SendOutput']();

}

/** IP Test. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'ip-test' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Page initial prepwork. */
    $CIDRAM['InitialPrepwork']($CIDRAM['L10N']->getString('link_ip_test'), $CIDRAM['L10N']->getString('tip_ip_test'), false);

    $CIDRAM['FE']['bNav'] = $CIDRAM['L10N']->getString('bNav_home_logout');

    /** Add flags CSS. */
    if ($CIDRAM['FE']['Flags'] = file_exists($CIDRAM['Vault'] . 'fe_assets/flags.css')) {
        $CIDRAM['FE']['OtherHead'] .= "\n  <link rel=\"stylesheet\" type=\"text/css\" href=\"?cidram-page=flags\" />";
    }

    /** Template for result rows. */
    $CIDRAM['FE']['IPTestRow'] = $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_ip_test_row.html'));

    /** Initialise results data. */
    $CIDRAM['FE']['IPTestResults'] = '';

    /** Module switch for SimulateBlockEvent closure. */
    $CIDRAM['ModuleSwitch'] = !empty($_POST['ModuleSwitch']);

    /** Auxiliary switch for SimulateBlockEvent closure. */
    $CIDRAM['AuxSwitch'] = !empty($_POST['AuxSwitch']);

    /** Verification switch for SimulateBlockEvent closure. */
    $CIDRAM['VerificationSwitch'] = !empty($_POST['VerificationSwitch']);

    /** Module switch for HTML. */
    $CIDRAM['FE']['ModuleSwitch'] = $CIDRAM['ModuleSwitch'] ? ' checked' : '';

    /** Auxiliary switch for HTML. */
    $CIDRAM['FE']['AuxSwitch'] = $CIDRAM['AuxSwitch'] ? ' checked' : '';

    /** Verification switch for HTML. */
    $CIDRAM['FE']['VerificationSwitch'] = $CIDRAM['VerificationSwitch'] ? ' checked' : '';

    /** Fetch custom fields if specified. */
    foreach (['custom-query', 'custom-referrer', 'custom-ua'] as $CIDRAM['ThisField']) {
        $CIDRAM['FE'][$CIDRAM['ThisField']] = !empty($_POST[$CIDRAM['ThisField']]) ? $_POST[$CIDRAM['ThisField']] : '';
    }
    unset($CIDRAM['ThisField']);

    /** IPs were submitted for testing. */
    if (isset($_POST['ip-addr'])) {
        $CIDRAM['FE']['ip-addr'] = $_POST['ip-addr'];
        $_POST['ip-addr'] = array_unique(array_map(function ($IP) {
            return preg_replace('~[^\da-f:./]~i', '', $IP);
        }, explode("\n", $_POST['ip-addr'])));
        natsort($_POST['ip-addr']);
        $CIDRAM['ThisIP'] = [];
        foreach ($_POST['ip-addr'] as $CIDRAM['ThisIP']['IPAddress']) {
            if (!$CIDRAM['ThisIP']['IPAddress']) {
                continue;
            }
            $CIDRAM['SimulateBlockEvent']($CIDRAM['ThisIP']['IPAddress'], $CIDRAM['ModuleSwitch'], $CIDRAM['AuxSwitch'], $CIDRAM['VerificationSwitch']);
            if (
                $CIDRAM['Caught'] ||
                empty($CIDRAM['LastTestIP']) ||
                empty($CIDRAM['TestResults']) ||
                !empty($CIDRAM['ModuleErrors']) ||
                !empty($CIDRAM['AuxErrors'])
            ) {
                $CIDRAM['ThisIP']['YesNo'] = $CIDRAM['L10N']->getString('response_error');
                $CIDRAM['ThisIP']['StatClass'] = 'txtOe';
                if (!empty($CIDRAM['AuxErrors'])) {
                    $CIDRAM['ThisIP']['YesNo'] .= sprintf(
                        ' – auxiliary.yaml (%s)',
                        $CIDRAM['NumberFormatter']->format(count($CIDRAM['AuxErrors']))
                    );
                }
                if (!empty($CIDRAM['ModuleErrors'])) {
                    $CIDRAM['ModuleErrorCounts'] = [];
                    foreach ($CIDRAM['ModuleErrors'] as $CIDRAM['ModuleError']) {
                        if (isset($CIDRAM['ModuleErrorCounts'][$CIDRAM['ModuleError'][2]])) {
                            $CIDRAM['ModuleErrorCounts'][$CIDRAM['ModuleError'][2]]++;
                        } else {
                            $CIDRAM['ModuleErrorCounts'][$CIDRAM['ModuleError'][2]] = 1;
                        }
                    }
                    arsort($CIDRAM['ModuleErrorCounts']);
                    foreach ($CIDRAM['ModuleErrorCounts'] as $CIDRAM['ModuleName'] => $CIDRAM['ModuleError']) {
                        $CIDRAM['ThisIP']['YesNo'] .= sprintf(
                            ' – %s (%s)',
                            $CIDRAM['ModuleName'],
                            $CIDRAM['NumberFormatter']->format($CIDRAM['ModuleError'])
                        );
                    }
                    unset($CIDRAM['ModuleName'], $CIDRAM['ModuleError'], $CIDRAM['ModuleErrorCounts']);
                }
            } elseif ($CIDRAM['BlockInfo']['SignatureCount']) {
                $CIDRAM['ThisIP']['YesNo'] = $CIDRAM['L10N']->getString('response_yes') . ' – ' . $CIDRAM['BlockInfo']['WhyReason'];
                $CIDRAM['ThisIP']['StatClass'] = 'txtRd';
                if (
                    $CIDRAM['FE']['Flags'] &&
                    preg_match_all('~\[([A-Z]{2})\]~', $CIDRAM['ThisIP']['YesNo'], $CIDRAM['ThisIP']['Matches']) &&
                    !empty($CIDRAM['ThisIP']['Matches'][1])
                ) {
                    foreach ($CIDRAM['ThisIP']['Matches'][1] as $CIDRAM['ThisIP']['ThisMatch']) {
                        $CIDRAM['ThisIP']['YesNo'] = str_replace(
                            '[' . $CIDRAM['ThisIP']['ThisMatch'] . ']',
                            '<span class="flag ' . $CIDRAM['ThisIP']['ThisMatch'] . '"><span></span></span>',
                            $CIDRAM['ThisIP']['YesNo']
                        );
                    }
                }
            } else {
                $CIDRAM['ThisIP']['YesNo'] = $CIDRAM['L10N']->getString('response_no');
                $CIDRAM['ThisIP']['StatClass'] = 'txtGn';
            }
            $CIDRAM['ThisIP']['NegateFlags'] = '';
            if ($CIDRAM['Flag Don\'t Log']) {
                $CIDRAM['ThisIP']['NegateFlags'] .= '📓';
            }
            if ($CIDRAM['ThisIP']['NegateFlags']) {
                $CIDRAM['ThisIP']['YesNo'] .= ' – 🚫' . $CIDRAM['ThisIP']['NegateFlags'];
            }
            $CIDRAM['FE']['IPTestResults'] .= $CIDRAM['ParseVars'](
                $CIDRAM['L10N']->Data + $CIDRAM['ThisIP'],
                $CIDRAM['FE']['IPTestRow']
            );
        }
        unset($CIDRAM['ThisIP']);
    } else {
        $CIDRAM['FE']['ip-addr'] = '';
    }

    /** Calculate page load time (useful for debugging). */
    $CIDRAM['FE']['ProcessTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
    $CIDRAM['FE']['state_msg'] .= sprintf(
        $CIDRAM['L10N']->getPlural($CIDRAM['FE']['ProcessTime'], 'state_loadtime'),
        $CIDRAM['NumberFormatter']->format($CIDRAM['FE']['ProcessTime'], 3)
    );

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['L10N']->Data + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_ip_test.html'))
    );

    /** Send output. */
    echo $CIDRAM['SendOutput']();

}

/** IP Tracking. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'ip-tracking' && $CIDRAM['FE']['Permissions'] === 1) {

    $CIDRAM['FE']['TrackingFilter'] = 'cidram-page=ip-tracking';
    $CIDRAM['FE']['TrackingFilterControls'] = '';
    $CIDRAM['StateModified'] = false;
    $CIDRAM['FilterSwitch'](
        ['tracking-blocked-already', 'tracking-aux', 'tracking-hide-banned-blocked'],
        isset($_POST['FilterSelector']) ? $_POST['FilterSelector'] : '',
        $CIDRAM['StateModified'],
        $CIDRAM['FE']['TrackingFilter'],
        $CIDRAM['FE']['TrackingFilterControls']
    );
    if ($CIDRAM['StateModified']) {
        header('Location: ?' . $CIDRAM['FE']['TrackingFilter']);
        die;
    }
    unset($CIDRAM['StateModified']);

    /** Temporarily mute signature files if "tracking-blocked-already" is false. */
    if (!$CIDRAM['FE']['tracking-blocked-already']) {
        $CIDRAM['TempMuted'] = [
            'IPv4' => $CIDRAM['Config']['signatures']['ipv4'],
            'IPv6' => $CIDRAM['Config']['signatures']['ipv6']
        ];
        $CIDRAM['Config']['signatures']['ipv4'] = '';
        $CIDRAM['Config']['signatures']['ipv6'] = '';
    }

    if (!$CIDRAM['FE']['ASYNC']) {

        /** Page initial prepwork. */
        $CIDRAM['InitialPrepwork']($CIDRAM['L10N']->getString('link_ip_tracking'), $CIDRAM['L10N']->getString('tip_ip_tracking'));

        $CIDRAM['FE']['bNav'] = $CIDRAM['L10N']->getString('bNav_home_logout');

        /** Template for result rows. */
        $CIDRAM['FE']['TrackingRow'] = $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_ip_tracking_row.html'));

    }

    /** Initialise variables. */
    $CIDRAM['FE']['TrackingData'] = '';
    $CIDRAM['FE']['TrackingCount'] = '';

    /** Generate confirm button. */
    $CIDRAM['FE']['Confirm-ClearAll'] = $CIDRAM['GenerateConfirm']($CIDRAM['L10N']->getString('field_clear_all'), 'trackForm');

    /** Clear/revoke IP tracking for an IP address. */
    if (isset($_POST['IPAddr'])) {
        $CIDRAM['Cleared'] = false;
        if ($_POST['IPAddr'] === '*') {
            $CIDRAM['Tracking'] = [];
            $CIDRAM['Cleared'] = true;
        } elseif (isset($CIDRAM['Tracking'][$_POST['IPAddr']])) {
            unset($CIDRAM['Tracking'][$_POST['IPAddr']]);
            $CIDRAM['Cleared'] = true;
        }
        if ($CIDRAM['Cleared']) {
            $CIDRAM['FE']['state_msg'] = $CIDRAM['L10N']->getString('response_tracking_cleared');
            $CIDRAM['Tracking-Modified'] = true;
        }
        unset($CIDRAM['Cleared']);
    }

    /** Count currently tracked IPs. */
    $CIDRAM['FE']['TrackingCount'] = count($CIDRAM['Tracking']);
    $CIDRAM['FE']['TrackingCount'] = sprintf(
        $CIDRAM['L10N']->getPlural($CIDRAM['FE']['TrackingCount'], 'state_tracking'),
        '<span class="txtRd">' . $CIDRAM['NumberFormatter']->format($CIDRAM['FE']['TrackingCount']) . '</span>'
    );

    if (!$CIDRAM['FE']['ASYNC']) {

        uasort($CIDRAM['Tracking'], function ($A, $B) {
            if (empty($A['Time']) || empty($B['Time']) || $A['Time'] === $B['Time']) {
                return 0;
            }
            return ($A['Time'] < $B['Time']) ? -1 : 1;
        });

        $CIDRAM['ThisTracking'] = [];
        foreach ($CIDRAM['Tracking'] as $CIDRAM['ThisTracking']['IPAddr'] => $CIDRAM['ThisTrackingArr']) {
            if (!isset($CIDRAM['ThisTrackingArr']['Time'], $CIDRAM['ThisTrackingArr']['Count'])) {
                continue;
            }

            /** Check whether normally blocked by signature files and/or auxiliary rules. */
            if ($CIDRAM['FE']['tracking-blocked-already'] || $CIDRAM['FE']['tracking-aux']) {
                $CIDRAM['SimulateBlockEvent']($CIDRAM['ThisTracking']['IPAddr'], false, $CIDRAM['FE']['tracking-aux'], false);
                $CIDRAM['ThisTracking']['Blocked'] = ($CIDRAM['Caught'] || $CIDRAM['BlockInfo']['SignatureCount']);
            } else {
                $CIDRAM['ThisTracking']['Blocked'] = false;
            }

            /** Hide banned/blocked IPs. */
            if ($CIDRAM['FE']['tracking-hide-banned-blocked'] && (
                $CIDRAM['ThisTracking']['Blocked'] || $CIDRAM['ThisTrackingArr']['Count'] >= $CIDRAM['Config']['signatures']['infraction_limit']
            )) {
                continue;
            }
            $CIDRAM['ThisTracking']['IPID'] = bin2hex($CIDRAM['ThisTracking']['IPAddr']);

            /** Set clearing option. */
            $CIDRAM['ThisTracking']['Options'] = (
                $CIDRAM['ThisTrackingArr']['Count'] > 0
            ) ?
                '<input type="button" class="auto" onclick="javascript:{window[\'IPAddr\']=\'' .
                $CIDRAM['ThisTracking']['IPAddr'] .
                '\';$(\'POST\',\'\',[\'IPAddr\'],function(){w(\'stateMsg\',\'' .
                $CIDRAM['L10N']->getString('state_loading') . '\')},function(e){w(\'stateMsg\',e);hideid(\'' .
                $CIDRAM['ThisTracking']['IPID'] . '\')},function(e){w(\'stateMsg\',e)})}" value="' .
                $CIDRAM['L10N']->getString('field_clear') . '" />'
            : '';
            $CIDRAM['ThisTracking']['Expiry'] = $CIDRAM['TimeFormat'](
                $CIDRAM['ThisTrackingArr']['Time'],
                $CIDRAM['Config']['general']['timeFormat']
            );

            if ($CIDRAM['ThisTrackingArr']['Count'] >= $CIDRAM['Config']['signatures']['infraction_limit']) {
                $CIDRAM['ThisTracking']['StatClass'] = 'txtRd';
                $CIDRAM['ThisTracking']['Status'] = $CIDRAM['L10N']->getString('field_banned');
            } elseif ($CIDRAM['ThisTrackingArr']['Count'] >= ($CIDRAM['Config']['signatures']['infraction_limit'] / 2)) {
                $CIDRAM['ThisTracking']['StatClass'] = 'txtOe';
                $CIDRAM['ThisTracking']['Status'] = $CIDRAM['L10N']->getString('field_tracking');
            } else {
                $CIDRAM['ThisTracking']['StatClass'] = 's';
                $CIDRAM['ThisTracking']['Status'] = $CIDRAM['L10N']->getString('field_tracking');
            }
            if ($CIDRAM['ThisTracking']['Blocked']) {
                $CIDRAM['ThisTracking']['StatClass'] = 'txtRd';
                $CIDRAM['ThisTracking']['Status'] .= '/' . $CIDRAM['L10N']->getString('field_blocked');
            }
            $CIDRAM['ThisTracking']['Status'] .= ' – ' . $CIDRAM['NumberFormatter']->format($CIDRAM['ThisTrackingArr']['Count'], 0);
            $CIDRAM['ThisTracking']['TrackingFilter'] = $CIDRAM['FE']['TrackingFilter'];
            $CIDRAM['FE']['TrackingData'] .= $CIDRAM['ParseVars'](
                $CIDRAM['L10N']->Data + $CIDRAM['ThisTracking'],
                $CIDRAM['FE']['TrackingRow']
            );
        }
        unset($CIDRAM['ThisTrackingArr'], $CIDRAM['ThisTracking']);

    }

    /** Restore muted values. */
    if (isset($CIDRAM['TempMuted']['IPv4'], $CIDRAM['TempMuted']['IPv6'])) {
        $CIDRAM['Config']['signatures']['ipv4'] = $CIDRAM['TempMuted']['IPv4'];
        $CIDRAM['Config']['signatures']['ipv6'] = $CIDRAM['TempMuted']['IPv6'];
        unset($CIDRAM['TempMuted']);
    }

    /** Fix status display. */
    if ($CIDRAM['FE']['state_msg']) {
        $CIDRAM['FE']['state_msg'] .= '<br />';
    }

    if ($CIDRAM['FE']['TrackingCount']) {
        $CIDRAM['FE']['TrackingCount'] .= ' ';
    }

    /** Calculate page load time (useful for debugging). */
    $CIDRAM['FE']['ProcessTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
    $CIDRAM['FE']['TrackingCount'] .= sprintf(
        $CIDRAM['L10N']->getPlural($CIDRAM['FE']['ProcessTime'], 'state_loadtime'),
        $CIDRAM['NumberFormatter']->format($CIDRAM['FE']['ProcessTime'], 3)
    );

    if ($CIDRAM['FE']['ASYNC']) {
        /** Send output (async). */
        echo $CIDRAM['FE']['state_msg'] . $CIDRAM['FE']['TrackingCount'];
    } else {

        /** Parse output. */
        $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
            $CIDRAM['L10N']->Data + $CIDRAM['FE'],
            $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_ip_tracking.html'))
        );

        /** Send output. */
        echo $CIDRAM['SendOutput']();

    }

}

/** CIDR Calculator. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'cidr-calc' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Page initial prepwork. */
    $CIDRAM['InitialPrepwork']($CIDRAM['L10N']->getString('link_cidr_calc'), $CIDRAM['L10N']->getString('tip_cidr_calc'), false);

    $CIDRAM['FE']['bNav'] = $CIDRAM['L10N']->getString('bNav_home_logout');

    /** Template for result rows. */
    $CIDRAM['FE']['CalcRow'] = $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_cidr_calc_row.html'));

    /** Initialise results data. */
    $CIDRAM['FE']['Ranges'] = '';

    /** IPs were submitted for testing. */
    if (isset($_POST['cidr'])) {
        $CIDRAM['FE']['cidr'] = $_POST['cidr'];
        if ($_POST['cidr'] = preg_replace('~[^\da-f:./]~i', '', $_POST['cidr'])) {
            if (!$CIDRAM['CIDRs'] = $CIDRAM['ExpandIPv4']($_POST['cidr'])) {
                $CIDRAM['CIDRs'] = $CIDRAM['ExpandIPv6']($_POST['cidr']);
            }
        }
    } else {
        $CIDRAM['FE']['cidr'] = '';
    }

    /** Process CIDRs. */
    if (!empty($CIDRAM['CIDRs'])) {
        $CIDRAM['Factors'] = count($CIDRAM['CIDRs']);
        array_walk($CIDRAM['CIDRs'], function ($CIDR, $Key) use (&$CIDRAM) {
            $First = substr($CIDR, 0, strlen($CIDR) - strlen($Key + 1) - 1);
            if ($CIDRAM['Factors'] === 32) {
                $Last = $CIDRAM['IPv4GetLast']($First, $Key + 1);
            } elseif ($CIDRAM['Factors'] === 128) {
                $Last = $CIDRAM['IPv6GetLast']($First, $Key + 1);
            } else {
                $Last = $CIDRAM['L10N']->getString('response_error');
            }
            $Arr = ['CIDR' => $CIDR, 'Range' => $First . ' – ' . $Last];
            $CIDRAM['FE']['Ranges'] .= $CIDRAM['ParseVars']($Arr, $CIDRAM['FE']['CalcRow']);
        });
    }

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['L10N']->Data + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_cidr_calc.html'))
    );

    /** Send output. */
    echo $CIDRAM['SendOutput']();

}

/** Statistics. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'statistics' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Page initial prepwork. */
    $CIDRAM['InitialPrepwork']($CIDRAM['L10N']->getString('link_statistics'), $CIDRAM['L10N']->getString('tip_statistics'), false);

    /** Display how to enable statistics if currently disabled. */
    if (!$CIDRAM['Config']['general']['statistics']) {
        $CIDRAM['FE']['state_msg'] .= '<span class="txtRd">' . $CIDRAM['L10N']->getString('tip_statistics_disabled') . '</span><br />';
    }

    /** Generate confirm button. */
    $CIDRAM['FE']['Confirm-ClearAll'] = $CIDRAM['GenerateConfirm']($CIDRAM['L10N']->getString('field_clear_all'), 'statForm');

    /** Clear statistics. */
    if (!empty($_POST['ClearStats'])) {
        $CIDRAM['Cache']->deleteEntry('Statistics');
        $CIDRAM['FE']['state_msg'] .= $CIDRAM['L10N']->getString('response_statistics_cleared') . '<br />';
    } elseif ($CIDRAM['Config']['general']['statistics']) {
        /** Initialise statistics. */
        $CIDRAM['InitialiseCacheSection']('Statistics');
    }

    /** Statistics have been counted since... */
    $CIDRAM['FE']['Other-Since'] = '<span class="s">' . (
        empty($CIDRAM['Statistics']['Other-Since']) ? '-' : $CIDRAM['TimeFormat'](
            $CIDRAM['Statistics']['Other-Since'],
            $CIDRAM['Config']['general']['timeFormat']
        )
    ) . '</span>';

    /** Fetch and process various statistics. */
    foreach ([
        ['Blocked-IPv4', 'Blocked-Total'],
        ['Blocked-IPv6', 'Blocked-Total'],
        ['Blocked-Other', 'Blocked-Total'],
        ['Banned-IPv4', 'Banned-Total'],
        ['Banned-IPv6', 'Banned-Total'],
        ['reCAPTCHA-Failed', 'reCAPTCHA-Total'],
        ['reCAPTCHA-Passed', 'reCAPTCHA-Total']
    ] as $CIDRAM['TheseStats']) {
        $CIDRAM['FE'][$CIDRAM['TheseStats'][0]] = '<span class="s">' . $CIDRAM['NumberFormatter']->format(
            empty($CIDRAM['Statistics'][$CIDRAM['TheseStats'][0]]) ? 0 : $CIDRAM['Statistics'][$CIDRAM['TheseStats'][0]]
        ) . '</span>';
        if (!isset($CIDRAM['FE'][$CIDRAM['TheseStats'][1]])) {
            $CIDRAM['FE'][$CIDRAM['TheseStats'][1]] = 0;
        }
        $CIDRAM['FE'][$CIDRAM['TheseStats'][1]] += empty(
            $CIDRAM['Statistics'][$CIDRAM['TheseStats'][0]]
        ) ? 0 : $CIDRAM['Statistics'][$CIDRAM['TheseStats'][0]];
    }

    /** Fetch and process totals. */
    foreach (['Blocked-Total', 'Banned-Total', 'reCAPTCHA-Total'] as $CIDRAM['TheseStats']) {
        $CIDRAM['FE'][$CIDRAM['TheseStats']] = '<span class="s">' . $CIDRAM['NumberFormatter']->format(
            $CIDRAM['FE'][$CIDRAM['TheseStats']]
        ) . '</span>';
    }

    /** Active signature files. */
    foreach ([
        ['ipv4', 'Other-ActiveIPv4'],
        ['ipv6', 'Other-ActiveIPv6'],
        ['modules', 'Other-ActiveModules']
    ] as $CIDRAM['TheseStats']) {
        if (empty($CIDRAM['Config']['signatures'][$CIDRAM['TheseStats'][0]])) {
            $CIDRAM['FE'][$CIDRAM['TheseStats'][1]] = '<span class="txtRd">' . $CIDRAM['NumberFormatter']->format(0) . '</span>';
        } else {
            $CIDRAM['FE'][$CIDRAM['TheseStats'][1]] = 0;
            $CIDRAM['StatWorking'] = explode(',', $CIDRAM['Config']['signatures'][$CIDRAM['TheseStats'][0]]);
            array_walk($CIDRAM['StatWorking'], function ($SigFile) use (&$CIDRAM) {
                if (!empty($SigFile) && is_readable($CIDRAM['Vault'] . $SigFile)) {
                    $CIDRAM['FE'][$CIDRAM['TheseStats'][1]]++;
                }
            });
            $CIDRAM['StatColour'] = $CIDRAM['FE'][$CIDRAM['TheseStats'][1]] ? 'txtGn' : 'txtRd';
            $CIDRAM['FE'][$CIDRAM['TheseStats'][1]] = '<span class="' . $CIDRAM['StatColour'] . '">' . $CIDRAM['NumberFormatter']->format(
                $CIDRAM['FE'][$CIDRAM['TheseStats'][1]]
            ) . '</span>';
        }
    }

    $CIDRAM['FE']['bNav'] = $CIDRAM['L10N']->getString('bNav_home_logout');

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['L10N']->Data + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_statistics.html'))
    );

    /** Send output. */
    echo $CIDRAM['SendOutput']();

    /** Cleanup. */
    unset($CIDRAM['StatColour'], $CIDRAM['StatWorking'], $CIDRAM['TheseStats']);

}

/** Auxiliary Rules. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'aux' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Attempt to parse the auxiliary rules file. */
    if (!isset($CIDRAM['AuxData'])) {
        $CIDRAM['AuxData'] = (new \Maikuolan\Common\YAML($CIDRAM['ReadFile']($CIDRAM['Vault'] . 'auxiliary.yaml')))->Data;
    }

    /** Create new auxiliary rule. */
    if (isset($_POST['ruleName'], $_POST['conSourceType'], $_POST['conIfOrNot'], $_POST['conSourceValue'], $_POST['act'], $_POST['mtd'], $_POST['logic'])) {

        /** Construct new rule array. */
        $CIDRAM['AuxData'][$_POST['ruleName']] = [];

        /** Construct new rule method. */
        if ($_POST['mtd'] === 'mtdReg') {
            $CIDRAM['AuxData'][$_POST['ruleName']]['Method'] = 'RegEx';
        } elseif ($_POST['mtd'] === 'mtdWin') {
            $CIDRAM['AuxData'][$_POST['ruleName']]['Method'] = 'WinEx';
        }

        /** Construct new rule matching logic. */
        $CIDRAM['AuxData'][$_POST['ruleName']]['Logic'] = $_POST['logic'];

        /** Construct new rule block reason. */
        if (!empty($_POST['ruleReason'])) {
            $CIDRAM['AuxData'][$_POST['ruleName']]['Reason'] = $_POST['ruleReason'];
        }

        /** Possible actions (other than block). */
        $CIDRAM['Actions'] = [
            'actWhl' => 'Whitelist',
            'actGrl' => 'Greylist',
            'actByp' => 'Bypass',
            'actLog' => 'Don\'t log'
        ];

        /** Determine appropriate action for new rule. */
        $CIDRAM['Action'] = isset($CIDRAM['Actions'][$_POST['act']]) ? $CIDRAM['Actions'][$_POST['act']] : 'Block';

        /** Construct new rule action array. */
        $CIDRAM['AuxData'][$_POST['ruleName']][$CIDRAM['Action']] = ['If matches' => [], 'But not if matches' => []];

        /** Determine number of new rule conditions to construct. */
        $CIDRAM['AuxConditions'] = count($_POST['conSourceType']);

        /** Construct new rule conditions. */
        for ($CIDRAM['Iteration'] = 0; $CIDRAM['Iteration'] < $CIDRAM['AuxConditions']; $CIDRAM['Iteration']++) {

            /** Skip if something went wrong during form submission, or if the fields are empty. */
            if (
                empty($_POST['conSourceType'][$CIDRAM['Iteration']]) ||
                empty($_POST['conIfOrNot'][$CIDRAM['Iteration']]) ||
                empty($_POST['conSourceValue'][$CIDRAM['Iteration']])
            ) {
                continue;
            }

            /** Where to construct into. */
            $CIDRAM['ConstructInto'] = (
                $_POST['conIfOrNot'][$CIDRAM['Iteration']] === 'If'
            ) ? 'If matches' : 'But not if matches';

            /** Set source sub in rule if it doesn't already exist. */
            if (!isset($CIDRAM['AuxData'][$_POST['ruleName']][$CIDRAM['Action']][$CIDRAM['ConstructInto']][
                $_POST['conSourceType'][$CIDRAM['Iteration']]
            ])) {
                $CIDRAM['AuxData'][$_POST['ruleName']][$CIDRAM['Action']][$CIDRAM['ConstructInto']][
                    $_POST['conSourceType'][$CIDRAM['Iteration']]
                ] = [];
            }

            /** Construct expected condition values. */
            $CIDRAM['AuxData'][$_POST['ruleName']][$CIDRAM['Action']][$CIDRAM['ConstructInto']][
                $_POST['conSourceType'][$CIDRAM['Iteration']]
            ][] = $_POST['conSourceValue'][$CIDRAM['Iteration']];

        }

        /** Remove possible empty array. */
        if (empty($CIDRAM['AuxData'][$_POST['ruleName']][$CIDRAM['Action']]['If matches'])) {
            unset($CIDRAM['AuxData'][$_POST['ruleName']][$CIDRAM['Action']]['If matches']);
        }

        /** Remove possible empty array. */
        if (empty($CIDRAM['AuxData'][$_POST['ruleName']][$CIDRAM['Action']]['But not if matches'])) {
            unset($CIDRAM['AuxData'][$_POST['ruleName']][$CIDRAM['Action']]['But not if matches']);
        }

        /** Reconstruct and update auxiliary rules data. */
        if ($CIDRAM['NewAuxData'] = $CIDRAM['YAML']->reconstruct($CIDRAM['AuxData'])) {
            $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'auxiliary.yaml', 'wb');
            fwrite($CIDRAM['Handle'], $CIDRAM['NewAuxData']);
            fclose($CIDRAM['Handle']);
        }

        /** Cleanup. */
        unset($CIDRAM['NewAuxData'], $CIDRAM['ConstructInto'], $CIDRAM['Iteration'], $CIDRAM['AuxConditions'], $CIDRAM['Action']);

        /** Update state message. */
        $CIDRAM['FE']['state_msg'] = sprintf(
            $CIDRAM['L10N']->getString('response_aux_rule_created_successfully'),
            $_POST['ruleName']
        );

    }

    /** Prepare data for display. */
    if (!$CIDRAM['FE']['ASYNC']) {

        /** Page initial prepwork. */
        $CIDRAM['InitialPrepwork']($CIDRAM['L10N']->getString('link_aux'), $CIDRAM['L10N']->getString('tip_aux'));

        /** Append to async globals for deleting rules. */
        $CIDRAM['FE']['JS'] .= "function delRule(a,i){window.auxD=a,$('POST','',['auxD'],null,function(a){null!=i&&hide(i);w('stateMsg',a)})}";

        /** Append to async globals for moving rules to the top of the list. */
        $CIDRAM['FE']['JS'] .= "function moveToTop(a,i){window.auxT=a,$('POST','',['auxT'],null,function(a){window.location.reload()})}";

        /** Append to async globals for moving rules to the bottom of the list. */
        $CIDRAM['FE']['JS'] .= "function moveToBottom(a,i){window.auxB=a,$('POST','',['auxB'],null,function(a){window.location.reload()})}";

        /** Add new condition onclick event. */
        $CIDRAM['FE']['JS'] .= sprintf(
            "var createNewRule=function(){var doSub=!0;%1\$s('ruleName').value||(doSu" .
            "b=!1,%1\$s('ruleName%2\$snAux 0.5s ease 0s 1 normal',setTimeout(function" .
            "(){%1\$s('ruleName%2\$s'},500)),%1\$s('ruleReason').value||'actBlk'!=%1" .
            "\$s('act').value||(doSub=!1,%1\$s('ruleReason%2\$snAux 0.5s ease 0s 1 no" .
            "rmal',setTimeout(function(){%1\$s('ruleReason%2\$s'},500)),doSub&&%1\$s(" .
            "'auxForm').submit()};",
            'document.getElementById',
            "Warn').style.animation='"
        );

        /** Append other auxiliary rules JS. */
        $CIDRAM['FE']['JS'] .=
            'var conIn=1,conAdd=\'<select name="conSourceType[]" class="auto">{conSou' .
            'rces}</select> <select name="conIfOrNot[]" class="auto"><option value="I' .
            'f">=</option><option value="Not">≠</option></select> <input type="text" ' .
            'name="conSourceValue[]" placeholder="' .
            $CIDRAM['L10N']->getString('tip_condition_placeholder') . '" style="width:400px" />' .
            "',addCondition=function(){var conId='condition'+conIn,t=document.createE" .
            "lement('div');t.setAttribute('id',conId),t.setAttribute('style','opacity" .
            ":0.0;animation:xAux 2.0s ease 0s 1 normal'),document.getElementById('con" .
            "ditions').appendChild(t),document.getElementById(conId).innerHTML=conAdd" .
            ",setTimeout(function(){document.getElementById(conId).style.opacity='1.0" .
            "'},1999),conIn++};";

        $CIDRAM['FE']['bNav'] = $CIDRAM['L10N']->getString('bNav_home_logout');

        /** Populate methods. */
        $CIDRAM['FE']['optMtdStr'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_method'), $CIDRAM['L10N']->getString('label_aux_mtdStr'));
        $CIDRAM['FE']['optMtdReg'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_method'), $CIDRAM['L10N']->getString('label_aux_mtdReg'));
        $CIDRAM['FE']['optMtdWin'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_method'), $CIDRAM['L10N']->getString('label_aux_mtdWin'));

        /** Populate actions. */
        $CIDRAM['FE']['optActWhl'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_action'), $CIDRAM['L10N']->getString('label_aux_actWhl'));
        $CIDRAM['FE']['optActGrl'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_action'), $CIDRAM['L10N']->getString('label_aux_actGrl'));
        $CIDRAM['FE']['optActBlk'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_action'), $CIDRAM['L10N']->getString('label_aux_actBlk'));
        $CIDRAM['FE']['optActByp'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_action'), $CIDRAM['L10N']->getString('label_aux_actByp'));
        $CIDRAM['FE']['optActLog'] = sprintf($CIDRAM['L10N']->getString('label_aux_menu_action'), $CIDRAM['L10N']->getString('label_aux_actLog'));

        /** Fetch sources L10N fields. */
        $CIDRAM['SourcesL10N'] = [
            'IPAddr' => $CIDRAM['L10N']->getString('field_ipaddr'),
            'IPAddrResolved' => $CIDRAM['L10N']->getString('field_ipaddr_resolved'),
            'Query' => $CIDRAM['L10N']->getString('field_query'),
            'Referrer' => $CIDRAM['L10N']->getString('field_referrer'),
            'UA' => $CIDRAM['L10N']->getString('field_ua'),
            'UALC' => $CIDRAM['L10N']->getString('field_ualc'),
            'SignatureCount' => $CIDRAM['L10N']->getString('field_sigcount'),
            'Signatures' => $CIDRAM['L10N']->getString('field_sigref'),
            'WhyReason' => $CIDRAM['L10N']->getString('field_whyreason'),
            'ReasonMessage' => $CIDRAM['L10N']->getString('field_reasonmessage'),
            'rURI' => $CIDRAM['L10N']->getString('field_rURI'),
            'Request_Method' => $CIDRAM['L10N']->getString('field_Request_Method'),
            'Hostname' => $CIDRAM['L10N']->getString('field_hostname')
        ];

        /** Populate sources. */
        $CIDRAM['FE']['conSources'] = $CIDRAM['GenerateOptions']($CIDRAM['SourcesL10N'], '~(?: | )?(?:：|:) ?$~');

        /** Process auxiliary rules. */
        $CIDRAM['FE']['Data'] = file_exists($CIDRAM['Vault'] . 'auxiliary.yaml') ?
            $CIDRAM['AuxGenerateFEData']() :
            '        <tr><td class="h4f" colspan="2"><div class="s">' . $CIDRAM['L10N']->getString('response_aux_none') . "</div></td></tr>";

        /** Cleanup. */
        unset($CIDRAM['SourcesL10N']);

        /** Parse output. */
        $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
            $CIDRAM['L10N']->Data + $CIDRAM['FE'],
            $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_aux.html'))
        );

        /** Send output. */
        echo $CIDRAM['SendOutput']();

    } else {

        /** Delete an auxiliary rule. */
        if (isset($_POST['auxD'], $CIDRAM['AuxData'][$_POST['auxD']])) {

            /** Destroy the target rule data array. */
            unset($CIDRAM['AuxData'][$_POST['auxD']]);

            /** Reconstruct and update auxiliary rules data. */
            if (($CIDRAM['NewAuxData'] = $CIDRAM['YAML']->reconstruct($CIDRAM['AuxData'])) && strlen($CIDRAM['NewAuxData']) > 2) {
                $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'auxiliary.yaml', 'wb');
                fwrite($CIDRAM['Handle'], $CIDRAM['NewAuxData']);
                fclose($CIDRAM['Handle']);
            } elseif (file_exists($CIDRAM['Vault'] . 'auxiliary.yaml')) {
                /** If auxiliary rules data reconstruction fails, or if it's empty, delete the file. */
                unlink($CIDRAM['Vault'] . 'auxiliary.yaml');
            }

            /** Confirm successful deletion. */
            echo sprintf($CIDRAM['L10N']->getString('response_aux_rule_deleted_successfully'), $_POST['auxD']);

        }

        /** Move an auxiliary rule to the top of the list. */
        elseif (isset($_POST['auxT'], $CIDRAM['AuxData'][$_POST['auxT']])) {

            $CIDRAM['Split'] = [$_POST['auxT'] => $CIDRAM['AuxData'][$_POST['auxT']]];
            unset($CIDRAM['AuxData'][$_POST['auxT']]);
            $CIDRAM['AuxData'] = array_merge($CIDRAM['Split'], $CIDRAM['AuxData']);
            unset($CIDRAM['Split']);

            /** Reconstruct and update auxiliary rules data. */
            if (($CIDRAM['NewAuxData'] = $CIDRAM['YAML']->reconstruct($CIDRAM['AuxData'])) && strlen($CIDRAM['NewAuxData']) > 2) {
                $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'auxiliary.yaml', 'wb');
                fwrite($CIDRAM['Handle'], $CIDRAM['NewAuxData']);
                fclose($CIDRAM['Handle']);
            }

        }

        /** Move an auxiliary rule to the bottom of the list. */
        elseif (isset($_POST['auxB'], $CIDRAM['AuxData'][$_POST['auxB']])) {

            $CIDRAM['Split'] = [$_POST['auxB'] => $CIDRAM['AuxData'][$_POST['auxB']]];
            unset($CIDRAM['AuxData'][$_POST['auxB']]);
            $CIDRAM['AuxData'] = array_merge($CIDRAM['AuxData'], $CIDRAM['Split']);
            unset($CIDRAM['Split']);

            /** Reconstruct and update auxiliary rules data. */
            if (($CIDRAM['NewAuxData'] = $CIDRAM['YAML']->reconstruct($CIDRAM['AuxData'])) && strlen($CIDRAM['NewAuxData']) > 2) {
                $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'auxiliary.yaml', 'wb');
                fwrite($CIDRAM['Handle'], $CIDRAM['NewAuxData']);
                fclose($CIDRAM['Handle']);
            }

        }

        /** Cleanup. */
        if (isset($CIDRAM['NewAuxData'])) {
            unset($CIDRAM['NewAuxData']);
        }

    }

}

/** Logs. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'logs' && $CIDRAM['FE']['Permissions'] > 0) {

    /** Page initial prepwork. */
    $CIDRAM['InitialPrepwork']($CIDRAM['L10N']->getString('link_logs'), $CIDRAM['L10N']->getString('tip_logs'), false);

    $CIDRAM['FE']['bNav'] = $CIDRAM['L10N']->getString('bNav_home_logout');

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['L10N']->Data + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_logs.html'))
    );

    /** Initialise array for fetching logs data. */
    $CIDRAM['FE']['LogFiles'] = [
        'Files' => $CIDRAM['Logs-RecursiveList']($CIDRAM['Vault']),
        'Out' => ''
    ];

    /** Text mode switch link base. */
    $CIDRAM['FE']['TextModeSwitchLink'] = '';

    /** Default field separator. */
    $CIDRAM['FE']['FieldSeparator'] = ': ';

    /** Link to search for related blocks and search information readout. */
    $CIDRAM['FE']['SearchInfo'] = $CIDRAM['FE']['SearchQuery'] = $CIDRAM['FE']['BlockLink'] = '';

    /** Add flags CSS. */
    if ($CIDRAM['FE']['Flags'] = file_exists($CIDRAM['Vault'] . 'fe_assets/flags.css')) {
        $CIDRAM['FE']['OtherHead'] .= "\n  <link rel=\"stylesheet\" type=\"text/css\" href=\"?cidram-page=flags\" />";
    }

    /** How to display the log data? */
    $CIDRAM['FE']['TextMode'] = false;
    if (empty($CIDRAM['QueryVars']['text-mode']) || $CIDRAM['QueryVars']['text-mode'] === 'false') {
        $CIDRAM['FE']['TextModeLinks'] = 'false';
    } elseif ($CIDRAM['QueryVars']['text-mode'] === 'tally') {
        $CIDRAM['FE']['TextModeLinks'] = 'tally';
    } else {
        $CIDRAM['FE']['TextModeLinks'] = 'true';
        $CIDRAM['FE']['TextMode'] = true;
    }

    /** Define log data. */
    if (empty($CIDRAM['QueryVars']['logfile'])) {
        $CIDRAM['FE']['logfileData'] = $CIDRAM['L10N']->getString('logs_no_logfile_selected');
    } elseif (empty($CIDRAM['FE']['LogFiles']['Files'][$CIDRAM['QueryVars']['logfile']])) {
        $CIDRAM['FE']['logfileData'] = $CIDRAM['L10N']->getString('logs_logfile_doesnt_exist');
    } else {
        $CIDRAM['FE']['TextModeSwitchLink'] .= '?cidram-page=logs&logfile=' . $CIDRAM['QueryVars']['logfile'];
        if (strtolower(substr($CIDRAM['QueryVars']['logfile'], -3)) === '.gz') {
            $CIDRAM['GZLogHandler'] = gzopen($CIDRAM['Vault'] . $CIDRAM['QueryVars']['logfile'], 'rb');
            $CIDRAM['FE']['logfileData'] = '';
            if (is_resource($CIDRAM['GZLogHandler'])) {
                while (!gzeof($CIDRAM['GZLogHandler'])) {
                    $CIDRAM['FE']['logfileData'] .= gzread($CIDRAM['GZLogHandler'], 131072);
                }
                gzclose($CIDRAM['GZLogHandler']);
            }
            unset($CIDRAM['GZLogHandler']);
        } else {
            $CIDRAM['FE']['logfileData'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['QueryVars']['logfile']);
        }
        if (strpos($CIDRAM['FE']['logfileData'], '：') !== false) {
            $CIDRAM['FE']['FieldSeparator'] = '：';
        }

        /** Handle block filtering. */
        if (!empty($CIDRAM['FE']['logfileData']) && !empty($CIDRAM['QueryVars']['search'])) {
            $CIDRAM['FE']['NewLogFileData'] = '';
            $CIDRAM['FE']['SearchQuery'] = base64_decode(str_replace('_', '=', $CIDRAM['QueryVars']['search']));
            $CIDRAM['FE']['BlockStart'] = 0;
            $CIDRAM['FE']['BlockEnd'] = 0;
            $CIDRAM['FE']['BlockSeparator'] = (
                strpos($CIDRAM['FE']['logfileData'], "\n\n") !== false
            ) ? "\n\n" : "\n";
            while (($CIDRAM['FE']['Needle'] = strpos(
                $CIDRAM['FE']['logfileData'],
                ($CIDRAM['FE']['BlockSeparator'] === "\n\n" ? $CIDRAM['FE']['FieldSeparator'] . $CIDRAM['FE']['SearchQuery'] . "\n" : $CIDRAM['FE']['SearchQuery']),
                $CIDRAM['FE']['BlockEnd']
            )) !== false || ($CIDRAM['FE']['Needle'] = strpos(
                $CIDRAM['FE']['logfileData'],
                '("' . $CIDRAM['FE']['SearchQuery'] . '", L',
                $CIDRAM['FE']['BlockEnd']
            )) !== false || (strlen($CIDRAM['FE']['SearchQuery']) === 2 && ($CIDRAM['FE']['Needle'] = strpos(
                $CIDRAM['FE']['logfileData'],
                '[' . $CIDRAM['FE']['SearchQuery'] . ']',
                $CIDRAM['FE']['BlockEnd']
            )) !== false)) {
                $CIDRAM['FE']['BlockStart'] = strrpos(substr($CIDRAM['FE']['logfileData'], 0, $CIDRAM['FE']['Needle']), $CIDRAM['FE']['BlockSeparator'], $CIDRAM['FE']['BlockEnd']);
                $CIDRAM['FE']['BlockEnd'] = strpos($CIDRAM['FE']['logfileData'], $CIDRAM['FE']['BlockSeparator'], $CIDRAM['FE']['Needle']);
                $CIDRAM['FE']['NewLogFileData'] .= substr($CIDRAM['FE']['logfileData'], $CIDRAM['FE']['BlockStart'], $CIDRAM['FE']['BlockEnd'] - $CIDRAM['FE']['BlockStart']);
            }
            if ($CIDRAM['FE']['BlockSeparator'] === "\n\n") {
                $CIDRAM['FE']['logfileData'] = substr($CIDRAM['FE']['NewLogFileData'], strlen($CIDRAM['FE']['BlockSeparator'])) . $CIDRAM['FE']['BlockSeparator'];
            }
            $CIDRAM['FE']['EntryCount'] = !str_replace("\n", '', $CIDRAM['FE']['logfileData']) ? 0 : (
                substr_count($CIDRAM['FE']['logfileData'], $CIDRAM['FE']['BlockSeparator'])
            );
            unset($CIDRAM['FE']['Needle'], $CIDRAM['FE']['BlockSeparator'], $CIDRAM['FE']['BlockEnd'], $CIDRAM['FE']['BlockStart'], $CIDRAM['FE']['NewLogFileData']);
            $CIDRAM['FE']['SearchInfoRender'] = (
                $CIDRAM['FE']['Flags'] && preg_match('~^[A-Z]{2}$~', $CIDRAM['FE']['SearchQuery'])
            ) ? '<span class="flag ' . $CIDRAM['FE']['SearchQuery'] . '"><span></span></span>' : '<code>' . $CIDRAM['FE']['SearchQuery'] . '</code>';
            $CIDRAM['FE']['SearchInfo'] = '<br />' . sprintf(
                $CIDRAM['L10N']->getPlural($CIDRAM['FE']['EntryCount'], 'label_displaying_that_cite'),
                $CIDRAM['NumberFormatter']->format($CIDRAM['FE']['EntryCount']),
                $CIDRAM['FE']['SearchInfoRender']
            );
        } else {
            $CIDRAM['FE']['EntryCount'] = !str_replace("\n", '', $CIDRAM['FE']['logfileData']) ? 0 : (
                substr_count($CIDRAM['FE']['logfileData'], "\n\n") ?: substr_count($CIDRAM['FE']['logfileData'], "\n")
            );
            if (substr($CIDRAM['FE']['logfileData'], 0, 2) === '<?') {
                $CIDRAM['FE']['EntryCount']--;
            }
            $CIDRAM['FE']['SearchInfo'] = '<br />' . sprintf(
                $CIDRAM['L10N']->getPlural($CIDRAM['FE']['EntryCount'], 'label_displaying'),
                $CIDRAM['NumberFormatter']->format($CIDRAM['FE']['EntryCount'])
            );
        }

        $CIDRAM['FE']['TextModeSwitchLink'] .= '&text-mode=';
        $CIDRAM['FE']['BlockLink'] .= $CIDRAM['FE']['TextModeSwitchLink'] . $CIDRAM['FE']['TextModeLinks'];
        $CIDRAM['FE']['logfileData'] = $CIDRAM['FE']['TextMode'] ? str_replace(
            ['<', '>', "\r", "\n"], ['&lt;', '&gt;', '', "<br />\n"], $CIDRAM['FE']['logfileData']
        ) : str_replace(
            ['<', '>', "\r"], ['&lt;', '&gt;', ''], $CIDRAM['FE']['logfileData']
        );
        $CIDRAM['FE']['mod_class_nav'] = ' big';
        $CIDRAM['FE']['mod_class_right'] = ' extend';
    }
    if (empty($CIDRAM['FE']['mod_class_nav'])) {
        $CIDRAM['FE']['mod_class_nav'] = ' extend';
        $CIDRAM['FE']['mod_class_right'] = ' big';
    }
    if (empty($CIDRAM['FE']['TextModeSwitchLink'])) {
        $CIDRAM['FE']['TextModeSwitchLink'] .= '?cidram-page=logs&text-mode=';
    }
    $CIDRAM['FE']['SearchPart'] = empty($CIDRAM['QueryVars']['search']) ? '' : '&search=' . $CIDRAM['QueryVars']['search'];

    /** Text mode switch link formatted. */
    $CIDRAM['FE']['TextModeSwitchLink'] = sprintf(
        $CIDRAM['L10N']->getString('link_textmode'),
        $CIDRAM['FE']['TextModeSwitchLink'],
        $CIDRAM['FE']['SearchPart']
    );

    /** Prepare log data formatting. */
    if ($CIDRAM['FE']['TextMode']) {
        $CIDRAM['Formatter']($CIDRAM['FE']['logfileData'], $CIDRAM['FE']['BlockLink'], $CIDRAM['FE']['SearchQuery'], $CIDRAM['FE']['FieldSeparator'], $CIDRAM['FE']['Flags']);
    } elseif ($CIDRAM['FE']['TextModeLinks'] === 'tally') {
        $CIDRAM['FE']['logfileData'] = $CIDRAM['Tally'](
            $CIDRAM['FE']['logfileData'],
            $CIDRAM['FE']['BlockLink'],
            [$CIDRAM['L10N']->getString('field_id'), $CIDRAM['L10N']->getString('field_datetime')]
        );
    } else {
        $CIDRAM['FE']['logfileData'] = '<textarea readonly>' . $CIDRAM['FE']['logfileData'] . '</textarea>';
    }

    /** Define logfile list. */
    array_walk($CIDRAM['FE']['LogFiles']['Files'], function ($Arr) use (&$CIDRAM) {
        $CIDRAM['FE']['LogFiles']['Out'] .= sprintf(
            '      <a href="?cidram-page=logs&logfile=%1$s&text-mode=%3$s">%1$s</a> – %2$s<br />',
            $Arr['Filename'],
            $Arr['Filesize'],
            $CIDRAM['FE']['TextModeLinks']
        ) . "\n";
    });

    /** Calculate page load time (useful for debugging). */
    $CIDRAM['FE']['ProcessTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
    $CIDRAM['FE']['SearchInfo'] .= '<br />' . sprintf(
        $CIDRAM['L10N']->getPlural($CIDRAM['FE']['ProcessTime'], 'state_loadtime'),
        $CIDRAM['NumberFormatter']->format($CIDRAM['FE']['ProcessTime'], 3)
    );

    /** Set logfile list or no logfiles available message. */
    $CIDRAM['FE']['LogFiles'] = $CIDRAM['FE']['LogFiles']['Out'] ?: $CIDRAM['L10N']->getString('logs_no_logfiles_available');

    /** Send output. */
    echo $CIDRAM['SendOutput']();

}

/** Rebuild cache. */
if ($CIDRAM['FE']['Rebuild']) {
    $CIDRAM['FE']['FrontEndData'] =
        "USERS\n-----" . $CIDRAM['FE']['UserList'] .
        "\nSESSIONS\n--------" . $CIDRAM['FE']['SessionList'] .
        "\nCACHE\n-----" . $CIDRAM['FE']['Cache'];
    $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'fe_assets/frontend.dat', 'wb');
    fwrite($CIDRAM['Handle'], $CIDRAM['FE']['FrontEndData']);
    fclose($CIDRAM['Handle']);
}

/** Destroy cache object and some related values. */
$CIDRAM['DestroyCacheObject']();

/** Print Cronable failure state messages here. */
if ($CIDRAM['FE']['CronMode'] && $CIDRAM['FE']['state_msg'] && $CIDRAM['FE']['UserState'] !== 1) {
    if (empty($UpdateAll)) {
        echo json_encode(['state_msg' => $CIDRAM['FE']['state_msg']]);
    } else {
        $Results = ['state_msg' => $CIDRAM['FE']['state_msg']];
    }
}

/** Exit front-end. */
if (empty($CIDRAM['Alternate']) && empty($UpdateAll)) {
    die;
}
