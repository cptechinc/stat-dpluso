<?php
	header('Content-Type: application/json'); 
	
	$key1 = $input->get->text('key1');
	$key2 = $input->get->text('key2');
	$type = $input->get->text('type');

	if ($input->get->recnbr) {
		$recnbr = $input->get->text('recnbr');
		$dplusnotes = get_dplusnote(session_id(), $key1, $key2, $type, $recnbr, false);
		$response = array('note' => $dplusnotes);
	} else {
		$dplusnotes = get_dplusnotes(session_id(), $key1, $key2, $type, false); 
		$response = array('notes' => $dplusnotes);
	}
	
	
	
	echo json_encode($response);
?>