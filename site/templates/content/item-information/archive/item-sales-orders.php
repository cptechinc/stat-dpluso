<?php $salesfile = $config->jsonfilepath.session_id()."-iisalesordr.json"; ?>
<?php //$salesfile = $config->jsonfilepath."iiso-iisalesordr.json"; ?>


<?php if (file_exists($salesfile)) : ?>
    <?php $ordersjson = json_decode(file_get_contents($salesfile), true);  ?>
    <?php if (!$ordersjson) { $ordersjson = array('error' => true, 'errormsg' => 'The sales order JSON contains errors');} ?>

    <?php if ($ordersjson['error']) : ?>
        <div class="alert alert-warning" role="alert"><?php echo $ordersjson['errormsg']; ?></div>
    <?php else : ?>
        <?php $columns = array_keys($ordersjson['columns']); ?>
        <?php foreach ($ordersjson['data'] as $whse) : ?>

            <?php if ($whse != $ordersjson['data']['zz']) : ?>
                <div>
                    <h3><?php echo $whse['Whse Name']; ?></h3>
                    <table class="table table-striped table-bordered table-condensed table-excel" id="<?php echo urlencode($whse['Whse Name']); ?>">
                        <thead>
                            <tr>
                                <?php foreach($ordersjson['columns'] as $column) : ?>
                                    <th class="<?= $config->textjustify[$column['headingjustify']]; ?>"><?php echo $column['heading']; ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($whse['orders'] as $order) : ?>
                                <tr>
                                    <?php foreach($columns as $column) : ?>
                                        <td class="<?= $config->textjustify[$ordersjson['columns'][$column]['datajustify']]; ?>"><?php echo $order[$column]; ?></td>
                                    <?php endforeach; ?>
                                </tr>
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
                    <tr>
                        <?php foreach($ordersjson['columns'] as $column) : ?>
                            <th class="<?php echo $config->textjustify[$column['headingjustify']]; ?>"><?php echo $column['heading']; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($ordersjson['data']['zz']['orders'] as $order) : ?>
                        <tr>
                            <?php foreach($columns as $column) : ?>
                                <td class="<?= $config->textjustify[$ordersjson['columns'][$column]['datajustify']]; ?>"><?php echo $order[$column]; ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <script>
        $(function() {
            <?php foreach ($ordersjson['data'] as $whse) : ?>
                $('<?= '#'.urlencode($whse['Whse Name']); ?>').DataTable();
            <?php endforeach; ?>
        });

        </script>
    <?php endif; ?>

<?php else : ?>
    <div class="alert alert-warning" role="alert">Information Not Available</div>
<?php endif; ?>
