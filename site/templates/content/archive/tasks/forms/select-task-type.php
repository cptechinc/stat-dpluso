<?php foreach ($tasktypes as $tasktype) : ?>
	<?php if (isset($task)) : ?>
		<?php if ($tasktype['value'] == $task->tasktype) : ?>
			<button class="btn btn-primary select-button-choice btn-sm" type="button" data-value="<?= $tasktype['value']; ?>">
				<?= $tasktype['icon']." ".$tasktype['label']; ?>
			</button>
		<?php else : ?>
			<button class="btn btn-default select-button-choice btn-sm" type="button" data-value="<?= $tasktype['value']; ?>">
				<?= $tasktype['icon']." ".$tasktype['label']; ?>
			</button>
		<?php endif; ?>
	<?php else : ?>
		<button class="btn btn-default select-button-choice btn-sm" type="button" data-value="<?= $tasktype['value']; ?>">
			<?= $tasktype['icon']." ".$tasktype['label']; ?>
		</button>
	<?php endif; ?>

<?php endforeach; ?>

<input type="hidden" class="select-button-value required" name="tasktype" value="<?php echo $task->tasktype; ?>">
