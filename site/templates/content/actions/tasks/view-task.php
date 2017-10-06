<?php
	// $task is loaded by Crud Controller

    if ($task->hascontactlink) { //DOESNT MATTER DEPRECATE
        $contactinfo = get_customercontact($task->customerlink, $task->shiptolink, $task->contactlink, false);
    } else {
        $contactinfo = get_customercontact($task->customerlink, $task->shiptolink, $task->contactlink, false);
    }

    if ($task->isrescheduled) {
        $rescheduledtask = loaduseraction($task->rescheduledlink, true, false);
    }
    $task->getactionlineage();
?>

<div>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#task" aria-controls="task" role="tab" data-toggle="tab">Task ID: <?= $taskid; ?></a></li>
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
