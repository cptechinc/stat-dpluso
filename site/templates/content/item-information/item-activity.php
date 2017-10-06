<?php
	$activityfile = $config->jsonfilepath.session_id()."-iiactivity.json";
	//$activityfile = $config->jsonfilepath."iiact-iiactivity.json";
	
	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	if (file_exists($activityfile)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$activityjson = json_decode(file_get_contents($activityfile), true);
		$activityjson = $activityjson ? $activityjson : array('error' => true, 'errormsg' => 'The Item Activity JSON contains errors. JSON ERROR: '.json_last_error());
		
		if ($activityjson['error']) {
			$page->bootstrap->createalert('warning', $activityjson['errormsg']);
		} else {
			$columns = array_keys($activityjson['columns']); 
			foreach($activityjson['data'] as $warehouse) {
				echo '<h3>'.$warehouse['Whse Name'].'</h3>';
				$tb = new Table('class=table table-striped table-bordered table-condensed table-excel');
				$tb->tablesection('thead');
					$tb->tr();
					foreach($activityjson['columns'] as $column)  {
						$class = $config->textjustify[$column['headingjustify']];
						$tb->th("class=$class", $column['heading']);
					}
				$tb->closetablesection('thead');
				$tb->tablesection('tbody');
					foreach($warehouse['orders'] as $order) {
						$tb->tr();
						foreach($columns as $column) {
							$class = $config->textjustify[$activityjson['columns'][$column]['datajustify']];
							$tb->td("class=$class", $order[$column]);
						}
					}
				$tb->closetablesection('tbody');
				echo $tb->close();
			}
		}
	} else {
		$page->bootstrap->createalert('warning', 'Information Not Available');
	}

?>
