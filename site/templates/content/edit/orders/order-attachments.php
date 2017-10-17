<div class="text-center form-group hidden-xs">
	<div class="btn-group" role="group" aria-label="View Order Attachments">
		<?= $ordereditdisplay->generate_loaddplusnoteslink($order);?>
		<?= $ordereditdisplay->generate_loaddocumentslink($order); ?>
		<?= $ordereditdisplay->generate_loadtrackinglink($order); ?>
	</div>
</div>
<div class="text-center form-group hidden-sm hidden-md hidden-lg">
	<div class="btn-group-vertical" role="group" aria-label="View Order Attachments">
		<?= $ordereditdisplay->generate_loaddplusnoteslink($order); ?>
		<?= $ordereditdisplay->generate_loaddocumentslink($order); ?>
		<?= $ordereditdisplay->generate_loadtrackinglink($order); ?>
	</div>
</div>
