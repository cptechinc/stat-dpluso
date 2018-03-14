<?php if (can_accesscustomercontact($user->loginid, $user->hasrestrictions, $custID, $shipID, $contactID, false)) : ?>
	<?php $contact = get_customercontact($custID, $shipID, $contactID, false); ?>
	<h3 class="text-muted"><?= $contact->contact; ?></h3>
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
                <input type="hidden" name="custID" value="<?= $contact->custid; ?>">
                <input type="hidden" name="shipID" value="<?= $contact->shiptoid; ?>">
                <input type="hidden" name="contactID" value="<?= $contact->contact; ?>">
				<input type="hidden" name="page" value="<?= $page->fullURL; ?>">
                <table class="table table-striped table-bordered table-condensed">
                    <tbody>
                        <tr>
                            <td class="control-label">Name</td>
                            <td><input class="form-control input-sm required" name="contact-name" value="<?= $contact->contact; ?>"></td>
                        </tr>
						<tr>
							<td class="control-label">Contact Title</td>
							<td><input type="text" class="form-control input-sm" name="contact-title" value="<?= $contact->title; ?>"></td>
						</tr>
                        <tr>
                            <td class="control-label">Phone</td>
                            <td><input class="form-control input-sm phone-input required" name="contact-phone" value="<?= $page->stringerbell->format_phone($contact->phone); ?>"></td>
                        </tr>
                        <tr>
                            <td class="control-label">Ext.</td>
                            <td><input class="form-control input-sm" name="contact-extension" value="<?= $contact->extension; ?>"></td>
                        </tr>
						<tr>
						<td class="control-label">Fax</td>
							<td><input type="tel" class="form-control input-sm phone-input" name="contact-fax" value="<?= $page->stringerbell->format_phone($contact->faxnbr); ?>"></td>
						</tr>
                        <tr>
                            <td class="control-label">Cell</td>
                            <td><input class="form-control input-sm phone-input " name="contact-cellphone" value="<?= $page->stringerbell->format_phone($contact->cellphone); ?>"></td>
                        </tr>
                        <tr>
                            <td class="control-label">Email</td>
                            <td><input class="form-control input-sm required" name="contact-email" value="<?= $contact->email; ?>"></td>
                        </tr>
						<tr class="<?= $contact->has_shipto() ? 'hidden' : ''; ?>">
							<td class="control-label">AR Contact</td>
							<td>
								<?= $page->bootstrap->select('class=form-control input-sm|name=arcontact', array_flip($config->yesnoarray), $contact->arcontact); ?>
							</td>
						</tr>
						<tr class="<?= $contact->has_shipto() ? 'hidden' : ''; ?>">
							<td class="control-label">Dunning Contact</td>
							<td>
								<?= $page->bootstrap->select('class=form-control input-sm|name=dunningcontact', array_flip($config->yesnoarray), $contact->dunningcontact); ?>
							</td>
						</tr>
						<tr <?= $contact->has_shipto() ? 'hidden' : ''; ?>>
							<td class="control-label">Acknowledgement Contact</td>
							<td>
								<?= $page->bootstrap->select('class=form-control input-sm|name=ackcontact', array_flip($config->yesnoarray), $contact->ackcontact); ?>
							</td>
						</tr>
						<tr>
							<?php if ($primarycontact) : ?>
								<td class="control-label">Buying Contact <a class="small" href="<?= $primarycontact->generate_contacturl(); ?>" target="_blank">[View Primary]</a></td>
							<?php else : ?>
								<td class="control-label">Buying Contact</td>
							<?php endif; ?>
							<td>
								<?= $page->bootstrap->select('class=form-control input-sm|name=buycontact', $config->buyertypes, $contact->buyingcontact); ?>
							</td>
						</tr>
						<tr>
							<?php if ($config->cptechcustomer == 'stat') : ?>
								<td class="control-label">End User</td>
							<?php else : ?>
								<td class="control-label">Certificate Contact</td>
							<?php endif; ?>
							
							<td>
								<?= $page->bootstrap->select('class=form-control input-sm|name=certcontact', array_flip($config->yesnoarray), 'N'); ?>
							</td>
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
