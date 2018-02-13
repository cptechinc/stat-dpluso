<?php
	$vendorID = '';
	$itemlink = $config->pages->products."redir/?action=vi-costing";
	if ($input->get->vendorID) { $vendorID = $input->get->text('vendorID'); }
	if (!empty($vendorID)) { $itemlink .= "&vendorID=".urlencode($vendorID); }
	if ($input->get->q) {
		$q = $input->get->text('q');
		$items = search_items($q, $custID, $session->display, $input->pageNum); 
		$resultscount = count_searchitems($q, $vendorID);
	}
?>

<div class="list-group" id="item-results">
	<?php if ($input->get->q) : ?>
		<?php if ($resultscount) : ?>
			<?php foreach ($items as $item) : ?>
				<?php
					if (!file_exists($config->imagefiledirectory.$item['image'])) {$item['image'] = 'notavailable.png'; }
					switch($input->get->text('action')) {
						case 'vi-costing':
							$onclick = "choosevicostingitem('".$item['itemid']."')";
							break;
						default:
							$onclick = "choosevicostingitem('".$item['itemid']."')";
							break;
					}
				?>
				<a href="#<?= $item['itemid']; ?>" class="list-group-item item-master-result" onclick="<?= $onclick; ?>">
					<div class="row">
						<div class="col-xs-2"><img src="<?php echo $config->imagedirectory.$item['image']; ?>" alt=""></div>
						<div class="col-xs-10"><h4 class="list-group-item-heading"><?php echo $item['itemid']; ?></h4>
						<p class="list-group-item-text"><?php echo $item['desc1']; ?></p></div>
					</div>
				</a>
			<?php endforeach; ?>
			
		<?php else : ?>
			<a href="#" class="list-group-item item-master-result">
				<div class="row">
					<div class="col-xs-2"></div>
					<div class="col-xs-10"><h4 class="list-group-item-heading">No Items Match your query.</h4>
					<p class="list-group-item-text"></p></div>
				</div>
			</a>
		<?php endif; ?>
	<?php endif; ?>
</div>
