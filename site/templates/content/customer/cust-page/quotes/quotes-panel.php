<?php
	$quotepanel = new CustomerQuotePanel(session_id(), $page->fullURL, '#ajax-modal', "#quotes-panel", $config->ajax);
	$quotepanel->set_customer($custID, $shipID);
	$quotepanel->pagenbr = $input->pageNum;
	$quotepanel->activeID = !empty($input->get->qnbr) ? $input->get->text('qnbr') : false;
	$quotepanel->get_quotecount();
	
	$paginator = new Paginator($quotepanel->pagenbr, $quotepanel->count, $quotepanel->pageurl->getUrl(), $quotepanel->paginationinsertafter, $quotepanel->ajaxdata);
	
	// echo $quotepanel->get_quotes(true);
?>
<div class="panel panel-primary not-round" id="quotes-panel">
    <div class="panel-heading not-round" id="quotes-panel-heading">
    	<?php if ($session->{'quote-search'}) : ?>
        	<a href="#quotes-div" data-parent="#quotes-panel" data-toggle="collapse">
				Searching for <?= $session->{'quote-search'}; ?> <span class="caret"></span> <span class="badge"><?= $quotepanel->count; ?></span>
            </a>
    	<?php elseif ($quotepanel->count > 0) : ?>
            <a href="#quotes-div" data-parent="#quotes-panel" data-toggle="collapse">Customer Quotes <span class="caret"></span></a> <span class="badge"><?= $quotepanel->count; ?></span> &nbsp; | &nbsp;
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
                <div class="col-sm-4">
					<?php if (100 == 1) : // TODO Add quotesearch link ?>
						<?= $quotepanel->generate_searchlink(); ?>
	                    <?php if ($session->quotessearch) : ?>
		                    <?= $quotepanel->generate_clearsearchlink(); ?>
	                    <?php endif; ?>
					<?php endif; ?>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <?php include $config->paths->content.'customer/cust-page/quotes/quotes-table.php'; ?>
            <?= $paginator; ?>
        </div>
    </div>
</div>
