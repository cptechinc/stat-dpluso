<?php 
    class QuoteDisplay extends OrderDisplay implements OrderDisplayInterface, QuoteDisplayInterface {
        use QuoteDisplayTraits;
        
        protected $qnbr;
        protected $quote;
        protected $modal;
        
        public function __construct($sessionID, \Purl\Url $pageurl, $modal, $qnbr) {
            parent::__construct($sessionID, $pageurl);
            $this->qnbr = $qnbr;
            $this->modal = $modal;
        }
        
        public function get_quote($debug = false) {
            return get_quotehead($this->sessionID, $this->qnbr, 'Quote', false);
        }
        
        /* =============================================================
            OrderDisplayInterface Functions
            LINKS ARE HTML LINKS, AND URLS ARE THE URLS THAT THE HREF VALUE
        ============================================================ */
        public function generate_documentsrequesturl(Order $quote, OrderDetail $quotedetail = null) {
            return $this->generate_documentsrequesturltrait($quote, $quotedetail);
        }
        
        public function generate_editlink(Order $quote) {
            $bootstrap = new Contento();
            $href = $this->generate_editurl($quote);
            $icon = $bootstrap->createicon('material-icons', '&#xE150;');
            return $bootstrap->openandclose('a', "href=$href|class=btn btn-block btn-warning", $icon. " Edit Quote");   
        }
        
        public function generate_detailvieweditlink(Order $quote, OrderDetail $detail) {
            $bootstrap = new Contento();
            $href = $this->generate_detailviewediturl($quote, $detail);
            $icon = $bootstrap->openandclose('span', 'class=h3', $bootstrap->createicon('glyphicon glyphicon-eye-open'));
            return $bootstrap->openandclose('a', "href=$href|class=update-line|data-kit=$detail->kititemflag|data-itemid=$detail->itemid|data-custid=$quote->custid|aria-label=View Detail Line", $icon);    
        }
        
        /* =============================================================
            Class specific Functions
            
        ============================================================ */
        public function generate_customershiptolink(Order $quote) {
            $bootstrap = new Contento();
            $href = $this->generate_customershiptourl($quote);
            $icon = $bootstrap->createicon('fa fa-user');
            return $bootstrap->openandclose('a', "href=$href|class=btn btn-block btn-primary", $icon. " Go to Customer Page");   
        }
        
        public function generate_loaddetailsurl(Order $quote) {
			$url = new \Purl\Url($this->generate_loaddetailsurltrait());
            return $url->getUrl();    
        }
    }
