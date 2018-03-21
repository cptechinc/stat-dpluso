<?php
	/**
	 * Functions that all Detail Types will implement
	 */
	interface OrderDetailInterface {
		/**
		 * Returns if Detail has error
		 * @return bool
		 */
		public function has_error();
		
		/**
		 * Returns if Detail is a Kit Item
		 * @return bool [description]
		 */
		public function is_kititem();
		
		/**
		 * If Detail has Notes
		 * @return bool
		 */
		public function has_notes();
		
		/**
		 * Returns if Detail has Documents
		 * @return bool
		 */
		public function has_documents();
	}
