<?php
	header('Content-Type: application/json');

    if (isset($input->post)) {
        $action = $input->post->text('action');

        $notelinks = UserAction::getlinkarray();
        $notelinks['actiontype'] = 'note';
        $notelinks['actionsubtype'] = $input->post->text('tasktype');
        $notelinks['customerlink'] = $input->post->text('custlink');
        $notelinks['shiptolink'] = $input->post->text('shiptolink');
        $notelinks['contactlink'] = $input->post->text('contactlink');
        $notelinks['salesorderlink'] = $input->post->text('salesorderlink');
        $notelinks['quotelink'] = $input->post->text('quotelink');
        $notelinks['title'] = $input->post->text('title');
		$notelinks['textbody'] = $input->post->purify('textbody');
		$notelinks['tasklink'] = $input->post->text('tasklink');
        $notelinks['notelink'] = $input->post->text('notelink');
        $notelinks['actionlink'] = $input->post->text('actionlink');

        $notelinks['datecreated'] = date("Y-m-d H:i:s");
        $notelinks['assignedto'] = $input->post->text('assignedto');
        $notelinks['createdby'] = $user->loginid;
        $notelinks['assignedby'] = $user->loginid;

		if (empty($notelinks['customerlink'])) {
			if (!empty($notelinks['salesorderlink'])) {
				$notelinks['customerlink'] = get_custid_from_order(session_id(), $notelinks['salesorderlink']);
			} elseif (!empty($notelinks['quotelink'])) {
				$notelinks['customerlink'] = getquotecustomer(session_id(), $notelinks['quotelink']);
			}
		}

        $maxrec = get_useractions_maxrec($user->loginid);

        $results = insertaction($notelinks, false);

        $session->insertedid = $results['insertedid'];
        $session->sql = $results['sql'];
		$newnoteID = $results['insertedid'];
		$newnote = loaduseraction($newnoteID, true, false); // (id, bool fetchclass, bool debug)

		if ($results['insertedid'] > $maxrec) {
            $error = false;
            $message = "<strong>Success!</strong><br> Your note for {replace} has been created";
            $icon = "glyphicon glyphicon-floppy-saved";
            $message = $newnote->createmessage($message);
		} else {
			$error = true;
			$message = "<strong>Error!</strong><br> Your note could not be created";
			$icon = "glyphicon glyphicon-warning-sign";
		}

		$json = array (
				'response' => array (
						'error' => $error,
						'notifytype' => 'success',
						'message' => $message,
						'icon' => $icon,
						'actionid' => $newnoteID,
						'actiontype' => $notelinks['actiontype']

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
