<?php 
    $topshiptos = get_topxsellingshiptos(session_id(), $customer->custid, 25);
    $data = array();
    foreach ($topshiptos as $shipto) {
        $data[] = array(
            'label' => get_shiptoname($customer->custid, $shipto['shiptoid']),
            'value' => $shipto['amountsold']
        ); 
    }
?>
<div class="panel panel-primary not-round" id="shipto-sales-panel">
    <div class="panel-heading not-round">
        <a href="#shipto-sales-div" class="panel-link" data-parent="#contacts-panel" data-toggle="collapse" ><i class="fa fa-pie-chart" aria-hidden="true"></i> &nbsp; Shipto Sales Data <span class="caret"></span></a>
    </div>
    <div id="shipto-sales-div">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-5">
                    <div id="shipto-sales-graph"></div>
                </div>
                <div class="col-sm-7">
            		<table class="table table-striped table-bordered table-condensed table-excel" id="legend-table">
            			<thead>
            				<tr>
            					<th>Color</th> <th>Shiptoid</th> <th>Name</th> <th>Times Sold</th> <th>Amount</th> <th>Last Sale Date</th>
            				</tr>
            			</thead>
                        <tbody>
                            <?php foreach ($topshiptos as $shipto) : ?>
                                <tr>
                                    <td id="<?= $shipto['shiptoid'].'-shipto'; ?>"></td>
                                    <td><?= $shipto['shiptoid']; ?></td>
                                    <td><?= get_shiptoname($customer->custid, $shipto['shiptoid']); ?></td>
                                    <td class="text-right"><?= $shipto['timesold']; ?></td>
                                    <td class="text-right"><?= $shipto['amountsold']; ?></td>
                                    <td class="text-right"><?= $shipto['lastsaledate'] == 0 ? '' : DplusDateTime::formatdate($shipto['lastsaledate']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
            		</table>
            	</div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        var pie = Morris.Donut({
            element: 'shipto-sales-graph',
            data: <?= json_encode($data); ?>,
        });
        pie.options.data.forEach(function(label, i) {
            $('#legend-table').find('#'+label['shiptoid']+'-shipto').css('backgroundColor', pie.options.colors[i]);
        });
    });
</script>
