<?php include $config->paths->content.'edit/orders/order-attachments.php'; ?>
<form id="orderhead-form" action="<?= $config->pages->orders."redir/"; ?>" class="form-group" data-ordn="<?= $order->orderno; ?>">
	<input type="hidden" name="action" value="update-orderhead">
	<input type="hidden" name="ordn" id="ordn" value="<?= $ordn; ?>">
    <input type="hidden" name="custID" id="custID" value="<?= $order->custid; ?>">
    <div class="row"> <div class="col-xs-10 col-xs-offset-1"> <div class="response"></div> </div> </div>

    <div class="row">
    	<div class="col-sm-6">
        	<?php include $config->paths->content.'edit/orders/orderhead/bill-to.php'; ?>
            <?php include $config->paths->content.'edit/orders/orderhead/ship-to.php'; ?>
        </div>
        <div class="col-sm-6">
        	<?php include $config->paths->content.'edit/orders/orderhead/order-info.php'; ?>
			<?php if ($ordereditdisplay->canedit) : ?>
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
			<?php if ($ordereditdisplay->canedit) : ?>
        		<button type="submit" class="btn btn-success btn-block text-center"><span class="glyphicon glyphicon-floppy-disk"></span> Save Changes</button>
			<?php endif; ?>
		</div>
    </div>
	<hr>
	<?php if (!$ordereditdisplay->canedit) : ?>
		<?= $ordereditdisplay->generate_confirmationlink($order); ?>
	<?php else : ?>
		<div class="row">
			<div class="col-sm-6 form-group">
				 <?= $ordereditdisplay->generate_discardchangeslink($order); ?>
			</div>
			<div class="col-sm-6 form-group">
				<?= $ordereditdisplay->generate_saveunlocklink($order); ?>
			</div>
		</div>
	<?php endif; ?>
</form>
