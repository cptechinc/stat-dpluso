<?php
    header('Content-Type: application/json');
    $taskid = $input->get->id;
    if ($input->get->text('complete') == 'true') {
        $completedate = date("Y-m-d H:i:s");
        $complete = 'Y';
    } else {
        $completedate = '0000-00-00 00:00:00';
        $complete = 'N';
    }

    $updatedate = date("Y-m-d H:i:s");


	$response = updatetaskcompletion($taskid, $completedate, $updatedate, $complete);
	$session->sql = $response['sql'];
	$response['request_type'] = 'update';
	$response['taskid'] = $taskid;

	echo json_encode($response);

?>
