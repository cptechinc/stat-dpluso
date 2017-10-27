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
		foreach($whse['orders'] as $order) {
			for ($x = 1; $x < $table['header']['maxrows'] + 1; $x++) {
				$tb->tr('');
				for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) {
					if (isset($table['header']['rows'][$x]['columns'][$i])) {
						$column = $table['header']['rows'][$x]['columns'][$i];
						$class = $config->textjustify[$fieldsjson['data']['header'][$column['id']]['datajustify']];
						$colspan = $column['col-length'];

						if ($i == 1 && !empty($order['Order Number'])) {
							$ordn = $order['Ordn'];
							$onclick = "loadorderdocuments('$ordn')";
							$celldata = $page->bootstrap->openandclose('b', '', $column['label']). ': ';
							$celldata .= Table::generatejsoncelldata($fieldsjson['data']['header'][$column['id']]['type'], $order, $column);
							$icon = $page->bootstrap->createicon('fa fa-folder-open');
							$celldata .= '&nbsp;'.$page->bootstrap->openandclose('a', "href=#|title=load order documents|data-load=#ajax-modal|onclick=$onclick", $icon);
							$tb->td('colspan='.$colspan.'|class='.$class, $celldata);
						} else {
							$celldata = $page->bootstrap->openandclose('b', '', $column['label']). ': ';
							$celldata .= Table::generatejsoncelldata($fieldsjson['data']['header'][$column['id']]['type'], $order, $column);
							$tb->td('colspan='.$colspan.'|class='.$class, $celldata);
						}
						if ($colspan > 1) { $i = $i + ($colspan - 1); }
					} else {
						$tb->td();
					}
				}
			}

			foreach ($order['details'] as $item) {
				for ($x = 1; $x < $table['detail']['maxrows'] + 1; $x++) {
					$tb->tr();
					for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) {
						if (isset($table['detail']['rows'][$x]['columns'][$i])) {
							$column = $table['detail']['rows'][$x]['columns'][$i];
							$class = $config->textjustify[$fieldsjson['data']['detail'][$column['id']]['datajustify']];
							$colspan = $column['col-length'];
							$celldata = Table::generatejsoncelldata($fieldsjson['data']['detail'][$column['id']]['type'], $item, $column);
							$tb->td('colspan='.$colspan.'|class='.$class, $celldata);
							if ($colspan > 1) { $i = $i + ($colspan - 1); }
						} else {
							$tb->td();
						}
					}
				}
				$tb->tr();
				for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) {
					if (isset($table['itemstatus']['rows'][1]['columns'][$i])) {
						$column = $table['itemstatus']['rows'][1]['columns'][$i];
						$class = $config->textjustify[$fieldsjson['data']['itemstatus'][$column['id']]['datajustify']];
						$colspan = $column['col-length'];
						$celldata = '<b>'.$column['label'] . ':</b> ';
						$celldata .= Table::generatejsoncelldata($fieldsjson['data']['itemstatus'][$column['id']]['type'], $item['itemstatus'], $column);
						$tb->td('colspan='.$colspan.'|class='.$class, $celldata);
						if ($colspan > 1) { $i = $i + ($colspan - 1); }
					} else {
						$tb->td();
					}
				}

				foreach ($item['purchordrs'] as $purchaseorder) {
					$tb->tr();
					for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) {
						if (isset($table['purchaseorder']['rows'][1]['columns'][$i])) {
							$column = $table['purchaseorder']['rows'][1]['columns'][$i];
							$class = $config->textjustify[$fieldsjson['data']['purchaseorder'][$column['id']]['datajustify']];
							$colspan = $column['col-length'];
							$celldata = '<b>'.$column['label'] . ':</b> ';
							$celldata .= Table::generatejsoncelldata($fieldsjson['data']['purchaseorder'][$column['id']]['type'], $purchaseorder, $column);
							$tb->td('colspan='.$colspan.'|class='.$class, $celldata);
							if ($colspan > 1) { $i = $i + ($colspan - 1); }
						} else {
							$tb->td();
						}
					}
				}
			} // END foreach ($order['details'] as $item)

			for ($x = 1; $x < $table['total']['maxrows'] + 1; $x++) {
				$tb->tr();
				for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) {
					if (isset($table['total']['rows'][$x]['columns'][$i])) {
						$column = $table['total']['rows'][$x]['columns'][$i];
						$class = $config->textjustify[$fieldsjson['data']['total'][$column['id']]['datajustify']];
						$colspan = $column['col-length'];
						$tb->td('', '<b>'.$column['label'] . '</b>');
						$celldata = Table::generatejsoncelldata($fieldsjson['data']['total'][$column['id']]['type'], $order['totals'], $column);
						$tb->td('colspan='.$colspan.'|class='.$class, $celldata);
						$i++;
						if ($colspan > 1) { $i = $i + ($colspan - 1); }
					} else {
						$tb->td();
					}
				}
			}

			foreach ($order['shipments'] as $shipment) {
				for ($x = 1; $x < $table['shipments']['maxrows'] + 1; $x++) {
					$tb->tr();
					for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) {
						if (isset($table['shipments']['rows'][$x]['columns'][$i])) {
							$column = $table['shipments']['rows'][$x]['columns'][$i];
							$class = $config->textjustify[$fieldsjson['data']['shipments'][$column['id']]['headingjustify']];
							$colspan = $column['col-length'];
							$tb->th('colspan='.$colspan.'|class='.$class, $column['label']);
							if ($colspan > 1) { $i = $i + ($colspan - 1); }
						} else {
							$tb->th();
						}
					}
				}

				for ($x = 1; $x < $table['shipments']['maxrows'] + 1; $x++) {
					$tb->tr();
					for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) {
						if (isset($table['shipments']['rows'][$x]['columns'][$i])) {
							$column = $table['shipments']['rows'][$x]['columns'][$i];
							$class = $config->textjustify[$fieldsjson['data']['shipments'][$column['id']]['datajustify']];
							$colspan = $column['col-length'];
							$celldata = Table::generatejsoncelldata($fieldsjson['data']['shipments'][$column['id']]['type'], $shipment, $column);
							$tb->td('colspan='.$colspan.'|class='.$class, $celldata);
							if ($colspan > 1) { $i = $i + ($colspan - 1); }
						} else {
							$tb->td();
						}
					}
				}
			}
			$tb->tr('class=last-row-bottom');
			$tb->td('colspan='.$table['maxcolumns'],'&nbsp;');
		}
	$tb->closetablesection('tbody');
	echo $tb->close();
