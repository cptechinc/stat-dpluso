<?php 
	if (checkformatterifexists($user->loginid, 'ii-outline', false)) {
		$formatterjson = json_decode(getformatter($user->loginid, 'ii-outline', false), true);
	} else {
		$default = $config->paths->content."item-information/screen-formatters/default/ii-outline.json";
		$formatterjson = json_decode(file_get_contents($default), true);
	}

	$detailcolumns = array_keys($formatterjson['header']['columns']);
    
	$fieldsjson = json_decode(file_get_contents($config->companyfiles."json/iihfmattbl.json"), true);

	/**
	 * iihf formatter notes
	 *
	 * this formatter doesn't need the max columns value and it doesn't need the maxrows value for each section 
	 */
	$table = array(
		'header' => array(
			'sections' => array(
				'1' => array(),
				'2' => array(),
				'3' => array(),
				'4' => array()
			)
		)
	);
	
	for ($i = 1; $i < 5; $i++) {
		foreach($detailcolumns as $column) {
			if ($formatterjson['header']['columns'][$column]['column'] == $i) {
				$col = array(
					'id' => $column, 
					'label' => $formatterjson['header']['columns'][$column]['label'], 
					'column' => $formatterjson['header']['columns'][$column]['column'], 
					'col-length' => $formatterjson['header']['columns'][$column]['col-length'], 
					'before-decimal' => $formatterjson['header']['columns'][$column]['before-decimal'],
					'after-decimal' => $formatterjson['header']['columns'][$column]['after-decimal'], 
					'date-format' => $formatterjson['header']['columns'][$column]['date-format']
				 );
				$table['header']['sections'][$i][$formatterjson['header']['columns'][$column]['line']] = $col;
			}
		}
	}

	// echo json_encode($table);
	return $table;
