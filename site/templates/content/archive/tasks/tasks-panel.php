<?php
	$config->scripts->append($config->urls->templates.'scripts/task-scheduler.js');
	$ajax = new stdClass();
	$ajax->link = $taskpanel->getpanelrefreshlink();
	$ajax->data = $taskpanel->data;
	$ajax->insertafter = $taskpanel->getinsertafter();

	$totalcount = $taskpanel->count;

	$popover_content = "<div class='form-group'><a href='".$taskpanel->getpanelnewtaskschedulelink()."' class='btn btn-info load-task-item' data-modal='".$taskpanel->modal."'  role='button' title='Schedule Task'> <i class='material-icons md-18'>&#xE192;</i> Create a New Scheduled Task</a></div>";
	$popover_content .= "<div class='form-group'><a href='".$taskpanel->getpanelloadtaskschedulelink()."' class='btn btn-info load-task-item' data-modal='".$taskpanel->modal."' role='button' title='View Scheduled Tasks'> <i class='material-icons md-18'>&#xE85D;</i> View List of Scheduled Tasks</a></div>";
?>

<div class="panel panel-primary not-round" id="tasks-panel">
    <div class="panel-heading not-round" id="task-panel-heading">
    	<a href="#tasks-div" class="panel-link" data-parent="#tasks-panel" data-toggle="collapse">
        	<span class="glyphicon glyphicon-check"></span> &nbsp; <?php echo $taskpanel->getpaneltitle(); ?> <span class="caret"></span>  &nbsp;&nbsp;<span class="badge"><?= $taskpanel->count; ?></span>
        </a>

		<?php if ($taskpanel->needsaddtasklink()) : ?>
			<a href="<?= $taskpanel->getaddtasklink(); ?>" class="btn btn-info btn-xs load-task-item add-new-task pull-right hidden-print" data-modal="<?= $taskpanel->modal; ?>" role="button" title="Add Task">
	            <i class="material-icons md-18">&#xE146;</i>
	        </a>
		<?php endif; ?>

        <span class="pull-right">&nbsp; &nbsp;&nbsp; &nbsp;</span>
        <a tabindex="0" class="btn btn-info btn-xs pull-right task-popover hidden-print" <?= $ajax->data; ?> role="button" data-toggle="popover" data-html="true" data-placement="top" data-trigger="focus" title="Schedule Tasks" data-content="<?= $popover_content; ?>"><i class="material-icons md-18">&#xE192;</i> <span class="hidden-xs">Schedule Tasks</span>
        </a>

        <span class="pull-right">&nbsp; &nbsp;&nbsp; &nbsp;</span>
        <a href="<?= $taskpanel->getpanelrefreshlink(); ?>" class="btn btn-info btn-xs load-link tasks-refresh pull-right hidden-print" <?= $ajax->data; ?> role="button" title="Refresh Tasks">
            <i class="material-icons md-18">&#xE86A;</i>
        </a>
        <span class="pull-right"><?php if ($input->pageNum > 1 ) {echo 'Page '.$input->pageNum;} ?> &nbsp; &nbsp;</span>
    </div>
    <div id="tasks-div" class="<?= $taskpanel->collapse; ?>">
        <div>
        	<div class="panel-body">
				<div class="row">
					<div class="col-xs-5">
						<label for="view-task-status">View Completed Tasks</label>
						<select name="" id="view-task-status" class="form-control input-sm" <?= $taskpanel->data; ?> data-url="<?= $taskpanel->getpanelrefreshlink(); ?>" >
							<?php foreach ($taskpanel->statuses as $status => $label) : ?>
								<?php if ($status == $taskpanel->taskstatus) : ?>
									<option value="<?= $status; ?>" selected><?= $label; ?></option>
								<?php else : ?>
									<option value="<?= $status; ?>"><?= $label; ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
            </div>
             <?php include $config->paths->content.'pagination/ajax/pagination-start-no-form.php'; ?>
             <?php include $config->paths->content.'tasks/task-list/'.$taskpanel->type.'-task-list.php'; ?>
             <?php $totalpages = ceil($totalcount / $config->showonpage); ?>
             <?php include $config->paths->content.'pagination/ajax/pagination-links.php'; ?>
        </div>
    </div>
</div>
