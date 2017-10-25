<?php $quote = $quotedisplay->get_quote();  ?>
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
		<h4>Bill-to</h4>
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
		<h4>Ship-to</h4>
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
	<?php  $details = get_quote_details(session_id(), $qnbr, false); ?>
	<?php foreach ($details as $detail) : ?>
		<?php $qtyo = $detail['ordrqty'] + 0; ?>
		<tr class="detail">
			<td>
				<?= $detail['itemid']; ?>
				<?php if (strlen($detail['vendoritemid'])) { echo ' '.$detail['vendoritemid'];} ?>
				<br>
				<small><?= $detail['desc1']. ' ' . $detail['desc2'] ; ?></small>
			</td>
			<td>
                <a href="<?= $config->pages->ajax."load/edit-detail/quote/?qnbr=".$detail['quotenbr']."&line=".$detail['linenbr']; ?>" class="btn btn-xs btn-warning update-line" data-line="<?= $detail['recno']; ?>" data-itemid="<?= $detail['itemid']; ?>" data-kit="<?= $detail['kititemflag']; ?>"  data-custid="<?= $quote->custid; ?>">
                    <i class="glyphicon glyphicon-eye-open"></i>
                </a>
            </td>
			<td class="text-right"> <?= $qtyo ; ?> </td>
			<td class="text-right">$ <?= formatmoney($detail['ordrprice']); ?></td>
			<td class="text-right">$ <?= formatmoney($detail['ordrprice'] * $qtyo) ?> </td>
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
		<a href="<?= $config->pages->customer.urlencode($quote->custid).'/'; ?>" class="btn btn-block btn-primary">
			Proceed to Customer Page
		</a>
	</div>
	<div class="col-sm-6">
		<a href="<?= $config->pages->quotes.'redir/?action=edit-quote&qnbr='.$qnbr; ?>" class="btn btn-block btn-warning">
			<i class="fa fa-pencil" aria-hidden="true"></i> Edit Quote
		</a>
	</div>
</div>
