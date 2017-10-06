<?php $quotesfile = $config->jsonfilepath.session_id()."-iiquote.json"; ?>
<?php //$quotesfile = $config->jsonfilepath."iiqt-iiquote.json";?>
<?php if ($config->ajax) : ?>
	<p> <a href="<?php echo $config->filename; ?>" class="h4" target="_blank"><i class="glyphicon glyphicon-print" aria-hidden="true"></i> View Printable Version</a> </p>
<?php endif; ?>
<?php if (file_exists($quotesfile)) : ?>
    <?php $quotesjson = json_decode(file_get_contents($quotesfile), true);  ?>
    <?php if (!$quotesjson) { $quotesjson = array('error' => true, 'errormsg' => 'The item quotes JSON contains errors');} ?>

    <?php if ($quotesjson['error']) : ?>
        <div class="alert alert-warning" role="alert"><?php echo $quotesjson['errormsg']; ?></div>
    <?php else : ?>
        <?php $columns = array_keys($quotesjson['columns']); ?>
        <?php foreach ($quotesjson['data'] as $whse) : ?>
            <h3><?php echo $whse['Whse Name']; ?></h3>
            <table class="table table-striped table-bordered table-condensed table-excel">
                <thead>
                    <tr>
                        <?php foreach($quotesjson['columns'] as $column) : ?>
                            <th class="<?= $config->textjustify[$column['headingjustify']]; ?>"><?php echo $column['heading']; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($whse['quotes'] as $quote) : ?>
                        <tr>
                            <?php foreach ($columns as $column) : ?>
                                <td class="<?= $config->textjustify[$quotesjson['columns'][$column]['datajustify']]; ?>"><?php echo $quote[$column]; ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php endif; ?>
<?php else : ?>
    <div class="alert alert-warning" role="alert">Information Not Available</div>
<?php endif; ?>
