<?php
    $quote = get_quotehead(session_id(), $qnbr, false); $hidden_domestic = '';

    $noteurl = $config->pages->notes.'redir/?action=get-quote-notes&qnbr='.$qnbr.'&linenbr=0&modal=modal';
	if ($quote['notes'] != 'Y') {
		$headnotelink = '<a class="btn btn-default load-notes" role="button" href="'.$noteurl.'" data-modal="#ajax-modal"><i class="material-icons" title="View quote notes">&#xE0B9;</i> Add Quote Notes</a>';
	} else {
		$headnotelink = '<a class="btn btn-primary load-notes" role="button" href="'.$noteurl.'" data-modal="#ajax-modal"> <i class="material-icons" title="View quote notes">&#xE0B9;</i> View Quote Notes</a>';
	}
?>

<div class="row">
    <div class="col-sm-12">
    	<?php include $config->paths->content.'edit/quotes/actions/actions-panel.php'; ?>
    </div>
</div>
<?php include $config->paths->content.'edit/quotes/quote-attachments.php'; ?>
<form id="quotehead-form" action="<?php echo $config->pages->quotes."redir/";  ?>" class="form-group" method="post">
	<input type="hidden" name="action" value="save-quotehead">
	<input type="hidden" name="qnbr" id="qnbr" value="<?php echo $qnbr; ?>">
    <input type="hidden" name="custID" id="custID" value="<?php echo $quote['custid']; ?>">
    <div class="row"> <div class="col-xs-10 col-xs-offset-1"> <div class="response"></div> </div> </div>

    <div class="row">
    	<div class="col-sm-6">
        	<?php include $config->paths->content.'edit/quotes/quotehead/bill-to.php'; ?>
            <?php include $config->paths->content.'edit/quotes/quotehead/ship-to.php'; ?>
        </div>
        <div class="col-sm-6">
        	<?php include $config->paths->content.'edit/quotes/quotehead/quote-info.php'; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="text-center form-group">
        		<button type="submit" class="btn btn-success btn-block"><i class="glyphicon glyphicon-floppy-disk"></i> Save Changes</button>
        	</div>
        </div>
        <div class="col-sm-6">
            <div class="text-right form-group">
				<?php if ($editquote['canedit']) : ?>
	        		<button type="button" class="btn btn-success text-center" onclick="$('#quotedetail-link').click()"><span class="glyphicon glyphicon-triangle-right"></span> Details Page</button>
				<?php endif; ?>
	        </div>
        </div>
    </div>
    <hr>
    <?php if (!$editquote['canedit']) : ?>
         <a href="<?= $config->pages->confirmquote."?qnbr=".$qnbr; ?>" class="btn btn-block btn-success">Finished with Quote</a>
    <?php else : ?>
        <?php if (($config->pages->orderquote.'?qnbr='.$qnbr) != $config->filename) : ?>
            <div class="form-group">
                <a href="<?= $config->pages->orderquote.'?qnbr='.$qnbr; ?>" class="btn btn-block btn-default">
                    <i class="fa fa-paper-plane-o" aria-hidden="true"></i> Send To Order
                </a>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-sm-6 form-group">
    			<div class="text-center">
    				<a href="<?= $editquote['unlock-url']; ?>" class="btn btn-block btn-warning"><i class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></i> Discard Changes, Unlock Quote</a>
    			</div>
    		</div>
    		<div class="col-sm-6 form-group">
    			<div class="text-center">
    				<button type="button" class="btn btn-emerald btn-block save-unlock-quotehead"><i class="fa fa-unlock" aria-hidden="true"></i> Save and Unlock Quote</button>
    			</div>
    		</div>
    	</div>    
    <?php endif; ?>
</form>
