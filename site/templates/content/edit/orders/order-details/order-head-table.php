<div class="table-responsive">
    <table class="table table-condensed" id="orders-table">
    	<?php $order = get_orderhead(session_id(), $ordn, false); ?>
        <?php
			$shiptoaddress = $order['shipaddress']."<br>";
			if ($order['shipaddress2'] != '' ) { $shiptoaddress .= $order['shipaddress2']."<br>"; }
			$shiptoaddress .= $order['shipcity'].", ". $order['shipstate'].' ' . $order['shipzip'];
			$shiptopopover = 'data-toggle="popover" role="button" data-placement="top" data-trigger="focus" data-html="true" title="Ship-To Address"';
            $ordersredirect = $page->fullURL;
            $ordersredirect->setPath($config->pages->customer."redir/");

			$docsurl = $ordersredirect->setData(array('action' => 'get-order-documents', 'ordn' => $ordn, 'linenbr' => '0', 'page' => $input->pageNum));
			if ($order['hasdocuments'] == 'Y') {
				$documentlink = '
							<a class="load-sales-docs" href="'.$docsurl.'" '.$ajax->data.'> <span class="sr-only">View Documents</span>
								<i class="material-icons md-36" title="Click to View Documents">&#xE873;</i>
							</a>';
			} else {
				$documentlink = '<a class="text-muted"> <span class="sr-only">View Documents</span>
									<i class="material-icons md-36" title="There are no documents for this order">&#xE873;</i>
								</a>';
			}

            $trackhref = $ordersredirect->setData(array('action' => 'get-order-tracking', 'ordn' => $ordn, 'page' => $input->pageNum));
			if ($order['hastracking'] == 'Y') {
				$tracklink = '
							<a href="'.$trackhref.'" class="h3 load-detail" '.$ajax->data.'>
									<span class="sr-only">View Tracking</span><span class="glyphicon glyphicon-plane hover" title="Click to view Tracking"></span>
								</a>';
			} else {
				$tracklink = '<a class="text-muted h3">
									<span class="sr-only">View Tracking</span>
									<span class="glyphicon glyphicon-plane hover" title="There are no tracking numbers for this order"></span>
							  </a>';

			}
		
			$noteurl = $config->pages->notes.'redir/?action=get-order-notes&ordn='.$ordn.'&linenbr=0&modal=modal';
			if ($order['hasnotes'] != 'Y') {
				$headnoteicon = '<a class="load-notes text-muted" href="'.$noteurl.'" data-modal="#notes-modal"><i class="material-icons md-36" title="View order notes">&#xE0B9;</i></a>';
			} else {
				$headnoteicon = '<a class="load-notes" href="'.$noteurl.'" data-modal="#notes-modal"> <i class="material-icons md-36" title="View order notes">&#xE0B9;</i></a>';
			}


		?>
    	<tr class="selected">
            <td> <?php echo $order['orderno'];?> </td>
            <td><?php echo $order['custpo']; ?></td>
            <td>
                <a href="<?php echo $customer_ship; ?>"><?php echo $shipID = $order['shiptoid']; ?></a>
                <a tabindex="0" class="btn btn-default bordered btn-sm" <?php echo $shiptopopover; ?> data-content="<?php echo $shiptoaddress; ?>"><b>?</b></a>
            </td>
            <td align="right">$ <?php echo formatmoney($order['ordertotal']); ?></td> <td align="right" ><?php echo $order['orderdate']; ?></td>
            <td align="right"><?php echo $order['status']; ?></td>
            <td colspan="4">
                <span class="col-xs-3"><?php echo $documentlink; ?></span>
                <span class="col-xs-3"><?php echo $tracklink; ?></span>
                <span class="col-xs-3"><?php echo $headnoteicon; ?> </span>
            </td>
        </tr>
    </table>
</div>
