<?php
	// $task is loaded by Crud Controller
    $taskdisplay = new UserActionDisplay($page->fullURL);
    
    $contactinfo = get_customercontact($task->customerlink, $task->shiptolink, $task->contactlink, false);

    if ($task->is_rescheduled()) {
        $rescheduledtask = UserAction::get($task->rescheduledlink);
    }
    
    $task->get_actionlineage();
?>

<div>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#task" aria-controls="task" role="tab" data-toggle="tab">Task ID: <?= $taskID; ?></a></li>
		<?php if (!empty($task->actionlineage)) : ?>
			<li role="presentation"><a href="#history" aria-controls="history" role="tab" data-toggle="tab">Task History</a></li>
		<?php endif; ?>
	</ul>
	<br>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="task"><?php include $config->paths->content."actions/tasks/view/view-task-details.php"; ?></div>
		<?php if (!empty($task->actionlineage)) : ?>
			<div role="tabpanel" class="tab-pane" id="history"><?php include $config->paths->content."actions/tasks/view/view-task-history.php"; ?></div>
		<?php endif; ?>
	</div>
</div>
