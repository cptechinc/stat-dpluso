<?php 

	class SalesOrderPanel {
		public $type = 'cust';
		public $modal;
		public $focus;
		public $loadinto;
		public $ajaxdata;
		public $pageurl;
		public $throughajax;
		public $sessionID;
		public $collapse = 'collapse';
		public $tablesorter; // Will be instatnce of TablePageSorter
		public $nextorder;
		public $orderby;
		public $pagenbr; // Will be instance of \Purl\Url
		public $active;
		public $custID;
		public $shipID;
		public $count;
		public $orders = array();
		private $debugorders;
		
		public function __construct($type, \Purl\Url $pageurl, $modal, $loadinto, $ajax, $sessionID) {
			$this->type = $type;
			$this->pageurl = $this->setup_pageurl($pageurl);
			$this->modal = $modal;
			$this->loadinto = $this->focus = $loadinto;
			$this->ajaxdata = 'data-loadinto="'.$this->loadinto.'" data-focus="'.$this->focus.'"';
			$this->sessionID = $sessionID;
			
			$this->tablesorter = new TablePageSorter($this->pageurl->query->get('orderby'));
			
			if ($ajax) {
				$this->collapse = '';
			} else {
				$this->collapse = 'collapse';
			}
		}
		
		/* =============================================================
			SETUP FUNCTIONS = FUNCTIONS THAT PREPARE THE SALES ORDER PANEL
		============================================================ */
		public function setup_pageurl($pageurl) {
			$url = $pageurl;
			$url->path = wire('config')->pages->ajax."load/orders/";
			$url->query->remove('display');
			$url->query->remove('ajax');
			return $url;
		}

		public function setup_customerpanel($custID, $shipID) {
			$this->custID = $custID;
			$this->shipID = $shipID;
			$this->pageurl->path->add('cust')->add("$custID");
			if (!empty($shipID)) $this->pageurl->path->add("shipto-$shipID");
		}
		
		public function setup_reppanel() {
			$this->pageurl->path->add('salesrep');
		}
		
		public function set_activeorder($ordn) {
			$this->active = $ordn;
		}
		
		/* =============================================================
			ORDER RETREIVAL FUNCTIONS
		============================================================ */
		public function get_orders() {
			if ($this->type == 'cust') {
				$this->orders = $this->get_customerorders();
				$this->debugorders = $this->get_customerorders(true);
			} else {
				$this->orders = $this->get_salesreporders(); // TODO
			}
		}
		
		public function get_ordercount() {
			if ($this->type == 'cust') {
				$this->count = $this->get_customerordercount();
			} else {
				$this->count = $this->get_repordercount(); // TODO
			}
		}
		
		protected function get_customerordercount() {
			return count_customerorders($this->sessionID, $this->custID, false);
		}
		
		protected function get_repordercount() {
			return count_salesreporders($this->sessionID, false);
		}
		
		protected function get_customerorders($debug = false) {
			$useclass = true;
			if ($this->tablesorter->orderby) {
				if ($this->tablesorter->orderby == 'orderdate') {
					return get_customerordersorderdate($this->sessionID, $this->custID, wire('session')->display, $this->pagenbr, $this->tablesorter->sortrule, $useclass, $debug);
				} else {
					return get_customerordersorderby($this->sessionID, $this->custID, wire('session')->display, $this->pagenbr, $this->tablesorter->sortrule, $this->tablesorter->orderby, $useclass, $debug);
				}
			} else {
				$this->tablesorter->sortrule = 'DESC'; $this->tablesorter->orderby = 'orderno';
				return get_customerordersorderby($this->sessionID, $this->custID, wire('session')->display, $this->pagenbr, $this->tablesorter->sortrule, $this->tablesorter->orderby, $useclass, $debug);
			}
		}
		
		protected function get_salesreporders($debug = false) {
			$useclass = true; 
			if ($this->tablesorter->orderby) {
				if ($this->tablesorter->orderby == 'orderdate') {
					return get_salesrepordersorderdate($this->sessionID, wire('session')->display, $this->pagenbr, $this->tablesorter->sortrule, $useclass, false);
				} else {
					return get_salesrepordersorderby($this->sessionID, wire('session')->display, $this->pagenbr, $this->tablesorter->sortrule, $this->tablesorter->orderby, $useclass, false);
				}
			} else {
				$this->tablesorter->sortrule = 'DESC'; $this->tablesorter->orderby = 'orderno';
				return get_salesrepordersorderby($this->sessionID, wire('session')->display, $this->pagenbr, $this->tablesorter->sortrule, $this->tablesorter->orderby, $useclass, false);
			}
		}
		
		/* =============================================================
			CONTENT FUNCTIONS
		============================================================ */
		public function generate_salesordericonlegend() {
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
		
		/* =============================================================
			LINK FUNCTIONS -> FUNCTIONS THAT CREATE THE HTML LINKS
		============================================================ */
		public function generate_loadorderslink() {
			$bootstrap = new Contento();
			$href = $this->generate_loadordersurl();
			$ajaxdata = $this->generate_ajaxdataforcontento();
			return $bootstrap->openandclose('a', "href=$href|class=generate-load-link|$ajaxdata", "Load Orders");
		}
		
		public function generate_refreshorderslink() {
			$bootstrap = new Contento();
			$href = $this->generate_loadordersurl();
			$icon = $bootstrap->createicon('fa fa-refresh');
			$ajaxdata = $this->generate_ajaxdataforcontento();
			return $bootstrap->openandclose('a', "href=$href|class=generate-load-link|$ajaxdata", "$icon Refresh Orders");
		}
		
		public function generate_clearsearchlink() {
			$bootstrap = new Contento();
			$href = $this->generate_loadordersurl();
			$ajaxdata = $this->generate_ajaxdataforcontento();
			return $bootstrap->openandclose('a', "href=$href|class=btn btn-warning generate-load-link|$ajaxdata", "Clear Search");
		}
		
		function generate_ordersearchlink() {
			$bootstrap = new Contento();
			$href = $this->generate_ordersearchurl();
			return $bootstrap->openandclose('a', "href=$href|class=btn btn-default bordered load-into-modal|data-modal=$this->modal", "Search Orders");
		}
		
		public function generate_clearsortlink() {
			$bootstrap = new Contento();
			$href = $this->generate_clearsorturl();
			$ajaxdata = $this->generate_ajaxdataforcontento();
			return $bootstrap->openandclose('a', "href=$href|class=btn btn-warning btn-sm load-link|$ajaxdata", '(Clear Sort)');
		}
		
		/* =============================================================
			URL FUNCTIONS -> FUNCTIONS THAT CREATE URL FOR LINKS
		============================================================ */
		public function generate_loadordersurl() { //USED BY generate_clearsearchlink(), generate_loadorderslink(), generate_refreshorderslink() 
			$url = new \Purl\Url($this->pageurl->getUrl());
			$url->path = wire('config')->pages->orders.'redir/';
			if ($this->type == 'cust') {
				$url->query->setData(array('action' => 'load-cust-orders', 'custID' => $this->custID));
			} else {
				$url->query->setData(array('action' => 'load-orders'));
			}
			return $url->getUrl();
		}
		
		public function generate_ordersearchurl() {
			$url = new \Purl\Url($this->pageurl->getUrl());
			$url->path = wire('config')->pages->ajax.'load/orders/search/';
			$url->query = '';
			if ($this->type = 'cust') {
				$url->path->add('cust');
				$url->query->set('custID', $this->custID);
				if ($this->shipID) {
					$url->query->set('shipID', $this->shipID);
				}
			}
			return $url->getUrl();
		}
		
		public function generate_tableorderbyurl($column) {
			$url = new \Purl\Url($this->pageurl->getUrl());
			$url->query->set("orderby", "$column-".$this->tablesorter->generate_columnsortingrule($column));
			return $url->getUrl();
		}
		
		public function generate_clearsorturl() {
			$url = new \Purl\Url($this->pageurl->getUrl());
			$url->query->remove("orderby");
			return $url->getUrl();
		}
		
		/* =============================================================
			STRING FUNCTIONS
		============================================================ */
		public function generate_ajaxdataforcontento() {
			return str_replace(' ', '|', str_replace("'", "", str_replace('"', '', $this->ajaxdata)));
		}
		
		
	}
