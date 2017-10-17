<tr class="domestic <?= $hidden_domestic; ?>">
    <td class="control-label">Contact Phone</td>
    <td> <input type="text" name="contact-phone" class="form-control input-sm phone-input required" id="shiptophone"  value="<?= $billing->phone; ?>"> </td>
</tr>
<tr class="domestic <?= $hidden_domestic; ?>">
    <td class="control-label">Ext.</td>
    <td> <input type="text" name="contact-extension" class="form-control input-sm phone-input" value="<?= $billing->extension; ?>"> </td>
</tr>

<tr class="domestic <?= $hidden_domestic; ?>">
    <td class="control-label">Contact Fax</td>
    <td> <input type="text" name="contact-fax" class="form-control input-sm phone-input" value="<?= $billing->faxnumber; ?>"> </td>
</tr>

<?php //onKeyup='addDashes(this)' ?>
