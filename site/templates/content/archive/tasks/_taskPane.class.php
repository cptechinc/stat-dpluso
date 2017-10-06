<?php

    class TaskPanel {
		public $type = 'cust';
		public $loadinto;
		public $focus;
		public $data;
		public $modal;
		public $collapse;
		public $completed = false;
        public $rescheduled = false;

        public $taskstatus = 'N';

		public $custID;
		public $shipID;
		public $contactID;

        public $qnbr;

        public $links = array('assignedto' => false, 'customerlink' => false, 'shiptolink' => false, 'contactlink' => false);

        public $statuses = array('Y' => 'Completed', 'N' => 'Not Completed', 'R' => 'Rescheduled');

        public $count = 0;

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

		function setupcontactpanel($custID, $shipID, $contactID) {
			$this->setupcustomerpanel($custID, $shipID);
			$this->contactID = $contactID;
		}

        function setupquotepanel($qnbr) {
            $this->qnbr = $qnbr;
        }

		function setupcompletetasks() {
            $this->taskstatus = 'Y';
			$this->completed = true;
		}

        function setuprescheduledtasks() {
            $this->taskstatus = 'R';
			$this->rescheduled = true;
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


		function getaddtasklink() {
			$link = '';
			switch ($this->type) {
				case 'cust':
					$link = wire('config')->pages->tasks."add/new/?custID=".urlencode($this->custID);
					if ($this->shipID != '') {$link .= "&shipID=".urlencode($this->shipID);}
					break;
				case 'contact':
					$link = wire('config')->pages->tasks."add/new/?custID=".urlencode($this->custID);
					if ($this->shipID != '') {$link .= "&shipID=".urlencode($this->shipID);}
					$link .= "&contactID=".urlencode($this->contactID);
					break;
                case 'user':
					$link = wire('config')->pages->tasks."add/new/";
					break;
                case 'quote':
                    $link = wire('config')->pages->tasks."add/new/?qnbr=".$this->qnbr;
                    break;
			}
			return $link;
		}

		function getpanelrefreshlink() {
			$link = '';
			switch ($this->type) {
				case 'cust':
					$link = wire('config')->pages->tasks."load/list/cust/?custID=".urlencode($this->custID);
					if ($this->shipID != '') {$link .= "&shipID=".urlencode($this->shipID);}
					break;
				case 'contact':
					$link = wire('config')->pages->tasks."load/list/contact/?custID=".urlencode($this->custID);
					if ($this->shipID != '') {$link .= "&shipID=".urlencode($this->shipID);}
					$link .= "&contactID=".urlencode($this->contactID);
					break;
                case 'user':
					$link = wire('config')->pages->tasks."load/list/user/";
					break;
                case 'quote':
					$link = wire('config')->pages->tasks."load/list/quote/?qnbr=".$this->qnbr;;
					break;
			}
			return $link;
		}

		function getpanelloadtaskschedulelink() {
			$link = '';
			switch ($this->type) {
				case 'cust':
					$link = wire('config')->pages->taskschedule."load/list/?custID=".urlencode($this->custID);
					if ($this->shipID != '') {$link .= "&shipID=".urlencode($this->shipID);}
					break;
				case 'contact':
					$link = wire('config')->pages->taskschedule."load/list/?custID=".urlencode($this->custID);
					if ($this->shipID != '') {$link .= "&shipID=".urlencode($this->shipID);}
					$link .= "&contactID=".urlencode($this->contactID);
					break;
                case 'user':
					$link = wire('config')->pages->taskschedule."load/list/";
					break;
                case 'quote':
					$link = wire('config')->pages->taskschedule."load/list/?qnbr=".$this->qnbr;
					break;
			}
			return $link;
		}

		function getpanelnewtaskschedulelink() {
			$link = '';
			switch ($this->type) {
				case 'cust':
					$link = wire('config')->pages->taskschedule."add/new/?custID=".urlencode($this->custID);
					if ($this->shipID != '') {$link .= "&shipID=".urlencode($this->shipID);}
					break;
				case 'cust':
					$link = wire('config')->pages->taskschedule."add/new/?custID=".urlencode($this->custID);
					if ($this->shipID != '') {$link .= "&shipID=".urlencode($this->shipID);}
					$link .= "&contactID=".urlencode($this->contactID);
					break;
                case 'cust':
					$link = wire('config')->pages->taskschedule."add/new/";
					break;
                case 'quote':
					$link = wire('config')->pages->taskschedule."add/new/?qnbr=".$this->qnbr;
					break;

			}
			return $link;
		}

		function getpaneladdtaskschedulelink() {
			$link = '';
			switch ($this->type) {
				case 'cust':
					$link = wire('config')->pages->taskschedule."add/?custID=".urlencode($this->custID);
					if ($this->shipID != '') {$link .= "&shipID=".urlencode($this->shipID);}
					break;
				case 'contact':
					$link = wire('config')->pages->taskschedule."add/?custID=".urlencode($this->custID);
					if ($this->shipID != '') {$link .= "&shipID=".urlencode($this->shipID);}
					$link .= "&contactID=".urlencode($this->contactID);
					break;
                case 'user':
					$link = wire('config')->pages->taskschedule."add/";
					break;
                case 'quote':
					$link = wire('config')->pages->taskschedule."add/?qnbr=".$this->qnbr;
					break;
			}
			return $link;
		}

        function needsaddtasklink() {
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

		function getloadtasklink($noteid) {
			return wire('config')->pages->tasks."load/?id=".$noteid;
		}

		function getpaneltitle() {
			switch ($this->type) {
				case 'cust':
					return 'Customer Tasks';
					break;
				case 'contact':
					return 'Contact Tasks';
					break;
                case 'user':
					return 'Your Tasks';
					break;
                case 'quote':
					return 'Quote '.$this->qnbr.' Tasks';
					break;
			}
		}

		function getinsertafter() {
			switch ($this->type) {
				case 'cust':
					return 'cust/';
					break;
				case 'contact':
					return 'contact/';
					break;
                case 'user':
					return 'user/';
					break;
                case 'quote':
                    return 'quote/';
                    break;
			}
		}

        function buildarraylinks(array $links) {
            $this->links['assignedto'] = wire('user')->loginid;
            if ($this->hascustomer()) { $this->links['customerlink'] = $this->custID; }
            if ($this->hasshipto()) { $this->links['shiptolink'] = $this->shipID; }
            if ($this->hascontact()){  $this->links['contactlink'] = $this->contactID; }
        }

        function getarraylinks() {
            $this->buildarraylinks();
            return $this->links;
        }
    }


 ?>
