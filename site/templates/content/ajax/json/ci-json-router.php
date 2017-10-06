<?php
	header('Content-Type: application/json');
	switch ($input->urlSegment(2)) {
		case 'ci-shipto-list':
			include $config->paths->content . 'ajax/json/ci/ci-shipto-list.php';
			break;
		case 'ci-open-invoices-formatter':
			include $config->paths->content . 'ajax/json/ci/ci-oi-formatter.php';
			break;
		case 'ci-payment-history-formatter':
			include $config->paths->content . 'ajax/json/ci/ci-ph-formatter.php';
			break;
		case 'ci-quotes-formatter':
			include $config->paths->content . 'ajax/json/ci/ci-qt-formatter.php';
			break;
		case 'ci-sales-order-formatter':
			include $config->paths->content . 'ajax/json/ci/ci-so-formatter.php';
			break;
		case 'ci-sales-history-formatter':
			include $config->paths->content . 'ajax/json/ci/ci-sh-formatter.php';
			break;
	}

?>
