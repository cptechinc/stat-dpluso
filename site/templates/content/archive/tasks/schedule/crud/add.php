<?php
	header('Content-Type: application/json');
	if ($input->post->customerlink) {
		$date = date("Y-m-d H:i:s");
		$custID = $input->post->text('customerlink');
		$shipID = $input->post->text('shiptolink');
		$contactID = $input->post->text('contactlink');
		$desc = $input->post->text('description');
		$startdate = $input->post->text('start-date');
		$icalpattern = $input->post->text('icalvalue');
		$scheduletype = $input->post->text('tasktype');

		$startdate = date("Y-m-d H:i:s", strtotime($startdate));


		$maxrec = get_user_taskscheduler_maxrec($user->loginid);
		//$results = createtaskschedule($date, $startdate, $user->loginid, $repititiontype, $interval, $fallson, $active, $desc, $custID, $shipID, $contactID, false);
		$results = createtaskschedule($date, $startdate, $user->loginid, $icalpattern , $desc, $custID, $shipID, $contactID, $scheduletype, false);
		$session->insertedid = $results['insertedid'];
        $session->sql = $results['sql'];
		$scheduleID =  $results['insertedid'];


		if ($results['insertedid'] > $maxrec) {
			$error = false;
			$message = "<strong>Success!</strong><br> Your task schedule for {replace} has been created";
			$type = 'success';
			$icon = "glyphicon glyphicon-floppy-saved";
			$message = createmessage($message, $custID, $shipID, $contactID, $taskID, $noteID, $ordn, $qnbr);
		} else {
			$error = true;
			$type = 'danger';
			$message = "<strong>Error!</strong><br> Your task schedule could not be created";
			$icon = "glyphicon glyphicon-warning-sign";
		}


		$json = array (
				'response' => array (
						'error' => $error,
						'notifytype' => $type,
						'message' => $message,
						'icon' => $icon,
						'taskid' => $taskID

				)
			);
	} else {
		$json = array (
				'response' => array (
						'error' => true,
						'notifytype' => 'danger',
						'message' => '<strong>Error!</strong><br> Your task schedule could not be created',
						'icon' => 'glyphicon glyphicon-warning-sign'
				)
			);
	}

	echo json_encode($json);


?>
