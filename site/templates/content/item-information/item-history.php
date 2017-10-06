<?php
	$historyfile = $config->jsonfilepath.session_id()."-iisaleshist.json";
	//$historyfile = $config->jsonfilepath."iish-iisaleshist.json";

	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	

	if (file_exists($historyfile)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$historyjson = json_decode(file_get_contents($historyfile), true); 
		$historyjson = $historyjson ? $historyjson : array('error' => true, 'errormsg' => 'The Item Sales History JSON contains errors. JSON ERROR: '.json_last_error());
		
		if ($historyjson['error']) {
			echo $page->bootstrap->createalert('warning', $docjson['errormsg']);
		} else {
			$table = include $config->paths->content."item-information/screen-formatters/logic/item-history.php";
			foreach ($historyjson['data'] as $whse) {
				echo '<div>';
					echo '<h3>'.$whse['Whse Name'].'</h3>';
					include $config->paths->content."item-information/tables/sales-history-formatted.php";
				echo '</div>';
			}
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
?>
