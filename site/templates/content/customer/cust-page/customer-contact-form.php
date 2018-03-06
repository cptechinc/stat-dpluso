<legend>Company Contact</legend>
<table class="table table-striped table-bordered table-condensed">
	<tr>
		<td class="control-label">Contact:</td>
		<td><?php echo $customer['contact']; ?></td>
	</tr>
	<tr>
		<td class="control-label">Address: </td>
		<td><?php echo $customer['addr1']; ?> </td>
	</tr>
	<?php if ($cust_address2 != '') : ?>
		<tr>
			<td class="control-label">Address 2: </td>
			<td><?php echo $customer['addr2']; ?> </td>
		</tr>
	<?php endif; ?>
	<tr>
		<td class="control-label">City, St. Zip: </td>
		<td><?php echo $customer['city'].', '.$customer['state'].' '.$customer['zip']; ?> </td>
	</tr>
	<tr>
		<td class="control-label">Phone: </td>
		<td><a href="tel:<?php echo $customer['phone']?>"><?php echo $customer['phone']; ?></a></td>
	</tr>
	<tr>
		<td class="control-label">Email: </td>
		<td><a href="mailto:<?php echo $customer['email']?>"><?php echo $customer['email']; ?></a></td>
	</tr>
	<tr>
		<td class="control-label">Date Entered: </td>
		<td>
			<div class="input-group" style="width: 180px;">
				<div class="input-group-addon input-sm"><span class="glyphicon glyphicon-calendar"></span></div>
				<input type="text" placeholder="MM/DD/YYYY" class="form-control input-sm text-right" value="<?php echo $customer['dateentered']; ?>">
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2"><h4>Customer Sales data last 12 months</h4></td>
	</tr>
	<tr>
		<td class="control-label">Last Sale Date: </td>
		<td>
			<div class="input-group" style="width: 180px;">
				<div class="input-group-addon input-sm"><span class="glyphicon glyphicon-calendar"></span></div>
				<input type="text" placeholder="MM/DD/YYYY" class="form-control input-sm text-right" value="<?= DplusDateTime::format_date($customer['lastsaledate']); ?>">
			</div>
		</td>
	</tr>
	<tr>
		<td class="control-label">Amount Sold</td>
		<td class="text-right">$ <?= $page->stringerbell->format_money($customer['amountsold']); ?></td>
	</tr>
	<tr>
		<td class="control-label">Times Sold</td>
		<td class="text-right"><?php echo $customer['timesold']; ?></td>
	</tr>
	 <tr>
		<td>
			<?php $resultsurl = $config->pages->ajax.'load/products/item-search-results/cart/?custID='.urlencode($custID).'&shipID='.urlencode($shipID); ?>
			<button class="btn btn-primary" data-toggle="modal" data-target="#add-item-modal" data-addtype="cart" data-resultsurl="<?= $resultsurl; ?>" data-nonstock>
				<span class="glyphicon glyphicon-plus"></span> Add Item
			</button>
		</td>
	 </tr>
</table>
