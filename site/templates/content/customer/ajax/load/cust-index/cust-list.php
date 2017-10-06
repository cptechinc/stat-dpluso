
<?php
    include $config->paths->content."customer/ajax/load/index/search-index-form.php";
    $custID = '';
    $custlink = $config->pages->customer."redir/?action=ci-select";
    if ($input->get->q) {
        $custresults = search_custindexpaged($user->loginid, $config->showonpage, $input->pageNum, $user->hascontactrestrictions, $input->get->text('q'),  false);
        //$custresults = search_custindex_keyword_paged($user->loginid, $config->showonpage, $input->pageNum, $user->hascontactrestrictions, $input->get->text('q'),  false);
        $resultscount = count_searchcustindex($user->loginid, $user->hascontactrestrictions, $input->get->text('q'), false);
    }

?>


<div class="list-group" id="cust-results">
    <?php if ($input->get->q) : ?>
        <table id="cust-index" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="100">CustID</th> <th>Customer Name</th> <th>Ship-To</th> <th>Location</th><th width="100">Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultscount > 0) : ?>
                    <?php foreach ($custresults as $cust) : ?>
                       <tr>
                           <td>

   							<a href="<?= $cust->generateciloadurl(); ?>">
   								<?= highlight($cust->custid, $input->get->text('q'),'<span class="highlight">{ele}</span>');?>
   							</a> &nbsp; <span class="glyphicon glyphicon-share"></span>
   						</td>
                           <td><?= highlight($cust->name, $input->get->q,'<span class="highlight">{ele}</span>'); ?></td>
   						<td><?= highlight($cust->shiptoid, $input->get->q,'<span class="highlight">{ele}</span>'); ?></td>
   						<td><?= highlight($cust->generateaddress(), $input->get->q, '<span class="highlight">{ele}</span>'); ?></td>
                           <td><a href="tel:<?= $cust->cphone; ?>" title="Click To Call"><?= highlight($cust->cphone, $input->get->q,'<span class="highlight">{ele}</span>'); ?></a></td>
                       </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <td colspan="5">
                        <h4 class="list-group-item-heading">No Customer Matches your query.</h4>
                    </td>
                <?php endif; ?>
            </tbody>
        </table>

    <?php endif; ?>
</div>
