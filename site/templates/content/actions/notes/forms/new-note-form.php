<?php
	$editactiondisplay = new EditUserActionsDisplay($page->fullURL);
	$action = $config->pages->actions."notes/add/";
	$form = new FormMaker("action=$action|method=post|id=new-action-form|data-refresh=#actions-panel|data-modal=#ajax-modal|onKeyPress=return disable_enterkey(event)");

		$form->input("type=hidden|name=action|value=add-crm-note");
		$form->input("type=hidden|name=customerlink|value=$note->customerlink");
		$form->input("type=hidden|name=shiptolink|value=$note->shiptolink");
		$form->input("type=hidden|name=contactlink|value=$note->contactlink");
		$form->input("type=hidden|name=salesorderlink|value=$note->salesorderlink");
		$form->input("type=hidden|name=quotelink|value=$note->quotelink");
		$form->input("type=hidden|name=actionlink|value=$note->actionlink");
		
		$tb = new Table("class=table table-bordered table-striped");
			$tb->tr();
				$tb->td('', 'Note Create Date: ')->td('', date('m/d/Y g:i A'));
			$tb->tr();
				$tb->td('class=control-label', 'Assigned To: ')->td('', $editactiondisplay->generate_selectsalesperson($note->assignedto));
			$tb->tr();
				$tb->td('class=control-label', "Note Type <br> " . $page->bootstrap->openandclose('small', '', "(Click to Choose)"));
				$tb->td('', $editactiondisplay->generate_selectsubtype($note));
			
			if (!empty($note->customerlink)) {
				$tb->tr();
				$icon = $page->bootstrap->createicon('glyphicon glyphicon-share');
				$href = $page->bootstrap->openandclose('a', 'href='.$editactiondisplay->generate_customerurl($note), "$icon Go to Customer Page");
				$tb->td('', 'Customer:')->td('',get_customername($note->customerlink)." ($note->customerlink)" . ' '. $href);
			}
			
			if (!empty($note->shiptolink)) {
				$tb->tr();
				$tb->td('', 'Ship-to:')->td('', $page->bootstrap->openandclose('a', 'href='.$editactiondisplay->generate_shiptourl($note), get_shiptoname($note->customerlink, $note->shiptolink, false). " ($note->shiptolink)"));
			}
			
			if (!empty($note->contactlink)) {
				$tb->tr();
				$tb->td('', 'Contact:')->td('', $note->contactlink);
			}
			
			if (!empty($note->salesorderlink)) {
				$tb->tr();
				$tb->td('', 'Order #:')->td('', $note->salesorderlink);
			}
			
			if (!empty($note->quotelink)) {
				$tb->tr();
				$tb->td('', 'Quote #:')->td('', $note->quotelink);
			}
			
			$tb->tr();
			$tb->td('class=control-label', 'Title')->td('', $page->bootstrap->input("type=text|name=title|class=form-control|value=$note->title"));
			
			$tb->tr();
			$tb->td('colspan=2', $page->bootstrap->openandclose('label', 'class=control-label', 'Notes') ."<br>". $page->bootstrap->textarea('name=textbody|id=note|cols=30|rows=10|class=form-control note', $note->textbody));
		$table = $tb->close();
		
		$form->add($table);
		$form->button("type=submit|class=btn btn-success", $page->bootstrap->createicon('glyphicon glyphicon-floppy-disk'). " Save Changes");
	echo $form->finish();
?>
