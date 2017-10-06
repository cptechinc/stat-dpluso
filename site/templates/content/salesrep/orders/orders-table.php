<table class="table table-striped table-bordered table-condensed" id="orders-table">
	<thead>
       <?php include $config->paths->content.'salesrep/orders/orders-thead-rows.php'; ?>
    </thead>
    <tbody>
    	<?php if ($input->get->ordn) : ?>
			<?php if ($ordercount == 0 && $sanitizer->text($input->get->ordn) == '') : ?>
                <tr> <td colspan="12" class="text-center">No Orders found! Try using a date range to find the order(s) you are looking for.</td> </tr>
            <?php endif; ?>
        <?php endif; ?>
		<?php $orderpanel->get_orders(); ?>
        <?php foreach($orderpanel->orders as $order) : ?>
            <tr class="<?= $order->generate_rowclass($orderpanel); ?>" id="<?php echo $order->orderno; ?>">
            	<td class="text-center"><?= $order->generate_clicktoexpandlink($orderpanel); ?></td>
                <td><?php echo $order->orderno; ?></td>
                <td><?= $order->custid; ?><br><?= get_customer_name_from_order(session_id(), $order->orderno); ?></td>
                <td><?php echo $order->custpo; ?></td>
                <td>
					<a href="<?= $order->generate_customershiptourl(); ?>"><?= $order->shiptoid; ?></a>
                    <?= $order->generate_shiptopopover(); ?>
                </td>
                <td align="right">$ <?php echo formatmoney($order->odrtotal); ?></td>
                <td align="right" ><?php echo $order->orderdate; ?></td>
                <td align="right"><?php echo $order->status; ?></td>
                <td colspan="3">
                    <span class="col-xs-3"><?= $order->generate_loaddocumentslink($orderpanel, '0'); ?></span>
                    <span class="col-xs-3"><?= $order->generate_loadtrackinglink($orderpanel); ?></span>
                    <span class="col-xs-3"><?= $order->generate_loadnoteslink($orderpanel, '0'); ?></span>
                    <span class="col-xs-3"><?= $order->generate_editorderlink(); ?></span>
                </td>
            </tr>

            <?php if ($order->orderno == $input->get->text('ordn')) : ?>
            	<?php if ($input->get->show == 'documents' && (!$input->get('item-documents'))) : ?>
                	<?php include $config->paths->content.'salesrep/orders/order-documents-rows.php'; ?>
                <?php endif; ?>

               <?php include $config->paths->content.'salesrep/orders/order-detail-rows.php'; ?>

               <?php include $config->paths->content.'salesrep/orders/order-totals.php'; ?>

               <?php if ($input->get->text('show') == 'tracking') : ?>
					<?php include $config->paths->content.'salesrep/orders/order-tracking-rows.php'; ?>
               <?php endif; ?>

        	<?php if ($order->error == 'Y') : ?>
                <tr class="detail bg-danger" >
                    <td colspan="2" class="text-center"><b class="text-danger">Error:</b></td>
                    <td colspan="2"><?php echo $order->errormsg; ?></td> <td></td> <td></td>
                    <td colspan="2"> </td> <td></td> <td></td> <td></td>
                </tr>
            <?php endif; ?>

             <tr class="detail last-detail">
             	<td colspan="2">
             		<?= $order->generate_viewprintlink($orderpanel); ?>
             	</td>
				<td colspan="3">
					<a href="<?= $config->pages->actions.'all/load/list/order/?ordn='.$order->orderno.'&modal=modal'; ?>" class="load-into-modal" data-modal="#ajax-modal">
						<span class="h3"><i class="glyphicon glyphicon-check" aria-hidden="true"></i></span> <span>View Associated Actions</span>
					</a>
				</td>
                <td>
                	<?= $order->generate_viewrelatedactionslink($orderpanel); ?>
                </td>
                <td></td>  <td></td>
                <td colspan="2">
                    <div class="pull-right"> <a class="btn btn-danger btn-sm load-link" href="<?php echo $orderlink; ?>" <?php echo $orderpanel->ajaxdata; ?>>Close</a> </div>
                </td>
             	<td></td>
             </tr>
        	<?php endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>
