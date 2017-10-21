<legend>Bill-To</legend>
<table class="table table-striped table-bordered table-condensed">
	<tr> <td class="control-label">Customer</td> <td><p class="form-control-static"><?php echo $quote['custid']. ' - ' . $quote['btname']; ?></p></td> </tr>
	<tr> <td class="control-label">Name</td> <td><?php echo $quote['btname']; ?></td> </tr>
    <tr> <td class="control-label">Address</td> <td><?php echo $quote['btadr1']; ?></td> </tr>
    <tr> <td class="control-label">Address 2</td> <td><?php echo $quote['btadr2']; ?></td> </tr>
    <tr> <td class="control-label">City</td> <td><?php echo $quote['btcity']; ?></td> </tr>
    <tr> <td class="control-label">State</td> <td><?php echo $quote['btstate']; ?></td> </tr>
    <tr> <td class="control-label">Zip</td> <td><?php echo $quote['btzip']; ?></td> </tr>
</table>
