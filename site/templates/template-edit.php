<?php
    $page->useractionpanelfactory = new UserActionPanelFactory($user->loginid, $page->fullURL);
    
    switch ($page->name) { //$page->name is what we are editing
        case 'order':
            $ordn = $input->get->text('ordn');
            $custID = get_custidfromorder(session_id(), $ordn);
            $editorderdisplay = new EditSalesOrderDisplay(session_id(), $page->fullURL, '#ajax-modal', $ordn);
        	$order = $editorderdisplay->get_order();
            
            if (!$order) {
                $page->title = "Order #" . $ordn . ' failed to load';
                $page->body = '';
            } else {
                $editorderdisplay->canedit = ($input->get->readonly) ? false : $order->can_edit();
                $prefix = ($editorderdisplay->canedit) ? 'Editing' : 'Viewing';
                $page->title = "$prefix Order #" . $ordn . ' for ' . get_customername($custID);
                $config->scripts->append(hashtemplatefile('scripts/dplusnotes/order-notes.js'));
    			$config->scripts->append(hashtemplatefile('scripts/edit/card-validate.js'));
    			$config->scripts->append(hashtemplatefile('scripts/edit/edit-orders.js'));
    			$config->scripts->append(hashtemplatefile('scripts/edit/edit-pricing.js'));
    			$page->body = $config->paths->content."edit/orders/outline.php";
                $itemlookup->set_customer($order->custid, $order->shiptoid);
                $itemlookup = $itemlookup->set_ordn($ordn);
            }
            break;
        case 'quote':
            $qnbr = $input->get->text('qnbr');
            $editquotedisplay = new EditQuoteDisplay(session_id(), $page->fullURL, '#ajax-modal', $qnbr);
            $quote = $editquotedisplay->get_quote();
            $editquotedisplay->canedit = $quote->can_edit();
            $prefix = ($editquotedisplay->canedit) ? 'Editing' : 'Viewing';
            $page->title = "$prefix Quote #" . $qnbr . ' for ' . get_customername($quote->custid);
            $page->body = $config->paths->content."edit/quotes/outline.php";
            $config->scripts->append(hashtemplatefile('scripts/dplusnotes/quote-notes.js'));
			$config->scripts->append(hashtemplatefile('scripts/edit/edit-quotes.js'));
            $config->scripts->append(hashtemplatefile('scripts/edit/edit-pricing.js'));
            $itemlookup->set_customer($quote->custid, $quote->shiptoid);
            $itemlookup = $itemlookup->set_qnbr($qnbr);
            break;
        case 'quote-to-order':
            $qnbr = $input->get->text('qnbr');
            $editquotedisplay = new EditQuoteDisplay(session_id(), $page->fullURL, '#ajax-modal', $qnbr);
            $quote = $editquotedisplay->get_quote();
            $editquotedisplay->canedit = $quote->can_edit();
            $page->title = "Creating a Sales Order from Quote #" . $qnbr;
            $page->body = $config->paths->content."edit/quote-to-order/outline.php";
            $config->scripts->append(hashtemplatefile('scripts/edit/edit-quotes.js'));
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
         <?php 
             if (!empty($page->body)) {
                 include $page->body; 
             } 
        ?>
     </div>
 <?php include('./_foot.php'); // include footer markup ?>
