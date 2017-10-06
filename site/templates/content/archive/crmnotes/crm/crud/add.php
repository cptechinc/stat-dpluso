<?php 
	/*if (!$config->ajax) {
		include($config->paths->templates.'_head.php'); // include header markup
		echo '<div class="container page">';
	} else {*/
		header('Content-Type: application/json');
		if ($input->post->custlink) {
			$date = date("Y-m-d H:i:s");
			$custID = $input->post->text('custlink');
			$shipID = $input->post->text('shiptolink');
			$contactID = $input->post->text('contactlink');
			$ordn = $input->post->text('salesorderlink');
			$qnbr = $input->post->text('quotelink');
			$taskID = $input->post->text('tasklink');
			$textbody = $input->post->text('textbody');
			$noteID = '';
			$maxrec = get_user_note_maxrec($user->loginid);
			$results = writecrmnote($user->loginid, $date, $custID, $shipID, $contactID, $ordn, $qnbr, $textbody);
			$session->sql = $results['sql'];
			if ($results['insertedid'] > $maxrec) {
				$error = false;
				$message = "<strong>Success!</strong><br> Your note for {replace} has been saved";
				$icon = "glyphicon glyphicon-floppy-saved";
				$message = createmessage($message, $custID, $shipID, $contactID, $taskID, $noteID, $ordn, $qnbr);
				$noteID = $results['insertedid'];
			} else {
				$error = true;
				$message = "<strong>Error!</strong><br> Your note could not be saved";
				$icon = "glyphicon glyphicon-warning-sign";
			}

			$json = array (
					'response' => array (
							'error' => $error,
							'message' => $message,
							'icon' => $icon,
							'noteid' => $noteID

					)
				);
		} else {
			$json = array (
					'response' => array (
							'error' => true,
							'message' => 'DID NOT POST',
							'icon' => 'glyphicon glyphicon-warning-sign'
					)
				);
		}
		echo json_encode($json);
	//}


    /*if (!$config->ajax) {
        echo '</div>';
    	include($config->paths->templates.'_foot.php'); // include footer markup
    }*/



?>