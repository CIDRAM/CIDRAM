<?php 

require_once __DIR__.'/../../vault/classes/LogFileReader.php';

class LogFileReaderTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $logFileReader;
    
    protected function _before()
    {
        $this->logFileReader = new LogFileReader();
    }

    protected function _after()
    {
        unset($this->logFileReader);
    }

    // tests
    public function testSomeFeature()
    {

    }
}