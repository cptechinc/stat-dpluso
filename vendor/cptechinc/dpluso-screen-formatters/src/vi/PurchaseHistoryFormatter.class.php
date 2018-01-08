<?php
	class VI_PurchaseHistoryFormatter extends TableScreenFormatter {
        protected $tabletype = 'normal'; // grid or normal
		protected $type = 'vi-purchase-history'; // ii-sales-history
		protected $title = 'Vendor Purchase History';
		protected $datafilename = 'vipurchhist'; // iisaleshist.json
		protected $testprefix = 'viph'; // iish
		protected $formatterfieldsfile = 'viphfmattbl'; // iishfmtbl.json
		protected $datasections = array(
			"detail" => "Detail"
		);
		
        public function generate_screen() {
            $bootstrap = new Contento();
            $content = '';
			$this->generate_tableblueprint();
		    
			$tb = new Table('class=table table-striped table-bordered table-condensed table-excel|id=purchase-history');
			$tb->tablesection('thead');
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
					foreach($order['details'] as $detail) {
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
					}
					
					$pototals = $order['pototals'];
					for ($x = 1; $x < $this->tableblueprint['detail']['maxrows'] + 1; $x++) {
						$tb->tr('class=totals');
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
							$colspan = $column['id'] == "Purchase Order Number" ? 2 : $column['col-length'];
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
