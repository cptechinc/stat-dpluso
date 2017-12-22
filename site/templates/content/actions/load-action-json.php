<?php
    header('Content-Type: application/json');
    
    $actionID = $input->get->id;
    $actiondisplay = new UserActionDisplay($page->fullURL);
    $action = UserAction::get($actionID);

    if ($action) {
        $urls = array(
            'completion' => $actiondisplay->generate_completionurl($action, 'true'),
            'view' => $actiondisplay->generate_viewactionurl($action),
            'reschedule' => $actiondisplay->generate_rescheduleurl($action)
        );
        $action->urls = $urls;
        echo json_encode(array(
            'response' => array(
                'error' => false,
                'action' => $action
            )
        ));
    } else {
        echo json_encode( array(
            'response' => array(
                'error' => true,
                'message' => 'Error finding Action with ID ' . $actionID
            )
        ));
    }
