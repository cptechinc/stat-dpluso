<?php 
    class UserActionDisplay {
        public $modal = '#ajax-modal';
        public $pageurl = false;
        public $userID;
        
		/* =============================================================
			CONSTRUCTOR FUNCTIONS
		============================================================ */
        public function __construct(\Purl\Url $pageurl) {
            $this->pageurl = new \Purl\Url($pageurl->getUrl());
            $this->userID = Processwire\wire('user')->loginid;
        }
        
		/* =============================================================
			CLASS FUNCTIONS
		============================================================ */
		/**
		 * Returns URL location where that action can be displayed from
		 * @param  UserAction $action UserAcion to use the Action Type and ID
		 * @return string         URL where action can be displayed from
		 */
		public function generate_viewactionurl(UserAction $action) {
			return Processwire\wire('config')->pages->actions."$action->actiontype/load/?id=".$action->id;
		}

		/**
		 * Returns URL to the Edit Action Page
		 * @param  UserAction $action UserAcion to use the Action Type and ID
		 * @return string         URL where action can be edited from
		 */
		public function generate_editactionurl(UserAction $action) {
			return Processwire\wire('config')->pages->actions."$action->actiontype/edit/?id=".$action->id;
		}
		
		/**
		 * Returns URL where the User Action can mark itself as compelete
		 * @param  UserAction $action   $action UserAcion to use the Action Type and ID
		 * @param  string     $complete Y | N
		 * @return string               URL
		 */
		public function generate_completionurl(UserAction $action, $complete) {
			return Processwire\wire('config')->pages->actions."$action->actiontype/update/completion/?id=".$action->id."&complete=".$complete; //true or false
		}
		
		/**
		 * Returns URL where the User Action can be Rescheduled
		 * @param  UserAction $action $action UserAcion to use the Action Type and ID
		 * @return string            URL
		 */
		public function generate_rescheduleurl(UserAction $action) {
			return Processwire\wire('config')->pages->actions."$action->actiontype/update/reschedule/?id=".$action->id;
		}
		
		/**
		 * Returns URL where the Action can be viewed in JSON format
		 * @param  UserAction $action $action UserAcion to use the Action ID
		 * @return string            URL
		 */
		public function generate_viewactionjsonurl(UserAction $action) {
			return Processwire\wire('config')->pages->ajax."json/load-action/?id=".$action->id;
		}

		/**
		 * Takes the UserAction customerlink and shiptolink makes a Customer object and then generates
		 * the link to the customer page
		 * @param  UserAction $action Uses customerlink and shiptolink to generate Customer object
		 * @return string             URL to load the customer page
		 */
		public function generate_ciloadurl(UserAction $action) {
			$customer = Customer::load($action->customerlink, $action->shiptolink);
			return $customer->generate_customerurl();
		}
		
		/**
		 * Takes the UserAction customerlink makes a Customer object and then generates
		 * the link to the customer page
		 * @param  UserAction $action Uses customerlink to generate Customer object
		 * @return string             URL to load the customer page
		 */
		public function generate_customerurl(UserAction $action) {
			$customer = Customer::load($action->customerlink);
			return $customer->generate_customerurl();
		}
		
		/**
		 * Takes the UserAction customerlink and shiptolink makes a Customer object and then generates
		 * the link to the customer page
		 * @param  UserAction $action Uses customerlink and shiptolink to generate Customer object
		 * @return string             URL to load the customer page
		 */
		public function generate_shiptourl(UserAction $action) {
			$customer = Customer::load($action->customerlink, $action->shiptolink);
			return $customer->generate_customerurl();
		}
		
		/**
		 * Returns the URL to the contact page that is linked to this UserAction
		 * @param  UserAction $action customerlink, shiptolink, and contactlink to generate Contact object
		 * @return string             Contact page URL
		 */
		public function generate_contacturl(UserAction $action) {
			$customer = Contact::load($action->customerlink, $action->shiptolink, $action->contactlink);
			return $customer->generate_contacturl();
		}
        
		/**
		 * Returns link to click to view the UserAction
		 * @param  UserAction $action Uses it to generate the URL to view it
		 * @return string             HTML link
		 */
        public function generate_viewactionlink(UserAction $action) {
            $bootstrap = new Contento();
            $href = $this->generate_viewactionurl($action);
            $icon = $bootstrap->createicon('material-icons md-18', '&#xE02F;');
            return $bootstrap->openandclose('a', "href=$href|role=button|class=btn btn-xs btn-primary load-action|data-modal=$this->modal|title=View Action", $icon);
        }
        
		/**
		 * Returns link to click to edit the action
		 * @param  UserAction $action Uses it to generate the URL to edit
		 * @return string             HTML link
		 */
        public function generate_editactionlink(UserAction $action) {
            $bootstrap = new Contento();
            $href = $this->generate_editactionurl($action);
            $icon = $bootstrap->createicon('glyphicon glyphicon-pencil');
            return $bootstrap->openandclose('a', "href=$href|role=button|class=btn btn-primary load-action|data-modal=$this->modal|title=Edit Action", $icon. ' Edit Action');
        }
        
		/**
		 * Returns link to click to mark task as complete
		 * @param  UserAction $task Uses it to generate the URL to mark as comeplete
		 * @return string           HTML link
		 */
        public function generate_completetasklink(UserAction $task) {
            $bootstrap = new Contento();
            $href = $this->generate_viewactionjsonurl($task);
            $icon = $bootstrap->createicon('fa fa-check-circle');
            $icon .= ' <span class="sr-only">Mark as Complete</span>';
            return $bootstrap->openandclose('a', "href=$href|role=button|class=btn btn-primary complete-action|title=Mark Task as Complete", $icon. " Complete Task");
        }
        
        public function generate_rescheduletasklink(UserAction $task) {
            $bootstrap = new Contento();
            $href = $this->generate_rescheduleurl($task);
            $icon = $bootstrap->createicon('fa fa-calendar');
            return $bootstrap->openandclose('a', "href=$href|role=button|class=btn btn-default reschedule-task", $icon. " Reschedule Task");
        }
        
        public function generate_customerpagelink(UserAction $action) {
            $bootstrap = new Contento();
            $href = $this->generate_customerurl($action);
            $icon = $bootstrap->createicon('glyphicon glyphicon-share');
            return $bootstrap->openandclose('a', "href=$href", $icon." Go to Customer Page");
        }
        
        public function generate_shiptopagelink(UserAction $action) {
            $bootstrap = new Contento();
            $href = $this->generate_customerurl($action);
            $icon = $bootstrap->createicon('glyphicon glyphicon-share');
            return $bootstrap->openandclose('a', "href=$href", $icon." Go to Shipto Page");
        }
        
        public function generate_contactpagelink(UserAction $action) {
            $bootstrap = new Contento();
            $href = $this->generate_contacturl($action);
            $icon = $bootstrap->createicon('glyphicon glyphicon-share');
            return $bootstrap->openandclose('a', "href=$href", $icon." Go to Contact Page");
        }
    }
