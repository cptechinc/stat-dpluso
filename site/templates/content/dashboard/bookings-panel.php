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
			<span class="pull-right"><?= $bookingspanel->generate_todaysbookingsdescription(); ?></span>
		</a>
	</div>
	<div id="bookings-div" class="" aria-expanded="true">
		<div>
			<h3 class="text-center"><?= $bookingspanel->generate_title(); ?></h3>
			<div id="bookings-chart">
				
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="table-responsive bookings-table-div">
						<?php include $config->paths->content."bookings/panel/$bookingspanel->interval-table.php"; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
	include $config->paths->content."dashboard/bookings/bookings-line-chart.js.php";
?>