<div class="text-center form-group hidden-xs">
	<div class="btn-group" role="group" aria-label="View Order Attachments">
		<?php //echo $headnotelink; ?>
		<?= $billing->generate_loaddocumentslink($orderpanel); ?>
		<?php //echo $tracklink; ?>
	</div>
</div>
<div class="text-center form-group hidden-sm hidden-md hidden-lg">
	<div class="btn-group-vertical" role="group" aria-label="View Order Attachments">
		<?php //echo $headnotelink; ?>
		<?php echo $billing->generate_loaddocumentslink($orderpanel); ?>
		<?php //echo $tracklink; ?>
	</div>
</div>
