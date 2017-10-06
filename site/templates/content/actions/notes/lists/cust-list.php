<?php
	$notes = getuseractions($user->loginid, $actionpanel->querylinks, $config->showonpage, $input->pageNum(), false);
?>


<table class="table table-bordered table-condensed table-striped">
	<thead>
    	<tr> <th>Type</th> <th>Subtype</th> <th>Written on</th> <th>Title</th> <th>View Note</th>  </tr>
    </thead>
    <tbody>
		<?php if (!sizeof($notes)) : ?>
			<tr>
				<td colspan="6" class="text-center h4">
					No related Notes found
				</td>
			</tr>
		<?php else : ?>
			<?php foreach ($notes as $note) : ?>
	            <tr>
					<td><?= ucfirst($note->actiontype); ?></td>
					<td><?= ucfirst($note->getactionsubtypedescription()); ?></td>
                    <td><?= date('m/d/Y H:i A', strtotime($note->datecreated)); ?></td>
	                <td><?= $note->title; ?></td>
					<td>
						<a href="<?= $note->generateviewactionurl(); ?>" role="button" class="btn btn-xs btn-primary load-action" data-modal="<?= $actionpanel->modal; ?>" title="View Task">
							<i class="material-icons md-18">&#xE02F;</i>
						</a>
					</td>
	            </tr>
	        <?php endforeach; ?>
		<?php endif; ?>
    </tbody>
</table>
