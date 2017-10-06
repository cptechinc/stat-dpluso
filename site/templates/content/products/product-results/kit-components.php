<?php 
	$componentfile = $config->jsonfilepath.session_id()."-kititem.json"; 
	//$componentfile = $config->jsonfilepath."esokit-kititem.json";
	
?>
<?php if (file_exists($componentfile)) : ?>
    <?php $componentsjson = json_decode(file_get_contents($componentfile), true); $columns = array(); ?>
    <?php if (!$componentsjson) { $componentsjson = array('error' => true, 'errormsg' => 'The Kit Components JSON file contains errors'); } ?>
    <?php if ($componentsjson['error']) : ?>
        <div class="alert alert-warning" role="alert"><?php echo $componentsjson['errormsg']; ?>sadf</div>
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
