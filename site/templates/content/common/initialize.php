<?php
	include_once($config->paths->templates."configs/dpluso-config.php");
	$soconfig = json_decode(file_get_contents($config->paths->templates."configs/so-config.json"), true);
	$appconfig = $pages->get('/config/');
	
	include_once($config->paths->vendor."cptechinc/dpluso-processwire-classes/vendor/autoload.php");
	include_once($config->paths->vendor."cptechinc/dpluso-screen-formatters/vendor/autoload.php");
	include_once($config->paths->vendor."cptechinc/dplus-services/vendor/autoload.php");
	
	TableScreenMaker::set_filedirectory($config->jsonfilepath);
	TableScreenMaker::set_testfiledirectory($config->paths->vendor."cptechinc/dpluso-screen-formatters/src/examples/");
	TableScreenMaker::set_fieldfiledirectory($config->companyfiles."json/");

	$config->pages = new Processwire\Paths($config->rootURL);
	include $config->paths->templates."configs/nav-config.php"; 
	
	FormFieldsConfig::set_defaultconfigdirectory($config->paths->templates."configs/customer/");
