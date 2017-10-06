<?php
	$quotesfile = $config->jsonfilepath.session_id()."-ciquote.json";
	//$quotesfile = $config->jsonfilepath."ciqt-ciquote.json";

	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}

	if (file_exists($quotesfile)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$quotejson = json_decode(file_get_contents($quotesfile), true);
		$quotejson = $quotejson ? $quotejson : array('error' => true, 'errormsg' => 'The Quote JSON contains errors JSON ERROR: '.json_last_error());
		
		if ($quotejson['error']) {
			echo $page->bootstrap->createalert('warning', $quotejson['errormsg']);
		} else {
			$table = include $config->paths->content."cust-information/screen-formatters/logic/quotes.php"; 
			foreach ($quotejson['data'] as $whse) {
				echo '<div>';
					echo '<h3>'.$whse['Whse Name'].'</h3>';
					include $config->paths->content."cust-information/tables/quotes-formatted.php";
				echo '</div>';
			}
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
?>
