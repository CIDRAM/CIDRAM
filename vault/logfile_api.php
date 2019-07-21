<?php 
	require 'classes/LogFileViewer.php';
	// mocking cidram array for now
	$CIDRAM = array();
	$CIDRAM['Vault'] = __DIR__."/";
	$CIDRAM['Config'] = parse_ini_file('config.ini', true);

	if (isset($_REQUEST['buffer_pos']) && isset($_REQUEST['category'])) {

			$category = $_REQUEST['category'];

			$buffer_pos = $_REQUEST['buffer_pos'];

			$logFileViewer  = new LogFileViewer($CIDRAM);

			$response  = $logFileViewer->getResponse($buffer_pos, $category);

			echo json_encode($response);
		

	}

?>