<div class="row">
	<div class="col-xs-6">
		<img src="<?= $appconfig->companylogo->url; ?>" alt="<?= $appconfig->companydisplayname.' logo'; ?>">
	</div>
	<div class="col-xs-6 text-right">
		<h1>Quote # <?= $quote->quotnbr; ?></h1>
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
			<tr> <td>Quote Date</td> <td><?= $quote->quotdate; ?></td> </tr>
			<tr> <td>Review Date</td> <td><?= $quote->revdate; ?></td> </tr>
			<tr> <td>Expire Date</td> <td><?= $quote->expdate; ?></td> </tr>
			<tr> <td>CustID</td> <td><?= $quote->custid; ?></td> </tr>
			<tr> <td>Customer PO</td> <td><?= $quote->custpo; ?></td> </tr>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-xs-6">
		<div class="page-header"><h3>Bill-to</h3></div>
		<address>
			<?= $quote->btname; ?><br>
			<?= $quote->btadr1; ?><br>
			<?php if (strlen($quote->btadr2) > 0) : ?>
				<?= $quote->btadr2; ?><br>
			<?php endif; ?>
			<?= $quote->btcity.", ".$quote->btstate." ".$quote->btzip; ?>
		</address>
	</div>
	<div class="col-xs-6">
		<div class="page-header"><h3>Ship-to</h3></div>
		<address>
			<?php if (strlen($quote->stname) > 0) : ?>
				<?= $quote->stname; ?><br>
			<?php endif; ?>
			<?= $quote->stadr1; ?><br>
			<?php if (strlen($quote->stadr2) > 0) : ?>
				<?= $quote->stadr2; ?><br>
			<?php endif; ?>
			<?= $quote->stcity.", ".$quote->ststate." ".$quote->stzip; ?>
		</address>
	</div>
</div>
<table class="table table-bordered table-striped">
	 <tr class="detail item-header">
		<th class="text-center">Item ID/Cust Item ID</th>
		<th class="text-right">Qty</th>
		<th class="text-right" width="100">Price</th>
		<th class="text-right">Line Total</th>
	</tr>
	<?php $details = $quotedisplay->get_quotedetails($quote); ?>
	<?php foreach ($details as $detail) : ?>
		<tr class="detail">
			<td>
				<?= $detail->itemid; ?>
				<?php if (strlen($detail->vendoritemid)) { echo ' '.$detail->vendoritemid;} ?>
				<br>
				<small><?= $detail->desc1. ' ' . $detail->desc2; ?></small>
			</td>
			<td class="text-right"> <?= intval($detail->ordrqty); ?> </td>
			<td class="text-right">$ <?= formatmoney($detail->ordrprice); ?></td>
			<td class="text-right">$ <?= formatmoney($detail->ordrprice * intval($detail->ordrqty)) ?> </td>
		</tr>
	<?php endforeach; ?>
	<tr>
		<td></td> <td>Subtotal</td> <td></td> <td class="text-right">$ <?= formatmoney($quote->subtotal); ?></td>
	</tr>
	<tr>
		<td></td><td>Tax</td> <td></td> <td colspan="2" class="text-right">$ <?= formatmoney($quote->salestax); ?></td>
	</tr>
	<tr>
		<td></td><td>Freight</td> <td></td> <td class="text-right">$ <?= formatmoney($quote->freight); ?></td>
	</tr>
	<tr>
		<td></td><td>Misc.</td> <td></td><td class="text-right">$ <?= formatmoney($quote->miscellaneous); ?></td>
	</tr>
	<tr>
		<td></td><td>Total</td> <td></td> <td class="text-right">$ <?= formatmoney($quote->order_total); ?></td>
	</tr>
</table>
