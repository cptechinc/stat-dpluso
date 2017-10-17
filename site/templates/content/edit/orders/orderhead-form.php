<?php 
$orderpanel = new SalesOrderPanel('edit-order', $page->fullURL, '#ajax-modal', '#orders-panel', $config->ajax, session_id());
$order->panel = setup_editorderpanel($page->fullURL);
$billing = get_orderhead(session_id(), $ordn, 'SalesOrderEdit', false);  

?>
<?php include $config->paths->content.'edit/orders/order-attachments.php'; ?>
<form id="orderhead-form" action="<?= $config->pages->orders."redir/"; ?>" class="form-group" data-ordn="<?= $billing->orderno; ?>">
	<input type="hidden" name="action" value="update-orderhead">
	<input type="hidden" name="ordn" id="ordn" value="<?= $ordn; ?>">
    <input type="hidden" name="custID" id="custID" value="<?= $billing->custid; ?>">
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
