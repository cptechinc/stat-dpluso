<?php 
	if (checkformatterifexists($user->loginid, 'ii-quote', false)) {
		$formatterjson = json_decode(getformatter($user->loginid, 'ii-quote', false), true);
	} else {
		$default = $config->paths->content."item-information/screen-formatters/default/ii-quote.json";
		$formatterjson = json_decode(file_get_contents($default), true);
	}

	$detailcolumns = array_keys($formatterjson['detail']['columns']);
	$headercolumns = array_keys($formatterjson['header']['columns']);
	$fieldsjson = json_decode(file_get_contents($config->companyfiles."json/iiqtfmattbl.json"), true);

	$table = array(
		'maxcolumns' => $formatterjson['cols'],
		'detail' => array('maxrows' => $formatterjson['detail']['rows'], 'rows' => array()),
		'header' => array('maxrows' => $formatterjson['header']['rows'], 'rows' => array())
	);

	for ($i = 1; $i < $formatterjson['detail']['rows'] + 1; $i++) {
		$table['detail']['rows'][$i] = array('columns' => array());
		$count = 1;
		foreach($detailcolumns as $column) {
			if ($formatterjson['detail']['columns'][$column]['line'] == $i) {
				$col = array(
					'id' => $column,
					'label' => $formatterjson['detail']['columns'][$column]['label'],
					'column' => $formatterjson['detail']['columns'][$column]['column'],
					'col-length' => $formatterjson['detail']['columns'][$column]['col-length'],
					'before-decimal' => $formatterjson['detail']['columns'][$column]['before-decimal'],
					'after-decimal' => $formatterjson['detail']['columns'][$column]['after-decimal'],
					'date-format' => $formatterjson['detail']['columns'][$column]['date-format']
				);
				$table['detail']['rows'][$i]['columns'][$formatterjson['detail']['columns'][$column]['column']] = $col;
				$count++;
			}
		}
	}

	for ($i = 1; $i < $formatterjson['header']['rows'] + 1; $i++) {
		$table['header']['rows'][$i] = array('columns' => array());
		foreach($headercolumns as $column) {
			if ($formatterjson['header']['columns'][$column]['line'] == $i) {
				$col = array(
					'id' => $column,
					'label' => $formatterjson['header']['columns'][$column]['label'],
					'column' => $formatterjson['header']['columns'][$column]['column'],
					'col-length' => $formatterjson['header']['columns'][$column]['col-length'],
					'before-decimal' => $formatterjson['header']['columns'][$column]['before-decimal'],
					'after-decimal' => $formatterjson['header']['columns'][$column]['after-decimal'],
					'date-format' => $formatterjson['header']['columns'][$column]['date-format']
				);
				$table['header']['rows'][$i]['columns'][$formatterjson['header']['columns'][$column]['column']] = $col;
			}
		}
	}
	return $table;
?>
