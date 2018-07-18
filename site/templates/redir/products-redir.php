<?php
	/**
	* PRODUCT REDIRECT
	* @param string $action
	*
	*
	*/
	$custID = $shipID = '';
    $action = ($input->post->action ? $input->post->text('action') : $input->get->text('action'));
	$itemID = ($input->post->itemID ? $input->post->text('itemID') : $input->get->text('itemID'));
	$filename = session_id();
	
	/**
	* PRODUCT REDIRECT
	*
	*
	*
	*
	* switch ($action) {
	*	case 'item-search':
	*		DBNAME=$config->DBNAME
	*		ITNOSRCH=$query
	*		CUSTID=$custID
	*		break;
	*	case 'ii-select':
	*		DBNAME=$config->DBNAME
	*		IISELECT
	*		ITEMID=$itemID
	*		CUSTID=$custID **OPTIONAL
	*		SHIPID=$shipID **OPTIONAL
	*		break;
	*	case 'item-info':
	*		DBNAME=$config->DBNAME
	*		ITNOSRCH=$query
	*		CUSTID=$custID
	*		break;
	*	case 'get-item-price':
	*		DBNAME=$config->DBNAME
	*		IIPRICING
	*		ITEMID=$itemID
	*		CUSTID=$custID
	*		break;
	*	case 'ii-pricing':
	*		DBNAME=$config->DBNAME
	*		IIPRICE n2zz725p
	*		ITEMID=$itemID
	*		CUSTID=$custID **OPTIONAL
	*		SHIPID=$shipID **OPTIONAL
	*		break;
	*	case 'ii-costing':
	*		DBNAME=$config->DBNAME
	*		IICOST n2zz721p
	*		ITEMID=$itemID
	*		break;
	*	case 'ii-purchase-order':
	*		DBNAME=$config->DBNAME
	*		IIPURCHORDR n2zz708p
	*		ITEMID=$itemID
	*		break;
	*	case 'ii-quotes':
	*		DBNAME=$config->DBNAME
	*		IIQUOTE n2zz716p
	*		ITEMID=$itemID
	*		break;
	*	case 'ii-purchase-history':
	*		DBNAME=$config->DBNAME
	*		IIPURCHHIST n2zz709p
	*		ITEMID=$itemID
	*		break;
	*	case 'ii-where-used':
	*		DBNAME=$config->DBNAME
	*		IIWHEREUSED n2zz717p
	*		ITEMID=$itemID
	*		break;
	*	case 'ii-kit':
	*		DBNAME=$config->DBNAME
	*		IIKIT n2zz718p
	*		ITEMID=$itemID
	*		QTYNEEDED=$qty
	*		break;
	*	case 'ii-item-bom':
	*		DBNAME=$config->DBNAME
	*		IIBOMSINGLE|IIBOMCONS
	*		ITEMID=$itemID
	*		QTYNEEDED=$qty
	*		break;
	*	case 'ii-usage':
	*		DBNAME=$config->DBNAME
	*		IIUSAGE
	*		ITEMID=$itemID
	*		break;
	*	case 'ii-notes':
	*		DBNAME=$config->DBNAME
	*		IINOTES
	*		ITEMID=$itemID
	*		break;
	*	case 'ii-misc':
	*		DBNAME=$config->DBNAME
	*		IIMISC
	*		ITEMID=$itemID
	*		break;
	*	case 'ii-general':
	*		//TODO replace ii-usage, ii-notes, ii-misc
	*		break;
	*	case 'ii-activity':
	*		DBNAME=$config->DBNAME
	*		IIACTIVITY n2zz711p
	*		ITEMID=$itemID
	*		DATE=$date
	*		break;
	*	case 'ii-requirements':
	*		DBNAME=$config->DBNAME
	*		IIREQUIRE n2zz714p
	*		ITEMID=$itemID
	*		WHSE=$whse
	*		REQAVL=REQ|AVL
	*		break;
	*	case 'ii-lot-serial':
	*		DBNAME=$config->DBNAME
	*		IILOTSER n2zz712p
	*		ITEMID=$itemID
	*		break;
	*	case 'ii-sales-orders':
	*		DBNAME=$config->DBNAME
	*		IISALESORDR n2zz706p
	*		ITEMID=$itemID
	*		break;
	*	case 'ii-sales-history':
	*		DBNAME=$config->DBNAME
	*		IISALESHIST n2zz705p
	*		ITEMID=$itemID
	*		CUSTID=$custID **OPTIONAL
	*		SHIPID=$shipID **OPTIONAL
	*		DATE=$date
	*		break;
	*	case 'ii-stock':
	*		DBNAME=$config->DBNAME
	*		IISTKBYWHSE n2zz707p
	*		ITEMID=$itemID
	*		break;
	*	case 'ii-substitutes':
	*		DBNAME=$config->DBNAME
	*		IISUB n2zz713p
	*		ITEMID=$itemID
	*		break;
	*	case 'ii-documents':
	*		DBNAME=$config->DBNAME
	*		DOCVIEW n2zz735p
	*		FLD1CD=IT
	*		FLD1DATA=$itemID
	*		FLD21DESC=$desc
	*		break;
	*	case 'ii-uments':
	*		DBNAME=$config->DBNAME
	*		DOCVIEW
	*		FLD1CD=SO
	*		FLD1DATA=$ordn
	*		FLD2CD=IT
	*		FLD2DATA=$itemID
	*		break;
	* }
	*
	**/
	
	
    switch ($action) {
        case 'item-search':
            $q = ($input->post->q ? $input->post->text('q') : $input->get->text('q'));
			$custID = ($input->post->custID ? $input->post->text('custID') : $input->get->text('custID'));
			if (empty($custID)) { $custID == $config->defaultweb; }
			$data = array('DBNAME' => $config->dbName, 'ITNOSRCH' => strtoupper($q), 'CUSTID' => $custID);
            $session->loc = $config->page->index;
            break;
		case 'ii-select':
			if ($session->iidate) { $session->remove('iidate'); }
			$data = array('DBNAME' => $config->dbName, 'IISELECT' => false, 'ITEMID' => $itemID);
			$session->loc = $config->pages->iteminfo."?itemID=".urlencode($itemID);
            if ($input->post->custID) { $custID = $input->post->custID; } else { $custID = $input->get->text('custID'); }
            if ($input->post->shipID) { $shipID = $input->post->shipID; } else { $shipID = $input->get->text('shipID'); }
            if ($custID != '') {$data['CUSTID'] = $custID; $session->loc .= "&custID=".urlencode($custID); }
			if ($shipID != '') {$data['SHIPID'] = $shipID; $session->loc .= "&shipID=".urlencode($shipID); }
            break;
        case 'item-info':
            $q = ($input->post->q ? $input->post->text('q') : $input->get->text('q'));
			$custID = ($input->post->custID ? $input->post->text('custID') : $input->get->text('custID'));
			if (empty($custID)) { $custID == $config->defaultweb; }
			$data = array('DBNAME' => $config->dbName, 'ITNOSRCH' => $q, 'ITEMID' => $itemID, 'CUSTID' => $custID);
            $session->loc = $config->page->index;
            break;
        case 'get-item-price':
			$custID = ($input->post->custID ? $input->post->text('custID') : $input->get->text('custID'));
			if (empty($custID)) { $custID == $config->defaultweb; }
			$data = array('DBNAME' => $config->dbName, 'IIPRICING' => false, 'ITEMID' => $itemID, 'CUSTID' => $custID);
            $session->loc = $config->page->index;
            break;
		case 'ii-pricing': //II INFORMATION
			$data = array('DBNAME' => $config->dbName, 'IIPRICE' => false, 'ITEMID' => $itemID);
			$custID = ($input->post->custID ? $input->post->text('custID') : $input->get->text('custID'));
			$shipID = ($input->post->shipID ? $input->post->text('shipID') : $input->get->text('shipID'));
			if (!empty($custID))  {$data['CUSTID'] = $custID; } if (!empty($shipID)) {$data['SHIPID'] = $shipID; }
			$session->loc = $config->page->index;
            break;
		case 'ii-costing':
			$data = array('DBNAME' => $config->dbName, 'IICOST' => false, 'ITEMID' => $itemID);
            $session->loc = $config->page->index;
            break;
		case 'ii-purchase-order':
			$data = array('DBNAME' => $config->dbName, 'IIPURCHORDR' => false, 'ITEMID' => $itemID);
			$session->loc = $config->page->index;
            break;
		case 'ii-quotes':
			$data = array('DBNAME' => $config->dbName, 'IIQUOTE' => false, 'ITEMID' => $itemID);
			$custID = ($input->post->custID ? $input->post->text('custID') : $input->get->text('custID'));
			if (!empty($custID))  {$data['CUSTID'] = $custID; }
            $session->loc = $config->page->index;
            break;
		case 'ii-purchase-history':
			$data = array('DBNAME' => $config->dbName, 'IIPURCHHIST' => false, 'ITEMID' => $itemID);
			$session->loc = $config->page->index;
            break;
		case 'ii-where-used':
			$data = array('DBNAME' => $config->dbName, 'IIWHEREUSED' => false, 'ITEMID' => $itemID);
            $session->loc = $config->page->index;
            break;
		case 'ii-kit':
			$qty = ($input->post->qty ? $input->post->text('qty') : $input->get->text('qty'));
			$data = array('DBNAME' => $config->dbName, 'IIKIT' => false, 'ITEMID' => $itemID, 'QTYNEEDED' => $qty);
            $session->loc = $config->page->index;
            break;
		case 'ii-item-bom':
            $qty = ($input->post->qty ? $input->post->text('qty') : $input->get->text('qty'));
            $bom = ($input->post->bom ? $input->post->text('bom') : $input->get->text('bom'));
            if ($bom == 'single') {
				$data = array('DBNAME' => $config->dbName, 'IIBOMSINGLE' => false, 'ITEMID' => $itemID, 'QTYNEEDED' => $qty);
            } elseif ($bom == 'consolidated') {
				$data = array('DBNAME' => $config->dbName, 'IIBOMCONS' => false, 'ITEMID' => $itemID, 'QTYNEEDED' => $qty);
            }
            $session->loc = $config->page->index;
            break;
		case 'ii-usage':
			$data = array('DBNAME' => $config->dbName, 'IIUSAGE' => false, 'ITEMID' => $itemID);
            $session->loc = $config->page->index;
            break;
        case 'ii-notes':
			$data = array('DBNAME' => $config->dbName, 'IINOTES' => false, 'ITEMID' => $itemID);
            $session->loc = $config->page->index;
            break;
		case 'ii-misc':
			$data = array('DBNAME' => $config->dbName, 'IIMISC' => false, 'ITEMID' => $itemID);
            $session->loc = $config->page->index;
            break;
		case 'ii-general':
			//TODO replace ii-usage, ii-notes, ii-misc
			break;
		case 'ii-activity':
            $custID = $shipID = $date = '';
			$data = array('DBNAME' => $config->dbName, 'IIACTIVITY' => false, 'ITEMID' => $itemID);
            $date = ($input->post->date ? $input->post->text('date') : $input->get->text('date'));
            if (!empty($date)) {$data['DATE'] = date('Ymd', strtotime($date)); }
			$session->loc = $config->page->index;
            break;
		case 'ii-requirements':
            $whse = ($input->post->whse ? $input->post->text('whse') : $input->get->text('whse'));
            $screentype = ($input->post->screentype ? $input->post->text('screentype') : $input->get->text('screentype'));
            //screen type would be REQ or AVL
			$data = array('DBNAME' => $config->dbName, 'IIREQUIRE' => false, 'ITEMID' => $itemID, 'WHSE' => $whse, 'REQAVL' => $screentype);
            $session->loc = $config->page->index;
            break;
		case 'ii-lot-serial':
			$data = array('DBNAME' => $config->dbName, 'IILOTSER' => false, 'ITEMID' => $itemID);
            $session->loc = $config->page->index;
            break;
		case 'ii-sales-orders':
			$data = array('DBNAME' => $config->dbName, 'IISALESORDR' => false, 'ITEMID' => $itemID);
			$session->loc = $config->page->index;
            break;
		case 'ii-sales-history':
            $date = '';
			$data = array('DBNAME' => $config->dbName, 'IISALESHIST' => false, 'ITEMID' => $itemID);
			$custID = ($input->post->custID ? $input->post->text('custID') : $input->get->text('custID'));
			$shipID = ($input->post->shipID ? $input->post->text('shipID') : $input->get->text('shipID'));
			$date = ($input->post->date ? $input->post->text('date') : $input->get->text('date'));
            if (!empty($custID)) {$data['CUSTID'] = $custID; } if (!empty($shipID)) {$data['SHIPID'] = $shipID; }
            if (!empty($date)) { $data['DATE'] = date('Ymd', strtotime($date)); }
            $session->loc = $config->page->index;
            break;
       case 'ii-stock':
			$data = array('DBNAME' => $config->dbName, 'IISTKBYWHSE' => false, 'ITEMID' => $itemID);
			$session->loc = $config->page->index;
            break;
        case 'ii-substitutes':
			$data = array('DBNAME' => $config->dbName, 'IISUB' => false, 'ITEMID' => $itemID);
            $session->loc = $config->page->index;
            break;
		case 'ii-documents':
			$desc = XRefItem::get_itemdescription($itemID);
			$session->sql = XRefItem::get_itemdescription($itemID);
			$data = array('DBNAME' => $config->dbName, 'DOCVIEW' => false, 'FLD1CD' => 'IT', 'FLD1DATA' => $itemID, 'FLD1DESC' => $desc);
            $session->loc = $config->page->index;
            break;
		case 'ii-order-documents':
			$ordn = $input->get->text('ordn');
			$type = $input->get->text('type');
			$desc = XRefItem::get_itemdescription($itemID);
			$data = array('DBNAME' => $config->dbName, 'DOCVIEW' => false, 'FLD1CD' => $config->documentstoragetypes[$type], 'FLD1DATA' => $ordn, 'FLD2CD' => 'IT', 'FLD2DATA' => $itemID);
            $session->loc = $config->page->index;
            break;
		case 'ii-order-documents':
			$qnbr = $input->get->text('qnbr');
			$type = $input->get->text('type');
			$desc = XrefItem::get_itemdescription($itemID);
			$data = array('DBNAME' => $config->dbName, 'DOCVIEW' => false, 'FLD1CD' => $config->documentstoragetypes[$type], 'FLD1DATA' => $qnbr, 'FLD2CD' => 'IT', 'FLD2DATA' => $itemID);
            $session->loc = $config->page->index;
            break;
    }

    writedplusfile($data, $filename);
	header("location: /cgi-bin/" . $config->cgi . "?fname=" . $filename);
	exit;
