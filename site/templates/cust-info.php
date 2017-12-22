<?php
    $custID = $shipID = '';
    $page->useractionpanelfactory = new UserActionPanelFactory($user->loginid, $page->fullURL);
    
    if ($input->urlSegment(1)) {
        $custID = $input->urlSegment(1);
		
		if ($input->urlSegment(2)) {
            $shipID = urldecode(str_replace('shipto-', '', $input->urlSegment(2)));
		} 
        
        $customer = Customer::load($custID, $shipID);
        
        if ($customer) {
            $page->title = 'CI: ' . $customer->generate_title();
            $custjson = json_decode(file_get_contents($config->jsonfilepath.session_id()."-cicustomer.json"), true);
            $custshiptos = json_decode(file_get_contents($config->jsonfilepath.session_id()."-cishiptolist.json"), true);
            
            if ($customer->has_shipto()) {
                $shiptojson = json_decode(file_get_contents($config->jsonfilepath.session_id()."-cishiptoinfo.json"), true);
                $buttonsjson = json_decode(file_get_contents($config->jsonfilepath.session_id()."-cistbuttons.json"), true);
            } else {
        		$buttonsjson = json_decode(file_get_contents($config->jsonfilepath.session_id()."-cibuttons.json"), true);
            }
            $toolbar = $config->paths->content."cust-information/toolbar.php";
            
            $config->scripts->append(hashtemplatefile('scripts/libs/raphael.js'));
            $config->scripts->append(hashtemplatefile('scripts/libs/morris.js'));
    		$config->scripts->append(hashtemplatefile('scripts/ci/cust-functions.js'));
    		$config->scripts->append(hashtemplatefile('scripts/ci/cust-info.js'));
            $page->body = $config->paths->content."cust-information/cust-info-outline.php";
            $itemlookup->set_customer($customer->custid, $customer->shiptoid);
        } else {
            $page->title = "Vendor $custID Not Found";
            if ($input->urlSegment(2)) { 
                $page->title = "Vendor $custID Ship-to: $shipttoID Not Found";  
            }
            $toolbar = false;
            $input->get->function = 'ci';
            $page->body = $config->paths->content."customer/ajax/load/cust-index/search-form.php";
        }
    } else {
		$toolbar = false;
        $input->get->function = 'ci';
        $dplusfunction = 'ci';
        $page->body = $config->paths->content."customer/ajax/load/cust-index/search-form.php";
	}
    
    include $config->paths->content."common/include-toolbar-page.php";
?>
