<?php
	/**
	 * Dplus Qnotes are for Notes for Quotes and Orders and the functions
	 * needed to update the notes on them
	 */
	class QNote {
		use ThrowErrorTrait;
		use MagicMethodTraits;
		use CreateFromObjectArrayTraits;
		use CreateClassArrayTraits;
		
		protected $sessionid;
		protected $recno;
		protected $date;
		protected $time;
		/**
		 * Record Type
		 * SORD = Sales Order | QUOT Quote | CART Cart
		 * @var string
		 */
		protected $rectype; // SORD|QUOT|CART
		
		/**
		 * Key 1
		 * Sales Order # | Quote # | Session ID
		 * @var string
		 */
		protected $key1;
		
		/**
		 * Key 2 is Line #
		 * @var int
		 */
		protected $key2;
		
		/**
		 * Not Yet Used
		 * @var string
		 */
		protected $key3;
		
		/**
		 * Not Yet Used
		 * @var string
		 */
		protected $key4;
		/**
		 * Not Yet Used
		 * @var string
		 */
		protected $key5;
		
		/**
		 * FORM 1 
		 * QUOTE = Quote
		 * CART = Quote
		 * SALES ORDER = Pick Ticket
		 * @var string Y | N
		 */
		protected $form1;
		
		/**
		 * FORM 2 
		 * QUOTE = Pick Ticket
		 * CART = Pick Ticket
		 * SALES ORDER = Pack Ticket
		 * @var string Y | N
		 */
		protected $form2;
		
		/**
		 * FORM 3 
		 * QUOTE = Pack Ticket
		 * CART = Pack Ticket
		 * SALES ORDER = Invoice
		 * @var string Y | N
		 */
		protected $form3;
		
		/**
		 * FORM 3 
		 * QUOTE = Invoice
		 * CART = Invoice
		 * SALES ORDER = Acknowledgement
		 * @var string Y | N
		 */
		protected $form4;
		
		/**
		 * FORM 3 
		 * QUOTE = Acknowledgement
		 * CART = Acknowledgement
		 * SALES ORDER = NOT USED
		 * @var string Y | N
		 */
		protected $form5;
		
		/**
		 * Not Used
		 * @var string Y | N
		 */
		protected $form6;
		/**
		 * Not Used
		 * @var string Y | N
		 */
		protected $form7;
		/**
		 * Not Used
		 * @var string Y | N
		 */
		protected $form8;
		
		/**
		 * Character Width for Textarea
		 * @var string
		 */
		protected $colwidth = '35';
		
		/**
		 * Note
		 * @var string
		 */
		protected $notefld;
		
		/**
		 * Dummy
		 * @var string X
		 */
		protected $dummy;
		
		/* =============================================================
			SETTER FUNCTIONS
			Inherits from MagicMethodTraits
		============================================================ */
		
		/* =============================================================
			GETTER FUNCTIONS 
			Inherits from MagicMethodTraits
		============================================================ */
		
		/* =============================================================
			CLASS FUNCTIONS 
		============================================================ */
		/**
		 * Returns URL to the JSON data
		 * @return string
		 */
		public function generate_jsonurl() {
			$url = new Purl\Url(wire('config')->pages->ajax."json/dplus-notes/");
			$url->query->setData(array(
				'key1' => $this->key1,
				'key2' => $this->key2,
				'recnbr' => $this->recno,
				'type' => $this->rectype
			));
			return $url->getUrl();
		}
		 
		/* =============================================================
			CRUD FUNCTIONS 
		============================================================ */
		/**
		 * Returns if Qnote note can be written or not depending if Order/Quote
		 * is available to edit, 
		 * @param  string $sessionID Session Identifier
		 * @param  string $type      Type of Qnote CART|SORD|QUOT
		 * @param  string $key1      For CART = SESSIONID, SORD = Order #, QUOT = Quote #
		 * @param  string $key2      Line #
		 * @return bool            [description]
		 */
		public static function can_write($sessionID, $type, $key1, $key2) {
			switch ($type) {
				case 'CART' :
					return true;
					break;
				case 'SORD':
					$order = SalesOrder::load($sessionID, $key1);
					return $order->can_edit();
					break;
				case 'QUOT':
					$quote = Quote::load($sessionID, $key1);
					return $quote->can_edit();
					break;
				default: 
					return false;
					break;
			}
		}
		
		/**
		 * Returns the Qnote Type for the Ordertype
		 * @param  string $type cart|sales-orders|quotes
		 * @return string       CART|SORD|QUOT
		 */
		public static function get_qnotetype($type) {
			return DplusWire::wire('pages')->get("/config/$type/qnotes/")->qnote_type;
		}
		
		/**
		 * Gets the Default Value from the config for each order type
		 * @param  string $type      cart|sales-orders|quotes
		 * @param  string $formfield quote|pickticket|packticket|invoice|acknowledgement
		 * @return bool            defaulf value
		 */
		public static function get_qnotedefaultvalue($type, $formfield) {
			$qnotetypeconfig = DplusWire::wire('pages')->get("/config/$type/qnotes/");
			$field = 'qnote_'.$formfield;
			return $qnotetypeconfig->$field;
		}
		
		/**
		 * Returns if the string to show if checkbox should be checked
		 * @param  string $type      cart|sales-orders|quotes
		 * @param  string $formfield quote|pickticket|packticket|invoice|acknowledgement
		 * @return string            checked or (blank)
		 */
		public static function generate_showchecked($type, $formfield) {
			return self::get_qnotedefaultvalue($type, $formfield) ? 'checked' : '';
		}
		
		/* =============================================================
			CRUD FUNCTIONS
		============================================================ */
		/**
		 * Create new Qnote record in the mysql table
		 * @param bool $debug
		 */
		public function create($debug = false) {
			return add_qnote($this->sessionid, $this, $debug);
		}
		
		/**
		 * Returns a Qnote object from the Database
		 * @param  string  $sessionID Session Identifier
		 * @param  string  $key1      For CART = SESSIONID, SORD = Order #, QUOT = Quote #
		 * @param  string  $key2      Line #
		 * @param  string  $rectype   CART|SORD|QUOT
		 * @param  int  $recnbr
		 * @param  bool $debug     Whether to return object or SQL QUERY FOR IT
		 * @return Qnote
		 */
		public static function load($sessionID, $key1, $key2, $rectype, $recnbr, $debug = false) {
			return get_qnote($sessionID, $key1, $key2, $rectype, $recnbr, true, $debug); 
		}
		
		/**
		 * Saves Changes to the database for this object
		 * @param  bool $debug whether or not to actually run it or display debug info
		 * @return string         SQL QUERY used in update
		 */
		public function update($debug = false) {
			return update_note($this->sessionid, $this, $debug);
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
			return $array;
		}
	}
