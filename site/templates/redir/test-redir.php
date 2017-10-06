<?php
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

	if ($qty == '' || $qty == false) {$qty = "1"; }
	$filename = session_id();

	/**
	* CART REDIRECT
	* @param string $action
	*
	*
	*
	* switch ($action) {
	*	case 'cart-add-nonstock:
	*		DBNAME=$config->DBNAME
	*		CARTDET
	*		ITEMID=N
	*		QTY=$qty
	*		CUSTID=$custID
	*		break;
	* }
	*
	**/
    switch ($action) {
		case 'cart-add-nonstock':
			insertcartline(session_id(), '0', false);
			$cartdetail = getcartline(session_id(), '0', false);
			$cartdetail['orderno'] = session_id();
			$cartdetail['recno'] = '0';
			$cartdetail['price'] = '18.88';
			$cartdetail['desc1'] = 'Desc 1';
			$cartdetail['desc2'] = 'Desc 2';
			$cartdetail['vendorid'] = 'ABRTEC';
			$cartdetail['shipfromid'] = '';
			$cartdetail['vendoritemid'] = '8398';
			$cartdetail['nsitemgroup'] = '110';
			$cartdetail['ponbr'] = '98';
			$cartdetail['poref'] = 'REF';
			$cartdetail['uom'] = 'EA';
			$cartdetail['spcord'] = 'S';
			$session->sql = edit_cartline(session_id(), $cartdetail, false);
			$data = array('DBNAME' => $config->dbName, 'CARTDET' => false, 'LINENO' => '0', 'ITEMID' => 'N', 'QTY' => '5', 'CUSTID' => 'JAMES');
			$session->loc = $config->pages->cart;
			break;
		case 'order-add-nonstock':
			$ordn = $input->get->text('ordn');
			$custID = get_custid_from_order(session_id(), $ordn);
			$qty = '5';
			insertorderline(session_id(), $ordn, '0', false);
			$orderdetail = getorderlinedetail(session_id(), $ordn, '0', false);
			$orderdetail['recno'] = '0';
			$orderdetail['price'] = '18.88';
			$orderdetail['desc1'] = 'Desc 1';
			$orderdetail['desc2'] = 'Desc 2';
			$orderdetail['vendorid'] = 'ABRTEC';
			$orderdetail['shipfromid'] = '';
			$orderdetail['vendoritemid'] = '8398';
			$orderdetail['nsitemgroup'] = '110';
			$orderdetail['ponbr'] = '98';
			$orderdetail['poref'] = 'REF';
			$orderdetail['uom'] = 'EA';
			$orderdetail['spcord'] = 'S';
			$orderdetail['qty'] = $qty;
			$orderdetail['spcord'] = 'S';
			$session->sql = edit_orderline(session_id(), $ordn, $orderdetail, false);
			$data = array('DBNAME' => $config->dbName, 'SALEDET' => false, 'ORDERNO' => $ordn, 'LINENO' => '0', 'ITEMID' => 'N', 'QTY' => $qty, 'CUSTID' => $custID);
			if ($input->post->page) {
				$session->loc = $input->post->text('page');
			} else {
				$session->loc = $config->pages->edit."order/?ordn=".$ordn;
			}
			$session->editdetail = true;
			break;
		case 'quote-add-nonstock':
			$qnbr = $input->get->text('qnbr');
			$qty = '2';
			$custID = getquotecustomer(session_id(), $qnbr, false);
			insertquoteline(session_id(), $qnbr, '0', false);
			$quotedetail = getquotelinedetail(session_id(), $qnbr, '0', false);
			$quotedetail['quotenbr'] = $qnbr;
			$quotedetail['recno'] = '0';
			$quotedetail['ordrprice'] = '18.88';
			$quotedetail['desc1'] = 'Desc 1';
			$quotedetail['desc2'] = 'Desc 2';
			$quotedetail['vendorid'] = 'ABRTEC';
			$quotedetail['shipfromid'] = '';
			$quotedetail['vendoritemid'] = '8398';
			$quotedetail['nsitemgroup'] = '110';
			//$quotedetail['ponbr'] = '98';
			//$quotedetail['poref'] = 'REF';
			$quotedetail['uom'] = 'EA';
			$quotedetail['spcord'] = 'S';
			$quotedetail['qty'] = $qty;
			$quotedetail['spcord'] = 'S';
			$session->sql = edit_quoteline(session_id(), $qnbr, $quotedetail, false);

			$data = array('DBNAME' => $config->dbName, 'UPDATEQUOTEDETAIL' => false, 'QUOTENO' => $qnbr, 'LINENO' => '0', 'ITEMID' => 'N', 'QTY' => $qty);
			if ($input->post->page) {
				$session->loc = $input->post->text('page');
			} else {
				$session->loc = $config->pages->edit."quote/?qnbr=".$qnbr;
			}
			$session->editdetail = true;
			break;
	}
	writedplusfile($data, $filename);
	header("location: /cgi-bin/" . $config->cgi . "?fname=" . $filename);
 	exit;
