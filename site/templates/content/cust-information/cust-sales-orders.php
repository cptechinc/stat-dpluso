<?php
	$salesfile = $config->jsonfilepath.session_id()."-cisalesordr.json";
	//$salesfile = $config->jsonfilepath."ciso-cisalesordr.json";

	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	if (file_exists($salesfile))  {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$salesjson = json_decode(convertfiletojson($salesfile), true); 
		$salesjson = $salesjson ? $salesjson : array('error' => true, 'errormsg' => 'The CI Sales Order JSON contains errors. JSON ERROR: ' . json_last_error());
		 
		if ($salesjson['error']) {
			echo $page->bootstrap->createalert('warning', $salesjson['errormsg']);
		} else {
			$table = include $config->paths->content."cust-information/screen-formatters/logic/sales-orders.php";
			foreach ($salesjson['data'] as $whse) {
				echo '<div>';
					echo '<h3>'.$whse['Whse Name'].'</h3>';
					include $config->paths->content."/cust-information/tables/sales-orders-formatted.php";
				echo '</div>';
			}
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
?>
