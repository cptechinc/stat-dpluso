<?php
	/**
	* CART REDIRECT
	*  @param string $action
	*
	*/

	$custID = $shipID = '';
	if ($input->post->action) {
		$action = $input->post->text('action');
		$itemID = $input->post->text('itemID');
		$qty = $input->post->text('qty');
	} else {
		$action = $input->get->text('action');
		$itemID = $input->get->text('itemID');
		$qty = $input->get->text('qty');
	}

	if (empty($qty)) {$qty = "1"; }

	$custID = (!empty($input->post->custID) ? $input->post->text('custID') : $input->get->text('custID'));
	$shipID = (!empty($input->post->shipID) ? $input->post->text('shipID') : $input->get->text('shipID'));
	if (!empty($custID)) {$session->custID = $custID;}
	if (!empty($shipID)) {$session->shipID = $shipID;}

	$filename = session_id();

	/**
	* CART REDIRECT
	*
	*
	*
	*
	* switch ($action) {
	*	case 'add-to-cart':
	*		DBNAME=$config->DBNAME
	*		CARTDET
	*		ITEMID=$itemID
	*		CUSTID=$custID
	*		SHIPTOID=$shipID
	*		WHSE=$whse  **OPTIONAL
	*		break;
	*	case 'reorder':
	*		DBNAME=$config->DBNAME
	*		CARTDET
	*		ITEMID=$itemID
	*		CUSTID=$custID
	*		SHIPTOID=$shipID
	*		WHSE=$whse  **OPTIONAL
	*		break;
	*	case 'add-non-stock-to-cart':
	*		DBNAME=$config->DBNAME
	*		CARTDET
	*		ITEMID=N
	*		QTY=$qty
	*		CUSTID=$custID
	*		break;
	*	case 'add-multiple-items':
	*		DBNAME=$config->DBNAME
	*		CARTADDMULTIPLE
	*		CUSTID=$custID
	*		ITEMID=$custID   QTY=$qty  **REPEAT
	*		break;
	*	case 'update-line':
	*		DBNAME=$config->DBNAME
	*		CARTDET
	*		LINENO=$linenbr
	*		CUSTID=$custID
	*		SHIPTOID=$shipID
	*		break;
	*	case 'remove-line':
	*		DBNAME=$config->DBNAME
	*		CARTDET
	*		LINENO=$linenbr
	*		CUSTID=$custID
	*		SHIPTOID=$shipID
	*		break;
	*	case 'create-sales-order':
	*		DBNAME=$config->DBNAME
	*		CREATESO
	*		break;
	*	case 'create-quote':
	*		DBNAME=$config->DBNAME
	*		CREATEQT
	*		break;
	* }
	*
	**/

    switch ($action) {
        case 'add-to-cart':
			$data = array('DBNAME' => $config->dbName, 'CARTDET' => false, 'ITEMID' => $itemID, 'QTY' => $qty);
			if ($custID == '') {$custID = $config->defaultweb;}
			$data['CUSTID'] = $custID; if ($shipID != '') {$data['SHIPTOID'] = $shipID; }
			if ($input->post->whse) { if ($input->post->whse != '') { $data['WHSE'] = $input->post->whse; } }
			$session->data = $data;
            $session->addtocart = 'You added ' . $qty . ' of ' . $itemID . ' to your cart';
            $session->loc = $input->post->page;
            break;
		case 'reorder':
			$data = array('DBNAME' => $config->dbName, 'CARTDET' => false, 'ITEMID' => $itemID, 'QTY' => $qty);
			if ($custID == '') {$custID = $config->defaultweb;}
			$data['CUSTID'] = $custID; if ($shipID != '') {$data['SHIPTOID'] = $shipID; }
			if ($input->post->whse) { if ($input->post->whse != '') { $data['WHSE'] = $input->post->whse; } }
            $session->addtocart = 'You added ' . $qty . ' of ' . $itemID . ' to your cart';
            $session->loc = $input->post->page;
			break;
		case 'add-nonstock-item':
			insertcartline(session_id(), '0', false);
			$cartdetail = getcartline(session_id(), '0', false);
			$cartdetail['orderno'] = session_id();
			$cartdetail['recno'] = '0';
			$cartdetail['price'] = $input->post->text('price');
			$cartdetail['desc1'] = $input->post->text('desc1');
			$cartdetail['desc2'] = $input->post->text('desc2');
			$cartdetail['vendorid'] = $input->post->text('vendorID');
			$cartdetail['shipfromid'] = $input->post->text('shipfromid');
			$cartdetail['vendoritemid'] = $input->post->text('itemID');
			$cartdetail['nsitemgroup'] = $input->post->text('group');
			$cartdetail['ponbr'] = $input->post->text('ponbr');
			$cartdetail['poref'] = $input->post->text('poref');
			$cartdetail['uom'] = $input->post->text('uofm');
			$cartdetail['spcord'] = 'S';
			$session->sql = edit_cartline(session_id(), $cartdetail, false);
			$data = array('DBNAME' => $config->dbName, 'CARTDET' => false, 'LINENO' => '0', 'ITEMID' => 'N', 'QTY' => $qty, 'CUSTID' => $custID);
			$session->loc = $config->pages->cart;
			break;
		case 'add-multiple-items':
			$itemids = $input->post->itemID;
			$qtys = $input->post->qty;
			$data = array("DBNAME=$config->dbName", 'CARTADDMULTIPLE', "CUSTID=$custID");
			$data = writedataformultitems($data, $itemids, $qtys);
            $session->addtocart = sizeof($itemIDs);
            $session->loc = $config->pages->cart;
			break;
		case 'update-line':
			$linenbr = $input->post->text('linenbr');
			$cartdetail = getcartline(session_id(), $linenbr, false);
			$cartdetail['price'] = $input->post->text('price');
			$cartdetail['discpct'] =  $input->post->text('discount');
			$cartdetail['qtyordered'] = $qty;
			$cartdetail['rshipdate'] = $input->post->text('rqst-date');
			$cartdetail['whse'] = $input->post->text('whse');
			$cartdetail['spcord'] = $input->post->text('specialorder');
			$cartdetail['linenbr'] = $input->post->text('linenbr');

			$cartdetail['spcord'] = $input->post->text('specialorder');
			$cartdetail['vendorid'] = $input->post->text('vendorID');
			$cartdetail['shipfromid'] = $input->post->text('shipfromid');
			$cartdetail['vendoritemid'] = $input->post->text('itemID');
			$cartdetail['nsitemgroup'] = $input->post->text('group');
			$cartdetail['ponbr'] = $input->post->text('ponbr');
			$cartdetail['poref'] = $input->post->text('poref');
			$cartdetail['uom'] = $input->post->text('uofm');

			$session->sql = edit_cartline(session_id(), $cartdetail, false);
			$session->loc = $input->post->text('page');
			$data = array('DBNAME' => $config->dbName, 'CARTDET' => false, 'LINENO' => $input->post->linenbr);

			if ($custID == '') {$custID = $config->defaultweb;}
			$data['CUSTID'] = $custID; if ($shipID != '') {$data['SHIPTOID'] = $shipID; }
			$session->loc = $config->pages->cart;
			break;
		case 'remove-line':
			$linenbr = $input->post->text('linenbr');
			$cartdetail = getcartline(session_id(), $linenbr, false);
			$cartdetail['qtyordered'] = '0';
			$session->sql = edit_cartline(session_id(), $cartdetail, false);
			$session->loc = $config->pages->cart;
			$custID = getcartcustomer(session_id(), false);
			$data = array('DBNAME' => $config->dbName, 'CARTDET' => false, 'LINENO' => $input->post->linenbr, 'QTY' => '0');

			if ($custID == '') {$custID = $config->defaultweb;}
			$data['CUSTID'] = $custID; if ($shipID != '') {$data['SHIPTOID'] = $shipID; }
			break;
        case 'create-sales-order':
			$data = array('DBNAME' => $config->dbName, 'CREATESO' => false);
           	$session->loc = $config->pages->orders . "redir/?action=edit-new-order";
            break;
		case 'create-quote':
			$data = array('DBNAME' => $config->dbName, 'CREATEQT' => false);
           	$session->loc = $config->pages->quotes . "redir/?action=edit-new-quote";
            break;
	}

	writedplusfile($data, $filename);
	header("location: /cgi-bin/" . $config->cgi . "?fname=" . $filename);
 	exit;
