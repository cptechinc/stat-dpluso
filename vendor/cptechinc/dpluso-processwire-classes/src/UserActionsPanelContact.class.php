<?php
    class ContactUserActionsPanel extends CustomerUserActionsPanel{
        public static $type = 'contact';
        public $custID;
		public $shipID;
        public $contactID;
        
        /* =============================================================
 		   CLASS FUNCTIONS 
 	   ============================================================ */
        public function setup_contactpanel($custID, $shipID, $contactID) {
			$this->setup_customerpanel($custID, $shipID);
            $this->contactID = $contactID;
		}
        
        /** 
         * Generates title for Panel
         * Will be overwritten by children
         * @return string 
         */
         public function generate_title() {
 			return 'Contact Actions';
 		}
        
        public function set_querylinks() {
            parent::set_querylinks();
            $this->querylinks['contactlink'] = $this->contactID;
        }
        
        /* =============================================================
           CONTENT FUNCTIONS
       ============================================================ */
        public function draw_allactionstable($actions) { 
            $tb = new Table('class=table table-bordered table-condensed table-striped');
            $tb->tablesection('thead');
                $tb->tr();
                $tb->th('', 'Due')->th('', 'Type')->th('', 'Subtype')->th('', 'Regarding / Title')->th('', 'View action');
            $tb->closetablesection('thead');
            $tb->tablesection('tbody');
                if (!$this->count) {
                    $tb->tr();
                    $tb->td('colspan=5|class=text-center h4', 'No related actions found');
                }
                
                foreach ($actions as $action) {
                    $class = $this->generate_rowclass($action);
                    
                    $tb->tr("class=$class");
                    $tb->td('', $action->generate_duedatedisplay('m/d/Y'));
                    $tb->td('', $action->actiontype);
                    $tb->td('', $action->generate_actionsubtypedescription());
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
                $tb->th('', 'Date / Time')->th('', 'Subtype')->th('', 'Regarding / Title')->th('', 'View action');
            $tb->closetablesection('thead');
            $tb->tablesection('tbody');
                if (!$this->count) {
                    $tb->td('colspan=4|class=text-center h4', 'No related actions found');
                }
                
                foreach ($actions as $action) {
                    $class = $this->generate_rowclass($action);
                    
                    $tb->tr("class=$class");
                    $tb->td('', date('m/d/Y g:i A', strtotime($action->datecreated)));
                    $tb->td('', ucfirst($action->generate_actionsubtypedescription()));
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
                $tb->th('', 'Written on')->th('', 'Subtype')->th('', 'Regarding / Title')->th('', 'View Note');
            $tb->closetablesection('thead');
            $tb->tablesection('tbody');
                if (!$this->count) {
                    $tb->td('colspan=4|class=text-center h4', 'No related actions found');
                }
                
                foreach ($notes as $note) {
                    $class = $this->generate_rowclass($note);
                    
                    $tb->tr("class=$class");
                    $tb->td('', date('m/d/Y g:i A', strtotime($note->datecreated)));
                    $tb->td('', ucfirst($note->generate_actionsubtypedescription()));
                    $tb->td('', $note->generate_regardingdescription());
                    $tb->td('', $this->generate_viewactionlink($note));
                }
            $tb->closetablesection('tbody');
            return $tb->close();
        }
        
        public function draw_taskstable($tasks) {
            $tb = new Table('class=table table-bordered table-condensed table-striped');
            $tb->tablesection('thead');
                $tb->tr();
                $tb->th('', 'Due')->th('', 'Subtype')->th('', 'Regarding / Title')->th('', 'View Task')->th('', 'Complete Task');
            $tb->closetablesection('thead');
            $tb->tablesection('tbody');
                if (!$this->count) {
                    $tb->tr();
                    $tb->td('colspan=5|class=text-center h4', 'No related tasks found');
                }
                
                foreach ($tasks as $task) {
                    $class = $this->generate_rowclass($task);
                    
                    $tb->tr("class=$class");
                    $tb->td('', $task->generate_duedatedisplay('m/d/Y'));
                    $tb->td('', $task->generate_actionsubtypedescription());
                    $tb->td('', $task->generate_regardingdescription());
                    $tb->td('', $this->generate_viewactionlink($task));
                    $complete = ($task->is_completed()) ? '' : $this->generate_completetasklink($task);
                    $tb->td('', $complete);
                }
            $tb->closetablesection('tbody');
            return $tb->close();
        }
        
        /* =============================================================
           GENERATE URLS 
           URLS ARE THE HREF VALUE 
       ============================================================ */
       public function generate_refreshurl($keepactiontype = false) { 
            $url = new \Purl\Url(parent::generate_refreshurl($keepactiontype));
            $url->query->set('contactID', $this->contactID);
            return $url->getUrl();
       }
    }
