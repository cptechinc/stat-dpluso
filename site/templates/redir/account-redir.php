<?php
	/**
	* ACCOUNT REDIRECT
	* @param string $action
	*
	*/
	$action = ($input->post->action ? $input->post->text('action') : $input->get->text('action'));

	$filename = session_id();

	/**
	* ACCOUNT REDIRECT
	*
	*
	*
	*
	* switch ($action) {
	*	case 'login':
	*		DBNAME=$config->DBNAME
	*		LOGPERM
	*		LOGINID=$username
	*		PSWD=$password
	*		break;
	*	case 'logout':
	*		DBNAME=$config->DBNAME
	*		LOGOUT
	*		break;
	* }
	*
	**/

	switch ($action) {
		case 'login':
			if ($input->post->username) {
				$username = $input->post->text('username');
				$password = $input->post->text('password');
				$data = array('DBNAME' => $config->dbName, 'LOGPERM' => false, 'LOGINID' => $username, "PSWD" => $password);
				$session->loc = "login";
			}
			break;
		case 'permissions':
			$data = array('DBNAME' => $config->dbName, 'FUNCPERM' => false);
			$session->loc = $config->pages->index;
			break;
		case 'logout':
			$data = array('DBNAME' => $config->dbName, 'LOGOUT' => false);
			$session->loc = $config->pages->login;

			$session->remove('shipID');
			$session->remove('custID');
			$session->remove('locked-ordernumber');
			break;
		//DELETED CASES add-task get-order-notes write-recent-order-note

	}

	writedplusfile($data, $filename);
	header("location: /cgi-bin/" . $config->cgi . "?fname=" . $filename);
 	exit;
