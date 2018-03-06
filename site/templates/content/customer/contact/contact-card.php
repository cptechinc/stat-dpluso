<?php
    $editpage = new \Purl\Url($page->fullURL->getUrl());
    $editpage->path->remove(6);
    $editpage->path->add('edit');
?>

<div class="panel panel-primary not-round">
    <div class="panel-heading not-round">
        <h3 class="panel-title"><?php echo $contact->contact; ?></h3>
     </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-8 ">
                <table class="table table-user-information">
                    <tbody>
                        <tr>
                            <td>CustID:</td>
                            <td>
                                <a href="<?= $contact->generate_customerurl(); ?>" target="_blank">
                                    <?= $contact->custid. ' - '. get_customername($contact->custid, false); ?> <i class="glyphicon glyphicon-share" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        <?php if ($contact->has_shipto()) : ?>
                            <tr> 
                                <td>Ship-to ID:</td> 
                                <td><a href="<?= $contact->generate_shiptourl(); ?>" target="_blank"><?= $contact->shiptoid; ?> <i class="glyphicon glyphicon-share" aria-hidden="true"></i></a></td>
                            </tr>
                        <?php endif; ?>
                        <tr> <td>Title:</td><td><?php //echo $contact->title; ?></td> </tr>
                        <tr> <td>Email:</td> <td><a href="mailto:<?php echo $contact->email; ?>"><?php echo $contact->email; ?></a></td></tr>
                        <tr>
                            <td>Office Phone:</td>
                            <td>
                                <a href="tel:<?= $contact->phone; ?>"><?= $page->stringerbell->format_phone($contact->phone); ?></a><b> &nbsp;
                                <?php if ($contact->has_extension()) { echo 'Ext. ' . $contact->extension;} ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>Cell Phone:</td> <td><a href="tel:<?= $contact->cellphone; ?>"> <?= $page->stringerbell->format_phone($contact->cellphone); ?></a></td>
                        </tr>
                        <!--<tr> <td>Fax:</td> <td><?php //echo $contact->faxnumber; ?></td> </tr>     -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <a href="<?= $editpage->getUrl(); ?>" class="btn btn-warning btn-sm"> <i class="fa fa-pencil" aria-hidden="true"></i> Edit Contact</a>
    </div>
</div>
