<?php
	/**
	* ORDERS REDIRECT
	* @param string $action
	*
	* */

	$action = ($input->post->action ? $input->post->text('action') : $input->get->text('action'));

	// USED FOR MAINLY ORDER LISTING FUNCTIONS
	$pagenumber = (!empty($input->get->page) ? $input->get->int('page') : 1);
	$sortaddon = (!empty($input->get->orderby) ? '&orderby=' . $input->get->text('orderby') : '');

	$linkaddon = $sortaddon;
	$session->{'from-redirect'} = $page->url;
	$session->remove('order-search');

	$filename = session_id();

	/**
	* ORDERS REDIRECT
	*
	*
	*
	*
	* switch ($action) {
	*	case 'load-cust-orders':
	*		DBNAME=$config->DBNAME
	*		ORDRHED
	*		CUSTID=$custID
	*		TYPE=O  ** OPEN ORDERS
	*		break;
	*	case 'load-orders'
	*		DBNAME=$config->DBNAME
	*		REPORDRHED
	*		TYPE=O
	*		break;
	*	case 'get-order-details':
	*		DBNAME=$config->DBNAME
	*		ORDRDET=$ordn
	*		CUSTID=$custID
	* 		break;
	* 	case 'get-order-tracking':
	*		DBNAME=$config->DBNAME
	*		ORDTRK=$ordn
	*		CUSTID=$custID
	*		break;
	*	case 'get-order-documents':
	*		DBNAME=$config->DBNAME
	*		ORDDOCS=$ordn
	*		CUSTID=$custID
	*		break;
	* 	case 'search-cust-orders':
	* 		DBNAME=$config->DBNAME
	*		ORDRHED
	*		CUSTID=$custID
	*		ALL|PON|SON|ITM|TRK=$searchterm
	*		TYPE=O|AS|B|S **OPEN ORDERS|OPEN ORDERS|BOOKED ORDER|SHIPPED
	* 		break;
	* 	case 'edit-new-order':
	*		DBNAME=$config->DBNAME
	*		ORDRDET=$ordn
	*		CUSTID=$custID
	*		LOCK
	* 		break;
	* 	case 'update-orderhead'
	* 		DBNAME=$config->DBNAME
	*		SALESHEAD
	*		ORDERNO=$ordn
	* 		break;
	* 	case 'add-to-order':
	* 		DBNAME=$config->DBNAME
	* 		SALEDET
	*		ORDERNO=$ordn
	*		ITEMID=$itemID
	*		QTY=$qty
	* 		break;
	* 	case 'add-multiple-items':
	*		DBNAME=$config->DBNAME
	*		ORDERADDMULTIPLE
	*		ORDERNO=$ordn
	*		ITEMID=$custID   QTY=$qty  ** REPEAT
	*		break;
	*	case 'add-nonstock-item':
	*		DBNAME=$config->DBNAME
	*		SALEDET
	*		ORDERNO=$ordn
	*		ITEMID=N
	*		QTY=$qty
	*		CUSTID=$custID
	* 		break;
	* 	case 'update-line':
	*		DBNAME=$config->DBNAME
	*		SALEDET
	*		ORDERNO=$ordn
	*		LINENO=$linenbr
	* 		break;
	* 	case 'remove-line':
	* 		DBNAME=$config->DBNAME
	*		SALEDET
	*		ORDERNO=$ordn
	*		LINENO=$linenbr
	* 		break;
	*	case 'unlock-order':
	*		DBNAME=$config->DBNAME
	*		UNLOCK
	*		ORDERNO=$ordn
	* 		break;
	* }
	*
	**/

	switch ($action) {
		case 'load-cust-orders':
			$session->remove('ordersearch');
			$custID = $input->get->text('custID');
			$data = array('DBNAME' => $config->dbName, 'ORDRHED' => false, 'CUSTID' => $custID, 'TYPE' => 'O');
			$session->loc = $config->pages->ajax."load/orders/cust/".urlencode($custID)."/"."?ordn=".$linkaddon;
			$session->{'orders-loaded-for'} = $custID;
			$session->{'orders-updated'} = date('m/d/Y h:i A');
			break;
		case 'load-orders':
			$data = array('DBNAME' => $config->dbName, 'REPORDRHED' => false, 'TYPE' => 'O');
			$session->loc = $config->pages->ajax."load/orders/salesrep/".urlencode($custID)."/?ordn=".$linkaddon."";
			$session->{'orders-loaded-for'} = $user->loginid;
			$session->{'orders-updated'} = date('m/d/Y h:i A');
			break;
		case 'get-order-details':
			$ordn = $input->get->text('ordn');
			$custID = get_custidfromorder(session_id(), $ordn);
			$data = array('DBNAME' => $config->dbName, 'ORDRDET' => $ordn, 'CUSTID' => $custID);
			if ($input->get->lock) {
				$data['LOCK'] = false;
				$session->loc = $config->pages->editorder."?ordn=".$ordn;
			} elseif ($input->get->print) {
				$session->loc = $config->pages->print."order/?ordn=".$ordn;
			} elseif($input->get->custID) {
				$session->loc = paginate($config->pages->ajax."load/orders/cust/".urlencode($custID)."/?ordn=".$ordn.$linkaddon, $pagenumber, $custID, '');
			} else {
				$session->loc = paginate($config->pages->ajax."load/orders/salesrep/?ordn=".$ordn.$linkaddon, $pagenumber, "salesrep", '');
				if ($input->get->readonly) {$session->loc = $config->pages->editorder."?ordn=".$ordn; }
			}
			break;
		case 'get-order-tracking':
			$ordn = $input->get->text('ordn');
			$custID = get_custidfromorder(session_id(), $ordn);
			$data = array('DBNAME' => $config->dbName, 'ORDRTRK' => $ordn, 'CUSTID' => $custID);
			if ($input->get->ajax) {
				$session->loc = $config->pages->ajax."load/order/tracking/?ordn=".$ordn;
			} elseif($input->get->custID) {
				$session->loc = paginate($config->pages->ajax."load/orders/cust/".urlencode($custID)."/?ordn=".$ordn.$linkaddon."&show=tracking", $pagenumber, $custID, '');
			} elseif ($input->get->page == 'edit') {
				$session->loc = $config->pages->ajax.'load/order/tracking/?ordn='.$ordn;
			} else {
				$session->loc = paginate($config->pages->ajax."load/orders/salesrep/".urlencode($custID)."/?ordn=".$ordn.$linkaddon."&show=tracking", $pagenumber, $custID, '');
			}
			break;
		case 'get-order-documents':
			$ordn = $input->get->text('ordn');
			$custID = get_custidfromorder(session_id(), $ordn);

			if ($input->get->itemdoc) {
				if ($input->get->custID) {
					$session->loc = paginate($config->pages->ajax."load/orders/cust/".urlencode($custID)."/?ordn=".$ordn.$linkaddon."&show=documents&itemdoc=".$input->get->text('itemdoc'), $pagenumber, $custID, '');
				} elseif ($input->get->page == 'edit') {
					$session->loc = $config->pages->ajax.'load/order/documents/?ordn='.$ordn;
				} else {
					$session->loc = paginate($config->pages->ajax."load/orders/salesrep/?ordn=".$ordn.$linkaddon."&show=documents&itemdoc=".$input->get->text('itemdoc'), $pagenumber, "salesrep", '');
				}
			} else {
				if ($input->get->custID) {
					$custID = $input->get->text('custID');
					$session->loc = paginate($config->pages->ajax."load/orders/cust/".urlencode($custID)."/?ordn=".$ordn."&show=documents".$linkaddon, $pagenumber, $custID, '');
				} elseif ($input->get->page == 'edit') {
					$session->loc = $config->pages->ajax.'load/order/documents/?ordn='.$ordn;
				} else {
					$session->loc = paginate($config->pages->ajax."load/orders/salesrep/?ordn=".$ordn."&show=documents".$linkaddon, $pagenumber, "salesrep", '');
				}

			}
			$data = array('DBNAME' => $config->dbName, 'ORDDOCS' => $ordn, 'CUSTID' => $custID);
			break;
		case 'search-cust-orders':
			$session->remove('ordersearch');
			$orderstatus = $input->post->orderstatus;
			$searchtype = $input->post->searchtype;
			$searchterm = $input->post->q;
			$custID = $input->post->text('custID');
			$shipID = $input->post->text('shipID');

			//searchtype is the code the COBOL program looks for when scanning the file made. The code is based on SearchType
			//so if we are searching order# then the searchType will be ORDERNBR and the cobol program will know to fill our mysql based on ordernbr

			$data = array('DBNAME' => $config->dbName, 'ORDRHED' => false, 'CUSTID' => $custID, $searchtype => $searchterm);
			switch ($orderstatus) {
				case 'O':
					$os = 'Open Orders';
					$session->{'ordertype'} = 'O';
					break;
				case 'AS':
					$os = 'Open Orders';
					$session->{'ordertype'} = 'O';
					$orderStatus = 'O';
					break;
				case 'B':
					$os = 'Booked Orders';
					$session->{'ordertype'} = 'B';
					break;
				case 'S':
					$os = 'Shipped Orders';
					$session->{'ordertype'} = 'S';
					break;
				default:
					$os = 'Both Open and Shipped Orders';
					$session->{'ordertype'} = '';
			}
			$data['TYPE'] = $orderstatus;

			if ($searchterm == '' ) {
				$session->ordersearch = 'STUFF';
			} else {
				$session->ordersearch = "'" . $searchterm . "' in " . $os;
			}

			if (($input->post->text('date-from')) != "") {
				$datefrom = $input->post->text('date-from');
				$datethru = "";
				$searchvalu;
				if ($input->post->text('date-through') == "" || $input->post->text('date-through') == NULL) {
					$datethru = date('m/d/Y');
				} else {
					$datethru = $input->post->text('date-through');
				}
				if ($datefrom != date('m/d/Y') || $datethru != date('m/d/Y')) {
					if ($datefrom != "" || $datefrom != NULL) {
						$searchvalu = "Date Range: ".$datefrom.' - '.$datethru;
					}
					$data['DATEFROM'] = $datefrom;
					$data['DATETHRU'] = $datethru;
					$session->ordersearch =  $searchvalu . ' in '.$os;
				}
			}

			$session->loc = $config->pages->ajax."load/orders/cust/".urlencode($custID)."/";
			if ($shipID != '') {$session->loc .= "shipto-".urlencode($shipID)."/";}
			$session->loc .= "?ordn=".$linkaddon;
			$session->{'orders-loaded-for'} = $custID;
			$session->{'orders-updated'} = date('m/d/Y h:i A');
			break;
		case 'edit-new-order':
			if ($session->custID) { $custID = $session->custID; } else { $custID = $config->defaultweb; }
			$ordn = getcreatedordn(session_id(), false);
			$data = array('DBNAME' => $config->dbName, 'ORDRDET' => $ordn, 'CUSTID' => $custID, 'LOCK' => false);
			$session->loc = $config->pages->edit.'order/?ordn=' . $ordn;
			break;
		case 'update-orderhead':
			$ordn = $input->post->text("ordn");
			$order = SalesOrder::load(session_id(), $ordn);
			//$order = get_orderhead(session_id(), $ordn, false, false);
			$intl = $input->post->text("intl");
			$order->shiptoid = $input->post->text('shiptoid');
			$order->custname = $input->post->text('cust-name');
			$order->custpo = $input->post->text("custpo");
			$order->shipname = $input->post->text("shiptoname");
			$order->shipaddress = $input->post->text("shipto-address");
			$order->shipaddress2 = $input->post->text("shipto-address2");
			$order->shipcity = $input->post->text("shipto-city");
			$order->shipstate = $input->post->text("shipto-state");
			$order->shipzip = $input->post->text("shipto-zip");
			$order->contact = $input->post->text('contact');
			$order->phone = $input->post->text("contact-phone");
			$order->extension = $input->post->text("contact-extension");
			$order->faxnbr = $fax = $input->post->text("contact-fax");
			$order->email = $input->post->text("contact-email");
			$order->releasenbr = $input->post->text("release-number");
			$order->shipviacd = $input->post->text('shipvia');
			$order->rqstdate = $input->post->text("rqstdate");
			$order->shipcom = $input->post->text("shipcomplete");
			$order->billname = $input->post->text('cust-name');
			// $order->billaddress = $input->post->text('cust-address');
			// $order->billaddress2 = $input->post->text('cust-address2');
			// $order->billcity = $input->post->text('cust-city');
			// $order->billstate = $input->post->text('cust-state');
			// $order->billzip = $input->post->text('cust-zip');

			if ($intl == 'Y') {
				$order->phone = $input->post->text("office-accesscode") . $input->post->text("office-countrycode") . $input->post->text("intl-office");
				$order->extension = $input->post->text("intl-ofice-ext");
				$order->faxnbr = $input->post->text("fax-accesscode") . $input->post->text("fax-countrycode") . $input->post->text("intl-fax");
			} else {
				$order->phone = $input->post->text("contact-phone");
				$order->extension = $input->post->text("contact-extension");
				$order->faxnbr = $input->post->text("contact-fax");
			}
			$custID = get_custidfromorder(session_id(), $ordn);
			$session->sql = $order->update();
			$order->paymenttype = addslashes($input->post->text("paytype"));
			
			if ($paymenttype == 'cc') {
				$order->cardnumber = $input->post->text("ccno");
				$order->cardexpire = $input->post->text("xpd");
				$order->cardcode = $input->post->text("ccv");
			}
			
			$session->sql .= '<br>'. $order->update_payment();
			$data = array('DBNAME' => $config->dbName, 'SALESHEAD' => false, 'ORDERNO' => $ordn, 'CUSTID' => $custID);
			
			if ($input->post->exitorder) {
				$session->loc = $config->pages->orders."redir/?action=unlock-order&ordn=$ordn";
				$data['UNLOCK'] = false;
			} else {
				$session->loc = $config->pages->editorder."?ordn=$ordn";
			}
			break;
		case 'add-to-order':
			$itemID = $input->post->text('itemID');
			$qty = $input->post->text('qty'); if ($qty == '') {$qty = 1; }
			$ordn = $input->post->text('ordn');
			$custID = get_custidfromorder(session_id(), $ordn);
			$data = array('DBNAME' => $config->dbName, 'SALEDET' => false, 'ORDERNO' => $ordn, 'ITEMID' => $itemID, 'QTY' => $qty, 'CUSTID' => $custID);
			$session->loc = $input->post->page;
			break;
		case 'add-multiple-items':
			$ordn = $input->post->text('ordn');
			$itemids = $input->post->itemID;
			$qtys = $input->post->qty;
			$data = array("DBNAME=$config->dbName", 'ORDERADDMULTIPLE', "ORDERNO=$ordn");
			$data = writedataformultitems($data, $itemids, $qtys);
            $session->loc = $config->pages->edit."order/?ordn=".$ordn;
			break;
		case 'add-nonstock-item':
			$ordn = $input->post->text('ordn');
			$qty = $input->post->text('qty');
			insertorderline(session_id(), $ordn, '0', false);
			$orderdetail = getorderlinedetail(session_id(), $ordn, '0', false);
			$orderdetail['orderno'] = $ordn;
			$orderdetail['recno'] = '0';
			$orderdetail['price'] = $input->post->text('price');
			$orderdetail['qty'] = $qty;
			$orderdetail['desc1'] = $input->post->text('desc1');
			$orderdetail['desc2'] = $input->post->text('desc2');
			$orderdetail['vendorid'] = $input->post->text('vendorID');
			$orderdetail['shipfromid'] = $input->post->text('shipfromid');
			$orderdetail['vendoritemid'] = $input->post->text('itemID');
			$orderdetail['nsitemgroup'] = $input->post->text('itemgroup');
			$orderdetail['ponbr'] = $input->post->text('ponbr');
			$orderdetail['poref'] = $input->post->text('poref');
			$orderdetail['uom'] = $input->post->text('uofm');
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
		case 'update-line':
			$ordn = $input->post->text('ordn');
			$linenbr = $input->post->text('linenbr');
			$orderdetail = getorderlinedetail(session_id(), $ordn, $linenbr, false);
			$orderdetail['price'] = $input->post->text('price');
			$orderdetail['discpct'] =  $input->post->text('discount');
			$orderdetail['qtyordered'] = $input->post->text('qty');
			$orderdetail['rshipdate'] = $input->post->text('rqstdate');
			$orderdetail['whse'] = $input->post->text('whse');
			$orderdetail['linenbr'] = $input->post->text('linenbr');

			$orderdetail['spcord'] = $input->post->text('specialorder');
			$orderdetail['vendorid'] = $input->post->text('vendorID');
			$orderdetail['shipfromid'] = $input->post->text('shipfromid');
			$orderdetail['vendoritemid'] = $input->post->text('itemID');
			$orderdetail['nsitemgroup'] = $input->post->text('group');
			$orderdetail['ponbr'] = $input->post->text('ponbr');
			$orderdetail['poref'] = $input->post->text('poref');
			$orderdetail['uom'] = $input->post->text('uofm');

			if ($orderdetail['spcord'] != 'N') {
				$orderdetail['desc1'] = $input->post->text('desc1');
				$orderdetail['desc2'] = $input->post->text('desc2');
			}
			$custID = get_custidfromorder(session_id(), $ordn);
			$session->sql = edit_orderline(session_id(), $ordn, $orderdetail, false);
			$data = array('DBNAME' => $config->dbName, 'SALEDET' => false, 'ORDERNO' => $ordn, 'LINENO' => $linenbr, 'CUSTID' => $custID);
			
			if ($input->post->page) {
				$session->loc = $input->post->text('page');
			} else {
				$session->loc = $config->pages->edit."order/?ordn=".$ordn;
			}
			$session->editdetail = true;
			break;
		case 'remove-line':
			$ordn = $input->post->text('ordn');
			$linenbr = $input->post->text('linenbr');
			$orderdetail = getorderlinedetail(session_id(), $ordn, $linenbr, false);
			$orderdetail['qtyordered'] = '0';
			$orderdetail['linenbr'] = $input->post->text('linenbr');
			$session->sql = edit_orderline(session_id(), $ordn, $orderdetail, false);
			$session->editdetail = true;
			$custID = get_custidfromorder(session_id(), $ordn);
			$data = array('DBNAME' => $config->dbName, 'SALEDET' => false, 'ORDERNO' => $ordn, 'LINENO' => $linenbr, 'QTY' => '0', 'CUSTID' => $custID);
			if ($input->post->page) {
				$session->loc = $input->post->text('page');
			} else {
				$session->loc = $config->pages->edit."order/?ordn=".$ordn;
			}
			break;
		case 'unlock-order':
			$ordn = $input->get->text('ordn');
			$data = array('DBNAME' => $config->dbName, 'UNLOCK' => false, 'ORDERNO' => $ordn);
			$session->loc = $config->pages->confirmorder."?ordn=$ordn";
			break;
		default:
			$data = array('DBNAME' => $config->dbName, 'REPORDRHED' => false, 'TYPE' => 'O');
			$session->loc = $config->pages->ajax."load/orders/salesrep/".urlencode($custID)."/?ordn=".$linkaddon."";
			$session->{'orders-loaded-for'} = $user->loginid;
			$session->{'orders-updated'} = date('m/d/Y h:i A');
			break;
	}

	writedplusfile($data, $filename);
	header("location: /cgi-bin/" . $config->cgi . "?fname=" . $filename);
 	exit;
