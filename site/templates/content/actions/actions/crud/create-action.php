<?php
    header('Content-Type: application/json');

    if (isset($input->post)) {
        $action = $input->post->text('action');

        $action = new UserAction();
        $action->set('actiontype', 'actions');
        $action->set('actionsubtype', $input->post->text('subtype'));
        $action->set('customerlink', $input->post->text('customerlink'));
        $action->set('shiptolink', $input->post->text('shiptolink'));
        $action->set('contactlink', $input->post->text('contactlink'));
        $action->set('salesorderlink', $input->post->text('salesorderlink'));
        $action->set('quotelink', $input->post->text('quotelink'));
        $action->set('title', $input->post->text('title'));
        $action->set('textbody', $input->post->purify('textbody'));
        $action->set('actionlink', $input->post->text('actionlink'));
        $action->set('datecreated', date("Y-m-d H:i:s"));
        $action->set('datecompleted', date("Y-m-d H:i:s", strtotime($input->post->text('actiondate').' '.$input->post->text('actiontime'))));
        $action->set('assignedto', $user->loginid);
        $action->set('createdby', $user->loginid);
        $action->set('assignedby', $user->loginid);

        if (empty($action->customerlink)) {
        	if (!empty($action->salesorderlink)) {
        		$action->set('customerlink', get_custidfromorder(session_id(), $action->salesorderlink));
                $action->set('shiptolink', get_shiptoidfromorder(session_id(), $action->salesorderlink));
        	} elseif (!empty($action->quotelink)) {
        		$action->set('customerlink', get_custidfromquote(session_id(), $action->quotelink));
                $action->set('shiptolink', get_shiptoidfromquote(session_id(), $action->quotelink));
        	}
        }

        $maxrec = get_useractions_maxrec($user->loginid);

        $results = $action->save();

        $session->insertedid = $results['insertedid'];
        $session->sql = $results['sql'];
        $action->set('id', $results['insertedid']);
        
        if ($results['insertedid'] > $maxrec) {
            $error = false;
            $template = "<strong>Success!</strong><br> Your action for {replace} has been created";
            $icon = "glyphicon glyphicon-floppy-saved";
            $message = $action->generate_message($template);
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
                'actionid' => $action->id,
                'actiontype' => $action->actiontype
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
