<?php
	$quotepanel = new RepQuotePanel(session_id(), $page->fullURL, '#ajax-modal', "#quotes-panel", $config->ajax);
	$quotepanel->pagenbr = $input->pageNum;
	$quotepanel->activeID = !empty($input->get->qnbr) ? $input->get->text('qnbr') : false;
	$quotepanel->generate_filter($input);
	$quotepanel->get_quotecount();

	$paginator = new Paginator($quotepanel->pagenbr, $quotepanel->count, $quotepanel->pageurl->getUrl(), $quotepanel->paginationinsertafter, $quotepanel->ajaxdata);
?>
<div class="panel panel-primary not-round" id="quotes-panel">
	<div class="panel-heading not-round" id="quotes-panel-heading">
		<?php if ($input->get->filter) : ?>
			<a href="#quotes-div" data-parent="#quotes-panel" data-toggle="collapse">
				<?= $quotepanel->generate_filterdescription(); ?> <span class="caret"></span> <span class="badge"><?= $quotepanel->count; ?></span> &nbsp; | &nbsp;
			</a>
			<?= $quotepanel->generate_refreshlink(); ?>
		<?php elseif ($quotepanel->count > 0) : ?>
			<a href="#quotes-div" data-parent="#quotes-panel" data-toggle="collapse">Your Quotes <span class="caret"></span></a> &nbsp; <span class="badge"><?= $quotepanel->count; ?></span> &nbsp; | &nbsp;
			<?= $quotepanel->generate_refreshlink(); ?>
		<?php else : ?>
			<?= $quotepanel->generate_loadlink(); ?>
		<?php endif; ?>
		&nbsp; &nbsp;
		<?= $quotepanel->generate_lastloadeddescription(); ?>
		<span class="pull-right"><?= $quotepanel->generate_pagenumberdescription(); ?></span>
	</div>
	<div id="quotes-div" class="<?= $quotepanel->collapse; ?>">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-6">
					<?= $paginator->generate_showonpage(); ?>
				</div>
				<div class="col-sm-6">
					<button class="btn btn-primary toggle-order-search pull-right" type="button" data-toggle="collapse" data-target="#quotes-search-div" aria-expanded="false" aria-controls="orders-search-div">Toggle Search <i class="fa fa-search" aria-hidden="true"></i></button>
				</div>
			</div>
			<div id="quotes-search-div" class="<?= (empty($quotepanel->filters)) ? 'collapse' : ''; ?>">
				<?php include $config->paths->content.'dashboard/quotes/search-form.php'; ?>
			</div>
		</div>
		<div class="table-responsive">
			<?php
				if ($modules->isInstalled('QtyPerCase')) {
					include $config->paths->siteModules.'QtyPerCase/content/dashboard/quotes/table.php';
				} else {
					include $config->paths->content.'dashboard/quotes/table.php';
				}
				echo $paginator;
			?>
		</div>
	</div>
</div>
