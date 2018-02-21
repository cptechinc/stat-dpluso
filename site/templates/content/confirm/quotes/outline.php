<div class="row">
	<div class="col-sm-6">
		<img src="<?= $appconfig->companylogo->url; ?>" alt="<?= $appconfig->companydisplayname.' logo'; ?>">
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
            <tr> <td>Ship Via</td> <td><?= $quote->shipviacd.' - '.$quote->shipviadesc; ?></td> </tr>
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
			<?= $quote->billname; ?><br>
			<?= $quote->billaddress; ?><br>
			<?php if (strlen($quote->billaddress2) > 0) : ?>
				<?= $quote->billaddress2; ?><br>
			<?php endif; ?>
			<?= $quote->billcity.", ".$quote->billstate." ".$quote->billzip; ?>
		</address>
	</div>
	<div class="col-sm-6">
		<div class="page-header"><h3>Ship-to</h3></div>
		<address>
			<?= $quote->shipname; ?><br>
			<?= $quote->shipaddress; ?><br>
			<?php if (strlen($quote->shipaddress2) > 0) : ?>
				<?= $quote->shipaddress2; ?><br>
			<?php endif; ?>
			<?= $quote->shipcity.", ".$quote->shipstate." ".$quote->shipzip; ?>
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
			<td class="text-right"> <?= intval($detail->qty); ?> </td>
			<td class="text-right">$ <?= formatmoney($detail->ordrprice); ?></td>
			<td class="text-right">$ <?= formatmoney($detail->ordrprice * intval($detail->qty)) ?> </td>
		</tr>
	<?php endforeach; ?>
	<tr>
		<td></td> <td><b>Subtotal</b></td> <td colspan="3" class="text-right">$ <?= formatmoney($quote->subtotal); ?></td>
	</tr>
	<tr>
		<td></td><td><b>Tax</b></td> <td colspan="3" class="text-right">$ <?= formatmoney($quote->salestax); ?></td>
	</tr>
	<tr>
		<td></td><td><b>Freight</b></td> <td colspan="3" class="text-right">$ <?=formatmoney($quote->freight); ?></td>
	</tr>
	<tr>
		<td></td><td><b>Misc.</b></td> <td colspan="3" class="text-right">$ <?= formatmoney($quote->misccost); ?></td>
	</tr>
	<tr>
		<td></td><td><b>Total</b></td> <td colspan="3" class="text-right">$ <?= formatmoney($quote->ordertotal); ?></td>
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
