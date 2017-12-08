<?php
	$config->scripts->append(hashtemplatefile('scripts/dplusnotes/cart-notes.js'));
	$config->scripts->append(hashtemplatefile('scripts/edit/edit-pricing.js'));
	
    if (getcartheadcount(session_id(), false)) {
        $carthead = getcarthead(session_id(), false);
        $custID = $carthead['custid'];
        $shipID = $carthead['shiptoid'];
        $page->title = "Quote for ".get_customername($carthead['custid']);
    }
	$noteurl = $config->pages->notes.'redir/?action=get-cart-notes';
?>
   <?php include('./_head.php'); // include header markup ?>
    <div class="jumbotron pagetitle">
        <div class="container">
            <h1><?php echo $page->get('pagetitle|headline|title') ; ?></h1>
        </div>
    </div>
    <div class="container page">
        <?php include $config->paths->content.'cart/cart-outline.php'; ?>
    </div>
<?php include('./_foot.php'); // include footer markup ?>
