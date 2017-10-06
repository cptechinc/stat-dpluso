<?php $pricingfile = $config->jsonfilepath.session_id()."-price.json"; ?>

<?php if (file_exists($pricingfile)) : ?>
	<?php $jsonpricing = json_decode(file_get_contents($pricingfile), true); $columns = array(); ?>
	<?php if (!$jsonpricing) { $jsonpricing = array('error' => true, 'errormsg' => 'The pricing JSON contains errors');} ?>

	<?php if ($jsonpricing['error']) : ?>
		<div class="alert alert-warning" role="alert"><?php echo $jsonpricing['errormsg']; ?></div>
	<?php else : ?>
		<table class="table table-striped table-bordered table-condensed table-excel">
			<tbody>
				<?php foreach($jsonpricing['columns'] as $column => $name) : ?>
					<tr>
						<td><?= $name; ?></td>
						<?php foreach ($jsonpricing['data'] as $pricebreak) : ?>
							<?php if (is_numeric($pricebreak[$column])) : ?>
								<td class="text-right"><?= $pricebreak[$column]; ?></td>
							<?php else : ?>
								<td><?= $pricebreak[$column]; ?></td>
							<?php endif; ?>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
<?php else : ?>
	<div class="alert alert-warning" role="alert">Information Not Available</div>
<?php endif; ?>
