<?php 
    $salespersonjson = json_decode(file_get_contents($config->companyfiles."json/salespersontbl.json"), true);
    $salespersoncodes = array_keys($salespersonjson['data']);
?>
<div class="row">
    <div class="col-sm-3 form-group">
        <label class="control-label">From Date </label>
        <?php $name = 'date-from'; $value = ""; ?>
        <?php include $config->paths->content."common/date-picker.php"; ?>
    </div>
    <div class="col-sm-3 form-group">
        <label class="control-label">Through Date </label>
        <?php $name = 'date-through'; $value = ""; ?>
        <?php include $config->paths->content."common/date-picker.php"; ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-4 form-group">
        <label for="">Change Action Type</label>
        <select class="form-control input-sm">
            <?php $types = $pages->get('/activity/')->children(); ?>
            <?php foreach ($types as $type) : ?>
                    <option value="<?= $type->name; ?>"><?= ucfirst($type->name); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-sm-4 form-group">
        <?php if (!$user->hasrestrictions) : ?>
            <label>Change User</label>
            <select class="form-control input-sm">
                <?php foreach ($salespersoncodes as $salespersoncode) : ?>
                    
                    <option value="<?= $salespersonjson['data'][$salespersoncode]['splogin']; ?>"><?= $salespersoncode.' - '.$salespersonjson['data'][$salespersoncode]['spname']; ?></option>
                    
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
    </div>
</div>
