<?php 
	$salesfile = $config->jsonfilepath.session_id()."-iisalesordr.json";
	//$salesfile = $config->jsonfilepath."iiso-iisalesordr.json";



	if (checkformatterifexists($user->loginid, 'ii-sales-order', false)) {
		$defaultjson = json_decode(getformatter($user->loginid, 'ii-sales-order', false), true);
	} else {
		$default = $config->paths->content."item-information/screen-formatters/default/ii-sales-order.json";
		$defaultjson = json_decode(file_get_contents($default), true);
	}

	$columns = array_keys($defaultjson['columns']);
	$fieldsjson = json_decode(file_get_contents($config->companyfiles."json/iisofmattbl.json"), true);

	$table = array('maxrows' => $defaultjson['rows'], 'maxcolumns' => $defaultjson['cols'], 'rows' => array());
	for ($i = 1; $i < $defaultjson['rows'] + 1; $i++) {
		$table['rows'][$i] = array('columns' => array());
		$count = 1;
		foreach($columns as $column) {
			if ($defaultjson['columns'][$column]['line'] == $i) {
				$table['rows'][$i]['columns'][$count] = array('id' => $column, 'label' => $defaultjson['columns'][$column]['label'], 'col-length' => $defaultjson['columns'][$column]['col-length'], 'before-decimal' => $defaultjson['columns'][$column]['before-decimal'], 'after-decimal' => $defaultjson['columns'][$column]['after-decimal'], 'date-format' => $defaultjson['columns'][$column]['date-format']);
				$count++;
			}
		}
	}


?>

<?php if (file_exists($salesfile)) : ?>
    <?php $ordersjson = json_decode(file_get_contents($salesfile), true);  ?>
    <?php if (!$ordersjson) { $ordersjson = array('error' => true, 'errormsg' => 'The sales order JSON contains errors');} ?>

    <?php if ($ordersjson['error']) : ?>
        <div class="alert alert-warning" role="alert"><?php echo $ordersjson['errormsg']; ?></div>
    <?php else : ?>


        <?php foreach ($ordersjson['data'] as $whse) : ?>

            <?php if ($whse != $ordersjson['data']['zz']) : ?>
                <div>
                    <h3><?php echo $whse['Whse Name']; ?></h3>
                    <table class="table table-striped table-bordered table-condensed table-excel" id="<?php echo urlencode($whse['Whse Name']); ?>">
                        <thead>
                           <?php for ($x = 1; $x < $table['maxrows'] + 1; $x++) : ?>
                          		<tr>
                           		<?php for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) : ?>
                           			<?php if (isset($table['rows'][$x]['columns'][$i])) : ?>
                           				<?php $column = $table['rows'][$x]['columns'][$i]['id']; ?>
                           				<th class="<?= $config->textjustify[$fieldsjson['data'][$column]['datajustify']]; ?>"><?php echo $table['rows'][$x]['columns'][$i]['label']; ?> </th>
                           			<?php else : ?>
                           				<th></th>
                           			<?php endif; ?>

                           		<?php endfor; ?>
                           		</tr>
                           <?php endfor; ?>
                        </thead>
                        <tbody>
                           	<?php foreach($whse['orders'] as $order) : ?>
                           		<?php if ($order != $whse['orders']['TOTAL']) : ?>
								<?php for ($x = 1; $x < $table['maxrows'] + 1; $x++) : ?>
									<tr>
									<?php for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) : ?>
										<?php if (isset($table['rows'][$x]['columns'][$i])) : ?>
											<?php $column = $table['rows'][$x]['columns'][$i]; ?>
											<td colspan="<?= $column['col-length']; ?>" class="<?= $config->textjustify[$fieldsjson['data'][$column['id']]['datajustify']]; ?>">
												<?php if ($fieldsjson['data'][$column['id']]['type'] == 'D') : ?>
													<?php if (strlen($order[$column['id']]) > 0 ) : ?>
														<?php echo date($column['date-format'], strtotime($order[$column['id']]));  ?>
													<?php endif; ?>
												<?php elseif ($fieldsjson['data'][$column['id']]['type'] == 'N') : ?>
													<?php echo number_format($order[$column['id']], $column['after-decimal']); ?>
												<?php else : ?>
													<?php echo $order[$column['id']]; ?>
												<?php endif; ?>
											</td>
										<?php else : ?>
											<td></td>
										<?php endif; ?>

									<?php endfor; ?>
									</tr>
							   <?php endfor; ?>
                          		<?php endif; ?>
                           	<?php endforeach; ?>

                        </tbody>
                    </table>

                </div>
            <?php endif; ?>

        <?php endforeach; ?>
        <div>
            <h3><?php echo $ordersjson['data']['zz']['Whse Name']; ?></h3>
            <table class="table table-striped table-bordered table-condensed table-excel">
                <thead>
                    <?php for ($x = 1; $x < $table['maxrows'] + 1; $x++) : ?>
						<tr>
						<?php for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) : ?>
							<?php if (isset($table['rows'][$x]['columns'][$i])) : ?>
								<?php $column = $table['rows'][$x]['columns'][$i]['id']; ?>
								<th class="<?= $config->textjustify[$fieldsjson['data'][$column]['datajustify']]; ?>"><?php echo $table['rows'][$x]['columns'][$i]['label']; ?> </th>
							<?php else : ?>
								<th></th>
							<?php endif; ?>

						<?php endfor; ?>
						</tr>
				   <?php endfor; ?>
                </thead>
                <tbody>
                    <?php foreach($ordersjson['data']['zz']['orders'] as $order) : ?>
						<?php for ($x = 1; $x < $table['maxrows'] + 1; $x++) : ?>
							<tr>
							<?php for ($i = 1; $i < $table['maxcolumns'] + 1; $i++) : ?>
								<?php if (isset($table['rows'][$x]['columns'][$i])) : ?>
									<?php $column = $table['rows'][$x]['columns'][$i]; ?>
									<td colspan="<?= $column['col-length']; ?>" class="<?= $config->textjustify[$fieldsjson['data'][$column['id']]['datajustify']]; ?>">
										<?php if ($fieldsjson['data'][$column['id']]['type'] == 'D') : ?>
											<?php if (strlen($order[$column['id']]) > 0 ) : ?>
												<?php echo date($column['date-format'], strtotime($order[$column['id']]));  ?>
											<?php endif; ?>
										<?php elseif ($fieldsjson['data'][$column['id']]['type'] == 'N') : ?>
											<?php echo number_format($order[$column['id']], $column['after-decimal']); ?>
										<?php else : ?>
											<?php echo $order[$column['id']]; ?>
										<?php endif; ?>
									</td>
								<?php else : ?>
									<td></td>
								<?php endif; ?>

							<?php endfor; ?>
							</tr>
					   <?php endfor; ?>
					<?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

<?php else : ?>
    <div class="alert alert-warning" role="alert">Information Not Available</div>
<?php endif; ?>
