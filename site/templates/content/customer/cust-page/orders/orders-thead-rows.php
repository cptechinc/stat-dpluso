<tr>
    <th>Detail</th>
    <th>
        <a href="<?= $orderpanel->generate_tablesortbyurl("orderno") ; ?>" class="load-link" <?= $orderpanel->ajaxdata; ?>>
            Order # <?= $orderpanel->tablesorter->generate_sortsymbol('orderno'); ?>
        </a>
    </th>
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
    <th class="text-center">
        <a href="<?= $orderpanel->generate_tablesortbyurl("status") ; ?>" class="load-link" <?= $orderpanel->ajaxdata; ?>>
            Status: <?= $orderpanel->tablesorter->generate_sortsymbol('status'); ?>
        </a>
    </th>
    <th colspan="2">
        <?= $orderpanel->generate_iconlegend(); ?>
        <?php if (isset($input->get->orderby)) : ?>
            <?= $orderpanel->generate_clearsortlink(); ?>
        <?php endif; ?>
    </th>
    <th colspan="2"> </th>
</tr>
