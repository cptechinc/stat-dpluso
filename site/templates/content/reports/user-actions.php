<?php 
    $actionsreport = new UserActionsReport($page->fullURL, array('assignedto' => $user->loginid));
    $salespersonjson = json_decode(file_get_contents($config->companyfiles."json/salespersontbl.json"), true);
	$salespersoncodes = array_keys($salespersonjson['data']);
    $donutarray = $actionsreport->generate_actionsbytypearray();
    $completionarray = $actionsreport->generate_completionarray();
?>
<div class="row">
    <div class="col-sm-3">
        <label for="">Change Action Type</label>
        <select class="form-control input-sm change-action-type">
            <?php $types = $pages->get('/activity/')->children(); ?>
            <?php foreach ($types as $type) : ?>
                <option value="<?= $type->name; ?>"><?= ucfirst($type->name); ?></option>
            <?php endforeach; ?>
        </select>
        
        <h3>Filter</h3>
        <div class="form-group">
            <label class="control-label">From Date </label>
            <?php $name = 'date-from'; $value = ""; ?>
            <?php include $config->paths->content."common/date-picker.php"; ?>
        </div>
        <div class="form-group">
            <label class="control-label">Through Date </label>
            <?php $name = 'date-through'; $value = ""; ?>
            <?php include $config->paths->content."common/date-picker.php"; ?>
        </div>
        
        <?php if (!$user->hasrestrictions) : ?>
            <label>Change User</label>
            <select class="form-control input-sm change-actions-user">
                <option value="n/a">You</option>
                <?php foreach ($salespersoncodes as $salespersoncode) : ?>
                    <option value="<?= $salespersonjson['data'][$salespersoncode]['splogin']; ?>"><?= $salespersoncode.' - '.$salespersonjson['data'][$salespersoncode]['spname']; ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
    </div>
    <div class="col-sm-9">
        <div class="row">
            <div class="col-sm-6">
                <h3>Action Type</h3>
                <div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#chart" aria-controls="chart" role="tab" data-toggle="tab">Chart</a></li>
                        <li role="presentation"><a href="#table" aria-controls="table" role="tab" data-toggle="tab">Table</a></li>
                    </ul>
                    
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="chart">
                            <br>
                            <h4>Total Tasks: <?= $actionsreport->count_actions(); ?></h4>
                            <div id="donut-display"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="table">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr> <th>Action Type</th> <th>Count</th> </tr>
                                </thead>
                                <?php foreach ($donutarray as $action) : ?>
                                    <tr> <td><?= $action['label']; ?></td> <td><?= $action['value']; ?></td> </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <h3>Task Completion Data</h3>
                <div id="task-completion-chart"></div>
            </div>
        </div>
    </div>
</div>



<script>
    $(function() {
        var data = JSON.parse('<?= json_encode($donutarray); ?>');
        var taskdata = JSON.parse('<?= json_encode($completionarray); ?>');
        new Morris.Donut({
            element: 'donut-display',
            data: data
        });
        
        new Morris.Donut({
            element: 'task-completion-chart',
            data: taskdata
        });
    });

</script>
