<?php 
    class EditSalesOrderDisplay extends SalesOrderDisplay {
        protected $ordn;
        protected $modal;
        public $canedit;
        use SalesOrderDisplayTraits;
        
        public function __construct($sessionID, \Purl\Url $pageurl, $modal, $ordn) {
            parent::__construct($sessionID, $pageurl, $modal, $ordn);
        }
        
        public function generate_unlockurl(Order $order) {
            $url = $this->generate_ordersredirurl();
            $url->query->set('action', 'unlock-order');
            $url->query->set('ordn', $order->orderno);
            return $url->getUrl();
        }
        
        public function generate_confirmationurl(Order $order) {
            $url = new \Purl\Url(wire('config')->pages->confirmorder);
            $url->query->set('ordn', $order->orderno);
            return $url->getUrl();
        }
        
        public function generate_discardchangeslink(Order $order) {
            $bootstrap = new Contento();
            $href = $this->generate_unlockurl($order);
            $icon = $bootstrap->createicon('glyphicon glyphicon-floppy-remove');
            return $bootstrap->openandclose('a', "href=$href|class=btn btn-block btn-warning", $icon. " Discard Changes, Unlock Order");
        }
        
        public function generate_saveunlocklink(Order $order) {
            $bootstrap = new Contento();
            $href = $this->generate_unlockurl($order);
            $icon = $bootstrap->createicon('fa fa-unlock');
            return $bootstrap->openandclose('a', "href=$href|class=btn btn-block btn-emerald save-unlock-order|data-form=#orderhead-form", $icon. " Save and Unlock Order");
        }
        
        public function generate_confirmationlink(Order $order) {
            $href = generate_confirmationurl($order);
            $bootstrap = new Contento();
            $href = $this->generate_unlockurl($order);
            $icon = $bootstrap->createicon('btn btn-block btn-success');
            return $bootstrap->openandclose('a', "href=$href|class=btn btn-block btn-success", $icon. " Finished with Order");
        }
    }
