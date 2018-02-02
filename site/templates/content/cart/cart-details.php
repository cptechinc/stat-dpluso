<div id="no-more-tables" class="form-group">
	<table class="table-condensed cf order-details numeric">
		<thead class="cf">
			<tr>
				<th>Item</th> <th class="numeric">Price</th> <th class="numeric">Qty</th> <th class="numeric">Total</th>
				<th>Rqstd Ship Date</th> <th>Warehouse</th>
				<th>
					<div class="row">
						<div class="col-xs-3">Details</div><div class="col-xs-2">Docs</div> <div class="col-xs-2">Notes</div> <div class="col-xs-5">Edit</div>
					</div>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php $orderdetails = getcart(session_id(), false); ?>
			<?php foreach ($orderdetails as $detail) : ?>
				<?php
					$detail['havenote'] = has_dplusnote(session_id(), session_id(), $detail['linenbr'], $config->dplusnotes['cart']['type']);
					$detailnoteurl = $config->pages->notes.'redir/?action=get-cart-notes&linenbr='.$detail['linenbr'].'&modal=modal';
					if ($detail['havenote'] != 'Y') {
						$detnoteicon = '<a class="load-notes text-muted" href="'.$detailnoteurl.'" data-modal="#ajax-modal"><i class="material-icons md-36" title="View order notes">&#xE0B9;</i></a>';
					} else {
						$detnoteicon = '<a class="load-notes" href="'.$detailnoteurl.'" data-modal="#ajax-modal"> <i class="material-icons md-36" title="View order notes">&#xE0B9;</i></a>';
					}
				?>
			<tr class="cart-item">
				<td data-title="ItemID/Desc">
					<?php if ($detail['errormsg'] != '') : ?>
						<div class="btn-sm btn-danger">
						  <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>Error!</strong> <?= $detail['errormsg']; ?>
						</div>
					<?php else : ?>
						<?= $detail['itemid']; ?>
						<?php if (strlen($detail['vendoritemid'])) { echo ' '.$detail['vendoritemid'];} ?>
						<br> <small><?= $detail['desc1']; ?></small>
					<?php endif; ?>
				</td>
				<td data-title="Price" class="text-right">$ <?= formatMoney($detail['price']); ?></td>
				<td data-title="Ordered" class="text-right"><?= $detail['qtyordered'] + 0; ?></td>
				<td data-title="Total" class="text-right">$ <?= formatMoney($detail['extamt']); ?></td>
				<td data-title="Requested Ship Date" class="text-right"><?= $detail['rshipdate']; ?></td>
				<td data-title="Warehouse">MN</td>
				<td class="action">
					<div class="row">
						<div class="col-xs-3"> <span class="visible-xs-block action-label">Details</span>
							<a href="<?= $config->pages->ajax."load/view-detail/cart/?line=".$detail['linenbr']; ?>" class="btn btn-sm btn-primary view-item-details" data-itemid="<?php echo $detail['itemid']; ?>" data-kit="<?php echo $detail['kititemflag']; ?>" data-modal="#ajax-modal"><i class="material-icons">&#xE8DE;</i></a>
						</div>
						<div class="col-xs-2"> <span class="visible-xs-block action-label">Docs</span> <i class="material-icons md-36">&#xE873;</i> </div>
						<div class="col-xs-2"> <span class="visible-xs-block action-label">Notes</span> <?= $detnoteicon; ?></div>
						<div class="col-xs-5"> <span class="visible-xs-block action-label">Edit</span>
							<a href="<?php echo $config->pages->ajax."load/edit-detail/cart/?line=".$detail['recno']; ?>" class="btn btn-sm btn-warning update-line" data-line="<?= $detail['recno']; ?>" data-itemid="<?= $detail['itemid']; ?>" data-custid="<?php echo $carthead['custid']; ?>" data-kit="<?php echo $detail['kititemflag']; ?>">
								<i class="fa fa-pencil fa-1-5x" aria-hidden="true"></i><span class="sr-only">Edit</span>
							</a>&nbsp;
							<form class="inline-block" action="<?php echo $config->pages->cart."redir/"; ?>" method="post">
								<input type="hidden" name="action" value="remove-line">
								<input type="hidden" name="linenbr" value="<?= $detail['linenbr']; ?>">
								<button type="submit" class="btn btn-sm btn-danger" name="button">
									<i class="fa fa-trash fa-1-5x" aria-hidden="true"></i><span class="sr-only">Delete</span>
								</button>
							</form>
						</div>
					</div>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<br>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#item-lookup-modal">
	<span class="glyphicon glyphicon-plus"></span> Add Item
</button>
<br>
