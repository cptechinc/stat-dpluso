<div class="row">
	<div class="col-xs-12">
		<div class="row">
			<div class="col-sm-6">
				<table class="table table-striped table-bordered table-condensed table-excel">
					<?php $topcolumns = array_keys($custjson['columns']['top']); ?>
					<?php foreach ($topcolumns as $column) : ?>
						<?php if ($custjson['columns']['top'][$column]['heading'] == '' && $custjson['data']['top'][$column] == '') : ?>
						<?php else : ?>
							<tr>
								<td> <?= $custjson['columns']['top'][$column]['heading']; ?></td>
								<td>
									<?php
										if ($column == 'customerid') {
											include $config->paths->content."cust-information/forms/cust-page-form.php";
										} else {
											echo $custjson['data']['top'][$column];
										}
									?>
								</td>
							</tr>
						<?php endif; ?>
					<?php endforeach; ?>
				</table>
			</div>
			<div class="col-sm-6"></div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<table class="table table-striped table-bordered table-condensed table-excel">
					<?php $leftcolumns = array_keys($custjson['columns']['left']); ?>
					<tbody>
						<?php foreach ($leftcolumns as $column) : ?>
							<?php if ($custjson['columns']['left'][$column]['heading'] == '' && $custjson['data']['left'][$column] == '') : ?>
							<?php else : ?>
								<tr>
									<td class="<?= $config->textjustify[$custjson['columns']['left'][$column]['headingjustify']]; ?>">
										<?php echo $custjson['columns']['left'][$column]['heading']; ?>
									</td>
									<td>
										<?php echo $custjson['data']['left'][$column]; ?>
									</td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="col-sm-6">
				<table class="table table-striped table-bordered table-condensed table-excel">
					<?php $rightsections = array_keys($custjson['columns']['right']); ?>
					<?php foreach ($rightsections as $section) : ?>
						<?php if ($section != 'misc') : ?>
							<tr>
								<?php foreach ($custjson['columns']['right'][$section] as $column) : ?>
									<th class="<?= $config->textjustify[$column['headingjustify']]; ?>">
										<?php echo $column['heading']; ?>
									</th>
								<?php endforeach; ?>
							</tr>

							<?php $rows = array_keys($custjson['data']['right'][$section] ); ?>
							<?php foreach ($rows as $row) : ?>
								<tr>
									<?php $columns = array_keys($custjson['data']['right'][$section][$row]); ?>
									<?php foreach ($columns as $column) : ?>
										<td class="<?= $config->textjustify[$custjson['columns']['right'][$section][$column]['datajustify']]; ?>">
											<?php echo $custjson['data']['right'][$section][$row][$column]; ?>
										</td>
									<?php endforeach; ?>
								</tr>
							<?php endforeach; ?>
							<tr class="last-section-row"> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> </tr>
						<?php endif; ?>
					<?php endforeach; ?>

					<?php $misccolumns = array_keys($custjson['data']['right']['misc']); ?>
					<?php foreach ($misccolumns as $misc) : ?>
						<?php if ($misc != 'rfml') : ?>
							<tr>
								<td class="<?= $config->textjustify[$custjson['columns']['right']['misc'][$misc]['headingjustify']]; ?>">
									<?php echo $custjson['columns']['right']['misc'][$misc]['heading']; ?>
								</td>
								<td class="<?= $config->textjustify[$custjson['columns']['right']['misc'][$misc]['datajustify']]; ?>">
									<?php echo $custjson['data']['right']['misc'][$misc]; ?>
								</td>
								<td></td>
							</tr>
						<?php endif; ?>
					<?php endforeach; ?>
				</table>
			</div>
		</div>
		<?php include $config->paths->content."cust-information/cust-sales-data.php"; ?>
	</div>
</div>
