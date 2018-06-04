<?php
<<<<<<< HEAD
    abstract class TableScreenMaker {
        protected $sessionID;
        protected $userID;
=======
	abstract class TableScreenMaker {
		use ThrowErrorTrait;

		/**
		 * Session ID
		 * Used for getting the Dplus Generated File
		 * @var string
		 */
		protected $sessionID;

		/**
		 * Used to grab user Permissions
		 * @var string
		 */
		protected $userID;

		/**
		 * Run in Debug
		 * @var bool
		 */
>>>>>>> 963151d4... Merge pull request #171 from cptechinc/new-detail
		protected $debug = false;

		/**
		 * format for print page?
		 * @var bool
		 */
		protected $forprint = false;

		/**
		 * Table Type
		 * @var string normal | grid
		 */
		protected $tabletype = 'normal';

		/**
		 * Type of Screen, corresponds with the function Ran to create screen
		 * @var string
		 */
		protected $type = '';
<<<<<<< HEAD
		protected $title = '';
		protected $datafilename = '';
		protected $fullfilepath = false;
		protected $testprefix = '';
		protected $json = false; // WILL BE JSON DECODED ARRAY
		protected $fields = false; // WILL BE JSON DECODED ARRAY
		protected $datasections = array();

        public static $filedir = false;
		public static $testfiledir = false;
		public static $fieldfiledir = false;

        protected static $trackingcolumns = array('Tracking Number');
        protected static $phonecolumns = array('phone', 'fax');
        /* =============================================================
           CONSTRUCTOR AND SETTER FUNCTIONS
       ============================================================ */
=======

		/**
		 * Title of Screen
		 * @var string
		 */
		protected $title = '';

		/**
		 * File Name
		 * @var string
		 */
		protected $datafilename = '';

		/**
		 * Directory where files are
		 * @var string path/to/file
		 */
		protected $fullfilepath = false;

		/**
		 * Used for Test Files
		 * @var string
		 */
		protected $testprefix = '';

		/**
		 * JSON
		 * @var array
		 */
		protected $json = false; // WILL BE JSON DECODED ARRAY

		/**
		 * Array of the fields that will be used by the JSON array
		 * @var array
		 */
		protected $fields = false; // WILL BE JSON DECODED ARRAY

		/**
		 * Key Value array of Sections that exist I.E. header => Header, detail => Detail
		 * @var string
		 */
		protected $datasections = array();

		/**
		 * File Directory
		 * @var string /path/to/dir
		 */
		public static $filedir = false;
		/**
		 * File Directory for Test Files
		 * @var string /path/to/dir
		 */
		public static $testfiledir = false;

		/**
		 * File Directory for field Definitions
		 * @var string /path/to/dir
		 */
		public static $fieldfiledir = false;

		/**
		 * Columns Aliases that signifiy Tracking Number
		 * Used for generating Cell Data
		 * @var array
		 */
		protected static $trackingcolumns = array('Tracking Number');

		/**
		 * Columns Aliases that signifiy Tracking Number
		 * Used for generating Cell Data
		 * @var array
		 */
		protected static $phonecolumns = array('phone', 'fax');

		/* =============================================================
			CONSTRUCTOR AND SETTER FUNCTIONS
		============================================================ */
		/**
		 * Constructor
		 * @param string $sessionID Session ID
		 */
>>>>>>> 963151d4... Merge pull request #171 from cptechinc/new-detail
		public function __construct($sessionID) {
            $this->sessionID = $sessionID;
			$this->userID = Processwire\wire('user')->loginid;
			$this->load_filepath();
		}

<<<<<<< HEAD
=======
		/**
		 * Turn debug on or Off
		 * @param bool $debug
		 */
>>>>>>> 963151d4... Merge pull request #171 from cptechinc/new-detail
		public function set_debug($debug) {
			$this->debug = $debug;
			$this->load_filepath();
		}
<<<<<<< HEAD
<<<<<<< HEAD

        /* =============================================================
          GETTER FUNCTIONS
       ============================================================ */
=======
=======
>>>>>>> 963151d4... Merge pull request #171 from cptechinc/new-detail

		/**
		 * Turn debug on or Off
		 * @param bool $debug
		 */
		public function set_printpage($forprint = false) {
			$this->forprint = $forprint;
		}

		/* =============================================================
			GETTER FUNCTIONS
		============================================================ */
		/**
		 * Looks and returns property value, also looks through
		 * @param  string $property Property name to get value from
		 * @return mixed           Value of Property
		 */
>>>>>>> 87a916f0... Merge pull request #164 from cptechinc/test-fixes
		public function __get($property) {
			if (property_exists($this, $property) !== true) {
				$this->error("This property ($property) does not exist");
				return false;
			}
			$method = "get_{$property}";
			if (method_exists($this, $method)) {
				return $this->$method();
			} else {
				return $this->$property;
			}
		}

		public function get_fields() {
			if (!$this->fields) {
<<<<<<< HEAD
                $this->load_fields();
            }
            return $this->fields;
		}

        /* =============================================================
          PUBLIC FUNCTIONS
       	============================================================ */
=======
				$this->load_fields();
			}
			return $this->fields;
		}

		/* =============================================================
			CLASS FUNCTIONS
		============================================================ */
		/**
		 * Sets the file path
		 * File path depends on if debug is true
		 */
>>>>>>> 963151d4... Merge pull request #171 from cptechinc/new-detail
		public function load_filepath() {
			$this->fullfilepath = ($this->debug) ? self::$testfiledir.$this->datafilename.".json" : self::$filedir.$this->sessionID."-".$this->datafilename.".json";
		}

<<<<<<< HEAD
=======
		/**
		 * Converts json into array, then if errors, then we make an array for Errors
		 * @return array
		 */
>>>>>>> 963151d4... Merge pull request #171 from cptechinc/new-detail
		public function process_json() {
			$this->load_filepath();
			$json = json_decode(file_get_contents($this->fullfilepath), true);
			$this->json = (!empty($json)) ? $json : array('error' => true, 'errormsg' => "The $this->title JSON contains errors. JSON ERROR: ".json_last_error());
		}

<<<<<<< HEAD
        public function get_tableblueprint() {
            if (!$this->tableblueprint) {
                $this->generate_tableblueprint();
            }
            return $this->tableblueprint;
        }

        public function generate_screen() {
            return '';
        }

        public function process_andgeneratescreen($generatejavascript = false) {
            $bootstrap = new Contento();
            if (file_exists($this->fullfilepath)) {
        		// JSON file will be false if an error occurred during file_get_contents or json_decode
        		$this->process_json();

        		if ($this->json['error']) {
        			return $bootstrap->createalert('warning', $this->json['errormsg']);
        		} else {
                    return $generatejavascript ? $this->generate_screen() . $this->generate_javascript() : $this->generate_screen();
        		}
        	} else {
        		return $bootstrap->createalert('warning', 'Information Not Available');
        	}
        }

        public function generate_javascript() {
            return '';
        }

        public function generate_shownotesselect() {
            $bootstrap = new Contento();
            $array = array();
            foreach (Processwire\wire('config')->yesnoarray as $key => $value) {
                $array[$value] = $key;
            }
            return $bootstrap->select('class=form-control input-sm|id=shownotes', $array);
        }

        /* =============================================================
=======
		/**
		 * Returns blueprint and loads it if need be
		 * @return array blueprint array
		 */
		public function get_tableblueprint() {
			if (!$this->tableblueprint) {
				$this->generate_tableblueprint();
			}
			return $this->tableblueprint;
		}

		/**
		 * Returns the screen made
		 * @return string html of screen
		 */
		public function generate_screen() {
			return '';
		}

		/**
		 * Processes JSON then generates the screen
		 * USED BY Preview Screen formatter
		 * @param  bool $generatejavascript whether or not to use javascript
		 * @return string                    HTML for screen
		 */
		public function process_andgeneratescreen($generatejavascript = false) {
			$bootstrap = new Contento();
			if (file_exists($this->fullfilepath)) {
				// JSON file will be false if an error occurred during file_get_contents or json_decode
				$this->process_json();

				if ($this->json['error']) {
					return $bootstrap->createalert('warning', $this->json['errormsg']);
				} else {
					return $generatejavascript ? $this->generate_screen() . $this->generate_javascript() : $this->generate_screen();
				}
			} else {
				return $bootstrap->createalert('warning', 'Information Not Available');
			}
		}

		/**
		 * Returns the javascript for this screen
		 * @return string Javascript
		 */
		public function generate_javascript() {
			return '';
		}

		/**
		 * Returns the show notes input
		 * @return string  HTML select
		 */
		public function generate_shownotesselect() {
			$bootstrap = new Contento();
			$array = array();
			foreach (DplusWire::wire('config')->yesnoarray as $key => $value) {
				$array[$value] = $key;
			}
			return $bootstrap->select('class=form-control input-sm|id=shownotes', $array);
		}

		/* =============================================================
>>>>>>> 963151d4... Merge pull request #171 from cptechinc/new-detail
			STATIC FUNCTIONS
		============================================================ */

        /**
		 * Generates the celldata based of the column, column type and the json array it's in, looks at if the data is numeric
		 * @param string $type the type of data D = Date, N = Numeric, string
		 * @param string $parent the array in which the data is contained
		 * @param string $column the key in which we use to look up the value
		 */
		static public function generate_formattedcelldata($type, $parent, $column) {
            $bootstrap = new Contento();
			$celldata = '';
<<<<<<< HEAD
=======
			$qtyregex = "/(quantity)/i";
>>>>>>> 963151d4... Merge pull request #171 from cptechinc/new-detail

			if ($type == 'D') {
                $celldata = (strlen($parent[$column['id']]) > 0) ? date($column['date-format'], strtotime($parent[$column['id']])) : $parent[$column['id']];
			} elseif ($type == 'N') {
				if (is_string($parent[$column['id']])) {
					$celldata = number_format(floatval($parent[$column['id']]), $column['after-decimal']);
				} else {
					$celldata = number_format($parent[$column['id']], $column['after-decimal']);
				}
			} else {
				$celldata = $parent[$column['id']];
			}

<<<<<<< HEAD
            if (in_array($column['id'], self::$trackingcolumns)) {
                $href = self::generate_trackingurl($parent['Service Type'], $parent[$column['id']]);
                return $href ? $bootstrap->a("href=$href|target=_blank", $celldata) : $celldata;
            } elseif(in_array($column['id'], self::$phonecolumns)) {
                $href = self::generate_phoneurl($parent[$column['id']]);
                return $bootstrap->a("href=tel:$href", $celldata);
            } else {
                return $celldata;
            }
		}

        static public function generate_celldata($parent, $column) {
            $bootstrap = new Contento();
            if (in_array($column, self::$trackingcolumns)) {
                $href = self::generate_trackingurl($parent['Service Type'], $parent[$column]);
                return $href ? $bootstrap->a("href=$href|target=_blank", $parent[$column]) : $parent[$column];
            } elseif(in_array($column, self::$phonecolumns)) {
                $href = self::generate_phoneurl($parent[$column]);
                return $bootstrap->a("href=tel:$href", $parent[$column]);
            } else {
                return $parent[$column];
            }
        }

        public static function generate_trackingurl($servicetype, $tracknbr) {
            $href = false;
            if (strpos(strtolower($servicetype), 'fed') !== false) {
                $href = "https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber=$tracknbr&cntry_code=us";
            } elseif (strpos(strtolower($servicetype), 'ups') !== false) {
                $href = "http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=$tracknbr&loc=en_us";
            } elseif (strpos(strtolower($servicetype), 'gro') !== false) {
                $href = "http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=$tracknbr&loc=en_us";
            } elseif (strpos(strtolower($servicetype), 'usps') !== false) {
                $href = "https://tools.usps.com/go/TrackConfirmAction?tLabels=$tracknbr";
            } elseif (strpos(strtolower($servicetype), 'spee') !== false) {
                $href = "http://packages.speedeedelivery.com/index.php?barcodes=$tracknbr";
            }
            return $href;
        }

        static public function generate_phoneurl($phone) {
            return str_replace('-', '', $phone);
        }

        /* =============================================================
=======
			if (in_array($column['id'], self::$trackingcolumns)) {
				$href = self::generate_trackingurl($parent['Service Type'], $parent[$column['id']]);
				return $href ? $bootstrap->a("href=$href|target=_blank", $celldata) : $celldata;
			} elseif (in_array($column['id'], self::$phonecolumns)) {
				$href = self::generate_phoneurl($parent[$column['id']]);
				return $bootstrap->a("href=tel:$href", $celldata);
			} elseif (preg_match($qtyregex , $column['id'])) {
				if (DplusWire::wire('modules')->isInstalled('QtyPerCase')) {
					$qtypercase = DplusWire::wire('modules')->get('QtyPerCase');
					$description = $qtypercase->generate_casebottleqtydesc($parent["Item ID"], $celldata);
					return $bootstrap->span("data-toggle=tooltip|title=$description", $celldata);
				} else {
					return $celldata;
				}
			} else {
				return $celldata;
			}
		}

		/**
		 * Returns the data formatted into appropriate formats
		 * Ex. Tracking Columns will have a tracking link
		 * @param  array $parent contains value
		 * @param  string $column Name of Column
		 * @return string         Value or HTML content
		 */
		public static function generate_celldata($parent, $column) {
			$bootstrap = new Contento();
			if (in_array($column, self::$trackingcolumns)) {
				$href = self::generate_trackingurl($parent['Service Type'], $parent[$column]);
				return $href ? $bootstrap->a("href=$href|target=_blank", $parent[$column]) : $parent[$column];
			} elseif(in_array($column, self::$phonecolumns)) {
				$href = self::generate_phoneurl($parent[$column]);
				return $bootstrap->a("href=tel:$href", $parent[$column]);
			} else {
				return $parent[$column];
			}
		}

		/**
		 * Returns HTML Tracking Link
		 * @param  string $servicetype Ex. Fedex, UPS, USPS
		 * @param  string $tracknbr    Tracking Number
		 * @return string             URL
		 */
		public static function generate_trackingurl($servicetype, $tracknbr) {
			$href = false;
			if (strpos(strtolower($servicetype), 'fed') !== false) {
				$href = "https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber=$tracknbr&cntry_code=us";
			} elseif (strpos(strtolower($servicetype), 'ups') !== false) {
				$href = "http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=$tracknbr&loc=en_us";
			} elseif (strpos(strtolower($servicetype), 'gro') !== false) {
				$href = "http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=$tracknbr&loc=en_us";
			} elseif (strpos(strtolower($servicetype), 'usps') !== false) {
				$href = "https://tools.usps.com/go/TrackConfirmAction?tLabels=$tracknbr";
			} elseif (strpos(strtolower($servicetype), 'spee') !== false) {
				$href = "http://packages.speedeedelivery.com/index.php?barcodes=$tracknbr";
			}
			return $href;
		}

		/**
		 * Returns phone string for URL
		 * @param  string $phone Phone Number
		 * @return string        reformatted phone value
		 */
		public static function generate_phoneurl($phone) {
			return str_replace('-', '', $phone);
		}

		/* =============================================================
>>>>>>> 963151d4... Merge pull request #171 from cptechinc/new-detail
			CLASS FUNCTIONS
		============================================================ */
		protected function error($error, $level = E_USER_ERROR) {
			$error = (strpos($error, 'DPLUSO[SCREEN-FORMATTER]: ') !== 0 ? 'DPLUSO[SCREEN-FORMATTER]: ' . $error : $error);
			trigger_error($error, $level);
			return;
		}

		public static function set_filedirectory($dir) {
			self::$filedir = $dir;
		}

<<<<<<< HEAD
=======
		/**
		 * Defines test File Directory
		 * @param string $dir [path/to/dir
		 */
>>>>>>> 963151d4... Merge pull request #171 from cptechinc/new-detail
		public static function set_testfiledirectory($dir) {
			self::$testfiledir = $dir;
		}

<<<<<<< HEAD
=======
		/**
		 * Defines fields files Directory
		 * @param string $dir [path/to/dir
		 */
>>>>>>> 963151d4... Merge pull request #171 from cptechinc/new-detail
		public static function set_fieldfiledirectory($dir) {
			self::$fieldfiledir = $dir;
		}
    }
