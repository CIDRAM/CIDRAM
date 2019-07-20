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
 * This file: LogFileReader (last modified: 2019.07.20).
 */

class LogFileReader {

	const NORMAL_LOG = "NORMAL_LOG";

	const LOG_APACHE = "LOG_APACHE";

	const LOG_SERIALIZED = "LOG_SERIALIZED";

	const FILE_CATEGORY_ERROR = "FILE_CATEGORY_ERROR";

	const FILE_NOT_EXISTS = "FILE_NOT_EXISTS";

	private $CIDRAM;

	public function __construct(array &$CIDRAM) {
		$this->CIDRAM = $CIDRAM;
	}

	private function getLogFileNameByCategory($category) {
		
		switch ($category) {		
			case LogFileReader::NORMAL_LOG:
				return $this->CIDRAM['Config']['logfile'];
				break;
			case LogFileReader::LOG_APACHE:
				return $this->CIDRAM['Config']['logfileApache'];
				break;
			case LogFileReader::LOG_SERIALIZED:
				return $this->CIDRAM['Config']['logfileSerialized'];
				break;
			default:
				break;
		}
	}

	private function getLogFileDirectory() {
		return $this->CIDRAM['Vault'];
	}

	private function getLogFileDirectoryLocation($category) {
		$filename = $this->getLogFileNameByCategory($category);
		$log_file_directory = $this->getLogFileDirectory();
		return $log_file_directory.$filename;
		
	}


	public function readFile($category) {

		if ($category != LogFileReader::NORMAL_LOG && $category != LogFileReader::LOG_APACHE && $category != LogFileReader::LOG_SERIALIZED) {
			return LogFileReader::FILE_CATEGORY_ERROR;
		}

		$file_location = $this->getLogFileDirectoryLocation($category);
		if (file_exists($file_location)) {

		}
		else {
			return LogFileReader::FILE_NOT_EXISTS;
		}
	}
}

?>