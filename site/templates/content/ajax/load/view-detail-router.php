<?php

    $detailtype = $input->urlSegment(2); // CART | ORDER | QUOTE
    $linenbr = $sanitizer->text($input->get->line);
    switch ($detailtype) {
        case 'cart':
            $linedetail = getcartline(session_id(), $linenbr, false);
			$page->title = 'Details about line #' .$linenbr;
			$page->body = $config->paths->content."view/details/view-order-details.php";
            break;
        case 'order':
            $ordn = $sanitizer->text($input->get->ordn);
            $linedetail = getorderlinedetail(session_id(), $ordn, $linenbr, false);
			$page->title = 'Details about line #' .$linenbr;
			$page->body = $config->paths->content."view/details/view-order-details.php";
            break;
        case 'quote':
            $qnbr = $sanitizer->text($input->get->qnbr);
            $linedetail = getquotelinedetail(session_id(), $qnbr, $linenbr, false);
			$page->title = 'Details about line #' .$linenbr;
			$page->body = $config->paths->content."view/details/view-quote-details.php";
            break;
    }

	include $config->paths->content."common/modals/include-ajax-modal.php";

?>
