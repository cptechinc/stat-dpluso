<?php
    $sessionID = $input->get->referenceID ? $input->get->text('referenceID') : session_id();
    $emailurl = new \Purl\Url($config->pages->ajaxload."email/email-file-form/");
    $emailurl->query->set('referenceID', $sessionID);
    
    switch ($page->name) { //$page->name is what we are printing
        case 'order':
            $ordn = $input->get->text('ordn');
            $orderdisplay = new SalesOrderDisplay($sessionID, $page->fullURL, '#ajax-modal', $ordn);
            $order = $orderdisplay->get_order();
            $page->title = 'Order #' . $ordn;
            $emailurl->query->set('printurl', $orderdisplay->generate_sendemailurl($order));
			$page->body = $config->paths->content."print/orders/outline.php";
            break;
        case 'quote':
            $qnbr = $input->get->text('qnbr');
            $quotedisplay = new QuoteDisplay($sessionID, $page->fullURL, '#ajax-modal', $qnbr);
            $quote = $quotedisplay->get_quote();
            $page->title = 'Quote #' . $qnbr;
            $emailurl->query->set('printurl', $quotedisplay->generate_sendemailurl($quote));
            $page->body = $config->paths->content."print/quotes/outline.php";
            break;
    }
    
    $emailurl->query->set('subject', urlencode($page->title));
    
    if ($input->get->print) {
        $page->fullURL->query->remove('print');
        $url = new Purl\Url($page->fullURL->getUrl());
        $url->query->remove('print');
        $url->query->set('referenceID', $sessionID);
    
        $pdfmaker = new PDFMaker($sessionID, $url->getUrl());
        $result = $pdfmaker->process();

        $page->title = '';
    }
    
    
    
    
    include $config->paths->content.'common/include-blank-page.php';
