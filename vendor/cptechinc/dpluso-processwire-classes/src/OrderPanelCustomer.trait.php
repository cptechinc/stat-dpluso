<?php     
    trait OrderPanelCustomerTraits {
        public $custID;
        public $shipID;
        
        public function set_customer($custID, $shipID) {
            $this->custID = $custID;
            $this->shipID = $shipID;
            $this->pageurl = $this->setup_pageurl($this->pageurl);
        }
        
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
