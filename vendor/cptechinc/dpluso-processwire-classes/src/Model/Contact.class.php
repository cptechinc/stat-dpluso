<?php 
    class Contact {
        use CreateFromObjectArrayTraits;
		use CreateClassArrayTraits;
		use ThrowErrorTrait;
        
        public $recno;
		public $date;
		public $time;
		public $splogin1;
		public $splogin2;
		public $splogin3;
		public $custid;
		public $shiptoid;
		public $name;
		public $addr1;
		public $addr2;
		public $ccity;
		public $cst;
		public $czip;
		public $cphone;
		public $ccellphone;
		public $contact;
		public $source;
		public $cphext;
		public $amountsold;
		public $timesold;
		public $lastsaledate;
		public $email;
        public $fieldaliases = array(
            'custID' => 'custid',
            'shipID' => 'shiptoid',
        );
        
        /* =============================================================
			GETTER FUNCTIONS 
		============================================================ */
        public function __get($property) {
            $method = "get_{$property}";
            if (method_exists($this, $method)) {
                return $this->$method();
            } elseif (property_exists($this, $property)) {
                return $this->$property;
            } elseif (array_key_exists($property, $this->fieldaliases)) {
                $realproperty = $this->fieldaliases[$property];
                return $this->$realproperty;
            } else {
                $this->error("This property ($property) does not exist");
                return false;
            }
        }
        
		/**
		 * Grabs the name of the customer off the contact object, and if blank, 
		 * it will just return custid
		 * @return string customername
		 */
        public function get_customername() {
            return (!empty($this->name)) ? $this->name : $this->custid;
        }
        
        /**
         * Returns if Contact has a shiptoid
         * @return bool [description]
         */
        public function has_shipto() {
            return (!empty($this->shiptoid));
        }
        
        /**
         * Returns if contact has phone extension 
         * @return bool [description]
         */
        protected function has_extension() {
            return ($this->cphext != '') ? true : false;
        }
        
        /* =============================================================
			CLASS FUNCTIONS 
		============================================================ */
		/**
		 * Generates the URL to the customer page which currently
		 * goes to load the CI Page.
		 * @return string 
		 */
        public function generate_customerurl() {
            return $this->generate_ciloadurl();
        }
		
		/**
		 * Generates the customer URL but also defines the Shiptoid in the URL
		 * @return string
		 */
        public function generate_shiptourl() {
            return $this->generate_customerurl() . "&shipID=".urlencode($this->shiptoid);
        }
		
		/**
		 * Generates URL to the contact page
		 * @return string
		 */
        public function generate_contacturl() {
            $url = new \Purl\Url(Processwire\wire('config')->pages->contact);
            $url->query->set('custID', $this->custid);
            
            if ($this->has_shipto()) {
                $url->query->set('shipID', $this->shiptoid);
            } 
            $url->query->set('id', $this->contact);
            return $url->getUrl();
        }
		
		/** 
		 * Generates the load customer URL to get to the CI PAGE
		 * @return string
		 */
	    public function generate_ciloadurl() {
            $url = $this->generate_redirurl();
            $url->query->set('action', 'ci-customer');
            $url->query->set('custID', $this->custid);
            
			if ($this->has_shipto()) {
                $url->query->set('shipID', $this->shiptoid);
            }
            return $url->getUrl();
		}
        
		/**
		 * URL to redirect page to set the customer for the cart, 
		 * redirects to the cart
		 * @return string
		 */
        public function generate_setcartcustomerurl() {
            $url = $this->generate_redirurl();
            $url->query->set('action', 'shop-as-customer');
            $url->query->set('custID', $this->custid);
            
			if ($this->has_shipto()) {
                $url->query->set('shipID', $this->shiptoid);
            }
            return $url->getUrl();
        }
        
		/**
		 * URL to the customer redirect page, will be used by other functions to extend on
		 * @return [type] [description]
		 */
        public function generate_redirurl() {
            return new \Purl\Url(Processwire\wire('config')->pages->customer."redir/");
        }
        
        /**
         * Outputs the javascript function name with parameter
         * @param string $function which II function
         * @return string          Function name with parameter for the call
         */
        function generate_iifunction($function) {
            switch ($function) {
                case 'ii':
                    return "ii_customer('".$this->custid."')";
                    break;
                case 'ii-pricing':
                    return "chooseiipricingcust('".$this->custid."', '')";
                    break;
                case 'ii-item-hist':
                    return "chooseiihistorycust('".$this->custid."', '')";
                    break;
            }
        }
		
		/**
		 * Returns Phone with extension 
		 * or without it depending if it has one
		 * @return string
		 */
		public function generate_phonedisplay() {
			if ($this->has_extension()) {
				return $this->cphone . ' &nbsp; ' . $this->cphext;
			} else {
				return $this->cphone;
			}
		}
		
		/**
		 * Takes the method type and makes a proper URL depending on the method
		 * @param  string $method two main groups : phone / email
		 * @return string         url with with the protocol defined
		 */
		public function generate_contactmethodurl($method) {
			switch ($method) {
				case 'cell':
					return "tel:".$this->ccellphone;
					break;
				case 'phone':
					return "tel:".$this->cphone;
					break;
				case 'email':
					return "mailto:".$this->email;
					break;
				default:
					return "tel:".$this->cphone;
					break;
			}
		}
		
		/**
		 * Generates a one line address string
		 * @return string
		 */
		public function generate_address() {
			return $this->addr1 . ' ' . $this->addr2. ' ' . $this->ccity . ', ' . $this->cst . ' ' . $this->czip;
		}
        
        /* =============================================================
			OTHER CONSTRUCTOR FUNCTIONS 
            Inherits some from CreateFromObjectArrayTraits
		============================================================ */
		/**
		 * Loads an object with this class using the parameters as provided
		 * @param  string $custID    CustomerID
		 * @param  string $shiptoID  Shipto ID (can be blank)
		 * @param  string $contactID Contact ID (can be blank)
		 * @return Contact
		 */
        public static function load($custID, $shiptoID = '', $contactID = '') {
            return get_customercontact($custID, $shiptoID, $contactID);
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
		 * @return array]        with certain keys removed
		 */
 		public static function remove_nondbkeys($array) {
			unset($array['fieldaliases']);
 			return $array;
 		}
    }
