<?php 
	class Quote extends Order {
		use CreateFromObjectArrayTraits;
		use CreateClassArrayTraits;
		
		protected $quotnbr; 
		protected $careof;
		protected $revdate;
		protected $expdate;
		protected $sp1pct;
		protected $sp2pct;
		protected $sp3pct;
		protected $fob;
		protected $deliverydesc;
		protected $cost_total;
		protected $margin_amt;
		protected $margin_pct;
		
		//FOR SQL
		protected $quotedate;
		protected $reviewdate;
		protected $expiredate;
				
		/* =============================================================
			GETTER FUNCTIONS
		============================================================ */
		public function has_documents() {
			//return $this->hasdocuments == 'Y' ? true : false;
			return false;
		}

		public function has_notes() {
			return $this->hasnotes == 'Y' ? true : count_qnotes($this->sessionid, $this->quotnbr, '0', Qnote::get_qnotetype('quote'));
		}

		public function can_edit() {
			$quoteconfig = Processwire\wire('pages')->get('/config/')->child('name=quotes');
			return $quoteconfig->allow_edit;
		}

		// public function is_phoneintl() {
		// 	return $this->phintl == 'Y' ? true : false;
		// }

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
		public static function load($sessionID, $qnbr) {
			return get_quotehead($sessionID, $qnbr, true, false);
		}

		public function update($debug = false) {
			return edit_quotehead($this->sessionid, $this->quotnbr, $this, $debug);
		}
		
		public function has_changes() {
			$properties = array_keys(get_object_vars($this));
			$quote = Quote::load($this->sessionid, $this->quotnbr);
			
			foreach ($properties as $property) {
				if ($this->$property != $quote->$property) {
					return true;
				}
			}
			return false;
		}
	}
