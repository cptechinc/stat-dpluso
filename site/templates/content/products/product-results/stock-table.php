<?php $warehouses = getitemavailability(session_id(), $item['itemid'], false); ?>
<table class="table-condensed table-bordered table">
    <thead>
        <tr><th>Whse</th> <th>Name</th> <th>Available</th> <th>On-Order</th> <th>ETA</th></tr>
    </thead>
    <tbody>
        <?php foreach ($warehouses as $whse) : ?>
            <tr class="warehouse-tr <?php echo $whse['whsecd']; ?>-row">
                <td>
                    <button type="button" class="btn btn-primary btn-xs" onclick="chooseitemwhse('<?php echo cleanforjs($item['itemid']); ?>', '<?php echo $whse['whsecd']; ?>')">
                        <?php echo $whse['whsecd']; ?>
                    </button>
                </td>
                <td><?php echo $whse['whsename']; ?></td>
                <td><?php echo $whse['itemavail']; ?></td>
                <td><?php echo $whse['itemonord']; ?></td>
                <td><?php echo $whse['itemetadt']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
