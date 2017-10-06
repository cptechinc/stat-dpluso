<?php if (isset($_SESSION['shipID']) && $_SESSION['shipID'] != '' && $_SESSION['custID'] == $quote['custid']) : ?>
	<?php $shiptoid = $_SESSION['shipID']; ?>
<?php else : ?>
    <?php $shiptoid = $quote['shiptoid']; ?>
<?php endif ;?>

<legend>Ship-To <?php echo $quote['shiptoid']; ?></legend>

<table class="table table-striped table-bordered table-condensed">
	<tr>
    	<td class="control-label">Ship-To ID <b class="text-danger">*</b> <input type="hidden" id="shipto-id" value="<?php echo $quote['shiptoid']; ?>"></td>
        <td>
        	<select class="form-control input-sm ordrhed shipto-select" name="shiptoid" data-custid="<?php echo $quote['custid']; ?>">
				<?php $shiptos = get_customershiptos($quote['custid'], $user->loginid, $user->hasrestrictions, false); ?>
                <?php foreach ($shiptos as $shipto) : ?>
                    <?php if ($quote['shiptoid'] == $shipto['shiptoid']) : ?>
                        <option value="<?php echo $shipto['shiptoid'];?>" selected><?php echo $shipto['shiptoid'].' - '.$shipto['name']; ?></option>
                    <?php else : ?>
                        <option value="<?php echo $shipto['shiptoid'];?>"><?php echo $shipto['shiptoid'].' - '.$shipto['name']; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
                <option value="">Drop Ship To </option>
            </select>
        </td>
    </tr>
    <tr>
    	<td class="control-label">Ship-To Name <b class="text-danger">*</b></td>
    	<td><input type="text" class="form-control input-sm ordrhed required shipto-name" name="shiptoname" value="<?php echo $quote['stname']; ?>"></td>
    </tr>
    <tr>
    	<td class="control-label">Address <b class="text-danger">*</b></td>
    	<td><input type="text" class="form-control input-sm ordrhed required shipto-address" name="shipto-address" value="<?php echo $quote['stadr1']; ?>"></td>
    </tr>
    <tr>
    	<td class="control-label">Address 2 <b class="text-danger">*</b></td>
    	<td><input type="text" class="form-control input-sm ordrhed shipto-address2" name="shipto-address2" value="<?php echo $quote['stadr2']; ?>"></td>
    </tr>
    <tr>
    	<td class="control-label">City <b class="text-danger">*</b></td>
    	<td><input type="text" class="form-control input-sm required shipto-city" name="shipto-city" value="<?php echo $quote['stcity']; ?>"></td>
    </tr>
    <tr>
    	<td class="control-label">State <b class="text-danger">*</b></td>
    	<td>
        	<select class="form-control input-sm shipto-state" name="shipto-state">
            <option value="">---</option>
				<?php $states = getstates(); ?>
                <?php foreach ($states as $state) : ?>
                    <?php if ($state['state'] == $quote['ststate'] ) { $selected = 'selected'; } else { $selected = ''; } ?>
                    <option value="<?php echo $state['state']; ?>" <?php echo $selected; ?>><?php echo $state['state'] . ' - ' . $state['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
    	<td class="control-label">Zip <b class="text-danger">*</b></td>
    	<td><input type="text" class="form-control input-sm required shipto-zip" name="shipto-zip" value="<?php echo $quote['stzip']; ?>"></td>
    </tr>
	<tr>
		<td class="control-label">Country</td>
		<td>
			<?php $countries = getcountries(); if (empty($quote['stctry'])) {$quote['stctry'] = 'USA';}?>
			<select name="shipto-country" class="form-control input-sm">
				<?php foreach ($countries as $country) : ?>
					<?php if ($country['ccode'] == $quote['stctry'] ) { $selected = 'selected'; } else { $selected = ''; } ?>
					<option value="<?= $country['ccode']; ?>" <?= $selected; ?>><?= $country['name']; ?></option>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>
</table>
