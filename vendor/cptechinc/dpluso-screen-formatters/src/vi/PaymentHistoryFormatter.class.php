<?php
	class VI_PaymentHistoryFormatter extends TableScreenFormatter {
        protected $tabletype = 'normal';
		protected $type = 'vi-payment-history'; 
		protected $title = 'Vendor Payment History';
		protected $datafilename = 'vipayment'; 
		protected $testprefix = 'vipy';
		protected $formatterfieldsfile = 'vipyfmattbl';
		protected $datasections = array(
			"detail" => "Detail"
		);
		
        public function generate_screen() {
            $bootstrap = new Contento();
            $content = '';
			$this->generate_tableblueprint();
			
            $tb = new Table('class=table table-striped table-bordered table-condensed table-excel|id=payments');
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
        		foreach($this->json['data']['payments'] as $invoice) {
        			for ($x = 1; $x < $this->tableblueprint['detail']['maxrows'] + 1; $x++) {
        				$tb->tr();
        				for ($i = 1; $i < $this->tableblueprint['cols'] + 1; $i++) {
        					if (isset($this->tableblueprint['detail']['rows'][$x]['columns'][$i])) {
        						$column = $this->tableblueprint['detail']['rows'][$x]['columns'][$i];
        						$class = wire('config')->textjustify[$this->fields['data']['detail'][$column['id']]['datajustify']];
        						$colspan = $column['col-length'];
        						$celldata = Table::generatejsoncelldata($this->fields['data']['detail'][$column['id']]['type'], $invoice, $column);
        						$tb->td("colspan=$colspan|class=$class", $celldata);
        						$i = ($colspan > 1) ? $i + ($colspan - 1) : $i;
        					} else {
        						$tb->td();
        					}
        				}
        			}
        		}
        	$tb->closetablesection('tbody');
        	$tb->close();
			return $tb->close();
        }
		
		public function generate_javascript() {
			$bootstrap = new Contento();
			$content = $bootstrap->open('script', '');
				$content .= "\n";
                    if ($this->tableblueprint['detail']['maxrows'] < 2) {
						$content .= $bootstrap->indent() . "$(function() {";
                        $content .= $bootstrap->indent() . $bootstrap->indent() . "$('#payments').DataTable();";
						$content .= $bootstrap->indent() ."});";
                    }
				$content .= "\n";
			$content .= $bootstrap->close('script');
			return $content;
		}
    }
