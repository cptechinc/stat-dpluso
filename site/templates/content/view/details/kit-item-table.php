<?php 
	$specfile = $config->jsonfilepath.session_id()."-kititem.json";
	//$specfile = $config->jsonfilepath."esokit-kititem.json"; 
?>

<h3>Kit Components</h3>
<?php if (file_exists($specfile)) : ?>
	<?php $componentsjson = json_decode(file_get_contents($specfile), true); ?>
	<?php if (!$componentsjson) { $componentsjson = array('error' => true, 'errormsg' => 'The item Kit Components JSON contains errors'); } ?>
	<?php if ($kitjson['error']) : ?>
        <div class="alert alert-warning" role="alert"><?php echo $kitjson['errormsg']; ?></div>
    <?php else : ?>
    	<table class="table table-bordered table-condensed">
			<thead>
				<tr>
					<?php foreach ($componentsjson['columns'] as $column => $name) : ?>
						<th><?php echo $name; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($componentsjson['data'] as $component) : ?>
					<tr>
						<?php foreach ($componentsjson['columns'] as $column => $name) : ?>
							<td><?php echo $component[$column]; ?></td>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
<?php else : ?>
	<div class="alert alert-warning" role="alert">Information Not Available</div>
<?php endif; ?>


