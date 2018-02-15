<div class="panel panel-primary not-round" id="customer-sales-panel">
    <div class="panel-heading not-round" id="customer-sale-panel-heading">
    	<a href="#salesdata-div" class="panel-link" data-parent="#tasks-panel" data-toggle="collapse" aria-expanded="true">
        	<span class="glyphicon glyphicon-book"></span> &nbsp; Top 25 customers <span class="caret"></span>
        </a>
    </div>
    <div id="salesdata-div" class="collapse" aria-expanded="true">
        <div class="table-responsive">
            <table class="table table-bordered table-condensed table-striped" id="cust-sales">
                <thead> <tr> <th>CustID</th> <th>Name</th> <th>Amount Sold</th> <th>Times Sold</th> <th>Last Sale Date</th> </tr> </thead>
                <tbody>
                    <?php $customers = get_topxsellingcustomers(session_id(), 25); ?>
                    <?php foreach ($customers as $customer) : ?>
                        <?php $cust = Customer::load($customer['custid']); ?>
                        <tr>
                            <td>
                                <a href="<?= $cust->generate_ciloadurl(); ?>" class="btn btn-primary btn-sm"><?= $customer['custid']; ?></a>
                            </td>
                            <td><?= $cust->get_name(); ?></td>
                            <td class="text-right">$ <?= $page->stringerbell->format_money($customer['amountsold']); ?></td>
                            <td class="text-right"><?= $customer['timesold']; ?></td> 
                            <td class="text-right"><?= DplusDateTime::formatdate($customer['lastsaledate']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
