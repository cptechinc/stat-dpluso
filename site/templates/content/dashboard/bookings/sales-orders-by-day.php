<?php 
	$bookingspanel = new BookingsPanel(session_id(), $page->fullURL); 
	$date = $input->get->text('date');
	$salesorders = $bookingspanel->get_daybookingordernumbers($date);
	$count = $bookingspanel->count_daybookingordernumbers($date);
	echo $bookingspanel->get_daybookingordernumbers($date, true);
?>
<div class="table-responsive">
	<table class="table table-bordered table-condensed table-striped">
		<thead> 
			<tr> <th>Date</th> <th>Sales Order #</th> <th>View</th> </tr> 
		</thead>
		<tbody>
			<?php if ($count) : ?>
				<?php foreach ($salesorders as $salesorder) : ?>
					<tr>
						<td><?= DplusDateTime::format_date($salesorder['bookdate']); ?></td>
						<td><?= $salesorder['salesordernbr']; ?></td>
						<td><?= $bookingspanel->generate_viewsalesorderdaylink($salesorder['salesordernbr'], DplusDateTime::format_date($salesorder['bookdate'])); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr>
					<td colspan="2" class="text-center">
						No Sales Orders Booked
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
