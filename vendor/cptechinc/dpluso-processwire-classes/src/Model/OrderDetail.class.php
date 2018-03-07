<?php 
	class OrderDetail {
		use ThrowErrorTrait;
		
		protected $sessionid;
		protected $recno;
		protected $date;
		protected $time;
		protected $status;
		protected $linenbr;
		protected $sublinenbr;
		protected $itemid;
		protected $custid;
		protected $custitemid;
		protected $desc1;
		protected $desc2;
		protected $vendorid;
		protected $vendoritemid;
		protected $totalprice;
		protected $ordrtotalcost;
		protected $hasnotes;
		protected $whse;
		protected $errormsg;
		protected $nsitemgroup;
		protected $shipfromid;
		protected $itemtype;
		protected $minprice;
		protected $spcord;
		protected $kititemflag;
		protected $uom;
		protected $lostreason;
		protected $lostdate;
		protected $listprice;
		protected $discpct;
		protected $taxcode;
		protected $stancost;
		protected $rshipdate;
		protected $dummy;
		
		/* =============================================================
			GETTER FUNCTIONS 
		============================================================ */
		/**
		 * Properties are protected from modification without function, but
		 * We want to allow the property values to be accessed
		 * @param  string $property Property Name
		 * @return mixed          Property or Error
		 */
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
		
		/**
		 * Is used to PHP functions like isset() and empty() get access and see
		 * if variable is set
		 * @param  string  $property Property Name
		 * @return bool           Whether Property is set
		 */
		public function __isset($property){
		    return isset($this->$property);
		} 
		
		/**
		 * Checks if Detail is a kit by checking if the flag is 'Y'
		 * @return bool Whether or not kit item is Y
		 */
		public function is_kititem() {
			return $this->kititemflag == 'Y' ? true : false;
		}
		
		/**
		 * Checks if Detail has notes by looking at the notes flag
		 * @return bool Whether or not $this->hasnotes is Y
		 */
		public function has_notes() {
			return $this->hasnotes == 'Y' ? true : false;
		}
		
		/* =============================================================
			SETTER FUNCTIONS
		============================================================ */
		/**
		 * We don't want to allow direct modification of properties so we have this function
		 * look for if property exists then if it does it will set the value for the property
		 * @param string $property Property Name
		 * @param mixed $value    Value for Property
		 */
		public function set($property, $value) {
			if (property_exists($this, $property) !== true) {
				$this->error("This property ($property) does not exist ");
				return false;
			}
			$this->$property = $value;
		}
	}
