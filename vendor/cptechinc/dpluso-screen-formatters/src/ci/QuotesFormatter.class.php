<?php
	class CI_QuotesFormatter extends TableScreenFormatter {
        protected $tabletype = 'normal'; // grid or normal
		protected $type = 'ci-quotes'; // ii-sales-history
		protected $title = 'Customer Quotes';
		protected $datafilename = 'ciquote'; // iisaleshist.json
		protected $testprefix = 'ciqt'; // iish
		protected $formatterfieldsfile = 'ciqtfmattbl'; // iishfmtbl.json
		protected $datasections = array(
			"header" => "Header",
			"detail" => "Detail",
			"totals" => "Totals"
		);
        
        public function generate_screen() {
			$url = new \Purl\Url(wire('config')->pages->ajaxload."ci/ci-documents/order/");
            $bootstrap = new Contento();
			$this->generate_tableblueprint();
			$content = '';
            
            foreach ($this->json['data'] as $whse) {
                $tb = new Table('class=table table-striped table-bordered table-condensed table-excel|id='.key($this->json['data']));
            	$tb->tablesection('thead');
            		for ($x = 1; $x < $this->tableblueprint['detail']['maxrows'] + 1; $x++) {
            			$tb->tr('');
            			for ($i = 1; $i < $this->tableblueprint['cols'] + 1; $i++) {
            				if (isset($this->tableblueprint['detail']['rows'][$x]['columns'][$i])) {
            					$column = $this->tableblueprint['detail']['rows'][$x]['columns'][$i];
            					$class = wire('config')->textjustify[$this->fields['data']['detail'][$column['id']]['headingjustify']];
            					$colspan = $column['col-length'];
            					$tb->th("colspan=$colspan|class=$class", $column['label']);
            					$i = ($colspan > 1) ? $i + + ($colspan - 1) : $i;
            				} else {
            					$tb->th('');
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
            						$celldata = '<b>'.$column['label'].'</b>: '.Table::generatejsoncelldata($this->fields['data']['header'][$column['id']]['type'], $quote, $column);
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
						if (isset($this->table->tableblueprint['totals'])) {
							for ($x = 1; $x < $this->tableblueprint['totals']['maxrows'] + 1; $x++) {
								$tb->tr();
								for ($i = 1; $i < $this->tableblueprint['cols'] + 1; $i++) {
									if (isset($this->tableblueprint['totals']['rows'][$x]['columns'][$i])) {
										$column = $this->tableblueprint['totals']['rows'][$x]['columns'][$i];
										$class = wire('config')->textjustify[$this->fields['data']['totals'][$column['id']]['datajustify']];
										$colspan = $column['col-length'];
										$celldata = '<b>'.$column['label'].'</b>: '.Table::generatejsoncelldata($this->fields['data']['totals'][$column['id']]['type'], $quote['totals'], $column);
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
            	echo $tb->close();
            }
        }
		
        public function generate_javascript() {
			$bootstrap = new Contento();
			$content = $bootstrap->open('script', '');
				$content .= "\n";
				
				$content .= "\n";
			$content .= $bootstrap->close('script');
			return $content;
		}
    }
