<?php
	if (checkformatterifexists($user->loginid, 'ci-quote', false)) {
		$defaultjson = json_decode(getformatter($user->loginid, 'ci-quote', false), true);
	} else {
		$default = $config->paths->content."cust-information/screen-formatters/default/ci-quote.json";
		$defaultjson = json_decode(file_get_contents($default), true);
	}

	$detailcolumns = array_keys($defaultjson['detail']['columns']);
	$headercolumns = array_keys($defaultjson['header']['columns']);
	$fieldsjson = json_decode(file_get_contents($config->companyfiles."json/iiqtfmattbl.json"), true);

	$table = array(
		'maxcolumns' => $defaultjson['cols'],
		'detail' => array('maxrows' => $defaultjson['detail']['rows'], 'rows' => array()),
		'header' => array('maxrows' => $defaultjson['header']['rows'], 'rows' => array())
	);

	for ($i = 1; $i < $defaultjson['detail']['rows'] + 1; $i++) {
		$table['detail']['rows'][$i] = array('columns' => array());
		$count = 1;
		foreach($detailcolumns as $column) {
			if ($defaultjson['detail']['columns'][$column]['line'] == $i) {
				$col = array(
					'id' => $column, 
					'label' => $defaultjson['detail']['columns'][$column]['label'], 
					'column' => $defaultjson['detail']['columns'][$column]['column'], 
					'col-length' => $defaultjson['detail']['columns'][$column]['col-length'], 
					'before-decimal' => $defaultjson['detail']['columns'][$column]['before-decimal'], 
					'after-decimal' => $defaultjson['detail']['columns'][$column]['after-decimal'], 
					'date-format' => $defaultjson['detail']['columns'][$column]['date-format']
				);
				$table['detail']['rows'][$i]['columns'][$defaultjson['detail']['columns'][$column]['column']] = $col;
				$count++;
			}
		}
	}

	for ($i = 1; $i < $defaultjson['header']['rows'] + 1; $i++) {
		$table['header']['rows'][$i] = array('columns' => array());
		foreach($headercolumns as $column) {
			if ($defaultjson['header']['columns'][$column]['line'] == $i) {
				$col = array(
					'id' => $column, 
					'label' => $defaultjson['header']['columns'][$column]['label'], 
					'column' => $defaultjson['header']['columns'][$column]['column'], 
					'col-length' => $defaultjson['header']['columns'][$column]['col-length'], 
					'before-decimal' => $defaultjson['header']['columns'][$column]['before-decimal'], 
					'after-decimal' => $defaultjson['header']['columns'][$column]['after-decimal'], 
					'date-format' => $defaultjson['header']['columns'][$column]['date-format']
				);
				$table['header']['rows'][$i]['columns'][$defaultjson['header']['columns'][$column]['column']] = $col;
			}
		}
	}
	return $table;
?>
