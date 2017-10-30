<?php 
    interface SalesOrderPanelInterface {
        public function get_ordercount($debug = false);
		public function get_orders($debug = false);
    }
