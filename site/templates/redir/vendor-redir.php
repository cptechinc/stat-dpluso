<?php
	/**
	* VENDOR REDIRECT
	* @param string $action
	*
	*/

	$action = ($input->post->action ? $input->post->text('action') : $input->get->text('action'));
	$vendorID = ($input->post->vendorID ? $input->post->text('vendorID') : $input->get->text('vendorID'));
	

	$session->{'from-redirect'} = $page->url;

	$filename = session_id();

	/**
	* CUSTOMER REDIRECT
	* @param string $action
	*
	*
	*
	* switch ($action) {
	* 	case 'vi-buttons': 760p
	* 		DBNAME=$config->DBNAME
	*		VIBUTTONS
	*		break;
	*	case 'vi-vendor': 759p  //AUTO CALLS vi-buttons and vi-shipfromlist
	*		DBNAME=$config->DBNAME
	*		VIVENDOR
	*		VENDID=$custID
	* 		break;
	* 	case 'vi-shipfrom-list'
	* 		DBNAME=$config->DBNAME
	*		VISHIPFROMLIST
	*		VENDID=$custID
	* 		break;
	*	case 'vi-payment'
	* 		DBNAME=$config->DBNAME
	*		VIPAYMENT
	*		VENDID=$custID
	* 		break;
	*	case 'vi-shipfrom'
	* 		DBNAME=$config->DBNAME
	*		VISHIPFROMINFO
	*		VENDID=$custID
	*		SHIPID=
	* 		break;
	*	case 'vi-purchasehist'
	* 		DBNAME=$config->DBNAME
	*		VIPURCHHIST
	*		VENDID=$custID
	*		SHIPID=
	*		DATE=
	* 		break;
	* }
	*
	**/


	switch ($action) {
		case 'vi-buttons': //NOT USED WILL BE AUTOCALLED BY vend-vendor
			$data = array('DBNAME' => $config->dbName, 'VIBUTTONS' => false);
			$session->loc = $config->pages->index;
			break;
		case 'vi-vendor':
			$data = array('DBNAME' => $config->dbName, 'VIVENDOR' => false, 'VENDID' => $vendorID);
			$session->loc = $config->pages->vendorinfo. "$vendorID/";
			break;
		case 'vi-shipfrom-list':
			$data = array('DBNAME' => $config->dbName, 'VISHIPFROMLIST' => $vendorID);
			$session->loc = $config->pages->index;
			break;
		case 'vi-shipfrom':
			$shipfromID = $input->get->text('shipfromID');
			$data = array('DBNAME' => $config->dbName, 'VISHIPFROMINFO' => false, 'VENDID' => $vendorID, 'SHIPID' => $shipfromID);
			// USE THIS for cases where buttons will be grabbed twice 
			// if (!empty($input->get->text('shipfromID'))) {
			// 	$data['SHIPID'] = $input->get->text('shipfromID');
			// }
			$session->loc = $config->pages->vendorinfo. "$vendorID/shipfrom-$shipfromID";
			break;
		case 'vi-openinv':
			$data = array('DBNAME' => $config->dbName, 'VIOPENINV' => false, 'VENDID' => $vendorID);
			$session->loc = $config->pages->vendorinfo. "$vendorID/";
			break;
		case 'vi-payment':
			$data = array('DBNAME' => $config->dbName, 'VIPAYMENT' => false, 'VENDID' => $vendorID);
			$session->loc = $config->pages->vendorinfo. "$vendorID/";
			break;
		case 'vi-purchasehist':
			$date = $input->post->text('date');
			$session->date = $date;
			$startdate = date('Ymd', strtotime($date));
			$data = array('DBNAME' => $config->dbName, 'VIPURCHHIST' => false, 'VENDID' => $vendorID, 'DATE' => $startdate);
			if (!empty($input->post->shipfromID)) {
				$data['SHIPID'] = $input->post->text('shipfromID');
			}
			$session->loc = $config->pages->vendorinfo. "$vendorID/";
			break;
	}

	writedplusfile($data, $filename);
	header("location: /cgi-bin/" . $config->cgi . "?fname=" . $filename);
 	exit;
