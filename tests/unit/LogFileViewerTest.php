<?php
require_once __DIR__.'/../../vault/classes/LogFileReader.php';
require_once __DIR__.'/../../vault/classes/LogFileViewer.php';

class LogFileViewerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
        $this->CIDRAM['Vault'] = "";
        $this->CIDRAM['Config']['general']['logfile'] = __DIR__.'/sample_log.txt';
        $this->CIDRAM['Config']['general']['logfileApache'] = 'sample_apache_log.txt';
        $this->CIDRAM['Config']['general']['logfileSerialized'] = 'sample_serialized_log.txt';

        $this->logFileViewer = new LogFileViewer($this->CIDRAM);

    }

    protected function _after()
    {
         if (file_exists($this->CIDRAM['Config']['general']['logfile'])) {
            unlink($this->CIDRAM['Config']['general']['logfile']);
        }
    }

    public function testWhenGivenProperParamsMustReturnValidFileResponse() {
        // lets create a log file
        file_put_contents($this->CIDRAM['Config']['general']['logfile'], "some text");
        $data['file_size'] = strlen("some text");
        $data['category'] = "";
        $data['file_data'] = "";
        $data['error'] = "";

        $response_data = $this->logFileViewer->getResponse(0,LogFileReader::NORMAL_LOG);
        
        $previous_buffer = $response_data['file_size'];
        
        // write the real log data
        $real_log_data = "ID: 1562861905-314008-5290923775".PHP_EOL.
                        "Script Version: CIDRAM v1.13.0".PHP_EOL.
                        "Date/Time: Fri, 12 Jul 2019 00:18:25 +0800".PHP_EOL.
                        "IP Address: 60.191.38.77".PHP_EOL.
                        "Hostname: ns.zjnbptt.net.cn".PHP_EOL.
                        "Signatures Count: 1".PHP_EOL.
                        "Signatures Reference: 60.160.0.0/11".PHP_EOL.
                        "Why Blocked: Generic ('China Backbone 4134', L3969:F5, [CN])!".PHP_EOL.
                        "User Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36".PHP_EOL.
                        "Reconstructed URI: http://localhost/[REDACTED]/indirect.php".PHP_EOL.
                        "reCAPTCHA State: Enabled.".PHP_EOL;
        
        $data['file_data'][0] = $real_log_data;

        file_put_contents($this->CIDRAM['Config']['general']['logfile'], $real_log_data, FILE_APPEND);

        $response_data = $this->logFileViewer->getResponse($previous_buffer,LogFileReader::NORMAL_LOG);


        $this->assertEquals($response_data['file_data'][0], $real_log_data);

    }
}