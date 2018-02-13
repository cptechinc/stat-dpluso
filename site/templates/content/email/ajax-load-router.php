<?php
    switch ($input->urlSegment(2)) {
        case 'email-file-form':
            $page->title = "Emailing";
            $sessionID = $input->get->referenceID ? $input->get->text('referenceID') : session_id();
            $printurl = $input->get->text('printurl');
            $page->body = $config->paths->content."email/forms/email-file-form.php";
            break;
        default:
            $page->title = 'Search for a customer';
            if ($input->get->q) {$q = $input->get->text('q');}
            $page->body = $config->paths->content."cust-information/forms/cust-search-form.php";
            break;
    }

	if ($config->ajax) {
		if ($config->modal) {
			include $config->paths->content."common/modals/include-ajax-modal.php";
		} else {
			include $page->body;
		}
	} else {
		include $config->paths->content."common/include-blank-page.php";
	}
