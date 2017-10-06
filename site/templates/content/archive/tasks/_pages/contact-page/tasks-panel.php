<?php
	$taskpanel = new TaskPanel('contact', '#tasks-panel', '#tasks-panel', '#ajax-modal', $config->ajax);
	$taskpanel->setupcontactpanel($custID, $shipID, $contactID);
	switch ($input->get->text('tasks-status')) {
		case 'Y':
			$taskpanel->setupcompletetasks();
			break;
		case 'R':
			$taskpanel->setuprescheduledtasks();
			break;
	}
	$taskpanel->count = get_linked_task_count($user->loginid, $custID, $shipID, $contactID, '', '', '', $taskpanel->taskstatus, false);
	include $config->paths->content."tasks/tasks-panel.php";
?>
