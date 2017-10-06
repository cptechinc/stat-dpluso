<?php if ($config->ajax) : ?>
	<p>
		<a href="<?php echo $config->filename; ?>" target="_blank"><i class="glyphicon glyphicon-print" aria-hidden="true"></i> View Printable Version</a>
		&nbsp; &nbsp;
		<a href="<?= $config->pages->products.'redir/?action=ii-select&itemID='.urlencode($linedetail['itemid']); ?>" target="_blank"><i class="material-icons" aria-hidden="true">&#xE051;</i> View In II</a>
	</p>
<?php endif; ?>
<form action="<?php echo $formaction; ?>" method="post" id="<?= $linedetail['itemid'].'-form'; ?>">
    <input type="hidden" class="action" name="action" value="update-line">
    <input type="hidden" name="ordn" value="<?= $ordn; ?>">
    <input type="hidden" class="listprice" value="<?= formatmoney($linedetail['listprice']); ?> ">
    <input type="hidden" class="linenumber" name="linenbr" value="<?= $linedetail['linenbr']; ?> ">
    <input type="hidden" class="originalprice" value="<?= formatmoney($linedetail['price']); ?> ">
    <input type="hidden" class="discountprice" value="<?= formatmoney($linedetail['price']); ?> ">
    <input type="hidden" class="cost" value="<?= formatmoney($linedetail['cost']); ?> ">
    <input type="hidden" class="calculate-from" value="percent">
    <?php if ($soconfig['config']['use_discount'] != 'Y'): ?>
        <input type="hidden" class="discpct" name="discount" value="<?= formatmoney($linedetail['discpct']); ?>">
    <?php endif; ?>
    <div class="row">
    	<div class="col-sm-8">
    		<div class="jumbotron item-detail-heading"> <div> <h4>Item Info</h4> </div> </div>
            <?php include $config->paths->content."edit/pricing/item-info.php"; ?>

            <br><br>
            <div class="row">
            	<div class="col-sm-6">
            		<div class="jumbotron item-detail-heading"> <div> <h4>Item Pricing</h4> </div> </div>
            		<?php include $config->paths->content."edit/pricing/item-pricing.php"; ?>
            	</div>
            	<div class="col-sm-6">
            		<div class="jumbotron item-detail-heading"> <div class=""> <h4><?= get_customername($custID); ?> History</h4> </div> </div>
          			<?php include $config->paths->content."edit/pricing/item-history.php"; ?>
            	</div>
            </div>

            <div class="jumbotron item-detail-heading"> <div class=""> <h4>Item Availability</h4> </div> </div>
            <?php include $config->paths->content."edit/pricing/item-stock.php"; ?>
    	</div>
    	<div class="col-sm-4">
    		<h4>Current Price</h4>
			<table class="table table-bordered table-striped table-condensed">
				<tr> <td>Price </td> <td class="text-right">$ <?= formatmoney($linedetail['price']); ?></td> </tr>
				<tr> <td>Unit of Measurement</td> <td> <?= $linedetail['uom'] ?></td> </tr>
				<tr> <td>Qty</td> <td class="text-right"><input type="text" class="qty form-control input-sm text-right" name="qty" value="<?= $linedetail['qtyordered']+0; ?>"></td> </tr>
				<tr> <td>Original Ext. Amt.</td> <td class="text-right">$ <?= formatmoney($linedetail['price'] * $linedetail['qtyordered']); ?></td> </tr>
				<?php if ($soconfig['config']['show_originalprice'] == 'Y') : ?>
					<tr> <td>Original Price</td> <td class="text-right">$ <?= formatmoney($linedetail['price']); ?></td> </tr>
				<?php endif; ?>
				<?php if ($soconfig['config']['show_listprice'] == 'Y') : ?>
					<tr> <td>List Price</td> <td class="text-right">$ <?= formatmoney($linedetail['listprice']); ?></td> </tr>
				<?php endif; ?>
				<?php if ($soconfig['config']['show_cost'] == 'Y') : ?>
					<tr> <td>Cost</td> <td class="text-right">$ <?= formatmoney($linedetail['cost']); ?></td> </tr>
				<?php endif; ?>
				<tr><td>Kit:</td><td><?php echo $linedetail['kititemflag']; ?></td></tr>
			</table>

   			<table class="table table-bordered table-striped table-condensed">
				<tr>
					<td>Requested Ship Date</td>
					<td>
						<div class="input-group date" style="width: 180px;">
							<?php $name = 'rqstdate'; $value = $linedetail['rshipdate'];?>
							<?php include $config->paths->content."common/date-picker.php"; ?>
						</div>
					</td>
				</tr>
				<tr>
					<td>Warehouse</td><td><input type="text" class="form-control input-sm qty <?= $linedetail['itemid']."-whse"; ?>" name="whse" value="<?= $linedetail['whse']; ?>"></td>
				</tr>
                <tr>
					<td>
						Special Order
					</td>
					<td>
						<select name="specialorder" class="form-control input-sm">
							<?php foreach ($config->specialordertypes as $spectype => $specdesc) : ?>
								<?php if ($linedetail['spcord'] == $spectype) : ?>
									<option value="<?= $spectype; ?>" selected><?= $specdesc; ?></option>
								<?php else : ?>
									<option value="<?= $spectype; ?>"><?= $specdesc; ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
			</table>

			<?php if ($linedetail['can-edit']) :?>
		    	<button type="submit" class="btn btn-success btn-block"><i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Save Changes</button>
				<br>
				<button type="button" class="btn btn-danger btn-block remove-item"><i class="fa fa-trash" aria-hidden="true"></i> Delete Line</button>
		    <?php endif; ?>

    	</div>
    </div>
</form>
