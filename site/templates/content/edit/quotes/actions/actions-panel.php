<?php
    $actionpanel = $page->useractionpanelfactory->create_actionpanel('quote', session_id(), $config->ajax, $config->modal, $input->get->text('action-status'));
    $actionpanel->setup_quotepanel($qnbr);
    $actionpanel->start_querylinks();
    $actionpanel->count_actions();
    
    if (file_exists($config->paths->content."actions/$config->cptechcustomer-actions-panel.php")) {
        include $config->paths->content."actions/$config->cptechcustomer-actions-panel.php";
    } else {
        include $config->paths->content."actions/actions-panel.php";
    }
