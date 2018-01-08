<?php 
    class SalesOrderDisplay extends OrderDisplay implements OrderDisplayInterface, SalesOrderDisplayInterface {
        use SalesOrderDisplayTraits;
        
        protected $ordn;
        protected $order;
        protected $modal;
        
        public function __construct($sessionID, \Purl\Url $pageurl, $modal, $ordn) {
            parent::__construct($sessionID, $pageurl);
            $this->ordn = $ordn;
            $this->modal = $modal;
        }
        
        public function get_order($debug = false) {
            return get_orderhead($this->sessionID, $this->ordn, 'SalesOrder', false);
        }
        
        public function get_creditcard($debug = false) {
            return get_ordercreditcard($this->sessionID, $this->ordn, false);
        }
        
        public function showhide_creditcard(Order $order) {
            return ($order->paytype == 'cc') ? '' : 'hidden';
        }
        
        public function showhide_phoneintl(Order $order) {
            return $order->is_phoneintl() ? '' : 'hidden';
        }
        
        public function showhide_phonedomestic(Order $order) {
            return $order->is_phoneintl() ? 'hidden' : '';
        }
        
        /* =============================================================
            OrderDisplayInterface Functions
            LINKS ARE HTML LINKS, AND URLS ARE THE URLS THAT THE HREF VALUE
        ============================================================ */
        public function generate_documentsrequesturl(Order $order, OrderDetail $orderdetail = null) {
            return $this->generate_documentsrequesturltrait($order, $orderdetail);
        }
        
        public function generate_editlink(Order $order) {
            $bootstrap = new Contento();
            $href = $this->generate_editurl($order);
            $icon = $bootstrap->createicon('material-icons', '&#xE150;');
            return $bootstrap->openandclose('a', "href=$href|class=btn btn-block btn-warning", $icon. " Edit Order");   
        }
        
        public function generate_detailvieweditlink(Order $order, OrderDetail $detail) {
            $bootstrap = new Contento();
            $href = $this->generate_detailviewediturl($order, $detail);
            $icon = $bootstrap->openandclose('span', 'class=h3', $bootstrap->createicon('glyphicon glyphicon-eye-open'));
            return $bootstrap->openandclose('a', "href=$href|class=update-line|data-kit=$detail->kititemflag|data-itemid=$detail->itemid|data-custid=$order->custid|aria-label=View Detail Line", $icon);    
        }
        
        /* =============================================================
            SalesOrderDisplayInterface Functions
            LINKS ARE HTML LINKS, AND URLS ARE THE URLS THAT THE HREF VALUE
        ============================================================ */
        public function generate_trackingrequesturl(Order $order) {
            return $this->generate_trackingrequesturltrait($order);
        }
        
        /* =============================================================
            Class specific Functions
            
        ============================================================ */
        public function generate_customershiptolink(Order $order) {
            $bootstrap = new Contento();
            $href = $this->generate_customershiptourl($order);
            $icon = $bootstrap->createicon('fa fa-user');
            return $bootstrap->openandclose('a', "href=$href|class=btn btn-block btn-primary", $icon. " Go to Customer Page");   
        }
        
        public function generate_loaddetailsurl(Order $order) {
            $url = new \Purl\Url($this->generate_loaddetailsurltrait($order));
            return $url->getUrl();    
        }
    }
