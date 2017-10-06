		<br>
        <div class="container hidden-print">
            <div class="row">
                <div class="col-xs-12">
                    <a id="back-to-top" href="#" class="btn btn-success back-to-top" role="button"><span class="glyphicon glyphicon-chevron-up"></span></a>
                </div>
            </div>
        </div>
        <footer class="hidden-print">
            <div class="container">
                <p> Web Development by CPTech © 2015 --------- <?php echo session_id(); ?> --- </p>
                <p class="visible-xs-inline-block"> XS </p> <p class="visible-sm-inline-block"> SM </p>
                <p class="visible-md-inline-block"> MD </p> <p class="visible-lg-inline-block"> LG </p>
            </div>
        </footer>
		<?php include $config->paths->content."common/modals/ajax-modal.php"; ?>
		<?php include $config->paths->content."common/modals/lightbox-modal.php"; ?>
		<?php include $config->paths->content."common/modals/add-item-modal.php"; ?>
        <?php foreach($config->scripts->unique() as $script) : ?>
        	<script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
        <?php include $config->paths->content."common/phpjs/add-to-cart-msg.php"; ?>
		<?php include $config->paths->content."common/phpjs/new-shopping-customer-msg.php"; ?>
    </body>
</html>
