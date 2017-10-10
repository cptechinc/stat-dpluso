<div class="table-responsive">
	<table id="cust-index" class="table table-striped table-bordered">
		<thead>
			<tr>
            	<th width="100">CustID</th> <th>Customer Name</th> <th>Ship-To</th> <th>Location</th> <th>Contact</th> <th width="100">Phone</th>
                <th>Amount Sold</th> <th>Times Sold</th> <th>Last Sales Date</th>
            </tr>
		</thead>
		<tbody>
			<?php if ($resultscount > 0) : ?>
				<?php
					if ($input->get->q) {
						$customer_records = search_custindexpaged($user->loginid, $config->showonpage, $input->pageNum, $user->hascontactrestrictions, $input->get->q, false);
					} else {
						$customer_records = get_distinctcustindexpaged($user->loginid, $config->showonpage, $input->pageNum, $user->hascontactrestrictions, false);
					}
				?>
				<?php foreach ($customer_records as $cust) : ?>
					<tr>
						<td>
							<a href="<?= $cust->generatecustloadurl(); ?>">
								<?= highlight($cust->custid, $input->get->q,'<span class="highlight">{ele}</span>');?>
							</a> &nbsp; <span class="glyphicon glyphicon-share"></span>
						</td>
						<td><?= highlight($cust->name, $input->get->q,'<span class="highlight">{ele}</span>'); ?></td>
						<td><?= highlight($cust->shiptoid, $input->get->q,'<span class="highlight">{ele}</span>'); ?></td>
						<td><?= highlight($cust->generateaddress(), $input->get->q, '<span class="highlight">{ele}</span>'); ?></td>
						<td><a href="<?= $cust->generatecontacturl(); ?>"><?= highlight($cust->contact, $input->get->q, '<span class="highlight">{ele}</span>'); ?></a></td>
						<td><a href="tel:<?= $cust->cphone; ?>" title="Click To Call"><?= highlight($cust->cphone, $input->get->q,'<span class="highlight">{ele}</span>'); ?></a></td>
						<td class="text-right">$ <?= formatmoney($cust->amountsold); ?></td>
						<td class="text-right"> <?= $cust->timesold; ?></td>
						<td> <?= DplusDateTime::formatdate($cust->lastsaledate); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<td colspan="9" class="text-center"><b>No Customers match your query.</b></td>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
            	<th width="100">CustID</th> <th>Customer Name</th> <th>Ship-To</th> <th>Location</th> <th>Contact</th> <th width="100">Phone</th>
                <th>Amount Sold</th> <th>Times Sold</th> <th>Last Sales Date</th>
            </tr>
		</tfoot>
	</table>
</div>
<?php $total_pages = ceil($resultscount / $config->showonpage); $pagination_link = $config->pages->customer.'page'; ?>
<?php if (isset($input->get->q)) { $linkaddon = '?q='.$input->get->q; } else { $linkaddon = ''; } ?>
<?php $insertafter = '/customers'; ?>
<?php include $config->paths->content.'pagination/pw/pagination-links.php'; ?>
