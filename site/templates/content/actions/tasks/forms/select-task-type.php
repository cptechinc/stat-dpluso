<?php $tasktypes = $pages->get('/activity/tasks/')->children(); ?>
<?php foreach ($tasktypes as $tasktype) : ?>
	<?php if (isset($task)) : ?>
		<?php if ($tasktype->name == $task->actionsubtype) : ?>
			<button class="btn btn-primary select-button-choice btn-sm" type="button" data-value="<?= $tasktype->name; ?>">
				<?= $tasktype->subtypeicon." ".$tasktype->actionsubtypelabel; ?>
			</button>
		<?php else : ?>
			<button class="btn btn-default select-button-choice btn-sm" type="button" data-value="<?= $tasktype->name; ?>">
				<?= $tasktype->subtypeicon." ".$tasktype->actionsubtypelabel; ?>
			</button>
		<?php endif; ?>
	<?php else : ?>
		<button class="btn btn-default select-button-choice btn-sm" type="button" data-value="<?= $tasktype->name; ?>">
			<?= $tasktype->subtypeicon." ".$tasktype->actionsubtypelabel; ?>
		</button>
	<?php endif; ?>
<?php endforeach; ?>

<input type="hidden" class="select-button-value required" name="tasktype" value="<?php echo $task->actionsubtype; ?>">
