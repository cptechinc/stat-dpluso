<?php 
	if (checkformatterifexists($user->loginid, 'ii-sales-history', false)) {
		$columnindex = 'columns';
		$formatter = json_decode(getformatter($user->loginid, 'ii-sales-history', false), true); 
		$action = 'edit-formatter';
	} else {
		$columnindex = 'columns';
		$formatter = json_decode(file_get_contents($config->paths->content."item-information/screen-formatters/default/ii-sales-history.json"), true); 
		$action = 'add-formatter';
	}
	
	$fieldsjson = json_decode(file_get_contents($config->companyfiles."json/iishfmattbl.json"), true);
	$detailcolumns = array_keys($fieldsjson['data']['detail']);
	$lotcolumns = array_keys($fieldsjson['data']['lotserial']);
	
	$examplejson = json_decode(file_get_contents($config->paths->content."item-information/screen-formatters/examples/ii-sales-history.json"), true);

	$datetypes = array('m/d/y' => 'MM/DD/YY', 'm/d/Y' => 'MM/DD/YYYY', 'm/d' => 'MM/DD', 'm/Y' => 'MM/YYYY')
?>
<div class="formatter-response">
	<div class="message"></div>
</div>

<form action="<?php echo $config->pages->ajax."json/ii/ii-sales-history-formatter/"; ?>" method="POST" class="screen-formatter-form" id="ii-sh-form">
	<input type="hidden" name="action" value="<?php echo $action; ?>">
	<input type="hidden" name="detail-rows" class="detail-rows">
	<input type="hidden" name="lotserial-rows" class="lotserial-rows">
	<input type="hidden" name="cols" class="cols">
	<div class="panel panel-default">
		<div class="panel-heading"><h3 class="panel-title"><?php echo $page->title; ?></h3> </div>
		<br>
		<div class="row">
			<div class="col-xs-12">
				<div class="formatter-container">
					<div>
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#details" aria-controls="home" role="tab" data-toggle="tab">Details</a></li>
							<li role="presentation"><a href="#lot" aria-controls="profile" role="tab" data-toggle="tab">Lot / Serial</a></li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="details">
								<?php $table = 'detail'; $columns = $detailcolumns; 
									include $config->paths->content."item-information/screen-formatters/table.php"; 
								?>

							</div>
							<div role="tabpanel" class="tab-pane" id="lot">
								<?php $table = 'lotserial'; $columns = $lotcolumns; 
									include $config->paths->content."item-information/screen-formatters/table.php"; 
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	<button type="button" class="btn btn-info" onClick="previewtable('#ii-sh-form')"><i class="fa fa-table" aria-hidden="true"></i> Preview Table</button>
	<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-floppy-disk"></i> Save Configuration</button>
</form>
<script>
	var tabletype = 'sales-history';
</script>