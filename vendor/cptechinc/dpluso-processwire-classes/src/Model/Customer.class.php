<?php 
	/**
	 * Class for dealing with Customers
	 * Derived from the custindex Table
	 */
    class Customer extends Contact {
		
        /* =============================================================
			GETTER FUNCTIONS 
		============================================================ */
		/**
		 * Returns the customer name or just return the custid
		 * @return string Name or Customer ID
		 */
        public function get_name() {
            return (!empty($this->name)) ? $this->name : $this->custid;
        }
        
		/**
		 * Return the number of shiptos this Customer has
		 * it also takes in account if user has customer restrictions
		 * @param  bool   $debug Whether to return Number or SQL Query
		 * @return int    # of Shiptos or SQL Query
		 */
        public function get_shiptocount($debug = false) {
            return count_shiptos($this->custid, DplusWire::wire('user')->loginid, $debug);
        }
        
		/**
		 * Gets the next shiptoid for this Customer
		 * if shiptoid is defined then we get the next one
		 * if no next one, go to the first one
		 * Used for CI page
		 * @return string shiptoid
		 */
        public function get_nextshiptoid() {
            $shiptos = get_customershiptos($this->custid, DplusWire::wire('user')->loginid);
            if (sizeof($shiptos) < 1) {
                return false;
            } else {
                if ($this->has_shipto()) {
                    for ($i = 0; $i < sizeof($shiptos); $i++) {
                        if ($shiptos[$i]->shiptoid == $this->shiptoid) {
                            break;
                        }
                    }
                    $i++; // Get the next 
                    
                    if ($i > sizeof($shiptos)) {
                        return $shiptos[0]->shiptoid;
                    } elseif ($i == sizeof($shiptos)) {
                        return $shiptos[$i - 1]->shiptoid;
                    } else {
                        $shiptos[$i]->shiptoid;
                    }
                } else {
                    return $shiptos[0]->shiptoid;
                }
            }
        }
        
        /* =============================================================
			CLASS FUNCTIONS 
		============================================================ */
		/**
		 * Generates Title for CI page
		 * @return string title for CI page
		 */
        public function generate_title() {
            return $this->get_name() . (($this->has_shipto()) ? ' Ship-to: ' . $this->shiptoid : '');
        }
		
		/** 
		 * Generates an array for the Sales Data for this Customer
		 * so it can be used in Morris.js to draw up a pie chart
		 * @param  float $value Amount
		 * @return array        has the Name, value, custid and shiptoid in an array
		 */
		public function generate_piesalesdata($value) {
			return array(
				'label' => $this->get_name(),
				'value' => $value,
				'custid' => $this->custid,
				'shiptoid' => $this->shiptoid
			);
		}
		
		
		/**
		 * Return URL to the add Contact form
		 * @return string  Add Contact URL
		 */
		public function generate_addcontacturl() {
			$url = new \Purl\Url(DplusWire::wire('config')->pages->contact.'add/');
            $url->query->set('custID', $this->custid);
            
            if ($this->has_shipto()) {
                $url->query->set('shipID', $this->shiptoid);
            } 
            return $url->getUrl();
		}
        
		/* =============================================================
			CRUD FUNCTIONS
		============================================================ */
		/**
		 * Loads an object with this class using the parameters as provided
		 * @param  string $custID    CustomerID
		 * @param  string $shiptoID  Shipto ID (can be blank)
		 * @param  string $contactID Contact ID (can be blank)
		 * @param  bool   $debug Determines if Query Runs and if Customer Object is returned or SQL Query
		 * @return Customer
		 */
        public static function load($custID, $shiptoID = '', $contactID = '', $debug = false) {
            return get_customer($custID, $shiptoID, $contactID, $debug);
        }
		
		/**
		 * Changes customer's custid
		 * @param  string $currentID current customerID
		 * @param  string $newcustID new customerID
		 * @param  bool   $debug     Whether to execute change
		 * @return string            SQL Query
		 */
		public static function change_custid($currentID, $newcustID, $debug = false) {
            $currentID = substr($currentID, 0, 6);
			return change_custindexcustid($currentID, $newcustID, $debug). " - " . change_custpermcustid($currentID, $newcustID, $debug);
		}
		
		/* =============================================================
			STATIC FUNCTIONS
		============================================================ */
		/**
		 * Uses the static function load() function to load the customer
		 * then uses, the Customer->get_customername() to return the name
		 * @param  string $custID   customerID
		 * @param  string $shiptoID customer shiptoID
		 * @return string           Customer Name
		 * @uses load()
		 */
		public static function get_customernamefromid($custID, $shiptoID = '') {
			$customer = self::load($custID, $shiptoID);
			return $customer ? $customer->get_customername() : $custID;
		}
		
		/** 
		 * Generates an array for the bookings Data for this Customer
		 * so it can be used in Morris.js to draw up a pie chart
		 * if customer can't be found, then return data with as much
		 * info as possible
		 * @param  string $custID   Customer
		 * @param  string $shiptoID Shipto ID
		 * @param  float  $value    Amount
		 * @return array        has the Name, value, custid and shiptoid in an array
		 */
		public static function generate_bookingsdata($custID, $shiptoID, $value) {
			$customer = self::load($custID, $shiptoID);
			
			return array(
				'label' => $customer ? $customer->get_name() : $custID,
				'value' => $value,
				'custid' => $custID,
				'shiptoid' => $shiptoID
			);
		}
    }
