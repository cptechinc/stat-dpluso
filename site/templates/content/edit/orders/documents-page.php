<div class="docs">
    <h2>Documents</h2>
    <table class="table table-striped">
        <thead>
            <tr> <th>ItemID</th> <th>Document</th> <th>Date</th> <th>Time</th> </tr>
        </thead>
        <tbody>
        	<?php $documents = getallorderdocs(session_id(), $ordn, false); ?>
            <?php foreach ($documents as $document) : ?>
            	<td><?php echo $document['itemnbr']; ?></td>
            	<td><a href="<?= $config->documentstorage.$document['pathname']; ?>" target="_blank"><?php echo $document['title']; ?></a></td>
            	<td><?php echo $document['createdate']; ?></td>
            	<td><?= DplusDateTime::formatdplustime($document['time']); ?></td>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
