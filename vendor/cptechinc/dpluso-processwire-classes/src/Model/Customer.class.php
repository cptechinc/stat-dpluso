<?php 
    class Customer extends Contact {
        use CreateFromObjectArrayTraits;
        /* =============================================================
			GETTER FUNCTIONS 
		============================================================ */
        public function get_name() {
            return (!empty($this->name)) ? $this->name : $this->custid;
        }
        
        public function get_shiptocount() {
            return count_shiptos($this->custid, Processwire\wire('user')->loginid, Processwire\wire('user')->hascontactrestrictions);
        }
        
        public function get_nextshiptoid() {
            $shiptos = get_customershiptos($this->custid, Processwire\wire('user')->loginid, Processwire\wire('user')->hascontactrestrictions);
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
                    
                    if ($i > sizeof($shiptos)) {
                        return $shiptos[0]->shiptoid;
                    } elseif ($i == sizeof($shiptos)) {
                        return $shiptos[$i - 1]->shiptoid;
                    } else {
                        $shiptos[$i]->shiptoid;
                    }
                } else {
                    return $shiptos[0]->shiptoid;
                }
            }
        }
        
        /* =============================================================
			CLASS FUNCTIONS 
		============================================================ */
        public function generate_title() {
            return $this->get_name() . (($this->has_shipto()) ? ' Ship-to: ' . $this->shiptoid : '');
        }
		
		public function generate_piesalesdata($value) {
			return array(
				'label' => $this->get_name(),
				'value' => $value,
				'custid' => $this->custid,
				'shiptoid' => $this->shiptoid
			);
		}
        
        /* =============================================================
			CRUD FUNCTIONS
		============================================================ */
		/**
		 * Loads an object with this class using the parameters as provided
		 * @param  string $custID    CustomerID
		 * @param  string $shiptoID  Shipto ID (can be blank)
		 * @param  string $contactID Contact ID (can be blank)
		 * @param  bool   $debug Determines if Query Runs and if Customer Object is returned or SQL Query
		 * @return Customer
		 */
        public static function load($custID, $shiptoID = '', $contactID = '', $debug = false) {
            return self::create_fromobject(parent::load($custID, $shiptoID, $contactID, $debug));
        } 
        
    }
