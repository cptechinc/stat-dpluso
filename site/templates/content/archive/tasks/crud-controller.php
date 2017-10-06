<?php
	$custID = ''; $shipID = ''; $contactID = ''; $ordn = ''; $qnbr = ''; $taskID = ''; $noteID = '';

	if ($input->get->custID) { $custID = $input->get->text('custID'); }
	if ($input->get->shipID) { $shipID = $input->get->text('shipID'); }
	if ($input->get->contactID) { $contactID = $input->get->text('contactID'); }
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
							include $config->paths->content."tasks/new-task.php";
							break;
						default:
							include $config->paths->content."tasks/crud/add.php";
							break;
					}
				} else {
					include $config->paths->content."tasks/crud/add.php";
					break;
				}

                break;
            case 'update':
				switch($input->urlSegment2) {
                    case 'completion':
                        include $config->paths->content."tasks/crud/update-completion.php";
                        break;
                    case 'reschedule':
                        include $config->paths->content."tasks/crud/reschedule.php";
                        break;
                }
                break;
            case 'load':
				if ($input->urlSegment2) {
					switch($input->urlSegment2) {
						case 'list':
							if ($input->urlSegment3) {
								switch($input->urlSegment3) {
									case 'cust':
										include $config->paths->content.'customer/cust-page/tasks/tasks-panel.php';
										break;
									case 'contact':
										include $config->paths->content.'customer/contact/tasks-panel.php';
										break;
									case 'user':
										include $config->paths->content.'dashboard/tasks/tasks-panel.php';
										break;
									case 'quote':
										include $config->paths->content.'edit/quotes/tasks/tasks-panel.php';
										break;
								}
							}
							break;
						default:
						if ($config->ajax) {
							include $config->paths->content."tasks/view-task.php";
						} else {
							$title = 'Task ID: ' . $input->get->text('id');
							$modalbody = $config->paths->content."tasks/view-task.php";
							include $config->paths->content."common/include-blank-page.php";
						}
							break;
					}
				} else {
					if ($config->ajax) {
						include $config->paths->content."tasks/view-task.php";
					} else {
						$title = 'Task ID: ' . $input->get->text('id');
						$modalbody = $config->paths->content."tasks/view-task.php";
						include $config->paths->content."common/include-blank-page.php";
					}

					break;
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
