<?php
	include_once($config->paths->templates."configs/dpluso-config.php");
	$soconfig = json_decode(file_get_contents($config->paths->templates."configs/so-config.json"), true);
	$appconfig = $pages->get('/config/');
	
	include_once($config->paths->vendor."cptechinc/dpluso-processwire-classes/vendor/autoload.php");
	include_once($config->paths->vendor."cptechinc/dpluso-screen-formatters/vendor/autoload.php");
	
	TableScreenFormatter::set_filedirectory($config->jsonfilepath);
	TableScreenFormatter::set_testfiledirectory($config->paths->vendor."cptechinc/dpluso-screen-formatters/src/examples/");
	TableScreenFormatter::set_fieldfiledirectory($config->companyfiles."json/");

	$config->pages = new Paths($config->rootURL);
	include $config->paths->templates."configs/nav-config.php"; 
