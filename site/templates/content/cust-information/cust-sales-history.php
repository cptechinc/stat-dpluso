<?php
	$historyfile = $config->jsonfilepath.session_id()."-cisaleshist.json";
	//$historyfile = $config->jsonfilepath."cishist-cisaleshist.json";
	
	$shipID = $input->get->text('shipID');
	$pageajax = $config->ajax ? 'Y' :  'N';
	$shownotes = ($input->get->text('shownotes') == 'Y') ? true : false;
	$startdate = $input->get->text('startdate');
	$shownoteslink = $config->pages->ajax.'load/ci/ci-sales-history/?custID='.urlencode($custID).'&shipID'.urlencode($shipID).'&startdate='.urlencode($startdate);

	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	if (file_exists($historyfile)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$historyjson = json_decode(convertfiletojson($historyfile), true);
		$historyjson = $historyjson ? $historyjson : array('error' => true, 'errormsg' => 'The CI Sales History JSON contains errors. JSON ERROR: ' . json_last_error());
		
		if ($historyjson['error']) {
			echo $page->bootstrap->createalert('warning', $historyjson['errormsg']);
		} else {
			$table = include $config->paths->content."cust-information/screen-formatters/logic/sales-history.php"; 
			include $config->paths->content."cust-information/forms/sales-history-show-notes.php";
			foreach ($historyjson['data'] as $whse) {
				echo '<div>';
					echo '<h3>'.$whse['Whse Name'].'</h3>';
					include $config->paths->content."cust-information/tables/sales-history-formatted.php";
				echo '</div>';
			}
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
?>
