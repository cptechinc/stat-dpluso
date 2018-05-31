<?php $carthead = get_carthead(session_id(), false); ?>
<?php
	if ($modules->isInstalled('QtyPerCase')) {
		include $config->paths->siteModules.'QtyPerCase/content/cart/cart-details.php';
	} else {
		include $config->paths->content."/cart/cart-details.php";
	}
?>
<br>
<?php if (has_dpluspermission($user->loginid, 'eso')) : ?>
	<a href="<?= $config->pages->cart."redir/?action=create-sales-order"; ?>" class="btn btn-success create-order" data-type="order">
		<span class="fa-stack fa-md">
		  <i class="fa fa-usd fa-stack-1x"></i>
		  <i class="fa fa-file-o fa-stack-2x"></i>
		</span>
		Create Sales Order
	</a>
<?php endif; ?>

<?php if (has_dpluspermission($user->loginid, 'eqo')) : ?>
	<a href="<?= $config->pages->cart."redir/?action=create-quote"; ?>" class="btn btn-success create-order" data-type="quote">
		<span class="fa-stack fa-md" aria-hidden="true">
		  <i class="fa fa-quote-left fa-stack-1x"></i>
		  <i class="fa fa-file-o fa-stack-2x"></i>
		</span>
		Create Quote
	</a>
<?php endif; ?>
