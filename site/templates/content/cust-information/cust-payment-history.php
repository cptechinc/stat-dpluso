<?php
	$historyfile = $config->jsonfilepath.session_id()."-cipayment.json";
	//$historyfile = $config->jsonfilepath."cioi-cipayment.json";

	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	if (file_exists($historyfile)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$historyjson = json_decode(file_get_contents($historyfile), true);
		$historyjson = $historyjson ? $historyjson : array('error' => true, 'errormsg' => 'The Payment History JSON contains errors. JSON ERROR: '.json_last_error());
		
		if ($historyjson['error']) {
			echo $page->bootstrap->createalert('warning', $historyjson['errormsg']);
		} else {
			$table = include $config->paths->content."cust-information/screen-formatters/logic/payment-history.php";
			include $config->paths->content."cust-information/tables/payment-history-formatted.php";
			include $config->paths->content."cust-information/scripts/payment-history.js.php";
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
?>
