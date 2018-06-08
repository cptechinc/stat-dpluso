<?php
	/**
	 * Class for Items that reside in the itemsearch table
	 */
    class XRefItem {
        use CreateFromObjectArrayTraits;
		use CreateClassArrayTraits;
		use ThrowErrorTrait;

		/**
		 * Part or Item (ID or #)
		 * @var string
		 */
        protected $itemid;

		/**
		 * Where itemid originates
		 * (V)endor | (Item) | (C)ustomer
		 * @var string
		 */
        protected $origintype;

		/**
		 * he ItemID / Part # used by Vendor or Customer
		 * @var string
		 */
        protected $refitemid;

		/**
		 * Item Description
		 * @var string
		 */
        protected $desc1;

		/**
		 * Secondary Item Description
		 * @var string
		 */
        protected $desc2;

		/**
		 * Image filename
		 * @var string
		 */
        protected $image;

        /**
         * How many in a case
         * @var int
         */
        protected $qty_percase;

		/**
		 * Date updted In Database
		 * @var int
		 */
        protected $create_date;

		/**
		 * Time updated in Database
		 * @var int
		 */
        protected $create_time;

		/**
		 * If Item is Active
		 * @var string
		 * (A)ctive | (D)elete when empty | (I)nactive
		 */
        protected $activestatus;

		/**
		 * Aliases that properties might use or have
		 * so the __get function can lookup and find
		 * @var array
		 */
        public $fieldaliases = array(
            'itemID' => 'itemid',
        );

		/* ============================================================
			GETTER FUNCTIONS
		============================================================ */
		/**
		 * Looks and returns property value, also looks through
		 * $this->aliases
		 * @param  string $property Property name to get value from
		 * @return mixed           Value of Property
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
         * Returns true if this item is dealt in case qty
         * If the case qty is 1, then we only deal with the item as Eaches
         * @return bool if this item is dealt in case qty
         */
        public function has_caseqty() {
            return ($this->qty_percase != 1) ? true : false;
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

		/**
		 * Returns URL to Load the Item Information page for this item
		 * @param  mixed $custID Provide Customer ID if pricing and other things need to be for particular customer
		 * @return string         II Load URL
		 */
        public function generate_iiselecturl($custID = false) {
            $url = new \Purl\Url(DplusWire::wire('config')->pages->products."redir/?action=ii-select");
            if (!empty($custID)) $url->query->set('custID', $custID);
            $url->query->set('itemID', $this->itemid);
            return $url->getUrl();
        }

		/**
		 * Returns the string for javascript function for this particular item for CI
		 * @param  string $action Action to to run
		 * @return string         Javascript function with this itemid parameterized
		 */
        public function generate_cionclick($action) {
            switch($action) {
                case 'ci-pricing':
                    $onclick = "choosecipricingitem('$this->itemid')";
                    break;
                case 'ci-sales-history':
                    $onclick = "choosecisaleshistoryitem('$this->itemid')";
                    break;
                default:
                    $onclick = "choosecipricingitem('$this->itemid')";
                    break;
            }
            return $onclick;
        }

		/**
		 * Returns the string for javascript function for this particular item for VI
		 * @param  string $action Action to to run
		 * @return string         Javascript function with this itemid parameterized
		 */
		public function generate_vionclick($action) {
			switch($action) {
				case 'vi-costing':
					$onclick = "choosevicostingitem('".$this->itemid."')";
					break;
                default:
                    $onclick = "choosevicostingitem('".$this->itemid."')";
                    break;
            }
            return $onclick;
		}


        /* ============================================================
			GENERATE ARRAY FUNCTIONS
			The following are defined CreateClassArrayTraits
			public static function generate_classarray()
			public function _toArray()
		============================================================ */
 		public static function remove_nondbkeys($array) {
			unset($array['fieldaliases']);
 			return $array;
 		}

		/* ============================================================
			CRUD FUNCTIONS
		============================================================ */
		/**
		 * Returns an object with XrefItem Class after
		 * inputing the crossreferences as needed
		 * @param  string  $itemID   Item ID / Part #
		 * @param  mixed $custID   Customer ID to use Cross-reference or false
		 * @param  mixed $vendorID Vendor ID to use Cross-reference or false
		 * @param  bool $debug    Whether XrefItem object is returned or the SQL for retreiving it
		 * @return XrefItem            Or SQL Query for it
		 */
		public static function load($itemID, $custID = false, $vendorID = false, $debug = false) {
			return get_xrefitem($itemID, $custID, $vendorID, $debug);
		}
    }
