<?php 
	$shiptofile = $config->jsonfilepath.session_id()."-cishiptoinfo.json";
?>

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
			<div class="col-sm-6">
				<?php if ($input->urlSegment(2)) : ?>
					<?php if (file_exists($shiptofile)) : ?>
						<?php $shiptojson = json_decode(file_get_contents($shiptofile), true);  ?>
						<?php if (!$shiptojson) { $shiptojson = array('error' => true, 'errormsg' => 'The Customer Shipto Single JSON contains errors');} ?>
						<?php if ($shiptojson['error']) : ?>
							<div class="alert alert-warning" role="alert"><?php echo $shiptojson['errormsg']; ?></div>
						<?php else : ?>
							<table class="table table-striped table-bordered table-condensed table-excel">
								<?php $topcolumns = array_keys($shiptojson['columns']['top']); ?>
								<tbody>
									<?php foreach ($topcolumns as $column) : ?>
										<?php if ($shiptojson['columns']['top'][$column]['heading'] == '' && $shiptojson['data']['top'][$column] == '') : ?>
										<?php else : ?>
											<?php if ($column == 'shiptoid') : ?>
												<tr>
													<td> <?= $shiptojson['columns']['top'][$column]['heading']; ?></td>
													<td>
														<select name="shipTo" class="form-control input-sm" onChange="refreshshipto(this.value,'<?php echo $custID; ?>')">
											            	<option value=" " <?php if ($shipID == '') { echo 'selected'; } ?>>Company Totals</option>
											               	<?php $shiptos = get_customershiptos($custID, $user->loginid, $user->hascontactrestrictions, false); ?>
											                <?php foreach ($shiptos as $shipto) : ?>
																<?php $address = $shipto['addr1'] . ' ' .$shipto['addr2']; $whole_address = $address.', '.$shipto['ccity'].', '.$shipto['cst']; ?>
																<?php $show = $shipto['name'].' - '.$shipto['ccity'].', '.$shipto['cst']; ?>
												            		<option value="<?= $shipto['shiptoid']; ?>" <?php if ($shipto['shiptoid'] == $shipID) { echo 'selected'; } ?>><?= $show; ?> </option>
											            	<?php endforeach; ?>
											            </select>
													</td>
												</tr>
											<?php else : ?>
												<tr>
													<td> <?= $shiptojson['columns']['top'][$column]['heading']; ?></td> <td> <?= $shiptojson['data']['top'][$column]; ?></td>
												</tr>
											<?php endif; ?>
										<?php endif; ?>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php endif; ?>
					<?php else : ?>
						<div class="alert alert-warning" role="alert">Information Not Available</div>
					<?php endif; ?>
					<a href="<?= $config->pages->custinfo.$custID.'/'; ?>" class="btn btn-primary">Clear Shipto</a>
					<div class="form-group"></div>
				<?php else : ?>
					<table class="table table-striped table-bordered table-condensed table-excel">
						<?php $topcolumns = array_keys($custjson['columns']['top']); ?>
						<?php foreach ($topcolumns as $column) : ?>
							<?php if ($custjson['columns']['top'][$column]['heading'] == '' && $custjson['data']['top'][$column] == '') : ?>
							<?php else : ?>
								<tr>
									<td> <?= $custjson['columns']['top'][$column]['heading']; ?></td>
									<td>
										<?php if ($column == 'customerid') : ?>
											<select name="shipTo" class="form-control input-sm" onChange="refreshshipto(this.value,'<?php echo $custID; ?>')">
												<option value=" " <?php if ($shipID == '') { echo 'selected'; } ?>>Company Totals</option>
												<?php $shiptos = get_customershiptos($custID, $user->loginid, $user->hascontactrestrictions, false); ?>
												<?php foreach ($shiptos as $shipto) : ?>
												<?php $address = $shipto['addr1'] . ' ' .$shipto['addr2']; $whole_address = $address.', '.$shipto['ccity'].', '.$shipto['cst']; ?>
												<?php $show = $shipto['name'].' - '.$shipto['ccity'].', '.$shipto['cst']; ?>
													<option value="<?= $shipto['shiptoid']; ?>" <?php if ($shipto['shiptoid'] == $shipID) { echo 'selected'; } ?>><?= $show; ?> </option>
												<?php endforeach; ?>
											</select>
										<?php else : ?>
											<?php echo $custjson['data']['top'][$column]; ?>
										<?php endif; ?>
									</td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					</table>
				<?php endif; ?>
			</div>
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
