<?php $order = get_orderhead(session_id(), $ordn, false); ?>
<div id="sales-order-details ">
	<div class="form-group"><?php include $config->paths->content.'edit/orders/order-details/order-details.php'; ?></div>
	<div class="text-center form-group">
		<?php $resultsurl = $config->pages->ajax.'load/products/item-search-results/order/?ordn='.$ordn.'&custID='.urlencode($order['custid']).'&shipID='.urlencode($order['shiptoid']); ?>
		<button class="btn btn-primary" data-toggle="modal" data-target="#add-item-modal" data-addtype="order" data-resultsurl="<?= $resultsurl; ?>">
			<span class="glyphicon glyphicon-plus"></span> Add Item
		</button>
	</div>
	<div class="row">
		<div class="col-xs-3 col-sm-7"></div>
	    <div class="col-xs-9 col-sm-5">
	    	<table class="table-condensed table table-striped numeric">
	        	<tr>
	        		<td>Subtotal</td>
	        		<td class="text-right">$ <?php echo formatmoney($order['odrsubtot']); ?></td>
	        	</tr>
	        	<tr>
	        		<td>Tax</td>
	        		<td class="text-right">$ <?php echo formatmoney($order['odrtax']); ?></td>
	        	</tr>
	        	<tr>
	        		<td>Freight</td>
	        		<td class="text-right">$ <?php echo formatmoney($order['odrfrt']); ?></td>
	        	</tr>
	        	<tr>
	        		<td>Misc.</td>
	        		<td class="text-right">$ <?php echo formatmoney($order['odrmis']); ?></td>
	        	</tr>
	        	<tr>
	        		<td>Total</td>
	        		<td class="text-right">$ <?php echo formatmoney($order['odrtotal']); ?></td>
	        	</tr>
	        </table>
	    </div>
	</div>
</div>
