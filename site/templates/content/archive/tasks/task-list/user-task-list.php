<?php
	$tasks = get_linked_tasks($user->loginid, '', '', '', '', '', '', $taskpanel->taskstatus, $config->showonpage, $input->pageNum(), false);
?>
<table class="table table-bordered table-condensed table-striped">
	<thead>
    	<tr> <th>Due</th> <th>CustID</th> <th>Type</th> <th>Note</th> <th>View Task</th>  <th> Complete</th>  </tr>
    </thead>
    <tbody>
		<?php if (!$taskpanel->count) : ?>
			<tr>
				<td colspan="6" class="text-center h4">
					No related tasks found
				</td>
			</tr>
		<?php else : ?>
			<?php foreach ($tasks as $task) : ?>
	            <tr <?php if ($task->isoverdue) {echo 'class="bg-warning"'; } ?>>
	                <td><?= date('m/d/Y', strtotime($task->duedate)); ?></td>
					<td><?= $task->customerlink; ?></td>
					<td><?= $tasktypes[$task->tasktype]['icon'].' '.$tasktypes[$task->tasktype]['label']; ?></td>
	                <td><?= substr($task->textbody, 0, 21); ?></td>
	                <td>
	                    <a href="<?= $task->generateviewtaskurl(); ?>" class="btn btn-primary btn-xs load-task-item" data-modal="<?= $taskpanel->modal; ?>" role="button" title="View Task">
	                       <i class="material-icons md-18">&#xE02F;</i>
	                    </a>
	                </td>
	                <td>
	                    <a href="<?= $task->generatecompletionurl('true'); ?>" class="btn btn-primary btn-xs complete-task" data-id="<?= $task->id; ?>" role="button" title="Mark as Complete">
	                       <i class="material-icons md-18">&#xE86C;</i>
	                    </a>
	                </td>
	            </tr>
	        <?php endforeach; ?>
		<?php endif; ?>
    </tbody>
</table>
