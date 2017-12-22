<?php 
    class Customer extends Contact {
        use CreateFromObjectArrayTraits;
        
        public function get_name() {
            return (!empty($this->name)) ? $this->name : $this->custid;
        }
        
        public function generate_title() {
            return $this->get_name() . (($this->has_shipto()) ? ' Ship-to: ' . $this->shiptoid : '');
        }
        
        public function get_shiptocount() {
            return count_shiptos($this->custid, wire('user')->loginid, wire('user')->hascontactrestrictions);
        }
        
        public function get_nextshiptoid() {
            $shiptos = get_customershiptos($this->custid, wire('user')->loginid, wire('user')->hascontactrestrictions);
            if (sizeof($shiptos) < 1) {
                return false;
            } else {
                if ($this->has_shipto()) {
                    for ($i = 0; $i < sizeof($shiptos); $i++) {
                        if ($shiptos[$i]->shiptoid == $this->shiptoid) {
                            break;
                        }
                    }
                    $i++; // Get the next 
                    echo $i;
                    return ($i > sizeof($shiptos)) ? $shiptos[0]->shiptoid : $shiptos[$i]->shiptoid;
                } else {
                    return $shiptos[0]->shiptoid;
                }
            }
        }
        
        /* =============================================================
			OTHER CONSTRUCTOR FUNCTIONS 
            Inherits some from CreateFromObjectArrayTraits
		============================================================ */
		
        public static function load($custID, $shiptoID = '', $contactID = '') {
            return self::create_fromobject(get_customercontact($custID, $shiptoID, $contactID));
        } 
        
        protected function error($error, $level = E_USER_ERROR) {
			$error = (strpos($error, 'DPLUSO [CUSTOMER]: ') !== 0 ? 'DPLUSO [CUSTOMER]: ' . $error : $error);
			trigger_error($error, $level);
			return;
		}
    }
