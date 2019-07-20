<?php
/**
 * This file is a part of the CIDRAM package.
 * Homepage: https://cidram.github.io/
 *
 * CIDRAM COPYRIGHT 2019 and beyond by Naveen Muthusamy.
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: LogFileViewer (last modified: 2019.07.20).
 */

require_once 'LogFileReader.php';

// this viewer serializes the file data in to meaningful associative arrays
class LogFileViewer {

	private $logFileReader;
	
	public function __construct(array &$CIDRAM) {
		$this->logFileReader = new LogFileReader($CIDRAM);
	}

	// get the response from log file reader, if it is not empty then you need  to serialize them before posting to client
	public function getResponse($buffer_position, $category) {
		$data = $this->logFileReader->readFile($buffer_position, $category);
		$data['file_data'] = preg_split("#\n\s*\n#Uis", $data['file_data']);
		return $data;
	}

}


?>