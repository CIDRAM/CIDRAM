<?php 

require_once __DIR__.'/../../vault/classes/LogFileReader.php';

class LogFileReaderTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $logFileReader;

    public $CIDRAM = array();
    
    protected function _before()
    {
        // lets mock the dependancies here
        $this->CIDRAM['Vault'] = "";
        $this->CIDRAM['Config']['logfile'] = 'sample_log.txt';
        $this->CIDRAM['Config']['logfileApache'] = 'sample_apache_log.txt';
        $this->CIDRAM['Config']['logfileSerialized'] = 'sample_serialized_log.txt';
        
        $this->logFileReader = new LogFileReader($this->CIDRAM);
        print_r($this->CIDRAM);
    }

    protected function _after()
    {
        unset($this->logFileReader);
    }

    // log is currently done in 3 places, normal log, apache log and serialized log, so checking correct category
    public function testGivenUnknownLogFileCategoryShouldReturnError()
    {   
        $this->assertEquals($this->logFileReader->readFile(10), LogFileReader::FILE_CATEGORY_ERROR);
    }

    public function testGivenNotExistingFileShouldReturnError() {

        $this->assertEquals($this->logFileReader->readFile(LogFileReader::NORMAL_LOG), LogFileReader::FILE_NOT_EXISTS);
    }
}