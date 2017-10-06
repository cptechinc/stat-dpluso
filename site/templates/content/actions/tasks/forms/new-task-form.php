<?php
	$salespersonjson = json_decode(file_get_contents($config->companyfiles."json/salespersontbl.json"), true);
	$salespersoncodes = array_keys($salespersonjson['data']);
?>
<div>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#task" aria-controls="task" role="tab" data-toggle="tab">Task</a></li>
	</ul>
	<br>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="task">
			<form action="<?php echo $config->pages->actions."tasks/add/"; ?>" method="POST" id="new-action-form" data-refresh="#actions-panel" data-modal="#ajax-modal">
				<input type="hidden" name="action" value="write-task">
				<input type="hidden" name="custlink" value="<?= $tasklinks['customerlink']; ?>">
				<input type="hidden" name="shiptolink" value="<?= $tasklinks['shiptolink']; ?>">
				<input type="hidden" name="contactlink" value="<?= $tasklinks['contactlink']; ?>">
				<input type="hidden" name="salesorderlink" value="<?= $tasklinks['salesorderlink']; ?>">
				<input type="hidden" name="quotelink" value="<?= $tasklinks['quotelink']; ?>">
				<input type="hidden" name="notelink" value="<?= $tasklinks['notelink']; ?>">
				<input type="hidden" name="tasklink" value="<?= $tasklinks['tasklink']; ?>">
				<input type="hidden" name="actionlink" value="<?= $tasklinks['actionlink']; ?>">
				<div class="response"></div>
				<table class="table table-bordered table-striped">
					<tr>  <td class="control-label">Task Date:</td> <td><?php echo date('m/d/Y g:i A'); ?></td> </tr>
					<tr>
						<td class="control-label">Assigned To:</td>
						<td>
							<select name="assignedto" class="form-control input-sm" style="width: 200px;">
                                <?php foreach ($salespersoncodes as $salespersoncode) : ?>
									<?php if ($salespersonjson['data'][$salespersoncode]['splogin'] == $tasklinks['assignedto']) : ?>
										<option value="<?= $salespersonjson['data'][$salespersoncode]['splogin']; ?>" selected><?= $salespersoncode.' - '.$salespersonjson['data'][$salespersoncode]['spname']; ?></option>
									<?php else : ?>
										<option value="<?= $salespersonjson['data'][$salespersoncode]['splogin']; ?>"><?= $salespersoncode.' - '.$salespersonjson['data'][$salespersoncode]['spname']; ?></option>
									<?php endif; ?>

                                <?php endforeach; ?>
                            </select>
						</td>
					</tr>
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
							<?php include $config->paths->content."actions/tasks/forms/select-task-type.php"; ?>
						</td>
					</tr>
					<tr>
						<td class="control-label">Title</td>
						<td>
							<input type="text" name="title" class="form-control">
						</td>
					</tr>
					<tr>
						<td colspan="2" class="control-label">
							<label for="" class="control-label">Notes</label>
							<textarea name="textbody" id="note" cols="30" rows="10" class="form-control note required"> </textarea> <br>
							<button type="submit" class="btn btn-success">Create Task</button>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
