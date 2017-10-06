<?php
    $actiontype = "task";
	if ($input->get->modal) {
		$partialid = 'actions-modal';
	} else {
		$partialid = 'actions';
	}
    switch ($input->urlSegment1) {
        case 'add':
            switch($input->urlSegment2) {
                case 'new':
                    if (file_exists($config->paths->content."actions/tasks/$config->cptechcustomer-new-task.php")) {
                        include $config->paths->content."actions/tasks/$config->cptechcustomer-new-task.php";
                    } else {
                        include $config->paths->content."actions/tasks/new-task.php";
                    }
                    break;
                default:
                    include $config->paths->content."actions/tasks/crud/add-task.php";
                    break;
            }
            break;
        case 'load':
            switch ($input->urlSegment2) {
                case 'list':
                    if (!$config->ajax) {
    					$actionpanel = new UserActionPanel($input->urlSegment3, 'task', $partialid, '#ajax-modal', $config->ajax, $config->modal);
                        $actionpanel->setuptasks($input->get->text('action-status'));
                        $actionpanel->querylinks = UserAction::getlinkarray();
                        $actionpanel->querylinks['assignedto'] = $user->loginid;
                        $actionpanel->querylinks['actiontype'] = 'task';
                        $actionpanel->querylinks['completed'] = $actionpanel->databasetaskstatus();
                    }

                    switch($input->urlSegment3) {
                        case 'user':
                            if ($config->ajax) {
        						include $config->paths->content.'dashboard/actions/actions-panel.php';
        					} else {
        						$page->title = 'Viewing User Task List';
        						$page->body = $config->paths->content.'actions/tasks/lists/user-list.php';
        						include $config->paths->content."common/include-blank-page.php";
        					}
                            break;
                        case 'cust':
                            if ($config->ajax) {
        						include $config->paths->content.'customer/cust-page/actions/actions-panel.php';
        					} else {
                                $actionpanel->setupcustomerpanel($custID, $shipID);
                                $actionpanel->querylinks['customerlink'] = $custID;
                                $actionpanel->querylinks['shiptolink'] = $shipID;
        						$page->title = 'Viewing User Task List';
        						$page->body = $config->paths->content.'actions/tasks/lists/cust-list.php';
        						include $config->paths->content."common/include-blank-page.php";
        					}
                            break;
						case 'contact':
                            if ($config->ajax) {
        						include $config->paths->content.'customer/contact/actions-panel.php';
        					} else {
                                $actionpanel->setupcontactpanel($custID, $shipID, $contactID);
                                $actionpanel->querylinks['customerlink'] = $custID;
                                $actionpanel->querylinks['shiptolink'] = $shipID;
								$actionpanel->querylinks['contactlink'] = $contactID;
        						$page->title = 'Viewing User Actions List';
        						$page->body = $config->paths->content.'actions/tasks/lists/contact-list.php';
        						include $config->paths->content."common/include-blank-page.php";
        					}
                            break;
                        case 'order':
                            if ($config->ajax) {
        						include $config->paths->content.'edit/orders/actions/actions-panel.php';
        					} else {
                                $actionpanel->setuporderpanel($ordn);
                                $actionpanel->querylinks['salesorderlink'] = $ordn;
        						$page->title = 'Viewing Order #'.$ordn.' Task List';
        						$page->body = $config->paths->content.'actions/tasks/lists/order-list.php';
        						include $config->paths->content."common/include-blank-page.php";
        					}
                            break;
                        case 'quote':
                            if ($config->ajax) {
        						include $config->paths->content.'edit/quotes/actions/actions-panel.php';
        					} else {
                                $actionpanel->setupquotepanel($qnbr);
                                $actionpanel->querylinks['quotelink'] = $qnbr;
        						$page->title = 'Viewing Quote #'.$qnbr.' Task List';
        						$page->body = $config->paths->content.'actions/tasks/lists/quote-list.php';
        						include $config->paths->content."common/include-blank-page.php";
        					}
                            break;
                    }
                    break;
                default:
					$taskid = $input->get->id;
					$fetchclass = true;
					$task = loaduseraction($taskid, $fetchclass, false);
					$messagetemplate = "Viewing Action for {replace}";
					$page->title = $task->createmessage($messagetemplate);

                    if ($config->ajax) {
                        $page->body = $config->paths->content.'actions/tasks/view-task.php';
						include $config->paths->content.'common/modals/include-ajax-modal.php';
                    } else {
                        $page->body = $config->paths->content.'actions/tasks/view-task.php';
                        include $config->paths->content."common/include-blank-page.php";
                    }
                    break;
            }
            break;
        case 'update':
            switch ($input->urlSegment2) {
                case 'completion':
                    include $config->paths->content."actions/tasks/crud/update-completion.php";
                    break;
                case 'reschedule':
                    include $config->paths->content."actions/tasks/reschedule-task.php";
                    break;
            }
            break;
    }
