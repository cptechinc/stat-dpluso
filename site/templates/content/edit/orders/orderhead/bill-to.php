<legend>Bill-To</legend>
<table class="table table-striped table-bordered table-condensed">
	<tr> <td>Customer</td> <td><p class="form-control-static"><?= $billing->custid. ' - ' . $billing->custname; ?></p></td> </tr>
	<tr> <td class="control-label">Name</td> <td><input class="form-control input-sm required" name="cust-name" value="<?= $billing->custname; ?>"></td> </tr>
    <tr> <td>Address</td> <td><input class="form-control input-sm required" name="cust-address" value="<?= $billing->btadr1; ?>"></td> </tr>
    <tr> <td>Address 2</td> <td><input class="form-control input-sm" name="cust-address2" value="<?= $billing->btadr2; ?>"></td> </tr>
    <tr> <td class="control-label">City</td> <td><input class="form-control input-sm required" name="cust-city" value="<?= $billing->btcity; ?>"></td> </tr>
    <tr>
    	<td>State</td>
        <td>
        	<select class="form-control input-sm ordrhed" name="cust-state">
                <option value="">---</option>
                <?php $states = getstates(); ?>
                <?php foreach ($states as $state) : ?>
                    <?php if ($state['state'] == $billing->btstate) { $selected = 'selected'; } else { $selected = ''; } ?>
                    <option value="<?= $state['state']; ?>" <?= $selected; ?>><?= $state['state'] . ' - ' . $state['name']; ?></option>
                <?php endforeach; unset($states); ?>
            </select>
        </td>
    </tr>
    <tr> <td>Zip</td> <td><input class="form-control input-sm" name="cust-zip" value="<?= $billing->btzip; ?>"></td> </tr>
</table>
