<?php
	$invoicefile = $config->jsonfilepath.session_id()."-ciopeninv.json";
	//$invoicefile = $config->jsonfilepath."cioi-ciopeninv.json";

	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	if (file_exists($invoicefile)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$invoicejson = json_decode(file_get_contents($invoicefile), true);
		$invoicejson = $invoicejson ? $invoicejson : array('error' => true, 'errormsg' => 'The open invoice JSON contains errors. JSON ERROR: '.json_last_error());
		
		if ($invoicejson['error']) {
			$page->bootstrap->createalert('warning', $invoicejson['errormsg']);
		} else {
			$table = include $config->paths->content."cust-information/screen-formatters/logic/open-invoices.php"; 
			include $config->paths->content."cust-information/tables/open-invoices-formatted.php"; 
			include $config->paths->content."cust-information/scripts/open-invoices.js.php"; 
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
?>
