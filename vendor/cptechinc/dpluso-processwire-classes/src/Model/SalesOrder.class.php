<?php 
	class SalesOrder extends Order implements OrderInterface {
		use CreateFromObjectArrayTraits;
		use CreateClassArrayTraits;
		
		protected $type;
		protected $custname;
		protected $orderno;
		protected $orderdate;
		protected $careof;
		protected $invdate;
		protected $shipdate;
		protected $revdate;
		protected $expdate;
		protected $havedoc;
		protected $havetrk;
		protected $editord;
		protected $sconame;
		protected $phintl;
		protected $extension;
		protected $releasenbr;
		protected $pricedisp;
		protected $taxcodedisp;
		protected $termtype;
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
		protected $dummy;
		
		/* =============================================================
 		   GETTER FUNCTIONS 
 	   	============================================================ */
		public function has_documents() {
			return $this->havedoc == 'Y' ? true : false;
		}

		public function has_tracking() {
			return $this->havetrk == 'Y' ? true : false;
		}
		
		public function has_notes() {
			return $this->hasnote == 'Y' ? true : false;
		}

		public function can_edit() {
			$config = Processwire\wire('pages')->get('/config/')->child("name=sales-orders");
			$allowed = $config->allow_edit;
			if ($config->allow_edit) {
				$allowed = has_dpluspermission(wire('user')->loginid, 'eso');
			}
			return $allowed ? ($this->editord == 'Y' ? true : false) : false;
		}

		public function is_phoneintl() {
			return $this->phintl == 'Y' ? true : false;
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
        public static function load($sessionID, $ordn) {
            return get_orderhead($sessionID, $ordn, true, false);
        }
		
		public function update($debug = false) {
            return edit_orderhead($this->sessionid, $this->orderno, $this, $debug);
        }
		
		public function update_payment($debug = false) {
			return edit_orderhead_credit($sessionID, $this->orderno, $this->paytype, $this->cardnumber, $this->cardexpire, $this->cardcode, $debug) ;
		}
		
		public function has_changes() {
            $properties = array_keys(get_object_vars($this));
            $order = SalesOrder::load($this->sessionid, $this->orderno);
            
            foreach ($properties as $property) {
                if ($this->$property != $order->$property) {
                    return true;
                }
            }
            return false;
        }
	}
