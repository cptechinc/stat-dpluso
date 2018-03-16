<?php 
	class CartTest {
		protected $sessionID;
		protected $redir;
		protected $config;
		
		public function __construct($sessionID, \Purl\Url $url, $config) {
			$this->sessionID = $sessionID;
			$this->redir = new \Purl\Url($url->getUrl());
			$this->redir->path = DplusWire::wire('config')->pages->cart.'redir/';
			$this->config = $config;
		}
		
		/* =============================================================
			TEST FEATURE FUNCTIONS
		============================================================ */
		/**
		 * Sends the Empty Cart Request to Dplus
		 * @return bool Checks if Cart has been emptied
		 */
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
		
		/**
		 * Adds the First Item from the config
		 * Then checks if the cart has more items than before, 
		 * then it checks if the last item in the cart's item Id maatches
		 */
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
		
		/**
		 * Adds two items from the config, then checks the
		 * the last added item's item ID matches the second ItemID from config
		 */
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
		
		/**
		 * Adds the Non-Stock Item From config,
		 * then verifies by comparing itemids to the last item in the cart
		 */
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
		
		/**
		 * Sends the Edit Detail Request
		 * 1. Checks the CartDetail set functions work
		 * 2. Verifies that CartDetail has been edited to match config
		 * 3. Sends Dplus Request
		 * 4. Verifies Dplus Request has been edited to match config
		 * @param  int $linenbr Line # to edit
		 * @return array          array of key value of the sections tested and their bool results
		 */
		public function edit_detail($linenbr) {
			$cartdetail = CartDetail::load($this->sessionID, $linenbr);
			$beforedplus = $afterdplus = false;
			
			if ($cartdetail) {
				$cartdetail->set('price', $this->config['edit']['price']);
				$cartdetail->set('discpct', $this->config['edit']['discount']);
				$cartdetail->set('qty', $this->config['edit']['qty']);
				$cartdetail->set('rshipdate', $this->config['edit']['rqstdate']);
				$cartdetail->set('whse', $this->config['edit']['whse']);
				$cartdetail->set('spcord', $this->config['edit']['specialorder']);
				$cartdetail->set('vendorid', $this->config['edit']['vendorID']);
				$cartdetail->set('shipfromid', $this->config['edit']['shipfromID']);
				$cartdetail->set('vendoritemid', $this->config['edit']['vendoritemID']);
				$cartdetail->set('nsitemgroup', $this->config['edit']['nsitemgroup']);
				$cartdetail->set('ponbr', $this->config['edit']['ponbr']);
				$cartdetail->set('poref', $this->config['edit']['poref']);
				$cartdetail->set('uom', $this->config['edit']['uofm']);
				DplusWire::wire('session')->sql = $cartdetail->update();
				$beforedplus = $this->check_editdetail($linenbr);
				$data = $cartdetail->generate_editdetaildata($this->config['custID']);
				writedplusfile($data, $this->sessionID);
				$url = new \Purl\Url($this->redir->getUrl());
				$url->path = '/cgi-bin/'.DplusWire::wire('config')->cgi.'/';
				$url->query->set('fname', $this->sessionID);
				curl_redir($url->getUrl());
				$afterdplus = $this->check_editdetail($linenbr);
			}
			
			$response = array(
				'line-exists' => $cartdetail ? true : false,
				'before-dplus' => $beforedplus,
				'after-dplus' => $afterdplus
			);
			return $response;
		}
		
		/**
		 * Add Qnote to the cart detail Line
		 * @param int $linenbr Detail Line #
		 */
		public function add_qnote($linenbr) {
			$intialcount = count_qnotes($this->sessionID, $this->sessionID, $this->sessionID, Qnote::get_qnotetype('cart'));
			$url = new \Purl\Url($this->redir->getUrl());
			$url->path = DplusWire::wire('config')->pages->notes;
			$fields = $this->config['note'];
			$fields['action'] = 'add-note';
			$fields['type'] = Qnote::get_qnotetype('cart');
			curl_post($url->getUrl(), $fields);
			$aftercount = count_qnotes($this->sessionID, $this->sessionID, $this->sessionID, Qnote::get_qnotetype('cart'));
			return ($aftercount > $intialcount) ? true : false;
		}
		
		/* =============================================================
			CHECK FUNCTIONS
		============================================================ */
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
		
	}
