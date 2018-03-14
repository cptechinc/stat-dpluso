<?php 
	class CartTest {
		protected $sessionID;
		protected $redir;
		protected $config;
		
		public function __construct($sessionID, \Purl\Url $url, $config) {
			$this->sessionID = $sessionID;
			$this->redir = new \Purl\Url($url->getUrl());
			$this->redir->path = Processwire\wire('config')->pages->cart.'redir/';
			$this->config = $config;
		}
		
		public function empty_cart() {
			$fields = array(
				'action' => 'empty-cart',
				'sessionID' => $this->sessionID
			);
			$query = http_build_query($fields);
			curl_redir($this->redir->getUrl()."?$query");
			$count = count_cartdetails($this->sessionID);
			return $count == 0 ? true : false;
		}
		
		public function add_cart() {
			$initcount = count_cartdetails($this->sessionID);
			$fields = array(
				'action' => 'add-to-cart',
				'itemID' => $this->config['itemID'][0],
				'custID' => $this->config['custID'],
				'sessionID' => $this->sessionID,
				'qty' => 3
			);
			curl_post($this->redir->getUrl(), $fields);
			$count = count_cartdetails($this->sessionID);
			return $count > $initcount ? $this->check_addcart($count, $this->config['itemID'][0]) : false;
		}
		
		public function add_multiple() {
			$initcount = count_cartdetails($this->sessionID);
			$fields = array(
				'action' => 'add-multiple-items',
				'itemID' => array($this->config['itemID'][0], $this->config['itemID'][1]),
				'custID' => $this->config['custID'],
				'sessionID' => $this->sessionID,
				'qty' => array(3, 2)
			);
			curl_post($this->redir->getUrl(), $fields);
			$count = count_cartdetails($this->sessionID);
			return $count > $initcount ? $this->check_addcart($count, $this->config['itemID'][1]) : false;
		}
		
		public function add_nonstock() {
			$initcount = count_cartdetails($this->sessionID);
			$fields = $this->config['non-stock'];
			$fields['action'] = 'add-nonstock-item';
			$fields['sessionID'] = $this->sessionID;
			$fields['custID'] = $this->config['custID'];
			curl_post($this->redir->getUrl(), $fields);
			$count = count_cartdetails($this->sessionID);
			return $count > $initcount ? $this->check_addcart($count, $this->config['non-stock']['itemID'], true) : false;
		}
		
		public function edit_detail($linenbr) {
			$fields = $this->config['edit'];
			$fields['linenbr'] = $linenbr;
			$fields['action'] = 'update-line';
			$fields['sessionID'] = $this->sessionID;
			$fields['custID'] = $this->config['custID'];
			curl_post($this->redir->getUrl(), $fields);
			return $this->check_editdetail($linenbr);
		}
		
		public function check_editdetail($linenbr) {
			$cartdetail = CartDetail::load($this->sessionID, $linenbr);
			if (!$cartdetail) {
				return false;
			}
			return ($cartdetail->price == $this->config['edit']['price']) ? true : false;
		}
		
		public function check_addcart($linenbr, $itemID, $vendoritem = false) {
			$cartdetail = CartDetail::load($this->sessionID, $linenbr);
			if (!$cartdetail) {
				return false;
			}
			if ($vendoritem) {
				return $cartdetail->vendoritemid == $itemID ? true : false;
			} else {
				return $cartdetail->itemid == $itemID ? true : false;
			}
		}
		
		public function add_note($linenbr) {
			$url = new \Purl\Url($this->redir->getUrl());
			$url->path = Processwire\wire('config')->pages->notes;
			$fields = $this->config['note'];
			$fields['action'] = 'add-note';
			$fields['type'] = Qnote::get_qnotetype('cart');
			curl_post($url->getUrl(), $fields);
		}
	}
