<?php
    $custID = $shipID = '';

    if ($input->urlSegment(1)) {
        $custID = $input->urlSegment(1);
		$page->title = 'CI: ' . get_customername($custID);
		$custjson = json_decode(file_get_contents($config->jsonfilepath.session_id()."-cicustomer.json"), true);
	    $custshiptos = json_decode(file_get_contents($config->jsonfilepath.session_id()."-cishiptolist.json"), true);
		$buttonsjson = json_decode(file_get_contents($config->jsonfilepath.session_id()."-cibuttons.json"), true);
		$toolbar = $config->paths->content."cust-information/toolbar.php";

		if ($input->urlSegment(2)) {
			$shiptojson = json_decode(file_get_contents($config->jsonfilepath.session_id()."-cishiptoinfo.json"), true);
			$shipID = urldecode(str_replace('shipto-', '', $input->urlSegment(2)));
			$page->title .= ' - ' . $shipID;
            $buttonsjson = json_decode(file_get_contents($config->jsonfilepath.session_id()."-cistbuttons.json"), true);
            
			for ($i = 1; $i < sizeof($custshiptos['data']) + 1; $i++) {
				if ($custshiptos['data']["$i"]['shipid'] == $shipID) {
					$i++;
					break;
				}
			}

			if ($i > sizeof($custshiptos['data'])) {
				$i = 1;
			}
			$nextshipid = $custshiptos['data']["$i"]['shipid'];
		} else {
			if (isset($custshiptos['data'])) {
				if (sizeof($custshiptos['data'])) {
					$nextshipid = $custshiptos['data']["1"]['shipid'];
				} else {
					$nextshipid = false;
				}
			} else {
				$nextshipid = false;
			}
		}
        $config->scripts->append(hashtemplatefile('scripts/libs/raphael.js'));
        $config->scripts->append(hashtemplatefile('scripts/libs/morris.js'));
        
		$config->scripts->append(hashtemplatefile('scripts/ci/cust-functions.js'));
		$config->scripts->append(hashtemplatefile('scripts/ci/cust-info.js'));
        $config->scripts->append(hashtemplatefile('scripts/pages/customer-page.js'));
    } else {
		$toolbar = false;
	}
?>
<?php include('./_head.php'); // include header markup ?>
    <div class="jumbotron pagetitle">
        <div class="container">
            <h1><?php echo $page->get('pagetitle|headline|title') ; ?></h1>
        </div>
    </div>
    <div class="container page">
        <?php
			if ($input->urlSegment(1)) {
				if ($input->urlSegment(2)) {
					include $config->paths->content."cust-information/cust-info-outline.php";
				} else {
					include $config->paths->content."cust-information/cust-info-outline.php";
				}
			} else {
                $input->get->function = 'ci';
                include $config->paths->content."customer/ajax/load/cust-index/search-form.php";
			}
		?>
    </div>
<?php include('./_foot-with-toolbar.php'); // include footer markup ?>
