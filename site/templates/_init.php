<?php 

/**
 * Initialization file for template files
 *
 * This file is automatically included as a result of $config->prependTemplateFile
 * option specified in your /site/config.php.
 *
 * You can initialize anything you want to here. In the case of this beginner profile,
 * we are using it just to include another file with shared functions.
 *
 */

	include_once("./_func.php"); // include our shared functions
	include_once("./_dbfunc.php");
	include_once($config->paths->vendor."cptechinc/dpluso-processwire-classes/src/utfport.php"); // UTF8 conversion library functions
	include_once($config->paths->content."common/initialize.php");  // includes class files

	$session->sessionName = session_name();

	$page->fullURL = new \Purl\Url($page->httpUrl);
	$page->fullURL->path = '';
	if (!empty($config->filename) && $config->filename != '/') {
		$page->fullURL->join($config->filename);
	}
	
	$page->bootstrap = new Contento();

	$page->querystring = $querystring = $page->fullURL->query;
	$page->PageURL = $page->httpUrl.'?'.$page->querystring;

	$config->styles->append(hashtemplatefile('styles/bootstrap.min.css'));
	$config->styles->append('https://fonts.googleapis.com/icon?family=Material+Icons');
	$config->styles->append(hashtemplatefile('styles/libraries.css'));
	$config->styles->append(hashtemplatefile('styles/styles.css'));

	$config->scripts->append(hashtemplatefile('scripts/js-config.js'));
	$config->scripts->append(hashtemplatefile('scripts/libraries.js'));
	$config->scripts->append(hashtemplatefile('scripts/libs/key-listener.js'));
	$config->scripts->append(hashtemplatefile('scripts/libs/datatables.js'));
	$config->scripts->append(hashtemplatefile('scripts/classes.js'));
	
	if (file_exists($config->paths->templates."scripts/$config->dplusocompany-scripts.js")) {
		$config->scripts->append(hashtemplatefile("scripts/$config->dplusocompany-scripts.js"));
	} else {
		$config->scripts->append(hashtemplatefile('scripts/scripts.js'));
	}

	//$config->scripts->append($config->urls->modules . 'Inputfield/InputfieldCKEditor/ckeditor-4.6.1/ckeditor.js'));

	$user->loggedin = is_validlogin(session_id());

	if ($user->loggedin) {
		setupuser(session_id());
	} elseif (strtolower($page->title) != 'login' && strtolower($page->title) != 'redir' ) {
		header('location: ' . $config->pages->login);
		exit;
	}

	if ($input->get->modal) {
		$config->modal = true;
	}
