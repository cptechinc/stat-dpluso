<?php 
    class PricingItem {
        use CreateFromObjectArrayTraits;
        use CreateClassArrayTraits;
        use ThrowErrorTrait;
        
		protected $sessionid;
		protected $recno;
		protected $date;
		protected $time;
		protected $itemid;
		protected $price;
		protected $qty;
		protected $priceqty1;
		protected $priceqty2;
		protected $priceqty3;
		protected $priceqty4;
		protected $priceqty5;
		protected $priceqty6;
		protected $priceprice1;
		protected $priceprice2;
		protected $priceprice3;
		protected $priceprice4;
		protected $priceprice5;
		protected $priceprice6;
		protected $unit;
		protected $listprice;
		protected $name1;
		protected $name2;
		protected $shortdesc;
		protected $image;
		protected $familyid;
		protected $ermes;
		protected $speca;
		protected $specb;
		protected $specc;
		protected $specd;
		protected $spece;
		protected $specf;
		protected $specg;
		protected $spech;
		protected $longdesc;
		protected $orderno;
		protected $name3;
		protected $name4;
		protected $thumb;
		protected $width;
		protected $height;
		protected $familydes;
		protected $keywords;
		protected $vpn;
		protected $uomdesc;
		protected $vidinffg;
		protected $vidinflk;
		protected $additemflag;
		protected $schemafam;
		protected $origitemid;
		protected $techspecflg;
		protected $techspecname;
		protected $cost;
		protected $prop65;
		protected $leadfree;
		protected $extendesc;
		protected $minprice;
		protected $spcord;
		protected $vendorid;
		protected $vendoritemid;
		protected $shipfromid;
		protected $nsitemgroup;
		protected $itemtype;
        protected $fieldaliases = array(
            'itemID' => 'itemid',
            'shipfromID' => 'shipfromid',
            'vendorID' => 'vendorid'
        );
        protected $historyfields = array('lastsold', 'lastqty', 'lastprice');
        
        /* =============================================================
 		   CONSTRUCTOR FUNCTIONS 
 	   ============================================================ */
        public function __construct() {
            $this->image = (file_exists(Processwire\wire('config')->imagefiledirectory.$this->image)) ? $this->image : 'notavailable.png';
        }
        
        /* =============================================================
 		   GETTER FUNCTIONS 
 	   ============================================================ */
       /**
        * Returns properties that may not be publically accessible by calling a function that could exist or by
        * directly returning the property
        * @param  string $property The property trying to be accessed
        * @return string           returns the property or the value returned by the method call
        */
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
         * Checks if there's sales history for the Pricing Item from the database
         * @param  boolean $debug if true it will return the SQL statement used, 
         * if not it will return the result from the query execution
         */
        public function has_saleshistory($debug = false) {
            return count_itemhistory($this->sessionid, $this->itemid, $debug);
        }
        
        /* =============================================================
 		   CLASS FUNCTIONS 
 	   ============================================================ */
       /**
        * Returns an array of item availability records
        * @param  boolean $debug if true it will return the SQL statement used, 
        * if not it will return the result from the query execution
        */
        public function get_availability($debug = false) {
            return get_itemavailability($this->sessionid, $this->itemid, $debug);
        }
        
        /**
         * Returns the customer history $field value
         * @param  string  $field [description]
         * @param  boolean $debug if true it will return the SQL statement used, 
         * if not it will return the field value from the query execution
         */
        public function history($field, $debug = false) {
            if (in_array($field, $this->historyfields)) {
                return get_itemhistoryfield($this->sessionid, $this->itemid, $field, $debug);
            }
        }
        
        /* =============================================================
 		   GENERATE ARRAY FUNCTIONS 
           The following are defined CreateClassArrayTraits
           public static function generate_classarray()
           public function _toArray()
 	   ============================================================ */
 		public static function remove_nondbkeys($array) {
			unset($array['fieldaliases']);
			unset($array['historyfields']);
 			return $array;
 		}
    }
