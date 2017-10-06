<table class="table table-bordered table-striped">
    <tr>
        <td>Task ID:</td> <td><?= $task->id; ?></td>
    </tr>
    <tr>
        <td>Written on:</td> <td><?php echo date('m/d/Y g:i A', strtotime($task->datewritten)); ?></td>
    </tr>
    <tr>
        <td>Written by:</td> <td><?php echo $task->writtenby; ?></td>
    </tr>
    <tr>
        <td>Due:</td> <td><?php echo date('m/d/Y g:i A', strtotime($task->duedate)); ?></td>
    </tr>
    <tr>
        <td>Customer:</td>
        <td><?= get_customername($task->customerlink); ?> <a href="<?php echo $task->generatecustomerurl(); ?>" target="_blank"><i class="glyphicon glyphicon-share"></i> Go to Customer Page</a></td>
    </tr>
    <?php if ($task->hasshiptolink) : ?>
        <tr>
            <td>Ship-to:</td>
            <td><?php echo get_shiptoname($task->customerlink, $task->shiptolink, false); ?> <a href="<?php echo $task->generateshiptourl(); ?>" target="_blank"><i class="glyphicon glyphicon-share"></i> Go to Ship-to Page</a></td>
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
        <td colspan="2"><b>Notes</b><br><?php echo $task->textbody; ?></td>
    </tr>
</table>

<?php if (!$task->hascompleted && !$task->isrescheduled) : ?>
    <a href="<?= $task->generatecompletionurl('true'); ?>" class="btn btn-primary complete-task" data-id="<?= $task->id; ?>">
        <i class="fa fa-check-circle" aria-hidden="true"></i> Complete Task
    </a>
    &nbsp;
    &nbsp;
    <a href="<?= $task->generaterescheduleurl(); ?>" class="btn btn-default reschedule-task">
        <i class="fa fa-calendar" aria-hidden="true"></i> Reschedule Task
    </a>
<?php endif; ?>
