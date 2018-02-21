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
			$detail = self::load($sessionID, $linenbr, $debug);
			
			foreach ($properties as $property) {
				if ($this->$property != $detail->$property) {
					return true;
				}
			}
			return false;
		}
	}
