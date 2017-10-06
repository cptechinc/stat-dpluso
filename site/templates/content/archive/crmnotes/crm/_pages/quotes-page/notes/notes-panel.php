<?php
	$notepanel = new NotePanel('quote', '#notes-panel', '#notes-panel', '#ajax-modal', $config->ajax);
	$notepanel->setupquotepanel($qnbr);
	$notepanel->count = getlinkednotescount($notepanel->getarraylinks(), false);
	include $config->paths->content."notes/crm/notes-panel.php";
?>
