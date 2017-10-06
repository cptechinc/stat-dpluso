<div class="row">
    <div class="col-sm-3">
        <div class="list-group">
            <?php foreach ($configurations as $label => $link) : ?>
                <a href="<?php echo $config->pages->userconfigs.$link.'/'; ?>" class="list-group-item"><?php echo $label; ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-sm-9"></div>
</div>
