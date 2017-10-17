<?php 
	header('Content-Type: application/json'); 
	$ordn = $input->get->text('ordn');
	$order = get_orderhead(session_id(), $ordn, 'SalesOrder', false);
	echo json_encode(array("response" => array("order" => $order)));
