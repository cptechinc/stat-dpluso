<?php if (isset($_SESSION['shipID']) && $_SESSION['shipID'] != '' && $_SESSION['custID'] == $order->custid) : ?>
	<?php $shiptoid = $_SESSION['shipID']; ?>
<?php else : ?>
    <?php $shiptoid = $order->shiptoid; ?>
<?php endif ;?>

<legend>Ship-To <?= $order->shiptoid; ?></legend>
<?php if (100 == 1) : ?>
	<div class="form-group">
		<button type="button" class="btn btn-block btn-primary" id="load-shiptos">Load This Customer's Ship-tos</button>
	</div>
<?php endif; ?>

<table class="table table-striped table-bordered table-condensed">
	<tr>
    	<td class="control-label">Ship-To ID <b class="text-danger">*</b> <input type="hidden" id="shipto-id" value="<?= $order->shiptoid; ?>"></td>
        <td>
        	<select class="form-control input-sm ordrhed shipto-select" name="shiptoid" data-custid="<?= $order->custid; ?>">
				<?php $shiptos = get_customershiptos($order->custid, $user->loginid, $user->hasrestrictions, false); ?>
                <?php foreach ($shiptos as $shipto) : ?>
                    <?php if ($order->shiptoid == $shipto['shiptoid']) : ?>
                        <option value="<?= $order->shiptoid; ?>" selected><?= $order->shiptoid.' - '.$order->sname; ?></option>
                    <?php else : ?>
                        <option value="<?= $shipto['shiptoid'];?>"><?= $shipto['shiptoid'].' - '.$shipto['name']; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
                <option value="">Drop Ship To </option>
            </select>
        </td>
    </tr>
    <tr>
    	<td class="control-label">Ship-To Name <b class="text-danger">*</b></td>
    	<td><input type="text" class="form-control input-sm ordrhed required shipto-name" name="shiptoname" value="<?= $order->sname; ?>"></td>
    </tr>
    <tr>
    	<td class="control-label">Address <b class="text-danger">*</b></td>
    	<td><input type="text" class="form-control input-sm ordrhed required shipto-address" name="shipto-address" value="<?= $order->saddress; ?>"></td>
    </tr>
    <tr>
    	<td class="control-label">Address 2 <b class="text-danger">*</b></td>
    	<td><input type="text" class="form-control input-sm ordrhed shipto-address2" name="shipto-address2" value="<?= $order->saddress2; ?>"></td>
    </tr>
    <tr>
    	<td class="control-label">City <b class="text-danger">*</b></td>
    	<td><input type="text" class="form-control input-sm required shipto-city" name="shipto-city" value="<?= $order->scity; ?>"></td>
    </tr>
    <tr>
    	<td class="control-label">State <b class="text-danger">*</b></td>
    	<td>
        	<select class="form-control input-sm shipto-state" name="shipto-state">
            <option value="">---</option>
				<?php $states = getstates(); ?>
                <?php foreach ($states as $state) : ?>
                    <?php if ($state['state'] == $order->sst ) { $selected = 'selected'; } else { $selected = ''; } ?>
                    <option value="<?= $state['state']; ?>" <?= $selected; ?>><?= $state['state'] . ' - ' . $state['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
    	<td class="control-label">Zip <b class="text-danger">*</b></td>
    	<td><input type="text" class="form-control input-sm required shipto-zip" name="shipto-zip" value="<?= $order->szip; ?>"></td>
    </tr>
	<tr>
		<td class="control-label">Country</td>
		<td>
			<?php $countries = getcountries(); if (empty($order->scountry)) {$order->scountry = 'USA';}?>
			<select name="shipto-country" class="form-control input-sm">
				<?php foreach ($countries as $country) : ?>
					<?php if ($country['ccode'] == $order->scountry ) { $selected = 'selected'; } else { $selected = ''; } ?>
					<option value="<?= $country['ccode']; ?>" <?= $selected; ?>><?= $country['name']; ?></option>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>
</table>
