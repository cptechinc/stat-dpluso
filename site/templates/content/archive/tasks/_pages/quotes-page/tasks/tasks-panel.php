<?php
	$taskpanel = new TaskPanel('quote', '#tasks-panel', '#tasks-panel', '#ajax-modal', $config->ajax);
	$taskpanel->setupquotepanel($qnbr);
	switch ($input->get->text('tasks-status')) {
		case 'Y':
			$taskpanel->setupcompletetasks();
			break;
		case 'R':
			$taskpanel->setuprescheduledtasks();
			break;
	}
	$taskpanel->count = get_linked_task_count($user->loginid, '', '', '', '', $qnbr, '', $taskpanel->taskstatus, false);
	include $config->paths->content."tasks/tasks-panel.php";

?>
