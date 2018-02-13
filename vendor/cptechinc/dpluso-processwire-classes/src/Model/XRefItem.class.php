<?php 
    class XRefItem {
        use CreateFromObjectArrayTraits;
		use CreateClassArrayTraits;
		use ThrowErrorTrait;
        
        protected $itemid;
        protected $origintype;
        protected $refitemid;
        protected $desc1;
        protected $desc2;
        protected $image;
        protected $create_date;
        protected $create_time;
        public $fieldaliases = array(
            'itemID' => 'itemid',
        );
        
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
        
        public function generate_imagesrc() {
            if (file_exists(wire('config')->imagefiledirectory.$this->image)) {
                return wire('config')->imagedirectory.$this->image;
            } else {
                return wire('config')->imagedirectory.wire('config')->imagenotfound;
            }
        }
        
        public function generate_iiselecturl($custID = false) {
            $url = new \Purl\Url(wire('config')->pages->products."redir/?action=ii-select");
            if (!empty($custID)) $url->query->set('custID', $custID);
            return $url->getUrl();
        }
        
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
    }
