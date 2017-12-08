<table class="table table-striped table-bordered table-condensed table-sm">
	<thead>
		<tr> <th>Field</th> <th>Field Definition</th> <th>Line</th> <th>Column</th> <th>Column Length</th> <th>Column Label</th> <th class="hidden"></th> </tr>
	</thead>
	<?php foreach ($columns as $column) : ?>
		<?php $name = str_replace(' ', '', $column); ?>
		<tr>
			<td class="field"><?php echo $column; ?></td>
			<td>
				<?php if ($fieldsjson['data'][$table][$column]['type'] == 'D') : ?>
					<select class="form-control input-sm" name="<?php echo $name."-date-format";?>">
						<?php foreach ($datetypes as $key => $value) : ?>
							<?php if ($key == $formatter[$table][$columnindex][$column]['date-format']) : ?>
								<option value="<?= $key; ?>" selected><?php echo $value . ' - '. date($key); ?></option>
							<?php else : ?>
								<option value="<?= $key; ?>"><?php echo $value . ' - '. date($key); ?></option>
							<?php endif; ?>
							
						<?php endforeach; ?>
					</select>
				<?php elseif ($fieldsjson['data'][$table][$column]['type'] == 'I') : ?>
					Integer
				<?php elseif ($fieldsjson['data'][$table][$column]['type'] == 'C') : ?>
					Text
				<?php elseif ($fieldsjson['data'][$table][$column]['type'] == 'N') : ?>
					<div>
						Before Decimal &nbsp;
						<input type="text" class="form-control inline input-sm qty-sm before-decimal" name="<?php echo $name."-before-decimal";?>" value="<?= $formatter[$table][$columnindex][$column]['before-decimal']; ?>"> &nbsp; &nbsp;
						After Decimal &nbsp;  
						<input type="text" class="form-control inline input-sm qty-sm after-decimal" name="<?php echo $name."-after-decimal";?>" value="<?= $formatter[$table][$columnindex][$column]['after-decimal']; ?>">
						<span class="display"></span>
					</div>
				<?php endif; ?>
			</td>
			<td><input type="text" class="form-control input-sm qty-sm <?= $table; ?>-line" name="<?= $name."-line";?>" value="<?= $formatter[$table][$columnindex][$column]['line']; ?>"></td>
			<td><input type="text" class="form-control input-sm qty-sm column" name="<?= $name."-column";?>" value="<?= $formatter[$table][$columnindex][$column]['column']; ?>"></td>
			<td><input type="text" class="form-control input-sm qty-sm column-length" name="<?= $name."-length";?>" value="<?= $formatter[$table][$columnindex][$column]['col-length']; ?>"></td>
			<td><input type="text" class="form-control input-sm col-label" name="<?= $name."-label";?>" value="<?= $formatter[$table][$columnindex][$column]['label']; ?>"></td>
			<!-- <td class="hidden"><input type="hidden" class="example-data" value="<?= $examplejson[$table][$column]; ?>"></td> -->
			<!-- since the original json code was written before a formatter was in mind, the $table is getting an undefined index for header and will not output teh II outline examplejson -->
			<td class="hidden"><input type="hidden" class="example-data" value="<?= $examplejson['detail'][$column]; ?>"></td>
		</tr>
	<?php endforeach; ?>
</table>
