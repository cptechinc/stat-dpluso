<?php 
    class OrderPanel extends OrderDisplay {
        public $focus;
        public $loadinto;
        public $ajaxdata;
        public $paginationinsertafter;
        public $throughajax;
        public $collapse = 'collapse';
        public $tablesorter; // Will be instatnce of TablePageSorter
        public $pagenbr;
        public $activeID = false;
        public $count;
        
        public function __construct($sessionID, \Purl\Url $pageurl, $modal, $loadinto, $ajax) {
            parent::__construct($sessionID, $pageurl);
            $this->modal = $modal;
            $this->loadinto = $this->focus = $loadinto;
            $this->ajaxdata = 'data-loadinto="'.$this->loadinto.'" data-focus="'.$this->focus.'"';
            
            $this->tablesorter = new TablePageSorter($this->pageurl->query->get('orderby'));
            
            if ($ajax) {
                $this->collapse = '';
            } else {
                $this->collapse = 'collapse';
            }
        }
        
        public function generate_pagenumberdescription() {
            return ($this->pagenbr > 1) ? "Page $this->pagenbr" : '';
        }
        
        /* =============================================================
            OrderPanelInterface Functions
        ============================================================ */
        public function generate_clearsearchlink() {
            $bootstrap = new Contento();
            $href = $this->generate_loadurl();
            $ajaxdata = $this->generate_ajaxdataforcontento();
            return $bootstrap->openandclose('a', "href=$href|class=btn btn-warning generate-load-link|$ajaxdata", "Clear Search");
        }
        
        public function generate_clearsortlink() {
            $bootstrap = new Contento();
            $href = $this->generate_clearsorturl();
            $ajaxdata = $this->generate_ajaxdataforcontento();
            return $bootstrap->openandclose('a', "href=$href|class=btn btn-warning btn-sm load-link|$ajaxdata", '(Clear Sort)');
        }
        
        public function generate_clearsorturl() {
            $url = new \Purl\Url($this->pageurl->getUrl());
            $url->query->remove("orderby");
            return $url->getUrl();
        }
        
        public function generate_loadlink() {
            $bootstrap = new Contento();
            $href = $this->generate_loadurl();
            $ajaxdata = $this->generate_ajaxdataforcontento();
            return $bootstrap->openandclose('a', "href=$href|class=generate-load-link|$ajaxdata", "Load Orders");
        }

        public function generate_tablesortbyurl($column) {
            $url = new \Purl\Url($this->pageurl->getUrl());
            $url->query->set("orderby", "$column-".$this->tablesorter->generate_columnsortingrule($column));
            return $url->getUrl();
        }
        
        public function generate_ajaxdataforcontento() {
            return str_replace(' ', '|', str_replace("'", "", str_replace('"', '', $this->ajaxdata)));
        }
    }
