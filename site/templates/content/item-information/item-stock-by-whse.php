<?php
	$whsestockfile = $config->jsonfilepath.session_id()."-iistkbywhse.json";
	//$whsestockfile = $config->jsonfilepath."debugstkbywhse-iistkbywhse.json";
	
	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	if (file_exists($whsestockfile)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$whsestock = json_decode(file_get_contents($whsestockfile), true);
		$whsestock = $whsestock ? $whsestock : array('error' => true, 'errormsg' => 'The Warehouse JSON contains errors. JSON ERROR: '.json_last_error());
		
		if ($whsestock['error']) {
			echo $page->bootstrap->createalert('warning', $whsestock['errormsg']);
		} else {
			$whsecolumns = array_keys($whsestock['columns']['warehouse']);
			$lotcolumns = array_keys($whsestock['columns']['lots']);
			$ordercolumns = array_keys($whsestock['columns']['orders']);
			
			foreach ($whsestock['data'] as $whse) {
				if ($whse != $whsestock['data']['zz']) {
					echo '<div>';
						echo '<h3>'.$whse['Whse Name'].'</h3>';
						$tb = new Table('class=table table-striped table-bordered table-condensed table-excel');
						$tb->tablesection('thead');
							$tb->tr();
							foreach($whsestock['columns']['warehouse'] as $column) {
								$class = $config->textjustify[$column['headingjustify']];
								$tb->th("class=$class", $column['heading']);
							}
						$tb->closetablesection('thead');
						$tb->tablesection('tbody');
							$tb->tr();
							foreach($whsecolumns as $column) {
								$class = $config->textjustify[$whsestock['columns']['warehouse'][$column]['datajustify']];
								$tb->td("class=$class", $whse[$column]);
							}
						$tb->closetablesection('tbody');
						echo $tb->close();
						
						$tb = new Table('class=table table-striped table-bordered table-condensed table-excel');
						$tb->tr();
						foreach ($whsestock['columns']['lots'] as $column) {
							$class = $config->textjustify[$column['headingjustify']];
							$tb->th("class=$class", $column['heading']);
						}
						
						$tb->tr();
						foreach ($whsestock['columns']['orders'] as $column) {
							$class = $config->textjustify[$column['headingjustify']];
							$tb->td("class=$class", $column['heading']);
						}
						
						foreach ($whse['lots'] as $lot) {
							$tb->tr();
							foreach($lotcolumns as $column) {
								$class = $config->textjustify[$whsestock['columns']['lots'][$column]['datajustify']];
								$tb->td("class=$class", $lot[$column]);
							}
							foreach($lot['orders'] as $order) {
								$tb->tr();
								foreach($ordercolumns as $column) {
									$class = $config->textjustify[$whsestock['columns']['orders'][$column]['datajustify']];
									$tb->td("class=$class", $order[$column]);
								}
							}
						}
						echo $tb->close();
					echo '</div>';
					
					echo '<h3>'.$whsestock['data']['zz']['Whse Name'].'</h3>';
					$tb = new Table('class=table table-striped table-bordered table-condensed table-excel');
					$tb->tablesection('thead');
						$tb->tr();
						foreach ($whsestock['columns']['warehouse'] as $column) {
							$class = $config->textjustify[$column['headingjustify']];
							$tb->th("class=$class", $column['heading']);
						}
					$tb->closetablesection('thead');
					$tb->tablesection('tbody');
						$tb->tr();
						foreach ($whsecolumns as $column) {
							$class = $config->textjustify[$whsestock['columns']['warehouse'][$column]['datajustify']];
							$tb->th("class=$class", $whsestock['data']['zz'][$column]);
						}
					$tb->closetablesection('tbody');
					echo $tb->close();
				}
			}
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
?>
