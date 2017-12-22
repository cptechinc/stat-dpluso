<?php
	$docfile = $config->jsonfilepath.session_id()."-docview.json";
	//$docfile = $config->jsonfilepath."iiprc-iiprice.json";
	if ($input->get->returnpage) {
		$returnurl = urldecode($input->get->text('returnpage'));
		$icon = $page->bootstrap->createicon('fa fa-arrow-circle-left');
		$link = $page->bootstrap->openandclose('a', "href=$returnurl|class=h3 modal-load info-screen|data-modal=#ajax-modal|modal-size=xl", "$icon Go Back");
		echo $page->bootstrap->openandclose('div', 'class=form-group', $link);
	}
	
	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	if (file_exists($docfile)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$docjson = json_decode(file_get_contents($docfile), true);
		$docjson = $docjson ? $docjson : array('error' => true, 'errormsg' => 'The Item Documents JSON contains errors. JSON ERROR: '.json_last_error());
		
		if ($docjson['error']) {
			echo $page->bootstrap->createalert('warning', $docjson['errormsg']);
		} else {
			$columns = array_keys($docjson['columns']);
			$documents = array_keys($docjson['data']);
			
			$tb = new Table('class=table table-striped table-condensed table-excel');
			$tb->tablesection('thead');
				$tb->tr();
				foreach ($columns as $column) {
					$class = $config->textjustify[$docjson['columns'][$column]['headingjustify']];
					$tb->th("class=$class", $docjson['columns'][$column]['heading']);
				}
				$tb->th('', "Load Document");
			$tb->closetablesection('thead');
			$tb->tablesection('tbody');
				foreach ($documents as $doc) {
					$class = $doc;
					$tb->tr("class=$class");
					foreach ($columns as $column) {
						$class = $config->textjustify[$docjson['columns'][$column]['datajustify']];
						$tb->td("class=$class", $docjson['data'][$doc][$column]);
					}
					$button = $page->bootstrap->openandclose('button', "type=button|class=btn btn-sm btn-primary load-doc|data-doc=$doc", '<i class="fa fa-file-o" aria-hidden="true"></i> Load');
					$tb->td('', $button);
				}
			$tb->closetablesection('tbody');
			echo $tb->close();
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
 ?>
