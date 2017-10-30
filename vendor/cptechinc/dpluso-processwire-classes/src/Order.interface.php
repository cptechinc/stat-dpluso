<?php 
    interface OrderInterface {
        public function has_documents();
		public function has_notes();
		public function can_edit();
		public function is_phoneintl();
		public function has_error();
    }
