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
 * This file: Front-end handler (last modified: 2016.10.08).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** Set page selector if not already set. */
if (empty($CIDRAM['QueryVars']['cidram-page'])) {
    $CIDRAM['QueryVars']['cidram-page'] = '';
}

/** Populate common front-end variables. */
$CIDRAM['FE'] = array(
    'Template' => $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/frontend.html'),
    'DateTime' => date('r', $CIDRAM['Now']),
    'ScriptIdent' => $CIDRAM['ScriptIdent'],
    'UserList' => "\n",
    'SessionList' => "\n",
    'UserState' => 0,
    'UserRaw' => '',
    'UserLevel' => 0,
    'state_msg' => '',
    'ThisSession' => '',
    'bNav' => '&nbsp;',
    'FE_Title' => ''
);

/** Fetch user list and sessions list. */
if (file_exists($CIDRAM['Vault'] . 'fe_assets/frontend.dat')) {
    $CIDRAM['FE']['FrontEndData'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/frontend.dat');
    $CIDRAM['FE']['Rebuild'] = false;
} else {
    $CIDRAM['FE']['FrontEndData'] = "USERS\n-----\nYWRtaW4=,\$2y\$10\$FPF5Im9MELEvF5AYuuRMSO.QKoYVpsiu1YU9aDClgrU57XtLof/dK,1\n\nSESSIONS\n--------\n";
    $CIDRAM['FE']['Rebuild'] = true;
}
$CIDRAM['FE']['UserListPos'] = strpos($CIDRAM['FE']['FrontEndData'], "USERS\n-----\n");
$CIDRAM['FE']['SessionListPos'] = strpos($CIDRAM['FE']['FrontEndData'], "SESSIONS\n--------\n");
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
        $CIDRAM['FE']['SessionListPos'] + 17
    );
}

/** Clear expired sessions. */
$CIDRAM['ClearExpired']($CIDRAM['FE']['SessionList'], $CIDRAM['FE']['Rebuild']);

/** A fix for correctly displaying LTR/RTL text. */
if (empty($CIDRAM['lang']['textDir']) || $CIDRAM['lang']['textDir'] !== 'rtl') {
    $CIDRAM['lang']['textDir'] = 'ltr';
    $CIDRAM['FE']['FE_Align'] = 'left';
} else {
    $CIDRAM['FE']['FE_Align'] = 'right';
}

/** Attempt to log in the user. */
if (!empty($_POST['username']) && empty($_POST['password'])) {
    $CIDRAM['FE']['UserState'] = -1;
    $CIDRAM['FE']['state_msg'] = $CIDRAM['WrapRedText']($CIDRAM['lang']['error_login_password_field_empty']);
} elseif (empty($_POST['username']) && !empty($_POST['password'])) {
    $CIDRAM['FE']['UserState'] = -1;
    $CIDRAM['FE']['state_msg'] = $CIDRAM['WrapRedText']($CIDRAM['lang']['error_login_username_field_empty']);
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
        $CIDRAM['FE']['UserLevel'] = (int)substr($CIDRAM['FE']['Password'], -1);
        $CIDRAM['FE']['Password'] = substr($CIDRAM['FE']['Password'], 0, -2);
        if (password_verify($_POST['password'], $CIDRAM['FE']['Password'])) {
            $CIDRAM['FE']['SessionKey'] = md5($CIDRAM['GenerateSalt']());
            $CIDRAM['FE']['Cookie'] = $_POST['username'] . $CIDRAM['FE']['SessionKey'];
            setcookie('CIDRAM-ADMIN', $CIDRAM['FE']['Cookie'], $CIDRAM['Now'] + 604800, '/', (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '', false, true);
            $CIDRAM['FE']['UserState'] = 1;
            $CIDRAM['FE']['ThisSession'] =
                $CIDRAM['FE']['User'] . ',' .
                password_hash($CIDRAM['FE']['SessionKey'], PASSWORD_DEFAULT) . ',' .
                ($CIDRAM['Now'] + 604800) . "\n";
            $CIDRAM['FE']['SessionList'] .= $CIDRAM['FE']['ThisSession'];
            $CIDRAM['FE']['Rebuild'] = true;
        } else {
            $CIDRAM['FE']['UserLevel'] = 0;
            $CIDRAM['FE']['state_msg'] = $CIDRAM['WrapRedText']($CIDRAM['lang']['error_login_invalid_password']);
        }
    } else {
        $CIDRAM['FE']['state_msg'] = $CIDRAM['WrapRedText']($CIDRAM['lang']['error_login_invalid_username']);
    }

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
            $CIDRAM['FE']['SEDelimiter'] = strpos($CIDRAM['FE']['SessionEntry'], ',');
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
                    $CIDRAM['FE']['UserLevel'] = (int)substr(substr(
                        $CIDRAM['FE']['UserList'],
                        $CIDRAM['FE']['UserOffset'],
                        strpos($CIDRAM['FE']['UserList'], "\n", $CIDRAM['FE']['UserOffset']) - $CIDRAM['FE']['UserOffset']
                    ), -1);
                    $CIDRAM['FE']['UserState'] = 1;
                }
                break;
            }
        }
    }

}

/** Only execute this code block for users that are logged in. */
if ($CIDRAM['FE']['UserState'] === 1) {
    if ($CIDRAM['QueryVars']['cidram-page'] === 'logout') {

        /** Log out the user. */
        $CIDRAM['FE']['SessionList'] = str_ireplace($CIDRAM['FE']['ThisSession'], '', $CIDRAM['FE']['SessionList']);
        $CIDRAM['FE']['ThisSession'] = '';
        $CIDRAM['FE']['Rebuild'] = true;
        $CIDRAM['FE']['UserState'] = $CIDRAM['FE']['UserLevel'] = 0;
        setcookie('CIDRAM-ADMIN', '', -1, '/', (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '', false, true);

    } else {

        /** Generate default tooltip for the user ("Hello, {username}"). */
        $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
            array('username' => $CIDRAM['FE']['UserRaw']),
            $CIDRAM['lang']['tip_hello']
        );

    }
}

/** The user hasn't logged in. Show them the login page. */
if ($CIDRAM['FE']['UserState'] !== 1) {

    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_login'];

    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['lang']['tip_login'];

    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_login.html')
    );

    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/**
 * The user has logged in, but hasn't selected anything to view. Show them the
 * front-end home page.
 */
elseif ($CIDRAM['QueryVars']['cidram-page'] === '') {

    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_home'];

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_logout'];

    if ($CIDRAM['FE']['UserLevel'] === 1) {

        $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
            $CIDRAM['lang'] + $CIDRAM['FE'],
            $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_home_admin.html')
        );

    } elseif ($CIDRAM['FE']['UserLevel'] === 2) {

        $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
            $CIDRAM['lang'] + $CIDRAM['FE'],
            $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_home_mod.html')
        );

    }

    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** Accounts. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'accounts' && $CIDRAM['FE']['UserLevel'] === 1) {

    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_accounts'];

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_accounts.html')
    );

    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** Configuration. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'config' && $CIDRAM['FE']['UserLevel'] === 1) {

    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_config'];

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_config.html')
    );

    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** Updates. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'updates' && $CIDRAM['FE']['UserLevel'] === 1) {

    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_updates'];

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_updates.html')
    );

    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** Logs. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'logs') {

    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_logs'];

    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['lang']['tip_logs'];

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_logs.html')
    );

    $CIDRAM['FE']['LogFiles'] = array(
        'Files' => scandir($CIDRAM['Vault']),
        'Types' => ',txt,log,',
        'Out' => ''
    );

    if (empty($CIDRAM['QueryVars']['logfile'])) {
        $CIDRAM['FE']['logfileData'] = $CIDRAM['lang']['logs_no_logfile_selected'];
    } elseif (
        file_exists($CIDRAM['Vault'] . $CIDRAM['QueryVars']['logfile']) &&
        !preg_match("\x01(?:^\.|\.\.|[\x01-\x1f\[-`/\?\*\$])\x01i", $CIDRAM['QueryVars']['logfile']) &&
        (strpos(
            $CIDRAM['FE']['LogFiles']['Types'],
            strtolower(substr($CIDRAM['QueryVars']['logfile'], -3))
        ) !== false)
    ) {
        $CIDRAM['FE']['logfileData'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['QueryVars']['logfile']);
        $CIDRAM['FE']['logfileData'] = str_replace(
            array('<', '>', "\r", "\n"),
            array('&lt;', '&gt;', '', "<br />\n"),
            $CIDRAM['FE']['logfileData']
        );
    } else {
        $CIDRAM['FE']['logfileData'] = $CIDRAM['lang']['logs_logfile_doesnt_exist'];
    }

    $CIDRAM['FE']['LogFiles']['Count'] = count($CIDRAM['FE']['LogFiles']['Files']);
    for (
        $CIDRAM['FE']['LogFiles']['Iterate'] = 0;
        $CIDRAM['FE']['LogFiles']['Iterate'] < $CIDRAM['FE']['LogFiles']['Count'];
        $CIDRAM['FE']['LogFiles']['Iterate']++
    ) {
        if (strpos(
            $CIDRAM['FE']['LogFiles']['Types'],
            strtolower(substr($CIDRAM['FE']['LogFiles']['Files'][$CIDRAM['FE']['LogFiles']['Iterate']], -3))
        ) !== false) {
            $CIDRAM['FE']['LogFiles']['This'] = $CIDRAM['FE']['LogFiles']['Files'][$CIDRAM['FE']['LogFiles']['Iterate']];
            $CIDRAM['FE']['LogFiles']['Filesize'] = filesize($CIDRAM['Vault'] . $CIDRAM['FE']['LogFiles']['This']);
            $CIDRAM['FormatFilesize']($CIDRAM['FE']['LogFiles']['Filesize']);
            $CIDRAM['FE']['LogFiles']['Out'] .= sprintf(
                '            <a href="?cidram-page=logs&logfile=%1$s">%1$s</a> – %2$s<br />',
                $CIDRAM['FE']['LogFiles']['This'],
                $CIDRAM['FE']['LogFiles']['Filesize']
            ) . "\n";
        }
    }
    if (!$CIDRAM['FE']['LogFiles'] = $CIDRAM['FE']['LogFiles']['Out']) {
        $CIDRAM['FE']['LogFiles'] = $CIDRAM['lang']['logs_no_logfiles_available'];
    }

    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

if ($CIDRAM['FE']['Rebuild']) {
    $CIDRAM['FE']['FrontEndData'] = "USERS\n-----" . $CIDRAM['FE']['UserList'] . "\nSESSIONS\n--------" . $CIDRAM['FE']['SessionList'];
    $CIDRAM['FE']['Handle'] = fopen($CIDRAM['Vault'] . 'fe_assets/frontend.dat', 'w');
    fwrite($CIDRAM['FE']['Handle'], $CIDRAM['FE']['FrontEndData']);
    fclose($CIDRAM['FE']['Handle']);
}

die;
