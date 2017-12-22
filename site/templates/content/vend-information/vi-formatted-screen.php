<?php
	if ($input->get->debug) {
		$tableformatter->set_debug(true);
	} elseif ($input->post->text('action') == 'preview') {
		$tableformatter->set_debug(true);
	}

	if ($config->ajax && $input->post->text('action') != 'preview') {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	
	if (file_exists($tableformatter->fullfilepath)) {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$tableformatter->process_json();
		
		if ($tableformatter->json['error']) {
			echo $page->bootstrap->createalert('warning', $tableformatter->json['errormsg']);
		} else {
			echo $tableformatter->generate_screen();
            echo $tableformatter->generate_javascript();
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
?>
