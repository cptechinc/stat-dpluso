<?php
    class SalesOrderUserActionsPanel extends UserActionsPanel {
        public static $type = 'salesorder';
        public $ordn;
        
        public function setup_salesorderpanel($ordn) {
			$this->ordn = $ordn;
		}
        
        /** 
         * Generates title for Panel
         * Will be overwritten by children
         * @return string 
         */
		public function generate_title() {
			return "Order # $this->ordn Actions";
		}
        
        public function start_querylinks() {
            parent::start_querylinks();
            $this->querylinks['salesorderlink'] = $this->ordn;
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
                     $tb->tr();
                     $tb->td('colspan=5|class=text-center h4', 'No related actions found');
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
                 $tb->th('', 'Written on')->th('', 'Subtype')->th('', 'Regarding / Title')->th('', 'View action');
             $tb->closetablesection('thead');
             $tb->tablesection('tbody');
                 if (!$this->count) {
                     $tb->tr();
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
             $form = $this->generate_changetaskstatusview();
             $tb = new Table('class=table table-bordered table-condensed table-striped');
             $tb->tablesection('thead');
                 $tb->tr();
                 $tb->th('', 'Due')->th('', 'Subtype')->th('', 'CustID')->th('', 'Regarding / Title')->th('', 'View action')->th('', 'Complete action');
             $tb->closetablesection('thead');
             $tb->tablesection('tbody');
                 if (!$this->count) {
                     $tb->td('colspan=6|class=text-center h4', 'No related actions found');
                 }
                 
                 foreach ($tasks as $task) {
                     $class = $this->generate_rowclass($task);
                     $tb->tr("class=$class");
                     $tb->td('', $task->generate_duedatedisplay('m/d/Y'));
                     $tb->td('', $task->generate_actionsubtypedescription());
                     $tb->td('', $task->customerlink);
                     $tb->td('', $task->generate_regardingdescription());
                     $tb->td('', $this->generate_viewactionlink($task));
                     $complete = ($task->is_completed()) ? '' : $this->generate_completetasklink($task);
                     $tb->td('', $complete);
                 }
             $tb->closetablesection('tbody');
             return $form . $tb->close();
         }
        
        /* =============================================================
           GENERATE URLS 
           URLS ARE THE HREF VALUE 
       ============================================================ */
       public function generate_refreshurl($keepactiontype = false) { 
            $url = new \Purl\Url(parent::generate_refreshurl($keepactiontype));
            $url->query->set('ordn', $this->ordn);
            return $url->getUrl();
       }
    }
