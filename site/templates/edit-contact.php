<?php 
	$custID = $input->get->text('custID');
	$shipID = $input->get->text('shipID');
	$contactID = $input->get->text('id');
	$contact = Contact::load($custID, $shipID, $contactID);
	$primarycontact = Contact::load_primarycontact($custID, $shipID);
	
	if ($contact) {
        if (can_accesscustomercontact($user->loginid, $user->hasrestrictions, $custID, $shipID, $contactID, false)) {
			$page->title = "Editing " .$contact->contact . ", ".$contact->get_customername(); 
			$page->body = $config->paths->content.'customer/contact/edit-contact.php';
			$config->scripts->append(hashtemplatefile('scripts/pages/contact-page.js'));
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
