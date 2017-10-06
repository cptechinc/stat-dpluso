<?php if ($session->{'new-shopping-customer'} ) : ?>
	<script>
        $(function() {
            $.notify({
                icon: "glyphicon glyphicon-shopping-cart",
                message: "You are now shopping for <?php echo $session->{'new-shopping-customer'}; ?>" ,
                target: '_self'
            },{
                type: "success",
                url_target: '_self'
            });
        });
    </script>
    <?php $session->remove('new-shopping-customer'); ?>
<?php endif; ?>
