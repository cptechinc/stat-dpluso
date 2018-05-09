<?php 
     class II_ItemNotesScreen extends TableScreenMaker {
		protected $tabletype = 'normal'; // grid or normal
		protected $type = 'ii-notes'; 
		protected $title = 'Item Notes';
		protected $datafilename = 'iinotes'; 
		protected $testprefix = 'iint';
		protected $datasections = array();
        
        /* =============================================================
          PUBLIC FUNCTIONS
       	============================================================ */
        public function generate_screen() {
            $bootstrap = new Contento();
            $content = $bootstrap->h3('', 'Inspection Notes');
            $content .= $this->generate_inspectiontable();
            $content .= $bootstrap->h3('', 'Internal Notes');
            $content .= $this->generate_internaltable();
            $content .= $bootstrap->h3('', 'Order Notes');
            $content .= $this->generate_orderstable();
            return $content;
        }
        
        public function generate_javascript() {
			$bootstrap = new Contento();
			return $bootstrap->script('', '');
		}
        
        /* =============================================================
          CLASS FUNCTIONS
       	============================================================ */
        protected function generate_inspectiontable() {
            // echo json_encode($this->json);
            $tb = new Table('class=table table-striped table-condensed table-excel');
            $tb->tablesection('thead');
                $tb->tr();
                foreach ($this->json['columns']['inspection notes'] as $column) {
                    $class = Processwire\wire('config')->textjustify[$column['headingjustify']];
                    $tb->td("class=$class", $column['heading']);
                }
            $tb->closetablesection('thead');
            $tb->tablesection('tbody');
                foreach ($this->json['data']['inspection notes'] as $note) {
                    $tb->tr();
                    foreach (array_keys($this->json['columns']['inspection notes']) as $column) {
                        $class = Processwire\wire('config')->textjustify[$this->json['columns']['inspection notes'][$column]['datajustify']];
                        $tb->td("class=$class", $note[$column]);
                    }
                }
            $tb->closetablesection('tbody');
            return $tb->close();
        }
        
        protected function generate_internaltable() {
            $tb = new Table('class=table table-striped table-condensed table-excel');
            $tb->tablesection('thead');
                $tb->tr();
                foreach ($this->json['columns']['internal notes'] as $column) {
                    $class = Processwire\wire('config')->textjustify[$column['headingjustify']];
                    $tb->td("class=$class", $column['heading']);
                }
            $tb->closetablesection('thead');
            $tb->tablesection('tbody');
                foreach ($this->json['data']['internal notes'] as $note) {
                    $tb->tr();
                    foreach ($this->json['columns']['internal notes'] as $key => $column) {
                        $class = Processwire\wire('config')->textjustify[$column['datajustify']];
                        $tb->td("class=$class", $note[$key]);
                    }
                }
            $tb->closetablesection('tbody');
            return $tb->close();
        }
        
        protected function generate_orderstable() {
            $tb = new Table('class=table table-striped table-condensed table-excel');
            $tb->tablesection('thead');
                $tb->tr();
                foreach ($this->json['columns']['order notes'] as $column) {
                    $class = Processwire\wire('config')->textjustify[$column['headingjustify']];
                    $tb->td("class=$class", $column['heading']);
                }
            $tb->closetablesection('thead');
            $tb->tablesection('tbody');
                foreach ($this->json['data']['order notes'] as $note) {
                    $tb->tr();
                    foreach ($this->json['columns']['order notes'] as $key => $column) {
                        $class = Processwire\wire('config')->textjustify[$column['datajustify']];
                        $tb->td("class=$class", $note[$key]);
                    }
                }
            $tb->closetablesection('tbody');
            return $tb->close();
        }
    }
