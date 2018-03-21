<?php 
	/**
	 * Dplus User that has their email, name, loginid, role, company, fax, phone
	 */
	class LogmUser {
		use ThrowErrorTrait;
		use MagicMethodTraits;
		
		/**
		 * Login ID
		 * @var string
		 */
		protected $loginid;
		
		/**
		 * User's Name
		 * @var string
		 */
		protected $name;
		
		/**
		 * Warehouse ID
		 * @var string
		 */
		protected $whseid;
		
		/**
		 * Role in the Company
		 * @var string
		 */
		protected $role;
		
		/**
		 * Company Name
		 * @var string
		 */
		protected $company;
		
		/** 
		 * Fax #
		 * @var string
		 */
		protected $fax;
		
		/**
		 * Phone #
		 * @var string
		 */
		protected $phone;
		
		/**
		 * User Email
		 * @var string
		 */
		protected $email;
		
		/**
		 * Dummy 
		 * @var string X
		 */
		protected $dummy;
		
		
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
