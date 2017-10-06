<?php
    $taskID = $input->get->text('id');
    $originaltask = loadtask($taskID, false);
    $custID = $originaltask->customerlink;
    $shipID = $originaltask->shiptolink;
    $contactID = $originaltask->contactlink;
    $ordn = $originaltask->salesorderlink;
    $qnbr = $originaltask->quotelink;
    $noteID = $originaltask->salesorderlink;
    $taskID = $originaltask->id;
    $task = Task::blanktask($custID, $shipID, $contactID, $ordn, $qnbr, $noteID, $taskID);


    if ($task->hasnotelink) {
        $note = loadcrmnote($task->notelink, false);
    }
    if ($config->ajax) {
        $message = "Rescheduling a task for {replace} ";
        $modaltitle = createmessage($message, $custID, $shipID, $contactID, $taskID, $noteID, $ordn, $qnbr);
        $modalbody = $config->paths->content."tasks/forms/reschedule-task-form.php";
        include $config->paths->content."common/modals/include-ajax-modal.php";
    } else {
        include $config->paths->content."tasks/forms/reschedule-task-form.php";
    }
