<div class="row">
	<div class="col-sm-6">
		<img src="<?= $appconfig->companylogo->url; ?>" alt="<?= $appconfig->companydisplayname.' logo'; ?>">
	</div>
	<div class="col-sm-6 text-right">
		<h1>Order # <?= $ordn; ?></h1>
	</div>
</div>
<div class="row">
	<div class="col-xs-6"></div>

	<div class="col-xs-6">
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
	<div class="col-xs-6">
		<div class="page-header"><h3>Bill-to</h3></div>
		<address>
			<?= $order->custname; ?><br>
			<?= $order->btadr1; ?><br>
			<?php if (strlen($order->btadr2) > 0) : ?>
				<?= $order->btadr2; ?><br>
			<?php endif; ?>
			<?= $order->btcity.", ".$order->btstate." ".$order->btzip; ?>
		</address>
	</div>
	<div class="col-xs-6">
		<div class="page-header"><h3>Ship-to</h3></div>
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
		<th class="text-center">Item ID/Cust Item ID</th>  <th class="text-right">Qty</th>
		<th class="text-right" width="100">Price</th>
		<th class="text-right">Line Total</th>
	</tr>
	<?php  $details = $orderdisplay->get_orderdetails($order); ?>
	<?php foreach ($details as $detail) : ?>
		<tr class="detail">
			<td>
				<?= $detail->itemid; ?>
				<?php if (strlen($detail->vendoritemid)) { echo ' '.$detail->vendoritemid;} ?>
				<br>
				<small><?= $detail->desc1. ' ' . $detail->desc2 ; ?></small>
			</td>
			<td class="text-right"><?= intval($detail->qtyordered); ?></td>
			<td class="text-right">$ <?= formatmoney($detail->price); ?></td>
			<td class="text-right">$ <?= formatmoney($detail->price * $detail->qtyordered) ?> </td>
		</tr>
	<?php endforeach; ?>
	<tr>
		<td></td> <td>Subtotal</td> <td></td> <td class="text-right">$ <?= formatmoney($order->odrsubtot); ?></td>
	</tr>
	<tr>
		<td></td><td>Tax</td> <td></td> <td colspan="2" class="text-right">$ <?= formatmoney($order->odrtax); ?></td>
	</tr>
	<tr>
		<td></td><td>Freight</td> <td></td> <td class="text-right">$ <?= formatmoney($order->odrfrt); ?></td>
	</tr>
	<tr>
		<td></td><td>Misc.</td> <td></td><td class="text-right">$ <?= formatmoney($order->odrmis); ?></td>
	</tr>
	<tr>
		<td></td><td>Total</td> <td></td> <td class="text-right">$ <?= formatmoney($order->odrtotal); ?></td>
	</tr>
</table>
