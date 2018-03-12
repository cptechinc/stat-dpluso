<?php
	$shipID = "";
	if ($input->urlSegment(1)) {
		if ($input->urlSegment(1) == 'add') {
			$page->title = "Add Customer";
			$page->body = $config->paths->content.'customer/add/outline.php';
		} else {
			$page->contact = false; //WHETHER OR NOT TO LOAD CONTACT PAGE
			$page->editcontact = false;
			$custID = $sanitizer->text($input->urlSegment(1));
			$customer = get_customername($input->urlSegment(1));
			$page->title = $input->urlSegment(1) . ' - ' . $customer;
			$user->hascustomeraccess = can_accesscustomer($user->loginid, $user->hascontactrestrictions, $custID, false);
			if ($user->hascustomeraccess) {
				$page->body = $config->paths->content.'customer/cust-page/customer-page-outline.php';
			} else {
				$page->body = $config->paths->content.'customer/cust-page/customer-access-denied.php';
			}
			$config->scripts->append(hashtemplatefile('scripts/pages/customer-page.js'));
			$page->useractionpanelfactory = new UserActionPanelFactory($user->loginid, $page->fullURL);

			if ($input->urlSegment(2)) {
				if (strpos($input->urlSegment(2), 'contacts') !== FALSE) {
					$contactID = $input->get->text('id');
					$page->title = $contactID .", " . $customer;
					$user->hasshiptoaccess = false;
					$page->contact = true;
					if ($input->urlSegment(3) == 'edit') {
						$page->editcontact = true;
						$config->scripts->append(hashtemplatefile('scripts/pages/contact-page.js'));
					}
				} elseif (strpos($input->urlSegment(2), 'shipto-') !== FALSE) {
					$shipID = urldecode(str_replace('shipto-', '', $input->urlSegment(2)));
					$user->hasshiptoaccess = can_accesscustomershipto($user->loginid, $user->hascontactrestrictions, $custID, $shipID, false);
				}

				if (strpos($input->urlSegment(3), 'contacts') !== FALSE) {
					$contactID = $input->get->text('id');
					$page->title = $contactID .", " . $customer;
					$shipID = urldecode(str_replace('shipto-', '', $input->urlSegment(2)));
					$page->contact = true;
					if ($input->urlSegment(4) == 'edit') {
						$page->editcontact = true;
					}
				}

				if ($page->contact) {
					if ($page->editcontact) {
						$page->body = $config->paths->content.'customer/contact/edit-contact.php';
					} else {
						$page->body = $config->paths->content.'customer/contact/contact-page.php';
					}
				} else {
					if (!empty($shipID)) {
						if ($user->hasshiptoaccess) {
							$page->body = $config->paths->content.'customer/cust-page/customer-page-outline.php';
						} else {
							$page->body = $config->paths->content.'customer/cust-page/customer-access-denied.php';
						}
					}
				}
			}
		}
	} else {
		if ($input->get->q) {
			$page->title = "Searching for '".$input->get->text('q')."'";
		} else {
			$page->title = "Customer Index";
		}
		$page->body = $config->paths->content.'customer/cust-index/customer-index.php';
	}

	if ($config->ajax) {
		if ($config->modal) {
			include $config->paths->content."common/modals/include-ajax-modal.php";
		} else {
			include $page->body;
		}
	} else {
		include $config->paths->content."common/include-page.php";
	}
