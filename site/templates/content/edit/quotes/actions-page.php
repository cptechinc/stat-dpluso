<?php $actionpanel = new QuoteActionsPanel(session_id(), $page->fullURL, $input); ?>
<?php $actionpanel->set_qnbr($qnbr); ?>
<div>
	<?php include $config->paths->content."user-actions/user-actions-panel.php";  ?>
</div>
