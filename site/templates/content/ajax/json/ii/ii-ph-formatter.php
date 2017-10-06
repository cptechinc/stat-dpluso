<?php
	header('Content-Type: application/json');

	$formatjson = json_decode(file_get_contents($config->companyfiles."json/iiphfmattbl.json"), true);
	$detailcolumns = array_keys($formatjson['data']['detail']);
	$lotserialcolumns = array_keys($formatjson['data']['lotserial']);
	$postarray = array('cols' => 0, 'detail' => array('rows' => 0, 'columns' => array()), 'lotserial' => array('rows' => 0, 'columns' => array()));

	if ($input->requestMethod() == "POST") {
		$maxrec = getmaxtableformatterid($user->loginid, 'ii-purchase-history', false);
		$postarray['cols'] = $input->post->int('cols');


		$postarray['detail']['rows'] = $input->post->int('detail-rows');
		$postarray['lotserial']['rows'] = $input->post->int('lotserial-rows');


		foreach ($detailcolumns as $column) {
			$postcolumn = str_replace(' ', '', $column);
			$linenumber = $input->post->int($postcolumn.'-line');
			$length = $input->post->int($postcolumn.'-length');
			$colnumber = $input->post->int($postcolumn.'-column');
			$label = $input->post->text($postcolumn.'-label');
			$dateformat = $beforedecimal = $afterdecimal = false;
			if ($formatjson['data']['detail'][$column]['type'] == 'D') {
				$dateformat = $input->post->text($postcolumn.'-date-format');
			} elseif ($formatjson['data']['detail'][$column]['type'] == 'N') {
				$beforedecimal = $input->post->int($postcolumn.'-before-decimal');
				$afterdecimal = $input->post->int($postcolumn.'-after-decimal');
			}
			$postarray['detail']['columns'][$column] = array('line' => $linenumber, 'column' => $colnumber, 'col-length' => $length, 'label' => $label, 'before-decimal' => $beforedecimal, 'after-decimal' => $afterdecimal, 'date-format' => $dateformat);

		}

		foreach ($lotserialcolumns as $column) {
			$postcolumn = str_replace(' ', '', $column);
			$linenumber = $input->post->int($postcolumn.'-line');
			$length = $input->post->int($postcolumn.'-length');
			$colnumber = $input->post->int($postcolumn.'-column');
			$label = $input->post->text($postcolumn.'-label');
			$dateformat = $beforedecimal = $afterdecimal = false;
			if ($formatjson['data']['lotserial'][$column]['type'] == 'D') {
				$dateformat = $input->post->text($postcolumn.'-date-format');
			} elseif ($formatjson['data']['lotserial'][$column]['type'] == 'N') {
				$beforedecimal = $input->post->int($postcolumn.'-before-decimal');
				$afterdecimal = $input->post->int($postcolumn.'-after-decimal');
			}
			$postarray['lotserial']['columns'][$column] = array('line' => $linenumber, 'column' => $colnumber, 'col-length' => $length, 'label' => $label, 'before-decimal' => $beforedecimal, 'after-decimal' => $afterdecimal, 'date-format' => $dateformat);

		}

		if ($input->post->text('action') == 'add-formatter') {
			$session->action = 'add';
			if (checkformatterifexists($user->loginid, 'ii-purchase-history', false)) {
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
				$session->addsql = addformatter($user->loginid, 'ii-purchase-history', json_encode($postarray), true);
				$response = addformatter($user->loginid, 'ii-purchase-history', json_encode($postarray), false);
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
			$session->editsql = editformatter($user->loginid, 'ii-purchase-history', json_encode($postarray), true);
			$response = editformatter($user->loginid, 'ii-purchase-history', json_encode($postarray), false);
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
		if (checkformatterifexists($user->loginid, 'ii-purchase-history', false)) {
			$defaultjson = json_decode(getformatter($user->loginid, 'ii-purchase-history', false), true);
		} else {
			$default = $config->paths->content."item-information/screen-formatters/default/ii-purchase-history.json";
			$defaultjson = json_decode(file_get_contents($default), true);
		}

		echo json_encode($defaultjson);
	}
