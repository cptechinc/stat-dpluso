<?php 
    class OrderDisplay {
        public $pageurl;
        
        function __construct($sessionID, \Purl\Url $pageurl) {
            $this->sessionID = $sessionID;
            $this->pageurl = $pageurl;
        }
    }
?>
