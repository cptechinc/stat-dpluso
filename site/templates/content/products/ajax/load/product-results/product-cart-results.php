<div class="item-result">
	<div class="row">
		<div class="col-md-2 col-sm-3 print-col-sm-2">
			<a href="#" data-toggle="modal" data-target="#lightbox-modal">
				<img src="<?= $config->imagedirectory.$item['image']; ?>" data-desc="<?php echo $item['itemid'].' image'; ?>">
			</a>
		</div>
		<div class="col-md-7 col-sm-6 print-col-sm-10">
			<h4><a href="<?= $config->pages->products.'redir/?action=ii-select&itemID='.urlencode($item['itemid']); ?>" target="_blank"><?= $item['itemid']; ?></a></h4>
			<h5><?= $item['name1']; ?></h5>
			<div class="product-info">
				<ul class="nav nav-tabs nav_tabs hidden-print">
					<li class="active"><a href="#<?= cleanforjs($item['itemid']); ?>-desc-tab" data-toggle="tab" aria-expanded="true">Description</a></li>
					<li><a href="#<?= cleanforjs($item['itemid']); ?>-specs-tab" data-toggle="tab" aria-expanded="false">Specifications</a></li>
					<li><a href="#<?= cleanforjs($item['itemid']); ?>-pricing-tab" data-toggle="tab" aria-expanded="false">Price Breaks</a></li>
					<li><a href="#<?= cleanforjs($item['itemid']); ?>-stock-tab" data-toggle="tab" aria-expanded="false">Item Stock</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade active in" id="<?= cleanforjs($item['itemid']); ?>-desc-tab">
						<br><p><?= $item['shortdesc']; ?></p>

					</div>
					<div class="tab-pane fade" id="<?= cleanforjs($item['itemid']); ?>-specs-tab"><br><?php include $config->paths->content."products/product-results/product-features.php"; ?></div>
					<div class="tab-pane fade" id="<?= cleanforjs($item['itemid']); ?>-pricing-tab"><br><?php include $config->paths->content."products/product-results/price-structure.php"; ?></div>
					<div class="tab-pane fade" id="<?= cleanforjs($item['itemid']); ?>-stock-tab"><br><?php include $config->paths->content."products/product-results/stock-table.php"; ?></div>
				</div>
				<?php if (!empty($item['lastqty'])) : ?>
					<table class="table">
						<tr>
							<td>Last Sold: <?= DplusDateTime::formatdate($item['lastsold']); ?></td>
							<td>Price: $<?= $item['lastprice']; ?></td>
							<td>Qty Bought: <?= $item['lastqty']; ?></td>
						</tr>
					</table>
				<?php endif; ?>
			</div>
		</div>
		<div class="col-md-3 col-sm-3 hidden-print">
			<form action="<?= $addtoform->action; ?>" method="post" id="<?= cleanforjs($item['itemid'])."-form"; ?>">
				<input type="hidden" name="action" value="<?= $addtoform->rediraction; ?>">
				<input type="hidden" name="page" value="<?= $addtoform->returnpage; ?>">
				<input type="hidden" name="itemID" value="<?= $item['itemid']; ?>">
				<input type="hidden" name="whse" class="<?= cleanforjs($item['itemid'])."-whse"; ?>" value="">
				<?php if ($custID != '') : ?>
					<input type="hidden" name="custID" value="<?= $custID; ?>">
					<input type="hidden" name="shipID" value="<?= $shipID; ?>">
				<?php endif; ?>
				<input type="hidden" name="linenumber" value="<?= $linenumber; ?>">

				<table class="table table-condensed no-bottom ">
					<tr> <td>UoM</td> <td><?= $item['unit']; ?></td> </tr>
					<?php if ($soconfig['config']['show_listprice'] == 'Y') : ?>
						<tr> <td>List Price</td> <td class="text-right">$ <?= $item['listprice']; ?></td> </tr>
					<?php endif; ?>
					<tr> <td>Price</td> <td class="text-right">$ <?= $item['price'];?></td> </tr>
					<tr> <td>In Stock</td> <td class="text-right"><?= $item['qty']; ?></td> </tr>
					<tr class="item-whse-row"><td>Whse:</td> <td class="item-whse-val"></td></tr>
					<tr> <td>Qty</td> <td class="text-right"><input type="text" class="form-control input-sm text-right qty" name="qty"></td> </tr>
					<tr>
						<td colspan="2" class="text-center">
							<button type="submit" class="btn btn-primary btn-sm">
								<i class="material-icons">&#xE854;</i>Add to <?= ucfirst($addtype); ?>
							</button>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
