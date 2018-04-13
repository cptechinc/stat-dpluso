<?php 
	$custbookings = $bookingspanel->get_bookingsummarybycustomer();
?>
<div class="row">
	<div class="col-sm-6">
		<div class="jumbotron item-detail-heading"> <div> <h4>Booking Dates</h4> </div> </div>
		<div class="table-responsive">
			<table class="table table-bordered table-condensed table-striped">
				<thead> 
					<tr> <th>Date</th> <th>Amount</th> <th>View</th> </tr> 
				</thead>
				<tbody>
					<?php foreach ($bookings as $booking) : ?>
						<tr>
							<td>
								<?= DplusDateTime::format_date($booking['bookdate']); ?>
							</td>
							<td class="text-right">$ <?= $page->stringerbell->format_money($booking['amount']); ?></td>
							<td class="text-right"><?= $bookingspanel->generate_viewsalesordersbydaylink($booking['bookdate']); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="jumbotron item-detail-heading"> <div> <h4>Customer Bookings</h4> </div> </div>
		<div class="table-responsive">
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
		</div>
	</div>
</div>
<script>
	$(function() {
		$('#bookings-by-customer').DataTable();
	});
</script>
