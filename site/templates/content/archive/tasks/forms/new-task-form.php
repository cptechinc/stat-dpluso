<div>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#task" aria-controls="task" role="tab" data-toggle="tab">Task</a></li>
		<?php if ($noteID != '') : ?>
			<li role="presentation"><a href="#note" aria-controls="note" role="tab" data-toggle="tab">Note</a></li>
		<?php endif; ?>
	</ul>
	<br>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="task">
			<form action="<?php echo $config->pages->tasks."add/"; ?>" class="fuel-ux" method="POST" id="new-task-form" data-refresh="#tasks-panel" data-modal="#ajax-modal">
				<input type="hidden" name="action" value="write-task">
				<input type="hidden" name="custlink" value="<?php echo $custID; ?>">
				<input type="hidden" name="shiptolink" value="<?php echo $shipID; ?>">
				<input type="hidden" name="contactlink" value="<?php echo $contactID; ?>">
				<input type="hidden" name="salesorderlink" value="<?php echo $ordn; ?>">
				<input type="hidden" name="quotelink" value="<?php echo $qnbr; ?>">
				<input type="hidden" name="notelink" value="<?php echo $noteID; ?>">
				<div class="response"></div>
				<table class="table table-bordered table-striped">
					<tr>  <td>Task Date:</td> <td><?php echo date('m/d/Y g:i A'); ?></td> </tr>
					<?php include $config->paths->content."common/show-linked-table-rows.php"; ?>

					<tr>
						<td class="control-label">Due Date</td>
						<td>
							<div class="input-group date" style="width: 180px;">
								<?php $name = 'duedate'; $value = ''; ?>
								<?php include $config->paths->content."common/date-picker.php"; ?>
							</div>
						</td>
					</tr>
					<tr>
						<td class="control-label">Task Type <br><small>(Click to choose)</small></td>
						<td>
							<?php include $config->paths->content."tasks/forms/select-task-type.php"; ?>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="control-label">
							<label for="" class="control-label">Notes</label>
							<textarea name="textbody" id="" cols="30" rows="10" class="form-control note required"> </textarea> <br>
							<button type="submit" class="btn btn-success">Create Task</button>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<?php if ($task->hasnotelink) : ?>
			<div role="tabpanel" class="tab-pane" id="note"><?php include $config->paths->content."notes/crm/read-note-table.php"; ?></div>
		<?php endif; ?>
	</div>
</div>
