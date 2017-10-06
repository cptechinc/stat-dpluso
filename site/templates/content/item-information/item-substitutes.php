<?php
	$substitutefile = $config->jsonfilepath.session_id()."-iisub.json";
	//$substitutefile = $config->jsonfilepath."iisublindst-iisub.json";
	//$substitutefile = $config->jsonfilepath."iisub-iisub.json";
	
	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	if (file_exists($substitutefile)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$substitutejson = json_decode(file_get_contents($substitutefile), true);
		$substitutejson = $substitutejson ? $substitutejson : array('error' => true, 'errormsg' => 'The Item Substitutes JSON contains errors. JSON ERROR: '.json_last_error());
		
		if ($substitutejson['error']) {
			echo $page->bootstrap->createalert('warning', $substitutejson['errormsg']);
		} else {
			include $config->paths->content.'item-information/tables/item-substitute-tables.php';
			
			echo '<div class="row">';
				echo '<div class="col-sm-6">';
					echo $itemtable;
				echo '</div>';
				echo '<div class="col-sm-6">';
					echo $saletable;
				echo '</div>';
			echo '</div>';
			
			echo $subtitutetable;
		}	
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
?>
