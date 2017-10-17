<?php $order = get_orderhead(session_id(), $ordn, true, false);  ?>
<div class="row">
	<div class="col-sm-6">
		<img src="<?= $config->urls->files."images/dplus.png"; ?>" alt="">
	</div>
	<div class="col-sm-6 text-right">
		<h1>Order # <?= $order->orderno; ?></h1>
	</div>
</div>
<div class="row">
	<div class="col-sm-6"></div>

	<div class="col-sm-6">
		<table class="table table-bordered table-striped table-condensed">
			<tr> <td>Order Date</td> <td><?= $order->orderdate; ?></td> </tr>
			<tr> <td>Request Date</td> <td><?= $order->rqstdate; ?></td> </tr>
			<tr> <td>Status</td> <td><?= $order->status; ?></td> </tr>
			<tr> <td>CustID</td> <td><?= $order->custid; ?></td> </tr>
			<tr> <td>Customer PO</td> <td><?= $order->custpo; ?></td> </tr>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<h4>Bill-to</h4>
		<address>
			<?= $order->custname; ?><br>
			<?= $order->btadr1; ?><br>
			<?php if (strlen($order->btadr2) > 0) : ?>
				<?= $order->btadr2; ?><br>
			<?php endif; ?>
			<?= $order->btcity.", ".$order->btstate." ".$order->btzip; ?>
		</address>
	</div>
	<div class="col-sm-6">
		<h4>Ship-to</h4>
		<address>
			<?= $order->sname; ?><br>
			<?= $order->saddress; ?><br>
			<?php if (strlen($order->saddress2) > 0) : ?>
				<?= $order->saddress2; ?><br>
			<?php endif; ?>
			<?= $order->scity.", ".$order->sst." ".$order->szip; ?>
		</address>
	</div>
</div>
<table class="table table-bordered table-striped">
	 <tr class="detail item-header">
		<th class="text-center">Item ID/Cust Item ID</th>
		<th class="text-center">Detail</th>
		<th class="text-right">Qty</th>
		<th class="text-right" width="100">Price</th>
		<th class="text-right">Line Total</th>
	</tr>
	<?php  $details = get_order_details(session_id(), $ordn, false); ?>
	<?php foreach ($details as $detail) : ?>
		<?php $qtyo = $detail['qtyordered'] + 0; ?>
		<tr class="detail">
			<td>
				<?= $detail['itemid']; ?>
				<?php if (strlen($detail['vendoritemid'])) { echo ' '.$detail['vendoritemid'];} ?>
				<br>
				<small><?= $detail['desc1']. ' ' . $detail['desc2'] ; ?></small>
			</td>
			<td>
				<a href="<?= $config->pages->ajax."load/edit-detail/order/?ordn=".$detail['orderno']."&line=".$detail['linenbr']; ?>" class="btn btn-xs btn-warning update-line" data-kit="<?= $detail['kititemflag']; ?>" data-itemid="<?= $detail['itemid']; ?>" data-custid="<?= $order->custid; ?>">
	                <i class="glyphicon glyphicon-eye-open"></i>
	            </a>
			</td>
			<td class="text-right"> <?= $qtyo ; ?> </td>
			<td class="text-right">$ <?= formatmoney($detail['price']); ?></td>
			<td class="text-right">$ <?= formatmoney($detail['price'] * $qtyo) ?> </td>
		</tr>
	<?php endforeach; ?>
	<tr>
		<td></td> <td><b>Subtotal</b></td> <td></td> <td></td> <td class="text-right">$ <?= formatmoney($order->odrsubtot); ?></td>
	</tr>
	<tr>
		<td></td><td><b>Tax</b></td> <td></td> <td colspan="2" class="text-right">$ <?= formatmoney($order->odrtax); ?></td>
	</tr>
	<tr>
		<td></td><td><b>Freight</b></td> <td></td> <td></td> <td class="text-right">$ <?= formatmoney($order->odrfrt); ?></td>
	</tr>
	<tr>
		<td></td><td><b>Misc.</b></td> <td></td> <td></td><td class="text-right">$ <?= formatmoney($order->odrmis); ?></td>
	</tr>
	<tr>
		<td></td><td><b>Total</b></td> <td></td> <td></td> <td class="text-right">$ <?= formatmoney($order->odrtotal); ?></td>
	</tr>
</table>
