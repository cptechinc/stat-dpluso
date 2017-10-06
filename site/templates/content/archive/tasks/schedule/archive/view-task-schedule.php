<?php
    $message = "Viewing Task Schedule for {replace} ";
    $tasktitle = createmessage($message, $custID, $shipID, $contactID, $taskID, $noteID, $ordn, $qnbr);
    $scheduledtasks = get_user_task_schedule($user->loginid, $custID, $shipID, $contactID, $config->showonpage, $input->pageNum(), false);
	$taskscheduler = new TaskScheduler();
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title" id="ajax-modal-label"><?= $tasktitle; ?></h4>
</div>
<div class="modal-body scroll">
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Start Date</th> <th>Active</th> <th>Description</th> <th>Frequency description</th> <th>Next Scheduled Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scheduledtasks as $scheduledtask) : ?>
                        <tr>
                            <td><?= date('m/d/Y', strtotime($scheduledtask['startdate'])); ?></td> <td><?= $scheduledtask['active']; ?></td>
                            <td><?= $scheduledtask['description']; ?></td> <td><?= $taskscheduler->writetaskfrequencydescription($scheduledtask['repeats'], $scheduledtask['interval'], $scheduledtask['fallson']); ?></td>
                            <td><?php echo $taskscheduler->getnextscheduledtaskdate($scheduledtask); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>