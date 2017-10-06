<?php $notes = getlinkednotes($notepanel->getarraylinks(), $config->showonpage, $input->pageNum, false); ?>

<table class="table table-bordered table-striped table-condensed">
	<thead>
    	<tr> <th>Date</th> <th>Writer</th> <th>Note</th> <th>View</th> </tr>
    </thead>
    <tbody>
    	<?php foreach ($notes as $note) : ?>
            <tr>
                <td><?= date('m/d/Y g:i A', strtotime($note['datecreated'])); ?></td>
                <td><?= $note['writtenby']; ?> </td>
                <td><?= $note['textbody']; ?></td>
                <td>
                    <a href="<?= $notepanel->getloadnotelink($note['id']) ?>" class="btn btn-primary btn-xs load-crm-note" data-modal="<?= $notepanel->modal; ?>" role="button" title="View Note">
                        <i class="material-icons md-18">&#xE02F;</i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
