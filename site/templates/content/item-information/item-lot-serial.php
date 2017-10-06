<?php 
	$lotserialfile = $config->jsonfilepath.session_id()."-iilotser.json"; 
	//$lotserialfile = $config->jsonfilepath."iilot-iilotser.json";
	
	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	if (file_exists($lotserialfile)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$lotserialjson = json_decode(file_get_contents($lotserialfile), true);
		$lotserialjson = $lotserialjson ? $lotserialjson : array('error' => true, 'errormsg' => 'The Lot Serial JSON contains errors. JSON ERROR: '.json_last_error());
	
		if ($lotserialjson['error']) {
			echo $page->bootstrap->createalert('warning', $kitjson['errormsg']);
		} else {
			$columns = array_keys($lotserialjson['columns']);
			$count = 0; 
			$array = array(); 
			foreach ($lotserialjson['columns'] as $column) {
				if ($column['sortavailable'] == 'n') { $array[] = $count; }
				$count++;
			}
			
			$tb = new Table("class=table table-striped table-bordered table-condensed table-excel|id=table");
			$tb->tablesection('thead');
				$tb->tr();
				foreach($lotserialjson['columns'] as $column) {
					$class = $config->textjustify[$column['headingjustify']];
					$tb->th("class=$class", $column['heading']);
				}
			$tb->closetablesection('thead');
			$tb->tablesection('tbody');
				foreach ($lotserialjson['data']['lots'] as $lot) {
					$tb->tr();
					foreach($columns as $column) {
						$class = $config->textjustify[$lotserialjson['columns'][$column]['datajustify']];
						$tb->td("class=$class", $lot[$column]);
					}
				}
			$tb->closetablesection('tbody');
			echo $tb->close();
			include $config->paths->content.'item-information/scripts/lot-serial.js.php';
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
?>
