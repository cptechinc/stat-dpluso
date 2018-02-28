<?php 
	/**
	 * Dplus User that has their email, name, loginid, role, company, fax, phone
	 */
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
		
		/* =============================================================
			CRUD FUNCTIONS
		============================================================ */
		/**
		 * Loads an object of this class
		 * @param  string  $loginID User's Dplus Login ID
		 * @param  bool $debug   Whether to return the SQL to create the object or the object
		 * @return LogmUser
		 */
		public static function load($loginID, $debug = false) {
			return get_logmuser($loginID, $debug);
		} 
	}
