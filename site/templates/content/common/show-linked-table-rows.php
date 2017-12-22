<?php if (!empty($custID)) : ?>
    <tr> <td>Customer:</td> <td><?= get_customername($custID)." ($custID)"; ?></td> </tr>
<?php endif; ?>

<?php if (!empty($shipID)) : ?>
    <tr> <td>Ship-to:</td> <td><?= get_shiptoname($custID, $shipID, false). " ($shipID)"; ?></td>  </tr>
<?php endif; ?>

<?php if (!empty($contactID)) : ?>
    <tr> <td>Contact:</td> <td><?= $contactID; ?></td>  </tr>
<?php endif; ?>

<?php if (!empty($ordn)) : ?>
    <tr> <td>Sales Order #:</td> <td><?= $ordn; ?></td>  </tr>
<?php endif; ?>

<?php if (!empty($qnbr)) : ?>
    <tr> <td>Quote #:</td> <td><?= $qnbr; ?></td>  </tr>
<?php endif; ?>
