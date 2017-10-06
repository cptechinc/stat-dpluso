<?php $historyfile = $config->jsonfilepath.session_id()."-iisaleshist.json"; ?>
<?php if (file_exists($historyfile)) : ?>
    <?php $jsonhistory = json_decode(file_get_contents($historyfile), true); ?>
    <?php if (!$jsonhistory) {$jsonhistory = array('error' => true, 'errormsg' => 'The item history JSON contains errors');} ?>

    <?php if ($jsonhistory['error']) : ?>
        <div class="alert alert-warning" role="alert"><?php echo $jsonhistory['errormsg']; ?></div>
    <?php else : ?>
        <?php $columns = array_keys($jsonhistory['columns']); ?>
        <?php foreach($jsonhistory['data'] as $warehouse) : ?>
            <h3><?php echo $warehouse['Whse Name']; ?></h3>
            <table class="table table-striped table-bordered table-condensed table-excel">
                <thead>
                    <?php foreach($jsonhistory['columns'] as $column) : ?>
                        <th class="<?= $config->textjustify[$column['headingjustify']]; ?>"><?php echo $column['heading']; ?></th>
                    <?php endforeach; ?>
                </thead>
                <tbody>
                    <?php foreach($warehouse['invoices'] as $invoices) : ?>
                        <tr>
                            <?php foreach($columns as $column) : ?>
                                <td class="<?= $config->textjustify[$jsonhistory['columns'][$column]['datajustify']]; ?>"><?php echo $invoices[$column]; ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php foreach ($invoices['lots'] as $lot) : ?>
                            <tr>
                                <td colspan="<?php echo sizeof($jsonhistory['columns'])-3; ?>"></td>
                                <?php if (strpos($lot['Lot/Serial Number'], 'Lot') !== false) : ?>
                                    <td> <?php echo $lot['Lot/Serial Number']; ?> </td>
                                <?php else : ?>
                                    <td> &nbsp; &nbsp; &nbsp; &nbsp; <?php echo $lot['Lot/Serial Number']; ?> </td>
                                <?php endif; ?>
                                <td class="text-right"><?php echo $lot['Lot/Serial Qty Shipped']; ?></td>
                                <td colspan="<?php echo sizeof($jsonhistory['columns'])-3-2; ?>"></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php endif; ?>
<?php else : ?>
    <div class="alert alert-warning" role="alert">Information Not Available</div>
<?php endif; ?>
