<?php 

    class QuotePanel {
        public $type = 'cust';
        public $modal;
        public $focus;
        public $throughajax;
        public $collapse = 'collapse';
        public $orderby;
        public $orderbystring;
        public $sortrule;
        public $nextorder;
        public $custID;
        public $shipID;
        public $count;
        public $quotes = array();
        
        public function __construct($type, $orderbystring, $modal, $loadinto, $throughajax) {
        	$this->type = $type;
            $this->orderbystring = $orderbystring;
        	
        	if ($throughajax) {
        		$this->collapse = '';
        	} else {
        		$this->collapse = 'collapse';
        	}
        	
        	$this->loadinto = $this->focus = $loadinto;
        	
        	$this->data = 'data-loadinto="'.$this->loadinto.'" data-focus="'.$this->focus.'"';
        }

        public function setupcustomerpanel($custID, $shipID) {
        	$this->custID = $custID;
        	$this->shipID = $shipID;
        }
        
        public function setuporderby() {
            if (!empty($this->orderbystring)) {
        		$orderby_array = explode('-', $this->orderbystring);
        		$this->orderby = $orderby_array[0];
        		if ($orderby_array[0] != '') {
        			$this->sortrule = $orderby_array[1];
        			$this->nextorder = get_sorting_rule($orderby, $this->sortrule, $this->orderby);
        		} else {
        			$this->sortrule = "ASC";
        			$this->nextorder = "DESC";
        		}
                // TODO: add logic for adding orderby to url
        		// $sortlinkaddon = "&orderby=".$this->orderbystring;
        	} else {
        		$this->orderby = false;
        		$this->sortrule = false;
        		$this->nextorder = "ASC";
                // TODO: add logic for adding orderby to url
        		// $sortlinkaddon = '';
        	}
        }
        
        public function createsalesordericonlegend() {
            $legendcontent = "<span class='glyphicon glyphicon-shopping-cart' title='re-order'></span> = Re-order whole order <br>";
        	$legendcontent .= "<span class='glyphicon glyphicon-folder-open' title='Click to view Documents'></span> &nbsp; = Documents <br>";
        	$legendcontent .= "<span class='glyphicon glyphicon-plane hover' title='Click to view Tracking'></span> = Tracking <br>";
        	$legendcontent .= "<span class='glyphicon glyphicon-list-alt' title='View notes'></span> = Notes <br>";
        	$legendcontent .= "<span class='glyphicon glyphicon-pencil' title='Edit this Order'></span> = Edit Order<br>";
        	$legendiconcontent = 'class="btn btn-sm btn-info" role="button" data-toggle="popover" data-placement="bottom" data-trigger="focus" data-html="true" title="Icon Definition"';
            
            // TODO: look at legend content
            return $legendcontent;
        }
        
        public function get_symbols($orderby, $match, $page_orderby) {
    		$symbol = "";
    		if ($orderby == $match) {
    			if ($page_orderby == "ASC") {
    				$symbol = "&#x25B2;";
    				$symbol = "<span class='glyphicon glyphicon-arrow-up'></span>";
    			} else {
    				$symbol = "&#x25BC;";
    				$symbol = "<span class='glyphicon glyphicon-arrow-down'></span>";
    			}
    		}
    		return $symbol;
    	}
        
        // TODO: look at function
        public function get_sorting_rule($orderingby, $sort, $orderby) {
    		if ($orderingby != $orderby || $sort == false) {
    			$sortrule = "ASC";
    		} else {
    			switch ($sort) {
    				case 'ASC':
    					$sortrule = 'DESC';
    					break;
    				case 'DESC':
    					$sortrule = 'ASC';
    					break;
    			}
    		}
    		return $sortrule;
    	}
    }
