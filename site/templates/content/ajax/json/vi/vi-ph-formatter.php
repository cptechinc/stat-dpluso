<?php
	header('Content-Type: application/json');

	$formatjson = json_decode(file_get_contents($config->companyfiles."json/vipyfmattbl.json"), true);
	$columns = array_keys($formatjson['data']['detail']);
	$postarray = array();

	if ($input->requestMethod() == "POST") {
		$maxrec = getmaxtableformatterid($user->loginid, 'vi-payment-history', false);
		$postarray['rows'] = $input->post->int('detail-rows');
		$cols = $input->post->int('cols');
		$postarray['columns'] = array();

		foreach ($columns as $column) {
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
			$postarray['columns'][$column] = array('line' => $linenumber, 'column' => $colnumber, 'col-length' => $length, 'label' => $label, 'before-decimal' => $beforedecimal, 'after-decimal' => $afterdecimal, 'date-format' => $dateformat);

		}

		$json = array('cols' => $cols, 'detail' => $postarray);

		if ($input->post->text('action') == 'add-formatter') {
			if (checkformatterifexists($user->loginid, 'vi-payment-history', false)) {
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
				$response = addformatter($user->loginid, 'vi-payment-history', json_encode($json), false);
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
			$response = editformatter($user->loginid, 'vi-payment-history', json_encode($json), false);
			$session->response = $response;
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
							'message' => 'Your configuration was not able to be saved',
							'icon' => 'glyphicon glyphicon-warning-sign'
					)
				);
			}
		}

		echo json_encode($json);
	} else {
		if (checkformatterifexists($user->loginid, 'vi-payment-history', false)) {
			$defaultjson = json_decode(getformatter($user->loginid, 'vi-payment-history', false), true);
		} else {
			$default = $config->paths->content."vend-information/screen-formatters/default/vi-payment-history.json";
			$defaultjson = json_decode(file_get_contents($default), true);
		}

		echo json_encode($defaultjson);


	}
