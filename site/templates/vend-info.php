<?php 
    $vendorID = $shipfromID = '';

    if ($input->urlSegment(1)) { // Vendor ID provided 
        $vendorID = $input->urlSegment(1);
        
        if ($input->urlSegment(2)) { // ShipfromID is provided
            $shipfromID = urldecode(str_replace('shipfrom-', '', $input->urlSegment(2)));
        } 
        
        $vendor = Vendor::load($vendorID, $shipfromID);
        
        if ($vendor) {
            $page->title = 'VI: ' . $vendor->generate_title();
            
            if ($vendor->has_shipfrom()) {
                $buttonsjson = json_decode(file_get_contents($config->jsonfilepath.session_id()."-visfbuttons.json"), true);
                $page->body = $config->paths->content."vend-information/vend-shipfrom.php";
            } else {
                $buttonsjson = json_decode(file_get_contents($config->jsonfilepath.session_id()."-vibuttons.json"), true);
                $page->body = $config->paths->content."vend-information/vend-info-outline.php";
            }
            $toolbar = $config->paths->content."vend-information/toolbar.php";
            $config->scripts->append(hashtemplatefile('scripts/vi/vend-functions.js'));
            $config->scripts->append(hashtemplatefile('scripts/vi/vend-info.js'));
            $config->scripts->append(hashtemplatefile('scripts/libs/raphael.js'));
            $config->scripts->append(hashtemplatefile('scripts/libs/morris.js'));
            include $config->paths->content."common/include-toolbar-page.php";
        } else {
            $page->title = "Vendor $vendorID Not Found";
            if ($input->urlSegment(2)) { 
                $page->title = "Vendor $vendorID Shipfrom: $shipfromID Not Found";  
            }
            $toolbar = false;
            $input->get->function = 'vi';
            $page->body = $config->paths->content."vendor/ajax/load/vend-index/search-form.php";
            include $config->paths->content."common/include-toolbar-page.php";
        }
    } else {
        $toolbar = false;
        $input->get->function = 'vi';
        $page->body = $config->paths->content."vendor/ajax/load/vend-index/search-form.php";
        include $config->paths->content."common/include-toolbar-page.php";
    }
?>
