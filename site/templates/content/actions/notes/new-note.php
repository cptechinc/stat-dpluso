<?php
	$notelinks = UserAction::getlinkarray();
	$notelinks['actiontype'] = 'note';
	$notelinks['customerlink'] = $custID;
	$notelinks['shiptolink'] = $shipID;
	$notelinks['contactlink'] = $contactID;
	$notelinks['salesorderlink'] = $ordn;
	$notelinks['quotelink'] = $qnbr;
	$notelinks['notelink'] = $noteID;
	$notelinks['tasklink'] = $taskID;
	$notelinks['actionlink'] = $actionID;

	if (empty($notelinks['customerlink'])) {
		if (!empty($notelinks['salesorderlink'])) {
			$notelinks['customerlink'] = get_custid_from_order(session_id(), $notelinks['salesorderlink']);
			$notelinks['shiptolink'] = get_shiptoid_from_order(session_id(), $notelinks['salesorderlink']);
		} elseif (!empty($notelinks['quotelink'])) {
			$notelinks['customerlink'] = getquotecustomer(session_id(), $notelinks['quotelink']);
			$notelinks['shiptolink'] = getquoteshipto(session_id(), $notelinks['salesorderlink'], false);
		}
	}

	$notelinks['assignedto'] = $user->loginid;
	$note = UserAction::blankuseraction($notelinks);

    $message = "Writing Note for {replace} ";
    $page->title = $note->createmessage($message);

	if ($config->ajax) {
		$page->body = $config->paths->content."actions/notes/forms/new-note-form.php";
		include $config->paths->content."common/modals/include-ajax-modal.php";
	} else {
		$page->body = $config->paths->content."actions/notes/forms/new-note-form.php";
		include $config->paths->content."common/include-blank-page.php";
	}
?>
