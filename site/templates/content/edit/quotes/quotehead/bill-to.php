<legend>Bill-To</legend>
<table class="table table-striped table-bordered table-condensed">
	<tr> <td class="control-label">Customer</td> <td><p class="form-control-static"><?php echo $quote['custid']. ' - ' . $quote['btname']; ?></p></td> </tr>
	<tr> <td class="control-label">Name</td> <td><input class="form-control input-sm required" name="cust-name" value="<?php echo $quote['btname']; ?>"></td> </tr>
    <tr> <td class="control-label">Address</td> <td><input class="form-control input-sm required" name="cust-address" value="<?php echo $quote['btadr1']; ?>"></td> </tr>
    <tr> <td class="control-label">Address 2</td> <td><input class="form-control input-sm" name="cust-address2" value="<?php echo $quote['btadr2']; ?>"></td> </tr>
    <tr> <td class="control-label">City</td> <td><input class="form-control input-sm required" name="cust-city" value="<?php echo $quote['btcity']; ?>"></td> </tr>
    <tr>
    	<td class="control-label">State</td>
        <td>
        	<select class="form-control input-sm ordrhed" name="cust-state">
                <option value="">---</option>
                <?php $states = getstates(); ?>
                <?php foreach ($states as $state) : ?>
                    <?php if ($state['state'] == $quote['btstate'] ) { $selected = 'selected'; } else { $selected = ''; } ?>
                    <option value="<?php echo $state['state']; ?>" <?php echo $selected; ?>><?php echo $state['state'] . ' - ' . $state['name']; ?></option>
                <?php endforeach; unset($states); ?>
            </select>
        </td>
    </tr>
    <tr> <td class="control-label">Zip</td> <td><input class="form-control input-sm" name="cust-zip" value="<?php echo $quote['btzip']; ?>"></td> </tr>
</table>
