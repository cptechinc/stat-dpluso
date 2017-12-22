<?php if ($custID != '' ) : ?>
    <tr>
		<td>Customer:</td>
		<td>
  			<a href="<?= $editactiondisplay->generate_customerurl($note); ?>">
  				<?= get_customername($custID)." ($custID)"; ?>
  			</a>
   		</td>
    </tr>
<?php endif; ?>

<?php if ($shipID != '' ) : ?>
    <tr>
    	<td>Ship-to:</td>
		<td>
			<a href="<?= $editactiondisplay->generate_shiptourl($note); ?>">
				<?= get_shiptoname($custID, $shipID, false). " ($shipID)"; ?>
			</a>
   		</td>
    </tr>
<?php endif; ?>

<?php if ($contactID != '') : ?>
    <tr> <td>Contact:</td> <td><?= $contactID; ?></td>  </tr>
<?php endif; ?>

<?php if ($ordn != '') : ?>
    <tr> <td>Order #:</td> <td><?= $ordn; ?></td>  </tr>
<?php endif; ?>

<?php if ($qnbr != '') : ?>
    <tr> <td>Quote #:</td> <td><?= $qnbr; ?></td>  </tr>
<?php endif; ?>

<?php if ($taskID != '') : ?>
    <tr> <td>Task #:</td> <td><?= $taskID; ?></td>  </tr>
<?php endif; ?>

<?php if ($noteID != '') : ?>
    <tr> <td>Note #:</td> <td><?= $noteID; ?></td>  </tr>
<?php endif; ?>
