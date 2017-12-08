<form action="<?php echo $config->pages->ajax."load/ii/search-results/"; ?>" method="get" id="ii-search-item">
	<input type="text" class="form-control ii-item-search" name="q" placeholder="Type Item ID" autocomplete="off">
	<input type="hidden" name="custID" class="custID" value="<?= $custID; ?>" >
</form>

</br>
<div class="table-responsive table-striped table-bordered">
	<div>
		<?php include $config->paths->content.'item-information/tables/item-search-table.php'; ?>
	</div>
</div>
