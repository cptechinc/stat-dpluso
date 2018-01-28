<?php if ($appconfig->child('name=dplus')->has_crm) : ?>
    <div class="row">
        <div class="col-sm-12">
            <?php include $config->paths->content.'dashboard/actions/actions-panel.php'; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?php include $config->paths->content.'dashboard/sales-panel.php'; ?>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-sm-12">
        <?php include $config->paths->content.'salesrep/orders/orders-panel.php'; ?>
    </div>
</div>
