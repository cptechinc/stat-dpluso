<?php 
     class II_ItemDocumentScreen extends TableScreenMaker {
		protected $tabletype = 'normal'; // grid or normal
		protected $type = 'ii-document'; 
		protected $title = 'Item Document';
		protected $datafilename = 'docview'; 
		protected $testprefix = 'iiprc';
		protected $datasections = array();
        
        /* =============================================================
          PUBLIC FUNCTIONS
       	============================================================ */
        
        public function generate_screen() {

            $columns = array_keys($this->json['columns']);
            $documents = array_keys($this->json['data']);

            $tb = new Table('class=table table-striped table-condensed table-excel');
            $tb->tablesection('thead');
                $tb->tr();
                foreach ($columns as $column) {
                    $class = wire('config')->textjustify[$this->json['columns'][$column]['headingjustify']];
                    $tb->th("class=$class", $this->json['columns'][$column]['heading']);
                }
                $tb->th('', "Load Document");
            $tb->closetablesection('thead');
            $tb->tablesection('tbody');
                foreach ($documents as $doc) {
                    $class = $doc;
                    $tb->tr("class=$class");
                    foreach ($columns as $column) {
                        $class = wire('config')->textjustify[$this->json['columns'][$column]['datajustify']];
                        $tb->td("class=$class", $this->json['data'][$doc][$column]);
                    }
                    $button = $page->bootstrap->openandclose('button', "type=button|class=btn btn-sm btn-primary load-doc|data-doc=$doc", '<i class="fa fa-file-o" aria-hidden="true"></i> Load');
                    $tb->td('', $button);
                }
            $tb->closetablesection('tbody');
            return $tb->close();
        }
        
        public function generate_javascript() {
			$bootstrap = new Contento();
			$content = $bootstrap->open('script', '');
				$content .= "\n";
                // TODO
				$content .= "\n";
			$content .= $bootstrap->close('script');
			return $content;
		}
        
    }
