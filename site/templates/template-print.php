<?php
    $sessionID = $input->get->referenceID ? $input->get->text('referenceID') : session_id();

    switch ($page->name) { //$page->name is what we are printing
        case 'order':
            $ordn = $input->get->text('ordn');
            $orderdisplay = new SalesOrderDisplay($sessionID, $page->fullURL, '#ajax-modal', $ordn);
            $order = $orderdisplay->get_order(); 
            $page->title = 'Viewing Order #' . $ordn;
			$page->body = $config->paths->content."print/orders/outline.php";
            break;
        case 'quote':
            $qnbr = $input->get->text('qnbr');
            $quotedisplay = new QuoteDisplay($sessionID, $page->fullURL, '#ajax-modal', $qnbr);
            $quote = $quotedisplay->get_quote();
            $page->title = 'Viewing Quote #' . $qnbr;
            $page->body = $config->paths->content."print/quotes/outline.php";
            break;
    }
    
    include $config->paths->content.'common/include-blank-page.php'; 
 ?>
