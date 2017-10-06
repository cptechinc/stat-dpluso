<?php
    $actiontype = "action";
	if ($input->get->modal) {
		$partialid = 'actions-modal';
	} else {
		$partialid = 'actions';
	}

    switch ($input->urlSegment1) {
        case 'add':
            switch($input->urlSegment2) {
                case 'new':
                    if (file_exists($config->paths->content."actions/actions/$config->cptechcustomer-new-action.php")) {
                        include $config->paths->content."actions/actions/$config->cptechcustomer-new-action.php";
                    } else {
                        include $config->paths->content."actions/actions/new-action.php";
                    }
                    break;
                default:
                    include $config->paths->content."actions/actions/crud/add-action.php";
                    break;
            }
            break;
        case 'load':
            switch ($input->urlSegment2) {
                case 'list':
                    if (!$config->ajax) {
    					$actionpanel = new \Dpluso\UserActionPanel($input->urlSegment3, 'action', $partialid, '#ajax-modal', $config->ajax, $config->modal);
                        $actionpanel->setuptasks($input->get->text('action-status'));
                        $actionpanel->querylinks = UserAction::getlinkarray();
                        $actionpanel->querylinks['assignedto'] = $user->loginid;
                        $actionpanel->querylinks['actiontype'] = 'action';
                    }

                    switch($input->urlSegment3) {
                        case 'user':
                            if ($config->ajax) {
        						include $config->paths->content.'dashboard/actions/actions-panel.php';
        					} else {
        						$page->title = 'Viewing User Action List';
        						$page->body = $config->paths->content.'actions/actions/lists/user-list.php';
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
        						$page->title = 'Viewing User Actions List';
        						$page->body = $config->paths->content.'actions/actions/lists/cust-list.php';
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
        						$page->body = $config->paths->content.'actions/actions/lists/contact-list.php';
        						include $config->paths->content."common/include-blank-page.php";
        					}
                            break;
                        case 'order':
                            if ($config->ajax) {
        						include $config->paths->content.'edit/orders/actions/actions-panel.php';
        					} else {
                                $actionpanel->setuporderpanel($qnbr);
                                $actionpanel->querylinks['salesorderlink'] = $ordn;
        						$page->title = 'Viewing Order #'.$ordn.' Actions List';
        						$page->body = $config->paths->content.'actions/actions/lists/order-list.php';
        						include $config->paths->content."common/include-blank-page.php";
        					}
                            break;
                        case 'quote':
                            if ($config->ajax) {
        						include $config->paths->content.'edit/quotes/actions/actions-panel.php';
        					} else {
                                $actionpanel->setupquotepanel($qnbr);
                                $actionpanel->querylinks['quotelink'] = $qnbr;
        						$page->title = 'Viewing Quote #'.$qnbr.' List';
        						$page->body = $config->paths->content.'actions/actions/lists/quote-list.php';
        						include $config->paths->content."common/include-blank-page.php";
        					}
                            break;
                    }
                    break;
                default:
					$actionid = $input->get->id;
					$fetchclass = true;
					$action = loaduseraction($actionid, $fetchclass, false);
					$messagetemplate = "Viewing Action for {replace}";
					$page->title = $action->createmessage($messagetemplate);

                    if ($config->ajax) {
                        $page->body = $config->paths->content.'actions/actions/view-action.php';
						include $config->paths->content.'common/modals/include-ajax-modal.php';
                    } else {
                        $page->body = $config->paths->content.'actions/actions/view-action.php';
                        include $config->paths->content."common/include-blank-page.php";
                    }
                    break;
            }
            break;
    }
