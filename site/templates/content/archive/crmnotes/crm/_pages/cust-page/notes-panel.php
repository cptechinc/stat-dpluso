<?php
	$notepanel = new NotePanel('cust', '#notes-panel', '#notes-panel', '#ajax-modal', $config->ajax);
	$notepanel->setupcustomerpanel($custID, $shipID);
	$notepanel->count = getlinkednotescount($notepanel->getarraylinks(), false);
	include $config->paths->content."notes/crm/notes-panel.php";
?>
