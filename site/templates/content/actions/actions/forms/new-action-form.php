<?php
    $editactiondisplay = new EditUserActionsDisplay($page->fullURL);
?>
<div>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#action" aria-controls="task" role="tab" data-toggle="tab">Action</a></li>
	</ul>
	<br>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="action">
			<form action="<?= $config->pages->actions."actions/add/"; ?>" method="POST" id="new-action-form" data-refresh="#actions-panel" data-modal="#ajax-modal" onKeyPress="return disable_enterkey(event)">
				<input type="hidden" name="action" value="write-action">
                <input type="hidden" name="customerlink" value="<?= $action->customerlink; ?>">
            	<input type="hidden" name="shiptolink" value="<?= $action->shiptolink; ?>">
            	<input type="hidden" name="contactlink" value="<?= $action->contactlink; ?>">
            	<input type="hidden" name="salesorderlink" value="<?= $action->salesorderlink; ?>">
            	<input type="hidden" name="quotelink" value="<?= $action->quotelink; ?>">
            	<input type="hidden" name="actionlink" value="<?= $action->actionlink; ?>">
				<div class="response"></div>
				<table class="table table-bordered table-striped">
					<?php include $config->paths->content."common/show-linked-table-rows.php"; ?>
					<tr>
						<td class="control-label">Action Date</td>
						<td>
							<div class="input-group date" style="width: 180px;">
								<?php $name = 'actiondate'; $value = date('m/d/Y'); ?>
								<?php include $config->paths->content."common/date-picker.php"; ?>
							</div>
						</td>
					</tr>
                    <tr>
                        <td class="control-label">Action Time</td>
                        <td>
                            <input type="text" class="form-control input-sm timepicker" name="actiontime" style="width: 180px;">
                        </td>
                    </tr>
					<tr>
						<td class="control-label">Action Type <br><small>(Click to choose)</small></td>
						<td>
                            <?= $editactiondisplay->generate_selectsubtype($action); ?>
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
							<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Record Action</button>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
