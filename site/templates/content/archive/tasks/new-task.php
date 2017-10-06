<?php
	$taskID = $input->get->text('id');
	$task = Task::blanktask($custID, $shipID, $contactID, $ordn, $qnbr, $noteID, $taskID);
	if ($task->hasnotelink) {
        $note = loadcrmnote($task->notelink, false);
    }

	if ($config->ajax) {
		$message = "Creating a task for {replace} ";
    	$modaltitle = createmessage($message, $custID, $shipID, $contactID, $taskID, $noteID, $ordn, $qnbr);
		$modalbody = $config->paths->content."tasks/forms/new-task-form.php";
		include $config->paths->content."common/modals/include-ajax-modal.php";

	} else {
		include $config->paths->content."tasks/forms/new-task-form.php";
	}

?>
