<?php     
	class TablePageSorter {
		public $orderby;
		public $sortrule;
		public $orderbystring;
		
		public function __construct($orderbystring) {
			$this->orderbystring = $orderbystring;
			if (!empty($orderbystring)) { // $orderbystring looks like orderno-ASC
				$orderby_array = explode('-', $orderbystring);
				$this->orderby = $orderby_array[0];
				if (!empty($orderby_array[0])) { 
					$this->sortrule = $orderby_array[1];
				} else {
					$this->sortrule = "ASC";
				}
				// TODO: add logic for adding orderby to url
				// $sortlinkaddon = "&orderby=".$this->orderbystring;
			} else {
				$this->orderby = false;
				$this->sortrule = false;
				// TODO: add logic for adding orderby to url
				// $sortlinkaddon = '';
			}
		}
		
		public function generate_sortsymbol($column) {
			$symbol = "";
			if ($this->orderby == $column) {
				if ($this->sortrule == "ASC") {
					$symbol = "<span class='glyphicon glyphicon-arrow-up'></span>";
				} else {
					$symbol = "<span class='glyphicon glyphicon-arrow-down'></span>";
				}
			}
			return $symbol;
		}
		
		public function generate_columnsortingrule($column) {
			if ($this->orderby != $column || $this->sortrule == false) {
				$sortrule = "ASC";
			} else {
				switch ($this->sortrule) {
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
?>
