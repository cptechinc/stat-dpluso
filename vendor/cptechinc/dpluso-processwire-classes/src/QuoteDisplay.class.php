<?php 
	class QuoteDisplay extends OrderDisplay implements OrderDisplayInterface, QuoteDisplayInterface {
		use QuoteDisplayTraits;
		
		protected $qnbr;
		protected $quote;
		
		public function __construct($sessionID, \Purl\Url $pageurl, $modal, $qnbr) {
			parent::__construct($sessionID, $pageurl, $modal);
			$this->qnbr = $qnbr;
		}
		
		/* =============================================================
			Class Functions
		============================================================ */
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
		
		public function generate_loaddetailsurl(Order $quote) {
			$url = new \Purl\Url($this->generate_loaddetailsurltrait());
			return $url->getUrl();
		}
		
		public function generate_detailvieweditlink(Order $quote, OrderDetail $detail) {
			$bootstrap = new Contento();
			$href = $this->generate_detailviewediturl($quote, $detail);
			$icon = $bootstrap->openandclose('span', 'class=h3', $bootstrap->createicon('glyphicon glyphicon-eye-open'));
			return $bootstrap->openandclose('a', "href=$href|class=update-line|data-kit=$detail->kititemflag|data-itemid=$detail->itemid|data-custid=$quote->custid|aria-label=View Detail Line", $icon);	
		}
	}
