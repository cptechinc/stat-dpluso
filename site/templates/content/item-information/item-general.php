<?php
	$iiusageformatter = $page->screenformatterfactory->generate_screenformatter('ii-usage');
	$iiusageformatter->process_json();
	
	$iinotesformatter = $page->screenformatterfactory->generate_screenformatter('ii-notes');
	$iinotesformatter->process_json();
	
	$iimiscformatter = $page->screenformatterfactory->generate_screenformatter('ii-misc');
	$iimiscformatter->process_json();
	
	if ($config->ajax) {
		echo $page->bootstrap->openandclose('p', '', $page->bootstrap->makeprintlink($config->filename, 'View Printable Version'));
	}
 ?>

<?= $iiusageformatter->generate_iteminfotable(); ?>

<div>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#usage" aria-controls="usage" role="tab" data-toggle="tab">Usage</a></li>
		<li role="presentation"><a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">Notes</a></li>
		<li role="presentation"><a href="#misc" aria-controls="misc" role="tab" data-toggle="tab">Misc</a></li>
	</ul>

	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="usage">
			<br>
			<?= $iiusageformatter->process_andgeneratescreen(); ?>
		</div>
		<div role="tabpanel" class="tab-pane" id="notes">
			<br>
			<?= $iinotesformatter->process_andgeneratescreen(); ?>
		</div>
		<div role="tabpanel" class="tab-pane" id="misc">
			<br>
			<?= $iimiscformatter->process_andgeneratescreen(); ?>
		</div>
	</div>
</div>
<?= $iiusageformatter->generate_javascript(); ?>
