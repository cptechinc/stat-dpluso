<?php

    class NotePanel {
		public $type = 'cust';
		public $loadinto;
		public $focus;
		public $data;
		public $modal;
		public $collapse;

		public $custID;
		public $shipID;
        public $contactID;

        public $qnbr;
        public $count = 0;

        public $links = array('writtenby' => false, 'customerlink' => false, 'shiptolink' => false, 'contactlink' => false, 'quotelink' => false);

		public function __construct($type, $loadinto, $focus, $modal, $throughajax) {
	   		$this->type = $type;
			$this->loadinto = $loadinto;
			$this->focus = $focus;
			$this->modal = $modal;
			$this->data = 'data-loadinto="'.$this->loadinto.'" data-focus="'.$this->focus.'"';
			if ($throughajax) {
				$this->collapse = '';
			} else {
				$this->collapse = 'collapse';
			}
        }

		function setupcustomerpanel($custID, $shipID) {
			$this->custID = $custID;
			$this->shipID = $shipID;
		}

        function setupquotepanel($qnbr) {
			$this->qnbr = $qnbr;
		}

        function hascustomer() {
            return ($this->custID) ? true : false;
        }

        function hasshipto() {
            return ($this->shipID) ? true : false;
        }

        function hascontact() {
            return ($this->contactID) ? true : false;
        }

        function hasquote() {
            return ($this->qnbr) ? true : false;
        }

		function getaddnotelink() {
			$link = '';
			switch ($this->type) {
				case 'cust':
					$link = wire('config')->pages->notes."add/new/?custID=".urlencode($this->custID);
					if ($this->shipID != '') {$link .= "&shipID=".urlencode($this->shipID);}
					break;
                case 'quote':
					$link = wire('config')->pages->notes."add/new/?qnbr=".$this->qnbr;
					break;

			}
			return $link;
		}

        function needsaddnotelink() {
            $needsadd = false;
            switch ($this->type) {
				case 'cust':
					$needsadd = true;
					break;
				case 'contact':
					$needsadd = true;
					break;
                case 'user':
					$needsadd = false;
					break;
                case 'quote':
					$needsadd = true;
					break;
			}
            return $needsadd;
        }

		function getpanelrefreshlink() {
			$link = '';
			switch ($this->type) {
				case 'cust':
					$link = wire('config')->pages->notes."load/list/cust/?custID=".urlencode($this->custID);
					if ($this->hasshipto()) {$link .= "&shipID=".urlencode($this->shipID);}
					break;
                case 'user':
                    $link = wire('config')->pages->notes."load/list/user/";
                    break;
                case 'quote':
					$link = wire('config')->pages->notes."load/list/quote/?qnbr=".$this->qnbr;
					break;
			}
			return $link;
		}

		function getinsertafter() {
			switch ($this->type) {
				case 'cust':
                    return 'cust/';
					break;
                case 'user':
					return 'user/';
					break;
                case 'quote':
					return 'quote/';
					break;
			}
		}

		function getloadnotelink($noteid) {
			return wire('config')->pages->notes."load/?id=".$noteid;
		}

		function getpaneltitle() {
			switch ($this->type) {
				case 'cust':
					return 'Customer Notes';
					break;
                case 'user':
                    return 'Your Notes';
                    break;
                case 'quote':
                    return 'Notes for Quote #' . $this->qnbr;
                    break;
			}
		}

        function buildarraylinks() {
            $this->links['writtenby'] = wire('user')->loginid;
            if ($this->hascustomer()) { $this->links['customerlink'] = $this->custID; }
            if ($this->hasshipto()) { $this->links['shiptolink'] = $this->shipID; }
            if ($this->hascontact()) {  $this->links['contactlink'] = $this->contactID; }
            if ($this->hasquote()) {  $this->links['quotelink'] = $this->qnbr; }
        }

        function getarraylinks() {
            $this->buildarraylinks();
            return $this->links;
        }


    }


 ?>
