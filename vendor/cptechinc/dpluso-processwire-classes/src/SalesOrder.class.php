<?php 

	class SalesOrder {
		public $sessionid;
		public $recno;
		public $date;
		public $time;
		public $type;
		public $custid;
		public $shiptoid;
		public $custname;
		public $orderno;
		public $custpo;
		public $custref;
		public $status;
		public $orderdate;
		public $careof;
		public $quotdate;
		public $invdate;
		public $shipdate;
		public $revdate;
		public $expdate;
		public $havedoc;
		public $havetrk;
		public $odrsubtot;
		public $odrtax;
		public $odrfrt;
		public $odrmis;
		public $odrtotal;
		public $havenote;
		public $editord;
		public $error;
		public $errormsg;
		public $sconame;
		public $sname;
		public $saddress;
		public $saddress2;
		public $scity;
		public $sst;
		public $szip;
		public $scountry;
		public $contact;
		public $phintl;
		public $phone;
		public $extension;
		public $faxnumber;
		public $email;
		public $releasenbr;
		public $shipviacd;
		public $shipviadesc;
		public $priccode;
		public $pricdesc;
		public $pricdisp;
		public $taxcode;
		public $taxcodedesc;
		public $taxcodedisp;
		public $termcode;
		public $termtype;
		public $termdesc;
		public $rqstdate;
		public $shipcom;
		public $sp1;
		public $sp1name;
		public $sp2;
		public $sp2name;
		public $sp2disp;
		public $sp3;
		public $sp3name;
		public $sp3disp;
		public $fob;
		public $deliverydesc;
		public $whse;
		public $ccno;
		public $xpdate;
		public $ccvalidcode;
		public $ccapproval;
		public $costtot;
		public $totdisc;
		public $paytype;
		public $srcdatefrom;
		public $srcdatethru;
		public $btname;
		public $btadr1;
		public $btadr2;
		public $btadr3;
		public $btctry;
		public $btcity;
		public $btstate;
		public $btzip;
		public $prntfmt;
		public $prntfmtdisp;
		public $dummy;       
		
		public $hasdocuments = false;
		public $hastracking = false;
		public $hasnotes = false;
		public $canedit = false;
		public $haserror = false;
		public $phoneinternational = false;
		
		public function __construct() {
			$this->update_properties();
		}
		
		public function update_properties() {
			if ($this->havedoc == 'Y') { $this->hasdocuments = true; }
			if ($this->havetrk == 'Y') { $this->hastracking = true; }
			if ($this->havenote == 'Y') { $this->hasnotes = true; }
			if ($this->editord == 'Y') { $this->canedit = true; }
			if ($this->phintl == 'Y') { $this->phoneinternational = true; }
			if ($this->error == 'Y') { $this->haserror = true; }
		}
		
		/* =============================================================
			LINK FUNCTIONS FUNCTIONS THAT GENERATE HTML LINK
		============================================================ */
		public function generate_loadnoteslink(SalesOrderPanel $orderpanel, $linenbr) {
			$bootstrap = new Contento();
			$href = $this->generate_dplusnotesrequesturl($orderpanel, $linenbr);
			
			if ($this->canedit) {
				$title = ($this->hasnotes) ? "View and Create Order Notes" : "Create Order Notes";
				$addclass = ($this->hasnotes) ? '' : 'text-muted';
			} else {
				$title = ($this->hasnotes) ? "View Order Notes" : "View Order Notes";
				$addclass = ($this->hasnotes) ? '' : 'text-muted';
			}
			$content = $bootstrap->createicon('material-icons md-36', '&#xE0B9;');
			$link = $bootstrap->openandclose('a', "href=$href|class=load-notes $addclass|title=$title|data-modal=$orderpanel->modal", $content);
			return $link;
		}
		
		public function generate_loadtrackinglink(SalesOrderPanel $orderpanel) { // TODO add logic to handle if for customer / rep
			$bootstrap = new Contento();
			
			if ($this->hastracking) {
				$href = $this->generate_trackingrequesturl($orderpanel);
				$content = $bootstrap->openandclose('span', "class=sr-only", 'View Tracking');
				$content .= $bootstrap->createicon('glyphicon glyphicon-plane hover');
				$ajaxdata = $orderpanel->generate_ajaxdataforcontento();
				return $bootstrap->openandclose('a', "href=$href|class=h3 generate-load-link|title=Click to view Tracking|$ajaxdata", $content);
			} else {
				$content = $bootstrap->openandclose('span', "class=sr-only", 'No Tracking Information Available');
				$content .= $bootstrap->createicon('glyphicon glyphicon-plane hover');
				return $bootstrap->openandclose('a', "|class=text-muted h3|title=No Tracking Info Available", $content);
			}
		}
		
		public function generate_loaddocumentslink(SalesOrderPanel $orderpanel, $linenbr) { // TODO add logic to handle if for customer / rep
			$bootstrap = new Contento();
			
			if ($this->hasdocuments) {
				$href = $this->generate_documentsrequesturl($orderpanel);
				$content = $bootstrap->openandclose('span', "class=sr-only", 'View Documents');
				$content .= $bootstrap->createicon('material-icons md-36', '&#xE873;');
				$ajaxdata = $orderpanel->generate_ajaxdataforcontento();
				return $bootstrap->openandclose('a', "href=$href|class=h3 generate-load-link|title=Click to view Documents|$ajaxdata", $content);
			} else {
				$content = $bootstrap->openandclose('span', "class=sr-only", 'No Documents Available');
				$content .= $bootstrap->createicon('material-icons md-36', '&#xE873;');
				return $bootstrap->openandclose('a', "|class=text-muted h3|title=No Documents Available", $content);
			}
			
		}
		
		public function generate_editorderlink() {
			$bootstrap = new Contento();
			/*
				ORDER LOCK LOGIC
				-------------------------------------
				N = PICKED, INVOICED, ETC CANNOT EDIT
				Y = CAN EDIT
				L = YOU'VE LOCKED THIS ORDER
			*/

			if ($this->canedit) {
				$icon = $bootstrap->createicon('glyphicon glyphicon-pencil');
				$title = "Edit this Order";
			} elseif ($this->editord == 'L') {
				if (wire('user')->hasorderlocked) {
					if ($this->orderno == wire('user')->lockedordn) {
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
			$href = $this->generate_editorderurl();
			return $bootstrap->openandclose('a', "href=$href|class=edit-order h3|title=$title", $icon);
		}
		
		public function generate_clicktoexpandlink(SalesOrderPanel $orderpanel) {
			$bootstrap = new Contento();
			
			if ($this->orderno == $orderpanel->active) {
				$href = $this->generate_closedetailsurl($orderpanel);
				$ajaxdata = $orderpanel->generate_ajaxdataforcontento();
				$addclass = 'load-link';
				$icon = '-';
			} else {
				if ($orderpanel->orderby) { $sorting = 	$orderby."-".$sortrule; } // just to set up the orderlink querystring replace TODO
				$href = $this->generate_getorderdetailsurl($orderpanel);
				$ajaxdata = "data-loadinto=$orderpanel->loadinto|data-focus=#$this->orderno";
				$addclass = 'generate-load-link';
				$icon = '+';
			}
			return $bootstrap->openandclose('a', "href=$href|class=btn btn-sm btn-primary $addclass|$ajaxdata", $icon);
		}
		
		public function generate_viewprintlink(SalesOrderPanel $orderpanel) {
			$bootstrap = new Contento();
			$href = $this->generate_viewprinturl($orderpanel);
			$icon = $bootstrap->openandclose('span','class=h3', $bootstrap->createicon('glyphicon glyphicon-print'));
			return $bootstrap->openandclose('a', "href=$href|target=_blank", $icon." View Printable Order");
		}
		
		public function generate_viewrelatedactionslink(SalesOrderPanel $orderpanel) {
			$bootstrap = new Contento();
			$href = $this->generate_relatedactionsurl($orderpanel);
			$icon = $bootstrap->openandclose('span','class=h3', $bootstrap->createicon('glyphicon glyphicon-check'));
			return $bootstrap->openandclose('a', "href=$href|class=load-into-modal|data-modal=$orderpanel->modal", $icon." View Associated Actions");
		}
		/* =============================================================
			URL FUNCTIONS -> FUNCTIONS THAT GENERATE URLS 
		============================================================ */
		public function generate_dplusnotesrequesturl(SalesOrderPanel $orderpanel, $linenbr) {
			$url = new \Purl\Url($orderpanel->pageurl->getUrl());
			$url->path = wire('config')->pages->notes."redir/";
			$url->query->setData(array('action' => 'get-order-notes','ordn' => $this->orderno, 'linenbr' => $linenbr));
			return $url->getUrl();
		}
		
		public function generate_trackingrequesturl(SalesOrderPanel $orderpanel) {
			$url = $this->generate_ordersredirurl();
			$url->query->setData(array('action' => 'get-order-tracking', 'ordn' => $this->orderno, 'page' => $orderpanel->pagenbr, 'orderby' => $orderpanel->tablesorter->orderbystring));
			if ($orderpanel->type == 'cust') {
				$url->query->set('custID', $this->custid);
			}
			return $url->getUrl();
		}
		
		public function generate_documentsrequesturl(SalesOrderPanel $orderpanel) {
			$url = $this->generate_ordersredirurl();
			$url->query->setData(array('action' => 'get-order-documents', 'ordn' => $this->orderno, 'page' => $orderpanel->pagenbr, 'orderby' => $orderpanel->tablesorter->orderbystring));
			if ($orderpanel->type == 'cust') {
				$url->query->set('custID', $this->custid);
			}
			return $url->getUrl();
		}
		
		
		public function generate_editorderurl() {
			$url = $this->generate_ordersredirurl();
			$url->query->setData(array('action' => 'get-order-details','ordn' => $this->orderno));
			if ($this->canedit) {
				$url->query->set('lock', 'lock');
			} elseif ($this->editord == 'L') {
				if (wire('user')->hasorderlocked) {
					if ($this->orderno == wire('user')->lockedordn) {
						$url->query->set('lock', 'lock');
					} else {
						$url->query->set('readonly', 'readonly');
					}
				} else {
					$url->query->set('readonly', 'readonly');
				}
			} else {
				$url->query->set('readonly', 'readonly');
			}
			return $url->getUrl();
		}
		
		public function generate_closedetailsurl(SalesOrderPanel $orderpanel) { 
			$url = new \Purl\Url($orderpanel->pageurl->getUrl());
			$url->query->setData(array('ordn' => false, 'show' => false));
			return $url->getUrl();
		}
		
		public function generate_getorderdetailsurl(SalesOrderPanel $orderpanel) { // TODO add logic to handle if for customer / rep
			$url = $this->generate_ordersredirurl();
			$url->query->setData(array('action' => 'get-order-details', 'ordn' => $this->orderno, 'page' => $orderpanel->pagenbr, 'orderby' => $orderpanel->tablesorter->orderbystring));
			if ($orderpanel->type == 'cust') {
				$url->query->set('custID', $this->custid);
			}
			return $url->getUrl();
		}
		
		public function generate_customerurl() {
			$url = new \Purl\Url(wire('page')->httpUrl);
			$url->path = wire('config')->pages->customer."$this->custid/";
			return $url->getUrl();
		}
		
		public function generate_customershiptourl() {
			$url = new \Purl\Url($this->generate_customerurl());
			if (!empty($this->shiptoid)) $url->path->add("shipto-$this->shiptoid");
			return $url->getUrl();
		}
				
		public function generate_viewprinturl(SalesOrderPanel $orderpanel) {
			$url = new \Purl\Url($this->generate_getorderdetailsurl($orderpanel));
			$url->query->set('print', 'true');
			return $url->getUrl();
		}
		
		public function generate_relatedactionsurl(SalesOrderPanel $orderpanel) {
			$url = new \Purl\Url($orderpanel->pageurl->getUrl());
			$url->path = wire('config')->pages->actions."all/load/list/order/";
			$url->query->setData(array('ordn' => $this->orderno));
			return $url->getUrl();
		}
		
		public function generate_ordersredirurl() {
			$url = new \Purl\Url(wire('page')->httpUrl);
			$url->path = wire('config')->pages->orders."redir/";
			return $url;
		}
		
		/* =============================================================
			CONTENT FUNCTIONS 
		============================================================ */
		function generate_shiptopopover() {
			$bootstrap = new Contento();
			$address = $this->saddress.'<br>';
			$address .= (!empty($this->saddress2)) ? $this->saddress2."<br>" : '';
			$address .= $this->scity.", ". $this->sst.' ' . $this->szip;
			$attr = "tabindex=0|role=button|class=btn btn-default bordered btn-sm|data-toggle=popover";
			$attr .= "|data-placement=top|data-trigger=focus|data-html=true|title=Ship-To Address|data-content=$address";
			return $bootstrap->openandclose('a', $attr, '<b>?</b>');
		}
		
		public function generate_rowclass(SalesOrderPanel $orderpanel) {
			if ($orderpanel->active == $this->orderno) {
				return 'selected';
			} else {
				return '';
			}
		}
		
		/* =============================================================
			OTHER CONSTRUCTOR FUNCTIONS 
		============================================================ */
		public static function create_fromarray(array $array) {
			$myClass = get_class();
			$object  = new $myClass(); 

			foreach ($array as $key => $val) {
			$object->$key = $val;
			}
			$object->update_properties();
			return $object;
	   }
	   
	   /* =============================================================
		   GENERATE ARRAY FUNCTIONS 
	   ============================================================ */
		
		public static function generate_classarray() {
			return SalesOrder::remove_nondbkeys(get_class_vars('SalesOrder'));
		}
		
		public static function remove_nondbkeys($array) {
			unset($array['canedit']);
			unset($array['hasnotes']);
			unset($array['hasdocuments']);
			unset($array['hastracking']);
			unset($array['haserror']);
			unset($array['phoneinternational']);
			return $array;
		}
		
	}
	
	
