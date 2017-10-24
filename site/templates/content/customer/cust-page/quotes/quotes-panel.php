<?php
	$quotepanel = new CustomerQuotePanel(session_id(), $page->fullURL, '#ajax-modal', "#quotes-panel", $config->ajax);
	$quotepanel->set_customer($custID, $shipID);
	$quotepanel->pagenbr = $input->pageNum;
	$quotepanel->activeID = !empty($input->get->qnbr) ? $input->get->text('qnbr') : false;
	$quotepanel->get_quotecount();
	
	//SETUP AJAX
	$ajax = new stdClass();
	$ajax->loadinto = "#quotes-panel"; //WHERE TO LOAD AJAX LOADED DATA
	$ajax->focus = "#quotes-panel"; //WHERE TO FOCUS AFTER LOADED DATA IS LOADED
	$ajax->searchlink = $config->pages->ajax.'load/quote-search/cust/'.$custID."/";  //LINK TO THE SEARCH PAGE FOR THIS OBJECT
	$ajax->data = 'data-loadinto="'.$ajax->loadinto.'" data-focus="'.$ajax->focus.'"'; //DATA FIELDS FOR JAVASCRIPT

	$ajax->url = $page->fullURL;
	$ajax->url->path = $config->pages->ajax.'load/quote-search/cust/'.$custID."/";
	$ajax->url->query->setData(array("display" => false, "ajax" => false));

	$ajax->path = $ajax->url->path; //MODAL TO LOAD INTO IF NEED BE
	$ajax->querystring = $ajax->url->query;//BASE QUERYSTRING NEEDED FOR AJAX
	$ajax->link = $ajax->url; //LINK TO THE AJAX FILE
	
	$paginator = new Paginator($quotepanel->pagenbr, $quotepanel->count, $quotepanel->pageurl->getUrl(), $quotepanel->paginationinsertafter, $quotepanel->ajaxdata);
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
