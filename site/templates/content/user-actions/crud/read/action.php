<?php
	// $action is Loaded by Crud Controller
	$actiondisplay = new UserActionDisplay($page->fullURL);
    $contactinfo = get_customercontact($action->customerlink, $action->shiptolink, $action->contactlink, false);
?>

<div>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#action" aria-controls="action" role="tab" data-toggle="tab">Action ID: <?= $action->id; ?></a></li>
	</ul>
	<br>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="action">
			<?php if ($config->ajax) : ?>
                <?= $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version')); ?>
            <?php endif; ?>
			<?php include $config->paths->content."user-actions/crud/read/action-details.php"; ?>
		</div>
	</div>
</div>
