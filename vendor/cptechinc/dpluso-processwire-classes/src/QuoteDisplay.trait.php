<?php 
    trait QuoteDisplayTraits {
        /* =============================================================
            OrderDisplayInterface Functions
            LINKS ARE HTML LINKS, AND URLS ARE THE URLS THAT THE HREF VALUE
        ============================================================ */
        public function generate_loaddplusnoteslink(Order $quote, $linenbr = '0') {
            $bootstrap = new Contento();
            $href = $this->generate_dplusnotesrequesturl($quote, $linenbr);
            
            if ($quote->can_edit()) {
                $title = ($quote->has_notes()) ? "View and Create Quote Notes" : "Create Quote Notes";
            } else {
                $title = ($quote->has_notes()) ? "View Quote Notes" : "View Quote Notes";
            }
            $content = $bootstrap->createicon('material-icons', '&#xE0B9;') . ' ' . $title;
            $link = $bootstrap->openandclose('a', "href=$href|class=btn btn-default load-notes|title=$title|data-modal=$this->modal", $content);
            return $link;
        }
        
        public function generate_dplusnotesrequesturl(Order $quote, $linenbr) {
            $url = new \Purl\Url($this->pageurl->getUrl());
            $url->path = wire('config')->pages->notes."redir/";
            $url->query->setData(array('action' => 'get-quote-notes', 'qnbr' => $quote->quotnbr, 'linenbr' => $linenbr));
            return $url->getUrl();
        }
        
        public function generate_loaddocumentslink(Order $quote, OrderDetail $quotedetail = null) {
            $bootstrap = new Contento();
            $href = $this->generate_documentsrequesturl($quote, $quotedetail);
            $icon = $bootstrap->createicon('material-icons', '&#xE873;');
            $ajaxdata = "data-loadinto=.docs|data-focus=.docs|data-click=#documents-link";
            
            if ($quote->has_documents()) {
                return $bootstrap->openandclose('a', "href=$href|class=btn btn-primary load-sales-docs|role=button|title=Click to view Documents|$ajaxdata", $icon. ' Show Documents');
            } else {
                return $bootstrap->openandclose('a', "href=#|class=btn btn-default|title=No Documents Available", $icon. ' 0 Documents Found');
            }
        }
        
        /**
         * Sets up a common url function for getting documents request url, classes that have this trait 
         * will define generate_documentsrequesturl(Order $quote)
         * Not used as of 10/25/2017
         * @param  Order  $quote [description]
         * @return String        URL to the order redirect to make the get order documents request
         */
        public function generate_documentsrequesturltrait(Order $quote, OrderDetail $quotedetail = null) {
            $url = $this->generate_quotesredirurl();
            $url->query->setData(array('action' => 'get-quote-documents', 'qnbr' => $quote->quotnbr));
            if ($quotedetail) {
                $url->query->set('itemdoc', $quotedetail->itemid);
            }
            return $url->getUrl();
        }
        
        public function generate_editurl(Order $quote) {
            $url = $this->generate_quotesredirurl();
            $url->query->setData(array('action' => 'edit-quote', 'qnbr' => $quote->quotnbr));
            return $url->getUrl();
        }
        
        public function generate_orderquotelink(Order $quote) {
            $bootstrap = new Contento();
            $href = $this->generate_orderquoteurl($quote);
            $icon = $bootstrap->createicon('glyphicon glyphicon-print');
            return $bootstrap->openandclose('a', "href=$href|class=btn btn-sm btn-default", $icon." Send To Order");
        }
        
        public function generate_orderquoteurl(Order $quote) {
            $url = $url = new \Purl\Url($this->pageurl->getUrl());
            $url->path = wire('config')->pages->orderquote;
            $url->query->setData(array('qnbr' => $quote->quotnbr));
            return $url->getUrl();
        }
        
        public function generate_viewprintlink(Order $quote) {
            $bootstrap = new Contento();
            $href = $this->generate_viewprinturl($quote);
            $icon = $bootstrap->openandclose('span','class=h3', $bootstrap->createicon('glyphicon glyphicon-print'));
            return $bootstrap->openandclose('a', "href=$href|target=_blank", $icon." View Printable Quote");
        }
        
        public function generate_viewprinturl(Order $quote) {
            $url = new \Purl\Url($this->generate_loaddetailsurl($quote));
            $url->query->set('print', 'true');
            return $url->getUrl();
        }
        
        public function generate_viewlinkeduseractionslink(Order $quote) {
            $href = $this->generate_viewlinkeduseractionsurl($quote);
            $icon = $bootstrap->openandclose('span','class=h3', $bootstrap->createicon('glyphicon glyphicon-check'));
            return $bootstrap->openandclose('a', "href=$href|target=_blank", $icon." View Associated Actions");
        }
        
        public function generate_viewlinkeduseractionsurl(Order $quote) {
            $url = new \Purl\Url($this->pageurl->getUrl());
            $url->path = wire('config')->pages->actions."all/load/list/quote/";
            $url->query->setData(array('qnbr' => $quote->quotnbr));
            return $url->getUrl();
        }
        
        /**
         * Return String URL to orders redir to request order details
         * This is here for the use of getting the Print link
         * @param  Order  $quote [description]
         * @return String        [description]
         */
        public function generate_loaddetailsurltrait(Order $quote) {
			$url = $this->generate_quotesredirurl();
			$url->query->setData(array('action' => 'load-quote-details', 'qnbr' => $quote->quotnbr));
			return $url->getUrl();
		}
        
        public function generate_detailviewediturl(Order $quote, OrderDetail $detail) {
            $url = new \Purl\Url(wire('config')->pages->ajaxload.'edit-detail/quote/');
            $url->query->setData(array('qnbr' => $quote->quotnbr, 'line' => $detail->linenbr));
            return $url->getUrl();
        }
        
        public function get_quotedetails(Order $quote, $debug = false) {
            return get_quotedetails($this->sessionID, $quote->quotnbr, true, $debug);
        }
        
        /* =============================================================
            URL Helper Functions
        ============================================================ */
        public function generate_customerurl(Order $quote) {
            $url = $this->generate_customerredirurl();
            $url->query->setData(array('action' => 'load-customer', 'custID' => $quote->custid));
            return $url->getUrl();
        }
        
        public function generate_customershiptourl(Order $quote) {
            $url = new \Purl\Url($this->generate_customerurl($quote));
            if (!empty($quote->shiptoid)) $url->query->set('shipID', $quote->shiptoid);
            return $url->getUrl();
        }
        
        /** 
         * Makes the URL to the orders redirect page, 
         * @return \Purl\Url URL to REDIRECT page
         */
        public function generate_quotesredirurl() {
            $url = new \Purl\Url(wire('config')->pages->quotes);
            $url->path = wire('config')->pages->quotes."redir/";
            return $url;
        }
        
        public function generate_customerredirurl() {
            $url = new \Purl\Url(wire('config')->pages->customer);
            $url->path = wire('config')->pages->customer."redir/";
            return $url;
        }
    }
