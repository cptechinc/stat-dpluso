<?php 
    class ItemLookupModal {
        protected $type = 'cart';
        protected $custID;
        protected $shipID;
        
        
        public function get_type() {
            return $this->type;
        }
        
        public function __get($property) {
            $method = "get_{$property}";
            if (method_exists($this, $method)) {
                return $this->$method();
            } elseif (property_exists($this, $property)) {
                return $this->$property;
            } else {
                $this->error("This property ($property) does not exist");
                return false;
            }
        }
        
        public function set_customer($custID, $shipID) {
            $this->custID = $custID;
            $this->shipID = $shipID;
        }  
        
        public function set_ordn($ordn) {
            $lookup = new ItemLookupModalOrder($ordn);
            $lookup->set_customer($this->custID, $this->shipID);
            return $lookup;
        }
        
        public function set_qnbr($qnbr) {
            $lookup = new ItemLookupModalQuote($qnbr);
            $lookup->set_customer($this->custID, $this->shipID);
            return $lookup;
        }
        
        public function generate_resultsurl() {
            $url = new \Purl\Url(wire('config')->pages->ajax.'load/products/item-search-results/cart/');
            $url->query->set('custID', $this->custID)->set('shipID', $this->shipID);
            return $url->getUrl();
        }
        
        public function generate_nonstockformurl() {
            $url = new \Purl\Url(wire('config')->pages->ajax.'load/products/non-stock/form/cart/');
            $url->query->set('custID', $this->custID)->set('shipID', $this->shipID);
            return $url->getUrl();
        }
        
        public function generate_addmultipleurl() {
            $url = new \Purl\Url(wire('config')->pages->ajax.'load/add-detail/cart/');
            $url->query->set('custID', $this->custID)->set('shipID', $this->shipID);
            return $url->getUrl();
        }
        
        protected function error($error, $level = E_USER_ERROR) {
			$error = (strpos($error, 'DPLUSO [ITEMLOOKUPMODAL]: ') !== 0 ? 'DPLUSO [ITEMLOOKUPMODAL]: ' . $error : $error);
			trigger_error($error, $level);
			return;
		}
    }
    
    class ItemLookupModalOrder extends ItemLookupModal  {
        protected $type = 'order';
        protected $ordn;
        
        public function __construct($ordn) {
            $this->ordn = $ordn;
        }
        
        public function generate_resultsurl() {
            $url = new \Purl\Url(wire('config')->pages->ajax.'load/products/item-search-results/order/');
            $url->query->setData(array('ordn' => $this->ordn,'custID' => $this->custID, 'shipID' => $this->shipID));
            $url->query->set('ordn', $this->ordn)->set('custID', $this->custID)->set('shipID', $this->shipID);
            return $url->getUrl();
        }
        
        public function generate_nonstockformurl() {
            $url = new \Purl\Url(wire('config')->pages->ajax.'load/products/non-stock/form/order/');
            $url->query->set('ordn', $this->ordn)->set('custID', $this->custID)->set('shipID', $this->shipID);
            return $url->getUrl();
        }
        
        public function generate_addmultipleurl() {
            $url = new \Purl\Url(wire('config')->pages->ajax.'load/add-detail/order/');
            $url->query->set('ordn', $this->ordn)->set('custID', $this->custID)->set('shipID', $this->shipID);
            return $url->getUrl();
        }
    }
    
    class ItemLookupModalQuote extends ItemLookupModal {
        protected $type = 'quote';
        protected $qnbr;
        
        public function __construct($qnbr) {
            $this->qnbr = $qnbr;
        }
        
        public function generate_resultsurl() {
            $url = new \Purl\Url(wire('config')->pages->ajax.'load/products/item-search-results/quote/');
            $url->query->set('qnbr', $this->qnbr)->set('custID', $this->custID)->set('shipID', $this->shipID);
            return $url->getUrl();
        }
        
        public function generate_nonstockformurl() {
            $url = new \Purl\Url(wire('config')->pages->ajax.'load/products/non-stock/form/quote/');
            $url->query->set('qnbr', $this->qnbr)->set('custID', $this->custID)->set('shipID', $this->shipID);
            echo $url->query->get('shipID');
            return $url->getUrl();
        }
        
        public function generate_addmultipleurl() {
            $url = new \Purl\Url(wire('config')->pages->ajax.'load/add-detail/quote/');
            $url->query->set('qnbr', $this->qnbr)->set('custID', $this->custID)->set('shipID', $this->shipID);
            return $url->getUrl();
        }
    }
