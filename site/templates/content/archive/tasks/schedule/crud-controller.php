<?php
	$custID = ''; $shipID = ''; $contactID = ''; $ordn = ''; $qnbr = ''; $taskID = ''; $noteID = '';
	
	if ($input->get->custID) { $custID = $input->get->text('custID'); }
	if ($input->get->shipID) { $shipID = $input->get->text('shipID'); } 
	if ($input->get->contactID) { $shipID = $input->get->text('contactID'); } 
	if ($input->get->task) { $taskID = $input->get->text('task'); } 
	if ($input->get->ordn) { $ordn = $input->get->text('ordn'); }
	if ($input->get->qnbr) { $qnbr = $input->get->text('qnbr'); }
	if ($input->get->noteID) { $noteID = $input->get->text('noteID'); }

    if ($input->urlSegment1) {
        switch($input->urlSegment1) {
            case 'add':
				if ($input->urlSegment2) {
					switch($input->urlSegment2) {
						case 'new':
							include $config->paths->content."tasks/schedule/task-scheduler-form.php";
							break;
						default: 
							include $config->paths->content."tasks/schedule/crud/add.php";
							break;
					}
				} else {
					include $config->paths->content."tasks/schedule/crud/add.php";
					break;
				}
                
                break;
            case 'update':
				switch($input->urlSegment2) {
                    case 'completion':
                       // include $config->paths->content."tasks/update-completion.php";
                        break;
                }
                break;
            case 'load':
				if ($input->urlSegment2) {
					switch($input->urlSegment2) {
						case 'list':
							include $config->paths->content."tasks/schedule/view-task-schedule.php";
							break;
						default: 
							include $config->paths->content."tasks/schedule/view-task-schedule.php";
							break;
					}
				} else {
					include $config->paths->content."tasks/schedule/view-task-schedule.php";
				}
                
                break;
			case 'schedule-tasks':
				include $config->paths->content."tasks/schedule/schedule-tasks.php";
				break;
            default:
                throw new Wire404Exception();
                break;
        }
    } else {
        throw new Wire404Exception();
    }

?>