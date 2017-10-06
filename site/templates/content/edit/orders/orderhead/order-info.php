<legend>Contact</legend>
<table class="table table-striped table-bordered table-condensed">
	<tr>
    	<td class="control-label">Contact Name</td>
        <td> <input type="text" name="contact" class="form-control input-sm required" id="shiptocontact" value="<?php echo $billing['contact']; ?>"> </td>
    </tr>
    <?php if ($config->phoneintl) : ?>
		<tr>
			<td class="control-label">International ?</td>
			<td>
				<select class="form-control input-sm" name="intl" onChange="showphone(this.value)">
					<?php foreach ($config->yesnoarray as $key => $value) : ?>
						<?php if ($billing['phintl'] == $value) {$selected = 'selected';} else {$selected = '';} ?>
						<option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $key; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
   		<?php include $config->paths->content.'edit/orders/orderhead/phone-intl.php'; ?>
    	<?php include $config->paths->content.'edit/orders/orderhead/phone-domestic.php'; ?>
    <?php else : ?>
    	<?php include $config->paths->content.'edit/orders/orderhead/phone-domestic.php'; ?>
    <?php endif; ?>
    
    
    <tr>
    	<td class="control-label">Contact Email</td>
        <td> <input type="email" name="contact-email" class="form-control input-sm required email" value="<?php echo $billing['email']; ?>"> </td>
    </tr>
</table>

<legend>Sales Order</legend>
<table class="table table-striped table-bordered table-condensed">
	<tr class="bg-info">
    	<td class="control-label">Sales Person</td> <td> <p class="form-control-static"><?php echo $billing['sp1']; ?> - <?php echo $billing['sp1name']; ?></p> </td>
    </tr>
	<tr>
    	<td class="control-label">Cust PO<b class="text-danger">*</b></td> <td> <input type="text" name="custpo" class="form-control input-sm required" value="<?php echo $billing['custpo']; ?>"> </td>
    </tr>
    <tr>
    	<td class="control-label">Release #</td> <td> <input type="text" name="release-number" class="form-control input-sm" value="<?php echo $billing['releasenbr']; ?>"> </td>
    </tr>
	<tr>
    	<td>Shipvia</td>
        <td>
            <select name="shipvia" class="form-control input-sm">
				<?php $shipvias = getshipvias(session_id()); ?>
                <?php foreach($shipvias as $shipvia) : ?>
					<?php if ($billing['shipviacd'] == $shipvia['code']) {$selected = 'selected'; } else {$selected = ''; } ?>
                    <option value="<?php echo $shipvia['code']; ?>" <?php echo $selected; ?>><?php echo $shipvia['via']; ?> </option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
	<tr>
    	<td class="control-label">Terms Code</td> <td class="value"><?php echo $billing['termcode']; ?> - <?php echo $billing['termdesc']; ?></td>
    </tr>
    <tr>
    	<td class="control-label">Order Date</td> <td class="value text-right"><?php echo $billing['orderdate']; ?></td>
    </tr>
    <tr>
    	<td class="control-label">Request Date</td>
        <td>
			<div class="input-group date">
                <?php $name = 'rqstdate'; $value = $billing['rqstdate']; ?>
				<?php include $config->paths->content."common/date-picker.php"; ?>
            </div>
        </td>
    </tr>
    <tr>
    	<td class="control-label">Ship Complete</td>
        <td>
			<select name="ship-complete" class="form-control input-sm">
				<?php foreach ($config->yesnoarray as $key => $value) : ?>
                    <?php if ($billing['shipcom'] == $value) {$selected = 'selected'; } else {$selected = ''; } ?>
                    <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $key; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <?php if ($billing['termtype'] == 'STD') : ?>
    	<?php if ($billing['paytype'] == 'cc') { $cchidden = ''; } else { $cchidden = 'hidden';} ?>
        <tr>
            <td class="control-label">Payment Type</td>
            <td>
                <select name="paytype" class="form-control input-sm required" onChange="showcredit(this.value)">
                    <option value="<?php echo $billing['paytype']; ?>">-- Choose Payment Type -- </option>
                    <option value="billacc" <?php if ($billing['paytype'] == 'bill') echo 'selected'; ?> >Bill To Account</option>
                    <option value="cc" <?php if ($billing['paytype'] == 'cc') {echo 'selected';} ?>>Credit Card</option>
                </select>
            </td>
        </tr>
    <?php else :  ?>

    <?php endif; ?>

</table>
<?php $creditcard = getordercreditcard(session_id(), $ordn, false); ?>

<?php if ($billing['termtype'] == 'STD') : ?>
    <div id="credit" class="<?php echo $cchidden; ?>">
        <?php include $config->paths->content.'edit/orders/orderhead/credit-card-form.php'; ?>
    </div>
<?php else : ?>
	<?php include $config->paths->content.'edit/orders/orderhead/credit-card-form.php'; ?>
<?php endif; ?>
