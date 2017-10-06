<div class="panel panel-primary not-round" id="customer-sales-panel">
    <div class="panel-heading not-round" id="customer-sale-panel-heading">
    	<a href="#salesdata-div" class="panel-link" data-parent="#tasks-panel" data-toggle="collapse" aria-expanded="true">
        	<span class="glyphicon glyphicon-book"></span> &nbsp; Top 25 customers <span class="caret"></span>
        </a>
    </div>
    <div id="salesdata-div" class="collapse" aria-expanded="true">
        <div>
            <table class="table table-bordered table-condensed table-striped small" id="cust-sales">
                <thead> <tr> <th>CustID</th> <th>Name</th> <th>Amount Sold</th> <th>Times Sold</th> <th>Last Sale Date</th> </tr> </thead>
                <tbody>
                    <?php $customers = get_topxsellingcustomers($user->loginid, 25, $user->hascontactrestrictions, false); ?>
                    <?php foreach ($customers as $customer) : ?>
                        <tr>
                            <td>
                                <a href="<?php echo $config->pages->customer.'redir/?action=load-customer&custID='.$customer['custid']; ?>" class="btn btn-primary btn-sm"><?php echo $customer['custid']; ?></a>
                            </td>
                            <td><?php echo $customer['name']; ?></td> <td class="text-right">$ <?= $customer['amountsold']; ?></td>
                            <td class="text-right"><?php echo $customer['timesold']; ?></td> <td><?= DplusDateTime::formatdate($customer['lastsaledate']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>
