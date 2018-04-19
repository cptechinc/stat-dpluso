<?php
    class UserActionsPanel extends UserActionDisplay {
        public static $type = 'user';
        public $sessionID;
        public $actiontype = '';
        public $focus = '';
        public $panelid = '';
        public $panelbody = '';
        public $loadinto = '';
        public $partialid = 'actions';
        public $modal = '#ajax-modal';
        public $pageurl = false;
        
        public $ajaxdata;
        public $throughajax = false;
        public $loadintomodal = false;
        public $collapse = 'collapse';
        //public $tablesorter; // Will be instatnce of TablePageSorter
        
        public $pagenbr = 0;
        public $count = 0;
        
        public $paginator = false;
        
        public $completed = false;
        public $rescheduled = false;
        public $taskstatus = 'N';
        public $querylinks = array();
        public $taskstatuses = array('Y' => 'Completed', 'N' => 'Not Completed', 'R' => 'Rescheduled');
        public $userID;
        public $assigneduserID;
        
        /* =============================================================
 		   CONSTRUCTOR FUNCTIONS 
 	   ============================================================ */
        public function __construct($sessionID, $actiontype, \Purl\Url $pageurl, $throughajax, $isinmodal, $taskstatus = null) {
            $this->sessionID = $sessionID;
            $this->actiontype = $actiontype;
            $this->pageurl = new \Purl\Url($pageurl->getUrl());
            $this->pagenbr = Paginator::generate_pagenbr($pageurl);
            $this->partialid = ($isinmodal) ? 'actions-modal' : $this->partialid;
            $this->loadinto = '#'.$this->partialid.'-panel';
			$this->focus = '#'.$this->partialid.'-panel';
			$this->panelid = $this->partialid.'-panel';
			$this->panelbody = $this->partialid.'-div';
			$this->loadintomodal = $isinmodal;
			$this->ajaxdata = 'data-loadinto="'.$this->loadinto.'" data-focus="'.$this->focus.'"';
            $this->throughajax = $throughajax;
            $this->collapse = $throughajax ? '' : 'collapse';
            
            $this->userID = Processwire\wire('user')->loginid;
            $this->assigneduserID = Processwire\wire('user')->loginid;
            $this->setup_tasks($taskstatus);
            $this->start_querylinks();
        }
        
        /* =============================================================
 		   SETTER FUNCTIONS 
 	   ============================================================ */
        public function update_assignedtouserID($userID) {
            $this->assigneduserID = $userID;
            $this->start_querylinks();
        }
        
		public function setup_completedtasks() {
            $this->taskstatus = 'Y';
			$this->completed = true;
		}
        
        public function setup_rescheduledtasks() {
            $this->taskstatus = 'R';
			$this->rescheduled = true;
		}
        
        public function setup_tasks($status) {
            switch ($status) {
        		case 'Y':
        			$this->setup_completedtasks();
        			break;
        		case 'R':
        			$this->setup_rescheduledtasks();
        			break;
        	}
        }
        
        public function generate_databasetaskstatus() {
            if ($this->actiontype == 'tasks') {
                switch ($this->taskstatus) {
                    case 'N':
                        return ' ';
                        break;
                    default:
                        return $this->taskstatus;
                }
            } else {
                return '';
            }
        }
        
        public function start_querylinks() {
            $this->querylinks = UserAction::generate_classarray();
            $this->querylinks['assignedto'] = $this->assigneduserID;
            $this->querylinks['completed'] = $this->generate_databasetaskstatus();
            if ($this->actiontype != 'all') {
                $this->querylinks['actiontype'] = $this->actiontype;
            }
        }
        
        /* GENERATE URLS - URLS ARE THE HREF VALUE */
       public function generate_refreshurl($keepactiontype = false) { 
            $actionpath = ($keepactiontype) ? $this->actiontype : '{replace}';
            $url = new \Purl\Url($this->pageurl->getUrl());
            $url->path = Processwire\wire('config')->pages->actions.$actionpath."/load/list/";
            $url->path->add(rtrim($this->generate_insertafter(), '/'));
			$url->query->remove('id');
            if ($this->assigneduserID != $this->userID) {
                $url->query->set('assignedto', $this->assigneduserID);
            }
			if ($this->loadintomodal) { $url->query->set('modal', 'modal'); }
			return $url->getUrl();
		}
        
		function generate_addactionurl($keepactiontype = false) {
            if (Processwire\wire('config')->cptechcustomer == 'stempf') {
                $actionpath = ($this->actiontype == 'all') ? 'tasks' : $this->actiontype;
            } else {
                $actionpath = ($keepactiontype) ? $this->actiontype : '{replace}';
            }
            $url = new \Purl\Url($this->generate_refreshurl(true));
            $url->path = Processwire\wire('config')->pages->actions.$actionpath."/add/";
			return $url->getUrl();
		}
        
        public function generate_removeassigneduserIDurl() {
            $url = new \Purl\Url($this->generate_refreshurl(true));
            $url->query->remove('assignedto');
            return $url->getUrl();
        }
        
        /* =============================================================
			CLASS FUNCTIONS 
		============================================================ */
        /* = GENERATE LINKS - LINKS ARE THE HTML MARKUP FOR LINKS */
         public function generate_refreshlink() {
             $bootstrap = new Contento();
             $href = $this->generate_refreshurl(true);
             $icon = $bootstrap->createicon('material-icons md-18', '&#xE86A;');
             $ajaxdata = $this->generate_ajaxdataforcontento();
             return $bootstrap->openandclose('a', "href=$href|class=btn btn-info btn-xs load-link actions-refresh pull-right hidden-print|title=button|title=Refresh Actions|aria-label=Refresh Actions|$ajaxdata", $icon);
         }
         
         public function generate_printlink() {
             $bootstrap = new Contento();
             $href = $this->generate_refreshurl(true);
             $icon = $bootstrap->createicon('glyphicon glyphicon-print');
             return $bootstrap->openandclose('a', "href=$href|class=h3|target=_blank", $icon." View Printable");
         }
         
         function generate_addlink() {
			 if (get_class($this) == 'UserActionsPanel') return '';
             $bootstrap = new Contento();
             $href = $this->generate_addactionurl();
             $icon = $bootstrap->createicon('material-icons md-18', '&#xE146;');
             if (Dpluswire::wire('config')->cptechcustomer == 'stempf') {
                 return $bootstrap->openandclose('a', "href=$href|class=btn btn-info btn-xs load-into-modal pull-right hidden-print|data-modal=$this->modal|role=button|title=Add Action", $icon);
             }
             return $bootstrap->openandclose('a', "href=$href|class=btn btn-info btn-xs add-action pull-right hidden-print|data-modal=$this->modal|role=button|title=Add Action", $icon);
         }
         
         public function generate_removeassigneduserIDlink() {
             $bootstrap = new Contento();
             $href = $this->generate_removeassigneduserIDurl();
             $icon = $bootstrap->createicon('fa fa-user-times');
             $ajaxdata = $this->generate_ajaxdataforcontento();
             return $bootstrap->openandclose('a', "href=$href|class=btn btn-warning btn-xs load-link pull-right hidden-print|title=button|title=Return to Your Actions|aria-label=Return to Your Actions|$ajaxdata", $icon.' Remove User lookup');
         }
         
         /* CONTENT FUNCTIONS  */
        public function generate_rowclass($action) {
            if ($action->actiontype == 'tasks') {
                if ($action->is_rescheduled()) {
                    return 'bg-info';
                }
                if ($action->is_overdue()) {
                    return 'bg-warning';    
                }
                if ($action->is_completed()) {
                    return 'bg-success';
                }
            }
            return '';
        }
        
        public function generate_actionstable() {
            $actions = $this->get_actions();
             $table = false;
             switch ($this->actiontype) {
                 case 'all':
                     $table = $this->draw_allactionstable($actions);
                     break;
                case 'actions':
                     $table = $this->draw_actionstable($actions);
                    break;
                case 'notes':
                     $table = $this->draw_notestable($actions);
                    break;
                case 'tasks':
                     $table = $this->draw_taskstable($actions);
                    break;
             }
             return $table;
         }
         
         public function draw_allactionstable($actions) {
             $tb = new Table('class=table table-bordered table-condensed table-striped');
             $tb->tablesection('thead');
                 $tb->tr();
                 $tb->th('', 'Due')->th('', 'Type')->th('', 'Subtype')->th('', 'Customer')->th('', 'Regarding / Title')->th('', 'View');
             $tb->closetablesection('thead');
             $tb->tablesection('tbody');
                 if (!sizeof($this->count)) {
                     $tb->tr();
                     $tb->td('colspan=6|class=text-center h4', 'No related actions found');
                 }
                 
                 foreach ($actions as $action) {
                     $class = $this->generate_rowclass($action);
                     $tb->tr("class=$class");
                     $tb->td('', $action->generate_duedatedisplay('m/d/Y'));
                     $tb->td('', $action->actiontype);
                     $tb->td('', $action->generate_actionsubtypedescription());
                     $tb->td('', $action->customerlink.' - '.Customer::get_customernamefromid($action->customerlink, '', false));
                     $tb->td('', $action->generate_regardingdescription());
                     $tb->td('', $this->generate_viewactionlink($action));
                 }
             $tb->closetablesection('tbody');
             return $tb->close();
         }
         
         public function draw_actionstable($actions) { // DEPRECATED 02/21/2018
             $tb = new Table('class=table table-bordered table-condensed table-striped');
             $tb->tablesection('thead');
                 $tb->tr();
                 $tb->th('', 'Date / Time')->th('', 'Subtype')->th('', 'CustID')->th('', 'Regarding / Title')->th('', 'View action');
             $tb->closetablesection('thead');
             $tb->tablesection('tbody');
                 if (!sizeof($this->count)) {
                     $tb->tr();
                     $tb->td('colspan=5|class=text-center h4', 'No related actions found');
                 }
                 
                 foreach ($actions as $action) {
                     $class = $this->generate_rowclass($action);
                     
                     $tb->tr("class=$class");
                     $tb->td('', date('m/d/Y g:i A', strtotime($action->datecreated)));
                     $tb->td('', ucfirst($action->generate_actionsubtypedescription()));
                     $tb->td('', $action->customerlink.' - '.Customer::get_customernamefromid($action->customerlink, '', false));
                     $tb->td('', $action->generate_regardingdescription());
                     $tb->td('', $this->generate_viewactionlink($action));
                 }
             $tb->closetablesection('tbody');
             return $tb->close();
         }
         
         public function draw_notestable($notes) {
             $tb = new Table('class=table table-bordered table-condensed table-striped');
             $tb->tablesection('thead');
                 $tb->tr();
                 $tb->th('', 'Subtype')->th('', 'CustID')->th('', 'Regarding / Title')->th('', 'View action');
             $tb->closetablesection('thead');
             $tb->tablesection('tbody');
                 if (!sizeof($this->count)) {
                     $tb->td('colspan=4|class=text-center h4', 'No related actions found');
                 }
                 
                 foreach ($notes as $note) {
                     $class = $this->generate_rowclass($note);
                     
                     $tb->tr("class=$class");
                     $tb->td('', ucfirst($note->generate_actionsubtypedescription()));
                     $tb->td('', $note->customerlink.' - '.Customer::get_customernamefromid($note>customerlink, '', false));
                     $tb->td('', $note->generate_regardingdescription());
                     $tb->td('', $this->generate_viewactionlink($note));
                 }
             $tb->closetablesection('tbody');
             return $tb->close();
         }
         
         public function draw_taskstable($tasks) {
             $form = $this->generate_changetaskstatusview();
             $tb = new Table('class=table table-bordered table-condensed table-striped');
             $tb->tablesection('thead');
                 $tb->tr();
                 $tb->th('', 'Due')->th('', 'Subtype')->th('', 'CustID')->th('', 'Regarding / Title')->th('', 'View action')->th('', 'Complete action');
             $tb->closetablesection('thead');
             $tb->tablesection('tbody');
                 if (!sizeof($this->count)) {
                     $tb->td('colspan=6|class=text-center h4', 'No related actions found');
                 }
                 
                 foreach ($tasks as $task) {
                     $class = $this->generate_rowclass($task);
                     $tb->tr("class=$class");
                     $tb->td('', $task->generate_duedatedisplay('m/d/Y'));
                     $tb->td('', $task->generate_actionsubtypedescription());
                     $tb->td('', $task->customerlink.' - '.Customer::get_customernamefromid($task->customerlink, '', false));
                     $tb->td('', $task->generate_regardingdescription());
                     $tb->td('', $this->generate_viewactionlink($task));
                     $complete = ($task->is_completed()) ? '' : $this->generate_completetasklink($task);
                     $tb->td('', $complete);
                 }
             $tb->closetablesection('tbody');
             return $form . $tb->close();
         }
         
         public function generate_changetaskstatusview() {
             $bootstrap = new Contento();
             $ajaxdata = $this->generate_ajaxdataforcontento();
             $href = $this->generate_refreshurl(true);
             $form = new FormMaker('', false);
             $form->add($form->bootstrap->open('div', 'class=panel-body'));
                 $form->add($form->bootstrap->open('div', 'class=row'));
                     $form->add($form->bootstrap->open('div', 'class=col-xs-4'));
                         $form->add($form->bootstrap->openandclose('label', 'for=view-action-completion-status', 'View Completed Tasks'));
                         $form->select("id=view-action-completion-status|class=form-control input-sm|$ajaxdata|data-url=$href", $this->taskstatuses, $this->taskstatus);
                     $form->add($form->bootstrap->close('div'));
                 $form->add($form->bootstrap->close('div'));
             $form->add($form->bootstrap->close('div'));
             return $form->finish();
         }
         
         public function generate_legend() {
 			$bootstrap = new Contento();
 			$tb = new Table('class=table table-bordered table-condensed table-striped');
            $tb->tr('class=bg-warning')->td('', 'Task Overdue');
            $tb->tr('class=bg-info')->td('', 'Task Rescheduled');
            $tb->tr('class=bg-success')->td('', 'Task Completed');
 			$content = str_replace('"', "'", $tb->close());
 			$attr = "tabindex=0|role=button|class=btn btn-sm btn-info|data-toggle=popover|data-placement=bottom|data-trigger=focus";
 			$attr .= "|data-html=true|title=Icons Definition|data-content=$content";
 			return $bootstrap->openandclose('a', $attr, 'Icon Definitions');
 		}
         
         public function generate_completetasklink(UserAction $task) {
             $bootstrap = new Contento();
             $href = $this->generate_viewactionjsonurl($task);
             $icon = $bootstrap->createicon('fa fa-check-circle');
             $icon .= ' <span class="sr-only">Mark as Complete</span>';
             return $bootstrap->openandclose('a', "href=$href|role=button|class=btn btn-xs btn-primary complete-action|title=Mark Task as Complete", $icon);
         }
             
        /** 
         * Generates insertafter string for Paginator object to put the pagination string after
         * @return string 
         */
		public function generate_insertafter() { 
			return $this::$type . "/";
		}
        
        /**
         * Checks if USER and the and $this->assigneduserID are equal
         * and if not return true
         * @return bool
         */
        public function should_haveremoveuserIDlink() {
            return ($this->userID != $this->assigneduserID) ? true : false;
        }
        
        /** 
         * Generates title for Panel
         * Will be overwritten by children
         * @return string 
         */
		public function generate_title() {
			return 'Your Actions';
		}
        
        /**
         * Returns if the panel should have the add link
         * Will be overwritten by children
         * @return bool
         */
        public function should_haveaddlink() {
            return true;
        }
        
        public function count_actions($debug = false, $overridelinks = false) {
            $querylinks = $overridelinks ? array_merge($this->querylinks, $overridelinks) : $this->querylinks;
            if ($debug) {
                return count_useractions($this->userID, $querylinks, $debug);
            } else {
                if (!empty($overridelinks)) {
                    return count_useractions($this->userID, $querylinks, $debug);
                } else {
                    return $this->count = count_useractions($this->userID, $querylinks, $debug);
                }
            }
        }
        
        public function get_actions($debug = false) {
            return get_useractions($this->assigneduserID, $this->querylinks, DplusWire::wire('session')->display, $this->pagenbr, $debug);
        }
        
        public function generate_pagenumberdescription() {
            return ($this->pagenbr > 1) ? "Page $this->pagenbr" : '';
        }
        
        public function generate_ajaxdataforcontento() {
            return str_replace(' ', '|', str_replace("'", "", str_replace('"', '', $this->ajaxdata)));
        }
    }
