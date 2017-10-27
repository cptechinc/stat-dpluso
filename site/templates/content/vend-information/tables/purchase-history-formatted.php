<?php
	echo "<h3>Purchase Orders</h3>";
	$tb = new Table('class=table table-striped table-bordered table-condensed table-excel|id=purchase-history');
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
		foreach($purchasehistjson['data']['purchaseorders'] as $order) {
			foreach($order['details'] as $detail) {
				for ($x = 1; $x < $table['detail']['maxrows'] + 1; $x++) {
					$tb->tr('');
					for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) {
						if (isset($table['detail']['rows'][$x]['columns'][$i])) {
							$column = $table['detail']['rows'][$x]['columns'][$i];
							$class = $config->textjustify[$fieldsjson['data']['detail'][$column['id']]['datajustify']];
							$colspan = $column['col-length'];
							$celldata = Table::generatejsoncelldata($fieldsjson['data']['detail'][$column['id']]['type'], $detail, $column);
							$tb->td('colspan='.$colspan.'|class='.$class, $celldata);
							if ($colspan > 1) { $i = $i + ($colspan - 1); }
						} else {
							$tb->td();
						}
					}
				}
			}
			$pototals = $order['pototals'];
			for ($x = 1; $x < $table['detail']['maxrows'] + 1; $x++) {
				$tb->tr('');
				for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) {
					if (isset($table['detail']['rows'][$x]['columns'][$i])) {
						$column = $table['detail']['rows'][$x]['columns'][$i];
						$class = $config->textjustify[$fieldsjson['data']['detail'][$column['id']]['datajustify']];
						$colspan = $column['id'] == "Purchase Order Number" ? 2 : $column['col-length'];
						$celldata = Table::generatejsoncelldata($fieldsjson['data']['detail'][$column['id']]['type'], $pototals, $column);
						$tb->td('colspan='.$colspan.'|class='.$class, $celldata);
						if ($colspan > 1) { $i = $i + ($colspan - 1); }
					} else {
						$tb->td();
					}
				}
			}
			$tb->tr('class=last-section-row');
			for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) {
				$tb->td();
			}
		}
		
		$vendortotal = $purchasehistjson['data']['vendortotals'];
			for ($x = 1; $x < $table['detail']['maxrows'] + 1; $x++) {
				$tb->tr('class=bg-primary');
				for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) {
					if (isset($table['detail']['rows'][$x]['columns'][$i])) {
						$column = $table['detail']['rows'][$x]['columns'][$i];
						$class = $config->textjustify[$fieldsjson['data']['detail'][$column['id']]['datajustify']];
						$colspan = $column['id'] == "Purchase Order Number" ? 2 : $column['col-length'];
						$celldata = Table::generatejsoncelldata($fieldsjson['data']['detail'][$column['id']]['type'], $vendortotal, $column);
						$tb->td('colspan='.$colspan.'|class='.$class, $celldata);
						if ($colspan > 1) { $i = $i + ($colspan - 1); }
					} else {
						$tb->td();
					}
				}
			}
		
	$tb->closetablesection('tbody');
	echo $tb->close();
