<?php
	$kititemfile = $config->jsonfilepath.session_id()."-iikit.json";
	//$kititemfile = $config->jsonfilepath."iikt-iikit.json";
	
	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	if (file_exists($kititemfile)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$kitjson = json_decode(file_get_contents($kititemfile), true);
		$kitjson = $kitjson ? $kitjson : array('error' => true, 'errormsg' => 'The Item Kit Components Consolidated JSON contains errors. JSON ERROR: '.json_last_error());
		
		if ($kitjson['error']) {
			echo $page->bootstrap->createalert('warning', $kitjson['errormsg']);
		} else {
			$componentcolumns = array_keys($kitjson['columns']['component']);
			$warehousecolumns = array_keys($kitjson['columns']['warehouse']);
			
			echo "<p><b>Kit Qty:</b>".$kitjson['qtyneeded']."</p>";
			
			foreach ($kitjson['data']['component'] as $component) {
				echo "<h3>".$component['component item']."</h3>";
				$tb = new Table('class=table table-striped table-bordered table-condensed table-excel no-bottom');
				$tb->tablesection('thead');
					$tb->tr();
					foreach($kitjson['columns']['component'] as $column) {
						$class = $config->textjustify[$column['headingjustify']];
						$tb->th("class=$class", $column['heading']);
					}
				$tb->closetablesection('thead');
				$tb->tablesection('tbody');
					foreach ($componentcolumns as $column) {
						$tb->tr();
						$class = $config->textjustify[$kitjson['columns']['component'][$column]['datajustify']];
						$tb->td("class=$class", $component[$column]);
					}
				$tb->closetablesection('tbody');
				echo $tb->close();
				
				// WAREHOUSE Table
				$tb = new Table('class=table table-striped table-bordered table-condensed table-excel');
				$tb->tablesection('thead');
					$tb->tr();
					foreach($kitjson['columns']['warehouse'] as $column) {
						$class = $config->textjustify[$column['headingjustify']];
						$tb->th("class=$class", $column['heading']);
					}
				$tb->closetablesection('thead');
				$tb->tablesection('tbody');
					foreach ($component['warehouse'] as $whse) {
						foreach ($warehousecolumns as $column) {
							$class = $config->textjustify[$kitjson['columns']['warehouse'][$column]['datajustify']];
							$tb->td("class=$class", $whse[$column]);
						}
					}
				$tb->closetablesection('tbody');
				echo $tb->close();
			} // foreach ($kitjson['data']['component'] as $component)
			$warehouses = '';
			foreach ($kitjson['data']['whse meeting req'] as $whse => $name) {
				$warehouses .= $name . ' ';
			}
			echo "<p><b>Warehouses that meet the Requirement: </b> $warehouses</p>";
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
?>
