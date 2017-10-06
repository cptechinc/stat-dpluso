<?php
    // $note is loaded by Crud Controller

    if ($note->hascontactlink) { //DOESNT MATTER DEPRECATE
        $contactinfo = get_customercontact($note->customerlink, $note->shiptolink, $note->contactlink, false);
    } else {
        $contactinfo = get_customercontact($note->customerlink, $note->shiptolink, $note->contactlink, false);
    }
?>

<div>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#note" aria-controls="note" role="tab" data-toggle="tab">Note ID: <?= $noteID; ?></a></li>
	</ul>
	<br>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="note"><?php include $config->paths->content."actions/notes/view/view-note-details.php"; ?></div>
	</div>
</div>
