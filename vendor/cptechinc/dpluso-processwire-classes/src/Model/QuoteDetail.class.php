<?php 
	class QuoteDetail extends OrderDetail implements OrderDetailInterface {
		use CreateFromObjectArrayTraits;
		use CreateClassArrayTraits;
		
		protected $quotenbr;
		protected $venddetail;
		protected $leaddays;
		protected $ordrqty;
		protected $ordrprice;
		protected $ordrcost;
		protected $ordrtotalprice;
		protected $costuom;
		protected $quotind;
		protected $quotqty;
		protected $quotprice;
		protected $quotcost;
		protected $quotmkupmarg;
		protected $error;
		
		/* =============================================================
			GETTER FUNCTIONS
		============================================================ */
		public function has_error() {
			return $this->error == 'Y' ? true : false;
		}
		
		public function has_documents() {
			//return $this->notes == 'Y' ? true : false;
			return false;
		}
		
		public function can_edit() {
			$quote = Quote::load($this->sessionid, $this->quotenbr);
			return $quote->can_edit();
		}
		
		/* =============================================================
			GENERATE ARRAY FUNCTIONS 
			The following are defined CreateClassArrayTraits
			public static function generate_classarray()
			public function _toArray()
		============================================================ */
		public static function remove_nondbkeys($array) {
			unset($array['totalprice']);
			return $array;
		}
		
		/* =============================================================
			CRUD FUNCTIONS
		============================================================ */
		public static function load($sessionID, $qnbr, $linenbr, $debug = false) {
			return get_quotedetail($sessionID, $qnbr, $linenbr, $debug);
		}
		
		public function update($debug = false) {
			return update_quotedetail($this->sessionid, $this, $debug);
		}
		
		public function has_changes() {
			$properties = array_keys(get_object_vars($this));
			$detail = self::load($this->sessionid, $this->quotenbr, $this->linenbr, false);
			
			foreach ($properties as $property) {
				if ($this->$property != $detail->$property) {
					return true;
				}
			}
			return false;
		}
	}
