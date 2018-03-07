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
	* VENDOR REDIRECT
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
	*		VENDID=$vendorID
	* 		break;
	* 	case 'vi-shipfrom-list'
	* 		DBNAME=$config->DBNAME
	*		VISHIPFROMLIST 
	*		VENDID=$vendorID
	* 		break;
	*	case 'vi-payments'
	* 		DBNAME=$config->DBNAME
	*		VIPAYMENT n2zz764p
	*		VENDID=$vendorID
	* 		break;
	*	case 'vi-shipfrom'
	* 		DBNAME=$config->DBNAME
	*		VISHIPFROMINFO n2zz761p
	*		VENDID=$vendorID
	*		SHIPID=
	* 		break;
	*	case 'vi-purchase-history'
	* 		DBNAME=$config->DBNAME
	*		VIPURCHHIST n2zz766p
	*		VENDID=$vendorID
	*		SHIPID=
	*		DATE=
	* 		break;
	*	case 'vi-purchaseorder'
	* 		DBNAME=$config->DBNAME
	*		VIPURCHORDR n2zz767p
	*		VENDID=$vendorID
	*		SHIPID=
	* 		break;
	*	case 'vi-contact'
	* 		DBNAME=$config->DBNAME
	*		VICONTACT n2zz768p
	*		VENDID=$vendorID
	*		SHIPID=
	* 		break;
	*	case 'vi-costing'
	* 		DBNAME=$config->DBNAME
	*		VICOST n2zz770p
	*		VENDID=$vendorID
	*		ITEMID=
	* 		break;
	*	case 'vi-unreleased'
	* 		DBNAME=$config->DBNAME
	*		VIUNRELEASED n2zz772p
	*		VENDID=$vendorID
	*		SHIPID=
	* 		break;
	*	case 'vi-uninvoiced'
	* 		DBNAME=$config->DBNAME
	*		VIUNINVOICED n2zz773p
	*		VENDID=$vendorID
	* 		break;
	* 	case 'vi-open-invoices'
	* 		DBNAME=$config->DBNAME
	*		VIOPENINV n2zz765p
	*		VENDID=$vendorID
	* 		break;
	* 	case 'vi-24monthsummary'
	* 		DBNAME=$config->DBNAME
	*		VIMONTHSUM n2zz774p
	*		VENDID=$vendorID
	* 		break;
	* 	case 'vi-notes'
	* 		DBNAME=$config->DBNAME
	*		VINOTES 
	*		VENDID=$vendorID
	*		SHIPID=
	* 		break;
	* 	case 'vi-docview'
	* 		DBNAME=$config->DBNAME
	*		DOCVIEW n2zz735p
	*		FLD1CO=VI
	*		FLD1DATA=$vendorID
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
		case 'vi-open-invoices':
			$data = array('DBNAME' => $config->dbName, 'VIOPENINV' => false, 'VENDID' => $vendorID);
			$session->loc = $config->pages->vendorinfo. "$vendorID/";
			break;
		case 'vi-payments':
			$data = array('DBNAME' => $config->dbName, 'VIPAYMENT' => false, 'VENDID' => $vendorID);
			$session->loc = $config->pages->vendorinfo. "$vendorID/";
			break;
		case 'vi-purchase-history':
			$date = $input->post->text('date');
			$session->date = $date;
			$startdate = date('Ymd', strtotime($date));
			$data = array('DBNAME' => $config->dbName, 'VIPURCHHIST' => false, 'VENDID' => $vendorID, 'DATE' => $startdate);
			if (!empty($input->post->shipfromID)) {
				$data['SHIPID'] = $input->post->text('shipfromID');
			}
			$session->loc = $config->pages->vendorinfo. "$vendorID/";
			break;
		case 'vi-purchase-orders':
			$data = array('DBNAME' => $config->dbName, 'VIPURCHORDR' => false, 'VENDID' => $vendorID);
			if (!empty($input->post->shipfromID)) {
				$data['SHIPID'] = $input->post->text('shipfromID');
			}
			$session->loc = $config->pages->vendorinfo. "$vendorID/";
			break;
		case 'vi-contact':
			$data = array('DBNAME' => $config->dbName, 'VICONTACT' => false, 'VENDID' => $vendorID);
			if (!empty($input->post->shipfromID)) {
				$data['SHIPID'] = $input->post->text('shipfromID');
			}
			$session->loc = $config->pages->vendorinfo. "$vendorID/";
			break;
		case 'vi-notes':
			$data = array('DBNAME' => $config->dbName, 'VINOTES' => false, 'VENDID' => $vendorID);
			if (!empty($input->post->shipfromID)) {
				$data['SHIPID'] = $input->post->text('shipfromID');
			}
			$session->loc = $config->pages->vendorinfo. "$vendorID/";
			break;
		case 'vi-costing':
			$itemID = $input->get->text('itemID');
			$data = array('DBNAME' => $config->dbName, 'VICOST' => false, 'VENDID' => $vendorID, 'ITEMID' => $itemID);
			$session->loc = $config->pages->index;
			break;
		case 'vi-unreleased-purchase-orders':
			$data = array('DBNAME' => $config->dbName, 'VIUNRELEASED' => false, 'VENDID' => $vendorID);
			if (!empty($input->post->shipfromID)) {
				$data['SHIPID'] = $input->post->text('shipfromID');
			}
			$session->loc = $config->pages->vendorinfo. "$vendorID/";
			break;
		case 'vi-uninvoiced':
			$data = array('DBNAME' => $config->dbName, 'VIUNINVOICED' => false, 'VENDID' => $vendorID);
			$session->loc = $config->pages->vendorinfo. "$vendorID/";
			break;
		case 'vi-24monthsummary':
			$data = array('DBNAME' => $config->dbName, 'VIMONTHSUM' => false, 'VENDID' => $vendorID);
			if (!empty($input->post->shipfromID)) {
				$data['SHIPID'] = $input->post->text('shipfromID');
			}
			$session->loc = $config->pages->vendorinfo. "$vendorID/";
			break;
		case 'vi-docview':
			$data = array('DBNAME' => $config->dbName, 'VIDOCVIEW' => false, 'FLD1CD' => 'VI', 'FLD1DATA' => $vendorID);
			$session->loc = $config->pages->vendorinfo. "$vendorID/";
			break;
	}

	writedplusfile($data, $filename);
	header("location: /cgi-bin/" . $config->cgi . "?fname=" . $filename);
 	exit;
