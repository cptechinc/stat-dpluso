<?php 
	$custbookings = $bookingspanel->get_bookingsummarybycustomer();
	echo $bookingspanel->get_bookingsummarybycustomer(true);
?>
<div class="row">
	<div class="col-sm-6">
		<div class="table-responsive">
			<table class="table table-bordered table-condensed table-striped">
				<thead> 
					<tr> <th>Date</th> <th>Amount</th> </tr> 
				</thead>
				<tbody>
					<?php foreach ($bookings as $booking) : ?>
						<tr>
							<td>
								<?= DplusDateTime::format_date($booking['bookdate'], 'F Y'); ?>
							</td>
							<td>$ <?= $page->stringerbell->format_money($booking['amount']); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="table-responsive cust-bookings-table-div">
			<table class="table table-bordered table-condensed table-striped" id="bookings-cust-table">
				<thead> 
					<tr><th>Date</th> <th>Amount</th> </tr> 
				</thead>
				<tbody>
					<?php foreach ($custbookings as $custbooking) : ?>
						<?php $cust = Customer::load($custbooking['custid']); ?>
						<tr>
							<td><?= Customer::get_customernamefromid($custbooking['custid']); ?></td>
							<td>$ <?= $page->stringerbell->format_money($custbooking['amount']); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
