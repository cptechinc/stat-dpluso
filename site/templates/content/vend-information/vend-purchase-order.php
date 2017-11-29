<?php
	$purchaseorderfile = $config->jsonfilepath.session_id()."-vipurchordr.json";
	// $purchaseorderfile = $config->jsonfilepath."vipov-vipurchordr.json";
	
	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	if (file_exists($purchaseorderfile)) {
		// JSON FILE will be false if an error occured during file get or json decode
		$purchaseorderjson = json_decode(convertfiletojson($purchaseorderfile), true);
		$purchaseorderjson ? $purchaseorderjson : array('error' => true, 'errormsg' => 'The VI Purchase Orders JSON contains errors. JSON ERROR: ' . json_last_error());
		if ($purchaseorderjson['error']) {
			echo $page->bootstrap->createalert('warning', $purchaseorderjson['errormsg']);
		} else {
			$table = include $config->paths->content. 'vend-information/screen-formatters/logic/purchase-order.php';
			include $config->paths->content. 'vend-information/tables/purchase-order-formatted.php';
			include $config->paths->content. 'vend-information/scripts/purchase-orders.js.php';
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information not available.');
	}
?>
