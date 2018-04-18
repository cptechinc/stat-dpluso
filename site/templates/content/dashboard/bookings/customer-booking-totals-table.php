<?php $custbookings = $bookingspanel->get_bookingtotalsbycustomer(); ?>
<table class="table table-bordered table-condensed table-striped" id="bookings-by-customer">
	<thead> 
		<tr><th>Customer</th> <th>Amount</th> </tr> 
	</thead>
	<tbody>
		<?php foreach ($custbookings as $custbooking) : ?>
			<?php $cust = Customer::load($custbooking['custid']); ?>
			<tr>
				<td><?= Customer::get_customernamefromid($custbooking['custid']); ?></td>
				<td class="text-right">$ <?= $page->stringerbell->format_money($custbooking['amount']); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
