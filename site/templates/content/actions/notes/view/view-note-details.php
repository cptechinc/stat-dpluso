<table class="table table-bordered table-striped">
    <tr>
        <td>Note ID:</td> <td><?= $note->id; ?></td>
    </tr>
    <tr>
        <td>Note Type</td> <td><?= $note->getactionsubtypedescription(); ?></td>
    </tr>
    <tr>
        <td>Written on:</td> <td><?php echo date('m/d/Y g:i A', strtotime($note->datecreated)); ?></td>
    </tr>
    <tr>
        <td>Written by:</td> <td><?php echo $note->createdby; ?></td>
    </tr>
    <tr>
        <td>Customer:</td>
        <td><?= get_customername($note->customerlink); ?> &nbsp;<a href="<?php echo $note->generatecustomerurl(); ?>"><i class="glyphicon glyphicon-share"></i> Go to Customer Page</a></td>
    </tr>
    <?php if ($note->hasshiptolink) : ?>
        <tr>
            <td>Ship-to:</td>
            <td><?= get_shiptoname($note->customerlink, $note->shiptolink, false); ?> &nbsp;<a href="<?php echo $note->generateshiptourl(); ?>"><i class="glyphicon glyphicon-share"></i> Go to Ship-to Page</a></td>
        </tr>
    <?php endif; ?>
    <?php if ($note->hascontactlink) : ?>
        <tr>
            <td>Contact:</td>
            <td><?php echo $note->contactlink; ?> &nbsp;<a href="<?php echo $note->generatecontacturl(); ?>"><i class="glyphicon glyphicon-share"></i> Go to Contact Page</a></td>
        </tr>
    <?php endif; ?>
    <?php if ($note->hasorderlink) : ?>
        <tr>
            <td>Sales Order #:</td>
            <td><?php echo $note->salesorderlink; ?></td>
        </tr>
    <?php endif; ?>
    <?php if ($note->hasquotelink) : ?>
        <tr>
            <td>Quote #:</td>
            <td><?php echo $note->quotelink; ?></td>
        </tr>
    <?php endif; ?>
    <?php if ($note->hastasklink) : ?>
        <tr>
            <td>Task #:</td>
            <td><?php echo $note->salestasklink; ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td class="control-label">Title</td> <td><?= $note->title; ?></td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Notes</b><br>
            <div class="display-notes">
                <?php echo $note->textbody; ?>
            </div>
        </td>
    </tr>
</table>
