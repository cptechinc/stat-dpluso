<?php
    header('Content-Type: application/json');
    $taskID = $input->get->id;
    $task = loaduseraction($taskID, false, false); // (id, bool fetchclass, bool debug)

    if ($input->get->text('complete') == 'true') {
        $task['datecompleted'] = date("Y-m-d H:i:s");
        $task['completed'] = 'Y';
    } else {
        $task['datecompleted'] = '0000-00-00 00:00:00';
        $task['completed'] = ' ';
    }

    if ($input->post) {
        $task['reflectnote'] = $input->post->text('reflectnote');
    }

    $task['dateupdated'] = date("Y-m-d H:i:s");



    $response = updateaction($taskID, $task, false);
	$session->sql = $response['sql'];
	$response['request_type'] = 'update';
	$response['taskid'] = $taskID;

	echo json_encode($response);

?>
