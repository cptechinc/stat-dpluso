<?php
    /**
     * Class for dealing with the Customer Index database table
     */
    class CustomerIndex {
        use ThrowErrorTrait;
		use MagicMethodTraits;
        use AttributeParser;
        
        /**
		 * Array of filters that will apply to the orders
		 * @var array
		 */
		public $filters = false; // Will be instance of array

		/**
		 * Array of key->array of filterable columns
		 * @var array
		 */
		public $filterable;
        
        /**
         * Page Number
         * @var int
         */
        public $pagenbr;
        
        /**
         * Returns the number of customer index records that fit the current 
         * criteria for the search based on user permissions
         * @param  string $query   Search Query
         * @param  string $loginID User Login ID, if blank, will use current user
         * @param  bool   $debug   Run in debug? If so, will return SQL Query
         * @return int             Number of customer index records that match
         */
        public function count_searchcustindex($query = '', $loginID = '', $debug = false) {
            return count_searchcustindex($query, $loginID, $debug);
        }
        
        /**
         * Return the number of customer index that user has access to
         * @param  string $loginID User Login ID, if blank, will use current user
         * @param  bool   $debug   Run in debug? If so, will return SQL Query
         * @return int             Number of customer index that user has access to
         */
        public function count_distinctcustindex($loginID = '', $debug = false) {
            return count_distinctcustindex($loginID, $debug);
        }
        
        
        
        /**
         * Returns the grouping description of the Customer Index based on configurations
         * NOTE customer-shipto=Customer Shipto | customer=Customer | none=No grouping
         * @return string Customer Index grouping description
         */
        public function get_configcustindexgroupby() {
            return DplusWire::wire('pages')->get('/config/customer/')->group_custindexby->title;
        }
    }
