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
            $url = new \Purl\Url(wire('config')->pages->orderquote);
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
            $url = new \Purl\Url(wire('config')->pages->confirmquote);
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
    }
