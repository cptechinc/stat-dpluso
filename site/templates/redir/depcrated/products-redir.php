<?php

switch ($action) {
	case 'get-item-history':
		if ($input->post->custID) { $custID = $input->post->custID; } else { $custID = $input->get->text('custID'); }
		if ($custID == '') { $custID == $config->defaultweb; }
		$data = array('DBNAME' => $config->dbName, 'PRICEHIST' => false, 'ITEMID' => $itemid, 'CUSTID' => $custID);
		$session->loc = $config->page->index;
		break;
	case 'get-item-stock':
		$data = array('DBNAME' => $config->dbName, 'ITEMSTOCK' => false, 'ITEMID' => $itemid);
		$session->loc = $config->page->index;
		break;
	case 'ii-stock-status': // PART OF IISELECT
		$data = array('DBNAME' => $config->dbName, 'IISTOCKSTATUS' => false, 'ITEMID' => $itemid);
		$session->loc = $config->page->index;
		break;
		case 'ii-price': //NOW IS IIPRICING
			$data = array('DBNAME' => $config->dbName, 'IIPRICE' => false, 'ITEMID' => $itemid);
            if ($input->post->custID) { $custID = $input->post->custID; } else { $custID = $input->get->text('custID'); }
            if ($input->post->shipID) { $shipID = $input->post->shipID; } else { $shipID = $input->get->text('shipID'); }
            if ($custID != '') {$data['CUSTID'] = $custID; } if ($shipID != '') {$data['SHIPID'] = $shipID; }
            $session->loc = $config->page->index;
            break;
		
}