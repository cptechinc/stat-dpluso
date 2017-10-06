<?php include('./_head.php'); ?>
	<div class="jumbotron pagetitle">
		<div class="container">
			<h1><?php echo $page->get('pagetitle|headline|title') ; ?></h1>
		</div>
	</div>
	<div class="container page">
		<?php if ($input->urlSegment1) : ?>

			<?php include $include; ?>

		<?php else : ?>
			<div class="row">
				<div class="col-sm-3">
					<div class="list-group">
				  		<?php foreach ($formatters as $label => $link) : ?>
				  			<a href="<?php echo $link.'/'; ?>" class="list-group-item"><?php echo $label; ?></a>
				  		<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<?php $setequalheights = array('.featured-item .panel-body', '.featured-item .panel-header'); ?>
<?php include('./_foot.php'); // include footer markup ?>
