<div id="no-more-tables" class="form-group">
	<table class="table-condensed cf order-details numeric">
		<thead class="cf">
			<tr>
				<th>Item</th> <th class="numeric text-right">Price</th> <th class="numeric text-right">Qty</th> <th class="numeric text-right">Total</th>
				<th class="text-center">Rqstd Ship Date</th> <th>Warehouse</th>
				<th>
					<div class="row">
						<div class="col-xs-3">Details</div><div class="col-xs-2">Docs</div> <div class="col-xs-2">Notes</div> <div class="col-xs-5">Edit</div>
					</div>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php $details = get_cartdetails(session_id(), true); ?>
			<?php foreach ($details as $detail) : ?>
				<tr class="cart-item">
					<td data-title="ItemID/Desc">
						<?php if ($detail->has_error()) : ?>
							<div class="btn-sm btn-danger">
							  <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>Error!</strong> <?= $detail->errormsg; ?>
							</div>
						<?php else : ?>
							<?= $detail->itemid; ?>
							<?= (strlen($detail->vendoritemid)) ? $detail->vendoritemid : ''; ?>
							<br> <small><?= $detail->desc1; ?></small>
						<?php endif; ?>
					</td>
					<td data-title="Price" class="text-right">$ <?= $page->stringerbell->format_money($detail->price); ?></td>
					<td data-title="Ordered" class="text-right"><?= $detail->qty + 0; ?></td>
					<td data-title="Total" class="text-right">$ <?= $page->stringerbell->format_money($detail->totalprice); ?></td>
					<td data-title="Requested Ship Date"  class="text-center"><?= $detail->rshipdate; ?></td>
					<td data-title="Warehouse"><?= $detail->whse; ?></td>
					<td class="action">
						<div class="row">
							<div class="col-xs-3"> <span class="visible-xs-block action-label">Details</span>
								<a href="<?= $config->pages->ajax."load/view-detail/cart/?line=".$detail->linenbr; ?>" class="btn btn-sm btn-primary view-item-details" data-itemid="<?= $detail->itemid; ?>" data-kit="<?= $detail->kititemflag; ?>" data-modal="#ajax-modal"><i class="material-icons">&#xE8DE;</i></a>
							</div>
							<div class="col-xs-2"> <span class="visible-xs-block action-label">Docs</span> <span class="text-muted"><i class="material-icons md-36">&#xE873;</i></span> </div>
							<div class="col-xs-2"> <span class="visible-xs-block action-label">Notes</span> <?= $cartdisplay->generate_loaddplusnoteslink($cart, $detail->linenbr); ?></div>
							<div class="col-xs-5"> <span class="visible-xs-block action-label">Edit</span>
								<?= $cartdisplay->generate_detailvieweditlink($cart, $detail); ?>
								<form class="inline-block" action="<?= $config->pages->cart."redir/"; ?>" method="post">
									<input type="hidden" name="action" value="remove-line">
									<input type="hidden" name="linenbr" value="<?= $detail->linenbr; ?>">
									<button type="submit" class="btn btn-md btn-danger" name="button">
										<span class="glyphicon glyphicon-trash"></span><span class="sr-only">Delete</span>
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
