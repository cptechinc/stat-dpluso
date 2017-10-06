<?php 
	if (checkformatterifexists($user->loginid, 'ii-sales-order', false)) {
		$columnindex = 'columns';
		$formatter = json_decode(getformatter($user->loginid, 'ii-sales-order', false), true); 
		$action = 'edit-formatter';
	} else {
		$columnindex = 'columns';
		$formatter = json_decode(file_get_contents($config->paths->content."item-information/screen-formatters/default/ii-sales-order.json"), true); 
		$action = 'add-formatter';
	}
	
	$fieldsjson = json_decode(file_get_contents($config->companyfiles."json/iisofmattbl.json"), true);
	$columns = array_keys($fieldsjson['data']['detail']);
	
	$examplejson = json_decode(file_get_contents($config->paths->content."item-information/screen-formatters/examples/ii-sales-order.json"), true);

	$datetypes = array('m/d/y' => 'MM/DD/YY', 'm/d/Y' => 'MM/DD/YYYY', 'm/d' => 'MM/DD', 'm/Y' => 'MM/YYYY')
?>

<div class="formatter-response">
	<div class="message"></div>
</div>

<form action="<?php echo $config->pages->ajax."json/ii/ii-sales-order-formatter/"; ?>" method="POST" class="screen-formatter-form" id="ii-so-form">
	<input type="hidden" name="action" value="<?php echo $action; ?>">
	<input type="hidden" name="detail-rows" class="detail-rows">
	<input type="hidden" name="cols" class="cols">

	<div class="panel panel-default">
		<div class="panel-heading"><h3 class="panel-title"><?php echo $page->title; ?></h3> </div>
		<div class="formatter-container">
			<?php $table = 'detail';
				include $config->paths->content."item-information/screen-formatters/table.php"; 
			?>
		</div>
	</div>
	<button type="button" class="btn btn-info" onClick="previewtable('#ii-so-form')"><i class="fa fa-table" aria-hidden="true"></i> Preview Table</button>
	<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-floppy-disk"></i> Save Configuration</button>
</form>
<script>
	var tabletype = 'sales-order';
</script>
				
