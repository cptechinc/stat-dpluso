<?php
	//$linenbr is defined in notes-router
	$canwrite = true;
?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<div class="row">
			<div class="col-sm-2 col-xs-2">Quote</div>
			<div class="col-sm-2 col-xs-2">Pick Ticket</div> <div class="col-sm-2 col-xs-2">Pack Ticket</div>
			<div class="col-sm-2 col-xs-2">Invoice</div> <div class="col-sm-2 col-xs-2">Acknowledgement</div>
		</div>
	</div>
	<ul class="list-group">
		<?php $notes = get_dplusnotes(session_id(), session_id(), $linenbr, 'CART', false); ?>
		<?php foreach ($notes as $note) : ?>
			<?php $readnote = $config->pages->ajax."json/dplus-notes/?key1=".session_id()."&key2=".$linenbr."&recnbr=".$note['recno']."&type=".$config->dplusnotes['cart']['type']; ?>
			<a href="<?php echo $readnote; ?>" class="list-group-item dplusnote rec<?php echo $note['recno']; ?>" data-form="#notes-form">
				<div class="row">
					<div class="col-xs-2"><?php echo $note['form1']; ?></div> <div class="col-xs-2"><?php echo $note['form2']; ?></div>
					<div class="col-xs-2"><?php echo $note['form3']; ?></div> <div class="col-xs-2"><?php echo $note['form4']; ?></div> <div class="col-xs-2"><?php echo $note['form5']; ?></div>
				</div>
			</a>
		<?php endforeach; ?>
	</ul>
</div>
<div class="well">
	<form class="notes" action="<?php echo $config->pages->notes."redir/"; ?>" method="POST" id="notes-form">
		<div class="response"></div>
		<div class="row">
			<div class="form-group col-xs-6 col-sm-2">
				<label class="control-label">Quote</label><br><input type="checkbox" name="form1" id="note1" class="check-toggle" data-size="small" data-width="73px" value="Y">
			</div>
			<div class="form-group col-xs-6 col-sm-2">
				<label class="control-label">Pick Ticket</label><br><input type="checkbox" name="form2" id="note2" class="check-toggle" data-size="small" data-width="73px" value="Y">
			</div>
			<div class="form-group col-xs-6 col-sm-2">
				<label class="control-label">Pack Ticket</label><br><input type="checkbox" name="form3" id="note3" class="check-toggle" data-size="small" data-width="73px" value="Y">
			</div>
			<div class="form-group col-xs-6 col-sm-2">
				<label class="control-label">Invoice</label><br><input type="checkbox" name="form4" id="note4" class="check-toggle" data-size="small" data-width="73px" value="Y">
			</div>
			<div class="form-group col-xs-6 col-sm-2">
				<label class="control-label">Acknowledgement</label><input type="checkbox" name="form5" id="note5" class="check-toggle" data-size="small" data-width="73px" value="Y">
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 form-group">
				<label for="notes" class="control-label">Note: <span class="which"></span></label>
				<textarea class="form-control note" rows="3" cols="35" name="note" placeholder="Add a Note.." style="max-width: 35em;"></textarea>
				<input type="hidden" name="action" class="action" value="write-cart-note">
				<input type="hidden" name="key1" class="key1"value="<?php echo session_id(); ?>">
				<input type="hidden" name="key2" class="key2" value="<?php echo $linenbr; ?>">
				<input type="hidden" class="type" value="<?php echo $config->dplusnotes['cart']['type']; ?>">
				<input type="hidden" name="recnbr" class="recno" value="">
				<input type="hidden" name="editorinsert" class="editorinsert" value="insert">
				<input type="hidden" name="notepage" class="notepage" value="<?php echo $config->filename; ?>">
				<span class="help-block"></span>
				<?php if ($canwrite) : ?>
					<button type="submit" id="submit-note" class="btn btn-success"><i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Save Changes</button>
					&nbsp; &nbsp;
					<?php if (100 == 1) : //TODO ?>
						<button type="button" id="delete-note" class="btn btn-danger" data-form="#notes-form"><i class="fa fa-trash" aria-hidden="true"></i> Delete Note</button>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	</form>
</div>

<script>
	$(function() {
		$('.check-toggle').bootstrapToggle({on: 'Yes', off: 'No', onstyle: 'info' });
	});
</script>
