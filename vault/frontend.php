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
 * This file: Front-end handler (last modified: 2016.10.12).
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
    'DefaultPassword' => '$2y$10$FPF5Im9MELEvF5AYuuRMSO.QKoYVpsiu1YU9aDClgrU57XtLof/dK',
    'FE_Lang' => $CIDRAM['Config']['general']['lang'],
    'DateTime' => date('r', $CIDRAM['Now']),
    'ScriptIdent' => $CIDRAM['ScriptIdent'],
    'Traverse' => "\x01(?:^\.|\.\.|[\x01-\x1f\[-`/\?\*\$])\x01i",
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

/** A fix for correctly displaying LTR/RTL text. */
if (empty($CIDRAM['lang']['textDir']) || $CIDRAM['lang']['textDir'] !== 'rtl') {
    $CIDRAM['lang']['textDir'] = 'ltr';
    $CIDRAM['FE']['FE_Align'] = 'left';
} else {
    $CIDRAM['FE']['FE_Align'] = 'right';
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

/** Set form target if not already set. */
$CIDRAM['FE']['FormTarget'] = (empty($_POST['cidram-form-target'])) ? '' : $_POST['cidram-form-target'];

/** Fetch user list and sessions list. */
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

/** Attempt to log in the user. */
if ($CIDRAM['FE']['FormTarget'] === 'login') {
    if (!empty($_POST['username']) && empty($_POST['password'])) {
        $CIDRAM['FE']['UserState'] = -1;
        $CIDRAM['FE']['state_msg'] = $CIDRAM['WrapRedText']($CIDRAM['lang']['response_login_password_field_empty']);
    } elseif (empty($_POST['username']) && !empty($_POST['password'])) {
        $CIDRAM['FE']['UserState'] = -1;
        $CIDRAM['FE']['state_msg'] = $CIDRAM['WrapRedText']($CIDRAM['lang']['response_login_username_field_empty']);
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
                $CIDRAM['FE']['state_msg'] = $CIDRAM['WrapRedText']($CIDRAM['lang']['response_login_invalid_password']);
            }
        } else {
            $CIDRAM['FE']['state_msg'] = $CIDRAM['WrapRedText']($CIDRAM['lang']['response_login_invalid_username']);
        }

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

    } else {

        /** Generate default tooltip for the user ("Hello, {username}"). */
        $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
            array('username' => $CIDRAM['FE']['UserRaw']),
            $CIDRAM['lang']['tip_hello']
        );

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

    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_home.html')
    );

    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

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

    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_accounts'];

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

    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_accounts.html')
    );

    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** Configuration. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'config' && $CIDRAM['FE']['Permissions'] === 1) {

    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_config'];

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_config.html')
    );

    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** Updates. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'updates' && $CIDRAM['FE']['Permissions'] === 1) {

    /** A form has been submitted. */
    if ($CIDRAM['FE']['FormTarget'] === 'updates' && !empty($_POST['do'])) {

        /** Update a component. */
        if ($_POST['do'] === 'update-component' && !empty($_POST['component_id'])) {
            if (
                !preg_match($CIDRAM['FE']['Traverse'], $_POST['component_id']) &&
                file_exists($CIDRAM['Vault'] . $_POST['component_id'])
            ) {
            } else {
                // Component doesn't exist (aaa temp aaa)
            }
        }

    }

    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_updates'];

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    $CIDRAM['FE']['UpdatesRow'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . 'fe_assets/_updates_row.html');

    $CIDRAM['FE']['Components'] = array(
        'Files' => scandir($CIDRAM['Vault']),
        'Types' => ',dat,',
        'Out' => ''
    );

    $CIDRAM['FE']['Components']['Script'] = array(
        'component_id' => 'cidram-core',
        'component_name' => 'CIDRAM',
        'component_version_latest_url' => 'https://raw.githubusercontent.com/Maikuolan/CIDRAM-Extras/master/hashes/scriptversion.dat',
        'component_version_current' => $CIDRAM['ScriptVersion']
    );
    $CIDRAM['FE']['Components']['Script']['component_version_latest_data'] = $CIDRAM['FECacheGet'](
        $CIDRAM['FE']['Cache'],
        $CIDRAM['FE']['Components']['Script']['component_version_latest_url']
    );
    if (!$CIDRAM['FE']['Components']['Script']['component_version_latest_data']) {
        $CIDRAM['FE']['Components']['Script']['component_version_latest_data'] =
            $CIDRAM['Request']($CIDRAM['FE']['Components']['Script']['component_version_latest_url']);
        $CIDRAM['FECacheAdd'](
            $CIDRAM['FE']['Cache'],
            $CIDRAM['FE']['Rebuild'],
            $CIDRAM['FE']['Components']['Script']['component_version_latest_url'],
            $CIDRAM['FE']['Components']['Script']['component_version_latest_data'],
            $CIDRAM['Now'] + 3600
        );
    }
    $CIDRAM['FE']['Components']['Script']['component_version_latest'] = htmlentities(substr(
        $CIDRAM['FE']['Components']['Script']['component_version_latest_data'], 0, 32
    ));
    if (empty($CIDRAM['FE']['Components']['Script']['component_version_latest'])) {
        $CIDRAM['FE']['Components']['Script']['component_version_latest'] = $CIDRAM['lang']['response_updates_unable_to_determine'];
        $CIDRAM['FE']['Components']['Script']['stat_class'] = 's';
    } else {
        if ($CIDRAM['FE']['Components']['Script']['component_version_latest'] == $CIDRAM['FE']['Components']['Script']['component_version_current']) {
            $CIDRAM['FE']['Components']['Script']['stat_class'] = 'txtGn';
            $CIDRAM['FE']['Components']['Script']['component_status_options'] = $CIDRAM['lang']['response_updates_already_up_to_date'];
        } else {
            $CIDRAM['FE']['Components']['Script']['stat_class'] = 'txtRd';
            $CIDRAM['FE']['Components']['Script']['component_status_options'] = $CIDRAM['lang']['response_updates_outdated'];
        }
    }
    $CIDRAM['FE']['Components']['Out'] .= $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE']['Components']['Script'],
        $CIDRAM['FE']['UpdatesRow']
    );

    $CIDRAM['FE']['Components']['Count'] = count($CIDRAM['FE']['Components']['Files']);
    for (
        $CIDRAM['FE']['Components']['Iterate'] = 0;
        $CIDRAM['FE']['Components']['Iterate'] < $CIDRAM['FE']['Components']['Count'];
        $CIDRAM['FE']['Components']['Iterate']++
    ) {
        if (strpos(
            $CIDRAM['FE']['Components']['Types'],
            strtolower(substr($CIDRAM['FE']['Components']['Files'][$CIDRAM['FE']['Components']['Iterate']], -3))
        ) !== false) {
            $CIDRAM['FE']['Components']['This'] =
                $CIDRAM['Vault'] . $CIDRAM['FE']['Components']['Files'][$CIDRAM['FE']['Components']['Iterate']];
            $CIDRAM['FE']['Components']['Data'] = $CIDRAM['ReadFile']($CIDRAM['FE']['Components']['This']);
            if (
                substr($CIDRAM['FE']['Components']['Data'], 0, 10) === "---\nMeta:\n" &&
                ($CIDRAM['FE']['Components']['EoYAML'] = strpos(
                    $CIDRAM['FE']['Components']['Data'], "\n\n"
                )) !== false
            ) {
                $CIDRAM['FE']['Components']['This'] =
                    substr($CIDRAM['FE']['Components']['Data'], 4, $CIDRAM['FE']['Components']['EoYAML'] - 4);
                $CIDRAM['FE']['Components']['Status'] = $CIDRAM['YAML']($CIDRAM['FE']['Components']['This']);
                $CIDRAM['FE']['Components']['This'] = array(
                    'component_id' => $CIDRAM['FE']['Components']['Files'][$CIDRAM['FE']['Components']['Iterate']],
                    'component_name' => (empty($CIDRAM['Config']['Meta']['Name'])) ? '' : $CIDRAM['Config']['Meta']['Name'],
                    'component_options' => '',
                    'component_status_options' => '',
                    'source_url' => (empty($CIDRAM['Config']['Meta']['Source'])) ? '' : $CIDRAM['Config']['Meta']['Source'],
                    'component_version_latest_url' => (empty($CIDRAM['Config']['Meta']['Hash'])) ? '' : $CIDRAM['Config']['Meta']['Hash'],
                    'component_version_current' => strlen($CIDRAM['FE']['Components']['Data']) . ':' . md5($CIDRAM['FE']['Components']['Data'])
                );
                $CIDRAM['FE']['Components']['This']['component_version_latest_data'] = $CIDRAM['FECacheGet'](
                    $CIDRAM['FE']['Cache'],
                    $CIDRAM['FE']['Components']['This']['component_version_latest_url']
                );
                if (!$CIDRAM['FE']['Components']['This']['component_version_latest_data']) {
                    $CIDRAM['FE']['Components']['This']['component_version_latest_data'] =
                        $CIDRAM['Request']($CIDRAM['FE']['Components']['This']['component_version_latest_url']);
                    $CIDRAM['FECacheAdd'](
                        $CIDRAM['FE']['Cache'],
                        $CIDRAM['FE']['Rebuild'],
                        $CIDRAM['FE']['Components']['This']['component_version_latest_url'],
                        $CIDRAM['FE']['Components']['This']['component_version_latest_data'],
                        $CIDRAM['Now'] + 3600
                    );
                }
                $CIDRAM['FE']['Components']['This']['component_version_latest_url'] = preg_match(
                    '/^([0-9]+\:[0-9a-f]{32})/i',
                    $CIDRAM['FE']['Components']['This']['component_version_latest_data'],
                    $CIDRAM['FE']['Components']['This']['component_version_latest_matches']
                );
                if (
                    !$CIDRAM['FE']['Components']['This']['component_version_latest_data'] ||
                    empty($CIDRAM['FE']['Components']['This']['component_version_latest_matches'][1])
                ) {
                    $CIDRAM['FE']['Components']['This']['component_version_latest'] = $CIDRAM['lang']['response_updates_unable_to_determine'];
                    $CIDRAM['FE']['Components']['This']['stat_class'] = 's';
                } else {
                    $CIDRAM['FE']['Components']['This']['component_version_latest'] = htmlentities(substr(
                        $CIDRAM['FE']['Components']['This']['component_version_latest_matches'][1], 0, 64
                    ));
                    $CIDRAM['FE']['Components']['This']['component_version_latest_matches'] = '';
                    if ($CIDRAM['FE']['Components']['This']['component_version_latest'] == $CIDRAM['FE']['Components']['This']['component_version_current']) {
                        $CIDRAM['FE']['Components']['This']['stat_class'] = 'txtGn';
                        $CIDRAM['FE']['Components']['This']['component_status_options'] = $CIDRAM['lang']['response_updates_already_up_to_date'];
                    } else {
                        $CIDRAM['FE']['Components']['This']['stat_class'] = 'txtRd';
                        $CIDRAM['FE']['Components']['This']['component_status_options'] = $CIDRAM['lang']['response_updates_outdated'];
                        if (!empty($CIDRAM['FE']['Components']['This']['source_url'])) {
                            $CIDRAM['FE']['Components']['This']['component_options'] .=
                                '<option value="update-component">' . $CIDRAM['lang']['field_update'] . '</option>';
                        }
                    }
                }
                if (!empty($CIDRAM['FE']['Components']['This']['component_options'])) {
                    $CIDRAM['FE']['Components']['This']['component_status_options'] .=
                        '<br /><select name="do" class="half">' .
                        $CIDRAM['FE']['Components']['This']['component_options'] .
                        '</select><input class="half" type="submit" value="' . $CIDRAM['lang']['field_ok'] . '" />';
                    $CIDRAM['FE']['Components']['This']['component_options'] = '';
                }
                $CIDRAM['FE']['Components']['Out'] .= $CIDRAM['ParseVars'](
                    $CIDRAM['lang'] + $CIDRAM['FE']['Components']['This'],
                    $CIDRAM['FE']['UpdatesRow']
                );
            }
        }
    }
    if (!$CIDRAM['FE']['Components'] = $CIDRAM['FE']['Components']['Out']) {
        $CIDRAM['FE']['Components'] = 'Nothing to update.'; // @todo fix message (default nothing to update)
    }

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
        !preg_match($CIDRAM['FE']['Traverse'], $CIDRAM['QueryVars']['logfile']) &&
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
    $CIDRAM['FE']['FrontEndData'] =
        "USERS\n-----" . $CIDRAM['FE']['UserList'] .
        "\nSESSIONS\n--------" . $CIDRAM['FE']['SessionList'] .
        "\nCACHE\n-----" . $CIDRAM['FE']['Cache'];
    $CIDRAM['FE']['Handle'] = fopen($CIDRAM['Vault'] . 'fe_assets/frontend.dat', 'w');
    fwrite($CIDRAM['FE']['Handle'], $CIDRAM['FE']['FrontEndData']);
    fclose($CIDRAM['FE']['Handle']);
}

die;
