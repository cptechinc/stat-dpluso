<?php 
	header('Content-Type: application/json');
	if (isset($_POST['custlink'])) {
		$date = date("Y-m-d H:i:s");
		$custID = $_POST['custlink'];
		$shipID = $_POST['shiptolink'];
		$contactID = $_POST['contactlink'];
		$description = $_POST['description'];
		$repititiontype = $_POST['repeat'];
		if ($repititiontype == 'monthdate') {
			
		}
	}


?>