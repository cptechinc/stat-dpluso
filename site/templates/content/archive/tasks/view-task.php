<?php
    $taskid = $input->get->id;
    $task = loadtask($taskid, false);

    if ($task->hasnotelink) {
        $note = loadcrmnote($task->notelink, false);
    }

    if ($task->hascontactlink) { //DOESNT MATTER DEPRECATE
        $contactinfo = get_customercontact($task->customerlink, $task->shiptolink, $task->contactlink, false);
    } else {
        $contactinfo = get_customercontact($task->customerlink, $task->shiptolink, $task->contactlink, false);
    }



?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title" id="notes-modal-label">Viewing Task for <?= get_customername($task->customerlink); ?> </h4>
</div>
<div class="modal-body">
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#task" aria-controls="task" role="tab" data-toggle="tab">Task ID: <?= $taskid; ?></a></li>
            <?php if ($task->hasnotelink) : ?>
                <li role="presentation"><a href="#note" aria-controls="note" role="tab" data-toggle="tab">Note</a></li>
            <?php endif; ?>
            <?php if ($task->hastasklink) : ?>
                <li role="presentation"><a href="#task-lineage" aria-controls="parent task" role="tab" data-toggle="tab">Parent Task</a></li>
            <?php endif; ?>
        </ul>
        <br>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="task"><?php include $config->paths->content."tasks/view-task/view-task-details.php"; ?></div>
            <?php if ($task->hasnotelink) : ?>
                <div role="tabpanel" class="tab-pane" id="note"><?php include $config->paths->content."notes/crm/read-note-table.php"; ?></div>
            <?php endif; ?>
            <?php if ($task->hastasklink) : ?>
                <div role="tabpanel" class="tab-pane" id="task-lineage"><?php include $config->paths->content."tasks/view-task/view-task-lineage.php"; ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
