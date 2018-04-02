<?php $trackings = get_ordertracking(session_id(), $order->orderno); ?>
<?php foreach ($trackings as $tracking) : ?>
	<?php $carrier = $tracking['servtype']; $link = ""; $link = returntracklink($tracking['servtype'], $tracking['tracknbr'], $order->orderno); ?>
	<tr class="detail tracking">
		<td colspan="3"><b>Shipped:</b>  <?= $carrier; ?></td>
		<td colspan="2"><b>Tracking No.:</b>
				<?php if ($link == "#$order->orderno" ): ?>
					 <?= $tracking['tracknbr']; ?>
				<?php else : ?>
					 <b><a href="<?= $link; ?>"target="_blank" title="Click To Track"><?= $tracking['tracknbr']; ?></a></b>
				<?php endif; ?>
		</td>
		<td></td>
		<td colspan="2"><b>Weight: </b><?= $tracking['weight']; ?></td> 
		<td colspan="2"><b>Ship Date: </b><?= $tracking['shipdate']; ?> </td>
		<td></td>
	</tr>
<?php endforeach; ?>
