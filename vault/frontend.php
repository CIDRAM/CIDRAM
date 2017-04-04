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
 * This file: Front-end handler (last modified: 2017.04.04).
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
    'PIP_Left' => 'R0lGODlhCAAIAIABAJkCAP///yH5BAEKAAEALAAAAAAIAAgAAAINjH+ga6vJIEDh0UmzKQA7',
    'PIP_Right' => 'R0lGODlhCAAIAIABAJkCAP///yH5BAEKAAEALAAAAAAIAAgAAAINjH+gmwvoUGBSSfOuKQA7',
    'PIP_Key' => 'R0lGODlhBwAJAIABAJkAAP///yH5BAEKAAEALAAAAAAHAAkAAAINjH+gyaaAAkQrznRbKAA7',
    'Template' => $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/frontend.html'),
    'DefaultPassword' => '$2y$10$FPF5Im9MELEvF5AYuuRMSO.QKoYVpsiu1YU9aDClgrU57XtLof/dK',
    'FE_Lang' => $CIDRAM['Config']['general']['lang'],
    'DateTime' => date('r', $CIDRAM['Now']),
    'WebFontsLink' => $CIDRAM['WebFontsLink'],
    'ScriptIdent' => $CIDRAM['ScriptIdent'],
    'UserList' => "\n",
    'SessionList' => "\n",
    'Cache' => "\n",
    'UserState' => 0,
    'UserRaw' => '',
    'Permissions' => 0,
    'state_msg' => '',
    'ThisSession' => '',
    'bNav' => '&nbsp;',
    'FE_Title' => ''
);

/** Traversal detection. */
$CIDRAM['Traverse'] = function ($Path) {
    return !preg_match("\x01" . '(?:[\./]{2}|[\x01-\x1f\[-^`?*$])' . "\x01i", str_replace("\\", '/', $Path));
};

/** A fix for correctly displaying LTR/RTL text. */
if (empty($CIDRAM['lang']['textDir']) || $CIDRAM['lang']['textDir'] !== 'rtl') {
    $CIDRAM['lang']['textDir'] = 'ltr';
    $CIDRAM['FE']['FE_Align'] = 'left';
    $CIDRAM['FE']['FE_Align_Reverse'] = 'right';
    $CIDRAM['FE']['PIP_Input'] = $CIDRAM['FE']['PIP_Right'];
    $CIDRAM['FE']['Gradient_Degree'] = 90;
    $CIDRAM['FE']['Half_Border'] = 'solid solid none none';
} else {
    $CIDRAM['FE']['FE_Align'] = 'right';
    $CIDRAM['FE']['FE_Align_Reverse'] = 'left';
    $CIDRAM['FE']['PIP_Input'] = $CIDRAM['FE']['PIP_Left'];
    $CIDRAM['FE']['Gradient_Degree'] = 270;
    $CIDRAM['FE']['Half_Border'] = 'solid none none solid';
}

/** A simple passthru for the front-end CSS. */
if ($CIDRAM['QueryVars']['cidram-page'] === 'css') {
    header('Content-Type: text/css');
    echo $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/frontend.css')
    );
    die;
}

/** A simple passthru for the favicon. */
if ($CIDRAM['QueryVars']['cidram-page'] === 'favicon') {
    header('Content-Type: image/gif');
    echo base64_decode($CIDRAM['favicon']);
    die;
}

/** Set form target if not already set. */
$CIDRAM['FE']['FormTarget'] = (empty($_POST['cidram-form-target'])) ? '' : $_POST['cidram-form-target'];

/** Fetch user list, sessions list and the front-end cache. */
if (file_exists($CIDRAM['Vault'] . 'fe_assets/frontend.dat')) {
    $CIDRAM['FE']['FrontEndData'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/frontend.dat');
    $CIDRAM['FE']['Rebuild'] = false;
} else {
    $CIDRAM['FE']['FrontEndData'] = "USERS\n-----\nYWRtaW4=," . $CIDRAM['FE']['DefaultPassword'] . ",1\n\nSESSIONS\n--------\n\nCACHE\n-----\n";
    $CIDRAM['FE']['Rebuild'] = true;
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

/** Brute-force security check. */
if (($CIDRAM['LoginAttempts'] = (int)$CIDRAM['FECacheGet'](
    $CIDRAM['FE']['Cache'], 'LoginAttempts' . $_SERVER[$CIDRAM['Config']['general']['ipaddr']]
)) && ($CIDRAM['LoginAttempts'] >= $CIDRAM['Config']['general']['max_login_attempts'])) {
    header('Content-Type: text/plain');
    die('[CIDRAM] ' . $CIDRAM['lang']['max_login_attempts_exceeded']);
}

/** Attempt to log in the user. */
if ($CIDRAM['FE']['FormTarget'] === 'login') {
    if (!empty($_POST['username']) && empty($_POST['password'])) {
        $CIDRAM['FE']['UserState'] = -1;
        $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_login_password_field_empty'];
    } elseif (empty($_POST['username']) && !empty($_POST['password'])) {
        $CIDRAM['FE']['UserState'] = -1;
        $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_login_username_field_empty'];
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
                    $CIDRAM['FE']['Cache'], $CIDRAM['FE']['Rebuild'], 'LoginAttempts' . $_SERVER[$CIDRAM['Config']['general']['ipaddr']]
                );
                $CIDRAM['FE']['SessionKey'] = md5($CIDRAM['GenerateSalt']());
                $CIDRAM['FE']['Cookie'] = $_POST['username'] . $CIDRAM['FE']['SessionKey'];
                setcookie('CIDRAM-ADMIN', $CIDRAM['FE']['Cookie'], $CIDRAM['Now'] + 604800, '/', (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''), false, true);
                $CIDRAM['FE']['UserState'] = 1;
                $CIDRAM['FE']['ThisSession'] =
                    $CIDRAM['FE']['User'] . ',' .
                    password_hash($CIDRAM['FE']['SessionKey'], PASSWORD_DEFAULT) . ',' .
                    ($CIDRAM['Now'] + 604800) . "\n";
                $CIDRAM['FE']['SessionList'] .= $CIDRAM['FE']['ThisSession'];
                $CIDRAM['FE']['Rebuild'] = true;
            } else {
                $CIDRAM['FE']['Permissions'] = 0;
                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_login_invalid_password'];
            }
        } else {
            $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_login_invalid_username'];
        }

    }

    if ($CIDRAM['Config']['general']['FrontEndLog']) {
        if (strpos($CIDRAM['Config']['general']['FrontEndLog'], '{') !== false) {
            $CIDRAM['Config']['general']['FrontEndLog'] = $CIDRAM['Time2Logfile'](
                $CIDRAM['Now'],
                $CIDRAM['Config']['general']['FrontEndLog']
            );
        }
        $CIDRAM['FrontEndLog'] = $_SERVER[$CIDRAM['Config']['general']['ipaddr']] . ' - ' . $CIDRAM['FE']['DateTime'] . ' - ';
        $CIDRAM['FrontEndLog'] .= empty($_POST['username']) ? '""' : '"' . $_POST['username'] . '"';
    }
    if ($CIDRAM['FE']['state_msg']) {
        $CIDRAM['LoginAttempts']++;
        $CIDRAM['TimeToAdd'] = ($CIDRAM['LoginAttempts'] > 4) ? ($CIDRAM['LoginAttempts'] - 4) * 86400 : 86400;
        $CIDRAM['FECacheAdd'](
            $CIDRAM['FE']['Cache'],
            $CIDRAM['FE']['Rebuild'],
            'LoginAttempts' . $_SERVER[$CIDRAM['Config']['general']['ipaddr']],
            $CIDRAM['LoginAttempts'],
            $CIDRAM['Now'] + $CIDRAM['TimeToAdd']
        );
        if ($CIDRAM['Config']['general']['FrontEndLog']) {
            $CIDRAM['FrontEndLog'] .= ' - ' . $CIDRAM['FE']['state_msg'] . "\n";
        }
        $CIDRAM['FE']['state_msg'] = $CIDRAM['WrapRedText']($CIDRAM['FE']['state_msg']);
    } elseif ($CIDRAM['Config']['general']['FrontEndLog']) {
        $CIDRAM['FrontEndLog'] .= ' - ' . $CIDRAM['lang']['state_logged_in'] . "\n";
    }
    if ($CIDRAM['Config']['general']['FrontEndLog']) {
        $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . $CIDRAM['Config']['general']['FrontEndLog'], 'a');
        fwrite($CIDRAM['Handle'], $CIDRAM['FrontEndLog']);
        fclose($CIDRAM['Handle']);
        unset($CIDRAM['FrontEndLog']);
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
                    $CIDRAM['FE']['Permissions'] = (int)substr(substr(
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
        $CIDRAM['FE']['UserState'] = $CIDRAM['FE']['Permissions'] = 0;
        setcookie('CIDRAM-ADMIN', '', -1, '/', (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''), false, true);

    }

    /** If the user has complete access. */
    if ($CIDRAM['FE']['Permissions'] === 1) {

        $CIDRAM['FE']['nav'] = $CIDRAM['ParseVars'](
            $CIDRAM['lang'] + $CIDRAM['FE'],
            $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_nav_complete_access.html')
        );

    /** If the user has logs access only. */
    } elseif ($CIDRAM['FE']['Permissions'] === 2) {

        $CIDRAM['FE']['nav'] = $CIDRAM['ParseVars'](
            $CIDRAM['lang'] + $CIDRAM['FE'],
            $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_nav_logs_access_only.html')
        );

    }

    /** Execute hotfixes. */
    if (file_exists($CIDRAM['Vault'] . 'hotfixes.php')) {
        require $CIDRAM['Vault'] . 'hotfixes.php';
    }

}

$CIDRAM['FE']['bNavBR'] = ($CIDRAM['FE']['UserState'] === 1) ? '<br /><br />' : '<br />';

/** The user hasn't logged in. Show them the login page. */
if ($CIDRAM['FE']['UserState'] !== 1) {

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_login'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['lang']['tip_login'];

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_login.html')
    );

    /** Send output. */
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/**
 * The user has logged in, but hasn't selected anything to view. Show them the
 * front-end home page.
 */
elseif ($CIDRAM['QueryVars']['cidram-page'] === '') {

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_home'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
        array('username' => $CIDRAM['FE']['UserRaw']),
        $CIDRAM['lang']['tip_home']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_logout'];

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_home.html')
    );

    /** Send output. */
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

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

        /** Fetch file manager icons data. */
        if (file_exists($CIDRAM['Vault'] . 'icons.php') && is_readable($CIDRAM['Vault'] . 'icons.php')) {
            require $CIDRAM['Vault'] . 'icons.php';
        }

        header('Content-Type: image/gif');
        if (!empty($CIDRAM['Icons'][$CIDRAM['QueryVars']['icon']])) {
            echo gzinflate(base64_decode($CIDRAM['Icons'][$CIDRAM['QueryVars']['icon']]));
        } else {
            echo gzinflate(base64_decode($CIDRAM['Icons']['unknown']));
        }
    }

    die;

}

/** Accounts. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'accounts' && $CIDRAM['FE']['Permissions'] === 1) {

    /** A form has been submitted. */
    if ($CIDRAM['FE']['FormTarget'] === 'accounts' && !empty($_POST['do'])) {

        /** Create a new account. */
        if ($_POST['do'] === 'create-account' && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['permissions'])) {
            $CIDRAM['FE']['NewUser'] = $_POST['username'];
            $CIDRAM['FE']['NewPass'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $CIDRAM['FE']['NewPerm'] = (int)$_POST['permissions'];
            $CIDRAM['FE']['NewUserB64'] = base64_encode($_POST['username']);
            if (strpos($CIDRAM['FE']['UserList'], "\n" . $CIDRAM['FE']['NewUserB64'] . ',') !== false) {
                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_accounts_already_exists'];
            } else {
                $CIDRAM['FE']['NewAccount'] =
                    $CIDRAM['FE']['NewUserB64'] . ',' .
                    $CIDRAM['FE']['NewPass'] . ',' .
                    $CIDRAM['FE']['NewPerm'] . "\n";
                $CIDRAM['AccountsArray'] = array(
                    'Iterate' => 0,
                    'Count' => 1,
                    'ByName' => array($CIDRAM['FE']['NewUser'] => $CIDRAM['FE']['NewAccount'])
                );
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
                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_accounts_created'];
            }
        }

        /** Delete an account. */
        if ($_POST['do'] === 'delete-account' && !empty($_POST['username'])) {
            $CIDRAM['FE']['User64'] = base64_encode($_POST['username']);
            $CIDRAM['FE']['UserLinePos'] = strpos($CIDRAM['FE']['UserList'], "\n" . $CIDRAM['FE']['User64'] . ',');
            if ($CIDRAM['FE']['UserLinePos'] === false) {
                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_accounts_doesnt_exist'];
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
                    $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_accounts_deleted'];
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
            $CIDRAM['FE']['NewPass'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $CIDRAM['FE']['UserLinePos'] = strpos($CIDRAM['FE']['UserList'], "\n" . $CIDRAM['FE']['User64'] . ',');
            if ($CIDRAM['FE']['UserLinePos'] === false) {
                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_accounts_doesnt_exist'];
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
                    $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_accounts_password_updated'];
                }
            }
        }

    }

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_accounts'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
        array('username' => $CIDRAM['FE']['UserRaw']),
        $CIDRAM['lang']['tip_accounts']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    $CIDRAM['FE']['AccountsRow'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_accounts_row.html');
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
        $CIDRAM['RowInfo'] = explode(',', $CIDRAM['FE']['NewLine'], 3);
        $CIDRAM['RowInfo'][3] = (strpos($CIDRAM['FE']['SessionList'], "\n" . $CIDRAM['RowInfo'][0] . ',') !== false);
        $CIDRAM['RowInfo'][0] = htmlentities(base64_decode($CIDRAM['RowInfo'][0]));
        if ($CIDRAM['RowInfo'][1] === $CIDRAM['FE']['DefaultPassword']) {
            $CIDRAM['RowInfo'][1] = '<br /><div class="txtRd">' . $CIDRAM['lang']['state_default_password'] . '</div>';
        } elseif (strlen($CIDRAM['RowInfo'][1]) !== 60 || !preg_match('/^\$2.\$[0-9]{2}\$/', $CIDRAM['RowInfo'][1])) {
            $CIDRAM['RowInfo'][1] = '<br /><div class="txtRd">' . $CIDRAM['lang']['state_password_not_valid'] . '</div>';
        } else {
            $CIDRAM['RowInfo'][1] = '';
        }
        if ($CIDRAM['RowInfo'][3]) {
            $CIDRAM['RowInfo'][1] .= '<br /><div class="txtGn">' . $CIDRAM['lang']['state_logged_in'] . '</div>';
        }
        $CIDRAM['RowInfo'][2] = (int)$CIDRAM['RowInfo'][2];
        $CIDRAM['RowInfo'] = array(
            'username' => $CIDRAM['RowInfo'][0],
            'warning' => $CIDRAM['RowInfo'][1],
            'permissions' =>
                ($CIDRAM['RowInfo'][2] === 1) ?
                $CIDRAM['lang']['state_complete_access'] :
                $CIDRAM['lang']['state_logs_access_only']
        );
        $CIDRAM['FE']['NewLineOffset'] = $CIDRAM['FE']['NewLinePos'];
        $CIDRAM['FE']['Accounts'] .= $CIDRAM['ParseVars'](
            $CIDRAM['lang'] + $CIDRAM['RowInfo'], $CIDRAM['FE']['AccountsRow']
        );
    }
    unset($CIDRAM['RowInfo']);

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_accounts.html')
    );

    /** Send output. */
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** Configuration. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'config' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_config'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
        array('username' => $CIDRAM['FE']['UserRaw']),
        $CIDRAM['lang']['tip_config']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    $CIDRAM['FE']['ConfigRow'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_config_row.html');

    /** Indexes. */
    $CIDRAM['FE']['Indexes'] = '            ';

    /** Generate entries for display and regenerate configuration if any changes were submitted. */
    reset($CIDRAM['Config']['Config Defaults']);
    $CIDRAM['FE']['ConfigFields'] = $CIDRAM['RegenerateConfig'] = '';
    $CIDRAM['ConfigModified'] = (!empty($CIDRAM['QueryVars']['updated']) && $CIDRAM['QueryVars']['updated'] === 'true');
    foreach ($CIDRAM['Config']['Config Defaults'] as $CIDRAM['CatKey'] => $CIDRAM['CatValue']) {
        if (!is_array($CIDRAM['CatValue'])) {
            continue;
        }
        $CIDRAM['RegenerateConfig'] .= '[' . $CIDRAM['CatKey'] . "]\r\n\r\n";
        foreach ($CIDRAM['CatValue'] as $CIDRAM['DirKey'] => $CIDRAM['DirValue']) {
            $CIDRAM['ThisDir'] = array('FieldOut' => '');
            if (empty($CIDRAM['DirValue']['type']) || !isset($CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']])) {
                continue;
            }
            $CIDRAM['ThisDir']['DirLangKey'] = 'config_' . $CIDRAM['CatKey'] . '_' . $CIDRAM['DirKey'];
            $CIDRAM['ThisDir']['DirName'] = $CIDRAM['CatKey'] . '->' . $CIDRAM['DirKey'];
            $CIDRAM['FE']['Indexes'] .= '<a href="#' . $CIDRAM['ThisDir']['DirLangKey'] . '">' . $CIDRAM['ThisDir']['DirName'] . "</a><br /><br />\n            ";
            $CIDRAM['ThisDir']['DirLang'] =
                !empty($CIDRAM['lang'][$CIDRAM['ThisDir']['DirLangKey']]) ? $CIDRAM['lang'][$CIDRAM['ThisDir']['DirLangKey']] : $CIDRAM['lang']['response_error'];
            $CIDRAM['RegenerateConfig'] .= '; ' . wordwrap(strip_tags($CIDRAM['ThisDir']['DirLang']), 77, "\r\n; ") . "\r\n";
            if (isset($_POST[$CIDRAM['ThisDir']['DirLangKey']])) {
                if ($CIDRAM['DirValue']['type'] === 'string' || $CIDRAM['DirValue']['type'] === 'int' || $CIDRAM['DirValue']['type'] === 'bool') {
                    $CIDRAM['AutoType']($_POST[$CIDRAM['ThisDir']['DirLangKey']], $CIDRAM['DirValue']['type']);
                }
                if (
                    !preg_match('/[^\x20-\xff"\']/', $_POST[$CIDRAM['ThisDir']['DirLangKey']]) && (
                        !isset($CIDRAM['DirValue']['choices']) || isset($CIDRAM['DirValue']['choices'][$_POST[$CIDRAM['ThisDir']['DirLangKey']]])
                    )
                ) {
                    $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] = $_POST[$CIDRAM['ThisDir']['DirLangKey']];
                    $CIDRAM['ConfigModified'] = true;
                }
            }
            if ($CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] === true) {
                $CIDRAM['RegenerateConfig'] .= $CIDRAM['DirKey'] . "=true\r\n\r\n";
            } elseif ($CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] === false) {
                $CIDRAM['RegenerateConfig'] .= $CIDRAM['DirKey'] . "=false\r\n\r\n";
            } elseif ($CIDRAM['DirValue']['type'] === 'int') {
                $CIDRAM['RegenerateConfig'] .= $CIDRAM['DirKey'] . '=' . $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] . "\r\n\r\n";
            } else {
                $CIDRAM['RegenerateConfig'] .= $CIDRAM['DirKey'] . '=\'' . $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] . "'\r\n\r\n";
            }
            if (isset($CIDRAM['DirValue']['choices'])) {
                $CIDRAM['ThisDir']['FieldOut'] = '<select name="'. $CIDRAM['ThisDir']['DirLangKey'] . '">';
                foreach ($CIDRAM['DirValue']['choices'] as $CIDRAM['ChoiceKey'] => $CIDRAM['ChoiceValue']) {
                    $CIDRAM['ThisDir']['FieldOut'] .= ($CIDRAM['ChoiceKey'] === $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']]) ?
                        '<option value="' . $CIDRAM['ChoiceKey'] . '" selected>' . $CIDRAM['ChoiceValue'] . '</option>'
                    :
                        '<option value="' . $CIDRAM['ChoiceKey'] . '">' . $CIDRAM['ChoiceValue'] . '</option>';
                }
                $CIDRAM['ThisDir']['FieldOut'] .= '</select>';
            } elseif ($CIDRAM['DirValue']['type'] === 'bool') {
                if ($CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']]) {
                    $CIDRAM['ThisDir']['FieldOut'] =
                        '<select name="'. $CIDRAM['ThisDir']['DirLangKey'] . '">' .
                        '<option value="true" selected>True</option><option value="false">False</option>' .
                        '</select>';
                } else {
                    $CIDRAM['ThisDir']['FieldOut'] =
                        '<select name="'. $CIDRAM['ThisDir']['DirLangKey'] . '">' .
                        '<option value="true">True</option><option value="false" selected>False</option>' .
                        '</select>';
                }
            } elseif ($CIDRAM['DirValue']['type'] === 'int') {
                $CIDRAM['ThisDir']['FieldOut'] = '<input type="number" name="'. $CIDRAM['ThisDir']['DirLangKey'] . '" value="' . $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] . '" />';
            } elseif ($CIDRAM['DirValue']['type'] === 'string') {
                $CIDRAM['ThisDir']['FieldOut'] = '<textarea name="'. $CIDRAM['ThisDir']['DirLangKey'] . '" class="half">' . $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] . '</textarea>';
            } else {
                $CIDRAM['ThisDir']['FieldOut'] = '<input type="text" name="'. $CIDRAM['ThisDir']['DirLangKey'] . '" value="' . $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] . '" />';
            }
            $CIDRAM['FE']['ConfigFields'] .= $CIDRAM['ParseVars'](
                $CIDRAM['lang'] + $CIDRAM['ThisDir'], $CIDRAM['FE']['ConfigRow']
            );
        }
        $CIDRAM['RegenerateConfig'] .= "\r\n";
    }

    /** Update the configuration file if any changes were made. */
    if ($CIDRAM['ConfigModified']) {
        $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_configuration_updated'];
        $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'config.ini', 'w');
        fwrite($CIDRAM['Handle'], $CIDRAM['RegenerateConfig']);
        fclose($CIDRAM['Handle']);
        if (empty($CIDRAM['QueryVars']['updated'])) {
            header('Location: ?cidram-page=config&updated=true');
            die;
        }
    }

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_config.html')
    );

    /** Send output. */
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** Updates. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'updates' && $CIDRAM['FE']['Permissions'] === 1) {

    $CIDRAM['FE']['UpdatesFormTarget'] = 'cidram-page=updates';
    $CIDRAM['FE']['UpdatesFormTargetControls'] = '';
    $CIDRAM['QueryTemp'] = array('Switched' => false);
    foreach (array('hide-non-outdated', 'hide-unused') as $CIDRAM['QueryTemp']['Param']) {
        $CIDRAM['QueryTemp']['Switch'] = (
            !empty($_POST['updates-form-target-selecter']) &&
            $_POST['updates-form-target-selecter'] === $CIDRAM['QueryTemp']['Param']
        );
        if (empty($CIDRAM['QueryVars'][$CIDRAM['QueryTemp']['Param']])) {
            $CIDRAM['FE'][$CIDRAM['QueryTemp']['Param']] = false;
        } else {
            $CIDRAM['FE'][$CIDRAM['QueryTemp']['Param']] = (
                ($CIDRAM['QueryVars'][$CIDRAM['QueryTemp']['Param']] === 'true' && !$CIDRAM['QueryTemp']['Switch']) ||
                ($CIDRAM['QueryVars'][$CIDRAM['QueryTemp']['Param']] !== 'true' && $CIDRAM['QueryTemp']['Switch'])
            );
        }
        if ($CIDRAM['QueryTemp']['Switch']) {
            $CIDRAM['QueryTemp']['Switched'] = true;
        }
        if ($CIDRAM['FE'][$CIDRAM['QueryTemp']['Param']]) {
            $CIDRAM['FE']['UpdatesFormTarget'] .= '&' . $CIDRAM['QueryTemp']['Param'] . '=true';
            $CIDRAM['QueryTemp']['LangOpt'] = 'switch-' . $CIDRAM['QueryTemp']['Param'] . '-set-false';
        } else {
            $CIDRAM['FE']['UpdatesFormTarget'] .= '&' . $CIDRAM['QueryTemp']['Param'] . '=false';
            $CIDRAM['QueryTemp']['LangOpt'] = 'switch-' . $CIDRAM['QueryTemp']['Param'] . '-set-true';
        }
        $CIDRAM['FE']['UpdatesFormTargetControls'] .=
            '<option value="' . $CIDRAM['QueryTemp']['Param'] . '">' . $CIDRAM['lang'][$CIDRAM['QueryTemp']['LangOpt']] . '</option>';
    }
    if ($CIDRAM['QueryTemp']['Switched']) {
        header('Location: ?' . $CIDRAM['FE']['UpdatesFormTarget']);
        die;
    }
    unset($CIDRAM['QueryTemp']);

    /** Prepare components metadata working array. */
    $CIDRAM['Components'] = array('Meta' => array(), 'RemoteMeta' => array());

    /** Fetch components lists. */
    $CIDRAM['Components']['Files'] = new DirectoryIterator($CIDRAM['Vault']);

    /** Count files; Prepare to search for components metadata. */
    foreach ($CIDRAM['Components']['Files'] as $CIDRAM['ThisFile']) {
        if (!empty($CIDRAM['ThisFile']) && preg_match('/\.(?:dat|inc|yaml)$/i', $CIDRAM['ThisFile'])) {
            $CIDRAM['ThisData'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['ThisFile']);
            if (substr($CIDRAM['ThisData'], 0, 4) === "---\n" && ($CIDRAM['EoYAML']= strpos($CIDRAM['ThisData'], "\n\n")) !== false) {
                $CIDRAM['YAML'](substr($CIDRAM['ThisData'], 4, $CIDRAM['EoYAML'] - 4), $CIDRAM['Components']['Meta']);
            }
        }
    }

    /** Search cleanup. */
    unset($CIDRAM['EoYAML'], $CIDRAM['ThisData'], $CIDRAM['ThisFile'], $CIDRAM['Components']['Files']);

    /** Indexes. */
    $CIDRAM['FE']['Indexes'] = array();

    /** A form has been submitted. */
    if ($CIDRAM['FE']['FormTarget'] === 'updates' && !empty($_POST['do'])) {

        /** Update a component. */
        if (
            $_POST['do'] === 'update-component' &&
            !empty($_POST['ID']) &&
            isset($CIDRAM['Components']['Meta'][$_POST['ID']]['Remote'])
        ) {
            $CIDRAM['Components']['Meta'][$_POST['ID']]['RemoteData'] = $CIDRAM['FECacheGet'](
                $CIDRAM['FE']['Cache'],
                $CIDRAM['Components']['Meta'][$_POST['ID']]['Remote']
            );
            if (!$CIDRAM['Components']['Meta'][$_POST['ID']]['RemoteData']) {
                $CIDRAM['Components']['Meta'][$_POST['ID']]['RemoteData'] =
                    $CIDRAM['Request']($CIDRAM['Components']['Meta'][$_POST['ID']]['Remote']);
                if (
                    strtolower(substr($CIDRAM['Components']['Meta'][$_POST['ID']]['Remote'], -2)) === 'gz' &&
                    substr($CIDRAM['Components']['Meta'][$_POST['ID']]['RemoteData'], 0, 2) === "\x1f\x8b"
                ) {
                    $CIDRAM['Components']['Meta'][$_POST['ID']]['RemoteData'] =
                        gzdecode($CIDRAM['Components']['Meta'][$_POST['ID']]['RemoteData']);
                }
                if (empty($CIDRAM['Components']['Meta'][$_POST['ID']]['RemoteData'])) {
                    $CIDRAM['Components']['Meta'][$_POST['ID']]['RemoteData'] = '-';
                }
                $CIDRAM['FECacheAdd'](
                    $CIDRAM['FE']['Cache'],
                    $CIDRAM['FE']['Rebuild'],
                    $CIDRAM['Components']['Meta'][$_POST['ID']]['Remote'],
                    $CIDRAM['Components']['Meta'][$_POST['ID']]['RemoteData'],
                    $CIDRAM['Now'] + 3600
                );
            }
            if (
                substr($CIDRAM['Components']['Meta'][$_POST['ID']]['RemoteData'], 0, 4) === "---\n" &&
                ($CIDRAM['Components']['EoYAML'] = strpos(
                    $CIDRAM['Components']['Meta'][$_POST['ID']]['RemoteData'], "\n\n"
                )) !== false &&
                $CIDRAM['YAML'](
                    substr($CIDRAM['Components']['Meta'][$_POST['ID']]['RemoteData'], 4, $CIDRAM['Components']['EoYAML'] - 4),
                    $CIDRAM['Components']['RemoteMeta']
                ) &&
                !empty($CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Minimum Required']) &&
                !$CIDRAM['VersionCompare'](
                    $CIDRAM['ScriptVersion'],
                    $CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Minimum Required']
                ) &&
                (
                    empty($CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Minimum Required PHP']) ||
                    !$CIDRAM['VersionCompare'](PHP_VERSION, $CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Minimum Required PHP'])
                ) &&
                !empty($CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Files']['From']) &&
                !empty($CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Files']['To']) &&
                !empty($CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Reannotate']) &&
                $CIDRAM['Traverse']($CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Reannotate']) &&
                file_exists($CIDRAM['Vault'] . $CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Reannotate']) &&
                ($CIDRAM['Components']['OldMeta'] = $CIDRAM['ReadFile'](
                    $CIDRAM['Vault'] . $CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Reannotate']
                )) &&
                preg_match(
                    "\x01(\n" . preg_quote($_POST['ID']) . ":?)(\n [^\n]*)*\n\x01i",
                    $CIDRAM['Components']['OldMeta'],
                    $CIDRAM['Components']['OldMetaMatches']
                ) &&
                ($CIDRAM['Components']['OldMetaMatches'] = $CIDRAM['Components']['OldMetaMatches'][0]) &&
                ($CIDRAM['Components']['NewMeta'] = $CIDRAM['Components']['Meta'][$_POST['ID']]['RemoteData']) &&
                preg_match(
                    "\x01(\n" . preg_quote($_POST['ID']) . ":?)(\n [^\n]*)*\n\x01i",
                    $CIDRAM['Components']['NewMeta'],
                    $CIDRAM['Components']['NewMetaMatches']
                ) &&
                ($CIDRAM['Components']['NewMetaMatches'] = $CIDRAM['Components']['NewMetaMatches'][0])
            ) {
                $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Files']);
                $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Files']['From']);
                $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Files']['To']);
                if (!empty($CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Files']['Checksum'])) {
                    $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Files']['Checksum']);
                }
                $CIDRAM['Components']['NewMeta'] = str_replace(
                    $CIDRAM['Components']['OldMetaMatches'],
                    $CIDRAM['Components']['NewMetaMatches'],
                    $CIDRAM['Components']['OldMeta']
                );
                $CIDRAM['Count'] = count($CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Files']['From']);
                $CIDRAM['RemoteFiles'] = array();
                for (
                    $CIDRAM['Iterate'] = 0;
                    $CIDRAM['Iterate'] < $CIDRAM['Count'];
                    $CIDRAM['Iterate']++
                ) {
                    if (empty($CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Files']['To'][$CIDRAM['Iterate']])) {
                        continue;
                    }
                    $CIDRAM['ThisFileName'] = $CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Files']['To'][$CIDRAM['Iterate']];
                    $CIDRAM['RemoteFiles'][$CIDRAM['ThisFileName']] = true;
                    if (
                        (
                            !empty($CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Files']['Checksum'][$CIDRAM['Iterate']]) &&
                            !empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['Checksum'][$CIDRAM['Iterate']]) && (
                                $CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Files']['Checksum'][$CIDRAM['Iterate']] ===
                                $CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['Checksum'][$CIDRAM['Iterate']]
                            )
                        ) ||
                        empty($CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Files']['From'][$CIDRAM['Iterate']]) ||
                        !($CIDRAM['ThisFile'] = $CIDRAM['Request'](
                            $CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Files']['From'][$CIDRAM['Iterate']]
                        ))
                    ) {
                        continue;
                    }
                    if (
                        strtolower(substr(
                            $CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Files']['From'][$CIDRAM['Iterate']], -2
                        )) === 'gz' &&
                        strtolower(substr($CIDRAM['ThisFileName'], -2)) !== 'gz' &&
                        substr($CIDRAM['ThisFile'], 0, 2) === "\x1f\x8b"
                    ) {
                        $CIDRAM['ThisFile'] = gzdecode($CIDRAM['ThisFile']);
                    }
                    if (
                        !empty($CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Files']['Checksum'][$CIDRAM['Iterate']]) &&
                            $CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Files']['Checksum'][$CIDRAM['Iterate']] !==
                            md5($CIDRAM['ThisFile']) . ':' . strlen($CIDRAM['ThisFile'])
                    ) {
                        continue;
                    }
                    $CIDRAM['ThisName'] = $CIDRAM['ThisFileName'];
                    $CIDRAM['ThisPath'] = $CIDRAM['Vault'];
                    while (strpos($CIDRAM['ThisName'], '/') !== false || strpos($CIDRAM['ThisName'], "\\") !== false) {
                        $CIDRAM['Separator'] = (strpos($CIDRAM['ThisName'], '/') !== false) ? '/' : "\\";
                        $CIDRAM['ThisDir'] = substr($CIDRAM['ThisName'], 0, strpos($CIDRAM['ThisName'], $CIDRAM['Separator']));
                        $CIDRAM['ThisPath'] .= $CIDRAM['ThisDir'] . '/';
                        $CIDRAM['ThisName'] = substr($CIDRAM['ThisName'], strlen($CIDRAM['ThisDir']) + 1);
                        if (!file_exists($CIDRAM['ThisPath']) || !is_dir($CIDRAM['ThisPath'])) {
                            mkdir($CIDRAM['ThisPath']);
                        }
                    }
                    $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . $CIDRAM['ThisFileName'], 'w');
                    fwrite($CIDRAM['Handle'], $CIDRAM['ThisFile']);
                    fclose($CIDRAM['Handle']);
                    $CIDRAM['ThisFile'] = '';
                }
                if (
                    !empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To']) &&
                    is_array($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To'])
                ) {
                    array_walk($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To'], function ($ThisFile) use (&$CIDRAM) {
                        if (
                            !empty($ThisFile) &&
                            !isset($CIDRAM['RemoteFiles'][$ThisFile]) &&
                            file_exists($CIDRAM['Vault'] . $ThisFile) &&
                            $CIDRAM['Traverse']($ThisFile)
                        ) {
                            unlink($CIDRAM['Vault'] . $ThisFile);
                            while (strrpos($ThisFile, '/') !== false || strrpos($ThisFile, "\\") !== false) {
                                $Separator = (strrpos($ThisFile, '/') !== false) ? '/' : "\\";
                                $ThisFile = substr($ThisFile, 0, strrpos($ThisFile, $Separator));
                                if (
                                    is_dir($CIDRAM['Vault'] . $ThisFile) &&
                                    $CIDRAM['FileManager-IsDirEmpty']($CIDRAM['Vault'] . $ThisFile)
                                ) {
                                    rmdir($CIDRAM['Vault'] . $ThisFile);
                                } else {
                                    break;
                                }
                            }
                        }
                    });
                }
                $CIDRAM['Handle'] =
                    fopen($CIDRAM['Vault'] . $CIDRAM['Components']['RemoteMeta'][$_POST['ID']]['Reannotate'], 'w');
                fwrite($CIDRAM['Handle'], $CIDRAM['Components']['NewMeta']);
                fclose($CIDRAM['Handle']);
                $CIDRAM['FE']['state_msg'] =
                    (
                        empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Version']) &&
                        empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Files'])
                    ) ?
                    $CIDRAM['lang']['response_component_successfully_installed'] :
                    $CIDRAM['lang']['response_component_successfully_updated'];
                $CIDRAM['Components']['Meta'][$_POST['ID']] =
                    $CIDRAM['Components']['RemoteMeta'][$_POST['ID']];
            } else {
                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_component_update_error'];
            }
        }

        /** Uninstall a component. */
        if ($_POST['do'] === 'uninstall-component' && !empty($_POST['ID'])) {
            $CIDRAM['ComponentFunctionUpdatePrep']();
            if (
                empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['InUse']) &&
                !empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To']) &&
                ($_POST['ID'] !== 'l10n/' . $CIDRAM['Config']['general']['lang']) &&
                ($_POST['ID'] !== 'CIDRAM') &&
                !empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Reannotate']) &&
                !empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Uninstallable']) &&
                ($CIDRAM['Components']['OldMeta'] = $CIDRAM['ReadFile'](
                    $CIDRAM['Vault'] . $CIDRAM['Components']['Meta'][$_POST['ID']]['Reannotate']
                )) &&
                preg_match(
                    "\x01(\n" . preg_quote($_POST['ID']) . ":?)(\n [^\n]*)*\n\x01i",
                    $CIDRAM['Components']['OldMeta'],
                    $CIDRAM['Components']['OldMetaMatches']
                ) &&
                ($CIDRAM['Components']['OldMetaMatches'] = $CIDRAM['Components']['OldMetaMatches'][0])
            ) {
                $CIDRAM['Components']['NewMeta'] = str_replace(
                    $CIDRAM['Components']['OldMetaMatches'],
                    preg_replace(
                        array(
                            "/\n Files:(\n  [^\n]*)*\n/i",
                            "/\n Version: [^\n]*\n/i",
                        ),
                        "\n",
                        $CIDRAM['Components']['OldMetaMatches']
                    ),
                    $CIDRAM['Components']['OldMeta']
                );
                array_walk($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To'], function ($ThisFile) use (&$CIDRAM) {
                    if (
                        !empty($ThisFile) &&
                        file_exists($CIDRAM['Vault'] . $ThisFile) &&
                        $CIDRAM['Traverse']($ThisFile)
                    ) {
                        unlink($CIDRAM['Vault'] . $ThisFile);
                        while (strrpos($ThisFile, '/') !== false || strrpos($ThisFile, "\\") !== false) {
                            $Separator = (strrpos($ThisFile, '/') !== false) ? '/' : "\\";
                            $ThisFile = substr($ThisFile, 0, strrpos($ThisFile, $Separator));
                            if (
                                is_dir($CIDRAM['Vault'] . $ThisFile) &&
                                $CIDRAM['FileManager-IsDirEmpty']($CIDRAM['Vault'] . $ThisFile)
                            ) {
                                rmdir($CIDRAM['Vault'] . $ThisFile);
                            } else {
                                break;
                            }
                        }
                    }
                });
                $CIDRAM['Handle'] =
                    fopen($CIDRAM['Vault'] . $CIDRAM['Components']['Meta'][$_POST['ID']]['Reannotate'], 'w');
                fwrite($CIDRAM['Handle'], $CIDRAM['Components']['NewMeta']);
                fclose($CIDRAM['Handle']);
                $CIDRAM['Components']['Meta'][$_POST['ID']]['Version'] = false;
                $CIDRAM['Components']['Meta'][$_POST['ID']]['Files'] = false;
                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_component_successfully_uninstalled'];
            } else {
                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_component_uninstall_error'];
            }
        }

        /** Activate a component. */
        if ($_POST['do'] === 'activate-component' && !empty($_POST['ID'])) {
            $CIDRAM['Activation'] = array(
                'Config' => $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'config.ini'),
                'ipv4' => $CIDRAM['Config']['signatures']['ipv4'],
                'ipv6' => $CIDRAM['Config']['signatures']['ipv6'],
                'modules' => $CIDRAM['Config']['signatures']['modules'],
                'modified' => false
            );
            $CIDRAM['ComponentFunctionUpdatePrep']();
            if (
                empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['InUse']) &&
                !empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To']) &&
                !empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Extended Description'])
            ) {
                if (strpos($CIDRAM['Components']['Meta'][$_POST['ID']]['Extended Description'], 'signatures-&gt;ipv4') !== false) {
                    $CIDRAM['ActivateComponent']('ipv4');
                }
                if (strpos($CIDRAM['Components']['Meta'][$_POST['ID']]['Extended Description'], 'signatures-&gt;ipv6') !== false) {
                    $CIDRAM['ActivateComponent']('ipv6');
                }
                if (strpos($CIDRAM['Components']['Meta'][$_POST['ID']]['Extended Description'], 'signatures-&gt;modules') !== false) {
                    $CIDRAM['ActivateComponent']('modules');
                }
            }
            if (!$CIDRAM['Activation']['modified'] || !$CIDRAM['Activation']['Config']) {
                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_activation_failed'];
            } else {
                $CIDRAM['Activation']['Config'] = str_replace(
                    array(
                        "\r\nipv4='" . $CIDRAM['Config']['signatures']['ipv4'] . "'\r\n",
                        "\r\nipv6='" . $CIDRAM['Config']['signatures']['ipv6'] . "'\r\n",
                        "\r\nmodules='" . $CIDRAM['Config']['signatures']['modules'] . "'\r\n"
                    ), array(
                        "\r\nipv4='" . $CIDRAM['Activation']['ipv4'] . "'\r\n",
                        "\r\nipv6='" . $CIDRAM['Activation']['ipv6'] . "'\r\n",
                        "\r\nmodules='" . $CIDRAM['Activation']['modules'] . "'\r\n"
                    ),
                    $CIDRAM['Activation']['Config']
                );
                $CIDRAM['Config']['signatures']['ipv4'] = $CIDRAM['Activation']['ipv4'];
                $CIDRAM['Config']['signatures']['ipv6'] = $CIDRAM['Activation']['ipv6'];
                $CIDRAM['Config']['signatures']['modules'] = $CIDRAM['Activation']['modules'];
                $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'config.ini', 'w');
                fwrite($CIDRAM['Handle'], $CIDRAM['Activation']['Config']);
                fclose($CIDRAM['Handle']);
                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_activated'];
            }
            unset($CIDRAM['Activation']);
        }

        /** Deactivate a component. */
        if ($_POST['do'] === 'deactivate-component' && !empty($_POST['ID'])) {
            $CIDRAM['Deactivation'] = array(
                'Config' => $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'config.ini'),
                'ipv4' => $CIDRAM['Config']['signatures']['ipv4'],
                'ipv6' => $CIDRAM['Config']['signatures']['ipv6'],
                'modules' => $CIDRAM['Config']['signatures']['modules'],
                'modified' => false
            );
            if (!empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Files'])) {
                $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']);
                $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To']);
                $CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['InUse'] =
                    $CIDRAM['IsInUse']($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To'], '');
            }
            if (
                !empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['InUse']) &&
                !empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To'])
            ) {
                $CIDRAM['DeactivateComponent']('ipv4');
                $CIDRAM['DeactivateComponent']('ipv6');
                $CIDRAM['DeactivateComponent']('modules');
            }
            if (!$CIDRAM['Deactivation']['modified'] || !$CIDRAM['Deactivation']['Config']) {
                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_deactivation_failed'];
            } else {
                $CIDRAM['Deactivation']['Config'] = str_replace(
                    array(
                        "\r\nipv4='" . $CIDRAM['Config']['signatures']['ipv4'] . "'\r\n",
                        "\r\nipv6='" . $CIDRAM['Config']['signatures']['ipv6'] . "'\r\n",
                        "\r\nmodules='" . $CIDRAM['Config']['signatures']['modules'] . "'\r\n"
                    ), array(
                        "\r\nipv4='" . $CIDRAM['Deactivation']['ipv4'] . "'\r\n",
                        "\r\nipv6='" . $CIDRAM['Deactivation']['ipv6'] . "'\r\n",
                        "\r\nmodules='" . $CIDRAM['Deactivation']['modules'] . "'\r\n"
                    ),
                    $CIDRAM['Deactivation']['Config']
                );
                $CIDRAM['Config']['signatures']['ipv4'] = $CIDRAM['Deactivation']['ipv4'];
                $CIDRAM['Config']['signatures']['ipv6'] = $CIDRAM['Deactivation']['ipv6'];
                $CIDRAM['Config']['signatures']['modules'] = $CIDRAM['Deactivation']['modules'];
                $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'config.ini', 'w');
                fwrite($CIDRAM['Handle'], $CIDRAM['Deactivation']['Config']);
                fclose($CIDRAM['Handle']);
                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_deactivated'];
            }
            unset($CIDRAM['Deactivation']);
        }

    }

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_updates'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
        array('username' => $CIDRAM['FE']['UserRaw']),
        $CIDRAM['lang']['tip_updates']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    $CIDRAM['FE']['UpdatesRow'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_updates_row.html');

    $CIDRAM['Components'] = array(
        'Meta' => $CIDRAM['Components']['Meta'],
        'RemoteMeta' => $CIDRAM['Components']['RemoteMeta'],
        'Remotes' => array(),
        'Out' => array()
    );

    reset($CIDRAM['Components']['Meta']);
    /** Prepare installed component metadata and options for display. */
    foreach ($CIDRAM['Components']['Meta'] as $CIDRAM['Components']['Key'] => &$CIDRAM['Components']['ThisComponent']) {
        if (empty($CIDRAM['Components']['ThisComponent']['Name'])) {
            $CIDRAM['Components']['ThisComponent'] = '';
            continue;
        }
        if (is_array($CIDRAM['Components']['ThisComponent']['Name'])) {
            $CIDRAM['IsolateL10N'](
                $CIDRAM['Components']['ThisComponent']['Name'],
                $CIDRAM['Config']['general']['lang']
            );
        }
        if (empty($CIDRAM['Components']['ThisComponent']['Extended Description'])) {
            $CIDRAM['Components']['ThisComponent']['Extended Description'] = '';
        }
        if (is_array($CIDRAM['Components']['ThisComponent']['Extended Description'])) {
            $CIDRAM['IsolateL10N'](
                $CIDRAM['Components']['ThisComponent']['Extended Description'],
                $CIDRAM['Config']['general']['lang']
            );
        }
        $CIDRAM['Components']['ThisComponent']['ID'] = $CIDRAM['Components']['Key'];
        $CIDRAM['Components']['ThisComponent']['Options'] = '';
        $CIDRAM['Components']['ThisComponent']['StatusOptions'] = '';
        $CIDRAM['Components']['ThisComponent']['StatClass'] = '';
        if (empty($CIDRAM['Components']['ThisComponent']['Version'])) {
            if (empty($CIDRAM['Components']['ThisComponent']['Files']['To'])) {
                $CIDRAM['Components']['ThisComponent']['RowClass'] = 'h2';
                $CIDRAM['Components']['ThisComponent']['Version'] =
                    $CIDRAM['lang']['response_updates_not_installed'];
                $CIDRAM['Components']['ThisComponent']['StatClass'] = 'txtRd';
                $CIDRAM['Components']['ThisComponent']['StatusOptions'] =
                    $CIDRAM['lang']['response_updates_not_installed'];
            } else {
                $CIDRAM['Components']['ThisComponent']['Version'] =
                    $CIDRAM['lang']['response_updates_unable_to_determine'];
                $CIDRAM['Components']['ThisComponent']['StatClass'] = 's';
            }
        }
        if (empty($CIDRAM['Components']['ThisComponent']['Remote'])) {
            $CIDRAM['Components']['ThisComponent']['RemoteData'] =
                $CIDRAM['lang']['response_updates_unable_to_determine'];
            if (!$CIDRAM['Components']['ThisComponent']['StatClass']) {
                $CIDRAM['Components']['ThisComponent']['StatClass'] = 's';
            }
        } else {
            $CIDRAM['Arrayify']($CIDRAM['Components']['ThisComponent']['Files']);
            $CIDRAM['Arrayify']($CIDRAM['Components']['ThisComponent']['Files']['To']);
            $CIDRAM['Arrayify']($CIDRAM['Components']['ThisComponent']['Files']['From']);
            if (isset($CIDRAM['Components']['ThisComponent']['Files']['Checksum'])) {
                $CIDRAM['Arrayify']($CIDRAM['Components']['ThisComponent']['Files']['Checksum']);
            }
            $CIDRAM['FetchRemote']();
            if (
                substr($CIDRAM['Components']['ThisComponent']['RemoteData'], 0, 4) === "---\n" &&
                ($CIDRAM['Components']['EoYAML'] = strpos(
                    $CIDRAM['Components']['ThisComponent']['RemoteData'], "\n\n"
                )) !== false
            ) {
                if (!isset($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']])) {
                    $CIDRAM['YAML'](
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
            if (!$CIDRAM['Components']['ThisComponent']['StatClass']) {
                if (
                    !empty($CIDRAM['Components']['ThisComponent']['Latest']) &&
                    $CIDRAM['VersionCompare'](
                        $CIDRAM['Components']['ThisComponent']['Version'],
                        $CIDRAM['Components']['ThisComponent']['Latest']
                    )
                ) {
                    $CIDRAM['Components']['ThisComponent']['Outdated'] = true;
                    $CIDRAM['Components']['ThisComponent']['RowClass'] = 'r';
                    $CIDRAM['Components']['ThisComponent']['StatClass'] = 'txtRd';
                    if (
                        empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required']) ||
                        $CIDRAM['VersionCompare'](
                            $CIDRAM['ScriptVersion'],
                            $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required']
                        )
                    ) {
                        $CIDRAM['Components']['ThisComponent']['StatusOptions'] =
                            $CIDRAM['lang']['response_updates_outdated_manually'];
                    } elseif (
                        !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required PHP']) &&
                        $CIDRAM['VersionCompare'](PHP_VERSION, $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required PHP'])
                    ) {
                        $CIDRAM['Components']['ThisComponent']['StatusOptions'] = $CIDRAM['ParseVars'](
                            array('V' => $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required PHP']),
                            $CIDRAM['lang']['response_updates_outdated_php_version']
                        );
                    } else {
                        $CIDRAM['Components']['ThisComponent']['StatusOptions'] =
                            $CIDRAM['lang']['response_updates_outdated'];
                        $CIDRAM['Components']['ThisComponent']['Options'] .=
                            '<option value="update-component">' . $CIDRAM['lang']['field_update'] . '</option>';
                    }
                } else {
                    $CIDRAM['Components']['ThisComponent']['StatClass'] = 'txtGn';
                    $CIDRAM['Components']['ThisComponent']['StatusOptions'] =
                        $CIDRAM['lang']['response_updates_already_up_to_date'];
                }
            }
            if (!empty($CIDRAM['Components']['ThisComponent']['Files']['To'])) {
                $CIDRAM['Activable'] = (
                    strpos($CIDRAM['Components']['ThisComponent']['Extended Description'], 'signatures-&gt;ipv4') !== false ||
                    strpos($CIDRAM['Components']['ThisComponent']['Extended Description'], 'signatures-&gt;ipv6') !== false ||
                    strpos($CIDRAM['Components']['ThisComponent']['Extended Description'], 'signatures-&gt;modules') !== false
                );
                if (
                    ($CIDRAM['Components']['Key'] === 'l10n/' . $CIDRAM['Config']['general']['lang']) ||
                    ($CIDRAM['Components']['Key'] === 'CIDRAM') ||
                    $CIDRAM['IsInUse'](
                        $CIDRAM['Components']['ThisComponent']['Files']['To'],
                        $CIDRAM['Components']['ThisComponent']['Extended Description']
                    )
                ) {
                    $CIDRAM['AppendToString']($CIDRAM['Components']['ThisComponent']['StatusOptions'], '<hr />',
                        '<div class="txtGn">' . $CIDRAM['lang']['state_component_is_active'] . '</div>'
                    );
                    if ($CIDRAM['Activable']) {
                        $CIDRAM['Components']['ThisComponent']['Options'] .=
                            '<option value="deactivate-component">' . $CIDRAM['lang']['field_deactivate'] . '</option>';
                    }
                } else {
                    if ($CIDRAM['Activable']) {
                        $CIDRAM['Components']['ThisComponent']['Options'] .=
                            '<option value="activate-component">' . $CIDRAM['lang']['field_activate'] . '</option>';
                    }
                    if (!empty($CIDRAM['Components']['ThisComponent']['Uninstallable'])) {
                        $CIDRAM['Components']['ThisComponent']['Options'] .=
                            '<option value="uninstall-component">' . $CIDRAM['lang']['field_uninstall'] . '</option>';
                    }
                    if (!empty($CIDRAM['Components']['ThisComponent']['Provisional'])) {
                        $CIDRAM['AppendToString']($CIDRAM['Components']['ThisComponent']['StatusOptions'], '<hr />',
                            '<div class="txtOe">' . $CIDRAM['lang']['state_component_is_provisional'] . '</div>'
                        );
                    } else {
                        $CIDRAM['AppendToString']($CIDRAM['Components']['ThisComponent']['StatusOptions'], '<hr />',
                            '<div class="txtRd">' . $CIDRAM['lang']['state_component_is_inactive'] . '</div>'
                        );
                    }
                }
            }
        }
        if (empty($CIDRAM['Components']['ThisComponent']['Latest'])) {
            $CIDRAM['Components']['ThisComponent']['Latest'] =
                $CIDRAM['lang']['response_updates_unable_to_determine'];
        } elseif (
            empty($CIDRAM['Components']['ThisComponent']['Files']['To']) &&
            !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['To'])
        ) {
            if (
                empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required PHP']) ||
                !$CIDRAM['VersionCompare'](PHP_VERSION, $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required PHP'])
            ) {
                $CIDRAM['Components']['ThisComponent']['Options'] .=
                    '<option value="update-component">' . $CIDRAM['lang']['field_install'] . '</option>';
            } elseif ($CIDRAM['Components']['ThisComponent']['StatusOptions'] == $CIDRAM['lang']['response_updates_not_installed']) {
                $CIDRAM['Components']['ThisComponent']['StatusOptions'] = $CIDRAM['ParseVars'](
                    array('V' => $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required PHP']),
                    $CIDRAM['lang']['response_updates_not_installed_php']
                );
            }
        }
        $CIDRAM['Components']['ThisComponent']['VersionSize'] = 0;
        if (
            !empty($CIDRAM['Components']['ThisComponent']['Files']['Checksum']) &&
            is_array($CIDRAM['Components']['ThisComponent']['Files']['Checksum'])
        ) {
            array_walk($CIDRAM['Components']['ThisComponent']['Files']['Checksum'], function ($Checksum) use (&$CIDRAM) {
                if (!empty($Checksum) && ($Delimiter = strpos($Checksum, ':')) !== false) {
                    $CIDRAM['Components']['ThisComponent']['VersionSize'] +=
                        (int)substr($Checksum, $Delimiter + 1);
                }
            });
        }
        if ($CIDRAM['Components']['ThisComponent']['VersionSize'] > 0) {
            $CIDRAM['FormatFilesize']($CIDRAM['Components']['ThisComponent']['VersionSize']);
            $CIDRAM['Components']['ThisComponent']['VersionSize'] =
                '<br />' . $CIDRAM['lang']['field_size'] .
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
                    $CIDRAM['Components']['ThisComponent']['LatestSize'] +=
                        (int)substr($Checksum, $Delimiter + 1);
                }
            });
        }
        if ($CIDRAM['Components']['ThisComponent']['LatestSize'] > 0) {
            $CIDRAM['FormatFilesize']($CIDRAM['Components']['ThisComponent']['LatestSize']);
            $CIDRAM['Components']['ThisComponent']['LatestSize'] =
                '<br />' . $CIDRAM['lang']['field_size'] .
                $CIDRAM['Components']['ThisComponent']['LatestSize'];
        } else {
            $CIDRAM['Components']['ThisComponent']['LatestSize'] = '';
        }
        if (!empty($CIDRAM['Components']['ThisComponent']['Options'])) {
            $CIDRAM['AppendToString']($CIDRAM['Components']['ThisComponent']['StatusOptions'], '<hr />',
                '<select name="do" class="auto">' . $CIDRAM['Components']['ThisComponent']['Options'] .
                '</select><input type="submit" value="' . $CIDRAM['lang']['field_ok'] . '" class="auto" />'
            );
            $CIDRAM['Components']['ThisComponent']['Options'] = '';
        }
        $CIDRAM['Components']['ThisComponent']['Changelog'] =
            empty($CIDRAM['Components']['ThisComponent']['Changelog']) ? '' :
            '<br /><a href="' . $CIDRAM['Components']['ThisComponent']['Changelog'] . '">Changelog</a>';
        $CIDRAM['Components']['ThisComponent']['Filename'] = (
            empty($CIDRAM['Components']['ThisComponent']['Files']['To']) ||
            count($CIDRAM['Components']['ThisComponent']['Files']['To']) !== 1
        ) ? '' : '<br />' . $CIDRAM['lang']['field_filename'] . $CIDRAM['Components']['ThisComponent']['Files']['To'][0];
        if (
            !($CIDRAM['FE']['hide-non-outdated'] && empty($CIDRAM['Components']['ThisComponent']['Outdated'])) &&
            !($CIDRAM['FE']['hide-unused'] && empty($CIDRAM['Components']['ThisComponent']['Files']['To']))
        ) {
            if (empty($CIDRAM['Components']['ThisComponent']['RowClass'])) {
                $CIDRAM['Components']['ThisComponent']['RowClass'] = 'h1';
            }
            $CIDRAM['FE']['Indexes'][$CIDRAM['Components']['ThisComponent']['ID']] =
                '<a href="#' . $CIDRAM['Components']['ThisComponent']['ID'] . '">' . $CIDRAM['Components']['ThisComponent']['Name'] . "</a><br /><br />\n            ";
            $CIDRAM['Components']['Out'][$CIDRAM['Components']['Key']] = $CIDRAM['ParseVars'](
                $CIDRAM['lang'] + $CIDRAM['ArrayFlatten']($CIDRAM['Components']['ThisComponent']) + $CIDRAM['ArrayFlatten']($CIDRAM['FE']),
                $CIDRAM['FE']['UpdatesRow']
            );
        }
    }

    reset($CIDRAM['Components']['RemoteMeta']);
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
            "\x01(\n" . preg_quote($CIDRAM['Components']['Key']) . ":?)(\n [^\n]*)*\n\x01i",
            $CIDRAM['Components']['ThisComponent']['RemoteData'],
            $CIDRAM['Components']['RemoteDataThis']
        )) {
            continue;
        }
        $CIDRAM['Components']['RemoteDataThis'] = preg_replace(
            array(
                "/\n Files:(\n  [^\n]*)*\n/i",
                "/\n Version: [^\n]*\n/i",
            ),
            "\n",
            $CIDRAM['Components']['RemoteDataThis'][0]
        );
        if (empty($CIDRAM['Components']['Remotes'][$CIDRAM['Components']['ReannotateThis']])) {
            $CIDRAM['Components']['Remotes'][$CIDRAM['Components']['ReannotateThis']] =
                $CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['Components']['ReannotateThis']);
        }
        if (substr(
            $CIDRAM['Components']['Remotes'][$CIDRAM['Components']['ReannotateThis']], -2
        ) !== "\n\n" || substr(
            $CIDRAM['Components']['Remotes'][$CIDRAM['Components']['ReannotateThis']], 0, 4
        ) !== "---\n") {
            continue;
        }
        $CIDRAM['ThisOffset'] = array(0 => array());
        $CIDRAM['ThisOffset'][1] = preg_match(
            '/(\n+)$/',
            $CIDRAM['Components']['Remotes'][$CIDRAM['Components']['ReannotateThis']],
            $CIDRAM['ThisOffset'][0]
        );
        $CIDRAM['ThisOffset'] = strlen($CIDRAM['ThisOffset'][0][0]) * -1;
        $CIDRAM['Components']['Remotes'][$CIDRAM['Components']['ReannotateThis']] = substr(
            $CIDRAM['Components']['Remotes'][$CIDRAM['Components']['ReannotateThis']], 0, $CIDRAM['ThisOffset']
        ) . $CIDRAM['Components']['RemoteDataThis'] . "\n";
        if (is_array($CIDRAM['Components']['ThisComponent']['Name'])) {
            $CIDRAM['IsolateL10N'](
                $CIDRAM['Components']['ThisComponent']['Name'],
                $CIDRAM['Config']['general']['lang']
            );
        }
        if (empty($CIDRAM['Components']['ThisComponent']['Extended Description'])) {
            $CIDRAM['Components']['ThisComponent']['Extended Description'] = '';
        }
        if (is_array($CIDRAM['Components']['ThisComponent']['Extended Description'])) {
            $CIDRAM['IsolateL10N'](
                $CIDRAM['Components']['ThisComponent']['Extended Description'],
                $CIDRAM['Config']['general']['lang']
            );
        }
        $CIDRAM['Components']['ThisComponent']['ID'] = $CIDRAM['Components']['Key'];
        $CIDRAM['Components']['ThisComponent']['Latest'] =
            $CIDRAM['Components']['ThisComponent']['Version'];
        $CIDRAM['Components']['ThisComponent']['Version'] =
            $CIDRAM['lang']['response_updates_not_installed'];
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
                '<br />' . $CIDRAM['lang']['field_size'] .
                $CIDRAM['Components']['ThisComponent']['LatestSize'];
        } else {
            $CIDRAM['Components']['ThisComponent']['LatestSize'] = '';
        }
        $CIDRAM['Components']['ThisComponent']['StatusOptions'] = (
            !empty($CIDRAM['Components']['ThisComponent']['Minimum Required PHP']) &&
            $CIDRAM['VersionCompare'](PHP_VERSION, $CIDRAM['Components']['ThisComponent']['Minimum Required PHP'])
        ) ? $CIDRAM['ParseVars'](
            array('V' => $CIDRAM['Components']['ThisComponent']['Minimum Required PHP']),
            $CIDRAM['lang']['response_updates_not_installed_php']
        ) :
            $CIDRAM['lang']['response_updates_not_installed'] .
            '<br /><select name="do" class="auto"><option value="update-component">' .
            $CIDRAM['lang']['field_install'] . '</option></select><input type="submit" value="' .
            $CIDRAM['lang']['field_ok'] . '" class="auto" />';
        $CIDRAM['Components']['ThisComponent']['Changelog'] =
            empty($CIDRAM['Components']['ThisComponent']['Changelog']) ? '' :
            '<br /><a href="' . $CIDRAM['Components']['ThisComponent']['Changelog'] . '">Changelog</a>';
        $CIDRAM['Components']['ThisComponent']['Filename'] = '';
        if (!$CIDRAM['FE']['hide-unused']) {
            $CIDRAM['FE']['Indexes'][$CIDRAM['Components']['ThisComponent']['ID']] =
                '<a href="#' . $CIDRAM['Components']['ThisComponent']['ID'] . '">' . $CIDRAM['Components']['ThisComponent']['Name'] . "</a><br /><br />\n            ";
            $CIDRAM['Components']['Out'][$CIDRAM['Components']['Key']] = $CIDRAM['ParseVars'](
                $CIDRAM['lang'] + $CIDRAM['ArrayFlatten']($CIDRAM['Components']['ThisComponent']) + $CIDRAM['ArrayFlatten']($CIDRAM['FE']),
                $CIDRAM['FE']['UpdatesRow']
            );
        }
    }
    unset($CIDRAM['Components']['ThisComponent']);

    /** Write annotations for newly found component metadata. */
    array_walk($CIDRAM['Components']['Remotes'], function ($Remote, $Key) use (&$CIDRAM) {
        if (substr($Remote, -2) !== "\n\n" || substr($Remote, 0, 4) !== "---\n") {
            return;
        }
        $Handle = fopen($CIDRAM['Vault'] . $Key, 'w');
        fwrite($Handle, $Remote);
        fclose($Handle);
    });

    /** Finalise output and unset working data. */
    $CIDRAM['UpdatesSortFunc'] = function ($A, $B) {
        $CheckA = preg_match('/^(?:CIDRAM$|IPv[46]|l10n)/i', $A);
        $CheckB = preg_match('/^(?:CIDRAM$|IPv[46]|l10n)/i', $B);
        if ($CheckA && !$CheckB) {
            return -1;
        }
        if ($CheckB && !$CheckA) {
            return 1;
        }
        if ($A < $B) {
            return -1;
        }
        if ($A > $B) {
            return 1;
        }
        return 0;
    };
    uksort($CIDRAM['FE']['Indexes'], $CIDRAM['UpdatesSortFunc']);
    $CIDRAM['FE']['Indexes'] = implode('', $CIDRAM['FE']['Indexes']);
    uksort($CIDRAM['Components']['Out'], $CIDRAM['UpdatesSortFunc']);
    $CIDRAM['FE']['Components'] = implode('', $CIDRAM['Components']['Out']);
    unset($CIDRAM['UpdatesSortFunc'], $CIDRAM['Components'], $CIDRAM['Count'], $CIDRAM['Iterate']);

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_updates.html')
    );

    /** Send output. */
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** File Manager. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'file-manager' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_file_manager'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
        array('username' => $CIDRAM['FE']['UserRaw']),
        $CIDRAM['lang']['tip_file_manager']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    /** Upload a new file. */
    if (isset($_POST['do']) && $_POST['do'] === 'upload-file' && isset($_FILES['upload-file']['name'])) {

        /** Check whether safe. */
        $CIDRAM['SafeToContinue'] = (
            basename($_FILES['upload-file']['name']) === $_FILES['upload-file']['name'] &&
            $CIDRAM['FileManager-PathSecurityCheck']($_FILES['upload-file']['name']) &&
            isset($_FILES['upload-file']['tmp_name']) &&
            isset($_FILES['upload-file']['error']) &&
            $_FILES['upload-file']['error'] === UPLOAD_ERR_OK &&
            is_uploaded_file($_FILES['upload-file']['tmp_name']) &&
            !is_link($CIDRAM['Vault'] . $_FILES['upload-file']['name'])
        );

        /** If the filename already exists, delete the old file before moving the new file. */
        if (
            $CIDRAM['SafeToContinue'] &&
            file_exists($CIDRAM['Vault'] . $_FILES['upload-file']['name']) &&
            is_readable($CIDRAM['Vault'] . $_FILES['upload-file']['name'])
        ) {
            if (is_dir($CIDRAM['Vault'] . $_FILES['upload-file']['name'])) {
                if ($CIDRAM['FileManager-IsDirEmpty']($CIDRAM['Vault'] . $_FILES['upload-file']['name'])) {
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
            $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_file_uploaded'];
        } else {
            $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_upload_error'];
        }

    }

    /** A form was submitted. */
    elseif (
        isset($_POST['filename']) &&
        isset($_POST['do']) &&
        file_exists($CIDRAM['Vault'] . $_POST['filename']) &&
        is_readable($CIDRAM['Vault'] . $_POST['filename']) &&
        $CIDRAM['FileManager-PathSecurityCheck']($_POST['filename'])
    ) {

        /** Delete a file. */
        if ($_POST['do'] === 'delete-file') {

            if (is_dir($CIDRAM['Vault'] . $_POST['filename'])) {
                if ($CIDRAM['FileManager-IsDirEmpty']($CIDRAM['Vault'] . $_POST['filename'])) {
                    rmdir($CIDRAM['Vault'] . $_POST['filename']);
                    $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_directory_deleted'];
                } else {
                    $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_delete_error'];
                }
            } else {
                unlink($CIDRAM['Vault'] . $_POST['filename']);

                /** Remove empty directories. */
                while (strrpos($_POST['filename'], '/') !== false || strrpos($_POST['filename'], "\\") !== false) {
                    $CIDRAM['Separator'] = (strrpos($_POST['filename'], '/') !== false) ? '/' : "\\";
                    $_POST['filename'] = substr($_POST['filename'], 0, strrpos($_POST['filename'], $CIDRAM['Separator']));
                    if (
                        is_dir($CIDRAM['Vault'] . $_POST['filename']) &&
                        $CIDRAM['FileManager-IsDirEmpty']($CIDRAM['Vault'] . $_POST['filename'])
                    ) {
                        rmdir($CIDRAM['Vault'] . $_POST['filename']);
                    } else {
                        break;
                    }
                }

                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_file_deleted'];
            }

        /** Rename a file. */
        } elseif ($_POST['do'] === 'rename-file' && isset($_POST['filename'])) {

            if (isset($_POST['filename_new'])) {

                /** Check whether safe. */
                $CIDRAM['SafeToContinue'] = (
                    $CIDRAM['FileManager-PathSecurityCheck']($_POST['filename']) &&
                    $CIDRAM['FileManager-PathSecurityCheck']($_POST['filename_new'])
                );

                /** If the destination already exists, delete it before renaming the new file. */
                if (
                    $CIDRAM['SafeToContinue'] &&
                    file_exists($CIDRAM['Vault'] . $_POST['filename_new']) &&
                    is_readable($CIDRAM['Vault'] . $_POST['filename_new'])
                ) {
                    if (is_dir($CIDRAM['Vault'] . $_POST['filename_new'])) {
                        if ($CIDRAM['FileManager-IsDirEmpty']($CIDRAM['Vault'] . $_POST['filename_new'])) {
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
                        while (strrpos($_POST['filename'], '/') !== false || strrpos($_POST['filename'], "\\") !== false) {
                            $CIDRAM['Separator'] = (strrpos($_POST['filename'], '/') !== false) ? '/' : "\\";
                            $_POST['filename'] = substr($_POST['filename'], 0, strrpos($_POST['filename'], $CIDRAM['Separator']));
                            if (
                                is_dir($CIDRAM['Vault'] . $_POST['filename']) &&
                                $CIDRAM['FileManager-IsDirEmpty']($CIDRAM['Vault'] . $_POST['filename'])
                            ) {
                                rmdir($CIDRAM['Vault'] . $_POST['filename']);
                            } else {
                                break;
                            }
                        }

                        $CIDRAM['FE']['state_msg'] = (is_dir($CIDRAM['Vault'] . $_POST['filename_new'])) ?
                            $CIDRAM['lang']['response_directory_renamed'] : $CIDRAM['lang']['response_file_renamed'];

                    }

                } elseif (!$CIDRAM['FE']['state_msg']) {
                    $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_rename_error'];
                }

            } else {

                $CIDRAM['FE']['FE_Title'] .= ' – ' . $CIDRAM['lang']['field_rename_file'] . ' – ' . $_POST['filename'];
                $CIDRAM['FE']['filename'] = $_POST['filename'];

                /** Parse output. */
                $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
                    $CIDRAM['lang'] + $CIDRAM['FE'],
                    $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_files_rename.html')
                );

                /** Send output. */
                echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

                die;

            }

        /** Edit a file. */
        } elseif ($_POST['do'] === 'edit-file') {

            if (isset($_POST['content'])) {

                $_POST['content'] = str_replace("\r", '', $_POST['content']);
                $CIDRAM['OldData'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $_POST['filename']);
                if (substr_count($CIDRAM['OldData'], "\r\n") && !substr_count($CIDRAM['OldData'], "\n\n")) {
                    $_POST['content'] = str_replace("\n", "\r\n", $_POST['content']);
                }

                $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . $_POST['filename'], 'w');
                fwrite($CIDRAM['Handle'], $_POST['content']);
                fclose($CIDRAM['Handle']);

                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_file_edited'];

            } else {

                $CIDRAM['FE']['FE_Title'] .= ' – ' . $_POST['filename'];
                $CIDRAM['FE']['filename'] = $_POST['filename'];
                $CIDRAM['FE']['content'] = htmlentities($CIDRAM['ReadFile']($CIDRAM['Vault'] . $_POST['filename']));

                /** Parse output. */
                $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
                    $CIDRAM['lang'] + $CIDRAM['FE'],
                    $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_files_edit.html')
                );

                /** Send output. */
                echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

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
    $CIDRAM['FE']['FilesRow'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_files_row.html');

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_files.html')
    );

    /** Initialise files data variable. */
    $CIDRAM['FE']['FilesData'] = '';

    /** Fetch files data. */
    $CIDRAM['FilesArray'] = $CIDRAM['FileManager-RecursiveList']($CIDRAM['Vault']);

    /** Process files data. */
    array_walk($CIDRAM['FilesArray'], function ($ThisFile) use (&$CIDRAM) {
        $ThisFile['ThisOptions'] = '';
        if (!$ThisFile['Directory'] || $CIDRAM['FileManager-IsDirEmpty']($CIDRAM['Vault'] . $ThisFile['Filename'])) {
            $ThisFile['ThisOptions'] .= '<option value="delete-file">' . $CIDRAM['lang']['field_delete_file'] . '</option>';
            $ThisFile['ThisOptions'] .= '<option value="rename-file">' . $CIDRAM['lang']['field_rename_file'] . '</option>';
        }
        if ($ThisFile['CanEdit']) {
            $ThisFile['ThisOptions'] .= '<option value="edit-file">' . $CIDRAM['lang']['field_edit_file'] . '</option>';
        }
        if (!$ThisFile['Directory']) {
            $ThisFile['ThisOptions'] .= '<option value="download-file">' . $CIDRAM['lang']['field_download_file'] . '</option>';
        }
        if ($ThisFile['ThisOptions']) {
            $ThisFile['ThisOptions'] =
                '<select name="do">' . $ThisFile['ThisOptions'] . '</select>' .
                '<input type="submit" value="' . $CIDRAM['lang']['field_ok'] . '" class="auto" />';
        }
        $CIDRAM['FE']['FilesData'] .= $CIDRAM['ParseVars'](
            $CIDRAM['lang'] + $CIDRAM['FE'] + $ThisFile, $CIDRAM['FE']['FilesRow']
        );
    });

    /** Send output. */
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** IP Test. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'ip-test' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_ip_test'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
        array('username' => $CIDRAM['FE']['UserRaw']),
        $CIDRAM['lang']['tip_ip_test']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    /** Template for result rows. */
    $CIDRAM['FE']['IPTestRow'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_ip_test_row.html');

    /** Initialise results data. */
    $CIDRAM['FE']['IPTestResults'] = '';

    /** IPs were submitted for testing. */
    if (isset($_POST['ip-addr'])) {
        $CIDRAM['FE']['ip-addr'] = $_POST['ip-addr'];
        $_POST['ip-addr'] = array_unique(array_map(function ($IP) {
            return preg_replace("\x01[^0-9a-f:./]\x01i", '', $IP);
        }, explode("\n", $_POST['ip-addr'])));
        natsort($_POST['ip-addr']);
        $CIDRAM['ThisIP'] = array();
        foreach ($_POST['ip-addr'] as $CIDRAM['ThisIP']['IPAddress']) {
            if (!$CIDRAM['ThisIP']['IPAddress']) {
                continue;
            }
            $CIDRAM['BlockInfo'] = array(
                'IPAddr' => $CIDRAM['ThisIP']['IPAddress'],
                'Query' => $CIDRAM['Query'],
                'Referrer' => '',
                'UA' => '',
                'UALC' => '',
                'ReasonMessage' => '',
                'SignatureCount' => 0,
                'Signatures' => '',
                'WhyReason' => '',
                'xmlLang' => $CIDRAM['Config']['general']['lang'],
                'rURI' => 'FE'
            );
            try {
                $CIDRAM['Caught'] = false;
                $CIDRAM['TestResults'] = $CIDRAM['RunTests']($CIDRAM['ThisIP']['IPAddress']);
            } catch (\Exception $e) {
                $CIDRAM['Caught'] = true;
                $CIDRAM['ThisIP']['YesNo'] = $CIDRAM['lang']['response_error'];
                $CIDRAM['ThisIP']['StatClass'] = 'txtOe';
            }
            if (!$CIDRAM['Caught']) {
                if (!$CIDRAM['TestResults']) {
                    $CIDRAM['ThisIP']['YesNo'] = $CIDRAM['lang']['response_error'];
                    $CIDRAM['ThisIP']['StatClass'] = 'txtOe';
                } elseif ($CIDRAM['BlockInfo']['SignatureCount']) {
                    $CIDRAM['ThisIP']['YesNo'] = $CIDRAM['lang']['response_yes'] . ' – ' . $CIDRAM['BlockInfo']['WhyReason'];
                    $CIDRAM['ThisIP']['StatClass'] = 'txtRd';
                } else {
                    $CIDRAM['ThisIP']['YesNo'] = $CIDRAM['lang']['response_no'];
                    $CIDRAM['ThisIP']['StatClass'] = 'txtGn';
                }
            }
            $CIDRAM['FE']['IPTestResults'] .= $CIDRAM['ParseVars'](
                $CIDRAM['lang'] + $CIDRAM['ThisIP'],
                $CIDRAM['FE']['IPTestRow']
            );
        }
    } else {
        $CIDRAM['FE']['ip-addr'] = '';
    }

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_ip_test.html')
    );

    /** Send output. */
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** IP Tracking. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'ip-tracking' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_ip_tracking'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
        array('username' => $CIDRAM['FE']['UserRaw']),
        $CIDRAM['lang']['tip_ip_tracking']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    /** Template for result rows. */
    $CIDRAM['FE']['TrackingRow'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_ip_tracking_row.html');

    /** Initialise variables. */
    $CIDRAM['FE']['TrackingData'] = '';
    $CIDRAM['ThisTracking'] = array();

    /** Fetch cache.dat data. */
    $CIDRAM['Cache'] = (file_exists($CIDRAM['Vault'] . 'cache.dat')) ? unserialize($CIDRAM['ReadFile']($CIDRAM['Vault'] . 'cache.dat')) : array();

    /** Clear/revoke IP tracking for an IP address. */
    if (isset($_POST['IPAddr']) && isset($CIDRAM['Cache']['Tracking'][$_POST['IPAddr']])) {
        unset($CIDRAM['Cache']['Tracking'][$_POST['IPAddr']]);
        $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_tracking_cleared'];
        $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'cache.dat', 'w');
        fwrite($CIDRAM['Handle'], serialize($CIDRAM['Cache']));
        fclose($CIDRAM['Handle']);
    }

    /** Process IP tracking data. */
    if (!empty($CIDRAM['Cache']['Tracking']) && is_array($CIDRAM['Cache']['Tracking'])) {
        uasort($CIDRAM['Cache']['Tracking'], function ($A, $B) {
            if (empty($A['Time']) || empty($B['Time']) || $A['Time'] === $B['Time']) {
                return 0;
            }
            return ($A['Time'] < $B['Time']) ? -1 : 1;
        });
        foreach ($CIDRAM['Cache']['Tracking'] as $CIDRAM['ThisTracking']['IPAddr'] => $CIDRAM['ThisTrackingArr']) {
            if (!isset($CIDRAM['ThisTrackingArr']['Time']) || !isset($CIDRAM['ThisTrackingArr']['Count'])) {
                continue;
            }
            $CIDRAM['ThisTracking']['Expiry'] = date('r', $CIDRAM['ThisTrackingArr']['Time']);
            if ($CIDRAM['ThisTrackingArr']['Count'] >= $CIDRAM['Config']['signatures']['infraction_limit']) {
                $CIDRAM['ThisTracking']['StatClass'] = 'txtRd';
                $CIDRAM['ThisTracking']['Status'] = $CIDRAM['lang']['field_banned'];
            } elseif ($CIDRAM['ThisTrackingArr']['Count'] > ($CIDRAM['Config']['signatures']['infraction_limit'] / 2)) {
                $CIDRAM['ThisTracking']['StatClass'] = 'txtOe';
                $CIDRAM['ThisTracking']['Status'] = $CIDRAM['lang']['field_tracking'];
            } else {
                $CIDRAM['ThisTracking']['StatClass'] = 's';
                $CIDRAM['ThisTracking']['Status'] = $CIDRAM['lang']['field_tracking'];
            }
            $CIDRAM['ThisTracking']['Status'] .= ' – ' . number_format($CIDRAM['ThisTrackingArr']['Count']);
            $CIDRAM['FE']['TrackingData'] .= $CIDRAM['ParseVars'](
                $CIDRAM['lang'] + $CIDRAM['ThisTracking'],
                $CIDRAM['FE']['TrackingRow']
            );
        }
    }

    /** Cleanup. */
    unset($CIDRAM['Cache'], $CIDRAM['ThisTracking']);

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_ip_tracking.html')
    );

    /** Send output. */
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** CIDR Calculator. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'cidr-calc' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_cidr_calc'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
        array('username' => $CIDRAM['FE']['UserRaw']),
        $CIDRAM['lang']['tip_cidr_calc']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    /** Template for result rows. */
    $CIDRAM['FE']['CalcRow'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_cidr_calc_row.html');

    /** Initialise results data. */
    $CIDRAM['FE']['Ranges'] = '';

    /** IPs were submitted for testing. */
    if (isset($_POST['cidr'])) {
        $CIDRAM['FE']['cidr'] = $_POST['cidr'];
        if ($_POST['cidr'] = preg_replace("\x01[^0-9a-f:./]\x01i", '', $_POST['cidr'])) {
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
                $Last = $CIDRAM['lang']['response_error'];
            }
            $Arr = array('CIDR' => $CIDR, 'Range' => $First . ' – ' . $Last);
            $CIDRAM['FE']['Ranges'] .= $CIDRAM['ParseVars']($Arr, $CIDRAM['FE']['CalcRow']);
        });
    }

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_cidr_calc.html')
    );

    /** Send output. */
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** Logs. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'logs') {

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_logs'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
        array('username' => $CIDRAM['FE']['UserRaw']),
        $CIDRAM['lang']['tip_logs']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_logs.html')
    );

    /** Initialise array for fetching logs data. */
    $CIDRAM['FE']['LogFiles'] = array(
        'Files' => $CIDRAM['Logs-RecursiveList']($CIDRAM['Vault']),
        'Out' => ''
    );

    if (empty($CIDRAM['QueryVars']['logfile'])) {
        $CIDRAM['FE']['logfileData'] = $CIDRAM['lang']['logs_no_logfile_selected'];
    } elseif (empty($CIDRAM['FE']['LogFiles']['Files'][$CIDRAM['QueryVars']['logfile']])) {
        $CIDRAM['FE']['logfileData'] = $CIDRAM['lang']['logs_logfile_doesnt_exist'];
    } else {
        $CIDRAM['FE']['logfileData'] = str_replace(
            array('<', '>', "\r", "\n"),
            array('&lt;', '&gt;', '', "<br />\n"),
            $CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['QueryVars']['logfile'])
        );
    }

    array_walk($CIDRAM['FE']['LogFiles']['Files'], function ($Arr) use (&$CIDRAM) {
        $CIDRAM['FE']['LogFiles']['Out'] .= sprintf(
            '            <a href="?cidram-page=logs&logfile=%1$s">%1$s</a> – %2$s<br />',
            $Arr['Filename'],
            $Arr['Filesize']
        ) . "\n";
    });

    if (!$CIDRAM['FE']['LogFiles'] = $CIDRAM['FE']['LogFiles']['Out']) {
        $CIDRAM['FE']['LogFiles'] = $CIDRAM['lang']['logs_no_logfiles_available'];
    }

    /** Send output. */
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** Rebuild cache. */
if ($CIDRAM['FE']['Rebuild']) {
    $CIDRAM['FE']['FrontEndData'] =
        "USERS\n-----" . $CIDRAM['FE']['UserList'] .
        "\nSESSIONS\n--------" . $CIDRAM['FE']['SessionList'] .
        "\nCACHE\n-----" . $CIDRAM['FE']['Cache'];
    $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'fe_assets/frontend.dat', 'w');
    fwrite($CIDRAM['Handle'], $CIDRAM['FE']['FrontEndData']);
    fclose($CIDRAM['Handle']);
}

die;
