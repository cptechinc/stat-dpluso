<?php
	
    $ajax = new stdClass();
    $ajax->loadinto = ".results"; //WHERE TO LOAD AJAX LOADED DATA
    $ajax->focus = ".results"; //WHERE TO FOCUS AFTER LOADED DATA IS LOADED
    //$ajax->searchlink = ''; //LINK TO THE SEARCH PAGE FOR THIS OBJECT
    $ajax->data = 'data-loadinto="'.$ajax->loadinto.'" data-focus="'.$ajax->focus.'"'; //DATA FIELDS FOR JAVASCRIPT
    //$ajax->modal = '#add-item-panel'; // MODAL TO LOAD INTO IF NEED BE
	
	$ajax->path = $config->pages->ajax.$pathtoajax;
    //$ajax->querystring = querystring_replace($querystring, array('display', 'ajax'), array(false, false));  //BASE QUERYSTRING NEEDED FOR AJAX
	$ajax->insertafter = $addtype;
    $ajax->link = $config->pages->ajax.$pathtoajax;
	
	

	$addtoform = new stdClass();
	$addtoform->action = $formaction;
    $addtoform->rediraction = $rediraction;
    $addtoform->returnpage = $returnpage;
 ?>
