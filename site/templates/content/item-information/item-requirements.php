<?php
	$requirementsfile = $config->jsonfilepath.session_id()."-iirequire.json";
	//$requirementsfile = $config->jsonfilepath."iireq-iirequire.json";
	$warehousesfile = $config->companyfiles."json/whsetbl.json";
	$whsejson = json_decode(file_get_contents($warehousesfile), true);
	$warehouses = array_keys($whsejson['data']);
	$screentypes = array("REQ" => "requirements", "AVL" => 'available');
	$refresh = 'true';
	
	if ($config->ajax) {
        $refresh = 'false'; 
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	if (file_exists($requirementsfile)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$requirementsjson = json_decode(file_get_contents($requirementsfile), true);
		$requirementsjson = $requirementsjson ? $requirementsjson : array('error' => true, 'errormsg' => 'The Item Requirements JSON contains errors. JSON ERROR: '.json_last_error());
		
		if ($requirementsjson['error']) {
			echo $page->bootstrap->createalert('warning', $requirementsjson['errormsg']);
		} else {
			$columns = array_keys($requirementsjson['columns']);
			
			echo '<h3>'.$screentypes[$requirementsjson['reqavl']].'</h3>';
			echo '<div class="row">';
				echo '<div class="col-sm-5">';
					$tb = new Table('class=table table-striped table-bordered table-condensed table-excel');
					$tb->tr();
					$tb->td('', 'Item ID:');
					$tb->td('', $requirementsjson['itemid']);
					$tb->tr();
					$tb->td('', 'Whse:');
					$select = '<select class="form-control input-sm item-requirements-whse" onchange="requirements(false, this.value, '.$refresh.'.)">';
					foreach ($warehouses as $warehouse) {
						if ($warehouse == $requirementsjson['whse']) {
							$select .= '<option value="'.$warehouse.'" selected>'.$whsejson['data'][$warehouse]['warehouse name'].'</option>';
						} else {
							$select .= '<option value="'.$warehouse.'">'.$whsejson['data'][$warehouse]['warehouse name'].'</option>';
						}
					}
					$select .= '</select>';
					$tb->td('', $select);
					$tb->tr();
					$tb->td('', 'View');
					$select = '<select class="form-control input-sm item-requirements-screentype" onchange="requirements(this.value, false, '.$refresh.'.)">';
					foreach ($screentypes as $key => $value) {
						if ($key == $requirementsjson['reqavl']) {
							$select .= '<option value="'.$key.'" selected>'.$value.'</option>';
						} else {
							$select .= '<option value="'.$key.'">'.$value.'</option>';
						}
					}
					$select .= '</select>';
					$tb->td('', $select);
					echo $tb->close();
				echo '</div>';
			echo '</div>';
			
			$tb = new Table('class=table table-striped table-bordered table-condensed table-excel');
			$tb->tablesection('thead');
				$tb->tr();
				foreach($requirementsjson['columns'] as $column) {
					$class = $config->textjustify[$column['headingjustify']];
					$tb->th("class=$class", $column['heading']);
				}
			$tb->closetablesection('thead');
			$tb->tablesection('tbody');
				foreach($requirementsjson['data']['orders'] as $order) {
					$tb->tr();
					foreach($columns as $column) {
						$class = $config->textjustify[$requirementsjson['columns'][$column]['datajustify']];
						$tb->th("class=$class", $order[$column]);
					}
					
				}
			$tb->closetablesection('tbody');
			echo $tb->close();
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
?>
