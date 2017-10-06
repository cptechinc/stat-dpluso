<?php
	$purchasefile = $config->jsonfilepath.session_id()."-iipurchordr.json";
	//$purchasefile = $config->jsonfilepath."iiso-iipurchordr.json";
	
	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	if (file_exists($purchasefile)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$purchasejson = json_decode(file_get_contents($purchasefile), true); 
		$purchasejson  = $purchasejson ? $purchasejson : array('error' => true, 'errormsg' => 'The Purchase Order JSON contains errors. JSON ERROR: '.json_last_error());
		
		if ($purchasejson['error']) {
			echo $page->bootstrap->createalert('warning', $purchasejson['errormsg']);
		} else {
			$table = include $config->paths->content."item-information/screen-formatters/logic/purchase-orders.php";
			foreach ($purchasejson['data'] as $whse)  {
				echo '<div>';
					echo '<h3>'.$whse['Whse Name'].'</h3>';
					include $config->paths->content."item-information/tables/purchase-order-formatted.php";
				echo '</div>';
				include $config->paths->content.'item-information/scripts/purchase-orders.js.php';
			}
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
?>
