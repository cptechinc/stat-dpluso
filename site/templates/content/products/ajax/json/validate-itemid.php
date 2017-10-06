<?php
    if ($input->get->itemID) {
        $itemID = $input->get->text('itemID');
        $custID = $input->get->text('custID');
        $valid = validateitemid($itemID, $custID, false);
        if ($valid) {
            $response = array (
                'error' => false,
                'itemexists' => true,
            );
        } else {
            if (empty($custID)) {
                $msg = 'No item with the itemID ' . $itemID . ' has been found';
            } else {
                $msg = 'No item with the itemID ' . $itemID . ' has been found with also using customer X-ref '.$custID.'';
            }
            $response = array (
                'error' => false,
                'itemexists' => false,
                'msg' => $msg
            );
        }
    } else {
        $response = array (
                'error' => true,
                'errortype' => 'client',
                'msg' => 'No itemID was provided'
            );

    }

    echo json_encode($response);
 ?>
