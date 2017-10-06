<?php 
	if (checkformatterifexists($user->loginid, 'ci-sales-history', false)) {
		$formatterjson = json_decode(getformatter($user->loginid, 'ci-sales-history', false), true);
	} else {
		$default = $config->paths->content."cust-information/screen-formatters/default/ci-sales-history.json";
		$formatterjson = json_decode(file_get_contents($default), true);
	}

	$detailcolumns = array_keys($formatterjson['detail']['columns']);
	$headercolumns = array_keys($formatterjson['header']['columns']);
	$lotserialcolumns = array_keys($formatterjson['lotserial']['columns']);

	$totalcolumns = array_keys($formatterjson['total']['columns']);
	$shipmentcolumns = array_keys($formatterjson['shipments']['columns']);

	$fieldsjson = json_decode(file_get_contents($config->companyfiles."json/cishfmattbl.json"), true);

	$table = array(
		'maxcolumns' => $formatterjson['cols'],
		'header' => array('maxrows' => $formatterjson['header']['rows'], 'rows' => array()),
		'detail' => array('maxrows' => $formatterjson['detail']['rows'], 'rows' => array()),
		'lotserial' => array('maxrows' => $formatterjson['lotserial']['rows'], 'rows' => array()),
		'total' => array('maxrows' => $formatterjson['total']['rows'], 'rows' => array()),
		'shipments' => array('maxrows' => $formatterjson['shipments']['rows'], 'rows' => array()),
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

	foreach ($lotserialcolumns as $column) {
		$col = array(
			'id' => $column,
			'label' => $formatterjson['lotserial']['columns'][$column]['label'],
			'column' => $formatterjson['lotserial']['columns'][$column]['column'],
			'col-length' => $formatterjson['lotserial']['columns'][$column]['col-length'],
			'before-decimal' => $formatterjson['lotserial']['columns'][$column]['before-decimal'],
			'after-decimal' => $formatterjson['lotserial']['columns'][$column]['after-decimal'],
			'date-format' => $formatterjson['lotserial']['columns'][$column]['date-format']
		);
		$table['lotserial']['rows'][1]['columns'][$formatterjson['lotserial']['columns'][$column]['column']] = $col;

	}


	for ($i = 1; $i < $formatterjson['total']['rows'] + 1; $i++) {
		$table['total']['rows'][$i] = array('columns' => array());
		$count = 1;
		foreach($totalcolumns as $column) {
			if ($formatterjson['total']['columns'][$column]['line'] == $i) {
				$col = array(
					'id' => $column,
					'label' => $formatterjson['total']['columns'][$column]['label'],
					'column' => $formatterjson['total']['columns'][$column]['column'],
					'col-length' => $formatterjson['total']['columns'][$column]['col-length'],
					'before-decimal' => $formatterjson['total']['columns'][$column]['before-decimal'],
					'after-decimal' => $formatterjson['total']['columns'][$column]['after-decimal'],
					'date-format' => $formatterjson['total']['columns'][$column]['date-format']
				);
				$table['total']['rows'][$i]['columns'][$formatterjson['total']['columns'][$column]['column']] = $col;
				$count++;
			}
		}
	}

	for ($i = 1; $i < $formatterjson['shipments']['rows'] + 1; $i++) {
		$table['shipments']['rows'][$i] = array('columns' => array());
		$count = 1;
		foreach($shipmentcolumns as $column) {
			if ($formatterjson['shipments']['columns'][$column]['line'] == $i) {
				$col = array(
					'id' => $column,
					'label' => $formatterjson['shipments']['columns'][$column]['label'],
					'column' => $formatterjson['shipments']['columns'][$column]['column'],
					'col-length' => $formatterjson['shipments']['columns'][$column]['col-length'],
					'before-decimal' => $formatterjson['shipments']['columns'][$column]['before-decimal'],
					'after-decimal' => $formatterjson['shipments']['columns'][$column]['after-decimal'],
					'date-format' => $formatterjson['shipments']['columns'][$column]['date-format']
				);
				$table['shipments']['rows'][$i]['columns'][$formatterjson['shipments']['columns'][$column]['column']] = $col;
				$count++;
			}
		}
	}
	return $table;
