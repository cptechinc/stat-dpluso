<?php 
    class UserActionPanelFactory {
        protected $actiontype = 'all';
        protected $assigneduserID;
        protected $pageurl = false;
        protected $pagenbr = 1;
        
        function __construct($userID, \Purl\Url $pageurl, $actiontype = false) {
            $this->assigneduserID = $userID;
            $this->pageurl = $pageurl;
            $this->actiontype = ($actiontype) ? $actiontype : $this->actiontype;
        }
        
        public function create_actionpanel($paneltype, $sessionID, $ajaxTF, $modalTF, $taskstatus) {
            $actiontype = $this->actiontype;
            $assigneduserID = $this->assigneduserID;
            
            $panel = false;
            
            switch ($paneltype) {
                case 'cust':
                    $panel = new CustomerUserActionsPanel($sessionID, $actiontype, $this->pageurl, $ajaxTF, $modalTF, $taskstatus);
                    break;
                case 'contact':
                    $panel = new ContactUserActionsPanel($sessionID, $actiontype, $this->pageurl, $ajaxTF, $modalTF, $taskstatus);
                    break;
                case 'salesorder':
                    $panel = new SalesOrderUserActionsPanel($sessionID, $actiontype, $this->pageurl, $ajaxTF, $modalTF, $taskstatus);
                    break;
                case 'quote':
                    $panel = new QuoteUserActionsPanel($sessionID, $actiontype, $this->pageurl, $ajaxTF, $modalTF, $taskstatus);
                    break;
                default:
                    $panel = new UserActionsPanel($sessionID, $actiontype, $this->pageurl, $ajaxTF, $modalTF, $taskstatus);
                    break;
            }
            $panel->update_assignedtouserID($this->assigneduserID);
            return $panel;
        }
    }
