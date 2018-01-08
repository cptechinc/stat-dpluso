<?php     
	class Paginator {
		public $pagenbr;
		public $ajaxdata;
		public $count;
		public $pageurl;
		public $insertafter;
		
		/**
		 * CONSTRUCTOR
		 * @param int  $pagenbr     [description]
		 * @param int  $count       Number of items used to determine the number of pages needed
		 * @param string  $pageurl     pageurl to manipulate
		 * @param string  $insertafter if there isn't a urlsegmnet with page(1,3) in it then
		 * @param string or false $ajaxdata    String with ajaxdata needed like data-focus='#ajax'
		 */
		public function __construct($pagenbr, $count, $pageurl, $insertafter, $ajaxdata = false) {
			$this->pagenbr = $pagenbr;
			$this->count = $count;
			$this->pageurl = new \Purl\Url($pageurl);
			$this->insertafter = $insertafter;
			$this->ajaxdata = $ajaxdata;
			
			$this::setup_displayonpage();
		}
		
		/* =============================================================
 		   CLASS FUNCTIONS 
 	   ============================================================ */
		/**
		 * This will take the pageurl and append the page number to it or editing the page number in the url
		 * @param  int $pagenbr 
		 * @return string         url with page number
		 */
		public function paginate($pagenbr) {
			if (strpos($this->pageurl, 'page') !== false) {
				$regex = "((page)\d{1,3})";
				$replace = ($pagenbr > 1) ? $replace = "page".$pagenbr : "";
				$newurl = preg_replace($regex, $replace, $this->pageurl);
			} else {
				$this->insertafter = str_replace('/', '', $this->insertafter)."/";
				$regex = "(($this->insertafter))";
				$replace = ($pagenbr > 1) ? $this->insertafter."page".$pagenbr."/" : $replace = $this->insertafter;
				$newurl = preg_replace($regex, $replace, $this->pageurl);
			}
			return $newurl;
		}
		
		/** 
		 * Takes the $this->ajaxdata string and formats it for contento
		 * @return string pipe delimited representation of the ajax data ex data-focus=#this|data-loadinto=
		 */
		public function generate_ajaxdataforcontento() {
			return str_replace(' ', '|', str_replace("'", "", str_replace('"', '', $this->ajaxdata)));
		}
		
		/**
		 * Creates the dropdown to show how many items are on a page
		 * @return string html select
		 */
		public function generate_showonpage() {
			$url = new \Purl\Url($this->pageurl);
			$url->query->remove('display');
			$href = $url->getUrl();
			$ajaxdata = $this->generate_ajaxdataforcontento();
			$bootstrap = new Contento();
			
			$form = $bootstrap->open('div', 'class=form-group');
			$form .= $bootstrap->label('', 'Results Per Page') . '&nbsp; &nbsp;';
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
			
			$ajaxload = $this->ajaxdata ? 'ajax-load' : '';
			return $bootstrap->form("action=$href|method=get|class=form-inline results-per-page-form $ajaxload|$ajaxdata", $form);
		}
		
		/**
		 * Creates the pagination navigation
		 * @return string html of the navigation through pages
		 */
		public function __toString() {
			$bootstrap = new Contento();
			$list = '';
			$totalpages = ceil($this->count / wire('session')->display); 
			if ($this->pagenbr == 1) {
				$link = $bootstrap->openandclose('a', 'href=#|aria-label=Previous', '<span aria-hidden="true">&laquo;</span>');
				$list .= $bootstrap->openandclose('li', 'class=disabled', $link);
			} else {
				$href = $this->paginate($this->pagenbr - 1);
				$ajaxdetails = (!empty($this->ajaxdata)) ? "class=load-link|".$this->generate_ajaxdataforcontento() : '';
				$link = $bootstrap->openandclose('a', "href=$href|aria-label=Previous|$ajaxdetails", '<span aria-hidden="true">&laquo;</span>');
				$list .= $bootstrap->openandclose('li', '', $link);
			}
			
			for ($i = ($this->pagenbr - 3); $i < ($this->pagenbr + 4); $i++) {
				if ($i > 0) {
					if ($this->pagenbr == $i) {
						$href = $this->paginate($i);
						$ajaxdetails = (!empty($this->ajaxdata)) ? "class=load-link|".$this->generate_ajaxdataforcontento() : '';
						$link = $bootstrap->a("href=$href|$ajaxdetails", $i);
						$list .= $bootstrap->li('class=active', $link);
					} elseif ($i < ($totalpages + 1)) {
						$href = $this->paginate($i);
						$ajaxdetails = (!empty($this->ajaxdata)) ? "class=load-link|".$this->generate_ajaxdataforcontento() : '';
						$link = $bootstrap->a("href=$href|$ajaxdetails", $i);
						$list .= $bootstrap->li('', $link);
					}
				}
			}
			
			if ($this->pagenbr == $totalpages) {
				$link = $bootstrap->a('href=#|aria-label=Next', '<span aria-hidden="true">&raquo;</span>');
				$list .= $bootstrap->li('class=disabled', $link);
			} else {
				$href = $this->paginate($this->pagenbr + 1);
				$ajaxdetails = (!empty($this->ajaxdata)) ? "class=load-link|".$this->generate_ajaxdataforcontento() : '';
				$link = $bootstrap->a("href=$href|aria-label=Next|$ajaxdetails", '<span aria-hidden="true">&raquo;</span>');
				$list .= $bootstrap->li('', $link);
			}
			$ul = $bootstrap->ul('class=pagination', $list);
			return $bootstrap->nav('class=text-center', $ul);
		}
		
		/* =============================================================
 		   STATIC FUNCTIONS 
 	   ============================================================ */
	   /**
	    * Find what page number the $url is on
	    * @param  PurlUrl $url 
	    * @return int      page number
	    */
		public static function generate_pagenbr(\Purl\Url $url) {
	        if (preg_match("((page)\d{1,3})", $url->path, $matches)) {
	            return str_replace('page', '', $matches[0]);
	        } else {
	            return 1;
	        }
	    }
		
		/**
 	    * Initializes the wire('session')->display value;
 	    */
 		public static function setup_displayonpage() {
 			if (wire('input')->get->display) {
 				wire('session')->display = wire('input')->get->text('display');
 			} else {
 				if (!wire('session')->display) {
 					wire('session')->display = wire('config')->showonpage;
 				}
 			}
 		}
	}
