<?php
	$purchasehistfile = $config->jsonfilepath.session_id()."-vipurchhist.json";
	// $purchasehistfile = $config->jsonfilepath."viphv-vipurchhist.json";
	
	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	if (file_exists($purchasehistfile)) {
		// JSON FILE will be false if an error occured during file get or json decode
		$purchasehistjson = json_decode(convertfiletojson($purchasehistfile), true);
		$purchasehistjson = $purchasehistjson ? $purchasehistjson : array('error' => true, 'errormsg' => 'The VI Purchase History JSON contains errors. JSON ERROR: ' . json_last_error());
		if ($purchasehistjson['error']) {
			echo $page->bootstrap->createalert('warning', $purchasehistjson['errormsg']);
		} else {
			$table = include $config->paths->content. 'vend-information/screen-formatters/logic/purchase-history.php';
			include $config->paths->content. 'vend-information/tables/purchase-history-formatted.php';
			// include $config->paths->content. 'vend-information/scripts/purchase-history.js.php';
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information not available.');
	}
?>
