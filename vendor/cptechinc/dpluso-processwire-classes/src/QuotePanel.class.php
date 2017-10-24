<?php 	
	class QuotePanel extends OrderPanel implements OrderDisplayInterface, QuotePanelInterface {
		public $quotes = array();
		use QuoteDisplayTraits;
		
		public function __construct($sessionID, \Purl\Url $pageurl, $modal, $loadinto, $ajax) {
			parent::__construct($sessionID, $pageurl, $modal, $loadinto, $ajax);
			$this->pageurl = $this->setup_pageurl($pageurl);
		}
		
		public function setup_pageurl(\Purl\Url $pageurl) {
			$url = $pageurl;
			$url->path = wire('config')->pages->ajax."load/quotes/";
			$url->query->remove('display');
			$url->query->remove('ajax');
			$this->paginationinsertafter = 'quotes';
			return $url;
		}
		
		/* =============================================================
            SalesOrderPanelInterface Functions
            LINKS ARE HTML LINKS, AND URLS ARE THE URLS THAT THE HREF VALUE
        ============================================================ */
		public function get_quotecount() { }
		public function get_quotes($debug = false) { }
		
		/* =============================================================
			OrderPanelInterface Functions
			LINKS ARE HTML LINKS, AND URLS ARE THE URLS THAT THE HREF VALUE
		============================================================ */
		public function generate_loadlink() {
            $bootstrap = new Contento();
            $href = $this->generate_loadurl();
            $ajaxdata = $this->generate_ajaxdataforcontento();
            return $bootstrap->openandclose('a', "href=$href|class=generate-load-link|$ajaxdata", "Load Quotes");
        }
		
		public function generate_expandorcollapselink(Order $quote) {
			$bootstrap = new Contento();
			
			if ($this->activeID == $quote->quotnbr) {
				$href = $this->generate_closedetailsurl();
				$ajaxdata = $this->generate_ajaxdataforcontento();
				$addclass = 'load-link';
				$icon = '-';
			} else {
				$href = $this->generate_loaddetailsurl($quote);
				$ajaxdata = "data-loadinto=$this->loadinto|data-focus=#$quote->quotnbr";
				$addclass = 'generate-load-link';
				$icon = '+';
			}
			return $bootstrap->openandclose('a', "href=$href|class=btn btn-sm btn-primary $addclass|$ajaxdata", $icon);
		}
		
		public function generate_closedetailsurl() { 
			$url = new \Purl\Url($this->pageurl->getUrl());
			$url->query->setData(array('qnbr' => false, 'show' => false));
			return $url->getUrl();
		}
		
		public function generate_rowclass(Order $quote) {
			return ($this->activeID == $quote->quotnbr) ? 'selected' : '';
		}
		
		function generate_shiptopopover(Order $quote) {
			$bootstrap = new Contento();
			$address = $quote->saddress.'<br>';
			$address .= (!empty($quote->saddress2)) ? $quote->saddress2."<br>" : '';
			$address .= $quote->scity.", ". $quote->sst.' ' . $quote->szip;
			$attr = "tabindex=0|role=button|class=btn btn-default bordered btn-sm|data-toggle=popover";
			$attr .= "|data-placement=top|data-trigger=focus|data-html=true|title=Ship-To Address|data-content=$address";
			return $bootstrap->openandclose('a', $attr, '<b>?</b>');
		}
		
		public function generate_iconlegend() {
			$bootstrap = new Contento();
			$content = $bootstrap->openandclose('i', 'class=glyphicon glyphicon-shopping-cart|title=Re-order Icon', '') . ' = Re-order <br>';
			$content .= $bootstrap->openandclose('i', "class=material-icons|title=Documents Icon", '&#xE873;') . '&nbsp; = Documents <br>'; 
			$content .= $bootstrap->openandclose('i', 'class=glyphicon glyphicon-plane hover|title=Tracking Icon', '') . ' = Tracking <br>';
			$content .= $bootstrap->openandclose('i', 'class=material-icons|title=Notes Icon', '&#xE0B9;') . ' = Notes <br>';
			$content .= $bootstrap->openandclose('i', 'class=glyphicon glyphicon-pencil|title=Edit Order Icon', '') . ' = Edit Order <br>'; 
			$content = str_replace('"', "'", $content);
			$attr = "tabindex=0|role=button|class=btn btn-sm btn-info|data-toggle=popover|data-placement=bottom|data-trigger=focus";
			$attr .= "|data-html=true|title=Icons Definition|data-content=$content";
			return $bootstrap->openandclose('a', $attr, 'Icon Definitions');
		}

		public function generate_loadurl() { 
			$url = new \Purl\Url($this->pageurl->getUrl());
			$url->path = wire('config')->pages->quotes.'redir/';
			$url->query->setData(array('action' => 'load-quotes'));
			return $url->getUrl();
		}
		
		public function generate_searchlink() {
			$bootstrap = new Contento();
			$href = $this->generate_searchurl();
			return $bootstrap->openandclose('a', "href=$href|class=btn btn-default bordered load-into-modal|data-modal=$this->modal", "Search Orders");
		}
		
		public function generate_searchurl() {
			$url = new \Purl\Url($this->pageurl->getUrl());
			$url->path = wire('config')->pages->ajax.'load/quotes/search/';
			$url->query = '';
			return $url->getUrl();
		}
		
		public function generate_loaddetailsurl(Order $quote) {
			$url = new \Purl\Url($this->generate_loaddetailsurltrait($quote));
			$url->query->set('page', $this->pagenbr);
			$url->query->set('orderby', $this->tablesorter->orderbystring);
			return $url->getUrl();
		}
		
		/* =============================================================
            OrderDisplayInterface Functions
            LINKS ARE HTML LINKS, AND URLS ARE THE URLS THAT THE HREF VALUE
        ============================================================ */
		public function generate_loaddplusnoteslink(Order $quote, $linenbr = '0') {
			$bootstrap = new Contento();
			$href = $this->generate_dplusnotesrequesturl($quote, $linenbr);
			
			if ($quote->can_edit()) {
				$title = ($quote->has_notes()) ? "View and Create Order Notes" : "Create Order Notes";
				$addclass = ($quote->has_notes()) ? '' : 'text-muted';
			} else {
				$title = ($quote->has_notes()) ? "View Order Notes" : "View Order Notes";
				$addclass = ($quote->has_notes()) ? '' : 'text-muted';
			}
			$content = $bootstrap->createicon('material-icons md-36', '&#xE0B9;');
			$link = $bootstrap->openandclose('a', "href=$href|class=load-notes $addclass|title=$title|data-modal=$this->modal", $content);
			return $link;
		}
		
		public function generate_documentsrequesturl(Order $quote) {
            $url = new \Purl\Url($this->generate_documentsrequesturltrait($quote));
			$url->query->set('page', $this->pagenbr);
			$url->query->set('orderby', $this->tablesorter->orderbystring);
            return $url->getUrl();
        }
		
		public function generate_viewlinkeduseractionslink(Order $quote) {
			$bootstrap = new Contento();
			$href = $this->generate_viewlinkeduseractionsurl($quote);
			$icon = $bootstrap->openandclose('span','class=h3', $bootstrap->createicon('glyphicon glyphicon-check'));
			return $bootstrap->openandclose('a', "href=$href|class=load-into-modal|data-modal=$this->modal", $icon." View Associated Actions");
		}
		
		public function generate_editlink(Order $quote) {
			$bootstrap = new Contento();
			
			if (wire('user')->hasquotelocked) {
				if ($quote->quotnbr == wire('user')->lockedqnbr) {
					$icon = $bootstrap->createicon('glyphicon glyphicon-wrench');
					$title = "Continue editing this Quote";
				} else {
					$icon = $bootstrap->createicon('material-icons md-36', '&#xE897;');
					$title = "Open Quote in Read Only Mode";
				}
			} else {
				$icon = $bootstrap->createicon('glyphicon glyphicon-pencil');
				$title = "Edit Quote";
			}
		
			$href = $this->generate_editurl($quote);
			return $bootstrap->openandclose('a', "href=$href|class=edit-order h3|title=$title", $icon);
		}
		
		public function generate_loaddocumentslink(Order $quote) {
            $bootstrap = new Contento();
            $href = $this->generate_documentsrequesturl($quote);
            $icon = $bootstrap->createicon('material-icons md-36', '&#xE873;');
            $ajaxdata = $this->generate_ajaxdataforcontento();
            
            if ($quote->has_documents()) {
                return $bootstrap->openandclose('a', "href=$href|class=generate-load-link|title=Click to view Documents|$ajaxdata", $icon);
            } else {
                return $bootstrap->openandclose('a', "href=#|class=text-muted|title=No Documents Available", $icon);
            }
        }
		
	}
