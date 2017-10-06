<?php
    $actiontype = "note";
	if ($input->get->modal) {
		$partialid = 'actions-modal';
	} else {
		$partialid = 'actions';
	}
    switch ($input->urlSegment1) {
        case 'add':
            switch($input->urlSegment2) {
                case 'new':
                    if (file_exists($config->paths->content."actions/notes/$config->cptechcustomer-new-note.php")) {
                        include $config->paths->content."actions/notes/$config->cptechcustomer-new-note.php";
                    } else {
                        include $config->paths->content."actions/notes/new-note.php";
                    }
                    break;
                default:
                    include $config->paths->content."actions/notes/crud/add-note.php";
                    break;
            }
            break;
        case 'load':
            switch ($input->urlSegment2) {
                case 'list':
                    if (!$config->ajax) {
    					$actionpanel = new UserActionPanel($input->urlSegment3, 'note', $partialid, '#ajax-modal', $config->ajax, $config->modal);
                        $actionpanel->querylinks = UserAction::getlinkarray();
                        $actionpanel->querylinks['assignedto'] = $user->loginid;
                        $actionpanel->querylinks['actiontype'] = 'note';
                    }

                    switch($input->urlSegment3) {
                        case 'user':
                            if ($config->ajax) {
        						include $config->paths->content.'dashboard/actions/actions-panel.php';
        					} else {
        						$page->title = 'Viewing User Note List';
        						$page->body = $config->paths->content.'actions/notes/lists/user-list.php';
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
        						$page->title = 'Viewing Customer Note List';
        						$page->body = $config->paths->content.'actions/notes/lists/cust-list.php';
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
        						$page->body = $config->paths->content.'actions/notes/lists/contact-list.php';
        						include $config->paths->content."common/include-blank-page.php";
        					}
                            break;
                        case 'order':
                            if ($config->ajax) {
        						include $config->paths->content.'edit/orders/actions/actions-panel.php';
        					} else {
                                $actionpanel->setuporderpanel($ordn);
                                $actionpanel->querylinks['salesorderlink'] = $ordn;
        						$page->title = 'Viewing Order #'.$ordn.' Notes List';
        						$page->body = $config->paths->content.'actions/notes/lists/order-list.php';
        						include $config->paths->content."common/include-blank-page.php";
        					}
                            break;
                        case 'quote':
                            if ($config->ajax) {
        						include $config->paths->content.'edit/orders/actions/actions-panel.php';
        					} else {
                                $actionpanel->setupquotepanel($qnbr);
                                $actionpanel->querylinks['quotelink'] = $qnbr;
        						$page->title = 'Viewing Quote #'.$qnbr.' Notes List';
        						$page->body = $config->paths->content.'actions/notes/lists/order-list.php';
        						include $config->paths->content."common/include-blank-page.php";
        					}
                            break;

                    }
                    break;
                default:
					$noteID = $input->get->id;
					$fetchclass = true;
					$note = loaduseraction($noteID, $fetchclass, false);
					$messagetemplate = "Viewing Note for {replace}";
					$page->title = $note->createmessage($messagetemplate);

                    if ($config->ajax) {
                        $page->body = $config->paths->content.'actions/notes/view-note.php';
						include $config->paths->content.'common/modals/include-ajax-modal.php';
                    } else {
                        $page->body = $config->paths->content.'actions/notes/view-note.php';
                        include $config->paths->content."common/include-blank-page.php";
                    }
                    break;
            }
            break;
    }
