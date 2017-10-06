<form action="<?php echo $formaction; ?>" method="post" id="<?= $linedetail['itemid'].'-form'; ?>">
    <input type="hidden" name="action" value="update-line">
    <input type="hidden" name="ordn" value="<?= $ordn; ?>">
    <input type="hidden" class="listprice" value="<?= formatmoney($linedetail['listprice']); ?> ">
    <input type="hidden" class="linenumber" name="linenbr" value="<?= $linedetail['linenbr']; ?> ">
    <input type="hidden" class="originalprice" value="<?= formatmoney($linedetail['price']); ?> ">
    <input type="hidden" class="discountprice" value="<?= formatmoney($linedetail['price']); ?> ">
    <input type="hidden" class="cost" value="<?= formatmoney($linedetail['cost']); ?> ">
    <input type="hidden" class="calculate-from" value="percent">
    <?php if ($soconfig['config']['use_discount'] != 'Y'): ?>
        <input type="hidden" class="discpct" name="discount" value="<?= formatmoney($linedetail['discpct']); ?>">
    <?php endif; ?>
    <div class="row form-group">
        <div class="col-sm-8">
            <div class="jumbotron item-detail-heading"> <div class="container"> <h4>Item Info</h4> </div> </div>
            <?php include $config->paths->content."edit/pricing/item-info.php"; ?>
        </div>
        <div class="col-sm-4 table-bordered">
            <h4>Current Price</h4>
            <table class="table table-bordered table-striped table-condensed">
                <tr> <td>Price </td> <td class="text-right">$ <?= formatmoney($linedetail['price']); ?></td> </tr>
                <tr> <td>Unit of Measurement</td> <td> <?= $linedetail['uom'] ?></td> </tr>
                <tr> <td>Qty</td> <td class="text-right"><?= $linedetail['qtyordered']+0; ?></td> </tr>
                <tr> <td>Original Ext. Amt.</td> <td class="text-right">$ <?= formatmoney($linedetail['price'] * $linedetail['qtyordered']); ?></td> </tr>
                <?php if ($soconfig['config']['show_originalprice'] == 'Y') : ?>
                    <tr> <td>Original Price</td> <td class="text-right">$ <?= formatmoney($linedetail['price']); ?></td> </tr>
                <?php endif; ?>
                <?php if ($soconfig['config']['show_listprice'] == 'Y') : ?>
                    <tr> <td>List Price</td> <td class="text-right">$ <?= formatmoney($linedetail['listprice']); ?></td> </tr>
                <?php endif; ?>
                <?php if ($soconfig['config']['show_cost'] == 'Y') : ?>
                    <tr> <td>Cost</td> <td class="text-right">$ <?= formatmoney($linedetail['cost']); ?></td> </tr>
                <?php endif; ?>
                <tr><td>Kit:</td><td><?php echo $linedetail['kititemflag']; ?></td></tr>
            </table>
        </div>
    </div>
    <div class="row row-bordered">
        <div class="col-sm-4 grid-item">
            <div class="jumbotron item-detail-heading"> <div class="container"> <h4>Item Pricing</h4> </div> </div>
            <?php include $config->paths->content."edit/pricing/item-pricing.php"; ?>
        </div>
        <div class="col-sm-4 grid-item">
            <?php include $config->paths->content."edit/pricing/edit-pricing-table.php"; ?>
        </div>
        <div class="col-sm-4 grid-item">
            <table class="table table-bordered table-striped table-condensed">
                <tr>
                    <td>Requested Ship Date</td>
                    <td>
                        <div class="input-group date" style="width: 180px;">
                           <?php $name = 'rqst-date'; $value = $linedetail['rshipdate'];?>
							<?php include $config->paths->content."common/date-picker.php"; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Warehouse</td><td><input type="text" class="form-control input-sm qty <?= $linedetail['itemid']."-whse"; ?>" name="whse" value="<?= $linedetail['whse']; ?>"></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row row-bordered">
        <div class="col-sm-8 grid-item">
            <div class="jumbotron item-detail-heading"> <div class="container"> <h4>Item Availability</h4> </div> </div>
            <?php include $config->paths->content."edit/pricing/item-stock.php"; ?>
        </div>
        <div class="col-sm-4 grid-item">
            <div class="jumbotron item-detail-heading"> <div class="container"> <h4>Item History for <?= get_customername($custID); ?></h4> </div> </div>
            <?php include $config->paths->content."edit/pricing/item-history.php"; ?>
        </div>
    </div>
    <?php if ($linedetail['can-edit']) :?>
        <div class="text-center">
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    <?php endif; ?>
</form>
