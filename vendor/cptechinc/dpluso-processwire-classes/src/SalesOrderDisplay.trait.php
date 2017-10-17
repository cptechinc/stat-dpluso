<?php 
    trait SalesOrderDisplayTraits {
        /* =============================================================
            OrderDisplayInterface Functions
            LINKS ARE HTML LINKS, AND URLS ARE THE URLS THAT THE HREF VALUE
        ============================================================ */
        public function generate_loaddplusnoteslink(Order $order, $linenbr = '0') {
            $bootstrap = new Contento();
            $href = $this->generate_dplusnotesrequesturl($order, $linenbr);
            
            if ($order->can_edit()) {
                $title = ($order->has_notes()) ? "View and Create Order Notes" : "Create Order Notes";
            } else {
                $title = ($order->has_notes()) ? "View Order Notes" : "View Order Notes";
            }
            $content = $bootstrap->createicon('material-icons', '&#xE0B9;') . ' ' . $title;
            $link = $bootstrap->openandclose('a', "href=$href|class=btn btn-default load-notes|title=$title|data-modal=$this->modal", $content);
            return $link;
        }
        
        public function generate_dplusnotesrequesturl(Order $order, $linenbr) {
            $url = new \Purl\Url($this->pageurl->getUrl());
            $url->path = wire('config')->pages->notes."redir/";
            $url->query->setData(array('action' => 'get-order-notes', 'ordn' => $order->orderno, 'linenbr' => $linenbr));
            return $url->getUrl();
        }
        
        public function generate_loaddocumentslink(Order $order) {
            $bootstrap = new Contento();
            $href = $this->generate_documentsrequesturl($order);
            $icon = $bootstrap->createicon('material-icons', '&#xE873;');
            $ajaxdata = "data-loadinto=.docs|data-focus=.docs|data-click=#documents-link";
            
            if ($order->has_documents()) {
                return $bootstrap->openandclose('a', "href=$href|class=btn btn-primary load-sales-docs|role=button|title=Click to view Documents|$ajaxdata", $icon. ' Show Documents');
            } else {
                return $bootstrap->openandclose('a', "href=#|class=btn btn-default|title=No Documents Available", $icon. ' 0 Documents Found');
            }
        }
        
        public function generate_editurl(Order $order) {
            $url = $this->generate_ordersredirurl();
            $url->query->setData(array('action' => 'get-order-details','ordn' => $order->orderno));
            
            if ($order->can_edit()) {
                $url->query->set('lock', 'lock');
            } elseif ($order->editord == 'L') {
                if (wire('user')->hasorderlocked) {
                    $queryset = ($order->orderno == wire('user')->lockedordn) ?  'lock' : 'readonly';
                    $url->query->set($queryset, $queryset);
                } else {
                    $url->query->set('readonly', 'readonly');
                }
            } else {
                $url->query->set('readonly', 'readonly');
            }
            return $url->getUrl();
        }
        
        public function generate_viewprintlink(Order $order) {
            $bootstrap = new Contento();
            $href = $this->generate_viewprinturl($order);
            $icon = $bootstrap->openandclose('span','class=h3', $bootstrap->createicon('glyphicon glyphicon-print'));
            return $bootstrap->openandclose('a', "href=$href|target=_blank", $icon." View Printable Order");
        }
        
        public function generate_viewprinturl(Order $order) {
            $url = new \Purl\Url($this->generate_getorderdetailsurl($order));
            $url->query->set('print', 'true');
            return $url->getUrl();
        }
        
        public function generate_viewlinkeduseractionslink(Order $order) {
            $href = $this->generate_viewlinkeduseractionsurl($order);
            $icon = $bootstrap->openandclose('span','class=h3', $bootstrap->createicon('glyphicon glyphicon-check'));
            return $bootstrap->openandclose('a', "href=$href|target=_blank", $icon." View Associated Actions");
        }
        
        public function generate_viewlinkeduseractionsurl(Order $order) {
            $url = new \Purl\Url($this->pageurl->getUrl());
            $url->path = wire('config')->pages->actions."all/load/list/order/";
            $url->query->setData(array('ordn' => $order->orderno));
            return $url->getUrl();
        }
        
        /* =============================================================
            SalesOrderDisplayInterface Functions
        ============================================================ */
        public function generate_loadtrackinglink(Order $order) {
            $bootstrap = new Contento();
            $href = $this->generate_trackingrequesturl($order);
            $icon = $bootstrap->openandclose('i','class=glyphicon glyphicon-plane hover|style=top: 3px; padding-right: 5px; font-size: 130%;|aria-hidden=true', '');
            $ajaxdata = "data-loadinto=.tracking|data-focus=.tracking|data-click=#tracking-tab-link";
            
            if ($order->has_tracking()) {
                return $bootstrap->openandclose('a', "href=$href|role=button|class=btn btn-primary load-sales-tracking|title=Click to load tracking|$ajaxdata", $icon. ' Show Documents');
            } else {
                return $bootstrap->openandclose('a', "href=#|class=btn btn-default|title=No Tracking Available", $icon. ' No Tracking Available');
            }
        }
        
        public function generate_documentsrequesturltrait(Order $order) {
            $url = $this->generate_ordersredirurl();
            $url->query->setData(array('action' => 'get-order-documents', 'ordn' => $order->orderno));
            return $url->getUrl();
        }
        
        public function generate_trackingrequesturltrait(Order $order) {
            $url = $this->generate_ordersredirurl();
            $url->query->setData(array('action' => 'get-order-tracking', 'ordn' => $order->orderno));
            return $url->getUrl();
        }
        
        /* =============================================================
            URL Helper Functions
        ============================================================ */
        public function generate_customerurl(Order $order) {
            $url = $this->generate_customerredirurl();
            $url->query->setData(array('action' => 'load-customer', 'custID' => $order->custid));
            return $url->getUrl();
        }
        
        public function generate_customershiptourl(Order $order) {
            $url = new \Purl\Url($this->generate_customerurl($order));
            if (!empty($order->shiptoid)) $url->query->set('shipID', $order->shiptoid);
            return $url->getUrl();
        }
        
        /** 
         * Makes the URL to the orders redirect page, 
         * @return \Purl\Url URL to REDIRECT page
         */
        public function generate_ordersredirurl() {
            $url = new \Purl\Url(wire('config')->pages->orders);
            $url->path = wire('config')->pages->orders."redir/";
            return $url;
        }
        
        public function generate_customerredirurl() {
            $url = new \Purl\Url(wire('config')->pages->orders);
            $url->path = wire('config')->pages->customer."redir/";
            return $url;
        }
    }
