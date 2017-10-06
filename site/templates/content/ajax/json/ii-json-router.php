<?php
	switch ($input->urlSegment(2)) {
		case 'ii-move-document':
			include $config->paths->content . 'ajax/json/ii/move-file.php';
			break;
		case 'ii-sales-order-formatter':
			include $config->paths->content . 'ajax/json/ii/ii-so-formatter.php';
			break;
		case 'ii-sales-history-formatter':
			include $config->paths->content . 'ajax/json/ii/ii-sh-formatter.php';
			break;
		case 'ii-purchase-order-formatter':
			include $config->paths->content . 'ajax/json/ii/ii-po-formatter.php';
			break;
		case 'ii-purchase-history-formatter':
			include $config->paths->content . 'ajax/json/ii/ii-ph-formatter.php';
			break;
		case 'ii-quotes-formatter':
			include $config->paths->content . 'ajax/json/ii/ii-qt-formatter.php';
			break;

	}

?>
