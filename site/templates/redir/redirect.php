<?php
	$vl = is_validlogin(session_id());
	$L = $session->loc;

	if ($L == "") {
		$L = $config->pages->index;
	} elseif ($L == 'login') {
		if (!$vl) {
			$L = $config->pages->login;
		} else {
			$L = $config->pages->index;
			setcookie("session", strToHex(session_id()), time()+86400, "/", "",  0);
		}
	}
	header("location: ". $L);
	exit;
