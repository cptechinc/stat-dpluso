<table class="table table-bordered table-striped">
    <tr>
        <td>Task ID:</td> <td><?= $task->id; ?></td>
    </tr>
    <tr>
        <td>Task Type:</td> <td><?= $task->getactionsubtypedescription();; ?></td>
    </tr>
    <tr>
        <td>Status:</td>
        <td><?= $task->displaystatusdescription(); ?></td>
    </tr>
    <?php if ($task->isrescheduled) : ?>
        <tr>
            <td>Rescheduled task</td>
            <td>
                <?= $task->rescheduledlink; ?> &nbsp; &nbsp;
                <a href="<?= $rescheduledtask->generateviewactionurl(); ?>" role="button" class="btn btn-xs btn-primary load-action" data-modal="" title="View Task">
                    <i class="material-icons md-18">&#xE02F;</i> View Action
                </a>
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <td>Written on:</td> <td><?php echo date('m/d/Y g:i A', strtotime($task->datecreated)); ?></td>
    </tr>
    <tr>
        <td>Written by:</td> <td><?php echo $task->createdby; ?></td>
    </tr>
    <tr>
        <td>Due:</td> <td><?php echo $task->displayduedate('m/d/Y g:i A') ?></td>
    </tr>
    <tr>
        <td>Customer:</td>
        <td><?= get_customername($task->customerlink); ?> <a href="<?php echo $task->generatecustomerurl(); ?>" target="_blank"><i class="glyphicon glyphicon-share"></i> Go to Customer Page</a></td>
    </tr>
    <?php if ($task->hasshiptolink) : ?>
        <tr>
            <td>Ship-to:</td>
            <td><?= get_shiptoname($task->customerlink, $task->shiptolink, false); ?> <a href="<?php echo $task->generateshiptourl(); ?>" target="_blank"><i class="glyphicon glyphicon-share"></i> Go to Ship-to Page</a></td>
        </tr>
    <?php endif; ?>
    <?php if ($task->hascontactlink) : ?>
        <tr>
            <td>Task Contact:</td>
            <td><?php echo $task->contactlink; ?> <a href="<?php echo $task->generatecontacturl(); ?>" target="_blank"><i class="glyphicon glyphicon-share"></i> Go to Contact Page</a></td>
        </tr>
    <?php else : ?>
        <tr>
            <td class="text-center h5" colspan="2">
                Who to Contact
            </td>
        </tr>
        <tr>
            <td>Contact: </td>
            <td><?php echo $contactinfo['contact']; ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td>Phone:</td>
        <td>
            <a href="tel:<?php echo $contactinfo['cphone']; ?>"><?php echo $contactinfo['cphone']; ?></a> &nbsp; <?php if ($contactinfo['cphext'] != '') {echo ' Ext. '.$contactinfo['cphext'];} ?>
        </td>
    </tr>
    <?php if ($contactinfo['ccellphone'] != '') : ?>
        <tr>
            <td>Cell Phone:</td>
            <td>
                <a href="tel:<?php echo $contactinfo['ccellphone']; ?>"><?php echo $contactinfo['ccellphone']; ?></a>
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <td>Email:</td>
        <td><a href="mailto:<?php echo $contactinfo['email']; ?>"><?php echo $contactinfo['email']; ?></a></td>
    </tr>
    <?php if ($task->hasorderlink) : ?>
        <tr>
            <td>Sales Order #:</td>
            <td><?php echo $task->salesorderlink; ?></td>
        </tr>
    <?php endif; ?>
    <?php if ($task->hasquotelink) : ?>
        <tr>
            <td>Quote #:</td>
            <td><?php echo $task->quotelink; ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td class="control-label">Title</td> <td><?= $task->title; ?></td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Notes</b><br>
            <div class="display-notes">
                <?php echo $task->textbody; ?>
            </div>
        </td>
    </tr>
    <?php if ($task->hascompleted) : ?>
        <tr>
            <td colspan="2">
                <b>Completion Notes</b><br>
                <div class="display-notes">
                    <?= $task->reflectnote; ?>
                </div>
            </td>
        </tr>
    <?php endif; ?>
</table>

<?php if (!$task->hascompleted && !$task->isrescheduled) : ?>
    <a href="<?= $task->generateviewactionjson(); ?>" class="btn btn-primary complete-action" data-id="<?= $task->id; ?>">
        <i class="fa fa-check-circle" aria-hidden="true"></i> Complete Task
    </a>
    &nbsp;
    &nbsp;
    <a href="<?= $task->generaterescheduleurl(); ?>" class="btn btn-default reschedule-task">
        <i class="fa fa-calendar" aria-hidden="true"></i> Reschedule Task
    </a>
<?php endif; ?>
