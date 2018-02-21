<?php 
	interface OrderPanelInterface {
		public function setup_pageurl(\Purl\Url $pageurl);
		public function generate_expandorcollapselink(Order $order);
		public function generate_rowclass(Order $order);
		public function generate_shiptopopover(Order $order); // OrderPanel
		public function generate_ajaxdataforcontento();
		public function generate_iconlegend();
		public function generate_loadlink(); // OrderPanel
		public function generate_loadurl();
		public function generate_refreshlink();
		public function generate_clearsearchlink(); // OrderPanel
		public function generate_searchlink();
		public function generate_searchurl();
		public function generate_clearsortlink(); // OrderPanel
		public function generate_clearsorturl(); // OrderPanel
		public function generate_tablesortbyurl($column); // OrderPanel
		public function generate_closedetailsurl();
		public function generate_loaddetailsurl(Order $order);
		public function generate_lastloadeddescription();
	}
