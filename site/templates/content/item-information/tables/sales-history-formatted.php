<?php
	$tb = new Table('class=table table-striped table-bordered table-condensed table-excel|id='.urlencode($whse['Whse Name']));
	$tb->tablesection('thead');
		for ($x = 1; $x < $table['detail']['maxrows'] + 1; $x++) {
			$tb->tr();
			for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) {
				if (isset($table['detail']['rows'][$x]['columns'][$i])) {
					$column = $table['detail']['rows'][$x]['columns'][$i];
					$class = $config->textjustify[$fieldsjson['data']['detail'][$column['id']]['headingjustify']];
					$colspan = $column['col-length'];
					$tb->th('colspan='.$colspan.'|class='.$class, $column['label']);
					if ($colspan > 1) { $i = $i + ($colspan - 1); }
				} else {
					$tb->th();
				}
			}
		}
	$tb->closetablesection('thead');
	$tb->tablesection('tbody');
		foreach($whse['invoices'] as $invoice) {
			if ($invoice != $whse['invoices']['TOTAL']) {
				for ($x = 1; $x < $table['detail']['maxrows'] + 1; $x++) {
					$tb->tr();
					for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) {
						if (isset($table['detail']['rows'][$x]['columns'][$i])) {
							$column = $table['detail']['rows'][$x]['columns'][$i];
							$class = $config->textjustify[$fieldsjson['data']['detail'][$column['id']]['datajustify']];
							$colspan = $column['col-length'];
							$celldata = Table::generatejsoncelldata($fieldsjson['data']['detail'][$column['id']]['type'], $invoice, $column);
							$tb->td('colspan='.$colspan.'|class='.$class, $celldata);
							if ($colspan > 1) { $i = $i + ($colspan - 1); }
						} else {
							$tb->td();
						}
					}
				}
				
				if (sizeof($invoice['lots']) > 0) {
					for ($x = 1; $x < $table['lotserial']['maxrows'] + 1; $x++) {
						$tb->tr();
						for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) {
							if (isset($table['lotserial']['rows'][$x]['columns'][$i])) {
								$column = $table['lotserial']['rows'][$x]['columns'][$i];
								$class = $config->textjustify[$fieldsjson['data']['lotserial'][$column['id']]['headingjustify']];
								$colspan = $column['col-length'];
								$tb->th('colspan='.$colspan.'|class='.$class, $column['label']);
								if ($colspan > 1) { $i = $i + ($colspan - 1); }
							} else {
								$tb->th();
							}
						}
					}
					
					foreach ($invoice['lots'] as $lot) {
						for ($x = 1; $x < $table['lotserial']['maxrows'] + 1; $x++) {
							$tb->tr();
							for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) {
								if (isset($table['lotserial']['rows'][$x]['columns'][$i])) {
									$column = $table['lotserial']['rows'][$x]['columns'][$i];
									$class = $config->textjustify[$fieldsjson['data']['lotserial'][$column['id']]['datajustify']];
									$colspan = $column['col-length'];
									$celldata = Table::generatejsoncelldata($fieldsjson['data']['lotserial'][$column['id']]['type'], $lot, $column);
									$tb->td('colspan='.$colspan.'|class='.$class, $celldata);
									if ($colspan > 1) { $i = $i + ($colspan - 1); }
								} else {
									$tb->td();
								}
							}
						}
					}
				} // END IF (sizeof($invoice['lots']) > 0)
			} // END IF ($invoice != $whse['invoices']['TOTAL'])
		}

	$tb->closetablesection('tbody');
	$tb->tablesection('tfoot');
		$invoice = $whse['invoices']['TOTAL'];
		//for ($x = 1; $x < $table['detail']['maxrows'] + 1; $x++) {
			$x = 1;
			$tb->tr('class=has-warning');
			for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) {
				if (isset($table['detail']['rows'][$x]['columns'][$i])) {
					$column = $table['detail']['rows'][$x]['columns'][$i];
					$class = $config->textjustify[$fieldsjson['data']['detail'][$column['id']]['datajustify']];
					$celldata = Table::generatejsoncelldata($fieldsjson['data']['detail'][$column['id']]['type'], $invoice, $column);
					$tb->td('colspan=|class='.$class, $celldata);
				} else {
					$tb->td();
				}
			}
		//}
	$tb->closetablesection('tfoot');
	echo $tb->close();
