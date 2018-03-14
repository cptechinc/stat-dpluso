<form action="<?php echo $config->pages->orders."redir/"; ?>" method="post" id="order-filter-form" data-loadinto="#orders-panel" data-focus="#orders-panel" data-modal="#ajax-modal" class="fuelux">
    <input type="hidden" name="custID" value="<?php echo $custID; ?>">  
    <input type="hidden" name="action" value="search-cust-orders">
    <input type="hidden" name="shipID" value="<?php echo $shipID; ?>">
    
    <div class="row">
        <div class="form-group col-sm-12">
            <h4>Order Status :</h4>
            <div class="row">
                <div class="col-sm-3">
                    <input type="checkbox" name="orderstatus" value="New">
                    <label for="">&ensp;New</label>
                </div>
                <div class="col-sm-3">
                    <input type="checkbox" name="orderstatus" value="Invoice">
                    <label for="">&ensp;Invoice</label>
                </div>
                <div class="col-sm-3">
                    <input type="checkbox" name="orderstatus" value="Pick">
                    <label for="">&ensp;Pick</label>
                </div>
                <div class="col-sm-3">
                    <input type="checkbox" name="orderstatus" value="Verify">
                    <label for="">&ensp;Verify</label>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="row">
        <div class="form-group col-sm-12">
            <h4>Cust PO :</h4>
            <input class="form-control inline input-sm" type="text" name="q" value="" placeholder="Cust PO">
        </div>
    </div>
    
    
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12">
            <h4>Cust ID :</h4>
            <div class="row">
                <div class="col-xs-12 col-sm-6 form-group">
                    <input class="form-control inline input-sm" type="text" name="custid-low" value="" placeholder="From CustID">
                </div>
                <div class="col-xs-12 col-sm-6 form-group">
                    <input class="form-control inline input-sm" type="text" name="custid-high" value="" placeholder="Through CustID">
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12">
            <h4>Order # :</h4>
            <div class="row">
                <div class="col-xs-12 col-sm-6 form-group">
                    <input class="form-control inline input-sm" type="text" name="orderno-low" value="" placeholder="From Order #">
                </div>
                <div class="col-xs-12 col-sm-6 form-group">
                    <input class="form-control inline input-sm" type="text" name="orderno-high" value="" placeholder="Through Order #">
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="form-group col-xs-12 col-sm-12">
            <h4>Order Date :</h4>
            <div class="row">
                <div class="col-xs-12 col-sm-6 form-group">
                    <label class="control-label">From Date </label>
                    <?php $name = 'date-from'; $value=""; ?>
                    <?php include $config->paths->content."common/date-picker.php"; ?>
                </div>
                <div class="col-xs-12 col-sm-6 form-group">
                    <label class="control-label">Through Date </label>
                    <?php $name = 'date-through'; $value=""; ?>
                    <?php include $config->paths->content."common/date-picker.php"; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <button class="btn btn-success btn-block" type="submit">Search</button>
    </div>
</form>
