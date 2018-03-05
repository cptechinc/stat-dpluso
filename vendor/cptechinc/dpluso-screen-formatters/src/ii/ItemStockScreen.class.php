<?php 
     class II_ItemStockScreen extends TableScreenMaker {
		protected $tabletype = 'normal'; // grid or normal
		protected $type = 'ii-stock'; 
		protected $title = 'Item Stock';
		protected $datafilename = 'iistkstat'; 
		protected $testprefix = 'iiprc';
		protected $datasections = array();
        protected $custID = false;
        
        /* =============================================================
          PUBLIC FUNCTIONS
       	============================================================ */
        
        public function generate_screen() {
            $bootstrap = new Contento();
            $content = '';
            $itemlink = new \Purl\Url(wire('config')->pages->products."redir/");
            
            $columns = array_keys($this->json['columns']);
			$tb = new Table('class=table table-striped table-condensed table-bordered table-excel');
			$tb->tablesection('thead');
				$tb->tr();
				foreach ($this->json['columns'] as $column) {
					$class = Processwire\wire('config')->textjustify[$column['headingjustify']];
					$tb->th("class=$class", $column['heading']);
				}
			$tb->closetablesection('thead');
			$tb->tablesection('tbody');
				foreach ($this->json['data'] as $warehouse) {
					$tb->tr();
					foreach ($columns as $column) {
						$class = Processwire\wire('config')->textjustify[$this->json['columns'][$column]['datajustify']];
						if ($column == "Item ID") {
							$itemlink->query->setData(array("action" => "ii-select", "custID" => $this->custID, 'itemID' => $warehouse[$column]));;
							$content = $bootstrap->openandclose('a', "href=".$itemlink->getUrl(), $warehouse[$column]);
						} else {
							$content = $warehouse[$column];
						}
						$tb->td("class=$class", $content);
					}
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
        
        public function set_custid($custID) {
            $this->custID = $custID;
        }
        

    }
