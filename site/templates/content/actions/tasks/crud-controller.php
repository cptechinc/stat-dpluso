<?php
    $actiontype = "tasks";
	$page->useractionpanelfactory = new UserActionPanelFactory($assigneduserID, $page->fullURL, $actiontype);
    
    switch ($input->urlSegment1) {
        case 'add':
            if ($input->requestMethod() == 'POST') { // CREATE IN CRUD
                include $config->paths->content."actions/tasks/crud/create-task.php";
            } else { // SHOW FORM
                include $config->paths->content."actions/tasks/new-task.php";
            }
            break;
        case 'update':
            switch ($input->urlSegment2) {
                case 'completion':
                    include $config->paths->content."actions/tasks/crud/update-task-completion.php";
                    break;
                case 'reschedule':
                    include $config->paths->content."actions/tasks/reschedule-task.php";
                    break;
            }
            break;
        case 'load':
            switch ($input->urlSegment2) {
                case 'list':
                    switch($input->urlSegment3) {
                        case 'user':
                            if ($config->ajax) {
                                include $config->paths->content.'dashboard/actions/actions-panel.php';
                            } else {
                                $page->title = '';
                                $page->body = $config->paths->content.'dashboard/actions/actions-list.php';
                                include $config->paths->content.'common/include-blank-page.php';
                            }
                            break;
                        case 'cust':
                            if ($config->ajax) {
                                include $config->paths->content.'customer/cust-page/actions/actions-panel.php';
                            } else {
                                $page->title = '';
                                $page->body = $config->paths->content.'customer/cust-page/actions/actions-list.php';
                                include $config->paths->content.'common/include-blank-page.php';
                            }
                            break;
						case 'contact':
                            if ($config->ajax) {
                                include $config->paths->content.'customer/contact/actions/actions-panel.php';
                            } else {
                                $page->title = '';
                                $page->body = $config->paths->content.'customer/contact/actions/actions-list.php';
                                include $config->paths->content.'common/include-blank-page.php';
                            }
                            break;
                        case 'salesorder':
                            if ($config->ajax) {
                                if ($config->modal) {
                                    $page->title = 'Viewing actions for Order #'.$ordn;
                                    $page->body = $config->paths->content.'edit/orders/actions/actions-panel.php';
                                    include $config->paths->content."common/modals/include-ajax-modal.php";
                                } else {
                                    include $config->paths->content.'edit/orders/actions/actions-panel.php';
                                }
                            } else {
                                $page->title = '';
                                $page->body = $config->paths->content.'edit/orders/actions/actions-list.php';
                                include $config->paths->content.'common/include-blank-page.php';
                            }
                            break;
                        case 'quote':
                            if ($config->ajax) {
                                include $config->paths->content.'edit/quotes/actions/actions-panel.php';
                            } else {
                                $page->title = '';
                                $page->body = $config->paths->content.'edit/quotes/actions/actions-list.php';
                                include $config->paths->content.'common/include-blank-page.php';
                            }
                            break;
                    }
                    break;
                default:
					$taskID = $input->get->text('id');
					$task = UserAction::get($taskID);
					$messagetemplate = "Viewing Action for {replace}";
					$page->title = $task->generate_message($messagetemplate);

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
    }
