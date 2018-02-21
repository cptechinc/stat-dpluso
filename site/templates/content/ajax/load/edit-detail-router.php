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
            $custID = get_custidfromcart(session_id());
            $formaction = $config->pages->cart."redir/";
            $linedetail['can-edit'] = true;
            $ordn = '';
			$page->body = $config->paths->content."edit/pricing/edit-pricing-form.php";
            break;
        case 'order':
            $ordn = $input->get->text('ordn');
            $custID = get_custidfromorder(session_id(), $ordn);
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
			$custID = get_custidfromquote(session_id(), $qnbr);
			$linedetail = get_quoteline(session_id(), $qnbr, $linenbr, false);
            $linedetail['can-edit'] = true;
			$formaction = $config->pages->quotes."redir/";
            $page->title = 'Edit Pricing for '. $linedetail['itemid'];
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
