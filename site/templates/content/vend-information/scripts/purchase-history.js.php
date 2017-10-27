<?php if ($table['detail']['maxrows'] < 2) : ?>
    <script type="text/javascript">
        $(function() {
            $('#purchase-history').DataTable();
        });
    </script>
<?php endif; ?>
