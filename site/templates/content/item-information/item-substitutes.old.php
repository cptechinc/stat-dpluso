<?php
	$substitutefile = $config->jsonfilepath."iisublindst-iisub.json";
	$substitutefile = $config->jsonfilepath."iisub-iisub.json";
	$substitutefile = $config->jsonfilepath.session_id()."-iisub.json";
	if ($config->ajax) {
		echo '<p>' . makeprintlink($config->filename, 'View Printable Version') . '</p>';
	}
?>

<?php if (file_exists($substitutefile)) : ?>
	<?php $substitutejson = json_decode(file_get_contents($substitutefile), true);  ?>
	<?php if (!$substitutejson) { $substitutejson = array('error' => true, 'errormsg' => 'The item substitutes JSON contains errors');} ?>

	<?php if ($substitutejson['error']) : ?>
		<div class="alert alert-warning" role="alert"><?php echo $substitutejson['errormsg']; ?></div>
	<?php else : ?>
		<?php $columns = array_keys($substitutejson['columns']); ?>
		<div class="row">
			<div class="col-sm-6">
				<table class="table table-striped table-bordered table-condensed table-excel">
					<tr>
						<td>ItemID:</td>
						<td>
							<?php
								echo $substitutejson['itemid'] . "<br>";
								echo $substitutejson['desc1'] . "<br>";
								if (isset($substitutejson['alt item'])) {
									echo "<b>Alt Item ID:</b> ".$substitutejson['alt item'];
								} else {
									echo $substitutejson['desc2'];
								}
							?>
						</td>
					</tr>
				</table>
			</div>
			<div class="col-sm-6">
				<table class="table table-striped table-bordered table-condensed table-excel">
					<tr> <td>Sale UoM</td> <td><?php echo $substitutejson['sale uom']; ?></td> </tr>
					<tr> <td>Base Price</td> <td><?php echo $substitutejson['base price']; ?></td> </tr>
				</table>
			</div>
		</div>

		<table class="table table-striped table-bordered table-condensed table-excel" id="table">
			<thead>
				<tr>
					<?php foreach($substitutejson['columns'] as $column) : ?>
						<th class="<?= $config->textjustify[$column['headingjustify']]; ?>"><?php echo $column['heading']; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($substitutejson['data']['sub items'] as $item) : ?>
					<tr>
						<td colspan="2" class="<?= $config->textjustify[$substitutejson['columns']["sub item"]['datajustify']]; ?>"><?php echo $item["sub item"]; ?></td>
						<td><?php echo $item['same/like']; ?></td>
						<td colspan="<?= sizeof($columns) - 3; ?>"><?php echo $item['sub desc']; ?></td>
					</tr>
					<?php if (isset($item['alt items'])) : ?>
						<tr>
							<td colspan="2">&nbsp; &nbsp; &nbsp; &nbsp; <?php echo $item["alt items"]["alt item"]; ?></td>
							<td colspan="<?= sizeof($columns) - 2; ?>"><?php echo $item["alt items"]["bag qty"]; ?></td>
						</tr>
					<?php endif; ?>
					<?php foreach ($item['whse'] as $whse) : ?>
						<tr>
							<?php foreach($columns as $column) : ?>
								<?php if ($column == 'sub item') : ?>
									<td></td>
								<?php else : ?>
									<td class="<?= $config->textjustify[$substitutejson['columns'][$column]['datajustify']]; ?>"><?php echo $whse[$column]; ?></td>
								<?php endif; ?>

							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
<?php else : ?>
	<div class="alert alert-warning" role="alert">Information Not Available</div>
<?php endif; ?>
