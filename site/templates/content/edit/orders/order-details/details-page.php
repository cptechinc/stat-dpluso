<div id="sales-order-details ">
	<div class="form-group"><?php include $config->paths->content.'edit/orders/order-details/order-details.php'; ?></div>
	<div class="text-center form-group">
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#item-lookup-modal">
		    <span class="glyphicon glyphicon-plus"></span> Add Item
		</button>
	</div>
	<div class="row">
		<div class="col-xs-3 col-sm-7"></div>
	    <div class="col-xs-9 col-sm-5">
	    	<table class="table-condensed table table-striped numeric">
	        	<tr>
	        		<td>Subtotal</td>
	        		<td class="text-right">$ <?= formatmoney($order->subtotal); ?></td>
	        	</tr>
	        	<tr>
	        		<td>Tax</td>
	        		<td class="text-right">$ <?= formatmoney($order->salestax); ?></td>
	        	</tr>
	        	<tr>
	        		<td>Freight</td>
	        		<td class="text-right">$ <?= formatmoney($order->freight); ?></td>
	        	</tr>
	        	<tr>
	        		<td>Misc.</td>
	        		<td class="text-right">$ <?= formatmoney($order->misccost); ?></td>
	        	</tr>
	        	<tr>
	        		<td>Total</td>
	        		<td class="text-right">$ <?= formatmoney($order->ordertotal); ?></td>
	        	</tr>
	        </table>
	    </div>
	</div>
</div>
