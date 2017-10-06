<div class="row form-group">
    <div class="col-sm-3 col-xs-5">
        <label for="shownotes">Show Notes</label>
        <select name="" class="form-control" id="shownotes" data-link="<?= $shownoteslink; ?>" data-ajax="<?= $pageajax; ?>">
            <?php foreach ($config->yesnoarray as $key => $value) : ?>
                <?php if ($value == $input->get->text('shownotes')) : ?>
                    <option value="<?= $value; ?>" selected><?= $key; ?></option>
                <?php else : ?>
                    <option value="<?= $value; ?>"><?= $key; ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>
</div>
