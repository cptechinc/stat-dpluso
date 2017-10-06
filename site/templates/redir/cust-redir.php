<?php
	/**
	* CUSTOMER REDIRECT
	* @param string $action
	*
	*/

	$action = ($input->post->action ? $input->post->text('action') : $input->get->text('action'));
	$custID = ($input->post->custID ? $input->post->text('custID') : $input->get->text('custID'));
	$shipID = ($input->post->shipID ? $input->post->text('shipID') : $input->get->text('shipID'));

	$session->{'from-redirect'} = $page->url;

	$filename = session_id();

	/**
	* CUSTOMER REDIRECT
	* @param string $action
	*
	*
	*
	* switch ($action) {
	* 	case 'add-customer':
	* 		DBNAME=$config->DBNAME
	* 		NEWCUSTOMER
	* 		break;
	* 	case 'load-new-customer':
	*		DBNAME=$config->DBNAME
	*		CUSTID=$custID
	*		break;
	*	case 'load-customer':
	*		DBNAME=$config->DBNAME
	*		CUSTID=$custID
	*		break;
	*	case 'shop-as-customer':
	*		DBNAME=$config->DBNAME
	*		CARTCUST
	*		CUSTID=$custID
	*		SHIPID=$shipID
	*		break;
	*	case 'edit-contact':
	*		DBNAME=$config->DBNAME
	*		EDITCONTACT
	*		CUSTID=$custID
	*		SHIPID=$shipID
	*		CONTACT=$contactID
	*		NAME=$name
	*		PHONE=$phone
	*		EXTENSION=$extension
	*		CELLPHONE=$cellphone
	*		EMAIL=$email
	* 		break;
	* 	case 'ci-buttons':
	* 		DBNAME=$config->DBNAME
	*		CIBUTTONS
	*		break;
	*	case 'ci-customer':
	*		DBNAME=$config->DBNAME
	*		CICUSTOMER
	*		CUSTID=$custID
	* 		break;
	* 	case 'ci-shiptos':
	*		DBNAME=$config->DBNAME
	*		CISHIPTOLIST
	*		CUSTID=$custID
	* 		break;
	* 	case 'ci-shipto-info':
	*		DBNAME=$config->DBNAME
	*		CISHIPTOINFO
	*		CUSTID=$custID
	*		SHIPID=$shipID
	* 		break;
	* 	case 'ci-shipto-buttons':
	*		DBNAME=$config->DBNAME
	*		CISTBUTTONS
	* 		break;
	* 	case 'ci-pricing':
	*		DBNAME=$config->DBNAME
	*		CIPRICE
	*		ITEMID=$itemID
	*		CUSTID=$custID
	* 		break;
	* 	case 'ci-contacts':
	*		DBNAME=$config->DBNAME
	*		CICONTACT
	*		CUSTID=$custID
	*		SHIPID=$shipID
	*		break;
	*	case 'ci-documents':
	*		DOCVIEW
	*		FLD1CD=CU
	*		FLD1DATA=$custID
	*		FLD1DESC=$custname
	* 		break;
	* 	case 'ci-order-documents':
	*		DOCVIEW
	*		FLD1CD=SO
	*		FLD1DATA=$ordn
	* 		break;
	* 	case 'ci-standing-orders':
	*		CISTANDORDR
	*		CUSTID=$custID
	*		SHIPID=$shipID
	* 		break;
	* 	case 'ci-credit':
	*		CICREDIT
	*		CUSTID=$custID
	* 		break;
	* 	case 'ci-open-invoices':
	*		CIOPENINV
	*		CUSTID=$custID
	* 		break;
	* 	case 'ci-quotes':
	*		CIQUOTE
	*		CUSTID=$custID
	* 		break;
	* 	case 'ci-payments':
	*		CIPAYMENT
	*		CUSTID=$custID
	* 		break;
	* 	case 'ci-sales-orders':
	*		CISALESORDR
	*		CUSTID=$custID
	*		SHIPID=$shipID
	*		SALESORDRNBR=''
	*		ITEMID=''
	* 		break;
	* 	case 'ci-sales-history':
	* 		CISALESHIST
	* 		CUSTID=$custID
	*		SHIPID=$shipID
	*		DATE=$startdate
	*		SALESORDRNBR=''
	*		ITEMID=$itemID
	* 		break;
	* 	case 'ci-custpo':
	* 		CICUSTPO
	* 		CUSTID=$custID
	*		SHIPID=$shipID
	*		CUSTPO=$custpo
	* 		break;
	* }
	*
	**/


	switch ($action) {
		case 'add-customer':
			$customer = get_firstcustindexrecord(false);
			$custkeys = array_keys($customer);
			$newcustomer = array_fill_keys($custkeys, ' ');
			$newcustomer['custid'] = session_id();
			$newcustomer['splogin1'] = $input->post->text('salesperson1');
			$newcustomer['splogin2'] = $input->post->text('salesperson2');
			$newcustomer['splogin3'] = $input->post->text('salesperson3');
			$newcustomer['date'] = date('Ymd');
			$newcustomer['time'] = date('His');
			$newcustomershipto = $newcustomer;
			$data = array(
				'DBNAME' => $config->dbName,
				'NEWCUSTOMER' => false,
				'BILLTONAME' => $input->post->text('billto-name'),
				'BILLTOADDRESS1' => $input->post->text('billto-address'),
				'BILLTOADDRESS2' => $input->post->text('billto-address2'),
				'BILLTOADDRESS3' => $input->post->text('billto-address3'),
				'BILLTOCITY' => $input->post->text('billto-city'),
				'BILLTOSTATE' => $input->post->text('billto-state'),
				'BILLTOZIP' => $input->post->text('billto-zip'),
				'BILLTOCOUNTRY' => $input->post->text('billto-country'),
				'BILLTOPHONE' => str_replace('-', '', $input->post->text('billto-phone').$input->post->text('billto-ext')),
				'BILLTOFAX' => str_replace('-', '',$input->post->text('billto-fax')),
				'BILLTOEMAIL' => $input->post->text('billto-email'),
				'SHIPTOID' => '1',
				'SHIPTONAME' => $input->post->text('shipto-name'),
				'SHIPTOADDRESS1' => $input->post->text('shipto-address'),
				'SHIPTOADDRESS2' => $input->post->text('shipto-address2'),
				'SHIPTOADDRESS3' => $input->post->text('shipto-address3'),
				'SHIPTOCITY' => $input->post->text('shipto-city'),
				'SHIPTOSTATE' => $input->post->text('shipto-state'),
				'SHIPTOZIP' => $input->post->text('shipto-zip'),
				'SHIPTOCOUNTRY' => $input->post->text('shipto-country'),
				'SHIPTOPHONE' => str_replace('-', '', $input->post->text('shipto-phone').$input->post->text('shipto-ext')),
				'SHIPTOFAX' => str_replace('-', '',$input->post->text('shipto-fax')),
				'SHIPTOEMAIL' => $input->post->text('shipto-email'),
				'SALESPERSON1' => $input->post->text('salesperson1'),
				'SALESPERSON2' => $input->post->text('salesperson2'),
				'SALESPERSON3' => $input->post->text('salesperson3'),
				'PRICECODE' => $input->post->text('pricecode'),
				'NOTES' => ''
			);
			$newcustomer['shiptoid'] = '';
			$newcustomer['source'] = 'C';
			$newcustomer['name'] = $data['BILLTONAME'];
			$newcustomer['addr1'] = $data['BILLTOADDRESS1'];
			$newcustomer['addr2'] = $data['BILLTOADDRESS2'];
			$newcustomer['ccity'] = $data['BILLTOCITY'];
			$newcustomer['cst'] = $data['BILLTOSTATE'];
			$newcustomer['czip'] = $data['BILLTOZIP'];
			$newcustomer['cphone'] = $input->post->text('billto-phone');
			$newcustomer['cphext'] = $input->post->text('billto-ext');
			$newcustomer['email'] = $data['BILLTOEMAIL'];
			$newcustomer['recno'] = getmax_custindexrecnbr() + 1;
			$session->sql = insert_newcustindexrecord($newcustomer, false);

			$newcustomershipto['shiptoid'] = '1';
			$newcustomershipto['source'] = 'CS';
			$newcustomershipto['name'] = $data['SHIPTONAME'];
			$newcustomershipto['addr1'] = $data['SHIPTOADDRESS1'];
			$newcustomershipto['addr2'] = $data['SHIPTOADDRESS2'];
			$newcustomershipto['ccity'] = $data['SHIPTOCITY'];
			$newcustomershipto['cst'] = $data['SHIPTOSTATE'];
			$newcustomershipto['czip'] = $data['SHIPTOZIP'];
			$newcustomershipto['cphone'] = $input->post->text('shipto-phone');
			$newcustomershipto['cphext'] = $input->post->text('shipto-ext');
			$newcustomershipto['email'] = $data['SHIPTOEMAIL'];
			$newcustomershipto['recno'] = getmax_custindexrecnbr() + 1;
			$session->sql = insert_newcustindexrecord($newcustomershipto, false);
			$session->loc = $config->pages->customer.'redir/?action=load-new-customer';
			break;
		case 'load-new-customer':
			$custID = getcreatedordn(session_id(), false);
			edit_custindexcustid(session_id(), $custID);
			$session->loc = $config->pages->customer . urlencode($custID)."/shipto-1/";
			$data = array('DBNAME' => $config->dbName, 'CUSTID' => $custID);
			break;
		case 'load-customer':
			$session->loc = $config->pages->customer . urlencode($custID)."/";
			if (!empty($shipID)) { $session->loc .= "shipto-".$input->get->shipID."/"; }
			$data = array('DBNAME' => $config->dbName, 'CUSTID' => $custID);
			break;
		case 'shop-as-customer':
			$session->custID = $custID;
			$data = array('DBNAME' => $config->dbName, 'CARTCUST' => false, 'CUSTID' => $custID);
			$session->{'new-shopping-customer'} = get_customername($custID);
            if (!empty($shipID)) {$data['SHIPID'] = $shipID; $session->shipID = $shipID; }
			if (!getcartheadcount(session_id(), false)) { $session->sql = insertcarthead(session_id(), $custID, $shipID, false);}
			$session->loc = ($input->post->page ? $input->post->page : $input->get->text('page'));
			break;
		case 'edit-contact':
			$custID = $input->post->text('custID');
			$shipID = $input->post->text('shipID');
			$contactID = $input->post->text('contactID');
			$contact = get_customercontact($custID, $shipID, $contactID, false);
			$contact['contact'] = $input->post->text('name');
			$contact['cphone'] = str_replace('-', '', $input->post->text('phone'));
			$contact['cphext'] = $input->post->text('extension');
			$contact['ccellphone'] = str_replace('-', '', $input->post->text('cellphone'));
			$contact['email'] = $input->post->text('email');
			$session->sql = edit_customercontact($custID, $shipID, $contactID, $contact, true);
			$data = array('DBNAME' => $config->dbName, 'EDITCONTACT' => false, 'CUSTID' => $custID, 'SHIPID' => $shipID, 'CONTACT' => $contactID, 'NAME' => $contact['contact'], 'PHONE' => $contact['cphone'], 'EXTENSION' => $contact['cphext'], 'CELLPHONE' => $contact['ccellphone'], 'EMAIL' => $contact['email']);
			$returnpage = new \Purl\Url($input->post->text('page'));
			$returnpage->query->set('id', $contact['contact']);

			$oldlinks = array('customerlink' => $custID, 'shiptolink' => $shipID, 'contactlink' => $contactID);
			$newlinks = array('customerlink' => $custID, 'shiptolink' => $shipID, 'contactlink' => $contact['contact']);
			if ($contactID != $contact['contact']) {
				$session->sql = updateactionlinks($oldlinks, $newlinks, $oldlinks, true);
				updateactionlinks($oldlinks, $newlinks, $oldlinks, false);
			}
			$session->loc = $returnpage->getUrl();
			break;
		case 'ci-buttons':
			$data = array('DBNAME' => $config->dbName, 'CIBUTTONS' => false);
			$session->loc = $config->pages->index;
			break;
		case 'ci-customer':
			$data = array('DBNAME' => $config->dbName, 'CICUSTOMER' => false, 'CUSTID' => $custID);
			$session->loc = $config->pages->index;
			$session->loc = $config->pages->custinfo."$custID/";
			break;
		case 'ci-shiptos':
			$data = array('DBNAME' => $config->dbName, 'CISHIPTOLIST' => false, 'CUSTID' => $custID);
			$session->loc = $config->pages->index;
			break;
		case 'ci-shipto-info':
			$shipID = $input->get->text('shipID');
			$data = array('DBNAME' => $config->dbName, 'CISHIPTOINFO' => false, 'CUSTID' => $custID, 'SHIPID' => $shipID);
			$session->loc = $config->pages->custinfo."$custID/shipto-$shipID/";
			break;
		case 'ci-shipto-buttons':
			$data = array('DBNAME' => $config->dbName, 'CISTBUTTONS' => false);
			$session->loc = $config->pages->index;
			break;
		case 'ci-pricing':
			$itemID = $input->get->text('itemID');
			$data = array('DBNAME' => $config->dbName, 'CIPRICE' => false, 'ITEMID' => $itemID, 'CUSTID' => $custID);
			$session->loc = $config->pages->index;
			break;
		case 'ci-contacts':
			$shipID = $input->get->text('shipID');
			$data = array('DBNAME' => $config->dbName, 'CICONTACT' => false, 'CUSTID' => $custID, 'SHIPID' => $shipID);
			$session->loc = $config->pages->index;
			break;
		case 'ci-documents':
			$custname = get_customername($custID);
			$data = array('DBNAME' => $config->dbName, 'DOCVIEW' => false, 'FLD1CD' => 'CU', 'FLD1DATA' => $custID, 'FLD1DESC' => $custname);
			$session->loc = $config->pages->index;
			break;
		case 'ci-order-documents':
			$ordn = $input->get->ordn;
			$data = array('DBNAME' => $config->dbName, 'DOCVIEW' => false, 'FLD1CD' => 'SO', 'FLD1DATA' => $ordn);
			$session->loc = $config->pages->index;
			break;
		case 'ci-standing-orders':
			$shipID = $input->get->text('shipID');
			$data = array('DBNAME' => $config->dbName, 'CISTANDORDR' => false, 'CUSTID' => $custID, 'SHIPID' => $shipID);
			$session->loc = $config->pages->index;
			break;
		case 'ci-credit':
			$data = array('DBNAME' => $config->dbName, 'CICREDIT' => false, 'CUSTID' => $custID);
			$session->loc = $config->pages->index;
			break;
		case 'ci-open-invoices':
			$data = array('DBNAME' => $config->dbName, 'CIOPENINV' => false, 'CUSTID' => $custID);
			$session->loc = $config->pages->index;
			break;
		case 'ci-quotes':
			$data = array('DBNAME' => $config->dbName, 'CIQUOTE' => false, 'CUSTID' => $custID);
			$session->loc = $config->pages->index;
			break;
		case 'ci-payments':
			$data = array('DBNAME' => $config->dbName, 'CIPAYMENT' => false, 'CUSTID' => $custID);
			$session->loc = $config->pages->index;
			break;
		case 'ci-sales-orders':
			$shipID = $input->get->text('shipID');
			$data = array('DBNAME' => $config->dbName, 'CISALESORDR' => false, 'CUSTID' => $custID, 'SHIPID' => $shipID, 'SALESORDRNBR' => '', 'ITEMID' => '');
			$session->loc = $config->pages->index;
			break;
		case 'ci-sales-history':
			$shipID = $input->get->text('shipID');
			$itemID = $input->get->text('itemID');
			$date = $input->get->text('startdate');
			$session->date = $date;
			$startdate = date('Ymd', strtotime($date));
			$data = array('DBNAME' => $config->dbName, 'CISALESHIST' => false, 'CUSTID' => $custID, 'SHIPID' => $shipID, 'DATE' => $startdate, 'SALESORDRNBR' => '', 'ITEMID' => $itemID);
			$session->loc = $config->pages->index;
			break;
		case 'ci-custpo':
			$custpo = $input->get->text('custpo');
			$shipID = $input->get->text('shipID');
			$data = array('DBNAME' => $config->dbName, 'CICUSTPO' => false, 'CUSTID' => $custID, 'SHIPID' => $shipID, 'CUSTPO' => $custpo);
			$session->loc = $config->pages->index;
			break;
	}

	writedplusfile($data, $filename);
	header("location: /cgi-bin/" . $config->cgi . "?fname=" . $filename);
 	exit;
