<?php
	header('Location: '.$config->pages->dashboard);
	exit;
?>
<?php include('./_head.php'); ?>
	<div class="jumbotron pagetitle">
		<div class="container">
			<h1><?php echo $page->get('pagetitle|headline|title') ; ?></h1>
		</div>
	</div>
	<div class="container page">
		<?php if ($user->logged_in) : ?>
			<h2>Welcome, <?php echo $user->username; ?>!</h2>
		<?php endif; ?>
		<?php echo $page->body; ?>
	</div>
<?php include('./_foot.php'); // include footer markup ?>
