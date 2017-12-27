<?php if ($usagefile) : ?>
    <?php if ($usagejson['error']) : ?>
        <div class="alert alert-warning" role="alert"><?php echo $usagejson['errormsg']; ?></div>
    <?php else : ?>
        <table class="table table-striped table-bordered table-condensed table-excel no-bottom">
            <thead>
                <tr>
                    <?php foreach($usagejson['columns']['sales usage'] as $column) : ?>
                        <th class="<?= $config->textjustify[$column['headingjustify']]; ?>"><?php echo $column['heading']; ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($usagejson['data']['sales usage'] as $salesusage) : ?>
                    <tr>
                        <?php foreach ($usagecolumns as $column) : ?>
                            <td class="<?= $config->textjustify[$usagejson['columns']['sales usage'][$column]['datajustify']]; ?>"><?php echo $salesusage[$column]; ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php foreach ($warehouses as $whse) : ?>
            <?php if ($whse != 'zz') : ?>
                <h3><?php echo $usagejson['data']['24month'][$whse]['whse name']; ?></h3>
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#<?=$whse."-table"; ?>" aria-controls="<?=$whse."-table"; ?>" role="tab" data-toggle="tab">Table</a></li>
                        <li role="presentation"><a href="#<?=$whse."-graph"; ?>" aria-controls="<?=$whse."-graph"; ?>" role="tab" data-toggle="tab">Graph</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="<?=$whse."-table"; ?>">
                            <table class="table table-striped table-bordered table-condensed table-excel no-bottom">
                                <tr>
                                    <?php foreach($usagejson['columns']['24month'] as $column) : ?>
                                        <th class="<?= $config->textjustify[$column['headingjustify']]; ?>"><?php echo $column['heading']; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                                <?php foreach($usagejson['data']['24month'][$whse]['months'] as $month) : ?>
                                    <tr>
                                        <?php foreach ($todatecolumns as $column) : ?>
                                            <td class="<?= $config->textjustify[$usagejson['columns']['24month'][$column]['datajustify']]; ?>"><?php echo $month[$column]; ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="<?=$whse."-graph"; ?>"> <div id="<?php echo $whse."-chart"; ?>"></div> </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <h3><?php echo $usagejson['data']['24month']['zz']['whse name']; ?></h3>
        <div>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#zz-table" aria-controls="zz-table" role="tab" data-toggle="tab">Table</a></li>
                <li role="presentation"><a href="#zz-graph" aria-controls="zz-graph" role="tab" data-toggle="tab">Graph</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="zz-table">
                    <table class="table table-striped table-bordered table-condensed table-excel no-bottom">
                        <tr>
                            <?php foreach($usagejson['columns']['24month'] as $column) : ?>
                                <th class="<?= $config->textjustify[$column['headingjustify']]; ?>"><?php echo $column['heading']; ?></th>
                            <?php endforeach; ?>
                        </tr>
                        <?php foreach($usagejson['data']['24month']['zz']['months'] as $month) : ?>
                            <tr>
                                <?php foreach ($todatecolumns as $column) : ?>
                                    <td class="<?= $config->textjustify[$usagejson['columns']['24month'][$column]['datajustify']]; ?>"><?php echo $month[$column]; ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="zz-graph"> <div id="zz-chart"></div> </div>
            </div>
        </div>
        <script>
            $(function() {
                <?php foreach ($warehouses as $whse) : ?>
                    $('a[href="#<?=$whse."-graph"; ?>"]').on('shown.bs.tab', function (e) {
                        new Morris.Line({
                            element: '<?php echo $whse."-chart"; ?>',
                            data: [
                                <?php foreach($usagejson['data']['24month'][$whse]['months'] as $month) : ?>
                                    <?php $month['month'] = ($month['month'] == 'Current') ? date('Y-m') : str_replace(' ', ' 20', $month['month']); ?>
                                    { 
                                        month: '<?= date('Y-m', strtotime($month['month'])); ?>', 
                                        saleamount: <?= $month['sale amount']; ?>,
                                        <?php if (isset($month['lost amount'])) : ?>
                                            lostamount: <?= $month['lost amount']; ?>, 
                                        <?php endif; ?>
                                        usageamount: <?= $month['usage amount']; ?> 
                                    },
                                <?php endforeach; ?>
                            ],
                            xLabelFormat: function (x) { return  moment(x).format('MMM YYYY'); },
                            yLabelFormat: function (y) { return "$ "+y.formatMoney() + ' dollars'; },
                            hoverCallback: function(index, options, content) {
                                var data = options.data[index];

                            },
                            xkey: 'month',
                            ykeys: [
                                'saleamount', 
                                <?php if (isset($month['lost amount'])) : ?>
                                    'lostamount',
                                <?php endif; ?>
                                'usageamount'
                            ],
                            labels: [
                                'Amount Sold',
                                <?php if (isset($month['lost amount'])) : ?>
                                    'Amount Lost',
                                <?php endif; ?>
                                'Amount Used'
                            ],
                            dateFormat: function (d) {
                                var ds = new Date(d);
                                return moment(ds).format('MMM YYYY');
                            }
                        });
                    });
                    $('a[href="#<?=$whse."-graph"; ?>"]').on('hidden.bs.tab', function (e) {
                        $('#<?=$whse."-chart"; ?>').empty();
                    });
                <?php endforeach; ?>
            });
        </script>
    <?php endif; ?>
<?php else : ?>
    <div class="alert alert-warning" role="alert">Information Not Available</div>
<?php endif; ?>
