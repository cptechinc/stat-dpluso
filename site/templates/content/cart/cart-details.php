<div id="no-more-tables" class="form-group">
    <table class="table-condensed cf order-details numeric">
        <thead class="cf">
            <tr>
                <th>Item</th> <th class="numeric">Price</th> <th class="numeric">Qty</th> <th class="numeric">Total</th>
                <th>Rqstd Ship Date</th> <th>Warehouse</th>
                <th>
                	<div class="row">
                    	<div class="col-xs-3">Details</div><div class="col-xs-3">Documents</div> <div class="col-xs-3">Notes</div> <div class="col-xs-3">Update</div>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
       		<?php $orderdetails = getcart(session_id(), false); ?>
            <?php foreach ($orderdetails as $detail) : ?>
            	<?php
                    $detail['havenote'] = hasdplusnote(session_id(), session_id(), $detail['linenbr'], $config->dplusnotes['cart']['type']);
                    $detailnoteurl = $config->pages->notes.'redir/?action=get-cart-notes&linenbr='.$detail['linenbr'].'&modal=modal';
    				if ($detail['havenote'] != 'Y') {
    					$detnoteicon = '<a class="load-notes text-muted" href="'.$detailnoteurl.'" data-modal="#ajax-modal"><i class="material-icons md-36" title="View order notes">&#xE0B9;</i></a>';
    				} else {
    					$detnoteicon = '<a class="load-notes" href="'.$detailnoteurl.'" data-modal="#ajax-modal"> <i class="material-icons md-36" title="View order notes">&#xE0B9;</i></a>';
    				}
				?>
            <tr>
                <td data-title="ItemID/Desc">
                    <?= $detail['itemid']; ?>
                    <?php if (strlen($detail['vendoritemid'])) { echo ' '.$detail['vendoritemid'];} ?>
                    <br> <small><?= $detail['desc1']; ?></small>
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
                        <div class="col-xs-3"> <span class="visible-xs-block action-label">Documents</span> <i class="material-icons md-36">&#xE873;</i> </div>
                        <div class="col-xs-3"> <span class="visible-xs-block action-label">Notes</span> <?= $detnoteicon; ?></div>
                        <div class="col-xs-3"> <span class="visible-xs-block action-label">Update</span>
                            <a href="<?php echo $config->pages->ajax."load/edit-detail/cart/?line=".$detail['recno']; ?>" class="btn btn-sm btn-warning update-line" data-line="<?= $detail['recno']; ?>" data-itemid="<?= $detail['itemid']; ?>" data-custid="<?php echo $carthead['custid']; ?>" data-kit="<?php echo $detail['kititemflag']; ?>">
                                <i class="material-icons">&#xE3C9;</i>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
			<?php endforeach; ?>
        </tbody>
    </table>
</div>
<br>
<?php $resultsurl = $config->pages->ajax.'load/products/item-search-results/cart/?custID='.urlencode($carthead['custid']).'&shipID='.urlencode($carthead['shiptoid']); ?>
<?php if (isset($custID)) : ?>
    <button class="btn btn-primary" data-toggle="modal" data-target="#add-item-modal" data-addtype="cart" data-resultsurl="<?= $resultsurl; ?>">
        <span class="glyphicon glyphicon-plus"></span> Add Item
    </button>
<?php else : ?>
    <button class="btn btn-primary" data-toggle="modal" data-target="#add-item-modal" data-addtype="cart" data-resultsurl="<?= $resultsurl; ?>">
        <span class="glyphicon glyphicon-plus"></span> Add Item
    </button>
<?php endif; ?>
<br>
