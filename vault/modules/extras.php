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
 * This file: Optional security extras module (last modified: 2024.07.16).
 *
 * False positive risk (an approximate, rough estimate only): « [ ]Low [x]Medium [ ]High »
 */

/** Safety. */
if (!isset($this->CIDRAM['ModuleResCache'])) {
    $this->CIDRAM['ModuleResCache'] = [];
}

/** Initialise honoured signatures information. */
$this->CIDRAM['ExtrasHonoured'] = array_flip(explode("\n", $this->Configuration['extras']['signatures']));

/** Defining as closure for later recall (no params; no return value). */
$this->CIDRAM['ModuleResCache'][$Module] = function () {
    /** The number of signatures triggered by this point in time. */
    $Before = $this->BlockInfo['SignaturesCount'] ?? 0;

    $this->trigger(count($_REQUEST) >= 500, 'Hack attempt', 'Too many request variables sent!'); // 2017.01.01

    /** Needed for some bypasses specific to WordPress (detects whether we're running as a WordPress plugin). */
    $is_WP_plugin = (defined('ABSPATH') || strtolower(str_replace('\\', '/', substr(__DIR__, -31))) === 'wp-content/plugins/cidram/vault');

    /** If enabled, block empty user agents. */
    if ($this->CIDRAM['ExtrasHonoured']['empty_ua']) {
        $this->trigger(preg_replace('~[^\w\d]~i', '', $this->BlockInfo['UA']) === '', 'Empty UA');
    }

    /**
     * Signatures based on the reconstructed URI start from here.
     * Please report all false positives to https://github.com/CIDRAM/CIDRAM/issues
     */
    if ($this->CIDRAM['ExtrasHonoured']['ruri'] && $this->BlockInfo['rURI']) {
        $LCNrURI = str_replace('\\', '/', strtolower($this->BlockInfo['rURI']));

        /** Directory traversal protection. */
        if (!$this->trigger(preg_match('~%5[cf]\.{2,}%5[cf]~', $LCNrURI), 'Traversal attack')) {
            /** Detect bad/dangerous/malformed requests. */
            $this->trigger(preg_match('~%5[cf]\.%5[cf]|%5[cf]{3,}|[\x00-\x1f\x7f]~', $LCNrURI), 'Bad request'); // 2017.01.13 mod 2024.02.08
        } // 2017.01.13 mod 2024.02.08

        /** WordPress user enumeration (modified 2022.11.07). */
        if ($this->trigger(preg_match('~\?author=\d+~i', $LCNrURI), 'WordPress user enumeration not allowed')) {
            $this->bypass(
                strpos($LCNrURI, 'administrator/') !== false,
                'Joomla image inserting tool bypass (WordPress user enumeration conflict)'
            ) || $this->bypass(
                strpos($LCNrURI, 'search.php?keywords=') !== false,
                'phpBB search bypass (WordPress user enumeration conflict)'
            );
        }

        $this->trigger(strpos($LCNrURI, 'wp-print.php?script=1') !== false, 'WP hack attempt'); // 2017.10.07 mod 2023.08.10

        /** Probing for quarantined files. */
        if ($this->trigger(preg_match('~\.[\da-z]{2,4}\.suspected(?:$|[/?])~', $LCNrURI), 'Probing for quarantined files')) {
            $this->Reporter->report([15], ['Caught probing for quarantined files.'], $this->BlockInfo['IPAddr']);
        } // 2017.03.22 mod 2023.08.13

        /** Probing for unsecured backup files. */
        if ($this->trigger(preg_match(
            '~(?:/backup|(?:backup|docroot|htdocs|public_html|site|www)\.(?:gz|rar|tar(?:\.gz)?|zip)|d(?:atabase|b|ump)\.sql)(?:$|[/?])~',
            $LCNrURI
        ), 'Probing for unsecured backup files not allowed')) {
            $this->Reporter->report([15], ['Caught probing for unsecured backup files.'], $this->BlockInfo['IPAddr']);
        } // 2023.08.13 mod 2023.08.21

        /** Probing for unsecured SQL dumps. */
        if ($this->trigger(preg_match(
            '~^[^?]*[^/?]+\.sql(?:\.(?:b[ac]k|bz|new|old|t?gz|7?zip|[rt]ar))?(?:$|[/?])~',
            $LCNrURI
        ), 'Probing for unsecured SQL dumps not allowed')) {
            $this->Reporter->report([15], ['Caught probing for unsecured SQL dumps.'], $this->BlockInfo['IPAddr']);
        } // 2024.05.12

        /** Probing for unsecured WordPress configuration files. */
        if ($this->trigger(preg_match(
            '~(?:^|[/?.]|\._)wp-config\.php(?:\.(?:bak\d*|bkp|conf|dist|du?mp|inc|new|old|orig|sw.|tar|te?mp|txt|[\d\~#_]+)|[-.]backup)?(?:$|[/?])~',
            $LCNrURI
        ), 'Probing for unsecured WordPress configuration files not allowed')) {
            $this->Reporter->report([15, 20, 21], ['Caught probing for unsecured WordPress configuration files.'], $this->BlockInfo['IPAddr']);
        } // 2023.09.02 mod 2023.09.04

        /** Probing for webshells/backdoors. */
        if ($this->trigger(preg_match(
            '~^/{3,}wp-|(?:^|[/?])(?:mt-xmlrpc\.cgi|shell\?cd|wp-includes/wlwmanifest\.xml)(?:$|[/?])|(?:^|[/?])(?:' .
            '\+theme\+/(?:error|index)|' .
            '\.w(?:ell-known|p-cli)/.*(?:a(?:bout|dmin)[\da-z]*|fierza[\da-z]*|install[\da-z]*|moon[\da-z]*|shell[\da-z]*|wp-login[\da-z]*|x)|' .
            '\.?rxr(?:_[\da-z]+)?|' .
            '\d{3,5}[a-z]{3,5}|\d+-?backdoor|0byte|0[xz]|10+|991176|' .
            'a(?:dmin-heade\d*|dminfuns|hhygskn|lfa(?:-rex|_data|a?cgiapi|ioxi|new)?\d*|njas|pismtp|xx)|' .
            'b0|b3d2acc621a0|bak|bala|' .
            'c(?:(?:9|10)\d+|asper[\da-z]+|d(?:.*tmp.*rm-rf|chmod.*\d{3,})|fom[-_]files|(?:gi-bin|ss)/(?:luci/;|moon|newgolden|radio|sgd|stok=/|uploader|well-known|wp-login)|jfuns|lasssmtps|olors/blue/uploader|ong)|' .
            'd7|deadcode\d*|dkiz|' .
            'ee|' .
            'f(?:ddqradz|ilefuns?)|' .
            'gel4y|gh[0o]st|glab-rare|gzismexv|' .
            'h[4a]x+[0o]r|h6ss|hanna1337|hehehe|htmlawedtest|' .
            'i(?:\d{3,}[a-z]{2,}|cesword|ndoxploit|optimize|r7szrsouep|itsec|xr/(?:allez|wp-login))|' .
            'lock0?360|lufix(?:-shell)?|' .
            'miin|my1|' .
            'old/wp-admin/install|orvx(?:-shell)?|' .
            'perl\.alfa|php(?:1|_niu_\d+)|(?:plugins|themes)/(?:ccx|ioptimization|yyobang)|poison|priv8|pzaiihfi|' .
            'rendixd|' .
            's(?:ession91|h[3e]llx?\d*|hrift|idwso|ilic|kipper(?:shell)?|onarxleetxd|pammervip|rc/util/php/(?:eval(?:-stdin)?|kill))|' .
            't62|tenda\.sh.*tenda\.sh|themes/(?:finley/min|pridmag/db|universal-news/www)|tinymce/(?:langs/about|plugins/compat3x/css/index)|tk(?:_dencode_\d+)?|(?:tmp|wp-content)/vuln|topxoh/(?:drsx|wdr)|' .
            'u(?:nisibfu|pfile(?:_\\(\d\\))?|ploader_by_cloud7_agath|tchiha(?:_uploader)?)|' .
            'vzlateam|' .
            'w(?:[0o]rm\d+|0rdpr3ssnew|alker-nva|ebshell-[a-z\d]+|idgets-nva|idwsisw|loymzuk)|' .
            'wp[-_](?:2019|22|(?:admin(?:/images)?|content|css(?:/colors)?|includes(?:/ixr|/customize|/pomo)?|js(?:/widgets)?|network)/(?:(?:random_compat/|requests/)?class(?:_api|-wp-page-[\da-z]{5,})|dropdown|fgertreyersd|install|js/privacy-tools\.min|repeater|simple|text/about|themes/hello-element/footer|uploads/error_log|wp-login)|conflg|content/plugins/(?:backup-backup/includes/hro|cache/dropdown|contact-form-7/.+styles-rtl|contus-hd-flv-player/uploadvideo|dzs-zoomsounds/savepng|fix/up|wordpresscore/include|wp-file-manager/lib/php/connector\.minimal)|filemanager|setups|sigunq|sts|p)|' .
            'wp-configs|' .
            'ws[ou](?:yanz)?(?:[\d.]*|[\da-z]{4,})|wwdv|' .
            'x{3,}|xiaom|xichang/x|x+l(?:\d+|eet(?:mailer|-shell)?x?)|xm(?:lrpcs|lrpz|rlpc)|xw|' .
            'ya?nz|yyobang/mar|' .
            'zone_hackbar(?:_beutify_other)?|' .
            '版iisspy|大马|一句话(?:木马|扫描脚本程序)?' .
            ')\.php[57]?(?:$|[/?])~',
            $LCNrURI
        ), 'Probing for webshells/backdoors')) {
            $this->Reporter->report([15, 20, 21], ['Caught probing for webshells/backdoors. Host might be compromised.'], $this->BlockInfo['IPAddr']);
        } // 2023.08.18 mod 2024.07.16

        /** Probing for webshells/backdoors. */
        if ($this->trigger(preg_match(
            '~(?:^|[/?])(?:[1-9cefimnptuwx]{27}\.jsp$)~',
            $LCNrURI
        ), 'Probing for webshells/backdoors')) {
            $this->Reporter->report([15, 20], ['Caught probing for webshells/backdoors. Host might be compromised.'], $this->BlockInfo['IPAddr']);
        } // 2024.02.18

        /** Probing for exposed Git data. */
        if ($this->trigger(preg_match('~\.git(?:$|\W)~', $LCNrURI), 'Probing for exposed git data')) {
            $this->Reporter->report([15, 21], ['Caught probing for exposed git data.'], $this->BlockInfo['IPAddr']);
        } // 2022.06.05 mod 2023.09.04

        /** Probing for exposed VSCode data. */
        if ($this->trigger(preg_match('~(?:^|[/?])\.vscode(?:$|\W)~', $LCNrURI), 'Probing for exposed VSCode data')) {
            $this->Reporter->report([15, 21], ['Caught probing for exposed VSCode data.'], $this->BlockInfo['IPAddr']);
        } // 2024.02.08

        /** Probing for exposed SSH data. */
        if ($this->trigger(preg_match('~(?:^|[/?])\.ssh(?:$|\W)~', $LCNrURI), 'Probing for exposed SSH data')) {
            $this->Reporter->report([15, 22], ['Caught probing for exposed SSH data.'], $this->BlockInfo['IPAddr']);
        } // 2022.06.05 mod 2023.09.04

        /** Probing for exposed AWS credentials. */
        if ($this->trigger(preg_match('~(?:^|[/?])(?:\.aws/credentials?|aws\.yml)(?:$|\W)~', $LCNrURI), 'Probing for exposed AWS credentials')) {
            $this->Reporter->report([15, 21], ['Caught probing for exposed AWS credentials.'], $this->BlockInfo['IPAddr']);
        } // 2023.09.04 mod 2024.05.14

        /** Probing for vulnerable routers. */
        if ($this->trigger(preg_match('~(?:^|\W)HNAP1~i', $LCNrURI), 'Probing for vulnerable routers')) {
            $this->Reporter->report([15, 23], ['Caught probing for vulnerable routers.'], $this->BlockInfo['IPAddr']);
        } // 2022.06.05

        /** Probing for vulnerable webapps. */
        if ($this->trigger(preg_match('~cgi-bin/(?:get_status|(?:web)?login)\.cgi(?:$|\?)|manager/text/list~i', $LCNrURI), 'Probing for vulnerable webapps')) {
            $this->Reporter->report([15, 21], ['Caught probing for vulnerable webapps.'], $this->BlockInfo['IPAddr']);
        } // 2022.06.05 mod 2023.09.15

        /** CONNECT-based signatures. */
        if ($this->BlockInfo['Request_Method'] === 'CONNECT') {
            $Port = (isset($_SERVER['SERVER_PORT']) && is_scalar($_SERVER['SERVER_PORT'])) ? (int)$_SERVER['SERVER_PORT'] : 0;
            if ($this->trigger(strpos($LCNrURI, 'shadowserver.org') !== false, 'Probing for vulnerabilities and attempting unauthorised proxy tunnel; Botnet-like activity')) {
                $this->Reporter->report([9, 15, 19, 20], ['Caught probing for vulnerabilities and attempting unauthorised proxy tunnel; Botnet-like activity.'], $this->BlockInfo['IPAddr']);
                if ($this->trigger($Port !== 0 && $Port !== 80, 'Port scanning')) {
                    $this->Reporter->report([14], ['Caught port scanning.'], $this->BlockInfo['IPAddr']);
                } // 2023.09.15
            } // 2023.09.15
            if ($this->trigger(($Port === 443 || strpos($LCNrURI, ':443') !== false) && (
                (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] !== 'https') ||
                (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] !== 'on') ||
                (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'off')
            ), 'Attempted proxy tunnel to an SSH port via a non-SSH connection')) {
                $this->Reporter->report([22], ['Attempted proxy tunnel to an SSH port via a non-SSH connection detected.'], $this->BlockInfo['IPAddr']);
            } // 2023.09.15
            if ($this->trigger($Port === 20 || $Port === 21 || $Port === 22 || $Port === 69 || $Port === 115, 'Attempted proxy tunnel to an FTP port via a non-FTP connection')) {
                $this->Reporter->report([5], ['Attempted proxy tunnel to an FTP port via a non-FTP connection detected.'], $this->BlockInfo['IPAddr']);
            } // 2023.09.15
        }

        /** Probing for sendgrid env file. */
        if ($this->trigger(preg_match('~/sendgrid\.env(?:$|[/?])~i', $LCNrURI), 'Probing for sendgrid env file')) {
            $this->Reporter->report([15, 21], ['Caught probing for sendgrid env file.'], $this->BlockInfo['IPAddr']);
        } // 2024.05.02
    }

    /**
     * Query-based signatures start from here.
     * Please report all false positives to https://github.com/CIDRAM/CIDRAM/issues
     */
    if ($this->CIDRAM['ExtrasHonoured']['query'] && !empty($this->BlockInfo['Query'])) {
        $Query = str_replace('\\', '/', strtolower(urldecode($this->BlockInfo['Query'])));
        $QueryNoSpace = preg_replace('/\s/', '', $Query);

        $this->trigger(!$is_WP_plugin && preg_match(
            '/(?:_once|able|as(?:c|hes|sert)|c(?:hr|ode|ontents)|e(?:cho|regi|sc' .
            'ape|val)|ex(?:ec|ists)?|f(?:ile|late|unction)|get(?:c|csv|ss?)?|if|' .
            '(?<!context=edit&)include(?!\[\d+\]=\d+&)|len(?:gth)?|nt|open|p(?:r' .
            'ess|lace|lode|uts)|print(?:f|_r)?|re(?:place|quire|store)|rot13|s(?' .
            ':tart|ystem)|w(?:hil|rit)e)[(\[{<$]/',
            $QueryNoSpace
        ), 'Query command injection'); // 2018.05.02 mod 2023.10.06

        $this->trigger(preg_match(
            '~\$(?:globals|_(?:cookie|env|files|get|post|request|se(?:rver|ssion)))|' .
            '_contents|dotnet_load|execcgi|http_(?:cmd|sum)|move_uploaded_file|' .
            'pa(?:rse_ini_file|ssthru)|rewrite(?:cond|rule)|symlink|tmp_name|u(?:nserializ|ploadedfil)e~',
            $QueryNoSpace
        ), 'Query command injection'); // 2022.10.01

        $this->trigger(preg_match('/%(?:0[0-8bcef]|1)/i', $this->BlockInfo['Query']), 'Non-printable characters in query'); // 2016.12.31

        $this->trigger(!$is_WP_plugin && preg_match('/(?:amp(?:;|%3b)){3,}/', $QueryNoSpace), 'Nesting attack'); // 2016.12.31 mod 2023.07.14

        $this->trigger((
            !$is_WP_plugin &&
            strpos($this->BlockInfo['rURI'], '/ucp.php?mode=login') === false &&
            strpos($this->BlockInfo['rURI'], 'Category=') === false &&
            preg_match('/%(?:(25){2,}|(25)+27)/', $this->BlockInfo['Query'])
        ), 'Nesting attack'); // 2017.01.01 mod 2023.07.14

        $this->trigger(preg_match(
            '/(?:<(\?|body|i?frame|object|script)|(body|i?frame|object|script)>)/',
            $QueryNoSpace
        ), 'Query script injection'); // 2017.01.05

        $this->trigger(preg_match(
            '/_(?:cookie|env|files|get|post|request|se(rver|ssion))\[/',
            $QueryNoSpace
        ), 'Query global variable hack'); // 2017.01.13

        $this->trigger(strpos($QueryNoSpace, 'globals['), 'Query global variable hack'); // 2017.01.01

        $this->trigger(substr($this->BlockInfo['Query'], -3) === '%00', 'Null truncation attempt'); // 2016.12.31
        $this->trigger(substr($this->BlockInfo['Query'], -4) === '%000', 'Null truncation attempt'); // 2016.12.31
        $this->trigger(substr($this->BlockInfo['Query'], -5) === '%0000', 'Null truncation attempt'); // 2016.12.31

        $this->trigger(preg_match('/%(?:20\'|25[01u]|[46]1%[46]e%[46]4)/', $this->BlockInfo['Query']), 'Hack attempt'); // 2017.01.05
        $this->trigger(preg_match('/&arrs[12]\\[\\]=/', $QueryNoSpace), 'Hack attempt'); // 2017.02.25
        $this->trigger(preg_match('/p(?:ath|ull)\[?\]/', $QueryNoSpace), 'Hack attempt'); // 2017.01.06
        $this->trigger(preg_match('/user_login,\w{4},user_(?:pass|email|activation_key)/', $QueryNoSpace), 'WP hack attempt'); // 2017.02.18
        $this->trigger(preg_match('/\'%2[05]/', $this->BlockInfo['Query']), 'Hack attempt'); // 2017.01.05
        $this->trigger(preg_match('/\|(?:include|require)/', $QueryNoSpace), 'Hack attempt'); // 2017.01.01
        $this->trigger(strpos($QueryNoSpace, "'='") !== false, 'Hack attempt'); // 2017.01.05
        $this->trigger(strpos($QueryNoSpace, '.php/login.php') !== false, 'Hack attempt'); // 2017.01.05
        $this->trigger(preg_match('~\dhttps?:~', $QueryNoSpace), 'Hack attempt'); // 2017.01.01 mod 2018.09.22
        $this->trigger(strpos($QueryNoSpace, 'id=\'') !== false, 'Hack attempt'); // 2017.02.18
        $this->trigger(strpos($QueryNoSpace, 'name=lobex21.php') !== false, 'Hack attempt'); // 2017.02.18
        $this->trigger(strpos($QueryNoSpace, 'php://') !== false, 'Hack attempt'); // 2017.02.18
        $this->trigger(strpos($QueryNoSpace, 'tmunblock.cgi') !== false, 'Hack attempt'); // 2017.02.18
        $this->trigger(strpos($this->BlockInfo['Query'], '=-1%27') !== false, 'Hack attempt'); // 2017.01.05
        $this->trigger(substr($QueryNoSpace, 0, 1) === ';', 'Hack attempt'); // 2017.01.05
        $this->trigger(strpos($this->BlockInfo['Query'], 'ZWNobyBh' . 'RHJpdjQ7' . 'ZXZhbCgk' . 'X1BPU1Rb' . 'J3Z6J10pOw==') !== false, 'Hack attempt'); // 2023.08.09

        $this->trigger(strpos($QueryNoSpace, 'allow_url_include=on') !== false, 'Plesk hack'); // 2017.01.05
        $this->trigger(strpos($QueryNoSpace, 'auto_prepend_file=php://input') !== false, 'Plesk hack'); // 2017.01.05
        $this->trigger(strpos($QueryNoSpace, 'cgi.force_redirect=0') !== false, 'Plesk hack'); // 2017.01.05
        $this->trigger(strpos($QueryNoSpace, 'cgi.redirect_status_env=0') !== false, 'Plesk hack'); // 2017.01.05
        $this->trigger(strpos($QueryNoSpace, 'disable_functions=""') !== false, 'Plesk hack'); // 2017.01.05
        $this->trigger(strpos($QueryNoSpace, 'open_basedir=none') !== false, 'Plesk hack'); // 2017.01.05
        $this->trigger(strpos($QueryNoSpace, 'safe_mode=off') !== false, 'Plesk hack'); // 2017.01.05
        $this->trigger(strpos($QueryNoSpace, 'suhosin.simulation=on') !== false, 'Plesk hack'); // 2017.01.05

        $this->trigger(preg_match('~(?:^-|/r[ks]=|dg[cd]=1|pag(?:e|ina)=-)~', $QueryNoSpace), 'Probe attempt'); // 2017.02.25
        $this->trigger(preg_match('~yt=phpinfo~', $QueryNoSpace), 'Probe attempt'); // 2017.03.05

        $this->trigger(preg_match(
            '/\[(?:[alrw]\]|classes|file|itemid|l(?:astrss_ap_enabled|oadfile|ocalserverfile)|pth|src)/',
            $QueryNoSpace
        ), 'Probe attempt'); // 2017.01.17 mod 2020.11.29

        $this->trigger(strpos($QueryNoSpace, '+result:') !== false, 'Spam attempt'); // 2017.01.08
        $this->trigger(strpos($QueryNoSpace, 'result:+\\') !== false, 'Spam attempt'); // 2017.01.08

        $this->trigger(preg_match('/(?:["\'];|[;=]\|)/', $QueryNoSpace), 'Query command injection'); // 2017.01.13
        $this->trigger(preg_match('/[\'"`]sysadmin[\'"`]/', $QueryNoSpace), 'Query command injection'); // 2017.02.25
        $this->trigger(preg_match('/[\'"`]\+[\'"`]/', $QueryNoSpace), 'Query command injection'); // 2017.01.03
        $this->trigger(preg_match('/[\'"`]\|[\'"`]/', $QueryNoSpace), 'Pipe hack'); // 2017.01.08 mod 2017.10.31 (bugged)
        $this->trigger(strpos($QueryNoSpace, 'num_replies=77777') !== false, 'Overflow attempt'); // 2017.02.25
        $this->trigger(strpos($this->BlockInfo['Query'], '++++') !== false, 'Overflow attempt'); // 2017.01.05
        $this->trigger(strpos($this->BlockInfo['Query'], '->') !== false, 'Hack attempt'); // 2017.02.25

        $this->trigger(preg_match('~src=https?:~', $QueryNoSpace), 'RFI'); // 2017.02.18 mod 2018.09.22
        $this->trigger(strpos($QueryNoSpace, 'path]=') !== false, 'Path hack'); // 2017.02.18

        $this->trigger(strpos($QueryNoSpace, 'e9xmkgg5h6') !== false, 'Query error'); // 2017.02.18
        $this->trigger(strpos($QueryNoSpace, '5889d40edd5da7597dfc6d1357d98696') !== false, 'Query error'); // 2017.02.18

        $this->trigger(preg_match('/(?:keywords|query|searchword|terms)=%d8%b3%d9%83%d8%b3/', $QueryNoSpace), 'Unauthorised'); // 2017.02.18

        $this->trigger(strpos($this->BlockInfo['Query'], '??') !== false, 'Bad query'); // 2017.02.25
        $this->trigger(strpos($this->BlockInfo['Query'], ',0x') !== false, 'Bad query'); // 2017.02.25
        $this->trigger(strpos($this->BlockInfo['Query'], ',\'\',') !== false, 'Bad query'); // 2017.02.25

        $this->trigger(preg_match('/(?<![a-z])id=.*(?:benchmark\\(|id[xy]=|sleep\\()/', $QueryNoSpace), 'Query SQLi'); // 2017.03.01 mod 2023.11.10
        $this->trigger(preg_match(
            '~(?:from|union|where).*select|then.*else|(?:o[nr]|where).*isnull|(?:inner|left|outer|right)join~',
            $QueryNoSpace
        ), 'Query SQLi'); // 2017.03.01 mod 2023.08.30

        $this->trigger(preg_match('/cpis_.*i0seclab@intermal\.com/', $QueryNoSpace), 'Hack attempt'); // 2018.02.20
        $this->trigger(preg_match('/^(?:3x=3x|of=1&a=1)/i', $this->BlockInfo['Query']), 'Hack attempt'); // 2023.07.13 mod 2023.09.02

        $this->trigger(strpos($QueryNoSpace, 'u=ahr0cdovl3nolnrozwjlc3rjb2rllnrvcc90zxh0l3rlehrfmy50ehrz'), 'Compromised credential in brute-force attacks'); // 2023.10.10

        $this->trigger(preg_match(
            '~pw=(?:o(?:dvlmgnkc|tjmmdu1)|n(?:zrlnjnl|tk2m2i5)|mzllmwnh|yti4ngu2)~',
            $QueryNoSpace
        ), 'Compromised password used in brute-force attacks'); // 2023.10.10

        $this->trigger(preg_match('~/etc/passwd:null:null$~', $QueryNoSpace), 'Hack attempt'); // 2024.02.18

        /** These signatures can set extended tracking options. */
        if (
            $this->trigger(strpos($QueryNoSpace, '$_' . '[$' . '__') !== false, 'Shell upload attempt') || // 2017.03.01
            $this->trigger(strpos($QueryNoSpace, '@$' . '_[' . ']=' . '@!' . '+_') !== false, 'Shell upload attempt') || // 2017.03.01
            $this->trigger(strpos($Query, 'rm ' . '-rf') !== false, 'Hack attempt') || // 2017.01.02
            $this->trigger(strpos($QueryNoSpace, ';c' . 'hmod7' . '77') !== false, 'Hack attempt') || // 2017.01.05
            $this->trigger(substr($QueryNoSpace, 0, 2) === '()', 'Bash/Shellshock') || // 2017.01.05
            $this->trigger(strpos($QueryNoSpace, '0x31303235343830303536') !== false, 'Probe attempt') || // 2017.02.25
            $this->trigger(preg_match('~(?:modez|osc|tasya)=|=(?:(?:bot|scanner|shell)z|psybnc)~', $QueryNoSpace), 'Query command injection') // 2017.02.25 mod 2021.06.28
        ) {
            $this->CIDRAM['Tracking options override'] = 'extended';
        }
    }

    /** If enabled, fetch the first 1MB of raw input from the input stream. */
    if ($this->CIDRAM['ExtrasHonoured']['raw']) {
        $Handle = fopen('php://input', 'rb');
        $RawInput = fread($Handle, 1048576);
        fclose($Handle);
    } else {
        $RawInput = '';
    }

    /**
     * Signatures based on raw input start from here.
     * Please report all false positives to https://github.com/CIDRAM/CIDRAM/issues
     */
    if ($this->CIDRAM['ExtrasHonoured']['raw'] && $RawInput) {
        $RawInputSafe = strtolower(preg_replace('/[\s\x00-\x1f\x7f-\xff]/', '', $RawInput));

        $this->trigger(preg_match('/charcode\\(88,83,83\\)/', $RawInputSafe), 'Hack attempt'); // 2017.03.01
        $this->trigger((
            strpos($RawInputSafe, '<?xml') !== false &&
            strpos($RawInputSafe, '<!doctype') !== false &&
            strpos($RawInputSafe, '<!entity') !== false
        ), 'Suspicious request'); // 2018.07.10
        $this->trigger(strpos($RawInputSafe, 'inputbody:action=update&mfbfw') !== false, 'FancyBox exploit attempt'); // 2017.03.01

        $this->trigger(!$is_WP_plugin && preg_match(
            '~(?:lwp-download|fetch)ftp://|(?:fetch|lwp-download|wget)https?://|<name|method(?:call|name)|value>~i',
            $RawInputSafe
        ), 'POST RFI'); // 2018.07.10

        /** Joomla plugins update bypass (POST RFI conflict). */
        $this->bypass(
            ($this->BlockInfo['SignatureCount'] - $Before) > 0 &&
            strpos($this->BlockInfo['rURI'], 'administrator/') !== false &&
            strpos($this->BlockInfo['WhyReason'], 'POST RFI') !== false,
            'Joomla plugins update bypass (POST RFI conflict)'
        ); // 2017.05.10

        $this->trigger(preg_match(
            '~(?:%61%(6c%6c%6f%77%5f%75%72%6c%5f%69%6e%63%6c%75%64%65%3d%6f%6e|7' .
            '5%74%6f%5f%70%72%65%70%65%6e%64%5f%66%69%6c%65%3d%70%68%70%3a%2f%2f' .
            '%69%6e%70%75%74)|%63%67%69%2e%(66%6f%72%63%65%5f%72%65%64%69%72%65%' .
            '63%74%3d%30|72%65%64%69%72%65%63%74%5f%73%74%61%74%75%73%5f%65%6e%7' .
            '6%3d%30)|%64%69%73%61%62%6c%65%5f%66%75%6e%63%74%69%6f%6e%73%3d%22%' .
            '22|%6f%70%65%6e%5f%62%61%73%65%64%69%72%3d%6e%6f%6e%65|%73%(61%66%6' .
            '5%5f%6d%6f%64%65%3d%6f%66%66|75%68%6f%73%69%6e%2e%73%69%6d%75%6c%61' .
            '%74%69%6f%6e%3d%6f%6e))~',
            $RawInputSafe
        ), 'Plesk hack'); // 2017.03.01

        $this->trigger(preg_match('~//dail' . 'ydigita' . 'ldeals' . '\.info/~i', $RawInput), 'Spam attempt'); // 2017.03.01
        $this->trigger(preg_match('~streaming\.live365\.com/~i', $RawInput), 'Spam attempt'); // 2020.03.02 mod 2023.10.10

        /** These signatures can set extended tracking options. */
        if (
            $this->trigger(preg_match('~^/\?-~', $RawInput), 'Hack attempt') || // 2017.03.01
            $this->trigger(strpos($RawInputSafe, '$_' . '[$' . '__') !== false, 'Shell upload attempt') || // 2017.03.01
            $this->trigger(strpos($RawInputSafe, '@$' . '_[' . ']=' . '@!' . '+_') !== false, 'Shell upload attempt') || // 2017.03.01
            $this->trigger(preg_match('~&author_name=(?:%5b|\[)~', $RawInputSafe), 'Bot detection') // 2017.03.01
        ) {
            $this->CIDRAM['Tracking options override'] = 'extended';
        }
    }

    /** Reporting. */
    if (!empty($this->BlockInfo['IPAddr'])) {
        if (strpos($this->BlockInfo['WhyReason'], 'Compromised credential') !== false) {
            $this->Reporter->report([15, 18], ['Unauthorised use of known compromised credential detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Compromised password') !== false) {
            $this->Reporter->report([15, 18], ['Unauthorised use of known compromised password detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'FancyBox exploit attempt') !== false) {
            $this->Reporter->report([15, 21], ['FancyBox hack attempt detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'WP hack attempt') !== false) {
            $this->Reporter->report([15, 21], ['WordPress hack attempt detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Hack attempt') !== false) {
            $this->Reporter->report([15], ['Hack attempt detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Nesting attack') !== false) {
            $this->Reporter->report([15], ['Nesting attack detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Null truncation attempt') !== false) {
            $this->Reporter->report([15], ['Null truncation attempt detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Overflow attempt') !== false) {
            $this->Reporter->report([15], ['Overflow attempt detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Path hack') !== false) {
            $this->Reporter->report([15], ['Path hack detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Pipe hack') !== false) {
            $this->Reporter->report([15], ['Pipe hack detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Plesk hack') !== false) {
            $this->Reporter->report([15, 21], ['Plesk hack attempt detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Probe attempt') !== false) {
            $this->Reporter->report([19], ['Probe detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Query SQLi') !== false) {
            $this->Reporter->report([16], ['SQL injection attempt detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Query command injection') !== false) {
            $this->Reporter->report([15], ['Query command injection attempt detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Query global variable hack') !== false) {
            $this->Reporter->report([15], ['Query global variable hack attempt detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Query script injection') !== false) {
            $this->Reporter->report([15], ['Query script injection attempt detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'POST RFI') !== false) {
            $this->Reporter->report([15], ['POST RFI detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Shell upload attempt') !== false) {
            $this->Reporter->report([15], ['Shell upload attempt detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Spam attempt') !== false) {
            $this->Reporter->report([10, 19], ['Spam attempt detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Traversal attack') !== false) {
            $this->Reporter->report([15, 21], ['Traversal attack detected.'], $this->BlockInfo['IPAddr']);
        }
    }
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
