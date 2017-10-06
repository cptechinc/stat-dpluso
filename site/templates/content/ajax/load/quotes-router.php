<?php
    $filteron = $input->urlSegment(2);
    switch ($filteron) {
        case 'cust':
            $custID = $sanitizer->text($input->urlSegment(3));
            $shipID = '';
            if ($input->urlSegment(4)) {
                if (strpos($input->urlSegment(4), 'shipto') !== false) {
                    $shipID = str_replace('shipto-', '', $input->urlSegment(4));
                }
            }
            $page->body = $config->paths->content.'customer/cust-page/quotes/quotes-panel.php';
            break;
        case 'salesrep': //TODO
            //$include = $config->paths->content.'salesrep/orders/orders-panel.php'; //FIX
            break;
		case 'search': //TODO
			//$include = $config->paths->content.'recent-orders/ajax/load/order-search-modal.php'; //FIX
			break;

    }

	if ($input->get->qnbr) { $qnbr = $input->get->text('qnbr'); } else { $qnbr = NULL; }

    if ($config->ajax) {
		if ($config->modal) {
			include $config->paths->content.'common/modals/include-ajax-modal.php';
		} else {
			include($page->body);
		}
    } else {
        include $config->paths->content."common/include-blank-page.php";
    }
?>
