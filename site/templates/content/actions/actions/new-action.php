<?php
	$action = new UserAction();
	$action->set('actiontype', 'actions');
	$action->set('customerlink', $custID);
	$action->set('shiptolink', $shipID);
	$action->set('contactlink', $contactID);
	$action->set('salesorderlink', $ordn);
	$action->set('quotelink', $qnbr);
	$action->set('actionlink', $actionID);

	if (empty($action->customerlink)) {
		if (!empty($action->salesorderlink)) {
			$action->set('customerlink', get_custidfromorder(session_id(), $action->salesorderlink));
			$action->set('shiptolink', get_shiptoidfromorder(session_id(), $action->salesorderlink));
		} elseif (!empty($action->quotelink)) {
			$action->set('customerlink', get_custidfromquote(session_id(), $action->quotelink));
			$action->set('shiptolink', get_shiptoidfromquote(session_id(), $action->quotelink));
		}
	}

	if (!empty($action->customerlink) && $config->cptechcustomer == 'stempf') {
		$action->set('assignedto', get_customersalesperson($action->customerlink, $action->shiptolink, false));
	} else {
		$action->set('assignedto', $user->loginid);
	}

	if (!empty($action->actionlink)) {
		$originalaction = UserAction::get($action->actionlink);
		if ($originalaction->actiontype = 'actions') {
			$action->set('actionsubtype', $originalaction->actionsubtype);
		}
	}
	
	$message = "Creating an action for {replace}";
	$page->title = $action->generate_message($message);
	$page->body = $config->paths->content."actions/actions/forms/new-action-form.php";
	
	if ($config->ajax) {
		if ($config->modal) {
			include $config->paths->content."common/modals/include-ajax-modal.php";
		}
	} else {
		include $config->paths->content."common/include-blank-page.php";
	}

?>
