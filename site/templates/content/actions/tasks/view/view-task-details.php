<table class="table table-bordered table-striped">
    <tr>
        <td>Task ID:</td> <td><?= $task->id; ?></td>
    </tr>
    <tr>
        <td>Task Type:</td> <td><?= $task->generate_actionsubtypedescription();; ?></td>
    </tr>
    <tr>
        <td>Status:</td>
        <td><?= $task->generate_taskstatusdescription(); ?></td>
    </tr>
    <?php if ($task->is_rescheduled()) : ?>
        <tr>
            <td>Rescheduled task</td>
            <td>
                <?= $task->rescheduledlink; ?> &nbsp; &nbsp;
                <?= $taskdisplay->generate_viewactionlink($rescheduledtask); ?>
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <td>Written on:</td> <td><?= date('m/d/Y g:i A', strtotime($task->datecreated)); ?></td>
    </tr>
    <tr>
        <td>Written by:</td> <td><?= $task->createdby; ?></td>
    </tr>
    <tr>
        <td>Due:</td> <td><?= $task->generate_duedatedisplay('m/d/Y g:i A') ?></td>
    </tr>
    <tr>
        <td>Customer:</td>
        <td><?= get_customername($task->customerlink); ?> <?= $taskdisplay->generate_customerpagelink($task); ?>
    </tr>
    <?php if ($task->has_shiptolink()) : ?>
        <tr>
            <td>Ship-to:</td>
            <td><?= get_shiptoname($task->customerlink, $task->shiptolink, false); ?> <?= $taskdisplay->generate_shiptopagelink($task); ?></td>
        </tr>
    <?php endif; ?>
    <?php if ($task->has_contactlink()) : ?>
        <tr>
            <td>Task Contact:</td>
            <td><?= $task->contactlink; ?> <?= $taskdisplay->generate_contactpagelink($task); ?></td>
        </tr>
    <?php else : ?>
        <tr>
            <td class="text-center h5" colspan="2">
                Who to Contact
            </td>
        </tr>
        <tr>
            <td>Contact: </td>
            <td><?= $contactinfo['contact']; ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td>Phone:</td>
        <td>
            <a href="tel:<?= $contactinfo['cphone']; ?>"><?= $contactinfo['cphone']; ?></a> &nbsp; <?php if ($contactinfo['cphext'] != '') {echo ' Ext. '.$contactinfo['cphext'];} ?>
        </td>
    </tr>
    <?php if ($contactinfo['ccellphone'] != '') : ?>
        <tr>
            <td>Cell Phone:</td>
            <td>
                <a href="tel:<?= $contactinfo['ccellphone']; ?>"><?= $contactinfo['ccellphone']; ?></a>
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <td>Email:</td>
        <td><a href="mailto:<?= $contactinfo['email']; ?>"><?= $contactinfo['email']; ?></a></td>
    </tr>
    <?php if ($task->has_salesorderlink()) : ?>
        <tr>
            <td>Sales Order #:</td>
            <td><?= $task->salesorderlink; ?></td>
        </tr>
    <?php endif; ?>
    <?php if ($task->has_quotelink()) : ?>
        <tr>
            <td>Quote #:</td>
            <td><?= $task->quotelink; ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td class="control-label">Title</td> <td><?= $task->title; ?></td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Notes</b><br>
            <div class="display-notes">
                <?= $task->textbody; ?>
            </div>
        </td>
    </tr>
    <?php if ($task->is_completed()) : ?>
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

<?php if (!$task->is_completed() && !$task->is_rescheduled()) : ?>
    <?= $taskdisplay->generate_completetasklink($task); ?>
    &nbsp;
    &nbsp;
    <?= $taskdisplay->generate_rescheduletasklink($task); ?>
<?php endif; ?>
