<?php
	header('Content-Type: application/json');
	switch ($input->urlSegment(2)) {
		case 'validate-itemid':
			include $config->paths->content . 'products/ajax/json/validate-itemid.php';
			break;
	}
?>
