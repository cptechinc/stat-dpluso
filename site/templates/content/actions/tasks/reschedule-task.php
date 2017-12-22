<?php
    $actionID = $input->get->text('id');
    $task = UserAction::get($actionID);
    
    $message = "Rescheduling a task for {replace}";
    $page->title = $task->generate_message($message);
    $page->body = $config->paths->content."actions/tasks/forms/reschedule-task-form.php";
    
    if ($config->ajax) {
        include $config->paths->content."common/modals/include-ajax-modal.php";
    } else {
        include $page->body;
    }
