<?php
    $daydistinctions = array('AM', 'PM');
    $minutes = array('00', '15', '30', '45');
    $hours = array('12', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11');
?>
<div>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#action" aria-controls="task" role="tab" data-toggle="tab">Action</a></li>
	</ul>
	<br>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="action">
			<form action="<?= $config->pages->actions."actions/add/"; ?>" method="POST" id="new-action-form" data-refresh="#actions-panel" data-modal="#ajax-modal">
				<input type="hidden" name="action" value="write-action">
                <input type="hidden" name="custlink" value="<?= $actionlinks['customerlink']; ?>">
            	<input type="hidden" name="shiptolink" value="<?= $actionlinks['shiptolink']; ?>">
            	<input type="hidden" name="contactlink" value="<?= $actionlinks['contactlink']; ?>">
            	<input type="hidden" name="salesorderlink" value="<?= $actionlinks['salesorderlink']; ?>">
            	<input type="hidden" name="quotelink" value="<?= $actionlinks['quotelink']; ?>">
            	<input type="hidden" name="notelink" value="<?= $actionlinks['notelink']; ?>">
            	<input type="hidden" name="tasklink" value="<?= $actionlinks['tasklink']; ?>">
            	<input type="hidden" name="actionlink" value="<?= $actionlinks['actionlink']; ?>">
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
                            <div class="input-group input-append dropdown combobox" data-initialize="combobox" style="width: 180px;">
                    			<input type="text" class="form-control input-sm" name="actiontime">
                    			<div class="input-group-btn">
                    				<button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                    				<ul class="dropdown-menu dropdown-menu-right" style=" max-height: 200px; overflow: auto;">
                    					<?php foreach ($daydistinctions as $timeofday) : ?>
                    						<?php foreach ($hours as $hour) : ?>
                    							<?php foreach ($minutes as $minute) : ?>
                    								<li data-value="<?= $hour.':'.$minute.' '.$timeofday; ?>"><a href="#"><?= $hour.':'.$minute.' '.$timeofday; ?></a></li>
                    							<?php endforeach; ?>
                    						<?php endforeach; ?>
                    					<?php endforeach; ?>
                    				</ul>
                    			</div>
                    		</div>
                        </td>
                    </tr>
					<tr>
						<td class="control-label">Action Type <br><small>(Click to choose)</small></td>
						<td>
							<?php include $config->paths->content."actions/actions/forms/select-action-type.php"; ?>
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
