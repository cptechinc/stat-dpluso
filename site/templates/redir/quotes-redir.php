<?php
	/**
	*  QUOTE REDIRECT
	* @param string $action
	*
	*/


	$action = ($input->post->action ? $input->post->text('action') : $input->get->text('action'));

	// USED FOR MAINLY ORDER LISTING FUNCTIONS
	$pagenumber = (!empty($input->get->page) ? $input->get->int('page') : 1);
	$sortaddon = (!empty($input->get->orderby) ? '&orderby=' . $input->get->text('orderby') : '');

	$linkaddon = $sortaddon;
	$session->{'from-redirect'} = $page->url;
	$session->remove('quote-search');
	$filename = session_id();

	//TODO merge get-quote-details and get-quote-details-print
	/**
	*  QUOTE REDIRECT
	*
	*
	*
	*
	* switch ($action) {
	* 	case 'load-quotes':
	*		DBNAME=$config->DBNAME
	*		LOADREPQUOTEHED
	*		TYPE=QUOTE
	*		break;
	* 	case 'load-cust-quotes':
	*		DBNAME=$config->DBNAME
	*		LOADCUSTQUOTEHEAD
	*		TYPE=QUOTE
	*		CUSTID=$custID
	*		break;
	*	case 'load-quote-details':
	*		DBNAME=$config->DBNAME
	*		LOADQUOTEDETAIL
	*		QUOTENO=$qnbr
	*		CUSTID=$custID
	*		break;
	*	case 'get-quote-details-print': // DEPRECATED 10/30/2017
	*		DBNAME=$config->DBNAME
	*		LOADQUOTEDETAIL
	*		QUOTENO=$qnbr
	*		CUSTID=$custID
	*		break;
	*	case 'edit-new-quote':
	*		DBNAME=$config->DBNAME
	*		EDITQUOTE=$qnbr
	*		QUOTENO=$qnbr
	*		break;
	*	case 'save-quotehead':
	*		DBNAME=$config->DBNAME
	*		UPDATEQUOTEHEAD
	*		QUOTENO=$qnbr
	*		CUSTID=$custID
	*		break;
	*	case 'add-to-quote':
	*		DBNAME=$config->DBNAME
	*		UPDATEQUOTEDETAIL
	*		QUOTENO=$qnbr
	*		ITEMID=$itemID
	*		QTY=$qty
	*		break;
	*	case 'add-multiple-items':
	*		DBNAME=$config->DBNAME
	*		ORDERADDMULTIPLE
	*		QUOTENO=$ordn
	*		ITEMID=$custID   QTY=$qty  **REPEAT
	*		break;
	*	case 'add-nonstock-item':
	*		DBNAME=$config->DBNAME
	*		UPDATEQUOTEDETAIL
	*		QUOTENO=$qnbr
	*		LINENO=0
	*		ITEMID=N
	*		QTY=$qty
	*		CUSTID=$custID
	* 		break;
	*	case 'update-line':
	*		DBNAME=$config->DBNAME
	*		UPDATEQUOTEDETAIL
	*		QUOTENO=$qnbr
	*		LINENO=$linenbr
	*		break;
	*	case 'remove-line':
	*		DBNAME=$config->DBNAME
	*		UPDATEQUOTEDETAIL
	*		QUOTENO=$qnbr
	*		LINENO=$linenbr
	*		QTY=0
	*		break;
	*	case 'remove-line-get' // SAME AS ABOVE
	*		break;
	*	case 'unlock-quote':
	*		UNLOCKING QUOTE
	*		break;
	*	case 'send-quote-to-order':
	*		DBNAME=$config->DBNAME
	*		QUOTETOORDER
	*		QUOTENO=$quotnbr
	*		LINENO = 'ALL'
	*		break;
	* }
	**/


	switch ($action) {
		case 'load-quotes':
			$data = array('DBNAME' => $config->dbName, 'LOADREPQUOTEHED' => false);
			$session->loc = $config->pages->ajax."load/quotes/salesrep/".urlencode($custID)."/?qnbr=".$linkaddon;
			$session->{'quotes-loaded-for'} = $user->loginid;
			$session->{'quotes-updated'} = date('m/d/Y h:i A');
			break;
		case 'load-cust-quotes':
			$custID = $input->get->text('custID');
			$data = array('DBNAME' => $config->dbName, 'LOADCUSTQUOTEHEAD' => false, 'TYPE' => 'QUOTE', 'CUSTID' => $custID);
			$session->loc = $config->pages->ajax."load/quotes/cust/".urlencode($custID)."/?qnbr=".$linkaddon;
			$session->{'quotes-loaded-for'} = $custID;
			$session->{'quotes-updated'} = date('m/d/Y h:i A');
			break;
		case 'load-quote-details':
			$qnbr = $input->get->text('qnbr');
			$custID = get_custidfromquote(session_id(), $qnbr, false);
			$data = array('DBNAME' => $config->dbName, 'LOADQUOTEDETAIL' => false, 'QUOTENO' => $qnbr, 'CUSTID' => $custID);
			
			if ($input->get->lock) {
				$session->loc = $config->pages->editquote."?qnbr=".$qnbr;
			} elseif ($input->get->print) {
				$session->loc = $config->pages->print."quote/?qnbr=".$qnbr;
			} else {
				if ($input->get->custID) {
					$session->loc = $config->pages->ajax."load/quotes/cust/".urlencode($custID)."/?qnbr=".$qnbr.$linkaddon;
				} else {
					$session->loc = $config->pages->ajax."load/quotes/salesrep/?qnbr=".$qnbr.$linkaddon;
				}
			}
			break;
		case 'get-quote-details-print': // DEPRECATED 10/30/2017
			$qnbr = $input->get->text('qnbr');
			$custID = get_custidfromquote(session_id(), $qnbr, false);
			$data = array('DBNAME' => $config->dbName, 'LOADQUOTEDETAIL' => false, 'QUOTENO' => $qnbr, 'CUSTID' => $custID);
			$session->loc = $config->pages->print."quote/?qnbr=".$qnbr;
			break;
		case 'edit-quote':
			$qnbr = $input->get->text('qnbr');
			$date = date('Ymd');
			$time = date('Hi');
			$custID = get_custidfromquote(session_id(), $qnbr, false);
			$data = array('DBNAME' => $config->dbName, 'EDITQUOTE' => false, 'QUOTENO' => $qnbr);
			$session->loc= $config->pages->editquote."?qnbr=".$qnbr;
			break;
		case 'edit-new-quote':
			$qnbr = getcreatedordn(session_id(), false);
			$date = date('Ymd');
			$time = date('Hi');
			$data = array('DBNAME' => $config->dbName, 'EDITQUOTE' => $qnbr, 'QUOTENO' => $qnbr);
			$session->loc = $config->pages->editquote."?qnbr=".$qnbr;
			break;
		case 'save-quotehead':
			$qnbr = $input->post->text('qnbr');
			$quote = get_quotehead(session_id(), $qnbr, false);
			$quote = Quote::load(session_id(), $qnbr);
			$quote->shiptoid = $input->post->text('shiptoid');
			$quote->stname = $input->post->text('shiptoname');
			$quote->stadr1 = $input->post->text('shipto-address');
			$quote->stadr2 = $input->post->text('shipto-address2');
			$quote->stcity = $input->post->text('shipto-city');
			$quote->ststate = $input->post->text('shipto-state');
			$quote->stzip = $input->post->text('shipto-zip');
			$quote->contact = $input->post->text('contact');
			$quote->emailadr = $input->post->text('contact-email');
			$quote->careof = $input->post->text('careof');
			$quote->revdate = $input->post->text('reviewdate');
			$quote->expdate = $input->post->text('expiredate');
			$quote->sviacode = $input->post->text('shipvia');
			$quote->deliverydesc = $input->post->text('delivery');
			$quote->custpo = $input->post->text('custpo');
			$quote->custref = $input->post->text('reference');
			$quote->telenbr = $input->post->text('contact-phone');
			$quote->faxnbr = $input->post->text('contact-fax');
			$haschanges = $quote->has_changes();
			$session->sql = $quote->update();
		
			$data = array('DBNAME' => $config->dbName, 'UPDATEQUOTEHEAD' => false, 'QUOTENO' => $qnbr);
			
			if ($input->post->exitquote) {
				$session->loc = $config->pages->edit."quote/confirm/?qnbr=".$qnbr.$linkaddon;
				
				if (!$haschanges) {
					$data = array('UNLOCKING QUOTE' => false);
				}
			} else {
				$session->loc = $config->pages->editquote."?qnbr=".$qnbr.$linkaddon;
			}
			break;
		case 'add-to-quote':
			$qnbr = $input->post->text('qnbr');
			$itemID = $input->post->text('itemID');
			$qty = $input->post->text('qty');
			$data = array('DBNAME' => $config->dbName, 'UPDATEQUOTEDETAIL' => false, 'QUOTENO' => $qnbr, 'ITEMID' => $itemID, 'QTY' => $qty);
			$session->editdetail = true;
			break;
		case 'add-multiple-items':
			$qnbr = $input->post->text('qnbr');
			$itemids = $input->post->itemID;
			$qtys = $input->post->qty;
			$data = array("DBNAME=$config->dbName", 'ORDERADDMULTIPLE', "QUOTENO=$qnbr");
			$data = writedataformultitems($data, $itemids, $qtys);
            $session->loc = $config->pages->edit."quote/?qnbr=".$qnbr;
			break;
		case 'add-nonstock-item':
			$qnbr = $input->post->text('qnbr');
			$qty = $input->post->text('qty');
			insertquoteline(session_id(), $qnbr, '0', false);
			$quotedetail = getquotelinedetail(session_id(), $qnbr, '0', false);
			$quotedetail['quotenbr'] = $qnbr;
			$quotedetail['recno'] = '0';
			$quotedetail['linenbr'] = '0';
			$quotedetail['ordrprice'] = $input->post->text('price');
			$quotedetail['qty'] = $qty;
			$quotedetail['desc1'] = $input->post->text('desc1');
			$quotedetail['desc2'] = $input->post->text('desc2');
			$quotedetail['vendorid'] = $input->post->text('vendorID');
			$quotedetail['shipfromid'] = $input->post->text('shipfromid');
			$quotedetail['vendoritemid'] = $input->post->text('itemID');
			$quotedetail['nsitemgroup'] = $input->post->text('itemgroup');
			//$quotedetail['ponbr'] = $input->post->text('ponbr');
			//$quotedetail['poref'] = $input->post->text('poref');
			$quotedetail['uom'] = $input->post->text('uofm');
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
		case 'update-line':
			if ($input->post) {
				$qnbr = $input->post->text('qnbr');
				$linenbr = $input->post->text('linenbr');
			} else {
				$qnbr = $input->get->text('qnbr');
				$linenbr = $input->get->text('linenbr');
			}

			$quotedetail = getquotelinedetail(session_id(), $qnbr, $linenbr, false);
			$quotedetail['quotprice'] = $input->post->text('price');
			$quotedetail['discpct'] =  $input->post->text('discount');
			$quotedetail['quotunit'] = $input->post->text('qty');
			$quotedetail['ordrqty'] = $input->post->text('qty');
			$quotedetail['rshipdate'] = $input->post->text('rqstdate');
			$quotedetail['whse'] = $input->post->text('whse');
			$quotedetail['linenbr'] = $input->post->text('linenbr');
			
			$quotedetail['spcord'] = $input->post->text('specialorder');
			$quotedetail['vendorid'] = $input->post->text('vendorID');
			$quotedetail['shipfromid'] = $input->post->text('shipfromid');
			$quotedetail['vendoritemid'] = $input->post->text('itemID');
			$quotedetail['nsitemgroup'] = $input->post->text('group');
			$quotedetail['ponbr'] = $input->post->text('ponbr');
			$quotedetail['poref'] = $input->post->text('poref');
			$quotedetail['uom'] = $input->post->text('uofm');

			if ($quotedetail['spcord'] != 'N') {
				$quotedetail['desc1'] = $input->post->text('desc1');
				$quotedetail['desc2'] = $input->post->text('desc2');
			}

			$session->sql = edit_quoteline(session_id(), $qnbr, $quotedetail, false);
			$session->detail = $quotedetail;
			$custID = get_custidfromquote(session_id(), $qnbr, false);
			$data = array('DBNAME' => $config->dbName, 'UPDATEQUOTEDETAIL' => false, 'QUOTENO' => $qnbr, 'LINENO' => $linenbr, 'CUSTID' => $custID);
			if ($input->post->page) {
				$session->loc = $input->post->text('page');
			} else {
				$session->loc = $config->pages->edit."quote/?qnbr=".$qnbr;
			}
			$session->editdetail = true;
			break;
		case 'remove-line':
			$qnbr = $input->post->text('qnbr');
			$linenbr = $input->post->text('linenbr');
			$quotedetail = getquotelinedetail(session_id(), $qnbr, $linenbr, false);
			$quotedetail['quotunit'] = '0';
			$quotedetail['linenbr'] = $input->post->text('linenbr');
			$session->sql = edit_quoteline(session_id(), $qnbr, $quotedetail, false);
			$session->detail = $quotedetail;
			$custID = get_custidfromquote(session_id(), $qnbr, false);
			$data = array('DBNAME' => $config->dbName, 'UPDATEQUOTEDETAIL' => false, 'QUOTENO' => $qnbr, 'LINENO' => $linenbr, 'QTY' => '0', 'CUSTID' => $custID);
			if ($input->post->page) {
				$session->loc = $input->post->text('page');
			} else {
				$session->loc = $config->pages->edit."quote/?qnbr=".$qnbr;
			}
			$session->editdetail = true;
			break;
		case 'remove-line-get':
			$qnbr = $input->get->text('qnbr');
			$linenbr = $input->get->text('linenbr');
			$quotedetail = getquotelinedetail(session_id(), $qnbr, $linenbr, false);
			$quotedetail['quotunit'] = '0';
			$quotedetail['linenbr'] = $input->post->text('linenbr');
			$session->sql = edit_quoteline(session_id(), $qnbr, $quotedetail, false);
			$session->detail = $quotedetail;
			$custID = get_custidfromquote(session_id(), $qnbr, false);
			$data = array('DBNAME' => $config->dbName, 'UPDATEQUOTEDETAIL' => false, 'QUOTENO' => $qnbr, 'LINENO' => $linenbr, 'QTY' => '0', 'CUSTID' => $custID);
			if ($input->get->page) {
				$session->loc = $input->get->text('page');
			} else {
				$session->loc = $config->pages->edit."quote/?qnbr=".$qnbr;
			}
			$session->editdetail = true;
			break;
		case 'unlock-quote':
			$qnbr = $input->get->text('qnbr');
			$data = array('UNLOCKING QUOTE' => false);
			$session->loc = $config->pages->edit."quote/confirm/?qnbr=".$qnbr.$linkaddon;
			remove_orderlock($sessionID, $ordn, $userID, $debug);
			break;
		case 'send-quote-to-order':
			$qnbr = $input->post->text('qnbr');
			$linenbrs = $input->post->linenbr;
			$linecount = nextquotelinenbr(session_id(), $qnbr);
			for ($i = 1; $i < $linecount; $i++) {
				$quotedetail = getquotelinedetail(session_id(), $qnbr, $i, false);
				if (in_array($i, $linenbrs)) {
					$quotedetail['ordrqty'] = $quotedetail['quotunit'];
				} else {
					$quotedetail['ordrqty'] = '0';
				}
				$session->sql = edit_quoteline(session_id(), $qnbr, $quotedetail, false);
			}
			$session->custID = $custID = get_custidfromquote(session_id(), $qnbr, false);
			$data = array('DBNAME' => $config->dbName, 'QUOTETOORDER' => false, 'QUOTENO' => $qnbr, 'LINENO' => 'ALL');
			$session->loc = $config->pages->orders."redir/?action=edit-new-order";
			break;
	}

	writedplusfile($data, $filename);
	header("location: /cgi-bin/" . $config->cgi . "?fname=" . $filename);
	exit;
