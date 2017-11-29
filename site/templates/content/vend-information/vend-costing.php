<div class="col-md-12">
	<?php if ($config->ajax) : ?>
		<?php echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version')); ?>
	<?php endif; ?>
		<ul class="nav nav-tabs nav_tabs">
			<li class="active"><a href="#vendor" data-toggle="tab" aria-expanded="true">Vendor Costs</a></li>
			<li><a href="#subs" data-toggle="tab" aria-expanded="false">Substitutions</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="vendor">
				<br><?php include $config->paths->content."vend-information/vend-cost-sub.php"; ?></p>
			</div>
			<div class="tab-pane fade" id="subs"><br><?php include $config->paths->content."vend-information/vend-sub.php"; ?></div>
		</div>
</div>
