<?php 
	$pricingfile = $config->jsonfilepath.session_id()."-price.json";
	
	if (file_exists($pricingfile))  {
		// JSON file will be false if an error occurred during file_get_contents or json_decode
		$pricingjson = json_decode(file_get_contents($pricingfile), true);
		$pricingjson = $pricingjson ? $pricingjson : array('error' => true, 'errormsg' => 'The Item Pricing JSON contains errors. JSON ERROR: '.json_last_error());
		
		if ($pricingjson['error']) {
			echo $page->bootstrap->createalert('warning', $pricingjson['errormsg']);
		} else {
			$tb = new Table("class=table table-striped table-bordered table-condensed table-excel");
			foreach($pricingjson['columns'] as $column => $name)  {
				$tb->tr();
				$tb->td('', $name);
				foreach ($pricingjson['data'] as $pricebreak)  {
					$attr = is_numeric($pricebreak[$column]) ? 'class=text-right' : '';
					$tb->td($attr, $pricebreak[$column]);
				}
			}
			echo $tb->close();
		}
	} else {
		echo $page->bootstrap->createalert('warning', 'Information Not Available');
	}
?>
