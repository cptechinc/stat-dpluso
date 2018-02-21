<div id="no-more-tables">
    <table class="table-condensed cf order-details">
        <thead class="cf">
            <tr>
                <th>Item</th> <th class="numeric" width="90">Price</th> <th class="numeric">Qty</th> <th class="numeric" >Total</th>
                <th class="numeric">Shipped</th> <th>Rqstd Ship Date</th> <th>Whse</th>
                <th>
                	<div class="row">
                    	<div class="col-xs-2 action-padding">Details</div><div class="col-xs-2 action-padding">Docs</div> <div class="col-xs-2 action-padding">Notes</div> <div class="col-xs-6 action-padding">Edit</div>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
       		<?php $order_details = $editorderdisplay->get_orderdetails($order) ?>
            <?php foreach ($order_details as $detail) : ?>
            <tr class="numeric">
                <td data-title="ItemID/Desc">
                    <?= $detail->itemid; ?>
                    <?php if ($detail->errormsg != '') : ?>
                        <div class="btn-sm btn-danger">
                          <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>Error!</strong> <?= $detail->errormsg; ?>
                        </div>
                    <?php else : ?>
                        <?php if (strlen($detail->vendoritemid)) { echo ' '.$detail->vendoritemid;} ?>
                        <br> <?= $detail->desc1; ?>
					<?php endif; ?>
                </td>
                <td data-title="Price" class="text-right">$ <?= formatMoney($detail->price); ?></td>
                <td data-title="Ordered" class="text-right"><?= $detail->qty + 0; ?></td>
                <td data-title="Total" class="text-right">$ <?= formatMoney($detail->totalprice); ?></td>
                <td data-title="Shipped" class="text-right"><?= $detail->qtyshipped + 0; ?></td>
                <td data-title="Requested Ship Date" class="text-right"><?= $detail->rshipdate; ?></td>
                <td data-title="Warehouse"><?= $detail->whse; ?></td>
                <td class="action">
                    <div class="row">
                        <div class="col-xs-2 action-padding">
                            <span class="visible-xs-block action-label">Details</span>
							<?= $editorderdisplay->generate_viewdetaillink($order, $detail); ?>
                        </div>
                        <div class="col-xs-2 action-padding">
                            <span class="visible-xs-block action-label">Documents</span> <?= $editorderdisplay->generate_loaddocumentslink($order, $detail); ?></div>
                        <div class="col-xs-2 action-padding">
                            <span class="visible-xs-block action-label">Notes</span> <?= $editorderdisplay->generate_loaddplusnoteslink($order, $detail->linenbr); ?></div>
                        <div class="col-xs-6 action-padding">
                            <span class="visible-xs-block action-label">Update</span>
                            <?= $editorderdisplay->generate_detailvieweditlink($order, $detail); ?>
                            <?php if ($editorderdisplay->canedit) : ?>
                                <?= $editorderdisplay->generate_deletedetailform($order, $detail); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>
            </tr>
			<?php endforeach; ?>
        </tbody>
    </table>
</div>
