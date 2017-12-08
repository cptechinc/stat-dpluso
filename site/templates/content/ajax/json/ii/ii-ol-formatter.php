<?php
	header('Content-Type: application/json');

	$formatjson = json_decode(file_get_contents($config->companyfiles."json/iihfmattbl.json"), true);
	$headercolumns = array_keys($formatjson['data']['header']);
	$postarray = array(
		'cols' => 0, 
		'header' => array('rows' => 0, 'columns' => array())
	);

	if ($input->requestMethod() == "POST") {
		$maxrec = getmaxtableformatterid($user->loginid, 'ii-outline', false);
		$postarray['cols'] = $input->post->int('cols');
		$postarray['header']['rows'] = $input->post->int('header-rows');
		$postarray['columns'] = array();

		foreach ($headercolumns as $column) {
			$postcolumn = str_replace(' ', '', $column);
			$linenumber = $input->post->int($postcolumn.'-line');
			$length = $input->post->int($postcolumn.'-length');
			$colnumber = $input->post->int($postcolumn.'-column');
			$label = $input->post->text($postcolumn.'-label');
			$dateformat = $beforedecimal = $afterdecimal = false;
			if ($formatjson['data']['header'][$column]['type'] == 'D') {
				$dateformat = $input->post->text($postcolumn.'-date-format');
			} elseif ($formatjson['data']['header'][$column]['type'] == 'N') {
				$beforedecimal = $input->post->int($postcolumn.'-before-decimal');
				$afterdecimal = $input->post->int($postcolumn.'-after-decimal');
			}
			$postarray['header']['columns'][$column] = array('line' => $linenumber, 'column' => $colnumber, 'col-length' => $length, 'label' => $label, 'before-decimal' => $beforedecimal, 'after-decimal' => $afterdecimal, 'date-format' => $dateformat);

		}

		if ($input->post->text('action') == 'add-formatter') {
			$session->action = 'add';
			if (checkformatterifexists($user->loginid, 'ii-outline', false)) {
				$json = array (
					'response' => array (
							'error' => true,
							'notifytype' => 'danger',
							'alreadyexists' => true,
							'message' => 'You already have a screen defined',
							'icon' => 'glyphicon glyphicon-warning-sign'
					)
				);
			} else {
				$session->addsql = addformatter($user->loginid, 'ii-outline', json_encode($postarray), true);
				$response = addformatter($user->loginid, 'ii-outline', json_encode($postarray), false);
				if ($response['insertedid'] > $maxrec) {
					$json = array (
						'response' => array (
								'error' => false,
								'notifytype' => 'success',
								'alreadyexists' => false,
								'message' => 'Your screen configuration has been saved',
								'icon' => 'glyphicon glyphicon-floppy-disk'
						)
					);
				}
			}

		} elseif ($input->post->text('action') == 'edit-formatter') {
			$session->editsql = editformatter($user->loginid, 'ii-outline', json_encode($postarray), true);
			$response = editformatter($user->loginid, 'ii-outline', json_encode($postarray), false);
			$session->action = 'edit';
			if ($response['affectedrows']) {
				$json = array (
						'response' => array (
								'error' => false,
								'notifytype' => 'success',
								'alreadyexists' => false,
								'message' => 'Your screen configuration has been saved',
								'icon' => 'glyphicon glyphicon-floppy-disk'
						)
					);
			} else {
				$json = array (
					'response' => array (
							'error' => true,
							'notifytype' => 'danger',
							'alreadyexists' => true,
							'message' => 'Your configuration was not able to be saved, you may have not made any discernable changes.',
							'icon' => 'glyphicon glyphicon-warning-sign'
					)
				);
			}
		}

		echo json_encode($json);
	} else {
		if (checkformatterifexists($user->loginid, 'ii-outline', false)) {
			$defaultjson = json_decode(getformatter($user->loginid, 'ii-outline', false), true);
		} else {
			$default = $config->paths->content."item-information/screen-formatters/default/ii-outline.json";
			$defaultjson = json_decode(file_get_contents($default), true);
		}

		echo json_encode($defaultjson);
	}
