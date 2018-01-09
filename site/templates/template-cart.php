<?php
    if (getcartheadcount(session_id(), false)) {
        $carthead = getcarthead(session_id(), false);
        $custID = $carthead['custid'];
        $shipID = $carthead['shiptoid'];
		$itemlookup->set_customer($carthead['custid'], $carthead['shiptoid']); 
        $page->pagetitle = "Quote for ".get_customername($custID);
		$noteurl = $config->pages->notes.'redir/?action=get-cart-notes';
    }
    $config->scripts->append(hashtemplatefile('scripts/pages/cart.js'));
	$config->scripts->append(hashtemplatefile('scripts/edit/edit-pricing.js'));
	$page->body = $config->paths->content.'cart/cart-outline.php';
	include $config->paths->content."common/include-page.php";
?>
