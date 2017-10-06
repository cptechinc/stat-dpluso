<?php
	$notepanel = new NotePanel('user', '#notes-panel', '#notes-panel', '#ajax-modal', $config->ajax);
	$notepanel->count = getlinkednotescount($notepanel->getarraylinks(), false);
	include $config->paths->content."notes/crm/notes-panel.php";
?>
