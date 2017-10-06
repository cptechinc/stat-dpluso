<?php
	$noteID = $input->get->id; $note = loadcrmnote($noteID, false);
	$message = "Reading Note for {replace} ";
	$notetitle = createmessage($message, $note->customerlink, $note->shiptolink, $note->contactlink, $note->tasklink, '', $note->salesorderlink, $note->quotelink);
	$notetitle = "Reading Note #". $noteID;
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title" id="ajax-modal-label"><?php echo $notetitle; ?> </h4>
</div>
<div class="modal-body">
	<?php include $config->paths->content."notes/crm/read-note-table.php"; ?>
</div>
