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
			$url->path = Processwire\wire('config')->pages->notes."redir/";
			$url->query->setData(array('action' => 'get-order-notes', 'ordn' => $order->orderno, 'linenbr' => $linenbr));
			return $url->getUrl();
		}
		
		public function generate_loaddocumentslink(Order $order, OrderDetail $orderdetail = null) {
			if ($orderdetail) {
				return $this->generate_loaddetaildocumentslink($order, $orderdetail);
			} else {
				return $this->generate_loadheaderdocumentslink($order, $orderdetail);
			}
		}
		
		public function generate_loadheaderdocumentslink(Order $order, OrderDetail $orderdetail = null) {
			$bootstrap = new Contento();
			$href = $this->generate_documentsrequesturl($order, $orderdetail);
			$icon = $bootstrap->createicon('fa fa-file-text');
			$ajaxdata = "data-loadinto=.docs|data-focus=.docs|data-click=#documents-link";
			
			if ($order->has_documents()) {
				return $bootstrap->openandclose('a', "href=$href|class=btn btn-primary load-sales-docs|role=button|title=Click to view Documents|$ajaxdata", $icon. ' Show Documents');
			} else {
				return $bootstrap->openandclose('a', "href=#|class=btn btn-default|title=No Documents Available", $icon. ' 0 Documents Found');
			}
		}
		
		public function generate_loaddetaildocumentslink(Order $order, OrderDetail $orderdetail = null) {
			$bootstrap = new Contento();
			$href = $this->generate_documentsrequesturl($order, $orderdetail);
			$icon = $bootstrap->createicon('fa fa-file-text');
			$ajaxdata = "data-loadinto=.docs|data-focus=.docs|data-click=#documents-link";
			$documentsTF = ($orderdetail) ? $orderdetail->has_documents() : $order->has_documents();
			
			if ($documentsTF) {
				return $bootstrap->openandclose('a', "href=$href|class=h3 load-sales-docs|role=button|title=Click to view Documents|$ajaxdata", $icon);
			} else {
				return $bootstrap->openandclose('a', "href=#|class=h3 text-muted|title=No Documents Available", $icon);
			}
		}
		
		/**
		 * Sets up a common url function for getting documents request url, classes that have this trait 
		 * will define generate_documentsrequesturltr(Order $order)
		 * @param  Order  $order [description]
		 * @return String		URL to the order redirect to make the get order documents request
		 */
		public function generate_documentsrequesturltrait(Order $order, OrderDetail $orderdetail = null) {
			$url = $this->generate_ordersredirurl();
			$url->query->setData(array('action' => 'get-order-documents', 'ordn' => $order->orderno));
			if ($orderdetail) {
				$url->query->set('itemdoc', $orderdetail->itemid);
			}
			return $url->getUrl();
		}
		
		public function generate_editurl(Order $order) {
			$url = $this->generate_ordersredirurl();
			$url->query->setData(array('action' => 'get-order-details','ordn' => $order->orderno));
			
			if ($order->can_edit()) {
				$url->query->set('lock', 'lock');
			} elseif ($order->editord == 'L') {
				if (Processwire\wire('user')->hasorderlocked) {
					$queryset = ($order->orderno == Processwire\wire('user')->lockedordn) ?  'lock' : 'readonly';
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
			$url = new \Purl\Url($this->generate_loaddetailsurl($order));
			$url->query->set('print', 'true');
			return $url->getUrl();
		}
		
		public function generate_viewprintpageurl(Order $order) {
			$url = new \Purl\Url($this->pageurl->getUrl());
			$url->path = Processwire\wire('config')->pages->print."order/";
			$url->query->set('ordn', $order->orderno);
			$url->query->set('print', 'true');
			return $url->getUrl();
		}
		
		public function generate_sendemailurl(Order $order) {
			$url = new \Purl\Url(wire('config')->pages->email."sales-order/");
			$url->query->set('ordn', $order->orderno);
			$url->query->set('referenceID', $this->sessionID);
			return $url->getUrl();
		}
		
		public function generate_viewlinkeduseractionslink(Order $order) {
			$bootstrap = new Contento();
			$href = $this->generate_viewlinkeduseractionsurl($order);
			$icon = $bootstrap->openandclose('span','class=h3', $bootstrap->createicon('glyphicon glyphicon-check'));
			return $bootstrap->openandclose('a', "href=$href|target=_blank", $icon." View Associated Actions");
		}
		
		public function generate_viewlinkeduseractionsurl(Order $order) {
			$url = new \Purl\Url($this->pageurl->getUrl());
			$url->path = Processwire\wire('config')->pages->actions."all/load/list/salesorder/";
			$url->query->setData(array('ordn' => $order->orderno));
			return $url->getUrl();
		}
		
		public function generate_viewdetaillink(Order $order, OrderDetail $detail) {
			$bootstrap = new Contento();
			$href = $this->generate_viewdetailurl($order, $detail);
			$icon = $bootstrap->createicon('glyphicon glyphicon-th-list');
			return $bootstrap->openandclose('a', "href=$href|class=btn btn-xs btn-primary view-item-details|data-itemid=$detail->itemid|data-kit=$detail->kititemflag|data-modal=#ajax-modal", $icon);
		}
		
		public function generate_viewdetailurl(Order $order, OrderDetail $detail) {
			$url = new \Purl\Url($this->pageurl->getUrl());
			$url->path = Processwire\wire('config')->pages->ajax."load/view-detail/order/";
			$url->query->setData(array('ordn' => $order->orderno, 'line' => $detail->linenbr));
			return $url->getUrl();
		}
		
		/**
		 * Return String URL to orders redir to request order details
		 * This is here for the use of getting the Print link
		 * will be used by classes to extend 
		 * Extending classes : SalesOrderPanel
		 * @param  Order  $order
		 * @return String 
		 */
		public function generate_loaddetailsurltrait(Order $order) {
			$url = $this->generate_ordersredirurl();
			$url->query->setData(array('action' => 'get-order-details', 'ordn' => $order->orderno));
			return $url->getUrl();
		}
		
		public function generate_loaddetailsurl(Order $order) {
			return $this->generate_loaddetailsurltrait($order);
		}
		
		public function generate_detailviewediturl(Order $order, OrderDetail $detail) {
			$url = new \Purl\Url(Processwire\wire('config')->pages->ajaxload.'edit-detail/order/');
			$url->query->setData(array('ordn' => $order->orderno, 'line' => $detail->linenbr));
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
		
		/**
		 * Sets up a common url function for getting d request url, classes that have this trait 
		 * will definve generate_trackingrequesturl(Order $order)
		 * @param  Order  $order [description]
		 * @return String		URL to the order redirect to make the get order documents request
		 */
		public function generate_trackingrequesturltrait(Order $order) {
			$url = $this->generate_ordersredirurl();
			$url->query->setData(array('action' => 'get-order-tracking', 'ordn' => $order->orderno));
			return $url->getUrl();
		}
		
		public function get_orderdetails(Order $order, $debug = false) {
			return get_orderdetails($this->sessionID, $order->orderno, true, $debug);
		}
		
		/** 
		 * Makes the URL to the orders redirect page, 
		 * @return \Purl\Url URL to REDIRECT page
		 */
		public function generate_ordersredirurl() {
			$url = new \Purl\Url(Processwire\wire('config')->pages->orders);
			$url->path = Processwire\wire('config')->pages->orders."redir/";
			return $url;
		}
	}
