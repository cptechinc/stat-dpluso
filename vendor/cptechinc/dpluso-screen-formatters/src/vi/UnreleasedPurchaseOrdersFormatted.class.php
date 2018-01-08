<?php
	class VI_UnreleasedPurchaseOrdersFormatter extends TableScreenFormatter {
        protected $tabletype = 'normal';
		protected $type = 'vi-unreleased-purchase-orders'; 
		protected $title = 'Vendor Unreleased Purchase Orders';
		protected $datafilename = 'viunreleased'; 
		protected $testprefix = 'viunrv';
		protected $formatterfieldsfile = 'vipofmattbl';
		protected $datasections = array(
			"detail" => "Detail"
		);
		
        public function generate_screen() {
            $bootstrap = new Contento();
            $content = '';
			$this->generate_tableblueprint();
			$content = $bootstrap->div('class=row', $bootstrap->div('class=col-sm-4 form-group', $bootstrap->label('', 'Show Notes') . $this->generate_shownotesselect()));
			
            $tb = new Table('class=table table-striped table-bordered table-condensed table-excel|id=unreleased');
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
        				$tb->tr();
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
        				foreach ($order['details'] as $detail) {
        					for ($x = 1; $x < $this->tableblueprint['detail']['maxrows'] + 1; $x++) {
        		                $tb->tr();
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
								
        						foreach ($detail['detailnotes'] as $note) {
        							for ($y = 1; $y < $this->tableblueprint['detail']['maxrows'] + 1; $y++) {
        								$tb->tr('class=show-notes hidden');
        								for ($i = 1; $i < $this->tableblueprint['cols'] + 1; $i++) {
        									if ($i == 2) {
        										$tb->td('', $note['Detail Notes']);
        									} else {
        										$tb->td('');
        									}
        								}
        							}	
        						} // end of detail notes
        		            }
        				} // end of details
        				
        				foreach ($order['ordernotes'] as $ordernote) {
        					for ($y = 1; $y < $this->tableblueprint['detail']['maxrows'] + 1; $y++) {
        						$tb->tr('class=show-notes hidden');
        						for ($i = 1; $i < $this->tableblueprint['cols'] + 1; $i++) {
        							if ($i == 2) {
        								$tb->td('', $ordernote['Order Notes']);
        							} else {
        								$tb->td('');
        							}
        						}
        					}
        				}
        				
        	            $pototals = $order['pototals'];
        	            for ($x = 1; $x < $this->tableblueprint['detail']['maxrows'] + 1; $x++) {
        	                $tb->tr();
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
        	            } // end of pototals
        			} // end of purchase order #
        			
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
			$content .= $tb->close();
			return $content;
        }
		
		public function generate_javascript() {
			$bootstrap = new Contento();
			$content = $bootstrap->open('script', '');
				$content .= "\n";
                    if ($this->tableblueprint['detail']['maxrows'] < 2) {
						$content .= $bootstrap->indent() . "$(function() {";
                        $content .= $bootstrap->indent() . $bootstrap->indent() . "$('#unreleased').DataTable();";
						$content .= $bootstrap->indent() ."});";
                    }
				$content .= "\n";
			$content .= $bootstrap->close('script');
			return $content;
		}
    }
