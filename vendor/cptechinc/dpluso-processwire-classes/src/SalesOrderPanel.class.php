<?php 	
	class SalesOrderPanel extends OrderPanel implements OrderDisplayInterface, SalesOrderDisplayInterface, OrderPanelInterface, SalesOrderPanelInterface {
		public $orders = array();
		use SalesOrderDisplayTraits;
		
		public function __construct($sessionID, \Purl\Url $pageurl, $modal, $loadinto, $ajax) {
			parent::__construct($sessionID, $pageurl, $modal, $loadinto, $ajax);
			$this->pageurl = $this->setup_pageurl($pageurl);
		}
		
		public function setup_pageurl(\Purl\Url $pageurl) {
			$url = $pageurl;
			$url->path = wire('config')->pages->ajax."load/orders/";
			$url->query->remove('display');
			$url->query->remove('ajax');
			$this->paginationinsertafter = 'orders';
			return $url;
		}
		
		public function generate_lastloadeddescription() {
            if (wire('session')->{'orders-loaded-for'}) {
                if (wire('session')->{'orders-loaded-for'} == wire('user')->loginid) {
                    return 'Last Updated : ' . wire('session')->{'orders-updated'};
                }
                return '';
            }
            return '';
        }
		
		/* =============================================================
            SalesOrderPanelInterface Functions
            
        ============================================================ */
		public function get_ordercount($debug = false) { }
		public function get_orders($debug = false) { }
		
		/* =============================================================
			OrderPanelInterface Functions
			LINKS ARE HTML LINKS, AND URLS ARE THE URLS THAT THE HREF VALUE
		============================================================ */
		public function generate_refreshlink() {
            $bootstrap = new Contento();
            $href = $this->generate_loadurl();
            $icon = $bootstrap->createicon('fa fa-refresh');
            $ajaxdata = $this->generate_ajaxdataforcontento();
            return $bootstrap->openandclose('a', "href=$href|class=generate-load-link|$ajaxdata", "$icon Refresh Orders");
        }
		
		public function generate_expandorcollapselink(Order $order) {
			$bootstrap = new Contento();
			
			if ($order->orderno == $this->activeID) {
				$href = $this->generate_closedetailsurl($order);
				$ajaxdata = $this->generate_ajaxdataforcontento();
				$addclass = 'load-link';
				$icon = '-';
			} else {
				$href = $this->generate_loaddetailsurl($order);
				$ajaxdata = "data-loadinto=$this->loadinto|data-focus=#$order->orderno";
				$addclass = 'generate-load-link';
				$icon = '+';
			}
			return $bootstrap->openandclose('a', "href=$href|class=btn btn-sm btn-primary $addclass|$ajaxdata", $icon);
		}
		
		public function generate_closedetailsurl() { 
			$url = new \Purl\Url($this->pageurl->getUrl());
			$url->query->setData(array('ordn' => false, 'show' => false));
			return $url->getUrl();
		}
		
		public function generate_rowclass(Order $order) {
			return  ($this->activeID == $order->orderno) ? 'selected' : '';
		}
		
		function generate_shiptopopover(Order $order) {
			$bootstrap = new Contento();
			$address = $order->saddress.'<br>';
			$address .= (!empty($order->saddress2)) ? $order->saddress2."<br>" : '';
			$address .= $order->scity.", ". $order->sst.' ' . $order->szip;
			$attr = "tabindex=0|role=button|class=btn btn-default bordered btn-sm|data-toggle=popover";
			$attr .= "|data-placement=top|data-trigger=focus|data-html=true|title=Ship-To Address|data-content=$address";
			return $bootstrap->openandclose('a', $attr, '<b>?</b>');
		}
		
		public function generate_iconlegend() {
			$bootstrap = new Contento();
			$content = $bootstrap->openandclose('i', 'class=glyphicon glyphicon-shopping-cart|title=Re-order Icon', '') . ' = Re-order <br>';
			$content .= $bootstrap->openandclose('i', "class=material-icons|title=Documents Icon", '&#xE873;') . '&nbsp; = Documents <br>'; 
			$content .= $bootstrap->openandclose('i', 'class=glyphicon glyphicon-plane hover|title=Tracking Icon', '') . ' = Tracking <br>';
			$content .= $bootstrap->openandclose('i', 'class=material-icons|title=Notes Icon', '&#xE0B9;') . ' = Notes <br>';
			$content .= $bootstrap->openandclose('i', 'class=glyphicon glyphicon-pencil|title=Edit Order Icon', '') . ' = Edit Order <br>'; 
			$content = str_replace('"', "'", $content);
			$attr = "tabindex=0|role=button|class=btn btn-sm btn-info|data-toggle=popover|data-placement=bottom|data-trigger=focus";
			$attr .= "|data-html=true|title=Icons Definition|data-content=$content";
			return $bootstrap->openandclose('a', $attr, 'Icon Definitions');
		}

		public function generate_loadurl() { 
			$url = new \Purl\Url($this->pageurl->getUrl());
			$url->path = wire('config')->pages->orders.'redir/';
			$url->query->setData(array('action' => 'load-orders'));
			return $url->getUrl();
		}
		
		public function generate_searchlink() {
			$bootstrap = new Contento();
			$href = $this->generate_searchurl();
			return $bootstrap->openandclose('a', "href=$href|class=btn btn-default bordered load-into-modal|data-modal=$this->modal", "Search Orders");
		}
		
		public function generate_searchurl() {
			$url = new \Purl\Url($this->pageurl->getUrl());
			$url->path = wire('config')->pages->ajax.'load/orders/search/';
			$url->query = '';
			return $url->getUrl();
		}
		
		public function generate_loaddetailsurl(Order $order) {
			$url = new \Purl\Url($this->generate_loaddetailsurltrait($order));
			$url->query->set('page', $this->pagenbr);
			$url->query->set('orderby', $this->tablesorter->orderbystring);
			return $url->getUrl();
		}
		
		public function generate_detailreorderform(Order $order, OrderDetail $detail) {
            if (empty($detail->itemid)) return '';
            $action = wire('config')->pages->cart.'redir/';
            $id = $order->orderno.'-'.$detail->itemid.'-form';
            $form = new FormMaker("method=post|action=$action|class=item-reorder|id=$id");
            $form->input("type=hidden|name=action|value=add-to-cart");
            $form->input("type=hidden|name=ordn|value=$order->orderno");
            $form->input("type=hidden|name=custID|value=$order->custid");
            $form->input("type=hidden|name=itemID|value=$detail->itemid");
            $form->input("type=hidden|name=qty|value=".intval($detail->qtyordered));
            $form->input("type=hidden|name=desc|value=$detail->desc1");
            $form->button("type=submit|class=btn btn-primary btn-xs", $form->createicon('glyphicon glyphicon-shopping-cart'). $form->openandclose('span', 'class=sr-only', 'Submit Reorder'));
            return $form->finish();
        }
		
		/* =============================================================
            OrderDisplayInterface Functions
            LINKS ARE HTML LINKS, AND URLS ARE THE URLS THAT THE HREF VALUE
        ============================================================ */
		public function generate_loaddplusnoteslink(Order $order, $linenbr = '0') {
			$bootstrap = new Contento();
			$href = $this->generate_dplusnotesrequesturl($order, $linenbr);
			
			if ($order->can_edit()) {
				$title = ($order->has_notes()) ? "View and Create Order Notes" : "Create Order Notes";
				$addclass = ($order->has_notes()) ? '' : 'text-muted';
			} else {
				$title = ($order->has_notes()) ? "View Order Notes" : "View Order Notes";
				$addclass = ($order->has_notes()) ? '' : 'text-muted';
			}
			$content = $bootstrap->createicon('material-icons md-36', '&#xE0B9;');
			$link = $bootstrap->openandclose('a', "href=$href|class=load-notes $addclass|title=$title|data-modal=$this->modal", $content);
			return $link;
		}
		
		public function generate_documentsrequesturl(Order $order, OrderDetail $orderdetail = null) {
            $url = new \Purl\Url($this->generate_documentsrequesturltrait($order, $orderdetail));
			$url->query->set('page', $this->pagenbr);
			$url->query->set('orderby', $this->tablesorter->orderbystring);
            return $url->getUrl();
        }
		
		public function generate_viewlinkeduseractionslink(Order $order) {
			$bootstrap = new Contento();
			$href = $this->generate_viewlinkeduseractionsurl($order);
			$icon = $bootstrap->openandclose('span','class=h3', $bootstrap->createicon('glyphicon glyphicon-check'));
			return $bootstrap->openandclose('a', "href=$href|class=load-into-modal|data-modal=$this->modal", $icon." View Associated Actions");
		}
		
		public function generate_editlink(Order $order) {
			$bootstrap = new Contento();
			/*
				ORDER LOCK LOGIC
				-------------------------------------
				N = PICKED, INVOICED, ETC CANNOT EDIT
				Y = CAN EDIT
				L = YOU'VE LOCKED THIS ORDER
			*/

			if ($order->can_edit()) {
				$icon = $bootstrap->createicon('glyphicon glyphicon-pencil');
				$title = "Edit this Order";
			} elseif ($order->editord == 'L') {
				if (wire('user')->hasorderlocked) {
					if ($order->orderno == wire('user')->lockedordn) {
						$icon = $bootstrap->createicon('glyphicon glyphicon-wrench');
						$title = "Edit this Order";
					} else {
						$icon = $bootstrap->createicon('material-icons md-36', '&#xE897;');
						$title = "You have this order locked, but you can still view it";
					}
				} else {
					$icon = $bootstrap->createicon('material-icons md-36', '&#xE897;');
					$title = "You have this order locked, but you can still view it";
				}
			} else {
				$icon = $bootstrap->createicon('glyphicon glyphicon-eye-open');
				$title = "Open in read-only mode";
			}
			$href = $this->generate_editurl($order);
			return $bootstrap->openandclose('a', "href=$href|class=edit-order h3|title=$title", $icon);
		}
		
		public function generate_loaddocumentslink(Order $order, OrderDetail $orderdetail = null) {
            $bootstrap = new Contento();
            $href = $this->generate_documentsrequesturl($order, $orderdetail);
            $icon = $bootstrap->createicon('fa fa-file-text');
            $ajaxdata = $this->generate_ajaxdataforcontento();
            $documentsTF = ($orderdetail) ? $orderdetail->has_documents() : $order->has_documents();
			
            if ($documentsTF) {
                return $bootstrap->openandclose('a', "href=$href|class=h3 generate-load-link|title=Click to view Documents|$ajaxdata", $icon);
            } else {
                return $bootstrap->openandclose('a', "href=#|class=h3 text-muted|title=No Documents Available", $icon);
            }
        }
		
		public function generate_detailvieweditlink(Order $order, OrderDetail $detail) {
            $bootstrap = new Contento();
            $href = $this->generate_detailviewediturl($order, $detail);
            return $bootstrap->openandclose('a', "href=$href|class=update-line|data-kit=$detail->kititemflag|data-itemid=$detail->itemid|data-custid=$order->custid|aria-label=View Detail Line", $detail->itemid);    
        }
		
		/* =============================================================
            SalesOrderDisplayInterface Functions
            LINKS ARE HTML LINKS, AND URLS ARE THE URLS THAT THE HREF VALUE
        ============================================================ */
		public function generate_loadtrackinglink(Order $order) { 
			$bootstrap = new Contento();
			if ($order->has_tracking()) {
				$href = $this->generate_trackingrequesturl($order);
				$content = $bootstrap->openandclose('span', "class=sr-only", 'View Tracking');
				$content .= $bootstrap->createicon('glyphicon glyphicon-plane hover');
				$ajaxdata = $this->generate_ajaxdataforcontento();
				return $bootstrap->openandclose('a', "href=$href|class=h3 generate-load-link|title=Click to view Tracking|$ajaxdata", $content);
			} else {
				$content = $bootstrap->openandclose('span', "class=sr-only", 'No Tracking Information Available');
				$content .= $bootstrap->createicon('glyphicon glyphicon-plane hover');
				return $bootstrap->openandclose('a', "href=#|class=h3 text-muted|title=No Tracking Info Available", $content);
			}
		}
		
		public function generate_trackingrequesturl(Order $order) {
            $url = new \Purl\Url($this->generate_trackingrequesturltrait($order));
			$url->query->set('page', $this->pagenbr);
			$url->query->set('orderby', $this->tablesorter->orderbystring);
            return $url->getUrl();
        }
	}
