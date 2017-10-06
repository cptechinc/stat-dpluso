<?php
	$actions = getuseractions($user->loginid, $actionpanel->querylinks, $config->showonpage, $input->pageNum(), false);
?>
<table class="table table-bordered table-condensed table-striped">
	<thead>
    	<tr> <th>Due</th> <th>Type</th> <th>Subtype</th> <th>CustID</th> <th>Regarding</th> <th>Note</th> <th>View action</th>  </tr>
    </thead>
    <tbody>
		<?php if (!sizeof($actions)) : ?>
			<tr>
				<td colspan="7" class="text-center h4">
					No related actions found
				</td>
			</tr>
		<?php else : ?>
			<?php foreach ($actions as $action) : ?>
				<?php if ($action->isoverdue && $action->actiontype == 'task' && (!$action->isrescheduled)) {$class="bg-warning";} else {$class="";} ?>
	            <tr class="<?= $class; ?>">
	                <td><?= $action->displayduedate('m/d/Y'); ?></td>
					<td><?= $action->actiontype; ?></td>
					<td><?= $action->getactionsubtypedescription(); ?></td>
					<td><?= $action->customerlink; ?></td>
					<td><?= $action->regardinglink; ?></td>
	                <td><?= $action->title; ?></td>
					<td>
						<a href="<?= $action->generateviewactionurl(); ?>" role="button" class="btn btn-xs btn-primary load-action" data-modal="<?= $actionpanel->modal; ?>" title="View Task">
							<i class="material-icons md-18">&#xE02F;</i>
						</a>
					</td>
	            </tr>
	        <?php endforeach; ?>
		<?php endif; ?>
    </tbody>
</table>
