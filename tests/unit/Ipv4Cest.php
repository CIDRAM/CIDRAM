<?php

class Ipv4Cest
{
    public function _before(UnitTester $I)
    {
        require 'vault/functions.php';
        $CIDRAM['Config'] = parse_ini_file('tests/_support/config/config.ini', true);
        $GLOBALS['CIDRAM'] = $CIDRAM;
    }

    // tests
    public function tryToTestIpv4Expansion(UnitTester $I)
    {
        global $CIDRAM;
        $expected = [
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
        $I->assertEquals($expected, $CIDRAM['ExpandIPv4']('127.0.0.1'));
    }
}
