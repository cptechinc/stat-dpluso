<?php if ($custID != '' ) : ?>
    <tr> <td>Customer:</td> <td><?= get_customername($custID)." ($custID)"; ?></td> </tr>
<?php endif; ?>

<?php if ($shipID != '' ) : ?>
    <tr> <td>Ship-to:</td> <td><?= get_shiptoname($custID, $shipID, false). " ($shipID)"; ?></td>  </tr>
<?php endif; ?>

<?php if ($contactID != '') : ?>
    <tr> <td>Contact:</td> <td><?php echo $contactID; ?></td>  </tr>
<?php endif; ?>

<?php if ($ordn != '') : ?>
    <tr> <td>Order #:</td> <td><?php echo $ordn; ?></td>  </tr>
<?php endif; ?>

<?php if ($qnbr != '') : ?>
    <tr> <td>Quote #:</td> <td><?php echo $qnbr; ?></td>  </tr>
<?php endif; ?>

<?php if ($taskID != '') : ?>
    <tr> <td>Task #:</td> <td><?php echo $taskID; ?></td>  </tr>
<?php endif; ?>

<?php if ($noteID != '') : ?>
    <tr> <td>Note #:</td> <td><?php echo $noteID; ?></td>  </tr>
<?php endif; ?>
