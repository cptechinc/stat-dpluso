<?php
	
	class UserAction {
		public $id;
		public $datecreated;
		public $actiontype;
		public $actionsubtype;
		public $duedate;
		public $createdby;
		public $assignedto;
		public $assignedby;
		public $title;
		public $textbody;
		public $reflectnote;
		public $completed;
		public $datecompleted;
		public $dateupdated;
		public $customerlink;
		public $shiptolink;
		public $contactlink;
		public $salesorderlink;
		public $quotelink;
		public $notelink;
		public $tasklink;
		public $actionlink;
		public $rescheduledlink;

		public $actionlineage = array();

		public $hascustomerlink = false;
		public $hasshiptolink = false;
		public $hascontactlink = false;
		public $hasorderlink = false;
		public $hasquotelink = false;
		public $hasnotelink = false;
		public $hastasklink = false;
		public $hasactionlink = false;
		public $regardinglink = false;
		public $isoverdue = false;
		public $hascompleted = false;
		public $isrescheduled = false;


		public function __construct() {
			if (!empty($this->customerlink)) { $this->hascustomerlink = true; }
			if (!empty($this->shiptolink)) { $this->hasshiptolink = true; }
			if (!empty($this->contactlink)) { $this->hascontactlink = true; }
			if (!empty($this->salesorderlink)) { $this->hasorderlink = true; }
			if (!empty($this->quotelink)) { $this->hasquotelink = true; }
			if (!empty($this->actionlink)) { $this->hasactionlink = true; }
			if (!empty($this->notelink)) { $this->hasnotelink = true; }
			if (!empty($this->tasklink)) { $this->hastasklink = true; }

			if ($this->completed == 'Y') {
				$this->hascompleted = true;
			} elseif ($this->completed == 'R') {
				$this->isrescheduled = true;
			}

			//$this->generatetasklineage();
			if ($this->actiontype == 'task') {
				if (strtotime($this->duedate) < strtotime("now") && (!$this->hascompleted) ) { $this->isoverdue = true;}
			}

			$this->defineregardinglink();

			//$contact = getcustcontact($this->customerlink, $this->shiptolink, $this->contactlink, false);

		}

		public static function blankuseraction(array $actionlinks) {
			$action = new UserAction();
			$action->actiontype = $actionlinks['actiontype'];
			$action->customerlink = $actionlinks['customerlink'];
			$action->shiptolink = $actionlinks['shiptolink'];
			$action->contactlink = $actionlinks['contactlink'];
			$action->salesorderlink = $actionlinks['salesorderlink'];
			$action->quotelink = $actionlinks['quotelink'];
			$action->notelink = $actionlinks['notelink'];
			$action->tasklink = $actionlinks['tasklink'];
			$action->actionlink = $actionlinks['actionlink'];

			if ($action->customerlink != '') { $action->hascustomerlink = true; }
			if ($action->shiptolink != '') { $action->hasshiptolink = true; }
			if ($action->contactlink != '') { $action->hascontactlink = true; }
			if ($action->salesorderlink != '') { $action->hasorderlink = true; }
			if ($action->quotelink != '') { $action->hasquotelink = true; }
			if ($action->actionlink != '') { $action->hasactionlink = true; }
			//if ($action->notelink != '') { $action->hasnotelink = true; }
			//if ($action->tasklink != '') { $action->hastasklink = true; }

			$action->defineregardinglink();
			return $action;
		}


		public static function getlinkarray() {
			return array(
				'id' => false,
				'datecreated' => false,
				'actiontype' => false,
				'actionsubtype' => false,
				'duedate' => false,
				'createdby' => false,
				'assignedto' => false,
				'assignedby' => false,
				'title' => false,
				'textbody' => false,
				'completed' => false,
				'datecompleted' => false,
				'dateupdated' => false,
				'customerlink' => false,
				'shiptolink' => false,
				'contactlink' => false,
				'salesorderlink' => false,
				'quotelink' => false,
				'notelink' => false,
				'tasklink' => false,
				'actionlink' => false,
			);
		}

		function defineregardinglink() {
			$regardinglink = '';
			if ($this->customerlink != '') {
				//$this->hascustomerlink = true; $regardinglink = 'CustID: '. $this->customerlink;
				$this->hascustomerlink = true; $regardinglink = 'CustID: '. get_customername($this->customerlink);

			}
			if ($this->shiptolink != '') {
				//$this->hasshiptolink = true; $regardinglink .= ' ShipID: '. $this->shiptolink;
				$this->hasshiptolink = true; $regardinglink .= ' ShipID: '. get_shiptoname($this->customerlink, $this->shiptolink, false);
			}
			if ($this->contactlink != '') { $this->hascontactlink = true; $regardinglink .= ' Contact: '. $this->contactlink; }
			if ($this->salesorderlink != '') { $this->hasorderlink = true; $regardinglink = 'Order #' . $this->salesorderlink; }
			if ($this->quotelink != '') { $this->hasquotelink = true; $regardinglink = 'Quote #' . $this->quotelink; }
			if ($this->actionlink != '') { $this->hasactionlink = true; $regardinglink = 'ActionID: ' . $this->actionlink; }
			$this->regardinglink = $regardinglink;
			return $regardinglink;
		}

		function createmessage($message) {
			$regex = '/({replace})/i';
			$replace = "";

			if ($this->hascustomerlink) {
				$replace = get_customername($this->customerlink)." ($this->customerlink)";
			}

			if ($this->hasshiptolink) {
				$replace .= " Shipto: " . get_shiptoname($this->customerlink, $this->shiptolink, false)." ($this->shiptolink)";
			}

			if ($this->hascontactlink) {
				$replace .= " Contact: " . $this->contactlink;
			}

			if ($this->hasorderlink) {
				$replace .= " Sales Order #" . $this->salesorderlink;
			} elseif ($this->hasquotelink) {
				$replace .= " Quote #" . $this->quotelink;
			}

			if ($this->hastasklink) {
				$replace .= " Task #" . $this->tasklink;
			} elseif ($this->hasnotelink) {
				$replace .= " CRM Note #" . $this->notelink;
			} elseif ($this->hasactionlink) {
				$replace .= " Action #" . $this->tasklink;
			}
			$replace = trim($replace);

			if (empty($replace)) {
				if (empty($this->assignedto)) {
					$replace = 'Yourself ';
				} else {
					if ($this->assignedto != wire('user')->loginid) {
						$replace = 'User: ' . wire('user')->loginid;
					} else {
						$replace = 'Yourself ';
					}
				}
			}
			return preg_replace($regex, $replace, $message);
		}

		public function generateviewactionurl() {
			$action = $this->actiontype."s";
			return wire('config')->pages->actions."$action/load/?id=".$this->id;
		}

		public function generatecompletionurl($complete) {
			$action = $this->actiontype."s";
			return wire('config')->pages->actions."$action/update/completion/?id=".$this->id."&complete=".$complete; //true or false
		}

		public function generaterescheduleurl() {
			$action = $this->actiontype."s";
			return wire('config')->pages->actions."$action/update/reschedule/?id=".$this->id;
		}

		public function generateviewactionjson() {
			return wire('config')->pages->ajax."json/load-action/?id=".$this->id;
		}

		public function generatecustomerurl() {
			return wire('config')->pages->customer."redir/?action=load-customer&custID=".urlencode($this->customerlink);
		}

		public function generateshiptourl() {
			return $this->generatecustomerurl() . "&shipID=".urlencode($this->shiptolink);
		}

		public function generatecontacturl() {
			if ($this->hasshiptolink) {
				return wire('config')->pages->customer.urlencode($this->customerlink) . "/shipto-".urlencode($this->shiptolink)."/contacts/?id=".urlencode($this->contactlink);
			} else {
				return wire('config')->pages->customer.urlencode($this->customerlink)."/contacts/?id=".urlencode($this->contactlink);
			}
		}

		public function displayduedate($format) {
			switch ($this->actiontype) {
				case 'task':
					return date($format, strtotime($this->duedate));
					break;
				case 'note':
					return 'N/A';
					break;
			}
		}

		public function getactiontypepage() {
			if ($this->actiontype == 'all') {
				return $this->actiontype;
			} else {
				return $this->actiontype.'s';
			}
		}

		public function displaystatusdescription() {
			switch (trim($this->completed)) {
				case 'R':
					return 'rescheduled';
				case 'Y':
					return 'completed';
				default:
					return 'incomplete';
			}
		}

		public function getactionsubtypedescription() {
			switch ($this->actiontype) {
				case 'task':
					$subpage = wire('pages')->get("/activity/".$this->getactiontypepage()."/".$this->actionsubtype."/");
					return $subpage->subtypeicon.' '.$subpage->actionsubtypelabel;
					break;
				case 'note':
					$subpage = wire('pages')->get("/activity/".$this->getactiontypepage()."/".$this->actionsubtype."/");
					return $subpage->subtypeicon.' '.$subpage->actionsubtypelabel;
					break;
				case 'action':
					$subpage = wire('pages')->get("/activity/".$this->getactiontypepage()."/".$this->actionsubtype."/");
					return $subpage->subtypeicon.' '.$subpage->actionsubtypelabel;
					break;
				default:
					return '';
					break;
			}
		}

		public function getactionlineage() {
			if ($this->actionlink) {
				$parentid = getparentaction($this->id, false);
				while ($parentid != '') {
					$this->actionlineage[] = $parentid;
					$parentid = getparentaction($parentid, false);
				}
			}
			return $this->actionlineage;
		}

	}
