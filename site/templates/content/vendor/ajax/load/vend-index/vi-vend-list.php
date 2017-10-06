<?php
    if ($input->get->q) {
        $vendresults = search_vendorspaged($config->showonpage, $input->pageNum, $input->get->text('q'),  false);
        $resultscount = count_searchvendors($input->get->text('q'), false);
    }
?>

<div id="vend-results">
    <?php if ($input->get->q) : ?>
        <table id="vend-index" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="100">VendID</th> <th>Vendor Name</th> <th>Ship-From</th> <th>Address</th> <th>City</th> <th>State</th> <th>Zip</th> <th width="100">Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultscount > 0) : ?>
                    <?php foreach ($vendresults as $vend) : ?>
                        <tr>
                            <td>
                                <a href="#">
                                    <?= highlight($vend['vendid'], $input->get->text('q'),'<span class="highlight">{ele}</span>');?>
                                </a> &nbsp; <span class="glyphicon glyphicon-share"></span>
                            </td>
                            <td><?= highlight($vend['name'], $input->get->q,'<span class="highlight">{ele}</span>'); ?></td>
                            <td><?= highlight($vend['shipfrom'], $input->get->q,'<span class="highlight">{ele}</span>'); ?></td>
                            <td>
                                <?= highlight($vend['address1'], $input->get->q,'<span class="highlight">{ele}</span>'); ?>
                                <?= highlight($vend['address2'], $input->get->q,'<span class="highlight">{ele}</span>'); ?>
                            </td>
                            <td><?= highlight($vend['city'], $input->get->q, '<span class="highlight">{ele}</span>'); ?></td>
                            <td><?= highlight($vend['state'], $input->get->q, '<span class="highlight">{ele}</span>'); ?></td>
                            <td><?= highlight($vend['zip'], $input->get->q, '<span class="highlight">{ele}</span>'); ?></td>
                            <td><a href="tel:<?= $vend['phone']; ?>" title="Click To Call"><?= highlight($vend['phone'], $input->get->q,'<span class="highlight">{ele}</span>'); ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <td colspan="5">
                        <h4 class="list-group-item-heading">No Vendor Matches your query.</h4>
                    </td>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
