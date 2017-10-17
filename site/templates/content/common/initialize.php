<?php
	$soconfig = json_decode(file_get_contents($config->paths->templates."configs/so-config.json"), true);
	
	$dplusoclasses = require $config->paths->vendor."cptechinc/dpluso-processwire-classes/vendor/composer/autoload_files.php";
	foreach ($dplusoclasses as $class) {
		include_once($class);
	}
