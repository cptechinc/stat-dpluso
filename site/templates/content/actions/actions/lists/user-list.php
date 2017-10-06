<?php
	$tasks = getuseractions($user->loginid, $actionpanel->querylinks, $config->showonpage, $input->pageNum(), false);
?>

<table class="table table-bordered table-condensed table-striped">
	<thead>
    	<tr> <th>Date / Time</th> <th>Type</th> <th>Subtype</th> <th>CustID</th> <th>Regarding</th> <th>View action</th> </tr>
    </thead>
    <tbody>
		<?php if (!sizeof($tasks)) : ?>
			<tr>
				<td colspan="7" class="text-center h4">
					No related actions found
				</td>
			</tr>
		<?php else : ?>
			<?php foreach ($tasks as $task) : ?>
				<?php if ($task->isoverdue) {$class="bg-warning";} else {$class = "";} ?>
	            <tr class="<?= $class; ?>">
					<td><?= date('m/d/Y H:i A', strtotime($task->datecompleted)); ?></td>
					<td><?= $task->actiontype; ?></td>
					<td><?= $task->getactionsubtypedescription(); ?></td>
					<td><?= $task->customerlink; ?></td>
	                <td><?= $task->regardinglink; ?></td>
					<td>
						<a href="<?= $task->generateviewactionurl(); ?>" role="button" class="btn btn-xs btn-primary load-action" data-modal="<?= $actionpanel->modal; ?>" title="View Task">
							<i class="material-icons md-18">&#xE02F;</i>
						</a>
					</td>
	            </tr>
	        <?php endforeach; ?>
		<?php endif; ?>
    </tbody>
</table>
