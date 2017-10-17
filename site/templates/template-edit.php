<?php
    switch ($page->name) { //$page->name is what we are editing
        case 'order':
            $ordn = $input->get->text('ordn');
            $custID = get_custid_from_order(session_id(), $ordn);
            $ordereditdisplay = new EditSalesOrderDisplay(session_id(), $page->fullURL, '#ajax-modal', $ordn);
        	$order = $ordereditdisplay->get_order(); 
            $ordereditdisplay->canedit = ($input->get->readonly) ? $ordereditdisplay->canedit = false : $order->can_edit();
            $prefix = ($ordereditdisplay->canedit) ? 'Editing' : 'Viewing';
            $page->title = "$prefix Order #" . $ordn . ' for ' . get_customer_name_from_order(session_id(), $ordn);
            
            $config->scripts->append(hashtemplatefile('scripts/dplusnotes/order-notes.js'));
			$config->scripts->append(hashtemplatefile('scripts/edit/card-validate.js'));
			$config->scripts->append(hashtemplatefile('scripts/edit/edit-orders.js'));
			$config->scripts->append(hashtemplatefile('scripts/edit/edit-pricing.js'));
			$page->body = $config->paths->content."edit/orders/outline.php";
            break;
        case 'quote':
            $qnbr = $input->get->text('qnbr');
            $editquote['qnbr'] = $qnbr;
            $editquote['custID'] = getquotecustomer(session_id(), $qnbr, false); $custID = $editquote['custID'];
            $editquote['canedit'] = true; //caneditquote(session_id(), $qnbr);
            if ($editquote['canedit']) {
                $page->title = "Editing Quote #" . $editquote['qnbr'] . ' for ' . get_customername($custID);
            } else {
                $page->title = "Viewing Quote #" . $editquote['qnbr'] . ' for ' . get_customername($custID);
            }
            $editquote['unlock-url'] = $config->pages->quotes."redir/?action=unlock-quote&qnbr=".$qnbr;

            $page->body = $config->paths->content."edit/quotes/outline.php";
            $config->scripts->append(hashtemplatefile('scripts/dplusnotes/quote-notes.js'));
			$config->scripts->append(hashtemplatefile('scripts/edit/edit-quotes.js'));
            $config->scripts->append(hashtemplatefile('scripts/edit/edit-pricing.js'));
            break;
        case 'quote-to-order':
            $qnbr = $input->get->text('qnbr');
            $editquote['custID'] = getquotecustomer(session_id(), $qnbr, false); $custID = $editquote['custID'];
            $editquote['canedit'] = true;
            $page->title = "Creating a Sales Order from Quote #" . $qnbr;
            $page->body = $config->paths->content."edit/quote-to-order/outline.php";
            $config->scripts->append(hashtemplatefile('scripts/edit/edit-quote-to-order.js'));
            $config->scripts->append(hashtemplatefile('scripts/edit/edit-pricing.js'));
            break;
    }
 ?>
 <?php include('./_head.php'); // include header markup ?>
 	<div class="jumbotron pagetitle">
 		<div class="container">
 			<h1><?php echo $page->get('pagetitle|headline|title') ; ?></h1>
 		</div>
 	</div>
     <div class="container page" id="edit-page">
        <?php include ($page->body); ?>
     </div>
 <?php include('./_foot.php'); // include footer markup ?>
