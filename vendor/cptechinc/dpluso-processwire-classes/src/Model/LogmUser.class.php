<?php 
	class LogmUser {
        use ThrowErrorTrait;
        
		protected $loginid;
		protected $name;
		protected $whseid;
		protected $role;
		protected $company;
		protected $fax;
		protected $phone;
		protected $email;
		protected $dummy;
        
        public function __get($property) {
			$method = "get_{$property}";
            if (method_exists($this, $method)) {
                return $this->$method();
            } elseif (property_exists($this, $property)) {
                return $this->$property;
            } else {
                $this->error("This property ($property) does not exist");
            }
        }
        
        public static function load($loginID, $debug = false) {
            return get_logmuser($loginID, $debug);
        } 
	}
