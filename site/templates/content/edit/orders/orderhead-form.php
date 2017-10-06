<?php $billing = get_orderhead(session_id(), $ordn, false);  ?>
<?php
	if (!$config->phoneintl) { $billing['phintl'] == 'N'; }
	if ($billing['phintl'] == 'Y') {
		$hidden_intl = '';
		$hidden_domestic = "hidden";
		//[0] = access code; [1] = country code; [2] = phone
		$office_phone_arr = explode('-', $billing['phone']);
		$fax_phone_arr = explode('-', $billing['faxnumber']);

	} else {
		$hidden_intl = 'hidden';
		$hidden_domestic = '';
	}

	if ($billing['rqstdate'] == '' || $billing['rqstdate'] == ' / / ') { $billing['rqstdate'] = date('m/d/Y'); }

	$ordersredirect = $page->fullURL;

	$docsurl = new \Purl\Url($page->httpUrl);
	$docsurl->path = $config->pages->orders."redir/";
	$docsurl->query->setData(array('action' => 'get-order-documents', 'ordn' => $ordn, 'linenbr' => '0', 'page' => 'edit'));


	$docsdata = "data-loadinto='.docs' data-focus='.docs' data-click='#documents-link'";
	if ($billing['havedoc'] == 'Y') {
		$documentlink = '
					<a class="btn btn-primary load-sales-docs" role="button" href="'.$docsurl.'" '.$docsdata.'>
						<i class="material-icons" title="Click to View Documents">&#xE873;</i> Show Documents
					</a>';
	} else {
		$documentlink = '<a class="btn btn-default" role="button">
							<i class="material-icons" title="There are no documents for this order">&#xE873;</i> 0 Documents Found
						</a>';
	}

	$noteurl = $config->pages->notes.'redir/?action=get-order-notes&ordn='.$ordn.'&linenbr=0&modal=modal';
	if ($billing['havenote'] != 'Y') {
		$headnotelink = '<a class="btn btn-default load-notes" role="button" href="'.$noteurl.'" data-modal="#ajax-modal"><i class="material-icons" title="View order notes">&#xE0B9;</i> Add Order Notes</a>';
	} else {
		$headnotelink = '<a class="btn btn-primary load-notes" role="button" href="'.$noteurl.'" data-modal="#ajax-modal"> <i class="material-icons" title="View order notes">&#xE0B9;</i> View Order Notes</a>';
	}

	$trackhref = $docsurl->query->set('action', 'get-order-tracking');
	$trackingdata = "data-loadinto='.tracking' data-focus='.tracking' data-click='#tracking-tab-link'";
	if ($billing['havetrk'] == 'Y') {
		$tracklink = '<a href="'.$trackhref.'" class="btn btn-primary load-sales-tracking" role="button" '.$trackingdata.' title="Click to view Tracking">
						<span class="sr-only">View Tracking</span><span class="glyphicon glyphicon-plane hover" style="top: 3px; padding-right: 5px; font-size: 130%;"></span> Tracking Available
					 </a>';
	} else {
		$tracklink = '<a class="btn btn-default load-sales-tracking" role="button" title="There are no tracking numbers for this order">
							<span class="glyphicon glyphicon-plane hover" style="top: 3px; padding-right: 5px; font-size: 130%;" ></span> No Tracking Info
					  </a>';
	}
?>
<?php include $config->paths->content.'edit/orders/order-attachments.php'; ?>
<form id="orderhead-form" action="<?php echo $config->pages->orders."redir/"; ?>" class="form-group" data-ordn="<?php echo $ordn; ?>">
	<input type="hidden" name="action" value="update-orderhead">
	<input type="hidden" name="ordn" id="ordn" value="<?php echo $ordn; ?>">
    <input type="hidden" name="custID" id="custID" value="<?php echo $billing['custid']; ?>">
    <div class="row"> <div class="col-xs-10 col-xs-offset-1"> <div class="response"></div> </div> </div>

    <div class="row">
    	<div class="col-sm-6">
        	<?php include $config->paths->content.'edit/orders/orderhead/bill-to.php'; ?>
            <?php include $config->paths->content.'edit/orders/orderhead/ship-to.php'; ?>
        </div>
        <div class="col-sm-6">
        	<?php include $config->paths->content.'edit/orders/orderhead/order-info.php'; ?>
			<?php if ($editorder['canedit']) : ?>
				<div class="text-right form-group">
					<button type="button" class="btn btn-success text-center" onclick="$('#salesdetail-link').click()">
						<span class="glyphicon glyphicon-triangle-right"></span> Details Page
					</button>
				</div>
			<?php endif; ?>
        </div>
    </div>
    <div class="row">
		<div class="col-sm-6">
			<?php if ($editorder['canedit']) : ?>
        		<button type="submit" class="btn btn-success btn-block text-center"><span class="glyphicon glyphicon-floppy-disk"></span> Save Changes</button>
			<?php endif; ?>
		</div>
    </div>
	<hr>
	<?php if (!$editorder['canedit']) : ?>
		<a href="<?= $config->pages->confirmorder."?ordn=".$ordn; ?>" class="btn btn-block btn-success">Finished with Order</a>
	<?php else : ?>
		<div class="row">
			<div class="col-sm-6 form-group">
				 <a href="<?= $editorder['unlock-url']; ?>" class="btn btn-block btn-warning"><i class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></i> Discard Changes, Unlock Order</a>
			</div>
			<div class="col-sm-6 form-group">
				<a href="<?= $editorder['unlock-url']; ?>" class="btn btn-block btn-emerald save-unlock-order" data-form="#orderhead-form"><i class="fa fa-unlock" aria-hidden="true"></i> Save and Unlock Order</a>
			</div>
		</div>
	<?php endif; ?>
</form>
