<?php 
	abstract class OrderDisplay {
		use ThrowErrorTrait;
		
		protected $pageurl;
		protected $sessionID;
		protected $modal;
		
		public function __construct($sessionID, \Purl\Url $pageurl, $modal = false) {
			$this->sessionID = $sessionID;
			$this->pageurl = new \Purl\Url($pageurl->getUrl());
			$this->modal = $modal;
		}
		
		public function __get($property) {
			$method = "get_{$property}";
			if (method_exists($this, $method)) {
				return $this->$method();
			} elseif (property_exists($this, $property)) {
				return $this->$property;
			} else {
				$this->error("This property ($property) does not exist");
				return false;
			}
		}
		
		/* =============================================================
			Helper Functions
		============================================================ */
		public function generate_customershiptolink(Order $quote) {
			$bootstrap = new Contento();
			$href = $this->generate_customershiptourl($quote);
			$icon = $bootstrap->createicon('fa fa-user');
			return $bootstrap->openandclose('a', "href=$href|class=btn btn-block btn-primary", $icon. " Go to Customer Page");   
		}
		
		public function generate_customerredirurl() {
			$url = new \Purl\Url(Processwire\wire('config')->pages->orders);
			$url->path = Processwire\wire('config')->pages->customer."redir/";
			return $url;
		}
		
		/* =============================================================
			OrderDisplay Interface Functions
		============================================================ */
		public function generate_customerurl(Order $order) {
			$url = $this->generate_customerredirurl();
			$url->query->setData(array('action' => 'ci-customer', 'custID' => $order->custid));
			return $url->getUrl();
		}
		
		public function generate_customershiptourl(Order $order) {
			$url = new \Purl\Url($this->generate_customerurl($order));
			if (!empty($order->shiptoid)) $url->query->set('shipID', $order->shiptoid);
			return $url->getUrl();
		}
	}
