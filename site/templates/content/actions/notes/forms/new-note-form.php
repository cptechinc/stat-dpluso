<?php
	$salespersonjson = json_decode(file_get_contents($config->companyfiles."json/salespersontbl.json"), true);
	$salespersoncodes = array_keys($salespersonjson['data']);
?>
<form action="<?php echo $config->pages->actions."notes/add/"; ?>" method="post" id="new-action-form" data-refresh="#actions-panel" data-modal="#ajax-modal">
	<input type="hidden" name="action" value="write-crm-note">
	<input type="hidden" name="custlink" value="<?= $notelinks['customerlink']; ?>">
	<input type="hidden" name="shiptolink" value="<?= $notelinks['shiptolink']; ?>">
	<input type="hidden" name="contactlink" value="<?= $notelinks['contactlink']; ?>">
	<input type="hidden" name="salesorderlink" value="<?= $notelinks['salesorderlink']; ?>">
	<input type="hidden" name="quotelink" value="<?= $notelinks['quotelink']; ?>">
	<input type="hidden" name="notelink" value="<?= $notelinks['notelink']; ?>">
	<input type="hidden" name="tasklink" value="<?= $notelinks['tasklink']; ?>">
	<input type="hidden" name="actionlink" value="<?= $notelinks['actionlink']; ?>">
	<table class="table table-bordered table-striped">
	    <tr>  <td>Note Create Date:</td> <td><?php echo date('m/d/Y g:i A'); ?></td> </tr>
	    <tr>
	        <td class="control-label">Assigned To:</td>
	        <td>
	            <select name="assignedto" class="form-control input-sm" style="width: 200px;">
	                <?php foreach ($salespersoncodes as $salespersoncode) : ?>
	                    <?php if ($salespersonjson['data'][$salespersoncode]['splogin'] == $notelinks['assignedto']) : ?>
	                        <option value="<?= $salespersonjson['data'][$salespersoncode]['splogin']; ?>" selected><?= $salespersoncode.' - '.$salespersonjson['data'][$salespersoncode]['spname']; ?></option>
	                    <?php else : ?>
	                        <option value="<?= $salespersonjson['data'][$salespersoncode]['splogin']; ?>"><?= $salespersoncode.' - '.$salespersonjson['data'][$salespersoncode]['spname']; ?></option>
	                    <?php endif; ?>

	                <?php endforeach; ?>
	            </select>
	        </td>
	    </tr>
		<tr>
			<td class="control-label">Note Type <br><small>(Click to choose)</small></td>
			<td>
				<?php include $config->paths->content."actions/notes/forms/select-note-type.php"; ?>
			</td>
		</tr>
	    <?php include $config->paths->content."actions/notes/view/view-note-links.php"; ?>
		<tr>
			<td class="control-label">Title</td>
			<td>
				<input type="text" name="title" class="form-control">
			</td>
		</tr>
	    <tr>
	        <td colspan="2">
	            <label for="" class="control-label">Notes</label>
	            <textarea name="textbody" id="note" cols="30" rows="10" class="form-control note"> </textarea> <br>
				<button type="submit" class="btn btn-success">Save Note</button>
	        </td>
	    </tr>
	</table>
</form>
