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
        $this->CIDRAM['Config']['logfile'] = __DIR__.'/sample_log.txt';
        $this->CIDRAM['Config']['logfileApache'] = 'sample_apache_log.txt';
        $this->CIDRAM['Config']['logfileSerialized'] = 'sample_serialized_log.txt';
        $this->logFileReader = new LogFileReader($this->CIDRAM);
    }

    protected function _after()
    {
        if (file_exists($this->CIDRAM['Config']['logfile'])) {
            unlink($this->CIDRAM['Config']['logfile']);
        }
        unset($this->logFileReader);
    }

    // log is currently done in 3 places, normal log, apache log and serialized log, so checking correct category
    public function testGivenUnknownLogFileCategoryShouldReturnError()
    {   
        $this->assertEquals($this->logFileReader->readFile(0, "Foo category")['error'], LogFileReader::FILE_CATEGORY_ERROR);
    }

    public function testGivenNotExistingFileShouldReturnError() {

        $this->assertEquals($this->logFileReader->readFile(0, LogFileReader::NORMAL_LOG)['error'], LogFileReader::FILE_NOT_EXISTS);
    }

    public function testGivenZeroBufferPositionShouldReturnFileSize() {
        file_put_contents($this->CIDRAM['Config']['logfile'], "");
        $this->assertEquals($this->logFileReader->readFile(0, LogFileReader::NORMAL_LOG)['file_size'],
        0);
        unlink($this->CIDRAM['Config']['logfile']);
    }

    public function testGivenPreviousFileSizeGetChangedData() {

        file_put_contents($this->CIDRAM['Config']['logfile'], "some text");

        $previous_buffer_pos = $this->logFileReader->readFile(0, LogFileReader::NORMAL_LOG)['file_size'];

        $changed_log_data = "changed log data";

        // change the log file ( simulates how the server file get changed )
        file_put_contents($this->CIDRAM['Config']['logfile'], $changed_log_data, FILE_APPEND);

        // if we give the previous memory size then it should return the string we have added by $changed_log_data
        $data = $this->logFileReader->readFile($previous_buffer_pos, LogFileReader::NORMAL_LOG);

        $this->assertEquals($changed_log_data, $data['file_data']);
        
    }

    public function testGivenPreviousFileSizeGetChangedDataForMultiLineChange() {

        file_put_contents($this->CIDRAM['Config']['logfile'], "some text");

        $previous_buffer_pos = $this->logFileReader->readFile(0, LogFileReader::NORMAL_LOG)['file_size'];


        $changed_log_data = "changed log data".PHP_EOL."boom boom";

        // change the log file ( simulates how the server file get changed )
        file_put_contents($this->CIDRAM['Config']['logfile'], $changed_log_data, FILE_APPEND);

        // if we give the previous memory size then it should return the string we have added by $changed_log_data
        $data = $this->logFileReader->readFile($previous_buffer_pos, LogFileReader::NORMAL_LOG);

        
        $this->assertEquals($changed_log_data, $data['file_data']);
        
    }

    public function testGivenPreviousFileSizeMustReturnSameDataIfFileIsNotChanged() {

        file_put_contents($this->CIDRAM['Config']['logfile'], "some text");

        $previous_buffer_pos = $this->logFileReader->readFile(0, LogFileReader::NORMAL_LOG)['file_size'];

        $data = $this->logFileReader->readFile($previous_buffer_pos, LogFileReader::NORMAL_LOG);

        $this->assertEquals($previous_buffer_pos, $data['file_size']);

    }

}