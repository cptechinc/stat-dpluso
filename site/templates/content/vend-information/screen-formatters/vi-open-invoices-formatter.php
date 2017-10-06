<?php 
	if (checkformatterifexists($user->loginid, 'vi-open-invoices', false)) {
		$columnindex = 'columns';
		$formatter = json_decode(getformatter($user->loginid, 'vi-open-invoices', false), true); 
		$action = 'edit-formatter';
	} else {
		$columnindex = 'columns';
		$formatter = json_decode(file_get_contents($config->paths->content."vend-information/screen-formatters/default/vi-open-invoices.json"), true); 
		$action = 'add-formatter';
	}
	
	$fieldsjson = json_decode(file_get_contents($config->companyfiles."json/vioifmattbl.json"), true);
	$columns = array_keys($fieldsjson['data']['detail']);
	
	$examplejson = json_decode(file_get_contents($config->paths->content."vend-information/screen-formatters/examples/vi-open-invoices.json"), true);

	$datetypes = array('m/d/y' => 'MM/DD/YY', 'm/d/Y' => 'MM/DD/YYYY', 'm/d' => 'MM/DD', 'm/Y' => 'MM/YYYY')
?>


<div class="formatter-response">
	<div class="message"></div>
</div>

<form action="<?php echo $config->pages->ajax."json/vi/vi-open-invoices-formatter/"; ?>" method="POST" class="screen-formatter-form" id="vi-oi-form">
	<input type="hidden" name="action" value="<?php echo $action; ?>">
	<input type="hidden" name="detail-rows" class="detail-rows">
	<input type="hidden" name="cols" class="cols">
	<div class="panel panel-default">
		<div class="panel-heading"><h3 class="panel-title"><?php echo $page->title; ?></h3> </div>
		<div class="formatter-container">
			<?php $table = 'detail'; include $config->paths->content."vend-information/screen-formatters/table.php";  ?>
		</div>
	</div>
	<button type="button" class="btn btn-info" onClick="previewtable('#vi-oi-form')"><i class="fa fa-table" aria-hidden="true"></i> Preview Table</button>
	<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-floppy-disk"></i> Save Configuration</button>
</form>
<script>
	var tabletype = 'open-invoices';
</script>
