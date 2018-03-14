<?php
    $filteron = $input->urlSegment(2);
    if ($input->get->ordn) { $ordn = $input->get->text('ordn'); } else { $ordn = NULL; }
    switch ($filteron) {
        case 'cust':
            $custID = $sanitizer->text($input->urlSegment(3));
            $shipID = '';
            if ($input->urlSegment(4)) {
                if (strpos($input->urlSegment(4), 'shipto') !== false) {
                    $shipID = str_replace('shipto-', '', $input->urlSegment(4));
                }
            }
            $page->body = $config->paths->content.'customer/cust-page/orders/orders-panel.php';
            break;
        case 'salesrep':
            $page->body = $config->paths->content.'salesrep/orders/orders-panel.php';
            break;
		case 'search':
			$searchtype = $sanitizer->text($input->urlSegment(3));
			switch ($searchtype) {
				case 'cust':
					$custID = $input->get->text('custID');
					$shipID = $input->get->text('shipID');
					$page->body = $config->paths->content.'customer/cust-page/orders/order-filter-form.php'; // Barbara - changed from order-search-form 
					$page->title = "Searching through ".get_customername($custID)." orders";
					break;
				case 'salesrep':
					//FIX
					break;
			}
			break;
    }


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
