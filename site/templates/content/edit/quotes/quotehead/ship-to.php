<?php if (isset($_SESSION['shipID']) && $_SESSION['shipID'] != '' && $_SESSION['custID'] == $quote->custid) : ?>
	<?php $shiptoid = $_SESSION['shipID']; ?>
<?php else : ?>
    <?php $shiptoid = $quote->shiptoid; ?>
<?php endif ;?>

<legend>Ship-To <?= $quote->shiptoid; ?></legend>

<table class="table table-striped table-bordered table-condensed">
	<tr>
    	<td class="control-label"><?= $formconfig->fields['fields']['shiptoid']['label']; ?><?= $formconfig->generate_asterisk('shiptoid'); ?><input type="hidden" id="shipto-id" value="<?= $quote->shiptoid; ?>"></td>
        <td>
        	<select class="form-control input-sm ordrhed <?php echo $formconfig->generate_showrequiredclass('shiptoid'); ?> shipto-select" name="shiptoid" data-custid="<?= $quote->custid; ?>">
				<?php $shiptos = get_customershiptos($quote->custid, $user->loginid, $user->hasrestrictions, false); ?>
                <?php foreach ($shiptos as $shipto) : ?>
					<?php $selected =  ($shipto->shiptoid == $quote->shiptoid) ? 'selected' : ''; ?>
                    <option value="<?= $shipto->shiptoid;?>" <?= $selected; ?>><?= $shipto->shiptoid.' - '.$shipto->name; ?></option>
                <?php endforeach; ?>
                <option value="">Drop Ship To </option>
            </select>
        </td>
    </tr>
    <tr>
    	<td class="control-label"><?= $formconfig->fields['fields']['stname']['label']; ?><?= $formconfig->generate_asterisk('stname'); ?></td>
    	<td><input type="text" class="form-control input-sm ordrhed <?php echo $formconfig->generate_showrequiredclass('stname'); ?> shipto-name" name="shiptoname" value="<?= $quote->stname; ?>"></td>
    </tr>
    <tr>
    	<td class="control-label"><?= $formconfig->fields['fields']['stadr1']['label']; ?><?= $formconfig->generate_asterisk('stadr1'); ?></td>
    	<td><input type="text" class="form-control input-sm ordrhed <?php echo $formconfig->generate_showrequiredclass('stadr1'); ?> shipto-address" name="shipto-address" value="<?= $quote->stadr1; ?>"></td>
    </tr>
    <tr>
    	<td class="control-label"><?= $formconfig->fields['fields']['stadr2']['label']; ?><?= $formconfig->generate_asterisk('stadr2'); ?></td>
    	<td><input type="text" class="form-control input-sm ordrhed <?php echo $formconfig->generate_showrequiredclass('stadr2'); ?> shipto-address2" name="shipto-address2" value="<?= $quote->stadr2; ?>"></td>
    </tr>
    <tr>
    	<td class="control-label"><?= $formconfig->fields['fields']['stcity']['label']; ?><?= $formconfig->generate_asterisk('stcity'); ?></td>
    	<td><input type="text" class="form-control input-sm <?php echo $formconfig->generate_showrequiredclass('stcity'); ?> shipto-city " name="shipto-city" value="<?= $quote->stcity; ?>"></td>
    </tr>
    <tr>
    	<td class="control-label"><?= $formconfig->fields['fields']['ststate']['label']; ?><?= $formconfig->generate_asterisk('ststate'); ?></td>
    	<td>
        	<select class="form-control input-sm <?php echo $formconfig->generate_showrequiredclass('ststate'); ?> shipto-state" name="shipto-state">
            <option value="">---</option>
				<?php $states = getstates(); ?>
                <?php foreach ($states as $state) : ?>
					<?php $selected = ($state['state'] == $quote->ststate) ? 'selected' : ''; ?>
                    <option value="<?= $state['state']; ?>" <?= $selected; ?>><?= $state['state'] . ' - ' . $state['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
    	<td class="control-label"><?= $formconfig->fields['fields']['stzip']['label']; ?><?= $formconfig->generate_asterisk('stzip'); ?></td>
    	<td><input type="text" class="form-control input-sm <?php echo $formconfig->generate_showrequiredclass('stzip'); ?> shipto-zip" name="shipto-zip" value="<?= $quote->stzip; ?>"></td>
    </tr>
	<tr>
		<td class="control-label">Country</td>
		<td>
			<?php $countries = getcountries(); if (empty($quote->stctry)) {$quote->stctry = 'USA';}?>
			<select name="shipto-country" class="form-control input-sm">
				<?php foreach ($countries as $country) : ?>
					<?php $selected = ($country['ccode'] == $quote->stctry) ? 'selected' : ''; ?>
					<option value="<?= $country['ccode']; ?>" <?= $selected; ?>><?= $country['name']; ?></option>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>
</table>
