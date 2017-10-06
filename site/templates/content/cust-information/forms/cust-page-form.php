<form action="<?php echo $config->pages->ajax."load/customers/cust-index/"; ?>" method="POST" id="ci-cust-lookup">
    <input type="hidden" name="action" value="ci-item-lookup">
    <input type="hidden" name="shipID" class="shipID" value="<?php echo $shipID; ?>">
    <input type="hidden" name="nextshipID" class="nextshipID" value="<?php echo $nextshipid; ?>">
    <input type="hidden" name="shiptocount" class="shiptocount" value="<?php echo $custjson['shiptocount']; ?>">
    <div class="form-group">
        <div class="input-group custom-search-form">
            <input type="text" class="form-control input-sm not-round custID" name="custID" placeholder="Search custID" value="<?php echo $custID;  ?>">
            <span class="input-group-btn">
            	<button type="submit" class="btn btn-sm btn-default not-round"> <span class="glyphicon glyphicon-search"></span> </button>
            </span>
        </div>
    </div>
</form>
