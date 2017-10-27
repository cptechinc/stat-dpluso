<?php if ($table['detail']['maxrows'] < 2) : ?>
    <script type="text/javascript">
        $(function() {
            $('#payments').DataTable();
        });
    </script>
<?php endif; ?>
