<?php
	class CartDisplay extends OrderDisplay {
		use ThrowErrorTrait;
		
		protected $cart;
		
		/* =============================================================
			Class Functions
		============================================================ */
		public function get_cartquote($debug = false) {
			return $this->cart = get_carthead($this->sessionID, true, $debug);
		}
		
		public function load_dplusnoteslinkdetail($linenbr) {
			$bootstrap = new Contento();
			$href = $this->generate_dplusnotesrequesturl($this->cart, $linenbr);
			$detail = CartDetail::load($this->sessionID, $linenbr);
			$title = ($detail->has_notes()) ? "View and Create Quote Notes" : "Create Quote Notes";
			$addclass = ($detail->has_notes()) ? '' : 'text-muted';
			$content = $bootstrap->createicon('material-icons md-36', '&#xE0B9;');
			$link = $bootstrap->openandclose('a', "href=$href|class=load-notes $addclass|title=$title|data-modal=$this->modal", $content);
			return $link;
		}
		
		public function load_dplusnoteslinkheader($linenbr = '0') {
			$bootstrap = new Contento();
			$href = $this->generate_dplusnotesrequesturl($this->cart, $linenbr);
			$has_notes = has_dplusnote($this->sessionID, $this->sessionID, '0', Processwire\wire('config')->dplusnotes['cart']['type']) == 'Y' ? true : false;
			$title = ($has_notes) ? "View and Create Quote Notes" : "Create Quote Notes";
			$addclass = ($has_notes) ? '' : 'text-muted';
			$content = $bootstrap->createicon('material-icons md-36', '&#xE0B9;');
			$link = $bootstrap->openandclose('a', "href=$href|class=load-notes $addclass|title=$title|data-modal=$this->modal", $content);
			return $link;
		}
		
		public function generate_loaddplusnoteslink(Order $cart, $linenbr = '0') {
			return intval($linenbr) ? $this->load_dplusnoteslinkdetail($linenbr) : $this->load_dplusnoteslinkheader($linenbr);
		}
		
		public function generate_dplusnotesrequesturl(Order $cart, $linenbr) {
			$url = new \Purl\Url($this->pageurl->getUrl());
			$url->path = Processwire\wire('config')->pages->notes."redir/";
			$url->query->setData(array('action' => 'get-cart-notes', 'linenbr' => $linenbr));
			return $url->getUrl();
		}
		
		public function generate_loaddocumentslink(Order $cart, OrderDetail $detail = null) {
			// TODO
		}
		
		public function generate_documentsrequesturl(Order $cart, OrderDetail $detail = null) {
			// TODO
		}
		
		public function generate_detailvieweditlink(Order $cart, OrderDetail $detail) {
			$bootstrap = new Contento();
			$href = $this->generate_detailviewediturl($cart, $detail);
			$icon = $bootstrap->openandclose('button', 'class=btn btn-sm btn-warning', $bootstrap->createicon('glyphicon glyphicon-pencil'));
			return $bootstrap->openandclose('a', "href=$href|class=update-line|data-kit=$detail->kititemflag|data-itemid=$detail->itemid|data-custid=$cart->custid|aria-label=View Detail Line", $icon);
		}
		
		public function generate_detailviewediturl(Order $cart, OrderDetail $detail) {
			$url = new \Purl\Url($this->pageurl->getUrl());
			$url->path = Processwire\wire('config')->pages->ajax."load/edit-detail/cart/";
			$url->query->setData(array('line' => $detail->linenbr));
			return $url->getUrl();
		}
	}
