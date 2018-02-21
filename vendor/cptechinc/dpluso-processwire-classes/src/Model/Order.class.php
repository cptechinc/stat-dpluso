<?php 
    class Order {
        protected $sessionid;
		protected $recno;
		protected $date;
		protected $time;
        protected $custid;
        protected $shiptoid;
        protected $quotdate;
        protected $billname;
        protected $billaddress;
        protected $billaddress2;
        protected $billaddress3;
        protected $billcountry;
        protected $billcity;
        protected $billstate;
        protected $billzip;
        protected $shipname;
        protected $shipaddress;
        protected $shipaddress2;
        protected $shipaddress3;
        protected $shipcountry;
        protected $shipcity;
        protected $shipstate;
        protected $shipzip;
        protected $contact; 
        protected $sp1;
		protected $sp1name;
		protected $sp2;
		protected $sp2name;
		protected $sp2disp;
		protected $sp3;
		protected $sp3name;
		protected $sp3disp;
        protected $hasnote;
        protected $shipviacd;
		protected $shipviadesc;
        protected $custpo;
        protected $custref;
        protected $status;
        protected $phone;
        protected $faxnbr;
        protected $email;
        protected $subtotal;
        protected $salestax;
        protected $freight;
        protected $misccost;
        protected $ordertotal;
        protected $whse;
        protected $taxcode;
		protected $taxcodedesc;
        protected $termcode;
        protected $termcodedesc; 
        protected $pricecode;
        protected $pricecodedesc;
        protected $error;
		protected $errormsg;
        
        /* =============================================================
           GETTER FUNCTIONS
       ============================================================ */
       public function __get($property) {
           $method = "get_{$property}";
           if (method_exists($this, $method)) {
               return $this->$method();
           } elseif (property_exists($this, $property)) {
               return $this->$property;
           } else {
               $this->error("This property ($property) does not exist");
               return false;
           }
       }
      
        public function has_notes() {
			return $this->hasnote == 'Y' ? true : false;
		}
        
        public function has_error() {
            return $this->error == 'Y' ? true : false;
        }
        
        /* =============================================================
           SETTER FUNCTIONS
       ============================================================ */
        public function set($property, $value) {
            if (property_exists($this, $property) !== true) {
                $this->error("This property ($property) does not exist ");
                return false;
            }
            $this->$property = $value;
        }
        
    }
