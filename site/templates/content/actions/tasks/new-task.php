<?php
	$task = new UserAction();
	$task->set('actiontype', 'tasks');
	$task->set('customerlink', $custID);
	$task->set('shiptolink', $shipID);
	$task->set('contactlink', $contactID);
	$task->set('salesorderlink', $ordn);
	$task->set('quotelink', $qnbr);
	$task->set('actionlink', $actionID);

	if (empty($task->customerlink)) {
		if (!empty($task->salesorderlink)) {
			$task->set('customerlink', get_custidfromorder(session_id(), $task->salesorderlink));
			$task->set('shiptolink', get_shiptoidfromorder(session_id(), $task->salesorderlink));
		} elseif (!empty($task->quotelink)) {
			$task->set('customerlink', get_custidfromquote(session_id(), $task->quotelink));
			$task->set('shiptolink', get_shiptoidfromquote(session_id(), $task->quotelink));
		}
	}

	if (!empty($task->customerlink) && $config->cptechcustomer == 'stempf') {
		$task->set('assignedto', get_customersalesperson($task->customerlink, $task->shiptolink, false));
	} else {
		$task->set('assignedto', $user->loginid);
	}

	if (!empty($actionID)) {
		$originaltask = UserAction::get($actionID);
		$task->set('actionsubtype', $originaltask->actionsubtype);
	}
	

	$message = "Creating a task for {replace} ";
	$page->title = $task->generate_message($message);
	$page->body = $config->paths->content."actions/tasks/forms/new-task-form.php";

	if ($config->ajax) {
		include $config->paths->content."common/modals/include-ajax-modal.php";
	} else {
		include $config->paths->content."common/include-blank-page.php";
	}

?>
