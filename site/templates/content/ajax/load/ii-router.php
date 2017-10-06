<?php
    if (checkconfigifexists($user, 'iio', false)) {
        $iiconfig = json_decode(getconfiguration($user->loginid, $configtype, false), true);
    } else {
        $iiconfig = json_decode(file_get_contents($config->paths->content."salesrep/configs/defaults/item-info-options.json"), true);
    }

    if ($input->get->itemID) {
        $itemID = $input->get->text('itemID');
    }

    switch ($input->urlSegment(2)) { //Parts of order to load
        case 'search-results':
            if ($input->get->q) {$q = $input->get->text('q'); $page->title = "Searching for '$q'";}
			switch ($input->urlSegment(3)) {
				case 'modal':
					$page->body = $config->paths->content."item-information/forms/item-search-form.php";
					break;
				default:
					$page->body = $config->paths->content."item-information/item-search-results.php";
					break;
			}
            break;
		case 'ii-pricing': // $itemID provided by $input->get
			$custID = $input->get->text('custID');
            $page->title = $itemID. ' Price Inquiry for ' . $custID;
            $page->body = $config->paths->content."item-information/item-pricing.php";
            break;
        case 'ii-costing': // $itemID provided by $input->get
            $page->title = $itemID .' Cost Inquiry';
            $page->body = $config->paths->content."item-information/item-costing.php";
            break;
        case 'ii-purchase-order': // $itemID provided by $input->get
            $page->title = $itemID. ' Purchase Order Inquiry';
            $page->body = $config->paths->content."item-information/item-purchase-orders.php";
            break;
		case 'ii-quotes': // $itemID provided by $input->get
            $page->title = 'Viewing ' .$itemID . ' Quotes';
            $page->body = $config->paths->content."item-information/item-quotes.php";
            break;
		 case 'ii-purchase-history': // $itemID provided by $input->get
            $page->title = $itemID.'Purchase History Inquiry';
            $page->body = $config->paths->content."item-information/item-purchase-history.php";
            break;
		case 'ii-where-used': // $itemID provided by $input->get
            $page->title = $itemID.' Where Used Inquiry';
            $page->body = $config->paths->content."item-information/item-where-used.php";
            break;
		case 'ii-kit-components': // $itemID provided by $input->get
            $page->title = $itemID.' Kit Component Inquiry ';
            $page->body = $config->paths->content."item-information/item-kit-components.php";
            break;
		case 'ii-bom': // $itemID provided by $input->get
			$bom = $input->get->text('bom');
            $page->title = $itemID.' BOM Item Inquiry ';
            $page->body = $config->paths->content."item-information/item-bom-".$bom.".php";
            break;
		case 'ii-general': // $itemID provided by $input->get
            $page->title = $itemID . ' General Item Inquiry';
            $page->body = $config->paths->content."item-information/item-general.php";
            break;
		case 'ii-activity': // $itemID provided by $input->get
			if ($input->urlSegment(3) == 'form') {
				$page->title = 'Enter the Starting Report Date ';
				$page->body = $config->paths->content."item-information/forms/item-activity-form.php";
			} else {
				$page->title = $itemID.' Activity Inquiry';
				$page->body = $config->paths->content."item-information/item-activity.php";
			}
            break;
		case 'ii-requirements': // $itemID provided by $input->get
            $page->title = $itemID. ' Requirements Inquiry';
            $page->body = $config->paths->content."item-information/item-requirements.php";
            break;
		case 'ii-lot-serial': // $itemID provided by $input->get
            $page->title = 'Viewing ' .$itemID. ' Lot/Serial Inquiry';
            $page->body = $config->paths->content."item-information/item-lot-serial.php";
            break;
		case 'ii-sales-orders': // $itemID provided by $input->get
            $page->title = $itemID . ' Sales Order Inquiry';
            $page->body = $config->paths->content."item-information/item-sales-orders.php";
            break;
		case 'ii-sales-history': // $itemID provided by $input->get
			if ($input->urlSegment(3) == 'form') {
				if ($input->get->custID) { $custID = $input->get->text('custID'); } else { $custID = ''; }
				$page->title = 'Search Item History';
				$page->body = $config->paths->content."item-information/forms/item-history-form.php";
			} else {
				if ($input->get->custID) { $custID = $input->get->text('custID'); } else { $custID = ''; }
				$page->title = $itemID. ' Sales History Inquiry';
				$page->body = $config->paths->content."item-information/item-history.php";
			}
			break;
		case 'ii-stock': // $itemID provided by $input->get
            $page->title = $itemID. ' Stock by Warehouse Inquiry';
            $page->body = $config->paths->content."item-information/item-stock-by-whse.php";
            break;
		case 'ii-substitutes': // $itemID provided by $input->get
            $page->title = 'Viewing Item Substitutes for ' .$itemID;
            $page->body = $config->paths->content."item-information/item-substitutes.php";
            break;
		case 'ii-documents': // $itemID provided by $input->get
            switch ($input->urlSegment(3)) {
                case 'order':
                    $page->title = "Order #" . $input->get->text('ordn'). ' Documents';
                    break;
                default:
                    $page->title = 'Viewing Item Documents for ' .$itemID;
                    break;
            }
            $page->body = $config->paths->content."item-information/item-documents.php";
            break;
        default:
            $page->title = 'Search for an item';
            if ($input->get->q) {$q = $input->get->text('q');}
            $page->body = $config->paths->content."item-information/forms/item-search-form.php";
            break;

    }

	if ($config->ajax) {
		if ($config->modal) {
			include $config->paths->content."common/modals/include-ajax-modal.php";
		} else {
			include $page->body;
		}
	} else {
		$config->styles->append('//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css');
		$config->scripts->append('//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js');
		$config->scripts->append('//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js');
		$config->scripts->append(hashtemplatefile('scripts/libs/datatables.js'));
		$config->scripts->append(hashtemplatefile('scripts/ii/item-functions.js'));
		$config->scripts->append(hashtemplatefile('scripts/ii/item-info.js'));
		include $config->paths->content."common/include-blank-page.php";
	}


 ?>
