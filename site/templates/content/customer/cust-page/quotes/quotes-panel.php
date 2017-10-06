<?php
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

	if ($shipID != '') {
		$ajax->url->path->add('shipto-'.$shipID);
		$ajax->insertafter = 'shipto-'.$shipID;
		$ajax->searchlink .=	"shipto-".$shipID."/";
	} else {
		$ajax->insertafter = $custID;
	}

    if ($config->ajax) {$collapse = '';} else {$collapse = 'collapse'; }


	//include $config->paths->content.'recent-orders/setup.php';
	$quotecount =  get_cust_quote_count(session_id(), $custID, false);
	//$quotecount = 1;
	$totalcount = $quotecount;

?>
<div class="panel panel-primary not-round" id="quotes-panel">
    <div class="panel-heading not-round" id="quotes-panel-heading">
    	<?php if (isset($_SESSION['quote-search'])) : ?>
        	<a href="#quotes-div" data-parent="#quotes-panel" data-toggle="collapse">
				Searching for <?php echo $_SESSION['quote-search']; ?> <span class="caret"></span> <span class="badge"><?php echo $quotecount; ?></span>
            </a>
    	<?php elseif ($quotecount > 0) : ?>
            <a href="#quotes-div" data-parent="#quotes-panel" data-toggle="collapse">Customer Quotes <span class="caret"></span></a> <span class="badge"><?php echo $quotecount; ?></span> &nbsp; | &nbsp;
            <a href="<?php echo $config->pages->quotes."redir/?action=load-cust-quotes&custID=".$custID;?>" class="generate-load-link" id="load-cust-quotes" data-custid="<?php echo $custID; ?>" <?php echo $ajax->data; ?>>
                <i class="fa fa-refresh" aria-hidden="true"></i> Refresh Quotes
            </a>
        <?php else : ?>
        	<a href="<?php echo $config->pages->quotes."redir/?action=load-cust-quotes&custID=".$custID;?>" class="generate-load-link" id="load-cust-quotes" data-custid="<?php echo $custID; ?>" <?php echo $ajax->data; ?>>
                Customer Quotes
            </a>
        <?php endif; ?>
		&nbsp; &nbsp;
		<?php
			if (isset($_SESSION['quotes-loaded-for'])) {
				if ($_SESSION['quotes-loaded-for'] == $custID) {
					echo 'Last Updated : ' . $_SESSION['quotes-updated'];
				}
			}
		?>
        <span class="pull-right"><?php if ($input->pageNum > 1 ) {echo 'Page '.$input->pageNum;} ?></span>
    </div>
    <div id="quotes-div" class="<?php echo $collapse; ?>">
        <div class="panel-body">
        	<div class="row">
                <div class="col-sm-6">
                    <?php include $config->paths->content.'pagination/ajax/pagination-start.php'; ?>
                </div>
                <div class="col-sm-4">
                	<a href="<?php echo $ajax->searchlink; ?>" class="btn btn-default bordered search-orders" data-modal="#order-search-modal">Search Quotes</a>
                    &nbsp; &nbsp; &nbsp;
                    <?php if (isset($_SESSION['quote-search'])) : ?>
                    <a href="<?php echo $config->pages->quotes."redir/?action=load-cust-quotes&custID=".$custID;?>" class="btn-warning btn" id="load-cust-quotes" data-custid="<?php echo $custID; ?>" <?php echo $ajax->data; ?>>
                        Clear Search
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <?php include $config->paths->content.'customer/cust-page/quotes/quotes-table.php'; ?>
            <?php $totalpages = ceil($totalcount / $config->showonpage); ?>
            <?php include $config->paths->content.'pagination/ajax/pagination-links.php'; ?>
        </div>
    </div>
</div>
