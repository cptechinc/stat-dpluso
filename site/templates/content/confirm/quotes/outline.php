<div class="row">
	<div class="col-sm-6">
		<img src="<?= $config->urls->files."images/dplus.png"; ?>" alt="">
	</div>
	<div class="col-sm-6 text-right">
		<h1>Summary for Quote # <?= $qnbr; ?></h1>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<table class="table table-bordered table-striped table-condensed">
			<tr> <td>CustID</td> <td><?= $quote->custid; ?></td> </tr>
			<tr> <td>Quote Date</td> <td><?= $quote->quotdate; ?></td> </tr>
			<tr> <td>Review Date</td> <td><?= $quote->revdate; ?></td> </tr>
			<tr> <td>Expire Date</td> <td><?= $quote->expdate; ?></td> </tr>
			<tr> <td>Terms Code</td> <td><?= $quote->termcode; ?></td> </tr>
			<tr> <td>Tax</td> <td><?= $quote->taxcode; ?></td> </tr>
            <tr> <td>Sales Person</td> <td><?= $quote->sp1; ?></td> </tr>
		</table>
	</div>

	<div class="col-sm-6">
		<table class="table table-bordered table-striped table-condensed">
			<tr> <td>Customer PO</td> <td><?= $quote->custpo; ?></td> </tr>
            <tr> <td>Cust Ref</td> <td><?= $quote->custref; ?></td> </tr>
            <tr> <td>Ship Via</td> <td><?= $quote->sviacode.' - '.$quote->sviacodedesc; ?></td> </tr>
            <tr> <td>FOB</td> <td><?= $quote->fob; ?></td> </tr>
            <tr> <td>Delivery</td> <td><?= $quote->deliverydesc; ?></td> </tr>
            <tr> <td>Whse</td> <td><?= $quote->whse; ?></td> </tr>
            <tr> <td>Care Of</td> <td><?= $quote->careof; ?></td> </tr>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
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
	<div class="col-sm-6">
		<div class="page-header"><h3>Ship-to</h3></div>
		<address>
			<?= $quote->stname; ?><br>
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
		<th class="text-center">Details</th>
		<th class="text-right">Qty</th>
		<th class="text-right" width="100">Price</th>
		<th class="text-right">Line Total</th>
	</tr>
	<?php $details = $quotedisplay->get_quotedetails($quote); ?>
	<?php foreach ($details as $detail) : ?>
		<?php $qtyo = $detail->ordrqty + 0; ?>
		<tr class="detail">
			<td>
				<?= $detail->itemid; ?>
				<?php if (strlen($detail->vendoritemid)) { echo ' '.$detail->vendoritemid;} ?>
				<br>
				<small><?= $detail->desc1. ' ' . $detail->desc2 ; ?></small>
			</td>
			<td>
				<?= $quotedisplay->generate_detailvieweditlink($quote, $detail, $page->bootstrap->createicon('glyphicon glyphicon-eye-open')); ?>
            </td>
			<td class="text-right"> <?= intval($detail->ordrqty); ?> </td>
			<td class="text-right">$ <?= formatmoney($detail->ordrprice); ?></td>
			<td class="text-right">$ <?= formatmoney($detail->ordrprice * $qtyo) ?> </td>
		</tr>
	<?php endforeach; ?>
	<tr>
		<td></td> <td><b>Subtotal</b></td> <td></td> <td></td> <td class="text-right">$ <?= formatmoney($quote->subtotal); ?></td>
	</tr>
	<tr>
		<td></td><td><b>Tax</b></td> <td></td> <td></td> <td class="text-right">$ <?= formatmoney($quote->salestax); ?></td>
	</tr>
	<tr>
		<td></td><td><b>Freight</b></td> <td></td> <td></td><td class="text-right">$ <?=formatmoney($quote->freight); ?></td>
	</tr>
	<tr>
		<td></td><td><b>Misc.</b></td> <td></td> <td></td> <td class="text-right">$ <?= formatmoney($quote->miscellaneous); ?></td>
	</tr>
	<tr>
		<td></td><td><b>Total</b></td> <td></td> <td></td> <td class="text-right">$ <?= formatmoney($quote->order_total); ?></td>
	</tr>
</table>

<div class="row">
	<div class="col-sm-6">
		<?= $quotedisplay->generate_customershiptolink($quote); ?>
	</div>
	<div class="col-sm-6">
		<?= $quotedisplay->generate_editlink($quote); ?>
	</div>
</div>
