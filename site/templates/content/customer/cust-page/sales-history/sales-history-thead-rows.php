<tr>
	<th>Detail</th>
	<th>
		<a href="<?= $orderpanel->generate_tablesortbyurl("orderno") ; ?>" class="load-link" <?= $orderpanel->ajaxdata; ?>>
				Order # <?= $orderpanel->tablesorter->generate_sortsymbol('orderno'); ?>
		</a>
	</th>
	<th> Customer </th>
	<th>
		<a href="<?= $orderpanel->generate_tablesortbyurl("custpo") ; ?>" class="load-link" <?= $orderpanel->ajaxdata; ?>>
			Customer PO: <?= $orderpanel->tablesorter->generate_sortsymbol('custpo'); ?>
		</a>
	</th>
	<th>Ship-To</th>
	<th>
		<a href="<?= $orderpanel->generate_tablesortbyurl("ordertotal") ; ?>" class="load-link" <?= $orderpanel->ajaxdata; ?>>
			Order Totals <?= $orderpanel->tablesorter->generate_sortsymbol('ordertotal'); ?>
		</a>
	</th>
	<th>
		<a href="<?= $orderpanel->generate_tablesortbyurl("orderdate") ; ?>" class="load-link" <?= $orderpanel->ajaxdata; ?>>
			Order Date: <?= $orderpanel->tablesorter->generate_sortsymbol('orderdate'); ?>
		</a>
	</th>
	<th>
		<a href="<?= $orderpanel->generate_tablesortbyurl("invdate") ; ?>" class="load-link" <?= $orderpanel->ajaxdata; ?>>
			Invoice Date: <?= $orderpanel->tablesorter->generate_sortsymbol('invdate'); ?>
		</a>
	</th>
	<th colspan="3">
		<?= $orderpanel->generate_iconlegend(); ?>
		<?php if (isset($input->get->orderby)) : ?>
				<?= $orderpanel->generate_clearsortlink(); ?>
		<?php endif; ?>
	</th>
</tr>
