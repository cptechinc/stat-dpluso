<?php
	if (checkformatterifexists($user->loginid, 'ci-quote', false)) {
		$columnindex = 'columns';
		$formatter = json_decode(getformatter($user->loginid, 'ci-quote', false), true);
		$action = 'edit-formatter';
	} else {
		$columnindex = 'columns';
		$formatter = json_decode(file_get_contents($config->paths->content."cust-information/screen-formatters/default/ci-quote.json"), true);
		$action = 'add-formatter';
	}

	$fieldsjson = json_decode(file_get_contents($config->companyfiles."json/ciqtfmattbl.json"), true);
	$detailcolumns = array_keys($fieldsjson['data']['detail']);
	$headercolumns = array_keys($fieldsjson['data']['header']);

	$examplejson = json_decode(file_get_contents($config->paths->content."cust-information/screen-formatters/examples/ci-quote.json"), true);

	$datetypes = array('m/d/y' => 'MM/DD/YY', 'm/d/Y' => 'MM/DD/YYYY', 'm/d' => 'MM/DD', 'm/Y' => 'MM/YYYY')
?>

<div class="formatter-response">
	<div class="message"></div>
</div>

<form action="<?php echo $config->pages->ajax."json/ci/ci-quotes-formatter/"; ?>" method="POST" class="screen-formatter-form" id="ci-qt-form">
	<input type="hidden" name="action" value="<?php echo $action; ?>">
	<input type="hidden" name="detail-rows" class="detail-rows">
	<input type="hidden" name="header-rows" class="header-rows">
	<input type="hidden" name="cols" class="cols">

	<div class="panel panel-default">
		<div class="panel-heading"><h3 class="panel-title"><?php echo $page->title; ?></h3> </div>
		<div class="formatter-container">
			<div>
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#header" aria-controls="header" role="tab" data-toggle="tab">Header</a></li>
					<li role="presentation"><a href="#details" aria-controls="details" role="tab" data-toggle="tab">Details</a></li>

				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="header">
						<?php $table = 'header'; $columns = $headercolumns; include $config->paths->content."cust-information/screen-formatters/table.php"; ?>

					</div>
					<div role="tabpanel" class="tab-pane" id="details">
						<?php $table = 'detail'; $columns = $detailcolumns;  include $config->paths->content."cust-information/screen-formatters/table.php"; ?>
					</div>

				</div>
			</div>
		</div>
	</div>
	<button type="button" class="btn btn-info" onClick="previewtable('#ci-qt-form')"><i class="fa fa-table" aria-hidden="true"></i> Preview Table</button>
	<button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-floppy-disk"></i> Save Configuration</button>
</form>
<script>
	var tabletype = 'quote';
</script>
