<?php
	$orderpanel = new SalesOrderPanel('rep', $page->fullURL, '#ajax-modal', '#orders-panel', $config->ajax, session_id());
	$orderpanel->setup_reppanel();
	$orderpanel->pagenbr = $input->pageNum;
	$orderpanel->active = !empty($input->get->ordn) ? $input->get->text('ordn') : false;
	$orderpanel->get_ordercount();

	$insertafter = 'salesrep';
	$paginator = new Paginator($orderpanel->pagenbr, $orderpanel->count, $orderpanel->pageurl->getUrl(), $insertafter, $orderpanel->ajaxdata);
	
?>
<div class="panel panel-primary not-round" id="orders-panel">
    <div class="panel-heading not-round" id="order-panel-heading">
    	<?php if (isset($_SESSION['order-search'])) : ?>
        	<a href="#orders-div" data-parent="#orders-panel" data-toggle="collapse">
				Searching for <?php echo $_SESSION['order-search']; ?> <span class="caret"></span> <span class="badge"><?php echo $orderpanel->count; ?></span>
            </a>
    	<?php elseif ($orderpanel->count > 0) : ?>
            <a href="#orders-div" data-parent="#orders-panel" data-toggle="collapse">Your Orders <span class="caret"></span></a> &nbsp; <span class="badge"> <?php echo $orderpanel->count; ?></span> &nbsp; | &nbsp;
            <?php echo $orderpanel->generate_refreshorderslink(); ?>
        <?php else : ?>
        	<?php echo $orderpanel->generate_loadorderslink(); ?>
        <?php endif; ?>
		&nbsp; &nbsp;
		<?php
			if (isset($_SESSION['orders-loaded-for'])) {
				if ($_SESSION['orders-loaded-for'] == $user->loginid) {
					echo 'Last Updated : ' . $_SESSION['orders-updated'];
				}
			}
		?>
        <span class="pull-right"><?php if ($input->pageNum > 1 ) {echo 'Page '.$input->pageNum;} ?></span>
    </div>
    <div id="orders-div" class="<?php echo $orderpanel->collapse; ?>">
        <div class="panel-body">
        	<div class="row">
                <div class="col-sm-6">
                    <?= $paginator->generate_showonpage(); ?>
                </div>
                <div class="col-sm-4">
					<?php if (100 == 1) : // TODO add salesrep order search ?>
						<?php echo $orderpanel->generate_ordersearchlink(); ?>
	                    &nbsp; &nbsp; &nbsp;
	                    <?php if (isset($_SESSION['order-search'])) : ?>
	                    	<?php echo $orderpanel->generate_clearsearchlink(); ?>
	                    <?php endif; ?>
					<?php endif; ?>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <?php include $config->paths->content.'salesrep/orders/orders-table.php'; ?>
			<?php echo $paginator; ?>
        </div>
    </div>
</div>
