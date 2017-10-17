<?php
    $edittype = $input->urlSegment(2); // CART || SALE
    $linenbr = $sanitizer->text($input->get->line);
    if ($input->get->vendorID) {
        $vendorID = $input->get->text('vendorID');
    }
    switch ($edittype) {
        case 'cart':
            $linedetail = getcartline(session_id(), $linenbr, false);
            $page->title = 'Edit Pricing for '. $linedetail['itemid'];
            $custID = getcartcustomer(session_id(), false);
            $formaction = $config->pages->cart."redir/";
            $linedetail['can-edit'] = true;
            $ordn = '';
			$page->body = $config->paths->content."edit/pricing/edit-pricing-form.php";
            break;
        case 'order':
            $ordn = $input->get->text('ordn');
            $custID = get_custid_from_order(session_id(), $ordn);
            $linedetail = getorderlinedetail(session_id(), $ordn, $linenbr, false);
            if (can_editorder(session_id(), $ordn, false) && $ordn == getlockedordn(session_id())) {
                $linedetail['can-edit'] = true;
                $formaction = $config->pages->orders."redir/";
                $page->title = 'Edit Pricing for '. $linedetail['itemid'];
            } else {
                $linedetail['can-edit'] = false;
                $formaction = '';
                $page->title = 'Viewing Details for '. $linedetail['itemid'];
            }
			$page->body = $config->paths->content."edit/pricing/edit-pricing-form.php";
            break;
		case 'quote':
			$qnbr = $input->get->text('qnbr');
			$custID = getquotecustomer(session_id(), $qnbr, false);
			$linedetail = get_quoteline(session_id(), $qnbr, $linenbr, false);
            $linedetail['can-edit'] = false;
			$formaction = $config->pages->quotes."redir/";
            $page->title = 'Edit Pricing for '. $linedetail['itemid'];
            if (is_orderlocked(session_id(), $qnbr)) {
                $linedetail['can-edit'] = true;
            }

			$page->body = $config->paths->content."edit/pricing/quotes/edit-pricing-form.php";
    }

	if ($config->ajax) {
        if ($config->modal) {
            include $config->paths->content."common/modals/include-ajax-modal.php";
        }
	} else {
		include $config->paths->content."common/include-blank-page.php";
	}


?>
