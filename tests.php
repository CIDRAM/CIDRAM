<?php
/**
 * This file is a part of the CIDRAM package.
 * Homepage: https://cidram.github.io/
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 */

/**
 * If this file remains intact after deploying the package to production,
 * preventing it from running outside of Composer may be useful as a means of
 * prevent potential attackers from hammering the file and needlessly wasting
 * cycles at the server.
 */
if (!isset($_SERVER['COMPOSER_BINARY'])) {
    die;
}

// Suppress unexpected errors from output and exit early as a failure when encountered.
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    echo 'Error triggered: ' . $errstr . PHP_EOL;
    exit(1);
});

define('DEV_DEBUG_MODE', true);
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vault' . DIRECTORY_SEPARATOR . 'loader.php';
$CIDRAM = new \CIDRAM\CIDRAM\Core();

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
if (serialize($Expected) !== serialize($CIDRAM->expandIpv4('127.0.0.1'))) {
    echo 'ExpandIPv4 failed.' . PHP_EOL;
    exit(3);
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
if (serialize($Expected) !== serialize($CIDRAM->expandIpv6('::1'))) {
    echo 'ExpandIPv6 failed.' . PHP_EOL;
    exit(4);
}

$TestInput = '127.0.0.1 Some arbitrary single IPs from here
127.0.0.2
127.0.0.3
1::
1::1
1:2:3:4::
1:2:3:4::1
1:2:3:4::2
1:2:3:4::3
2002::1
ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff
127.0.0.4
127.0.0.5
257.0.0.999 Some arbitrary INVALID single IPs from here
555.666.777.888
2002:abcd:efgh::1
10.0.0.0/9 Some arbitrary CIDRs from here
10.128.0.0/9
10.192.0.0/10
11.128.0.0/10
11.192.0.0/10
12.0.0.0/9
12.128.0.0/9
13.0.0.0/9
13.128.0.0/9
192.168.0.0/8 Some arbitrary INVALID CIDRs from here
192.168.0.0/9
192.168.0.0/10
192.168.192.0/10
192.169.0.0/10
192.169.64.0/10
1.2.3.4/255.255.255.254 Some arbitrary netmasks from here
2.3.4.5/255.255.255.255
99.99.99.99/255.255.255.255
99.10.10.0/255.255.255.0
99.10.11.0/255.255.255.0
99.8.0.0/255.252.0.0
11.11.11.11/11.11.11.11 Some arbitrary INVALID netmasks from here
255.255.255.254/1.2.3.4
6.7.8.9/255.255.255.254
88.88.88.88/255.255.254.255
Foobar Some garbage data from here
ASDFQWER!@#$
>>HelloWorld<<
SDFSDFSDF
QWEQWEQWE';

$ExpectedOutput = '1.2.3.4/31
2.3.4.5/32
10.0.0.0/8
11.128.0.0/9
12.0.0.0/7
99.8.0.0/14
99.99.99.99/32
127.0.0.1/32
127.0.0.2/31
127.0.0.4/31
1::/127
1:2:3:4::/126
2002::1/128
ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/128';

$Aggregator = new \CIDRAM\CIDRAM\Aggregator();
$Aggregator->Results = true;
$Aggregated = $Aggregator->aggregate($TestInput);

if ($ExpectedOutput !== $Aggregated) {
    echo 'Actual aggregated output does not match expected aggregated output!' . PHP_EOL;
    exit(5);
}

$ExpectedOutput = '1.2.3.4/255.255.255.254
2.3.4.5/255.255.255.255
10.0.0.0/255.0.0.0
11.128.0.0/255.128.0.0
12.0.0.0/254.0.0.0
99.8.0.0/255.252.0.0
99.99.99.99/255.255.255.255
127.0.0.1/255.255.255.255
127.0.0.2/255.255.255.254
127.0.0.4/255.255.255.254
1::/ffff:ffff:ffff:ffff:ffff:ffff:ffff:fffe
1:2:3:4::/ffff:ffff:ffff:ffff:ffff:ffff:ffff:fffc
2002::1/ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff
ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff/ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff';

$Aggregator = new \CIDRAM\CIDRAM\Aggregator(1);
$Aggregator->Results = true;
$Aggregated = $Aggregator->aggregate($TestInput);

if ($ExpectedOutput !== $Aggregated) {
    echo 'Actual aggregated output does not match expected aggregated output!' . PHP_EOL;
    exit(6);
}

restore_error_handler();

echo 'All tests passed.' . PHP_EOL;
exit(0);
