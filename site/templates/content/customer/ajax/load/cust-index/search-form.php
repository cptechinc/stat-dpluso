<form action="<?php echo $config->pages->ajax."load/customers/cust-index/"; ?>" method="POST" id="cust-index-search-form">
    <div class="form-group">
        <?php if ($input->get->function) : ?>
        	<input type="hidden" name="function" class="function" value="<?= $input->get->function; ?>">
        <?php endif; ?>
        <div class="input-group">
            <input type="text" class="form-control cust-index-search" name="q" placeholder="Type customer phone, name, ID, contact">
            <span class="input-group-btn">
            	<button type="submit" class="btn btn-default not-round"> <span class="glyphicon glyphicon-search" aria-hidden="true"></span> <span class="sr-only">Search</span> </button>
            </span>
        </div>
    </div>
    <div>
        <?php
            if (!empty($input->get->q) || !empty($input->get->function)) {
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
