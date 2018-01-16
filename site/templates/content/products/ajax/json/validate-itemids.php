<?php
    $invalidcount = 0;
    
    if ($input->post->itemID) {
        $items = $input->post->itemID;
        $custID = $input->post->text('custID');
        $itemexistsarray = array();
        
        foreach ($items as $item) {
            $valid = validateitemid($sanitizer->text($item), $custID, false);
            if (!$valid) {
                $invalidcount++;
            }
            $itemid = $sanitizer->text($item);
            $itemexists["$itemid"] = !empty($valid);
        }
        
        if ($invalidcount < 1) {
            $response = array (
                'error' => false,
                'invalid' => $invalidcount,
                'items' => $itemexists
            );
        } else {
            if (empty($custID)) {
                $msg = 'No items with the itemIDs ' . implode($items, ', ') . ' have been found';
            } else {
                $msg = 'No items with the itemIDs ' . implode($items, ', ') . ' have been found with also using customer X-ref '.$custID.'';
            }
            
            $response = array (
                'error' => false,
                'invalid' => $invalidcount,
                'msg' => $msg,
                'items' => $itemexists
            );
        }
    } else {
        $response = array (
            'error' => true,
            'errortype' => 'client',
            'msg' => 'No itemIDs were provided'
        );
    }

    echo json_encode($response);
 ?>
