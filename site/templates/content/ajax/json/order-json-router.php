<?php
 	header('Content-Type: application/json');
    $ordn = $input->get->text('ordn');


	switch ($input->urlSegment(2)) {
		case 'orderhead':
			$order = get_orderhead(session_id(), $ordn, 'SalesOrder', false);
			echo json_encode(array("response" => array("order" => $order->toJson())));
			break;
		case 'details':
			$orderdetails = getorderdetails(session_id(), $ordn, false);
            $editurl = $config->pages->ajax.'load/edit-detail/order/?ordn='.$ordn.'&line=';
    		echo json_encode(array("response" => array("orderdetails" => $orderdetails, "editurl" => $editurl)));
			break;
	}
