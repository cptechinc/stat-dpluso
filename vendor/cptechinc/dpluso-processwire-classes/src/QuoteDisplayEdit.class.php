<?php 
    class EditQuoteDisplay extends QuoteDisplay {
        protected $qnbr;
        protected $modal;
        public $canedit;
        use QuoteDisplayTraits;
        
        public function __construct($sessionID, \Purl\Url $pageurl, $modal, $qnbr) {
            parent::__construct($sessionID, $pageurl, $modal, $qnbr);
        }
        
        public function generate_sendtoorderurl(Order $quote) {
            $url = new \Purl\Url(Processwire\wire('config')->pages->orderquote);
            $url->query->set('qnbr', $quote->quotnbr);
            return $url->getUrl();
        }
        
        public function generate_sendtoorderlink(Order $quote) {
            $bootstrap = new Contento();
            $href = $this->generate_sendtoorderurl($quote);
            $icon = $bootstrap->createicon('fa fa-paper-plane-o');
            return $bootstrap->openandclose('a', "href=$href|class=btn btn-block btn-default", $icon. " Send To Order");
        }
        
        public function generate_unlockurl(Order $quote) {
            $url = $this->generate_quotesredirurl();
            $url->query->set('action', 'unlock-quote');
            $url->query->set('qnbr', $quote->quotnbr);
            return $url->getUrl();
        }
        
        public function generate_confirmationurl(Order $quote) {
            $url = new \Purl\Url(Processwire\wire('config')->pages->confirmquote);
            $url->query->set('qnbr', $quote->quotnbr);
            return $url->getUrl();
        }
        
        public function generate_discardchangeslink(Order $quote) {
            $bootstrap = new Contento();
            $href = $this->generate_unlockurl($quote);
            $icon = $bootstrap->createicon('glyphicon glyphicon-floppy-remove');
            return $bootstrap->openandclose('a', "href=$href|class=btn btn-block btn-warning", $icon. " Discard Changes, Unlock Quote");
        }
        
        public function generate_saveunlockbutton(Order $quote) {
            $bootstrap = new Contento();
            $icon = $bootstrap->createicon('fa fa-unlock');
            return $bootstrap->openandclose('button', "class=btn btn-block btn-emerald save-unlock-quotehead|data-form=#quotehead-form", $icon. " Save and Unlock Quote");
        }
        
        public function generate_confirmationlink(Order $quote) {
            $href = generate_confirmationurl($quote);
            $bootstrap = new Contento();
            $href = $this->generate_unlockurl($quote);
            $icon = $bootstrap->createicon('btn btn-block btn-success');
            return $bootstrap->openandclose('a', "href=$href|class=btn btn-block btn-success", $icon. " Finished with quote");
        }
        
        public function generate_detailvieweditlink(Order $quote, OrderDetail $detail) {
            $bootstrap = new Contento();
            $href = $this->generate_detailviewediturl($quote, $detail);
            $icon = $bootstrap->openandclose('button', 'class=btn btn-sm btn-warning', $bootstrap->createicon('glyphicon glyphicon-pencil'));
            return $bootstrap->openandclose('a', "href=$href|class=update-line|data-kit=$detail->kititemflag|data-itemid=$detail->itemid|data-custid=$quote->custid|aria-label=View Detail Line", $icon);    
        }
        
        public function generate_deletedetailform(Order $quote, OrderDetail $detail) {
            $url = $this->generate_quotesredirurl();
            $action = $url->getUrl();
            $form = new FormMaker("class=inline-block|action=$action|method=post");
            $form->input("class=hidden|name=action|value=remove-line");
            $form->input("class=hidden|name=qnbr|value=$quote->quotnbr");
            $form->input("class=hidden|name=linenbr|value=$detail->linenbr");
            $icon = $form->bootstrap->createicon('fa fa-trash fa-1-5x') . $form->bootstrap->openandclose('span', 'class=sr-only', 'Delete Line');
            $form->button('type=submit|class=btn btn-sm btn-danger', $icon);
            return $form->finish();
        }
        
        public function generate_deletedetaillink(Order $quote, OrderDetail $detail) {
            $bootstrap = new Contento();
            $icon = $bootstrap->createicon('fa fa-trash fa-1-5x') . $bootstrap->openandclose('span', 'class=sr-only', 'Delete Line');
            $url = $this->generate_quotesredirurl();
            $url->query->setData(array('action' => 'remove-line-get', 'qnbr' => $quote->quotnbr, 'linenbr' => $detail->linenbr, 'page' => $this->pageurl->getUrl()));
            $href = $url->getUrl();
            return $bootstrap->a("href=$href|class=btn btn-sm btn-danger", $icon);
        }
        
        public function generate_readonlyalert() {
            $bootstrap = new Contento();
            $msg = $bootstrap->openandclose('b', '', 'Attention!') . ' This order will open in read-only mode, you will not be able to save changes.';
            return $bootstrap->createalert('warning', $msg);
        }
        
        public function generate_erroralert($quote) {
            $bootstrap = new Contento();
            $msg = $bootstrap->openandclose('b', '', 'Error!') . $quote->errormsg;
            return $bootstrap->createalert('danger', $msg, false);
        }
        
        public function generate_loaddplusnoteslink(Order $quote, $linenbr = '0') {
            $bootstrap = new Contento();
            $href = $this->generate_dplusnotesrequesturl($quote, $linenbr);
            
            if ($quote->can_edit()) {
                $title = ($quote->has_notes()) ? "View and Create Quote Notes" : "Create Quote Notes";
            } else {
                $title = ($quote->has_notes()) ? "View Quote Notes" : "View Quote Notes";
            }
            
            if (intval($linenbr) > 0) {
                $content = $bootstrap->createicon('material-icons md-36', '&#xE0B9;');
    			$link = $bootstrap->openandclose('a', "href=$href|class=load-notes|title=$title|data-modal=$this->modal", $content);
            } else {
                $content = $bootstrap->createicon('material-icons', '&#xE0B9;') . ' ' . $title;
                $link = $bootstrap->openandclose('a', "href=$href|class=btn btn-default load-notes|title=$title|data-modal=$this->modal", $content);
            }
            return $link;
        }
    }
