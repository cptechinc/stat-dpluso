<table class="table table-striped table-bordered table-condensed" id="quotes-table">
	<thead>
       <?php include $config->paths->content.'customer/cust-page/quotes/quotes-thead-rows.php'; ?>
    </thead>
	<tbody>
		<?php if ($input->get->qnbr) : ?>
			<?php if ($quotecount == 0 && $input->get->text('qnbr') == '') : ?>
				<tr> <td colspan="6" class="text-center">No Quotes found! Try using a date range to find the order(s) you are looking for.</td> </tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php $quotes = get_cust_quotes(session_id(), $custID, $config->showonpage, $input->pageNum(), false); ?>
		<?php foreach ($quotes as $quote) : ?>
			<?php
				$qtnbr = $quote['quotnbr'];
				if ($qtnbr == $input->get->text('qnbr')) {
					$rowclass = 'selected';
					$quotelink = new \Purl\Url($page->httpUrl);
					$quotelink->path = $ajax->path;
					$quotelink->query->setData(array('qnbr' => false, 'show' => false, 'orderby' => false));
					$quotejsdata = $ajax->data;
				} else {
					$rowclass = ''; $title = "View Quote Details";
					if (isset($input->get->orderby)) { $sorting = 	$orderby."-".$sortrule; } else { $sorting = false; } // just to set up the orderlink querystring replace
					$quotelink = new \Purl\Url($page->httpUrl);
					$quotelink->path = $config->pages->quotes."redir/";
					$quotelink->query->setData(array('action' => 'load-quote-details', 'qnbr' => $qtnbr, 'custID' => urlencode($custID), 'page' => $input->pageNum, 'orderby' => $sorting));
					$quotejsdata = 'data-loadinto="'.$ajax->loadinto.'"  data-focus="#'.$qtnbr.'"';
				}

				$noteurl = $config->pages->notes.'redir/?action=get-quote-notes&qnbr='.$qtnbr.'&linenbr=0&modal=modal'; //TODO
				if ($quote['notes'] != 'Y') {
					$headnoteicon = '<a class="load-notes text-muted" href="'.$noteurl.'" data-modal="#ajax-modal"><i class="material-icons md-36" title="View quote notes">&#xE0B9;</i></a>';
				} else {
					$headnoteicon = '<a class="load-notes" href="'.$noteurl.'" data-modal="#ajax-modal"> <i class="material-icons md-36" title="View quote notes">&#xE0B9;</i></a>';
				}

				if ($user->hasquotelocked) {
					if ($qtnbr == $user->lockedquote) {
						$editlink = $config->pages->quotes."redir/?action=edit-quote&qnbr=".$qtnbr."&custID=".$quote['custid']."&lock=lock";
						$editicon = 'glyphicon glyphicon-wrench';
						$atitle = "Continue editing this quote";
					} else {
						$editlink = $config->pages->quotes."redir/?action=edit-quote&qnbr=".$qtnbr."&custID=".$quote['custid'];
						$editicon = 'glyphicon glyphicon-eye-open';
						$atitle = "Open Quote in Read Only Mode";
					}
				} else {
					$editlink = $config->pages->quotes."redir/?action=edit-quote&qnbr=".$qtnbr."&custID=".$quote['custid']."&lock=lock";
					$editicon = 'glyphicon glyphicon-pencil';
					$atitle = "Edit Quote";
				}

				$editicon = "<span class='h3'><a href='".$editlink."' class='edit-quote' title='".$atitle."'><span class='".$editicon."'></span></a></span>";

			?>
			<tr class="<?= $rowclass; ?>" id="<?= $qtnbr; ?>">
				<?php if ($qtnbr == $input->get->text('qnbr')) : ?>
                	 <td class="text-center">
                     	<a href="<?php echo $quotelink; ?>" class="btn btn-sm btn-primary load-link" <?php echo $quotejsdata; ?>>-</a>
                     </td>
                <?php else : ?>
                	 <td class="text-center">
                     	<a href="<?php echo $quotelink; ?>" class="btn btn-sm btn-primary generate-load-link" <?php echo $quotejsdata; ?>>+</a>
                     </td>
                <?php endif; ?>

				<td><?php echo $quote['quotnbr']; ?></td>
				<td><?php echo $quote['shiptoid']; ?></td>
				<td><?php echo $quote['quotdate']; ?></td>
				<td><?php echo $quote['revdate']; ?></td>
				<td><?php echo $quote['expdate']; ?></td>
				<td><?php echo $headnoteicon; ?></td>
				<td><?php echo $editicon; ?></td>
			</tr>

			<?php if ($qtnbr == $input->get->text('qnbr')) : ?>
				<?php if ($quote['error'] == 'Y') : ?>
	                <tr class="detail bg-danger" >
	                    <td></td>
	                    <td colspan="3"><b>Error: </b><?php echo $quote['errormsg']; ?></td>
	                    <td></td>
	                    <td></td>
						<td></td>
						<td></td>
	                </tr>
	            <?php endif; ?>
				<?php include $config->paths->content."customer/cust-page/quotes/quote-detail-rows.php"; ?>
				<?php include $config->paths->content."customer/cust-page/quotes/quote-totals.php"; ?>
				<tr class="detail last-detail">
					<td></td>
					<td>
						<a href="<?= $config->pages->quotes."redir/?action=get-quote-details-print&qnbr=".$quote['quotnbr']; ?>" target="_blank">
							<span class="h3"><i class="glyphicon glyphicon-print" aria-hidden="true"></i></span>  View Printable Quote
						</a>
					</td>
					<td>
					    <a href="<?= $config->pages->orderquote.'?qnbr='.$qtnbr; ?>" class="btn btn-sm btn-default">
                            <i class="fa fa-paper-plane-o" aria-hidden="true"></i> Send To Order
                        </a>
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><a href="<?php echo $quotelink; ?>" class="btn btn-sm btn-danger load-link" <?php echo $quotejsdata; ?>>Close</a></td>
				</tr>
			<?php endif; ?>
		<?php endforeach; ?>
	</tbody>
</table>
