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

	/**
	* takes in the last @file size, and category of the logs file, returns
	* the data, error message, category
	* data structure from this function is a array with following keys:
	*
	* 	$data['file_size']
	*  	$data['category']
	*  	$data['data']
	*  	$data['error']
	*/

	public function readFile($buffer_position, $category) {
		// before making any calls do a clear stat cache (otherwise filesize returns old value)
		clearstatcache();

		$data['file_size'] = "";
		$data['category'] = "";
		$data['file_data'] = "";
		$data['error'] = "";

		if ($category != LogFileReader::NORMAL_LOG && $category != LogFileReader::LOG_APACHE && $category != LogFileReader::LOG_SERIALIZED) {
			$data['error'] = LogFileReader::FILE_CATEGORY_ERROR;
			return $data;
		}

		$file_location = $this->getLogFileDirectoryLocation($category);

		if (file_exists($file_location)) {
			// if the client is making the first request it doesnt know any thing, it just gets the memory size which can be useful for next requests
			if ($buffer_position == 0) {
				$data['file_size'] = filesize($file_location);
				return $data;
			}
			else {

				// there are three possibilities when we are going to subtract the new file size with old one, so we get the offset to read the file backwards
				// 
				//  1.the offset is positive  - good case, we now know some data has been appended to the system, we can read the data return the file size and data which was appended
				//
				//  2. the offset is 0, so no file is appended, return the file size itself
				//  
				//  3. the offset is negative, this is little problematic, if we get negative offset then the only possibility would be user deleted some thing from the file and it caused the new file size to be less than old file size (for that just return the current file size)
				
				$new_file_size = filesize($file_location); // always set the new file size, it is needed for client.
				
				$data['file_size'] = $new_file_size;

				$old_file_size = $buffer_position;
				$offset = $new_file_size - $old_file_size;

				if ($offset > 0) {
					// offset is positive now we know some data is returned
					$file_handle = fopen($file_location, "rb");
					fseek($file_handle, -$offset, SEEK_END);	
					$data['file_data'] = fread($file_handle, $offset + 1);
					fclose($file_handle);
					return $data;
				}
				else {
					// offset is either 0 or the file is shortened by some one, so we should just return current file size, so user can read the changed file in subsequent requests
					$data['error'] = "OFFSET BROKEN ".$offset;
					return $data;
				}
				
			}
		}
		else {

			$data['error'] =  LogFileReader::FILE_NOT_EXISTS;
			return $data;
		}
	}
}

?>