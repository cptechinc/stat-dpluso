<?php 	
	class RepQuotePanel extends QuotePanel {
		
		public function __construct($sessionID, \Purl\Url $pageurl, $modal, $loadinto, $ajax) {
			parent::__construct($sessionID, $pageurl, $modal, $loadinto, $ajax);
			$this->pageurl = $this->setup_pageurl($pageurl);
		}
		
		public function setup_pageurl(\Purl\Url $pageurl) {
			$url = $pageurl;
			$url->path = Processwire\wire('config')->pages->ajax."load/quotes/salesrep/";
			$url->query->remove('display');
			$url->query->remove('ajax');
			$this->paginationinsertafter = 'quotes';
			return $url;
		}
		
		/* =============================================================
			QuotePanelInterface Functions
			LINKS ARE HTML LINKS, AND URLS ARE THE URLS THAT THE HREF VALUE
		============================================================ */
		public function get_quotecount($debug = false) {
			return parent::get_quotecount($debug);
		}
		
		public function get_quotes($debug = false) {
			return parent::get_quotes($debug);
		}
	}
