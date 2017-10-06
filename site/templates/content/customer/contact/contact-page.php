<?php if (can_accesscustomercontact($user->loginid, $user->hasrestrictions, $custID, $shipID, $contactID, false)) : ?>
	<?php $contact = get_customercontact($custID, $shipID, $contactID, false); ?>
	<h3 class="text-muted"><?= $contact['contact']; ?></h3>
	<?php include $config->paths->content.'customer/contact/contact-address.php'; ?>
	<div class="row">
		<div class="col-sm-6"> <?php include $config->paths->content.'customer/contact/contact-card.php'; ?> </div>
	</div>
	<div class="row">
		<div class="col-sm-8">
			<?php include $config->paths->content."customer/contact/actions-panel.php"; ?>
		</div>
	</div>
<?php endif; ?>
