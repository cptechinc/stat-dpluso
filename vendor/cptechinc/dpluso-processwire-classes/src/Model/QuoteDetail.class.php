<?php 
	class QuoteDetail extends OrderDetail implements OrderDetailInterface {
		use CreateFromObjectArrayTraits;
		use CreateClassArrayTraits;
		
		protected $quotenbr;
		protected $venddetail;
		protected $leaddays;
		protected $ordrqty;
		protected $ordrprice;
		protected $ordrcost;
		protected $ordrtotalprice;
		protected $costuom;
		protected $quotind;
		protected $quotunit;
		protected $quotprice;
		protected $quotcost;
		protected $quotmkupmarg;
		protected $error;
		
		/* =============================================================
			GETTER FUNCTIONS
		============================================================ */
		public function has_error() {
			return $this->error == 'Y' ? true : false;
		}
		
		public function has_documents() {
			//return $this->notes == 'Y' ? true : false;
			return false;
		}
		
		public function can_edit() {
			$quote = Quote::load($this->sessionid, $this->quotenbr);
			return $quote->can_edit();
		}
	}
