<?php
    // $note is loaded by Crud Controller
    $notedisplay = new UserActionDisplay($page->fullURL);
    
    $contactinfo = get_customercontact($note->customerlink, $note->shiptolink, $note->contactlink, false);
?>

<div>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#note" aria-controls="note" role="tab" data-toggle="tab">Note ID: <?= $noteID; ?></a></li>
	</ul>
	<br>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="note">
            <?php if ($config->ajax) : ?>
                <?= $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version')); ?>
            <?php endif; ?>
            <?php include $config->paths->content."actions/notes/view/view-note-details.php"; ?>
        </div>
	</div>
</div>
