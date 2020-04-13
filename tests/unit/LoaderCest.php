<?php

class LoaderCest
{
    public function _before(UnitTester $I)
    {
        $GLOBALS['CIDRAM_Config'] = parse_ini_file('tests/_support/config/config.ini', true);
        if (!isset($_SERVER)) {
            $_SERVER = [];
        }
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        require 'loader.php';
    }

    /**
     * Tests whether any errors occur when requiring loader.php.
     */
    public function testNoErrors(UnitTester $I)
    {
        $I->assertNull(error_get_last(), 'Errors were reported.');
    }
}
