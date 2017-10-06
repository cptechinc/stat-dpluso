<?php
	$itemfile = $config->jsonfilepath.session_id()."-iiitem.json";
	if (file_exists($itemfile)) {
		$itemjson = json_decode(file_get_contents($itemfile), true); $columns = array();
	} else {
		$itemjson = false;
	}
?>
<div class="row">
   <?php if (100 == 2) : ?>
	<div class="col-sm-2">
		<?php include $config->paths->content."item-information/ii-buttons.php"; ?>
	</div>
	<?php endif; ?>
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-6">
				<?php include $config->paths->content."item-information/item-display.php"; ?>
			</div>
			<div class="col-sm-6">
				<table class="table table-bordered table-striped table-condensed table-excel">
					<tr>
						<td class="control-label">Item ID:</td>
						<td>
							<?php include $config->paths->content."item-information/forms/item-page-form.php"; ?>
						</td>
					</tr>
					<tr>
						<td></td><td><?php echo $itemjson['data']['description']; ?></td>
					</tr>
					<tr>
						<td></td><td><?php echo $itemjson['data']['description2']; ?></td>
					</tr>
					<tr>
						<td class="control-label"><?php echo $itemjson['columns']['itemgroup']; ?></td> <td><?php echo $itemjson['data']['itemgroup']; ?></td>
					</tr>
					<tr>
						<td class="control-label"><?php echo $itemjson['columns']['pricegroup']; ?></td> <td><?php echo $itemjson['data']['pricegroup']; ?></td>
					</tr>
					<tr>
						<td class="control-label"><?php echo $itemjson['columns']['taxable']; ?></td> <td><?php echo $itemjson['data']['taxable']; ?></td>
					</tr>
					<tr>
						<td class="control-label"><?php echo $itemjson['columns']['upc_code']; ?></td> <td><?php echo $itemjson['data']['upc_code']; ?></td>
					</tr>
				</table>
			</div>

		</div>
		<div class="row">
			<div class="col-sm-4">
				<?php $columns = array('asstcode', 'weight', 'finish', 'material', 'color'); ?>
				<?php include $config->paths->content."item-information/tables/generate-table.php"; ?>
			</div>
			<div class="col-sm-4">
				<?php $columns = array('revision', 'primvend', 'unitcost', 'merged'); ?>
				<?php include $config->paths->content."item-information/tables/generate-table.php"; ?>
			</div>
			<div class="col-sm-4">
				<table class="table table-bordered table-striped table-condensed table-excel">
					<tr>
						<td class="control-label"><?php echo $itemjson['columns']['kitbom']; ?></td> <td><?php echo $itemjson['data']['kitbom']; ?></td>
					</tr>
					<tr> <td>&nbsp;</td> <td>&nbsp;</td> </tr>
					<tr>
						<td class="control-label"><?php echo $itemjson['columns']['puruom']; ?></td> <td><?php echo $itemjson['data']['puruom']; ?></td>
					</tr>
					<tr> <td class="control-label"><?php echo $itemjson['columns']['specsheetname']; ?></td> <td><?php echo $itemjson['data']['specsheetname']; ?></td> </tr>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<?php if ($input->get->custID) : ?>
					<h4>Pricing for <?= get_customername($custID); ?></h4>
				<?php else : ?>
					<h4>Pricing</h4>
				<?php endif; ?>
				<?php include $config->paths->content."item-information/item-price-breaks.php"; ?>
			</div>
			<div class="col-sm-6">

			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<h4>Stock</h4>
				<?php include $config->paths->content."item-information/item-stock.php"; ?>
			</div>
		</div>
	</div>
</div>
<br>
