<?php
	// $itemfile = $config->jsonfilepath.session_id()."-iiitem.json";
	$itemfile = $config->jsonfilepath."iiitemid-iiitem.json";
	
	if (file_exists($itemfile)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$itemjson = json_decode(file_get_contents($itemfile), true);
		$itemjson = $itemjson ? $itemjson : array('error' => true, 'errormsg' => 'The Item Outline JSON contains errors. JSON ERROR: '.json_last_error());
		
		if ($itemjson['error']) {
			echo $page->bootstrap->createalert('warning', $itemjson['errormsg']);
		} else {
			// include $config->paths->content."item-information/item-display.php";
			$table = include $config->paths->content."item-information/screen-formatters/logic/item-outline.php"; 
			include $config->paths->content."item-information/tables/item-outline-formatted.php"; 
			include $config->paths->content."item-information/item-price-breaks.php";
			include $config->paths->content."item-information/item-stock.php";
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
	
?>
