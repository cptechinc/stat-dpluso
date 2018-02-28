<?php 
	class CartDetail extends OrderDetail implements OrderDetailInterface {
		use CreateFromObjectArrayTraits;
		use CreateClassArrayTraits;
	
		protected $orderno;
		protected $price;
		protected $qty;
		protected $qtyshipped;
		protected $qtybackord;
		protected $hasdocuments;
		protected $qtyavail;
		protected $cost;
		protected $promocode;
		protected $taxcodeperc;
		protected $uomconv;
		protected $catlgid;
		protected $ponbr;
		protected $poref;
				
		/* =============================================================
			GETTER FUNCTIONS
		============================================================ */
		public function has_error() {
			return !empty($this->errormsg);
		}
		
		public function is_kititem() {
			return $this->kitemflag == 'Y' ? true : false;
		}
		
		public function has_documents() {
			return $this->hasdocuments == 'Y' ? true : false;
		}
		
		public function has_notes() {
			return has_dplusnote($this->sessionid, $this->sessionid, $this->linenbr, wire('config')->dplusnotes['cart']['type']) == 'Y' ? true : false;
		}
		
		public function can_edit() {
			return true;
		}
		
		/* =============================================================
			GENERATE ARRAY FUNCTIONS 
			The following are defined CreateClassArrayTraits
			public static function generate_classarray()
			public function _toArray()
		============================================================ */
		public static function remove_nondbkeys($array) {
			unset($array['sublinenbr']);
			unset($array['status']);
			unset($array['custid']);
			unset($array['ordrtotalcost']);
			unset($array['lostreason']);
			unset($array['lostdate']);
			unset($array['stancost']);
			return $array;
		}
		
		/* =============================================================
			CRUD FUNCTIONS
		============================================================ */
		public static function load($sessionID, $linenbr, $debug = false) {
			return get_cartdetail($sessionID, $linenbr, $debug);
		}
		
		public function update($debug = false) {
			return update_cartdetail($this->sessionid, $this, $debug);
		}
		
		public function has_changes() {
			$properties = array_keys(get_object_vars($this));
			$detail = self::load($this->sessionid, $this->linenbr, false);
			
			foreach ($properties as $property) {
				if ($this->$property != $detail->$property) {
					return true;
				}
			}
			return false;
		}
	}
