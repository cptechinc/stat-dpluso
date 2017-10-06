<?php
	$taskpanel = new TaskPanel('user', '#tasks-panel', '#tasks-panel', '#ajax-modal', $config->ajax);
	switch ($input->get->text('tasks-status')) {
		case 'Y':
			$taskpanel->setupcompletetasks();
			break;
		case 'R':
			$taskpanel->setuprescheduledtasks();
			break;
	}
	
	$taskpanel->count = get_linked_task_count($user->loginid, '', '', '', '', '', '', $taskpanel->taskstatus, false);
	include $config->paths->content."tasks/tasks-panel.php";

?>
