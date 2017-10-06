<address>
	<?php echo $contact['addr1']; ?><br>
    <?php if (strlen($contact['addr2']) > 0) { echo $contact['addr2'].'<br>'; } ?>
    <?php echo $contact['ccity'] . ', ' . $contact['cst'] . ' ' . $contact['czip']; ?>
</address>
