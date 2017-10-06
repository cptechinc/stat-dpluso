<?php
	//WAS item-sales-orders-formatted
	$salesfile = $config->jsonfilepath.session_id()."-iisalesordr.json";
	//$salesfile = $config->jsonfilepath."iiso-iisalesordr.json";
	
	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	if (file_exists($salesfile)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$ordersjson = json_decode(file_get_contents($salesfile), true);
		$ordersjson = $ordersjson ? $ordersjson : array('error' => true, 'errormsg' => 'The Sales Order JSON contains errors. JSON ERROR: '.json_last_error());
		
		if ($ordersjson['error'])  {
			echo $page->bootstrap->createalert('warning', $ordersjson['errormsg']);
		} else {
			$table = include $config->paths->content."item-information/screen-formatters/logic/sales-orders.php";
			foreach ($ordersjson['data'] as $whse)  {
				echo '<div>';
					echo '<h3>'.$whse['Whse Name'].'</h3>';
					include $config->paths->content."item-information/tables/sales-orders-formatted.php";
				echo '</div>';
			}
			include $config->paths->content.'item-information/scripts/sales-orders.js.php';
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
?>
