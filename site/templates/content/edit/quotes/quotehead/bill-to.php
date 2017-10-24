<legend>Bill-To</legend>
<table class="table table-striped table-bordered table-condensed">
	<tr> <td class="control-label">Customer</td> <td><?= $quote->custid. ' - ' . $quote->btname; ?></td> </tr>
	<tr> <td class="control-label">Name</td> <td><?= $quote->btname; ?></td> </tr>
    <tr> <td class="control-label">Address</td> <td><?= $quote->btadr1; ?></td> </tr>
    <tr> <td class="control-label">Address 2</td> <td><?= $quote->btadr2; ?></td> </tr>
    <tr> <td class="control-label">City</td> <td><?= $quote->btcity; ?></td> </tr>
    <tr> <td class="control-label">State</td> <td><?= $quote->btstate; ?></td> </tr>
    <tr> <td class="control-label">Zip</td> <td><?= $quote->btzip; ?></td> </tr>
</table>
