<?php
	$page->title = ($input->get->q) ? "Searching for '".$input->get->text('q')."'" : $page->title = "Customer Index";
	$page->body = $config->paths->content.'customer/cust-index/customer-index.php';

	if ($config->ajax) {
		if ($config->modal) {
			include $config->paths->content."common/modals/include-ajax-modal.php";
		} else {
			include $page->body;
		}
	} else {
		include $config->paths->content."common/include-page.php";
	}
