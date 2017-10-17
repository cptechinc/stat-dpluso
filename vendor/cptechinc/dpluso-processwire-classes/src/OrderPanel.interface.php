<?php 
    interface OrderPanelInterface {
        public function setup_pageurl(\Purl\Url $pageurl);
        public function generate_expandorcollapselink(Order $order);
        public function generate_rowclass(Order $order);
        public function generate_shiptopopover(Order $order);
        public function generate_ajaxdataforcontento();
        public function generate_iconlegend();
        public function generate_loadlink();
        public function generate_loadurl();
        public function generate_refreshlink();
        public function generate_clearsearchlink();
        public function generate_searchlink();
        public function generate_searchurl();
        public function generate_clearsortlink(); 
        public function generate_clearsorturl();
        public function generate_tablesortbyurl($column);
    }
