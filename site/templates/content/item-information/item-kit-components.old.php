<?php
	$kititemfile = $config->jsonfilepath.session_id()."-iikit.json";
	//$kititemfile = $config->jsonfilepath."iikt-iikit.json";
	
	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
	

?>

<?php if (file_exists($kititemfile)) : ?>
	<?php $kitjson = json_decode(file_get_contents($kititemfile), true);  ?>
	<?php if (!$kitjson) { $kitjson = array('error' => true, 'errormsg' => 'The item Kit Components JSON contains errors');} ?>
	<?php if ($kitjson['error']) : ?>
		<div class="alert alert-warning" role="alert"><?php echo $kitjson['errormsg']; ?></div>
	<?php else : ?>
		<?php $componentcolumns = array_keys($kitjson['columns']['component']); ?>
		<?php $warehousecolumns = array_keys($kitjson['columns']['warehouse']); ?>
		<p><b>Kit Qty:</b> <?php echo $kitjson['qtyneeded']; ?></p>
		<?php foreach ($kitjson['data']['component'] as $component) : ?>
			<h3><?php echo $component['component item']; ?></h3>
			<table class="table table-striped table-bordered table-condensed table-excel no-bottom">
				<thead>
					<tr>
						<?php foreach($kitjson['columns']['component'] as $column) : ?>
							<th class="<?= $config->textjustify[$column['headingjustify']]; ?>"><?php echo $column['heading']; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<tr>
						<?php foreach ($componentcolumns as $column) : ?>
							<td class="<?= $config->textjustify[$kitjson['columns']['component'][$column]['datajustify']]; ?>"><?php echo $component[$column]; ?></td>
						<?php endforeach; ?>
					</tr>
				</tbody>
			</table>
			<table class="table table-striped table-bordered table-condensed table-excel">
				<thead>
					<tr>
						<?php foreach($kitjson['columns']['warehouse'] as $column) : ?>
							<th class="<?= $config->textjustify[$column['headingjustify']]; ?>"><?php echo $column['heading']; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($component['warehouse'] as $whse) : ?>
						<tr>
							<?php foreach ($warehousecolumns as $column) : ?>
								<td class="<?= $config->textjustify[$kitjson['columns']['warehouse'][$column]['datajustify']]; ?>"><?php echo $whse[$column]; ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endforeach; ?>
		<p>
			<b>Warehouses that meet the Requirement: </b>
			<?php foreach ($kitjson['data']['whse meeting req'] as $whse => $name) : ?>
				<?= $name; ?> &nbsp;
			<?php endforeach; ?>
		</p>
	<?php endif; ?>
<?php else : ?>
	<div class="alert alert-warning" role="alert">Information Not Available</div>
<?php endif; ?>
