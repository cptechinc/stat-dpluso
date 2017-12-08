<?php
	$item = getitemfromim($itemID, false); 
	$specs = $pricing = $item;
	if (!file_exists($config->imagefiledirectory.$item['image'])) {$item['image'] = 'notavailable.png'; }
	
	echo $page->bootstrap->open('div', 'class=row');
		echo $page->bootstrap->open('div', 'class=col-sm-4 form-group');
			$image = $page->bootstrap->open('img', "src=".$config->imagedirectory.$item['image']."|class=img-responsive|data-desc=".$item['itemid'].' image');
			echo $page->bootstrap->openandclose('a', 'data-toggle=modal|data-target=#lightbox-modal', $image);
		echo $page->bootstrap->close('div');
		
		echo $page->bootstrap->open('div', 'class=col-sm-8 form-group');
		$tb = new Table('class=table table-striped table-bordered table-condensed table-excel|id=');
		foreach ($table['header']['sections']['1'] as $column) {
			$tb->tr();
			$class = $config->textjustify[$fieldsjson['data']['header'][$column['id']]['headingjustify']];
			$colspan = $column['col-length'];
			$tb->td('colspan='.$colspan.'|class='.$class, $page->bootstrap->openandclose('b', '', $column['label']));
			$class = $config->textjustify[$fieldsjson['data']['header'][$column['id']]['datajustify']];
			$colspan = $column['col-length'];
			$celldata = Table::generatejsoncelldata($fieldsjson['data']['header'][$column['id']]['type'], $itemjson['data'], $column);
			$tb->td('colspan='.$colspan.'|class='.$class, $celldata);
		}
		echo $tb->close();
		echo $page->bootstrap->close('div');
	echo $page->bootstrap->close('div');
	
	echo $page->bootstrap->open('div', 'class=row');
		for ($i=2; $i<5; $i++) {
			echo $page->bootstrap->open('div', 'class=col-sm-4 form-group');
			$tb = new Table('class=table table-striped table-bordered table-condensed table-excel|id=');
			foreach ($table['header']['sections']["$i"] as $column) {
				$tb->tr();
				$class = $config->textjustify[$fieldsjson['data']['header'][$column['id']]['headingjustify']];
				$colspan = $column['col-length'];
				$tb->td('colspan='.$colspan.'|class='.$class, $page->bootstrap->openandclose('b', '', $column['label']));
				$class = $config->textjustify[$fieldsjson['data']['header'][$column['id']]['datajustify']];
				$colspan = $column['col-length'];
				$celldata = Table::generatejsoncelldata($fieldsjson['data']['header'][$column['id']]['type'], $itemjson['data'], $column);
				$tb->td('colspan='.$colspan.'|class='.$class, $celldata);
			}
			echo $tb->close();
			echo $page->bootstrap->close('div');
		}
	
	
	echo $page->bootstrap->close('div');
