<?php
	/**
	 * Traits for defining a Customer on the orderpanels
	 */
    trait OrderPanelCustomerTraits {
		/**
		 * Customer ID
		 * @var string
		 */
        protected $custID;
		
		/**
		 * ShiptoID
		 * @var string
		 */
        protected $shipID;
        
		/**
		 * Sets the customer and shipto for the OrderPanel
		 * @param string $custID Customer ID
		 * @param string $shipID Customer ShiptoID
		 * @uses
		 */
        public function set_customer($custID, $shipID) {
            $this->custID = $custID;
            $this->shipID = $shipID;
            $this->pageurl = $this->setup_pageurl($this->pageurl);
        }
        
		/**
		 * Sets up the page URL to have the Customer ID and shiptoID
		 * @param  Purl\Url $pageurl Page URL
		 * @return string            New URL with customer and shiptoID
		 */
        public function setup_pageurl(\Purl\Url $pageurl) {
			$url = parent::setup_pageurl($pageurl);
            $url->path->add("cust");
            $url->path->add($this->custID);
            $this->paginationinsertafter = $this->custID;
            if (!empty($this->shipID)) {
                $url->path->add("shipto-$this->shipID");
                $this->paginationinsertafter = "shipto-$this->shipID";
            }
			return $url;
		}
    }
