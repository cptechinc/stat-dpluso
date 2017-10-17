<?php 
    class Contact {
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

        public $hasshipto = false;
		public $hasphoneextension = false;

        public function __construct() {
            if ($this->shiptoid != '') { $this->hasshipto = true; }
			if ($this->cphext != '') { $this->hasphoneextension = true; }

        }
        
        /**
         * Returns if Contact has a shiptoid
         * @return boolean [description]
         */
        protected function has_shipto() {
            return ($this->shiptoid != '') ? true : false;
        }
        
        /**
         * Returns if contact has phone extension 
         * @return boolean [description]
         */
        protected function has_extension() {
            return ($this->cphext != '') ? true : false;
        }

        public function generatecustomerurl() {
            return wire('config')->pages->customer."redir/?action=load-customer&custID=".urlencode($this->custid);
        }

        public function generateshiptourl() {
            return $this->generatecustomerurl() . "&shipID=".urlencode($this->shiptoid);
        }

		public function generatecustloadurl() {
			if ($this->has_shipto()) {
				return $this->generateshiptourl();
			} else {
				return $this->generatecustomerurl();
			}
		}

        public function generatecontacturl() {
            if ($this->has_shipto()) {
                return wire('config')->pages->customer.urlencode($this->custid) . "/shipto-".urlencode($this->shiptoid)."/contacts/?id=".urlencode($this->contact);
            } else {
                return wire('config')->pages->customer.urlencode($this->custid)."/contacts/?id=".urlencode($this->contact);
            }
        }

		function generateciloadurl() {
			 if ($this->hasshipto) {
                return wire('config')->pages->customer."redir/?action=ci-customer&custID=".urlencode($this->custid)."&shipID=".urlencode($this->shiptoid);
            } else {
                return wire('config')->pages->customer."redir/?action=ci-customer&custID=".urlencode($this->custid);
            }
		}

		function generateciurl() {
			 if ($this->has_shipto()) {
                return wire('config')->pages->custinfo.urlencode($this->custid) . "/shipto-".urlencode($this->shiptoid)."/";
            } else {
                return wire('config')->pages->custinfo.urlencode($this->custid)."/";
            }
		}
        
        /**
         * Outputs the javascript function name with parameter
         * @param String $function which II function
         * @return String          Function name with parameter for the call
         */
        function generateiifunction($function) {
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

		public function generatephonedisplay() {
			if ($this->hasphoneextension) {
				return $this->cphone . ' &nbsp; ' . $this->cphext;
			} else {
				return $this->cphone;
			}
		}

		public function generatecontactmethodurl($method) {
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

		function generateaddress() {
			return $this->addr1 . ' ' . $this->addr2. ' ' . $this->ccity . ', ' . $this->cst . ' ' . $this->czip;
		}

    }


 ?>
