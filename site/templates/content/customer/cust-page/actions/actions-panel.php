<?php
    $actionpanel = $page->useractionpanelfactory->create_actionpanel('cust', session_id(), $config->ajax, $config->modal, $input->get->text('action-status'));
    $actionpanel->setup_customerpanel($custID, $shipID);
    $actionpanel->set_querylinks();
    $actionpanel->count_actions();
    
    include $config->paths->content."actions/actions-panel.php";
