<?php
	$actionlinks = UserAction::getlinkarray();
	$actionlinks['actiontype'] = 'action';
	$actionlinks['customerlink'] = $custID;
	$actionlinks['shiptolink'] = $shipID;
	$actionlinks['contactlink'] = $contactID;
	$actionlinks['salesorderlink'] = $ordn;
	$actionlinks['quotelink'] = $qnbr;
	$actionlinks['notelink'] = $noteID;
	$actionlinks['tasklink'] = $taskID;
	$actionlinks['actionlink'] = $actionID;

	if (empty($actionlinks['customerlink'])) {
		if (!empty($actionlinks['salesorderlink'])) {
			$actionlinks['customerlink'] = get_custid_from_order(session_id(), $actionlinks['salesorderlink']);
			$actionlinks['shiptolink'] = get_shiptoid_from_order(session_id(), $actionlinks['salesorderlink']);
		} elseif (!empty($actionlinks['quotelink'])) {
			$actionlinks['customerlink'] = getquotecustomer(session_id(), $actionlinks['quotelink']);
			$actionlinks['shiptolink'] = getquoteshipto(session_id(), $actionlinks['salesorderlink'], false);
		}
	}

	if (!empty($tasklinks['customerlink'])) {
		$tasklinks['assignedto'] = get_customersalesperson($tasklinks['customerlink'], $tasklinks['shiptolink'], false);
	} else {
		$tasklinks['assignedto'] = $user->loginid;
	}
	$action = UserAction::blankuseraction($actionlinks);

	$message = "Creating an action for {replace}";
	$page->title = $action->createmessage($message);

	if ($config->ajax) {
		if ($config->modal) {
			$page->body = $config->paths->content."actions/actions/forms/new-action-form.php";
			include $config->paths->content."common/modals/include-ajax-modal.php";
		}
	} else {
		$page->body = $config->paths->content."actions/actions/forms/new-action-form.php";
		include $config->paths->content."common/include-blank-page.php";
	}

?>
