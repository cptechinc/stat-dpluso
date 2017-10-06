<?php
    $custID = $shipID = $contactID = $ordn = $qnbr = $actionID = $noteID = $taskID = $assigneduserID = '';
    if ($input->get->custID) { $custID = $input->get->text('custID'); }
    if ($input->get->shipID) { $shipID = $input->get->text('shipID'); }
    if ($input->get->contactID) { $contactID = $input->get->text('contactID'); }

    if ($input->get->ordn) { $ordn = $input->get->text('ordn'); }
    if ($input->get->qnbr) { $qnbr = $input->get->text('qnbr'); }

    if ($input->get->actionID) { $actionID = $input->get->text('actionID'); }
    if ($input->get->taskID) { $taskID = $input->get->text('taskID'); }
    if ($input->get->noteID) { $noteID = $input->get->text('noteID'); }
    if ($input->get->assignedto) { $assigneduserID = $input->get->text('assignedto'); } else {$assigneduserID = $user->loginid;}

    $actionsubtype = $page->name;
    include $config->paths->content.'actions/'.$actionsubtype.'/crud-controller.php';
