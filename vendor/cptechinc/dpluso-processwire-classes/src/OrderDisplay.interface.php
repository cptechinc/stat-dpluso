<?php 
	interface OrderDisplayInterface {
		public function generate_customerurl(Order $order); // OrderDisplay
		public function generate_customershiptourl(Order $order); // OrderDisplay
		public function generate_loaddplusnoteslink(Order $order, $linenbr = '0');
		public function generate_dplusnotesrequesturl(Order $order, $linenbr);
		public function generate_loaddocumentslink(Order $order, OrderDetail $detail = null);
		public function generate_documentsrequesturl(Order $order, OrderDetail $detail = null);
		public function generate_editlink(Order $order);
		public function generate_editurl(Order $order);
		public function generate_viewprintlink(Order $order);
		public function generate_viewprinturl(Order $order);
		public function generate_viewprintpageurl(Order $order);
		public function generate_sendemailurl(Order $order);
		public function generate_viewlinkeduseractionslink(Order $order);
		public function generate_viewlinkeduseractionsurl(Order $order);
		
		// FUNCTIONS FOR DETAIL LINES 
		public function generate_loaddetailsurl(Order $order);
		// public function generate_detailvieweditlink(Order $order, OrderDetail $detail, $display = false);
		public function generate_detailviewediturl(Order $order, OrderDetail $detail); // SalesOrderDisplayTraits
	}
