<?php if ($appconfig->child('name=dplus')->has_crm) : ?>
	<div class="row">
		<div class="col-sm-12">
			<?php $actionpanel = new ActionsPanel(session_id(), $page->fullURL, $input); ?>
			<?php include $config->paths->content."user-actions/user-actions-panel.php"; ?>
		</div>
	</div>
<?php endif; ?>
<div class="row">
	<div class="col-sm-12">
		<?php include $config->paths->content.'dashboard/sales-panel.php'; ?>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<?php include $config->paths->content.'dashboard/bookings/bookings-panel.php'; ?>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<?php include $config->paths->content.'dashboard/sales-orders/sales-orders-panel.php'; ?>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<?php include $config->paths->content.'dashboard/quotes/quotes-panel.php'; ?>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<?php include $config->paths->content.'dashboard/sales-history/sales-history-panel.php'; ?>
	</div>
</div>
