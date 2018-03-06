<table class="table table-bordered table-striped">
    <tr>
        <td>Action ID:</td> <td><?= $action->id; ?></td>
    </tr>
    <tr>
        <td>Action Type:</td> <td><?= $action->generate_actionsubtypedescription();; ?></td>
    </tr>
    <tr>
        <td>Written on:</td> <td><?= date('m/d/Y g:i A', strtotime($action->datecreated)); ?></td>
    </tr>
    <tr>
        <td>Written by:</td> <td><?= $action->createdby; ?></td>
    </tr>
    <tr>
        <td>Completed:</td> <td><?= date('m/d/Y g:i A', strtotime($action->datecompleted));  ?></td>
    </tr>
    <?php if ($action->has_customerlink()) : ?>
        <tr>
            <td>Customer:</td>
            <td><?= get_customername($action->customerlink); ?> &nbsp;<a href="<?= $actiondisplay->generate_ciloadurl($action); ?>" target="_blank"><i class="glyphicon glyphicon-share"></i> Go to Customer Page</a></td>
        </tr>
    <?php endif; ?>
    <?php if ($action->has_shiptolink()) : ?>
        <tr>
            <td>Ship-to:</td>
            <td><?= get_shiptoname($action->customerlink, $action->shiptolink, false); ?> <a href="<?= $actiondisplay->generate_ciloadurl($action); ?>" target="_blank"><i class="glyphicon glyphicon-share"></i> Go to Ship-to Page</a></td>
        </tr>
    <?php endif; ?>
    <?php if ($action->has_contactlink()) : ?>
        <tr>
            <td>Action Contact:</td>
            <td><?= $action->contactlink; ?> <a href="<?= $actiondisplay->generate_contacturl($action); ?>" target="_blank"><i class="glyphicon glyphicon-share"></i> Go to Contact Page</a></td>
        </tr>
    <?php else : ?>
        <tr>
            <td class="text-center h5" colspan="2">
                Who to Contact
            </td>
        </tr>
        <tr>
            <td>Contact: </td>
            <td><?= $contactinfo->contact; ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td>Phone:</td>
        <td>
            <a href="tel:<?= $contactinfo->phone; ?>"><?= $contactinfo->phone; ?></a> &nbsp; <?php if ($contactinfo->has_extension()) {echo ' Ext. '.$contactinfo->extension;} ?>
        </td>
    </tr>
    <?php if ($contactinfo->cellphone != '') : ?>
        <tr>
            <td>Cell Phone:</td>
            <td>
                <a href="tel:<?= $contactinfo->cellphone; ?>"><?= $contactinfo->cellphone; ?></a>
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <td>Email:</td>
        <td><a href="mailto:<?= $contactinfo->email; ?>"><?= $contactinfo->email; ?></a></td>
    </tr>
    <?php if ($action->has_salesorderlink()) : ?>
        <tr>
            <td>Sales Order #:</td>
            <td><?= $action->salesorderlink; ?></td>
        </tr>
    <?php endif; ?>
    <?php if ($action->has_quotelink()) : ?>
        <tr>
            <td>Quote #:</td>
            <td><?= $action->quotelink; ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td class="control-label">Title</td> <td><?= $action->title; ?></td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Notes</b><br>
            <div class"view-notes">
                <?= $action->textbody; ?>
            </div>
        </td>
    </tr>
</table>
