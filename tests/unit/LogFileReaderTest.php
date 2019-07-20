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

    // log is currently done in 3 places, normal log, apache log and serialized log, so checking correct category
    public function testGivenUnknownLogFileCategoryShouldReturnError()
    {   
        $this->assertEquals($this->logFileReader->readFile(10), LogFileReader::FILE_CATEGORY_ERROR);
    }

    public function testGivenNotExistingFileShouldReturnError() {
        
        // $this->assertEquals($this->logFileReader(LogFileReader::NORMAL_LOG), LogFileReader::FILE_NOT_EXISTS);
    }
}