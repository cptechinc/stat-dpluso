<?php     
	class Paginator {
		public $pagenbr;
		public $ajaxdata;
		public $count;
		public $pageurl;
		public $insertafter;
		
		function __construct($pagenbr, $count, $pageurl, $insertafter, $ajaxdata = false) {
			$this->pagenbr = $pagenbr;
			$this->count = $count;
			$this->pageurl = $pageurl;
			$this->insertafter = $insertafter;
			$this->ajaxdata = $ajaxdata;
			
			$this->setup_displayonpage();
		}
		
		public function setup_displayonpage() {
			if (wire('input')->get->display) {
				wire('session')->display = wire('input')->get->text('display');
			} else {
				if (!wire('session')->display) {
					wire('session')->display = wire('config')->showonpage;
				}
			}
		}
		
		public function paginate($pagenbr) {
			if (strpos($this->pageurl, 'page') !== false) {
				$regex = "((page)\d{1,3})";
				if ($pagenbr > 1) { $replace = "page".$pagenbr; } else {$replace = ""; }
				$newurl = preg_replace($regex, $replace, $this->pageurl);
			} else {
				$this->insertafter = str_replace('/', '', $this->insertafter)."/";
				$regex = "(($this->insertafter))";
				if ($pagenbr > 1) { $replace = $this->insertafter."page".$pagenbr."/";} else {$replace = $this->insertafter; }
				$newurl = preg_replace($regex, $replace, $this->pageurl);
			}
			return $newurl;
		}
		
		public function generate_ajaxdataforcontento() {
			return str_replace(' ', '|', str_replace("'", "", str_replace('"', '', $this->ajaxdata)));
		}
		
		public function generate_showonpage() {
			$url = new \Purl\Url($this->pageurl);
			$url->query->remove('display');
			$href = $url->getUrl();
			$ajaxdata = $this->generate_ajaxdataforcontento();
			$bootstrap = new Contento();
			
			$form = $bootstrap->open('div', 'class=form-group');
			$form .= $bootstrap->openandclose('label','','Results Per Page');
			$form .= $bootstrap->open('select', 'class=form-control input-sm results-per-page|name=results-per-page');
			
			foreach (wire('config')->showonpageoptions as $val) {
				if ($val == wire('session')->display) {
					$form .= $bootstrap->openandclose('option',"value=$val|selected", $val);
				} else {
					$form .= $bootstrap->openandclose('option',"value=$val", $val);
				}
			}
			$form .= $bootstrap->close('select');
			$form .= $bootstrap->close('div');
			
			return $bootstrap->openandclose('form', "action=$href|method=get|class=form-inline results-per-page-form ajax-load|$ajaxdata", $form);
		}

		public function __toString() {
			$bootstrap = new Contento();
			$list = '';
			$totalpages = ceil($this->count / wire('session')->display); 
			if ($this->pagenbr == 1) {
				$link = $bootstrap->openandclose('a', 'href=#|aria-label=Previous', '<span aria-hidden="true">&laquo;</span>');
				$list .= $bootstrap->openandclose('li', 'class=disabled', $link);
			} else {
				$url = $this->paginate($this->pagenbr - 1);
				$ajaxdata = $this->generate_ajaxdataforcontento();
				$link = $bootstrap->openandclose('a', "href=$url|aria-label=Previous|class=load-link|$ajaxdata", '<span aria-hidden="true">&laquo;</span>');
				$list .= $bootstrap->openandclose('li', '', $link);
			}
			
			for ($i = ($this->pagenbr - 3); $i < ($this->pagenbr + 4); $i++) {
				if ($i > 0) {
					if ($this->pagenbr == $i) {
						$url = $this->paginate($i);
						$ajaxdata = $this->generate_ajaxdataforcontento();
						$link = $bootstrap->openandclose('a', "href=$url|class=load-link|$ajaxdata", $i);
						$list .= $bootstrap->openandclose('li', 'class=active', $link);
					} elseif ($i < ($totalpages + 1)) {
						$url = $this->paginate($i);
						$ajaxdata = $this->generate_ajaxdataforcontento();
						$link = $bootstrap->openandclose('a', "href=$url|class=load-link|$ajaxdata", $i);
						$list .= $bootstrap->openandclose('li', '', $link);
					}
				}
			}
			
			if ($this->pagenbr == $totalpages) {
				$link = $bootstrap->openandclose('a', 'href=#|aria-label=Next', '<span aria-hidden="true">&raquo;</span>');
				$list .= $bootstrap->openandclose('li', 'class=disabled', $link);
			} else {
				$url = $this->paginate($this->pagenbr + 1);
				$ajaxdata = $this->generate_ajaxdataforcontento();
				$link = $bootstrap->openandclose('a', "href=$url|aria-label=Next|class=load-link|$ajaxdata", '<span aria-hidden="true">&raquo;</span>');
				$list .= $bootstrap->openandclose('li', '', $link);
			}
			$ul = $bootstrap->openandclose('ul', 'class=pagination', $list);
			return $bootstrap->openandclose('nav', 'class=text-center', $ul);
		}
	}
?>
