<?php 
    class Quote extends Order {
        use CreateFromObjectArrayTraits;
        use CreateClassArrayTraits;
        
        public $sessionid;
        public $recno;
        public $date;
        public $time;
        public $quotnbr; 
        public $status;
        public $custid;
        public $btname;
        public $btadr1;
        public $btadr2;
        public $btadr3;
        public $btctry;
        public $btcity;
        public $btstate;
        public $btzip;
        public $shiptoid;
        public $stname;
        public $stadr1;
        public $stadr2;
        public $stadr3;
        public $stctry;
        public $stcity;
        public $ststate;
        public $stzip;
        public $contact; 
        public $telenbr;
        public $faxnbr;
        public $emailadr;
        public $careof; 
        public $quotdate;
        public $revdate;
        public $expdate;
        public $priccode;
        public $priccodedesc; 
        public $taxcode;
        public $taxcodedesc; 
        public $termcode;
        public $termcodedesc; 
        public $sviacode;
        public $sviacodedesc; 
        public $sp1;
        public $sp1pct;
        public $sp1name; 
        public $sp2;
        public $sp2pct;
        public $sp2name; 
        public $sp3;
        public $sp3pct;
        public $sp3name; 
        public $fob;
        public $deliverydesc; 
        public $whse;
        public $custpo; 
        public $custref;
        public $notes;
        public $error;
        public $errormsg;
        public $subtotal;
        public $salestax;
        public $freight;
        public $miscellaneous;
        public $order_total;
        public $cost_total;
        public $margin_amt;
        public $margin_pct;
        public $dummy;
                
        /* =============================================================
 		   GETTER FUNCTIONS 
 	   ============================================================ */
		public function has_documents() {
			//return $this->havedoc == 'Y' ? true : false;
			return false;
		}

		public function has_notes() {
			return $this->notes == 'Y' ? true : false;
		}

		public function can_edit() {
			$quoteconfig = Processwire\wire('pages')->get('/config/')->child('name=quotes');
            return $quoteconfig->allow_edit;
		}

		// public function is_phoneintl() {
		// 	return $this->phintl == 'Y' ? true : false;
		// }

		public function has_error() {
			return $this->error == 'Y' ? true : false;
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
                   Processwire\wire('session')->property = $property;
                   return true;
               }
           }
           return false;
       }
       
    }
