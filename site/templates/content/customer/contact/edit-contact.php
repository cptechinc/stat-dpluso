<?php if (can_accesscustomercontact($user->loginid, $user->hasrestrictions, $custID, $shipID, $contactID, false)) : ?>
	<?php $contact = get_customercontact($custID, $shipID, $contactID, false); ?>
	<h3 class="text-muted"><?php echo $contact['contact']; ?></h3>
	<?php include $config->paths->content.'customer/contact/contact-address.php'; ?>
	<div class="row">
		<div class="col-sm-6">
            <h4>Original Contact Details</h4>
            <?php include $config->paths->content.'customer/contact/contact-card.php'; ?>
        </div>
        <div class="col-sm-6">
            <h4>Change Contact Details</h4>
            <form action="<?= $config->pages->customer.'redir/'; ?>" method="post">
                <input type="hidden" name="action" value="edit-contact">
                <input type="hidden" name="custID" value="<?php echo $custID; ?>">
                <input type="hidden" name="shipID" value="<?php echo $shipID; ?>">
                <input type="hidden" name="contactID" value="<?php echo $contactID; ?>">
				<input type="hidden" name="page" value="<?= $page->fullURL; ?>">
                <table class="table table-striped table-bordered table-condensed">
                    <tbody>
                        <tr>
                            <td class="control-label">Name</td>
                            <td><input class="form-control input-sm required" name="name" value="<?= $contact['contact']; ?>"></td>
                        </tr>
                        <tr>
                            <td class="control-label">Phone</td>
                            <td><input class="form-control input-sm phone-input required" name="phone" value="<?= formatphone($contact['cphone']); ?>"></td>
                        </tr>
                        <tr>
                            <td class="control-label">Ext.</td>
                            <td><input class="form-control input-sm" name="extension" value="<?= $contact['cphext']; ?>"></td>
                        </tr>
                        <tr>
                            <td class="control-label">Cell</td>
                            <td><input class="form-control input-sm phone-input " name="cellphone" value="<?= formatphone($contact['ccellphone']); ?>"></td>
                        </tr>
                        <tr>
                            <td class="control-label">Email</td>
                            <td><input class="form-control input-sm required" name="email" value="<?= $contact['email']; ?>"></td>
                        </tr>
                    </tbody>
               </table>
               <button type="submit" class="btn btn-success">
                  <i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Save Changes
               </button>
    		</form>
        </div>
	</div>
<?php endif; ?>
