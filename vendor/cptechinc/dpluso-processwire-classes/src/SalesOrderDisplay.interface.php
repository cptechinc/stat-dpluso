<?php 
    interface SalesOrderDisplayInterface {
        public function generate_loadtrackinglink(Order $order);
        public function generate_trackingrequesturl(Order $order);
    }
