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
 * This file: Front-end handler (last modified: 2016.12.04).
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
    return !preg_match("\x01" . '(?:[\./]{2}|[\x01-\x1f\[-`?*$])' . "\x01i", str_replace("\\", '/', $Path));
};

/** A fix for correctly displaying LTR/RTL text. */
if (empty($CIDRAM['lang']['textDir']) || $CIDRAM['lang']['textDir'] !== 'rtl') {
    $CIDRAM['lang']['textDir'] = 'ltr';
    $CIDRAM['FE']['FE_Align'] = 'left';
    $CIDRAM['FE']['FE_Align_Reverse'] = 'right';
    $CIDRAM['FE']['PIP_Input'] = $CIDRAM['FE']['PIP_Right'];
} else {
    $CIDRAM['FE']['FE_Align'] = 'right';
    $CIDRAM['FE']['FE_Align_Reverse'] = 'left';
    $CIDRAM['FE']['PIP_Input'] = $CIDRAM['FE']['PIP_Left'];
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
                setcookie('CIDRAM-ADMIN', $CIDRAM['FE']['Cookie'], $CIDRAM['Now'] + 604800, '/', (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '', false, true);
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
        $CIDRAM['FrontEndLog'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['Config']['general']['FrontEndLog']);
        $CIDRAM['FrontEndLog'] .=
            $_SERVER[$CIDRAM['Config']['general']['ipaddr']] . ' - ' . $CIDRAM['FE']['DateTime'] . ' - ';
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
            $CIDRAM['FE']['state_msg'] = $CIDRAM['WrapRedText']($CIDRAM['FE']['state_msg']);
        }
    } elseif ($CIDRAM['Config']['general']['FrontEndLog']) {
        $CIDRAM['FrontEndLog'] .= ' - ' . $CIDRAM['lang']['state_logged_in'] . "\n";
    }
    if ($CIDRAM['Config']['general']['FrontEndLog']) {
        $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . $CIDRAM['Config']['general']['FrontEndLog'], 'w');
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
        setcookie('CIDRAM-ADMIN', '', -1, '/', (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '', false, true);

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

    /** Generate entries for display and regenerate configuration if any changes were submitted. */
    reset($CIDRAM['Config']['Config Defaults']);
    $CIDRAM['CountCats'] = count($CIDRAM['Config']['Config Defaults']);
    $CIDRAM['FE']['ConfigFields'] = $CIDRAM['RegenerateConfig'] = '';
    $CIDRAM['ConfigModified'] = (!empty($CIDRAM['QueryVars']['updated']) && $CIDRAM['QueryVars']['updated'] === 'true');
    for ($CIDRAM['IterateCats'] = 0; $CIDRAM['IterateCats'] < $CIDRAM['CountCats']; $CIDRAM['IterateCats']++) {
        $CIDRAM['CatKey'] = key($CIDRAM['Config']['Config Defaults']);
        next($CIDRAM['Config']['Config Defaults']);
        if (!is_array($CIDRAM['Config']['Config Defaults'][$CIDRAM['CatKey']])) {
            continue;
        }
        $CIDRAM['RegenerateConfig'] .= '[' . $CIDRAM['CatKey'] . "]\r\n\r\n";
        reset($CIDRAM['Config']['Config Defaults'][$CIDRAM['CatKey']]);
        $CIDRAM['CountDirs'] = count($CIDRAM['Config']['Config Defaults'][$CIDRAM['CatKey']]);
        for ($CIDRAM['IterateDirs'] = 0; $CIDRAM['IterateDirs'] < $CIDRAM['CountDirs']; $CIDRAM['IterateDirs']++) {
            unset($CIDRAM['ThisDir']);
            $CIDRAM['ThisDir'] = array(
                'DirKey' => key($CIDRAM['Config']['Config Defaults'][$CIDRAM['CatKey']]),
                'FieldOut' => ''
            );
            next($CIDRAM['Config']['Config Defaults'][$CIDRAM['CatKey']]);
            $CIDRAM['DirDefault'] = $CIDRAM['Config']['Config Defaults'][$CIDRAM['CatKey']][$CIDRAM['ThisDir']['DirKey']];
            if (empty($CIDRAM['DirDefault']['type']) || !isset($CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['ThisDir']['DirKey']])) {
                continue;
            }
            $CIDRAM['ThisDir']['Actual'] = &$CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['ThisDir']['DirKey']];
            $CIDRAM['ThisDir']['DirLangKey'] = 'config_' . $CIDRAM['CatKey'] . '_' . $CIDRAM['ThisDir']['DirKey'];
            $CIDRAM['ThisDir']['DirLang'] =
                (!empty($CIDRAM['lang'][$CIDRAM['ThisDir']['DirLangKey']])) ? $CIDRAM['lang'][$CIDRAM['ThisDir']['DirLangKey']] : $CIDRAM['lang']['response_error'];
            $CIDRAM['RegenerateConfig'] .= '; ' . wordwrap($CIDRAM['ThisDir']['DirLang'], 77, "\r\n; ") . "\r\n";
            if (isset($_POST[$CIDRAM['ThisDir']['DirLangKey']])) {
                if ($CIDRAM['DirDefault']['type'] === 'string' || $CIDRAM['DirDefault']['type'] === 'int' || $CIDRAM['DirDefault']['type'] === 'bool') {
                    $CIDRAM['AutoType']($_POST[$CIDRAM['ThisDir']['DirLangKey']], $CIDRAM['DirDefault']['type']);
                } elseif ($CIDRAM['DirDefault']['type'] === 'bool|int') {
                    $CIDRAM['AutoType']($_POST[$CIDRAM['ThisDir']['DirLangKey']]);
                }
                if (
                    !preg_match("/[\"'\x01-\x1f]/i", $_POST[$CIDRAM['ThisDir']['DirLangKey']]) && (
                        !isset($CIDRAM['DirDefault']['choices']) || isset($CIDRAM['DirDefault']['choices'][$_POST[$CIDRAM['ThisDir']['DirLangKey']]])
                    )
                ) {
                    $CIDRAM['ThisDir']['Actual'] = $_POST[$CIDRAM['ThisDir']['DirLangKey']];
                    $CIDRAM['ConfigModified'] = true;
                }
            }
            if ($CIDRAM['ThisDir']['Actual'] === true || ($CIDRAM['DirDefault']['type'] === 'bool|int' && $CIDRAM['ThisDir']['Actual'])) {
                $CIDRAM['RegenerateConfig'] .= $CIDRAM['ThisDir']['DirKey'] . "=true\r\n\r\n";
            } elseif ($CIDRAM['ThisDir']['Actual'] === false || ($CIDRAM['DirDefault']['type'] === 'bool|int' && !$CIDRAM['ThisDir']['Actual'])) {
                $CIDRAM['RegenerateConfig'] .= $CIDRAM['ThisDir']['DirKey'] . "=false\r\n\r\n";
            } elseif ($CIDRAM['DirDefault']['type'] === 'int') {
                $CIDRAM['RegenerateConfig'] .= $CIDRAM['ThisDir']['DirKey'] . '=' . $CIDRAM['ThisDir']['Actual'] . "\r\n\r\n";
            } else {
                $CIDRAM['RegenerateConfig'] .= $CIDRAM['ThisDir']['DirKey'] . '=\'' . $CIDRAM['ThisDir']['Actual'] . "'\r\n\r\n";
            }
            if (isset($CIDRAM['DirDefault']['choices'])) {
                $CIDRAM['ThisDir']['FieldOut'] = '<select name="'. $CIDRAM['ThisDir']['DirLangKey'] . '">';
                reset($CIDRAM['DirDefault']['choices']);
                $CIDRAM['CountChoices'] = count($CIDRAM['DirDefault']['choices']);
                for ($CIDRAM['IterateChoices'] = 0; $CIDRAM['IterateChoices'] < $CIDRAM['CountChoices']; $CIDRAM['IterateChoices']++) {
                    $CIDRAM['ChoiceKey'] = key($CIDRAM['DirDefault']['choices']);
                    next($CIDRAM['DirDefault']['choices']);
                    $CIDRAM['ThisDir']['FieldOut'] .= ($CIDRAM['ChoiceKey'] === $CIDRAM['ThisDir']['Actual']) ?
                        '<option value="' . $CIDRAM['ChoiceKey'] . '" selected>' .
                        $CIDRAM['DirDefault']['choices'][$CIDRAM['ChoiceKey']] . '</option>'
                    :
                        '<option value="' . $CIDRAM['ChoiceKey'] . '">' .
                        $CIDRAM['DirDefault']['choices'][$CIDRAM['ChoiceKey']] . '</option>';
                }
                $CIDRAM['ThisDir']['FieldOut'] .= '</select>';
            } elseif ($CIDRAM['DirDefault']['type'] === 'bool') {
                if ($CIDRAM['ThisDir']['Actual']) {
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
            } elseif ($CIDRAM['DirDefault']['type'] === 'int') {
                $CIDRAM['ThisDir']['FieldOut'] = '<input type="number" name="'. $CIDRAM['ThisDir']['DirLangKey'] . '" value="' . $CIDRAM['ThisDir']['Actual'] . '" />';
            } elseif ($CIDRAM['DirDefault']['type'] === 'string') {
                $CIDRAM['ThisDir']['FieldOut'] = '<textarea class="half" name="'. $CIDRAM['ThisDir']['DirLangKey'] . '">' . $CIDRAM['ThisDir']['Actual'] . '</textarea>';
            } else {
                $CIDRAM['ThisDir']['FieldOut'] = '<input type="text" name="'. $CIDRAM['ThisDir']['DirLangKey'] . '" value="' . $CIDRAM['ThisDir']['Actual'] . '" />';
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
    $CIDRAM['Components'] = array(
        'Files' => scandir($CIDRAM['Vault']),
        'Types' => ',dat,inc,yaml,',
        'Meta' => array(),
        'RemoteMeta' => array(),
    );

    /** Bump main components file to the top of the list. */
    if (file_exists($CIDRAM['Vault'] . 'components.dat')) {
        array_unshift($CIDRAM['Components']['Files'], 'components.dat');
        $CIDRAM['Components']['Files'] = array_unique($CIDRAM['Components']['Files']);
    }

    /** Count files; Prepare to search for components metadata. */
    array_walk($CIDRAM['Components']['Files'], function ($ThisFile) use (&$CIDRAM) {
        if (!empty($ThisFile) && strpos($CIDRAM['Components']['Types'], strtolower(substr($ThisFile, -3))) !== false) {
            $ThisData = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $ThisFile);
            if (substr($ThisData, 0, 4) === "---\n" && ($EoYAML = strpos($ThisData, "\n\n")) !== false) {
                $CIDRAM['YAML'](substr($ThisData, 4, $EoYAML - 4), $CIDRAM['Components']['Meta']);
            }
        }
    });

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
            if (!empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To'])) {
                $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To']);
                $CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['InUse'] = false;
                $CIDRAM['FilesCount'] = count($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To']);
                for (
                    $CIDRAM['FilesIterate'] = 0;
                    $CIDRAM['FilesIterate'] < $CIDRAM['FilesCount'];
                    $CIDRAM['FilesIterate']++
                ) {
                    $CIDRAM['FilesThis'] = $CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To'][$CIDRAM['FilesIterate']];
                    if (
                        strpos(',' . $CIDRAM['Config']['signatures']['ipv4'] . ',', $CIDRAM['FilesThis']) !== false ||
                        strpos(',' . $CIDRAM['Config']['signatures']['ipv6'] . ',', $CIDRAM['FilesThis']) !== false
                    ) {
                        $CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['InUse'] = true;
                        break;
                    }
                }
            }
            if (
                !$CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['InUse'] &&
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
        'Out' => ''
    );

    reset($CIDRAM['Components']['Meta']);
    $CIDRAM['Count'] = count($CIDRAM['Components']['Meta']);

    /** Prepare installed component metadata and options for display. */
    for (
        $CIDRAM['Iterate'] = 0;
        $CIDRAM['Iterate'] < $CIDRAM['Count'];
        $CIDRAM['Iterate']++
    ) {
        $CIDRAM['Components']['Key'] = key($CIDRAM['Components']['Meta']);
        if (empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Name'])) {
            $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']] = '';
            continue;
        }
        if (is_array($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Name'])) {
            $CIDRAM['IsolateL10N'](
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Name'],
                $CIDRAM['Config']['general']['lang']
            );
        }
        if (is_array($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Extended Description'])) {
            $CIDRAM['IsolateL10N'](
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Extended Description'],
                $CIDRAM['Config']['general']['lang']
            );
        }
        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['ID'] = $CIDRAM['Components']['Key'];
        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Options'] = '';
        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatusOptions'] = '';
        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatClass'] = '';
        if (empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Version'])) {
            if (empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files'])) {
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RowClass'] = 'h2';
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Version'] =
                    $CIDRAM['lang']['response_updates_not_installed'];
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatClass'] = 'txtRd';
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatusOptions'] =
                    $CIDRAM['lang']['response_updates_not_installed'];
            } else {
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Version'] =
                    $CIDRAM['lang']['response_updates_unable_to_determine'];
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatClass'] = 's';
            }
        }
        if (empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Remote'])) {
            $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RemoteData'] =
                $CIDRAM['lang']['response_updates_unable_to_determine'];
            if (!$CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatClass']) {
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatClass'] = 's';
            }
        } else {
            $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['To']);
            $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['From']);
            if (isset($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['Checksum'])) {
                $CIDRAM['Arrayify']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['Checksum']);
            }
            $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RemoteData'] = $CIDRAM['FECacheGet'](
                $CIDRAM['FE']['Cache'],
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Remote']
            );
            if (!$CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RemoteData']) {
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RemoteData'] =
                    $CIDRAM['Request']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Remote']);
                if (
                    strtolower(substr(
                        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Remote'], -2
                    )) === 'gz' &&
                    substr($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RemoteData'], 0, 2) === "\x1f\x8b"
                ) {
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RemoteData'] =
                        gzdecode($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RemoteData']);
                }
                if (empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RemoteData'])) {
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RemoteData'] = '-';
                }
                $CIDRAM['FECacheAdd'](
                    $CIDRAM['FE']['Cache'],
                    $CIDRAM['FE']['Rebuild'],
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Remote'],
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RemoteData'],
                    $CIDRAM['Now'] + 3600
                );
            }
            if (
                substr($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RemoteData'], 0, 4) === "---\n" &&
                ($CIDRAM['Components']['EoYAML'] = strpos(
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RemoteData'], "\n\n"
                )) !== false
            ) {
                if (!isset($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']])) {
                    $CIDRAM['YAML'](
                        substr($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RemoteData'], 4, $CIDRAM['Components']['EoYAML'] - 4),
                        $CIDRAM['Components']['RemoteMeta']
                    );
                }
                if (isset($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Version'])) {
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Latest'] =
                        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Version'];
                } else {
                    if (!$CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatClass']) {
                        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatClass'] = 's';
                    }
                }
            } elseif (!$CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatClass']) {
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatClass'] = 's';
            }
            if (!$CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatClass']) {
                if (
                    !empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Latest']) &&
                    $CIDRAM['VersionCompare'](
                        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Version'],
                        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Latest']
                    )
                ) {
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Outdated'] = true;
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RowClass'] = 'r';
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatClass'] = 'txtRd';
                    if (
                        !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required']) &&
                        $CIDRAM['VersionCompare'](
                            $CIDRAM['ScriptVersion'],
                            $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required']
                        )
                    ) {
                        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatusOptions'] =
                            $CIDRAM['lang']['response_updates_outdated_manually'];
                    } else {
                        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatusOptions'] =
                            $CIDRAM['lang']['response_updates_outdated'];
                        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Options'] .=
                            '<option value="update-component">' . $CIDRAM['lang']['field_update'] . '</option>';
                    }
                } else {
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatClass'] = 'txtGn';
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatusOptions'] =
                        $CIDRAM['lang']['response_updates_already_up_to_date'];
                }
            }
            if (!empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['To'])) {
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['InUse'] = false;
                $CIDRAM['FilesCount'] = count($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['To']);
                for (
                    $CIDRAM['FilesIterate'] = 0;
                    $CIDRAM['FilesIterate'] < $CIDRAM['FilesCount'];
                    $CIDRAM['FilesIterate']++
                ) {
                    $CIDRAM['FilesThis'] = $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['To'][$CIDRAM['FilesIterate']];
                    if (
                        strpos(',' . $CIDRAM['Config']['signatures']['ipv4'] . ',', $CIDRAM['FilesThis']) !== false ||
                        strpos(',' . $CIDRAM['Config']['signatures']['ipv6'] . ',', $CIDRAM['FilesThis']) !== false
                    ) {
                        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['InUse'] = true;
                        break;
                    }
                }
            }
            if (!empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files'])) {
                if (
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['InUse'] ||
                    ($CIDRAM['Components']['Key'] === 'l10n/' . $CIDRAM['Config']['general']['lang']) ||
                    ($CIDRAM['Components']['Key'] === 'CIDRAM')
                ) {
                    $CIDRAM['AppendToString']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatusOptions'], '<hr />',
                        '<div class="txtGn">' . $CIDRAM['lang']['state_component_is_active'] . '</div>'
                    );
                } else {
                    if (!empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Uninstallable'])) {
                        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Options'] .=
                            '<option value="uninstall-component">' . $CIDRAM['lang']['field_uninstall'] . '</option>';
                    }
                    if ($CIDRAM['Components']['Key'] === 'Bypasses') {
                        $CIDRAM['AppendToString']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatusOptions'], '<hr />',
                            '<div class="txtOe">' . $CIDRAM['lang']['state_component_is_provisional'] . '</div>'
                        );
                    } else {
                        $CIDRAM['AppendToString']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatusOptions'], '<hr />',
                            '<div class="txtRd">' . $CIDRAM['lang']['state_component_is_inactive'] . '</div>'
                        );
                    }
                }
            }
        }
        if (empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Latest'])) {
            $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Latest'] =
                $CIDRAM['lang']['response_updates_unable_to_determine'];
        } elseif (
            empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']) &&
            !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files'])
        ) {
            $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Options'] .=
                '<option value="update-component">' . $CIDRAM['lang']['field_install'] . '</option>';
        }
        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['VersionSize'] = 0;
        if (
            !empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['Checksum']) &&
            is_array($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['Checksum'])
        ) {
            $CIDRAM['Components']['FilesCount'] =
                count($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['Checksum']);
            for (
                $CIDRAM['FilesIterate'] = 0;
                $CIDRAM['FilesIterate'] < $CIDRAM['Components']['FilesCount'];
                $CIDRAM['FilesIterate']++
            ) {
                if (empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['Checksum'][$CIDRAM['FilesIterate']])) {
                    continue;
                }
                $CIDRAM['FilesThis'] =
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['Checksum'][$CIDRAM['FilesIterate']];
                if (($CIDRAM['FilesDelimit'] = strpos($CIDRAM['FilesThis'], ':')) !== false) {
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['VersionSize'] +=
                        (int)substr($CIDRAM['FilesThis'], $CIDRAM['FilesDelimit'] + 1);
                }
            }
        }
        if ($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['VersionSize'] > 0) {
            $CIDRAM['FormatFilesize']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['VersionSize']);
            $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['VersionSize'] =
                '<br />' . $CIDRAM['lang']['field_size'] .
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['VersionSize'];
        } else {
            $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['VersionSize'] = '';
        }
        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['LatestSize'] = 0;
        if (
            !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['Checksum']) &&
            is_array($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['Checksum'])
        ) {
            $CIDRAM['Components']['FilesCount'] =
                count($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['Checksum']);
            for (
                $CIDRAM['FilesIterate'] = 0;
                $CIDRAM['FilesIterate'] < $CIDRAM['Components']['FilesCount'];
                $CIDRAM['FilesIterate']++
            ) {
                if (empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['Checksum'][$CIDRAM['FilesIterate']])) {
                    continue;
                }
                $CIDRAM['FilesThis'] =
                    $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['Checksum'][$CIDRAM['FilesIterate']];
                if (($CIDRAM['FilesDelimit'] = strpos($CIDRAM['FilesThis'], ':')) !== false) {
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['LatestSize'] +=
                        (int)substr($CIDRAM['FilesThis'], $CIDRAM['FilesDelimit'] + 1);
                }
            }
        }
        if ($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['LatestSize'] > 0) {
            $CIDRAM['FormatFilesize']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['LatestSize']);
            $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['LatestSize'] =
                '<br />' . $CIDRAM['lang']['field_size'] .
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['LatestSize'];
        } else {
            $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['LatestSize'] = '';
        }
        if (!empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Options'])) {
            $CIDRAM['AppendToString']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['StatusOptions'], '<hr />',
                '<select name="do" class="half">' .
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Options'] .
                '</select><input class="half" type="submit" value="' . $CIDRAM['lang']['field_ok'] . '" />'
            );
            $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Options'] = '';
        }
        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Changelog'] =
            (empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Changelog'])) ? '' :
            '<br /><a href="' . $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Changelog'] . '">Changelog</a>';
        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Filename'] = (
            empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['To']) ||
            count($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['To']) !== 1
        ) ? '' : '<br />' . $CIDRAM['lang']['field_filename'] . $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']['To'][0];
        if (
            !($CIDRAM['FE']['hide-non-outdated'] && empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Outdated'])) &&
            !($CIDRAM['FE']['hide-unused'] && empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['Files']))
        ) {
            if (empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RowClass'])) {
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RowClass'] = 'h1';
            }
            $CIDRAM['Components']['Out'] .= $CIDRAM['ParseVars'](
                $CIDRAM['lang'] + $CIDRAM['ArrayFlatten']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]) + $CIDRAM['ArrayFlatten']($CIDRAM['FE']),
                $CIDRAM['FE']['UpdatesRow']
            );
        }
        next($CIDRAM['Components']['Meta']);
    }

    reset($CIDRAM['Components']['RemoteMeta']);
    $CIDRAM['Count'] = count($CIDRAM['Components']['RemoteMeta']);

    /** Prepare newly found component metadata and options for display. */
    for (
        $CIDRAM['Iterate'] = 0;
        $CIDRAM['Iterate'] < $CIDRAM['Count'];
        $CIDRAM['Iterate']++
    ) {
        $CIDRAM['Components']['Key'] = key($CIDRAM['Components']['RemoteMeta']);
        next($CIDRAM['Components']['RemoteMeta']);
        if (
            isset($CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]) ||
            empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Remote']) ||
            empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Version']) ||
            empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['From']) ||
            empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['To']) ||
            empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Reannotate']) ||
            !$CIDRAM['Traverse']($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Reannotate']) ||
            !file_exists($CIDRAM['Vault'] . $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Reannotate'])
        ) {
            continue;
        }
        $CIDRAM['Components']['ReannotateThis'] = $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Reannotate'];
        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['RemoteData'] = $CIDRAM['FECacheGet'](
            $CIDRAM['FE']['Cache'],
            $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Remote']
        );
        if (!$CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['RemoteData']) {
            $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['RemoteData'] =
                $CIDRAM['Request']($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Remote']);
            if (
                strtolower(substr(
                    $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Remote'], -2
                )) === 'gz' &&
                substr($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['RemoteData'], 0, 2) === "\x1f\x8b"
            ) {
                $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['RemoteData'] =
                    gzdecode($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['RemoteData']);
            }
            if (empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['RemoteData'])) {
                $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['RemoteData'] = '-';
            }
            $CIDRAM['FECacheAdd'](
                $CIDRAM['FE']['Cache'],
                $CIDRAM['FE']['Rebuild'],
                $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Remote'],
                $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['RemoteData'],
                $CIDRAM['Now'] + 3600
            );
        }
        if (!preg_match(
            "\x01(\n" . preg_quote($CIDRAM['Components']['Key']) . ":?)(\n [^\n]*)*\n\x01i",
            $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['RemoteData'],
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
        $CIDRAM['Components']['Remotes'][$CIDRAM['Components']['ReannotateThis']] =
            substr(
                $CIDRAM['Components']['Remotes'][$CIDRAM['Components']['ReannotateThis']], 0, -2
            ) . $CIDRAM['Components']['RemoteDataThis'] . "\n";
        if (is_array($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Name'])) {
            $CIDRAM['IsolateL10N'](
                $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Name'],
                $CIDRAM['Config']['general']['lang']
            );
        }
        if (is_array($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Extended Description'])) {
            $CIDRAM['IsolateL10N'](
                $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Extended Description'],
                $CIDRAM['Config']['general']['lang']
            );
        }
        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['ID'] = $CIDRAM['Components']['Key'];
        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Latest'] =
            $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Version'];
        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Version'] =
            $CIDRAM['lang']['response_updates_not_installed'];
        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['StatClass'] = 'txtRd';
        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['VersionSize'] = '';
        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['LatestSize'] = 0;
        if (
            !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['Checksum']) &&
            is_array($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['Checksum'])
        ) {
            $CIDRAM['Components']['FilesCount'] =
                count($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['Checksum']);
            for (
                $CIDRAM['FilesIterate'] = 0;
                $CIDRAM['FilesIterate'] < $CIDRAM['Components']['FilesCount'];
                $CIDRAM['FilesIterate']++
            ) {
                if (empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['Checksum'][$CIDRAM['FilesIterate']])) {
                    continue;
                }
                $CIDRAM['FilesThis'] =
                    $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Files']['Checksum'][$CIDRAM['FilesIterate']];
                if (($CIDRAM['FilesDelimit'] = strpos($CIDRAM['FilesThis'], ':')) !== false) {
                    $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['LatestSize'] +=
                        (int)substr($CIDRAM['FilesThis'], $CIDRAM['FilesDelimit'] + 1);
                }
            }
        }
        if ($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['LatestSize'] > 0) {
            $CIDRAM['FormatFilesize']($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['LatestSize']);
            $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['LatestSize'] =
                '<br />' . $CIDRAM['lang']['field_size'] .
                $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['LatestSize'];
        } else {
            $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['LatestSize'] = '';
        }
        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['StatusOptions'] =
            $CIDRAM['lang']['response_updates_not_installed'] .
            '<br /><select name="do" class="half"><option value="update-component">' .
            $CIDRAM['lang']['field_install'] .
            '</option></select><input class="half" type="submit" value="' .
            $CIDRAM['lang']['field_ok'] . '" />';
        if (!$CIDRAM['FE']['hide-unused']) {
            $CIDRAM['Components']['Meta'][$CIDRAM['Components']['Key']]['RowClass'] = 'h2';
            $CIDRAM['Components']['Out'] .= $CIDRAM['ParseVars'](
                $CIDRAM['lang'] + $CIDRAM['ArrayFlatten']($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]) + $CIDRAM['ArrayFlatten']($CIDRAM['FE']),
                $CIDRAM['FE']['UpdatesRow']
            );
        }
    }

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
    $CIDRAM['FE']['Components'] = $CIDRAM['Components']['Out'];
    unset($CIDRAM['Components'], $CIDRAM['Count'], $CIDRAM['Iterate']);

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
                '<input class="half" type="submit" value="' . $CIDRAM['lang']['field_ok'] . '" />';
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
        $CIDRAM['Count'] = count($_POST['ip-addr']);
        for ($CIDRAM['Iterate'] = 0; $CIDRAM['Iterate'] < $CIDRAM['Count']; $CIDRAM['Iterate']++) {
            $CIDRAM['ThisIP'] = array('Key' => key($_POST['ip-addr']));
            next($_POST['ip-addr']);
            $CIDRAM['ThisIP']['IPAddress'] = $_POST['ip-addr'][$CIDRAM['ThisIP']['Key']];
            if (empty($CIDRAM['ThisIP']['IPAddress'])) {
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
        'Files' => scandir($CIDRAM['Vault']),
        'Types' => ',txt,log,',
        'Out' => ''
    );

    if (empty($CIDRAM['QueryVars']['logfile'])) {
        $CIDRAM['FE']['logfileData'] = $CIDRAM['lang']['logs_no_logfile_selected'];
    } elseif (
        file_exists($CIDRAM['Vault'] . $CIDRAM['QueryVars']['logfile']) &&
        $CIDRAM['Traverse']($CIDRAM['QueryVars']['logfile']) &&
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
