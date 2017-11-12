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
 * This file: Front-end handler (last modified: 2017.11.12).
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

    /** Main front-end JavaScript data. */
    'JS' => $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('scripts.js')),

    /** Default password hash ("password"). */
    'DefaultPassword' => '$2y$10$FPF5Im9MELEvF5AYuuRMSO.QKoYVpsiu1YU9aDClgrU57XtLof/dK',

    /** Current default language. */
    'FE_Lang' => $CIDRAM['Config']['general']['lang'],

    /** Font magnification. */
    'Magnification' => $CIDRAM['Config']['template_data']['Magnification'],

    /** Warns if maintenance mode is enabled. */
    'MaintenanceWarning' => (
        $CIDRAM['Config']['general']['maintenance_mode']
    ) ? "\n<div class=\"center\"><span class=\"txtRd\">" . $CIDRAM['lang']['state_maintenance_mode'] . '</span></div><hr />' : '',

    /** Define active configuration file. */
    'ActiveConfigFile' => !empty($CIDRAM['Overrides']) ? $CIDRAM['Domain'] . '.config.ini' : 'config.ini',

    /** Number localisation JavaScript. */
    'Number_L10N_JS' => $CIDRAM['Number_L10N_JS'](),

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

    /** The current user state (0 = Not logged in; 1 = Logged in; -1 = Attempted and failed to log in). */
    'UserState' => 0,

    /** Taken from either $_POST['username'] or $_COOKIE['CIDRAM-ADMIN'] (the username claimed by the client). */
    'UserRaw' => '',

    /** User permissions (0 = Not logged in; 1 = Complete access; 2 = Logs access only; 3 = Cronable). */
    'Permissions' => 0,

    /** Will be populated by messages reflecting the current request state. */
    'state_msg' => '',

    /** Will be populated by the current session data. */
    'ThisSession' => '',

    /** Will be populated by either [Log Out] or [Home | Log Out] links. */
    'bNav' => '&nbsp;',

    /** State reflecting whether the current request is cronable. */
    'CronMode' => !empty($_POST['CronMode']),

    /** Will be populated by the page title. */
    'FE_Title' => ''

];

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
    $CIDRAM['FE']['Template'] = substr(
        $CIDRAM['FE']['Template'], 0, $CIDRAM['WebFontPos']['Begin']
    ) . substr(
        $CIDRAM['FE']['Template'], $CIDRAM['WebFontPos']['End'] + 20
    );
    unset($CIDRAM['WebFontPos']);
}

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

/** A simple passthru for non-private theme images and related data. */
if (!empty($CIDRAM['QueryVars']['cidram-asset'])) {

    $CIDRAM['Success'] = false;

    if (
        $CIDRAM['FileManager-PathSecurityCheck']($CIDRAM['QueryVars']['cidram-asset']) &&
        !preg_match('~[^0-9a-z._]~i', $CIDRAM['QueryVars']['cidram-asset'])
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
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['ReadFile']($CIDRAM['AssetPath']));
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
if ($CIDRAM['FE']['FormTarget'] === 'login' || $CIDRAM['FE']['CronMode']) {
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
                $CIDRAM['FE']['UserState'] = 1;
                if (!$CIDRAM['FE']['CronMode']) {
                    $CIDRAM['FE']['SessionKey'] = md5($CIDRAM['GenerateSalt']());
                    $CIDRAM['FE']['Cookie'] = $_POST['username'] . $CIDRAM['FE']['SessionKey'];
                    setcookie('CIDRAM-ADMIN', $CIDRAM['FE']['Cookie'], $CIDRAM['Now'] + 604800, '/', (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''), false, true);
                    $CIDRAM['FE']['ThisSession'] = $CIDRAM['FE']['User'] . ',' . password_hash(
                        $CIDRAM['FE']['SessionKey'], $CIDRAM['DefaultAlgo']
                    ) . ',' . ($CIDRAM['Now'] + 604800) . "\n";
                    $CIDRAM['FE']['SessionList'] .= $CIDRAM['FE']['ThisSession'];
                }
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
            $CIDRAM['Config']['general']['FrontEndLog'] = $CIDRAM['TimeFormat'](
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
        if (!$CIDRAM['FE']['CronMode']) {
            $CIDRAM['FE']['state_msg'] = '<div class="txtRd">' . $CIDRAM['FE']['state_msg'] . '<br /><br /></div>';
        }
    } elseif ($CIDRAM['Config']['general']['FrontEndLog']) {
        $CIDRAM['FrontEndLog'] .= ' - ' . $CIDRAM['lang']['state_logged_in'] . "\n";
    }
    if ($CIDRAM['Config']['general']['FrontEndLog']) {
        $CIDRAM['WriteMode'] = (
            !file_exists($CIDRAM['Vault'] . $CIDRAM['Config']['general']['FrontEndLog']) || (
                $CIDRAM['Config']['general']['truncate'] > 0 &&
                filesize($CIDRAM['Vault'] . $CIDRAM['Config']['general']['FrontEndLog']) >= $CIDRAM['ReadBytes']($CIDRAM['Config']['general']['truncate'])
            )
        ) ? 'w' : 'a';
        $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . $CIDRAM['Config']['general']['FrontEndLog'], $CIDRAM['WriteMode']);
        fwrite($CIDRAM['Handle'], $CIDRAM['FrontEndLog']);
        fclose($CIDRAM['Handle']);
        unset($CIDRAM['WriteMode'], $CIDRAM['FrontEndLog']);
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
                    $CIDRAM['FE']['UserState'] = 1;
                }
                break;
            }
        }
    }

}

/** Only execute this code block for users that are logged in. */
if ($CIDRAM['FE']['UserState'] === 1 && !$CIDRAM['FE']['CronMode']) {

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
            $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_nav_complete_access.html'))
        );

    /** If the user has logs access only. */
    } elseif ($CIDRAM['FE']['Permissions'] === 2) {

        $CIDRAM['FE']['nav'] = $CIDRAM['ParseVars'](
            $CIDRAM['lang'] + $CIDRAM['FE'],
            $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_nav_logs_access_only.html'))
        );

    }

    /** Execute hotfixes. */
    if (file_exists($CIDRAM['Vault'] . 'hotfixes.php')) {
        require $CIDRAM['Vault'] . 'hotfixes.php';
    }

}

$CIDRAM['FE']['bNavBR'] = ($CIDRAM['FE']['UserState'] === 1) ? '<br /><br />' : '<br />';

/** The user hasn't logged in. Show them the login page. */
if ($CIDRAM['FE']['UserState'] !== 1 && !$CIDRAM['FE']['CronMode']) {

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_login'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['lang']['tip_login'];

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_login.html'))
    );

    /** Send output. */
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/**
 * The user has logged in, but hasn't selected anything to view. Show them the
 * front-end home page.
 */
elseif ($CIDRAM['QueryVars']['cidram-page'] === '' && !$CIDRAM['FE']['CronMode']) {

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_home'];

    /** CIDRAM version used. */
    $CIDRAM['FE']['ScriptVersion'] = $CIDRAM['ScriptVersion'];

    /** PHP version used. */
    $CIDRAM['FE']['info_php'] = PHP_VERSION;

    /** SAPI used. */
    $CIDRAM['FE']['info_sapi'] = php_sapi_name();

    /** Operating system used. */
    $CIDRAM['FE']['info_os'] = php_uname();

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
        ['username' => $CIDRAM['FE']['UserRaw']],
        $CIDRAM['lang']['tip_home']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_logout'];

    /** Where to find remote version information? */
    $CIDRAM['RemoteVerPath'] = 'https://raw.githubusercontent.com/Maikuolan/Compatibility-Charts/gh-pages/';

    /** Fetch remote CIDRAM version information and cache it if necessary. */
    if (($CIDRAM['Remote-YAML-CIDRAM'] = $CIDRAM['FECacheGet']($CIDRAM['FE']['Cache'], 'cidram-ver.yaml')) === false) {
        $CIDRAM['Remote-YAML-CIDRAM'] = $CIDRAM['Request']($CIDRAM['RemoteVerPath'] . 'cidram-ver.yaml', false, 8);
        $CIDRAM['FECacheAdd']($CIDRAM['FE']['Cache'], $CIDRAM['FE']['Rebuild'], 'cidram-ver.yaml', $CIDRAM['Remote-YAML-CIDRAM'] ?: '-', $CIDRAM['Now'] + 86400);
    }

    /** Process remote CIDRAM version information. */
    if (empty($CIDRAM['Remote-YAML-CIDRAM'])) {

        /** CIDRAM latest stable. */
        $CIDRAM['FE']['info_cidram_stable'] = $CIDRAM['lang']['response_error'];
        /** CIDRAM latest unstable. */
        $CIDRAM['FE']['info_cidram_unstable'] = $CIDRAM['lang']['response_error'];
        /** CIDRAM branch latest stable. */
        $CIDRAM['FE']['info_cidram_branch'] = $CIDRAM['lang']['response_error'];

    } else {

        $CIDRAM['Remote-YAML-CIDRAM-Array'] = [];
        $CIDRAM['YAML']($CIDRAM['Remote-YAML-CIDRAM'], $CIDRAM['Remote-YAML-CIDRAM-Array']);

        /** CIDRAM latest stable. */
        $CIDRAM['FE']['info_cidram_stable'] = empty($CIDRAM['Remote-YAML-CIDRAM-Array']['Stable']) ?
            $CIDRAM['lang']['response_error'] : $CIDRAM['Remote-YAML-CIDRAM-Array']['Stable'];
        /** CIDRAM latest unstable. */
        $CIDRAM['FE']['info_cidram_unstable'] = empty($CIDRAM['Remote-YAML-CIDRAM-Array']['Unstable']) ?
            $CIDRAM['lang']['response_error'] : $CIDRAM['Remote-YAML-CIDRAM-Array']['Unstable'];
        /** CIDRAM branch latest stable. */
        if ($CIDRAM['ThisBranch'] = substr($CIDRAM['FE']['ScriptVersion'], 0, strpos($CIDRAM['FE']['ScriptVersion'], '.') ?: 0)) {
            $CIDRAM['ThisBranch'] = 'v' . ($CIDRAM['ThisBranch'] ?: 1);
            $CIDRAM['FE']['info_cidram_branch'] = empty($CIDRAM['Remote-YAML-CIDRAM-Array']['Branch'][$CIDRAM['ThisBranch']]['Latest']) ?
                $CIDRAM['lang']['response_error'] : $CIDRAM['Remote-YAML-CIDRAM-Array']['Branch'][$CIDRAM['ThisBranch']]['Latest'];
        } else {
            $CIDRAM['FE']['info_php_branch'] = $CIDRAM['lang']['response_error'];
        }

    }

    /** Cleanup. */
    unset($CIDRAM['Remote-YAML-CIDRAM-Array'], $CIDRAM['Remote-YAML-CIDRAM']);

    /** Fetch remote PHP version information and cache it if necessary. */
    if (($CIDRAM['Remote-YAML-PHP'] = $CIDRAM['FECacheGet']($CIDRAM['FE']['Cache'], 'php-ver.yaml')) === false) {
        $CIDRAM['Remote-YAML-PHP'] = $CIDRAM['Request']($CIDRAM['RemoteVerPath'] . 'php-ver.yaml', false, 8);
        $CIDRAM['FECacheAdd']($CIDRAM['FE']['Cache'], $CIDRAM['FE']['Rebuild'], 'php-ver.yaml', $CIDRAM['Remote-YAML-PHP'] ?: '-', $CIDRAM['Now'] + 86400);
    }

    /** Process remote PHP version information. */
    if (empty($CIDRAM['Remote-YAML-PHP'])) {

        /** PHP latest stable. */
        $CIDRAM['FE']['info_php_stable'] = $CIDRAM['lang']['response_error'];
        /** PHP latest unstable. */
        $CIDRAM['FE']['info_php_unstable'] = $CIDRAM['lang']['response_error'];
        /** PHP branch latest stable. */
        $CIDRAM['FE']['info_php_branch'] = $CIDRAM['lang']['response_error'];

    } else {

        $CIDRAM['Remote-YAML-PHP-Array'] = [];
        $CIDRAM['YAML']($CIDRAM['Remote-YAML-PHP'], $CIDRAM['Remote-YAML-PHP-Array']);

        /** PHP latest stable. */
        $CIDRAM['FE']['info_php_stable'] = empty($CIDRAM['Remote-YAML-PHP-Array']['Stable']) ?
            $CIDRAM['lang']['response_error'] : $CIDRAM['Remote-YAML-PHP-Array']['Stable'];
        /** PHP latest unstable. */
        $CIDRAM['FE']['info_php_unstable'] = empty($CIDRAM['Remote-YAML-PHP-Array']['Unstable']) ?
            $CIDRAM['lang']['response_error'] : $CIDRAM['Remote-YAML-PHP-Array']['Unstable'];
        /** PHP branch latest stable. */
        if ($CIDRAM['ThisBranch'] = substr(PHP_VERSION, 0, strpos(PHP_VERSION, '.') ?: 0)) {
            $CIDRAM['ThisBranch'] .= substr(PHP_VERSION, strlen($CIDRAM['ThisBranch']) + 1, strpos(PHP_VERSION, '.', strlen($CIDRAM['ThisBranch'])) ?: 0);
            $CIDRAM['ThisBranch'] = 'php' . $CIDRAM['ThisBranch'];
            $CIDRAM['FE']['info_php_branch'] = empty($CIDRAM['Remote-YAML-PHP-Array']['Branch'][$CIDRAM['ThisBranch']]['Latest']) ?
                $CIDRAM['lang']['response_error'] : $CIDRAM['Remote-YAML-PHP-Array']['Branch'][$CIDRAM['ThisBranch']]['Latest'];
            $CIDRAM['ForceVersionWarning'] = (!empty($CIDRAM['Remote-YAML-PHP-Array'][$CIDRAM['ThisBranch']]['WarnMin']) && (
                $CIDRAM['Remote-YAML-PHP-Array'][$CIDRAM['ThisBranch']]['WarnMin'] === '*' ||
                $CIDRAM['VersionCompare'](PHP_VERSION, $CIDRAM['Remote-YAML-PHP-Array'][$CIDRAM['ThisBranch']]['WarnMin'])
            ));
        } else {
            $CIDRAM['FE']['info_php_branch'] = $CIDRAM['lang']['response_error'];
        }

    }

    /** Cleanup. */
    unset($CIDRAM['Remote-YAML-PHP-Array'], $CIDRAM['Remote-YAML-PHP'], $CIDRAM['ThisBranch'], $CIDRAM['RemoteVerPath']);

    /** Process warnings. */
    $CIDRAM['FE']['Warnings'] = '';
    if (($CIDRAM['FE']['VersionWarning'] = $CIDRAM['VersionWarning']()) > 0) {
        if ($CIDRAM['FE']['VersionWarning'] >= 2) {
            $CIDRAM['FE']['VersionWarning'] = $CIDRAM['FE']['VersionWarning'] % 2;
            $CIDRAM['FE']['Warnings'] .= '<li><a href="https://www.cvedetails.com/version-list/74/128/1/PHP-PHP.html">' . $CIDRAM['lang']['warning_php_2'] . '</a></li>';
        }
        if ($CIDRAM['FE']['VersionWarning'] >= 1) {
            $CIDRAM['FE']['Warnings'] .= '<li><a href="https://secure.php.net/supported-versions.php">' . $CIDRAM['lang']['warning_php_1'] . '</a></li>';
        }
    }
    if (
        empty($CIDRAM['Config']['signatures']['ipv4']) &&
        empty($CIDRAM['Config']['signatures']['ipv6']) &&
        empty($CIDRAM['Config']['signatures']['modules'])
    ) {
        $CIDRAM['FE']['Warnings'] .= '<li>' . $CIDRAM['lang']['warning_signatures_1'] . '</li>';
    }
    if ($CIDRAM['FE']['Warnings']) {
        $CIDRAM['FE']['Warnings'] = '<hr />' . $CIDRAM['lang']['warning'] . '<br /><div class="txtRd"><ul>' . $CIDRAM['FE']['Warnings'] . '</ul></div>';
    }

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_home.html'))
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
                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_accounts_already_exists'];
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
            $CIDRAM['FE']['NewPass'] = password_hash($_POST['password'], $CIDRAM['DefaultAlgo']);
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
        ['username' => $CIDRAM['FE']['UserRaw']],
        $CIDRAM['lang']['tip_accounts']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

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
            $CIDRAM['RowInfo']['AccPermissions'] = $CIDRAM['lang']['state_complete_access'];
        } elseif ($CIDRAM['RowInfo']['AccPermissions'] === 2) {
            $CIDRAM['RowInfo']['AccPermissions'] = $CIDRAM['lang']['state_logs_access_only'];
        } elseif ($CIDRAM['RowInfo']['AccPermissions'] === 3) {
            $CIDRAM['RowInfo']['AccPermissions'] = 'Cronable';
        } else {
            $CIDRAM['RowInfo']['AccPermissions'] = $CIDRAM['lang']['response_error'];
        }
        $CIDRAM['RowInfo']['AccPassword'] = substr($CIDRAM['RowInfo']['AccPassword'], 0, -2);
        if ($CIDRAM['RowInfo']['AccPassword'] === $CIDRAM['FE']['DefaultPassword']) {
            $CIDRAM['RowInfo']['AccWarnings'] .= '<br /><div class="txtRd">' . $CIDRAM['lang']['state_default_password'] . '</div>';
        } elseif ((
            strlen($CIDRAM['RowInfo']['AccPassword']) !== 60 && strlen($CIDRAM['RowInfo']['AccPassword']) !== 96
        ) || (
            strlen($CIDRAM['RowInfo']['AccPassword']) === 60 && !preg_match('/^\$2.\$[0-9]{2}\$/', $CIDRAM['RowInfo']['AccPassword'])
        ) || (
            strlen($CIDRAM['RowInfo']['AccPassword']) === 96 && !preg_match('/^\$argon2i\$/', $CIDRAM['RowInfo']['AccPassword'])
        )) {
            $CIDRAM['RowInfo']['AccWarnings'] .= '<br /><div class="txtRd">' . $CIDRAM['lang']['state_password_not_valid'] . '</div>';
        }
        if (strrpos($CIDRAM['FE']['SessionList'], "\n" . $CIDRAM['RowInfo']['AccUsername'] . ',') !== false) {
            $CIDRAM['RowInfo']['AccWarnings'] .= '<br /><div class="txtGn">' . $CIDRAM['lang']['state_logged_in'] . '</div>';
        }
        $CIDRAM['RowInfo']['AccUsername'] = htmlentities(base64_decode($CIDRAM['RowInfo']['AccUsername']));
        $CIDRAM['FE']['NewLineOffset'] = $CIDRAM['FE']['NewLinePos'];
        $CIDRAM['FE']['Accounts'] .= $CIDRAM['ParseVars'](
            $CIDRAM['lang'] + $CIDRAM['RowInfo'], $CIDRAM['FE']['AccountsRow']
        );
    }
    unset($CIDRAM['RowInfo']);

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_accounts.html'))
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
        ['username' => $CIDRAM['FE']['UserRaw']],
        $CIDRAM['lang']['tip_config']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    /** Directive template. */
    $CIDRAM['FE']['ConfigRow'] = $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_config_row.html'));

    /** Indexes. */
    $CIDRAM['FE']['Indexes'] = '            ';

    /** Generate entries for display and regenerate configuration if any changes were submitted. */
    $CIDRAM['FE']['ConfigFields'] = $CIDRAM['RegenerateConfig'] = '';
    $CIDRAM['ConfigModified'] = (!empty($CIDRAM['QueryVars']['updated']) && $CIDRAM['QueryVars']['updated'] === 'true');
    foreach ($CIDRAM['Config']['Config Defaults'] as $CIDRAM['CatKey'] => $CIDRAM['CatValue']) {
        if (!is_array($CIDRAM['CatValue'])) {
            continue;
        }
        $CIDRAM['RegenerateConfig'] .= '[' . $CIDRAM['CatKey'] . "]\r\n\r\n";
        $CIDRAM['FE']['ConfigFields'] .= sprintf(
            '<table><tr><td class="ng2"><div id="%1$s-container" class="s">' .
            '<a id="%1$s-showlink" href="#%1$s-container" onclick="javascript:showid(\'%1$s-hidelink\');showid(\'%1$s-ihidelink\');hideid(\'%1$s-showlink\');hideid(\'%1$s-ishowlink\');show(\'%1$s-index\');show(\'%1$s-row\')">%1$s +</a>' .
            '<a id="%1$s-hidelink" style="display:none" href="#" onclick="javascript:showid(\'%1$s-showlink\');showid(\'%1$s-ishowlink\');hideid(\'%1$s-hidelink\');hideid(\'%1$s-ihidelink\');hide(\'%1$s-index\');hide(\'%1$s-row\')">%1$s -</a>' .
            "</div></td></tr></table>\n<span class=\"%1\$s-row\" style=\"display:none\"><table>\n",
            $CIDRAM['CatKey']
        );
        $CIDRAM['FE']['Indexes'] .= sprintf(
            '<a id="%1$s-ishowlink" href="#%1$s-container" onclick="javascript:showid(\'%1$s-hidelink\');showid(\'%1$s-ihidelink\');hideid(\'%1$s-showlink\');hideid(\'%1$s-ishowlink\');show(\'%1$s-index\');show(\'%1$s-row\')">%1$s +</a>' .
            '<a id="%1$s-ihidelink" style="display:none" href="#" onclick="javascript:showid(\'%1$s-showlink\');showid(\'%1$s-ishowlink\');hideid(\'%1$s-hidelink\');hideid(\'%1$s-ihidelink\');hide(\'%1$s-index\');hide(\'%1$s-row\')">%1$s -</a>' .
            "<br /><br />\n            ",
            $CIDRAM['CatKey']
        );
        foreach ($CIDRAM['CatValue'] as $CIDRAM['DirKey'] => $CIDRAM['DirValue']) {
            $CIDRAM['ThisDir'] = ['FieldOut' => '', 'CatKey' => $CIDRAM['CatKey']];
            if (empty($CIDRAM['DirValue']['type']) || !isset($CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']])) {
                continue;
            }
            $CIDRAM['ThisDir']['DirLangKey'] = 'config_' . $CIDRAM['CatKey'] . '_' . $CIDRAM['DirKey'];
            $CIDRAM['ThisDir']['DirName'] = $CIDRAM['CatKey'] . '-&gt;' . $CIDRAM['DirKey'];
            $CIDRAM['FE']['Indexes'] .= '<span class="' . $CIDRAM['CatKey'] . '-index" style="display:none"><a href="#' . $CIDRAM['ThisDir']['DirLangKey'] . '">' . $CIDRAM['ThisDir']['DirName'] . "</a><br /><br /></span>\n            ";
            $CIDRAM['ThisDir']['DirLang'] = !empty(
                $CIDRAM['lang'][$CIDRAM['ThisDir']['DirLangKey']]
            ) ? $CIDRAM['lang'][$CIDRAM['ThisDir']['DirLangKey']] : $CIDRAM['lang']['response_error'];
            $CIDRAM['RegenerateConfig'] .= '; ' . wordwrap(strip_tags($CIDRAM['ThisDir']['DirLang']), 77, "\r\n; ") . "\r\n";
            if (isset($_POST[$CIDRAM['ThisDir']['DirLangKey']])) {
                if ($CIDRAM['DirValue']['type'] === 'kb' || $CIDRAM['DirValue']['type'] === 'string' || $CIDRAM['DirValue']['type'] === 'timezone' || $CIDRAM['DirValue']['type'] === 'int' || $CIDRAM['DirValue']['type'] === 'real' || $CIDRAM['DirValue']['type'] === 'bool') {
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
            } elseif ($CIDRAM['DirValue']['type'] === 'int' || $CIDRAM['DirValue']['type'] === 'real') {
                $CIDRAM['RegenerateConfig'] .= $CIDRAM['DirKey'] . '=' . $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] . "\r\n\r\n";
            } else {
                $CIDRAM['RegenerateConfig'] .= $CIDRAM['DirKey'] . '=\'' . $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] . "'\r\n\r\n";
            }
            if (isset($CIDRAM['DirValue']['preview'])) {
                $CIDRAM['ThisDir']['Preview'] = ' = <span id="' . $CIDRAM['ThisDir']['DirLangKey'] . '_preview"></span>';
                $CIDRAM['ThisDir']['Trigger'] = ' onchange="javascript:' . $CIDRAM['ThisDir']['DirLangKey'] . '_function();" onkeyup="javascript:' . $CIDRAM['ThisDir']['DirLangKey'] . '_function();"';
                if ($CIDRAM['DirValue']['preview'] === 'kb') {
                    $CIDRAM['ThisDir']['Preview'] .= sprintf(
                            '<script type="text/javascript">function %1$s_function(){var e=document.g' .
                            'etElementById?document.getElementById(\'%1$s_field\').value:document.all' .
                            '&&!document.getElementById?document.all.%1$s_field.value:\'\',z=e.replac' .
                            'e(/o$/i,\'b\').substr(-2).toLowerCase(),y=\'kb\'==z?1:\'mb\'==z?1024:\'g' .
                            'b\'==z?1048576:\'tb\'==z?1073741824:\'b\'==e.substr(-1)?.0009765625:1,e=' .
                            'e.replace(/[^0-9]*$/i,\'\'),e=isNaN(e)?0:e*y,t=0>e?\'0 %2$s\':1>e?nft((1' .
                            '024*e).toFixed(0))+\' %2$s\':1024>e?nft((1*e).toFixed(2))+\' %3$s\':1048' .
                            '576>e?nft((e/1024).toFixed(2))+\' %4$s\':1073741824>e?nft((e/1048576).to' .
                            'Fixed(2))+\' %5$s\':nft((e/1073741824).toFixed(2))+\' %6$s\';document.ge' .
                            'tElementById?document.getElementById(\'%1$s_preview\').innerHTML=t:docum' .
                            'ent.all&&!document.getElementById?document.all.%1$s_preview.innerHTML=t:' .
                            '\'\'};%1$s_function();</script>',
                        $CIDRAM['ThisDir']['DirLangKey'],
                        $CIDRAM['Plural'](0, $CIDRAM['lang']['field_size_bytes']),
                        $CIDRAM['lang']['field_size_KB'],
                        $CIDRAM['lang']['field_size_MB'],
                        $CIDRAM['lang']['field_size_GB'],
                        $CIDRAM['lang']['field_size_TB']
                    );
                } elseif ($CIDRAM['DirValue']['preview'] === 'seconds') {
                    $CIDRAM['ThisDir']['Preview'] .= sprintf(
                            '<script type="text/javascript">function %1$s_function(){var t=document.getE' .
                            'lementById?document.getElementById(\'%1$s_field\').value:document.all&&!doc' .
                            'ument.getElementById?document.all.%1$s_field.value:\'\',e=isNaN(t)?0:0>t?t*' .
                            '-1:t,n=e?Math.floor(e/31536e3):0,e=e?e-31536e3*n:0,o=e?Math.floor(e/2592e3)' .
                            ':0,e=e-2592e3*o,l=e?Math.floor(e/604800):0,e=e-604800*l,r=e?Math.floor(e/86' .
                            '400):0,e=e-86400*r,d=e?Math.floor(e/3600):0,e=e-3600*d,i=e?Math.floor(e/60)' .
                            ':0,e=e-60*i,f=e?Math.floor(1*e):0,a=nft(n.toString())+\' %2$s – \'+nft(o.to' .
                            'String())+\' %3$s – \'+nft(l.toString())+\' %4$s – \'+nft(r.toString())+\' ' .
                            '%5$s – \'+nft(d.toString())+\' %6$s – \'+nft(i.toString())+\' %7$s – \'+nft' .
                            '(f.toString())+\' %8$s\';document.getElementById?document.getElementById(\'' .
                            '%1$s_preview\').innerHTML=a:document.all&&!document.getElementById?document' .
                            '.all.%1$s_preview.innerHTML=a:\'\'}%1$s_function();</script>',
                        $CIDRAM['ThisDir']['DirLangKey'],
                        $CIDRAM['lang']['previewer_years'],
                        $CIDRAM['lang']['previewer_months'],
                        $CIDRAM['lang']['previewer_weeks'],
                        $CIDRAM['lang']['previewer_days'],
                        $CIDRAM['lang']['previewer_hours'],
                        $CIDRAM['lang']['previewer_minutes'],
                        $CIDRAM['lang']['previewer_seconds']
                    );
                } elseif ($CIDRAM['DirValue']['preview'] === 'minutes') {
                    $CIDRAM['ThisDir']['Preview'] .= sprintf(
                            '<script type="text/javascript">function %1$s_function(){var t=document.getE' .
                            'lementById?document.getElementById(\'%1$s_field\').value:document.all&&!doc' .
                            'ument.getElementById?document.all.%1$s_field.value:\'\',e=isNaN(t)?0:0>t?t*' .
                            '-1:t,n=e?Math.floor(e/525600):0,e=e?e-525600*n:0,o=e?Math.floor(e/43200):0,' .
                            'e=e-43200*o,l=e?Math.floor(e/10080):0,e=e-10080*l,r=e?Math.floor(e/1440):0,' .
                            'e=e-1440*r,d=e?Math.floor(e/60):0,e=e-60*d,i=e?Math.floor(e*1):0,e=e-i,f=e?' .
                            'Math.floor(60*e):0,a=nft(n.toString())+\' %2$s – \'+nft(o.toString())+\' %3' .
                            '$s – \'+nft(l.toString())+\' %4$s – \'+nft(r.toString())+\' %5$s – \'+nft(d' .
                            '.toString())+\' %6$s – \'+nft(i.toString())+\' %7$s – \'+nft(f.toString())+' .
                            '\' %8$s\';document.getElementById?document.getElementById(\'%1$s_preview\')' .
                            '.innerHTML=a:document.all&&!document.getElementById?document.all.%1$s_previ' .
                            'ew.innerHTML=a:\'\'}%1$s_function();</script>',
                        $CIDRAM['ThisDir']['DirLangKey'],
                        $CIDRAM['lang']['previewer_years'],
                        $CIDRAM['lang']['previewer_months'],
                        $CIDRAM['lang']['previewer_weeks'],
                        $CIDRAM['lang']['previewer_days'],
                        $CIDRAM['lang']['previewer_hours'],
                        $CIDRAM['lang']['previewer_minutes'],
                        $CIDRAM['lang']['previewer_seconds']
                    );
                } elseif ($CIDRAM['DirValue']['preview'] === 'hours') {
                    $CIDRAM['ThisDir']['Preview'] .= sprintf(
                            '<script type="text/javascript">function %1$s_function(){var t=document.getE' .
                            'lementById?document.getElementById(\'%1$s_field\').value:document.all&&!doc' .
                            'ument.getElementById?document.all.%1$s_field.value:\'\',e=isNaN(t)?0:0>t?t*' .
                            '-1:t,n=e?Math.floor(e/8760):0,e=e?e-8760*n:0,o=e?Math.floor(e/720):0,e=e-72' .
                            '0*o,l=e?Math.floor(e/168):0,e=e-168*l,r=e?Math.floor(e/24):0,e=e-24*r,d=e?M' .
                            'ath.floor(e*1):0,e=e-d,i=e?Math.floor(60*e):0,e=e-(i/60),f=e?Math.floor(360' .
                            '0*e):0,a=nft(n.toString())+\' %2$s – \'+nft(o.toString())+\' %3$s – \'+nft(' .
                            'l.toString())+\' %4$s – \'+nft(r.toString())+\' %5$s – \'+nft(d.toString())' .
                            '+\' %6$s – \'+nft(i.toString())+\' %7$s – \'+nft(f.toString())+\' %8$s\';do' .
                            'cument.getElementById?document.getElementById(\'%1$s_preview\').innerHTML=a' .
                            ':document.all&&!document.getElementById?document.all.%1$s_preview.innerHTML' .
                            '=a:\'\'}%1$s_function();</script>',
                        $CIDRAM['ThisDir']['DirLangKey'],
                        $CIDRAM['lang']['previewer_years'],
                        $CIDRAM['lang']['previewer_months'],
                        $CIDRAM['lang']['previewer_weeks'],
                        $CIDRAM['lang']['previewer_days'],
                        $CIDRAM['lang']['previewer_hours'],
                        $CIDRAM['lang']['previewer_minutes'],
                        $CIDRAM['lang']['previewer_seconds']
                    );
                }
            } else {
                $CIDRAM['ThisDir']['Preview'] = $CIDRAM['ThisDir']['Trigger'] = '';
            }
            if ($CIDRAM['DirValue']['type'] === 'timezone') {
                $CIDRAM['DirValue']['choices'] = ['SYSTEM' => $CIDRAM['lang']['field_system_timezone']];
                foreach (array_unique(DateTimeZone::listIdentifiers()) as $CIDRAM['DirValue']['ChoiceValue']) {
                    $CIDRAM['DirValue']['choices'][$CIDRAM['DirValue']['ChoiceValue']] = $CIDRAM['DirValue']['ChoiceValue'];
                }
            }
            if (isset($CIDRAM['DirValue']['choices'])) {
                $CIDRAM['ThisDir']['FieldOut'] = '<select class="auto" name="'. $CIDRAM['ThisDir']['DirLangKey'] . '" id="'. $CIDRAM['ThisDir']['DirLangKey'] . '_field"' . $CIDRAM['ThisDir']['Trigger'] . '>';
                foreach ($CIDRAM['DirValue']['choices'] as $CIDRAM['ChoiceKey'] => $CIDRAM['ChoiceValue']) {
                    if (isset($CIDRAM['DirValue']['choice_filter'], $CIDRAM[$CIDRAM['DirValue']['choice_filter']])) {
                        if (!$CIDRAM[$CIDRAM['DirValue']['choice_filter']]($CIDRAM['ChoiceKey'], $CIDRAM['ChoiceValue'])) {
                            continue;
                        }
                    }
                    if (strpos($CIDRAM['ChoiceValue'], '{') !== false) {
                        $CIDRAM['ChoiceValue'] = $CIDRAM['TimeFormat']($CIDRAM['Now'], $CIDRAM['ChoiceValue']);
                        if (strpos($CIDRAM['ChoiceValue'], '{') !== false) {
                            $CIDRAM['ChoiceValue'] = $CIDRAM['ParseVars']($CIDRAM['lang'], $CIDRAM['ChoiceValue']);
                        }
                    }
                    $CIDRAM['ThisDir']['FieldOut'] .= ($CIDRAM['ChoiceKey'] === $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']]) ?
                        '<option value="' . $CIDRAM['ChoiceKey'] . '" selected>' . $CIDRAM['ChoiceValue'] . '</option>'
                    :
                        '<option value="' . $CIDRAM['ChoiceKey'] . '">' . $CIDRAM['ChoiceValue'] . '</option>';
                }
                $CIDRAM['ThisDir']['FieldOut'] .= '</select>';
            } elseif ($CIDRAM['DirValue']['type'] === 'bool') {
                if ($CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']]) {
                    $CIDRAM['ThisDir']['FieldOut'] =
                        '<select class="auto" name="'. $CIDRAM['ThisDir']['DirLangKey'] . '" id="'. $CIDRAM['ThisDir']['DirLangKey'] . '_field"' . $CIDRAM['ThisDir']['Trigger'] . '>' .
                        '<option value="true" selected>' . $CIDRAM['lang']['field_true'] . '</option><option value="false">' . $CIDRAM['lang']['field_false'] . '</option>' .
                        '</select>';
                } else {
                    $CIDRAM['ThisDir']['FieldOut'] =
                        '<select class="auto" name="'. $CIDRAM['ThisDir']['DirLangKey'] . '" id="'. $CIDRAM['ThisDir']['DirLangKey'] . '_field"' . $CIDRAM['ThisDir']['Trigger'] . '>' .
                        '<option value="true">' . $CIDRAM['lang']['field_true'] . '</option><option value="false" selected>' . $CIDRAM['lang']['field_false'] . '</option>' .
                        '</select>';
                }
            } elseif ($CIDRAM['DirValue']['type'] === 'int' || $CIDRAM['DirValue']['type'] === 'real') {
                $CIDRAM['ThisDir']['Step'] = isset($CIDRAM['DirValue']['step']) ? ' step="' . $CIDRAM['DirValue']['step'] . '"' : '';
                $CIDRAM['ThisDir']['FieldOut'] = '<input type="number" name="'. $CIDRAM['ThisDir']['DirLangKey'] . '" id="'. $CIDRAM['ThisDir']['DirLangKey'] . '_field" value="' . $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] . '"' . $CIDRAM['ThisDir']['Step'] . $CIDRAM['ThisDir']['Trigger'] . ' />';
            } elseif ($CIDRAM['DirValue']['type'] === 'string') {
                $CIDRAM['ThisDir']['FieldOut'] = '<textarea name="'. $CIDRAM['ThisDir']['DirLangKey'] . '" id="'. $CIDRAM['ThisDir']['DirLangKey'] . '_field" class="half"' . $CIDRAM['ThisDir']['Trigger'] . '>' . $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] . '</textarea>';
            } else {
                $CIDRAM['ThisDir']['FieldOut'] = '<input type="text" name="'. $CIDRAM['ThisDir']['DirLangKey'] . '" id="'. $CIDRAM['ThisDir']['DirLangKey'] . '_field" value="' . $CIDRAM['Config'][$CIDRAM['CatKey']][$CIDRAM['DirKey']] . '"' . $CIDRAM['ThisDir']['Trigger'] . ' />';
            }
            $CIDRAM['ThisDir']['FieldOut'] .= $CIDRAM['ThisDir']['Preview'];
            $CIDRAM['FE']['ConfigFields'] .= $CIDRAM['ParseVars'](
                $CIDRAM['lang'] + $CIDRAM['ThisDir'], $CIDRAM['FE']['ConfigRow']
            );
        }
        $CIDRAM['FE']['ConfigFields'] .= "</table></span>\n";
        $CIDRAM['RegenerateConfig'] .= "\r\n";
    }

    /** Update the currently active configuration file if any changes were made. */
    if ($CIDRAM['ConfigModified']) {
        $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_configuration_updated'];
        $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile'], 'w');
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
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_config.html'))
    );

    /** Send output. */
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** Updates. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'updates' && ($CIDRAM['FE']['Permissions'] === 1 || ($CIDRAM['FE']['Permissions'] === 3 && $CIDRAM['FE']['CronMode']))) {

    $CIDRAM['FE']['UpdatesFormTarget'] = 'cidram-page=updates';
    $CIDRAM['FE']['UpdatesFormTargetControls'] = '';
    if (!$CIDRAM['FE']['CronMode']) {
        $CIDRAM['StateModified'] = false;
        $CIDRAM['FilterSwitch'](
            ['hide-non-outdated', 'hide-unused'],
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
    }

    /** Prepare components metadata working array. */
    $CIDRAM['Components'] = ['Meta' => [], 'RemoteMeta' => []];

    /** Fetch components lists. */
    $CIDRAM['FetchComponentsLists']($CIDRAM['Vault'], $CIDRAM['Components']['Meta']);

    /** Cleanup. */
    unset($CIDRAM['Components']['Files']);

    /** Indexes. */
    $CIDRAM['FE']['Indexes'] = [];

    /** A form has been submitted. */
    if ($CIDRAM['FE']['FormTarget'] === 'updates' && !empty($_POST['do'])) {

        /** Update a component. */
        if ($_POST['do'] === 'update-component' && !empty($_POST['ID'])) {
            $CIDRAM['Components']['Target'] = $_POST['ID'];
            $CIDRAM['Arrayify']($CIDRAM['Components']['Target']);
            $CIDRAM['FileData'] = [];
            $CIDRAM['Annotations'] = [];
            foreach ($CIDRAM['Components']['Target'] as $CIDRAM['Components']['ThisTarget']) {
                if (
                    !isset($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Remote']) ||
                    !isset($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Reannotate'])
                ) {
                    continue;
                }
                $CIDRAM['Components']['BytesAdded'] = 0;
                $CIDRAM['Components']['BytesRemoved'] = 0;
                $CIDRAM['Components']['TimeRequired'] = microtime(true);
                $CIDRAM['Components']['RemoteMeta'] = [];
                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'] = $CIDRAM['FECacheGet'](
                    $CIDRAM['FE']['Cache'],
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Remote']
                );
                if (!$CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData']) {
                    $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'] = $CIDRAM['Request'](
                        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Remote']
                    );
                    if (
                        strtolower(substr($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Remote'], -2)) === 'gz' &&
                        substr($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'], 0, 2) === "\x1f\x8b"
                    ) {
                        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'] = gzdecode(
                            $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData']
                        );
                    }
                    if (empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'])) {
                        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'] = '-';
                    }
                    $CIDRAM['FECacheAdd'](
                        $CIDRAM['FE']['Cache'],
                        $CIDRAM['FE']['Rebuild'],
                        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Remote'],
                        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'],
                        $CIDRAM['Now'] + 3600
                    );
                }
                $CIDRAM['UpdateFailed'] = false;
                if (
                    substr($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'], 0, 4) === "---\n" &&
                    ($CIDRAM['Components']['EoYAML'] = strpos(
                        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'], "\n\n"
                    )) !== false &&
                    $CIDRAM['YAML'](
                        substr($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'], 4, $CIDRAM['Components']['EoYAML'] - 4),
                        $CIDRAM['Components']['RemoteMeta']
                    ) &&
                    !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Minimum Required']) &&
                    !$CIDRAM['VersionCompare'](
                        $CIDRAM['ScriptVersion'],
                        $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Minimum Required']
                    ) &&
                    (
                        empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Minimum Required PHP']) ||
                        !$CIDRAM['VersionCompare'](PHP_VERSION, $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Minimum Required PHP'])
                    ) &&
                    !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['From']) &&
                    !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['To']) &&
                    !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Reannotate']) &&
                    $CIDRAM['Traverse']($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Reannotate']) &&
                    ($CIDRAM['ThisReannotate'] = $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Reannotate']) &&
                    file_exists($CIDRAM['Vault'] . $CIDRAM['ThisReannotate']) &&
                    ((
                        !empty($CIDRAM['FileData'][$CIDRAM['ThisReannotate']]) &&
                        $CIDRAM['Components']['OldMeta'] = $CIDRAM['FileData'][$CIDRAM['ThisReannotate']]
                    ) || (
                        $CIDRAM['FileData'][$CIDRAM['ThisReannotate']] = $CIDRAM['Components']['OldMeta'] = $CIDRAM['ReadFile'](
                            $CIDRAM['Vault'] . $CIDRAM['ThisReannotate']
                        )
                    )) &&
                    preg_match(
                        "\x01(\n" . preg_quote($CIDRAM['Components']['ThisTarget']) . ":?)(\n [^\n]*)*\n\x01i",
                        $CIDRAM['Components']['OldMeta'],
                        $CIDRAM['Components']['OldMetaMatches']
                    ) &&
                    ($CIDRAM['Components']['OldMetaMatches'] = $CIDRAM['Components']['OldMetaMatches'][0]) &&
                    ($CIDRAM['Components']['NewMeta'] = $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData']) &&
                    preg_match(
                        "\x01(\n" . preg_quote($CIDRAM['Components']['ThisTarget']) . ":?)(\n [^\n]*)*\n\x01i",
                        $CIDRAM['Components']['NewMeta'],
                        $CIDRAM['Components']['NewMetaMatches']
                    ) &&
                    ($CIDRAM['Components']['NewMetaMatches'] = $CIDRAM['Components']['NewMetaMatches'][0])
                ) {
                    $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']);
                    $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['From']);
                    $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['To']);
                    if (!empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['Checksum'])) {
                        $CIDRAM['Arrayify']($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['Checksum']);
                    }
                    $CIDRAM['Components']['NewMeta'] = str_replace(
                        $CIDRAM['Components']['OldMetaMatches'],
                        $CIDRAM['Components']['NewMetaMatches'],
                        $CIDRAM['Components']['OldMeta']
                    );
                    $CIDRAM['Count'] = count($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['From']);
                    $CIDRAM['RemoteFiles'] = [];
                    $CIDRAM['IgnoredFiles'] = [];
                    $CIDRAM['Rollback'] = false;
                    /** Write new and updated files and directories. */
                    for ($CIDRAM['Iterate'] = 0; $CIDRAM['Iterate'] < $CIDRAM['Count']; $CIDRAM['Iterate']++) {
                        if (empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['To'][$CIDRAM['Iterate']])) {
                            continue;
                        }
                        $CIDRAM['ThisFileName'] = $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['To'][$CIDRAM['Iterate']];
                        /** Rolls back to previous version or uninstalls if an update/install fails. */
                        if ($CIDRAM['Rollback']) {
                            if (
                                isset($CIDRAM['RemoteFiles'][$CIDRAM['ThisFileName']]) &&
                                !isset($CIDRAM['IgnoredFiles'][$CIDRAM['ThisFileName']]) &&
                                is_readable($CIDRAM['Vault'] . $CIDRAM['ThisFileName'])
                            ) {
                                $CIDRAM['Components']['BytesAdded'] -= filesize($CIDRAM['Vault'] . $CIDRAM['ThisFileName']);
                                unlink($CIDRAM['Vault'] . $CIDRAM['ThisFileName']);
                                if (is_readable($CIDRAM['Vault'] . $CIDRAM['ThisFileName'] . '.rollback')) {
                                    $CIDRAM['Components']['BytesRemoved'] -= filesize($CIDRAM['Vault'] . $CIDRAM['ThisFileName'] . '.rollback');
                                    rename($CIDRAM['Vault'] . $CIDRAM['ThisFileName'] . '.rollback', $CIDRAM['Vault'] . $CIDRAM['ThisFileName']);
                                }
                            }
                            continue;
                        }
                        if (
                            !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['Checksum'][$CIDRAM['Iterate']]) &&
                            !empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Files']['Checksum'][$CIDRAM['Iterate']]) && (
                                $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['Checksum'][$CIDRAM['Iterate']] ===
                                $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Files']['Checksum'][$CIDRAM['Iterate']]
                            )
                        ) {
                            $CIDRAM['IgnoredFiles'][$CIDRAM['ThisFileName']] = true;
                            continue;
                        }
                        if (
                            empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['From'][$CIDRAM['Iterate']]) ||
                            !($CIDRAM['ThisFile'] = $CIDRAM['Request'](
                                $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['From'][$CIDRAM['Iterate']]
                            ))
                        ) {
                            $CIDRAM['Iterate'] = 0;
                            $CIDRAM['Rollback'] = true;
                            continue;
                        }
                        if (
                            strtolower(substr(
                                $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['From'][$CIDRAM['Iterate']], -2
                            )) === 'gz' &&
                            strtolower(substr($CIDRAM['ThisFileName'], -2)) !== 'gz' &&
                            substr($CIDRAM['ThisFile'], 0, 2) === "\x1f\x8b"
                        ) {
                            $CIDRAM['ThisFile'] = gzdecode($CIDRAM['ThisFile']);
                        }
                        if (
                            !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['Checksum'][$CIDRAM['Iterate']]) &&
                                $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['Checksum'][$CIDRAM['Iterate']] !==
                                md5($CIDRAM['ThisFile']) . ':' . strlen($CIDRAM['ThisFile'])
                        ) {
                            $CIDRAM['FE']['state_msg'] .=
                                '<code>' . $CIDRAM['Components']['ThisTarget'] . '</code> – ' .
                                '<code>' . $CIDRAM['ThisFileName'] . '</code> – ' .
                                $CIDRAM['lang']['response_checksum_error'] . '<br />';
                            if (!empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['On Checksum Error'])) {
                                $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['On Checksum Error']);
                            }
                            $CIDRAM['Iterate'] = 0;
                            $CIDRAM['Rollback'] = true;
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
                        if (is_readable($CIDRAM['Vault'] . $CIDRAM['ThisFileName'])) {
                            $CIDRAM['Components']['BytesRemoved'] += filesize($CIDRAM['Vault'] . $CIDRAM['ThisFileName']);
                            if (file_exists($CIDRAM['Vault'] . $CIDRAM['ThisFileName'] . '.rollback')) {
                                unlink($CIDRAM['Vault'] . $CIDRAM['ThisFileName'] . '.rollback');
                            }
                            rename($CIDRAM['Vault'] . $CIDRAM['ThisFileName'], $CIDRAM['Vault'] . $CIDRAM['ThisFileName'] . '.rollback');
                        }
                        $CIDRAM['Components']['BytesAdded'] += strlen($CIDRAM['ThisFile']);
                        $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . $CIDRAM['ThisFileName'], 'w');
                        $CIDRAM['RemoteFiles'][$CIDRAM['ThisFileName']] = fwrite($CIDRAM['Handle'], $CIDRAM['ThisFile']);
                        $CIDRAM['RemoteFiles'][$CIDRAM['ThisFileName']] = ($CIDRAM['RemoteFiles'][$CIDRAM['ThisFileName']] !== false);
                        fclose($CIDRAM['Handle']);
                        $CIDRAM['ThisFile'] = '';
                    }
                    if ($CIDRAM['Rollback']) {
                        /** Prune unwanted empty directories (update/install failure+rollback). */
                        if (
                            !empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['To']) &&
                            is_array($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['To'])
                        ) {
                            array_walk($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']]['Files']['To'], function ($ThisFile) use (&$CIDRAM) {
                                if (!empty($ThisFile) && $CIDRAM['Traverse']($ThisFile)) {
                                    $CIDRAM['DeleteDirectory']($ThisFile);
                                }
                            });
                        }
                        $CIDRAM['UpdateFailed'] = true;
                    } else {
                        /** Prune unwanted files and directories (update/install success). */
                        if (!empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Files']['To'])) {
                            $CIDRAM['ThisArr'] = $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Files']['To'];
                            $CIDRAM['Arrayify']($CIDRAM['ThisArr']);
                            array_walk($CIDRAM['ThisArr'], function ($ThisFile) use (&$CIDRAM) {
                                if (!empty($ThisFile) && $CIDRAM['Traverse']($ThisFile)) {
                                    if (file_exists($CIDRAM['Vault'] . $ThisFile . '.rollback')) {
                                        unlink($CIDRAM['Vault'] . $ThisFile . '.rollback');
                                    }
                                    if (
                                        !isset($CIDRAM['RemoteFiles'][$ThisFile]) &&
                                        !isset($CIDRAM['IgnoredFiles'][$ThisFile]) &&
                                        file_exists($CIDRAM['Vault'] . $ThisFile)
                                    ) {
                                        $CIDRAM['Components']['BytesRemoved'] += filesize($CIDRAM['Vault'] . $ThisFile);
                                        unlink($CIDRAM['Vault'] . $ThisFile);
                                        $CIDRAM['DeleteDirectory']($ThisFile);
                                    }
                                }
                            });
                            unset($CIDRAM['ThisArr']);
                        }
                        /** Assign updated component annotation. */
                        $CIDRAM['FileData'][$CIDRAM['ThisReannotate']] = $CIDRAM['Components']['NewMeta'];
                        if (!isset($CIDRAM['Annotations'][$CIDRAM['ThisReannotate']])) {
                            $CIDRAM['Annotations'][$CIDRAM['ThisReannotate']] = $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['RemoteData'];
                        }
                        $CIDRAM['FE']['state_msg'] .= '<code>' . $CIDRAM['Components']['ThisTarget'] . '</code> – ';
                        if (
                            empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Version']) &&
                            empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Files'])
                        ) {
                            $CIDRAM['FE']['state_msg'] .= $CIDRAM['lang']['response_component_successfully_installed'];
                            if (!empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['When Install Succeeds'])) {
                                $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['When Install Succeeds']);
                            }
                        } else {
                            $CIDRAM['FE']['state_msg'] .= $CIDRAM['lang']['response_component_successfully_updated'];
                            if (!empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['When Update Succeeds'])) {
                                $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['When Update Succeeds']);
                            }
                        }
                        $CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']] =
                            $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['ThisTarget']];
                    }
                } else {
                    $CIDRAM['UpdateFailed'] = true;
                }
                if ($CIDRAM['UpdateFailed']) {
                    $CIDRAM['FE']['state_msg'] .= '<code>' . $CIDRAM['Components']['ThisTarget'] . '</code> – ';
                    if (
                        empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Version']) &&
                        empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['Files'])
                    ) {
                        $CIDRAM['FE']['state_msg'] .= $CIDRAM['lang']['response_failed_to_install'];
                        if (!empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['When Install Fails'])) {
                            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['When Install Fails']);
                        }
                    } else {
                        $CIDRAM['FE']['state_msg'] .= $CIDRAM['lang']['response_failed_to_update'];
                        if (!empty($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['When Update Fails'])) {
                            $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$CIDRAM['Components']['ThisTarget']]['When Update Fails']);
                        }
                    }
                }
                $CIDRAM['FormatFilesize']($CIDRAM['Components']['BytesAdded']);
                $CIDRAM['FormatFilesize']($CIDRAM['Components']['BytesRemoved']);
                $CIDRAM['FE']['state_msg'] .= sprintf(
                    $CIDRAM['FE']['CronMode'] ? " « +%s | -%s | %s »\n" : ' <code><span class="txtGn">+%s</span> | <span class="txtRd">-%s</span> | <span class="txtOe">%s</span></code><br />',
                    $CIDRAM['Components']['BytesAdded'],
                    $CIDRAM['Components']['BytesRemoved'],
                    $CIDRAM['Number_L10N'](microtime(true) - $CIDRAM['Components']['TimeRequired'], 3)
                );
            }
            /** Update annotations. */
            foreach ($CIDRAM['FileData'] as $CIDRAM['ThisKey'] => $CIDRAM['ThisFile']) {
                /** Remove superfluous metadata. */
                if (!empty($CIDRAM['Annotations'][$CIDRAM['ThisKey']])) {
                    $CIDRAM['ThisFile'] = $CIDRAM['Congruency']($CIDRAM['ThisFile'], $CIDRAM['Annotations'][$CIDRAM['ThisKey']]);
                }
                $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . $CIDRAM['ThisKey'], 'w');
                fwrite($CIDRAM['Handle'], $CIDRAM['ThisFile']);
                fclose($CIDRAM['Handle']);
            }
            /** Cleanup. */
            unset(
                $CIDRAM['ThisPath'],
                $CIDRAM['ThisName'],
                $CIDRAM['ThisKey'],
                $CIDRAM['ThisFile'],
                $CIDRAM['Annotations'],
                $CIDRAM['FileData'],
                $CIDRAM['ThisFileName'],
                $CIDRAM['Rollback'],
                $CIDRAM['IgnoredFiles'],
                $CIDRAM['RemoteFiles'],
                $CIDRAM['ThisReannotate']
            );
        }

        /** Uninstall a component. */
        if ($_POST['do'] === 'uninstall-component' && !empty($_POST['ID'])) {
            $CIDRAM['ComponentFunctionUpdatePrep']();
            $CIDRAM['Components']['BytesRemoved'] = 0;
            $CIDRAM['Components']['TimeRequired'] = microtime(true);
            if (
                empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['InUse']) &&
                !empty($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To']) &&
                ($_POST['ID'] !== 'l10n/' . $CIDRAM['Config']['general']['lang']) &&
                ($_POST['ID'] !== 'theme/' . $CIDRAM['Config']['template_data']['theme']) &&
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
                        ["/\n Files:(\n  [^\n]*)*\n/i", "/\n Version: [^\n]*\n/i"],
                        "\n",
                        $CIDRAM['Components']['OldMetaMatches']
                    ),
                    $CIDRAM['Components']['OldMeta']
                );
                array_walk($CIDRAM['Components']['Meta'][$_POST['ID']]['Files']['To'], function ($ThisFile) use (&$CIDRAM) {
                    if (!empty($ThisFile) && $CIDRAM['Traverse']($ThisFile)) {
                        if (file_exists($CIDRAM['Vault'] . $ThisFile)) {
                            $CIDRAM['Components']['BytesRemoved'] += filesize($CIDRAM['Vault'] . $ThisFile);
                            unlink($CIDRAM['Vault'] . $ThisFile);
                        }
                        if (file_exists($CIDRAM['Vault'] . $ThisFile . '.rollback')) {
                            $CIDRAM['Components']['BytesRemoved'] += filesize($CIDRAM['Vault'] . $ThisFile . '.rollback');
                            unlink($CIDRAM['Vault'] . $ThisFile . '.rollback');
                        }
                        $CIDRAM['DeleteDirectory']($ThisFile);
                    }
                });
                $CIDRAM['Handle'] =
                    fopen($CIDRAM['Vault'] . $CIDRAM['Components']['Meta'][$_POST['ID']]['Reannotate'], 'w');
                fwrite($CIDRAM['Handle'], $CIDRAM['Components']['NewMeta']);
                fclose($CIDRAM['Handle']);
                $CIDRAM['Components']['Meta'][$_POST['ID']]['Version'] = false;
                $CIDRAM['Components']['Meta'][$_POST['ID']]['Files'] = false;
                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_component_successfully_uninstalled'];
                if (!empty($CIDRAM['Components']['Meta'][$_POST['ID']]['When Uninstall Succeeds'])) {
                    $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$_POST['ID']]['When Uninstall Succeeds']);
                }
            } else {
                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_component_uninstall_error'];
                if (!empty($CIDRAM['Components']['Meta'][$_POST['ID']]['When Uninstall Fails'])) {
                    $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$_POST['ID']]['When Uninstall Fails']);
                }
            }
            $CIDRAM['FormatFilesize']($CIDRAM['Components']['BytesRemoved']);
            $CIDRAM['FE']['state_msg'] .= sprintf(
                $CIDRAM['FE']['CronMode'] ? " « -%s | %s »\n" : ' <code><span class="txtRd">-%s</span> | <span class="txtOe">%s</span></code>',
                $CIDRAM['Components']['BytesRemoved'],
                $CIDRAM['Number_L10N'](microtime(true) - $CIDRAM['Components']['TimeRequired'], 3)
            );
        }

        /** Activate a component. */
        if ($_POST['do'] === 'activate-component' && !empty($_POST['ID'])) {
            $CIDRAM['Activation'] = [
                'Config' => $CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile']),
                'ipv4' => $CIDRAM['Config']['signatures']['ipv4'],
                'ipv6' => $CIDRAM['Config']['signatures']['ipv6'],
                'modules' => $CIDRAM['Config']['signatures']['modules'],
                'modified' => false
            ];
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
                if (!empty($CIDRAM['Components']['Meta'][$_POST['ID']]['When Activation Fails'])) {
                    $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$_POST['ID']]['When Activation Fails']);
                }
            } else {
                $CIDRAM['Activation']['Config'] = str_replace(
                    [
                        "\r\nipv4='" . $CIDRAM['Config']['signatures']['ipv4'] . "'\r\n",
                        "\r\nipv6='" . $CIDRAM['Config']['signatures']['ipv6'] . "'\r\n",
                        "\r\nmodules='" . $CIDRAM['Config']['signatures']['modules'] . "'\r\n"
                    ], [
                        "\r\nipv4='" . $CIDRAM['Activation']['ipv4'] . "'\r\n",
                        "\r\nipv6='" . $CIDRAM['Activation']['ipv6'] . "'\r\n",
                        "\r\nmodules='" . $CIDRAM['Activation']['modules'] . "'\r\n"
                    ],
                    $CIDRAM['Activation']['Config']
                );
                $CIDRAM['Config']['signatures']['ipv4'] = $CIDRAM['Activation']['ipv4'];
                $CIDRAM['Config']['signatures']['ipv6'] = $CIDRAM['Activation']['ipv6'];
                $CIDRAM['Config']['signatures']['modules'] = $CIDRAM['Activation']['modules'];
                $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile'], 'w');
                fwrite($CIDRAM['Handle'], $CIDRAM['Activation']['Config']);
                fclose($CIDRAM['Handle']);
                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_activated'];
                if (!empty($CIDRAM['Components']['Meta'][$_POST['ID']]['When Activation Succeeds'])) {
                    $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$_POST['ID']]['When Activation Succeeds']);
                }
            }
            unset($CIDRAM['Activation']);
        }

        /** Deactivate a component. */
        if ($_POST['do'] === 'deactivate-component' && !empty($_POST['ID'])) {
            $CIDRAM['Deactivation'] = [
                'Config' => $CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile']),
                'ipv4' => $CIDRAM['Config']['signatures']['ipv4'],
                'ipv6' => $CIDRAM['Config']['signatures']['ipv6'],
                'modules' => $CIDRAM['Config']['signatures']['modules'],
                'modified' => false
            ];
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
                if (!empty($CIDRAM['Components']['Meta'][$_POST['ID']]['When Deactivation Fails'])) {
                    $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$_POST['ID']]['When Deactivation Fails']);
                }
            } else {
                $CIDRAM['Deactivation']['Config'] = str_replace(
                    [
                        "\r\nipv4='" . $CIDRAM['Config']['signatures']['ipv4'] . "'\r\n",
                        "\r\nipv6='" . $CIDRAM['Config']['signatures']['ipv6'] . "'\r\n",
                        "\r\nmodules='" . $CIDRAM['Config']['signatures']['modules'] . "'\r\n"
                    ], [
                        "\r\nipv4='" . $CIDRAM['Deactivation']['ipv4'] . "'\r\n",
                        "\r\nipv6='" . $CIDRAM['Deactivation']['ipv6'] . "'\r\n",
                        "\r\nmodules='" . $CIDRAM['Deactivation']['modules'] . "'\r\n"
                    ],
                    $CIDRAM['Deactivation']['Config']
                );
                $CIDRAM['Config']['signatures']['ipv4'] = $CIDRAM['Deactivation']['ipv4'];
                $CIDRAM['Config']['signatures']['ipv6'] = $CIDRAM['Deactivation']['ipv6'];
                $CIDRAM['Config']['signatures']['modules'] = $CIDRAM['Deactivation']['modules'];
                $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . $CIDRAM['FE']['ActiveConfigFile'], 'w');
                fwrite($CIDRAM['Handle'], $CIDRAM['Deactivation']['Config']);
                fclose($CIDRAM['Handle']);
                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_deactivated'];
                if (!empty($CIDRAM['Components']['Meta'][$_POST['ID']]['When Deactivation Succeeds'])) {
                    $CIDRAM['FE_Executor']($CIDRAM['Components']['Meta'][$_POST['ID']]['When Deactivation Succeeds']);
                }
            }
            unset($CIDRAM['Deactivation']);
        }

    }

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_updates'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
        ['username' => $CIDRAM['FE']['UserRaw']],
        $CIDRAM['lang']['tip_updates']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    $CIDRAM['FE']['UpdatesRow'] = $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_updates_row.html'));

    $CIDRAM['Components'] = [
        'Meta' => $CIDRAM['Components']['Meta'],
        'RemoteMeta' => $CIDRAM['Components']['RemoteMeta'],
        'Remotes' => [],
        'Dependencies' => [],
        'Outdated' => [],
        'Out' => []
    ];

    /** Prepare installed component metadata and options for display. */
    foreach ($CIDRAM['Components']['Meta'] as $CIDRAM['Components']['Key'] => &$CIDRAM['Components']['ThisComponent']) {
        if (empty($CIDRAM['Components']['ThisComponent']['Name']) && empty($CIDRAM['lang']['Name: ' . $CIDRAM['Components']['Key']])) {
            $CIDRAM['Components']['ThisComponent'] = '';
            continue;
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
                $CIDRAM['Components']['ThisComponent']['Version'] = $CIDRAM['lang']['response_updates_not_installed'];
                $CIDRAM['Components']['ThisComponent']['StatClass'] = 'txtRd';
                $CIDRAM['Components']['ThisComponent']['StatusOptions'] = $CIDRAM['lang']['response_updates_not_installed'];
            } else {
                $CIDRAM['Components']['ThisComponent']['Version'] = $CIDRAM['lang']['response_updates_unable_to_determine'];
                $CIDRAM['Components']['ThisComponent']['StatClass'] = 's';
            }
        }
        if (empty($CIDRAM['Components']['ThisComponent']['Remote'])) {
            $CIDRAM['Components']['ThisComponent']['RemoteData'] = $CIDRAM['lang']['response_updates_unable_to_determine'];
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
            if (!empty($CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Extended Description'])) {
                $CIDRAM['Components']['ThisComponent']['Extended Description'] =
                    $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Extended Description'];
                $CIDRAM['PrepareExtendedDescription']($CIDRAM['Components']['ThisComponent'], $CIDRAM['Components']['Key']);
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
                    if (
                        $CIDRAM['Components']['Key'] === 'l10n/' . $CIDRAM['Config']['general']['lang'] ||
                        $CIDRAM['Components']['Key'] === 'theme/' . $CIDRAM['Config']['template_data']['theme']
                    ) {
                        $CIDRAM['Components']['Dependencies'][] = $CIDRAM['Components']['Key'];
                    }
                    $CIDRAM['Components']['Outdated'][] = $CIDRAM['Components']['Key'];
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
                            ['V' => $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required PHP']],
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
                    ($CIDRAM['Components']['Key'] === 'theme/' . $CIDRAM['Config']['template_data']['theme']) ||
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
            $CIDRAM['Components']['ThisComponent']['Latest'] = $CIDRAM['lang']['response_updates_unable_to_determine'];
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
                    ['V' => $CIDRAM['Components']['RemoteMeta'][$CIDRAM['Components']['Key']]['Minimum Required PHP']],
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
        ) ? '' : '<br />' . $CIDRAM['lang']['field_filename'] . $CIDRAM['Components']['ThisComponent']['Files']['To'][0];
        /** Finalise entry. */
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
            ["/\n Files:(\n  [^\n]*)*\n/i", "/\n Version: [^\n]*\n/i"],
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
        $CIDRAM['Components']['ThisComponent']['Version'] = $CIDRAM['lang']['response_updates_not_installed'];
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
            ['V' => $CIDRAM['Components']['ThisComponent']['Minimum Required PHP']],
            $CIDRAM['lang']['response_updates_not_installed_php']
        ) :
            $CIDRAM['lang']['response_updates_not_installed'] .
            '<br /><select name="do" class="auto"><option value="update-component">' .
            $CIDRAM['lang']['field_install'] . '</option></select><input type="submit" value="' .
            $CIDRAM['lang']['field_ok'] . '" class="auto" />';
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
    uksort($CIDRAM['FE']['Indexes'], $CIDRAM['UpdatesSortFunc']);
    $CIDRAM['FE']['Indexes'] = implode('', $CIDRAM['FE']['Indexes']);
    uksort($CIDRAM['Components']['Out'], $CIDRAM['UpdatesSortFunc']);
    $CIDRAM['FE']['Components'] = implode('', $CIDRAM['Components']['Out']);

    /** Instructions to update everything at once. */
    if (count($CIDRAM['Components']['Outdated'])) {
        $CIDRAM['FE']['UpdateAll'] =
            '<hr /><form action="?' . $CIDRAM['FE']['UpdatesFormTarget'] .
            '" method="POST"><input name="cidram-form-target" type="hidden" value="updates" />' .
            '<input name="do" type="hidden" value="update-component" />';
        foreach ($CIDRAM['Components']['Outdated'] as $CIDRAM['Components']['ThisOutdated']) {
            $CIDRAM['FE']['UpdateAll'] .= '<input name="ID[]" type="hidden" value="' . $CIDRAM['Components']['ThisOutdated'] . '" />';
        }
        $CIDRAM['FE']['UpdateAll'] .=
            '<input type="submit" value="' . $CIDRAM['lang']['field_update_all'] . '" class="auto" /></form>';
    } else {
        $CIDRAM['FE']['UpdateAll'] = '';
    }

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_updates.html'))
    );

    /** Inject dependencies into update instructions for core package component. */
    if (count($CIDRAM['Components']['Dependencies'])) {
        $CIDRAM['FE']['FE_Content'] = str_replace('<input name="ID" type="hidden" value="CIDRAM" />',
            '<input name="ID[]" type="hidden" value="' .
            implode('" /><input name="ID[]" type="hidden" value="', $CIDRAM['Components']['Dependencies']) .
            '" /><input name="ID[]" type="hidden" value="CIDRAM" />',
        $CIDRAM['FE']['FE_Content']);
    }

    /** Send output. */
    if (!$CIDRAM['FE']['CronMode']) {
        /** Normal page output. */
        echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);
    } elseif (!empty($CIDRAM['FE']['state_msg'])) {
        /** Returned state message for cronable. */
        echo json_encode([
            'state_msg' => str_ireplace(['<code>', '</code>', '<br />'], ['[', ']', "\n"], $CIDRAM['FE']['state_msg'])
        ]);
    } elseif (!empty($_POST['do']) && $_POST['do'] === 'get-list' && count($CIDRAM['Components']['Outdated'])) {
        /** Returned list of outdated components for cronable. */
        echo json_encode([
            'state_msg' => str_ireplace(['<code>', '</code>', '<br />'], ['[', ']', "\n"], $CIDRAM['FE']['state_msg']),
            'outdated' => $CIDRAM['Components']['Outdated']
        ]);
    }

    /** Cleanup. */
    unset($CIDRAM['Components'], $CIDRAM['Count'], $CIDRAM['Iterate']);

}

/** File Manager. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'file-manager' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_file_manager'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
        ['username' => $CIDRAM['FE']['UserRaw']],
        $CIDRAM['lang']['tip_file_manager']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

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
    $CIDRAM['Components'] = ['Files', 'Components', 'Names'];

    /** Show/hide pie charts link and etc. */
    if (!$CIDRAM['PieFile']) {

        $CIDRAM['FE']['FMgrFormTarget'] = 'cidram-page=file-manager';
        $CIDRAM['FE']['ShowHideLink'] = '<a href="?cidram-page=file-manager&show=true">' . $CIDRAM['lang']['label_show'] . '</a>';

    } else {

        $CIDRAM['FE']['FMgrFormTarget'] = 'cidram-page=file-manager&show=true';
        $CIDRAM['FE']['ShowHideLink'] = '<a href="?cidram-page=file-manager">' . $CIDRAM['lang']['label_hide'] . '</a>';

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
        isset($_POST['filename'], $_POST['do']) &&
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
                $CIDRAM['DeleteDirectory']($_POST['filename']);

                $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_file_deleted'];
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
                        $CIDRAM['DeleteDirectory']($_POST['filename']);

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
                    $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_files_rename.html'))
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
                    $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_files_edit.html'))
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
    $CIDRAM['FE']['FilesRow'] = $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_files_row.html'));

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
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
        $CIDRAM['FE']['PieChartHTML'] = '';

        /** Building pie chart values. */
        foreach ($CIDRAM['Components']['Components'] as $CIDRAM['Components']['ThisName'] => $CIDRAM['Components']['ThisData']) {
            if (empty($CIDRAM['Components']['ThisData'])) {
                continue;
            }
            $CIDRAM['Components']['ThisSize'] = $CIDRAM['Components']['ThisData'];
            $CIDRAM['FormatFilesize']($CIDRAM['Components']['ThisSize']);
            $CIDRAM['Components']['ThisName'] .= ' – ' . $CIDRAM['Components']['ThisSize'];
            $CIDRAM['FE']['PieChartValues'][] = $CIDRAM['Components']['ThisData'];
            $CIDRAM['FE']['PieChartLabels'][] = $CIDRAM['Components']['ThisName'];
            if ($CIDRAM['PiePath']) {
                $CIDRAM['Components']['ThisColour'] = substr(md5($CIDRAM['Components']['ThisName']), 0, 6);
                $CIDRAM['Components']['RGB'] =
                    hexdec(substr($CIDRAM['Components']['ThisColour'], 0, 2)) . ',' .
                    hexdec(substr($CIDRAM['Components']['ThisColour'], 2, 2)) . ',' .
                    hexdec(substr($CIDRAM['Components']['ThisColour'], 4, 2));
                $CIDRAM['FE']['PieChartColours'][] = '#' . $CIDRAM['Components']['ThisColour'];
                $CIDRAM['FE']['PieChartHTML'] .=
                    '<span style="background:linear-gradient(90deg,rgba(' .
                    $CIDRAM['Components']['RGB'] . ',0.3),rgba(' .
                    $CIDRAM['Components']['RGB'] . ',0))"><span style="color:#' .
                    $CIDRAM['Components']['ThisColour'] . '">➖</span> ' .
                    $CIDRAM['Components']['ThisName'] . "</span><br />\n";
            } else {
                $CIDRAM['FE']['PieChartHTML'] .= '➖ ' . $CIDRAM['Components']['ThisName'] . "<br />\n";
            }
        }

        /** Finalise pie chart values. */
        $CIDRAM['FE']['PieChartValues'] = '[' . implode(', ', $CIDRAM['FE']['PieChartValues']) . ']';

        /** Finalise pie chart labels. */
        $CIDRAM['FE']['PieChartLabels'] = '["' . implode('", "', $CIDRAM['FE']['PieChartLabels']) . '"]';

        /** Finalise pie chart colours. */
        $CIDRAM['FE']['PieChartColours'] = '["' . implode('", "', $CIDRAM['FE']['PieChartColours']) . '"]';

        /** Finalise pie chart. */
        $CIDRAM['FE']['PieChart'] = $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['PieFile']);

    }

    /** Cleanup. */
    unset($CIDRAM['PieFile'], $CIDRAM['PiePath'], $CIDRAM['Components']);

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
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** IP Aggregator. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'ip-aggregator' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_ip_aggregator'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
        ['username' => $CIDRAM['FE']['UserRaw']],
        $CIDRAM['lang']['tip_ip_aggregator']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    /** Results counts. */
    $CIDRAM['Results'] = ['In' => 0, 'Rejected' => 0, 'Accepted' => 0, 'Merged' => 0, 'Out' => 0];

    /** Data was submitted for aggregation. */
    if (isset($_POST['input'])) {
        $CIDRAM['FE']['input'] = $_POST['input'];
        require $CIDRAM['Vault'] . 'aggregator.php';
        $CIDRAM['Aggregator'] = new Aggregator($CIDRAM);
        $CIDRAM['FE']['output'] = $CIDRAM['Aggregator']->aggregate($_POST['input']);
        unset($CIDRAM['Aggregator']);
        $CIDRAM['FE']['ResultLine'] = sprintf(
            $CIDRAM['lang']['label_results'],
            '<span class="txtRd">' . $CIDRAM['Number_L10N']($CIDRAM['Results']['In']) . '</span>',
            '<span class="txtRd">' . $CIDRAM['Number_L10N']($CIDRAM['Results']['Rejected']) . '</span>',
            '<span class="txtRd">' . $CIDRAM['Number_L10N']($CIDRAM['Results']['Accepted']) . '</span>',
            '<span class="txtRd">' . $CIDRAM['Number_L10N']($CIDRAM['Results']['Merged']) . '</span>',
            '<span class="txtRd">' . $CIDRAM['Number_L10N']($CIDRAM['Results']['Out']) . '</span>'
        );
    } else {
        $CIDRAM['FE']['output'] = $CIDRAM['FE']['input'] = '';
    }

    /** Calculate page load time (useful for debugging). */
    $CIDRAM['FE']['state_msg'] .= sprintf($CIDRAM['lang']['state_loadtime'], $CIDRAM['Number_L10N'](microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 3));

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_ip_aggregator.html'))
    );

    /** Strip output row if input doesn't exist. */
    if (isset($_POST['input'])) {
        $CIDRAM['FE']['FE_Content'] = str_replace(['<!-- Output Begin -->', '<!-- Output End -->'], '', $CIDRAM['FE']['FE_Content']);
    } else {
        $CIDRAM['Markers'] = [
            'Begin' => strpos($CIDRAM['FE']['FE_Content'], '<!-- Output Begin -->'),
            'End' => strpos($CIDRAM['FE']['FE_Content'], '<!-- Output End -->')
        ];
        $CIDRAM['FE']['FE_Content'] =
            substr($CIDRAM['FE']['FE_Content'], 0, $CIDRAM['Markers']['Begin']) .
            substr($CIDRAM['FE']['FE_Content'], $CIDRAM['Markers']['End'] + 19);
        unset($CIDRAM['Markers']);
    }

    /** Send output. */
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** IP Test. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'ip-test' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_ip_test'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
        ['username' => $CIDRAM['FE']['UserRaw']],
        $CIDRAM['lang']['tip_ip_test']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    /** Template for result rows. */
    $CIDRAM['FE']['IPTestRow'] = $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_ip_test_row.html'));

    /** Initialise results data. */
    $CIDRAM['FE']['IPTestResults'] = '';

    /** IPs were submitted for testing. */
    if (isset($_POST['ip-addr'])) {
        $CIDRAM['FE']['ip-addr'] = $_POST['ip-addr'];
        $_POST['ip-addr'] = array_unique(array_map(function ($IP) {
            return preg_replace("\x01[^0-9a-f:./]\x01i", '', $IP);
        }, explode("\n", $_POST['ip-addr'])));
        natsort($_POST['ip-addr']);
        $CIDRAM['ThisIP'] = [];
        foreach ($_POST['ip-addr'] as $CIDRAM['ThisIP']['IPAddress']) {
            if (!$CIDRAM['ThisIP']['IPAddress']) {
                continue;
            }
            $CIDRAM['SimulateBlockEvent']($CIDRAM['ThisIP']['IPAddress']);
            if ($CIDRAM['Caught'] || empty($CIDRAM['LastTestIP']) || empty($CIDRAM['TestResults'])) {
                $CIDRAM['ThisIP']['YesNo'] = $CIDRAM['lang']['response_error'];
                $CIDRAM['ThisIP']['StatClass'] = 'txtOe';
            } elseif ($CIDRAM['BlockInfo']['SignatureCount']) {
                $CIDRAM['ThisIP']['YesNo'] = $CIDRAM['lang']['response_yes'] . ' – ' . $CIDRAM['BlockInfo']['WhyReason'];
                $CIDRAM['ThisIP']['StatClass'] = 'txtRd';
            } else {
                $CIDRAM['ThisIP']['YesNo'] = $CIDRAM['lang']['response_no'];
                $CIDRAM['ThisIP']['StatClass'] = 'txtGn';
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
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_ip_test.html'))
    );

    /** Send output. */
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** IP Tracking. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'ip-tracking' && $CIDRAM['FE']['Permissions'] === 1) {

    $CIDRAM['FE']['TrackingFilter'] = 'cidram-page=ip-tracking';
    $CIDRAM['FE']['TrackingFilterControls'] = '';
    $CIDRAM['StateModified'] = false;
    $CIDRAM['FilterSwitch'](
        ['tracking-blocked-already', 'tracking-hide-banned-blocked'],
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

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_ip_tracking'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
        ['username' => $CIDRAM['FE']['UserRaw']],
        $CIDRAM['lang']['tip_ip_tracking']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    /** Template for result rows. */
    $CIDRAM['FE']['TrackingRow'] = $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_ip_tracking_row.html'));

    /** Initialise variables. */
    $CIDRAM['FE']['TrackingData'] = '';
    $CIDRAM['FE']['TrackingCount'] = '';
    $CIDRAM['ThisTracking'] = [];

    /** Fetch cache.dat data. */
    $CIDRAM['Cache'] = file_exists($CIDRAM['Vault'] . 'cache.dat') ? unserialize($CIDRAM['ReadFile']($CIDRAM['Vault'] . 'cache.dat')) : [];

    /** Clear/revoke IP tracking for an IP address. */
    if (isset($_POST['IPAddr'])) {
        if ($_POST['IPAddr'] === '*') {
            unset($CIDRAM['Cache']['Tracking']);
            $CIDRAM['Cleared'] = true;
        } elseif (isset($CIDRAM['Cache']['Tracking'][$_POST['IPAddr']])) {
            unset($CIDRAM['Cache']['Tracking'][$_POST['IPAddr']]);
            $CIDRAM['Cleared'] = true;
        }
        if (!empty($CIDRAM['Cleared'])) {
            $CIDRAM['FE']['state_msg'] = $CIDRAM['lang']['response_tracking_cleared'];
            $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'cache.dat', 'w');
            fwrite($CIDRAM['Handle'], serialize($CIDRAM['Cache']));
            fclose($CIDRAM['Handle']);
            unset($CIDRAM['Cleared']);
        }
    }

    /** Process IP tracking data. */
    if (!empty($CIDRAM['Cache']['Tracking']) && is_array($CIDRAM['Cache']['Tracking'])) {

        /** Count currently tracked IPs. */
        $CIDRAM['FE']['TrackingCount'] = count($CIDRAM['Cache']['Tracking']);
        $CIDRAM['FE']['TrackingCount'] = sprintf(
            $CIDRAM['Plural']($CIDRAM['FE']['TrackingCount'], $CIDRAM['lang']['state_tracking']),
            '<span class="txtRd">' . $CIDRAM['Number_L10N']($CIDRAM['FE']['TrackingCount']) . '</span>'
        );

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
            /** Check whether normally blocked by signature files. */
            if ($CIDRAM['FE']['tracking-blocked-already']) {
                $CIDRAM['SimulateBlockEvent']($CIDRAM['ThisTracking']['IPAddr']);
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
            /** Alternate between operating modes. */
            if (!empty($CIDRAM['Cache']['Subnets']) && is_array($CIDRAM['Cache']['Subnets'])) {
                $CIDRAM['ThisTrackingArr']['TimeCheck'] = $CIDRAM['ThisTrackingArr']['Time'] - $CIDRAM['Config']['signatures']['default_tracktime'];
                $CIDRAM['ThisTracking']['Options'] = ($CIDRAM['ThisTrackingArr']['TimeCheck'] < $CIDRAM['Now']) ? $CIDRAM['TimeFormat'](
                    $CIDRAM['ThisTrackingArr']['Time'] - $CIDRAM['Config']['signatures']['default_tracktime'],
                    $CIDRAM['Config']['general']['timeFormat']
                ) : $CIDRAM['lang']['field_filetype_unknown'];
            } else {
                /** Set clearing option. */
                $CIDRAM['ThisTracking']['Options'] = ($CIDRAM['ThisTrackingArr']['Count'] > 0) ?
                    '<input type="submit" value="' . $CIDRAM['lang']['field_clear'] . '" class="auto" />' : '';
            }
            $CIDRAM['ThisTracking']['Expiry'] =
                $CIDRAM['TimeFormat']($CIDRAM['ThisTrackingArr']['Time'], $CIDRAM['Config']['general']['timeFormat']);
            if ($CIDRAM['ThisTrackingArr']['Count'] >= $CIDRAM['Config']['signatures']['infraction_limit']) {
                $CIDRAM['ThisTracking']['StatClass'] = 'txtRd';
                $CIDRAM['ThisTracking']['Status'] = $CIDRAM['lang']['field_banned'];
            } elseif ($CIDRAM['ThisTrackingArr']['Count'] >= ($CIDRAM['Config']['signatures']['infraction_limit'] / 2)) {
                $CIDRAM['ThisTracking']['StatClass'] = 'txtOe';
                $CIDRAM['ThisTracking']['Status'] = $CIDRAM['lang']['field_tracking'];
            } else {
                $CIDRAM['ThisTracking']['StatClass'] = 's';
                $CIDRAM['ThisTracking']['Status'] = $CIDRAM['lang']['field_tracking'];
            }
            if ($CIDRAM['ThisTracking']['Blocked']) {
                $CIDRAM['ThisTracking']['StatClass'] = 'txtRd';
                $CIDRAM['ThisTracking']['Status'] .= '/' . $CIDRAM['lang']['field_blocked'];
            }
            $CIDRAM['ThisTracking']['Status'] .= ' – ' . $CIDRAM['Number_L10N']($CIDRAM['ThisTrackingArr']['Count'], 0);
            $CIDRAM['ThisTracking']['TrackingFilter'] = $CIDRAM['FE']['TrackingFilter'];
            $CIDRAM['FE']['TrackingData'] .= $CIDRAM['ParseVars'](
                $CIDRAM['lang'] + $CIDRAM['ThisTracking'],
                $CIDRAM['FE']['TrackingRow']
            );
        }
    }

    /** Set options label. */
    $CIDRAM['FE']['OptionsLabel'] = (
        !empty($CIDRAM['Cache']['Subnets']) && is_array($CIDRAM['Cache']['Subnets']) ? $CIDRAM['lang']['field_first_seen'] : $CIDRAM['lang']['field_options']
    );

    /** Cleanup. */
    unset($CIDRAM['Cache'], $CIDRAM['ThisTracking']);

    /** Fix status display. */
    if ($CIDRAM['FE']['state_msg']) {
        $CIDRAM['FE']['state_msg'] .= '<br />';
    }

    if ($CIDRAM['FE']['TrackingCount']) {
        $CIDRAM['FE']['TrackingCount'] .= ' ';
    }

    /** Calculate page load time (useful for debugging). */
    $CIDRAM['FE']['TrackingCount'] .= sprintf($CIDRAM['lang']['state_loadtime'], $CIDRAM['Number_L10N'](microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 3));

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_ip_tracking.html'))
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
        ['username' => $CIDRAM['FE']['UserRaw']],
        $CIDRAM['lang']['tip_cidr_calc']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    /** Template for result rows. */
    $CIDRAM['FE']['CalcRow'] = $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_cidr_calc_row.html'));

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
            $Arr = ['CIDR' => $CIDR, 'Range' => $First . ' – ' . $Last];
            $CIDRAM['FE']['Ranges'] .= $CIDRAM['ParseVars']($Arr, $CIDRAM['FE']['CalcRow']);
        });
    }

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_cidr_calc.html'))
    );

    /** Send output. */
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

}

/** Statistics. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'statistics' && $CIDRAM['FE']['Permissions'] === 1) {

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_statistics'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
        ['username' => $CIDRAM['FE']['UserRaw']],
        $CIDRAM['lang']['tip_statistics']
    );

    /** Display how to enable statistics if currently disabled. */
    if (!$CIDRAM['Config']['general']['statistics']) {
        $CIDRAM['FE']['state_msg'] .= '<span class="txtRd">' . $CIDRAM['lang']['tip_statistics_disabled'] . '</span><br />';
    }

    /** Fetch cache.dat data. */
    $CIDRAM['Cache'] = file_exists($CIDRAM['Vault'] . 'cache.dat') ? unserialize($CIDRAM['ReadFile']($CIDRAM['Vault'] . 'cache.dat')) : [];

    /** Clear statistics. */
    if (!empty($_POST['ClearStats'])) {
        if (isset($CIDRAM['Cache']['Statistics'])) {
            unset($CIDRAM['Cache']['Statistics']);
            $CIDRAM['Handle'] = fopen($CIDRAM['Vault'] . 'cache.dat', 'w');
            fwrite($CIDRAM['Handle'], serialize($CIDRAM['Cache']));
            fclose($CIDRAM['Handle']);
            unset($CIDRAM['Handle']);
        }
        $CIDRAM['FE']['state_msg'] .= $CIDRAM['lang']['response_statistics_cleared'] . '<br />';
    }

    /** Statistics have been counted since... */
    if (empty($CIDRAM['Cache']['Statistics']['Other-Since'])) {
        $CIDRAM['FE']['Other-Since'] = '<span class="s">-</span>';
    } else {
        $CIDRAM['FE']['Other-Since'] = '<span class="s">' . $CIDRAM['TimeFormat'](
            $CIDRAM['Cache']['Statistics']['Other-Since'],
            $CIDRAM['Config']['general']['timeFormat']
        ) . '</span>';
    }

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
        $CIDRAM['FE'][$CIDRAM['TheseStats'][0]] = '<span class="s">' . $CIDRAM['Number_L10N'](
            empty($CIDRAM['Cache']['Statistics'][$CIDRAM['TheseStats'][0]]) ? 0 : $CIDRAM['Cache']['Statistics'][$CIDRAM['TheseStats'][0]]
        ) . '</span>';
        if (!isset($CIDRAM['FE'][$CIDRAM['TheseStats'][1]])) {
            $CIDRAM['FE'][$CIDRAM['TheseStats'][1]] = 0;
        }
        $CIDRAM['FE'][$CIDRAM['TheseStats'][1]] += empty(
            $CIDRAM['Cache']['Statistics'][$CIDRAM['TheseStats'][0]]
        ) ? 0 : $CIDRAM['Cache']['Statistics'][$CIDRAM['TheseStats'][0]];
    }

    /** Fetch and process totals. */
    foreach (['Blocked-Total', 'Banned-Total', 'reCAPTCHA-Total'] as $CIDRAM['TheseStats']) {
        $CIDRAM['FE'][$CIDRAM['TheseStats']] = '<span class="s">' . $CIDRAM['Number_L10N'](
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
            $CIDRAM['FE'][$CIDRAM['TheseStats'][1]] = '<span class="txtRd">' . $CIDRAM['Number_L10N'](0) . '</span>';
        } else {
            $CIDRAM['FE'][$CIDRAM['TheseStats'][1]] = 0;
            $CIDRAM['StatWorking'] = explode(',', $CIDRAM['Config']['signatures'][$CIDRAM['TheseStats'][0]]);
            array_walk($CIDRAM['StatWorking'], function ($SigFile) use (&$CIDRAM) {
                if (!empty($SigFile) && is_readable($CIDRAM['Vault'] . $SigFile)) {
                    $CIDRAM['FE'][$CIDRAM['TheseStats'][1]]++;
                }
            });
            $CIDRAM['StatColour'] = $CIDRAM['FE'][$CIDRAM['TheseStats'][1]] ? 'txtGn' : 'txtRd';
            $CIDRAM['FE'][$CIDRAM['TheseStats'][1]] = '<span class="' . $CIDRAM['StatColour'] . '">' . $CIDRAM['Number_L10N'](
                $CIDRAM['FE'][$CIDRAM['TheseStats'][1]]
            ) . '</span>';
        }
    }

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_statistics.html'))
    );

    /** Send output. */
    echo $CIDRAM['ParseVars']($CIDRAM['lang'] + $CIDRAM['FE'], $CIDRAM['FE']['Template']);

    /** Cleanup. */
    unset($CIDRAM['StatColour'], $CIDRAM['StatWorking'], $CIDRAM['TheseStats'], $CIDRAM['Cache']);

}

/** Logs. */
elseif ($CIDRAM['QueryVars']['cidram-page'] === 'logs') {

    /** Set page title. */
    $CIDRAM['FE']['FE_Title'] = $CIDRAM['lang']['title_logs'];

    /** Prepare page tooltip/description. */
    $CIDRAM['FE']['FE_Tip'] = $CIDRAM['ParseVars'](
        ['username' => $CIDRAM['FE']['UserRaw']],
        $CIDRAM['lang']['tip_logs']
    );

    $CIDRAM['FE']['bNav'] = $CIDRAM['lang']['bNav_home_logout'];

    /** Parse output. */
    $CIDRAM['FE']['FE_Content'] = $CIDRAM['ParseVars'](
        $CIDRAM['lang'] + $CIDRAM['FE'],
        $CIDRAM['ReadFile']($CIDRAM['GetAssetPath']('_logs.html'))
    );

    /** Initialise array for fetching logs data. */
    $CIDRAM['FE']['LogFiles'] = [
        'Files' => $CIDRAM['Logs-RecursiveList']($CIDRAM['Vault']),
        'Out' => ''
    ];

    /** Text mode switch link base. */
    $CIDRAM['FE']['TextModeSwitchLink'] = '';

    /** How to display the log data? */
    if (empty($CIDRAM['QueryVars']['text-mode']) || $CIDRAM['QueryVars']['text-mode'] === 'false') {
        $CIDRAM['FE']['TextModeLinks'] = 'false';
        $CIDRAM['FE']['TextMode'] = false;
    } else {
        $CIDRAM['FE']['TextModeLinks'] = 'true';
        $CIDRAM['FE']['TextMode'] = true;
    }

    /** Define log data. */
    if (empty($CIDRAM['QueryVars']['logfile'])) {
        $CIDRAM['FE']['logfileData'] = $CIDRAM['lang']['logs_no_logfile_selected'];
    } elseif (empty($CIDRAM['FE']['LogFiles']['Files'][$CIDRAM['QueryVars']['logfile']])) {
        $CIDRAM['FE']['logfileData'] = $CIDRAM['lang']['logs_logfile_doesnt_exist'];
    } else {
        $CIDRAM['FE']['TextModeSwitchLink'] .= '?cidram-page=logs&logfile=' . $CIDRAM['QueryVars']['logfile'] . '&text-mode=';
        $CIDRAM['FE']['logfileData'] = $CIDRAM['ReadFile']($CIDRAM['Vault'] . $CIDRAM['QueryVars']['logfile']);
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

    /** Text mode switch link formatted. */
    $CIDRAM['FE']['TextModeSwitchLink'] = sprintf(
        $CIDRAM['lang']['link_textmode'],
        $CIDRAM['FE']['TextModeSwitchLink']
    );

    /** Prepare log data formatting. */
    if ($CIDRAM['FE']['TextMode']) {
        $CIDRAM['Formatter']($CIDRAM['FE']['logfileData']);
    } else {
        $CIDRAM['FE']['logfileData'] = '<textarea readonly>' . $CIDRAM['FE']['logfileData'] . '</textarea>';
    }

    /** Define logfile list. */
    array_walk($CIDRAM['FE']['LogFiles']['Files'], function ($Arr) use (&$CIDRAM) {
        $CIDRAM['FE']['LogFiles']['Out'] .= sprintf(
            '            <a href="?cidram-page=logs&logfile=%1$s&text-mode=%3$s">%1$s</a> – %2$s<br />',
            $Arr['Filename'],
            $Arr['Filesize'],
            $CIDRAM['FE']['TextModeLinks']
        ) . "\n";
    });

    /** Set logfile list or no logfiles available message. */
    $CIDRAM['FE']['LogFiles'] = $CIDRAM['FE']['LogFiles']['Out'] ?: $CIDRAM['lang']['logs_no_logfiles_available'];

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

/** Print Cronable failure state messages here. */
if ($CIDRAM['FE']['CronMode'] && $CIDRAM['FE']['state_msg'] && $CIDRAM['FE']['UserState'] !== 1) {
    echo json_encode(['state_msg' => $CIDRAM['FE']['state_msg']]);
}

/** Exit front-end. */
die;
