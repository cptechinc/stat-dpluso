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
        
        public function get_customername() {
            return (!empty($this->name)) ? $this->name : $this->custid;
        }
        
        /**
         * Returns if Contact has a shiptoid
         * @return boolean [description]
         */
        public function has_shipto() {
            return (!empty($this->shiptoid));
        }
        
        /**
         * Returns if contact has phone extension 
         * @return boolean [description]
         */
        protected function has_extension() {
            return ($this->cphext != '') ? true : false;
        }
        
        /* =============================================================
			CLASS FUNCTIONS 
		============================================================ */
        public function generate_customerurl() {
            return $this->generate_ciloadurl();
        }

        public function generate_shiptourl() {
            return $this->generate_customerurl() . "&shipID=".urlencode($this->shiptoid);
        }

		public function generatecustloadurl() {
			if ($this->has_shipto()) {
				return $this->generate_shiptourl();
			} else {
				return $this->generate_customerurl();
			}
		}

        public function generate_contacturl() {
            $url = new \Purl\Url(Processwire\wire('config')->pages->contact);
            $url->query->set('custID', $this->custid);
            
            if ($this->has_shipto()) {
                $url->query->set('shipID', $this->shiptoid);
            } 
            $url->query->set('id', $this->contact);
            return $url->getUrl();
        }

	    public function generate_ciloadurl() {
            $url = $this->generate_redirurl();
            $url->query->set('action', 'ci-customer');
            $url->query->set('custID', $this->custid);
            
			if ($this->has_shipto()) {
                $url->query->set('shipID', $this->shiptoid);
            }
            return $url->getUrl();
		}
        
        public function generate_setcartcustomerurl() {
            $url = $this->generate_redirurl();
            $url->query->set('action', 'shop-as-customer');
            $url->query->set('custID', $this->custid);
            
			if ($this->has_shipto()) {
                $url->query->set('shipID', $this->shiptoid);
            }
            return $url->getUrl();
        }
        
        public function generate_redirurl() {
            return new \Purl\Url(Processwire\wire('config')->pages->customer."redir/");
        }
        
        /**
         * Outputs the javascript function name with parameter
         * @param String $function which II function
         * @return String          Function name with parameter for the call
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

		public function generate_phonedisplay() {
			if ($this->has_extension()) {
				return $this->cphone . ' &nbsp; ' . $this->cphext;
			} else {
				return $this->cphone;
			}
		}

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

		public function generate_address() {
			return $this->addr1 . ' ' . $this->addr2. ' ' . $this->ccity . ', ' . $this->cst . ' ' . $this->czip;
		}
        
        /* =============================================================
			OTHER CONSTRUCTOR FUNCTIONS 
            Inherits some from CreateFromObjectArrayTraits
		============================================================ */
        public static function load($custID, $shiptoID = '', $contactID = '') {
            return get_customercontact($custID, $shiptoID, $contactID);
        }
        
        /* =============================================================
			GENERATE ARRAY FUNCTIONS 
			The following are defined CreateClassArrayTraits
			public static function generate_classarray()
			public function _toArray()
		============================================================ */
 		public static function remove_nondbkeys($array) {
			unset($array['fieldaliases']);
 			return $array;
 		}
    }
