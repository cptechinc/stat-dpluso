<table class="table table-bordered table-striped table-condensed">
    <tr> <td>Price </td> <td class="text-right">$ <?= formatmoney($linedetail['price']); ?></td> </tr>
    <tr> <td>Unit of Measurement</td> <td> <?= $linedetail['uom'] ?></td> </tr>
    <tr> <td>Qty</td> <td class="text-right"><input type="text" class="qty form-control input-sm text-right" name="qty" value="<?= $linedetail['qtyordered']+0; ?>"></td> </tr>
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
