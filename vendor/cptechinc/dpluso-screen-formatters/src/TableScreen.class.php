<?php 
    abstract class TableScreenMaker {
        protected $sessionID;
        protected $userID;
		protected $debug = false;
		protected $tabletype = 'normal'; // grid or normal
		protected $type = ''; 
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
		public function __construct($sessionID) {
            $this->sessionID = $sessionID;
			$this->userID = wire('user')->loginid;
			$this->load_filepath();
		}
		
		public function set_debug($debug) {
			$this->debug = $debug;
			$this->load_filepath();
		}
        
        /* =============================================================
          GETTER FUNCTIONS
       ============================================================ */
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
                $this->load_fields();
            }
            return $this->fields;
		}
        
        /* =============================================================
          PUBLIC FUNCTIONS
       	============================================================ */
		public function load_filepath() {
			$this->fullfilepath = ($this->debug) ? self::$testfiledir.$this->datafilename.".json" : self::$filedir.$this->sessionID."-".$this->datafilename.".json";
		}
		
		public function process_json() {
			$this->load_filepath();
			$json = json_decode(file_get_contents($this->fullfilepath), true); 
			$this->json = (!empty($json)) ? $json : array('error' => true, 'errormsg' => "The $this->title JSON contains errors. JSON ERROR: ".json_last_error());
		}
        
        public function get_tableblueprint() {
            if (!$this->tableblueprint) {
                $this->generate_tableblueprint();
            }
            return $this->tableblueprint;
        }
        
        public function generate_screen() {
            return '';
        }
        
        public function generate_javascript() {
            return '';
        }
        
        public function generate_shownotesselect() {
            $bootstrap = new Contento();
            $array = array();
            foreach (wire('config')->yesnoarray as $key => $value) {
                $array[$value] = $key;
            }
            return $bootstrap->select('class=form-control input-sm|id=shownotes', $array);
        }
        
        /* =============================================================
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
        
        static public function generate_trackingurl($servicetype, $tracknbr) {
            $href = false;
            if (strpos(strtolower($servicetype), 'fed') !== false) {
                $href = "https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber=$tracknbr&cntry_code=us";
            } else if (strpos(strtolower($servicetype), 'ups') !== false) {
                $href = "http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=$tracknbr&loc=en_us";
            } else if (strpos(strtolower($servicetype), 'gro') !== false) {
                $href = "http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=$tracknbr&loc=en_us";
            }
            return $href;
        }
        
        static public function generate_phoneurl($phone) {
            return str_replace('-', '', $phone);
        }
        
        /* =============================================================
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
		
		public static function set_testfiledirectory($dir) {
			self::$testfiledir = $dir;
		}
		
		public static function set_fieldfiledirectory($dir) {
			self::$fieldfiledir = $dir;
		}
    }
