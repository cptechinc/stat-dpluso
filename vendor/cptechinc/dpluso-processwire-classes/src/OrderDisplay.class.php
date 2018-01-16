<?php 
    class OrderDisplay {
        public $pageurl;
        public $sessionID;
        
        function __construct($sessionID, \Purl\Url $pageurl) {
            $this->sessionID = $sessionID;
            $this->pageurl = new \Purl\Url($pageurl->getUrl());
        }
    }
?>
