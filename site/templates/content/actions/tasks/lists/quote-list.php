<?php $tasks = getuseractions($user->loginid, $actionpanel->querylinks, $config->showonpage, $input->pageNum(), false); ?>

<div class="panel-body">
	<div class="row">
		<div class="col-xs-4">
			<label for="view-task-status">View Completed Tasks</label>
			<select name="" id="view-action-completion-status" class="form-control input-sm" <?= $actionpanel->data; ?> data-url="<?= $actionpanel->getactiontyperefreshlink(true); ?>" >
				<?php foreach ($actionpanel->taskstatuses as $status => $label) : ?>
					<?php if ($status == $actionpanel->taskstatus) : ?>
						<option value="<?= $status; ?>" selected><?= $label; ?></option>
					<?php else : ?>
						<option value="<?= $status; ?>"><?= $label; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
</div>

<table class="table table-bordered table-condensed table-striped">
	<thead>
    	<tr> <th>Due</th> <th>Type</th> <th>Subtype</th> <th>Note</th> <th>View action</th> <th>Complete Action</th>  </tr>
    </thead>
    <tbody>
		<?php if (!sizeof($tasks)) : ?>
			<tr>
				<td colspan="6" class="text-center h4">
					No related tasks found
				</td>
			</tr>
		<?php else : ?>
			<?php foreach ($tasks as $task) : ?>
				<?php if ($task->isoverdue && (!$task->isrescheduled)) {$class="bg-warning";} else {$class = "";} ?>
	            <tr class="<?= $class; ?>">
					<td><?= $task->displayduedate('m/d/Y'); ?></td>
					<td><?= $task->actiontype; ?></td>
					<td><?= $task->getactionsubtypedescription(); ?></td>
	                <td><?= $task->title; ?></td>
					<td>
						<a href="<?= $task->generateviewactionurl(); ?>" role="button" class="btn btn-xs btn-primary load-action" data-modal="<?= $actionpanel->modal; ?>" title="View Task">
							<i class="material-icons md-18">&#xE02F;</i>
						</a>
					</td>
					<td>
						<?php if (!$task->isrescheduled && !$task->hascompleted) : ?>
							<a href="<?= $task->generateviewactionjson(); ?>" role="button" class="btn btn-xs btn-primary complete-action" data-modal="<?= $actionpanel->modal; ?>" title="Mark Task as Complete">
								<i class="fa fa-check-circle"></i> <span class="sr-only">Mark as Complete</span>
							</a>
						<?php endif; ?>
					</td>
	            </tr>
	        <?php endforeach; ?>
		<?php endif; ?>
    </tbody>
</table>
