<?php if ($notesfile) : ?>
    <?php if ($notesjson['error']) : ?>
        <div class="alert alert-warning" role="alert"><?php echo $notesjson['errormsg']; ?></div>
    <?php else : ?>
        <h3>Inspection Notes</h3>
        <table class="table table-striped table-condensed table-excel">
            <thead>
                <tr>
                    <?php foreach($notesjson['columns']['inspection notes'] as $column) : ?>
                        <th class="<?= $config->textjustify[$column['headingjustify']]; ?>"><?php echo $column['heading']; ?></th>
                    <?php endforeach; ?>
                </tr>
                <?php foreach ($notesjson['data']['inspection notes'] as $note) : ?>
                    <tr>
                        <?php foreach ($inspectioncolumns  as $column) : ?>
                            <td class="<?= $config->textjustify[$notesjson['columns']['inspection notes'][$column]['datajustify']]; ?>"><?php echo $note[$column]; ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </thead>
        </table>

        <h3>Internal Notes</h3>
        <table class="table table-striped table-condensed table-excel">
            <thead>
                <tr>
                    <?php foreach($notesjson['columns']['internal notes'] as $column) : ?>
                        <th class="<?= $config->textjustify[$column['headingjustify']]; ?>"><?php echo $column['heading']; ?></th>
                    <?php endforeach; ?>
                </tr>
                <?php foreach ($notesjson['data']['internal notes'] as $note) : ?>
                    <tr>
                        <?php foreach ($internalcolumns as $column) : ?>
                            <td class="<?= $config->textjustify[$notesjson['columns']['internal notes'][$column]['datajustify']]; ?>"><?php echo $note[$column]; ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </thead>
        </table>

        <h3>Order Notes</h3>
        <table class="table table-striped table-condensed table-excel">
            <thead>
                <tr>
                    <?php foreach($notesjson['columns']['order notes'] as $column) : ?>
                        <th class="<?= $config->textjustify[$column['headingjustify']]; ?>"><?php echo $column['heading']; ?></th>
                    <?php endforeach; ?>
                </tr>
                <?php foreach ($notesjson['data']['order notes'] as $note) : ?>
                    <tr>
                        <?php foreach ($ordercolumns  as $column) : ?>
                            <td class="<?= $config->textjustify[$notesjson['columns']['iorder notes'][$column]['datajustify']]; ?>"><?php echo $note[$column]; ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </thead>
        </table>
    <?php endif; ?>
<?php else : ?>
    <div class="alert alert-warning" role="alert">Information Not Available</div>
<?php endif; ?>
