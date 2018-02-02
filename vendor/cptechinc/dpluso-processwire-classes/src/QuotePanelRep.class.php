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
            SalesOrderPanelInterface Functions
            LINKS ARE HTML LINKS, AND URLS ARE THE URLS THAT THE HREF VALUE
        ============================================================ */
		public function get_quotecount($debug = false) {
            $this->count = count_salesrepquotes($this->sessionID);
        }
        
		public function get_quotes($debug = false) {
			$useclass = true;
            if ($this->tablesorter->orderby) {
                if ($this->tablesorter->orderby == 'quotdate') {
                    $quotes = get_salesrepquotesquotedate($this->sessionID, Processwire\wire('session')->display, $this->pagenbr, $this->tablesorter->sortrule, $useclass, $debug);
                } elseif ($this->tablesorter->orderby == 'revdate') {
                    $quotes = get_salesrepquotesrevdate($this->sessionID, Processwire\wire('session')->display, $this->pagenbr, $this->tablesorter->sortrule, $useclass, $debug);
                } elseif ($this->tablesorter->orderby == 'expdate') {
                    $quotes = get_salesrepquotesexpdate($this->sessionID, Processwire\wire('session')->display, $this->pagenbr, $this->tablesorter->sortrule, $useclass, $debug); 
                } else {
                    $quotes = get_salesrepquotesorderby($this->sessionID, Processwire\wire('session')->display, $this->pagenbr, $this->tablesorter->sortrule, $this->tablesorter->orderby, $useclass, $debug);
                }
            } else {
                $quotes = get_salesrepquotes($this->sessionID, Processwire\wire('session')->display, $this->pagenbr, $useclass, $debug);
            }
            return $debug ? $quotes: $this->quotes = $quotes;
        }
        
	}
