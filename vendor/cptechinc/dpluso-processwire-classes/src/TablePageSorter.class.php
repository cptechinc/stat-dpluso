<?php     
	class TablePageSorter {
		public $orderby;
		public $sortrule;
		public $orderbystring;
		
		/* =============================================================
 		   CONSTRUCTOR FUNCTIONS 
 	   ============================================================ */
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
		
		/* =============================================================
 		   CLASS FUNCTIONS 
 	   ============================================================ */
	   /**
	    * Returns an html string of the symbole to use based on the sort rule
	    * @param  string $column column to sort by if it matches the orderby column then the symbol will be the opposite of the current
	    * @return htmlstring         
	    */
		public function generate_sortsymbol($column) {
			$symbol = "";
			if ($this->orderby == $column) {
				if ($this->sortrule == "ASC") {
					$symbol = "<span class='glyphicon glyphicon-arrow-up' aria-label='Ascending'></span>";
				} else {
					$symbol = "<span class='glyphicon glyphicon-arrow-down' aria-label='descending'></span>";
				}
			}
			return $symbol;
		}
		
		/**
		 * Takes the provided column and determines the sort rule for it based upon the current
		 * sort rule and the current column being sorted by
		 * @param  string $column 
		 * @return string        ASC|DESC
		 */
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
