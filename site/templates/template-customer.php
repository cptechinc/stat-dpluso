<?php
	if ($input->urlSegment(1)) {
		if ($input->urlSegment(1) == 'add') {
			$page->title = "Add Customer";
			$page->body = $config->paths->content.'customer/add/outline.php';
		}
	} else {
		$page->title = ($input->get->q) ? "Searching for '".$input->get->text('q')."'" : $page->title = "Customer Index";
		$page->body = $config->paths->content.'customer/cust-index/customer-index.php';
	}

	if ($config->ajax) {
		if ($config->modal) {
			include $config->paths->content."common/modals/include-ajax-modal.php";
		} else {
			include $page->body;
		}
	} else {
		include $config->paths->content."common/include-page.php";
	}
