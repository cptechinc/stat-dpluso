<?php
    interface OrderDetailInterface {
        public function has_error();
        public function is_kititem();
        public function has_note();
        public function has_documents();
    }
