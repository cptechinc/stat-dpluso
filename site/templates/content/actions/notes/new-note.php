<?php
	$note = new UserAction();
	$note->set('actiontype', 'notes');
	$note->set('customerlink', $custID);
	$note->set('shiptolink', $shipID);
	$note->set('contactlink', $contactID);
	$note->set('salesorderlink', $ordn);
	$note->set('quotelink', $qnbr);
	$note->set('actionlink', $actionID);

	if (empty($note->customerlink)) {
		if (!empty($note->salesorderlink)) {
			$note->set('customerlink', get_custidfromorder(session_id(), $note->salesorderlink));
			$note->set('shiptolink', get_shiptoidfromorder(session_id(), $note->salesorderlink));
		} elseif (!empty($note->quotelink)) {
			$note->set('customerlink', get_custidfromquote(session_id(), $note->quotelink));
			$note->set('shiptolink', get_shiptoidfromquote(session_id(), $note->quotelink));
		}
	}

	if (!empty($note->customerlink) && $config->cptechcustomer == 'stempf') {
		$note->set('assignedto', get_customersalesperson($note->customerlink, $note->shiptolink, false));
	} else {
		$note->set('assignedto', $user->loginid);
	}

	if (!empty($note->actionlink)) {
		$originalnote = UserAction::get($note->actionlink);
		if ($originalnote->actiontype == 'notes') {
			$note->set('actionsubtype', $originalnote->actionsubtype);
		}
	}

    $message = "Writing Note for {replace} ";
    $page->title = $note->generate_message($message);
	$page->body = $config->paths->content."actions/notes/forms/new-note-form.php";
	
	if ($config->ajax) {
		include $config->paths->content."common/modals/include-ajax-modal.php";
	} else {
		include $config->paths->content."common/include-blank-page.php";
	}
?>
