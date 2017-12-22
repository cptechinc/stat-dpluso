<?php
	class II_Quotes extends TableScreenFormatter {
        protected $tabletype = 'normal'; // grid or normal
		protected $type = 'ii-quotes'; // ii-sales-history
		protected $title = 'Item Quotes';
		protected $datafilename = 'iiquote'; // iisaleshist.json
		protected $testprefix = 'iiqt'; // iish
		protected $formatterfieldsfile = 'iiqtfmattbl'; // iishfmtbl.json
		protected $datasections = array(
            "header" => "Header",
			"detail" => "Detail",
		);
		
        public function generate_screen() {
            $bootstrap = new Contento();
            $content = '';
			$this->generate_tableblueprint();
		    
			foreach ($this->json['data'] as $whse) {
				$content .= $bootstrap->h3('', $whse['Whse Name']);
                
                $tb = new Table('class=table table-striped table-bordered table-condensed table-excel|id='.key($this->json['data']));
            	$tb->tablesection('thead');
            		for ($x = 1; $x < $this->tableblueprint['detail']['maxrows'] + 1; $x++) {
            			$tb->tr();
            			for ($i = 1; $i < $this->tableblueprint['cols'] + 1; $i++) {
            				if (isset($this->tableblueprint['detail']['rows'][$x]['columns'][$i])) {
            					$column = $this->tableblueprint['detail']['rows'][$x]['columns'][$i];
            					$class = wire('config')->textjustify[$this->fields['data']['detail'][$column['id']]['headingjustify']];
            					$colspan = $column['col-length'];
            					$tb->th("colspan=$colspan|class=$class", $column['label']);
            					$i = ($colspan > 1) ? $i + ($colspan - 1) : $i;
            				} else {
            					$tb->th();
            				}
            			}
            		}
            	$tb->closetablesection('thead');
            	$tb->tablesection('tbody');
            		foreach($whse['quotes'] as $quote) {
            			for ($x = 1; $x < $this->tableblueprint['header']['maxrows'] + 1; $x++) {
            				$tb->tr();
            				for ($i = 1; $i < $this->tableblueprint['cols'] + 1; $i++) {
            					if (isset($this->tableblueprint['header']['rows'][$x]['columns'][$i])) {
            						$column = $this->tableblueprint['header']['rows'][$x]['columns'][$i];
            						$class = wire('config')->textjustify[$this->fields['data']['header'][$column['id']]['datajustify']];
            						$colspan = $column['col-length'];
            						$celldata = '<b>'.$column['label'].'</b>: ';
            						$celldata .= Table::generatejsoncelldata($this->fields['data']['header'][$column['id']]['type'], $quote, $column);
            						$tb->td("colspan=$colspan|class=$class", $celldata);
            						$i = ($colspan > 1) ? $i + ($colspan - 1) : $i;
            					} else {
            						$tb->td();
            					}
            				}
            			}

            			foreach ($quote['details'] as $item) {
            				for ($x = 1; $x < $this->tableblueprint['detail']['maxrows'] + 1; $x++) {
            					$tb->tr();
            					for ($i = 1; $i < $this->tableblueprint['cols'] + 1; $i++) {
            						if (isset($this->tableblueprint['detail']['rows'][$x]['columns'][$i])) {
            							$column = $this->tableblueprint['detail']['rows'][$x]['columns'][$i];
            							$class = wire('config')->textjustify[$this->fields['data']['detail'][$column['id']]['datajustify']];
            							$colspan = $column['col-length'];
            							$celldata = Table::generatejsoncelldata($this->fields['data']['detail'][$column['id']]['type'], $item, $column);
            							$tb->td("colspan=$colspan|class=$class", $celldata);
            							$i = ($colspan > 1) ? $i + ($colspan - 1) : $i;
            						} else {
            							$tb->td();
            						}
            					}
            				}
            			}
            			$tb->tr('class=last-row-bottom');
            			$tb->td('colspan='.$this->tableblueprint['cols'],'&nbsp;');
            		}
            	$tb->closetablesection('tbody');
            	$content .= $tb->close();
			}
            return $content;
        }
    }
