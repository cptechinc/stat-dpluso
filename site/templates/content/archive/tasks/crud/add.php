<?php
	header('Content-Type: application/json');

    if (isset($input->post->custlink)) {
        $date = date("Y-m-d H:i:s");
		$action = $input->post->text('action');
        $custID = $input->post->text('custlink');
        $shipID = $input->post->text('shiptolink');
        $contactID = $input->post->text('contactlink');
        $ordn = $input->post->text('salesorderlink');
        $qnbr = $input->post->text('quotelink');
        $noteID = $input->post->text('notelink');
        $textbody = $input->post->text('textbody');
        $tasktype = $input->post->text('tasktype');
		$taskID = $input->post->text('tasklink');
        $maxrec = get_user_task_maxrec($user->loginid);
        $duedate = date("Y-m-d H:i:s", strtotime($input->post->text('duedate')));
        $results = writetask($user->loginid, $date, $custID, $shipID, $contactID, $ordn, $qnbr, $noteID, $taskID, $textbody, $tasktype, $duedate, $user->loginid);
        $session->insertedid = $results['insertedid'];
        $session->sql = $results['sql'];
		$newtaskID =  $results['insertedid'];

		if ($results['insertedid'] > $maxrec) {
			switch ($action) {
				case 'reschedule-task':
					$completedate = '0000-00-00 00:00:00';
					$updatedate = date("Y-m-d H:i:s");
					$response = updatetaskcompletion($taskID, $completedate, $updatedate, 'R');
					$session->sql = $response['sql'];

					$error = false;
					$message = "<strong>Success!</strong><br> Your task for {replace} has been rescheduled";
					$icon = "glyphicon glyphicon-floppy-saved";
					$message = createmessage($message, $custID, $shipID, $contactID, $newtaskID, $noteID, $ordn, $qnbr);
					break;
				default:
					$error = false;
					$message = "<strong>Success!</strong><br> Your task for {replace} has been created";
					$icon = "glyphicon glyphicon-floppy-saved";
					$message = createmessage($message, $custID, $shipID, $contactID, $newtaskID, $noteID, $ordn, $qnbr);
					break;
			}

		} else {
			$error = true;
			$message = "<strong>Error!</strong><br> Your task could not be created";
			$icon = "glyphicon glyphicon-warning-sign";
		}

		$json = array (
				'response' => array (
						'error' => $error,
						'notifytype' => 'success',
						'message' => $message,
						'icon' => $icon,
						'taskid' => $newtaskID

				)
			);
	} else {
		$json = array (
				'response' => array (
						'error' => true,
						'notifytype' => 'danger',
						'message' => '<strong>Error!</strong><br> Your task could not be created',
						'icon' => 'glyphicon glyphicon-warning-sign'
				)
			);
	}
    echo json_encode($json);
