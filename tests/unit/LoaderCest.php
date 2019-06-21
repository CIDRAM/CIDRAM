<?php 

class LoaderCest
{
    public function _before(UnitTester $I)
    {
        require 'loader.php';
    }

    // tests if there are no errors when requiring loader.php
    public function testNoErrors(UnitTester $I)
    {
        $I->assertNull(error_get_last(), 'Errors were reported.');
    }
}
