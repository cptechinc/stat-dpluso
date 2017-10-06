<?php
    header('Content-Type: application/json');
    $actionID = $input->get->id;
    $action = loaduseraction($actionID, true, false); // (id, bool fetchclass, bool debug)

    if ($action) {
        $urls = array(
            'completion' => $action->generatecompletionurl('true'),
            'view' => $action->generateviewactionurl(),
            'reschedule' => $action->generaterescheduleurl()
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
