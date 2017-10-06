<?php 
	if (checkformatterifexists($user->loginid, 'ii-purchase-order', false)) {
		$formatterjson = json_decode(getformatter($user->loginid, 'ii-purchase-order', false), true);
	} else {
		$default = $config->paths->content."item-information/screen-formatters/default/ii-purchase-order.json";
		$formatterjson = json_decode(file_get_contents($default), true);
	}

	$columns = array_keys($formatterjson['detail']['columns']);
	$fieldsjson = json_decode(file_get_contents($config->companyfiles."json/iipofmattbl.json"), true);

	$table = array(
		'maxcolumns' => $formatterjson['cols'],
		'detail' => array(
			'maxrows' => $formatterjson['detail']['rows'],
			'rows' => array()
		)
	);
	for ($i = 1; $i < $formatterjson['detail']['rows'] + 1; $i++) {
		$table['detail']['rows'][$i] = array('columns' => array());
		$count = 1;
		foreach($columns as $column) {
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
	return $table;
?>
