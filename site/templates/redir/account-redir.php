<?php
	/**
	* ACCOUNT REDIRECT
	* @param string $action
	*
	*/
	$action = ($input->post->action ? $input->post->text('action') : $input->get->text('action'));

	if ($input->post->sessionID) {
		$filename = $input->post->text('sessionID');
		$sessionID = $input->post->text('sessionID');
	} elseif ($input->get->sessionID) {
		$filename = $input->get->text('sessionID');
		$sessionID = $input->get->text('sessionID');
	} else {
		$filename = session_id();
		$sessionID = session_id();
	}

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
		case 'store-document':
			$folder = $input->get->text('filetype');
			$file = $input->get->text('file');
			$field1 = $input->get->text('field1');
			$field2 = $input->get->text('field2');
			$field3 = $input->get->text('field3');
			$data = array(
				'DBNAME' => $config->dbName, 
				'DOCFILEFLDS' => $folder,
				'DOCFILENAME' => $config->documentstoragedirectory.$file,
				'DOCFLD1' => $field1,
				'DOCFLD2' => $field2,
				'DOCFLD3' => $field3
			);
			break;
	}

	writedplusfile($data, $filename);
	header("location: /cgi-bin/" . $config->cgi . "?fname=" . $filename);
 	exit;
