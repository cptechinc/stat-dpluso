<table class="table table-bordered table-striped">
    <tr>  <td>Note Create Date:</td> <td><?php echo date('m/d/Y g:i A'); ?></td> </tr>
    <?php include $config->paths->content."actions/notes/view/view-note-links.php"; ?>
    <tr>
        <td colspan="2">
            <label for="" class="control-label">Notes</label>
            <form action="<?php echo $config->pages->notes."add/"; ?>" method="post" id="crm-note-form" data-refresh="#notes-panel" data-modal="#ajax-modal">
                <input type="hidden" name="action" value="write-crm-note">
                <input type="hidden" name="custlink" value="<?php echo $custID; ?>">
                <input type="hidden" name="shiptolink" value="<?php echo $shipID; ?>">
                <input type="hidden" name="contactlink" value="<?php echo $contactID; ?>">
                <input type="hidden" name="salesorderlink" value="<?php echo $ordn; ?>">
                <input type="hidden" name="quotelink" value="<?php echo $qnbr; ?>">
                <input type="hidden" name="tasklink" value="<?php echo $taskID; ?>">
                <textarea name="textbody" id="" cols="30" rows="10" class="form-control note"> </textarea> <br>
                <button type="submit" class="btn btn-success">Save Note</button>
            </form>
        </td>
    </tr>
</table>
