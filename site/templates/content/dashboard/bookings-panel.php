<?php 
	$data = array();
	$days = 365;
	$bookings = get_userbookings($days);
	foreach ($bookings as $booking) {
		$data[] = array(
			'bookdate' => DplusDateTime::format_date($booking['bookdate'], 'Y-m-d'),
			'amount' => floatval($booking['amount'])
		);
	}
?>
<div class="panel panel-primary not-round" id="bookings-panel">
	<div class="panel-heading not-round" id="bookings-panel">
		<a href="#bookings-div" class="panel-link" data-parent="#bookings-panel" data-toggle="collapse" aria-expanded="true">
			<span class="glyphicon glyphicon-book"></span> &nbsp; Bookings <span class="caret"></span>
		</a>
	</div>
	<div id="bookings-div" class="" aria-expanded="true">
		<div>
			<h3 class="text-center"><?= "Viewing $days days of bookings"; ?></h3>
			<div id="booking-chart">
				
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="table-responsive">
						<table class="table table-bordered table-condensed table-striped">
							<thead> 
								<tr> <th>Date</th> <th>Amount</th>  </tr> 
							</thead>
							<tbody>
								<?php $bookings = get_userbookings(); ?>
								<?php foreach ($bookings as $booking) : ?>
									<tr>
										<td><?= DplusDateTime::format_date($booking['bookdate']); ?></td>
										<td>$ <?= $page->stringerbell->format_money($booking['amount']); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>
<script>
	$(function() {
		new Morris.Line({
			// ID of the element in which to draw the chart.
			element: 'booking-chart',
			// Chart data records -- each entry in this array corresponds to a point on
			// the chart.
			data: <?= json_encode($data); ?>,
			// The name of the data record attribute that contains x-values.
			xkey: 'bookdate',
			dateFormat: function (d) {
				var ds = new Date(d);
				return moment(ds).format('MM/DD/YYYY');
			},
			hoverCallback: function (index, options, content, row) {
				var date = moment(row.bookdate).format('MM/DD/YYYY');
				<?php if ($days > 90) : ?>
					date = moment(row.bookdate).format('MMM YYYY');
				<?php endif; ?>
				var hover = '<b>'+date+'</b><br>';
				hover += '<b>Amt Sold: </b> $' + row.amount.formatMoney() +'<br>';
				return hover;
			},
			xLabels: 'day',
			// A list of names of data record attributes that contain y-values.
			ykeys: ['amount'],
			// Labels for the ykeys -- will be displayed when you hover over the
			// chart.
			labels: ['Amount'],
			xLabelFormat: function (x) { return  moment(x).format('MM/DD/YYYY'); },
			yLabelFormat: function (y) { return "$ "+y.formatMoney() + ' dollars'; },
		});
	});
</script>
