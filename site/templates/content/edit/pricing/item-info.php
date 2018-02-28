<?php $item = getiteminfo(session_id(), $linedetail->itemid, false); $specs = $pricing = $item; ?>

<div class="row edit-pricing">
    <div class="col-md-3">
        <?php if (in_array($linedetail->itemid, $config->nonstockitems)) : ?>
            <img src="<?= $config->urls->files."images/$config->imagenotfound"; ?>" alt="">
        <?php else : ?>
            <img src="<?= $config->imagedirectory.$item['image']; ?>" alt="">
        <?php endif; ?>
    </div>
    <div class="col-md-9">
        <?php if (in_array($linedetail->itemid, $config->nonstockitems)) : ?>
            <h4><?= $linedetail->itemid; ?></h4> <h5><?= $linedetail->desc1; ?></h5>
        <?php else : ?>
            <h4><?= $item['itemid']; ?></h4> <h5><?= $item['name1']; ?></h5>
            <div class="product-info">
                <ul class="nav nav-tabs nav_tabs hidden-print">
                    <li class="active"><a href="#<?= cleanforjs($item['itemid']); ?>-desc-tab" data-toggle="tab" aria-expanded="true">Description</a></li>
                    <li><a href="#<?= cleanforjs($item['itemid']); ?>-specs-tab" data-toggle="tab" aria-expanded="false">Specifications</a></li>
                    <li><a href="#<?= cleanforjs($item['itemid']); ?>-pricing-tab" data-toggle="tab" aria-expanded="false">Pricings</a></li>
                    <?php if ($linedetail->kititemflag == 'Y') : ?>
                        <li><a href="#<?= cleanforjs($item['itemid']); ?>-components-tab" data-toggle="tab" aria-expanded="false">Kit Components</a></li>
                    <?php endif; ?>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="<?= $item['itemid']; ?>-desc-tab">
                        <br><p><?= $item['shortdesc']; ?></p>
                    </div>
                    <div class="tab-pane fade" id="<?= cleanforjs($item['itemid']); ?>-specs-tab"><br><?php include $config->paths->content."products/product-results/product-features.php"; ?></div>
                    <div class="tab-pane fade" id="<?= cleanforjs($item['itemid']); ?>-pricing-tab"><br><?php include $config->paths->content."products/product-results/price-structure.php"; ?></div>
                    <div class="tab-pane fade" id="<?= cleanforjs($item['itemid']); ?>-components-tab"><br><?php include $config->paths->content."products/product-results/kit-components.php"; ?></div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
