<?php
    if (checkconfigifexists($user, $configtype, false)) {
        $iiconfig = json_decode(getconfiguration($user->loginid, $configtype, false), true);
    } else {
        $iiconfig = json_decode(file_get_contents($config->paths->content."salesrep/configs/defaults/item-info-options.json"), true);
    }
?>
<div class="row">
    <div class="col-sm-3">
        <div class="list-group">
            <?php foreach ($configurations as $label => $link) : ?>
                <?php if ($link == $input->urlSegment1) : ?>
                    <a href="#" class="list-group-item active"><?php echo $label; ?></a>
                <?php else : ?>
                    <a href="<?php echo $config->pages->userconfigs.$link.'/'; ?>" class="list-group-item"><?php echo $label; ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-sm-9">
        <table class="table table-bordered"></table>
    </div>
</div>
