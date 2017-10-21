<?php

    interface OrderDetailInterface {
        public function has_error(OrderDetail $detail);
        public function is_kititem($detail)
        public function has_note($detail)
        public function has_documents($detail)
    }
