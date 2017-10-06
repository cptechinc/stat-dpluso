<?php if ($pricing['priceqty2'] != "" && $pricing['priceqty2'] != "0") : ?>
    <table class="table-condensed table-bordered table">
        <tr> <th>Qty</th> <th class="text-right">Break</th> </tr>
        <tr> <td><?php echo $pricing['priceqty1']; ?></td> <td class="text-right">$ <?php echo $pricing['priceprice1']; ?></td> </tr>
        <tr> <td><?php echo $pricing['priceqty2']; ?></td> <td class="text-right">$ <?php echo $pricing['priceprice2']; ?></td> </tr>
        <?php if ($pricing['priceqty3'] != "" && $pricing['priceqty3'] != "0") : ?>
            <tr> <td><?php echo $pricing['priceqty3']; ?></td> <td class="text-right">$ <?php echo $pricing['priceprice3']; ?></td> </tr>
        <?php endif; ?>
        <?php if ($pricing['priceqty4'] != "" && $pricing['priceqty4'] != "0") : ?>
            <tr> <td><?php echo $pricing['priceqty4']; ?></td> <td class="text-right">$ <?php echo $pricing['priceprice4']; ?></td> </tr>
        <?php endif; ?>
        <?php if ($pricing['priceqty5'] != "" && $pricing['priceqty5'] != "0") : ?>
            <tr> <td><?php echo $pricing['priceqty5']; ?></td> <td class="text-right">$ <?php echo $pricing['priceprice5']; ?></td> </tr>
        <?php endif; ?>
        <?php if ($pricing['priceqty6'] != "" && $pricing['priceqty6'] != "0") : ?>
            <tr> <td><?php echo $pricing['priceqty6']; ?></td> <td class="text-right">$ <?php echo $pricing['priceprice6']; ?></td> </tr>
        <?php endif; ?>
    </table>
<?php endif; ?>
