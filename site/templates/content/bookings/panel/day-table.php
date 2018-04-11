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
				<td>$ <?= $page->stringerbell->format_money($booking['amount']); ?></td>
				<td><?= $bookingspanel->generate_viewsalesordersbydaylink($booking['bookdate']); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
