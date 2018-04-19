<table class="table table-striped table-bordered table-condensed" id="orders-table">
	<thead>
       <?php include $config->paths->content.'customer/cust-page/sales-orders/thead-rows.php'; ?>
    </thead>
    <tbody>
		<?php if (isset($input->get->ordn)) : ?>
			<?php if ($orderpanel->count == 0 && $input->get->text('ordn') == '') : ?>
                <tr> <td colspan="12" class="text-center">No Orders found! Try using a date range to find the order(s) you are looking for.</td> </tr>
            <?php endif; ?>
        <?php endif; ?>
        <?php $orderpanel->get_orders(); ?>
        <?php foreach ($orderpanel->orders as $order) : ?>
            <tr class="<?= $orderpanel->generate_rowclass($order); ?>" id="<?= $order->orderno; ?>">
            	<td class="text-center"><?= $orderpanel->generate_expandorcollapselink($order);?></td>
                <td> <?= $order->orderno; ?></td>
                <td><?= $order->custpo; ?></td>
                <td>
                    <a href="<?= $orderpanel->generate_customershiptourl($order); ?>"><?= $order->shiptoid; ?></a>
                    <?= $orderpanel->generate_shiptopopover($order); ?>
                </td>
                <td align="right">$ <?= $page->stringerbell->format_money($order->ordertotal); ?></td> <td align="right" ><?= $order->orderdate; ?></td>
                <td align="right"><?=  $order->status; ?></td>
                <td colspan="4">
                    <span class="col-xs-3"><?= $orderpanel->generate_loaddocumentslink($order); ?></span>
                    <span class="col-xs-3"><?= $orderpanel->generate_loadtrackinglink($order); ?></span>
                    <span class="col-xs-3"><?= $orderpanel->generate_loaddplusnoteslink($order, '0'); ?></span>
                    <span class="col-xs-3"><?= $orderpanel->generate_editlink($order); ?></span>
                </td>
            </tr>

            <?php if ($order->orderno == $input->get->text('ordn')) : ?>
            	<?php if ($input->get->show == 'documents' && (!$input->get('item-documents'))) : ?>
                	<?php include $config->paths->content.'customer/cust-page/orders/documents-rows.php'; ?>
                <?php endif; ?>

               <?php include $config->paths->content.'customer/cust-page/sales-orders/detail-rows.php'; ?>

               <?php include $config->paths->content.'customer/cust-page/sales-orders/totals-row.php'; ?>

               <?php if ($input->get->text('show') == 'tracking') : ?>
					<?php include $config->paths->content.'customer/cust-page/sales-orders/tracking-rows.php'; ?>
               <?php endif; ?>

        	<?php if ($order->has_error()) : ?>
                <tr class="detail bg-danger" >
                    <td colspan="2" class="text-center"><b class="text-danger">Error:</b></td>
                    <td colspan="2"><?= $order->errormsg; ?></td> <td></td> <td></td>
                    <td colspan="2"> </td> <td></td> <td></td> <td></td>
                </tr>
            <?php endif; ?>

             <tr class="detail last-detail">
             	<td colspan="2">
					<?= $orderpanel->generate_viewprintlink($order); ?>
				</td>
				<td colspan="3">
					<?= $orderpanel->generate_viewlinkeduseractionslink($order); ?>
				</td>
                <td>
                	<a class="btn btn-primary btn-sm" onClick="reorder('<?= $order->orderno; ?>')">
                    	<span class="glyphicon glyphicon-shopping-cart" title="re-order"></span> Reorder Order
                    </a>
                </td>
                <td></td>
				<td></td>
                <td colspan="2">
                    <div class="pull-right"> <a class="btn btn-danger btn-sm load-link" href="<?= $orderpanel->generate_closedetailsurl($order); ?>" <?php echo $orderpanel->ajaxdata; ?>>Close</a> </div>
                </td>
             	<td></td>
             </tr>
        	<?php endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>
