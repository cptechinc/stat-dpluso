<?php
	$usagefile = $config->jsonfilepath.session_id()."-iiusage.json";
	//$usagefile = $config->jsonfilepath."iiu-iiusage.json";

	$notesfile = $config->jsonfilepath.session_id()."-iinotes.json";
	//$notesfile = $config->jsonfilepath."iint-iinotes.json";

	$miscfile = $config->jsonfilepath.session_id()."-iimisc.json";
	//$miscfile = $config->jsonfilepath."iim-iimisc.json";

	if (file_exists($usagefile)) {
		$usagejson = json_decode(file_get_contents($usagefile), true);
		if (!$usagejson) {
			$usagejson = array('error' => true, 'errormsg' => 'The BOM Item Inquiry Single JSON contains errors');
		} else {
			$usagecolumns = array_keys($usagejson['columns']['sales usage']);
			$todatecolumns = array_keys($usagejson['columns']['24month']);
			$warehouses = array_keys($usagejson['data']['24month']);
		}
	} else {
		$usagefile = false;
	}

	if (file_exists($notesfile)) {
		$notesjson = json_decode(file_get_contents($notesfile), true);
		if (!$notesjson) {
			$notesjson = array('error' => true, 'errormsg' => 'The BOM Item Inquiry Single JSON contains errors');
		} else {
			if (isset($notesjson['columns'])) {
				$inspectioncolumns = array_keys($notesjson['columns']['inspection notes']);
				$internalcolumns = array_keys($notesjson['columns']['internal notes']);
				$ordercolumns = array_keys($notesjson['columns']['order notes']);
			}
		}
	} else {
		$notesfile = false;
	}

	if (file_exists($miscfile)) {
		$miscjson = json_decode(file_get_contents($miscfile), true);
		if (!$miscjson) {
			$miscjson = array('error' => true, 'errormsg' => 'The BOM Item Inquiry Single JSON contains errors');
		} else {
			$misccolumns = array_keys($miscjson['columns']['misc info']);
		}
	} else {
		$miscfile = false;
	}
	
	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
 ?>

<?php if ($usagefile) : ?>
	<?php if (!$usagejson['error']) : ?>
		<table class="table table-striped table-condensed table-excel">
			<tr> <td><b>Item ID</b></td> <td><?= $usagejson['itemid']; ?></td> <td colspan="2"><?= $usagejson['desc1']; ?></td> </tr>
			<tr> <td><b>Sales UoM</b></td> <td><?= $usagejson['sale uom']; ?></td> <td colspan="2"><?= $usagejson['desc2']; ?></td> </tr>
			<tr>
				<td><b>Last Sale Date: </b></td> <td class="text-center"><?= $usagejson['last sale date']; ?></td>
				<td><b>Last Usage Date:</b> <?php echo $usagejson['last usage date']; ?> </td>
			</tr>
		</table>
	<?php endif; ?>
<?php endif; ?>

<div>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#usage" aria-controls="usage" role="tab" data-toggle="tab">Usage</a></li>
		<li role="presentation"><a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">Notes</a></li>
		<li role="presentation"><a href="#misc" aria-controls="misc" role="tab" data-toggle="tab">Misc</a></li>
	</ul>

  <!-- Tab panes -->
  <div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="usage">
		<br>
		<?php include $config->paths->content."item-information/item-general/item-usage.php"; ?>
	</div>
	<div role="tabpanel" class="tab-pane" id="notes">
		<br>
		<?php include $config->paths->content."item-information/item-general/item-notes.php"; ?>
	</div>
	<div role="tabpanel" class="tab-pane" id="misc"><br>
		<?php if ($miscfile) : ?>
			<?php if ($miscjson['error']) : ?>
				<div class="alert alert-warning" role="alert"><?php echo $miscjson['errormsg']; ?></div>
			<?php else : ?>
				<div class="col-sm-6">
					<table class="table table-striped table-condensed table-excel">
						<?php foreach ($miscjson['data'] as $misc) : ?>
								<?php foreach ($misccolumns as $column) : ?>
									<tr>
										<td><b><?php echo $miscjson['columns']['misc info'][$column]['heading']; ?></b></td>
										<td class="<?= $config->textjustify[$miscjson['columns']['misc info'][$column]['datajustify']]; ?>"><?php echo $misc[$column]; ?></td>
									</tr>
								<?php endforeach; ?>
						<?php endforeach; ?>
					</table>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<div class="alert alert-warning" role="alert">Information Not Available</div>
		<?php endif; ?>
	</div>
  </div>
</div>
