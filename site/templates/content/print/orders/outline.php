<div class="row">
	<div class="col-sm-6">
		<img src="<?= $appconfig->companylogo->url; ?>" alt="<?= $appconfig->companydisplayname.' logo'; ?>">
	</div>
	<div class="col-sm-6 text-right">
		<h1>Order # <?= $ordn; ?></h1>
	</div>
</div>
<div class="row">
	<div class="col-xs-6">
		<?php if (!$input->get->print) : ?>
			<a href="<?= $emailurl->getUrl(); ?>" class="btn btn-primary load-into-modal" data-modal="#ajax-modal"><i class="glyphicon glyphicon-envelope" aria-hidden="true"></i> Send as Email</a>
		<?php endif; ?>
	</div>
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
			<?= $order->billaddress; ?><br>
			<?php if (strlen($order->billaddress2) > 0) : ?>
				<?= $order->billaddress2; ?><br>
			<?php endif; ?>
			<?= $order->billcity.", ".$order->billstate." ".$order->billzip; ?>
		</address>
	</div>
	<div class="col-xs-6">
		<div class="page-header"><h3>Ship-to</h3></div>
		<address>
			<?= $order->shipname; ?><br>
			<?= $order->shipaddress; ?><br>
			<?php if (strlen($order->shipaddress2) > 0) : ?>
				<?= $order->shipaddress2; ?><br>
			<?php endif; ?>
			<?= $order->shipcity.", ".$order->shipstate." ".$order->shipzip; ?>
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
			<td class="text-right"><?= intval($detail->qty); ?></td>
			<td class="text-right">$ <?= formatmoney($detail->price); ?></td>
			<td class="text-right">$ <?= formatmoney($detail->price * $detail->qty) ?> </td>
		</tr>
	<?php endforeach; ?>
	<tr>
		<td></td> <td>Subtotal</td> <td></td> <td class="text-right">$ <?= formatmoney($order->subtotal); ?></td>
	</tr>
	<tr>
		<td></td><td>Tax</td> <td></td> <td colspan="2" class="text-right">$ <?= formatmoney($order->salestax); ?></td>
	</tr>
	<tr>
		<td></td><td>Freight</td> <td></td> <td class="text-right">$ <?= formatmoney($order->freight); ?></td>
	</tr>
	<tr>
		<td></td><td>Misc.</td> <td></td><td class="text-right">$ <?= formatmoney($order->misccost); ?></td>
	</tr>
	<tr>
		<td></td><td>Total</td> <td></td> <td class="text-right">$ <?= formatmoney($order->ordertotal); ?></td>
	</tr>
</table>
