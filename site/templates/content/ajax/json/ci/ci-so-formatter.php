<?php
	header('Content-Type: application/json');

	$formatjson = json_decode(file_get_contents($config->companyfiles."json/cisofmattbl.json"), true);
	$detailcolumns = array_keys($formatjson['data']['detail']);
    $headercolumns = array_keys($formatjson['data']['header']);
    $itemstatuscolumns = array_keys($formatjson['data']['itemstatus']);
	$purchaseordercolumns = array_keys($formatjson['data']['purchaseorder']);
	$totalcolumns = array_keys($formatjson['data']['total']);
	$shipmentcolumns = array_keys($formatjson['data']['shipments']);

	$postarray = array();

	if ($input->requestMethod() == "POST") {
		$maxrec = getmaxtableformatterid($user->loginid, 'ci-sales-order', false);
        $postarray['cols'] = $input->post->int('cols');
		$postarray['detail']['rows'] = $input->post->int('detail-rows');
		$postarray['header']['rows'] = $input->post->int('header-rows');
        $postarray['itemstatus']['rows'] = $input->post->int('itemstatus-rows');
        $postarray['purchaseorder']['rows'] = $input->post->int('purchaseorder-rows');
        $postarray['total']['rows'] = $input->post->int('total-rows');
        $postarray['shipments']['rows'] = $input->post->int('shipments-rows');

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

        foreach ($itemstatuscolumns as $column) {
			$postcolumn = str_replace(' ', '', $column);
			$linenumber = $input->post->int($postcolumn.'-line');
			$length = $input->post->int($postcolumn.'-length');
			$colnumber = $input->post->int($postcolumn.'-column');
			$label = $input->post->text($postcolumn.'-label');
			$dateformat = $beforedecimal = $afterdecimal = false;
			if ($formatjson['data']['itemstatus'][$column]['type'] == 'D') {
				$dateformat = $input->post->text($postcolumn.'-date-format');
			} elseif ($formatjson['data']['itemstatus'][$column]['type'] == 'N') {
				$beforedecimal = $input->post->int($postcolumn.'-before-decimal');
				$afterdecimal = $input->post->int($postcolumn.'-after-decimal');
			}
			$postarray['itemstatus']['columns'][$column] = array('line' => $linenumber, 'column' => $colnumber, 'col-length' => $length, 'label' => $label, 'before-decimal' => $beforedecimal, 'after-decimal' => $afterdecimal, 'date-format' => $dateformat);
		}

        foreach ($purchaseordercolumns as $column) {
			$postcolumn = str_replace(' ', '', $column);
			$linenumber = $input->post->int($postcolumn.'-line');
			$length = $input->post->int($postcolumn.'-length');
			$colnumber = $input->post->int($postcolumn.'-column');
			$label = $input->post->text($postcolumn.'-label');
			$dateformat = $beforedecimal = $afterdecimal = false;
			if ($formatjson['data']['purchaseorder'][$column]['type'] == 'D') {
				$dateformat = $input->post->text($postcolumn.'-date-format');
			} elseif ($formatjson['data']['purchaseorder'][$column]['type'] == 'N') {
				$beforedecimal = $input->post->int($postcolumn.'-before-decimal');
				$afterdecimal = $input->post->int($postcolumn.'-after-decimal');
			}
			$postarray['purchaseorder']['columns'][$column] = array('line' => $linenumber, 'column' => $colnumber, 'col-length' => $length, 'label' => $label, 'before-decimal' => $beforedecimal, 'after-decimal' => $afterdecimal, 'date-format' => $dateformat);
		}

        foreach ($totalcolumns as $column) {
			$postcolumn = str_replace(' ', '', $column);
			$linenumber = $input->post->int($postcolumn.'-line');
			$length = $input->post->int($postcolumn.'-length');
			$colnumber = $input->post->int($postcolumn.'-column');
			$label = $input->post->text($postcolumn.'-label');
			$dateformat = $beforedecimal = $afterdecimal = false;
			if ($formatjson['data']['total'][$column]['type'] == 'D') {
				$dateformat = $input->post->text($postcolumn.'-date-format');
			} elseif ($formatjson['data']['total'][$column]['type'] == 'N') {
				$beforedecimal = $input->post->int($postcolumn.'-before-decimal');
				$afterdecimal = $input->post->int($postcolumn.'-after-decimal');
			}
			$postarray['total']['columns'][$column] = array('line' => $linenumber, 'column' => $colnumber, 'col-length' => $length, 'label' => $label, 'before-decimal' => $beforedecimal, 'after-decimal' => $afterdecimal, 'date-format' => $dateformat);
		}

        foreach ($shipmentcolumns as $column) {
			$postcolumn = str_replace(' ', '', $column);
			$linenumber = $input->post->int($postcolumn.'-line');
			$length = $input->post->int($postcolumn.'-length');
			$colnumber = $input->post->int($postcolumn.'-column');
			$label = $input->post->text($postcolumn.'-label');
			$dateformat = $beforedecimal = $afterdecimal = false;
			if ($formatjson['data']['shipments'][$column]['type'] == 'D') {
				$dateformat = $input->post->text($postcolumn.'-date-format');
			} elseif ($formatjson['data']['shipments'][$column]['type'] == 'N') {
				$beforedecimal = $input->post->int($postcolumn.'-before-decimal');
				$afterdecimal = $input->post->int($postcolumn.'-after-decimal');
			}
			$postarray['shipments']['columns'][$column] = array('line' => $linenumber, 'column' => $colnumber, 'col-length' => $length, 'label' => $label, 'before-decimal' => $beforedecimal, 'after-decimal' => $afterdecimal, 'date-format' => $dateformat);
		}

		if ($input->post->text('action') == 'add-formatter') {
			if (checkformatterifexists($user->loginid, 'ci-sales-order', false)) {
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
				$response = addformatter($user->loginid, 'ci-sales-order', json_encode($postarray), false);
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
			$response = editformatter($user->loginid, 'ci-sales-order', json_encode($postarray), false);
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
		if (checkformatterifexists($user->loginid, 'ci-sales-order', false)) {
			$defaultjson = json_decode(getformatter($user->loginid, 'ci-sales-order', false), true);
		} else {
			$default = $config->paths->content."cust-information/screen-formatters/default/ci-sales-orders.json";
			$defaultjson = json_decode(file_get_contents($default), true);
		}

		echo json_encode($defaultjson);


	}
