<?php
	$salespersonjson = json_decode(file_get_contents($config->companyfiles."json/salespersontbl.json"), true);
    $sessionID = $input->get->referenceID ? $input->get->text('referenceID') : session_id();
    $emailurl = new \Purl\Url($config->pages->ajaxload."email/email-file-form/");
    $emailurl->query->set('referenceID', $sessionID);
    $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
	
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
    
    if (!$input->get->text('view') == 'pdf') {
        $url = new Purl\Url($page->fullURL->getUrl());
        $url->query->set('referenceID', $sessionID);
    	$url->query->set('view', 'pdf');
		
        $pdfmaker = new PDFMaker($sessionID, $page->name, $url->getUrl());
        $result = $pdfmaker->process();
		
		switch ($page->name) { //$page->name is what we are printing
	        case 'quote':
				$folders = PDFMaker::$folders;
				$url = new Purl\Url($page->fullURL->getUrl());
				$url->path = $config->pages->account."redir/";
				$url->query->setData(array(
					'action' => 'store-document',
					'filetype' => $folders[$page->name],
					'field1' => $qnbr,
					'file' => $pdfmaker->filename,
					'sessionID' => $sessionID
				));
				curl_redir($url->getUrl());
	            break;
	    }
    }
    
    include $config->paths->content.'common/include-print-page.php';
