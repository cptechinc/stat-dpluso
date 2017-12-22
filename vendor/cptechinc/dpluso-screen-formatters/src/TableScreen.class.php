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
