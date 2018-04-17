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
		 */
        public function set_customer($custID, $shipID) {
            $this->custID = $custID;
            $this->shipID = $shipID;
            $this->setup_pageurl();
        }
    }
