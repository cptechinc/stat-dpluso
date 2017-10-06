<?php
	$tasklinks = UserAction::getlinkarray();
	$tasklinks['actiontype'] = 'task';
	$tasklinks['customerlink'] = $custID;
	$tasklinks['shiptolink'] = $shipID;
	$tasklinks['contactlink'] = $contactID;
	$tasklinks['salesorderlink'] = $ordn;
	$tasklinks['quotelink'] = $qnbr;
	$tasklinks['notelink'] = $noteID;
	$tasklinks['tasklink'] = $taskID;
	$tasklinks['actionlink'] = $taskID;
	if (empty($tasklinks['customerlink'])) {
		if (!empty($tasklinks['salesorderlink'])) {
			$tasklinks['customerlink'] = get_custid_from_order(session_id(), $tasklinks['salesorderlink']);
			$tasklinks['shiptolink'] = get_shiptoid_from_order(session_id(), $tasklinks['salesorderlink']);
		} elseif (!empty($tasklinks['quotelink'])) {
			$tasklinks['customerlink'] = getquotecustomer(session_id(), $tasklinks['quotelink']);
			$tasklinks['shiptolink'] = getquoteshipto(session_id(), $tasklinks['salesorderlink'], false);
		}
	}
	if (!empty($tasklinks['customerlink'])) {
		$tasklinks['assignedto'] = get_customersalesperson($tasklinks['customerlink'], $tasklinks['shiptolink'], false);
	} else {
		$tasklinks['assignedto'] = $user->loginid;
	}
	$task = UserAction::blankuseraction($tasklinks);
	$message = "Creating a task for {replace} ";
	$page->title = $task->createmessage($message);
	if ($config->ajax) {
		$page->body = $config->paths->content."actions/tasks/forms/new-task-form.php";
		include $config->paths->content."common/modals/include-ajax-modal.php";
	} else {
		$page->body = $config->paths->content."actions/tasks/forms/new-task-form.php";
		include $config->paths->content."common/include-blank-page.php";
	}
?>
