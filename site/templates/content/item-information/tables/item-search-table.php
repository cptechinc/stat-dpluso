<?php 
    $items = search_itm($q, false, $custID, $config->showonpage, $input->pageNum, false); 
    $itemlink = $config->pages->products."redir/?action=ii-select";
?>
<div class="table-responsive" id="item-results">
	<table id="item-search-table" class="table table-striped table-bordered">
		<thead>
			<tr>
            	<th width="125">Image</th> <th width="250">Item ID</th> <th>Description</th> 
            </tr>
		</thead>
		<tbody>
            <?php foreach ($items as $item) : ?>
    			<tr>
                    <td>
                        <a href="<?= $itemlink."&itemID=".urlencode($item['itemid']); ?>">
                            <img class="img-responsive" src="<?php echo $config->imagedirectory.$item['image']; ?>" alt="">
                        </a>
                    </td>
    				<td>
                        <a href="<?= $itemlink."&itemID=".urlencode($item['itemid']); ?>">
                            <?php echo $item['itemid']; ?>
    				    </a>
                    </td>
    				<td><?php echo $item['desc1']; ?></td>
    			</tr>
            <?php endforeach; ?>
		</tbody>
	</table>
</div>
