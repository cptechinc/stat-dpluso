<?php 
    interface OrderDisplayInterface {
        public function generate_loaddplusnoteslink(Order $order, $linenbr = '0');
        public function generate_dplusnotesrequesturl(Order $order, $linenbr);
        public function generate_loaddocumentslink(Order $order);
        public function generate_documentsrequesturl(Order $order);
        public function generate_editlink(Order $order);
        public function generate_editurl(Order $order);
        public function generate_viewprintlink(Order $order);
        public function generate_viewprinturl(Order $order);
        public function generate_viewlinkeduseractionslink(Order $order);
        public function generate_viewlinkeduseractionsurl(Order $order);
        public function generate_customerurl(Order $order);
        public function generate_customershiptourl(Order $order);
    }
