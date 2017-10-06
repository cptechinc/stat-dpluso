<?php

	$custID = $input->get->text('custID');
	if ($input->get->shipID) { $shipID = $input->get->text('shipID'); }
	if ($input->get->contactID) { $shipID = $input->get->text('contactID'); }
	if ($input->get->task) { $taskID = $input->get->text('task'); }
	if ($input->get->ordn) { $ordn = $input->get->text('ordn'); }
	if ($input->get->qnbr) { $qnbr = $input->get->text('qnbr'); }

	$note = new Note();
	$note->customerlink = $custID;
	$note->shiptolink = $shipID;
	$note->contactlink = $contactID;
	$note->salesorderlink = $ordn;
	$note->quotelink = $qnbr;
	$note->tasklink = $taskID;
	$note->update();

    $message = "Writing Note for {replace} ";
    $notetitle = createmessage($message, $custID, $shipID, $contactID, $taskID, $noteID, $ordn, $qnbr);
	if ($note->hastasklink) {
		$task = loadtask($taskID, false);

		if ($task->hascontactlink) { //DOESNT MATTER DEPRECATE
			$contactinfo = get_customercontact($task->customerlink, $task->shiptolink, $task->contactlink, false);
		} else {
			$contactinfo = get_customercontact($task->customerlink, $task->shiptolink, $task->contactlink, false);
		}
	}

?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title" id="ajax-modal-label"><?php echo $notetitle; ?> </h4>
</div>
<div class="modal-body">
	<div class="response"></div>
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#note" aria-controls="note" role="tab" data-toggle="tab">Note</a></li>
            <?php if ($input->get->task) : ?>
                <li role="presentation"><a href="#task" aria-controls="task" role="tab" data-toggle="tab">Task</a></li>
            <?php endif; ?>
        </ul>
        <br>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="note"><?php include $config->paths->content."notes/crm/new-note-table.php"; ?></div>
            <?php if ($input->get->task) : ?>
                <div role="tabpanel" class="tab-pane" id="task"><?php include $config->paths->content."tasks/view-task/view-task-details.php"; ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
