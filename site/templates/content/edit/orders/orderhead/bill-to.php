<legend>Bill-To</legend>
<table class="table table-striped table-bordered table-condensed">
	<tr> <td>Customer</td> <td><p class="form-control-static"><?= $order->custid. ' - ' . $order->custname; ?></p></td> </tr>
	<tr> <td class="control-label">Name</td> <td><?= $order->custname; ?></td> </tr>
    <tr> <td>Address</td> <td><?= $order->btadr1; ?></td> </tr>
    <tr> <td>Address 2</td> <td><?= $order->btadr2; ?></td> </tr>
    <tr> <td class="control-label">City</td> <td><?= $order->btcity; ?></td> </tr>
    <tr> <td>State</td> <td><?= $order->btstate; ?></td> </tr>
    <tr> <td>Zip</td> <td><?= $order->btzip; ?></td> </tr>
</table>
