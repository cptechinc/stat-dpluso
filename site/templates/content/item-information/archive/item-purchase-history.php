<?php $purchasehistoryfile = $config->jsonfilepath.session_id()."-iipurchhist.json"; ?>
<?php //$purchasehistoryfile= $config->jsonfilepath."iiph-iipurchhist.json"; ?>


<?php if (file_exists($purchasehistoryfile)) : ?>
    <?php $whsestock = json_decode(file_get_contents($purchasehistoryfile), true);  ?>
    <?php if (!$whsestock ) { $whsestock = array('error' => true, 'errormsg' => 'The purchase history JSON contains errors');} ?>

    <?php if ($whsestock['error']) : ?>
        <div class="alert alert-warning" role="alert"><?php echo $whsestock['errormsg']; ?></div>
    <?php else : ?>
        <?php $columns = array_keys($whsestock['columns']); ?>
        <?php foreach ($whsestock['data'] as $whse) : ?>

            <?php if ($whse != $whsestock['data']['zz']) : ?>
                <div>
                    <h3><?php echo $whse['Whse Name']; ?></h3>
                    <table class="table table-striped table-bordered table-condensed table-excel">
                        <thead>
                            <tr>
                                <?php foreach($whsestock['columns'] as $column) : ?>
                                    <th class="<?= $config->textjustify[$column['headingjustify']]; ?>"><?php echo $column['heading']; ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($whse['orders'] as $order) : ?>
                                <tr>
                                    <?php foreach($columns as $column) : ?>
                                        <td class="<?= $config->textjustify[$whsestock['columns'][$column]['datajustify']]; ?>"><?php echo $order[$column]; ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            <?php endif; ?>

        <?php endforeach; ?>
        <div>
            <h3><?php echo $whsestock['data']['zz']['Whse Name']; ?></h3>
            <table class="table table-striped table-bordered table-condensed table-excel">
                <thead>
                    <tr>
                        <?php foreach($whsestock['columns'] as $column) : ?>
                            <th class="<?php echo $config->textjustify[$column['headingjustify']]; ?>"><?php echo $column['heading']; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($whsestock['data']['zz']['orders'] as $order) : ?>
                        <tr>
                            <?php foreach($columns as $column) : ?>
                                <td class="<?= $config->textjustify[$whsestock['columns'][$column]['datajustify']]; ?>"><?php echo $order[$column]; ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

<?php else : ?>
    <div class="alert alert-warning" role="alert">Information Not Available</div>
<?php endif; ?>
