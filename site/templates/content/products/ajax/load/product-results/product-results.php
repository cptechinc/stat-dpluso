<?php
    $addtype = $input->urlSegment(3); // cart|order|quote
    $linenumber = $input->get->text('linenumber');
    $pathtoajax = "load/products/item-search-results/";
    switch($addtype) {
        case 'cart':
            $ordn = '';
            $pathtoajax .= "cart/";
            $formaction = $config->pages->cart."redir/";
            $rediraction = 'add-to-cart';
            $returnpage = $config->pages->cart;
            break;
		case 'order':
            $ordn = $sanitizer->text($input->get->ordn);
            $pathtoajax .= "order/";
            $formaction = $config->pages->orders."redir/";
            $rediraction = 'add-to-order';
            $returnpage = $config->pages->edit."order/?ordn=".$ordn;
            break;
		case 'quote':
            $qnbr = $sanitizer->text($input->get->qnbr);
            $pathtoajax .= "quote/";
            $formaction = $config->pages->quotes."redir/";
            $rediraction = 'add-to-quote';
            $returnpage = $config->pages->edit."quote/?qnbr=".$qnbr;
            break;
    }

    $items = get_item_search_results(session_id(), $config->showonpage,  $input->pageNum(), false);
    $totalcount = get_item_search_results_count(session_id(), false);

    include $config->paths->content."products/ajax/load/product-results/product-result-logic.php";
	include $config->paths->content.'pagination/ajax/pagination-start-no-form.php';

?>
<div>
	<?php if ($config->ajax) : ?>
		<p> <a href="<?php echo $config->filename; ?>" class="h4" target="_blank"><i class="glyphicon glyphicon-print" aria-hidden="true"></i> View Printable Version</a> </p>
	<?php endif; ?>
</div>
<div class="results">
	<?php if ($totalcount > 0) : ?>
		<?php foreach ($items as $item) : ?>
			<?php if (!file_exists($config->imagefiledirectory.$item['image'])) {$item['image'] = 'notavailable.png'; } ?>
			<?php $specs = $pricing = $item; ?>
			<?php
				switch ($addtype) {
					case 'cart':
						include $config->paths->content."products/ajax/load/product-results/product-cart-results.php";
						break;
					case 'order':
						include $config->paths->content."products/ajax/load/product-results/product-order-results.php";
						break;
					case 'quote':
						include $config->paths->content."products/ajax/load/product-results/product-quote-results.php";
						break;
				}
			?>
		<?php endforeach; ?>
	<?php else : ?>
		<h4>No Items found</h4>
	<?php endif; ?>
</div>

<?php $totalpages = ceil($totalcount / $config->showonpage); ?>
<?php include $config->paths->content.'pagination/ajax/pagination-links.php'; ?>
