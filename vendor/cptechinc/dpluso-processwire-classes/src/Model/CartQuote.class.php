<?php
	/**
	 * Class to handle Quotes
	 */
	class CartQuote extends Order implements OrderInterface {
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
		/**
		 * Mainly called by the _toArray() function which makes an array
		 * based of the properties of the class, but this function filters the array
		 * to remove keys that are not in the database
		 * This is used by database classes for update
		 * @param  array $array array of the class properties
		 * @return array        with certain keys removed
		 */
		public static function remove_nondbkeys($array) {
			return $array;
		}

		/* =============================================================
			CRUD FUNCTIONS
		============================================================ */
		/**
		 * returns CartQuote from carthed table
		 * @param  string $sessionID Session
		 * @param  bool   $debug     Whether or not to Return Cart Quote or SQL QUERY
		 * @return CartQuote         Or SQL Query
		 * @uses Read (CRUD)
		 * @source _dbfunc.php
		 */
		public static function load($sessionID, $debug = false) {
			return get_carthead($sessionID, $debug);
		}

		public function update($debug = false) {
			// TODO
		}

		/**
		 * Returns if Cart Header has any changes by comparing it to the original
		 * @return bool
		 * @uses CartQuote::load
		 */
		public function has_changes() {
			$properties = array_keys(get_object_vars($this));
			$cart = self::load($this->sessionid);

			foreach ($properties as $property) {
				if ($this->$property != $cart->$property) {
					return true;
				}
			}
			return false;
		}

		/**
		 * Returns Call to db function for getting the Customer ID
		 * Off the cartheader
		 * @param  string $sessionID Session Identifier
		 * @param  bool   $debug     Run in debug? If so return SQL Query
		 * @return string            Customer ID
		 */
		public static function get_cartcustid($sessionID, $debug = false) {
			return get_custidfromcart($sessionID, $debug);
		}

	}
