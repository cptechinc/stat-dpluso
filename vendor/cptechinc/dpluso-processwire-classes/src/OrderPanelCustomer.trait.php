<?php     
    trait OrderPanelCustomerTraits {
        public $custID;
        public $shipID;
        
        public function set_customer($custID, $shipID) {
            $this->custID = $custID;
            $this->shipID = $shipID;
            $this->pageurl = $this->setup_pageurl($this->pageurl);
        }
    }
