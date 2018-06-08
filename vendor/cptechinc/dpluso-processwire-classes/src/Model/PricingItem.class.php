<?php
	/**
	 * Class for Items from the Pricing table
	 */
	class PricingItem {
		use ThrowErrorTrait;
		use MagicMethodTraits;
		use CreateFromObjectArrayTraits;
		use CreateClassArrayTraits;

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
		protected $supercedes;
		/**
		 * If Item is Active
		 * @var string
		 * (A)ctive | (D)elete when empty | (I)nactive
		 */
        protected $activestatus;
		protected $fieldaliases = array(
			'itemID' => 'itemid',
			'shipfromID' => 'shipfromid',
			'vendorID' => 'vendorid'
		);
		protected $historyfields = array('lastsold', 'lastqty', 'lastprice');

		/* =============================================================
			CONSTRUCTOR FUNCTIONS
		============================================================ */
		/**
		 * When item is loaded,
		 */
		public function __construct() {
			$this->image = (file_exists(DplusWire::wire('config')->imagefiledirectory.$this->image)) ? $this->image : 'notavailable.png';
		}

		/* =============================================================
			GETTER FUNCTIONS
			Some are Handled by MagicMethodTraits
		============================================================ */
		/**
		 * Checks if there's sales history for the Pricing Item from the database
		 * @param  bool $debug if true it will return the SQL statement used,
		 * if not it will return the result from the query execution
		 */
		public function has_saleshistory($debug = false) {
			return count_itemhistory($this->sessionid, $this->itemid, $debug);
		}

		/**
		* Returns an array of item availability records
		* @param  bool $debug if true it will return the SQL statement used,
		* if not it will return the result from the query execution
		*/
		public function get_availability($debug = false) {
			return get_itemavailability($this->sessionid, $this->itemid, $debug);
		}

		/**
		 * Returns the customer history $field value
		 * @param  string  $field [description]
		 * @param  bool $debug if true it will return the SQL statement used,
		 * if not it will return the field value from the query execution
		 */
		public function history($field, $debug = false) {
			if (in_array($field, $this->historyfields)) {
				return get_itemhistoryfield($this->sessionid, $this->itemid, $field, $debug);
			}
		}

		/**
		 * Checks if Item image exists if not use the image not found
		 * @return string path/to/image
		 */
        public function generate_imagesrc() {
            if (file_exists(DplusWire::wire('config')->imagefiledirectory.$this->image)) {
                return DplusWire::wire('config')->imagedirectory.$this->image;
            } else {
                return DplusWire::wire('config')->imagedirectory.DplusWire::wire('config')->imagenotfound;
            }
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
			unset($array['fieldaliases']);
			unset($array['historyfields']);
			return $array;
		}

		/* =============================================================
			CRUD FUNCTIONS
		============================================================ */
		/**
		 * Returns PricingItem from pricing table
		 * @param  string $sessionID session ID
		 * @param  string $itemID    Item / Part (ID/#)
		 * @param  bool   $debug     Whether PricingItem is returned or SQL QUERY
		 * @return PricingItem       or SQL QUERY
		 */
		public static function load($sessionID, $itemID, $debug = false) {
			return get_pricingitem($sessionID, $itemID);
		}
	}
