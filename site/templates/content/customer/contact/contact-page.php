<a href="<?= $contact->generate_customerurl(); ?>" class="btn btn-primary"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Go To <?= $contact->get_customername()."'s"; ?> Page </a>
<h3 class="text-muted"><?= $contact->contact; ?></h3>
<?php include $config->paths->content.'customer/contact/contact-address.php'; ?>
<div class="row">
	<div class="col-sm-6"> <?php include $config->paths->content.'customer/contact/contact-card.php'; ?> </div>
</div>
<div class="row">
	<div class="col-sm-8">
		<?php include $config->paths->content."customer/contact/actions/actions-panel.php"; ?>
	</div>
</div>
