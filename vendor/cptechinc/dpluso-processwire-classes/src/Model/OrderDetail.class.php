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
		
		public function __isset($property){
		    return isset($this->$property);
		} 
		
		public function is_kititem() {
			return $this->kititemflag == 'Y' ? true : false;
		}
		
		public function has_notes() {
			return $this->hasnotes == 'Y' ? true : false;
		}
		
		/* =============================================================
			SETTER FUNCTIONS
		============================================================ */
		public function __set($property, $value) {
			if (property_exists($this, $property) !== true) {
				$this->error("This property ($property) does not exist ");
				return false;
			}
			$this->$property = $value;
		}
	}
