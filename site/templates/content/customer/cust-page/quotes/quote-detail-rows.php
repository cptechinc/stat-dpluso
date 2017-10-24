<tr class="detail">
    <th class="text-center">Item ID</th>
    <th colspan="2">Description</th>
    <th>Price</th>
    <th>Qty</th>
    <th>Ext Price</th>
    <th>Notes</th>
    <th></th>
</tr>

<?php $quotedetails = get_quote_details(session_id(), $quote->quotnbr, false); ?>
<?php foreach ($quotedetails as $quotedetail) : ?>
    <?php
        $detailnoteurl = $config->pages->notes.'redir/?action=get-quote-notes&qnbr='.$quote->quotnbr.'&linenbr='.$quotedetail['linenbr'].'&modal=modal';
        if ($quotedetail['notes'] == 'Y') {
            $detnoteicon = '<a class="h3 load-notes" href="'.$detailnoteurl.'" data-modal="<?= $quotepanel->modal; ?>"> <i class="material-icons" title="View quote notes">&#xE0B9;</i></a>';
        } else {
            $detnoteicon = '<a class="h3 load-notes text-muted" href="'.$detailnoteurl.'" data-modal="<?= $quotepanel->modal; ?>"><i class="material-icons" title="View quote notes">&#xE0B9;</i></a>';
        }
    ?>
    <tr class="detail">
        <td class="text-center">
            <a href="<?= $config->pages->ajax."load/edit-detail/quote/?qnbr=".$quotedetail['quotenbr']."&line=".$quotedetail['linenbr']."&readonly=readonly"; ?>" class="update-line" data-itemid="<?= $quotedetail['itemid']; ?>" data-kit="<?= $quotedetail['kititemflag']; ?>" data-custid="<?= $quote->custid; ?>">
                <?= $quotedetail['itemid']; ?>
            </a>
        </td>
        <td colspan="2">
            <?php if (strlen($quotedetail['vendoritemid'])) { echo ' '.$quotedetail['vendoritemid']."<br>";} ?>
            <?= $quotedetail['desc1']; ?>
        </td>
        <td class="text-right">$ <?= $quotedetail['quotprice']; ?></td>
        <td class="text-right"><?= $quotedetail['quotunit']; ?></td>
        <td class="text-right">$ <?= formatmoney($quotedetail['quotprice'] * $quotedetail['quotunit']); ?></td>
        <td><?= $detnoteicon; ?></td>
        <td></td>
    </tr>
<?php endforeach; ?>
