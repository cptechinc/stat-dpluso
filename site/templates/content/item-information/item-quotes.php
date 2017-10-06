<?php
	//WAS item-quotes-formatted
	$quotesfile = $config->jsonfilepath.session_id()."-iiquote.json";
	//$quotesfile = $config->jsonfilepath."iiqt-iiquote.json";

	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	if (file_exists($quotesfile)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$quotejson = json_decode(file_get_contents($quotesfile), true);
		$quotejson = $quotejson ? $quotejson : array('error' => true, 'errormsg' => 'The Quote JSON contains errors. JSON ERROR: '.json_last_error());
		
		if ($quotejson['error']) {
			echo $page->bootstrap->createalert('warning', $quotejson['errormsg']);
		} else {
			$table = include $config->paths->content."item-information/screen-formatters/logic/quotes.php";
			foreach ($quotejson['data'] as $whse)  {
				echo '<div>';
					echo '<h3>'.$whse['Whse Name'].'</h3>';
					include $config->paths->content."/item-information/tables/quote-formatted.php";
				echo '</div>';
			}
			include $config->paths->content.'item-information/scripts/lot-serial.js.php';
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
?>
