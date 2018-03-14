<?php 
	use ProcessWire\Wire;
	
	abstract class OrderPanel extends OrderDisplay {
		public $focus;
		public $loadinto;
		public $ajaxdata;
		public $paginationinsertafter;
		public $throughajax;
		public $collapse = 'collapse';
		public $tablesorter; // Will be instatnce of TablePageSorter
		public $pagenbr;
		public $activeID = false;
		public $count;
		public $filters = false; // Will be instance of array
		public $filterable;
		public $paneltype;
		
		public function __construct($sessionID, \Purl\Url $pageurl, $modal, $loadinto, $ajax) {
			parent::__construct($sessionID, $pageurl, $modal);
			$this->loadinto = $this->focus = $loadinto;
			$this->ajaxdata = 'data-loadinto="'.$this->loadinto.'" data-focus="'.$this->focus.'"';
			$this->tablesorter = new TablePageSorter($this->pageurl->query->get('orderby'));
			
			if ($ajax) {
				$this->collapse = '';
			} else {
				$this->collapse = 'collapse';
			}
		}
		
		/* =============================================================
			Class Functions
		============================================================ */
		public function generate_pagenumberdescription() {
			return ($this->pagenbr > 1) ? "Page $this->pagenbr" : '';
		}
		
		public function generate_shiptopopover(Order $order) {
			$bootstrap = new Contento();
			$address = $order->shipaddress.'<br>';
			$address .= (!empty($order->shipaddress2)) ? $order->shipaddress2."<br>" : '';
			$address .= $order->shipcity.", ". $order->shipstate.' ' . $order->shipzip;
			$attr = "tabindex=0|role=button|class=btn btn-default bordered btn-sm|data-toggle=popover";
			$attr .= "|data-placement=top|data-trigger=focus|data-html=true|title=Ship-To Address|data-content=$address";
			return $bootstrap->openandclose('a', $attr, '<b>?</b>');
		}
		
		/* =============================================================
			OrderPanelInterface Functions
		============================================================ */
		public function generate_clearsearchlink() {
			$bootstrap = new Contento();
			$href = $this->generate_loadurl();
			$icon = $bootstrap->createicon('fa fa-search-minus');
			$ajaxdata = $this->generate_ajaxdataforcontento();
			return $bootstrap->openandclose('a', "href=$href|class=generate-load-link btn btn-warning btn-block|$ajaxdata", "Clear Search $icon");
		}
		
		public function generate_clearsortlink() {
			$bootstrap = new Contento();
			$href = $this->generate_clearsorturl();
			$ajaxdata = $this->generate_ajaxdataforcontento();
			return $bootstrap->openandclose('a', "href=$href|class=btn btn-warning btn-sm load-link|$ajaxdata", '(Clear Sort)');
		}
		
		public function generate_clearsorturl() {
			$url = new \Purl\Url($this->pageurl->getUrl());
			$url->query->remove("orderby");
			return $url->getUrl();
		}
		
		public function generate_loadlink() {
			$bootstrap = new Contento();
			$href = $this->generate_loadurl();
			$ajaxdata = $this->generate_ajaxdataforcontento();
			return $bootstrap->openandclose('a', "href=$href|class=generate-load-link|$ajaxdata", "Load Orders");
		}

		public function generate_tablesortbyurl($column) {
			$url = new \Purl\Url($this->pageurl->getUrl());
			$url->query->set("orderby", "$column-".$this->tablesorter->generate_columnsortingrule($column));
			return $url->getUrl();
		}
		
		public function generate_ajaxdataforcontento() {
			return str_replace(' ', '|', str_replace("'", "", str_replace('"', '', $this->ajaxdata)));
		}
		
		/**
		 * Looks through the $input->get for properties that have the same name
		 * as filterable properties, then we populate $this->filter with the key and value
		 * @param  ProcessWire\WireInput $input Use the get property to get at the $_GET[] variables
		 */
		public function generate_filter(ProcessWire\WireInput $input) {
			if (!$input->get->filter) {
				$this->filters = false;
				return;
			} else {
				$this->filters = array();
				foreach ($this->filterable as $filter => $type) {
					if (!empty($input->get->$filter)) {
						if (!is_array($input->get->$filter)) {
							$value = $input->get->text($filter);
							$this->filters[$filter] = explode('|', $value);
						} else {
							$this->filters[$filter] = $input->get->$filter;
						}
					} elseif (is_array($input->get->$filter)) {
						if (strlen($input->get->$filter[0])) {
							$this->filters[$filter] = $input->get->$filter;
						}
					}
				}
			}
		}
		
		public function get_filtervalue($filtername, $index = 0) {
			if (empty($this->filters)) return '';
			if (isset($this->filters[$filtername])) {
				return (isset($this->filters[$filtername][$index])) ? $this->filters[$filtername][$index] : '';
			}
			return '';
		}
		
		public function has_filtervalue($filtername, $value) {
			if (empty($this->filters)) return false;
			return (isset($this->filters[$filtername])) ? in_array($value, $this->filters[$filtername]) : false;
		}
		
		public function generate_filterdescription() {
			if (empty($this->filters)) return '';
			$desc = 'Searching '.$this->generate_paneltypedescription().' with';
			
			foreach ($this->filters as $filter => $value) {
				$desc .= " " . QueryBuilder::generate_filterdescription($filter, $value, $this->filterable);
			}
			return $desc;
		}
		
		public function generate_paneltypedescription() {
			return ucwords(str_replace('-', ' ', $this->paneltype.'s'));
		}
	}
