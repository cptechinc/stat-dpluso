<?php
	class VI_PurchaseOrdersFormatter extends TableScreenFormatter {
        protected $tabletype = 'normal'; // grid or normal
		protected $type = 'vi-purchase-orders'; // ii-sales-history
		protected $title = 'Vendor Purchase Orders';
		protected $datafilename = 'vipurchordr'; // iisaleshist.json
		protected $testprefix = 'vipo'; // iish
		protected $formatterfieldsfile = 'vipofmattbl'; // iishfmtbl.json
		protected $datasections = array(
			"detail" => "Detail"
		);
		
        public function generate_screen() {
            $bootstrap = new Contento();
            $content = '';
			$this->generate_tableblueprint();
		    
            $tb = new Table('class=table table-striped table-bordered table-condensed table-excel|id=purchase-orders');
        	$tb->tablesection('thead');
        		for ($x = 1; $x < $this->tableblueprint['header']['maxrows'] + 1; $x++) {
        			$tb->tr();
        			for ($i = 1; $i < $this->tableblueprint['cols'] + 1; $i++) {
        				if (isset($this->tableblueprint['header']['rows'][$x]['columns'][$i])) {
        					$column = $this->tableblueprint['header']['rows'][$x]['columns'][$i];
        					$class = Processwire\wire('config')->textjustify[$this->fields['data']['header'][$column['id']]['headingjustify']];
        					$colspan = $column['col-length'];
        					$tb->th("colspan=$colspan|class=$class", $column['label']);
        					$i = ($colspan > 1) ? $i + ($colspan - 1) : $i;
        				} else {
        					$tb->th();
        				}
        			}
        		}
        		
        		for ($x = 1; $x < $this->tableblueprint['detail']['maxrows'] + 1; $x++) {
        			$tb->tr();
        			for ($i = 1; $i < $this->tableblueprint['cols'] + 1; $i++) {
        				if (isset($this->tableblueprint['detail']['rows'][$x]['columns'][$i])) {
        					$column = $this->tableblueprint['detail']['rows'][$x]['columns'][$i];
        					$class = Processwire\wire('config')->textjustify[$this->fields['data']['detail'][$column['id']]['headingjustify']];
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
                foreach($this->json['data']['purchaseorders'] as $order) {
        			for ($x = 1; $x < $this->tableblueprint['header']['maxrows'] + 1; $x++) {
        				$tb->tr('');
        				for ($i = 1; $i < $this->tableblueprint['cols'] + 1; $i++) {
        					if (isset($this->tableblueprint['header']['rows'][$x]['columns'][$i])) {
        						$column = $this->tableblueprint['header']['rows'][$x]['columns'][$i];
        						$class = Processwire\wire('config')->textjustify[$this->fields['data']['header'][$column['id']]['datajustify']];
        						$colspan = $column['col-length'];
        						$celldata = TableScreenMaker::generate_formattedcelldata($this->fields['data']['header'][$column['id']]['type'], $order, $column);
        						$tb->td("colspan=$colspan|class=$class", $celldata);
        						$i = ($colspan > 1) ? $i + ($colspan - 1) : $i;
        					} else {
        						$tb->td();
        					}
        				}
        			}
        			foreach ($order['details'] as $detail) {
        				for ($x = 1; $x < $this->tableblueprint['detail']['maxrows'] + 1; $x++) {
        	                $tb->tr('');
        	                for ($i = 1; $i < $this->tableblueprint['cols'] + 1; $i++) {
        	                    if (isset($this->tableblueprint['detail']['rows'][$x]['columns'][$i])) {
        	                        $column = $this->tableblueprint['detail']['rows'][$x]['columns'][$i];
        	                        $class = Processwire\wire('config')->textjustify[$this->fields['data']['detail'][$column['id']]['datajustify']];
        	                        $colspan = $column['col-length'];
        	                        $celldata = TableScreenMaker::generate_formattedcelldata($this->fields['data']['detail'][$column['id']]['type'], $detail, $column);
        	                        $tb->td("colspan=$colspan|class=$class", $celldata);
        	                        $i = ($colspan > 1) ? $i + ($colspan - 1) : $i;
        	                    } else {
        	                        $tb->td();
        	                    }
        	                }
        	            }

        	            $pototals = $order['pototals'];
        	            for ($x = 1; $x < $this->tableblueprint['detail']['maxrows'] + 1; $x++) {
        	                $tb->tr('');
        	                for ($i = 1; $i < $this->tableblueprint['cols'] + 1; $i++) {
        	                    if (isset($this->tableblueprint['detail']['rows'][$x]['columns'][$i])) {
        	                        $column = $this->tableblueprint['detail']['rows'][$x]['columns'][$i];
        	                        $class = Processwire\wire('config')->textjustify[$this->fields['data']['detail'][$column['id']]['datajustify']];
        	                        $colspan = $column['id'] == "Purchase Order Number" ? 2 : $column['col-length'];
        	                        $celldata = TableScreenMaker::generate_formattedcelldata($this->fields['data']['detail'][$column['id']]['type'], $pototals, $column);
        	                        $tb->td("colspan=$colspan|class=$class", $celldata);
        	                        $i = ($colspan > 1) ? $i + ($colspan - 1) : $i;
        	                    } else {
        	                        $tb->td();
        	                    }
        	                }
        	            }
        			}
                    $tb->tr('class=last-section-row');
                    for ($i = 1; $i < $this->tableblueprint['cols'] + 1; $i++) {
                        $tb->td();
                    }
                }
                
                $vendortotal = $this->json['data']['vendortotals'];
    			for ($x = 1; $x < $this->tableblueprint['detail']['maxrows'] + 1; $x++) {
    				$tb->tr('class=totals');
    				for ($i = 1; $i < $this->tableblueprint['cols'] + 1; $i++) {
    					if (isset($this->tableblueprint['detail']['rows'][$x]['columns'][$i])) {
    						$column = $this->tableblueprint['detail']['rows'][$x]['columns'][$i];
    						$class = Processwire\wire('config')->textjustify[$this->fields['data']['detail'][$column['id']]['datajustify']];
    						$colspan = $column['id'] == "Line Number" ? 2 : $column['col-length'];
    						$celldata = TableScreenMaker::generate_formattedcelldata($this->fields['data']['detail'][$column['id']]['type'], $vendortotal, $column);
    						$tb->td("colspan=$colspan|class=$class", $celldata);
    						$i = ($colspan > 1) ? $i + ($colspan - 1) : $i;
    					} else {
    						$tb->td();
    					}
    				}
    			}
        	$tb->closetablesection('tbody');
        	return $tb->close();
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
