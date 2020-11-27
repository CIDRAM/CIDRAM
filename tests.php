<?php
// Prevent running tests outside of Composer (if the package is deployed
// somewhere live with this file still intact, useful to prevent hammering and
// cycles being needlessly wasted).
if (!isset($_SERVER['COMPOSER_BINARY'])) {
    die;
}

// Suppress unexpected errors from output and exit early as a failure when encountered.
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    exit(1);
});

// "CIDRAM" constant needed as sanity check for some required files.
define('CIDRAM', true);

// Global variable for all our closures, plus vault path declaration.
$CIDRAM = ['Vault' => __DIR__ . DIRECTORY_SEPARATOR . 'vault' . DIRECTORY_SEPARATOR];

// Fetch CIDRAM configuration.
$CIDRAM_Config = parse_ini_file($CIDRAM['Vault'] . 'config.ini.RenameMe', true);
$CIDRAM_Config['general']['disable_cli'] = true;
$CIDRAM_Config['supplementary_cache_options']['enable_apcu'] = true;

// Load each required file or exit immediately if any of them don't exist.
foreach (['functions.php', 'config.php', 'lang.php', 'frontend_functions.php'] as $File) {
    if (!is_readable($CIDRAM['Vault'] . $File)) {
        exit(2);
    }
    require $CIDRAM['Vault'] . $File;
}

// Exit immediately if the loader of the configuration file doesn't exist.
if (!is_readable(__DIR__ . DIRECTORY_SEPARATOR . 'loader.php') || !is_readable($CIDRAM['Vault'] . 'config.ini.RenameMe')) {
    exit(3);
}

// Test expand IPv4 factors.
$Expected = [
    '0.0.0.0/1',
    '64.0.0.0/2',
    '96.0.0.0/3',
    '112.0.0.0/4',
    '120.0.0.0/5',
    '124.0.0.0/6',
    '126.0.0.0/7',
    '127.0.0.0/8',
    '127.0.0.0/9',
    '127.0.0.0/10',
    '127.0.0.0/11',
    '127.0.0.0/12',
    '127.0.0.0/13',
    '127.0.0.0/14',
    '127.0.0.0/15',
    '127.0.0.0/16',
    '127.0.0.0/17',
    '127.0.0.0/18',
    '127.0.0.0/19',
    '127.0.0.0/20',
    '127.0.0.0/21',
    '127.0.0.0/22',
    '127.0.0.0/23',
    '127.0.0.0/24',
    '127.0.0.0/25',
    '127.0.0.0/26',
    '127.0.0.0/27',
    '127.0.0.0/28',
    '127.0.0.0/29',
    '127.0.0.0/30',
    '127.0.0.0/31',
    '127.0.0.1/32'
];
if ($Expected !== $CIDRAM['ExpandIPv4']('127.0.0.1')) {
    exit(4);
}

// Test expand IPv6 factors.
$Expected = [
    '0::/1',
    '0::/2',
    '0::/3',
    '0::/4',
    '0::/5',
    '0::/6',
    '0::/7',
    '0::/8',
    '0::/9',
    '0::/10',
    '0::/11',
    '0::/12',
    '0::/13',
    '0::/14',
    '0::/15',
    '0::/16',
    '0::/17',
    '0::/18',
    '0::/19',
    '0::/20',
    '0::/21',
    '0::/22',
    '0::/23',
    '0::/24',
    '0::/25',
    '0::/26',
    '0::/27',
    '0::/28',
    '0::/29',
    '0::/30',
    '0::/31',
    '0::/32',
    '0::/33',
    '0::/34',
    '0::/35',
    '0::/36',
    '0::/37',
    '0::/38',
    '0::/39',
    '0::/40',
    '0::/41',
    '0::/42',
    '0::/43',
    '0::/44',
    '0::/45',
    '0::/46',
    '0::/47',
    '0::/48',
    '0::/49',
    '0::/50',
    '0::/51',
    '0::/52',
    '0::/53',
    '0::/54',
    '0::/55',
    '0::/56',
    '0::/57',
    '0::/58',
    '0::/59',
    '0::/60',
    '0::/61',
    '0::/62',
    '0::/63',
    '0::/64',
    '0::/65',
    '0::/66',
    '0::/67',
    '0::/68',
    '0::/69',
    '0::/70',
    '0::/71',
    '0::/72',
    '0::/73',
    '0::/74',
    '0::/75',
    '0::/76',
    '0::/77',
    '0::/78',
    '0::/79',
    '0::/80',
    '0::/81',
    '0::/82',
    '0::/83',
    '0::/84',
    '0::/85',
    '0::/86',
    '0::/87',
    '0::/88',
    '0::/89',
    '0::/90',
    '0::/91',
    '0::/92',
    '0::/93',
    '0::/94',
    '0::/95',
    '0::/96',
    '0::/97',
    '0::/98',
    '0::/99',
    '0::/100',
    '0::/101',
    '0::/102',
    '0::/103',
    '0::/104',
    '0::/105',
    '0::/106',
    '0::/107',
    '0::/108',
    '0::/109',
    '0::/110',
    '0::/111',
    '0::/112',
    '0::/113',
    '0::/114',
    '0::/115',
    '0::/116',
    '0::/117',
    '0::/118',
    '0::/119',
    '0::/120',
    '0::/121',
    '0::/122',
    '0::/123',
    '0::/124',
    '0::/125',
    '0::/126',
    '0::/127',
    '0::1/128'
];
if ($Expected !== $CIDRAM['ExpandIPv6']('::1')) {
    exit(5);
}

restore_error_handler();

// All tests passed.
exit(0);
