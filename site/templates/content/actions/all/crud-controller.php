<?php
    $actiontype = 'all';
	if ($input->get->modal) {
		$partialid = 'actions-modal';
	} else {
		$partialid = 'actions';
	}

    switch ($input->urlSegment1) {
        case 'load':
            switch ($input->urlSegment2) {
                case 'list':
                    if (!$config->ajax) {
    					$actionpanel = new UserActionPanel($input->urlSegment3, 'all', $partialid, '#ajax-modal', $config->ajax, $config->modal);
                        $actionpanel->querylinks = UserAction::getlinkarray();
                        $actionpanel->querylinks['assignedto'] = $assigneduserID;
                    }
                    switch($input->urlSegment3) {
                        case 'user':
                            if ($config->ajax) {
								if ($input->get->modal) {
									$page->title = '';
									$page->body = $config->paths->content.'dashboard/actions/actions-panel.php';
									include $config->paths->content."common/modals/include-ajax-modal.php";
								} else {
									include $config->paths->content.'dashboard/actions/actions-panel.php';
								}
        					} else {
        						$page->title = 'User Action List';
        						$page->body = $config->paths->content.'actions/all/lists/user-list.php';
        						include $config->paths->content."common/include-blank-page.php";
        					}
                            break;
                        case 'cust':
                            include $config->paths->content.'customer/cust-page/actions/actions-panel.php';
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
        						$page->body = $config->paths->content.'actions/all/lists/contact-list.php';
        						include $config->paths->content."common/include-blank-page.php";
        					}
                            break;
                        case 'order':
                            if ($config->ajax) {
								if ($config->modal) {
									$page->title = 'Viewing actions for Order #'.$ordn;
									$page->body = $config->paths->content.'edit/orders/actions/actions-panel.php';
									include $config->paths->content."common/modals/include-ajax-modal.php";
								} else {
									include $config->paths->content.'edit/orders/actions/actions-panel.php';
								}

        					} else {
                                $actionpanel->setuporderpanel($ordn);
                                $actionpanel->querylinks['salesorderlink'] = $ordn;
								$page->title = 'User Actions for Order #' . $ordn;
        						$page->body = $config->paths->content.'actions/all/lists/order-list.php';
        						include $config->paths->content."common/include-blank-page.php";
        					}
                            break;
                        case 'quote':
                            if ($config->ajax) {
        						include $config->paths->content.'edit/quotes/actions/actions-panel.php';
        					} else {
                                $actionpanel->setupquotepanel($qnbr);
                                $actionpanel->querylinks['quotelink'] = $qnbr;
        						$page->title = 'User Actions for Quote #' . $qnbr;
        						$page->body = $config->paths->content.'actions/all/lists/quote-list.php';
        						include $config->paths->content."common/include-blank-page.php";
        					}
                            break;
                    }
                    break;
                default:
                    if ($config->ajax) {
                        include $config->paths->content.'actions/tasks/view-task.php';
                    } else {
                        $page->title = 'Task ID: ' . $input->get->text('id');
                        $page->body = $config->paths->content.'actions/tasks/view-task.php';
                        include $config->paths->content."common/include-blank-page.php";
                    }
                    break;
            }
            break;
    }
