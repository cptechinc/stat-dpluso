<?php
	include $config->paths->assets."classes/php-ical/vendor/autoload.php";
	use RRule\RRule;
    $message = "Viewing Task Schedule for {replace} ";
    $tasktitle = createmessage($message, $custID, $shipID, $contactID, $taskID, $noteID, $ordn, $qnbr);
	$scheduledtasks = get_user_scheduled_tasks($user->loginid, $custID, $shipID, $contactID, false);
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title" id="ajax-modal-label"><?= $tasktitle; ?></h4>
</div>
<div class="modal-body scroll">
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Start Date</th> <th>Active</th> <th>Description</th> <th>Frequency description</th> <th>Next Scheduled Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scheduledtasks as $scheduledtask) : ?>
                    <?php 
						$rule = new RRule($scheduledtask['repeatlogic']); 
						$nextdate = '';
						foreach ($rule as $occurrence) {
							if (strtotime($occurrence->format('Y-m-d')) > strtotime(date('Y-m-d')) ) {
								$nextdate = $occurrence->format('Y-m-d');
								break;
							}
						}
					?>
                       
                        <tr>
                            <td><?= date('m/d/Y', strtotime($scheduledtask['startdate'])); ?></td> <td><?= $scheduledtask['active']; ?></td>
                            <td><?= $scheduledtask['description']; ?></td> <td><?php echo $rule->humanReadable(); ?></td>
                            <td><?php echo $nextdate; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>