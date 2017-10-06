 <tr class="detail item-header">
    <th colspan="2" class="text-center">Item ID/Cust Item ID</th> <th colspan="2">Description</th> <th class="text-right">Ordered</th>
    <th class="text-right" width="100">Price</th>
    <th class="text-right">Back Order</th> <th class="text-right">Shipped</th>  <th>Notes</th><th>Reorder</th> <th>Documents</th>
</tr>
<?php  $details = get_order_details(session_id(), $order->orderno, false); ?>
<?php foreach ($details as $detail) : ?>
    <?php
		$qtyo = $detail['qtyordered'] + 0;
		$qtys = $detail['qtyshipped'] + 0;
		$bo = $detail['qtybackord'] + 0;
		$detailnoteurl = $config->pages->notes.'redir/?action=get-order-notes&ordn='.$order->orderno.'&linenbr='.$detail['linenbr'].'&modal=modal';


		if ($detail['havenote'] != 'Y') {
			$detnoteicon = '<a class="h3 load-notes text-muted" href="'.$detailnoteurl.'" data-modal="#ajax-modal"><i class="material-icons" title="View order notes">&#xE0B9;</i></a>';
		} else {
			$detnoteicon = '<a class="h3 load-notes" href="'.$detailnoteurl.'" data-modal="#ajax-modal"> <i class="material-icons" title="View order notes">&#xE0B9;</i></a>';
		}

		if ($detail['haveitemdoc'] == 'Y') {
			$itemdocLink = '<a class="btn btn-primary" href="redir/cust-redir.php?action=get-order-ocuments&itemdoc='.$detail['itemid'].'&ordn='.$order->orderno.$linkaddon."&custID=".$cust.'">
				<span class="sr-only">View Documents</span>
				<span class="glyphicon glyphicon-folder-open" title="Click to view Documents"></span>
		</a>';
		} else {
			$itemdocLink = '<a class="text-muted h4">
								<span class="sr-only">There are no documents for for line number '.$detail['linenbr'].'</span>
								<span class="glyphicon glyphicon-folder-open" title="There are no documents for line number '.$detail['linenbr'].'"></span>
							</a>';

		}
		if ($detail['itemid'] != "") {
			$thispage = $config->pages->customer."$custID/";
			if (strlen($shipID) > 0) {
				$thispage .= "shipto-$shipID/";

			}
			$reorder = '<form method="post" action="'.$config->pages->cart.'redir/" id="'.$order->orderno.'-'.$detail['itemid'].'-form" class="item-reorder">
							<input type="hidden" name="itemID" value="'.$detail['itemid'].'"> <input type="hidden" name="ordn" value="'.$order->orderno.'">
							<input type="hidden" name="qty" class="qty" value="'.$qtyo .'"> <input type="hidden" name="page" value="'.$thispage.'">
							<input type="hidden" name="action" value="reorder">
							<input type="hidden" name="desc" class="desc" value="'.$detail['desc1'].'">
							<input type="hidden" name="custID" value="'.$custID.'">
							<button type="submit" class="btn btn-primary btn-xs">
								<span class="glyphicon glyphicon-shopping-cart"</span>
							</button>
						</form>';
		} else { $reorder = ""; }



	?>
    <tr class="detail">
        <td colspan="2" class="text-center">
            <a href="<?= $config->pages->ajax."load/edit-detail/order/?ordn=".$detail['orderno']."&line=".$detail['linenbr']; ?>" class="update-line" data-kit="<?= $detail['kititemflag']; ?>" data-itemid="<?= $detail['itemid']; ?>" data-custid="<?= $order->custid; ?>">
                <?php echo $detail['itemid']; ?>
            </a>
        </td>
        <td colspan="2">
            <?php if (strlen($detail['vendoritemid'])) { echo ' '.$detail['vendoritemid']."<br>";} ?>
            <?php echo $detail['desc1']. ' ' . $detail['desc2'] ; ?>
        </td>
        <td class="text-right"> <?php echo $qtyo ; ?> </td>
        <td class="text-right">$ <?php echo formatmoney($detail['price']); ?></td><td class="text-right"> <?php echo $bo; ?></td>  <td class="text-right"><?php echo $qtys; ?></td>
        <td><?php echo $detnoteicon; ?></td> <td><?php echo $reorder; ?></td> <td><div><?php echo $itemdocLink; ?></div></td>
    </tr>
    <?php if ($input->get->text('item-document')) : ?>
    	<?php if ($input->get->text('item-document') == $detail['itemid']) : ?>
        	<?php $itemdocs = get_item_docs(session_id(), $order->orderno, $detail['itemid'], false); ?>
            <?php foreach ($itemdocs->fetchAll() as $itemdoc) : ?>
            	<tr class="docs">
                    <td colspan="2"></td>
                    <td>
                        <b><a href="<?php echo $config->pathtofiles.$itemdoc['pathname'];; ?>" title="Click to View Document" target="_blank" ><?php echo $itemdoc['title']; ?></a></b>
                    </td>
                    <td align="right"><?php echo $itemdoc['createdate']; ?></td>
                    <td align="right"><?= DplusDateTime::formatdplustime($itemdoc['createtime']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
<?php endforeach; ?>
