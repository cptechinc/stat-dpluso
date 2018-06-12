<form action="<?= $actionpanel->generate_refreshurl(); ?>" class="form-ajax form-inline display-inline-block" data-loadinto="<?= $actionpanel->loadinto; ?>" data-focus="<?= $actionpanel->focus; ?>">
    <input type="hidden" name="filter" value="filter">
    <input type="hidden" name="view" value="calendar">
    <?php if ($appconfig->child('name=actions')->allow_changeuserview) : ?>
        <h4 id="actions-assignedto">Assigned To</h4>
        <select name="assignedto[]" class="selectpicker show-tick form-control input-sm" aria-labelledby="#actions-assignedto" data-style="btn-default btn-sm" multiple>
            <?php foreach ($salespersoncodes as $salespersoncode) : ?>
                <?php $selected = ($actionpanel->has_filtervalue('assignedto', $salespersonjson['data'][$salespersoncode]['splogin'])) ? 'selected' : ''; ?>
                <?php if (!empty($salespersonjson['data'][$salespersoncode]['splogin'])) : ?>
                    <option value="<?= $salespersonjson['data'][$salespersoncode]['splogin']; ?>" <?= $selected; ?>><?= $salespersoncode.' - '.$salespersonjson['data'][$salespersoncode]['spname']; ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>
    <button type="submit" class="btn btn-sm btn-success">Go</button>
</form>
