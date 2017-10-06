<table class="table table-striped table-bordered table-condensed" id="orders-table">
	<thead>
       <?php include $config->paths->content.'customer/cust-page/orders/orders-thead-rows.php'; ?>
    </thead>
    <tbody>
    	<?php if ($input->get->ordn) : ?>
			<?php if ($orderpanel->count == 0 && $input->get->text('ordn') == '') : ?>
                <tr> <td colspan="12" class="text-center">No Orders found! Try using a date range to find the order(s) you are looking for.</td> </tr>
            <?php endif; ?>
        <?php endif; ?>
        <?php $orderpanel->get_orders(); ?>
        <?php foreach($orderpanel->orders as $order) : ?>
            <tr class="<?= $order->generate_rowclass($orderpanel); ?>" id="<?= $order->orderno; ?>">
            	<td class="text-center"><?= $order->generate_clicktoexpandlink($orderpanel); ?></td>
                <td> <?= $order->orderno; ?></td>
                <td><?= $order->custpo; ?></td>
                <td>
                    <a href="<?= $order->generate_customershiptourl(); ?>"><?= $order->shiptoid; ?></a>
                    <?= $order->generate_shiptopopover(); ?>
                </td>
                <td align="right">$ <?= formatmoney($order->odrtotal); ?></td> <td align="right" ><?= $order->orderdate; ?></td>
                <td align="right"><?=  $order->status; ?></td>
                <td colspan="4">
                    <span class="col-xs-3"><?= $order->generate_loaddocumentslink($orderpanel, '0'); ?></span>
                    <span class="col-xs-3"><?= $order->generate_loadtrackinglink($orderpanel); ?></span>
                    <span class="col-xs-3"><?= $order->generate_loadnoteslink($orderpanel, '0'); ?></span>
                    <span class="col-xs-3"><?= $order->generate_editorderlink(); ?></span>
                </td>
            </tr>

            <?php if ($order->orderno == $input->get->text('ordn')) : ?>
            	<?php if ($input->get->show == 'documents' && (!$input->get('item-documents'))) : ?>
                	<?php include $config->paths->content.'customer/cust-page/orders/order-documents-rows.php'; ?>
                <?php endif; ?>

               <?php include $config->paths->content.'customer/cust-page/orders/order-detail-rows.php'; ?>

               <?php include $config->paths->content.'customer/cust-page/orders/order-totals.php'; ?>

               <?php if ($input->get->text('show') == 'tracking') : ?>
					<?php include $config->paths->content.'customer/cust-page/orders/order-tracking-rows.php'; ?>
               <?php endif; ?>

        	<?php if ($order->haserror) : ?>
                <tr class="detail bg-danger" >
                    <td colspan="2" class="text-center"><b class="text-danger">Error:</b></td>
                    <td colspan="2"><?php echo $order['errormsg']; ?></td> <td></td> <td></td>
                    <td colspan="2"> </td> <td></td> <td></td> <td></td>
                </tr>
            <?php endif; ?>

             <tr class="detail last-detail">
             	<td colspan="2">
					<a href="<?= $config->pages->orders."redir/?action=get-order-details&print=true&ordn=".$order->orderno; ?>" target="_blank">
						<span class="h3"><i class="glyphicon glyphicon-print" aria-hidden="true"></i></span> <span>View Printable Order</span>
					</a>
				</td>
				<td colspan="3">
					<a href="<?= $config->pages->actions.'all/load/list/order/?ordn='.$order->orderno.'&modal=modal'; ?>" class="load-into-modal" data-modal="#ajax-modal">
						<span class="h3"><i class="glyphicon glyphicon-check" aria-hidden="true"></i></span> <span>View Associated Actions</span>
					</a>
				</td>
                <td>
                	<a class="btn btn-primary btn-sm" onClick="reorder()">
                    	<span class="glyphicon glyphicon-shopping-cart" title="re-order"></span> Reorder Order
                    </a>
                </td>
                <td></td>  <td></td>
                <td colspan="2">
                    <div class="pull-right"> <a class="btn btn-danger btn-sm load-link" href="<?php echo $order->generate_closedetailsurl($orderpanel); ?>" <?php echo $orderpanel->ajaxdata; ?>>Close</a> </div>
                </td>
             	<td></td>
             </tr>
        	<?php endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>
