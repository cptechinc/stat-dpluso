<?php	
	$id = $input->get->text('id');
	$note = UserAction::get($id);

    $message = "Writing Note for {replace} ";
    $page->title = $note->generate_message($message);
	$page->body = $config->paths->content."actions/notes/forms/edit-note-form.php";
	
	if ($config->ajax) {
		include $config->paths->content."common/modals/include-ajax-modal.php";
	} else {
		include $config->paths->content."common/include-blank-page.php";
	}
?>
