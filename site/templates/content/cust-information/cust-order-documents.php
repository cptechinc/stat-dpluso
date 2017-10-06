<table class="table table-striped">
	<thead>
		<tr> <th>Document</th> <th>Date</th> <th>Time</th> </tr>
	</thead>
	<tbody>
		<?php $documents = get_order_docs(session_id(), $ordn, false); ?>
		<?php foreach ($documents as $document) : ?>
			<td><a href="<?php echo $config->documentstorage.$document['pathname']; ?>" target="_blank"><?php echo $document['title']; ?></a></td>
			<td><?php echo $document['createdate']; ?></td>
			<td><?= DplusDateTime::formatdplustime($document['time']); ?></td>
		<?php endforeach; ?>
	</tbody>
</table>
