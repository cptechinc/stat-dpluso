<?php 
    $q = '';
    $custresults = get_distinctcustindexpaged($user->loginid, $limit = 10, $page = 1, $user->hascontactrestrictions, $q, false)
?>

<div id="cust-results">
    <table id="cust-index" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th width="100">CustID</th> <th>Customer Name</th> <th>Ship-To</th> <th>Location</th><th width="100">Phone</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($custresults as $cust) : ?>
                <tr>
                    <td>
                        <a href="<?= $cust->generateciloadurl(); ?>">
                            <?= $cust->custid;?>
                        </a> &nbsp; <span class="glyphicon glyphicon-share"></span>
                    </td>
                    <td><?= $cust->name; ?></td>
                    <td><?= $cust->shiptoid; ?></td>
                    <td><?= $cust->generateaddress(); ?></td>
                    <td><a href="tel:<?= $cust->cphone; ?>" title="Click To Call"><?= $cust->cphone; ?></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
