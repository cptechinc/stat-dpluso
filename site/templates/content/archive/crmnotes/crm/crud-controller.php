<?php
	$custID = ''; $shipID = ''; $contactID = ''; $ordn = ''; $qnbr = ''; $taskID = ''; $noteID = '';

	if ($input->get->custID) { $custID = $input->get->text('custID'); }
	if ($input->get->shipID) { $shipID = $input->get->text('shipID'); }
	if ($input->get->contactID) { $shipID = $input->get->text('contactID'); }
	if ($input->get->task) { $taskID = $input->get->text('task'); }
	if ($input->get->ordn) { $ordn = $input->get->text('ordn'); }
	if ($input->get->qnbr) { $qnbr = $input->get->text('qnbr'); }
	if ($input->get->taskID) { $taskID = $input->get->text('taskID'); }


    if ($input->urlSegment1) {
        switch($input->urlSegment1) {
            case 'add':
				if ($input->urlSegment2) {
					switch($input->urlSegment2) {
						case 'new':
							include $config->paths->content."notes/crm/new-note.php";
							break;
						default:
							include $config->paths->content."notes/crm/crud/add.php";
							break;
					}
				} else {
					include $config->paths->content."notes/crm/crud/add.php";
				}

                break;
            case 'update':

                break;
            case 'load':
				if ($input->urlSegment2) {
					switch($input->urlSegment2) {
						case 'list':
							if ($input->urlSegment3) {
								switch($input->urlSegment3) {
									case 'cust':
										include $config->paths->content.'customer/cust-page/notes/notes-panel.php';
										break;
									case 'user':
										include $config->paths->content.'dashboard/notes/notes-panel.php';
										break;
									case 'quote':
										include $config->paths->content.'edit/quotes/notes/notes-panel.php';
										break;
								}
							}
							//include $config->paths->content."tasks/crud/load-task-panel.php"; // TODO
							break;
						default:
							include $config->paths->content."notes/crm/load/modal/read-note.php";
							break;
					}
				} else {
					include $config->paths->content."notes/crm/load/modal/read-note.php";
				}

                break;
            default:
                throw new Wire404Exception();
                break;
        }
    } else {
        throw new Wire404Exception();
    }

?>
