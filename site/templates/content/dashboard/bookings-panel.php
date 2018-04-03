<?php
	$bookingspanel = new BookingsPanel(session_id(), $page->fullURL, '#ajax-modal', 'data-loadinto=#bookings-panel|data-focus=#bookings-panel');
	$bookingspanel->generate_filter($input);
	$bookings = $bookingspanel->get_bookings();
	
	foreach ($bookings as $booking) {
		$bookingdata[] = array(
			'bookdate' => DplusDateTime::format_date($booking['bookdate'], 'Y-m-d'),
			'amount' => floatval($booking['amount'])
		);
	}
?>
<div class="panel panel-primary not-round" id="bookings-panel">
	<div class="panel-heading not-round" id="bookings-panel">
		<a href="#bookings-div" class="panel-link" data-parent="#bookings-panel" data-toggle="collapse" aria-expanded="true">
			<span class="glyphicon glyphicon-book"></span> &nbsp; Bookings <span class="caret"></span>
			&nbsp; | <?= $bookingspanel->generate_refreshlink(); ?>
		</a>
	</div>
	<div id="bookings-div" class="" aria-expanded="true">
		<div>
			<h3 class="text-center"><?= $bookingspanel->generate_title(); ?></h3>
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
		var pageurl = new URI('<?= $bookingspanel->pageurl->getUrl(); ?>').toString();
		var loadinto = '#bookings-panel';
		
		new Morris.Line({
			// ID of the element in which to draw the chart.
			element: 'booking-chart',
			// Chart data records -- each entry in this array corresponds to a point on
			// the chart.
			data: <?= json_encode($bookingdata); ?>,
			// The name of the data record attribute that contains x-values.
			xkey: 'bookdate',
			dateFormat: function (d) {
				var ds = new Date(d);
				return moment(ds).format('MM/DD/YYYY');
			},
			hoverCallback: function (index, options, content, row) {
				var bootstrap = new JsContento();
				var ajaxdata = 'data-loadinto='+loadinto+'|data-focus='+loadinto;
				var url = new URI(pageurl.toString()).addQuery('filter', 'filter');
				var date = moment(row.bookdate).format('MM/DD/YYYY');
				
				<?php if ($bookingspanel->interval ==  'month') : ?>
					date = moment(row.bookdate).format('MMM YYYY');
					var firstofmonth = moment(row.bookdate).format('MM/DD/YYYY');
					var lastofmonth = moment(row.bookdate).endOf('month').format('MM/DD/YYYY');
					href = URI(url).setQuery('filter', 'filter').removeQuery('bookdate[]').addQuery('bookdate', firstofmonth+"|"+lastofmonth).normalizeQuery().toString();
					var monthlink = "<a href='"+href+"' class='load-and-show' data-loadinto='"+loadinto+"' data-focus='"+loadinto+"'>"+
									'Click to view ' + date +
									'</a>';
				<?php endif; ?>
				var hover = '<b>'+date+'</b><br>';
				hover += '<b>Amt Sold: </b> $' + row.amount.formatMoney() +'<br>';
				<?php if ($bookingspanel->interval ==  'month') : ?>
					hover += monthlink;
				<?php endif; ?>
				return hover;
			},
			xLabels: '<?= $bookingspanel->interval; ?>',
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
