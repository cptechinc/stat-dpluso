<form action="<?php echo $config->pages->ajax."load/ii/search-results/"; ?>" method="get" id="ii-search-item">
	<input type="text" class="form-control ii-item-search" name="q" autocomplete="off">
	<input type="hidden" name="custID" class="custID" value="<?= $custID; ?>" >
	<div>
		<?php include $config->paths->content."item-information/item-search-results.php"; ?>
	</div>
</form>
