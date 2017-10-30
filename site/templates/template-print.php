<?php
    switch ($page->name) { //$page->name is what we are printing
        case 'order':
            $ordn = $input->get->text('ordn');
            $orderdisplay = new SalesOrderDisplay(session_id(), $page->fullURL, '#ajax-modal', $ordn);
            $order = $orderdisplay->get_order(); 
            $page->title = 'Viewing Order #' . $ordn;
			$includefile = $config->paths->content."print/orders/outline.php";
            break;
        case 'quote':
            $qnbr = $input->get->text('qnbr');
            $quotedisplay = new QuoteDisplay(session_id(), $page->fullURL, '#ajax-modal', $qnbr);
            $quote = $quotedisplay->get_quote();
            $page->title = 'Viewing Quote #' . $qnbr;
            $includefile = $config->paths->content."print/quotes/outline.php";
            break;
    }
 ?>
 <?php include('./_head-blank.php'); // include header markup ?>
     <div class="container page">
        <?php include ($includefile); ?>
     </div>
 <?php include('./_foot-blank.php'); // include footer markup ?>
