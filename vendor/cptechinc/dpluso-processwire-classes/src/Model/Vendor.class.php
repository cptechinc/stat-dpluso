<?php 
    class Vendor {
        use CreateFromObjectArrayTraits;
        
        protected $vendid;
        protected $shipfrom;
        protected $name; 
        protected $address1; 
        protected $address2; 
        protected $address3; 
        protected $city; 
        protected $state; 
        protected $zip;
        protected $country; 
        protected $phone; 
        protected $fax; 
        protected $email; 
        protected $createtime; 
        protected $createdate;
        protected $fieldaliases = array(
            'vendorID' => 'vendid',
            'shipfromID' => 'shipfrom'
        );
        
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
        
        public function get_name() {
            return (!empty($this->name)) ? $this->name : $this->vendid;
        }
        
        public function generate_title() {
            return $this->get_name() . (($this->has_shipfrom()) ? ' Shipfrom: '.$this->shipfrom : '');
        }
        
        public function has_shipfrom() {
            return (!empty($this->shipfrom));    
        }
        
        public function generate_viurl($withshipfrom = true) {
            $url = new \Purl\Url(wire('config')->pages->vendorinfo);
            $url->path->add($this->vendid);
            
            if ($withshipfrom) {
                $url->path->add('shipfrom-'.$this->shipfrom);
            }
            return $url->getUrl();
        }
        
        public static function load($vendorID, $shipfromID = '') {
            return get_vendor($vendorID, $shipfromID);
        }
        
        /* =============================================================
 		   GENERATE ARRAY FUNCTIONS 
 	   ============================================================ */
 		public static function generate_classarray() {
 			return UserAction::remove_nondbkeys(get_class_vars('Vendor'));
 		}
 		
 		public static function remove_nondbkeys($array) {
 			return $array;
 		}
 		
 		public function toArray() {
			return $this::remove_nondbkeys((array) $this);
 		}
        
        protected function error($error, $level = E_USER_ERROR) {
			$error = (strpos($error, 'DPLUSO [VENDORS]: ') !== 0 ? 'DPLUSO [VENDORS]: ' . $error : $error);
			trigger_error($error, $level);
			return;
		}
    }
