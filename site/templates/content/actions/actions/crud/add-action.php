<?php
    header('Content-Type: application/json');

    if (isset($input->post)) {
        $action = $input->post->text('action');

        $actionlinks = UserAction::getlinkarray();
        $actionlinks['actiontype'] = 'action';
        $actionlinks['actionsubtype'] = $input->post->text('actiontype');
        $actionlinks['customerlink'] = $input->post->text('custlink');
        $actionlinks['shiptolink'] = $input->post->text('shiptolink');
        $actionlinks['contactlink'] = $input->post->text('contactlink');
        $actionlinks['salesorderlink'] = $input->post->text('salesorderlink');
        $actionlinks['quotelink'] = $input->post->text('quotelink');
        $actionlinks['title'] = $input->post->text('title');
        $actionlinks['textbody'] = $input->post->purify('textbody');
        $actionlinks['tasklink'] = $input->post->text('tasklink');
        $actionlinks['notelink'] = $input->post->text('notelink');
        $actionlinks['actionlink'] = $input->post->text('actionlink');
        $actionlinks['datecreated'] = date("Y-m-d H:i:s");
        $actionlinks['datecompleted'] = date("Y-m-d H:i:s", strtotime($input->post->text('actiondate').' '.$input->post->text('actiontime')));
        $actionlinks['assignedto'] = $user->loginid;
        $actionlinks['createdby'] = $user->loginid;
        $actionlinks['assignedby'] = $user->loginid;

        if (empty($actionlinks['customerlink'])) {
			if (!empty($actionlinks['salesorderlink'])) {
				$actionlinks['customerlink'] = get_custid_from_order(session_id(), $actionlinks['salesorderlink']);
			} elseif (!empty($actionlinks['quotelink'])) {
				$actionlinks['customerlink'] = getquotecustomer(session_id(), $actionlinks['quotelink']);
			}
		}

        $maxrec = get_useractions_maxrec($user->loginid);

        $results = insertaction($actionlinks, false);

        $session->insertedid = $results['insertedid'];
        $session->sql = $results['sql'];
        $newactionID =  $results['insertedid'];

        $createdaction = UserAction::blankuseraction($actionlinks);

        if ($results['insertedid'] > $maxrec) {
            switch ($action) {
                default:
                    $error = false;
                    $message = "<strong>Success!</strong><br> Your action for {replace} has been created";
                    $icon = "glyphicon glyphicon-floppy-saved";
                    // TODO FIX CREATE MESSAGE TO HAVE IT COME FROM USERACTION
                    $message = $createdaction->createmessage($message);
                    break;
            }

        } else {
            $error = true;
            $message = "<strong>Error!</strong><br> Your action could not be created";
            $icon = "glyphicon glyphicon-warning-sign";
        }

        $json = array (
                'response' => array (
                        'error' => $error,
                        'notifytype' => 'success',
                        'message' => $message,
                        'icon' => $icon,
                        'actionid' => $newactionID,
                        'actiontype' => $actionlinks['actiontype']

                )
            );
    } else {
        $json = array (
                'response' => array (
                        'error' => true,
                        'notifytype' => 'danger',
                        'message' => '<strong>Error!</strong><br> Your action could not be recorded',
                        'icon' => 'glyphicon glyphicon-warning-sign',
                )
            );
    }
    echo json_encode($json);
