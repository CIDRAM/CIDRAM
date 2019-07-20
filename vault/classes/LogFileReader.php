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

	public function readFile($category) {

		if ($category != LogFileReader::NORMAL_LOG && $category != LogFileReader::LOG_APACHE && $category != LogFileReader::LOG_SERIALIZED) {

			return LogFileReader::FILE_CATEGORY_ERROR;
		}

	}
}

?>