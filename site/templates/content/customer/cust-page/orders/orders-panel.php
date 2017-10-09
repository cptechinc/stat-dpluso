<?php
	$orderpanel = new SalesOrderPanel('cust', $page->fullURL, '#ajax-modal', '#orders-panel', $config->ajax, session_id());
	$orderpanel->setup_customerpanel($custID, $shipID);
	$orderpanel->pagenbr = $input->pageNum;
	$orderpanel->active = !empty($input->get->ordn) ? $input->get->text('ordn') : false;
	$orderpanel->get_ordercount();
	
	if (!empty($shipID)) {
		$insertafter = 'shipto-'.$shipID;
	} else {
		$insertafter = $custID;
	}
	$paginator = new Paginator($orderpanel->pagenbr, $orderpanel->count, $orderpanel->pageurl->getUrl(), $insertafter, $orderpanel->ajaxdata);
?>
<div class="panel panel-primary not-round" id="orders-panel">
    <div class="panel-heading" id="orders-panel-heading">
    	<?php if ($session->ordersearch) : ?>
        	<a href="#orders-div" data-parent="#orders-panel" data-toggle="collapse">
				Searching for <?= $session->ordersearch; ?> <span class="caret"></span> <span class="badge"><?= $orderpanel->count; ?></span>
            </a>  |
			<?= $orderpanel->generate_refreshorderslink(); ?>
    	<?php elseif ($orderpanel->count > 0) : ?>
            <a href="#orders-div" data-parent="#orders-panel" data-toggle="collapse">Customer Orders <span class="caret"></span></a> <span class="badge"> <?= $orderpanel->count; ?></span> &nbsp; | &nbsp;
            <?= $orderpanel->generate_refreshorderslink(); ?>
		<?php elseif (isset($input->get->ordn)) : ?>
			<a href="#orders-div" data-parent="#orders-panel" data-toggle="collapse">Customer Orders <span class="caret"></span></a> <span class="badge"> <?= $orderpanel->count; ?></span> &nbsp; | &nbsp;
			<?= $orderpanel->generate_refreshorderslink(); ?>
        <?php else : ?>
        	<?= $orderpanel->generate_loadorderslink(); ?>
        <?php endif; ?>
		&nbsp; &nbsp;
		<?php
			if ($session->{'orders-loaded-for'}) {
				if ($session->{'orders-loaded-for'} == $custID) {
					echo 'Last Updated : ' . $session->{'orders-updated'};
				}
			}
		?>
        <span class="pull-right"><?php if ($input->pageNum > 1 ) {echo 'Page '.$input->pageNum;} ?></span>
    </div>
    <div id="orders-div" class="<?= $orderpanel->collapse; ?>">
        <div class="panel-body">
        	<div class="row">
                <div class="col-sm-6">
					<?= $paginator->generate_showonpage(); ?>
                </div>
                <div class="col-sm-4">
                	<?= $orderpanel->generate_ordersearchlink(); ?>
                    &nbsp; &nbsp; &nbsp;
                    <?php if ($session->ordersearch) : ?>
						<?= $orderpanel->generate_clearsearchlink(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <?php include $config->paths->content.'customer/cust-page/orders/orders-table.php'; ?>
			<?= $paginator; ?>
        </div>
    </div>
</div>
