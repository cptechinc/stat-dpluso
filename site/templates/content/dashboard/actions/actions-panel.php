<?php
    $actionpanel = $page->useractionpanelfactory->create_actionpanel('user', session_id(), $config->ajax, $config->modal, $input->get->text('action-status'));
    $actionpanel->count_actions();
    
    include $config->paths->content."actions/actions-panel.php";
