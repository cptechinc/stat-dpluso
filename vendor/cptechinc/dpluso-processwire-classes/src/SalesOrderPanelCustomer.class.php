<?php 
    class CustomerSalesOrderPanel extends SalesOrderPanel implements OrderPanelCustomerInterface {
        use OrderPanelCustomerTraits;
        
        public function setup_pageurl(\Purl\Url $pageurl) {
			$url = parent::setup_pageurl($pageurl);
            $url->path->add("cust");
            $url->path->add($this->custID);
			return $url;
		}
        
        public function generate_loadurl() { 
            $url = new \Purl\Url(parent::generate_loadurl());
            $url->query->set('action', 'load-cust-orders');
            $url->query->set('custID', $this->custID);
            return $url->getUrl();
        }
        
        public function generate_getorderdetailsurl(Order $order) {
            $url = new \Purl\Url(parent::generate_getorderdetailsurl($order));
			$url->query->set('custID', $order->custid);
			return $url->getUrl();
		}
        
        public function generate_trackingrequesturl(Order $order) {
            $url = new \Purl\Url(parent::generate_trackingrequesturl($order));
            $url->query->set('custID', $this->custID);
            return $url->getUrl();
        }
        
        public function generate_documentsrequesturl(Order $order) {
            $url = new \Purl\Url(parent::generate_documentsrequesturl($order));
            $url->query->set('custID', $this->custID);
            return $url->getUrl();
        }
        
        public function generate_searchurl() {
            $url = new \Purl\Url(parent::generate_searchurl());
            $url->path = wire('config')->pages->ajax.'load/orders/search/cust/';
            $url->query->set('custID', $this->custID);
            if ($this->shipID) {
                $url->query->set('shipID', $this->shipID);
            }
            return $url->getUrl();
        }
        
        public function get_ordercount() {
			$this->count = count_customerorders($this->sessionID, $this->custID, false);
		}
        
        public function get_orders($debug = false) {
            $useclass = true;
            if ($this->tablesorter->orderby) {
                if ($this->tablesorter->orderby == 'orderdate') {
                    $this->orders = get_customerordersorderdate($this->sessionID, $this->custID, wire('session')->display, $this->pagenbr, $this->tablesorter->sortrule, $useclass, $debug);
                } else {
                    $this->orders = get_customerordersorderby($this->sessionID, $this->custID, wire('session')->display, $this->pagenbr, $this->tablesorter->sortrule, $this->tablesorter->orderby, $useclass, $debug);
                }
            } else {
                $this->tablesorter->sortrule = 'DESC'; $this->tablesorter->orderby = 'orderno';
                $this->orders = get_customerordersorderby($this->sessionID, $this->custID, wire('session')->display, $this->pagenbr, $this->tablesorter->sortrule, $this->tablesorter->orderby, $useclass, $debug);
            }
        }
    }
