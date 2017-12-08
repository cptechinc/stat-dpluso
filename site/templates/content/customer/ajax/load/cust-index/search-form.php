<form action="<?php echo $config->pages->ajax."load/customers/cust-index/"; ?>" method="POST" id="cust-index-search-form">
    <div class="form-group">
        <?php if ($input->get->function) : ?>
        	<input type="hidden" name="function" class="function" value="<?= $input->get->function; ?>">
        <?php endif; ?>
        <input type="text" class="form-control cust-index-search" name="q" placeholder="Type customer phone, name, ID, contact">
    </div>
    <div>
        <?php
            if ($input->get->q) {
                switch ($dplusfunction) {
                    case 'ci':
                        include $config->paths->content."customer/ajax/load/cust-index/ci-cust-list.php";
                        break;
                    case 'ii':
                        include $config->paths->content."customer/ajax/load/cust-index/ii-cust-list.php";
                        break;
                }
            } else {
                include $config->paths->content."customer/ajax/load/cust-index/cust-search-table.php";
            }
        ?>
    </div>
</form>
