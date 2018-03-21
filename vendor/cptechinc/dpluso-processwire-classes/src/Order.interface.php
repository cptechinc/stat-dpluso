<?php 
	/**
	 * Functions that Quotes, Sales Orders have 
	 */
    interface OrderInterface {
		/**
		 * Returns if Order has documents
		 * @return bool
		 */
		public function has_documents();
		
		/**
		 * Returns if Order has notes
		 * @return bool
		 */
		public function has_notes();
		
		/**
		 * Returns if Order is editable
		 * @return bool
		 */
		public function can_edit();
		
		/**
		 * Returns if Order phone is international
		 * @return bool
		 */
		public function is_phoneintl();
		
		/**
		 * Returns if Order has error
		 * @return bool
		 */
		public function has_error();
    }
