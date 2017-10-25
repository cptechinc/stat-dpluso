<?php
    $actionID = $input->get->text('id');
    $originaltask = loaduseraction($actionID, true, false);
    $tasklinks = UserAction::getlinkarray();
	$tasklinks['actiontype'] = 'task';
	$tasklinks['customerlink'] = $originaltask->customerlink;
	$tasklinks['shiptolink'] = $originaltask->shiptolink;
	$tasklinks['contactlink'] = $originaltask->contactlink;
	$tasklinks['salesorderlink'] = $originaltask->salesorderlink;
	$tasklinks['quotelink'] = $originaltask->quotelink;
	$tasklinks['notelink'] = $originaltask->notelink;
	$tasklinks['tasklink'] = $originaltask->tasklink;
	$tasklinks['actionlink'] = $originaltask->id;
    $tasklinks['actionsubtype'] = $originaltask->actionsubtype;

    $task = UserAction::blankuseraction($tasklinks);


    if ($config->ajax) {
        $message = "Rescheduling a task for {replace}";
    	$page->title = $task->createmessage($message);
        $page->body = $config->paths->content."actions/tasks/forms/reschedule-task-form.php";
        include $config->paths->content."common/modals/include-ajax-modal.php";
    } else {
        include $config->paths->content."actions/tasks/forms/reschedule-task-form.php";
    }
