<table class="table table-bordered table-striped">
    <tr>
        <td>Written on:</td> <td><?= date('m/d/Y g:i A', strtotime($note->datecreated)); ?></td>
    </tr>
    <tr>
        <td>Written by:</td> <td><?= $note->writtenby; ?></td>
    </tr>
    <tr>
        <td>Customer:</td>
        <td><?= get_customername($note->customerlink); ?> <a href="<?php echo $note->generatecustomerurl(); ?>"><i class="glyphicon glyphicon-share"></i> Go to Customer Page</a></td>
    </tr>
    <?php if ($note->hasshiptolink) : ?>
        <tr>
            <td>Ship-to:</td>
            <td><?= get_shiptoname($note->customerlink, $note->shiptolink, false); ?> <a href="<?php echo $note->generateshiptourl(); ?>"><i class="glyphicon glyphicon-share"></i> Go to Ship-to Page</a></td>
        </tr>
    <?php endif; ?>
    <?php if ($note->hascontactlink) : ?>
        <tr>
            <td>Contact:</td>
            <td><?= $note->contactlink; ?> <a href="<?= $note->generatecontacturl(); ?>"><i class="glyphicon glyphicon-share"></i> Go to Contact Page</a></td>
        </tr>
    <?php endif; ?>
    <?php if ($note->hasorderlink) : ?>
        <tr>
            <td>Sales Order #:</td>
            <td><?= $note->salesorderlink; ?></td>
        </tr>
    <?php endif; ?>
    <?php if ($note->hasquotelink) : ?>
        <tr>
            <td>Quote #:</td>
            <td><?= $note->quotelink; ?></td>
        </tr>
    <?php endif; ?>
    <?php if ($note->hastasklink) : ?>
        <tr>
            <td>Task #:</td>
            <td><?= $note->salestasklink; ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td colspan="2"><b>Notes</b><br><?= $note->textbody; ?></td>
    </tr>
</table>
