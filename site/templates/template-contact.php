<?php 
	$custID = $input->get->text('custID');
	$shipID = $input->get->text('shipID');
	$contactID = $input->get->text('id');
	$page->body = $config->paths->content.'customer/contact/contact-page.php';
	$contact = get_customercontact($custID, $shipID, $contactID, false);
    
    if ($contact) {
        if (can_accesscustomercontact($user->loginid, $user->hasrestrictions, $custID, $shipID, $contactID, false)) {
            if ($page->name == 'edit') {
                $page->title = "Editing " .$contact->contact . ", ".$contact->get_customername(); 
                $page->body = $config->paths->content.'customer/contact/edit-contact.php';
                $config->scripts->append(hashtemplatefile('scripts/pages/contact-page.js'));
            } else {
                $page->useractionpanelfactory = new UserActionPanelFactory($user->loginid, $page->fullURL);
                $page->title = $contact->contact . ", ".$contact->get_customername(); 
                $page->body = $config->paths->content.'customer/contact/contact-page.php';
            }
        }
    }
    
    
    if ($contact) {
        if (can_accesscustomercontact($user->loginid, $user->hasrestrictions, $custID, $shipID, $contactID, false)) {
            if ($config->ajax) {
        		if ($config->modal) {
        			include $config->paths->content."common/modals/include-ajax-modal.php";
        		} else {
        			include $page->body;
        		}
        	} else {
        		include $config->paths->content."common/include-page.php";
        	}
        } else {
            $page->title = "Error";
            $page->body = "You don't have access to this contact";
            include $config->paths->templates."basic-page.php";
        }
    } else {
        $page->title = "Error";
        $page->body = "Contact $custID $shipID $contactID Not Found";
        include $config->paths->templates."basic-page.php";
    }
	
