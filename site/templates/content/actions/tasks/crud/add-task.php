<?php
	header('Content-Type: application/json');

    if (isset($input->post)) {
        $action = $input->post->text('action');

        $tasklinks = UserAction::getlinkarray();
        $tasklinks['actiontype'] = 'task';
        $tasklinks['actionsubtype'] = $input->post->text('tasktype');
        $tasklinks['customerlink'] = $input->post->text('custlink');
        $tasklinks['shiptolink'] = $input->post->text('shiptolink');
        $tasklinks['contactlink'] = $input->post->text('contactlink');
        $tasklinks['salesorderlink'] = $input->post->text('salesorderlink');
        $tasklinks['quotelink'] = $input->post->text('quotelink');
        $tasklinks['title'] = $input->post->text('title');
        $tasklinks['textbody'] = $input->post->purify('textbody');
		$tasklinks['tasklink'] = $input->post->text('tasklink');
        $tasklinks['notelink'] = $input->post->text('notelink');
        $tasklinks['actionlink'] = $input->post->text('actionlink');

        $tasklinks['datecreated'] = date("Y-m-d H:i:s");
        $tasklinks['duedate'] = date("Y-m-d H:i:s", strtotime($input->post->text('duedate')));
		$tasklinks['assignedto'] = $input->post->text('assignedto');
        $tasklinks['createdby'] = $user->loginid;
        $tasklinks['assignedby'] = $user->loginid;

		//FOR QUOTES AND orders
		if (empty($tasklinks['customerlink'])) {
			if (!empty($tasklinks['salesorderlink'])) {
				$tasklinks['customerlink'] = get_custid_from_order(session_id(), $tasklinks['salesorderlink']);
			} elseif (!empty($tasklinks['quotelink'])) {
				$tasklinks['customerlink'] = getquotecustomer(session_id(), $tasklinks['quotelink']);
			}
		}

        $maxrec = get_useractions_maxrec($user->loginid);

        $results = insertaction($tasklinks, false);

        $session->insertedid = $results['insertedid'];
        $session->sql = $results['sql'];
		$newtaskID =  $results['insertedid'];
		$newtask = loaduseraction($newtaskID, true, false); // (id, bool fetchclass, bool debug)

		if ($results['insertedid'] > $maxrec) {
			switch ($action) {
				case 'reschedule-task':
					$originaltask = loaduseraction($tasklinks['actionlink'], false, false); // (id, bool fetchclass, bool debug)
					$originaltask['datecompleted'] = '0000-00-00 00:00:00';
					$originaltask['dateupdated'] = date("Y-m-d H:i:s");
					$originaltask['completed'] = 'R';
					$originaltask['rescheduledlink'] = $results['insertedid'];
					$response = updateaction($tasklinks['actionlink'], $originaltask, false);
					$session->sql = $response['sql'];
					$error = false;
					$message = "<strong>Success!</strong><br> You have rescheduled task " . $tasklinks['actionlink'];
					$icon = "glyphicon glyphicon-floppy-saved";
					break;
				default:
					$error = false;
					$message = "<strong>Success!</strong><br> Your task for {replace} has been created";
					$icon = "glyphicon glyphicon-floppy-saved";
					$message = $newtask->createmessage($message);
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
						'actionid' => $newtaskID,
						'actiontype' => $tasklinks['actiontype']

				)
			);
	} else {
		$json = array (
				'response' => array (
						'error' => true,
						'notifytype' => 'danger',
						'message' => '<strong>Error!</strong><br> Your task could not be created',
						'icon' => 'glyphicon glyphicon-warning-sign',
				)
			);
	}
    echo json_encode($json);
