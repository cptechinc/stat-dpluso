<?php 
	class CartQuote extends Order implements OrderInterface {
		use CreateFromObjectArrayTraits;
		use CreateClassArrayTraits;
		
		protected $custname;
		protected $orderno;
		protected $orderdate;
		protected $invdate;
		protected $shipdate;
		protected $revdate;
		protected $expdate;
		protected $hasdocuments;
		protected $hastracking;
		protected $editord;
		protected $sconame;
		protected $phintl;
		protected $extension;
		protected $releasenbr;
		protected $pricedisp;
		protected $taxcodedisp;
		protected $termtype;
		protected $termdesc;
		protected $rqstdate;
		protected $shipcom;
		protected $fob;
		protected $deliverydesc;
		protected $cardnumber;
		protected $cardexpire;
		protected $cardcode;
		protected $cardapproval;
		protected $totalcost;
		protected $totaldiscount;
		protected $paymenttype;
		protected $srcdatefrom;
		protected $srcdatethru;
		protected $prntfmt;
		protected $prntfmtdisp;
		
		
		/* =============================================================
			GETTER FUNCTIONS 
		============================================================ */
		public function has_documents() {
			return $this->hasdocuments == 'Y' ? true : false;
		}

		public function has_tracking() {
			return $this->hastracking == 'Y' ? true : false;
		}
		
		public function has_notes() {
			return $this->hasnotes == 'Y' ? true : false;
		}

		public function can_edit() {
			return true;
		}

		public function is_phoneintl() {
			return false;
		}
		
		/* =============================================================
			GENERATE ARRAY FUNCTIONS 
			The following are defined CreateClassArrayTraits
			public static function generate_classarray()
			public function _toArray()
		============================================================ */
		public static function remove_nondbkeys($array) {
			return $array;
		}
		
		/* =============================================================
			CRUD FUNCTIONS
		============================================================ */
		public static function load($sessionID, $debug = false) {
			return get_carthead($sessionID, true, $debug);
		}
		
		public function update($debug = false) {
		//	return edit_orderhead($this->sessionid, $this->orderno, $this, $debug); TODO
		}
		
		public function has_changes() {
			$properties = array_keys(get_object_vars($this));
			$cart = self::load($this->sessionid);
			
			foreach ($properties as $property) {
				if ($this->$property != $carthead->$property) {
					return true;
				}
			}
			return false;
		}
	}
